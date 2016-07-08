<?php
	$profile = pdo_fetch('SELECT * FROM '.tablename('hc_moreshop_member')." WHERE `weid` = :weid AND from_user=:from_user ",array(':weid' => $weid,':from_user' => $from_user));
	$id = $profile['id'];
	if(!intval($id) && $op!='add'){
		$this->CheckCookie();
	}
	if($op=='display'){
		$opp = $_GPC['opp'];
		$rule = pdo_fetch('SELECT * FROM '.tablename('hc_moreshop_rules')." WHERE `weid` = :weid ",array(':weid' => $weid));
		include $this->template('register');
		exit;
	}

	if(!empty($profile)){
		$data=array(
			'realname'=>$_GPC['realname'],
			'mobile'=>$_GPC['mobile'],
			'bankcard'=>$_GPC['bankcard'],
			'banktype'=>$_GPC['banktype'],
			'alipay'=>$_GPC['alipay'],
			'wxhao'=>$_GPC['wxhao'],
		);
		
		$pro = pdo_fetch('SELECT mobile, id FROM '.tablename('hc_moreshop_member')." WHERE `weid` = :weid AND mobile=:mobile ",array(':weid' => $weid,':mobile' => $_GPC['mobile']));
		
		if($data['mobile']==$profile['mobile']){
		}else{
			if($data['mobile']==$pro['mobile']){
				echo -3;
				exit;
			}
		}
		pdo_update('hc_moreshop_member',$data, array('id'=>$profile['id']));
		
		echo 2;
		exit;
	}
	
	//注册
	if($op=='add'){
		$shareid = 'hc_moreshop_shareid'.$weid;
		$headimg = $_COOKIE['hc_moreshop_headimgurl'];
		if(empty($headimg)){
			$headimg = '';
		}
		$data = array(
			'weid'=>$weid,
			'from_user'=>$from_user,
			'headimg'=>$headimg,
			'shareid'=>empty($_GPC['id']) ? $_COOKIE[$shareid] : $_GPC['id'],
			'realname'=>$_GPC['realname'],
			'mobile'=>$_GPC['mobile'],
			'alipay'=>$_GPC['alipay'],
			'wxhao'=>$_GPC['wxhao'],
			'commission'=>0,
			'createtime'=>TIMESTAMP,
			'status'=>1,
			'flag'=>1
		);
		$profile = pdo_fetch('SELECT mobile,id FROM '.tablename('hc_moreshop_member')." WHERE `weid` = :weid AND mobile=:mobile ",array(':weid' => $weid,':mobile' => $_GPC['mobile']));
		
		if($data['mobile']==$profile['mobile']){
			echo -2;
			exit;
		}
		pdo_insert('hc_moreshop_member',$data);

		echo 1;
		exit;
	}
?>