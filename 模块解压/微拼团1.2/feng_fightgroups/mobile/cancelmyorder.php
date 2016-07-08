<?php
	$orderno = $_GPC['orderno'];	 //订单现在的状态
	$openid = $_W['openid'];	//用户的openid
	
	
	//取消订单的操作
	if (!empty($orderno)) {
	
			$sql = 'SELECT * FROM '.tablename('tg_order').' WHERE orderno=:id ';
			$params = array(':id'=>$orderno);
			$order = pdo_fetch($sql, $params);
			if(empty($order)){
				message('未找到指定订单.'.$orderno, $this->createMobileUrl('myorder'));
				$tip = 888;
		
		}else{
			
		$ret = pdo_update('tg_order', array('status'=>9), array('orderno'=>$orderno));
		
		$tip = 9999;	
		
		}
	}
	
	
	$this->doMobileMyOrder();
?>