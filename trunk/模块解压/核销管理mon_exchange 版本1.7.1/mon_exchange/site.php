<?php
/**
 */
defined('IN_IA') or exit('Access Denied');
define("MON_EXCHANGE", "mon_exchange");
define("MON_EXCHANGE_RES", "../addons/" . MON_EXCHANGE . "/");
require_once IA_ROOT . "/addons/" . MON_EXCHANGE . "/dbutil.class.php";

require_once IA_ROOT . "/addons/" . MON_EXCHANGE . "/monUtil.class.php";

/**
 * Class
 */
class Mon_ExchangeModuleSite extends WeModuleSite
{
	public static $USER_LOGIN_COOKIE_KEY = "mon_exchange01asdf";
	public $weid;
	function __construct() {
		global $_W;
		$this->weid = $_W['uniacid'];
	}

	/**
	 * author: codeMonkey QQ:800083075
	 * 游戏管理
	 */
	public function doWebgameManage() {
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_EXCHANGE_GAME) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $this->weid));
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_EXCHANGE_GAME) . " WHERE weid =:weid ",  array(':weid' => $this->weid));
			$pager = pagination($total, $pindex, $psize);
			include $this->template("game_list");
		} else if($operation == 'edit') {

			$gid = $_GPC['gid'];
			if (!empty($gid)) {
				$item = DBUtil::findById(DBUtil::$TABLE_EXCHANGE_GAME, $gid);
			}

			if (checksubmit('submit')) {
				$gameData = array(
					'weid' => $this->weid,
					'gname' => $_GPC['gname'],
					'gcode' => $_GPC['gcode']
				);

				if (empty($gid)) {
					$dbGame = $this->findGameBycode($_GPC['gcode']);
					if (!empty($dbGame)) {
						message('游戏编码已存在，请检查您的游戏编码');
					}

					$gameData['createtime'] = TIMESTAMP;
					DBUtil::create(DBUtil::$TABLE_EXCHANGE_GAME, $gameData);
					message('添加游戏成功！', $this->createWebUrl('gameManage'), 'success');
				} else {
					if ($_GPC['gcode'] != $item['gcode']) {
						$dbGame = $this->findGameBycode($_GPC['gcode']);
						if (!empty($dbGame)) {
							message('游戏编码已存在，请检查您的游戏编码');
						}
					}
					DBUtil::updateById(DBUtil::$TABLE_EXCHANGE_GAME, $gameData, $gid);
					message('更新游戏成功！', referer(), 'success');
				}
			}

			include  $this->template("game_edit");

		} else if($operation == 'delete') {
			$gid = $_GPC['gid'];
			pdo_delete(DBUtil::$TABLE_EXCHANGE_RECORD, array('gid' => $gid));
			pdo_delete(DBUtil::$TABLE_EXCHANGE_ADMIN, array('gid' => $gid));
			pdo_delete(DBUtil::$TABLE_EXCHANGE_GAME, array('id' => $gid));
			message('删除成功！', referer(), 'success');
		}

	}

	/**
	 * author: codeMonkey QQ:800083075
	 * 核销人员管理
	 */
	public function doWebGameAdmin() {
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$games = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_EXCHANGE_GAME) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC ", array(':weid' => $this->weid));
		if ($operation == 'display') {
			$gid = $_GPC['gid'];
			$where = '1 = 1 ';
			$params = array();
			$where .= " and weid=:weid";
			$params[":weid"] = $this->weid;
			if (!empty($gid)) {
				$where.= " and gid=:gid";
				$params[":gid"] = $gid;
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT a.* , (select gname from ".tablename(DBUtil::$TABLE_EXCHANGE_GAME)." g  where g.id = a.gid) as gname FROM " . tablename(DBUtil::$TABLE_EXCHANGE_ADMIN) . " a WHERE ".$where."  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_EXCHANGE_ADMIN) . " WHERE ".$where ,  $params);
			$pager = pagination($total, $pindex, $psize);
			include $this->template("admin_list");
		} else if($operation == 'edit') {
			$aid = $_GPC['aid'];
			if (!empty($aid)) {
				$item = DBUtil::findById(DBUtil::$TABLE_EXCHANGE_ADMIN, $aid);
			}
			if (checksubmit('submit')) {

				$token = md5($_GPC['admin'].$_GPC['pwd']);
				$adminData = array(
					'token' => $token,
					'gid' => $_GPC['gid'],
					'admin' => $_GPC['admin'],
					'pwd' => $_GPC['pwd'],
					'remark' => $_GPC['remark'],
					'weid' => $this->weid
				);

				if (empty($aid)) {
					$dbAdmin = $this->findAdmin($_GPC['admin'], $_GPC['pwd']);
					if (!empty($dbAdmin)) {
						message('核销人员已存在，请检查后修改核销人员信息。');
					}
					$adminData['createtime'] = TIMESTAMP;
					DBUtil::create(DBUtil::$TABLE_EXCHANGE_ADMIN, $adminData);
					message('添加核销人员成功！', $this->createWebUrl('gameAdmin'), 'success');
				} else {

					if ($_GPC['admin'] != $item['admin'] || $_GPC['pwd'] != $item['pwd']) {
						$dbAdmin = $this->findAdmin($_GPC['admin'], $_GPC['pwd']);
						if (!empty($dbAdmin)) {
							message('核销人员已存在，请检查后修改核销人员信息。');
						}
					}
					DBUtil::updateById(DBUtil::$TABLE_EXCHANGE_ADMIN, $adminData, $aid);
					message('更新核销人员成功！', referer(), 'success');
				}
			}

			include  $this->template("admin_edit");

		} else if($operation == 'delete') {
			$aid = $_GPC['aid'];
			pdo_delete(DBUtil::$TABLE_EXCHANGE_RECORD, array('aid' => $aid));
			pdo_delete(DBUtil::$TABLE_EXCHANGE_ADMIN, array('id' => $aid));
			message('删除成功！', referer(), 'success');
		}


	}

	/**
	 * author: codeMonkey QQ:800083075
	 * 核销记录
	 */

	public function doWebexchangeRecord() {
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$games = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_EXCHANGE_GAME) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC ", array(':weid' => $this->weid));
		if ($operation == 'display') {
			$gid = $_GPC['gid'];
			$aid = $_GPC['aid'];
			$where = '1 = 1 ';
			$params = array();
			$where .= " and weid=:weid";
			$params[":weid"] = $this->weid;
			if (!empty($gid)) {
				$where.= " and gid=:gid";
				$params[":gid"] = $gid;
			}
			if (!empty($aid)) {
				$where.= " and aid=:aid";
				$params[":aid"] = $aid;

			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT r.* , (select gname from ".tablename(DBUtil::$TABLE_EXCHANGE_GAME)." g  where g.id = r.gid) as gname,
			(select admin from ".tablename(DBUtil::$TABLE_EXCHANGE_ADMIN)." a  where a.id = r.aid) as admin

			 FROM " . tablename(DBUtil::$TABLE_EXCHANGE_RECORD) . " r WHERE ".$where."  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_EXCHANGE_RECORD) . " WHERE ".$where ,  $params);
			$pager = pagination($total, $pindex, $psize);
			include $this->template("record_list");
		} else if($operation == 'delete') {
			$rid = $_GPC['rid'];
			pdo_delete(DBUtil::$TABLE_EXCHANGE_RECORD, array('id' => $rid));
			message('删除成功！', referer(), 'success');
		}
	}


	public function findGameBycode($gameCode) {
		return DBUtil::findUnique(DBUtil::$TABLE_EXCHANGE_GAME, array(":gcode" => $gameCode, ":weid" => $this->weid));
	}

	public function findAdmin($admin, $pwd) {
		return DBUtil::findUnique(DBUtil::$TABLE_EXCHANGE_ADMIN, array(":admin" => $admin, ":pwd" => $pwd));
	}


	/*******************************************
	  手机核销
	 */

	public function doMobilegameAdminIndex() {
		MonUtil::checkmobile();
		include $this->template("admin_index");
	}

	public function doMobileAdminLogin() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$gcode = $_GPC['gcode'];
		$admin = $_GPC['admin'];
		$pwd = $_GPC['pwd'];
		$loginUser = $this->findLoinAdmin($gcode, $admin, $pwd);
		$res = array();
		if (empty($loginUser)) {
			$res['code'] = 500;
			$res['msg'] = "用户或密码不正确";
			die(json_encode($res));
		}
		$res['code'] = 200;
		$res['scanUrl'] = MonUtil::str_murl($this->createMobileUrl('AdminScan'));
		MonUtil::setClientCookieUserInfo($loginUser, $this::$USER_LOGIN_COOKIE_KEY);
		die(json_encode($res));
	}

	public function doMobileAdminScan() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$loginUser = MonUtil::getClientCookieUserInfo($this::$USER_LOGIN_COOKIE_KEY);
		if (empty($loginUser)) {
			message('登录失效，请重新登录！', MonUtil::str_murl($this->createMobileUrl("gameAdminIndex")), 'error');
		}
		$game = DBUtil::findById(DBUtil::$TABLE_EXCHANGE_GAME, $loginUser['gid']);
		include $this->template("admin_scan");
	}

	/**
	 * author: codeMonkey QQ:800083075
	 * 扫描结果处理
	 */
	public function doMobileScanResult() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$loginUser = MonUtil::getClientCookieUserInfo($this::$USER_LOGIN_COOKIE_KEY);
		$res = array();
		if (empty($loginUser)) {
			$res['code'] = 500;
			$res['msg'] = "登录已超时，请重新打登录";
			die(json_encode($res));
		}
		$codeInfo = json_decode(base64_decode($_GPC['codeString']), true);
		if (empty($codeInfo) || empty($codeInfo['exeUrl']) || empty($codeInfo['gcode'])) {
			$res['code'] = 500;
			$res['msg'] = "解析错误";
			die(json_encode($res));
		}

		$game = $this->findGameBycode($codeInfo['gcode']);

		if (empty($game)) {
			$res['code'] = 501;
			$res['msg'] = "游戏编号不正确，请自己仔细检查";
			die(json_encode($res));
		}

		$token_url = MonUtil::str_murl($this->createMobileUrl('tokenUrl', array(), true));
		load()->func('communication');
		$result = ihttp_post($codeInfo['exeUrl'], array('token' => $loginUser['token'], 'tokenUrl'=> urlencode($token_url)));

		//$res['code'] = 501;
		//$res['msg'] = $result['content'];
		//die(json_encode($res));

		//bom问题
		$resultJson = json_decode(substr($result['content'], 3), true);

		if (empty($resultJson)) {
			$resultJson = json_decode($result['content'], true);
		}

		if (!empty($resultJson) && !empty($resultJson['res'])) {

			if ($resultJson['res'] == 'success') {
				$loginUser = MonUtil::getClientCookieUserInfo($this::$USER_LOGIN_COOKIE_KEY);
				if (empty($loginUser)) {
					$res['code'] = 500;
					$res['msg'] = "登录已超时，请重新打登录";
					die(json_encode($res));
				}

				$record = array(
					'weid' => $game['weid'],
					'gid' => $game['id'],
					'aid' => $loginUser['id'],
					'uname'=> $resultJson['uname'],
					'unickname'=> $resultJson['unickname'],
					'utel'=> $resultJson['utel'],
					'pname'=> $resultJson['pname'],
					'remark'=>$resultJson['remark'],
					'createtime' => TIMESTAMP
				);

				DBUtil::create(DBUtil::$TABLE_EXCHANGE_RECORD, $record);
				$res['code'] = 200;
				die(json_encode($res));
			} else {
				//兑换失败
				$res['code'] = 503;
				$res['msg'] = $resultJson['msg'];
				die(json_encode($res));
			}
		}

	}


	/**
	 * author: codeMonkey QQ:800083075
	 * 验证
	 */
	public function doMobiletokenUrl() {
		global $_W, $_GPC;
		$token = $_GPC['token'];
		$admin = DBUtil::findUnique(DBUtil::$TABLE_EXCHANGE_ADMIN, array(':token'=> $token));
		$res = array();
		if (empty($admin)) {
			$res['code'] = 50001;
		} else {
			$res['code'] = 200;
		}
		die(json_encode($res));
	}
	public function findLoinAdmin($gcode, $admin, $pwd) {
		$game = $this->findGameBycode($gcode);
		$loginAdmin = $this->findAdmin($admin, $pwd);
		if ($loginAdmin['gid'] != $game['id']) {
			return null;
		}
		return $loginAdmin;
	}
}