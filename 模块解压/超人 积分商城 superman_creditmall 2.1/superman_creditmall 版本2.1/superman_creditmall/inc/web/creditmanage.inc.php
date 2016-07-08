<?php
/**
 * 【超人】积分商城模块定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Superman_creditmall_doWebCreditmanage extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        @header('Location: '.url('mc/creditmanage'));
        exit;
    }
}

$obj = new Superman_creditmall_doWebCreditmanage;
$obj->exec();
