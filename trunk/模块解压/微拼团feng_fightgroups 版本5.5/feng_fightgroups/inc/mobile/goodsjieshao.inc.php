<?php
	global $_W,$_GPC;
	$this->getuserinfo();
	$id = $_GPC['id'];
	$goods = pdo_fetch("select *from".tablename('tg_goods')."where id='{$id}' and uniacid='{$_W['uniacid']}'");
	$share_data = $this -> module['config'];
	if($share_data['share_imagestatus']){
		if($share_data['share_imagestatus']==3){
			$shareimage = $share_data['share_image'];
		}elseif($share_data['share_imagestatus']==1){
			$shareimage = $goods['gimg'];
		}elseif($share_data['share_imagestatus']==2){
			$result = mc_fetch($_W['member']['uid'], array('credit1', 'credit2','avatar','nickname'));
			$shareimage = $result['avatar'];
		}
	}
	include $this->template('goodsjieshao');
?>
