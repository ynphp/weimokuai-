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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'logout'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'logout'))."';
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
			if($weixin==1)
			{
				if(!empty($member['mobile']))
				{
					if($member['type']==1)
					{
						pdo_update("jy_ppp_member",array('uid'=>0,'status'=>0),array('id'=>$mid));
					}
					else
					{
						pdo_update("jy_ppp_member",array('uid'=>0,'from_user'=>''),array('id'=>$mid));
					}
					
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
				$_SESSION['mid']=0;
				echo 1;
				exit;
			}
			
		}
		else
		{
			echo 2;
			exit;
		}