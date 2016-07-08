<?php
global $_W,$_GPC;
	$xuniid=intval($_GPC['xuniid']);
	if(empty($xuniid))
	{
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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'account'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'account'))."';
					</script>";
				}
				else
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
					$mid=$member['id'];
				}
			}
		}
	}
	else
	{
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

			$dyid=$_SESSION['dyid'];
			if(!empty($dyid))
			{
				$dianyuan=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND id=".$dyid);
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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'account'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'account'))."';
					</script>";
				}
				else
				{
					$dianyuan=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
					$dyid=$dianyuan['id'];
				}
			}
		}
		if(empty($dianyuan))
		{
			echo "<script>
				window.location.href = '".$this->createMobileUrl('dy_login')."';
			</script>";
		}
		else
		{
			$xuni_qx=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_xuni_member')." WHERE weid=".$weid." AND mid=".$xuniid." AND dyid=".$dyid);
			if(empty($xuni_qx))
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('dy_user')."';
				</script>";
			}
			else
			{
				$mid=$xuniid;

				$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);
			}
		}
	}

		if(!empty($member))
		{
			$op=$_GPC['op'];
			if($op=='change')
			{
				$old=$_GPC['pwd1'];
				$pwd=$_GPC['pwd2'];
				if($old!=$member['pwd'])
				{
					echo 2;
					exit;
				}
				else
				{
					if($old==$pwd)
					{
						echo 3;
						exit;
					}
					else
					{
						pdo_update("jy_ppp_member",array('pwd'=>$pwd),array('id'=>$mid));
						echo 1;
						exit;
					}
				}
			}
			elseif ($op=='bind') {
				$mobile=$_GPC['mobile'];
				$password=$_GPC['password'];
				$member2=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND mobile='".$mobile."'");
				if(empty($member2))
				{
					if($weixin==1 && empty($member['mobile']) && !empty($mid))
					{
						pdo_update("jy_ppp_member",array('mobile'=>$mobile,'pwd'=>$password),array('id'=>$mid));
						echo 1;
						exit;
					}
					else
					{
						echo 3;
						exit;
					}
				}
				else
				{
					echo 2;
					exit;
				}
			}
			else
			{
				$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
				include $this->template('account');
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