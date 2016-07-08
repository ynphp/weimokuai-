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
		$list = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_help') . " WHERE weid = '{$_W['weid']}' AND parentid=0 AND enabled=1 ORDER BY displayorder DESC,id ASC");
		include $this->template('help');