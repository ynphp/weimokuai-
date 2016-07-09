<?php
/**
 * 通缉单身瞄模块定义
 *
 * @author
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_mojingModule extends WeModule {

  public function settingsDisplay($settings) {
    global $_W, $_GPC;
    //点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
    //在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
    if(checksubmit()) {
      //字段验证, 并获得正确的数据$dat
      $data = array(
        'btn_text' =>  $_GPC['btn_text'],
        'btn_url' =>  $_GPC['btn_url']
      );
      if($this->saveSettings($data)){
        message('保存成功', 'refresh');
      }
    }
    include $this->template('setting');
  }
}
