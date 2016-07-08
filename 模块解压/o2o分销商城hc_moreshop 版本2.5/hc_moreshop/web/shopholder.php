<?php
	// 店家列表
	if($op=='display'){
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("select * from ".tablename('hc_moreshop_shophost'). " where weid = ".$_W['uniacid']." ORDER BY id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
		$total = pdo_fetchcolumn("select count(id) from". tablename('hc_moreshop_shophost'). "where weid =".$_W['uniacid']);;
		$pager = pagination($total, $pindex, $psize);
	}

	if($op=='sort'){
		$sort = array(
			'realname'=>$_GPC['realname'],
			'mobile'=>$_GPC['mobile']
		);

		// 符合条件的店家
		$list = pdo_fetchall("select * from". tablename('hc_moreshop_shophost')."where weid =".$_W['uniacid'].".and realname like '%".$sort['realname']. "%' and mobile like '%".$sort['mobile']. "%' ORDER BY id DESC");
		$total = pdo_fetchcolumn("select count(id) from". tablename('hc_moreshop_shophost')."where weid =".$_W['uniacid'].".and realname like '%".$sort['realname']. "%' and mobile like '%".$sort['mobile']. "%' ORDER BY id DESC");
	}
	
	// 店家销商
	if($op=='delete'){
		$temp = pdo_delete('hc_moreshop_shophost', array('id'=>$_GPC['id']));
		if(empty($temp)){
			message('删除失败，请重新删除！', $this->createWebUrl('shopholder'), 'error');
		}else{
			message('删除成功！', $this->createWebUrl('shopholder'), 'success');
		}
	}
	
	// 店家详情
	if($op=='detail'){
		$id = $_GPC['id'];
		$shopholder = pdo_fetch("select * from ".tablename('hc_moreshop_shophost'). " where id = ".$id);
		include $this->template('shopholder_detail');
		exit;
	}
	
	// 设置店家权限
	if($op=='status'){
		$status = array(
			'status'=>$_GPC['status'],
			'ischeck'=>$_GPC['ischeck'],
			'content'=>trim($_GPC['content'])
		);
		$temp = pdo_update('hc_moreshop_shophost', $status, array('id'=>$_GPC['id']));
		if(empty($temp)){
			message('设置店家权限失败，请重新设置！', $this->createWebUrl('shopholder', array('op'=>'detail', 'id'=>$_GPC['id'])), 'error');
		}else{
			message('设置店家权限成功！', $this->createWebUrl('shopholder'), 'success');
		}
	}
	
	include $this->template('shopholder');
?>