<?php
global $_W,$_GPC;

		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];
		}


		$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
		$id=$_GPC['id'];
		$qt=1;
		$item = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_safe') . " WHERE weid = '{$_W['weid']}' AND id=".$id);
		$parent=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_safe')." WHERE weid=".$weid." AND id=".$item['parentid']." AND enabled=1");
		$list = pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_safe')." WHERE weid=".$weid." AND parentid=".$item['parentid']." AND enabled=1 AND id!=".$id." ORDER BY displayorder DESC,id ASC");
		include $this->template('safe_d');