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
		$item = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_help') . " WHERE weid = '{$_W['weid']}' AND id=".$id);
		$list = pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_help')." WHERE weid=".$weid." AND parentid=".$id." AND enabled=1 ORDER BY displayorder DESC,id ASC");
		include $this->template('help_l');