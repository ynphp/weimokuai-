<?php
/**
 * 微商圈
 *
 * 作者:迷失卍国度
 *
 * 联系qq : 15595755
 *
 * 未经许可，任何盗用代码行为都属于侵权
 */

defined('IN_IA') or exit('Access Denied');
include "../addons/businesscenter/model.php";

class businesscenterModule extends WeModule
{
    public $modulename = 'businesscenter';
    public $_debug = '1';

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if(checksubmit()) {
            $cfg = array();
            $cfg['appid'] = $_GPC['appid'];
            $cfg['secret'] = $_GPC['secret'];
            if($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('setting');
    }
}