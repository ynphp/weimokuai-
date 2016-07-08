<?php
global $_W,$_GPC;
		$id=$_GPC['id'];
		$weid=$_W['uniacid'];
		$item = pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE id = ".$id);
		if (empty($item)) {
			message('抱歉，该用户不存在或是已经删除！', '', 'error');
		}
		pdo_update("jy_ppp_dianyuan",array('from_user'=>'','uid'=>0),array('id'=>$id));
		message('解除绑定成功！请绑定其他人！',$this->createWebUrl('dianyuan',array('op' => 'dianyuan')),'success');