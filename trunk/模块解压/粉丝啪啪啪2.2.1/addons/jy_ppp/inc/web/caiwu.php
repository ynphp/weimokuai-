<?php
global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();

		load()->func('tpl');

		$now_day=strtotime(date('Y-m-d', time()));
		$time = $_GPC['time'];
		$starttime = empty($time['start']) ? $now_day : strtotime($time['start']);
		$endtime   = empty($time['end'])   ? $now_day + 7*86400 : strtotime($time['end']) + 86399;
		if (!empty($_GPC['time'])) {
			$condition.=" AND createtime>=$starttime AND createtime<=$endtime ";
			$condition2.=" AND a.createtime>=$starttime AND a.createtime<=$endtime ";
		}
		else
		{
			$starttime=$now_day - 7*86400;
			$endtime=$now_day + 86400;
			$condition.=" AND createtime>=$starttime AND createtime<=$endtime ";
		}
		$pay=pdo_fetchcolumn("SELECT sum(fee) FROM ".tablename('jy_ppp_pay_log')." WHERE weid=".$weid." AND status=1 ".$condition);
		$credit=pdo_fetchcolumn("SELECT sum(fee) FROM ".tablename('jy_ppp_pay_log')." WHERE weid=".$weid." AND status=1 AND log=1 ".$condition);
		$baoyue=pdo_fetchcolumn("SELECT sum(fee) FROM ".tablename('jy_ppp_pay_log')." WHERE weid=".$weid." AND status=1 AND log=2 ".$condition);
		$shouxin=pdo_fetchcolumn("SELECT sum(fee) FROM ".tablename('jy_ppp_pay_log')." WHERE weid=".$weid." AND status=1 AND log=3 ".$condition);


		$op=$_GPC['op'];

		if(empty($op) || $op=='pay')
		{
			$pay_tu_temp=pdo_fetchall("SELECT id,fee,createtime FROM ".tablename('jy_ppp_pay_log')." WHERE weid=".$weid." AND status=1 ".$condition);
		}
		if(empty($op) || $op=='credit')
		{
			$credit_tu_temp=pdo_fetchall("SELECT id,fee,createtime FROM ".tablename('jy_ppp_pay_log')." WHERE weid=".$weid." AND status=1 AND log=1 ".$condition);
		}
		if(empty($op) || $op=='baoyue')
		{
			$baoyue_tu_temp=pdo_fetchall("SELECT id,fee,createtime FROM ".tablename('jy_ppp_pay_log')." WHERE weid=".$weid." AND status=1 AND log=2 ".$condition);
		}
		if(empty($op) || $op=='shouxin')
		{
			$shouxin_tu_temp=pdo_fetchall("SELECT id,fee,createtime FROM ".tablename('jy_ppp_pay_log')." WHERE weid=".$weid." AND status=1 AND log=3 ".$condition);
		}



		$riqi=array();
		for ($i=$starttime; $i <=$endtime ; $i+=86400) {
			$temp=date('Y-m-d',$i);
			$riqi[]=$temp;
			if(empty($op) || $op=='pay')
			{
				$pay_tu[$temp]=0;
			}
			if(empty($op) || $op=='credit')
			{
				$credit_tu[$temp]=0;
			}
			if(empty($op) || $op=='baoyue')
			{
				$baoyue_tu[$temp]=0;
			}
			if(empty($op) || $op=='shouxin')
			{
				$shouxin_tu[$temp]=0;
			}

		}


		if(empty($op) || $op=='pay')
		{
			foreach ($pay_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$pay_tu[$temp]+=$value['fee'];
			}
		}
		if(empty($op) || $op=='credit')
		{
			if(!empty($credit_tu_temp))
			{
				foreach ($credit_tu_temp as $key => $value) {
					$temp=date('Y-m-d',$value['createtime']);
					$credit_tu[$temp]+=$value['fee'];
				}
			}
		}
		if(empty($op) || $op=='baoyue')
		{
			if(!empty($baoyue_tu_temp))
			{
				foreach ($baoyue_tu_temp as $key => $value) {
					$temp=date('Y-m-d',$value['createtime']);
					$baoyue_tu[$temp]+=$value['fee'];
				}
			}
		}
		if(empty($op) || $op=='shouxin')
		{
			if(!empty($shouxin_tu_temp))
			{
				foreach ($shouxin_tu_temp as $key => $value) {
					$temp=date('Y-m-d',$value['createtime']);
					$shouxin_tu[$temp]+=$value['fee'];
				}
			}
		}

		$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);

		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		if($op=='pay')
		{
			$pay_list=pdo_fetchall("SELECT a.createtime,a.fee,a.id,a.paytime,a.log,b.nicheng,b.mobile as mobile2,b.mobile_auth,c.nickname,c.avatar FROM ".tablename('jy_ppp_pay_log')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.weid= ".$weid." AND a.status=1 ".$condition2."LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$pay_total = count(pdo_fetchall("SELECT a.id FROM ".tablename('jy_ppp_pay_log')." as a  WHERE a.weid=".$weid." AND a.status=1".$condition2));
			$pager = pagination($pay_total, $pindex, $psize);
		}
		if($op=='credit')
		{
			$credit_list=pdo_fetchall("SELECT a.createtime,a.fee,a.id,a.paytime,a.log,b.nicheng,b.mobile as mobile2,b.mobile_auth,c.nickname,c.avatar FROM ".tablename('jy_ppp_pay_log')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.weid= ".$weid." AND a.status=1 AND a.log=1 ".$condition2."LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$credit_total = count(pdo_fetchall("SELECT a.id FROM ".tablename('jy_ppp_pay_log')." as a  WHERE a.weid=".$weid." AND a.status=1 AND a.log=1 ".$condition2));
			$pager = pagination($credit_total, $pindex, $psize);
		}
		if($op=='baoyue')
		{
			$baoyue_list=pdo_fetchall("SELECT a.createtime,a.fee,a.id,a.paytime,a.log,b.nicheng,b.mobile as mobile2,b.mobile_auth,c.nickname,c.avatar FROM ".tablename('jy_ppp_pay_log')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.weid= ".$weid." AND a.status=1 AND a.log=2 ".$condition2."LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$baoyue_total = count(pdo_fetchall("SELECT a.id FROM ".tablename('jy_ppp_pay_log')." as a  WHERE a.weid=".$weid." AND a.status=1 AND a.log=2 ".$condition2));
			$pager = pagination($baoyue_total, $pindex, $psize);
		}
		if($op=='shouxin')
		{
			$shouxin_list=pdo_fetchall("SELECT a.createtime,a.fee,a.id,a.paytime,a.log,b.nicheng,b.mobile as mobile2,b.mobile_auth,c.nickname,c.avatar FROM ".tablename('jy_ppp_pay_log')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.weid= ".$weid." AND a.status=1 AND a.log=3 ".$condition2."LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$shouxin_total = count(pdo_fetchall("SELECT a.id FROM ".tablename('jy_ppp_pay_log')." as a  WHERE a.weid=".$weid." AND a.status=1 AND a.log=3 ".$condition2));
			$pager = pagination($shouxin_total, $pindex, $psize);
		}


		include $this->template('web/caiwu');