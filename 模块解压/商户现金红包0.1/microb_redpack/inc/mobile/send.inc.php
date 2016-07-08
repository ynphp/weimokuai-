<?php
global $_W, $_GPC;

$user = $this->auth();
$activity = $this->getActivity(true, array('user' => $user));
if(is_error($activity)) {
    message($activity['message']);
}
$r = $this->send($user);
if(is_error($r)) {
    exit('领取红包失败. 请稍后重试, 或者联系我们的客服人员');
} else {
    exit('success');
}
