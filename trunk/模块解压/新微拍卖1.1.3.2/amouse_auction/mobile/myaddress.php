<?php
    $weid=$_W['uniacid'];
    $uid=$_GPC['uid'];
    $member=pdo_fetch("SELECT * FROM ".tablename('amouse_auction_member')." WHERE uniacid = $weid and id=$uid ");
    include $this->template('auction_myaddress');
?>