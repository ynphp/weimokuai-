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
	$setnews = pdo_fetch('SELECT * FROM '.tablename('hc_moreshop_membernews')." WHERE  weid = :weid  AND mid = :mid" , array(':weid' => $weid,':mid' => $id));
	if(empty($setnews)){
		$news = array(
			'weid'=>$weid,
			'mid'=>$id,
			'openid'=>$from_user,
			'istemplate_id'=>1,
			'issendGoodsSend'=>1,
			'issendCommWarm'=>1,
			'issendCheckChange'=>1,
			'issendApplyMoneyBack'=>1,
			'issendMoneyBack'=>1,
			'createtime'=>time()
		);
		pdo_insert('hc_moreshop_membernews', $news);
	}
	if($op=='set'){
		if($_GPC['status']==0){
			pdo_update('hc_moreshop_membernews', array($_GPC['flag']=>0), array('id'=>$setnews['id']));
		} else {
			pdo_update('hc_moreshop_membernews', array($_GPC['flag']=>1), array('id'=>$setnews['id']));
		}
		echo 1;
		exit;
	}
	include $this->template('activity');
?>