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
include $this->template('zhuce');
