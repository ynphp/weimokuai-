<?php
/**
 * 【超人】积分商城模块
 *
 * @author 超人
 * @url
 */
define('SUPERMAN_CREDITMALL_MANUAL', 'http://www.kancloud.cn/supermanapp/creditmall/77902');
function superman_format_price($price, $showcut = false) {
    if ($showcut && $price > 10000) {
        $price = $price / 10000;
        $price .= '万';
    }
    return str_replace('.00', '', $price);
}

function superman_attachment_root() {
    global $_W;
    if (!defined('ATTACHMENT_ROOT')) {
        $path = IA_ROOT .'/attachment/';
        define('ATTACHMENT_ROOT', $path);
        return $path;
    }
    //兼容微擎0.6
    if (substr(ATTACHMENT_ROOT, -1, 1) != '/') {
        return ATTACHMENT_ROOT.'/';
    }
    return ATTACHMENT_ROOT;
}

function superman_img_placeholder($returnsrc = true) {
    global $_W;
    $src = $_W['siteroot']."addons/superman_creditmall/template/mobile/images/placeholder.jpg";
    if ($returnsrc) {
        return $src;
    } else {
        return "<img src='{$src}'/>";
    }
}

function superman_hide_mobile($mobile) {
    return preg_replace('/(\d{3})(\d{4})/', "$1****", $mobile);
}

function superman_hide_nickname($nickname, $suffix = '**') {
    return cutstr($nickname, 1).$suffix;
}

function superman_random_float($min = 0, $max = 1) {
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}

function superman_is_redpack($type) {
    return in_array($type, array(5, 6));
}

function superman_is_virtual($product) {
    if ($product['isvirtual'] == 1) {
        return true;
    }
    return false;
}

function superman_task_url($task) {
    global $_W;
    $url = '';
    if ($task['name'] == 'superman_sign') {
        if (!$task['extend']['rid']) {
            return ERRNO::TASK_NOT_BINDING;
        }
        $url = $_W['siteroot'].'app/'.$task['url'].'&rid='.$task['extend']['rid'].'&__from=taskcenter';
    } else if ($task['name'] == 'superman_creditmall_task6') {
        $url = $_W['account']['subscribeurl'];
    } else if ($task['builtin']) {
        $url = $_W['siteroot'].'app/'.$task['url'].'&__from=taskcenter';
    } else {
        $url = $_W['siteroot'].'app/'.$task['url'].'&__from=taskcenter';
    }
    return $url;
}

function superman_fix_path($path) {
    global $_W;
    $path = strpos($path, 'http://')!==false||strpos($path, 'https://')!==false?str_replace($_W['attachurl'], '', $path):$path;
    $path = strpos($path, 'http://')!==false||strpos($path, 'https://')!==false?str_replace($_W['siteroot'], '', $path):$path;
    return $path;
}