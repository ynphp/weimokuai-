<?php
	$members = pdo_fetchall("select id, realname, mobile from ".tablename('hc_moreshop_member')." where weid = ".$_W['uniacid']." and status = 1");
	$member = array();
	foreach($members as $m){
		$member['realname'][$m['id']] = $m['realname'];
		$member['mobile'][$m['id']] = $m['mobile'];
	}
	// 正在申请
	if($op=='display'){
		if($_GPC['opp']=='sort'){
			$sort = array(
				'realname'=>$_GPC['realname'],
				'mobile'=>$_GPC['mobile']
			);
			//$shareid = pdo_fetchall("select id from ".tablename('hc_moreshop_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'");
			$mid = "select id from ".tablename('hc_moreshop_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'";
			$list = pdo_fetchall("select * from ".tablename('hc_moreshop_moneyapply')." where status = 0 and weid = ".$_W['uniacid']." and mid in (".$mid.") ORDER BY id desc");
			$total = sizeof($list);
		}else{
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("select * from ".tablename('hc_moreshop_moneyapply'). " where status = 0 and weid = ".$_W['uniacid']." ORDER BY id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
			$total = pdo_fetchcolumn("select count(id) from ".tablename('hc_moreshop_moneyapply')." where status = 0 and weid = ".$_W['uniacid']);
			$pager = pagination1($total, $pindex, $psize);
		}
		include $this->template('moneyapplying');
		exit;
	}
	if($op=='detail'){
		$id = intval($_GPC['id']);
		if($_GPC['opp']=='submit'){
			load()->model('payment');
			$setting = uni_setting($_W['uniacid'], array('payment'));
			if(!is_array($setting['payment'])) {
				exit('没有设定支付参数.');
			}
			$detail = pdo_fetch("select mid, money from ".tablename('hc_moreshop_moneyapply'). " where id = ".$id);
			$from_user = pdo_fetchcolumn("select from_user from ".tablename('hc_moreshop_member')." where id = ".$detail['mid']);
			$appid = pdo_fetchcolumn("select `key` from ".tablename('account_wechats')." where uniacid = ".$_W['uniacid']);
			$wechat = $setting['payment']['wechat'];
			$mach_id = $wechat['mchid'];								//微信支付商户号
			$total_amount = $detail['money']*100;						//发送红包金额，单位分
			$wishing = '提现愉快!';										//红包祝福语
			$remark = trim($_GPC['content']);							//备注
			$act_name = '余额提现';										//活动名称
			$key = $wechat['signkey'];									//微信支付秘钥
			$send_name = $_W['username'];								//发送红包人名称
			$openid = $from_user;								//被发送人的openid
			$back = get_red_bag($appid,$mach_id,$openid,$total_amount,$wishing,$act_name,$remark,$key,$send_name);
			$string = $back;
			//var_dump($back);
			$xml = simplexml_load_string($string);
			//$return_msg = $xml->return_msg;//这里返回的依然是个SimpleXMLElement对象
			$return_msg = (string)$xml->return_msg;	  //商户平台返回信息
			$return_code = (string)$xml->return_code;
			if($return_code=='SUCCESS'){
				pdo_update('hc_moreshop_moneyapply', array('content'=>trim($_GPC['content']), 'status'=>1), array('id'=>$id));
				message('发送成功成功！', $this->createWebUrl('moneyapply'), 'success');
			} else {
				message($return_msg, $this->createWebUrl('moneyapply'), 'error');
			}
			
		} else {
			$detail = pdo_fetch("select * from ".tablename('hc_moreshop_moneyapply'). " where id = ".$id);
			include $this->template('moneyapplying_detail');
			exit;
		}
	}	
	// 审核通过
	if($op=='applyed'){
		if($_GPC['opp']=='sort'){
			$sort = array(
				'realname'=>$_GPC['realname'],
				'mobile'=>$_GPC['mobile']
			);
			//$shareid = pdo_fetchall("select id from ".tablename('hc_moreshop_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'");
			$mid = "select id from ".tablename('hc_moreshop_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'";
			$list = pdo_fetchall("select * from ".tablename('hc_moreshop_moneyapply'). " where weid = ".$_W['uniacid']." and status = 1 and mid in (".$mid.") ORDER BY id desc");
			$total = sizeof($list);
		}else{
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("select * from ".tablename('hc_moreshop_moneyapply')." where weid = ".$_W['uniacid']." and status = 1 ORDER BY id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
			$total = pdo_fetchcolumn("select count(id) from ".tablename('hc_moreshop_moneyapply'). " where weid = ".$_W['uniacid']." and status = 1");
			$pager = pagination($total, $pindex, $psize);
		}
		include $this->template('moneyapplyed');
		exit;
	}?>