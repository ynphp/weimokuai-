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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mythumb'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mythumb'))."';
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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mythumb'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mythumb'))."';
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
			$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
			$id=$_GPC['id'];

			$op=$_GPC['op'];
			if($op=='set')
			{
				$touxiang=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_thumb')." WHERE weid=".$weid." AND id=".$id." AND type=1");
				if(empty($touxiang))
				{
					echo 2;
					exit;
				}
				else
				{
					pdo_update("jy_ppp_member",array('avatar'=>$touxiang['thumb']),array('id'=>$mid));
					echo 1;
					exit;
				}
			}
			elseif ($op=='del') {
				pdo_update("jy_ppp_thumb",array('type'=>4),array('id'=>$id));
				echo 1;
				exit;
			}
			else
			{
				$thumb=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_thumb')." WHERE weid=".$weid." AND mid=".$mid." AND type!=4 ORDER BY id DESC ");
				$i=0;
				foreach ($thumb as $key => $value) {
					if($value['id']==$id)
					{
						$i=$key;
					}
				}
				include $this->template('mythumb');
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