<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$user_table = 'meepo_paoma_user';
$activity_table = 'meepo_paoma_activity';
$reply_table = 'meepo_paoma_reply';
if($_W['isajax']){
	$rid = intval($_GPC['rid']);
	$rotate_id = intval($_GPC['rotate_id']);
	pdo_update($user_table,array('point'=>0,'createtime'=>0),array('weid'=>$weid,'rid'=>$rid,'rotate_id'=>$rotate_id));
	pdo_update($activity_table,array('status'=>0),array('weid'=>$weid,'rid'=>$rid,'id'=>$rotate_id));
	die(json_encode(array('ret'=>1)));
}else{
  die(json_encode(array('ret'=>0)));
}