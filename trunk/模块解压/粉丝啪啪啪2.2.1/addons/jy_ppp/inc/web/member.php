<?php
global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();

		$op=$_GPC['op'];

		if($op=='del')
		{
			$id=$_GPC['id'];
			pdo_update('jy_ppp_member',array('status'=>0),array('id'=>$id));
			message("封号成功！",$this->createWebUrl('member'),'success');
		}
		else if($op=='huifu')
		{
			$id=$_GPC['id'];
			pdo_update('jy_ppp_member',array('status'=>1),array('id'=>$id));
			message("解封成功！",$this->createWebUrl('member'),'success');
		}
		else if($op=='del2')
		{
			$id=$_GPC['id'];
			pdo_delete('jy_ppp_member',array('id'=>$id));
			pdo_delete('jy_ppp_xuni_member',array('mid'=>$id));
			pdo_delete('jy_ppp_basic',array('mid'=>$id));
			pdo_delete('jy_ppp_desc',array('mid'=>$id));
			pdo_delete('jy_ppp_match',array('mid'=>$id));
			pdo_delete('jy_ppp_mobile',array('mid'=>$id));
			pdo_delete('jy_ppp_idcard',array('mid'=>$id));
			pdo_delete('jy_ppp_code',array('mid'=>$id));
			pdo_delete('jy_ppp_chat_doubi',array('mid'=>$id));
			pdo_delete('jy_ppp_mianrao',array('mid'=>$id));
			pdo_delete('jy_ppp_aihao',array('mid'=>$id));
			pdo_delete('jy_ppp_tezheng',array('mid'=>$id));

			pdo_delete('jy_ppp_visit',array('mid'=>$id));
			pdo_delete('jy_ppp_visit',array('visitid'=>$id));
			pdo_delete('jy_ppp_attent',array('mid'=>$id));
			pdo_delete('jy_ppp_attent',array('attentid'=>$id));
			pdo_delete('jy_ppp_black',array('mid'=>$id));
			pdo_delete('jy_ppp_black',array('blackid'=>$id));
			pdo_delete('jy_ppp_thumb',array('mid'=>$id));
			pdo_delete('jy_ppp_report',array('mid'=>$id));
			pdo_delete('jy_ppp_xinxi',array('mid'=>$id));
			pdo_delete('jy_ppp_xinxi',array('sendid'=>$id));
			pdo_delete('jy_ppp_kefu',array('mid'=>$id));
			message("删除用户成功！",$this->createWebUrl('member'),'success');
		}
		else if($op=='mingxi')
		{
			$id=$_GPC['id'];
			load()->func('tpl');
			$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
			$row=pdo_fetch("SELECT a.*,b.nickname,b.avatar as avatar2 FROM ".tablename('jy_ppp_member')." as a left join ".tablename('mc_members')." as b on a.uid =b.uid WHERE a.id= ".$id);
			if($row['type']==3)
			{
				$thumb_arr=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_thumb')." WHERE weid=".$weid." AND mid=".$id." AND type!=4");
				if(!empty($thumb_arr))
				{
					$thumb=array();
					foreach ($thumb_arr as $key => $t) {
						array_push($thumb, $t['thumb']);
					}
				}
			}
			if(checksubmit('submit2')) {
				$nicheng=$_GPC['nicheng'];
				if(!empty($nicheng) && $nicheng!=$row['nicheng'])
				{
					$data['nicheng']=$nicheng;
				}
				if(!empty($_GPC['pwd']) && $_GPC['pwd']!=$row['pwd'])
				{
					$data['pwd']=$_GPC['pwd'];
				}
				if(!empty($_GPC['brith']['month']) && !empty($_GPC['brith']['day']) && !empty($_GPC['brith']['year']))
				{
					$brith=mktime(0,0,0,$_GPC['brith']['month'],$_GPC['brith']['day'],$_GPC['brith']['year']);
					$data['brith']=$brith;
				}
				if(!empty($_GPC['beizhu']) && $_GPC['beizhu']!=$row['beizhu'])
				{
					$data['beizhu']=$_GPC['beizhu'];
				}
				if(!empty($_GPC['province']) && $_GPC['province']!=$row['province'])
				{
					$data['province']=$_GPC['province'];
				}
				if(!empty($_GPC['city']) && $_GPC['city']!=$row['city'])
				{
					$data['city']=$_GPC['city'];
				}
				if(!empty($_GPC['avatar']) && $_GPC['avatar']!=$row['avatar'])
				{
					$data['avatar']=$_GPC['avatar'];
					if(!empty($thumb))
					{
						if(!in_array($_GPC['avatar'], $thumb))
						{
							pdo_insert("jy_ppp_thumb",array('weid'=>$weid,'mid'=>$id,'type'=>1,'thumb'=>$_GPC['avatar'],'createtime'=>TIMESTAMP));
						}
					}
					else
					{
						pdo_insert("jy_ppp_thumb",array('weid'=>$weid,'mid'=>$id,'type'=>1,'thumb'=>$_GPC['avatar'],'createtime'=>TIMESTAMP));
					}
				}

				pdo_update("jy_ppp_member",$data,array('id'=>$id));
				if(!empty($_GPC['thumb']))
				{
					$dif_thumb=array();
					foreach ($_GPC['thumb'] as $k => $b) {
						if(!empty($thumb))
						{
							if(!in_array($b, $thumb))
							{
								pdo_insert("jy_ppp_thumb",array('weid'=>$weid,'mid'=>$id,'type'=>1,'thumb'=>$b,'createtime'=>TIMESTAMP));
							}
							else
							{
								array_push($dif_thumb, $b);
							}
						}
						else
						{
							pdo_insert("jy_ppp_thumb",array('weid'=>$weid,'mid'=>$id,'type'=>1,'thumb'=>$b,'createtime'=>TIMESTAMP));
						}
					}
					$temp_dif=array_diff($thumb,$dif_thumb);
					if(!empty($temp_dif))
					{
						foreach ($temp_dif as $key => $dif) {
							pdo_delete('jy_ppp_thumb',array('mid'=>$id,'thumb'=>$dif));
						}
					}
				}
				message("更新数据成功！",$this->createWebUrl('member',array('id'=>$id,'op'=>'mingxi')),'success');
			}
			if(checksubmit('submit3')) {
				if($row['credit']>$_GPC['credit'])
				{
					$credit=$row['credit']-$_GPC['credit'];
					$data2=array(
							'weid'=>$weid,
							'mid'=>$id,
							'credit'=>$credit,
							'type'=>'reduce',
							'log'=>3,
							'logid'=>0,
							'createtime'=>TIMESTAMP,
						);
					pdo_insert("jy_ppp_credit_log",$data2);
				}
				if($row['credit']<$_GPC['credit'])
				{
					$credit=$_GPC['credit']-$row['credit'];
						$data2=array(
							'weid'=>$weid,
							'mid'=>$id,
							'credit'=>$credit,
							'type'=>'add',
							'log'=>3,
							'logid'=>0,
							'createtime'=>TIMESTAMP,
						);
					pdo_insert("jy_ppp_credit_log",$data2);
				}
				pdo_update("jy_ppp_member",array('credit'=>$_GPC['credit']),array('id'=>$id));
				message("更新积分成功！",$this->createWebUrl('member',array('id'=>$id,'op'=>'mingxi')),'success');
			}
			$item['feedback']=pdo_fetchall("SELECT a.*,b.nicheng,b.sex,b.mobile,b.avatar,c.nickname,c.avatar as avatar2 FROM ".tablename('jy_ppp_feedback')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.mid=".$row['id']);

			$year=date('Y',$row['brith']);
			$month=date('m',$row['brith']);
			$day=date('d',$row['brith']);
			$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');

			$basic=pdo_fetch("SELECT * FROM ".tablename("jy_ppp_basic")." WHERE weid=".$weid." AND mid=".$id);
			$desc=pdo_fetch("SELECT * FROM ".tablename("jy_ppp_desc")." WHERE weid=".$weid." AND mid=".$id);
			$desc['aihao']=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_aihao')." WHERE weid=".$weid." AND mid=".$id);
			$desc['tezheng']=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_tezheng')." WHERE weid=".$weid." AND mid=".$id);
			$match=pdo_fetch("SELECT * FROM ".tablename("jy_ppp_match")." WHERE weid=".$weid." AND mid=".$id);
			if($row['type']!=3)
			{
				$item['pay']=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_pay_log')." WHERE weid=".$weid." AND mid=".$id);
				$item['login']=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_login_log')." WHERE weid=".$weid." AND mid=".$id);
				$item['jf']=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_credit_log')." WHERE mid=".$id." AND weid=".$weid);
				if(!empty($item['jf']))
				{
					foreach ($item['jf'] as $key => $value) {
						if($value['log']==1)
						{
							$item['jf'][$key]['name']="身份认证消耗";
						}
						if($value['log']==2)
						{
							$temp=pdo_fetch("SELECT nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$value['logid']);
							$item['jf'][$key]['name']="用积分兑换了'".$temp['nicheng']."'的永久畅聊";
						}
						if($value['log']==3)
						{
							if($value['type']=='add')
							{
								$item['jf'][$key]['name']="系统管理员将您的积分增加了'".$value['credit']."'个虚拟货币";
							}
							else
							{
								$item['jf'][$key]['name']="系统管理员将您的积分减少了'".$value['credit']."'个虚拟货币";
							}
						}
						if($value['log']==4)
						{
							$item['jf'][$key]['name']="用户充值了'".$value['credit']."'个虚拟货币";
						}
						if($value['type']=='add')
						{
							$count+=$value['credit'];
						}
						else
						{
							$count-=$value['credit'];
						}
					}
				}
				$item['baoyue']=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_baoyue_log')." WHERE mid=".$id." AND weid=".$weid);
				$item['shouxin']=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_shouxin_log')." WHERE mid=".$id." AND weid=".$weid);
			}
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

			if (!empty($_GPC['keyword'])) {
				$condition .= " AND ( a.nicheng LIKE '%{$_GPC['keyword']}%' OR a.mobile LIKE '%{$_GPC['keyword']}%'";
				$condition .=")";
			}
			if(!empty($_GPC['type']))
			{
				if($_GPC['type']=='2')
				{
					$condition .=" AND a.type=0 ";
				}
				if($_GPC['type']=='1')
				{
					$condition .=" AND a.type=1 ";
				}
				if($_GPC['type']=='3')
				{
					$condition .=" AND a.type=3 ";
				}
			}
			if(!empty($_GPC['sex']))
			{
				if($_GPC['sex']=='2')
				{
					$condition .=" AND a.sex=2 ";
				}
				if($_GPC['sex']=='1')
				{
					$condition .=" AND a.sex=1 ";
				}
			}
			if(!empty($_GPC['sort']))
			{
				if($_GPC['sort']=='time')
				{
					$condition .=" ORDER BY a.createtime DESC";
				}
				if($_GPC['sort']=='jifen')
				{
					$condition .=" ORDER BY a.credit DESC";
				}
				if($_GPC['sort']=='baoyue')
				{
					$condition .=" ORDER BY a.baoyue DESC";
				}
			}
			else
			{
				$condition .=" ORDER BY a.createtime DESC";
			}
			
			if(!empty($_GPC['id']))
			{
				$condition.=" AND a.id=".$_GPC['id'];
			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');

			$list=pdo_fetchall("SELECT a.* FROM ".tablename('jy_ppp_member')." as a   WHERE a.weid=$weid $condition LIMIT ".($pindex - 1) * $psize.",{$psize}");
			$total=pdo_fetchcolumn("SELECT count(a.id) FROM ".tablename('jy_ppp_member')." as a WHERE a.weid=$weid $condition ");
			$pager = pagination($total, $pindex, $psize);
		}

		include $this->template('web/member');