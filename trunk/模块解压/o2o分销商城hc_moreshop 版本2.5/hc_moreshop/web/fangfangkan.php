<?php	
	if (checksubmit('submit')) {
		$id = intval($_GPC['id']);
		$data = array(
			'uniacid'=>$weid,
			'level'=>intval($_GPC['level']),
			'easycredit'=>intval($_GPC['easycredit']),
			'normalcredit'=>intval($_GPC['normalcredit']),
			'hardcredit'=>intval($_GPC['hardcredit']),
			'gametime'=>intval($_GPC['gametime']),
			'gametimes'=>intval($_GPC['gametimes']),
			'showtime'=>intval($_GPC['showtime']),
			'isopen'=>intval($_GPC['isopen']),
			'createtime'=>time(),
		);
		if (empty($id)) {
			pdo_insert('hc_moreshop_fangfangkan',$data);
			message('提交成功',$this->createWebUrl('fangfangkan',array('op' => 'display')),'success');
		}else{
			unset($data['createtime']);
			pdo_update('hc_moreshop_fangfangkan',$data,array('id' => $id));
			message('提交成功',$this->createWebUrl('fangfangkan',array('op' => 'display')),'success');
		}
	}
	$item = pdo_fetch("select * from ".tablename('hc_moreshop_fangfangkan')." where uniacid = ".$weid);
	if(empty($item)){
		$item['isopen'] = 1;
	}
	include $this->template('fangfangkan');
?>