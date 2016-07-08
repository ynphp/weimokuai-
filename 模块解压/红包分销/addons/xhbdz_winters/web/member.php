<?php
/**
 * 会员管理
 * 
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}

global $_W,$_GPC;
$op = $_GPC['op'];

$member_list = m('member')->get_memberAll();
$settings = $this->module['config'];

$level0 = $settings['name0'];
$level1 = $settings['name1'];
$level2 = $settings['name2'];
$level3 = $settings['name3'];

if($op == 'delete') {
    $id = intval($_GPC['uid']);
    if(empty($id)){
        message('未找到指定商品分类');
    }
    $result = pdo_delete(xhbdz_order,array('uid'=>$id));
    if(intval($result) == 1){
        message('删除会员成功.', $this->createWebUrl('order'), 'success');
    } else {
        message('删除会员失败.');
    }
}else {
include $this->template('web/member');
}
