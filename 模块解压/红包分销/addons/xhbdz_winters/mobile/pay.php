<?php
/**
 * 确认支付
 * 
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W,$_GPC;
//获取公众号ID
$uniacid = $_W['uniacid'];
$openid = $_W['openid'];
$pay = $_W['cache']['unisetting:'.$uniacid]['payment'];

$orderinfo = array();
$ordersn = date('YmdHis',time()).mt_rand(1000,9999);

//获取打包数据
if (!empty($_GPC['ordersn'])){
    $orderinfo = m('order')->get_orders(0,$_GPC['ordersn']);
}else {
$orderinfo = $_GPC['goods'];

if (empty($orderinfo['gid'])){
    message('抱歉，您的访问存在异常！',$this->createMobileUrl('index'),'error');
}

$goods = m('goods')->get_goods($orderinfo['gid']);
$orderinfo['title'] = $goods['title'];

$orderinfo['price'] = $goods['price'];
unset($orderinfo['sign']);

$orderinfo['openid'] = $openid;
$orderinfo['createtime'] = time();

if (!empty($order)){
    $orderinfo = $order;
}else {
    $orderinfo['ordersn'] = $ordersn;
    m('order')->add_order($orderinfo);
}
}
$params = array(
 
    'ordersn' => $orderinfo['ordersn'],  //收银台中显示的订单号
    'tid' => $orderinfo['ordersn'],      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
    'title' => $orderinfo['title'],          //收银台中显示的标题
    'fee' =>$orderinfo['price'],      //收银台中显示需要支付的金额,只能大于 0
    'user' => $orderinfo['turename'],     //付款用户, 付款的用户名(选填项)
    'module' => $this->module['name'],
);


//调用pay方法
$this->pay($params);