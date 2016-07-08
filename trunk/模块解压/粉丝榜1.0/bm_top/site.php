<?php
/**
 * 粉丝榜模块处理程序
 *
 * 美丽心情
 * QQ:513316788 
 */
defined('IN_IA') or exit('Access Denied');
class bm_siteModuleSite extends WeModuleSite {
    public $weid;
    public function __construct() {
        global $_W;
        $this->weid = IMS_VERSION<0.6?$_W['weid']:$_W['uniacid'];
    }

}
?>