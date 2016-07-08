<?php
global $_W,$_GPC;
$user_table = 'meepo_paipaile_user';
$activity_table = 'meepo_paipaile_activity';
if($_W['isajax']){
	$actid = intval($_GPC['actid']);
	$rid = intval($_GPC['rid']);
	pdo_update($activity_table,array('onoff'=>3),array('id'=>$actid,'weid'=>$_W['uniacid'],'rid'=>$rid));
	$data = pdo_fetchall("SELECT * FROM ".tablename($user_table)." WHERE weid = :weid AND actid = :actid AND rid = :rid",array(":weid"=>$_W['uniacid'],":actid"=>$actid,':rid'=>$rid));
	die(json_encode($data));
}