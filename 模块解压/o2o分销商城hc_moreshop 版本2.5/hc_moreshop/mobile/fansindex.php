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
	if(!empty($profile['shareid'])){
		$highmember = pdo_fetch("SELECT realname, headimg FROM ".tablename('hc_moreshop_member')." WHERE id = ".$profile['shareid']);
	}

	$starttime = strtotime(date('Y-m-d 00:00:00'));
	$endtime = strtotime(date('Y-m-d 23:59:59'));
	$credit = pdo_fetchcolumn("select credit from ".tablename('hc_moreshop_credit')." where flag = 0 and weid = ".$weid." and mid = ".$id." and createtime >= ".$starttime." and createtime <= ".$endtime);
	$credit = empty($credit)?-1:$credit;
	if($op=='report'){
		$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
		if(empty($follow) || $follow['follow']==0){
			echo -1;
			exit;
		}
		$credit1 = pdo_fetchcolumn("select credit from ".tablename('hc_moreshop_rules')." where weid = ".$weid);
		$creditrule = explode(",", $credit1);
		$low = intval($creditrule[0]);
		$high = intval($creditrule[1]);
		$credit1 = mt_rand($low, $high);
		$credits = array(
			'weid'=>$weid,
			'mid'=>$id,
			'credit'=>$credit1,
			'flag'=>0,
			'status'=>0,
			'total'=>1,
			'orderid'=>0,
			'goodsid'=>0,
			'createtime'=>time()
		);
		if($credit == -1){
			pdo_insert('hc_moreshop_credit', $credits);
			$fcredit = mc_fetch($follow['uid'], array('credit1'));
			pdo_update('mc_members', array('credit1'=>$fcredit['credit1']+$credit1), array('uid'=>$follow['uid']));
			//pdo_update('hc_moreshop_member', array('credit'=>$profile['credit']+$credit1), array('id'=>$id));
			echo $credit1;
			exit;
		} else {
			echo 0;
			exit;
		}
	}
	
	$imgname = $weid."share$id.png";
	$imgurl = "../addons/hc_moreshop/style/images/share/$imgname";
	if(!file_exists($imgurl)){
		include "phpqrcode.php";//引入PHP QR库文件
		$value = $_W['siteroot'].'app/'.$this->createMobileUrl('index',array('mid'=>$id));
		$errorCorrectionLevel = "L";
		$matrixPointSize = "4";
		QRcode::png($value, $imgurl, $errorCorrectionLevel, $matrixPointSize);
	}
	$gzurl = pdo_fetch("select gzurl, description, openrequire from ".tablename('hc_moreshop_rules')." where weid = ".$weid);
	if($op=='myqrcode'){
		$target_file = IA_ROOT.'/addons/hc_moreshop/qrcode/qrshare/'.$weid."share$id.jpg";
		$isexist = 1;
		if(!file_exists($target_file)){
			$isexist = 0;
		}
		include $this->template('myqrcode');
		exit;
	}
	
	if($op=='shareqrcode'){
		$profile = pdo_fetch('SELECT * FROM '.tablename('hc_moreshop_member')." WHERE id = ".$_GPC['mid']);
		$id = $profile['id'];
		$target_file = IA_ROOT.'/addons/hc_moreshop/qrcode/qrshare/'.$weid."share$id.jpg";
		$isexist = 1;
		if(!file_exists($target_file)){
			$isexist = 0;
		}
		include $this->template('myqrcode');
		exit;
	}
	
	if($op=='openedit'){
		include $this->template('openedit');
		exit;
	}
	
	if($op=='display'){
		$follow = pdo_fetch("select uid from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
		if(!empty($follow)){
			$fcredit = mc_fetch($follow['uid'], array('credit1', 'credit2'));
			$fcredit1 = $fcredit['credit1']*100/100;
			$fcredit2 = $fcredit['credit2']*100/100;
		} else {
			$fcredit1 = 0;
			$fcredit2 = 0;
		}
		$shophost = pdo_fetch("select * from ".tablename('hc_moreshop_shophost')." where weid = ".$weid." and mid = ".$id);
		if(empty($shophost)){
			$isopen = -1;
		} else if (empty($shophost['ischeck'])){
			$isopen = 0;
		} else {
			$isopen = 1;
		}
		$lists = pdo_fetchall("SELECT count(id) as sum, status FROM " . tablename('hc_moreshop_order') . " WHERE weid = ".$weid." and from_user = '".$_W['openid']."' group by status");
		$list = array(
			'0'=>0,
			'1'=>0,
			'2'=>0,
			'3'=>0,
			'4'=>0,
			'5'=>0
		);
		foreach($lists as $l){
			if($l['status']==-1){
				$list[4] = $l['sum'];
			} else if ($l['status']==-2){
				$list[5] = $l['sum'];
			} else {
				$list[$l['status']] = $l['sum'];
			}
		}
		// 总佣金
		$allcommission = pdo_fetchcolumn("select sum(commission) from ".tablename('hc_moreshop_commission')." where flag = 0 and mid = ".$id." and weid = ".$weid);
		$allcommission = empty($allcommission)?0.00:$allcommission;
		// 已结佣
		$commissioned = pdo_fetchcolumn("select sum(commission) from ".tablename('hc_moreshop_commission')." where (flag = 1 or flag = 2) and mid = ".$id." and weid = ".$weid);
		$commissioned = empty($commissioned)?0.00:$commissioned;
		// 可结佣
		$commissioning = $allcommission - $commissioned;
		// 充值金额
		$imoney = pdo_fetchcolumn("select sum(money) from ".tablename('hc_moreshop_inputmoney')." where weid = ".$weid." and mid = ".$id);
		$imoney = empty($imoney)?0.00:$imoney;
		// 是否够资格开店编辑
		if(intval($gzurl['openrequire']) > intval($commissioned+$imoney)){
			$isenough = 0;
		} else {
			$isenough = 1;
		}
		$ffkisopen = pdo_fetchcolumn("select isopen from ".tablename('hc_moreshop_fangfangkan')." where uniacid = ".$weid);
	}

	if($op=='loworder'){
		$level = heihei(intval($_GPC['level']));
		if($level >= 4){
			$level = 4;
			$list = pdo_fetchall("SELECT * FROM " . tablename('hc_moreshop_memberrelative') . " WHERE shareid = ".$id." and userdefault >= 4 and weid = ".$weid." ORDER BY createtime DESC");
		} else {
			$list = pdo_fetchall("SELECT * FROM " . tablename('hc_moreshop_memberrelative') . " WHERE shareid = ".$id." and userdefault = ".$_GPC['level']." and weid = ".$weid." ORDER BY createtime DESC");
		}
		$goods = pdo_fetchall("select id, title from ".tablename('hc_moreshop_goods'). " where weid = ".$weid. " and status = 1");
		$orders = pdo_fetchall("select id, status from ".tablename('hc_moreshop_order'). " where weid = ".$weid);
		$good = array();
		$order = array();
		foreach($goods as $g){
			$good[$g['id']] = $g['title'];
		}
		foreach($orders as $g){
			$order[$g['id']] = $g['status'];
		}
		include $this->template('fansorder');
		exit;
	}
	
	if($op=='lowfans'){
		$level = heihei(intval($_GPC['level']));
		if($level >= 4){
			$level = 4;
		}
		$lowfansids = $_GPC['lowfansids'];
		if(empty($lowfansids)){
			$lowfansids = "(".'-1'.")";
		}
		$fanslevel = pdo_fetchall("select * from ".tablename('hc_moreshop_member')." where shareid in ".$lowfansids." and status = 1");
		include $this->template('myfans');
		exit;
	}
	
	if($op=='lowshop'){
		$level = heihei(intval($_GPC['level']));
		if($level >= 4){
			$level = 4;
		}
		$lowshopids = $_GPC['lowshopids'];
		if(empty($lowshopids)){
			$lowshopids = "(".'-1'.")";
		}
		$shoplevel = pdo_fetchall("select * from ".tablename('hc_moreshop_shophost')." where shareid in ".$lowshopids." and ischeck = 1");
		include $this->template('myshop');
		exit;
	}
	
	include $this->template('fansindex');
?>