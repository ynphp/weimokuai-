<?php
	$profile = pdo_fetch('SELECT * FROM '.tablename('hc_moreshop_member')." WHERE  weid = :weid  AND from_user = :from_user" , array(':weid' => $weid,':from_user' => $from_user));
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
	$userdefault = pdo_fetchcolumn("select userdefault from ".tablename('hc_moreshop_rules')." where weid = ".$weid);
	$shophost = pdo_fetch("select * from ".tablename('hc_moreshop_shophost')." where weid = ".$weid." and from_user = '".$from_user."'");
	if(empty($userdefault)){
		$userdefault = 1;
	}
	$fanslevel = array();
	$lowfansid = '('.$id.')';
	$allteamid = -1;
	$lowfansids = array();
	$lowfansnum = array();
	$alllowfansnum = 0;
	$allteamnum = 0;
	$allteamnum3 = 0;
	for($i=1; $i<=$userdefault; $i++){
		$lowfansnum[$i] = 0;
		$lowfansids[$i] = $lowfansid;
		$fanslevel[$i] = pdo_fetchall("select * from ".tablename('hc_moreshop_member')." where shareid in ".$lowfansid." and status = 1 and from_user != '".$from_user."'");
		$lowfansid = '';
		if(!empty($fanslevel[$i])){
			$alllowfansnum = sizeof($fanslevel[$i]) + $alllowfansnum;
			if($i<=3){
				//前三级下线总数
				$allteamnum3 = sizeof($fanslevel[$i]) + $allteamnum3;
			} else {
				foreach($fanslevel[$i] as $f){
					$allteamid = $f['shareid'].','.$allteamid;
				}
			}
			$lowfansnum[$i] = sizeof($fanslevel[$i]);
			foreach($fanslevel[$i] as $f){
				$lowfansid = $lowfansid.$f['id'].',';
			}
			$lowfansid = '('.trim($lowfansid, ',').')';
		} else {
			for($k=$i; $k<=$userdefault; $k++){
				$fanslevel[$k] = 0;
				$lowfansnum[$k] = 0;
			}
			break;
		}
	}
	//我的团队总数
	$allteamnum = $alllowfansnum - $allteamnum3;
	$allteamid = '('.trim($allteamid, ',').')';
	//我的分店
	$shophost = pdo_fetch('SELECT * FROM '.tablename('hc_moreshop_shophost')." WHERE  weid = :weid  AND from_user = :from_user" , array(':weid' => $weid,':from_user' => $from_user));
	if(!empty($shophost)){
		$shopid = $shophost['id'];
		$shoplevel = array();
		$lowshopid = '('.$shopid.')';
		$lowshopids = array();
		$lowshopnum = array();
		$alllowfansnum = 0;
		$allteamnum = 0;
		$allshopnum3 = 0;
		for($i=1; $i<=$userdefault; $i++){
			$lowshopnum[$i] = 0;
			$lowshopids[$i] = $lowshopid;
			$shoplevel[$i] = pdo_fetchall("select * from ".tablename('hc_moreshop_shophost')." where shareid in ".$lowshopid." and ischeck = 1 and from_user != '".$from_user."'");
			$lowshopid = '';
			if(!empty($shoplevel[$i])){
				$alllowfansnum = sizeof($shoplevel[$i]) + $alllowfansnum;
				if($i<=3){
					//前三级分店总数
					$allshopnum3 = sizeof($shoplevel[$i]) + $allshopnum3;
				} else {
					break;
				}
				$lowshopnum[$i] = sizeof($shoplevel[$i]);
				foreach($shoplevel[$i] as $f){
					$lowshopid = $lowshopid.$f['id'].',';
				}
				$lowshopid = '('.trim($lowshopid, ',').')';
			} else {
				for($k=$i; $k<=$userdefault; $k++){
					$shoplevel[$k] = 0;
					$lowshopnum[$k] = 0;
				}
				break;
			}
		}
	} else {
		$shoplevel = array(
			'1'=>'',
			'2'=>'',
			'3'=>'',
		);
		$allshopnum3 = 0;
	}
	include $this->template('mylows');
?>