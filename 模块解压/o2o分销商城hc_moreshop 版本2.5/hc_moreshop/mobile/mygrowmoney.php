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
	if($op=='questions'){
		$qid = intval($_GPC['qid']);
		$questions = pdo_fetchall("select * from ".tablename('hc_moreshop_question')." where weid = ".$weid." and isopen = 1 order by displayorder desc");
		include $this->template('question');
		exit;
	}
	$growmoney = pdo_fetch("select * from ".tablename('hc_moreshop_growmoney')." where mid = ".$id." and weid = ".$weid);
	if(empty($growmoney)){
		$money = array(
			'weid'=>$weid,
			'mid'=>$id,
			'growmoney'=>0,
			'yestodaygrowmoney'=>0,
			'content'=>'',
			'createtime'=>time(),
			'upgradetime'=>time(),
		);
		pdo_insert('hc_moreshop_growmoney', $money);
	} else {
		$starttime = strtotime(date('Y-m-d 00:00:00'));
		$endtime = strtotime(date('Y-m-d 23:59:59'));
		$todaymoney = pdo_fetchcolumn("select sum(commission) from ".tablename('hc_moreshop_commission')." where (flag = 1 or flag = 2) and createtime >= ".$starttime." and createtime <= ".$endtime." and mid = ".$id." and weid = ".$weid);
		$commissioned = pdo_fetchcolumn("select sum(commission) from ".tablename('hc_moreshop_commission')." where (flag = 1 or flag = 2) and mid = ".$id." and weid = ".$weid);
		if($growmoney['upgradetime']<$starttime){
			$imoney = pdo_fetchcolumn("select sum(money) from ".tablename('hc_moreshop_inputmoney')." where mid = ".$id." and weid = ".$weid);
			$commissioned = empty($commissioned)?0:$commissioned;
			$imoney = empty($imoney)?0:$imoney;
			$allmoney = $imoney + $commissioned + $growmoney['growmoney'];
			if(!empty($allmoney)){
				$grow = pdo_fetchcolumn("select growmoney from ".tablename('hc_moreshop_rules')." where weid = ".$weid);
				if(!empty($grow)){
					$nowtimes = ceil((time() - $growmoney['upgradetime'])/3600/24);
					if($nowtimes > 0){
						// 增长后的成长倍率
						$growresult = 1;
						// 昨天成长倍率
						$yestodayresult = 0;
						for($i=0; $i<$nowtimes; $i++){
							$growresult = $growresult + $grow/100;
							if($i==$nowtimes-2){
								$yestodayresult = $growresult;
							}
						}
					}
					$upgrademoney = array(
						'growmoney'=>$allmoney*$growresult,
						'yestodaygrowmoney'=>$allmoney*$yestodayresult,
						'upgradetime'=>time()
					);
					pdo_update('hc_moreshop_growmoney', $upgrademoney, array('id'=>$growmoney['id']));
				}
			}
		}
	}
	$rebackmoney = pdo_fetchcolumn("select sum(price) from ".tablename('hc_moreshop_order')." where weid = ".$weid." and from_user = '".$profile['from_user']."' and status = -2 and isoutput = 1");
	// 今日退款
	$rebackmoney = empty($rebackmoney) ? 0 : $rebackmoney;
	$growmoney = pdo_fetch("select * from ".tablename('hc_moreshop_growmoney')." where mid = ".$id." and weid = ".$weid);
	$todaygorwmoney = $growmoney['growmoney'] - $growmoney['yestodaygrowmoney'];
	// 今日收入
	$todayinputmoney = $todaymoney + $todaygorwmoney;
	// 累计收入
	$allcommissioned = $commissioned + $growmoney['growmoney'];
	
	$userdefault = pdo_fetchcolumn("select userdefault from ".tablename('hc_moreshop_rules')." where weid = ".$weid);
	if(empty($userdefault)){
		$userdefault = 1;
	}
	$allorder = pdo_fetch("select count(id) as allid, sum(price) as allprice from ".tablename('hc_moreshop_order')." where weid = ".$weid." and status > -1 and from_user = '".$from_user."'");
	$allordernum = empty($allorder['allid']) ? 0 : $allorder['allid'];
	$allorderprice = empty($allorder['allprice']) ? 0 : $allorder['allprice'];
	
	$fanslevel = array();
	$lowfansid = '('.$id.')';
	$lowfansids = array();
	// 客户数
	$alllowfansnum = 0;
	for($i=1; $i<=$userdefault; $i++){
		$lowfansids[$i] = $lowfansid;
		$fanslevel[$i] = pdo_fetchall("select * from ".tablename('hc_moreshop_member')." where shareid in ".$lowfansid." and status = 1 and from_user != '".$from_user."'");
		$lowfansid = '';
		if(!empty($fanslevel[$i])){
			$alllowfansnum = sizeof($fanslevel[$i]) + $alllowfansnum;
			foreach($fanslevel[$i] as $f){
				$lowfansid = $lowfansid.$f['id'].',';
			}
			$lowfansid = '('.trim($lowfansid, ',').')';
		} else {
			break;
		}
	}
	$questions = pdo_fetchall("select * from ".tablename('hc_moreshop_question')." where weid = ".$weid." and isopen = 1 order by displayorder desc limit 3");
	include $this->template('growmoney');
?>