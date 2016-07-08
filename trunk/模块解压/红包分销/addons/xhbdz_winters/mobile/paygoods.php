<?php
/**
 * 购买产品页
 * 
 */
global $_W,$_GPC;
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W,$_GPC;
$openid = $_W['openid'];

$member = m('member')->get_member($openid);


if (!empty($_GPC['gid'])){ 
$goods = m('goods')->get_goods($_GPC['gid']);
}

$settings = $this->module['config'];
if ($settings['link1'] == $_GPC['gid']){
    $level = 1;
}elseif ($settings['link2'] == $_GPC['gid']){
    $level = 2;
}elseif ($settings['link3'] == $_GPC['gid']){
    $level = 3;
}else{
    $level = 0;
}


if ($member['level'] >= $level){
    message('正在为您加载相关创业课程中...',$this->createMobileUrl('mygoods',array('gid'=>$_GPC['gid'])),'warning');
}

if ($member['level'] < ($level-1)){
    message('抱歉您无权购买次商品！！',$this->createMobileUrl('index'),'error');
}

$goods['sign'] = md5(date('YmdHis',time()).mt_rand(10000000,99999999).'*（*（F：“GF{？：》》：》？”？》CVK~*&（%……EA685df*%￥@……**@。');
    
$order = m('order')->get_orders($member['uid']);

if ((!empty($order['ordersn']) && ($order['paystate'] != 1))){

    $url = $_W['siteroot'].$this->createMobileUrl('pay',array('ordersn'=>$order['ordersn']));
    header("Location: $url");
    exit();
}

include $this->template('paygoods');