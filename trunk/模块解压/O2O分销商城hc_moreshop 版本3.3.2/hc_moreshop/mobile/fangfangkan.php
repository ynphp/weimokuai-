<?php
	if($op=='game_list'){
		include $this->template('game_list');
		exit;
	}
	$profile = pdo_fetch('SELECT * FROM '.tablename('hc_moreshop_member')." WHERE `weid` = :weid AND from_user=:from_user ",array(':weid' => $weid,':from_user' => $from_user));
	$id = $profile['id'];
	if(intval($profile['id']) && $profile['status']==0){
		include $this->template('forbidden');
		exit;
	}
	if(empty($profile)){
		$url = $this->createMobileUrl('register');
		header("location:$url");
	} else {
		if(empty($profile['headimg'])){
			$this->CheckCookie();
		}
	}
	$item = pdo_fetch("select * from ".tablename('hc_moreshop_fangfangkan')." where uniacid = ".$weid);
	$starttime = strtotime(date('Y-m-d 00:00:00'));
	//$endtime = strtotime(date('Y-m-d 23:59:59'));
	if($profile['ffkupdatetime'] < $starttime){
		pdo_update('hc_moreshop_member', array('ffkupdatetime'=>time(), 'ffktimes'=>$item['gametimes']), array('id'=>$id));
	}
	if($op=='gametimes'){
		if($profile['ffktimes']>0){
			pdo_update('hc_moreshop_member', array('ffktimes'=>$profile['ffktimes']-1), array('id'=>$id));
			echo $profile['ffktimes'];
			exit;
		} else {
			echo 0;
			exit;
		}
	}

	if($op=='addcredit'){
		$credit = intval($_GPC['credit']);
		if($credit){
			$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
			load()->model('mc');
			mc_credit_update($follow['uid'], 'credit1', $credit, array('0'=>'', '1'=>'多店'.$profile['realname'].'翻翻看积分'));
		}
	}
	
	$level = array(
		'0'=>'easy',
		'1'=>'normal',
		'2'=>'hard',
	);
	
	include $this->template('skycat/index');
?>