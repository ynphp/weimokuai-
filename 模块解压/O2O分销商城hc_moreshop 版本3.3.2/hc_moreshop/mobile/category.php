<?php	$active = 1;	if(empty($_COOKIE[$ismobile]) || empty($_COOKIE[$ispwd])){		include $this->template('host/login');		exit;	}
	$host = pdo_fetch("select * from ".tablename('hc_moreshop_shophost')." where status = 1 and ischeck = 1 and weid = ".$weid." and mobile = '".trim($_COOKIE[$ismobile])."' and pwd = '".trim($_COOKIE[$ispwd])."'");
	if(empty($host)){		include $this->template('host/login');		exit;	}	if ($op == 'display') {
		if (!empty($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $id => $displayorder) {
				pdo_update('hc_moreshop_category', array('displayorder' => $displayorder), array('id' => $id));
			}
			message('分类排序更新成功！', $this->createMobileurl('category', array('op' => 'display')), 'success');
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM " . tablename('hc_moreshop_category') . " WHERE hid = ".$host['id']." and weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC");		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])) {
				$children[$row['parentid']][] = $row;
				unset($category[$index]);
			}
		}
		include $this->template('host/categorylist');
	} elseif ($op == 'post') {
		$parentid = intval($_GPC['parentid']);
		$id = intval($_GPC['id']);
		if (!empty($id)) {
			$category = pdo_fetch("SELECT * FROM " . tablename('hc_moreshop_category') . " WHERE id = '$id'");
		} else {
			$category = array(
				'displayorder' => 0,
			);
		}
		if (!empty($parentid)) {
			$parent = pdo_fetch("SELECT id, name FROM " . tablename('hc_moreshop_category') . " WHERE id = '$parentid'");
			if (empty($parent)) {
				message('抱歉，上级分类不存在或是已经被删除！', $this->createMobileurl('post'), 'error');
			}
		}
		if (checksubmit('submit')) {
			if (empty($_GPC['catename'])) {
				message('抱歉，请输入分类名称！');
			}
			$data = array(
				'weid' => $_W['uniacid'],
				'name' => $_GPC['catename'],				'hid' => $host['id'],				'thumb' => $_GPC['thumb'],
				'enabled' => intval($_GPC['enabled']),
				'displayorder' => intval($_GPC['displayorder']),
				'isrecommand' => intval($_GPC['isrecommand']),
				'description' => $_GPC['description'],
				'parentid' => intval($parentid),
			);
			if (!empty($id)) {
				unset($data['parentid']);
				pdo_update('hc_moreshop_category', $data, array('id' => $id));
			} else {
				pdo_insert('hc_moreshop_category', $data);
				$id = pdo_insertid();
			}
			message('更新分类成功！', $this->createMobileUrl('category', array('op' => 'display')), 'success');
		}
		include $this->template('host/categorypost');
	} elseif ($op == 'delete') {
		$id = intval($_GPC['id']);
		$category = pdo_fetch("SELECT id, parentid, thumb FROM " . tablename('hc_moreshop_category') . " WHERE id = '$id'");
		if (empty($category)) {
			message('抱歉，分类不存在或是已经被删除！', $this->createMobileurl('category', array('op' => 'display')), 'error');
		}
		pdo_delete('hc_moreshop_category', array('id' => $id, 'parentid' => $id), 'OR');		load()->func('file');		file_delete($category['thumb']);
		message('分类删除成功！', $this->createMobileurl('category', array('op' => 'display')), 'success');
	}
?>