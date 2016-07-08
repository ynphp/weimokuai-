<?php

global $_GPC, $_W;

$op = $_GPC['op'] ? $_GPC['op'] : 'all';

if($op == 'all'){
    load()->func('file');
    rmdirs(IA_ROOT . '/addons/jer_da/cache', true);

    message('清除缓存成功！', '', 'success');
}