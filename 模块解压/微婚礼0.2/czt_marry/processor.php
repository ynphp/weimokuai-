<?php
/**
 * 微婚礼模块处理程序
 *
 * @author czt
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_marryModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$fromuser = $this->message['from'];
		if($rid) {
			$reply = pdo_fetch("SELECT * FROM ".tablename('czt_marry')." b left join ".tablename('czt_marry_reply')." a on a.marry_id=b.id WHERE rid = :rid ", array(':rid' => $rid));
			if($reply) {
				$news = array();
				$news[] = array(
					'title' => empty($reply['s_title'])?$reply['title']:$reply['s_title'],
					'description' =>$reply['s_des'],
					'picurl' =>$reply['pic'],
					'url' => $this->createMobileUrl('marry', array('id' => $reply['marry_id'])),
				);
				return $this->respNews($news);
			}
		}
		return null;
	}
}