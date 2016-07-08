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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'xhdoubi','id'=>$_GPC['id']))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'xhdoubi','id'=>$_GPC['id']))."';
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
			$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
			$id=$_GPC['id'];
			if(empty($id))
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('detail')."';
					</script>";
			}
			else
			{
				$op=$_GPC['op'];
				if($op=='xh')
				{
					$id=$_GPC['id'];
					$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_chat_doubi')." WHERE weid=".$weid." AND mid=".$mid." AND chatid=".$id);
					if(!empty($temp))
					{
						echo 2;
						exit;
					}
					else
					{
						if(empty($sitem['chat']))
						{
							$sitem['chat']=20;
						}
						$temp=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);
						if($temp['credit']>$sitem['chat'])
						{
							$data=array(
								'weid'=>$weid,
								'mid'=>$mid,
								'chatid'=>$id,
								'createtime'=>TIMESTAMP,
							);
							pdo_insert('jy_ppp_chat_doubi',$data);
							pdo_update("jy_ppp_member",array('credit'=>$temp['credit']-$sitem['chat']),array('id'=>$mid));
							$data2=array(
									'weid'=>$weid,
									'mid'=>$mid,
									'credit'=>$sitem['chat'],
									'type'=>'reduce',
									'logid'=>$id,
									'log'=>2,
									'createtime'=>TIMESTAMP,
								);
							pdo_insert("jy_ppp_credit_log",$data2);
							echo 1;
							exit;
						}
						else
						{
							echo 3;
							exit;
						}
					}
				}
				else
				{
					$temp=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
					if(empty($temp['province']))
					{
						$temp['province']='11';
					}
					$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');
					$birthday=$temp['brith'];
					if(empty($birthday))
					{
						$birthday=662688000;
					}
					$now=time();
				    $month=0;
				    if(date('m', $now)>date('m', $birthday))
				    $month=1;
				    if(date('m', $now)==date('m', $birthday))
				    if(date('d', $now)>=date('d', $birthday))
				    $month=1;
				    $nianlin=date('Y', $now)-date('Y', $birthday)+$month;
					include $this->template('xhdoubi');
				}
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