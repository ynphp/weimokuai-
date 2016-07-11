<?php
	
	$ismobile = 'hc_moreshop_mobile'.$weid;
	$rembermobile = 'hc_moreshop_rembermobile'.$weid;
	$ispwd = 'hc_moreshop_pwd'.$weid;

	if($op=='display'){
		$isreg = 0;
		if(empty($_COOKIE[$ismobile]) || empty($_COOKIE[$ispwd])){
			
		} else {
			$host = pdo_fetch("select id, mobile, pwd from ".tablename('hc_moreshop_shophost')." where status = 1 and ischeck = 1 and weid = ".$weid." and mobile = '".trim($_COOKIE[$ismobile])."' and pwd = '".trim($_COOKIE[$ispwd])."'");
			if(!empty($host)){
				$isreg = 1;
			}
		}
		if(intval($_COOKIE['remember-username'])==1){
			$username = $_COOKIE[$rembermobile];
		}
		if($isreg == 0){
			include $this->template('host/login');
			exit;		
		}
	}
	if($op=='login'){
		$isreg = 0;
		$host = pdo_fetchall("select id, mobile, pwd from ".tablename('hc_moreshop_shophost')." where status = 1 and ischeck = 1 and weid = ".$weid);
		foreach($host as $h){
			if($h['mobile']==trim($_GPC['mobile']) && $h['pwd']==$_GPC['pwd']){
				$isreg = 1;
				setcookie($ismobile, $_GPC['mobile'], time()+3600*24);
				setcookie($rembermobile, $_GPC['mobile'], time()+3600*24);
				setcookie($ispwd, $_GPC['pwd'], time()+3600*24);
				if($_GPC['rember']){
					setcookie('remember-username', 1, time()+3600*24);
				} else {
					setcookie('remember-username', 0, time()+3600*24);
				}
				echo 1;
				exit;
			}
		}
		if($isreg == 0){
			echo -1;
			exit;
		}
	}
	
	if($op=='exit'){
		setcookie($ismobile, '', time()+3600*240);
		setcookie($ispwd, '', time()+3600*240);
		$url = $this->createMobileurl('openedit');
		header("location:$url");
	}
	
	//登录状态才可往下执行
	if(empty($_COOKIE[$ismobile]) || empty($_COOKIE[$ispwd])){
		include $this->template('host/login');
		exit;
	}
	
	include $this->template('host/loginindex');
?>