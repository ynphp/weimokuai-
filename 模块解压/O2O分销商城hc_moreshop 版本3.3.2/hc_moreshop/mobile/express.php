<?php
	$active = 4;
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
		$list = pdo_fetchall("SELECT * FROM " . tablename('hc_moreshop_express') . " WHERE hid = ".$host['id']." and weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
	} elseif ($op == 'post') {
		$id = intval($_GPC['id']);
		if (checksubmit('submit')) {
			if (empty($_GPC['express_name'])) {
				message('抱歉，请输入物流名称！');
			}
			$data = array(
				'weid' => $_W['uniacid'],
				'hid' => $host['id'],
				'displayorder' => intval($_GPC['express_name']),
				'express_name' => $_GPC['express_name'],
				'express_url' => $_GPC['express_url'],
				'express_area' => $_GPC['express_area'],
			);
			if (!empty($id)) {
				unset($data['parentid']);
				pdo_update('hc_moreshop_express', $data, array('id' => $id));
			} else {
				pdo_insert('hc_moreshop_express', $data);
				$id = pdo_insertid();
			}
			message('更新物流成功！', $this->createWeburl('express', array('op' => 'display')), 'success');
		}
		//修改
		$express = pdo_fetch("SELECT * FROM " . tablename('hc_moreshop_express') . " WHERE id = '$id' and weid = '{$_W['uniacid']}'");
	} elseif ($op == 'delete') {
		$id = intval($_GPC['id']);
		$express = pdo_fetch("SELECT id  FROM " . tablename('hc_moreshop_express') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
		if (empty($express)) {
			message('抱歉，物流方式不存在或是已经被删除！', $this->createWeburl('express', array('op' => 'display')), 'error');
		}
		pdo_delete('hc_moreshop_express', array('id' => $id));
		message('物流方式删除成功！', $this->createWeburl('express', array('op' => 'display')), 'success');
	} else {
		message('请求方式不存在');
	}
	include $this->template('host/express', TEMPLATE_INCLUDEPATH, true);
?>