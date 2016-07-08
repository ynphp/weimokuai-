<?php
/**
 * 提现管理
 * 
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
$deposit_list = m('deposit')->get_depositAll();
include $this->template('web/deposit');
