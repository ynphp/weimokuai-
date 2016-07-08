<?php
/**
 * codeMonkey:mitusky QQ：229364369
 */
defined('IN_IA') or exit('Access Denied');
define("MON_MAGAZINE", "mon_magazine");
define("MON_MAGAZINE_RES", "../addons/" . MON_MAGAZINE . "/");
require_once IA_ROOT . "/addons/" . MON_MAGAZINE . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_MAGAZINE . "/monUtil.class.php";

/**
 * Class Mon_MagazineModuleSite
 */
class Mon_MagazineModuleSite extends WeModuleSite
{
	public $weid;
	public $acid;
	function __construct()
	{
		global $_W;
		$this->weid = $_W['uniacid'];
	}


	/************************************************管理*********************************/

	/**
	 * 活动管理
	 */
	public function  doWebMagManage()
	{

		global $_W, $_GPC;

		$where='';
		$params = array();
		$params[':weid'] = $this->weid;
		if (isset($_GPC['keyword'])) {
			$where .= ' AND `title` LIKE :keywords';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_MAGAZINE). " WHERE weid =:weid ".$where." ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_MAGAZINE) . " WHERE weid =:weid ".$where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_MAGAZINE_PAGE, array("mid" => $id));
			pdo_delete(DBUtil::$TABLE_MAGAZINE, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}
		MonUtil::deleteInstall();
		include $this->template("magazine");


	}

	public function doMobileIndex() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$mid = $_GPC['mid'];
		$mag = DBUtil::findById(DBUtil::$TABLE_MAGAZINE,$mid);
		MonUtil::emtpyMsg($mag,'微杂志删除或不存在');
		$mag_pages=pdo_fetchall("select * from ".tablename(DBUtil::$TABLE_MAGAZINE_PAGE)." where mid=:mid order by page asc ",array(":mid"=>$mid));
		$page_json=json_encode($mag_pages);
		include $this->template("index");
	}

	/**
	 * author: codeMonkey QQ:mitusky QQ：229364369
	 * 封面
	 */
	public function doMobileBookIndex() {
		global $_W, $_GPC;
		$mid = $_GPC['mid'];
		$mag = DBUtil::findById(DBUtil::$TABLE_MAGAZINE,$mid);
		$mag_pages=pdo_fetchall("select * from ".tablename(DBUtil::$TABLE_MAGAZINE_PAGE)." where mid=:mid order by page asc ",array(":mid"=>$mid));
		include $this->template("book_index");
	}


	public function doMobileBookIndexConver() {
		global $_W, $_GPC;
		$mid = $_GPC['mid'];
		$mag = DBUtil::findById(DBUtil::$TABLE_MAGAZINE,$mid);
		$mag_pages=pdo_fetchall("select * from ".tablename(DBUtil::$TABLE_MAGAZINE_PAGE)." where mid=:mid order by page asc ",array(":mid"=>$mid));

		include $this->template("book_index_cover");
	}

	/**
	 * author: codeMonkey QQ:mitusky QQ：229364369
	 * 封面
	 */
	public function doMobileBookCover() {
		global $_W, $_GPC;
		$mid = $_GPC['mid'];
		$mag = DBUtil::findById(DBUtil::$TABLE_MAGAZINE,$mid);
		include $this->template("book_cover");
	}
}