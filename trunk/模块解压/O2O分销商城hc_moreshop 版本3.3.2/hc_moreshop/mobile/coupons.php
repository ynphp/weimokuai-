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
	if($op=='display'){
		$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
		$fcredit = mc_fetch($follow['uid'], array('credit1'));
		$fcredit = empty($fcredit['credit1']) ? 0 : $fcredit['credit1'];
		$coupons = pdo_fetchall("select * from ".tablename('hc_moreshop_coupons')." where uniacid = ".$weid." and isopen = 1 order by displayorder desc");
	}
	
	if($op=='detail'){
		$coupon = pdo_fetch("select * from ".tablename('hc_moreshop_coupons')." where id = ".$_GPC['id']);
		include $this->template('coupon_detail');
		exit;
	}
	
	if($op=='exchange'){
		$id = intval($_GPC['id']);
		if($id){
			$coupon = pdo_fetch("select * from ".tablename('hc_moreshop_coupons')." where id = ".$id);
			if($coupon['starttime'] > time()){
				message('兑换时间未到！');
			}
			if($coupon['endtime'] < time()){
				message('兑换时间已结束！');
			}
			if($coupon['number'] <= 0){
				message('该优惠券已被兑换完！');
			}
			$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
			$fcredit = mc_fetch($follow['uid'], array('credit1'));
			if(empty($follow) || $follow['follow']==0){
				message('需要关注该公众号才能兑换', $rule['gzurl']);
			}
			if($fcredit['credit1'] < $coupon['credit']){
				message('你的积分不够兑换该优惠券！');
			}
			$mycoupon = array(
				'uniacid'=>$weid,
				'mid'=>$profile['id'],
				'couponid'=>$id,
				'title'=>$coupon['title'],
				'thumb'=>$coupon['thumb'],
				'type'=>$coupon['type'],
				'discount'=>$coupon['discount'],
				'isuse'=>0,
				'createtime'=>time()
			);
			pdo_insert('hc_moreshop_mycoupons', $mycoupon);
			//load()->model('mc');
			mc_credit_update($follow['uid'], 'credit1', -$coupon['credit'], array('0'=>'', '1'=>'多店'.$profile['realname'].'兑换'.$coupon['title'].'花费积分'));
			pdo_update('hc_moreshop_coupons', array('number'=>$coupon['number']-1), array('id'=>$id));
			message('优惠券兑换成功！');
		} else {
			message('未知错误！');
		}
	}
	
	if($op=='delete'){
		$mycouponid = intval($_GPC['mycouponid']);
		if($mycouponid){
			pdo_delete('hc_moreshop_mycoupons', array('id'=>$mycouponid));
			echo 1;
			exit;
		} else {
			echo 0;
			exit;
		}
	}
	
	if($op=='mycoupons'){
		$mycoupons = pdo_fetchall("select * from ".tablename('hc_moreshop_mycoupons')." where uniacid = ".$weid." and mid = ".$profile['id']);
		include $this->template('mycoupons');
		exit;
	}
	
	include $this->template('coupons');
?>