<?php
global $_W, $_GPC;
$action = $_GPC['action'];
$optionId = $_GPC['oid'];
$tableName = $this->modulename . "_options";
$optionData = pdo_fetch("SELECT * FROM " . tablename($tableName) . " WHERE id= :oid", array(":oid" => $optionId));
if ($action == "send") {
    $threadId = $_GPC['tid'];
    $postData = array();
    $postData['touser'] = "";
    $tempArray = urlencodeForArray(json_decode(htmlspecialchars_decode($optionData['options']), true));
    switch ($optionData['type']) {
        case 6:
            $postData['msgtype'] = 'news';
            //$postData['news'] = urlencodeForArray(json_decode(htmlspecialchars_decode($optionData['options']), true));
            foreach ($tempArray['articles'] as $index => $val) {
                if (!preg_match("/^(http|https):/", urldecode($val['url']))) {
                    $tempArray['articles'][$index]['url'] = urlencode($_W['siteroot'] . "/app/") . $val['url'];
                }
                if (!preg_match("/^(http|https):/", urldecode($val['picurl']))) {
                    $tempArray['articles'][$index]['picurl'] = urlencode($_W['siteroot'] . "/attachment/") . $val['picurl'];
                }
            }
            $postData['news'] = $tempArray;
            break;
        case 7:
            $postData['msgtype'] = 'text';
            $postData['text'] = $tempArray;
            break;
    }
    $params = array(
        ":weid" => $optionData['weid'],
        ":tid" => $threadId,
        ":add_time" => $optionData['cache_name'],
        ":option_id" => $optionId,
    );
    $cacheData = pdo_fetch("SELECT * FROM " . tablename("zjl_mass_custom_msg_thread_cache") . " WHERE weid= :weid and tid = :tid and add_time = :add_time and option_id = :option_id", $params);

    //$threadIndex = $_GPC['index'] or exit("当前线程索引错误");

    $threadIndex = $cacheData['thread_index'] + 1;

    if (empty($cacheData)) {
        exit("未找到该线程缓存,请重新生成");
    }
    $fansList = json_decode($cacheData['options'], true);
    $postData['touser'] = $fansList['list'][$cacheData['thread_index']];
    $acc = WeAccount::create($optionData['weid']);
    $status = $acc->sendCustomNotice($postData); //测试
    $setSql = "thread_index = thread_index + 1 ";
    if ($status['errcode'] == 0) {
        //pdo_update("zjl_mass_custom_msg_thread_cache", array('success_count' => $cacheData['success_count'] + 1), $cacheData);
        $setSql.= ",success_count = success_count + 1 ";
        //pdo_update($tableName, array('success_count' => $optionData['success_count'] + 1), $optionData);
    } else {
        //var_dump($status);
    }

    $updateSql = "update " . tablename('zjl_mass_custom_msg_thread_cache') . " set " . $setSql . " where id=" . $cacheData['id'];
    pdo_run($updateSql);

    if ($threadIndex < $fansList['count']) {
        $targetUrl = $this->createWebUrl("sendProcess", array("action" => "send", "oid" => $optionId, "tid" => $threadId), true);
        $noticeStr = "线程{$threadId}，{$threadIndex}/{$fansList['count']}";
        $hasNext = true;
    } else {
        $noticeStr = "线程{$threadId}，已经发送完毕";
        $hasNext = false;
    }
    ?>
    <html>   
        <head>
            <title>正在发送...（预计发送：<?php echo $optionData['total'] ?>）</title>
            <?php if ($hasNext) {  //测试 ?>
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
