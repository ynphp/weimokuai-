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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'basic'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'basic'))."';
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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'basic'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'basic'))."';
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
			$basic=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_basic')." WHERE weid=".$weid." AND mid=".$mid);

			$op=$_GPC['op'];
			if($op=='add')
			{
				$nicheng=$_GPC['nicheng'];
				$year=$_GPC['year'];
				$month=$_GPC['month'];
				$day=$_GPC['day'];
				$brith=strtotime($year.'-'.$month."-".$day);
				$province=$_GPC['province'];
				$city=$_GPC['city'];
				if(!empty($nicheng))
				{
					$data2['nicheng']=$nicheng;
				}
				if(!empty($province) && !empty($city))
				{
					$data2['province']=$province;
					$data2['city']=$city;
				}
				$data2['brith']=$brith;
				pdo_update("jy_ppp_member",$data2,array('id'=>$mid));

				$data=array();
				$data['weid']=$weid;
				$data['mid']=$mid;
				$data['blank']=0;

			  	$data['height']=$_GPC['height'];
			  	if(empty($data['height']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['weight']=$_GPC['weight'];
			  	if(empty($data['weight']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['blood']=$_GPC['blood'];
			  	if(empty($data['blood']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['education']=$_GPC['education'];
			  	if(empty($data['education']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['job']=$_GPC['job'];
			  	if(empty($data['job']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['income']=$_GPC['income'];
			  	if(empty($data['income']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['marriage']=$_GPC['marriage'];
			  	if(empty($data['marriage']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['house']=$_GPC['house'];
			  	if(empty($data['house']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['createtime']=TIMESTAMP;

				if(empty($basic))
				{
					pdo_insert('jy_ppp_basic',$data);
				}
				else
				{
					pdo_update("jy_ppp_basic",$data,array('id'=>$basic['id']));
				}
				echo 1;
				exit;
			}
			else
			{
				$year=date('Y',$member['brith']);
				$month=date('m',$member['brith']);
				$day=date('d',$member['brith']);

				include $this->template('basic');
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