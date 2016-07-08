<?php
global $_W,$_GPC;
$operation = $_GPC['op']?$_GPC['op']:'display';
$aid=intval($_GPC['aid']);
$reply=pdo_fetch("SELECT * FROM ".tablename('j_act_activity')." where id=:id ",array(':id'=>$aid));
$parama=json_decode($reply['parama'],true);
$field=array();
if($parama){
	foreach($parama as $index=>$row){
		$rew=explode("|#|",$row);
		$field[$index]=$rew[0];
	}
}
if(!$reply)message("活动不存在或者已经删除");
$cfg = $this->module['config'];
if($operation=="display"){
	$count_alljoin=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_act_joiner')." where aid=:id ",array(':id'=>$aid));
	$count_join_in=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_act_joiner')." where aid=:id and status=1",array(':id'=>$aid));
	$count_join_pay=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_act_joiner')." where aid=:id and paystatus=1",array(':id'=>$aid));
	$count_join_attend=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_act_joiner')." where aid=:id and attend=1",array(':id'=>$aid));
	$parama=json_decode($reply['parama'],true);
}elseif($operation=='webscancode'){
	$_key=$_GPC['code'];
	if(!$_key)die(json_encode(array('success'=>false,'msg'=>"请输入内容")));
	$str=$this->encrypt(urldecode($_key), 'D', "jEtSum");
	if(strpos($str,"|#|")){
		$tempary=explode("|#|",$str);
		if($tempary[0]!=$aid)die(json_encode(array('success'=>false,'msg'=>"该客户没有参与本次活动")));
		$user=pdo_fetch("SELECT * FROM ".tablename('j_act_joiner')." where aid=:aid and from_user=:from_user",array(':aid'=>$aid,':from_user'=>$tempary[1]));
	}else{
		$user=pdo_fetch("SELECT * FROM ".tablename('j_act_joiner')." where aid=:id and (realname='".$_key."' or mobile='".$_key."' or from_user='".$_key."' or openid='".$_key."' ) order by id desc limit 1",array(':id'=>$aid));
	}
	if(!$user)die(json_encode(array('success'=>false,'msg'=>"查无内容")));
	$parama2=json_decode($user['parama'],true);
	$temp=array(
		"id"=>$user['id'],
		"mobile"=>$user['mobile'],
		"realname"=>$user['realname'],
		"createtime"=>date("m/d H:i",$user['createtime']),
		"status"=>$user['status'],
		"realname"=>$user['realname'],
		"nickname"=>$user['nickname'],
		"mobile"=>$user['mobile'],
		"avatar"=>$user['headimgurl'],
		"sex"=>$user['sex'],
		"avatar"=>$user["avatar"],
		"attend"=>$user["attend"],
		"reloadmsg"=>$user["reloadmsg"],
		"fee"=>"￥ ".sprintf('%.2f', $user['fee'] / 100),
		"remark"=>$user["remark"],
		"endtime"=>$user["endtime"] ? date("m/d H:i",$user['endtime']) : '',
		"paystatus"=>$user["paystatus"],
		"paytime"=>$user["paytime"] ? date("m/d H:i",$user['paytime']) : '',
		"refundstatus"=>$user["refundstatus"],
		"refundfee"=>"￥ ".sprintf('%.2f', $user['refundfee'] / 100),
		"reloadmsg"=>$user["reloadmsg"],
		"remark"=>$user["remark"],
		"parama"=>$parama2,
	);
	die(json_encode(array('success'=>true,'item'=>$temp)));
}elseif($operation=='userattend'){
	$uid=intval($_GPC['uid']);
	$times=TIMESTAMP;
	pdo_update("j_act_joiner",array("attend"=>1,"endtime"=>$times),array("id"=>$uid));
	$user=pdo_fetch("SELECT * FROM ".tablename('j_act_joiner')." where id=:id",array(':id'=>$uid));
	if($user['fee'] && $reply['auto_refund'] && $reply['paystatus']){
		$data=$this->_refuneFee($uid,$cfg['appid'],$cfg['pay_mchid'],$cfg['pay_signkey'],array("cert"=>$cfg['apiclient_cert'],"key"=>$cfg['apiclient_key']));
		if($data['success']){
			die(json_encode(array('success'=>true,"refune"=>true,'fee'=>$data['fee'])));
		}else{
			die(json_encode(array('success'=>false,"refune"=>false,'endtime'=>date("m/d H:i",$times),'msg'=>"退款失败。".$data['msg'])));
		}
	}
	die(json_encode(array('success'=>true,'endtime'=>date("m/d H:i",$times))));
}elseif($operation=='userrefune'){
	$uid=intval($_GPC['uid']);
	$user=pdo_fetch("SELECT * FROM ".tablename('j_act_joiner')." where id=:id",array(':id'=>$uid));
	if($user['fee'] &&  $user['paystatus']){
		$data=$this->_refuneFee($uid,$cfg['appid'],$cfg['pay_mchid'],$cfg['pay_signkey'],array("cert"=>$cfg['apiclient_cert'],"key"=>$cfg['apiclient_key']));
		if($data['success']){
			die(json_encode(array('success'=>true,'fee'=>$data['fee'])));
		}else{
			die(json_encode(array('success'=>false,'msg'=>"退款失败。".$data['msg'])));
		}
	}
	die(json_encode(array('success'=>false,'msg'=>"不符合退款条件")));
}

include $this->template('manage_attend');

