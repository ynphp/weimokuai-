<?php
defined('IN_IA') or exit('Access Denied');

class Wechat_dialogueModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_GPC, $_W;
		$renrenshopurl = "../addons/wechat_dialogue/";
		if(checksubmit()) {
			$cfg = array(
				'img' =>$_GPC['img'],	
					'title' =>$_GPC['title'],
						'desc' =>$_GPC['desc'],
							'uuu' =>$_GPC['uuu'],
								'kefuq1' =>$_GPC['kefuq1'],
								   'kefuq2' =>$_GPC['kefuq2'],
								     'kefuq3' =>$_GPC['kefuq3'],
									 	'kefutel' =>$_GPC['kefutel'],
			);
			if($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}
		if(!isset($settings['uuu'])) {
			$settings['uuu'] = 'http://www.baidu.com';
		}
		if(!isset($settings['title'])) {
			$settings['title'] = '待拓展功能';
		}
		if(!isset($settings['desc'])) {
			$settings['desc'] = '待拓展功能';
		}
		if(!isset($settings['kefuq1'])) {
			$settings['kefuq1'] = '123456789';
		}
		if(!isset($settings['kefuq2'])) {
			$settings['kefuq2'] = '223456789';
		}
		if(!isset($settings['kefuq3'])) {
			$settings['kefuq3'] = '323456789';
		}
		if(!isset($settings['kefutel'])) {
			$settings['kefutel'] = '8008008800';
		}
		include $this->template('setting');
	}

}
