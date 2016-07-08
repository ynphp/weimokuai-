<?php
/**
 * 拍卖模块定义
 *
 * @author 封遗
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Feng_auctionModule extends WeModule {


	public function settingsDisplay($settings) {
		global $_W, $_GPC;
        load()->func('tpl');
        
        if (checksubmit()) {
            $dat = array(
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
		include $this->template('setting');
	}

}