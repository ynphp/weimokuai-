<?php
/**
 * 便利店模块微站定义
 *
 * @author Gorden
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class We7_storeModuleSite extends WeModuleSite {

	/** 商品分类表 */
	private $tb_category = 'we7_store_category';
	
	/** 商品信息表*/
	private $tb_goods = 'we7_store_goods';
	
	/** 购物车表 */
	private $tb_cart = 'we7_store_cart';

	/** 订单表 */
	private $tb_order = 'we7_store_orders';
	
	/** 订单清单表*/
	private $tb_item = 'we7_store_items';
	
	public function doWebRule(){
		global $_W, $_GPC;
		$rid = intval($_GPC['id']);
		echo $rid;
	}
	
	/**
	 * 前端 - 便利店首页
	 */
	public function doMobileStore() {
		global $_W, $_GPC;
		
		echo $_W['openid'];
		
		load()->model('mc');
		$avatar = '';
		
		if (!empty($_W['member']['uid'])) {
			$member = mc_fetch(intval($_W['member']['uid']), array('avatar'));
			if (!empty($member)) {
				$avatar = $member['avatar'];
			}
		}
		if (empty($avatar)) {
			$fan = mc_fansinfo($_W['openid']);
			if (!empty($fan)) {
				$avatar = $fan['avatar'];
			}
		}
		if (empty($avatar)) {
			$userinfo = mc_oauth_userinfo();
			if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['avatar'])) {
				$avatar = $userinfo['avatar'];
			}
		}
		if (empty($avatar) && !empty($_W['member']['uid'])) {
			$avatar = mc_require($_W['member']['uid'], array('avatar'));
		}
		if (empty($avatar)) {
			// 提示用户关注公众号。;
			echo "最终没有获取到头像,follow: {$_W['fans']['follow']}";
		} else {
			
		echo '<img src="$avatar">';
		}
		
		$goodsid = intval($_GPC['goodsid']);
		$goods = $this->getGoods($goodsid);
		if (!empty($goods)) {
			$goodses = array($goods['id']=>$goods);
			$cart = $this->getCartByGoodsid($goods['id']);
			$carts = array($goods['id']=>$cart);
		} else {
			$where = ' WHERE uniacid=:uniacid AND status=:status ';
			$params = array(
				':uniacid' => $_W['uniacid'],
				':status' => 2
			);
			$cid = intval($_GPC['cid']);
			if (!empty($cid)) {
				$where .= ' AND categoryid=:cid';
				$params[':cid'] = $cid;
			}
			$sql = 'SELECT * FROM '.tablename($this->tb_goods). "{$where}";
			$goodses = pdo_fetchall($sql, $params, 'id');
			$carts = $this->getCarts();
		}
		
		$categories = $this->getAllCategory();
		include $this->template('store');
	}
	
	/**
	 * 获取所有商品分类
	 * @return array(id=>category, ...)
	 */
	private function getAllCategory(){
		global $_W;
		
		$sql = 'SELECT * FROM '.tablename($this->tb_category).' WHERE uniacid=:uniacid ORDER BY `orderno` asc, id asc';
		$params = array(
			':uniacid' => $_W['uniacid']
		);
		$categories = pdo_fetchall($sql, $params, 'id');
		
		return $categories;
	}
	
	/**
	 * 后台管理 - 商品分类管理
	 */
	public function doWebCategory() {
		global $_W, $_GPC;
		
		$ops = array('display', 'create', 'delete'); // 只支持此 3 种操作.
		$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
		
		if($op == 'display'){
			
			// 处理 POST 提交
			if (checksubmit()){
				$cats = $_GPC['categories'];
				
				// 表单验证
				if(empty($cats)){
					message('尚未添加任何分类.');
				}
				foreach ($cats as $k => $cat){
					empty($cat['name']) && message('有分类名称未添加,无法保存.');
					$cat['orderno'] = intval($cat['orderno']);
				}
				
				// 数据更新
				foreach ($cats as $k => $cat){
					pdo_update($this->tb_category, $cat, array('id'=>$k));
				}
				message('保存成功.','','success');
			}
			
			// 处理 GET 提交
			$categories = $this->getAllCategory();
			
			load()->func('tpl');
			include $this->template('category');
		}
		
		if ($op == 'create') {
			
			if (checksubmit()) {
				$category = $_GPC['category']; // 获取打包值
				if(empty($category['name'])){
					message('未添加分类名称, 无法保存');
				}
				
				$category['uniacid'] = $_W['uniacid'];
				$category['orderno'] = intval($cat['orderno']);
				
				pdo_insert($this->tb_category, $category);
				
				message('添加分类成功',$this->createWebUrl('category', array('op'=>'display')),'success');
			}
			
			include $this->template('category');
		}
		
		if($op == 'delete') {
			$id = intval($_GPC['id']);
			if(empty($id)){
				message('未找到指定商品分类');
			}
			$result = pdo_delete($this->tb_category, array('id'=>$id, 'uniacid'=>$_W['uniacid']));
			if(intval($result) == 1){
				message('删除商品分类成功.', $this->createWebUrl('category'), 'success');
			} else {
				message('删除商品分类失败.');
			}
		}
	}

	/**
	 * 获取商品状态的文字描述
	 * @param int $status
	 * @return string
	 */
	private function getGoodsStatus($status){
		$status = intval($status);
		if ($status == 1) {
			return '下架';
		} elseif ($status == 2) {
			return '上架';
		} else {
			return '未知';
		}
	}
	
	/**
	 * 获取订单状态描述
	 * @param int $status
	 * @return string
	 */
	private function getOrdersStatus($status){
		$status = intval($status);
		if ($status == 1) {
			return '未付款';
		} elseif ($status == 2) {
			return '已付款';
		} else {
			return '未知';
		}
	}
	
	/**
	 * 管理后台 - 商品信息管理
	 */
	public function doWebGoods() {
		global $_W, $_GPC;
		
		$ops = array('display', 'edit', 'delete'); // 只支持此 3 种操作.
		$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
		
		if($op == 'display'){
			
			// 处理 GET 提交
			$pageindex = max(intval($_GPC['page']), 1); // 当前页码
			$pagesize = 2; // 设置分页大小
			
			$where = ' WHERE uniacid=:uniacid';
			$params = array(
				':uniacid'=>$_W['uniacid']
			);
			if (!empty($_GPC['keyword'])) {
				$where .= ' AND ( (`name` like :keyword) OR (`sn` like :keyword) )';
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}
			if (!empty($_GPC['status'])) {
				$where .= ' AND (status = :status)';
				$params[':status'] = intval($_GPC['status']);
			}
			if (!empty($_GPC['categoryid'])) {
				$where .= ' AND (categoryid = :categoryid)';
				$params[':categoryid'] = intval($_GPC['categoryid']);
			}
			
			$sql = 'SELECT COUNT(*) FROM '.tablename($this->tb_goods).$where;
			$total = pdo_fetchcolumn($sql, $params);
			$pager = pagination($total, $pageindex, $pagesize);
			
			$sql = 'SELECT * FROM '.tablename($this->tb_goods)." {$where} ORDER BY id asc LIMIT ".(($pageindex -1) * $pagesize).','. $pagesize;
			$goodses = pdo_fetchall($sql, $params, 'id');
			
			$categories = $this->getAllCategory();
			
			load()->func('tpl');
			include $this->template('goods_display');
		}
		
		if ($op == 'edit') {
			
			$id = intval($_GPC['id']);
			if(!empty($id)){
				$sql = 'SELECT * FROM '.tablename($this->tb_goods).' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
				$params = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
				$goods = pdo_fetch($sql, $params);
				
				if(empty($goods)){
					message('未找到指定的商品.', $this->createWebUrl('goods'));
				}
			}
			
			$categories = $this->getAllCategory();
			
			if (checksubmit()) {
				$data = $_GPC['goods']; // 获取打包值
				
				empty($data['name']) && message('请填写商品名称');
				empty($data['sn']) && message('请填写商品编码');
				empty($data['image']) && message('请上传商品外观图片');
				empty($data['categoryid']) && message('请选择商品分类');
				empty($data['cost']) && message('请填写商品进价');
				empty($data['price']) && message('请填写商品售价');
				empty($data['quantity']) && message('请填写商品库存');
				
				if(empty($goods)){
					$data['uniacid'] = $_W['uniacid'];
					$data['createtime'] = TIMESTAMP;
					
					$ret = pdo_insert($this->tb_goods, $data);
					if (!empty($ret)) {
						$id = pdo_insertid();
					}
				} else {
					$ret = pdo_update($this->tb_goods, $data, array('id'=>$id));
				}
				
				if (!empty($ret)) {
					message('商品信息保存成功', $this->createWebUrl('goods', array('op'=>'edit', 'id'=>$id)), 'success');
				} else {
					message('商品信息保存失败');
				}
			}
			
			load()->func('tpl');
			include $this->template('goods_edit');
		}
		
		if($op == 'delete') {
			$id = intval($_GPC['id']);
			if(empty($id)){
				message('未找到指定商品分类');
			}
			$result = pdo_delete($this->tb_goods, array('id'=>$id, 'uniacid'=>$_W['uniacid']));
			if(intval($result) == 1){
				message('删除商品成功.', $this->createWebUrl('goods'), 'success');
			} else {
				message('删除商品失败.');
			}
		}
	}
	
	/**
	 * 后台管理 - 订单管理
	 */
	public function doWebOrders() {
		global $_W, $_GPC;
		
		$ops = array('display'); // 只支持此 3 种操作.
		$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
		
		if($op == 'display'){
			// 处理 GET 提交
			$pageindex = max(intval($_GPC['page']), 1); // 当前页码
			$pagesize = 2; // 设置分页大小
			
			$where = ' WHERE uniacid=:uniacid';
			$params = array(
				':uniacid'=>$_W['uniacid']
			);
			if (!empty($_GPC['sn'])) {
				$where .= ' AND sn like :sn ';
				$params[':sn'] = "%{$_GPC['sn']}%";
			}
			$status = intval($_GPC['status']);
			if (in_array($status, array(1, 2))) {
				$where .= ' AND (status = :status)';
				$params[':status'] = intval($_GPC['status']);
			}
			
			$createtime = $_GPC['createtime'];
			if (empty($createtime)) {
				$createtime['start'] = date('Y-m-d', TIMESTAMP - 86400);
				$createtime['end'] = date('Y-m-d');
			}
			
			$where .= ' AND (createtime >= :start) AND (createtime < :end) ';
			$params[':start'] = strtotime($createtime['start']);
			$params[':end'] = strtotime($createtime['end']) + 86399;
			
			$sql = 'SELECT COUNT(*) FROM '.tablename($this->tb_order).$where;
			$total = pdo_fetchcolumn($sql, $params);
			$pager = pagination($total, $pageindex, $pagesize);
			
			$sql = 'SELECT * FROM '.tablename($this->tb_order)." {$where} ORDER BY id ASC LIMIT ".(($pageindex -1) * $pagesize).','. $pagesize;
			$orders = pdo_fetchall($sql, $params, 'id');
			
			load()->model('mc');
			$users = array();
			foreach ($orders as $key => &$order) {
				$order['items'] = pdo_fetchall('SELECT * FROM '.tablename($this->tb_item).' WHERE orderid=:orderid', array(':orderid'=>$key));
				
				if(empty($users[$order['uid']])){
					$users[$order['uid']] = mc_fetch($order['uid']);
				}
				
				$order['user'] = $users[$order['uid']];
			}
			unset($key);
			unset($order);
			
			load()->func('tpl');
			include $this->template('orders_display');
		}
	}
	
	/**
	 * 前端 - 订单管理
	 */
	public function doMobileOrders() {
		global  $_W, $_GPC;
		
		checkauth();
		
		$ops = array('delete', 'display');
		$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
		
		if($op == 'display'){
			
			$where = ' WHERE uid=:uid AND uniacid=:uniacid ';
			$params = array(
				':uid' => $_W['member']['uid'],
				':uniacid' => $_W['uniacid']
			);
			if(!isset($_GPC['status'])){
				$_GPC['status'] = 1;
			}
			$status = intval($_GPC['status']);
			if(in_array($status, array(1, 2))){
				$where .= ' AND status=:status ';
				$params[':status'] = $status;
			}
			
			$sn = $_GPC['sn'];
			if(!empty($sn)){
				$where .= ' AND sn LIKE :sn';
				$params[':status'] = "%{$sn}%";
			}
			
			$pageindex = max(1, intval($_GPC['page']));
			$pagesize = 2;
			$sql = 'SELECT COUNT(*) FROM '.tablename($this->tb_order). $where;
			$total = pdo_fetchcolumn($sql, $params);
			$total = intval($total);
			$pager = pagination($total, $pageindex, $pagesize,'', array('before' => 0, 'after' => 0));
			
			$sql = 'SELECT * FROM '.tablename($this->tb_order)." {$where} ORDER BY status asc, id DESC LIMIT ".(($pageindex-1)*$pagesize)." , ".$pagesize;
			$orders = pdo_fetchall($sql, $params);
			
			if (!empty($orders)) {
				foreach ($orders as &$order) {
					$order['items'] = pdo_fetchall('SELECT * FROM '.tablename($this->tb_item).' WHERE orderid=:orderid', array(':orderid'=>$order['id']));
				}
			}
			unset($order);
			
			include $this->template('orders');
		}
		
		if($op == 'delete'){
			
		}
	}
	
	/**
	 * 前端 - 订单付款
	 */
	public function doMobilePay(){
		global  $_W, $_GPC;
		
		checkauth();
		
		$ops = array('order');
		$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
		
		if($op == 'order'){
			
			$id = intval($_GPC['id']);
			if(!empty($id)){
				$order = pdo_fetch('SELECT * FROM '.tablename($this->tb_order).' WHERE id=:id ', array(':id'=>$id));
			}
			if (empty($id) || empty($order) || $order['uid'] != $_W['member']['uid'] || $order['uniacid'] != $_W['uniacid']) {
				message('付款链接错误, 未找到指定订单.','','error');
			}
			if(intval($order['status']) != 1){
				message('订单已付款.');
			}
			
			$items = pdo_fetchall('SELECT * FROM '.tablename($this->tb_item).' WHERE orderid=:id AND uniacid=:uniacid', array(':id'=>$id, ':uniacid'=>$_W['uniacid']));
			if(empty($items)){
				message('指定订单没有清单,无需付款.','','error');
			}
			
			$order['items'] = $items;
			
			$params['tid'] = $order['id'];
			$params['user'] = $_W['fans']['from_user'];
			$params['fee'] = $order['amount'];
			$params['title'] = $_W['account']['name'];
			$params['ordersn'] = $order['sn'];
			$params['virtual'] = false;
			
			include $this->template('pay');
			exit;
		}
	}

	/**
	 * 获取单品信息
	 * @param int $id
	 * @return array
	 */
	private function getGoods($id){
		global $_W;
		
		$sql = 'SELECT * FROM '.tablename($this->tb_goods).' WHERE id=:id AND uniacid=:uniacid ';
		$params = array(
			':id' => intval($id),
			':uniacid' => $_W['uniacid']
		);
		$goods = pdo_fetch($sql, $params);
		
		return  $goods;
	}
	
	/**
	 * 获取当前公号下所有单品信息
	 * @return array( id=>goods, ...)
	 */
	private function getGoodses(){
		global $_W;
	
		$sql = 'SELECT * FROM '.tablename($this->tb_goods).' WHERE uniacid=:uniacid';
		$params = array(
			':uniacid' => $_W['uniacid']
		);
		$goodses = pdo_fetchall($sql, $params, 'id');
	
		return  $goodses;
	}
	
	/**
	 * 获取商品在购物车中的记录
	 * 
	 * @param int $goodsid
	 * @return array cart struct
	 */
	private function getCartByGoodsid($goodsid){
		global $_W;
		
		$sql = 'SELECT * FROM '.tablename($this->tb_cart).' WHERE goodsid=:goodsid AND uid=:uid AND uniacid=:uniacid LIMIT 1';
		$params = array(
			':goodsid' => intval($goodsid),
			':uid' => $_W['fans']['from_user'],
			':uniacid' => $_W['uniacid']
		);
		$cart = pdo_fetch($sql, $params);
		
		return $cart;
	}
	
	/**
	 * 获取当前粉丝用户购物车中商品信息
	 * @param unknown $id
	 * @return Ambigous <mixed, boolean>
	 */
	private function getCart($id){
		global $_W;
	
		$sql = 'SELECT * FROM '.tablename($this->tb_cart).' WHERE id=:id AND uid=:uid AND uniacid=:uniacid LIMIT 1';
		$params = array(
			':id' => intval($id),
			':uid' => $_W['fans']['from_user'],
			':uniacid' => $_W['uniacid']
		);
		$cart = pdo_fetch($sql, $params);
	
		return $cart;
	}
	
	/**
	 * 获取当前粉丝用户购物车商品清单
	 * @return array array( $goodsid=> $cart, ...)
	 */
	private function getCarts(){
		global $_W;
		
		$sql = 'SELECT * FROM '.tablename($this->tb_cart).' WHERE uid=:uid AND uniacid=:uniacid ';
		$params = array(
			':uid' => $_W['fans']['from_user'],
			':uniacid' => $_W['uniacid']
		);
		$carts = pdo_fetchall($sql, $params, 'goodsid');
		
		return $carts;
	}
	
	/**
	 * 前端 - 购物车管理
	 */
	public function doMobileCart(){
		global $_W, $_GPC;
		$ops = array(
			'increase',	// +1
			'decrease',	// -1
			'display',
			'delete',
			'settle'	// 生成订单
		);
		$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
		
		if ($op == 'display') {
			
			$carts = $this->getCarts();
			$goodses = $this->getGoodses();
			
			include $this->template('cart');
		}
		
		if($op == 'delete'){
			$id = intval($_GPC['id']);
			if(!empty($id)){
				$cart = $this->getCart($id);
			}
			if(empty($id) || empty($cart)){
				message('删除失败, 未在购物车中找到指定商品.');
			}
			pdo_delete($this->tb_cart, array('id'=>$id));
			message('删除成功.',$this->createMobileUrl('cart', array('op'=>'display')),'success');
		}
		
		if($op == 'increase'){
			
			if (empty($_W['ispost']) || empty($_W['isajax'])) {
				exit('Access Denied.');
			}
			
			$goodsid = intval($_GPC['goodsid']);
			$cart = $this->getCartByGoodsid($goodsid);
			$quantity = 0;
			
			if(empty($cart)){
				$data = array(
					'goodsid'=>$goodsid, 
					'uid'=>$_W['fans']['from_user'], 
					'uniacid'=>$_W['uniacid'], 
					'quantity'=>1,
					'createtime' => TIMESTAMP
				);
				$ret = pdo_insert($this->tb_cart, $data);
				if($ret == 1){
					$quantity = 1;
				}
			} else {
				$quantity = intval($cart['quantity']);
				$ret = pdo_update($this->tb_cart, array('quantity'=> $quantity + 1), array('id'=>$cart['id']));
				if ($ret == 1) {
					$quantity++;
				}
			}
			exit($quantity);
		}
		
		if($op == 'decrease'){
			
			if (empty($_W['ispost']) || empty($_W['isajax'])) {
				exit('Access Denied.');
			}
			
			$goodsid = intval($_GPC['goodsid']);
			$cart = $this->getCartByGoodsid($goodsid);
			$quantity = 0;
			if(!empty($cart)){
				$num = intval($cart['quantity']);
				if($num > 1) {
					$ret = pdo_update($this->tb_cart, array('quantity'=> ($num - 1)), array('id'=>$cart['id']));
					if ($ret == 1) {
						$quantity = $num - 1;
					}
				} else {
					pdo_delete($this->tb_cart, array('id'=>$cart['id']));
				}
			}
			exit($quantity);
		}
		
		if($op == 'settle'){
			
			if(checksubmit()){
				
				checkauth();
				
				$cartids = $_GPC['cartids'];
				if(empty($cartids) || !is_array($cartids)) {
					message('请选择要结算的商品.', $this->createMobileUrl('cart', array('op'=>'display')));
				}
				foreach ($cartids as &$item) {
					$item = intval($item);
				}
				unset($item);
				
				$sql = 'SELECT * FROM '.tablename($this->tb_cart).' WHERE uniacid=:uniacid AND uid=:uid AND id in ( '.implode(',', $cartids).' )';
				$params = array(
					':uniacid' => $_W['uniacid'],
					':uid' => $_W['fans']['from_user']
				);
				$carts = pdo_fetchall($sql, $params, 'goodsid');
				if (empty($carts)){
					message('请选择要结算的商品.');
				}
				
				$goodsids = array_keys($carts);
				$sql = 'SELECT * FROM '.tablename($this->tb_goods).' WHERE uniacid=:uniacid  AND id in ( '.implode(',', $goodsids).' )';
				$params = array(
					':uniacid' => $_W['uniacid'],
				);
				$goodses = pdo_fetchall($sql, $params, 'id');
				
				$order = array(
					'uid' => $_W['member']['uid'],
					'uniacid' => $_W['uniacid'],
					'amount' => 0,
					'createtime' => TIMESTAMP,
					'status' => 1,
					'sn' => date('YmdHis').'-'.$_W['member']['uid']
				);
				
				foreach ($carts as $goodsid => $cart) {
					$goods = $goodses[$goodsid];
					if (empty($goods) || $goods['status'] != 2) {
						message("{$goods['name']} 已下架, 无法购买.");
					}
					$order['amount'] += $goods['price'] * $cart['quantity'];
				}
				
				pdo_insert($this->tb_order, $order);
				$order['id'] = pdo_insertid();
				
				foreach ($carts as $goodsid => $cart) {
					$goods = $goodses[$goodsid];
					
					$item = array();
					$item['orderid'] = $order['id'];
					$item['goodsid'] = $goodsid;
					$item['uniacid'] = $_W['uniacid'];
					$item['name'] = $goods['name'];
					$item['image'] = $goods['image'];
					$item['price'] = $goods['price'];
					$item['cost'] = $goods['cost'];
					$item['quantity'] = $cart['quantity'];
					
					pdo_insert($this->tb_item, $item);
				}
				
				pdo_delete($this->tb_cart, ' id IN ('.implode(',', $cartids).')');
				
				message('订单生成成功, 请付款.',$this->createMobileUrl('orders', array('op'=>'display')));
				
			} else {
				exit('Access Denied.');
			}
		}
	}
	
	public function doMobileTest(){
		global $_W, $_GPC;
		
		include $this->template('test');
	}
	
	public function getRuleMenus(){
		global $_W;
		return array(
				array(
						'title' => 'rulemenu1',
						'url'=>$this->createWebUrl('rulemenu1', $_W['account'])
				),
				array(
						'title' => 'rulemenu2',
						'url'=>$this->createWebUrl('rulemenu2', $_W['account'])
				)
		);
	}
	public function doWebRuleMenu1(){
		echo doWebRuleMenu1;
		global $_W;
		print_r($_W['user']);
	}
	
	public function doWebRuleMenu2(){
		echo doWebRuleMenu2;
		global $_W;
		print_r($_W['user']);
	}
	
	public function getMenus(){
		global $_W;
		return array(
				array(
						'title' => 'menu1',
						'url'=>$this->createWebUrl('menu1', array('uid'=>$_W['uid']))
				),
				array(
						'title' => 'menu2',
						'url'=>$this->createWebUrl('menu2', array('uid'=>$_W['uid']))
				)
		);
	}
	
	public function doWebMenu1(){
		echo doWebMenu1;
		global $_W;
		print_r($_W['user']);
	}
	
	public function doWebMenu2(){
		echo doWebMenu2;
		global $_W;
		print_r($_W['user']);
	}
	
	public function getHomes(){
		return array(
				array(
						'title' => 'home1',
						'url'=>$this->createMobileUrl('home1')
				),
				array(
						'title' => 'home2',
						'url'=>$this->createMobileUrl('home2')
				)
		);
	}
	
	public function doMobileHome1(){
		echo doMobileHome1;
		global $_W;
		print_r($_W['member']);
	}
	
	public function doMobileHome2(){
		echo doMobileHome2;
		global $_W;
		print_r($_W['member']);
	}
	
	public function getProfiles(){
		return array(
				array(
						'title' => 'Profile1',
						'url'=>$this->createMobileUrl('profile1')
				),
				array(
						'title' => 'Profile2',
						'url'=>$this->createMobileUrl('profile2')
				)
		);
	}
	
	public function doMobileProfile1(){
		echo doMobileProfile1;
		global $_W;
		print_r($_W['member']);
	}
	
	public function doMobileProfile2(){
		echo doMobileProfile2;
		global $_W;
		print_r($_W['member']);
	}
	
	public function getShortcuts(){
		return array(
				array(
						'title' => 'Shortcut1',
						'url'=>$this->createMobileUrl('shortcut1')
				),
				array(
						'title' => 'Shortcut2',
						'url'=>$this->createMobileUrl('shortcut2')
				)
		);
	}
	
	public function doMobileShortcut1(){
		echo doMobileShortcut1;
		global $_W;
		print_r($_W['member']);
	}
	
	public function doMobileShortcut2(){
		echo doMobileShortcut2;
		global $_W;
		print_r($_W['member']);
	}
	
	public function doMobileJssdk(){
		global $_W;
		include $this->template('jssdk');
	}
	
	public function doMobileWebuploader(){
		global $_W, $_GPC;
		$item = array(
			'header' => $_GPC['header'],
			'thumb' => $_GPC['thumb'],
		);
		
		load()->func('tpl');
		include $this->template('webuploader');
	}
	
	public function doMobileCheckauth(){
		global $_W, $_GPC;
	
		checkauth();
	
		echo 'doMobileCheckauth';
		exit;
	}
	
	public function doMobileChecksubmit(){
		global $_W, $_GPC;
		if (checksubmit('submit')) {
			$_GPC['issubmit'] = true;
		}
		include $this->template('checksubmit');
	}
	
	public function doMobileTpl(){
		global $_W;
		load()->func('tpl');
		include $this->template('tpl');
	}
	
	public function doMobileOauthuserinfo(){
		global $_W, $_GPC;
	
		load()->model('mc');
		
		// 假设当前应用必需会员头像
		$avatar = '';
	
		// 1 如果是会员(存在 uid),从会员信息中获取
		if (!empty($_W['member']['uid'])) {
			$member = mc_fetch($_W['member']['uid']);
			if (!empty($member)) {
				$avatar = $member['avatar'];
			}
		}
		
		// 2 如果存在 fans 记录, 从 fans 中获取
		if (empty($avatar)) {
			$fan = mc_fansinfo($_W['openid']);
			if (!empty($fan) && !empty($fan['tag'])) {
				$avatar = $fan['tag']['avatar'];
			}
		}
		
		//3 最后调用网页授权, 如果有权限
		if (empty($avatar)) {
			if (!empty($_W['oauth_account'])) {
				$userinfo = mc_oauth_userinfo();
				if (is_error($userinfo)) {
					// you code here
					message($ret['message']);
				} else {
					$avatar = $userinfo['tag']['avatar'];
				}
			} else {
				mc_require($_W['uid'], array('avatar'));
			}
		}
	
		echo "<img src='{$avatar}'>";
	}
}