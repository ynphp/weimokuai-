<?php
/**
 * 男神来了模块微站定义
 *
 * @author 毛线
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
load()->func('communication');
class Mx_nanshenModuleSite extends WeModuleSite {
	public $userjj;
	

	public function doMobileNanshen() {
		global $_GPC, $_W;
		load()->func('tpl');
		$settings=$this->module['config'];
		//var_dump($_W);die;
		if ($_W['oauth_account']['key']) {
		    $appid = $_W['oauth_account']['key'];
		    $secret = $_W['oauth_account']['secret'];
		}else{
		   die("公众号没权限");
		}
		$isay = $this->mbstringtostr($settings['isay'],'utf-8','\',\'');
		$string = $_SERVER['REQUEST_URI'];
		if ($_GPC['code']) {			  
			$this->userjj = $this->useroauth();
			$headimgurl = $this->userjj['headimgurl'];
			$nickname = $this->userjj['nickname'];
		}else{
			$url = $_W['siteroot'] . substr($_SERVER['REQUEST_URI'],4);
			//var_dump($url);die;
            $this->getOauthCode($url,$appid);
		}
		//var_dump($this->userjj['headimgurl'] );die;
		$temp = 't'.$settings['template'];
		include $this->template($temp);
	}
	private function mbstringtostr($str,$charset,$params) {
		$strlen=mb_strlen($str);
		while($strlen){
			$array .= mb_substr($str,0,1,$charset).$params;
			$str=mb_substr($str,1,$strlen,$charset);
			$strlen=mb_strlen($str);
		}
		return $array;
	}
	private function getOauthCode($data, $key)
    {
        global $_GPC, $_W;
        $forward = urlencode($data);
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $key . '&redirect_uri=' . $forward . '&response_type=code&scope=snsapi_userinfo&wxref=mp.weixin.qq.com#wechat_redirect';
        header('location:' . $url);
    }
	private function useroauth()
    {
        global $_GPC, $_W;
        
     
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            die("本页面仅支持微信访问!非微信浏览器禁止浏览!");

        }
		
				$code = $_GPC['code'];
				$serverapp = $_W['account']['level'];	//是否为高级号
				if ($serverapp==4) {
					$appid = $_W['account']['key'];
					$secret = $_W['account']['secret'];
				}else{
					if ($_W['oauth_account']['key']) {
					$appid = $_W['oauth_account']['key'];
					$secret = $_W['oauth_account']['secret'];
					}else{
					   die("公众号没权限");
					}
				}
                $key = $appid;
                $secret =  $secret;
                $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $key . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';
                $data = ihttp_get($url);
                if ($data['code'] != 200) {
                    message('诶呦,网络异常..请稍后再试..');
                }
                $temp = @json_decode($data['content'], true);
                $access_token = $temp['access_token'];
				//var_dump($data);die;
                $openid = $temp['openid'];
                $user_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid;
                $user_temp = ihttp_get($user_url);
                if ($user_temp['code'] != 200) {
                    message('诶呦,网络异常..请稍后再试..');
                }
                $user = @json_decode($user_temp['content'], true);
                if (!empty($user['errocde']) || $user['errocde'] != 0) {
                    message(account_weixin_code($user['errcode']), '', 'error');//调试用查看报错提示
                }
                if (empty($fromuser)) {
                    $from_user = $openid;
                }
		return $user;
    }
}