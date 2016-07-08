<?php
	$active = 5;
	if(empty($_COOKIE[$ismobile]) || empty($_COOKIE[$ispwd])){
		include $this->template('host/login');
		exit;
	}
	$host = pdo_fetch("select * from ".tablename('hc_moreshop_shophost')." where status = 1 and ischeck = 1 and weid = ".$weid." and mobile = '".trim($_COOKIE[$ismobile])."' and pwd = '".trim($_COOKIE[$ispwd])."'");
	if(empty($host)){
		include $this->template('host/login');
		exit;
	}
	if ($op == 'display') {
		$list = pdo_fetchall("SELECT * FROM " . tablename('hc_moreshop_dispatch') . " WHERE hid = ".$host['id']." and weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
	} elseif ($op == 'post') {

		$id = intval($_GPC['id']);
		if (checksubmit('submit')) {
			$data = array(
				'weid' => $_W['uniacid'],
				'hid' => $host['id'],
				'displayorder' => intval($_GPC['displayorder']),
				'dispatchtype' => intval($_GPC['dispatchtype']),
				'dispatchname' => $_GPC['dispatchname'],
				'express' => $_GPC['express'],
				'firstprice' => $_GPC['firstprice'],
				'firstweight' => $_GPC['firstweight'],
				'secondprice' => $_GPC['secondprice'],
				'secondweight' => $_GPC['secondweight'],
				'description' => $_GPC['description'],
				'enabled' => $_GPC['enabled']
			);
			if (!empty($id)) {
				pdo_update('hc_moreshop_dispatch', $data, array('id' => $id));
			} else {
				pdo_insert('hc_moreshop_dispatch', $data);
				$id = pdo_insertid();
			}
			message('更新配送方式成功！', $this->createMobileUrl('dispatch', array('op' => 'display')), 'success');
		}
		//修改
		$dispatch = pdo_fetch("SELECT * FROM " . tablename('hc_moreshop_dispatch') . " WHERE id = '$id' and weid = '{$_W['uniacid']}'");
		$express = pdo_fetchall("select * from " . tablename('hc_moreshop_express') . " WHERE hid = ".$host['id']." and weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
	} elseif ($op == 'delete') {
		$id = intval($_GPC['id']);
		$dispatch = pdo_fetch("SELECT id  FROM " . tablename('hc_moreshop_dispatch') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
		if (empty($dispatch)) {
			message('抱歉，配送方式不存在或是已经被删除！', $this->createMobileUrl('dispatch', array('op' => 'display')), 'error');
		}
		pdo_delete('hc_moreshop_dispatch', array('id' => $id));
		message('配送方式删除成功！', $this->createMobileUrl('dispatch', array('op' => 'display')), 'success');
	} else {
		message('请求方式不存在');
	}
	include $this->template('host/dispatch', TEMPLATE_INCLUDEPATH, true);
?>