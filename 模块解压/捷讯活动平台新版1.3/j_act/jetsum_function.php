<?php 
//***加密函数***//
/*
$str = 'abc'; 
$key = 'www.helloweba.com'; 
echo '加密:'.encrypt($str, 'E', $key); 
echo '解密：'.encrypt($str, 'D', $key);
 */

function _getFansInfo($_openid){
	global $_W;
	if(!$_openid)return false;
	load()->func('communication');
	$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
	$acc = WeAccount::create($acid);
	$tokens=$acc->fetch_token();
	$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$tokens."&openid=".$_openid;
	$content = ihttp_get($url);
	if(is_error($content))return false;
	$token = @json_decode($content['content'], true);
	if(empty($token) || !is_array($token)) {
		$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
		$errorinfo = @json_decode($errorinfo, true);
		return false;
	}
	return $token;
}
function _sendText($openid,$txt){
	global $_W;
	$acid=$_W['account']['acid'];
	if(!$acid){
		$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
	}
	$acc = WeAccount::create($acid);
	$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'text','text'=>array('content'=>urlencode($txt))));
	return $data;
}
function _sendMBText($touser,$template_id, $postdata, $url = '', $topcolor = '#FF683F'){
	global $_W;
	$acid=$_W['account']['acid'];
	if(!$acid){
		$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
	}
	$acc = WeAccount::create($acid);
	$data = $acc->sendTplNotice($touser,$template_id,$postdata,$url,$topcolor);
	return $data;
}
function saveMedia($url){
	global $_W;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);    
	curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$package = curl_exec($ch);
	$httpinfo = curl_getinfo($ch);
	curl_close($ch);
	$media = array_merge(array('mediaBody' => $package), $httpinfo);
	//求出文件格式
	preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
	$extAry=array('jpg','jpeg','png','gif','bmp');
	$fileExt = strtolower($extmatches[1]);
	if(!in_array($fileExt,$extAry)){
		return 0;
	}
	$filename = time().rand(100,999).".".$fileExt;
	$dirname = "../attachment/images/".$_W['uniacid'].'/'.date("Y")."/".date('m')."/";
	if(!file_exists($dirname)){
		mkdir($dirname,0777,true);
	}
	file_put_contents($dirname.$filename,$media['mediaBody']);
	return $dirname.$filename;
}
/*
*签名
*/
function getNonceStr($length = 32) {
	$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
	$str ="";
	for ( $i = 0; $i < $length; $i++ )  {  
		$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
	} 
	return $str;
}
function ToUrlParams($parama=array()){
	$buff = "";
	foreach ($parama as $k => $v)
	{
		if($k != "sign" && $v != "" && !is_array($v)){
			$buff .= $k . "=" . $v . "&";
		}
	}
	$buff = trim($buff, "&");
	return $buff;
}
function MakeSign($parama=array(),$key){
	ksort($parama);
	$string = ToUrlParams($parama);
	$string = $string . "&key=".$key;
	$string = md5($string);
	$result = strtoupper($string);
	return $result;
}
function postXmlCurl($xml, $url, $second = 30){		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_TIMEOUT, $second);
	curl_setopt($ch,CURLOPT_URL, $url);
	/*curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验*/
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);//严格校验
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	$data = curl_exec($ch);
	if($data){
		curl_close($ch);
		return $data;
	} else { 
		$error = curl_errno($ch);
		curl_close($ch);
		return("curl出错，错误码:$error");
	}
}
function postXmlAndPemCurl($xml, $url, $pemary){		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_TIMEOUT, $second);
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	//证书开始
	curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
	curl_setopt($ch,CURLOPT_SSLCERT, $pemary['cert']);
	curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
	curl_setopt($ch,CURLOPT_SSLKEY, $pemary['key']);
	//证书结束
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	$data = curl_exec($ch);
	if($data){
		curl_close($ch);
		return $data;
	} else { 
		$error = curl_errno($ch);
		curl_close($ch);
		return("curl出错，错误码:$error");
	}
}
function ToXml($parama=array()){
	$xml = "<xml>";
	foreach ($parama as $key=>$val)
	{
		if (is_numeric($val)){
			$xml.="<".$key.">".$val."</".$key.">";
		}else{
			$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
		}
	}
	$xml.="</xml>";
	return $xml; 
}
function FromXml($xml){	
	if(!$xml)return "xml数据异常！";
	libxml_disable_entity_loader(true);
	$result = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
	return $result;
}


?>