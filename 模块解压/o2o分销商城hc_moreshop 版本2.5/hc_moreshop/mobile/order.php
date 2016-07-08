<?php
	load()->func('tpl');
	$active = 3;
	if(empty($_COOKIE[$ismobile]) || empty($_COOKIE[$ispwd])){
		include $this->template('host/login');
		exit;
	}
	$host = pdo_fetch("select * from ".tablename('hc_moreshop_shophost')." where status = 1 and ischeck = 1 and weid = ".$weid." and mobile = '".trim($_COOKIE[$ismobile])."' and pwd = '".trim($_COOKIE[$ispwd])."'");
	if(empty($host)){
		include $this->template('host/login');
		exit;
	}
	if($_GPC['opp']=='output'){
		$conditions = array(
			'keyword'=>$_GPC['keyword'],
			'transid'=>$_GPC['transid'],
			'member'=>$_GPC['member'],
			'paytype'=>$_GPC['paytype'],
			'starttime'=>strtotime($_GPC['time']['start']),
			'endtime'=>strtotime($_GPC['time']['end']) + 86399
		);
		$url = $this->createMobileUrl('outputorder', array('conditions'=>$conditions));
		header("location:$url");
	}
	if ($op == 'display') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$status = $_GPC['status'];
		$sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
		$condition = '';
		if (empty($starttime) || empty($endtime)) {
			$starttime = strtotime('-1 month');
			$endtime = time();
		}
		if (!empty($_GPC['time'])) {
			$starttime = strtotime($_GPC['time']['start']);
			$endtime = strtotime($_GPC['time']['end']) + 86399;
			$condition .= " AND createtime >= ".$starttime." AND createtime <= ".$endtime;
			$paras[':starttime'] = $starttime;
			$paras[':endtime'] = $endtime;
		}
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND ordersn LIKE '%{$_GPC['keyword']}%'";
		}
		 if (!empty($_GPC['transid'])) {
			$condition .= " AND transid = '{$_GPC['transid']}'";
		}
		if (!empty($_GPC['member'])) {
			$addressids = pdo_fetchall("select id from ".tablename('hc_moreshop_address')." where weid = ".$_W['uniacid']." and realname LIKE '%{$_GPC['member']}%' or mobile LIKE '%{$_GPC['member']}%'");
			$addressid = 0;
			if(!empty($addressids)){
				foreach($addressids as $a){
					$addressid = $addressid.','.$a['id'];
				}
				$addressid = trim($addressid, ',');
			}
			$condition .= " AND addressid in (".$addressid.")";
		}
		if($_GPC['paytype'] !=-1){
			if (!empty($_GPC['paytype'])) {
				$condition .= " AND paytype = '{$_GPC['paytype']}'";
			} elseif ($_GPC['paytype'] === '0') {
				$condition .= " AND paytype = '{$_GPC['paytype']}'";
			}
		}
		if (!empty($_GPC['cate_2'])) {
			$cid = intval($_GPC['cate_2']);
			$condition .= " AND ccate = '{$cid}'";
		} elseif (!empty($_GPC['cate_1'])) {
			$cid = intval($_GPC['cate_1']);
			$condition .= " AND pcate = '{$cid}'";
		}

		if ($status != 0 || $status != '') {
			$condition .= " AND status = '" . intval($status) . "'";
		}
		$paytype = array (
				'0' => array('css' => 'default', 'name' => '未支付'),
				'1' => array('css' => 'danger','name' => '余额支付'),
				'2' => array('css' => 'info', 'name' => '在线支付'),
				'3' => array('css' => 'warning', 'name' => '货到付款')
		);
		if(!empty($_GPC['shareid'])){
			$shareid = $_GPC['shareid'];
			$orderid = pdo_fetchall("select orderid from ".tablename('hc_moreshop_memberrelative')." where shareid = ".$shareid." and weid = ".$_W['uniacid']);
			$orderids = '';
			if(empty($orderid)){
				$orderids = "(0)";
			} else {
				foreach($orderid as $o){
					$orderids = $orderids.$o['orderid'].',';
				}
				$orderids = '('.trim($orderids,',').')';
			}
			$condition .= " AND id in ". $orderids;
		}
		
		if (!empty($sendtype) && empty($_GPC['shareid'])) {
			$condition .= " AND sendtype = '" . intval($sendtype)."'";
		}

		$list = pdo_fetchall("SELECT * FROM " . tablename('hc_moreshop_order') . " WHERE hid = ".$host['id']." and weid = '{$_W['uniacid']}' $condition ORDER BY status ASC, createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hc_moreshop_order') . " WHERE hid = ".$host['id']." and weid = '{$_W['uniacid']}' $condition");
		$pager = pagination($total, $pindex, $psize);
		if(!empty($shareid)){
			if (!empty($list)) {
				foreach ($list as $key=>$l){
					$commission = pdo_fetch("select total, commission from ".tablename('hc_moreshop_memberrelative')." where shareid = ".$shareid." and orderid = ".$l['id']." and weid = ".$_W['uniacid']);
					$list[$key]['commission'] = $commission['commission'] * $commission['total'];
				}
			}
		}
		if (!empty($list)) {
			foreach ($list as &$row) {
				!empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
				$row['dispatch'] = pdo_fetch("SELECT * FROM " . tablename('hc_moreshop_dispatch') . " WHERE id = :id", array(':id' => $row['dispatch']));
			}
			unset($row);
		}
		if (!empty($addressids)) {
			$address = pdo_fetchall("SELECT * FROM " . tablename('hc_moreshop_address') . " WHERE id IN ('" . implode("','", $addressids) . "')", array(), 'id');
		}
	} elseif ($op == 'detail') {
		$id = intval($_GPC['id']);
		$memberrelative = pdo_fetchall("select commission, shareid from ".tablename('hc_moreshop_memberrelative')." where weid = ".$_W['uniacid']." and orderid = ".$id);
		$members = pdo_fetchall("select id, realname, from_user from ".tablename('hc_moreshop_member')." where weid = ".$_W['uniacid']." and status = 1");
		$member = array();
		foreach($members as $m){
			$member[$m['id']] = $m['realname'];
			$from_user[$m['id']] = $m['from_user'];
		}

		$item = pdo_fetch("SELECT * FROM " . tablename('hc_moreshop_order') . " WHERE id = :id", array(':id' => $id));
		if (empty($item)) {
			message("抱歉，订单不存在!", referer(), "error");
		}
		if (checksubmit('confirmsend')) {
			if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
				message('请输入快递单号！');
			}
		   // $item = pdo_fetch("SELECT transid FROM " . tablename('hc_moreshop_order') . " WHERE id = :id", array(':id' => $id));
			if (!empty($item['transid'])) {
				$this->changeWechatSend($id, 1);
			}
			pdo_update('hc_moreshop_memberrelative', array('flag'=>2), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
			pdo_update('hc_moreshop_order', array(
				'status' => 2,
				'remark' => $_GPC['remark'],
				'express' => $_GPC['express'],
				'expresscom' => $_GPC['expresscom'],
				'expresssn' => $_GPC['expresssn'],
			), array('id' => $id));
			$url = $_W['siteroot'].'app/'.$this->createMobileUrl('myorder', array('op'=>'detail', 'orderid'=>$id));
			sendGoodsSend($item['from_user'], $item['ordersn'], $_GPC['expresscom'], $_GPC['expresssn'], $url);
			message('发货操作成功！', referer(), 'success');
		}
		if (checksubmit('cancelsend')) {
			$item = pdo_fetch("SELECT transid FROM " . tablename('hc_moreshop_order') . " WHERE id = :id", array(':id' => $id));
			if (!empty($item['transid'])) {
				$this->changeWechatSend($id, 0, $_GPC['cancelreson']);
			}
			pdo_update('hc_moreshop_order', array(
				'status' => 1,
				'remark' => $_GPC['remark'],
					), array('id' => $id));
			message('取消发货操作成功！', referer(), 'success');
		}
		if (checksubmit('finish')) {
			pdo_update('hc_moreshop_order', array('status' => 3, 'remark' => $_GPC['remark']), array('id' => $id));
			pdo_update('hc_moreshop_memberrelative', array('flag'=>3), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
			pdo_update('hc_moreshop_credit', array('status'=>0), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
			foreach($memberrelative as $m){
			if(!empty($m['commission'])){
				$url = $_W['siteroot'].'app/'.$this->createMobileUrl('fansindex');
				sendCommWarm($from_user[$m['shareid']], $m['commission'], date('Y-m-d H:i:s', time()), $url);
			}
		}
			//$this->setOrderCredit($id, true);
			message('订单操作成功！', referer(), 'success');
		}
//            if (checksubmit('cancel')) {
//                pdo_update('hc_moreshop_order', array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
//                message('取消完成订单操作成功！', referer(), 'success');
//            }
		if (checksubmit('cancelpay')) {
			pdo_update('hc_moreshop_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
			pdo_update('hc_moreshop_memberrelative', array('flag'=>0), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
			//设置库存
			$this->setOrderStock($id, false);
			//减少积分
			$this->setOrderCredit($orderid, false);

			message('取消订单付款操作成功！', referer(), 'success');
		}
		if (checksubmit('confrimpay')) {
			pdo_update('hc_moreshop_order', array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
			pdo_update('hc_moreshop_memberrelative', array('flag'=>1), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
			//设置库存
			$this->setOrderStock($id);
			//增加积分
			$this->setOrderCredit($orderid);

			message('确认订单付款操作成功！', referer(), 'success');
		}
		if (checksubmit('close')) {
			$item = pdo_fetch("SELECT transid FROM " . tablename('hc_moreshop_order') . " WHERE id = :id", array(':id' => $id));
			if (!empty($item['transid'])) {
				$this->changeWechatSend($id, 0, $_GPC['reson']);
			}
			pdo_update('hc_moreshop_order', array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
			pdo_update('hc_moreshop_memberrelative', array('flag'=>-1), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
			message('订单关闭操作成功！', referer(), 'success');
		}
		if (checksubmit('open')) {
			pdo_update('hc_moreshop_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
			pdo_update('hc_moreshop_memberrelative', array('flag'=>0), array('orderid'=>$id, 'weid'=>$_W['uniacid']));
			message('开启订单操作成功！', referer(), 'success');
		}

		$dispatch = pdo_fetch("SELECT * FROM " . tablename('hc_moreshop_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
		if (!empty($dispatch) && !empty($dispatch['express'])) {
			$express = pdo_fetch("select * from " . tablename('hc_moreshop_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
		}
		$item['user'] = pdo_fetch("SELECT * FROM " . tablename('hc_moreshop_address') . " WHERE id = {$item['addressid']}");
		$goods = pdo_fetchall("SELECT g.id, g.title, g.status, g.goodssn, g.productsn, g.thumb, g.unit, g.marketprice,o.total,g.type,o.optionname,o.optionid,o.price as orderprice FROM " . tablename('hc_moreshop_order_goods') . " o left join " . tablename('hc_moreshop_goods') . " g on o.goodsid=g.id "
				. " WHERE o.orderid='{$id}'");
		$item['goods'] = $goods;

	}elseif ($op == 'delete') {
		/*订单删除*/
		$orderid = intval($_GPC['id']);
		pdo_delete("hc_moreshop_memberrelative", array('orderid'=>$orderid, 'weid'=>$_W['uniacid']));
		if (pdo_delete('hc_moreshop_order', array('id' => $orderid))) {
			message('订单删除成功', $this->createMobileUrl('order', array('op' => 'display')), 'success');
		} else {
			message('订单不存在或已被删除', $this->createMobileUrl('order', array('op' => 'display')), 'error');
		}
	}
	include $this->template('host/order');
?>