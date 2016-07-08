<?php

defined('IN_IA') or exit('Access Denied');

class poster{

    
    //获取scene id
	public function get_next_avaliable_scene_id($openid) {
		global $_W;
		
		$sql = 'SELECT scene_id FROM ' . tablename('xhbdz_poster') . ' WHERE uniacid=:uniacid AND openid=:openid';
		
		$pars = array('uniacid' => $_W['uniacid'],'openid'=>$openid);
		
		$scene_id = pdo_fetchcolumn($sql, $pars);
		$scene_id = $scene_id + 1;
		
		/*if (empty($scene_id)) {
			pdo_insert(xhbdz_poster, array('uniacid' => $_W['uniacid'],'openid'=>$openid));
		}else {
			$scene_id++;
			pdo_update(xhbdz_poster, array('scene_id' => $scene_id), array('uniacid' => $_W['uniacid'],'openid'=>$openid));
		}*/
		return $scene_id;
	}
	
	
	
	public function getkeyOpenid($ticket) {
		    global $_W;
	    $uniacid = $_W['uniacid'];
		$exist = pdo_fetch('SELECT `openid` FROM '.tablename('xhbdz_poster')." WHERE `uniacid` = $uniacid AND `ticket` = '$ticket'");
		if (!empty($exist)) {
			return $exist;
		}
		return false;
	}
	
	public function uploadRes($access_token, $img,$ticket,$openid) {
	    
	    global $_W;
	    
	    $uniacid = $_W['uniacid'];
	    $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=image";

	    
		//现在时间
	    $time = time();
		//有效时间
		$Stime = strtotime("-4 day");

	    $poster = pdo_fetch('SELECT `scene_id`,`media_id` FROM '.tablename('xhbdz_poster')." WHERE `uniacid` = $uniacid AND `openid` = '$openid'  AND  `createtime` < $time AND `createtime` > $Stime");
	    if (empty($poster['media_id'])){
	       pdo_delete(xhbdz_poster,array('scene_id'=>$poster['scene_id']));
	    $post = array(
	        'media' => new CURLFILE($img)
	    );
	    
	    $result = $this->http_post($url,$post,true);
	    
	    if ($result)
	    {
	        $json = json_decode($result,true);
	        if (!$json || !empty($json['errcode'])) {
	            $this->errCode = $json['errcode'];
	            $this->errMsg = $json['errmsg'];
	            return $json['errmsg'];
	        }
	        pdo_insert(xhbdz_poster, array('ticket' => $ticket,'media_id'=>$json{'media_id'},'uniacid' => $_W['uniacid'],'openid'=>$openid,'createtime'=>time()));
	        return $json{'media_id'};
	    }
	    return false;
	    }else {
	        return $poster['media_id'];
	    }
	}
	
	private function http_post($url,$param,$post_file=false){
	    $oCurl = curl_init();
	    if(stripos($url,"https://")!==FALSE){
	        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
	        curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
	    }
	    if (is_string($param) || $post_file) {
	        $strPOST = $param;
	    } else {
	        $aPOST = array();
	        foreach($param as $key=>$val){
	            $aPOST[] = $key."=".urlencode($val);
	        }
	        $strPOST =  join("&", $aPOST);
	    }
	    curl_setopt($oCurl, CURLOPT_URL, $url);
	    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	    curl_setopt($oCurl, CURLOPT_POST,true);
	    curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
	    $sContent = curl_exec($oCurl);
	    $aStatus = curl_getinfo($oCurl);
	    curl_close($oCurl);
	    if(intval($aStatus["http_code"])==200){
	        return $sContent;
	    }else{
	        return false;
	    }
	}
	
}
