<?php

global $_W, $_GPC;
$openid = $_GPC['openid'];
$optionId = $_GPC['oid'];
if($openid != '') {
    $optionData = pdo_fetch("SELECT * FROM " . tablename($this->modulename . "_options") . " WHERE id= :oid", array(":oid" => $optionId));
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
    $postData['touser'] = $openid;
    $acc = WeAccount::create($optionData['weid']);
    $status = $acc->sendCustomNotice($postData); //测试
    if ($status['errcode'] == 0) {
        echo "1";
    } else {
        echo "0";
    }
    exit();
}
include $this->template('preview');



