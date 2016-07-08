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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'userthumb','id'=>$_GPC['id']))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'userthumb','id'=>$_GPC['id']))."';
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
			if($op=='zhaohu')
			{
				$id=$_GPC['id'];
				if(empty($id))
				{
					echo 3;
					exit;
				}
				$today=strtotime("today");
				$temp=pdo_fetch("SELECT createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND mid=".$id." AND type=0 ORDER BY createtime DESC LIMIT 1 ");
				if(!empty($temp) && $temp['createtime']>$today)
				{
					echo 2;
				}
				else
				{
					$data=array(
							'weid'=>$weid,
							'mid'=>$id,
							'sendid'=>$mid,
							'type'=>0,
							'yuedu'=>0,
							'createtime'=>TIMESTAMP,
						);
					if($member['sex']==2)
					{
						$wenti=pdo_fetch("SELECT id,name FROM ".tablename('jy_ppp_zhaohu')." WHERE weid=".$weid." AND parentid=0 AND enabled=1 "." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_zhaohu')." ) ORDER BY id LIMIT 1");
						$data['content']=$wenti['name'];
						$data['zhaohuid']=$wenti['id'];
						if(empty($data['content']))
						{
							$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
						}
					}
					else
					{
						$data['content']="你觉得她很赞，和她打了个招呼";
					}
					pdo_insert("jy_ppp_xinxi",$data);
					echo 1;
					exit;
				}
			}
			elseif ($op=='huifu') {
				$id=$_GPC['id'];
				if($member['sex']==2)
				{
					echo 3;
					exit;
				}
				else
				{
					if(empty($member['baoyue']))
					{
						$baoyue=0;
					}
					else
					{
						$baoyue=$member['baoyue']-time();
						if($baoyue<=0)
						{
							$baoyue=0;
						}
						else
						{
							$baoyue=1;
						}
					}
					if(!empty($baoyue))
					{
						echo 3;
						exit;
					}
					else
					{
						$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_chat_doubi')." WHERE weid=".$weid." AND mid=".$mid." AND chatid=".$id);
						if(!empty($temp))
						{
							echo 3;
							exit;
						}
						else
						{
							if(empty($member['credit']))
							{
								echo 1;
								exit;
							}
							else
							{
								echo 2;
								exit;
							}
						}
					}
				}
			}
			else
			{
				$id=$_GPC['id'];
				if(empty($id))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('index')."';
					</script>";
				}
				else
				{
					$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
					$thumbid=$_GPC['thumbid'];
					if(empty($thumbid))
					{
						$thumbid=1;
					}
					$detail=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')."  WHERE weid=".$weid." AND id= ".$id);
					$thumb=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_thumb')." WHERE weid=".$weid." AND mid=".$id." AND ( type=1 OR type=2 )");
					if($member['sex']==1)
					{
						if(empty($member['baoyue']))
						{
							$baoyue=0;
						}
						else
						{
							$baoyue=$member['baoyue']-time();
							if($baoyue<=0)
							{
								$baoyue=0;
							}
							else
							{
								$baoyue=1;
							}
						}
						if(!empty($baoyue))
						{
							$ltlx=2;
						}
						else
						{
							$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_chat_doubi')." WHERE weid=".$weid." AND mid=".$mid." AND chatid=".$id);
							if(empty($temp))
							{
								$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND mid=".$mid." AND sendid=".$id." AND type=2 ");
								if(!empty($temp))
								{
									$ltlx=3;
								}
								else
								{
									$today=strtotime('today');
									$temp=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND mid=".$mid." AND sendid=".$id." AND type=0 AND createtime> ".$today);
									if(empty($temp))
									{
										$ltlx=1;
									}
									else
									{
										if(count($temp)>1)
										{
											$ltlx=3;
										}
										else
										{
											$ltlx=1;
										}
									}
								}
							}
							else
							{
								$ltlx=2;
							}
						}
					}
					else
					{
						$duifang=pdo_fetch("SELECT baoyue FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
						if(empty($duifang['baoyue']))
						{
							$baoyue=0;
						}
						else
						{
							$baoyue=$duifang['baoyue']-time();
							if($baoyue<=0)
							{
								$baoyue=0;
							}
							else
							{
								$baoyue=1;
							}
						}
						if(!empty($baoyue))
						{
							$ltlx=2;
						}
						else
						{
							$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_chat_doubi')." WHERE weid=".$weid." AND mid=".$mid." AND chatid=".$id);
							if(!empty($temp))
							{
								$ltlx=2;
							}
							else
							{
								$ltlx=1;
							}
						}
					}

					//1为打招呼,2为写信,3为回复并索要联系方式

					include $this->template('userthumb');
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