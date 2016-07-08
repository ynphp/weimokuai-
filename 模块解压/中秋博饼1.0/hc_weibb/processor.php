<?php
/**
 * 中秋博饼模块
 *
 * 疯狂却不失细腻——微赞科技		www.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class hc_weibbModuleProcessor extends WeModuleProcessor {
	
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('hc_weibb_reply') . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));
		if (empty($row['id'])) {
			return array();
		}
		$title = pdo_fetchcolumn("SELECT name FROM ".tablename('rule')." WHERE id = :rid LIMIT 1", array(':rid' => $rid));
		return $this->respNews(array(
			'Title' => $title,
			'Description' => $row['description'],
			'PicUrl' => $_W['attachurl'] . $row['picture'],
			'Url' => $this->createMobileUrl('Index', array('id' => $rid)),
		));
	}
}
