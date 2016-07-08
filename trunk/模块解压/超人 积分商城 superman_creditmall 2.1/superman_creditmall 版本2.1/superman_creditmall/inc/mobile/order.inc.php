<?php
/**
 * 【超人】积分商城模块定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Superman_creditmall_doMobileOrder extends Superman_creditmallModuleSite {

    public function __construct() {
        parent::__construct();
        checkauth();
    }

    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display', 'delete', 'receive', 'cancel', 'detail'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '我的订单';
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 10;
            $start = ($pindex - 1) * $pagesize;
            //$allstatus = array(-99,0,2,3);
            $list = array(
                'all' => array(),
                'no_pay' => array(),
                'no_receive' => array(),
                'no_comment' => array(),
            );
            $status = 'all';  //全部订单
            if (isset($_GPC['status']) && array_key_exists($_GPC['status'], $list)) {
                $status = $_GPC['status'];
            }
            $filter = array(
                'uid' => $_W['member']['uid'],
            );
            switch ($status) {
                case 'all':
                    $filter['more_status'] = -2;    //status > -2
                    break;
                case 'no_pay':
                    $filter['status'] = 0;    //status == 0
                    break;
                case 'no_receive':
                    $filter['in_status'] = array(1,2);    //status IN(1,2)
                    break;
                case 'no_comment':
                    $filter['status'] = 3;    //status == 3
                    break;
            }
            $order_list = superman_order_fetchall($filter, '', $start, $pagesize);
            if ($order_list) {
                foreach ($order_list as &$item) {
                    superman_order_set($item);
                    $product = superman_product_fetch($item['product_id']);
                    $item['product'] = $product?superman_product_set($product):array();
                }
                unset($item);
                $list[$status] = $order_list;
            }
            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                die(json_encode($list[$status]));
            }
            include $this->template('order');
        } else if ($act == 'detail') {
            $header_title = '订单详情';
            $id = intval($_GPC['orderid']);
            if (!$id) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $order = superman_order_fetch($id);
            if (!$order) {
                $this->json_output(ERRNO::ORDER_NOT_EXIST);
            }
            if ($order['uid'] != $_W['member']['uid']) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            superman_order_set($order);
            $product = superman_product_fetch($order['product_id']);
            if ($product) {
                superman_product_set($product);
            }
            include $this->template('order');
        } else if ($act == 'delete') {
            $id = intval($_GPC['orderid']);
            if (!$id) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $order = superman_order_fetch($id);
            if (!$order || $order['status'] == -2) {
                $this->json_output(ERRNO::ORDER_NOT_EXIST);
            }
            if ($order['uid'] != $_W['member']['uid']) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $condition = array(
                'id' => $id,
            );
            $data = array(
                'status' => -2,
                'updatetime' => TIMESTAMP,
            );
            pdo_update('superman_creditmall_order', $data, $condition);
            $url = $this->createMobileUrl('order');
            $this->json_output(ERRNO::OK, '删除成功，跳转中...', array('url' => $url));
        } else if ($act == 'receive') {
            $id = intval($_GPC['orderid']);
            if (!$id) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $order = superman_order_fetch($id);
            if (!$order || $order['status'] == -2) {
                $this->json_output(ERRNO::ORDER_NOT_EXIST);
            }
            if ($order['uid'] != $_W['member']['uid']) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $condition = array(
                'id' => $id,
            );
            $data = array(
                'status' => 3,
                'updatetime' => TIMESTAMP,
            );
            pdo_update('superman_creditmall_order', $data, $condition);
            $url = $this->createMobileUrl('order', array('status' => 'no_comment'));
            $this->json_output(ERRNO::OK, '确认收货，跳转中...', array('url' => $url));
        } else if ($act == 'cancel') {
            $id = intval($_GPC['orderid']);
            if (!$id) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            $order = superman_order_fetch($id);
            if (!$order || $order['status'] == -2) {
                $this->json_output(ERRNO::ORDER_NOT_EXIST);
            }
            if ($order['uid'] != $_W['member']['uid']) {
                $this->json_output(ERRNO::INVALID_REQUEST);
            }
            //积分+现金兑换时，可能已经扣了积分，但是没有支付成功，这时需要退积分
            if ($order['pay_credit']) {
                $ret = $this->returnCredit($order, "取消订单({$order['ordersn']})");
                if ($ret !== true) {
                    WeUtility::logging('fatal', '取消订单退积分失败, order='.var_export($order, true));
                }
            }
            $condition = array(
                'id' => $id,
            );
            $data = array(
                'status' => -1,
                'updatetime' => TIMESTAMP,
            );
            pdo_update('superman_creditmall_order', $data, $condition);
            $url = $this->createMobileUrl('order', array('status' => 'no_pay'));
            $this->json_output(ERRNO::OK, '取消成功，跳转中...', array('url' => $url));
        }
    }
}

$obj = new Superman_creditmall_doMobileOrder;
$obj->exec();
