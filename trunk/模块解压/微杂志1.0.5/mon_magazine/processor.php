<?php

defined('IN_IA') or exit('Access Denied');
define("MON_MAGAZINE", "mon_magazine");
require_once IA_ROOT . "/addons/" . MON_MAGAZINE . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_MAGAZINE . "/monUtil.class.php";
class Mon_MagazineModuleProcessor extends WeModuleProcessor
{

	private $sae = false;

	public function respond()
	{
		$rid = $this->rule;


		$mag = pdo_fetch("select * from " . tablename(DBUtil::$TABLE_MAGAZINE) . " where rid=:rid", array(":rid" => $rid));

		if (!empty($mag)) {

			$news = array();
			$news [] = array('title' => $mag['new_title'], 'description' => $mag['new_content'],
				'picurl' => MonUtil::getpicurl($mag ['new_icon']), 'url' => $this->createMobileUrl('Index', array('mid' => $mag['id'])));
			return $this->respNews($news);
		} else {
			return $this->respText("微杂志删除或不存在");
		}

		return null;


	}


}
