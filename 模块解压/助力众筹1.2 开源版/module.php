<?php
/**
 * rebate模块定义
 *
 * @author ourteam
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Beauty_zhongchouModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		load()->func('tpl');
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
//			message($GPC['btn2']);exit;
			$dat = array(
                'share_title' => $_GPC['share_title'],
                'share_image' => $_GPC['share_image'],
                'share_desc' => $_GPC['share_desc'],
                'url' => $_GPC['url'],
                'share_xuanchuanimage'=> $_GPC['share_xuanchuanimage'],
                'content' => htmlspecialchars_decode($_GPC['content']),
                
                'p1-bg' => $_GPC['p1-bg'],
                'p1-slogn' => $_GPC['p1-slogn'],
                'p1-str' => $_GPC['p1-str'],
                'p1-person' => $_GPC['p1-person'],
                'p1-photo' => $_GPC['p1-photo'],
                'btn1' =>$_GPC['btn1'],
				
                'p2-bg' => $_GPC['p2-bg'],
                'p2-share-img' => $_GPC['p2-share-img'],
                'btn2' => $_GPC['btn2'],
                
				'p3-bg' => $_GPC['p3-bg'],
                'p3-slogn' => $_GPC['p3-slogn'],
                'p3-str' => $_GPC['p3-str'],
                'btn3' => $_GPC['btn3'],
                'btn4' => $_GPC['btn4'],
                
				'p4-bg' => $_GPC['p4-bg'],
                'p4-slogn' => $_GPC['p4-slogn'],
                'p4-btn-one' => $_GPC['p4-btn-one'],
                'p4-form' => $_GPC['p4-form'],
                'faq1' => $_GPC['faq1'],
                'faq2' => $_GPC['faq2'],
                'faq3' => $_GPC['faq3'],
                'faq4' => $_GPC['faq4'],
                'faq5' => $_GPC['faq5']
            );
			if ($this->saveSettings($dat)) {
                message('保存成功', 'refresh');
            }
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}

}