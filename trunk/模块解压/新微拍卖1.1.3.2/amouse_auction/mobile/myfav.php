<?php
$weid=$_W['uniacid'];
$uid=$_GPC['uid'];
$orders=array();
$list=pdo_fetchall("SELECT * FROM ".tablename('amouse_auction_mycollect')." WHERE uniacid=$weid and uid= $uid ");
foreach($list as $item) {
    $goods=pdo_fetch('SELECT id,title,au_pic,offerNum FROM '.tablename('amouse_auction_goods')." WHERE uniacid=:weid AND id=:id ", array(':weid'=>$weid, ':id'=>$item['gid']));
    $item['gid']=$goods['id'];
    $item['title']=$goods['title'];
    $item['au_pic']=$goods['au_pic'];
    $item['offerNum']=$goods['offerNum'];
    $orders[]=$item;
}
unset($item);
include $this->template('auction_myfav');
?>