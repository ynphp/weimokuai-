<?php
/**
 * codeMonkey:2463619823
 */
defined('IN_IA') or exit('Access Denied');
define("MON_XKWKJ", "mon_xkwkj");
define("MON_XKWKJ_RES", "../addons/" . MON_XKWKJ . "/");
require_once IA_ROOT . "/addons/" . MON_XKWKJ . "/dbutil.class.php";
require IA_ROOT . "/addons/" . MON_XKWKJ . "/oauth2.class.php";
require_once IA_ROOT . "/addons/" . MON_XKWKJ . "/value.class.php";
require_once IA_ROOT . "/addons/" . MON_XKWKJ . "/monUtil.class.php";
require_once IA_ROOT . "/addons/" . MON_XKWKJ . "/WxPayPubHelper/WxPayPubHelper.php";

/**
 * Class Mon_WkjModuleSite
 */
class Mon_XKWkjModuleSite extends WeModuleSite
{
	public $weid;
	public $acid;
	public $oauth;
	public static $USER_COOKIE_KEY = "__monkxwkjuserv2122232";

	public static $KJ_STATUS_WKS = 0;//未开始
	public static $KJ_STATUS_ZC = 1;//正常
	public static $KJ_STATUS_JS = 2;//结束
	public static $KJ_STATUS_XD = 3;//已下单
	public static $KJ_STATUS_GM = 4;//已支付
	public static $KJ_STATUS_YFH = 5;//已发货

	public static $TIP_DIALOG = 1;//对话框
	public static $TIP_U_FIRST = 2;//首次
	public static $TIP_U_ALREADY = 3;//已经砍过了
	public static $TIP_RANK = 4;//页面
	public static $TIP_FK_FIRST = 5;//好友
	public static $TIP_FK_ALREADY = 6;//好友已看
	public $xkkjSetting;

	function __construct()
	{
		global $_W;
		$this->weid = $_W['uniacid'];
		$this->xkkjSetting = $this->findXKKJsetting();
		$this->oauth = new Oauth2($this->xkkjSetting['appid'], $this->xkkjSetting['appsecret']);
	}


	/************************************************手机*********************************/
	/**
	 * author: codeMonkey QQ:2463619823
	 * 用户砍价页
	 */
	public function  doMobileIndex()
	{
		global $_W, $_GPC;
		$kid = $_GPC['kid'];
		MonUtil::checkmobile();
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		MonUtil::emtpyMsg($xkwkj, "炫酷砍价活动不存在或已删除");
		$join = false;
		$userInfo =$this->getCookidUerInfo($kid);
		$user = $this->findJoinUser($kid, $userInfo['openid']);

		$starttime = date("Y/m/d  H:i:s", $xkwkj['starttime'] );
		$endtime = date("Y/m/d  H:i:s", $xkwkj['endtime'] );
		$curtime = date("Y/m/d  H:i:s", TIMESTAMP);
		$leftCount = $this->getLeftCount($xkwkj);
		$status = $this->getStatus($xkwkj, $user);
		$statusText = $this->getStatusText($status);
		$joinCount = $this->getJoinCount($xkwkj);
		if (!empty($user)) {   //已参加
			$orderInfo = $this->findOrderInfo($kid, $user['id']);
			include $this->template('m_kj');

		} else { //没有参加

            $kjPrice = $this->getKjPrice($xkwkj, $xkwkj['p_y_price']);
			include $this->template('m_index');
		}
	}


