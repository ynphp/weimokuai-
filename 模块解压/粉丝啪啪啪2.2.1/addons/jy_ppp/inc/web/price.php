<?php
	global $_W, $_GPC;
	$weid = $_W ['uniacid'];
	checklogin ();
	$operation = ! empty ( $_GPC ['op'] ) ? $_GPC ['op'] : 'display';
	if ($operation == 'display') {
		if (! empty ( $_GPC ['displayorder'] )) {
			foreach ( $_GPC ['displayorder'] as $id => $displayorder ) {
				pdo_update ( 'jy_ppp_price', array ('displayorder' => $displayorder ), array ('id' => $id) );
			}
			message ( '价格设置更新成功！', $this->createWebUrl ( 'price', array ('op' => 'display') ), 'success' );
		}
		$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
		$category = pdo_fetchall ( "SELECT * FROM " . tablename ( 'jy_ppp_price' ) . " WHERE weid = '{$_W['weid']}' ORDER BY displayorder DESC,id ASC" );
		if (checksubmit ( 'submit2' )) {
			$sql = "
				INSERT INTO ".tablename('jy_ppp_price')." ( `weid`, `displayorder`, `fee`, `credit`, `baoyue`, `shouxin`, `log`, `description`, `enabled`) VALUES
				(".$weid.",	0,	30,	300,	0,	0,	1,	'',	1),
				(".$weid.",	0,	50,	600,	0,	0,	1,	'',	1),
				(".$weid.",	0,	100,	1300,	0,	0,	1,	'',	1),
				(".$weid.",	0,	50,	0,	30,	0,	2,	'',	1),
				(".$weid.",	0,	100,	0,	90,	0,	2,	'',	1),
				(".$weid.",	0,	10,	0,	0,	1,	3,	'',	1),
				(".$weid.",	0,	30,	0,	0,	3,	3,	'',	1),
				(".$weid.",	0,	50,	0,	0,	7,	3,	'',	1),
				(".$weid.",	0,	100,	0,	0,	15,	3,	'',	1);
			";
			pdo_query($sql);
	        message('更新信息设置成功！', $this->createWebUrl('price', array('op' => 'display')), 'success');
		}
		include $this->template ( 'web/price' );
	} elseif ($operation == 'post') {
		$id = intval ( $_GPC ['id'] );
		load()->func('tpl');
		if (! empty ( $id )) {
			$category = pdo_fetch ( "SELECT * FROM " . tablename ( 'jy_ppp_price' ) . " WHERE id = '$id'" );
		} else {
			$category = array ('displayorder' => 0,'enabled' => 1,'log' => 1);
		}
		$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
		if (checksubmit ( 'submit' )) {
			if (empty ( $_GPC ['fee'] )) {
				message ( '抱歉，请输入价格！' );
			}
			if (empty ( $_GPC ['credit'] ) && empty ( $_GPC ['baoyue'] ) && empty ( $_GPC ['shouxin'] )) {
				message ( '抱歉，请输入相对应的兑换数！' );
			}

			$data = array (
					'weid' => $_W ['weid'],
					'fee' => intval($_GPC ['fee']),
					'log' => intval($_GPC ['log']),
					'description' => $_GPC ['description'],
					'enabled' => intval ( $_GPC ['enabled'] ),
					'displayorder' => intval ( $_GPC ['displayorder'] )
			);
			if($data['log']==1)
			{
				if(empty($_GPC['credit']))
				{
					message("请输入相对应可兑换的数目");
				}
				$data['credit']=$_GPC['credit'];
			}
			if($data['log']==2)
			{
				if(empty($_GPC['baoyue']))
				{
					message("请输入相对应可兑换的天数");
				}
				$data['baoyue']=$_GPC['baoyue'];
			}
			if($data['log']==3)
			{
				if(empty($_GPC['shouxin']))
				{
					message("请输入相对应可兑换的天数");
				}
				$data['shouxin']=$_GPC['shouxin'];
			}
			if (! empty ( $id )) {
				pdo_update ( 'jy_ppp_price', $data, array ('id' => $id) );
			} else {
				pdo_insert ( 'jy_ppp_price', $data );
				$id = pdo_insertid ();
			}
			message ( '更新价格设置设置成功！', $this->createWebUrl ( 'price', array ('op' => 'display' ) ), 'success' );
		}
		include $this->template ( 'web/price' );
	} elseif ($operation == 'delete') {
		$id = intval ( $_GPC ['id'] );
		$category = pdo_fetch ( "SELECT id FROM " . tablename ( 'jy_ppp_price' ) . " WHERE id = '$id'" );
		if (empty ( $category )) {
			message ( '抱歉，价格设置设置不存在或是已经被删除！', $this->createWebUrl ( 'price', array ('op' => 'display') ), 'error' );
		}
		pdo_delete ( 'jy_ppp_price', array ('id' => $id) );
		message ( '价格设置设置删除成功！', $this->createWebUrl ( 'price', array ('op' => 'display') ), 'success' );
	}