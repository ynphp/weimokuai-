<?php

//www.012wz.com 
if (!defined('IN_IA')) {
    exit('Access Denied');
}
define('EWEI_SHOP_DEBUG', false);
!defined('EWEI_SHOP_PATH') && define('EWEI_SHOP_PATH', IA_ROOT . '/addons/ewei_shop/');
!defined('EWEI_SHOP_CORE') && define('EWEI_SHOP_CORE', EWEI_SHOP_PATH . 'core/');
!defined('EWEI_SHOP_PLUGIN') && define('EWEI_SHOP_PLUGIN', EWEI_SHOP_PATH . 'plugin/');
!defined('EWEI_SHOP_INC') && define('EWEI_SHOP_INC', EWEI_SHOP_CORE . 'inc/');
!defined('EWEI_SHOP_URL') && define('EWEI_SHOP_URL', $_W['siteroot'] . 'addons/ewei_shop/');
!defined('EWEI_SHOP_STATIC') && define('EWEI_SHOP_STATIC', EWEI_SHOP_URL . 'static/');
!defined('EWEI_SHOP_PREFIX') && define('EWEI_SHOP_PREFIX', 'ewei_shop_');
