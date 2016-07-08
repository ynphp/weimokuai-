<?php
defined('IN_IA') or exit('Access Denied');
class Ewei_hotel extends WeModuleSite{
	public function Main() {
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($operation=="getorder"){
			if($_GPC['ordersn']){
				$item=pdo_fetch("SELECT * FROM ".tablename('hotel2_order')." WHERE weid='{$_W['uniacid']}' and ordersn=:a ",array(":a"=>$_GPC['ordersn']));
				if(strlen(trim($_GPC['ordersn']))==11){
					$item=pdo_fetch("SELECT * FROM ".tablename('hotel2_order')." WHERE weid='{$_W['uniacid']}' and mobile=:a order by id desc limit 1",array(":a"=>$_GPC['ordersn']));
				}
				if(!$item)die(json_encode(array("success"=>false,"msg"=>"查询记录为空")));
				die(json_encode(array("success"=>true,"msg"=>$item)));
			}else{
				die(json_encode(array("success"=>false,"msg"=>"查询记录为空")));
			}
		}elseif($operation=="paysuccess"){
			$ordersn=$_GPC['ordersn'];
			if($ordersn){
				$item=pdo_fetch("SELECT * FROM ".tablename('hotel2_order')." WHERE weid='{$_W['uniacid']}' and ordersn=:a",array(":a"=>$_GPC['ordersn']));
				pdo_update("hotel2_order",array("status"=>3),array("ordersn"=>$_GPC['ordersn']));
			}
		}
		die(json_encode(array("success"=>false,"msg"=>"error")));
	}
}