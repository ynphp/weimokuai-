<?php
/**
 * 产品完成页
 * 
 */
global $_W,$_GPC;
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W,$_GPC;
$openid = $_W['openid'];

$member = m('member')->get_member($openid);

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
    
if ($member['level'] < $level){
        message('抱歉由于您等级不足无法查看',$this->createMobileUrl('index'),'warning');
}
    
$goods = m('goods')->get_goods($_GPC['gid']);
$goods['content'] = htmlspecialchars_decode($goods['content']);
include $this->template('mygoods');