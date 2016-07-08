<?php
global $_W,$_GPC;
$reply_table = 'meepo_paoma_reply';
$activity_table = 'meepo_paoma_activity';
$user_table = 'meepo_paoma_user';
$rotate_id = intval($_GPC['rotate_id']);
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid'];
if(!empty($rid)){
	$rounds = pdo_fetchall("SELECT `id` FROM ".tablename($activity_table)." WHERE weid = :weid AND rid = :rid ORDER BY createtime ASC",array(':weid'=>$weid,':rid'=>$rid));
}
if(!empty($rotate_id)){
$data = pdo_fetchall("SELECT * FROM ".tablename($user_table)." WHERE rid = :rid AND weid = :weid AND rotate_id = :rotate_id  AND point != 0 ORDER BY point DESC,createtime ASC LIMIT 0,19",array(':rid'=>$rid,':weid'=>$weid,':rotate_id'=>$rotate_id));
}else{
$data = pdo_fetchall("SELECT * FROM ".tablename($user_table)." WHERE rid = :rid AND weid = :weid AND rotate_id = :rotate_id  AND point != 0 ORDER BY point DESC,createtime ASC LIMIT 0,19",array(':rid'=>$rid,':weid'=>$weid,':rotate_id'=>$rounds[0]['id']));
}
include $this->template('result');