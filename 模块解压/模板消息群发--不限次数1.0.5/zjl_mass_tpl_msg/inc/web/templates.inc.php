<?php

global $_W, $_GPC;
$op = $_GPC['op'] == '' ? 'list' : $_GPC['op'];
$tableName = $this->modulename . "_templates";
if ($_W['uniacid'] == "") {
    die("acount id error");
}
$tid = max(0, intval($_GPC['tid']));
if ($op == 'edit') {
    if (checksubmit('submit')) {
        $templateInfo = pdo_fetch("select * from " . tablename($tableName) . " where uniacid=:uniacid and tpl_id=:tpl_id", array(':uniacid' => $_W['uniacid'], ":tpl_id" => $_GPC['tpl_id']));
        if (!empty($templateInfo)) {
            message("重复的消息模板", "", 'error');
        }
        $saveData = array(
            'uniacid' => $_W['uniacid'],
            'title' => trim($_GPC['title']),
            'tpl_id' => trim($_GPC['tpl_id']),
            'template' => trim(htmlspecialchars_decode($_GPC['template'])),
        );
        $tags = $this->getTplTags($saveData['template']);
        $saveData['tags'] = serialize($tags);
        if (empty($tags)) {
            message("模板信息填写错误，请重新从公众平处复制", "", 'error');
        }
        if ($tid > 0) {
            //update
            pdo_update($tableName, $saveData, array('id' => $tid));
        } else {
            //insert;
            pdo_insert($tableName, $saveData);
        }
        message("模板信息设置成功!", $this->createWebUrl('templates', array('op' => 'list')), 'success');
    }
    if ($tid > 0) {
        $templateInfo = pdo_fetch("select * from " . tablename($tableName) . " where uniacid=:uniacid and id=:tid", array(':uniacid' => $_W['uniacid'], ":tid" => $tid));
    }
}
if ($op == 'list') {
    $pageindex = max(intval($_GPC['page']), 1); // 当前页码
    $pagesize = 10; // 设置分页大小
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($tableName) . " where uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
    $templates = pdo_fetchall("select * from " . tablename($tableName) . " where uniacid=:uniacid order by id desc LIMIT " . ($pageindex - 1) * $pagesize . ",$pagesize", array(':uniacid' => $_W['uniacid']));
    $pager = pagination($total, $pageindex, $pagesize);
}
if ($op == 'delete') {
    $tid = intval($_GPC['tid']);
    $messageTemplate = pdo_fetch("SELECT id FROM " . tablename($tableName) . " WHERE id = {$tid}");
    if (empty($messageTemplate)) {
        message('抱歉，消息模板不存在或是已经被删除！', $this->createWebUrl('templates', array('op' => 'list')), 'error');
    }
    pdo_delete($tableName, array('id' => $tid));
    message('消息模板删除成功！', $this->createWebUrl('templates', array('op' => 'list')), 'success');
    die();
}
include $this->template('templates');
//include $this->template('mass_test');



