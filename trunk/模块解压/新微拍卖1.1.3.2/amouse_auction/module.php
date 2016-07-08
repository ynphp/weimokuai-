<?php
/**
 * 拍卖模块定义
 * qq:214983937
 * url:w.mamani.cn
 */
defined('IN_IA') or exit('Access Denied');

class Amouse_auctionModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
        load()->func('tpl');
        $accounts = uni_accounts();
        foreach($accounts as $k => $li) {
            if($li['level'] < 3) {
                unset($li[$k]);
            }
        }
        if (checksubmit()) {
            $dat = array(
                'appid' => $_GPC['appid'],
                'secret' => $_GPC['secret'],
                'mchid' => $_GPC['mchid'],
                'shkey' => $_GPC['shkey'],
                'isblank'=>$_GPC['isblank'],
                'notice_acid' => intval($_GPC['notice_acid']),
                'delivery_tpl' => trim($_GPC['delivery_tpl']),
                'share_title' => $_GPC['share_title'],
                'share_image' => $_GPC['share_image'],
                'share_desc' => $_GPC['share_desc'],
            );
            if ($this->saveSettings($dat)) {
                message('保存成功', 'refresh');
            }
        }
		include $this->template('auction_setting');
	}

}