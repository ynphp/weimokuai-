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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mianrao'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mianrao'))."';
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
			$mianrao=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_mianrao')." WHERE weid=".$weid." AND mid=".$mid);

			$op=$_GPC['op'];
			if($op=='add')
			{
				$nub=$_GPC['nub'];
				if($nub==2 && empty($member['avatar']))
				{
					echo 2;
					exit;
				}
				if($nub==5 && empty($member['mobile_auth']))
				{
					echo 3;
					exit;
				}
				$data=array();
				$data['weid']=$weid;
				$data['mid']=$mid;
				if($nub==1)
				{
					$data['zhaohu']=1;
				}
			  	elseif ($nub==2) {
			  		$data['thumb']=1;
			  	}elseif ($nub==3) {
			  		$data['province']=1;
			  	}elseif ($nub==4) {
			  		$data['age']=1;
			  	}elseif ($nub==5) {
			  		$data['mobile']=1;
			  	}

			  	$data['createtime']=TIMESTAMP;

				if(empty($mianrao))
				{
					pdo_insert('jy_ppp_mianrao',$data);
				}
				else
				{
					pdo_update("jy_ppp_mianrao",$data,array('id'=>$mianrao['id']));
				}

				echo 1;
				exit;
			}
			elseif ($op=='cancel') {
				$nub=$_GPC['nub'];
				if($nub==1 && empty($member['card_auth']))
				{
					echo 2;
					exit;
				}

				$data=array();
				$data['weid']=$weid;
				$data['mid']=$mid;
				if($nub==1)
				{
					$data['zhaohu']=0;
				}
			  	elseif ($nub==2) {
			  		$data['thumb']=0;
			  	}elseif ($nub==3) {
			  		$data['province']=0;
			  	}elseif ($nub==4) {
			  		$data['age']=0;
			  	}elseif ($nub==5) {
			  		$data['mobile']=0;
			  	}

			  	$data['createtime']=TIMESTAMP;

				if(empty($mianrao))
				{
					pdo_insert('jy_ppp_mianrao',$data);
				}
				else
				{
					pdo_update("jy_ppp_mianrao",$data,array('id'=>$mianrao['id']));
				}

				echo 1;
				exit;
			}
			else
			{
				include $this->template('mianrao');
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