<?php
/**
 * 图文天气模块定义
 *
 * @author n1ce   QQ：541535641
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class n1ce_picwetherModule extends WeModule {

public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$cfg = array(
                'ak' => $_GPC['ak'],
				'urls' => $_GPC['urls'],
                'pic' => $_GPC['pic'],      
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
		}
		if (empty($settings['ak'])) {
            $settings['ak'] = 'n1ce好帅';
        }
		if (empty($settings['urls'])) {
            $settings['urls'] = 'http://www.dwz.cn/Cv2Js';
        }
		include $this->template('setting');
	}



}