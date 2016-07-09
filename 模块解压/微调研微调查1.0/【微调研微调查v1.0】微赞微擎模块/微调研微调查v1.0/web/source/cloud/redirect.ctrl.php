<?php 
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */

if(empty($_W['isfounder'])) {
	message('访问非法.');
}
$do = in_array($do, array('profile','callback')) ? $do : 'profile';
$authurl = 'http://v2.addons.012wz.com/web/index.php?c=auth&a=passwort';

$auth = array();
$auth['key'] = '';
$auth['password'] = '';
$auth['url'] = rtrim($_W['siteroot'], '/');
$auth['referrer'] = intval($_W['config']['setting']['referrer']);
$auth['version'] = IMS_VERSION;
if(!empty($_W['setting']['site']['key']) && !empty($_W['setting']['site']['token'])) {
	$auth['key'] = $_W['setting']['site']['key'];
	$auth['password'] = md5($_W['setting']['site']['key'] . $_W['setting']['site']['token']);
}

if($do == 'profile') {
	$auth['forward'] = 'profile';
	$iframe = __to($auth);
	$title = '注册站点';
}

if($do == 'callback') {
	$secret = $_GPC['token'];
	if(strlen($secret) == 32) {
		$cache = cache_read('cloud:auth:transfer');
		cache_delete('cloud:auth:transfer');
		if(!empty($cache) && $cache['secret'] == $secret) {
			$site = array_elements(array('key', 'token'), $cache);
			setting_save($site, 'site');
			$auth['key'] = $site['key'];
			$auth['password'] = md5($site['key'] . $site['token']);
			$auth['forward'] = 'profile';
			header('location: ' . __to($auth));
			exit();
		}
	}
	message('访问错误.');
}

template('cloud/frame');

function __to($auth) {
	global $authurl;
	$query = base64_encode(json_encode($auth));
	return $authurl . '&__auth=' . $query;
}
