<?php
defined('IN_IA') or exit('Access Denied');

if (!function_exists('dump')) {
  function dump($arr){
  	echo '<pre>'.print_r($arr,TRUE).'</pre>';
  }
}

abstract class jssdk extends WeModuleSite{
  public static $appId;
  public static function get_curl($url){
  	$ch=curl_init($url);
  	curl_setopt($ch, CURLOPT_HEADER, 0);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  	$data  =  curl_exec($ch);
  	curl_close($ch);
  	return json_decode($data,1);
  }
  public static function post_curl($url,$post=''){
  	$ch=curl_init($url);
  	curl_setopt($ch, CURLOPT_HEADER, 0);
  	curl_setopt($ch, CURLOPT_POST, 1);
  	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  	$data  =  curl_exec($ch);
  	curl_close($ch);
  	return json_decode($data,1);
  }
/**
* 
* @param 生成随机字符串
* 
* @return
*/
  public static function get_randstr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }
  /**
  * 存储全局 jsapi_ticket
  * 
  * @return
  */
  public static function get_jsapi_ticket() {
  	global $_W;
    if ($_W['acid']==0) return '';
    load()->func('cache');
    $jsapi_ticket=cache_read('jsapi_ticket_'.$_W['acid']);
    $jsapi_ticket = iunserializer($jsapi_ticket);
    self::$appId=empty($jsapi_ticket['appId'])?'':$jsapi_ticket['appId'];
    if (is_array($jsapi_ticket)&&!empty( $jsapi_ticket['ticket'])&&!empty( $jsapi_ticket['expire'])&&$jsapi_ticket['expire'] > TIMESTAMP) {
      return $jsapi_ticket['ticket'];
    }
  	
    $access_token =self::get_access_token();
    $url = "http://api.weixin.qq.com/cgi-bin/ticket/getticket?type=1&access_token=$access_token";
    $arr =self::get_curl($url);
    if(empty($arr['ticket']) || empty($arr['expires_in'])) {
      message('获取微信公众号授权失败, 请稍后重试！ 公众平台返回原始数据为: <br />' .$arr['errcode']. $arr['errmsg']);
  	}
    $record = array();
  	$record['ticket'] = $arr['ticket'];
    $record['appId'] = self::$appId;
  	$record['expire'] =TIMESTAMP + $arr['expires_in'];
    cache_write('jsapi_ticket_'.$_W['acid'],iserializer($record));
    return $record['ticket'];
  } 
  
/**
* 全局票据access_token
*/
  public static function get_access_token(){
  	global $_W,$_GPC;
    load()->model('account');
    load()->func('communication');
    $accounts = uni_accounts();
    $account=$accounts[$_W['acid']];
    if (!is_array($account)) message('获取acid失败, 请稍后重试！');
    $account['access_token']=empty($account['access_token'])?'':iunserializer($account['access_token']);
    self::$appId=$account['key'];
    if(is_array($account['access_token']) && !empty($account['access_token']['token']) && !empty($account['access_token']['expire']) && $account['access_token']['expire'] > TIMESTAMP) {
      return $account['access_token']['token'];
    }
    if (empty($account['key']) || empty($account['secret'])) {
      message('请填写公众号的appid及appsecret, (需要你的号码为微信服务号)！', url('account/bind/post', array('acid' => $account['acid'], 'uniacid' => $account['uniacid'])), 'error');
    }
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$account['key']}&secret={$account['secret']}";
    $content = ihttp_get($url);
    if(is_error($content)) {
      message('获取微信公众号授权失败, 请稍后重试！错误详情: ' . $content['message']);
    }
    $token = @json_decode($content['content'], true);
    if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
      $errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
      $errorinfo = @json_decode($errorinfo, true);
      message('获取微信公众号授权失败, 请稍后重试！ 公众平台返回原始数据为: 错误代码-' . $errorinfo['errcode'] . '，错误信息-' . $errorinfo['errmsg']);
    }
    $record = array();
    $record['token'] = $token['access_token'];
    $record['expire'] = TIMESTAMP + $token['expires_in'];
    $row = array();
    $row['access_token'] = iserializer($record);
    pdo_update('account_wechats', $row, array('acid' => $account['acid']));
    return $record['token'];
  } 
/**
* 获得签名
* 
* @return
*/
public static function get_sign() {
  	global $_W;
    $jsapi_ticket = self::get_jsapi_ticket();
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $timestamp = time();
    $nonceStr =self::get_randstr();
    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapi_ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => self::$appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }
}
?>