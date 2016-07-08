<?php

$weid=$_W['uniacid'];
$uid=$_GPC['uid'];
$status=!isset($_GPC['status']) ? '' : $_GPC['status'];
$now_time=TIMESTAMP;
$member=pdo_fetch("SELECT * FROM ".tablename('amouse_auction_member')." WHERE uniacid = $weid and id=$uid  ");

$condition=" WHERE uniacid=$weid AND end_time < '{$now_time}' AND oid='{$member[oid]}' ";
if($status != ''){
    $condition.=" AND send_state= '".intval($status)."'";
}
$glist=pdo_fetchall("SELECT * FROM ".tablename('amouse_auction_goods').$condition."  ORDER BY id DESC  ");
include $this->template('auction_myorder');
?>