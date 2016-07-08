<?php	
	if ($op == 'post') {
		$id = intval($_GPC['id']);
		if($id){
			$item = pdo_fetch("select * from ".tablename('hc_moreshop_coupons')." where id = ".$id);
		} else {
			$item['isopen'] = 1;
		}
		$datelimit = $_GPC['datelimit'];
		$starttime = strtotime($datelimit['start']);
		$endtime   = strtotime($datelimit['end']);
		
		$data = array(
			'uniacid'=>$weid,
			'title'=>$_GPC['title'],
			'credit'=>intval($_GPC['credit']),
			'number'=>$_GPC['number'],
			'thumb'=>$_GPC['thumb'],
			'type'=>intval($_GPC['type']),
			'displayorder'=>intval($_GPC['displayorder']),
			'discount'=>$_GPC['discount'],
			'description'=>htmlspecialchars_decode(trim($_GPC['description'])),
			'isopen'=>intval($_GPC['isopen']),
			'starttime'=>$starttime,
			'endtime'=>$endtime,
			'createtime'=>time(),
		);
		if ($_W['ispost']) {
			if (empty($id)) {
				pdo_insert('hc_moreshop_coupons',$data);
				message('添加成功',$this->createWebUrl('coupons',array('op' => 'display')),'success');
			}else{
				pdo_update('hc_moreshop_coupons',$data,array('id' => $id));
				message('更新成功',$this->createWebUrl('coupons',array('op' => 'display')),'success');
			}
		}
	} elseif ($op == 'display') {
		if(!empty($_GPC['displayorder'])){
			foreach ($_GPC['displayorder'] as $id => $displayorder) {
				pdo_update('hc_moreshop_coupons', array('displayorder' => $displayorder), array('id' => $id));
			}
			message('排序更新成功！', $this->createWebUrl('coupons', array('op' => 'display')), 'success');
		}
		$sql = "select * from ".tablename('hc_moreshop_coupons')."where uniacid = ".$weid." order by displayorder desc";
		$list = pdo_fetchall($sql);
	} elseif ($op == 'delete') {
		$id = intval($_GPC['id']);
		pdo_delete('hc_moreshop_coupons',array('id' => $id));
		message('删除成功',referer(),'success');
	} elseif ($op == 'memcoupons'){
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$members = pdo_fetchall("select id, mobile, realname from ".tablename('hc_moreshop_member')." where weid = ".$weid);
		$member = array();
		foreach($members as $c){
			$member[$c['id']]['realname'] = $c['realname'];
			$member[$c['id']]['mobile'] = $c['mobile'];
		}
		if(!empty($_GPC['realname']) || !empty($_GPC['mobile'])){
			$sort = array(
				'realname'=>$_GPC['realname'],
				'mobile'=>$_GPC['mobile']
			);
			//$shareid = pdo_fetchall("select id from ".tablename('hc_moreshop_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'");
			$mid = "select id from ".tablename('hc_moreshop_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'";
			$memcoupons = pdo_fetchall("select * from ".tablename('hc_moreshop_mycoupons'). " where uniacid = ".$_W['uniacid']." and mid in (".$mid.")");
		} else {
			$sql = "select * from ".tablename('hc_moreshop_mycoupons')."where uniacid = ".$weid." limit ".($pindex - 1) * $psize . ',' . $psize;
			$memcoupons = pdo_fetchall($sql);
			$total = pdo_fetchcolumn("select count(id) from ".tablename('hc_moreshop_mycoupons'). " where uniacid = ".$weid);
			$pager = pagination($total, $pindex, $psize);
		}
	} elseif ($op == 'coupondel') {
		$id = intval($_GPC['id']);
		pdo_delete('hc_moreshop_mycoupons',array('id' => $id));
		message('删除成功',referer(),'success');
	}
	include $this->template('coupons');
?>