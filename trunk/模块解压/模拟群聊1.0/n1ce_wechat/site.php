<?php
/**
 * 模拟群聊模块微站定义
 *
 * @author n1ce   QQ：541535641
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
include "model.php";
define('RES', '../addons/n1ce_wechat/template/mobile/');


class N1ce_wechatModuleSite extends WeModuleSite {

	public $_appid = '';
    public $_appsecret = '';
    public $_accountlevel = '';
    public $_account = '';

    public $_weid = '';
    public $_fromuser = '';
    public $_nickname = '';
    public $_headimgurl = '';

    public $_auth2_openid = '';
    public $_auth2_nickname = '';
    public $_auth2_headimgurl = '';

    function __construct()
    {
        global $_W, $_GPC;
        $this->_weid = $_W['uniacid'];
        $this->_fromuser = $_W['fans']['from_user']; //debug
        if ($_SERVER['HTTP_HOST'] == '127.0.0.1' || $_SERVER['HTTP_HOST'] == 'localhost:8888' || $_SERVER['HTTP_HOST'] == '192.168.1.102:8888') {
            $this->_fromuser = 'debug';
        }

        $this->_auth2_openid = 'auth2_openid_' . $_W['uniacid'];
        $this->_auth2_nickname = 'auth2_nickname_' . $_W['uniacid'];
        $this->_auth2_headimgurl = 'auth2_headimgurl_' . $_W['uniacid'];

        $this->_appid = '';
        $this->_appsecret = '';
        $this->_accountlevel = $_W['account']['level']; //是否为高级号

        if (isset($_COOKIE[$this->_auth2_openid])) {
            $this->_fromuser = $_COOKIE[$this->_auth2_openid];
        }

        if ($this->_accountlevel < 4) {
            $setting = uni_setting($this->_weid);
            $oauth = $setting['oauth'];
            if (!empty($oauth) && !empty($oauth['account'])) {
                $this->_account = account_fetch($oauth['account']);
                $this->_appid = $this->_account['key'];
                $this->_appsecret = $this->_account['secret'];
            }
        } else {
            $this->_appid = $_W['account']['key'];
            $this->_appsecret = $_W['account']['secret'];
        }
    }

	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;
		$weid = $this->_weid;
        $from_user = $this->_fromuser;
		$title = $this->module['config']['title'];
		$desc = $this->module['config']['desc'];
        $pic = $this->module['config']['pic'];
		$pic = tomedia($pic);
		$s_url = $this->module['config']['s_url'];
        $method = 'index';
        $authurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method) . '&authkey=1';
        $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method);
        if (isset($_COOKIE[$this->_auth2_openid])) {
            $from_user = $_COOKIE[$this->_auth2_openid];
            $nickname = $_COOKIE[$this->_auth2_nickname];
            $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
        } else {
            if (isset($_GPC['code'])) {
                $userinfo = $this->oauth2($authurl);
                if (!empty($userinfo)) {
                    $from_user = $userinfo["openid"];
                    $nickname = $userinfo["nickname"];
                    $headimgurl = $userinfo["headimgurl"];
                } else {
                    message('授权失败!');
                }
            } else {
                if (!empty($this->_appsecret)) {
                    $this->getCode($url);
                }
            }
        }

        if (empty($from_user)) {
            message('抱歉，粉丝不存在！', '', 'error');
        }
        //分享信息
		$sql = "SELECT * FROM " . tablename('n1ce_wechat') . " WHERE `uniacid`=:uniacid ORDER BY `id` DESC";
		$params = array(':uniacid' => $_W['uniacid']);
		$wechat = pdo_fetch($sql, $params);
        //$wechat = "SELECT * FROM " . tablename('n1ce_wechat') . " WHERE `uniacid`=:uniacid ORDER BY `id` DESC" , array(':uniacid'=>$_W['uniacid']);
        include $this->template('index');
	}
	public function doWebChat() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		load()->func('tpl');
		if(checksubmit('ac')){
			$data['uniacid'] = $_W['uniacid'];
			$data['title'] = $_GPC['title'];
			$data['qname'] = $_GPC['qname'];
			
			$data['aimg'] = $_GPC['aimg'];
			$data['atxt'] = $_GPC['atxt'];
			$data['bimg'] = $_GPC['bimg'];
			$data['p1'] = $_GPC['p1'];
			$data['btxt'] = $_GPC['btxt'];
			$data['cimg'] = $_GPC['cimg'];
			$data['ctxt'] = $_GPC['ctxt'];
			$data['p2'] = $_GPC['p2'];
			$data['dimg'] = $_GPC['dimg'];
			$data['dtxt'] = $_GPC['dtxt'];
			$data['p3'] = $_GPC['p3'];
			$data['etxt'] = $_GPC['etxt'];
			$data['eimg'] = $_GPC['eimg'];
			$data['ftxt'] = $_GPC['ftxt'];
			

			//存入数据库
			$res = pdo_insert('n1ce_wechat',$data);
			if($res){
			//suess
				message('编辑活动成功','refresh','success');
			}else{
			//失败
				message('编辑活动失败','referer','error');
			}
			//$activity = $sql = "select * from ".tablename('n1ce_activity')." where `uniacid`=:uniacid",array(':uniacid'=$_W['uniacid']);
		}
			$sql = "SELECT * FROM " . tablename('n1ce_wechat') . " WHERE `uniacid`=:uniacid ORDER BY `id` DESC";
			$params = array(':uniacid' => $_W['uniacid']);
			$wechat = pdo_fetch($sql, $params);
			include $this->template('chat');
	}
	public function oauth2($url)
    {
        global $_GPC, $_W;
        load()->func('communication');
        $code = $_GPC['code'];
        if (empty($code)) {
            message('code获取失败.');
        }
        $token = $this->getAuthorizationCode($code);
        $from_user = $token['openid'];
        $userinfo = $this->getUserInfo($from_user);
        $sub = 1;
        if ($userinfo['subscribe'] == 0) {
            //未关注用户通过网页授权access_token
            $sub = 0;
            $authkey = intval($_GPC['authkey']);
            if ($authkey == 0) {
                $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
                header("location:$oauth2_code");
            }
            $userinfo = $this->getUserInfo($from_user, $token['access_token']);
        }

        if (empty($userinfo) || !is_array($userinfo) || empty($userinfo['openid']) || empty($userinfo['nickname'])) {
            echo '<h1>2获取微信公众号授权失败[无法取得userinfo], 请稍后重试！ 公众平台返回原始数据为: <br />' . $sub . $userinfo['meta'] . '<h1>';
            exit;
        }

        //设置cookie信息
        setcookie($this->_auth2_headimgurl, $userinfo['headimgurl'], time() + 3600 * 24);
        setcookie($this->_auth2_nickname, $userinfo['nickname'], time() + 3600 * 24);
        setcookie($this->_auth2_openid, $from_user, time() + 3600 * 24);
        setcookie($this->_auth2_sex, $userinfo['sex'], time() + 3600 * 24);
        return $userinfo;
    }

	public function getUserInfo($from_user, $ACCESS_TOKEN = '')
    {
        if ($ACCESS_TOKEN == '') {
            $ACCESS_TOKEN = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        } else {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        }

        $json = ihttp_get($url);
        $userInfo = @json_decode($json['content'], true);
        return $userInfo;
    }

	 public function getAuthorizationCode($code)
    {
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}&secret={$this->_appsecret}&code={$code}&grant_type=authorization_code";
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            echo '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
//            echo '<h1>1获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit;
        }
        return $token;
    }

	 public function getAccessToken()
    {
        global $_W;
        $account = $_W['account'];
        if($this->_accountlevel < 4){
            if (!empty($this->_account)) {
                $account = $this->_account;
            }
        }

        load()->classs('weixin.account');
        $accObj= WeixinAccount::create($account['acid']);
        $access_token = $accObj->fetch_token();
        return $access_token;
    }

	 public function getCode($url)
    {
        global $_W;
        $url = urlencode($url);
        $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
        header("location:$oauth2_code");
    }

}