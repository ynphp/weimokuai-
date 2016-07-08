<?php
/**
 * 活动中心模块处理程序
 *
 */
defined('IN_IA') or exit('Access Denied');

class J_activityModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		//$content = $this->message['content'];
		
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('j_activity_reply') . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));
		if (empty($row['id'])) {
			return array();
		}
		$title = pdo_fetchcolumn("SELECT name FROM ".tablename('rule')." WHERE id = :rid LIMIT 1", array(':rid' => $rid));
		return $this->respNews(array(
			'Title' => $title,
			'Description' => $row['description'],
			'PicUrl' => $_W['attachurl'] . $row['picture'],
			'Url' => './index.php?c=entry&m=j_activity&do=info&i='.$_W['uniacid']."&wxref=mp.weixin.qq.com#wechat_redirect",
		));
	}
}