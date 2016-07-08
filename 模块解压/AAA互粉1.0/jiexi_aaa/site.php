<?php

defined('IN_IA') or exit('Access Denied');
define('JX_ROOT', str_replace('\\\\', '/', dirname(preg_replace('@\(.*\(.*$@', '', __FILE__))));
require IA_ROOT.'/addons/jiexi_aaa/define.php';
require APP_PHP.'wechatutil.php';
require APP_PHP.'wechatapi.php';
require APP_PHP.'usermanager.php';
require APP_PHP.'wechatservice.php';
require_once APP_PHP.'responser.php';
class Jiexi_aaaModuleSite extends WeModuleSite {
    public
    function __construct() {}
    public
    function loadMod($class) {
        require_once JX_ROOT.'/mod/'.$class.'.mod.php';
    }
}
?>