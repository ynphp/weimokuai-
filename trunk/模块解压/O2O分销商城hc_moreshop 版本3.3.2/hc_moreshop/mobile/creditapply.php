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
			$fcredit = mc_fetch($follow['uid'], array('credit1'));
			$fcredit = $fcredit['credit1']*100/100;
		} else {
			$fcredit = 0;
		}
		$rule = pdo_fetch("select conversion, gzurl from ".tablename('hc_moreshop_rules')." where weid = ".$weid);
		$creditlog = pdo_fetchall("select * from ".tablename('hc_moreshop_credit')." where status = 0 and mid = ".$id." and weid = ".$weid." order by createtime desc");
		$goods = pdo_fetchall("select id, title from ".tablename('hc_moreshop_goods')." where weid = ".$weid);
		$good = array();
		foreach($goods as $g){
			$good[$g['id']] = $g['title'];
		}
	}
	

	// 处理申请
	if($op=='applyed'){
		if($profile['flag']==0){
			echo -1;
			exit;
		}
		$nowcredit = intval($_GPC['credit']);
		if(!is_numeric($nowcredit) && $nowcredit<=0){
			echo -2;
			exit;
		}
		$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
		if(!empty($follow) && $follow['follow']){
			$fcredit = mc_fetch($follow['uid'], array('credit1'));
			if($nowcredit > $fcredit['credit1']){
				echo -3;
				exit;
			}
			
		} else {
			echo -4;
			exit;
		}
		$rule = pdo_fetch("select conversion from ".tablename('hc_moreshop_rules')." where weid = ".$weid);
		if(intval($rule['conversion'])){
			$credit = sprintf("%.2f", $nowcredit/$rule['conversion']); 
		} else {
			echo -5;
			exit;
		}
		if($_GPC['typenum']==1){
			//pdo_update('mc_members', array('credit1'=>$fcredit['credit1']-$credit), array('uid'=>$follow['uid']));
			pdo_update('hc_moreshop_member', array('commission'=>$profile['commission']+$credit), array('id'=>$id));
			load()->model('mc');
			mc_credit_update($follow['uid'], 'credit1', -$nowcredit, array('0'=>'', '1'=>'多店'.$profile['realname'].'兑换积分'));
			mc_credit_update($follow['uid'], 'credit2', $credit, array('0'=>'', '1'=>'多店'.$profile['realname'].'积分兑换余额'));
			$commissionlog = array(
				'weid'=>$weid,
				'mid'=>$id,
				'ogid'=>0,
				'commission'=>$credit,
				'flag'=>0,
				'isout'=>0,
				'createtime'=>time()
			);
			pdo_insert('hc_moreshop_commission', $commissionlog);
			$commissionlog['flag']=2;
			$commissionlog['isout']=1;
			pdo_insert('hc_moreshop_commission', $commissionlog);
			echo 1;
			exit;
		} else {
			load()->model('payment');
			$setting = uni_setting($_W['uniacid'], array('payment'));
			if(!is_array($setting['payment'])) {
				//exit('没有设定支付参数.');
				echo -6;
				exit;
			}
			$appid = pdo_fetchcolumn("select `key` from ".tablename('account_wechats')." where uniacid = ".$_W['uniacid']);
			$wechat = $setting['payment']['wechat'];
			$mach_id = $wechat['mchid'];								//微信支付商户号
			$total_amount = $credit*100;								//发送红包金额，单位分
			$wishing = '兑换成功!';										//红包祝福语
			$remark = '积分兑换';										//备注
			$act_name = '积分兑换';										//活动名称
			$key = $wechat['signkey'];									//微信支付秘钥
			$send_name = $_W['account']['name'];						//发送红包人名称
			$openid = $from_user;										//被发送人的openid
			$back = get_red_bag($appid,$mach_id,$openid,$total_amount,$wishing,$act_name,$remark,$key,$send_name);
			$string = $back;
			//var_dump($back);
			$xml = simplexml_load_string($string);
			//$return_msg = $xml->return_msg;//这里返回的依然是个SimpleXMLElement对象
			$return_msg = (string)$xml->return_msg;	  //商户平台返回信息
			$return_code = (string)$xml->return_code;
			if($return_code=='SUCCESS'){
				//pdo_update('mc_members', array('credit1'=>$fcredit['credit1']-$credit), array('uid'=>$follow['uid']));
				pdo_update('hc_moreshop_member', array('commission'=>$profile['commission']+$credit), array('id'=>$id));
				load()->model('mc');
				mc_credit_update($follow['uid'], 'credit1', -$nowcredit, array('0'=>'', '1'=>'多店'.$profile['realname'].'兑换积分'));
				$commissionlog = array(
					'weid'=>$weid,
					'mid'=>$id,
					'ogid'=>0,
					'commission'=>$credit,
					'flag'=>0,
					'isout'=>0,
					'createtime'=>time()
				);
				pdo_insert('hc_moreshop_commission', $commissionlog);
				$commissionlog['flag']=2;
				$commissionlog['isout']=1;
				pdo_insert('hc_moreshop_commission', $commissionlog);
				echo 1;
				exit;
			} else {
				//$return_msg = json_encode($return_msg);
				echo $return_msg;
				exit;
			}
		}
	}
	
	include $this->template('creditapply');
?>