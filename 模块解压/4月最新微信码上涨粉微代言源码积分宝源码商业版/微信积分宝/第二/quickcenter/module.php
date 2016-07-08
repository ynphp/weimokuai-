<?php
 defined('IN_IA') or exit('Access Denied');
include 'define.php';
require_once(IA_ROOT . '/addons/quickcenter/loader.php');
class QuickCenterModule extends WeModule{
    public function settingsDisplay($settings){
        global $_GPC, $_W;
        if(checksubmit()){
            $cfg = array('template' => trim($_GPC['template']), 'title' => trim($_GPC['title']), 'uplevelname' => trim($_GPC['uplevelname']),);
            if($this -> saveSettings($cfg)){
                message('保存成功', 'refresh');
            }
        }
        if (empty($settings['uplevelname'])){
            $settings['uplevelname'] = '上线';
        }
        if (empty($settings['template'])){
            $settings['template'] = 'pink';
        }
        if (empty($settings['title'])){
            $settings['title'] = '记账本';
        }
        yload() -> classs('quickcenter', 'FormTpl');
        yload() -> classs('quickcenter', 'dirscanner');
        $_scanner = new DirScanner();
        $dirs = $_scanner -> scan('quicktemplate/quickcenter');
        $template_items = array();
        foreach($dirs as $dir){
            $template_items[$dir] = $dir;
        }
        include $this -> template('setting');
    }
}
