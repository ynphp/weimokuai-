<?php
global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();

		$op=$_GPC['op'];
		if($op=='fenpei')
		{
			$str=$_GPC['str'];
			if(!empty($str))
			{
				$str=substr($str, 0 , -1);
			}
			$str_arr=explode(',', $str);
			$dyid=$_GPC['dyid'];
			foreach ($str_arr as $key => $value) {
				pdo_delete('jy_ppp_xuni_member',array('mid'=>$value));
				$data=array(
						'weid'=>$weid,
						'mid'=>$value,
						'dyid'=>$dyid,
					);
				pdo_insert('jy_ppp_xuni_member',$data);
			}
			message("分配成功!",$this->createWebUrl('fenpei'),'success');
		}
		elseif ($op=='del') {
			$str=$_GPC['str'];
			if(!empty($str))
			{
				$str=substr($str, 0 , -1);
			}
			$str_arr=explode(',', $str);
			foreach ($str_arr as $key => $value) {
				pdo_delete('jy_ppp_member',array('id'=>$value));
				pdo_delete('jy_ppp_xuni_member',array('mid'=>$value));
				pdo_delete('jy_ppp_basic',array('mid'=>$value));
				pdo_delete('jy_ppp_desc',array('mid'=>$value));
				pdo_delete('jy_ppp_match',array('mid'=>$value));
				pdo_delete('jy_ppp_mobile',array('mid'=>$value));
				pdo_delete('jy_ppp_idcard',array('mid'=>$value));
				pdo_delete('jy_ppp_code',array('mid'=>$value));
				pdo_delete('jy_ppp_chat_doubi',array('mid'=>$value));
				pdo_delete('jy_ppp_mianrao',array('mid'=>$value));
				pdo_delete('jy_ppp_aihao',array('mid'=>$value));
				pdo_delete('jy_ppp_tezheng',array('mid'=>$value));

				pdo_delete('jy_ppp_visit',array('mid'=>$value));
				pdo_delete('jy_ppp_visit',array('visitid'=>$value));
				pdo_delete('jy_ppp_attent',array('mid'=>$value));
				pdo_delete('jy_ppp_attent',array('attentid'=>$value));
				pdo_delete('jy_ppp_black',array('mid'=>$value));
				pdo_delete('jy_ppp_black',array('blackid'=>$value));
				pdo_delete('jy_ppp_thumb',array('mid'=>$value));
				pdo_delete('jy_ppp_report',array('mid'=>$value));
				pdo_delete('jy_ppp_xinxi',array('mid'=>$value));
				pdo_delete('jy_ppp_xinxi',array('sendid'=>$value));
				pdo_delete('jy_ppp_kefu',array('mid'=>$value));
			}
			message("删除成功!",$this->createWebUrl('fenpei'),'success');
		}
		else
		{
			$op='display';
			load()->func('tpl');
			$now_day=strtotime(date('Y-m-d', time()));
			$time = $_GPC['time'];
			$starttime = empty($time['start']) ? $now_day : strtotime($time['start']);
			$endtime   = empty($time['end'])   ? $now_day + 7*86400 : strtotime($time['end']) + 86399;
			$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
			if (!empty($_GPC['time'])) {
				$condition.=" AND a.createtime>=$starttime AND a.createtime<=$endtime ";
			}
			if(!empty($_GPC['sex']))
			{
				$condition.=" AND a.sex=".$_GPC['sex'];
			}
			if(!empty($_GPC['type']))
			{
				if($_GPC['type']==1)
				{
					$condition.=" AND b.dyid!=0 ";
				}
				else
				{
					$condition.=" AND ( b.dyid is NULL ) ";
				}
			}

			if (!empty($_GPC['keyword'])) {
				$condition .= " AND ( a.nicheng LIKE '%{$_GPC['keyword']}%' OR a.mobile LIKE '%{$_GPC['keyword']}%'";
				$condition .=")";
			}
			if(!empty($_GPC['province']))
			{
				$condition.=" AND a.province=".$_GPC['province'];
			}

			if(!empty($_GPC['city']))
			{
				$condition.=" AND a.city=".$_GPC['city'];
			}

			if(!empty($_GPC['id']))
			{
				$condition.=" AND a.id=".$_GPC['id'];
			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');

			$list=pdo_fetchall("SELECT a.*,b.dyid,c.username FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_xuni_member')." as b on a.id=b.mid left join ".tablename('jy_ppp_dianyuan')." as c on b.dyid=c.id  WHERE a.weid=$weid AND a.type=3 $condition LIMIT ".($pindex - 1) * $psize.",{$psize}");
			$total=pdo_fetchcolumn("SELECT count(a.id) FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_xuni_member')." as b on a.id=b.mid  WHERE a.weid=$weid AND a.type=3 $condition ");
			$pager = pagination($total, $pindex, $psize);
			$dianyuan=pdo_fetchall("SELECT a.*,c.nickname,c.avatar FROM ".tablename('jy_ppp_dianyuan')." as a LEFT JOIN ".tablename('mc_members')." as c on a.uid=c.uid WHERE weid=".$weid." AND status=1 ");
			if(!empty($dianyuan))
			{
				foreach ($dianyuan as $key => $value) {
					$dianyuan[$key]['num']=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_xuni_member')." WHERE weid=".$weid." AND dyid=".$value['id']);
				}
			}
			include $this->template('web/fenpei');
		}