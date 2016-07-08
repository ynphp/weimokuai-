<?php
/**
 * codeMonkey:631872807
 */
defined('IN_IA') or exit('Access Denied');
define("MON_ZL", "mon_zl");
define("MON_ZL_RES", "../addons/" . MON_ZL . "/");
require_once IA_ROOT . "/addons/" . MON_ZL . "/dbutil.class.php";
require IA_ROOT . "/addons/" . MON_ZL . "/oauth2.class.php";
require_once IA_ROOT . "/addons/" . MON_ZL . "/value.class.php";
require_once IA_ROOT . "/addons/" . MON_ZL . "/monUtil.class.php";

/**
 * Class Mon_BatonModuleSite
 */
class Mon_ZLModuleSite extends WeModuleSite
{
	public $weid;
	public $acid;
	public $oauth;
	public static $USER_COOKIE_KEY = "__zl2212212221ss22";
	public static $USER_CB_PAGE_SIZE = 10;
	public $mZlSetting;

	function __construct()
	{
		global $_W;
		$this->weid = $_W['uniacid'];
		$this->mZlSetting = $this->findZlsetting();

		$this->oauth = new Oauth2($this->mZlSetting['appid'], $this->mZlSetting['apps']);
	}

	public function doWebZlManage()
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
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_ZL) . " WHERE weid =:weid " . $where . " ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_ZL) . " WHERE weid =:weid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_ZL_USER, array("zid" => $id));
			pdo_delete(DBUtil::$TABLE_ZL_FRIEND, array("zid" => $id));
			pdo_delete(DBUtil::$TABLE_ZL, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template("zl_manage");
	}

	/**
	 * 参加用户
	 */
	public function  doWebuserList()
	{
		global $_W, $_GPC;

		$zid = $_GPC['zid'];
		$zl = DBUtil::findById(DBUtil::$TABLE_ZL,$zid);
		$ord = $_GPC['ord'];
		if ($ord=='') {
			$orderStr ="createtime desc";
		}
		if($ord == 1) {
			$orderStr ="createtime desc";
		}
		if($ord == 2) {
			$orderStr ="createtime asc";
		}

		if($ord == 3) {
			$orderStr ="point desc";
		}
		if($ord == 4) {
			$orderStr ="point asc";
		}

		$where='';
		$params = array();
		$params[':zid'] =$zid;
		if (isset($_GPC['keyword'])) {
			$where .= ' AND nickname Like :keywords ';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 50;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_ZL_USER). " WHERE zid =:zid ".$where." ORDER BY ".$orderStr."  LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_ZL_USER) . " WHERE zid =:zid ".$where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_ZL_FRIEND, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_ZL_USER, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("user_list");

	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 删除用户
	 */
	public function doWebDeleteUser() {
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $uid) {
			$id = intval($uid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_ZL_FRIEND, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_ZL_USER, array("id" => $id));
		}

		echo json_encode(array('code'=>200));
	}

	/**
	 * author: codeMonkey QQ:63187280
	 * 记录
	 */
	public function  doWebRecordlist()
	{
		global $_W, $_GPC;
		$zid = $_GPC['zid'];
		$zl = DBUtil::findById(DBUtil::$TABLE_ZL,$zid);
		$pid = $_GPC['pid'];
		$where = '';
		$params = array();
		$params[':zid'] = $zid;

		if ($_GPC['uid']!='')
		{
			$where .= ' AND f.uid =:uid';
			$params[':uid'] = $_GPC['uid'];

		}
		if (isset($_GPC['keyword'])) {
			$where .= ' AND u.uname Like :keywords ';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT f.*,u.uname as uname  FROM " . tablename(DBUtil::$TABLE_ZL_FRIEND) . " f left join " . tablename(DBUtil::$TABLE_ZL_USER) . " u  on f.uid=u.id  WHERE f.zid =:zid " . $where . " ORDER BY f.createtime DESC, f.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_ZL_FRIEND) . " f left join ".tablename(DBUtil::$TABLE_ZL_USER)." u on f.uid=u.id  WHERE f.zid =:zid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_ZL_FRIEND, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("record_list");

	}


	/**
	 * author: codeMonkey QQ:2463619823
	 * 记录
	 */
	public function  doWebShareList()
	{
		global $_W, $_GPC;
		$sid = $_GPC['sid'];
		$shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$sid);
		$pid = $_GPC['pid'];

		$where = '';
		$params = array();
		$params[':sid'] = $sid;

		if ($_GPC['openid']!='')
		{
			$where .= ' AND s.openid =:openid';
			$params[':openid'] = $_GPC['openid'];

		}

		if (isset($_GPC['keyword'])) {
			$where .= ' AND (u.`tel` LIKE :keywords or u.nickname Like :keywords)';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT s.*,u.nickname nickname,u.tel as tel  FROM " . tablename(DBUtil::$TABLE_QMSHAKE_SHARE) . " s left join " . tablename(DBUtil::$TABLE_QMSHAKE_USER) . " u  on s.openid=u.openid WHERE s.sid =:sid  and u.sid=:sid " . $where . " ORDER BY s.createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_SHARE) . " s left join".tablename(DBUtil::$TABLE_QMSHAKE_USER)." u on s.openid=u.openid WHERE s.sid =:sid and u.sid=:sid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_QMSHAKE_SHARE, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("share_list");

	}



	/**
	 * author: codeMonkey QQ:631872807
	 * 删除摇一摇
	 */
	public function doWebDeleteZl()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_ZL_FRIEND, array("zid" => $id));
			pdo_delete(DBUtil::$TABLE_ZL_USER, array("zid" => $id));
			pdo_delete(DBUtil::$TABLE_ZL, array("id" => $id));
		}
		echo json_encode(array('code' => 200));
	}

	public function doWebDeleteRecord()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_ZL_FRIEND, array("id" => $id));

		}

		echo json_encode(array('code' => 200));
	}

	/**
	 * author: codeMonkey QQ:246361982
	 * 设置
	 */
	public function doWebZlSetting()
	{
		global $_GPC, $_W;
		$zlsetting = DBUtil::findUnique(DBUtil::$TABLE_ZL_SETTING, array(':weid' => $this->weid));

		if (checksubmit('submit')) {
			$data = array(
				'weid' => $this->weid,
				'appid' => trim($_GPC['appid']),
				'apps' => trim($_GPC['apps'])
			);
			if (!empty($zlsetting)) {
				DBUtil::updateById(DBUtil::$TABLE_ZL_SETTING, $data, $zlsetting['id']);
			} else {

				DBUtil::create(DBUtil::$TABLE_ZL_SETTING, $data);
			}
			message('参数设置成功！', $this->createWebUrl('ZlSetting', array(
				'op' => 'display'
			)), 'success');
		}
		include $this->template("zlsetting");
	}

	/**
	 * author: codeMonkey QQ:246361982
	 * 删除分享
	 */
	public function  doWebDeleteShare()
	{
		global $_GPC;
		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_QMSHAKE_SHARE, array("id" => $id));

		}

		echo json_encode(array('code' => 200));
	}



	/**
	 * author: codeMonkey QQ:631872807
	 * 用户信息导出
	 */
	public function  doWebUDownload()
	{

		require_once 'udownload.php';
	}

	public function  doWebRDownload()
	{

		require_once 'rdownload.php';
	}


	/**********************************************************
	手机
	 */

	public function  doMobileAuth()
	{
		global $_GPC, $_W;
		$au = $_GPC['au'];
		$zid = $_GPC['zid'];
		$uid = $_GPC['uid'];
		$params = array();
		$params['zid'] = $zid;
		$params['au'] = $au;
		$params['uid'] = $uid;



		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $zid);
		if (empty($userInfo)) {//授权
			$redirect_uri = MonUtil::str_murl($this->createMobileUrl('Auth2', $params, true));
			$this->oauth->authorization_code($redirect_uri, Oauth2::$SCOPE_USERINFO, 1);//进行授权
		} else {
			$params['openid'] = $userInfo['openid'];
			$redirect_uri = $this->getRedirectUrl($au, $params);
			header("location: $redirect_uri");
		}

	}

	public function  doMobileAuth2()
	{
		global $_GPC;
		$zid = $_GPC['zid'];
		$uid = $_GPC['uid'];
		$code = $_GPC ['code'];
		$au = $_GPC['au'];
		$tokenInfo = $this->oauth->getOauthAccessToken($code);

		$userInfo = $this->oauth->getOauthUserInfo($tokenInfo['openid'], $tokenInfo['access_token']);
		MonUtil::setClientCookieUserInfo($userInfo, $this::$USER_COOKIE_KEY . "" . $zid);//保存到cookie
		$params = array();
		$params['zid'] = $zid;
		$params['au'] = $au;
		$params['uid'] = $uid;
		$params['openid'] = $userInfo['openid'];
		$redirect_uri = $this->getRedirectUrl($au, $params);
		header("location: $redirect_uri");
	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $type
	 * 获取转向URL
	 */
	public function  getRedirectUrl($type, $parmas = array())
	{
		switch ($type) {

			case Value::$REDIRECT_INDEX://首页
				$redirectUrl = $this->createMobileUrl('index', $parmas, true);
				break;
			case Value::$REDIRECT_FRIEND://好友
				$redirectUrl = $this->createMobileUrl('firendZl', $parmas, true);
				breka;

		}
		return MonUtil::str_murl($redirectUrl);

	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 首页
	 */
	public function doMobileIndex() {
		global $_W,$_GPC;
		MonUtil::checkmobile();
		$zid = $_GPC['zid'];
		$openid = $_GPC['openid'];
		$zl = DBUtil::findById(DBUtil::$TABLE_ZL,$zid);
		if (empty($zl)) {
			message("助力活动删除或不存在");
		}
		$user = $this->findUserByOpenid($zid,$openid);
        if (!empty($user)) {

			$this->userTemplate($zl,$user);
			exit;
		}

		$cookieUser = $this->getCookieUserInof($zid);

		$rankList = $this->getRankList($zl['id'],$zl['randking_count']);
		$userCount = $this->getUserCount($zl['id']);
		$unShowRankCount = $userCount-count($rankList);
		$follow =1;

		if (!empty($_W['fans']['follow'])){
			$follow = 2;
		} else {
			$follow =1;
		}
		$this->updateZlViewCount($zl);
		include $this->template("index");
	}

	public function userTemplate($zl,$user) {
		global $_W;

		$rankList = $this->getRankList($zl['id'],$zl['randking_count']);
		$userCount = $this->getUserCount($zl['id']);
		$unShowRankCount = $userCount-count($rankList);
		$userRank = $this->getUserRank($zl['id'],$user['point']);
		$this->updateZlViewCount($zl);
		include $this->template("m_index");
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 */
	public function doMobilefirendZl() {
		global $_W,$_GPC;
		MonUtil::checkmobile();
		$zid = $_GPC['zid'];
		$uid = $_GPC['uid'];
		$fopenid = $_GPC['openid'];

		$zl = DBUtil::findById(DBUtil::$TABLE_ZL,$zid);
		$user = DBUtil::findById(DBUtil::$TABLE_ZL_USER,$uid);

		if (empty($zl)) {
			message("活动删除或不存在");
		}

		if (empty($user)) {
			message("分享人删除或不存在");
		}

		if ($user['openid'] == $fopenid) {//分享人点自己的链接
			$this->userTemplate($zl,$user);
			exit;
		}

		$dbFriendUser = $this->findUserByOpenid($zid,$fopenid);//查询助力还有有没有也参加过活动
		$helpCount = $this->findFirendHelpCount($user['id'],$fopenid);
		$leftCount = $zl['f_zl_limit'] - $helpCount;
		$rankList = $this->getRankList($zid,$zl['randking_count']);
        $userCount = $this->getUserCount($zid);
		$unShowRankCount = $userCount-count($rankList);

		$userRank = $this->getUserRank($zid,$user['point']);
		$follow =1;

		if (!empty($_W['fans']['follow'])){
			$follow = 2;
		} else {
			$follow =1;
		}

		$this->updateZlViewCount($zl);
		include $this->template("f_index");


	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 助力
	 */
	public function doMobileHelp() {
		global $_W,$_GPC;
		$zid = $_GPC['zid'];
		$uid = $_GPC['uid'];
		$zl = DBUtil::findById(DBUtil::$TABLE_ZL,$zid);
		$user = DBUtil::findById(DBUtil::$TABLE_ZL_USER,$uid);
		$res =array();
		if (empty($zl)) {
			$res['code'] = 500;
			$res['msg'] = "助力活动删除或不存在！";
			die(json_encode($res));
		}

		if (TIMESTAMP > $zl ['endtime']) {
			$res['code'] = 501;
			$res['msg'] = "助力活动已经结束，下次再参加吧，谢谢关注！";
			die(json_encode($res));
		}
		if (empty($user)) {
			$res['code'] = 502;
			$res['msg'] = "分享主人不存在哦！";
			die(json_encode($res));
		}

		
		$cookieUser = $this->getCookieUserInof($zid);
		if (empty($cookieUser['openid'])) {
			$res['code'] = 503;
			$res['msg'] = "请授权登录后再进行助力！别刷了，系统已记录你的信息!";
			die(json_encode($res));
		}
		$helpCount = $this->findFirendHelpCount($user['id'],$cookieUser['openid']);
		$leftCount = $zl['f_zl_limit'] - $helpCount;
		if($leftCount <= 0) {
			$res['code'] = 504;
			$res['msg'] = '您帮助次好友助力次数没有了，下次再帮助吧。';
			die(json_encode($res));
		}

        //每天助力检测
		$hepDayCount = $this->findFriendHelpDayCount($zid, $cookieUser['openid'], $user['id']);
		$dayLeftCount = $zl['f_day_limit'] - $hepDayCount;
		if ($dayLeftCount <= 0) {
			$res['code'] = 505;
			$res['msg'] = $zl['f_day_limit_tip'];
			die(json_encode($res));
		}


		//ip 检测
		$ipCount = $this->findFriendHelpIPCount($zid, $_W['clientip']);
        $ipLeftCount = $zl['ip_limit'] - $ipCount;

		if ($ipLeftCount <= 0) {
			$res['code'] = 506;
			$res['msg'] = $zl['ip_limit_tip'];
			die(json_encode($res));
		}


		//不同好友助力次数判断
		$diffCount = $this->findFriendHelpDiffCount($zid, $cookieUser['openid']);
		$diffLeftCount = $zl['f_diff_limt'] - $diffCount;

		if ($diffLeftCount <= 0) {
			$res['code'] = 507;
			$res['msg'] = $zl['f_diff_tip'];
			die(json_encode($res));
		}

		$point = $this->getPoint($zl,$user['point']);
		$firendData = array(
			'zid' =>$zid,
			'uid' =>$user['id'],
			'openid' =>$cookieUser['openid'],
			'nickname' => $cookieUser['nickname'],
			'headimgurl' =>$cookieUser['headimgurl'],
			'ip'=>$_W['clientip'],
			'point'=>$point,
			'createtime' => TIMESTAMP
		);

		DBUtil::create(DBUtil::$TABLE_ZL_FRIEND,$firendData);
		DBUtil::updateById(DBUtil::$TABLE_ZL_USER,array('point'=>$user['point']+$point,'ptime'=>$user['ptime']+1),$user['id']);
		$res['code'] = 200;
		$res['point'] = $point;
		$res['leftCount'] = $leftCount -1;;


		$this->synFansCredit($zl,  $user['moid'], $point, '助力--好友'.$cookieUser['nickname'].'帮他助力增加积分');

		die(json_encode($res));
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 用户分享
	 */
	function doMobileUserShare() {
		global $_W,$_GPC;
		$zid = $_GPC['zid'];

		$zl = DBUtil::findById(DBUtil::$TABLE_ZL,$zid);
		if(!empty($zl)) {
			DBUtil::updateById(DBUtil::$TABLE_ZL,array("share_count" =>$zl['share_count']+1),$zl['id']);
		}

		die(json_encode(array('code' => 200)));

	}
	/**
	 * author: codeMonkey QQ:631872807
	 * 注册
	 */
	public  function doMobileRegist() {
		global $_W,$_GPC;
		$zid = $_GPC['zid'];
		$uname =$_GPC['uname'];
		$tel =$_GPC['tel'];
		$zl = DBUtil::findById(DBUtil::$TABLE_ZL,$zid);
		$res =array();
		if (empty($zl)) {
			$res['code'] = 500;
			$res['msg'] = "助力活动删除或不存在";
			die(json_encode($res));
		}
		$cookieUserInfo = $this->getCookieUserInof($zid);

		if (empty($cookieUserInfo['openid'])) {
			$res['code'] = 501;
			$res['msg'] = "请授权登录后再报名";
			die(json_encode($res));
		}
		$userData =array(
			'zid' => $zid,
			'moid' => $_W['fans']['openid'],
			'openid' =>$cookieUserInfo['openid'],
			'nickname' =>$cookieUserInfo['nickname'],
			'headimgurl' =>$cookieUserInfo['headimgurl'],
			'uname' =>$uname,
		    'tel' => $tel,
		 	'point' =>$zl['startp'],
		    'ip' =>$_W['clientip'],
			'createtime' =>TIMESTAMP
		);
        $this->synFansCredit($zl,$userData['moid'], $zl['startp'], '助力--增加初始积分');
	    DBUtil::create(DBUtil::$TABLE_ZL_USER,$userData);
		$res['code'] = 200;
		die(json_encode($res));
	}
	/**
	 * author: codeMonkey QQ:631872807
	 * @param $sid
	 * @param $openid
	 * @return bool
	 * 总次数
	 */
	public function  findUserRecordCount($sid, $openid)
	{
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " WHERE  sid=:sid and openid=:openid ", array(':sid' => $sid, ":openid" => $openid));
		return $count;
	}


	public function synFansCredit($zl , $fansopenid, $credit, $log) {

		if ($zl['syncredit'] == 1) {
			load()->model('mc');
			if (!empty($fansopenid)) {
				$uid = mc_openid2uid($fansopenid);
				$result = mc_credit_update($uid, 'credit1', $credit, array($uid, $log));
			}
		}
	}
	/**
	 * author: codeMonkey QQ:631872807
	 * @param $sid
	 * @param $openid
	 * @return bool 查找分享次数
	 */
	public function  findUserDayShareCount($sid, $openid)
	{
		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));

		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_SHARE) . " WHERE  sid=:sid and openid=:openid and createtime<=:endtime and  createtime>=:starttime ", array(':sid' => $sid, ":openid" => $openid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * @param $sid
	 * @param $openid
	 * @return bool
	 */
	public function  findUserDayAward($sid, $openid)
	{
		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));
		$count = pdo_fetchcolumn('SELECT sum(award_count) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_SHARE) . " WHERE  sid=:sid and openid=:openid and createtime<=:endtime and  createtime>=:starttime ", array(':sid' => $sid, ":openid" => $openid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * @param $zid
	 * @param $openid
	 * @return bool超找用户
	 */
	public function findUserByOpenid($zid,$openid) {
		return DBUtil::findUnique(DBUtil::$TABLE_ZL_USER,array(':zid'=>$zid,':openid'=>$openid));
	}

	public function findUserByid($zid,$uid) {
		return DBUtil::findUnique(DBUtil::$TABLE_ZL_USER,array(':zid'=>$zid,':id'=>$uid));
	}

	public function getCookieUserInof($zid) {
		$cookieUserInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $zid);
		return $cookieUserInfo;
	}

	public function  findFirendHelpCount($uid,$fopenid)
	{
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_ZL_FRIEND) . " WHERE  uid=:uid and openid=:openid ", array(':uid' => $uid, ":openid" => $fopenid));
		return $count;
	}

	public function findFriendHelpDayCount($zid, $fopenid, $uid) {
		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_ZL_FRIEND) . " WHERE  zid=:zid and openid=:openid and uid=:uid and createtime<=:endtime and  createtime>=:starttime ", array(':zid' => $zid, ":openid" => $fopenid, ":uid" => $uid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}

	public function findFriendHelpIPCount($zid, $ip) {
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_ZL_FRIEND) . " WHERE  zid=:zid and ip=:ip ", array(':zid' => $zid, ":ip" => $ip));
		return $count;
	}

	public function findFriendHelpDiffCount($zid, $fopenid) {
		$count = pdo_fetchcolumn('SELECT COUNT(distinct uid) FROM ' . tablename(DBUtil::$TABLE_ZL_FRIEND) . "
		 WHERE zid=:zid and openid=:openid ", array(':zid' => $zid, ":openid" => $fopenid));
		return $count;
	}

	public function getUserRank($zid,$userPoint) {
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_ZL_USER) . " WHERE  zid=:zid and point>:point ", array(':zid' => $zid, ":point" => $userPoint));
		return $count+1;
	}

	public function updateZlViewCount($zl) {
		DBUtil::updateById(DBUtil::$TABLE_ZL,array("view_count" =>$zl['view_count']+1),$zl['id']);
	}
	/**
	 * author: codeMonkey QQ:631872807
	 * @param $zid
	 * @param $rankcount
	 * 列表
	 */
	public function getRankList($zid,$rankingcount) {
		$rankList=pdo_fetchall("select u.uname as uname, u.nickname ,u.tel as tel ,u.point as point from ".tablename(DBUtil::$TABLE_ZL_USER) ." u where zid=:zid order by point desc, createtime asc limit 0,".$rankingcount,array(":zid"=>$zid));
		return $rankList;
	}

	public function getUserCount($zid) {
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_ZL_USER) . " WHERE  zid=:zid ", array(':zid' => $zid));
		return $count;
	}

	/***************************函数********************************/
	/**
	 * author: codeMonkey QQ:631872807
	 * @param $kid
	 * @param $status
	 * @return bool数量
	 */

	function  encode($value,$dc)
	{

		if ($dc == 1) {
			return $value;
		}

		if($dc == 2) {
			return iconv("utf-8", "gb2312", $value);
		}

	}


	public function findZlsetting()
	{
		$mzlsetting = DBUtil::findUnique(DBUtil::$TABLE_ZL_SETTING, array(":weid" => $this->weid));
		return $mzlsetting;
	}

	public function getPoint($zl, $userPoint)
	{

		if (empty($zl['zl_rule'])) {
			return 1;
		}

		if ($userPoint >= $zl['maxp']) {
			return 0;
		}

		$zl_rule = unserialize($zl['zl_rule']);
		$zl_point = 0;
		$inRule = false;
		foreach ($zl_rule as $rule) {

			if ($userPoint >= $rule['rule_point']) {
				$zl_point = rand($rule['rule_point_start'], $rule['rule_point_end']);
				$inRule = true;
				break;
			}

		}
		if (!$inRule) {
			$zl_point =1;
		}

		return $zl_point;
	}


}