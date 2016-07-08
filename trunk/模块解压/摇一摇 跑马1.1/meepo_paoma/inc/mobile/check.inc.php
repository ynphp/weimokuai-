<?php
global $_W,$_GPC;
$activity_table = 'meepo_paoma_activity';
if($_W['isajax']){
	$rid = intval($_GPC['rid']);
	$weid = $_W['uniacid'];
	$rotate_id = intval($_GPC['rotate_id']);
	if(!$rid || !$rotate_id){
			die(json_encode(array('ret'=>1)));
	}
	$check = pdo_fetch("SELECT * FROM ".tablename($activity_table)." WHERE rid = :rid AND id = :id",array(':rid'=>$rid,':id'=>$rotate_id));
	if(empty($check)){
			die(json_encode(array('ret'=>1)));
	}
	pdo_update($activity_table,array('status'=>1),array('weid'=>$weid,'rid'=>$rid,'id'=>$rotate_id));
	die(json_encode(array('ret'=>0)));
}