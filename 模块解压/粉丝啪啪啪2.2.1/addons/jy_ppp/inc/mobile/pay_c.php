<?php
global $_W,$_GPC;
		
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			echo "请用微信打开该页面！";
			exit;
		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];

			$op=$_GPC['op'];
			$fee=$_GPC['fee'];
			$mid=$_GPC['mid'];
			$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);

			$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);

			include $this->template('pay_c');
		}