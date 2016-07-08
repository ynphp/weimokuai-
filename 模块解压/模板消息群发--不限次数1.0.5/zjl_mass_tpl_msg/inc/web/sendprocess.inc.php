<?php
global $_W, $_GPC;
$action = $_GPC['action'];
$optionId = $_GPC['oid'];
$tableName = $this->modulename . "_options";
$optionData = pdo_fetch("SELECT * FROM " . tablename($tableName) . " WHERE id= :oid", array(":oid" => $optionId));
if ($action == "send") {
    $threadId = $_GPC['tid'];
    $params = array(
        ":acid" => $optionData['acid'],
        ":tid" => $threadId,
        ":option_id" => $optionId,
    );

    $cacheData = pdo_fetch("SELECT * FROM " . tablename("zjl_mass_tpl_msg_thread_cache") . " WHERE acid= :acid and tid = :tid and option_id = :option_id", $params);
    if (empty($cacheData)) {
        exit("未找到该线程缓存,请重新生成");
    }
    $noticeStr = "线程{$threadId}，" . ($cacheData['success_count'] + 1) . "/{$cacheData['total']}";
    if ($cacheData['nextid'] > 0) {
        $fansInfo = pdo_fetchall("SELECT * FROM " . tablename("mc_mapping_fans") . " WHERE acid= :acid and follow = 1 and fanid >= {$cacheData['nextid']} and fanid <= {$cacheData['fanid_end']} order by fanid asc limit 0,2", array(":acid" => $cacheData['acid']));
        if (empty($fansInfo)) {
            exit("未找到粉丝信息,请重新生成");
        }
        $updateData = array();
        if (!empty($fansInfo[0]['openid'])) {
            //发送部分
            $result = $this->sendTplMsg($fansInfo[0]['openid'], $optionId);
            $updateData['success_count'] = $cacheData['success_count'] + 1;

        }

        $targetUrl = $this->createWebUrl("sendProcess", array("action" => "send", "oid" => $optionId, "tid" => $threadId), true);
        if (count($fansInfo) > 1) {
            $hasNext = true;
            $updateData['nextid'] = $fansInfo[1]['fanid'];
        } elseif (count($fansInfo) == 1) {
            $updateData['nextid'] = -1;
            $hasNext = true;
        } else {
            $noticeStr .= "，已经发送完毕";
            $hasNext = false;
        }
        $updateSql = "update " . tablename('zjl_mass_tpl_msg_thread_cache') . " set " . $setSql . " where id=" . $cacheData['id'];
        //pdo_run($updateSql);
        if (!empty($updateData)) {
            pdo_update("zjl_mass_tpl_msg_thread_cache", $updateData, array('id' => $cacheData['id']));
        }
        //更新进度
    } else {
        $noticeStr = "线程{$threadId}，" . ($cacheData['success_count']) . "/{$cacheData['total']}";
        $noticeStr .= "，已经发送完毕";
        $hasNext = false;
    }
    ?>
    <html>   
        <head>
            <title>正在发送...</title>
            <?php if ($hasNext) {  //测试  ?>
                <meta http-equiv="refresh" content="1;  
                      url=<?php echo $targetUrl; ?>" /> 
                  <?php } ?>
        </head>   
        <body>   
            <?php
            echo $noticeStr;
            //echo time();
            ?>
        </body> 
    </html>  
    <?php
    exit();
}
$threadUrlArray = array();
for ($threadId = 1; $threadId <= intval($optionData['thread_count']); $threadId++) {
    $threadUrlArray[] = $this->createWebUrl("sendProcess", array("action" => "send", "oid" => $optionId, "tid" => $threadId), true);
}
include $this->template('sendProcess');
