<?php

global $_W, $_GPC;
$openid = $_GPC['openid'];
$optionId = $_GPC['oid'];
if ($openid != '') {
    $result = $this->sendTplMsg($openid, $optionId);
    if ($result === true) {
        echo @json_encode(array('errno' => 0));
    } else {
        echo @json_encode($result);
    }
    exit();
}
include $this->template('preview');



