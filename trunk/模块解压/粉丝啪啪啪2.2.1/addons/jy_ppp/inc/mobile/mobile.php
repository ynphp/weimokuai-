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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mobile'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mobile'))."';
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
			$op=$_GPC['op'];
			if($op=='add')
			{
				$phone=$_GPC['mobile'];
				$code=$_GPC['code'];
				$user=pdo_fetch('SELECT * FROM '.tablename('jy_ppp_mobile')." WHERE weid=".$weid." AND mobile='$phone'");
				if(!empty($user))
				{
					if($user['mid']!=$mid)
					{
						echo 5;
						exit;
					}
					else
					{
						echo 4;
						exit;
					}
				}else
				{
					$code=$_GPC['code'];
					$code_log=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_code')." WHERE weid=".$weid." AND mobile='$phone'");
					if($code!=$code_log['code'])
					{
						echo 2;
						exit;
					}
					else
					{
						$t_user=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_mobile')." WHERE weid=".$weid." AND mid=".$mid." ORDER BY createtime DESC ");
						if(!empty($t_user) && (time()-$t_user['createtime'])<86399)
						{
							echo 3;
							exit;
						}
						else
						{
							if(empty($t_user))
							{
								pdo_insert("jy_ppp_mobile",array('weid'=>$weid,'mobile'=>$phone,'mid'=>$mid,'createtime'=>TIMESTAMP));
							}
							else
							{
								pdo_update("jy_ppp_mobile",array('mobile'=>$phone,'createtime'=>TIMESTAMP),array('id'=>$t_user['id']));
							}
							pdo_update("jy_ppp_member",array('mobile_auth'=>1),array('id'=>$mid));

							$xinxi="恭喜你,您已进行实名认证-手机认证。祝您尽快找到您的有缘人!";
							$data4=array(
								'weid'=>$weid,
								'mid'=>$mid,
								'sendid'=>0,
								'content'=>$xinxi,
								'type'=>3,
								'yuedu'=>0,
								'createtime'=>TIMESTAMP,
							);
							pdo_insert("jy_ppp_xinxi",$data4);
							echo 1;
							exit;
						}
					}

				}
			}
			if($op=='sms')
			{
				$phone=$_GPC['mobile'];
				$user=pdo_fetch('SELECT * FROM '.tablename('jy_ppp_mobile')." WHERE weid=".$weid." AND mobile='$phone'");
				if(!empty($user))
				{
					if($user['mid']!=$mid)
					{
						echo 2;
						exit;
					}
					else
					{
						echo 4;
						exit;
					}
				}
				else
				{
					$now_day=strtotime(date('Y-m-d', time()));
					$mt_day=$now_day + 86399;
					$code_log=pdo_fetchall("SELECT id FROM ".tablename('jy_ppp_code')." WHERE weid=".$weid." AND mid=".$mid." AND createtime<".$mt_day." AND createtime>".$now_day);
					if(count($code_log)==4)
					{
						echo 3;
						exit;
					}
					else
					{
						$code=rand(1000,9999);

						if(empty($sitem['sms_type']))
						{
							$product=$sitem['sms_product'];
							$account=$sitem['sms_username'];
							$pswd=$sitem['sms_pwd'];

							$content=rawurlencode("【".$sitem['sms_sign']."】,您的验证码是：".$code."。请不要把验证码泄露给其他人。");


							$target ='http://120.24.55.238/msg/HttpSendSM?account='.$account.'&pswd='.$pswd.'&mobile='.$phone.'&msg='.$content.'&needstatus=true&product='.$product.'';
							$return_str = file_get_contents($target);

							$i=substr($return_str, 15,1);

							if($i==0)
							{
								pdo_insert("jy_ppp_code",array('weid'=>$weid,'mobile'=>$phone,'code'=>$code,'mid'=>$mid,'createtime'=>TIMESTAMP));
								echo 1;
							}
							else
							{
								echo 5;
							}
							exit;
						}
						elseif ($sitem['sms_type']==1)
						{
							$target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";

							$username=$sitem['sms_username'];
							$pass=$sitem['sms_pwd'];
							$post_data = "account=".$username."&password=".$pass."&mobile=".$phone."&content=".rawurlencode("您的验证码是：".$code."。请不要把验证码泄露给其他人。");
							$temp = $this->Post($post_data, $target);
							$gets = $this->xml_to_array($temp);
							if($gets['SubmitResult']['code']==2){
								pdo_insert("jy_ppp_code",array('weid'=>$weid,'mobile'=>$phone,'code'=>$code,'mid'=>$mid,'createtime'=>TIMESTAMP));
								echo 1;
								exit;
							}
							else
							{
								echo 5;
								exit;
							}
						}
					}
				}
			}
			include $this->template('mobile');
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