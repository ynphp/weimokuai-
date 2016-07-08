<?php

/**
 * 新红包大战模块处理程序
 *
 * @author Winters Inc.
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
define('JX_ROOT', str_replace("\\", '/', dirname(__FILE__)));
class Xhbdz_wintersModuleProcessor extends WeModuleProcessor {
    
   private  $mod_member;
   private  $mod_poster;
   private  $openid;
   private  $ticket;
   private  $mod_message;
   private $userInfo;
	public function respond() {

	    $this->loadMod('poster');
	    $this->mod_poster = new poster();
	    
	    $this->loadMod('member');
	    $this->mod_member = new member();
	    $this->loadMod('message');
	    $this->mod_message = new message();
	    
	    $this->openid = $this->message['from'];
	    $this->ticket = $this->message['ticket'];

	    $this->userInfo = fans_search($this->openid, array('nickname','avatar'));

	   if ($this->message['msgtype'] == 'text' ||  $this->message['event'] == 'CLICK') {
			//关键词回复
	       return  $this->respondText();
		}else if ($this->message['msgtype'] == 'event') {
		    if ($this->message['event'] == 'subscribe') {
		        //关注事件
				return  $this->respondSubscribe();
			} elseif ($this->message['event'] == 'SCAN') {
			    //扫码事件
				return  $this->respondScan();
			}
		}
	
	}
	
	
	private function respondText(){
	    
	    global $_W;
	    
	    $settings = $this->module['config'];
	    $MyMember = $this->mod_member->get_member($this->openid);
	   
	    if ($MyMember['level'] <= 0){
	        return $this->respText('【'.$this->userInfo['nickname'].'】您好,您还没有购买过产品无法生成您的二维码,请购买后,生成属于您个人的专属二维码');
	    }
	    
	   
	    $uniaccount = 'uniaccount:'.$_W['uniacid'];
	    
	    $token = $_W['cache'][$uniaccount]['token'];
	    $encodingaeskey = $_W['cache'][$uniaccount]['encodingaeskey'];
	    $appid = $_W['cache'][$uniaccount]['key'];
	    $appsecret = $_W['cache'][$uniaccount]['secret'];
	    
	    $options = array(
	        'token'=>$token, //填写你设定的key
	        'encodingaeskey'=>$encodingaeskey, //填写加密用的EncodingAESKey
	        'appid'=>$appid, //填写高级调用功能的app id
	        'appsecret'=>$appsecret //填写高级调用功能的密钥
	    );
	    $this->loadMod('wechat');
	    $mod_wechat = new Wechat($options);

	    //获取access_token
	    $account = WeAccount::create($_W['uniacid']);
	    $access_token = $account->fetch_available_token();

	    
	    //设置 access_token
	    $mod_wechat->checkAuth(null,null,$access_token);
	    //获取  scene_id
	    $scene_id = $this->mod_poster->get_next_avaliable_scene_id($this->openid);
	
	    //设置 scene_id 获取ticket 换取 二维码
	    $getQRCode = $mod_wechat->getQRCode($scene_id,0,604800);
	    
	    $this->ticket = $getQRCode['ticket'];
	
	    
	    $qrUrl = $mod_wechat->getQRUrl($this->ticket);
	    $hbUrl = $_W['siteroot'].'addons/hbdz_winters/qr.php?img='.$qrUrl.'&path='.$_W['attachurl_local'].$settings['qr'].'&qrx='.$settings['qrx'].'&qry='.$settings['qry'];

	 // return $this->respText($qrUrl);
	    //上传资源图片 换取 微信资源id
	   file_put_contents(IA_ROOT.'/addons/hbdz_winters/qr/qr-'.$this->openid.'.jpg',file_get_contents($hbUrl));
	   $c = IA_ROOT.'/addons/hbdz_winters/qr/qr-'.$this->openid.'.jpg';
	   
	   $img = $this->mod_poster->uploadRes($access_token,$c,$this->ticket,$this->openid);
	   
	    return $this->respImage($img);
	}
	
	private function respondSubscribe(){
	    
	    global $_W;
	    
	    $scene_id = $this->message['eventkey'];
	   
	    $memOd = $this->mod_poster->getkeyOpenid($this->ticket);
	  //  二维码中的用户信息
	    $member = $this->mod_member->get_member($memOd['openid']);
	    //来源人中的用户信息
	    $fromMember = $this->mod_member->get_member($this->openid);
	     
	    if(!empty($member)){
	        if (empty($fromMember)){
	    if (($fromMember['level'] <= 0)){
	    $data = array(
	    'uniacid' => $_W['uniacid'],
	    'openid' => $this->openid,
	    'nickname' => $this->userInfo['nickname'],
	    'avatar' => $this->userInfo['avatar'],
	    'parent9' => $member['parent8'],
	    'parent8' => $member['parent7'],
	    'parent7' => $member['parent6'],
	    'parent6' => $member['parent5'],
	    'parent5' => $member['parent4'],
	    'parent4' => $member['parent3'],
	    'parent1' => $member['uid'],
	    'parent2' => $member['parent1'],
	    'parent3' => $member['parent2'],
	    'add_time'=> time()
	    );
	    $this->mod_member->add_member($data);
	    }
	    	    }else {
	    	            if (($fromMember['level'] <= 0)){
	    	        $data = array(
	    
	    'parent9' => $member['parent8'],
	    'parent8' => $member['parent7'],
	    'parent7' => $member['parent6'],
	    'parent6' => $member['parent5'],
	    'parent5' => $member['parent4'],
	    'parent4' => $member['parent3'],
	    'parent1' => $member['uid'],
	    'parent2' => $member['parent1'],
	    'parent3' => $member['parent2'],
	    	        );
	    	        $this->mod_member->update_member($fromMember['uid'],$data); 
	    	        }
	    	    }
	    	    
	}
$welcome = '['.$this->userInfo['nickname'].']欢迎您加入，恭喜您成为平台中的一员。由'.$member['nickname'].'推荐。
     小投资大收益，轻松月入过万不只是梦想。只要抢购至尊VIP课程，获得共富资格，并已获得属于自己的推广二维码(生成出的二维码最长可以保存7天！您可以自己尝试识别二维码是否有效！)。';
	    return $this->respText($welcome);
	}
	function respondScan(){
	    
	    $scene_id = $this->message['eventkey'];
	    $memOd = $this->mod_poster->getkeyOpenid($this->ticket);
	    $member = $this->mod_member->get_member($memOd['openid']);

	    if (empty($member['uid'])){
	        return $this->respText('这个二维码已经失效啦！！！');
	    }else {  
	        global $_W;
	        $fromMember = $this->mod_member->get_member($this->openid);
	        if (empty($fromMember)){
	    if (($fromMember['level'] <= 0)){
	    $data = array(
	    'uniacid' => $_W['uniacid'],
	    'openid' => $this->openid,
	    'nickname' => $this->userInfo['nickname'],
	    'avatar' => $this->userInfo['avatar'],
	    'parent9' => $member['parent8'],
	    'parent8' => $member['parent7'],
	    'parent7' => $member['parent6'],
	    'parent6' => $member['parent5'],
	    'parent5' => $member['parent4'],
	    'parent4' => $member['parent3'],
	    'parent1' => $member['uid'],
	    'parent2' => $member['parent1'],
	    'parent3' => $member['parent2'],
	    'add_time'=> time()
	    );
	    $this->mod_member->add_member($data);
	    return $this->respText('属于【'.$member['nickname'].'】的专属二维码！由于您还未成为VIP系统已自动为您切换上级关系！');
	    }
	    	    }else {
	    	        if (($fromMember['level'] <= 0)){
	    	        $data = array(
	    'parent9' => $member['parent8'],
	    'parent8' => $member['parent7'],
	    'parent7' => $member['parent6'],
	    'parent6' => $member['parent5'],
	    'parent5' => $member['parent4'],
	    'parent4' => $member['parent3'],
	    'parent1' => $member['uid'],
	    'parent2' => $member['parent1'],
	    'parent3' => $member['parent2'],
	    	        );
	    	        $this->mod_member->update_member($fromMember['uid'],$data); 
	    	        return $this->respText('属于【'.$member['nickname'].'】的专属二维码！由于您还未成为VIP系统已自动为您切换上级关系！');
	    	        }
	    	    }
	        
	        
	        
// 	       /$this->mod_message->sendCustomNotice($member['openid'],'成功');
	    
	    return $this->respText('属于【'.$member['nickname'].'】的专属二维码！');
	    }
	}
	private function loadMod($class) {
		require_once JX_ROOT . '/mod/' . $class . '.mod.php';
	}
}