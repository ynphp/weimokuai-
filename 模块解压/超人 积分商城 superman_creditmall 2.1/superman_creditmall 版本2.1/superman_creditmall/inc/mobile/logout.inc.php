<?php
/**
 * 【超人】积分商城模块定义
 *
 * @author 超人
 * @url
 */
defined('IN_IA') or exit('Access Denied');
class Superman_creditmall_doMobileLogout extends Superman_creditmallModuleSite {
    public function exec() {
        global $_W, $_GPC, $do;
        unset($_SESSION);
        session_destroy();
        isetcookie('logout', 1, 60);
        @header('Location: '.$this->createMobileUrl('home'));
        exit;
    }
}

$obj = new Superman_creditmall_doMobileLogout;
$obj->exec();
