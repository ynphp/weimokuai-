<?php

defined('IN_IA') or exit('Access Denied');

class cgc_group_shareModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		$settings['friend_circle'] = unserialize($settings['friend_circle']);
		$settings['fans_regional'] = unserialize($settings['fans_regional']);
		
		if(checksubmit()) {
			load()->func('file');
			
			$friend_circle=array();
			$friend_circle_pic=$_GPC['friend_circle_pic'];
			$friend_circle_content=$_GPC['friend_circle_content'];
			$friend_circle_num=$_GPC['friend_circle_num'];
			if(is_array($friend_circle_pic)){
				foreach($friend_circle_pic as $key=>$value){
					$d=array(
							'friend_circle_pic'=>$friend_circle_pic[$key],
							'friend_circle_content'=>$friend_circle_content[$key],
							'friend_circle_num'=> $friend_circle_num[$key],
					);
					$friend_circle[]=$d;
				}
			}
			if(!empty($friend_circle)){
				$_GPC['friend_circle'] = serialize($friend_circle);
			}
			
			
			$fans_regional=array();
			$fans_regional_addr=$_GPC['fans_regional_addr'];
			$fans_regional_pic=$_GPC['fans_regional_pic'];
			if(is_array($fans_regional)){
				foreach($fans_regional_addr as $key=>$value){
					$d=array(
							'fans_regional_addr'=>$fans_regional_addr[$key],
							'fans_regional_pic'=>$fans_regional_pic[$key],
					);
					$fans_regional[]=$d;
				}
			}
			if(!empty($fans_regional)){
				$_GPC['fans_regional'] = serialize($fans_regional);
			}
				    
		     
            $input =array();
            $input['succ_url'] = trim($_GPC['succ_url']);                                          
            $input['start_time'] = trim($_GPC['start_time']);                                                      
            $input['end_time'] = trim($_GPC['end_time']);      
            $input['share_title'] = trim($_GPC['share_title']);                                          
            $input['share_desc'] = trim($_GPC['share_desc']);                                                      
            $input['share_thumb'] = trim($_GPC['share_thumb']);
            $input['zdy_domain'] = trim($_GPC['zdy_domain']);  
            $input['share_url'] = trim($_GPC['share_url']);   
            $input['group_name'] = trim($_GPC['group_name']);  
            $input['wechat_no'] = trim($_GPC['wechat_no']);                                                                                                                   
            $input['qrcode'] = trim($_GPC['qrcode']);  
            $input['ip_get'] = trim($_GPC['ip_get']);    
            $input['ip_url'] = trim($_GPC['ip_url']);
            $input['ip_limit'] = trim($_GPC['ip_limit']);              
            $input['share_num'] = trim($_GPC['share_num']);
            $input['top_logo'] = trim($_GPC['top_logo']);
            $input['sj_qrcode'] = trim($_GPC['sj_qrcode']);
            $input['friend_circle'] = $_GPC['friend_circle']; 
            $input['copyright'] = $_GPC['copyright'];  
            $input['ip_forbidden'] = $_GPC['ip_forbidden'];  
            $input['fans_regional'] = $_GPC['fans_regional']; 
            if($this->saveSettings($input)) {
                message('保存参数成功', 'refresh');
            }
		}
		
		include $this->template('setting');
	}

}