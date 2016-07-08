<?php
global $_W,$_GPC;
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

			$mid=$_SESSION['mid'];
			if(!empty($mid))
			{
				$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid." AND status=1");
			}
		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];
			//checkAuth();
			$from_user=$_SESSION['jy_openid'];
			if(empty($from_user))
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'lianxi'))."';
				</script>";
			}
			else
			{
				$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
				if(empty($member_temp['nickname']) || $member_temp['uid']==0)
				{
					unset($uid);
				}
				else
				{
					$uid=$member_temp['uid'];
					unset($member_temp);
				}
				if(empty($uid))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'lianxi'))."';
					</script>";
				}
				else
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
					$mid=$member['id'];
				}
			}
		}

		if(!empty($member))
		{
			$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');
			$op=$_GPC['op'];
			if($op=='more')
			{
				$lastid=$_GPC['lastid'];
				if(empty($lastid))
				{
					echo json_encode(array('status'=>3));
					exit;
				}
				$last=pdo_fetch("SELECT mid,createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND id=".$lastid);
				if(empty($last['createtime']))
				{
					echo json_encode(array('status'=>4));
					exit;
				}
				$visit=pdo_fetchall("SELECT a.id,a.mid as mid,b.avatar,b.nicheng,b.province,b.brith,b.sex,c.height FROM ".tablename('jy_ppp_xinxi')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('jy_ppp_basic')." as c on a.mid=c.mid WHERE a.weid=".$weid." AND a.sendid=".$mid." AND a.type!=0 AND a.createtime<".$last['createtime']." GROUP BY a.mid ORDER BY a.createtime DESC LIMIT 10 ");
				$html='';
				if(!empty($visit))
				{
					foreach ($visit as $key => $l) {
						if(!empty($l['avatar']))
						{
							$avatar=$_W['attachurl'].$l['avatar'];
						}
						else
						{
							if($l['sex']==1)
							{
								$avatar="../addons/jy_ppp/images/boy.png";
							}
							else
							{
								$avatar="../addons/jy_ppp/images/girl.png";
							}
						}
						$ziliao='';
						if(!empty($l['brith']))
						{
							$birthday=$l['brith'];
							$now=time();
						    $month=0;
						    if(date('m', $now)>date('m', $birthday))
						    $month=1;
						    if(date('m', $now)==date('m', $birthday))
						    if(date('d', $now)>=date('d', $birthday))
						    $month=1;
						    $nianlin=date('Y', $now)-date('Y', $birthday)+$month;
							$ziliao.=$nianlin."岁";
						}
						if(!empty($l['province']))
						{
							$ziliao.="·".$province[$l['province']];
						}
						if(!empty($l['height']))
						{
							$ziliao.="·".$l['height']."cm";
						}
						$html.='<a href="'.$this->createMobileUrl('chat',array('id'=>$l['mid'])).'"><ul class="disbox-hor user-list"  data-id="'.$l['id'].'"><li class="foot-icon disbox-center"><img src="'.$avatar.'"></li><li class="disbox-flex user_mession"><b class="tit">'.$l['nicheng'].'</b><p class="bot">'.$ziliao.'</p></li><div class="right"><span class="hello">聊天</span></div></ul></a>';
					}
				}
				if(count($visit)<10)
				{
					echo json_encode(array('status'=>2,'log'=>$html));
					exit;
				}
				else
				{
					echo json_encode(array('status'=>1,'log'=>$html));
					exit;
				}
			}
			else
			{
				$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
				$lianxi=pdo_fetchall("SELECT a.id,a.mid as mid,b.avatar,b.nicheng,b.province,b.brith,b.sex,c.height FROM ".tablename('jy_ppp_xinxi')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('jy_ppp_basic')." as c on a.mid=c.mid WHERE a.weid=".$weid." AND a.sendid=".$mid." AND a.type!=0 GROUP BY a.mid ORDER BY a.createtime DESC LIMIT 10 ");


				include $this->template('lianxi');
			}

		}
		else
		{
			if($weixin==1)
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('zhuce')."';
					</script>";
			}
			else
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('login')."';
					</script>";
			}
		}