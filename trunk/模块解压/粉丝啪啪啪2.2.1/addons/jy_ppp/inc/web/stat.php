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
		$geren=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type!=3 ".$condition);
		$xuni=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND type=3 ".$condition);
		$fk=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_feedback')." WHERE weid=".$weid.$condition);
		$dy=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND status!=0 ".$condition);
		$thumb=pdo_fetchcolumn("SELECT count(a.id) FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND b.type!=3 AND a.type!=4 ".$condition2);
		$pay=pdo_fetchcolumn("SELECT sum(fee) FROM ".tablename('jy_ppp_pay_log')." WHERE weid=".$weid." AND status=1 ".$condition);
		$admin=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND type=3 ".$condition);
		$kefu=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid.$condition);
		$chat=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND type=2 ".$condition);
		$xinxi=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid.$condition);


		$op=$_GPC['op'];
		if(empty($op) || $op=='all')
		{
			$all_tu_temp=pdo_fetchall("SELECT id,createtime FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid.$condition);
		}
		if(empty($op) || $op=='geren')
		{
			$geren_tu_temp=pdo_fetchall("SELECT id,createtime FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid.$condition." AND type!=3");
		}
		if(empty($op) || $op=='xuni')
		{
			$qiye_tu_temp=pdo_fetchall("SELECT id,createtime FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid.$condition." AND type=3");
		}
		if(empty($op) || $op=='fk')
		{
			$fk_tu_temp=pdo_fetchall("SELECT id,createtime FROM ".tablename('jy_ppp_feedback')." WHERE weid=".$weid.$condition);
		}
		if(empty($op) || $op=='dy')
		{
			$dy_tu_temp=pdo_fetchall("SELECT id,createtime FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND status!=0 ".$condition);
		}
		if(empty($op) || $op=='thumb')
		{
			$thumb_tu_temp=pdo_fetchall("SELECT a.id,a.createtime FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id  WHERE a.weid=".$weid." AND b.type!=3 AND a.type!=4".$condition2);
		}
		if(empty($op) || $op=='pay')
		{
			$pay_tu_temp=pdo_fetchall("SELECT id,fee,createtime FROM ".tablename('jy_ppp_pay_log')." WHERE weid=".$weid." AND status=1 ".$condition);
		}
		if(empty($op) || $op=='admin')
		{
			$admin_tu_temp=pdo_fetchall("SELECT id,createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND type=3 ".$condition);
		}
		if(empty($op) || $op=='kefu')
		{
			$kefu_tu_temp=pdo_fetchall("SELECT id,createtime FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid.$condition);
		}
		if(empty($op) || $op=='chat')
		{
			$chat_tu_temp=pdo_fetchall("SELECT id,createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND type=2 ".$condition);
		}
		if(empty($op) || $op=='xinxi')
		{
			$xinxi_tu_temp=pdo_fetchall("SELECT id,createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid.$condition);
		}



		$riqi=array();
		for ($i=$starttime; $i <=$endtime ; $i+=86400) {
			$temp=date('Y-m-d',$i);
			$riqi[]=$temp;
			if(empty($op) || $op=='all')
			{
				$all_tu[$temp]=0;
			}
			if(empty($op) || $op=='geren')
			{
				$geren_tu[$temp]=0;
			}
			if(empty($op) || $op=='xuni')
			{
				$qiye_tu[$temp]=0;
			}
			if(empty($op) || $op=='fk')
			{
				$fk_tu[$temp]=0;
			}
			if(empty($op) || $op=='dy')
			{
				$dy_tu[$temp]=0;
			}
			if(empty($op) || $op=='thumb')
			{
				$thumb_tu[$temp]=0;
			}
			if(empty($op) || $op=='pay')
			{
				$pay_tu[$temp]=0;
			}
			if(empty($op) || $op=='admin')
			{
				$admin_tu[$temp]=0;
			}
			if(empty($op) || $op=='kefu')
			{
				$kefu_tu[$temp]=0;
			}
			if(empty($op) || $op=='chat')
			{
				$chat_tu[$temp]=0;
			}
			if(empty($op) || $op=='xinxi')
			{
				$xinxi_tu[$temp]=0;
			}

		}

		if(empty($op) || $op=='all')
		{
			foreach ($all_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$all_tu[$temp]++;
			}
		}

		if(empty($op) ||$op=='geren')
		{
			foreach ($geren_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$geren_tu[$temp]++;
			}
		}

		if(empty($op) || $op=='xuni')
		{
			foreach ($qiye_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$qiye_tu[$temp]++;
			}
		}

		if(empty($op) || $op=='fk')
		{
			foreach ($fk_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$fk_tu[$temp]++;
			}
		}

		if(empty($op) || $op=='dy')
		{
			foreach ($dy_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$dy_tu[$temp]++;
			}
		}

		if(empty($op) || $op=='thumb')
		{
			foreach ($thumb_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$thumb_tu[$temp]++;
			}
		}

		if(empty($op) || $op=='pay')
		{
			foreach ($pay_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$pay_tu[$temp]+=$value['fee'];
			}
		}

		if(empty($op) || $op=='admin')
		{
			foreach ($admin_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$admin_tu[$temp]++;
			}
		}

		if(empty($op) || $op=='kefu')
		{
			foreach ($kefu_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$kefu_tu[$temp]++;
			}
		}

		if(empty($op) || $op=='chat')
		{
			foreach ($chat_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$chat_tu[$temp]++;
			}
		}

		if(empty($op) || $op=='xinxi')
		{
			foreach ($xinxi_tu_temp as $key => $value) {
				$temp=date('Y-m-d',$value['createtime']);
				$xinxi_tu[$temp]++;
			}
		}

		$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
		$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');

		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		if($op=='all')
		{
			$all_list=pdo_fetchall("SELECT a.* FROM ".tablename('jy_ppp_member')." as a   WHERE a.weid=$weid $condition2 LIMIT ".($pindex - 1) * $psize.",{$psize}");
			$all_total = count(pdo_fetchall("SELECT a.* FROM " . tablename('jy_ppp_member') . " as a WHERE a.weid = ".$weid." $condition2"));
			$pager = pagination($all_total, $pindex, $psize);
		}
		if($op=='geren')
		{
			$geren_list=pdo_fetchall("SELECT a.* FROM ".tablename('jy_ppp_member')." as a   WHERE a.weid=$weid AND a.type!=3 $condition2 LIMIT ".($pindex - 1) * $psize.",{$psize}");
			$geren_total = count(pdo_fetchall("SELECT a.* FROM " . tablename('jy_ppp_member') . " as a WHERE a.weid = ".$weid." AND a.type!=3 $condition2"));
			$pager = pagination($geren_total, $pindex, $psize);
		}
		if($op=='xuni')
		{
			$xuni_list=pdo_fetchall("SELECT a.*,b.dyid,c.username FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_xuni_member')." as b on a.id=b.mid left join ".tablename('jy_ppp_dianyuan')." as c on b.dyid=c.id WHERE a.weid=$weid AND a.type=3 $condition2 LIMIT ".($pindex - 1) * $psize.",{$psize}");
			$xuni_total = count(pdo_fetchall("SELECT a.* FROM " . tablename('jy_ppp_member') . " as a WHERE a.weid = ".$weid." AND a.type=3 $condition2"));
			$pager = pagination($xuni_total, $pindex, $psize);
		}
		if($op=='dy')
		{
			$dy_list=pdo_fetchall("SELECT a.*,c.nickname,c.avatar FROM " . tablename('jy_ppp_dianyuan') . " AS a
					LEFT JOIN ".tablename('mc_members')." as c on a.uid=c.uid WHERE a.weid = ".$weid." AND a.status!=0 $condition2 LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$dy_total = count(pdo_fetchall("SELECT a.id FROM " . tablename('jy_ppp_dianyuan') . " as a WHERE a.weid = ".$weid." AND a.status!=0 $condition2"));
			$pager = pagination($dy_total, $pindex, $psize);
		}
		if($op=='thumb')
		{
			$thumb_list=pdo_fetchall("SELECT a.*,b.mobile as mobile2,b.nicheng,b.mobile_auth,c.nickname,c.avatar FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.weid=".$weid." AND b.type!=3 AND a.type!=4 $condition2 LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$thumb_total = count(pdo_fetchall("SELECT a.id FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id  WHERE a.weid=".$weid." AND b.type!=3 AND a.type!=4".$condition2));
			$pager = pagination($thumb_total, $pindex, $psize);
		}
		if($op=='pay')
		{
			$pay_list=pdo_fetchall("SELECT a.createtime,a.fee,a.id,a.paytime,a.log,b.nicheng,b.mobile as mobile2,b.mobile_auth,c.nickname,c.avatar FROM ".tablename('jy_ppp_pay_log')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.weid= ".$weid." AND a.status=1 ".$condition2."LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$pay_total = count(pdo_fetchall("SELECT a.id FROM ".tablename('jy_ppp_pay_log')." as a  WHERE a.weid=".$weid." AND a.status=1".$condition2));
			$pager = pagination($pay_total, $pindex, $psize);
		}
		if($op=='admin')
		{
			$admin_list=pdo_fetchall("SELECT a.*,b.nicheng,b.mobile as mobile2,b.mobile_auth,c.nickname,c.avatar FROM ".tablename('jy_ppp_xinxi')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.weid= ".$weid." AND a.type=3 ".$condition2." LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$pager = pagination($admin, $pindex, $psize);
		}
		if($op=='kefu')
		{
			$kefu_list=pdo_fetchall("SELECT a.*,b.nicheng,b.mobile as mobile2,b.mobile_auth,c.nickname,c.avatar FROM ".tablename('jy_ppp_kefu')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.weid= ".$weid.$condition2." LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$pager = pagination($kefu, $pindex, $psize);
		}
		if($op=='chat')
		{
			$chat_list=pdo_fetchall("SELECT a.*,b.mobile as mobile2,b.mobile_auth,c.nickname,c.avatar,d.mobile as mobile3,d.mobile_auth as mobile_auth2,e.nickname as nickname2,e.avatar as avatar2 FROM ".tablename('jy_ppp_xinxi')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid left join ".tablename('jy_ppp_member')." as d on a.sendid=d.id left join ".tablename('mc_members')." as e on d.uid=e.uid  WHERE a.weid= ".$weid." AND a.type=2 ".$condition2." LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$pager = pagination($chat, $pindex, $psize);
		}
		if($op=='xinxi')
		{
			$xinxi_list=pdo_fetchall("SELECT a.*,b.mobile as mobile2,b.mobile_auth,c.nickname,c.avatar,d.mobile as mobile3,d.mobile_auth as mobile_auth2,e.nickname as nickname2,e.avatar as avatar2 FROM ".tablename('jy_ppp_xinxi')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid left join ".tablename('jy_ppp_member')." as d on a.sendid=d.id left join ".tablename('mc_members')." as e on d.uid=e.uid  WHERE a.weid= ".$weid.$condition2." LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$pager = pagination($xinxi, $pindex, $psize);
		}
		if($op=='fk')
		{
			$fk_list=pdo_fetchall("SELECT a.*,b.nicheng,b.mobile as mobile2,b.mobile_auth,c.nickname,c.avatar FROM ".tablename('jy_ppp_feedback')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.weid=".$weid.$condition2." LIMIT ".($pindex - 1) * $psize.",{$psize}");
			$fk_total=pdo_fetchcolumn("SELECT count(a.id) FROM ".tablename('jy_ppp_feedback')." as a WHERE a.weid=$weid $condition2 ");
			$pager = pagination($fk_total, $pindex, $psize);
		}

		include $this->template('web/stat');