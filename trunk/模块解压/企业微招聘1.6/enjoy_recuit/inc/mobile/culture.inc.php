<?php
global $_W, $_GPC;
if(!empty($this->module['config']['appid'])&&!empty($this->module['config']['appsecret'])) {
	$this->auth();
}else{
	$user_agent  = $_SERVER['HTTP_USER_AGENT'];
	if (strpos($user_agent, 'MicroMessenger') === false) {
		die("本页面仅支持微信访问!非微信浏览器禁止浏览!");
	}
}
$uniacid=$_W['uniacid'];
//企业信息
$com=pdo_fetch("select * from ".tablename('enjoy_recuit_culture')." where uniacid = '{$_W['uniacid']}'");



include $this->template('culture');