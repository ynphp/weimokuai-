<?php
/**
 * codeMonkey:631872807
 */
defined('IN_IA') or exit('Access Denied');
define("MON_TIMELINE", "mon_timeline");
define("MON_TIMELINE_RES", "../addons/" . MON_TIMELINE . "/");

require_once IA_ROOT . "/addons/" . MON_TIMELINE . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_TIMELINE . "/monUtil.class.php";

/**
 * Class Mon_BatonModuleSite
 */
class Mon_TimelineModuleSite extends WeModuleSite
{
	public $weid;
	public $acid;
	public $oauth;

	public static $USER_CB_PAGE_SIZE = 10;


	function __construct()
	{
		global $_W;
		$this->weid = $_W['uniacid'];

	}

	public function doWebTimelineMange()
	{
		global $_W, $_GPC;
		$where = '';
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
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_TIMELINE) . " WHERE weid =:weid " . $where . " ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_TIMELINE) . " WHERE weid =:weid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_TIMELINE_ITEM, array("tid" => $id));
			pdo_delete(DBUtil::$TABLE_TIMELINE, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template("timeline_manage");
	}

	public function doWebtimeItemList()
	{
		global $_W, $_GPC;
		$tid = $_GPC['tid'];
		$where = '';
		$params = array();
		$params[':tid'] = $tid;
		if (isset($_GPC['keyword'])) {
			$where .= ' AND `title` LIKE :keywords';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_TIMELINE_ITEM) . " WHERE tid =:tid " . $where . " ORDER BY i_time asc LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_TIMELINE_ITEM) . " WHERE tid =:tid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_TIMELINE_ITEM, array("id" => $id));

			message('删除成功！', referer(), 'success');
		}
		include $this->template("time_item_list");
	}

	/**
	 * author: codeMonkey QQ:246361982
	 * 删除
	 */
	public function doWebDeleteTimeItem() {
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $uid) {
			$id = intval($uid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_TIMELINE_ITEM, array("id" => $id));
		}

		echo json_encode(array('code'=>200));
	}

	/**
	 * author: codeMonkey QQ:246361982
	 * 删除
	 */
	public function doWebDeleteTimeLine()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_TIMELINE_ITEM, array("tid" => $id));
			pdo_delete(DBUtil::$TABLE_TIMELINE, array("id" => $id));
		}
		echo json_encode(array('code' => 200));
	}

	/**
	 * author: codeMonkey QQ:631872807
	 *
	 */
	public function doWebeditTimeItem()
	{
		global $_W, $_GPC;
		$tid = $_GPC['tid'];
		$item_id = $_GPC['item_id'];
		load()->func('tpl');

		if (!empty($item_id)) {
			$item = DBUtil::findById(DBUtil::$TABLE_TIMELINE_ITEM,$item_id);
			$item['i_time'] = date("Y-m-d  H:i", $item['i_time']);
		}

		if (checksubmit('submit')) {
			$data = array(
				'tid' => $tid,
				'ititle' => $_GPC['ititle'],
				'i_time' => strtotime($_GPC['i_time']),
				'i_img' => $_GPC['i_img'],
				'i_bgcolor' => $_GPC['i_bgcolor'],
				'i_url' => $_GPC['i_url'],
				'displayorder' => $_GPC['displayorder'],
				'text' => $_GPC['itext'],
				'createtime' => TIMESTAMP,
			);

			if (empty ($item_id)) {
				DBUtil::create(DBUtil::$TABLE_TIMELINE_ITEM, $data);
				message('添加成功！', referer(), 'success');

			} else {
				DBUtil::updateById(DBUtil::$TABLE_TIMELINE_ITEM, $data, $item_id);


				message('修改成功！', referer(), 'success');
			}

		}

		include $this->template("time_item_edit");
	}

	public  function getItemBg($bgColor) {
		if (empty($bgColor)) {
			return "#737373";
		}
		return $bgColor;
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 */
	public  function doMobileIndex() {
		global $_W, $_GPC;
		$tid = $_GPC['tid'];
		$timeline = DBUtil::findById(DBUtil::$TABLE_TIMELINE,$tid);
		if(empty($timeline)) {
			message("时间轴删除或不存在!");
		}

		include $this->template("index");
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 */
	public function doMobileAjaxItem() {
		global $_W, $_GPC;
		$tid = $_GPC['tid'];
		$res =array();
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$res['result'] = true;
		$res['message'] = '';
		$res['count'] = 3;
		$res['page'] = $pindex;
		$data =pdo_fetchall("select ititle as title,text,i_bgcolor,i_url as url,i_img as pic,FROM_UNIXTIME(i_time, '%Y') as year,FROM_UNIXTIME(i_time, '%m') as mon,FROM_UNIXTIME(i_time, '%d') as mday,FROM_UNIXTIME(i_time, '%H') as hours,FROM_UNIXTIME(i_time, '%i') as minutes from ".tablename(DBUtil::$TABLE_TIMELINE_ITEM)." where tid=:tid order by i_time asc limit ".($pindex - 1) * $psize . ',' . $psize,array(":tid"=>$tid));
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_TIMELINE_ITEM) . " WHERE tid =:tid " , array(":tid" => $tid));
		$res['data'] = $data;
		$res['count'] =ceil($total/$psize);      //获得总页数 pagenum;
		die(json_encode($res));
	}
}