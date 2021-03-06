<?php
//	$id = "05217676";  //测试期间给一个默认值
$id = $_GPC['id']; //获取订单的id,,,,,,,,这里的id实际上对应数据库的orderno
		
		if(!empty($id)){
			//从订单信息表里获取数据,存在$order变量里面,前台页面使用了$order['ispaid']、$order['oprice']、$order['orderno']、$order['ctime']等数据
			$sql = 'SELECT * FROM '.tablename('tg_order').' WHERE orderno=:orderno  and uniacid = :uniacid'; //从订单信息表里面取得数据的sql语句
			$params = array(':orderno'=>$id , ':uniacid'=>$weid);
			$order = pdo_fetch($sql, $params); //获取符合条件的第一条数据,实际上,符合条件的数据应该有且只有一条
			if(empty($order)){
				message('获取订单信息失败.'.$id, $this->createMobileUrl('index'));	//如果没有找到符合条件的订单,则返回首页
			}
			
			//根据$order['addressid']从收货地址表tg_address获取数据,存在变量adds变量里面
			$sql = 'SELECT cname,tel, province, city, county, detailed_address FROM '.tablename('tg_address').' WHERE id=:addressid and uniacid = :uniacid'; //从收货地址表里面取得数据的sql语句
			$params = array(':addressid'=>$order['addressid'], ':uniacid'=>$weid);
			$adds = pdo_fetch($sql, $params); //获取符合条件的第一条数据,实际上,符合条件的数据应该有且只有一条
			if(empty($adds)){
				message('获取收货地址失败', $this->createMobileUrl('index'));	//如果没有找到符合条件的订单,则返回首页
			}
			
			//根据$order['gid']从收货地址表tg_goods获取数据,存在变量good变量里面
			$sql = 'SELECT gname,gprice, oprice,gimg FROM '.tablename('tg_goods').' WHERE id=:gid and uniacid = :uniacid'; //从商品信息表里面取得数据的sql语句
			$params = array(':gid'=>$order['g_id'], ':uniacid'=>$weid);
			$good = pdo_fetch($sql, $params); //获取符合条件的第一条数据,实际上,符合条件的数据应该有且只有一条
			if(empty($good)){
				message('获取商品信息失败', $this->createMobileUrl('index'));	//如果没有找到符合条件的订单,则返回首页
			}
		}
	include $this->template('orderdetails');
?>
