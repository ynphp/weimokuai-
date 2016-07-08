<?php
defined('IN_IA') or exit('Access Denied');
include 'define.php';
require_once(IA_ROOT . '/addons/quick_center/loader.php');

class quick_verifyModuleSite extends WeModuleSite
{
	public function doWebNewOrder()
	{
		global $_W, $_GPC;
		$this->doWebAuth();
		yload()->classs('quick_shop', 'order');
		yload()->classs('quick_shop', 'dispatch');
		yload()->classs('quick_shop', 'address');
		yload()->classs('quick_center', 'FormTpl');
		$_order = new Order();
		$_dispatch = new Dispatch();
		$_address = new Address();
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = 3;
			$goodstype = 2;
			$conds = array('status' => $status, 'goodstype' => $goodstype);
			if (isset($_GPC['search']) && !empty($_GPC['search'])) {
				yload()->classs('quick_shop', 'ordersearch');
				$_search = new OrderSearch();
				$conds[$_GPC['searchtype']] = trim($_GPC['search']);
				$pindex = 1;
				$psize = 100000;
				list($list, $total) = $_search->search($_W['weid'], $conds, null, $pindex, $psize);
				$pager = pagination($total, $pindex, $psize);
			} else {
				list($list, $total) = $_order->batchGet($_W['weid'], $conds, null, $pindex, $psize);
				$pager = pagination($total, $pindex, $psize);
			}
			if (!empty($list)) {
				foreach ($list as &$row) {
					!empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
					$row['dispatch'] = $_dispatch->get($row['dispatch']);
				}
				unset($row);
			}
			if (!empty($addressids)) {
				$address = $_address->batchGetByIds($_W['weid'], $addressids, 'id');
			}
		}
		include $this->template('neworder');
	}

	public function doWebVerifiedOrder()
	{
		global $_W, $_GPC;
		$this->doWebAuth();
		yload()->classs('quick_verify', 'verifiedorder');
		yload()->classs('quick_verify', 'clerk');
		yload()->classs('quick_center', 'FormTpl');
		yload()->classs('quick_shop', 'dispatch');
		yload()->classs('quick_shop', 'address');
		$_dispatch = new Dispatch();
		$_address = new Address();
		$_order = new VerifiedOrder();
		$_clerk = new Clerk();
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($op == 'display' or $op == 'display_settle') {
			yload()->classs('quick_verify', 'branchshop');
			$shops = BranchShop::batchGet($_W['weid']);
			$conds = array();
			if (isset($_GPC['shopid']) && !empty($_GPC['shopid'])) {
				$pindex = 1;
				$psize = 100000;
				$conds['shopid'] = intval($_GPC['shopid']);
			} else {
				$pindex = max(1, intval($_GPC['page']));
				$psize = 100;
			}
			if ($op == 'display') {
				$conds['settlestatus'] = 1;
			} else if ($op == 'display_settle') {
				$conds['settlestatus'] = 2;
			}
			list($list, $total) = $_order->batchGet($_W['weid'], $conds, $pindex, $psize, null);
			$pager = pagination($total, $pindex, $psize);
			$clerks = $_clerk->batchGet($_W['weid'], array(), 'clerk_openid');
		} else if ($op == 'settle') {
			if (checksubmit('batchsettleorder')) {
				foreach ($_GPC['orderid'] as $id) {
					$order = $_order->get($id);
					if (empty($order)) {
						continue;
					}
					$_order->update($_W['weid'], $id, array('settlestatus' => VerifiedOrder::$ORDER_SETTLED, 'settletime' => TIMESTAMP));
				}
				message('批量处理成功', referer(), 'success');
			}
		} else if ($op == 'stat') {
			$conds_scanned = array('settlestatus' => VerifiedOrder::$ORDER_SCANNED);
			$shop_scanned_list = $_order->getShopStat($_W['weid'], $conds_scanned);
			$goods_scanned_list = $_order->getGoodsStat($_W['weid'], $conds_scanned);
			$conds_settled = array('settlestatus' => VerifiedOrder::$ORDER_SETTLED);
			$shop_settled_list = $_order->getShopStat($_W['weid'], $conds_settled);
			$goods_settled_list = $_order->getGoodsStat($_W['weid'], $conds_settled);
		}
		include $this->template('verifiedorder');
	}

	public function doWebShop()
	{
		global $_GPC, $_W;
		$this->doWebAuth();
		yload()->classs('quick_center', 'FormTpl');
		yload()->classs('quick_verify', 'branchshop');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$exists = yload()->classs('xc_printer', 'printerdevice', true);
		if ($exists) {
			$printers = PrinterDevice::listDevice($_W['uniacid']);
		}
		if ($operation == 'display') {
			$shop = BranchShop::batchGet($_W['weid']);
			include $this->template('shop');
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$p = trim(str_replace(' ', '', $_GPC['printers']), ',');
				$data = array('weid' => $_W['weid'], 'remark' => $_GPC['remark'], 'shopname' => $_GPC['shopname'], 'printers' => $p, 'enabled' => intval($_GPC['enabled']), 'createtime' => TIMESTAMP);
				if (!empty($id)) {
					BranchShop::update($_W['weid'], $id, $data);
				} else {
					BranchShop::create($data);
				}
				message('更新门店信息成功！', $this->createWebUrl('shop', array('op' => 'display')), 'success');
			}
			if (!empty($id)) {
				$item = BranchShop::get($id);
			}
			include $this->template('shop');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$shop = BranchShop::get($id);
			if (empty($shop)) {
				message('抱歉，验单员不存在或是已经被删除！', $this->createWebUrl('shop', array('op' => 'display')), 'error');
			}
			BranchShop::remove($_W['weid'], $id);
			message('删除成功！该验单员如需获得验单权限，需重新通过微信页面注册。', $this->createWebUrl('shop', array('op' => 'display')), 'success');
		}
	}

	public function doWebClerk()
	{
		global $_GPC, $_W;
		$this->doWebAuth();
		yload()->classs('quick_center', 'FormTpl');
		yload()->classs('quick_verify', 'clerk');
		yload()->classs('quick_verify', 'branchshop');
		$_clerk = new Clerk();
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$clerk = $_clerk->batchGet($_W['weid']);
			include $this->template('clerk');
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$item = $_clerk->get($id);
			} else {
				message('不支持从后台创建验单员, 请验单员从手机端注册');
			}
			if (checksubmit('submit')) {
				$shopname = BranchShop::getShopname($_GPC['shopid']);
				$data = array('weid' => $_W['weid'], 'clerk_realname' => $_GPC['clerk_realname'], 'clerk_mobile' => $_GPC['clerk_mobile'], 'remark' => $_GPC['remark'], 'shopid' => $_GPC['shopid'], 'shopname' => $shopname, 'enabled' => intval($_GPC['enabled']),);
				if (!empty($id)) {
					$_clerk->update($_W['weid'], $id, $data);
					yload()->classs('quick_center', 'wechatapi');
					$_weapi = new WechatAPI();
					if (!empty($item['clerk_openid'])) {
						if ($data['enabled'] == true) {
							$text = '您好' . $_GPC['clerk_realname'] . '，您的验单员权限已经被授予。可以从微信端进入验单了。';
						} else {
							$text = '您好' . $_GPC['clerk_realname'] . '，您的验单员权限已经被收回。如果有任何疑问，请与店长联系。';
						}
						$_weapi->sendText($item['clerk_openid'], $text);
						WeUtility::logging('yandan' . $item['clerk_openid'], $text);
					}
				}
				message('更新验单员信息成功！', $this->createWebUrl('clerk', array('op' => 'display')), 'success');
			}
			$shopnames = $this->getShopname();
			include $this->template('clerk');
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$clerk = $_clerk->get($id);
			if (empty($clerk)) {
				message('抱歉，验单员不存在或是已经被删除！', $this->createWebUrl('clerk', array('op' => 'display')), 'error');
			}
			$_clerk->remove($_W['weid'], $id);
			message('删除成功！该验单员如需获得验单权限，需重新通过微信页面注册。', $this->createWebUrl('clerk', array('op' => 'display')), 'success');
		}
	}

	public function doMobileEntry()
	{
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'verify';
		$clerk_openid = $_W['fans']['from_user'];
		if (empty($clerk_openid)) {
			message('请从微信公众号进入');
		}
		yload()->classs('quick_verify', 'clerk');
		yload()->classs('quick_verify', 'verifiedorder');
		yload()->classs('quick_verify', 'branchshop');
		$_clerk = new Clerk();
		$clerk = $_clerk->find($_W['weid'], $clerk_openid);
		if (empty($clerk) && !empty($_GPC['type'])) {
			message('请交由店员扫码验证');
		} else if (empty($clerk)) {
			if (checksubmit()) {
				if (empty($_GPC['shopid'])) {
					message('程序已经升级，请管理员在后台处理升级后再进行本操作');
				}
				$shopname = BranchShop::getShopname($_GPC['shopid']);
				$data = array('clerk_openid' => $clerk_openid, 'clerk_realname' => $_GPC['clerk_realname'], 'clerk_mobile' => $_GPC['clerk_mobile'], 'shopid' => $_GPC['shopid'], 'shopname' => $shopname, 'remark' => $_GPC['remark'], 'weid' => $_W['weid'], 'enabled' => 0, 'createtime' => TIMESTAMP,);
				$id = $_clerk->create($data);
				if ($id > 0) {
					message('提交申请成功, 请等待审核通过', referer(), 'success');
				} else {
					message('提交申请失败, 请重试', referer(), 'success');
				}
			}
			$shopnames = $this->getShopname();
			include $this->template('clerk_register');
		} else if ($clerk['enabled'] == 0) {
			include $this->template('clerk_register');
		} else if ($operation == 'history') {
			$status = empty($_GPC['status']) ? VerifiedOrder::$ORDER_SCANNED : intval($_GPC['status']);
			$_verifiedorder = new VerifiedOrder();
			$conds = array('clerk_openid' => $_W['fans']['from_user'], 'settlestatus' => $status,);
			list($orders, $count) = $_verifiedorder->batchGet($_W['weid'], $conds);
			include $this->template('verify');
		} else if ($operation == 'print') {
			if (!empty($_GPC['orderid']) and !empty($clerk['id'])) {
				$this->printOrder($_GPC['orderid'], $clerk['id']);
				message('补打订单成功', murl('entry/module/entry', array('m' => 'quick_verify', 'op' => 'history')), 'success');
			} else {
				message('补打订单参数非法', murl('entry/module/entry', array('m' => 'quick_verify', 'op' => 'history')), 'error');
			}
		} else {
			if ((checksubmit()) or ($_GPC['type'] == 'qrscan')) {
				if (empty($_GPC['orderid'])) {
					$tmporders = pdo_fetchall('SELECT id,addressid FROM ' . tablename('quick_shop_order') . ' WHERE ordersn=:sn', array(':sn' => $_GPC['ordersn']));
					if (count($tmporders) == 1) {
						$_GPC['orderid'] = $tmporders[0]['id'];
						if (empty($_GPC['mobile'])) {
							$address = pdo_fetch('SELECT mobile FROM ' . tablename('quick_shop_address') . ' WHERE id=:id', array(':id' => $tmporders[0]['addressid']));
							$_GPC['mobile'] = $address['mobile'];
						}
					}
				}
				if (empty($_GPC['orderid']) or empty($_GPC['ordersn']) or empty($_GPC['mobile'])) {
					message('非法参数' . "{$_GPC['orderid']} - {$_GPC['ordersn']} - {$_GPC['mobile']}");
				}
				$ret = $this->getOrderInfo($_W['weid'], $_GPC['orderid'], $_GPC['ordersn'], $_GPC['mobile']);
				if (false == $ret) {
					message('订单信息不匹配:' . $_GPC['orderid']);
				}
				list($cnt, $order, $goodstitle, $status) = $ret;
				$orderid = $order['id'];
				if (intval($_GPC['orderid']) != $orderid) {
					message("订单ID不一致，请联系管理。id1:{$_GPC['orderid']} id2:{$orderid}");
				}
				if ($cnt > 1) {
					message('发现多个重名订单号，系统异常，请联系管理员');
				}
				if (empty($orderid)) {
					message('订单验证失败，请检查订单号是否输入正确，然后重试');
				}
				if ($order['paytype'] == 3) {
					message('订单验证失败。失败原因：这是一个货到付款的订单。仅在线支付成功的订单才能扫码验证。');
				}
				if ($order['paytype'] == Order::$PAY_DELIVERY) {
					message('订单验证失败。失败原因：货单付款订单不支持扫码验单。请在线支付方式。');
				}
				if ($order['paytype'] == Order::$PAY_ONLINE and empty($order['transid'])) {
					message('订单验证失败。失败原因：没有找到本订单的支付单号，请确认本订单是否实际在线支付成功。');
				}
				if ($status != Order::$ORDER_PAYED) {
					yload()->classs('quick_shop', 'order');
					$_order = new Order();
					if ($status > Order::$ORDER_PAYED) {
						$v = $this->getVerifyInfo($_W['weid'], $orderid);
						if (empty($v)) {
							message('该订单当前的状态是【' . $_order->getOrderStatusName($status, Goods::$VIRTUAL_GOODS) . '】，已经使用过了。但找不到验单信息。请通知管理员。orderid:' . $orderid);
						}
						message('该订单当前的状态是【' . $_order->getOrderStatusName($status, Goods::$VIRTUAL_GOODS) . '】。验单店铺【' . $v['shopname'] . '】，验单时间【' . date('m-d H:i:s', $v['createtime']) . '】，验单员【' . $v['clerk_realname'] . '】');
					} else {
						message('订单验证失败，该订单当前的状态是【' . $_order->getOrderStatusName($status, Goods::$VIRTUAL_GOODS) . '】，仅状态为【' . $_order->getOrderStatusName(Order::$ORDER_PAYED, Goods::$VIRTUAL_GOODS) . '】的订单能够参与验单');
					}
				}
				$this->confirm($_W['weid'], $orderid);
				$data = array('weid' => $_W['weid'], 'clerk_openid' => $clerk_openid, 'clerk_id' => $clerk['id'], 'orderid' => $orderid, 'shopid' => $clerk['shopid'], 'price' => $order['goodsprice'], 'ordersn' => $_GPC['ordersn'], 'mobile' => $_GPC['mobile'], 'title' => $goodstitle, 'createtime' => TIMESTAMP,);
				$_order = new VerifiedOrder();
				$id = $_order->create($data);
				if ($id > 0) {
					$this->printOrder($orderid, $clerk['id']);
					message('验证成功', murl('entry/module/entry', array('m' => 'quick_verify', 'op' => 'history')), 'success');
				} else {
					message('验证失败', '', 'error');
				}
			}
			include $this->template('verify');
		}
	}

	private function forceOpenInWechat()
	{
		if (DEVELOPMENT) {
			return;
		}
		yload()->classs('quick_center', 'wechatservice');
		$_weservice = new WechatService('quick_verify');
		$fakeopenid = $_weservice->forceOpenInWechat('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}

	public function doWebAuth()
	{
		global $_W, $_GPC;
		yload()->classs('quickauth', 'auth');
		$_auth = new Auth();
		$op = trim($_GPC['op']);
		$modulename = MODULE_NAME;
		$version = '0.60';
		$_auth->checkXAuth($op, $modulename, $version);
	}

	private function getShopname()
	{
		global $_W;
		yload()->classs('quick_verify', 'branchshop');
		$result = BranchShop::batchGet($_W['uniacid'], array('enabled' => 1), "id");
		return $result;
	}

	private function getOldShopname()
	{
		$shopnames = explode("\r", $this->module['config']['shopname']);
		$result = array();
		foreach ($shopnames as $line) {
			$tline = trim($line);
			if (!empty($tline)) {
				$result[] = $tline;
			}
		}
		return $result;
	}

	private function getOrderInfo($weid, $orderid, $ordersn, $mobile)
	{
		yload()->classs('quick_shop', 'ordersearch');
		$_search = new OrderSearch();
		return $_search->verifyById($weid, $orderid, array('ordersn' => $ordersn, 'mobile' => $mobile));
	}

	private function confirm($weid, $orderid)
	{
		if (empty($orderid)) {
			message('订单ID为空，请联系管理员处理，谢谢！', '', 'error');
		} else {
			yload()->classs('quick_shop', 'order');
			$_order = new Order();
			$data = array('status' => Order::$ORDER_RECEIVED,);
			$_order->update($weid, $orderid, $data);
			$this->notifyUser($weid, $orderid, 'notifyReceived');
		}
	}

	private function notifyUser($weid, $orderid, $method)
	{
		yload()->classs('quick_center', 'wechatsetting');
		$_setting = new WechatSetting();
		$setting = $_setting->get($weid, 'quick_shop');
		yload()->classs('quick_dynamics', 'messagequeue');
		$param = array('weid' => $weid, 'orderid' => $orderid, 'template_id' => $setting['payed_template_id']);
		WeUtility::logging('going to push to queue', $param);
		$mq = new MessageQueue();
		$mq->push('quick_shop', 'ordernotifier', 'OrderNotifier', $method, $param);
	}

	public function doWebDownloadOrder()
	{
		yload()->routing('quick_verify', 'download');
	}

	private function getVerifyInfo($weid, $orderid)
	{
		yload()->classs('quick_verify', 'verifiedorder');
		$_order = new VerifiedOrder();
		return $_order->getDetailed($weid, $orderid);
	}

	private function printOrder($orderid, $clerkid)
	{
		global $_W;
		yload()->classs('quick_shop', 'order');
		yload()->classs('quick_verify', 'branchshop');
		yload()->classs('quick_verify', 'clerk');
		$_order = new Order();
		$_shop = new BranchShop ();
		$_clerk = new Clerk();
		$order = $_order->get($orderid);
		$goods = $_order->getDetailedGoods($orderid);
		$clerk = $_clerk->get($clerkid);
		$shop = $_shop->get($clerk['shopid']);
		$shopTitle = mb_substr($shop['shopname'], 0, 30, 'utf-8');
		$goodsTitle = "名称           单价 数量  金额";
		$goodsList = $goodsTitle . "\n";
		foreach ($goods as $g) {
			$goodsList .= mb_substr($g['title'], 0, 6, 'utf-8') . '     ' . $g['ordergoodsprice'] . '  ' . $g['total'] . '  ' . ($g['ordergoodsprice'] * $g['total']) . "\n";
		}
		$totalPrice = $order['price'];
		$printTime = date('m-d H:i:s', TIMESTAMP);
		$text = $this->module['config']['printtemplate'];
		if (empty($text)) {
			$text = '请设置打印模板： 门店验单 - 参数设置 - 打印模板';
		}
		$parseMap = array("shopname" => $shopTitle, "goods" => $goodsList, "price" => $totalPrice, "time" => $printTime);
		yload()->classs('quick_center', 'textparser');
		$parser = new TextParser();
		$text = $parser->batchParseStr($parseMap, $text);
		if (!empty($shop['printers'])) {
			$exists = yload()->classs('xc_printer', 'printerdevice', true);
			if ($exists) {
				$p = trim(str_replace(' ', '', $shop['printers']), ',');
				$devIds = explode(',', $p);
				foreach ($devIds as $devId) {
					$devId = intval($devId);
					if ($devId > 0) {
						list($err_code, $err_msg) = PrinterDevice::doPrint($devId, $text);
						if ($err_code) {
							WeUtility::logging('printError', array($err_code, $err_msg, $devId, $text));
						}
					}
				}
			}
		}
	}

	public function doWebPrint()
	{
		global $_W, $_GPC;
		$this->printOrder($_GPC['orderid'], $_GPC['clerk_id']);
		message('打印完成');
	}

	public function doWebImportFromSetting()
	{
		global $_W;
		yload()->classs('quick_verify', 'branchshop');
		$shops = $this->getOldShopname();
		foreach ($shops as $shop) {
			$shop = trim($shop);
			$item = BranchShop::find($_W['uniacid'], $shop);
			if (!empty($item)) {
				continue;
			}
			$data = array('weid' => $_W['uniacid'], 'shopname' => $shop, 'enabled' => 1, 'createtime' => TIMESTAMP);
			$id = BranchShop::create($data);
			pdo_query("UPDATE " . tablename('quick_verify_clerk') . " SET shopid={$id} WHERE shopname='{$shop}'");
		}
		message('导入成功!', referer(), 'success');
	}
}