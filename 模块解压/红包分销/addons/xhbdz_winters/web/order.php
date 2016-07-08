<?php
/**
 * 订单管理
 * 
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W,$_GPC;
$op = $_GPC['op'];

$orders_list = m('order')->get_ordersAll();
if($op == 'delete') {
    $id = intval($_GPC['id']);
    if(empty($id)){
        message('未找到指定商品分类');
    }
    $result = pdo_delete(hbdz_order,array('id'=>$id));
    if(intval($result) == 1){
        message('删除订单成功.', $this->createWebUrl('order'), 'success');
    } else {
        message('删除订单失败.');
    }
}
include $this->template('web/order');