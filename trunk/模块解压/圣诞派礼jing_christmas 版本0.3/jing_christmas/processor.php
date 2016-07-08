<?php
/**
 * 圣诞派礼
 *
 * @author 刘靜
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class jing_christmasModuleProcessor extends WeModuleProcessor {
	public $table_reply = 'jing_christmas_reply';
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		$rid = $this->rule;
		$fromuser = $this->message['from'];
		if($rid) {
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
			if($reply) {
				$news = array();
				$news[] = array(
					'title' => $reply['title'],
					'description' =>$reply['description'],
					'picurl' =>$reply['thumb'],
					'url' => $this->createMobileUrl('home', array('id' => $reply['id'])),
				);
				return $this->respNews($news);
			}
		}
		return null;
	}
}