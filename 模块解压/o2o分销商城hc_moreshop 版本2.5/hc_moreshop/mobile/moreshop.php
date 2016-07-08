<?php
	
	$member = pdo_fetch('SELECT * FROM '.tablename('hc_moreshop_member')." WHERE weid = :weid AND from_user = :from_user" , array(':weid' => $weid,':from_user' => $from_user));
	if($member['flag']==0 && $op!='index'){
		message('你还不是粉丝管理，不能申请开店！', $this->createMobileUrl('index'), 'error');
	}
	if(intval($member['id']) && $member['status']==0){
		include $this->template('forbidden');
		exit;
	}
	if($op=='index'){
		$shopflag = 'hc_moreshop_shopfalg';
		setcookie($shopflag, 0, time()+3600*240);
		$url = $this->createMobileUrl('index', array('opp'=>'myshop'));
		header("location:$url");
		exit;
	}
	$shophost = pdo_fetch("select * from ".tablename('hc_moreshop_shophost')." where weid = ".$weid." and from_user = '".$from_user."'");
	if(!empty($shophost) && $shophost['ischeck']==0){
		message('请勿重复申请！');
	} else if ($shophost['ischeck']==1){
		$shopflag = 'hc_moreshop_shopfalg';
		setcookie($shopflag, 1, time()+3600*240);
		$url = $this->createMobileUrl('index', array('opp'=>'myshop'));
		header("location:$url");
		exit;
	}
	if($op=='apply'){
		$shareid = 'hc_moreshop_shareid'.$weid;
		$shareid = $_COOKIE[$shareid];
		if(intval($shareid)){
			$shareid = pdo_fetchcolumn("select id from ".tablename('hc_moreshop_shophost')." where weid = ".$weid." and mid = ".$shareid);
		} else {
			$shareid = 0;
		}
		$host = array(
			'weid'=>$weid,
			'mid'=>$member['id'],
			'shareid'=>intval($shareid),
			'from_user'=>$from_user,
			'realname'=>trim($_GPC['realname']),
			'mobile'=>trim($_GPC['mobile']),
			'pwd'=>trim($_GPC['password']),
			'ischeck'=>0,
			'status'=>1,
			'createtime'=>time()
		);
		if(empty($shophost)){
			pdo_insert('hc_moreshop_shophost', $host);
			setcookie("$shareid", 0);
			echo 1;
			exit;
		}
		echo 1;
		exit;
	}
	
	include $this->template('moreshop');
?>