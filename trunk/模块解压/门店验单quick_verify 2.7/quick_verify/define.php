<?php
global $_W;
if (!defined('quick_verify_INC')) {
	define('quick_verify_INC', 1);
	define('MODULE_NAME', 'quick_verify');
	define('APP_PHP', IA_ROOT . '/addons/quick_verify/');
	define('MODULE_ROOT', IA_ROOT . '/addons/');
	define('APP_WEB', IA_ROOT . '/addons/quick_verify/template/');
	define('APP_MOB', IA_ROOT . '/addons/quick_verify/template/mobile/');
	define('ATTACH_DIR', IA_ROOT . '/resource/attachment/');
	define('RES_CSS', $_W['siteroot'] . '../addons/quick_verify/css/');
	define('RES_JS', $_W['siteroot'] . '../addons/quick_verify/js/');
	define('RES_IMG', $_W['siteroot'] . '../addons/quick_verify/images/');
}