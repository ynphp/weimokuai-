<?php
function get_config($uniacid = 0) {
	global $_W;
	$uniacid = intval($uniacid);
	if(!$uniacid) {
		$uniacid = $_W['uniacid'];
	}
	$data = pdo_fetch("SELECT * FROM " . tablename('str_config') . ' WHERE uniacid = :uniacid', array(':uniacid' => $uniacid));
	return $data;
}

function init_print_order($store_id, $order_id, $type = 'order'){
	$store = get_store($store_id, array('id', 'print_type'));
	if(empty($store)) {
		return error(-1, '商家不存在');
	}
	if($store['print_type'] == 1 && $type == 'order') {
		print_order($order_id);
		return true;
	}
	if($store['print_type'] == 2 && $type == 'pay') {
		print_order($order_id);
		return true;
	}
	return true;
}

function check_trash($sid, $uid = 0, $type = 'exit') {
	global $_W;
	$uid = intval($uid);
	if(!$uid) {
		$uid = $_W['member']['uid'];
	}
	$isexist = pdo_fetchcolumn("SELECT uid FROM " . tablename('str_user_trash') . ' WHERE uniacid = :uniacid AND sid = :sid AND uid = :uid', array(':uniacid' => $_W['uniacid'], ':sid' => $sid, ':uid' => $uid));
	if($type == 'exit') {
		if(!empty($isexist)) {
			message('您被添加到商户黑名单了,不能进行点餐', '', 'error');
		} else {
			return true;
		}
	} else {
		return $isexist;
	}
}
function pay_types() {
	 $pay_types = array(
		'' => '未支付',
		'alipay' => '支付宝支付',
		'wechat' => '微信支付',
		'credit' => '余额支付',
		'delivery' => '餐到付款',
		'cash' => '现金支付'
	);
	 return $pay_types;
}

//根据订单状态发送通知
function wechat_notice_status($id, $status) {
	$content['first']['value'] = "您好,您在{$store['title']}的外卖单马上送到";
	$content['keyword1']['value'] = "{$store['title']}";
	$content['keyword1']['color'] = '#ff510'; 
	$content['keyword2']['value'] = str_pad($order['id'], 6, '0', STR_PAD_LEFT);
	$content['keyword2']['color'] = '#ff510'; 
	$content['keyword3']['value'] = implode(', ', $arr);
	$content['keyword3']['color'] = '#ff510'; 
	$content['keyword4']['value'] = ($order['status'] > 2) ? '已付款' : '未付款';
	$content['keyword4']['color'] = '#ff510'; 
	$content['keyword5']['value'] = '门店服务员';
	$content['keyword5']['color'] = '#ff510'; 
	$content['remark']['value'] = '点击查看订单详情'; 
	$acc = WeAccount::create($store['notice_acid']);
	$url = $_W['siteroot'] . 'app'. ltrim($this->createMobileUrl('orderdetail', array('sid' => $order['sid'], 'id' => $order['id'])), '.');
	$acc->sendTplNotice($order['openid'], $store['delivery_tpl'], $content, $url, '#ff510');

}

