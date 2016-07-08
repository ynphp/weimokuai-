<?php
global $_W, $_GPC;
$modulePublic = '../addons/microb_redpack/static/';

$activity = $this->module['config']['activity'];
if(empty($activity)) {
    message('活动还未开始, 敬请期待');
}
exit(htmlspecialchars_decode($activity['rules']));
