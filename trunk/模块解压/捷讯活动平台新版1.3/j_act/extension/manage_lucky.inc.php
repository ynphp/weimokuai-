<?php
global $_W,$_GPC;
include(MODULE_ROOT.'/inc/redisDB.php');
$operation = $_GPC['op']?$_GPC['op']:'display';
$aid=intval($_GPC['aid']);
$reply=pdo_fetch("SELECT * FROM ".tablename('j_act_activity')." where id=:id ",array(':id'=>$aid));
if(!$reply)message("活动不存在或者已经删除");
load()->func('tpl');
#include(MODULE_ROOT.'/jetsum_function.php');
if($operation=="show_lucky"){
	$id=intval($_GPC['id']);
	$item = pdo_fetch("SELECT * FROM ".tablename('j_act_luckygame')." WHERE id = :id",array(":id"=>$id));
	if(!$id)message("活动不存在");
	$prize_list=@explode("|#|",$item['option']);
	if($item['vid'])$votelist=pdo_fetchall("SELECT * FROM ".tablename('j_act_voteitem')." where aid=:aid order by votekey asc",array(':aid'=>$aid));
	include $this->template('show_lucky');
	exit();
}elseif($operation=="display"){
	$list=pdo_fetchall("SELECT * FROM ".tablename('j_act_luckygame')." where aid=:aid ",array(':aid'=>$aid));
	
}elseif($operation=='post'){
	$id=intval($_GPC['id']);
	if(!empty($id)){
		$item = pdo_fetch("SELECT * FROM ".tablename('j_act_luckygame')." WHERE id = :id",array(":id"=>$id));
		$prize_list=@explode("|#|",$item['option']);
	}
	$vote_list=pdo_fetchall("SELECT * FROM ".tablename('j_act_votegame')." where aid=:aid order by id desc",array(':aid'=>$aid));
	if (checksubmit('submit')){
		$data=array(
			"aid"=>$aid,
			"vid"=>$_GPC['vid'],
			"bg"=>$_GPC['bg'],
			"thumb"=>$_GPC['thumb'],
			"topthumb"=>$_GPC['topthumb'],
			"weid"=>$_W['uniacid'],
			
			"title"=>$_GPC['title'],
			"onlysome"=>intval($_GPC['onlysome']),
			"msg"=>$_GPC['msg'],
			
			"option"=>implode("|#|",$_GPC['option']),
		);
		if($id){
			unset($data['aid'],$data['weid']);
			pdo_update("j_act_luckygame",$data);
		}else{
			pdo_insert("j_act_luckygame",$data);
		}
		message('更新成功！', $this->createWebUrl('manage_lucky', array('op' => 'display','aid' =>$aid)), 'success');
	}
}elseif($operation=='delete'){
	$id=intval($_GPC['id']);
	if(!$id)message('缺少参数');
	pdo_delete("j_act_luckygame",array('id'=>$id));
	pdo_delete("j_act_luckywinner",array('lid'=>$id));
	message('删除成功！', $this->createWebUrl('manage_lucky', array('op' => 'display','aid' =>$aid)), 'success');
}elseif($operation=="joiner"){
	//得奖者列表
	$id=intval($_GPC['id']);
	$list=pdo_fetchall("SELECT * FROM ".tablename('j_act_luckywinner')." where lid=:lid ",array(':lid'=>$id));
	if (checksubmit('pldelete')){
		if($_GPC['select']){
			pdo_delete("j_act_luckywinner","id in (".implode(",",$_GPC['select']).")");
		}
		message('删除成功！', $this->createWebUrl('manage_lucky', array('op' => 'joiner','aid' =>$aid,'id' =>$id)), 'success');
	}
	if (checksubmit('plgetprize')){
		if($_GPC['select']){
			pdo_update("j_act_luckywinner",array('status'=>1),"id in (".implode(",",$_GPC['select']).")");
		}
		message('批量标记领奖成功！', $this->createWebUrl('manage_lucky', array('op' => 'joiner','aid' =>$aid,'id' =>$id)), 'success');
	}
	if (checksubmit('plungetprize')){
		if($_GPC['select']){
			pdo_update("j_act_luckywinner",array('status'=>0),"id in (".implode(",",$_GPC['select']).")");
		}
		message('批量取消领奖成功！', $this->createWebUrl('manage_lucky', array('op' => 'joiner','aid' =>$aid,'id' =>$id)), 'success');
	}
	$count_ary=array(
		"all"=>count($list),
		"noget"=>0,
		"get"=>0,
		"sendfail"=>0,
		"sendsuccess"=>0
	);
	foreach($list as $row){
		if(!$row['status']){
			$count_ary['noget']=$count_ary['noget']+1;
		}else{
			$count_ary['get']=$count_ary['get']+1;
		}
		if(!$row['sendstatus']){
			$count_ary['sendfail']=$count_ary['sendfail']+1;
		}else{
			$count_ary['sendsuccess']=$count_ary['sendsuccess']+1;
		}
	}
}elseif($operation=='resendmessage'){
	//重发中奖信息
	$id=intval($_GPC['id']);
	$uid=intval($_GPC['uid']);
	$sendtype=intval($_GPC['sendtype']);
	$pindex=intval($_GPC['page']);
	if($uid){
		$fans = pdo_fetch("SELECT * FROM ".tablename('j_act_luckywinner')." WHERE id = :id",array(":id"=>$uid));
	}else{
		$sql=$sql="SELECT * FROM ".tablename('j_act_luckywinner')." WHERE lid = :lid order by id asc  LIMIT ".$pindex.",1";
		if($sendtype==1)$sql="SELECT * FROM ".tablename('j_act_luckywinner')." WHERE lid = :lid and status=0 order by id asc LIMIT ".$pindex.",1";
		if($sendtype==2)$sql="SELECT * FROM ".tablename('j_act_luckywinner')." WHERE lid = :lid and sendstatus=0 order by id asc  LIMIT ".$pindex.",1";
		
		$fans = pdo_fetch($sql,array(":lid"=>$id));
	}
	$txt= $fans['prize'] ? "恭喜您，获得".$fans['prize']."。请到后台领取奖品，凭此信息兑奖":"恭喜您中奖了，请到后台领取奖品，凭此信息兑奖";
	$result=_sendText($fans['from_user'],$txt);
	if(!$result['errno']){
		pdo_update("j_act_luckywinner",array("sendstatus"=>1,"remark"=>""),array("id"=>$fans['id']));
	}else{
		pdo_update("j_act_luckywinner",array("sendstatus"=>0,"remark"=>implode('，',$result)),array("id"=>$fans['id']));
	}
	die(json_encode(array('success'=>true,'errno'=>$result['errno'],'msg'=>implode('，',$result))));
	
}elseif($operation=='joinerprize'){
	//标记领奖
	$id=intval($_GPC['id']);
	$uid=intval($_GPC['uid']);
	$status=intval($_GPC['status']);
	pdo_update("j_act_luckywinner",array("status"=>$status),array("id"=>$uid));
	message('标记领奖成功！', $this->createWebUrl('manage_lucky', array('op' => 'joiner','aid' =>$aid,'id' =>$id)), 'success');
}elseif($operation=='joinerdelete'){
	//删除中奖者
	$id=intval($_GPC['id']);
	$uid=intval($_GPC['uid']);
	pdo_delete("j_act_luckywinner",array("id"=>$uid));
	message('删除成功！', $this->createWebUrl('manage_lucky', array('op' => 'joiner','aid' =>$aid,'id' =>$id)), 'success');
}elseif($operation=='getuser'){
	//抽奖环节
	$id=intval($_GPC['id']);
	$vid=intval($_GPC['vid']);
	$item = pdo_fetch("SELECT * FROM ".tablename('j_act_luckygame')." WHERE id = :id",array(":id"=>$id));
	$user=array();
	if($item['vid']){
		//参与投票
		$list = pdo_fetchall("SELECT max(nickname) as nname,max(avatar) as head,from_user FROM ".tablename('j_act_voteresult')." WHERE aid = :aid and from_user not in(select from_user from ".tablename('j_act_luckywinner')." where lid=:lid group by from_user) group by from_user ",array(":aid"=>$aid,":lid"=>$id));
		if($vid)$list = pdo_fetchall("SELECT max(nickname) as nname,max(avatar) as head,from_user FROM ".tablename('j_act_voteresult')." WHERE aid = :aid and from_user not in(select from_user from ".tablename('j_act_luckywinner')." where lid=:lid group by from_user) and vid=:vid group by from_user ",array(":aid"=>$aid,":lid"=>$id,":vid"=>$vid));
		shuffle($list);
		$i=0;
		foreach($list as $row){
			if($i>=20)break;
			$user[]=array(
				'nickname'=>$row['nname'],
				'avatar'=>$row['head'],
				'from_user'=>$row['from_user'],
			);
			$i++;
		}
	}else{
		//报名参与活动
		$list = pdo_fetchall("SELECT max(nickname) as nname,max(avatar) as head,from_user FROM ".tablename('j_act_joiner')." WHERE aid = :aid and from_user not in(select from_user from ".tablename('j_act_luckywinner')." where lid=:lid group by from_user) group by from_user ",array(":aid"=>$aid,":lid"=>$id));
		
		if($item['vip'])$list = pdo_fetchall("SELECT max(nickname) as nname,max(avatar) as head,from_user FROM ".tablename('j_act_joiner')." WHERE aid = :aid and from_user not in(select from_user from ".tablename('j_act_luckywinner')." where lid=:lid group by from_user) and vip=1 group by from_user ",array(":aid"=>$aid,":lid"=>$id));
		shuffle($list);
		$i=0;
		foreach($list as $row){
			if($i>=20)break;
			$user[]=array(
				'nickname'=>$row['nname'],
				'avatar'=>$row['head'],
				'from_user'=>$row['from_user'],
			);
			$i++;
		}
	}
	die(json_encode(array('success'=>true,'user'=>$user)));
}elseif($operation=='submitwinner'){
	$id=intval($_GPC['id']);
	$prize=$_GPC['prize'];
	$userlist=$_GPC['userlist'];
	
	$list=array();
	if(strpos($userlist,"|^^|")){
		$list=explode("|^^|",$userlist);
	}else{
		$list[]=$userlist;
	}
	$item = pdo_fetch("SELECT * FROM ".tablename('j_act_luckygame')." WHERE id = :id",array(":id"=>$id));
	$listall=pdo_fetchall("select max(nickname) as nname,max(avatar) as head,from_user from ".tablename('j_act_joiner')." where aid=:aid and from_user in('".implode("','",$list)."') group by from_user",array(':aid'=>$aid));
	if($item['vid']){
		$listall=pdo_fetchall("select max(nickname) as nname,max(avatar) as head,from_user from ".tablename('j_act_voteresult')." where aid=:aid and from_user in('".implode("','",$list)."') group by from_user",array(':aid'=>$aid));
	}
	$tempsql="insert into ".tablename('j_act_luckywinner')."(aid, weid,lid,from_user,nickname,avatar,prize,sendstatus,remark) VALUES";
	$sql_ary=array();
	$txt= $prize ? "恭喜您，获得".$prize."。请到后台领取奖品，凭此信息兑奖":"恭喜您中奖了，请到后台领取奖品，凭此信息兑奖";
	foreach($listall as $row){
		$result=_sendText($row['from_user'],$txt);
		$temp="";
		if(!$result['errno']){
			$temp="('".$aid."','".$_W['uniacid']."','".$id."','".$row['from_user']."','".$row['nname']."','".$row['head']."','".$prize."','1','')";
		}else{
			$temp="('".$aid."','".$_W['uniacid']."','".$id."','".$row['from_user']."','".$row['nname']."','".$row['head']."','".$prize."','0','".implode('，',$result)."')";
		}
		array_push($sql_ary,$temp);
	}
	$tempsql.=implode(",",$sql_ary);
	@pdo_run($tempsql);
	//后续操作
	$prize_list=@explode("|#|",$item['option']);
	$prise_result=array();
	foreach($prize_list as $row){
		if($row)$prise_result[$row]=pdo_fetchcolumn("select count(*) from ".tablename('j_act_luckywinner')." where aid=:aid and lid=:lid and prize=:prize",array(':aid'=>$aid,':lid'=>$id,':prize'=>$row));
	}
	die(json_encode(array('success'=>true,'prize'=>$prise_result)));
}

include $this->template('manage_lucky');