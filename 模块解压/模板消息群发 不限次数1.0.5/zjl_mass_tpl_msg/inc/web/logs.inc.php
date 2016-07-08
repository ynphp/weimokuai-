<?php

global $_W, $_GPC;
$action = $_GPC['op'];
if ($action == 'delete') {
    $logId = $_GPC['id'];
    if ($logId > 0) {
        pdo_delete("zjl_mass_tpl_msg_options", array('id' => $logId));
        pdo_delete("zjl_mass_tpl_msg_thread_cache", array('option_id' => $logId));
    }
}
$pageindex = max(intval($_GPC['page']), 1); // 当前页码
$pagesize = 10; // 设置分页大小
$where = " WHERE uniacid = '{$_W['uniacid']}' ";
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('zjl_mass_tpl_msg_options') . $where);

$sql = "SELECT * FROM " . tablename("zjl_mass_tpl_msg_options") . " WHERE uniacid= :uniacid order by addtime desc limit " . ($pageindex - 1) * $pagesize . "," . $pagesize;
$optionsList = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
$pager = pagination($total, $pageindex, $pagesize);
$accounts = uni_accounts($_W['uniacid']);
$accList = array();
foreach ($accounts as $acc) {
    $accList[$acc['acid']] = $acc['name'];
}
include $this->template('logs');

