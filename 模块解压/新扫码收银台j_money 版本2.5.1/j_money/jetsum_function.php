<?php 
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
	$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
	$acc = WeAccount::create($acid);
	$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'text','text'=>array('content'=>urlencode($txt))));
	return $data;
}
function _sendMBText($touser,$template_id, $postdata, $url = '', $topcolor = '#FF683F'){
	global $_W;
	$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
	$acc = WeAccount::create($acid);
	$data = $acc->sendTplNotice($touser,$template_id,$postdata,$url,$topcolor);
	return $data;
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

function GetBankType($type="CFT"){
	if(!$type)return false;
	$data=array("CFT"=>"零钱包","ICBC_DEBIT"=>"工商银行(借记卡)","ICBC_CREDIT"=>"工商银行(信用卡)","ABC_DEBIT"=>"农业银行(借记卡)","ABC_CREDIT"=>"农业银行(信用卡)","PSBC_DEBIT"=>"邮政储蓄银行(借记卡)","PSBC_CREDIT"=>"邮政储蓄银行(信用卡)","CCB_DEBIT"=>"建设银行(借记卡)","CCB_CREDIT"=>"建设银行(信用卡)","CMB_DEBIT"=>"招商银行(借记卡)","CMB_CREDIT"=>"招商银行(信用卡)","BOC_DEBIT"=>"中国银行(借记卡)","BOC_CREDIT"=>"中国银行(信用卡)","COMM_DEBIT"=>"交通银行(借记卡)","SPDB_DEBIT"=>"浦发银行(借记卡)","SPDB_CREDIT"=>"浦发银行(信用卡)","GDB_DEBIT"=>"广发银行(借记卡)","GDB_CREDIT"=>"广发银行(信用卡)","CMBC_DEBIT"=>"民生银行(借记卡)","CMBC_CREDIT"=>"民生银行(信用卡)","PAB_DEBIT"=>"平安银行(借记卡)","PAB_CREDIT"=>"平安银行(信用卡)","CEB_DEBIT"=>"光大银行(借记卡)","CEB_CREDIT"=>"光大银行(信用卡)","CIB_DEBIT"=>"兴业银行(借记卡)","CIB_CREDIT"=>"兴业银行(信用卡)","CITIC_DEBIT"=>"中信银行(借记卡)","CITIC_CREDIT"=>"中信银行(信用卡)","BOSH_DEBIT"=>"上海银行(借记卡)","BOSH_CREDIT"=>"上海银行(信用卡)","CRB_DEBIT"=>"华润银行(借记卡)","HZB_DEBIT"=>"杭州银行(借记卡)","HZB_CREDIT"=>"杭州银行(信用卡)","BSB_DEBIT"=>"包商银行(借记卡)","BSB_CREDIT"=>"包商银行(信用卡)","CQB_DEBIT"=>"重庆银行(借记卡)","SDEB_DEBIT"=>"顺德农商行(借记卡)","SZRCB_DEBIT"=>"深圳农商银行(借记卡)","HRBB_DEBIT"=>"哈尔滨银行(借记卡)","BOCD_DEBIT"=>"成都银行(借记卡)","GDNYB_DEBIT"=>"南粤银行(借记卡)","GDNYB_CREDIT"=>"南粤银行(信用卡)","GZCB_DEBIT"=>"广州银行(借记卡)","GZCB_CREDIT"=>"广州银行(信用卡)","JSB_DEBIT"=>"江苏银行(借记卡)","JSB_CREDIT"=>"江苏银行(信用卡)","NBCB_DEBIT"=>"宁波银行(借记卡)","NBCB_CREDIT"=>"宁波银行(信用卡)","NJCB_DEBIT"=>"南京银行(借记卡)","JZB_DEBIT"=>"晋中银行(借记卡)","KRCB_DEBIT"=>"昆山农商(借记卡)","LJB_DEBIT"=>"龙江银行(借记卡)","LNNX_DEBIT"=>"辽宁农信(借记卡)","LZB_DEBIT"=>"兰州银行(借记卡)","WRCB_DEBIT"=>"无锡农商(借记卡)","ZYB_DEBIT"=>"中原银行(借记卡)","ZJRCUB_DEBIT"=>"浙江农信(借记卡)","WZB_DEBIT"=>"温州银行(借记卡)","XAB_DEBIT"=>"西安银行(借记卡)","JXNXB_DEBIT"=>"江西农信(借记卡)","NCB_DEBIT"=>"宁波通商银行(借记卡)","NYCCB_DEBIT"=>"南阳村镇银行(借记卡)","NMGNX_DEBIT"=>"内蒙古农信(借记卡)","SXXH_DEBIT"=>"陕西信合(借记卡)","SRCB_CREDIT"=>"上海农商银行(信用卡)","SJB_DEBIT"=>"盛京银行(借记卡)","SDRCU_DEBIT"=>"山东农信(借记卡)","SRCB_DEBIT"=>"上海农商银行(借记卡)","SCNX_DEBIT"=>"四川农信(借记卡)","QLB_DEBIT"=>"齐鲁银行(借记卡)","QDCCB_DEBIT"=>"青岛银行(借记卡)","PZHCCB_DEBIT"=>"攀枝花银行(借记卡)","ZJTLCB_DEBIT"=>"浙江泰隆银行(借记卡)","TJBHB_DEBIT"=>"天津滨海农商行(借记卡)","WEB_DEBIT"=>"微众银行(借记卡)","YNRCCB_DEBIT"=>"云南农信(借记卡)","WFB_DEBIT"=>"潍坊银行(借记卡)","WHRC_DEBIT"=>"武汉农商行(借记卡)","ORDOSB_DEBIT"=>"鄂尔多斯银行(借记卡)","XJRCCB_DEBIT"=>"新疆农信银行(借记卡)","ORDOSB_CREDIT"=>"鄂尔多斯银行(信用卡)","CSRCB_DEBIT"=>"常熟农商银行(借记卡)","JSNX_DEBIT"=>"江苏农商行(借记卡)","GRCB_CREDIT"=>"广州农商银行(信用卡)","GLB_DEBIT"=>"桂林银行(借记卡)","GDRCU_DEBIT"=>"广东农信银行(借记卡)","GDHX_DEBIT"=>"广东华兴银行(借记卡)","FJNX_DEBIT"=>"福建农信银行(借记卡)","DYCCB_DEBIT"=>"德阳银行(借记卡)","DRCB_DEBIT"=>"东莞农商行(借记卡)","CZCB_DEBIT"=>"稠州银行(借记卡)","CZB_DEBIT"=>"浙商银行(借记卡)","CZB_CREDIT"=>"浙商银行(信用卡)","GRCB_DEBIT"=>"广州农商银行(借记卡)","CSCB_DEBIT"=>"长沙银行(借记卡)","CQRCB_DEBIT"=>"重庆农商银行(借记卡)","CBHB_DEBIT"=>"渤海银行(借记卡)","BOIMCB_DEBIT"=>"内蒙古银行(借记卡)","BOD_DEBIT"=>"东莞银行(借记卡)","BOD_CREDIT"=>"东莞银行(信用卡)","BOB_DEBIT"=>"北京银行(借记卡)","BNC_DEBIT"=>"江西银行(借记卡)","BJRCB_DEBIT"=>"北京农商行(借记卡)","AE_CREDIT"=>"AE(信用卡)","GYCB_CREDIT"=>"贵阳银行(信用卡)","JSHB_DEBIT"=>"晋商银行(借记卡)","JRCB_DEBIT"=>"江阴农商行(借记卡)","JNRCB_DEBIT"=>"江南农商(借记卡)","JLNX_DEBIT"=>"吉林农信(借记卡)","JLB_DEBIT"=>"吉林银行(借记卡)","JJCCB_DEBIT"=>"九江银行(借记卡)","HXB_DEBIT"=>"华夏银行(借记卡)","HXB_CREDIT"=>"华夏银行(信用卡)","HUNNX_DEBIT"=>"湖南农信(借记卡)","HSB_DEBIT"=>"徽商银行(借记卡)","HSBC_DEBIT"=>"恒生银行(借记卡)","HRXJB_DEBIT"=>"华融湘江银行(借记卡)","HNNX_DEBIT"=>"河南农信(借记卡)","HKBEA_DEBIT"=>"东亚银行(借记卡)","HEBNX_DEBIT"=>"河北农信(借记卡)","HBNX_DEBIT"=>"湖北农信(借记卡)","HBNX_CREDIT"=>"湖北农信(信用卡)","GYCB_DEBIT"=>"贵阳银行(借记卡)","GSNX_DEBIT"=>"甘肃农信(借记卡)","JCB_CREDIT"=>"JCB(信用卡)","MASTERCARD_CREDIT"=>"MASTERCARD(信用卡)","VISA_CREDIT"=>"VISA(信用卡)",);
	return $data[$type];
}

function isMobile(){ 
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
        return true;
    } 
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])){ 
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])){
        $clientkeywords = array ('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))){
            return true;
        } 
    } 
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])){ 
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
} 
?>