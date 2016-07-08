<?php

global $_W, $_GPC;
$action = $_GPC['action'];
$tableName = $this->modulename . "_options";
if ($_W['uniacid'] == "") {
    die("acount id error");
}
if ($action == 'groupdata') {
    if ($_W['isajax']) {
        $acid = intval($_GPC['acid']);
        $groups = pdo_fetch('SELECT * FROM ' . tablename('mc_fans_groups') . ' WHERE uniacid = :uniacid AND acid = :acid', array(':uniacid' => $_W['uniacid'], ':acid' => $acid));
        $groups = unserialize($groups['groups']) ? unserialize($groups['groups']) : array();
        if (empty($groups)) {
            exit(json_encode(array('status' => 'empty', 'message' => '该公众号还没有从公众平台获取粉丝分组')));
        } else {
            $html = '<option name="groupid" value="0">请选择粉丝分组</option><option value="-2" name="groupid">全部用户</option>';
            foreach ($groups as $group) {
                $html .= '<option name="groupid" data-num = "' . $group['count'] . '" value="' . $group['id'] . '">' . $group['name'] . '</option>';
            }
            exit(json_encode(array('status' => 'success', 'message' => $html)));
        }
    }
}
if (checksubmit("submit")) {
    $tplInfo = pdo_fetch("select * from " . tablename($this->modulename . "_templates") . " where uniacid=:uniacid and id=:tid", array(':uniacid' => $_W['uniacid'], ":tid" => $_GPC['tid']));
    if (empty($tplInfo)) {
        message("模板标识错误", "", "success");
    }

    $postData = array();
    $tags = unserialize($tplInfo['tags']);
    if (!empty($tags)) {
        foreach ($tags as $tag) {
            $postData[$tag] = array(
                'value' => htmlspecialchars_decode($_GPC[$tag . '-value']),
                'color' => $_GPC[$tag . '-color'],
            );
        }
    }
    $groupId = intval($_GPC['groupid']);
    if ($groupId == -2) {
        $groupSet = "";
    } else if ($groupId == -1) {
        $groupSet = " and groupid = 0";
    } else {
        $groupSet = " and groupid = {$groupId}";
    }

    $fansTotal = pdo_fetchcolumn("select count(*) from " . tablename("mc_mapping_fans") . " where acid=:acid and follow = 1 " . $groupSet, array(':acid' => $_GPC['acid']));
    $threadCount = intval($_GPC['thread_count']);

    $saveData = array(
        'uniacid' => $_W['uniacid'],
        'acid' => intval($_GPC['acid']),
        'tid' => $_GPC['tid'],
        'tpl_id' => $tplInfo['tpl_id'],
        'url' => $_GPC['url'],
        'topcolor' => $_GPC['topcolor'],
        'post_data' => iserializer($postData),
        'addtime' => TIMESTAMP,
        'thread_count' => $threadCount,
        'total' => $fansTotal
    );
    pdo_insert("zjl_mass_tpl_msg_options", $saveData);
    $optionId = pdo_insertid();
    //$optionId = 1; // 测试
    // var_dump($optionId);
    //插入主记录
    $threadSize = intval(ceil($fansTotal / $threadCount));
    for ($i = 1; $i <= $threadCount; $i++) {
        $fansIdList = pdo_fetchall("select fanid from " . tablename("mc_mapping_fans") . " where acid=:acid and follow = 1 {$groupSet} order by fanid asc limit " . ($i - 1) * $threadSize . "," . $threadSize, array(':acid' => $_GPC['acid']));
        $threadSaveData = array(
            'acid' => intval($_GPC['acid']),
            'tid' => $i,
            'option_id' => $optionId,
            'addtime' => TIMESTAMP,
            'success_count' => 0,
            'total' => count($fansIdList),
            'fanid_begin' => $fansIdList[0]['fanid'],
            'fanid_end' => $fansIdList[count($fansIdList) - 1]['fanid'],
            'nextid' => $fansIdList[0]['fanid'],
        );
        pdo_insert("zjl_mass_tpl_msg_thread_cache", $threadSaveData);
    }
    //设置线程
    message("创建成功，请在列表中启动群发", $this->createWebUrl('logs'), "success'");

    die();
}


load()->func('tpl');
$accounts = uni_accounts($_W['uniacid']);
if (!empty($accounts)) {
    $accdata = array();
    foreach ($accounts as $index => $account) {
        if ($account['level'] < 4) {
            unset($accounts[$index]);
        }
    }
}
$templates = pdo_fetchall("select * from " . tablename($this->modulename . "_templates") . " where uniacid=:uniacid order by id desc", array(':uniacid' => $_W['uniacid']));
if (!empty($templates)) {
    foreach ($templates as &$tpl) {
        $tpl['tags'] = implode(",", unserialize($tpl['tags']));
    }
    unset($tpl);
} else {
    message("请先添加模板", $this->createWebUrl('templates', array('op' => 'edit')), "warning");
}
include $this->template('mass');
//include $this->template('mass_test');



