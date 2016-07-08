<?php

defined('IN_IA') or exit('Access Denied');

class deposit {

    //获取提现记录
	public function get_depositlist($openid) {
	    global $_W;
	    $uniacid = $_W['uniacid'];
		$exist = array_reverse(pdo_fetchall('SELECT * FROM '.tablename('xhbdz_deposit')." WHERE uniacid = $uniacid AND `state` != 1 AND `openid` = '$openid'"));
		if (!empty($exist)) {
			return $exist;
		}
		return false;
	}
	
	//获取全部提现记录
	public function get_depositAll() {
	    global $_W;
	    $uniacid = $_W['uniacid'];
	    $exist = array_reverse(pdo_fetchall('SELECT * FROM '.tablename('xhbdz_deposit')." WHERE uniacid = $uniacid "));
	    if (!empty($exist)) {
	        return $exist;
	    }
	    return false;
	}
	
	public function get_depositlist2($openid) {
	    global $_W;
	    $uniacid = $_W['uniacid'];
	    $exist = array_reverse(pdo_fetchall('SELECT * FROM '.tablename('xhbdz_deposit')." WHERE uniacid = $uniacid AND `state` = 1 AND `openid` = '$openid'"));
	    if (!empty($exist)) {
	        return $exist;
	    }
	    return false;
	}
	
	//获取一条提现
	public function get_deposit($id) {
	    global $_W;
	    $uniacid = $_W['uniacid'];
	    $exist = array_reverse(pdo_fetch('SELECT * FROM '.tablename('xhbdz_deposit')." WHERE uniacid = $uniacid  AND `id` = $id"));
	    if (!empty($exist)) {
	        return $exist;
	    }
	    return false;
	}
	
	//获取一条提现
	public function get_depositBS($biaoshi,$openid) {
	    global $_W;
	    $uniacid = $_W['uniacid'];
	    $exist = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('xhbdz_deposit')."WHERE `uniacid` = $uniacid AND `biaoshi` = $biaoshi AND `openid` = '$openid'");
	    if (!empty($exist)) {
	        return $exist;
	    }
	    return false;
	}
	
	public function get_Today($openid) {
	    global $_W;
	    //今天
	    $Today = strtotime(date('Y-m-d'));
	    //今天24点
	    $Todays = strtotime(date('Y-m-d',strtotime('+1 day')));
	    $uniacid = $_W['uniacid'];
	    $exist =  pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('xhbdz_deposit'));
	    if (!empty($exist)) {
	        return $exist;
	    }
	    return false;
	}
	
	//添加一条提现记录
	public function add_depositlog($data) {
	    global $_W;
	    $data['uniacid'] = $_W['uniacid'];
	   if(pdo_insert(xhbdz_deposit,$data)){
	        return true;
	    }
	    return false;
	}
	
	public function update_deposit($id,$data) {
	    global $_W;
	    if(pdo_update(xhbdz_deposit,$data,array('id'=>$id))){
	        return true;
	    }
	    return false;
	}

}