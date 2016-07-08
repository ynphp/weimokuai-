<?php
include(MODULE_ROOT.'/inc/redisDB.php');
global $_W,$_GPC;
$operation = $_GPC['op']?$_GPC['op']:'post';
$aid=intval($_GPC['aid']);
$reply=pdo_fetch("SELECT * FROM ".tablename('j_act_activity')." where id=:id ",array(':id'=>$aid));
if(!$reply)message("活动不存在或者已经删除");
load()->func('tpl');

/**/
$ask_Redis_reply="j_act_ask_".$_W['uniacid']."_".$aid;
$ask_Redis_list="j_act_ask_list".$_W['uniacid']."_".$aid;
$ask_Redis_asker="j_act_asker_".$_W['uniacid']."_".$aid."_*";
$ask_Redis_showlist="j_act_ask_showlist".$_W['uniacid']."_".$aid;
$ask_Redis_keyid="j_act_ask_id_".$_W['uniacid']."_".$aid;

if($operation=="display"){
	$redis=new redisDB();
	$item=pdo_fetch("SELECT * FROM ".tablename('j_act_ask')." where aid=:aid ",array(':aid'=>$aid));
	include $this->template('show_ask');
	exit();
}elseif($operation=='changestatus'){
	//改变状态
	$sta=$_GPC['status'];
	$status = $sta==1 ? 1:2;
	pdo_update("j_act_ask",array("status"=>$status),array("aid"=>$aid));
	$redis=new redisDB();
	$item=pdo_fetch("SELECT * FROM ".tablename('j_act_ask')." where aid=:aid ",array(':aid'=>$aid));
	$redis->set($ask_Redis_reply,$item);
	die(json_encode(array('success'=>true)));
	
}elseif($operation=='getuser'){
	$maxid=intval($_GPC['maxid']);
	$minid=intval($_GPC['minid']);
	$type=intval($_GPC['type']);
	
	$redis=new redisDB();
	$allAsker=$redis->keys($ask_Redis_asker);
	$list_asker=$redis->getrange($ask_Redis_list);
	$maxShowId=$redis->get($ask_Redis_keyid);
	if(!$maxShowId)die(json_encode(array('success'=>false,'msg'=>'前台没有记录或者已经清空数据')));
	
	$tempsql="insert into ".tablename('j_act_askresult')."(aid, weid,showid,from_user,nickname,avatar,content) VALUES";
	$sql_ary=array();
	$mysqlMaxshowid=pdo_fetchcolumn("select max(showid) from ".tablename('j_act_askresult')." where aid=:aid order by showid desc limit 1",array('aid'=>$aid));
	if($maxShowId!=$mysqlMaxshowid){
		foreach($list_asker as $row2){
			$row=json_decode($row2,true);
			if($row['showid']<=$mysqlMaxshowid)continue;
			array_push($sql_ary,"('".$aid."','".$_W['uniacid']."','".$row['showid']."','".$row['openid']."','".$row['nickname']."','".$row['avatar']."','".$row['content']."')");
		}
		$tempsql.=implode(",",$sql_ary);
		@pdo_run($tempsql);
	}
	$outlist=pdo_fetchall("select * from ".tablename('j_act_askresult')." where aid=:aid and showid>:maxid order by id asc limit 0,3",array('aid'=>$aid,":maxid"=>$maxid));
	if($type)$outlist=pdo_fetchall("select * from ".tablename('j_act_askresult')." where aid=:aid and showid<:minid order by id desc limit 0,3",array('aid'=>$aid,":minid"=>$minid));	
	die(json_encode(array('success'=>true,'list'=>$outlist)));
	
}elseif($operation=='post'){
	$item=pdo_fetch("SELECT * FROM ".tablename('j_act_ask')." where aid=:aid ",array(':aid'=>$aid));
	if (checksubmit('submit')) {
		$data=array(
			"aid"=>$aid,
			"title"=>$_GPC['title'],
			"weid"=>$_W['uniacid'],
			"status"=>intval($_GPC['status']),
			"sendnum"=>intval($_GPC['sendnum']),
			"answer"=>$_GPC['answer'],
		);
		if(!$item){
			pdo_insert("j_act_ask",$data);
		}else{
			unset($data['aid']);
			unset($data['weid']);
			pdo_update("j_act_ask",$data,array("aid"=>$aid));
		}
		$redis =  new redisDB();
		$item=pdo_fetch("SELECT * FROM ".tablename('j_act_ask')." where aid=:aid ",array(':aid'=>$aid));
		$redis->set($ask_Redis_reply,$item);
		if($redis->exists($ask_Redis_item))$redis->delete($ask_Redis_item);		
		message("修改成功",$this->createWebUrl('manage_ask',array("aid"=>$aid,'op'=>'post')),'success');
	}
	if(!$item){
		$item=array(
			"status"=>1,
			"sendnum"=>1,
			"title"=>$reply['title']."—现场提问",
		);
	}
}elseif($operation=='clear'){
	$redis=new redisDB();

	if($redis->exists($ask_Redis_reply))$redis->delete($ask_Redis_reply);
	if($redis->exists($ask_Redis_list))$redis->delete($ask_Redis_list);
	if($redis->exists($ask_Redis_showlist))$redis->delete($ask_Redis_showlist);
	if($redis->exists($ask_Redis_keyid))$redis->delete($ask_Redis_keyid);
	$keyslist=$redis->keys($ask_Redis_asker);
	foreach($keyslist as $row){
		if($redis->exists($row))$redis->delete($row);
	}
	message("删除成功",$this->createWebUrl('manage_ask',array("aid"=>$aid,'op'=>'post')),'success');
}elseif($operation=='asklist'){
	
}

include $this->template('manage_ask');