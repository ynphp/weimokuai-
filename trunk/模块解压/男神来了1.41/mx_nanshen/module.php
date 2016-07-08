<?php
/**
 * 男神来了模块定义
 *
 * @author 毛线
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Mx_nanshenModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$settingdata = $_GPC['settings'];
			//var_dump($_GPC['settings']);die;
            if ($this->saveSettings($settingdata)) {
                message('保存成功', 'refresh');
            }
		}
		if (empty($_W['token'])) {
            $settings['s_title'] = '男神都约我了，你特码还不行动？';
            $settings['s_content'] = '看看吧？';
            $settings['s_img'] = $_W['siteroot'].'addons/mx_nanshen/icon.jpg';
        }
		//这里来展示设置项表单
		load()->func('tpl');
		include $this->template('setting');
	}

}