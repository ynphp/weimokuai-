<?php
global $_W,$_GPC;
$activity_table = 'meepo_paipaile_activity';
$photo_table = 'meepo_paipaile_photo';
if($_W['isajax']){
		$id = intval($_GPC['actid']);
		$rid = intval($_GPC['rid']);
		pdo_update($activity_table,array('onoff'=>2),array("weid"=>$_W['uniacid'],"id"=>$id,'rid'=>$rid));
		$check_photo = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($photo_table)." WHERE weid = :weid AND actid = :actid AND rid=  :rid",array(':weid'=>$_W['uniacid'],':actid'=>$id,':rid'=>$rid));
		die($check_photo);
}