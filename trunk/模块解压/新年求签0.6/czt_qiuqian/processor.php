<?php
/**
 * 新年求签模块处理程序
 *
 * @author czt
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_qiuqianModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$fromuser = $this->message['from'];
		$content = $this->message['content'];
		if($rid) {
			$reply = pdo_fetch("SELECT * FROM ".tablename('czt_qiuqian_reply')." WHERE rid = :rid ", array(':rid' => $rid));
			if($reply) {
				return $this->respText("点击[U+1F449] <a href='".$_W['siteroot'].'app/'.$this->createMobileUrl('index',array('id' => $reply['id']))."'> 摇枚新年签</a>，祈求新年好运气吧！".'[U+1F64F]');
			}
		}
		return null;
	}
}