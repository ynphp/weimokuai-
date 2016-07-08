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
	$allordernum = pdo_fetchcolumn("select count(id) from ".tablename('hc_moreshop_order')." where weid = ".$weid." and status > -1 and from_user = '".$from_user."'");
	$userdefault = pdo_fetchcolumn("select userdefault from ".tablename('hc_moreshop_rules')." where weid = ".$weid);
	if(empty($userdefault)){
		$userdefault = 1;
	}
	$list = array();
	$alllowordernum = 0;
	$lowordernum = array();
	$allteamordernum = 0;
	$allteamordernum3 = 0;
	for($i=1; $i<=$userdefault; $i++){
		$list[$i] = pdo_fetchall("SELECT * FROM " . tablename('hc_moreshop_memberrelative') . " WHERE shareid = ".$id." and userdefault = ".$i." and weid = ".$weid." ORDER BY createtime DESC LIMIT 11");
		if(empty($list[$i])){
			for($k=$i; $k<=$userdefault; $k++){
				$list[$k] = 0;
				$lowordernum[$k] = 0;
			}
			break;
		} else {
			$alllowordernum = $alllowordernum + sizeof($list[$i]);
			if($i<=3){
				$allteamordernum3 = $allteamordernum3 + sizeof($list[$i]);
			}
		}
		$lowordernum[$i] = sizeof($list[$i]);
	}
	$allteamordernum = $alllowordernum - $allteamordernum3;
	include $this->template('myrecorder');
?>