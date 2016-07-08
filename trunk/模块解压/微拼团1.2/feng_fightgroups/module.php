<?php
/**
 * 拼团模块定义
 *
 * @author 甜筒君
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Feng_fightgroupsModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		load()->func('tpl');
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$dat = array(
				'status' => $_GPC['status'],
                'share_title' => $_GPC['share_title'],
                'share_image' => $_GPC['share_image'],
                'share_desc' => $_GPC['share_desc'],
                'url' => $_GPC['url'],
                'content' => htmlspecialchars_decode($_GPC['content'])
            );
			if ($this->saveSettings($dat)) {
                message('保存成功', 'refresh');
            }
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}

}