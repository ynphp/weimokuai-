<?php
/**
 * 新智能聊天机器人模块定义
 *
 * @author n1ce   QQ：541535641
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class n1ce_moreModule extends WeModule {

public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$cfg = array(
					'win' => $_GPC['win'],
					'lose' => $_GPC['lose'],
					'draw' => $_GPC['draw'],
					'rule' => $_GPC['rule'],
					'ad' => $_GPC['ad'],
					'aurl' => $_GPC['aurl'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
		}
		include $this->template('setting');
	}


}