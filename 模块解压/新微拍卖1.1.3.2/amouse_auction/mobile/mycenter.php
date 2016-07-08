<?php
$weid=$_W['uniacid'];
$openid=$_W['openid'];
$userInfo=Util::getClientCookieUserInfo("amouse_auction_2015070801001001".$weid);
if(empty($userInfo)){
    $this->checkCookie('mycenter');
}
$member=pdo_fetch("SELECT * FROM ".tablename('amouse_auction_member')." WHERE uniacid = $weid and oid= '{$userInfo['openid']}' ");
if($sharedata['isblank'] == 0){
    if($member && $member['blacklist'] == 0){
        include $this->template('auction_blank');
        exit;
    }
}
include $this->template('auction_my');
?>