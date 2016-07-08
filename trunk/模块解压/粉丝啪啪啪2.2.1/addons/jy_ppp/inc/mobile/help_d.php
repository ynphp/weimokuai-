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
		$item = pdo_fetch("SELECT a.*,b.name as pname FROM " . tablename('jy_ppp_help') . " as a left join ".tablename('jy_ppp_help')." as b on a.parentid=b.id WHERE a.weid = ".$weid." AND a.id=".$id);
		include $this->template('help_d');