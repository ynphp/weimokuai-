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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'report'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'report'))."';
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
			$op=$_GPC['op'];
			if($op=='add')
			{
				$id=$_GPC['id'];
				$report=$_GPC['report'];
				$temp=pdo_fetch("SELECT id,status FROM ".tablename('jy_ppp_report')." WHERE weid=".$weid." AND mid=".$mid." AND reportid=".$id);
				if(!empty($temp))
				{
					if(!empty($temp['status']))
					{
						$data=array(
							'weid'=>$weid,
							'mid'=>$mid,
							'reportid'=>$id,
							'report'=>$report,
							'createtime'=>TIMESTAMP,
						);
						pdo_insert("jy_ppp_report",$data);
					}
					else
					{
						pdo_update("jy_ppp_report",array('report'=>$report),array('id'=>$temp['id']));
					}
				}
				else
				{
					$data=array(
						'weid'=>$weid,
						'mid'=>$mid,
						'reportid'=>$id,
						'report'=>$report,
						'createtime'=>TIMESTAMP,
					);
					pdo_insert("jy_ppp_report",$data);
				}
				echo 1;
				exit;
			}
			else
			{
				$id=$_GPC['id'];
				$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
				include $this->template('report');
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