<?php
/**
 * how-old模块微站定义
 *
 * @author 冯齐跃 158881551
 * @url http://www.wifixc.com
 */
defined('IN_IA') or exit('Access Denied');

class HowoldModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			$config = $_GPC['add'];
            if(!empty($_GPC['logo'])){
                $config['logo'] = $_GPC['logo'];
            }
            $share = $_GPC['share'];
            if(!empty($_GPC['share_imgUrl'])){
                $share['imgUrl'] = $_GPC['share_imgUrl'];
            }
			$this->saveSettings(array(
                'config'=>$config,
                'share'=>$share
            ));
            message('设置成功', 'referer', 'success');
		}
        load()->func('tpl');
        $config = $settings['config'];
        $share = $settings['share'];
		include $this->template('setting');
	}

}