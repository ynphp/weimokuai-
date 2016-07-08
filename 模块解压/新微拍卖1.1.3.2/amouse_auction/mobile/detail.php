<?php
	if (empty($_GPC['id'])) {
        message('抱歉，参数错误！', '', 'error');
    }
	$id = intval($_GPC['id']);
    $userInfo=Util::getClientCookieUserInfo('amouse_auction_2015070201001001'.$weid);
    if(empty($userInfo)){
        $this->checkCookie('index');
    }
	$goods = pdo_fetch("SELECT * FROM ".tablename('amouse_auction_goods')." WHERE uniacid = $weid  and id =$id ");
    $end_time=date('Y/m/d H:i:s',$goods['end_time']);
    $currentdate=date('Y/m/d H:i:s',time());
	$pindex = 1;
	$psize = 10;
	$list = pdo_fetchall("SELECT * FROM ".tablename('amouse_auction_record')." WHERE uniacid =$weid and gid = $id ORDER BY price DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
    $rtotal =count($list);

    $mycollect= pdo_fetch("SELECT * FROM ".tablename('amouse_auction_mycollect')." WHERE uniacid = $weid AND gid = $id and oid= '{$userInfo['openid']}' ");

    $shareurl=$_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$goods['id']),true), 2);
    include $this->template('auction_detail');
?>