function get_address($id) {
	global $_W;
	$data = pdo_fetch("SELECT * FROM " . tablename('str_address') . ' WHERE uniacid = :uniacid AND id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
	return $data;
}

//多门店公用
function get_addresses() {
	global $_W;
	$data = pdo_fetchall("SELECT * FROM " . tablename('str_address') . ' WHERE uniacid = :uniacid AND uid = :uid ORDER BY is_default DESC,id DESC', array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid']));
	return $data;
}

function get_default_address() {
	global $_W;
	$data = pdo_fetch("SELECT * FROM " . tablename('str_address') . ' WHERE uniacid = :uniacid AND uid = :uid AND is_default = 1', array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid']));
	if(empty($data)) {
		$data = pdo_fetch("SELECT * FROM " . tablename('str_address') . ' WHERE uniacid = :uniacid AND uid = :uid ORDER BY id DESC', array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid']));
	}
	return $data;
}

function get_mine_order() {
	global $_W;
	$data = pdo_fetchall("SELECT * FROM " . tablename('str_order') . ' WHERE uniacid = :uniacid AND uid = :uid ORDER BY id DESC', array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid']));
	return $data;
}

function set_order_cart($sid) {
	global $_W, $_GPC;
	if(!empty($_GPC['dish'])) {
		$num = 0;
		$price = 0;
		$ids_str = implode(',', array_keys($_GPC['dish']));
		$dish_info = pdo_fetchall('SELECT * FROM ' . tablename('str_dish') ." WHERE uniacid = :aid AND sid = :sid AND id IN ($ids_str)", array(':aid' => $_W['uniacid'], ':sid' => $sid), 'id');
		$grant_credit = 0;
		foreach($_GPC['dish'] as $k => $v) {
			$k = intval($k);
			$v = intval($v);
			if($k && $v) {
				$dishes[$k] = $v;
				$num += $v;
				$price += ($dish_info[$k]['price'] * $v); 
				$grant_credit += ($dish_info[$k]['grant_credit'] * $v); 
			}
		}
		$isexist = pdo_fetchcolumn('SELECT id FROM ' . tablename('str_order_cart') . " WHERE uniacid = :aid AND sid = :sid AND uid = :uid", array(':aid' => $_W['uniacid'], ':sid' => $sid, ':uid' => $_W['member']['uid']));
		$data = array(
			'uniacid' => $_W['uniacid'],
			'sid' => $sid,
			'uid' => $_W['member']['uid'],
			'num' => $num,
			'price' => $price,
			'grant_credit' => $grant_credit,
			'data' => iserializer($dishes),
			'addtime' => TIMESTAMP,
		);
		if(empty($isexist)) {
			pdo_insert('str_order_cart', $data);
		} else {
			pdo_update('str_order_cart', $data, array('uniacid' => $_W['uniacid'], 'id' => $isexist, 'uid' => $_W['member']['uid']));
		}
		$data['data'] = $dishes;
		return $data;
	} else {
		return error(-1, '菜品信息错误');
	}
	return true;
}

function get_order_cart($sid) {
	global $_W, $_GPC;
	$cart = pdo_fetch('SELECT * FROM ' . tablename('str_order_cart') . " WHERE uniacid = :aid AND sid = :sid AND uid = :uid", array(':aid' => $_W['uniacid'], ':sid' => $sid, ':uid' => $_W['member']['uid']));
	if(empty($cart)) {
		return array('num' => 0, 'price' => 0);
	}
	if((TIMESTAMP - $cart['addtime']) > 7*86400) {
		pdo_delete('str_order_cart', array('id' => $cart['id']));
	}
	$cart['data'] = iunserializer($cart['data']);
	return $cart;
}

function del_order_cart($sid) {
	global $_W;
	pdo_delete('str_order_cart', array('sid' => $sid, 'uid' => $_W['member']['uid']));
	return true;
}

function checkclerk($sid) {
	global $_W;
	$_W['is_manager'] = 0;
	$openid = trim($_W['openid']);
	$sid = intval($sid);
	if(empty($openid)) {
		return error(-1, '系统没有获取到您的身份');
	} 
	$clerk = pdo_fetch('SELECT * FROM ' . tablename('str_clerk') . ' WHERE uniacid = :aid AND openid = :openid AND sid = :sid AND is_sys = 1', array(':aid' => $_W['uniacid'], ':openid' => $openid, ':sid' => $sid));
	if(empty($clerk)) {
		return error(-1, '您没有权限进行订单管理');
	}
	$_W['is_manager'] = 1;
	return $clerk;
}

function get_store($id, $field = array()) {
	global $_W;
	$field_str = '*';
	if(!empty($field)) {
		$field_str = implode(',', $field);
	}
	$data = pdo_fetch("SELECT {$field_str} FROM " . tablename('str_store') . ' WHERE uniacid = :uniacid AND id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
	if(!empty($data['thumbs'])) {
		$data['thumbs'] = iunserializer($data['thumbs']);
	}
	return $data;
}


function get_order($id) {
	global $_W;
	$id = intval($id);
	$order = pdo_fetch('SELECT * FROM ' . tablename('str_order') . ' WHERE uniacid = :aid AND id = :id', array(':aid' => $_W['uniacid'], ':id' => $id));
	return $order;
}

/*
* $oid 订单id
* $cancel 是否需要返回已取消的订单
*/
function get_dish($oid, $cancel = false) {
	global $_W;
	$oid = intval($oid);
	$condition = '';
	if(!$cancel) {
		$condition = ' AND is_complete != 2';
	}
	$data = pdo_fetchall('SELECT * FROM ' . tablename('str_stat') . ' WHERE uniacid = :aid AND oid = :oid' . $condition, array(':aid' => $_W['uniacid'], ':oid' => $oid), 'dish_id');
	return $data;
} 

function get_clerks($sid) {
	global $_W;
	$data = pdo_fetchall("SELECT * FROM " . tablename('str_clerk') . ' WHERE uniacid = :uniacid AND sid = :sid', array(':uniacid' => $_W['uniacid'], ':sid' => $sid));
	return $data;
}

function get_clerk($id) {
	global $_W;
	$data = pdo_fetch("SELECT * FROM " . tablename('str_clerk') . ' WHERE uniacid = :uniacid AND id = :id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
	return $data;
}

function notice(){
					//给管理员和订餐人发送消息
				if(!empty($store['notice_acid'])) {
					$acc = WeAccount::create($store['notice_acid']);
					$clerks = pdo_fetchall('SELECT * FROM ' . tablename('str_clerk') . ' WHERE uniacid = :aid AND sid = :sid', array(':aid' => $_W['uniacid'], ':sid' => $sid));
				}

}
/*
* 打印订单
*/
function print_order($id, $force = false) {
	global $_W;
	$order= get_order($id);
	if(empty($order)) {
		return error(-1, '订单不存在');
	}
	if($order['print_nums'] >= 1 && !$force) {
		return error(-1, '已经打印过该订单');
	}
	$sid = intval($order['sid']);
	$store = get_store($order['sid'], array('title'));
	//获取该门店的所有打印机
	$prints = pdo_fetchall('SELECT * FROM ' . tablename('str_print') . ' WHERE uniacid = :aid AND sid = :sid AND status = 1', array(':aid' => $_W['uniacid'], ':sid' => $sid));
	if(empty($prints)) {
		return error(-1, '没有有效的打印机');
	}

	$num = 0;
	foreach($prints as $li) {
		if($li['type'] == 1) {
			$num += feie_print($order, $store, $li);
		} else {
			$num += hongx_print($order, $store, $li);
		}
	}

	if($num > 0) {
		pdo_query('UPDATE ' . tablename('str_order') . " SET print_nums = print_nums + {$num} WHERE uniacid = {$_W['uniacid']} AND id = {$order['id']}");
	} else {
		return error(-1,'发送打印指令失败。没有有效的打印机或没有开启打印机');
	}
	return true;
}

function feie_print($order, $store, $print_set) {
	global $_W, $_GPC;
	$pay_types = pay_types();
	if(empty($order['pay_type'])) {
		$order['pay_type'] = '未支付';
	} else {
		$order['pay_type'] = !empty($pay_types[$order['pay_type']]) ? $pay_types[$order['pay_type']] : '其他支付方式';
	}
	if(empty($order['delivery_time'])) {
		$order['delivery_time'] = '尽快送出';
	}
	$order['dish'] = get_dish($order['id']);
	$orderinfo = '';
	$orderinfo .= "<CB>{$store['title']}</CB>\n";
	if(!empty($print_set['print_header'])) {
		$orderinfo .= $print_set['print_header'] . '<BR>';
	}
	$orderinfo .= '名称　　　　　 单价  数量 金额<BR>';
	$orderinfo .= '--------------------------------<BR>';
	if(!empty($order['dish'])) {
		foreach($order['dish'] as $di) {
			$dan = ($di['dish_price'] / $di['dish_num']);
			$orderinfo .= str_pad(cutstr($di['dish_title'], 7), '21', '　', STR_PAD_RIGHT);
			$orderinfo .= ' ' . str_pad($dan, '6', ' ', STR_PAD_RIGHT);
			$orderinfo .= 'X ' . str_pad($di['dish_num'], '3', ' ', STR_PAD_RIGHT);
			$orderinfo .= ' ' . str_pad($di['dish_price'], '5', ' ', STR_PAD_RIGHT);
			$orderinfo .= '<BR>';
		}
	}
	$orderinfo .= '--------------------------------<BR>';
	$orderinfo .= "支付方式：{$order['pay_type']}<BR>";
	$orderinfo .= "合计：{$order['price']}元<BR>";
	if($order['order_type'] == 1) {
		$orderinfo .= "下单人：{$order['username']}<BR>";
		$orderinfo .= "联系电话：{$order['mobile']}<BR>";
		$orderinfo .= "桌号：{$order['table_num']}　　就餐人数：{$order['person_num']}人<BR>";
	} elseif($order['order_type'] == 2) {
		$orderinfo .= "配送费：{$order['delivery_fee']}<BR>";
		$orderinfo .= "下单人：{$order['username']}<BR>";
		$orderinfo .= "联系电话：{$order['mobile']}<BR>";
		$orderinfo .= "送餐地址：{$order['address']}<BR>";
		$orderinfo .= "送餐时间：{$order['delivery_time']}<BR>";
	}
	$orderinfo .= "下单时间：".date('Y-m-d H:i', $order['addtime'])."<BR>";
	if(!empty($order['note'])) {
		$orderinfo .='备注：' . $order['note'] . '<BR>';
	}
	if(!empty($print_set['qrcode_link'])) {
		$orderinfo .= "<QR>{$print_set['qrcode_link']}</QR>";
	}
	if(!empty($print_set['print_footer'])) {
		$orderinfo .= $print_set['print_footer'] . '<BR>';
	}
	$i = 0;
	if(!empty($print_set['print_no']) && !empty($print_set['key'])) {
		$wprint = new wprint();
		$status = $wprint->StrPrint($print_set['print_no'], $print_set['key'], $orderinfo, $print_set['print_nums']);
		if(!is_error($status)) {
			$i++;
			$data = array(
				'uniacid' => $_W['uniacid'],
				'sid' => $order['sid'],
				'pid' => $print_set['id'], //打印机id
				'oid' => $order['id'], //订单id
				'status' => 2,
				'foid' => $status, //飞蛾打印机下发的唯一打印编号
				'print_type' => 1, //打印机品牌
				'addtime' => TIMESTAMP
			);
			pdo_insert('str_order_print', $data);
		}
	}
	return $i;
}

function hongx_print($order, $store, $print_set) {
	global $_W, $_GPC;
	$i = 0;
	if(!empty($print_set['print_no'])){
		$data = array(
			'uniacid' => $_W['uniacid'],
			'sid' => $order['sid'],
			'pid' => $print_set['id'], //打印机id
			'oid' => $order['id'], //订单id
			'status' => 2, 
			'foid' => '', //飞蛾打印机下发的唯一打印编号
			'print_type' => 2, //打印机品牌
			'addtime' => TIMESTAMP
		);
		pdo_insert('str_order_print', $data);
		$i++;
	}
	return $i;
}

function hongx_print_echo($usr, $ord, $sgn){
	global $_W, $_GPC;
	$print_set = pdo_fetch('SELECT * FROM ' . tablename('str_print') . ' WHERE print_no = :no AND status = 1', array(':no' => $usr));
	if(empty($print_set)) return false;
	$_W['uniacid'] = $print_set['uniacid'];
	$order_print = pdo_fetch('SELECT * FROM ' . tablename('str_order_print') . ' WHERE pid = :pid AND status = 2 AND print_type = 2 ORDER BY addtime ASC', array(':pid' => $print_set['id']));
	if(empty($order_print)) return false;
	$order = get_order($order_print['oid']);
	if(empty($order)) return false;
	$sid = intval($order['sid']);
	$store = get_store($order['sid'], array('title'));
	$pay_types = pay_types();
	if(empty($order['pay_type'])) {
		$order['pay_type'] = '未支付';
	} else {
		$order['pay_type'] = !empty($pay_types[$order['pay_type']]) ? $pay_types[$order['pay_type']] : '其他支付方式';
	}
	$order['dish'] = get_dish($order['id']);
	if(!empty($order['dish'])) {
		foreach($order['dish'] as $di) {
			$dan = ($di['dish_price'] / $di['dish_num']);
			$orderinfo .= str_pad(cutstr($di['dish_title'], 7), '21', '　', STR_PAD_RIGHT);
			$orderinfo .= ' ' . str_pad($dan, '6', ' ', STR_PAD_RIGHT);
			$orderinfo .= 'X ' . str_pad($di['dish_num'], '3', ' ', STR_PAD_RIGHT);
			$orderinfo .= ' ' . str_pad($di['dish_price'], '5', ' ', STR_PAD_RIGHT);
			$orderinfo .= '<BR>';
		}
	}
	$orderinfo = '';
	$orderinfo = "%10{$store['title']} \n";
	if(!empty($print_set['print_header'])) {
		$orderinfo .= "%00{$print_set['print_header']}\n";
	}
	$orderinfo .= "%00名称			  数量  单价 \n";
	$orderinfo .= "%00----------------------------\n";
	if(!empty($order['dish'])) {
		foreach($order['dish'] as $di) {
			$dan = ($di['dish_price'] / $di['dish_num']);
			$orderinfo .= "%10" . stringformat($di['dish_title'], 19) . stringformat($di['dish_num'], 4, false) . stringformat($dan, 7, false) . "\n";
		}
	}
	$orderinfo .= "%00----------------------------\n";
	$orderinfo .= "%00支付方式：{$order['pay_type']}\n";
	$orderinfo .= "%00合计：{$order['price']}元\n";
	if($order['order_type'] == 1) {
		$orderinfo .= "%00下单人：{$order['username']}\n";
		$orderinfo .= "%00联系电话：{$order['mobile']}\n";
		$orderinfo .= "%00桌号：{$order['table_num']}　　就餐人数：{$order['person_num']}人\n";
	} elseif($order['order_type'] == 2) {
		$orderinfo .= "%00配送费：{$order['delivery_fee']}\n";
		$orderinfo .= "%00下单人：{$order['username']}\n";
		$orderinfo .= "%00联系电话：{$order['mobile']}\n";
		$orderinfo .= "%00送餐地址：{$order['address']}\n";
		$orderinfo .= "%00送餐时间：{$order['delivery_time']}";
	}
	$orderinfo .= "%00下单时间：".date('Y-m-d H:i', $order['addtime'])."\n";
	if(!empty($order['note'])) {
		$orderinfo .='%00备注：' . $order['note'] . '\n';
	}
	if(!empty($print_set['qrcode_link'])) {
		$orderinfo .= "%50SPL{$print_set['qrcode_link']}";
	}
	if(!empty($print_set['print_footer'])) {
		$orderinfo .= $print_set['print_footer'] . "\n";
	}
	$orderinfo = iconv("UTF-8", "GB2312//IGNORE", $orderinfo);
	$setting = '<setting>124:' . $print_set['print_nums'] . '|134:0</setting>';
	$setting = iconv("UTF-8", "GB2312//IGNORE", $setting);
	echo '<?xml version="1.0" encoding="GBK"?><r><id>' . $order_print['id'] . '</id><time>' . date('Y-m-d H:i:s', $order['addtime']) . '</time><content>' . $orderinfo . '</content>' . $setting . '</r>';
}

function stringformat($string, $length = 0, $isleft = true) {
	$substr = '';
	if ($length == 0 || $string == '') {
		return $string;
	}
	if (strlen($string) > $length) {
		for ($i = 0; $i < $length; $i++) {
			$substr = $substr . "_";
		}
		$string = $string . '%%' . $substr;
	} else {
		for ($i = strlen($string); $i < $length; $i++) {
			$substr = $substr . " ";
		}
		$string = $isleft ? ($string . $substr) : ($substr . $string);
	}
	return $string;
}

//微信邮件通知
function init_notice_order($sid, $id, $type = 'order') {
	global $_W;
	$store = get_store($sid, array('title', 'notice_acid', 'notice_type'));
	$order= get_order($id);
	if(empty($order)) {
		return error(-1, '订单不存在');
	}

	$orderid = str_pad($id, 8, '0', STR_PAD_LEFT);
	$pay_types = pay_types();
	if(empty($order['pay_type'])) {
		$order['pay_type'] = '未支付';
	} else {
		$order['pay_type'] = !empty($pay_types[$order['pay_type']]) ? $pay_types[$order['pay_type']] : '其他支付方式';
	}
	$order['dish'] = get_dish($order['id']);
	$clerks = pdo_fetchall('SELECT * FROM ' . tablename('str_clerk') . ' WHERE uniacid = :aid AND sid = :sid', array(':aid' => $_W['uniacid'], ':sid' => $sid), 'id');
	//给管理员和下单人发送消息
	if(!empty($store['notice_acid'])) {
		$acc = WeAccount::create($store['notice_acid']);
		if($type == 'order') {
			//后台设置的是使用模板消息通知（只对外卖订餐有效）
			if($store['notice_type'] == '2' && $order['order_type'] == 2) {
				if(!empty($clerks)) {
					if(!empty($store['store_tpl'])) {
						foreach($$order['dish'] as $di) {
							$arr[] = $di['dish_title'] . ' * ' . $di['dish_num'] . '份';
						}
						$content['first']['value'] = "{$order['username']}在{$store['title']}下单成功";
						$content['keyword1']['value'] = "{$order['price']}元 未支付";
						$content['keyword1']['color'] = '#ff510'; 
						$content['keyword2']['value'] = implode(', ', $arr);
						$content['keyword2']['color'] = '#ff510'; 
						$content['keyword3']['value'] = "{$order['username']}, {$order['mobile']}";
						$content['keyword3']['color'] = '#ff510'; 
						$content['keyword4']['value'] = "{$order['address']}";
						$content['keyword4']['color'] = '#ff510'; 
						if(empty($order['delivery_time'])) {
							$content['keyword5']['value'] = "尽快送出";
						} else {
							$content['keyword5']['value'] = date('Y年m月d日', time()) . $order['delivery_time'];
						}
						$content['keyword5']['color'] = '#ff510'; 
						if(!empty($order['note'])) {
							$content['remark']['value'] = $order['note'];
						}
						$content['remark']['color'] = '#ff510'; 
						foreach($clerks as $li) {
							$acc->sendTplNotice($li['openid'], $store['store_tpl'], $content);
						}
					}
					if(!empty($store['member_tpl'])) {
						$arr = array();
						foreach($order['dish'] as $di) {
							$arr[] = $di['dish_title'] . ' * ' . $di['dish_num'] . '份';
						}
						$content['first']['value'] = "您好,您在{$store['title']}的外卖单下单成功";
						$content['keyword1']['value'] = "{$store['title']}";
						$content['keyword1']['color'] = '#ff510'; 
						$content['keyword2']['value'] = date('Y-m-d H:i:s');
						$content['keyword2']['color'] = '#ff510'; 
						$content['keyword3']['value'] = implode(', ', $arr);
						$content['keyword3']['color'] = '#ff510'; 
						$content['keyword4']['value'] = $order['price'] . '元';
						$content['keyword4']['color'] = '#ff510'; 
						$url = $_W['siteroot'] . 'app'. ltrim($this->createMobileUrl('orderdetail', array('sid' => $order['sid'], 'id' => $id)), '.');
						$acc->sendTplNotice($_W['openid'], $store['member_tpl'], $content, $url, '#ff510');
					} 			
				}
			} else {
				$header = "【{$store['title']}】新订单通知\n";
				$orderinfo .= "订单编号：{$orderid}\n";
				$orderinfo .= '名称　　　　　　数量　单价\n';
				$orderinfo .= '--------------------\n';
				foreach($order['dish'] as $di) {
					$dan = ($di['dish_price'] / $di['dish_num']);
					$orderinfo .= istr_pad(cutstr($di['dish_title'], 8), 8, '　', STR_PAD_RIGHT);
					$orderinfo .= istr_pad($di['dish_num'], '3', '　', STR_PAD_RIGHT);
					$orderinfo .= istr_pad($dan, '6', ' ', STR_PAD_RIGHT);
					$orderinfo .= '\n';
				}
				$orderinfo .= '--------------------\n';
				$orderinfo .= "支付方式：{$order['pay_type']}\n";
				$orderinfo .= "合计：{$order['price']}元\n";
				if($order['order_type'] == 1) {
					$orderinfo .= "下单人：{$order['username']}\n";
					$orderinfo .= "联系电话：{$order['mobile']}\n";
					$orderinfo .= "桌号：{$order['table_num']}　　就餐人数：{$order['person_num']}人\n";
				} elseif($order['order_type'] == 2) {
					$orderinfo .= "配送费：{$order['delivery_fee']}\n";
					$orderinfo .= "下单人：{$order['username']}\n";
					$orderinfo .= "联系电话：{$order['mobile']}\n";
					$orderinfo .= "送餐地址：{$order['address']}\n";
					$orderinfo .= "送餐时间：{$order['delivery_time']}\n";
				}
				$orderinfo .= "下单时间：".date('Y-m-d H:i', $order['addtime'])."\n";
				if(!empty($order['note'])) {
					$orderinfo .='备注：' . $order['note'] . '\n';
				}

				if(!empty($clerks)) {
					$url = $_W['siteroot'] . 'app' . ltrim(murl('entry', array('do' => 'manage', 'op' => 'detail', 'm' => 'str_takeout', 'sid' => $order['sid'], 'id' => $order['id'])), '.');
					$footer = "\n<a href='{$url}'>点击查看订单详情</a>";
					$send['msgtype'] = 'text';
					$send['text'] = array('content' => urlencode($header.$orderinfo.$footer));
					foreach($clerks as $li) {
						if(!empty($li['openid'])) {
							$send['touser'] = trim($li['openid']);
							$status = $acc->sendCustomNotice($send);
						}
					}
				}

				if(!empty($order['openid'])) {
					$header = "【{$store['title']}】下单通知\n";
					$url = $_W['siteroot'] . 'app' . ltrim(murl('entry', array('do' => 'orderdetail', 'm' => 'str_takeout', 'sid' => $order['sid'], 'id' => $order['id'])), '.');
					$footer = "\n<a href='{$url}'>点击查看订单详情</a>";
					$send['msgtype'] = 'text';
					$send['text'] = array('content' => urlencode($header.$orderinfo.$footer));
					$send['touser'] = trim($order['openid']);
					$status = $acc->sendCustomNotice($send);
				}
			}
		}
	}
	if(!empty($clerks)) {
		$header = '';
		$orderinfo = '';
		load()->func('communication');
		if($type == 'order') {
			$header = "<h3>【{$store['title']}】新订单通知</h3> <br />";
			$orderinfo .= "订单编号：{$orderid}<br />";
			$orderinfo .= "订单详情：<br />";
			foreach ($order['dish'] as $row) {
				$orderinfo .= "名称：{$row['dish_title']} ，数量：{$row['dish_num']} 份<br />";
			}
			$orderinfo .= "支付方式：{$order['pay_type']}<br>";
			$orderinfo .= "合计：{$order['price']}元<br>";
			if($order['order_type'] == 1) {
				$orderinfo .= "下单人：{$order['username']}<br>";
				$orderinfo .= "联系电话：{$order['mobile']}<br>";
				$orderinfo .= "桌号：{$order['table_num']}　　就餐人数：{$order['person_num']}人<br>";
			} elseif($order['order_type'] == 2) {
				$orderinfo .= "配送费：{$order['delivery_fee']}<br>";
				$orderinfo .= "下单人：{$order['username']}<br>";
				$orderinfo .= "联系电话：{$order['mobile']}<br>";
				$orderinfo .= "送餐地址：{$order['address']}<br>";
				$orderinfo .= "送餐时间：{$order['delivery_time']}<br>";
			}
			$orderinfo .= "下单时间：".date('Y-m-d H:i', $order['addtime'])."<br>";
			if(!empty($order['note'])) {
				$orderinfo .='备注：' . $order['note'] . '<br>';
			}
			foreach($clerks as $clerk) {
				if(!empty($clerk['email'])) {
					ihttp_email($clerk['email'], '微外卖订单提醒', $header.$orderinfo);
				}
			}
		}
	}
	return true;
}

function wechat_notice($sid, $id, $status) {
	global $_W;
	load()->model('mc');
	$types_arr = array(
		'2' => 'handel', //处理中
		'3' => 'end', //已完成
		'4' => 'cancel',//已取消
		'5' => 'pay',//已支付
	);
	$type = $types_arr[$status];
	$store = get_store($sid, array('title', 'notice_acid'));
	$order = get_order($id);
	$orderid = str_pad($id, 8, '0', STR_PAD_LEFT);
	if(!$order['is_grant'] && $order['grant_credit'] > 0) {
		$log = array($order['uid'], "外卖模块订单赠送{$order['grant_credit']}积分。订单id:{$order['id']}");
		mc_credit_update($order['uid'], 'credit1', $order['grant_credit'], $log);
		pdo_update('str_order', array('is_grant' => 1), array('uniacid' => $_W['uniacid'], 'id' => $order['id']));
		$is_grant = 1;
	}
	$acc = WeAccount::create($store['notice_acid']);
	if($type == 'pay') {
		$pay_types = pay_types();
		$order['pay_type'] = $pay_types[$order['pay_type']];
		$clerks = get_clerks($sid);
		if(!empty($order['openid'])) {
			$header = "【{$store['title']}】付款通知\n";
			$orderinfo = "您的点餐订单，订单号【{$orderid}】已于" . date('Y-m-d H:i', time()) . "通过【{$order['pay_type']}】付款成功。";
			if($is_grant == 1) {
				$orderinfo .= "系统赠送您【{$order['grant_credit']}积分】，感谢您对我们的支持。";
			}
			$send['msgtype'] = 'text';
			$send['text'] = array('content' => urlencode($header.$orderinfo));
			$send['touser'] = trim($order['openid']);
			$status = $acc->sendCustomNotice($send);
		}
		if(!empty($clerks)) {
			load()->func('communication');
			foreach($clerks as $clerk) {
				$header = "【{$store['title']}】付款通知\n";
				$orderinfo = "订单号为【{$orderid}】的订单已于" . date('Y-m-d H:i', time()) . "通过【{$order['pay_type']}】付款成功";
				$send['msgtype'] = 'text';
				$send['text'] = array('content' => urlencode($header.$orderinfo));
				$send['touser'] = trim($clerk['openid']);
				$status = $acc->sendCustomNotice($send);
				if(!empty($clerk['email'])) {
					$header = "【{$store['title']}】付款通知<br>";
					$orderinfo = "订单号为【{$orderid}】的订单已于" . date('Y-m-d H:i', time()) . "通过【{$order['pay_type']}】付款成功";
					ihttp_email($clerk['email'], '微外卖付款通知', $header.$orderinfo);
				}
			}
		}
	}

	if($type == 'handel') {
		if(!empty($order['openid'])) {
			$header = "【{$store['title']}】订单进度通知\n";
			$orderinfo = "您的点餐订单，订单号【{$orderid}】已于" . date('Y-m-d H:i', time()) . "处理，店家正在配餐中。。。";
			$send['msgtype'] = 'text';
			$send['text'] = array('content' => urlencode($header.$orderinfo));
			$send['touser'] = trim($order['openid']);
			$status = $acc->sendCustomNotice($send);
		}
	}

	if($type == 'end') {
		if(!empty($order['openid'])) {
			$header = "【{$store['title']}】订单进度通知\n";
			$orderinfo = "您的点餐订单，订单号【{$orderid}】已于" . date('Y-m-d H:i', time()) . "处理完成";
			$send['msgtype'] = 'text';
			$send['text'] = array('content' => urlencode($header.$orderinfo));
			$send['touser'] = trim($order['openid']);
			$status = $acc->sendCustomNotice($send);
		}
	}

	if($type == 'cancel') {
		if(!empty($order['openid'])) {
			$header = "【{$store['title']}】订单进度通知\n";
			$orderinfo = "您的点餐订单，订单号【{$orderid}】已于" . date('Y-m-d H:i', time()) . "取消";
			$send['msgtype'] = 'text';
			$send['text'] = array('content' => urlencode($header.$orderinfo));
			$send['touser'] = trim($order['openid']);
			$status = $acc->sendCustomNotice($send);
		}
	}
	return true;
}

function istr_pad($input , $pad_length ,$pad_string , $pad_type = STR_PAD_RIGHT){
	$strlen = istrlen($input);
	if($strlen < $pad_length){
		$difference = $pad_length - $strlen;
		switch ($pad_type) {
			case STR_PAD_RIGHT:
				return $input . str_repeat($pad_string, $difference);
				break;
			case STR_PAD_LEFT:
				return str_repeat($pad_string, $difference) . $input;
				break;
			default:
				$left = $difference / 2;
				$right = $difference - $left;
				return str_repeat($pad_string, $left) . $input . str_repeat($pad_string, $right);
				break;
		}
	}else{
		return $input;
	}
}

function comment_stat($sid) {
	global $_W;
	$sid = intval($sid);
	$count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('str_order_comment') . ' WHERE uniacid = :uniacid AND sid = :sid AND status = 1', array(':uniacid' => $_W['uniacid'], ':sid' => $sid));
	$count = intval($count);
	$return = array(
		'total' => 0,
		'avg_taste' => 0.00,
		'avg_serve' => 0.00,
		'avg_speed' => 0.00,
	);
	if($count > 0) {
		$sum_taste = pdo_fetchcolumn("SELECT SUM(taste) FROM " . tablename('str_order_comment') . ' WHERE uniacid = :uniacid AND sid = :sid AND status = 1', array(':uniacid' => $_W['uniacid'], ':sid' => $sid));
		$sum_serve = pdo_fetchcolumn("SELECT SUM(serve) FROM " . tablename('str_order_comment') . ' WHERE uniacid = :uniacid AND sid = :sid AND status = 1', array(':uniacid' => $_W['uniacid'], ':sid' => $sid));
		$sum_speed = pdo_fetchcolumn("SELECT SUM(speed) FROM " . tablename('str_order_comment') . ' WHERE uniacid = :uniacid AND sid = :sid AND status = 1', array(':uniacid' => $_W['uniacid'], ':sid' => $sid));
		$return = array(
			'total' => $count,
			'avg_taste' => round($sum_taste/$count, 2),
			'avg_serve' => round($sum_serve/$count, 2),
			'avg_speed' => round($sum_speed/$count, 2),
		);
	}
	return $return;
}

function get_share($store) {
	global $_W;
	if(!is_array($store)) {
		$store = get_store($store, array('title', 'id', 'content', 'logo'));
	}
	$_share = array(
		'title' => $store['title'],
		'imgUrl' => tomedia($store['logo']),
		'desc' => $store['content'],
		'link' => $_W['siteroot'].'app'. ltrim(murl('entry', array('do' => 'dish', 'sid' => $store['id'], 'm' => 'str_takeout')), '.'),
	);
	return $_share;
}

function get_area() {
	global $_W;
	$lists = pdo_fetchall('SELECT * FROM ' . tablename('str_area') . ' WHERE uniacid = :uniacid ORDER BY displayorder DESC,id ASC', array(':uniacid' => $_W['uniacid']), 'id');
	return $lists;
}