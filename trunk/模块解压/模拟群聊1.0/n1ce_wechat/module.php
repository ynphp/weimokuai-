<?php
/**
 * 模拟群聊模块定义
 *
 * @author n1ce   QQ：541535641
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class N1ce_wechatModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$cfg = array(
                'title' => $_GPC['title'],
				'desc' => $_GPC['desc'],
                'pic' => $_GPC['pic'],
                's_url' => $_GPC['s_url'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
		}
		
		include $this->template('setting');
	}

}