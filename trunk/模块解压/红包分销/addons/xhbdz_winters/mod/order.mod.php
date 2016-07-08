<?php

defined('IN_IA') or exit('Access Denied');

class order {

    
    //获取我的所有订单
    public function get_orderlist($openid) {
        global $_W;
        $uniacid = $_W['uniacid'];
        $exist = pdo_fetchall('SELECT * FROM '.tablename('xhbdz_order')." WHERE uniacid = $uniacid AND  `openid` = '$openid'  ORDER BY `id` DESC");
        if (!empty($exist)) {
            return $exist;
        }
        return false;
    }
    
    
    //获取一条订单信息
    public function get_orders($id = 0,$ordersn = 0,$openid = 0) {
        global $_W;
        $uniacid = $_W['uniacid'];
        $result = pdo_fetch('SELECT * FROM '.tablename('xhbdz_order')." WHERE uniacid = $uniacid AND (`id` = $id or `ordersn` = '$ordersn' or `openid` = '$openid')");
        if (!empty($result)) {
            return $result;
        }
        return false;
    }
    
    //获取所有订单信息
    public function get_ordersAll() {
        global $_W;
        $uniacid = $_W['uniacid'];
        $result = array_reverse(pdo_fetchall('SELECT * FROM '.tablename('xhbdz_order')." WHERE uniacid = $uniacid"));
        if (!empty($result)) {
            return $result;
        }
        return false;
    }
    
    //插入一条订单信息
	public function add_order($orderinfo) {
	    global $_W;
	    $orderinfo['uniacid'] = $_W['uniacid'];
	    $orderinfo['openid'] = $_W['openid'];
        $result = pdo_insert('xhbdz_order', $orderinfo);
		if (!empty($result)) {
			return true;
		}
		return false;
	}
	
	
    //更新订单信息信息
	public function up_order($id,$upOder) {
    $result = pdo_update(xhbdz_order, $upOder, array('id' => $id));
	if (!empty($result)) {
			return true;
	   }
	   return false;
    }
}