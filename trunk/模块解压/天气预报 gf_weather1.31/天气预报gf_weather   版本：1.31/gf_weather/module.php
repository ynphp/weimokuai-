<?php
/**
 * 天气预报模块定义
 *
 * @author gf
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Gf_weatherModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$data = array(
				'city'			=>	$_GPC['city'],
				'copyright'		=>	$_GPC['copyright'],
				'share_title'	=>	$_GPC['share_title'],
				'share_desc'	=>	$_GPC['share_desc'],
				'subscribe_url'	=>	$_GPC['subscribe_url'],
				'tongji'	=>	$_GPC['tongji'],
			);
			
			$this->saveSettings($data);
			
			message('配置参数更新成功！', referer(), 'success');
		}
		
		//require 'citylist2.inc.php';
		
		//$city_list = json_encode($city_list2);
		
		//这里来展示设置项表单
		include $this->template('setting');
	}

}