<?php
/**
 * Timfan design模块处理程序
 *
 * @author Tim Fan
 * QQ:1026073477
 * @url http://i-fanr.com/
 */
defined('IN_IA') or exit('Access Denied');

class Tim_print_loveModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		$news = array();
		$news['title'] = "真爱永恒";
		$news['picurl'] = "http://wqtim.sinaapp.com/addons/product_show/page.png";
		$news['description'] = "一款树形产品展示特效，让你的产品更吸引";
		$news['url'] = $this->createMobileUrl('index');
		return $this->respNews($news);
		
	}
    
}