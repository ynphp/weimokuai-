<?php
global $_W,$_GPC;

		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'login','id'=>$_GPC['id']))."';
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
					$_W['member']['uid']=$uid;
					unset($member_temp);
				}

				if(empty($uid))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'login','id'=>$_GPC['id']))."';
					</script>";
				}
			}
		}

		$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
		$op=$_GPC['op'];
		if($op=='add')
		{
			$mobile=intval($_GPC['mobile']);
			if(empty($mobile))
			{
				echo 4;
				exit;
			}
			$member=pdo_fetch("SELECT id,password,from_user FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND mobile='".$mobile."'");
			if(!empty($member))
			{
				$password=$_GPC['password'];
				if($member['password']==$password)
				{
					if(!empty($weixin))
					{
						pdo_update('jy_ppp_dianyuan',array('from_user'=>$from_user,'uid'=>$uid),array('id'=>$member['id']));
						pdo_update("jy_ppp_dianyuan",array('status'=>0),array('from_user'=>$from_user));
						pdo_update("jy_ppp_dianyuan",array('status'=>1),array('id'=>$member['id']));
					}
					else
					{
						$_SESSION['dyid']=$member['id'];
					}
					echo 1;
					exit;
				}
				else
				{
					echo 2;
					exit;
				}
			}
			else
			{
				echo 3;
				exit;
			}
		}
		include $this->template('dy_login');