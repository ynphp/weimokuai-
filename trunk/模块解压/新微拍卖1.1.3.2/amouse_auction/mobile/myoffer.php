<?php

$weid=$_W['uniacid'];
$uid=$_GPC['uid'];
$pro=pdo_fetch("SELECT oid FROM ".tablename('amouse_auction_member')." WHERE uniacid = $weid  and id =$uid ");
$records=pdo_fetchall("SELECT * FROM ".tablename('amouse_auction_record')." WHERE uniacid = $weid  and oid ='{$pro['oid']}' ORDER BY createtime DESC ");
$number=0;
foreach($records as $key=>$value) {
    $p_goods[$number]=pdo_fetch("SELECT * FROM ".tablename('amouse_auction_goods')." WHERE uniacid =:weid and id=:gid", array(':weid'=>$weid, ':gid'=>$value['gid']));
    if($p_goods[$number]['end_time'] < TIMESTAMP){
        $p_goods[$number]['state']=0;
        if(empty($p_goods[$number]['oid'])){
            $data['oid']=$pro['oid'];
            pdo_update('amouse_auction_goods', $data, array('id'=>$p_goods[$number]['id']));
        }
    } else {
        $p_goods[$number]['state']=1;
    }
    $number ++;
}
include $this->template('auction_myoffer');
?>