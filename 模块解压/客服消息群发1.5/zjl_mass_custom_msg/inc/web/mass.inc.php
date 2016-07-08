<?php

global $_W, $_GPC;
$action = $_GPC['action'];
$tableName = $this->modulename . "_options";
if ($_W['uniacid'] == "") {
    die("acount id error");
}
if ($action == "setMassOption") {
    if ($_W['isajax']) {
        if ($_GPC['acid'] == "") {
            die("acount id error");
        }
        $threadCount = intval($_GPC['thread_count']) or 10;
        $saveData = array();
        $returnArray = array();
        $saveData['uniacid'] = $_W['uniacid'];
        $saveData['weid'] = $_GPC['acid'];
        $saveData['add_time'] = time();
        $saveData['type'] = $_GPC['msgtype'];
        $saveData['thread_count'] = intval($threadCount);
        $saveData['options'] = htmlspecialchars_decode($_GPC['options']);
        $fansList = pdo_fetchall("SELECT openid FROM " . tablename("hsh_tools_interaction_time") . " where update_times >= unix_timestamp(now())-48*3600 and weid = :weid order by update_times asc", array(":weid" => $_GPC['acid']));
        $saveData['total'] = count($fansList);
        if (count($fansList) <= 0) {
            $returnArray['state'] = 0;
            $returnArray['msg'] = "当前无48小时内交互粉丝.";
            returnJSON($returnArray, "none");
            exit();
        }
        if (pdo_insert($this->modulename . "_options", $saveData)) {
            $insertId = pdo_insertid();
            $returnArray['state'] = 1;
            $returnArray['optionId'] = $insertId;
            $threadId = 1;
            $fileData = array();
            $threadDataCount = 0;
            $timeFlag = time();
            pdo_update($tableName, array('cache_name' => $timeFlag), array('id' => $insertId));
            foreach ($fansList as $index => $val) {
                //$val['openid'] = 'od8tRt2J8fp2QppgJcgSu2FLbblE'; // 测试
                if ($index < count($fansList) / $threadCount * $threadId) {
                    $fileData['list'][] = $val['openid'];
                    $threadDataCount++;
                } else {
                    $fileData['count'] = $threadDataCount;
                    $insertData = array(
                        "weid" => $_GPC['acid'],
                        "tid" => $threadId,
                        "add_time" => $timeFlag,
                        "option_id" => $insertId,
                        "options" => json_encode($fileData),
                        "success_count" => 0,
                        "total" => $threadDataCount,
                    );
                    pdo_insert("zjl_mass_custom_msg_thread_cache", $insertData);
                    $fileData['list'] = array($val['openid']);
                    $threadId++;
                    $threadDataCount = 1;
                }
                if ($index == count($fansList) - 1) {
                    $fileData['count'] = $threadDataCount;
                    $insertData = array(
                        "weid" => $_GPC['acid'],
                        "tid" => $threadId,
                        "add_time" => $timeFlag,
                        "option_id" => $insertId,
                        "options" => json_encode($fileData),
                        "success_count" => 0,
                        "total" => $threadDataCount,
                    );
                    pdo_insert("zjl_mass_custom_msg_thread_cache", $insertData);
                }
            }
        } else {
            $returnArray['state'] = 0;
            $returnArray['msg'] = "insert data error";
        }
        returnJSON($returnArray, "none");
    }
    exit();
}

load()->func('tpl');
$accounts = uni_accounts($_W['uniacid']);
if (!empty($accounts)) {
    $accdata = array();
    foreach ($accounts as $account) {
        if ($account['type'] == 1 && $account['type'] > 0) {
            $fansCount = pdo_fetch("SELECT count(id) as count FROM " . tablename("hsh_tools_interaction_time") . " where update_times >= unix_timestamp(now())-48*3600 and weid = :weid", array(":weid" => $account['acid']));
            $accdata[] = array('acid' => $account['acid'], 'name' => $account['name'], 'count' => $fansCount['count']);
        }
    }
}
include $this->template('mass');
//include $this->template('mass_test');



