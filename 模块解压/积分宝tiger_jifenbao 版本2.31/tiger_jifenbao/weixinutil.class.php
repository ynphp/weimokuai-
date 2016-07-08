<?php
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
class WXutil extends WeModule{
	public static function sendcustomMsg($uniacid,$openid,$msg) {
// 		load()->func('logging');
		load()->func('communication');
		$accessToken = WXutil::getWeixinToken($uniacid);
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$accessToken}";
// 		logging_run("msg:".var_export($msg,true),'info',date('Ymd'));
		$msg = str_replace('"', '\\"', $msg);
		$post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $msg . '"}}';
		ihttp_post($url, $post);
	}

	public static function getWeixinToken($uniacid){
		load()->classs('weixin.account');
		$accObj= WeixinAccount::create($uniacid);
		return $accObj->fetch_token();
	}
}