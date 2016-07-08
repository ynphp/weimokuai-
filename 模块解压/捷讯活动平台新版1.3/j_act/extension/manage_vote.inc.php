<?php
include(MODULE_ROOT.'/inc/redisDB.php');
global $_W,$_GPC;
$operation = $_GPC['op']?$_GPC['op']:'display';
$aid=intval($_GPC['aid']);
$reply=pdo_fetch("SELECT * FROM ".tablename('j_act_activity')." where id=:id ",array(':id'=>$aid));
if(!$reply)message("活动不存在或者已经删除");
$list_vote=pdo_fetchall("SELECT * FROM ".tablename('j_act_voteitem')." where aid=:aid order by votekey asc",array(':aid'=>$aid));
/*redis*/
$vote_Redis_reply="j_act_vote_".$_W['uniacid']."_".$aid;
$vote_Redis_item="j_act_vote_item".$_W['uniacid']."_".$aid;
$vote_Redis_list="j_act_vote_list".$_W['uniacid']."_".$aid;
$vote_Redis_voter="j_act_voter_".$_W['uniacid']."_".$aid."_*";

if($operation=="display"){
	$item=pdo_fetch("SELECT * FROM ".tablename('j_act_votegame')." where aid=:aid ",array(':aid'=>$aid));
	$parama=json_decode($reply['parama'],true);
	include $this->template('show_vote');
	exit();
}elseif($operation=='changestatus'){
	//改变状态
	$sta=$_GPC['status'];
	$status = $sta==1 ? 1:2;
	pdo_update("j_act_votegame",array("status"=>$status),array("aid"=>$aid));
	$redis=new redisDB();
	$item=pdo_fetch("SELECT * FROM ".tablename('j_act_votegame')." where aid=:aid ",array(':aid'=>$aid));
	$redis->set($vote_Redis_reply,$item);
	die(json_encode(array('success'=>true)));
	
}elseif($operation=='getvoteresult'){
	//获取投票结果
	$redis=new redisDB();
	$allVoter=$redis->keys($vote_Redis_voter);
	$list_vote=$redis->getrange($vote_Redis_list);
	$list_item=$redis->getrange($vote_Redis_item);
	$ary_item=array();
	$ary_result=array();
	foreach($list_item as $row){
		$row2=json_decode($row,true);
		$ary_item[$row2['votekey']]=$row2['id'];
	}
	$tempsql="insert into ".tablename('j_act_voteresult')."(aid, weid,from_user,nickname,avatar,vid) VALUES";
	$sql_ary=array();
	foreach($list_vote as $row2){
		$row=json_decode($row2,true);
		array_push($sql_ary,"('".$aid."','".$_W['uniacid']."','".$row['openid']."','".$row['nickname']."','".$row['avatar']."','".$ary_item[$row['votekey']]."')");
		if(!isset($ary_result[$row['votekey']]))$ary_result[$row['votekey']]=0;
		$ary_result[$row['votekey']]=$ary_result[$row['votekey']]+1;
	}
	$allcount=pdo_fetchcolumn("select count(*) from ".tablename('j_act_voteresult')." where aid=:aid",array('aid'=>$aid));
	if(count($list_vote)!=$allcount){
		pdo_delete("j_act_voteresult",array('aid'=>$aid));
		$tempsql.=implode(",",$sql_ary);
		@pdo_run($tempsql);
	}
	die(json_encode(array('success'=>true,'item'=>$ary_item,'result'=>$ary_result,'votercount'=>count($allVoter),'votecount'=>count($list_vote))));
	
}elseif($operation=='voteitem'){
	$id=intval($_GPC['id']);
	load()->func('tpl');
	$item=pdo_fetch("SELECT * FROM ".tablename('j_act_voteitem')." where id=:id ",array(':id'=>$id));
	if (checksubmit('submit')) {
		$data=array(
			"aid"=>$aid,
			"title"=>$_GPC['title'],
			"thumb"=>$_GPC['thumb'],
			"weid"=>$_W['uniacid'],
			"votekey"=>intval($_GPC['votekey']),
			"description"=>htmlspecialchars_decode($_GPC['description']),
		);
		if(!$id){
			pdo_insert("j_act_voteitem",$data);
		}else{
			unset($data['aid']);
			unset($data['weid']);
			pdo_update("j_act_voteitem",$data,array("id"=>$id));
		}
		$redis=new redisDB();
		if($redis->exists($vote_Redis_item))$redis->delete($vote_Redis_item);
		$itemlist=pdo_fetchall("SELECT * FROM ".tablename('j_act_voteitem')." where aid=:aid order by votekey asc",array(':aid'=>$aid));
		foreach($itemlist as $row){
			$redis->push($vote_Redis_item,$row);
		}
		message("修改成功",$this->createWebUrl('manage_vote',array("aid"=>$aid,'op'=>'post')),'success');
	}

}elseif($operation=='getiteminfo'){
	$id=intval($_GPC['tid']);
	$item=pdo_fetch("SELECT * FROM ".tablename('j_act_voteitem')." where id=:id ",array(':id'=>$id));
	die($item['description']);
}elseif($operation=='deletevoteitem'){
	$id=intval($_GPC['id']);
	if(!$id)message("数据不正确");
	pdo_delete("j_act_voteitem",array("id"=>$id));
	message("删除成功",$this->createWebUrl('manage_vote',array("aid"=>$aid,'op'=>'post')),'success');
}elseif($operation=='addvoteitemorder'){
	$post=$_GPC['data'];
	$ary_key=explode("#",$post);
	for($i=0;$i<count($ary_key);$i++){
		pdo_update("j_act_voteitem",array("votekey"=>($i+1)),array("id"=>$ary_key[$i]));
	}
	die(json_encode(array('success'=>true)));
}elseif($operation=='clear'){
	$redis=new redisDB();
	if($redis->exists($vote_Redis_item))$redis->delete($vote_Redis_item);
	if($redis->exists($vote_Redis_reply))$redis->delete($vote_Redis_reply);
	if($redis->exists($vote_Redis_list))$redis->delete($vote_Redis_list);
	$keyslist=$redis->keys($vote_Redis_voter);
	foreach($keyslist as $row){
		if($redis->exists($row))$redis->delete($row);
	}
	message("删除成功",$this->createWebUrl('manage_vote',array("aid"=>$aid,'op'=>'post')),'success');
}elseif($operation=='post'){
	load()->func('tpl');
	$item=pdo_fetch("SELECT * FROM ".tablename('j_act_votegame')." where aid=:aid ",array(':aid'=>$aid));
	
	if (checksubmit('submit')) {
		$data=array(
			"aid"=>$aid,
			"title"=>$_GPC['title'],
			"weid"=>$_W['uniacid'],
			"status"=>intval($_GPC['status']),
			'starttime' => strtotime($_GPC['acttime']['start']),
			'endtime' => strtotime($_GPC['acttime']['end']),
			"votetime"=>intval($_GPC['votetime']),
			"msg"=>$_GPC['msg'],
			"thumb"=>$_GPC['thumb'],
			"bg"=>$_GPC['bg'],
			"info"=>htmlspecialchars_decode($_GPC['info']),
		);
		if(!$item){
			pdo_insert("j_act_votegame",$data);
		}else{
			unset($data['aid']);
			unset($data['weid']);
			pdo_update("j_act_votegame",$data,array("aid"=>$aid));
		}
		$redis =  new redisDB();
		$item=pdo_fetch("SELECT * FROM ".tablename('j_act_votegame')." where aid=:aid ",array(':aid'=>$aid));
		$redis->set($vote_Redis_reply,$item);
		if($redis->exists($vote_Redis_item))$redis->delete($vote_Redis_item);
		$itemlist=pdo_fetchall("SELECT * FROM ".tablename('j_act_voteitem')." where aid=:aid order by votekey asc",array(':aid'=>$aid));
		foreach($itemlist as $row){
			$redis->push($vote_Redis_item,$row);
		}
		
		message("修改成功",$this->createWebUrl('manage_vote',array("aid"=>$aid,'op'=>'post')),'success');
	}
	if(!$item){
		$item=array(
			"status"=>1,
			"votetime"=>1,
			"starttime"=>TIMESTAMP,
			"endtime"=>TIMESTAMP+(60*60*24),
			"title"=>$reply['title']."—投票活动",
		);
	}
}
include $this->template('manage_vote');