	/**
	 * author: codeMonkey QQ:2463619823
	 * 排行榜
	 */
	public function  doMobileRank()
	{
		global $_W, $_GPC;
		$kid = $_GPC['kid'];
		MonUtil::checkmobile();
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		MonUtil::emtpyMsg($xkwkj, "炫酷砍价活动不存在或已删除");
		$starttime = date("Y/m/d  H:i:s", $xkwkj['starttime'] );
		$endtime = date("Y/m/d  H:i:s", $xkwkj['endtime'] );
		$curtime = date("Y/m/d  H:i:s", TIMESTAMP);
		$leftCount = $this->getLeftCount($xkwkj);
		$status = $this->getStatus($xkwkj, '');
		$statusText = $this->getStatusText($status);
		$joinCount = $this->getJoinCount($xkwkj);

		$join_rank_num = $xkwkj['join_rank_num'];

		$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_XKWKJ_USER) . " WHERE kid =:kid   ORDER BY price asc, createtime desc limit 0,  ".$join_rank_num , array(':kid'=>$kid));


		include $this->template("rank");


	}



	/**
	 * author: codeMonkey QQ:2463619823
	 * 自砍一刀
	 */
	public  function doMobileSelfKj() {
		global $_W, $_GPC;

		$kid = $_GPC['kid'];
		$seq_weapon = $_GPC['seq_weapon'];
		$name_seq = $_GPC['name_seq'];
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
        $userInfo = $this->getCookidUerInfo($kid);
		
		if (empty($userInfo)) {
		    $res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '请授权登录后再进行砍价哦!';
			die(json_encode($res));
		}

		$dbJoinUser=DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_USER,array(":kid"=>$kid,":openid"=>$userInfo['openid']));
		$res = array();
		if(empty($dbJoinUser)) {
			$userData = array(
				'kid' => $xkwkj['id'],
				'openid' => $userInfo['openid'],
				'nickname' => $userInfo['nickname'],
				'headimgurl' => $userInfo['headimgurl'],
				'price' => $xkwkj['p_y_price'],
				'ip' => $_W['clientip'],
				'createtime' => TIMESTAMP
			);
			DBUtil::create(DBUtil::$TABLE_XKWKJ_USER, $userData);//注册用户
			$uid = pdo_insertid();//用户ID
			$kj_res = $this->createFirendKj($xkwkj, $uid, $userInfo['openid'], $userInfo['nickname'], $userInfo['headimgurl'], $seq_weapon, $name_seq);
			$res['code'] = $kj_res[0];
			$res['price'] = $kj_res[1];
			$res['msg'] = $kj_res[2];
			die(json_encode($res));

		} else {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '您已经自己砍过了哦，不能再砍了哦';
			die(json_encode($res));
		}

	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 好友砍刀
	 */
	public  function doMobileFriendfKj() {
		global $_W, $_GPC;
		$res = array();
		$kid = $_GPC['kid'];
		$seq_weapon = $_GPC['seq_weapon'];
		$name_seq = $_GPC['name_seq'];
		$uid = $_GPC['uid'];
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
        $user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER,$uid);

		if (empty($xkwkj)) {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '看见活动删除或不存在';
			die(json_encode($res));
		}

		if (TIMESTAMP > $xkwkj['endtime']) {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '活动已结束';
			die(json_encode($res));
		}

		if (empty($user)) {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '分享主人删除或不存在';
			die(json_encode($res));
		}


		$status = $this->getStatus($xkwkj, $user);


		if ($status != $this::$KJ_STATUS_ZC) {
			$statusText = $this->getStatusText($status);
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = $statusText;
			die(json_encode($res));
		}



		$friend = $this->getCookidUerInfo($kid);

		if (empty($friend)) {
			$res['code'] = 0;
			$res['price'] = 0;
			$res['msg'] = '请授权的登录后再进行砍价哦!';
			die(json_encode($res));
		}

		$kj_res = $this->createFirendKj($xkwkj, $uid, $friend['openid'], $friend['nickname'], $friend['headimgurl'], $seq_weapon, $name_seq);
		$res['code'] = $kj_res[0];
		$res['price'] = $kj_res[1];
		$res['msg'] = $kj_res[2];
		die(json_encode($res));
	}
	/**
	 * author: codeMonkey QQ:2463619823
	 * 地址
	 */
	public function doMobileAddress() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$userInfo = $this->getCookidUerInfo($kid);
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
        $user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		$zfprice = $xkwkj['yf_price'] + $user['price'];
		$p_models=explode('|',$xkwkj['p_model']);


		$leftCount = $this->getLeftCount($xkwkj);

		include $this->template("address");
	}

	public function doMobileShow() {
		global $_GPC, $_W;
		$appid = $_W["account"]["key"];
        $secret = $_W["account"]["secret"];

		$url='http://'.$_SERVER['HTTP_HOST'].'/app/'.$this->createMobileUrl('show');
		
		if(!empty($_SESSION['openid']))
		{
			$openid=$_SESSION['openid'];
		}
		else
		{
			if (empty($_REQUEST["code"])) {
				$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_userinfo&state=blinq#wechat_redirect";

				header('Location: ' . $url);
		
			}else{
				$code = $_REQUEST['code'];
				
				$accessTokenUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=$code&grant_type=authorization_code";
		
				$ch = curl_init($accessTokenUrl);
				
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:21.0) Gecko/20100101 Firefox/21.0');
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$info = curl_exec($ch);
				$dataJson = json_decode($info, true);
			
				$openid = $dataJson['openid'];
				$access_token = $dataJson['access_token'];
				$_SESSION['openid'] = $openid;
				
			
					
				// 拉取用户信息
				$userInfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
				$ch = curl_init($userInfoUrl);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:21.0) Gecko/20100101 Firefox/21.0');
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$info = curl_exec($ch);
				$dataJson = json_decode($info, true);
				
				$headimgurl = $dataJson['headimgurl'];
				$nickname = $dataJson['nickname'];
				
			}
		}
		$show_row = pdo_fetch('SELECT * FROM ' . tablename('mon_xkwkj_show') . " WHERE uniacid = '{$_W['uniacid']}'  AND openid = '{$openid}'");
		
		
		if(empty($show_row))
		{
			$data = array(
			'uniacid' => $_W['uniacid'],
			'openid' => $openid,
			'number' => 1,
			'createtime' => TIMESTAMP								

			);
			pdo_insert('mon_xkwkj_show', $data);
		}
		else
		{
			
			$data = array(
			'uniacid' => $_W['uniacid'],
			'openid' => $openid,
			'number' => intval($show_row['number'])+1,
			'createtime' => TIMESTAMP								

			);
			pdo_update('mon_xkwkj_show', $data, array('openid' => $openid));
		}
		if(!empty($show_row['createtime']))
		{
			$show_row['time']=date('Y-m-d H:i:s',$show_row['createtime']);	
		}
		else
		{
			$show_row['time']=date('Y-m-d H:i:s',time());	
		}
			
		
		$show_list = pdo_fetch('SELECT * FROM ' . tablename('mon_xkwkj_show') . " WHERE uniacid = '{$_W['uniacid']}'  AND openid = '{$openid}'");
		$show_list['time']=date('Y-m-d H:i:s',$show_list['createtime']);

		$sqlorder = 'SELECT * FROM ' . tablename('mon_xkwkj_order') . " WHERE `openid` = :openid";
		$orderlist=pdo_fetch($sqlorder, array(':openid' => $openid));

		$sql = 'SELECT SUM(og.total) AS orderTotal FROM ' . tablename('shopping_order_goods') . ' AS og JOIN ' . tablename('shopping_order') .
				' AS o ON og.orderid = o.id WHERE o.status>0 AND  o.from_user = :from_user';
		$params = array(':from_user' => $openid);
		$orderTotal = pdo_fetchcolumn($sql, $params);
		

		include $this->template('show');
	}

	public function  getCookidUerInfo($kid) {
	//	return array('openid'=>'o_-HajnJvvy-DqNuQp8q4a-m3T98');
		$userInfo = MonUtil::getClientCookieUserInfo($this::$USER_COOKIE_KEY . "" . $kid);
		return $userInfo;
	}
	/**
	 * 砍价朋友页面
	 */
	public function doMobileKj()
	{

		global $_W, $_GPC;
		MonUtil::checkmobile();
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$fopenid = $_GPC['openid'];
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		MonUtil::emtpyMsg($xkwkj, "砍价活动不存在或已删除!");
		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		MonUtil::emtpyMsg($user, "用户删除或不存在!");
		$firend = $this->getCookidUerInfo($kid);
		$userInfo = $this->getCookidUerInfo($kid);
		$userInfo['nickname'] = $user['nickname'];
		if ($firend['openid'] == $user['openid']) {//自己点了自己分析的链接
			$indexUrl = MonUtil::str_murl($this->createMobileUrl('index', array('kid' => $kid, 'openid' => $fopenid), true));
			header("location: $indexUrl");
			exit;
		}
		$joinCount = $this->getJoinCount($xkwkj);
        $leftCount = $this->getLeftCount($xkwkj);
		$starttime = date("Y/m/d  H:i:s", $xkwkj['starttime'] );
		$endtime = date("Y/m/d  H:i:s", $xkwkj['endtime'] );
		$curtime = date("Y/m/d  H:i:s", TIMESTAMP);


		$status = $this->getStatus($xkwkj, $user);
		$statusText = $this->getStatusText($status);

		$joinFirend = $this->findJoinUser($kid, $firend['openid']);
		$firendJoined = false;
		if (!empty($joinFirend)) {
			$firendJoined = true;// 哥们自己有参加过活动
		}

		$dbFirend = $this->findHelpFirend($uid, $firend['openid']);
		$helped = false;
		if (!empty($dbFirend)) {
			$helped = true;//已经帮好友看过了。。。。。
			$follow = 1;
			if (!empty($_W['fans']['follow'])){
				$follow = 2;
			}

			include $this->template("firend_yk");

		} else {
			include $this->template("friend_index");
		}

	}

	public function getShareUrl($kid, $uid)
	{
		if (empty($uid)) {
			MonUtil::str_murl($this->createMobileUrl('Auth', array('kid' => $kid, 'au' => Value::$REDIRECT_USER_INDEX), true));
		} else {
			return MonUtil::str_murl($this->createMobileUrl('Auth', array('kid' => $kid, 'uid' => $uid, 'au' => Value::$REDIRECT_KJ), true));
		}
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * @param $uid
	 * @param $fopenid
	 * @param $fnickname
	 * @param $fheadimgurl
	 * 砍价信息
	 */
	public function  createFirendKj($xkwkj, $uid, $fopenid, $fnickname, $fheadimgurl, $seq_weapon, $name_seq)
	{
		global $_W;
		$dbFirend = $this->findHelpFirend($uid, $fopenid);
		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		$leftCount = $this->getLeftCount($xkwkj);
		if ($leftCount <= 0) {
			return array(0,0, "库存已经没了，下次再来砍吧！");
		}

		if ($user['price'] <= $xkwkj['p_low_price']) {
			return array(0,0, "好友砍价过猛已经最低价啦，下次再来帮好友砍吧！");
		}


		$k_price = $this->getKjPrice($xkwkj, $user['price']);
		if (empty($dbFirend)) {
			$leftPrice = $user['price'] - $k_price;
			if ($leftPrice <= $xkwkj['p_low_price']) {
				$leftPrice = $xkwkj['p_low_price'];
			}
			$helpFirend = array(
				'kid' => $xkwkj['id'],
				'uid' => $uid,
				'openid' => $fopenid,
				'nickname' => $fnickname,
				'headimgurl' => $fheadimgurl,
				'ac' => '砍了',
				'k_price' => $k_price,
				'kh_price' => $leftPrice,
				'kd' => $seq_weapon,
				'kname' => $name_seq,
				'ip' => $_W['clientip'],
				'createtime' => TIMESTAMP
			);

			DBUtil::create(DBUtil::$TABLE_XKWKJ_FIREND, $helpFirend);
			DBUtil::updateById(DBUtil::$TABLE_XKWKJ_USER, array('price' => $leftPrice), $uid);
		} else{
			return array(0,0, "已经帮好友砍过价了，下次再继续吧！！");
		}

		return array(1,$k_price, $this->getTipMsg($xkwkj, $this::$TIP_DIALOG));

	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 砍价高手
	 */
	public function doMobileKjFirendList() {
		global $_W, $_GPC;
		$uid = $_GPC['uid'];
		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $user['kid']);
		$friends = array();
		if (!empty($uid)) {
			$sql = "select nickname as user_name, ac as action, kname as seq_name ,kd as seq_weapon, k_price as amount, createtime as time, headimgurl as avatar from"
				. tablename(DBUtil::$TABLE_XKWKJ_FIREND) . " where uid=:uid order by createtime desc  limit 0,".$xkwkj['rank_num'];
			$friends = pdo_fetchall($sql, array(":uid" => $uid));
		}

		$res = array();
		$res["Status"] = 1;
		$res["Message"] = "";
		$res["Data"] = $friends;
		die(json_encode($res));
   }



	/**
	 * author: codeMonkey QQ:2463619823
	 * 订单提交
	 */
	public function  doMobileOrderSubmit()
	{
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$uid = $_GPC['uid'];
		$kid = $_GPC['kid'];
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		$uname = $_GPC['uname'];
		$address = $_GPC['address'];
		$tel = $_GPC['tel'];
		$zipcode = $_GPC['zipcode'];
		$p_model=$_GPC['p_model'];
		MonUtil::emtpyMsg($xkwkj, "砍价活动不存在或已删除");
		MonUtil::emtpyMsg($user, "用户不存在或已删除");
		$orderInfo = $this->findOrderInfo($kid, $uid);
		$orderNo = $this->getOrderNo($kid, $uid);
		 $leftCount = $this->getLeftCount($xkwkj) -1;
		 if ($leftCount < 0) {
			 message("库存已不足，下次再参加活动吧!");
		 }
		
		if (empty($orderInfo)) {//没有该用户的订单 信息
			$order_array = array(
				'order_no' => $orderNo,
				'kid' => $xkwkj['id'],
				'uid' => $user['id'],
				'uname' => $uname,
				'address' => $address,
				'tel' => $tel,
				'zipcode' => $zipcode,
				'openid' => $user['openid'],
				'y_price' => $xkwkj['p_y_price'],
				'kh_price' => $user['price'],
				'yf_price' => $xkwkj['yf_price'],
				'total_price' => $user['price'] + $xkwkj['yf_price'],
				'status' => $this::$KJ_STATUS_XD,
				'p_model'=>$p_model,
				'createtime' => TIMESTAMP

			);

			DBUtil::create(DBUtil::$TABLE_XKWJK_ORDER, $order_array);
			$oid = pdo_insertid();
			$orderInfo = DBUtil::findById(DBUtil::$TABLE_XKWJK_ORDER, $oid);
		}

		if ($orderInfo['status'] == $this::$KJ_STATUS_XD && $xkwkj['pay_type'] == 1) {//立即支付
			$this->toPayTemplate($user, $orderInfo, $xkwkj);
		} else if ($orderInfo['status'] == $this::$KJ_STATUS_XD && $xkwkj['pay_type'] == 2) {//货到付款
			include $this->template('orderInfo');
		}

	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 付款
	 */
    public function doMobilePay() {
		global $_W, $_GPC;
		$oid = $_GPC['oid'];
		$orderInfo = DBUtil::findById(DBUtil::$TABLE_XKWJK_ORDER, $oid);
		MonUtil::emtpyMsg($orderInfo, "订单删除或不存在");

		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $orderInfo['uid']);
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $orderInfo['kid']);
		$userInfo = $this->getCookidUerInfo($xkwkj['id']);
        $this->toPayTemplate($user, $orderInfo, $xkwkj);
	}
	public function toPayTemplate($user,$orderInfo, $xkwkj) {
		global $_W;
		$jsApi = new JsApi_pub($this->xkkjSetting);
		$jsApi->setOpenId($user['openid']);
		$unifiedOrder = new UnifiedOrder_pub($this->xkkjSetting);
		$unifiedOrder->setParameter("openid", $user['openid']);//商品描述
		$unifiedOrder->setParameter("body", "砍价商品" . $xkwkj['p_name']);//商品描述
		$out_trade_no = date('YmdHis', time());
		$unifiedOrder->setParameter("out_trade_no", $out_trade_no);//商户订单号
		//$orderInfo['total_price']
		//$unifiedOrder->setParameter("total_fee", 1);//总金额
		$unifiedOrder->setParameter("total_fee", $orderInfo['total_price']*100);//总金额
		$notifyUrl = $_W['siteroot'] . "addons/" . MON_XKWKJ . "/notify.php";
		$unifiedOrder->setParameter("notify_url", $notifyUrl);//通知地址
		$unifiedOrder->setParameter("trade_type", "JSAPI");//交易类型
		$prepay_id = $unifiedOrder->getPrepayId();
		$jsApi->setPrepayId($prepay_id);
		DBUtil::updateById(DBUtil::$TABLE_XKWJK_ORDER, array('outno' => $out_trade_no), $orderInfo['id']);
		$jsApiParameters = $jsApi->getParameters();
		//$gmCount = $this->getOrderCount($xkwkj['id'], $this::$KJ_STATUS_GM);
		//$leftCount = $this->getLeftCount($xkwkj);
		$leftCount = 3;
		$orderInfo = $this->findOrderInfo($xkwkj['id'], $user['id']);
		include $this->template('order_pay');

	}



	/**
	 * author: codeMonkey QQ:631872807
	 * 订单
	 */
	public function  doMobileOrderInfo()
	{
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		$user = DBUtil::findById(DBUtil::$TABLE_XKWKJ_USER, $uid);
		$userInfo = $this->getCookidUerInfo($xkwkj['id']);
		MonUtil::emtpyMsg($xkwkj, "砍价活动删除或不存在");
		MonUtil::emtpyMsg($user, "用户删除或不存在");
		$orderInfo = $this->findOrderInfo($kid, $uid);
		MonUtil::emtpyMsg($orderInfo, "您还未提交订单");

		include $this->template('orderInfo');

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $kid
	 * @param $uid
	 * @return string
	 * 订单号
	 */
	public function  getOrderNo($kid, $uid)
	{
		return date('YmdHis', time()) . "k" . $kid . "u" . $uid;
	}

	public function  doMobileAuth()
	{
		global $_GPC, $_W;
		$au = $_GPC['au'];
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$params = array();
		$params['kid'] = $kid;
		$params['au'] = $au;
		$params['uid'] = $uid;
		$userInfo = MonUtil::getClientCookieUserInfo(Mon_XKWkjModuleSite::$USER_COOKIE_KEY . "" . $kid);
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
		$kid = $_GPC['kid'];
		$uid = $_GPC['uid'];
		$code = $_GPC ['code'];
		$au = $_GPC['au'];
		$tokenInfo = $this->oauth->getOauthAccessToken($code);
		$userInfo = $this->oauth->getOauthUserInfo($tokenInfo['openid'], $tokenInfo['access_token']);
		MonUtil::setClientCookieUserInfo($userInfo, $this::$USER_COOKIE_KEY . "" . $kid);//保存到cookie
		$params = array();
		$params['kid'] = $kid;
		$params['au'] = $au;
		$params['uid'] = $uid;
		$params['openid'] = $tokenInfo['openid'];
		$redirect_uri = $this->getRedirectUrl($au, $params);
		header("location: $redirect_uri");
	}


	/************************************************管理*********************************/

	/**
	 * 活动管理
	 */
	public function  doWebXKKjManage()
	{

		global $_W, $_GPC;

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_XKWKJ) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $this->weid));
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ) . " WHERE weid =:weid ", array(':weid' => $this->weid));
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_FIREND, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_USER, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ, array('id' => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("xkwkj_manage");

	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 设置
	 */
	public function doWebXKKjSetting()
	{
		global $_GPC, $_W;

		$kjsetting = DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_SETTING, array(':weid' => $this->weid));
		if (checksubmit('submit')) {
			$data = array(
				'weid' => $this->weid,
				'appid' => trim($_GPC['appid']),
				'appsecret' => trim($_GPC['appsecret']),
				'mchid' => trim($_GPC['mchid']),
				'shkey' => trim($_GPC['shkey'])
			);
			if (!empty($kjsetting)) {
				DBUtil::updateById(DBUtil::$TABLE_XKWKJ_SETTING, $data, $kjsetting['id']);
			} else {
				DBUtil::create(DBUtil::$TABLE_XKWKJ_SETTING, $data);
			}
			message('参数设置成功！', $this->createWebUrl('XKKjSetting', array(
				'op' => 'display'
			)), 'success');
		}
		include $this->template("kjsetting");
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 砍价 订单
	 */

	public function  doWebOrderList()
	{

		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$kid = $_GPC['kid'];
		if ($operation == 'display') {

			

			$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);

			if (empty($xkwkj)) {
				message("砍价活动删除或不存在");

			}

			$keyword = $_GPC['keyword'];
			$where = '';
			$params = array(
				':kid' => $kid
			);


			if (!empty($keyword)) {
				$where .= ' and (order_no like :keyword) or (tel like :keyword)';
				$params[':keyword'] = "%$keyword%";

			}
            $status = $_GPC['status'];
			if ($_GPC['status'] != '') {
				$where .= ' and status =:status';
				$params[':status'] = $_GPC['status'];
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_XKWJK_ORDER) . " WHERE kid =:kid " . $where . "  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWJK_ORDER) . " WHERE kid =:kid  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("id" => $id));
			message('删除成功！', $this->createWebUrl('OrderList',array('kid'=>$kid)), 'success');
		} else if($operation == 'fh') {
			$id = $_GPC['id'];
			DBUtil::updateById(DBUtil::$TABLE_XKWJK_ORDER, array('status'=>$this::$KJ_STATUS_YFH), $id );
			message('发货成功！', $this->createWebUrl('OrderList',array('kid'=>$kid)), 'success');
		}

		include $this->template("order_list");
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 订单详细
	 */
	public function  doWebOrderDetail()
	{
		global $_W, $_GPC;
		$kid = $_GPC['kid'];
		$oid = $_GPC['oid'];
		$order = DBUtil::findById(DBUtil::$TABLE_XKWJK_ORDER, $oid);
		$xkwkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);
		include $this->template("order_detail");
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 参与用户
	 */
	public function  doWebJoinUser()
	{
		global $_W, $_GPC;

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'display') {

			$kid = $_GPC['kid'];

			$wkj = DBUtil::findById(DBUtil::$TABLE_XKWKJ, $kid);

			if (empty($wkj)) {
				message("砍价活动删除或不存在");

			}

			$keyword = $_GPC['keyword'];
			$where = '';
			$params = array(
				':kid' => $kid
			);


			if (!empty($keyword)) {
				$where .= ' and (nickname like :nickname)';
				$params[':nickname'] = "%$keyword%";

			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_XKWKJ_USER) . " WHERE kid =:kid " . $where . "  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_USER) . " WHERE kid =:kid  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_FIREND, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_USER, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}


		include $this->template("user_list");


	}


	/**
	 * author: codeMonkey QQ:63187280
	 * 抓奖品记录
	 */
	public function doWebhelpFirend()
	{
		global $_W, $_GPC;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$kid = $_GPC['kid'];


		$keyword = $_GPC['keywords'];
		$where = '';
		$params = array(
			':kid' => $kid

		);


		if (!empty($keyword)) {
			$where .= ' and f.nickname like :nickname';
			$params[':nickname'] = "%$keyword%";
		}

		if (!empty($_GPC['uid'])) {
			$where .= ' and f.uid=:uid';
			$params[':uid'] = $_GPC['uid'];
		}

		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("select * from " . tablename(DBUtil::$TABLE_XKWKJ_FIREND) . " f where f.kid=:kid " . $where . " order by f.createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_FIREND) . " f where f.kid=:kid  " . $where, $params);
			$pager = pagination($total, $pindex, $psize);

		} elseif ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_XKWKJ_FIREND, array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('kj_firends');

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * 抓奖品记录导出
	 */
	public function  doWeborderDownload()
	{
		require_once 'orderdownload.php';
	}

	/**
	 * author: codeMonkey QQ:631872807
	 * 用户信息导出
	 */
	public function  doWebUDownload()
	{
		require_once 'udownload.php';
	}








	/***************************函数********************************/

	public function getJoinCount($xkwkj) {
		$userCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWKJ_USER) . " WHERE kid =:kid", array(":kid" => $xkwkj['id']));
		return $userCount + $xkwkj['v_user'];
	}
	/**
	 * author: codeMonkey QQ:631872807
	 * @param $kid
	 * @param $status
	 * @return bool数量
	 */
	public function getOrderCount($kid, $status)
	{
		$orderCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWJK_ORDER) . " WHERE kid =:kid and status=:status", array(":kid" => $kid, ":status" => $status));
		return $orderCount;
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * @param $kid
	 * 查找剩余数量
	 */
	public function getLeftCount($xkwkj) {

		$orderCount = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_XKWJK_ORDER) . " WHERE kid=:kid ", array( ":kid" => $xkwkj['id']));

		return $xkwkj['p_kc'] - $orderCount;
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * 查找参加用户
	 */
	public function  findJoinUser($kid, $openid)
	{

		return DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_USER, array(':kid' => $kid, ':openid' => $openid));

	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * @param $uid
	 * @param $fopenid
	 * @return bool
	 * 超找帮助用户
	 */
	public function  findHelpFirend($uid, $fopenid)
	{
		return DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_FIREND, array(':uid' => $uid, ':openid' => $fopenid));
	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $type
	 * 获取转向URL
	 */
	public function  getRedirectUrl($type, $parmas = array())
	{
		switch ($type) {

			case Value::$REDIRECT_USER_INDEX://首页
				$redirectUrl = $this->createMobileUrl('index', $parmas, true);
				break;
			case Value::$REDIRECT_KJ:
				$redirectUrl = $this->createMobileUrl('kj', $parmas, true);
				breka;
		}

		return MonUtil::str_murl($redirectUrl);


	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $status
	 * 状态文字
	 */
	public function  getStatusText($status)
	{
		switch ($status) {
			case $this::$KJ_STATUS_WKS:
				return "未开始";
				break;
			case $this::$KJ_STATUS_ZC:
				return "正常";
				break;
			case $this::$KJ_STATUS_JS:
				return "已结束";
				break;
			case $this::$KJ_STATUS_XD:
				return "已下单";
				break;
			case $this::$KJ_STATUS_GM:
				return "已支付购买";
			break;
			case $this::$KJ_STATUS_YFH:
			return "已发货";
			break;
		}

	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $wkj
	 * @param $joinUser
	 * @return int
	 * 状态获取
	 */
	public function getStatus($xkwkj, $joinUser)
	{


		if (TIMESTAMP < $xkwkj['starttime']) {
			return $this::$KJ_STATUS_WKS;
		}
		if (TIMESTAMP > $xkwkj['endtime']) {
			return $this::$KJ_STATUS_JS;
		}

		$orderInfo = $this->findOrderInfo($xkwkj['id'], $joinUser['id']);


		if (empty($orderInfo)) {
			return $this::$KJ_STATUS_ZC;
		} else {
			return $orderInfo['status'];
		}


	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * @param $kid
	 * @param $uid
	 * @return bool 超找 订单信息
	 */
	public function findOrderInfo($kid, $uid)
	{
		$orderInfo = DBUtil::findUnique(DBUtil::$TABLE_XKWJK_ORDER, array(':kid' => $kid, ':uid' => $uid));
		return $orderInfo;
	}

	/**
	 * author: codeMonkey QQ:2463619823
	 * @param $wkj
	 * @param $user
	 * 获取砍价的价格
	 */
	public function getKjPrice($xkwkj, $userNowPrice)
	{

		if (empty($xkwkj['kj_rule'])) {
			return 0;
		}

		if ($userNowPrice <= $xkwkj['p_low_price']) {
			return 0;
		}

		$kj_rule = unserialize($xkwkj['kj_rule']);
		$kj_price = 0;
		$inRule = false;
		foreach ($kj_rule as $rule) {

			if ($userNowPrice >= $rule['rule_pice']) {
				$kj_price = rand($rule['rule_start'] * 10, $rule['rule_end'] * 10) / 10;
				$inRule = true;
				break;
			}

		}

		if (!$inRule) {
			$kj_price = rand(1 * 10, 2 * 10) / 10;
		}

		if ($userNowPrice - $kj_price < $xkwkj['p_low_price']) {
			return 0;
		}

		return $kj_price;
	}


	/**
	 * author: codeMonkey QQ:631872807
	 * @param $wkj
	 * @param $msg_type
	 * @return mixed
	 * 获取随机文字信息
	 */
	public function  getTipMsg($wkj, $msg_type)
	{
		if (empty($wkj)) {
			reutrn;
		}
		switch ($msg_type) {
			case $this::$TIP_DIALOG:
				$msgContent = $wkj['kj_dialog_tip'];
				break;
			case $this::$TIP_U_FIRST:
				$msgContent = $wkj['u_fist_tip'];
				break;
			case $this::$TIP_U_ALREADY:
				$msgContent = $wkj['u_already_tip'];
				break;
			case $this::$TIP_RANK:
				$msgContent = $wkj['rank_tip'];
				break;
			case $this::$TIP_FK_FIRST:
				$msgContent = $wkj['fk_fist_tip'];
				break;
			case $this::$TIP_FK_ALREADY:
				$msgContent = $wkj['fk_already_tip'];
				break;
		}
		$msgContent = trim($msgContent);
		$msg_arr = explode("\r\n", $msgContent);
		if (count($msg_arr) == 1) {
			return $msg_arr[0];
		} else {
			return $msg_arr[rand(0, count($msg_arr) - 1)];
		}
	}

	public function findXKKJsetting()
	{
		$xkkjsetting = DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_SETTING, array(":weid" => $this->weid));
		return $xkkjsetting;
	}

	function  encode($value,$dc)
	{

		if ($dc == 1) {
			return $value;
		}

		if($dc == 2) {
			return iconv("utf-8", "gb2312", $value);
		}

	}

	public function doWebDeleteXkWkj()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $kid) {
			$id = intval($kid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_FIREND, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_USER, array("kid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ, array('id' => $id));
		}
		echo json_encode(array('code' => 200));
	}
	/***
	 * 批量删除
	**/
	/**
	 * author: codeMonkey QQ:2463619823
	 * 删除摇一摇
	 */
	public function doWebDeleteUser()
	{
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $uid) {
			$id = intval($uid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_FIREND, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_XKWKJ_USER, array("id" => $id));
		}
		echo json_encode(array('code' => 200));
	}

	public function doWebDeleteOrder()
	{
		global $_GPC, $_W;
		foreach ($_GPC['idArr'] as $k => $oid) {
			$id = intval($oid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_XKWJK_ORDER, array("id" => $id));

		}
		echo json_encode(array('code' => 200));
	}


}