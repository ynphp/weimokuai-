<?php
	// 经销商列表
	if($op=='display'){
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("select * from ".tablename('hc_moreshop_member'). " where flag = 1 and weid = ".$_W['uniacid']." ORDER BY id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
		$total = pdo_fetchcolumn("select count(id) from". tablename('hc_moreshop_member'). "where flag = 1 and weid =".$_W['uniacid']);;
		$pager = pagination($total, $pindex, $psize);
				
		$commissions = pdo_fetchall("select mid, sum(commission) as commission from ".tablename('hc_moreshop_commission')." where weid = ".$_W['uniacid']." and flag = 0 group by mid");
		// 还需结佣
		$commission = array();
		foreach($commissions as $c){
			$commission[$c['mid']] = $c['commission']*1000/1000;
		}
	}
	
	// 查找经销商
	if($op=='sort'){
		$sort = array(
			'realname'=>$_GPC['realname'],
			'mobile'=>$_GPC['mobile']
		);
		if($_GPC['opp']=='nocheck'){
			$status = 0;
		} else {
			$status = 1;
		}
		// 符合条件的经销商
		$list = pdo_fetchall("select * from". tablename('hc_moreshop_member')."where flag = ".$status." and weid =".$_W['uniacid'].".and realname like '%".$sort['realname']. "%' and mobile like '%".$sort['mobile']. "%' ORDER BY id DESC");
		$total = pdo_fetchcolumn("select count(id) from". tablename('hc_moreshop_member')."where flag = ".$status." and weid =".$_W['uniacid'].".and realname like '%".$sort['realname']. "%' and mobile like '%".$sort['mobile']. "%' ORDER BY id DESC");
		$commissions = pdo_fetchall("select mid, sum(commission) as commission from ".tablename('hc_moreshop_commission')." where weid = ".$_W['uniacid']." and flag = 0 group by mid");
		// 还需结佣
		$commission = array();
		foreach($commissions as $c){
			$commission[$c['mid']] = $c['commission'];
		}
	}
	
	// 删除经销商
	if($op=='delete'){
		$temp = pdo_delete('hc_moreshop_member', array('id'=>$_GPC['id']));
		if(empty($temp)){
			message('删除失败，请重新删除！', $this->createWebUrl('fansmanager'), 'error');
		}else{
			message('删除成功！', $this->createWebUrl('fansmanager'), 'success');
		}
	}
	
	if($op=='inputmoney'){
		$id = $_GPC['id'];
		$list = pdo_fetchall("select * from ".tablename('hc_moreshop_inputmoney'). " where weid = ".$weid." and mid = ".$id);
		$total = pdo_fetchcolumn("select count(id) from ".tablename('hc_moreshop_inputmoney'). " where weid = ".$weid." and mid = ".$id);
		include $this->template('inputmoneylog');
		exit;
	}
	
	// 经销商详情
	if($op=='detail'){
		$id = $_GPC['id'];
		$user = pdo_fetch("select * from ".tablename('hc_moreshop_member'). " where id = ".$id);
		include $this->template('fansmanager_detail');
		exit;
	}
	
	// 设置经销商权限，类型
	if($op=='status'){
		$status = array(
			'status'=>$_GPC['status'],
			'content'=>trim($_GPC['content'])
		);
		$temp = pdo_update('hc_moreshop_member', $status, array('id'=>$_GPC['id']));
		if(empty($temp)){
			message('设置用户权限失败，请重新设置！', $this->createWebUrl('fansmanager', array('op'=>'detail', 'id'=>$_GPC['id'])), 'error');
		}else{
			message('设置用户权限成功！', $this->createWebUrl('fansmanager'), 'success');
		}
	}
	
	// 充值
	if($op=='recharge'){
		$id = $_GPC['id'];
		$user = pdo_fetch("select * from ".tablename('hc_moreshop_member'). " where id = ".$id);
		if($_GPC['opp']=='recharged'){
			if(empty($_GPC['commission'])){
				$com = 0;
			} else {
				if(!is_numeric($_GPC['commission'])){
					message('佣金请输入合法数字！', '', 'error');
				} else {
					$com = $_GPC['commission'];
				}
			}
			
			if(empty($_GPC['money'])){
				$money = 0;
			} else {
				if(!is_numeric($_GPC['money'])){
					message('充值请输入合法数字！', '', 'error');
				} else {
					$money = $_GPC['money'];
				}
			}
			if($_GPC['inputtype'] == 1){
				load()->model('payment');
				$setting = uni_setting($_W['uniacid'], array('payment'));
				if(!is_array($setting['payment'])) {
					exit('没有设定支付参数.');
				}
				$appid = pdo_fetchcolumn("select `key` from ".tablename('account_wechats')." where uniacid = ".$_W['uniacid']);
				$wechat = $setting['payment']['wechat'];
				$mach_id = $wechat['mchid'];								//微信支付商户号
				$total_amount = $com*100;									//发送红包金额，单位分
				$wishing = '收佣愉快!';										//红包祝福语
				$remark = trim($_GPC['content']);							//备注
				$act_name = '佣金充值';										//活动名称
				$key = $wechat['signkey'];									//微信支付秘钥
				$send_name = $_W['username'];								//发送红包人名称
				$openid = $user['from_user'];								//被发送人的openid
				$back = get_red_bag($appid,$mach_id,$openid,$total_amount,$wishing,$act_name,$remark,$key,$send_name);
				$string = $back;
				//var_dump($back);
				$xml = simplexml_load_string($string);
				//$return_msg = $xml->return_msg;//这里返回的依然是个SimpleXMLElement对象
				$return_msg = (string)$xml->return_msg;	  //商户平台返回信息
				$return_code = (string)$xml->return_code; //商户平台返回代码
				if($return_code=='SUCCESS'){
					if(!empty($com)){
						$recharged = array(
							'weid'=>$_W['uniacid'],
							'mid'=>$id,
							'flag'=>1,
							'content'=>trim($_GPC['content']),
							'commission'=>$com,
							'createtime'=>time()
						);
						$temp = pdo_insert('hc_moreshop_commission', $recharged);
					} else {
						$temp = true;
					}
				} else {
					$temp = false;
				}
			} else {
				if(!empty($com)){
					$recharged = array(
						'weid'=>$_W['uniacid'],
						'mid'=>$id,
						'flag'=>1,
						'content'=>trim($_GPC['content']),
						'commission'=>$com,
						'createtime'=>time()
					);
					$temp = pdo_insert('hc_moreshop_commission', $recharged);
					load()->model('mc');
					$profile = pdo_fetch("select uid from ".tablename('mc_mapping_fans')." where uniacid = ".$_W['uniacid']." and openid = '".$user['from_user']."'");
					mc_credit_update($profile['uid'], 'credit2', $com, '多店分销结佣记录');
				} else {
					$temp = true;
				}
			}
			if(!empty($money)){
				$imoney = array(
					'weid'=>$_W['uniacid'],
					'mid'=>$id,
					'money'=>$money,
					'moneycontent'=>trim($_GPC['moneycontent']),
					'createtime'=>time(),
				);
				pdo_insert('hc_moreshop_inputmoney', $imoney);
				load()->model('mc');
				$profile = pdo_fetch("select uid from ".tablename('mc_mapping_fans')." where uniacid = ".$_W['uniacid']." and openid = '".$user['from_user']."'");
				mc_credit_update($profile['uid'], 'credit2', $money, '多店分销充值记录');
			}
			// 已结佣金
			$commission = pdo_fetchcolumn("select commission from ".tablename('hc_moreshop_member'). " where id = ".$id);
			if(empty($return_msg)){
				$return_msg = '充值失败，请重新充值！';
			}
			if(empty($temp)){
				message($return_msg, $this->createWebUrl('fansmanager', array('op'=>'recharge', 'id'=>$_GPC['id'])), 'error');
			}else{
				pdo_update('hc_moreshop_member', array('commission'=>$commission+$_GPC['commission']), array('id'=>$id));
				message('充值成功！', $this->createWebUrl('fansmanager', array('op'=>'recharge', 'id'=>$_GPC['id'])), 'success');
			}
		}
		
		$commission = pdo_fetchcolumn("select sum(commission) from ".tablename('hc_moreshop_commission')." where mid = ".$id." and flag = 0 and weid = ".$_W['uniacid']);
		$commission = empty($commission)?0:$commission;
		// 可结佣金
		$commission = ($commission*1000 - $user['commission']*1000)/1000;
		// 充值记录
		$commissions = pdo_fetchall("select * from ".tablename('hc_moreshop_commission')." where mid = ".$id." and weid = ".$_W['uniacid']." and flag = 1");
		include $this->template('fansmanager_recharge');
		exit;
	}
	
	$userdefaulttotal = pdo_fetchcolumn("select userdefault from ".tablename('hc_moreshop_rules')." where weid = ".$weid);
	
	if($op=='lowfans'){
		if(empty($userdefaulttotal)){
			$userdefaulttotal = 1;
		}
		$fanslevel = array();
		$lowfansid = '('.intval($_GPC['id']).')';
		$lowfansids = array();
		for($i=1; $i<=$userdefaulttotal; $i++){
			$lowfansids[$i] = $lowfansid;
			$fanslevel[$i] = pdo_fetchall("select * from ".tablename('hc_moreshop_member')." where shareid in ".$lowfansid." and status = 1 and id != '".$_GPC['id']."'");
			$lowfansid = '';
			if(!empty($fanslevel[$i])){
				foreach($fanslevel[$i] as $f){
					$lowfansid = $lowfansid.$f['id'].',';
				}
				$lowfansid = '('.trim($lowfansid, ',').')';
			} else {
				break;
			}
			if($i==$_GPC['level']){
				break;
			}
		}
		if(empty($lowfansids[$_GPC['level']])){
			$lowfansids = "(".'-1'.")";
		} else {
			$lowfansids = $lowfansids[$_GPC['level']];
		}
		$list = pdo_fetchall("select * from ".tablename('hc_moreshop_member')." where shareid in ".$lowfansids." and status = 1");
	}
	if(intval($userdefaulttotal)>0){
		$fanslevel = array();
		for($i=1; $i<=$userdefaulttotal; $i++){
			$fanslevel[$i] = $i;
		}
	}
	
	include $this->template('fansmanager');
?>