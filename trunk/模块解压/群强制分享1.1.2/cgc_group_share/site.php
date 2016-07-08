<?php

defined('IN_IA') or exit('Access Denied');
define('STYLE_PATH','../addons/cgc_group_share/template/style');
session_start();
class cgc_group_shareModuleSite extends WeModuleSite {
	
function getIPLoc_sina($queryIP,&$city =''){
	    
$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$queryIP;    
$ch = curl_init($url);     
curl_setopt($ch,CURLOPT_ENCODING ,'utf8');     
curl_setopt($ch, CURLOPT_TIMEOUT, 5);   
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
$location = curl_exec($ch);    
$location = json_decode($location);    
curl_close($ch);         
$loc = "";   
if($location===FALSE) return "";     
if (empty($location->desc)) {    
  $loc =$location->province.$location->city.$location->district;  
}else{
  $loc = $location->desc;    
}    
$city=$location->city;
return $loc;

}
	
	
	  
   function createNonceStr($length = 16)
  {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$str = "";
	for ($i = 0; $i < $length; $i++) {
	  $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}
	return $str;
  }
	
	
   function get_random_domain($url){
     if (empty($url)){
     	return "";
     }
     $nonceStr=$this->createNonceStr();
     $zdy_domain=explode("|", $url);
     $size=count($zdy_domain);
     if ($size==1){
     	$zdy_domain[0]=str_replace("*",$nonceStr,$zdy_domain[0]);
        return $zdy_domain[0];
     } else {
       $random=mt_rand(1,$size);
      
       $zdy_domain[$random-1]=str_replace("*",$nonceStr,$zdy_domain[$random-1]);
       return $zdy_domain[$random-1];
     }
  }
  
 
  
  function valid(){
    global $_W, $_GPC;
  	if($_W['container']!="wechat" &&  empty($_GPC['test'])){
  	  header("location:http://qq.com");
      exit();
  	 }
 	 
  	 if (empty($_SESSION['forward'])){
  	  	 exit("error");
  	  }
  	    
  
  }
  
  function get_addr($city){
  	
     global $_W, $_GPC;
  	 $uniacid=$_W['uniacid'];     
      $settings=$this->module['config'];  
      $iplimit=$settings['ip_limit'];      
      $ipforbidden=$settings['ip_forbidden']; 
      
      if (empty($iplimit) && empty($ipforbidden)){
         return true;
       }
   
 
  
   if (!empty($iplimit)){    
     $iplimitarr=explode("|",$iplimit);    
	 foreach ($iplimitarr as $value){
	   $value=str_replace("市","",$value);
	   $value=str_replace("省","",$value);
	   $pos = strexists($city,$value);
       if ($pos!==false){
        return true;	
      }	    
	}
   } 
   
   
   $ret=true;
   if (!empty($ipforbidden)){    
     $ipforbidden=explode("|",$ipforbidden);   
	foreach ($ipforbidden as $value){
	  $value=str_replace("市","",$value);
	  $value=str_replace("省","",$value);
	  $pos = strexists($city,$value);
      if ($pos==true){
      	$ret=false;
             
     }	    
	}
   }  
   if ($ret==false){
    header("location:".$settings['ip_url']);
	exit();
   }
   
  }
  
   public function doMobileDefault(){    	
      global $_W, $_GPC;
      $this->valid();
      $uniacid=$_W['uniacid'];     
      $settings=$this->module['config'];      
      $ip=getip();
      $city="";
      $addr=$this->getIPLoc_sina($ip,$city);   
      $this->get_addr($addr);  
      $group_num=mt_rand(100,500);
      $settings['group_name']=str_replace("#addr#",$city,$settings['group_name']);      
      $settings['share_desc']=str_replace("#addr#",$city,$settings['share_desc']);
      $settings['share_title']=str_replace("#addr#",$city,$settings['share_title']);
      $settings['share_num']=empty($settings['share_num'])?0:$settings['share_num'];
           
      $settings['share_url']=$this->get_random_domain($settings['share_url']);
     
      $url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m={$this->modulename}&do=enter";
      $settings['share_url']=empty($settings['share_url'])?$url:$settings['share_url']; 
      $settings['succ_url']=$this->get_random_domain($settings['succ_url']);
      $settings['succ_url']=empty($settings['succ_url'])?$this->createMobileUrl("result"):$settings['succ_url']; 
      $settings['share_thumb']=MODULE_URL."template/style/qun_meilv.jpg";
      include $this->template("default");
   }
   
    public function get_qrcode($qrcode,$addr){
      global $_W, $_GPC; 
      $settings=$this->module['config'];
      if (empty($settings['fans_regional'])){
        return $qrcode;
      }
      
      $settings['fans_regional'] = unserialize($settings['fans_regional']);
      
      foreach ($settings['fans_regional'] as $value){
      	 $value['fans_regional_addr']=str_replace("市","",$value['fans_regional_addr']);
	     $value['fans_regional_addr']=str_replace("省","",$value['fans_regional_addr']);
      	
      	 $pos = strexists($addr,$value['fans_regional_addr']);
      	 
        if ($pos==true){
           return $value['fans_regional_pic'];
        }
      }
      
      
      
    
      return $qrcode;
    }
   
    public function doMobileResult(){
      global $_W, $_GPC; 
      $this->valid();
      $uniacid=$_W['uniacid'];
      $settings=$this->module['config'];
      $ip=getip();
      $city="";
      $addr=$this->getIPLoc_sina($ip,$city);  
      $this->get_addr($addr);    
      
      $settings['group_name']=str_replace("#addr#",$city,$settings['group_name']);      
      $settings['share_desc']=str_replace("#addr#",$city,$settings['share_desc']);
      $settings['share_title']=str_replace("#addr#",$city,$settings['share_title']);
      
      $qrcode="";
      if ($settings['qrcode']){
      $qrcode=tomedia($settings['qrcode']);      
      }
      
     if ($settings['sj_qrcode']){
       $qrcode=$qrcode."|".$settings['sj_qrcode'];
      }
    
      $qrcode=$this->get_random_domain($qrcode);
      $qrcode=$this->get_qrcode($qrcode,$addr); 
      
      $settings['share_url']=$this->get_random_domain($settings['share_url']);
      $url = $_W['siteroot'] . "app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&m={$this->modulename}&do=enter";
      $settings['share_url']=empty($settings['share_url'])?$url:$settings['share_url']; 
      $settings['succ_url']=$this->get_random_domain($settings['succ_url']);
      $settings['succ_url']=empty($settings['succ_url'])?$this->createMobileUrl("result"):$settings['succ_url']; 
      $settings['share_thumb']=MODULE_URL."template/style/qun_meilv.jpg";
           
      $settings['friend_circle'] = unserialize($settings['friend_circle']);
  
      
      include $this->template("result");
   }
   
  

 
}