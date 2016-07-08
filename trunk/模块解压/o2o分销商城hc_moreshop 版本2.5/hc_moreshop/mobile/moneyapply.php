<?php
	$profile = pdo_fetch('SELECT * FROM '.tablename('hc_moreshop_member')." WHERE weid = :weid  AND from_user = :from_user" , array(':weid' => $weid,':from_user' => $from_user));
	$id = $profile['id'];
	if(intval($profile['id']) && $profile['status']==0){
		include $this->template('forbidden');
		exit;
	}
	if(empty($profile)){
		message('请先注册',$this->createMobileUrl('register'),'error');
		exit;
	}
	
	if($op=='display'){
		$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
		if(!empty($follow) && $follow['follow']){
			$fcredit = mc_fetch($follow['uid'], array('credit2'));
			$fcredit = $fcredit['credit2']*100/100;
		} else {
			$fcredit = 0;
		}
		$rule = pdo_fetch("select gzurl from ".tablename('hc_moreshop_rules')." where weid = ".$weid);
		$moneylog = pdo_fetchall("select * from ".tablename('hc_moreshop_moneyapply')." where weid = ".$weid." and mid = ".$id);
		$status = array(
			'0'=>'正在申请',
			'1'=>'正在审核',
			'2'=>'提现成功',
		);
	}
	

	// 处理申请
	if($op=='applyed'){
		if($profile['flag']==0){
			echo -1;
			exit;
		}
		$credit = intval($_GPC['credit']);
		if(!is_numeric($credit) && $credit<=0){
			echo -2;
			exit;
		}
		$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
		if(!empty($follow) && $follow['follow']){
			$fcredit = mc_fetch($follow['uid'], array('credit2'));
			if($credit > $fcredit['credit2']){
				echo -3;
				exit;
			}
			pdo_update('mc_members', array('credit2'=>$fcredit['credit2']-$credit), array('uid'=>$follow['uid']));
		} else {
			echo -4;
			exit;
		}
		$moneylog = array(
			'weid'=>$weid,
			'mid'=>$id,
			'money'=>0,
			'money'=>$credit,
			'status'=>0,
			'createtime'=>time()
		);
		pdo_insert('hc_moreshop_moneyapply', $moneylog);
		echo 1;
		exit;
	}
	
	include $this->template('moneyapply');
?>