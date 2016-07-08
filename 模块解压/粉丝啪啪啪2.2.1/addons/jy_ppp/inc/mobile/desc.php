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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'desc'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'desc'))."';
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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'desc'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'desc'))."';
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
			$desc=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_desc')." WHERE weid=".$weid." AND mid=".$mid);

			$op=$_GPC['op'];
			if($op=='add')
			{

				$data=array();
				$data['weid']=$weid;
				$data['mid']=$mid;
				$data['blank']=0;

			  	$data['child']=$_GPC['child'];
			  	if(empty($data['child']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['yidi']=$_GPC['yidi'];
			  	if(empty($data['yidi']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['leixin']=$_GPC['leixin'];
			  	if(empty($data['leixin']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['sex']=$_GPC['sex'];
			  	if(empty($data['sex']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['fumu']=$_GPC['fumu'];
			  	if(empty($data['fumu']))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['meili']=$_GPC['meili'];
			  	if(empty($data['meili']))
			  	{
			  		$data['blank']++;
			  	}
			  	$aihao=$_GPC['aihao'];
			  	if(empty($aihao))
			  	{
			  		$data['blank']++;
			  	}
			  	$tezheng=$_GPC['tezheng'];
			  	if(empty($tezheng))
			  	{
			  		$data['blank']++;
			  	}
			  	$data['createtime']=TIMESTAMP;

				if(empty($desc))
				{
					pdo_insert('jy_ppp_desc',$data);
				}
				else
				{
					pdo_update("jy_ppp_desc",$data,array('id'=>$desc['id']));
				}

				pdo_delete("jy_ppp_aihao",array('weid'=>$weid,'mid'=>$mid));
				pdo_delete("jy_ppp_tezheng",array('weid'=>$weid,'mid'=>$mid));
				if(!empty($aihao))
				{
					$aihao=explode(',', $aihao);
					foreach ($aihao as $key => $value) {
						$temp=array(
								'weid'=>$weid,
								'mid'=>$mid,
								'aihao'=>$value,
								'createtime'=>TIMESTAMP,
							);
						pdo_insert('jy_ppp_aihao',$temp);
					}
				}
				if(!empty($tezheng))
				{
					$tezheng=explode(',', $tezheng);
					foreach ($tezheng as $key => $value) {
						$temp=array(
								'weid'=>$weid,
								'mid'=>$mid,
								'tezheng'=>$value,
								'createtime'=>TIMESTAMP,
							);
						pdo_insert('jy_ppp_tezheng',$temp);
					}
				}
				echo 1;
				exit;
			}
			else
			{
				$aihao=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_aihao')." WHERE weid=".$weid." AND mid=".$mid);
				$tezheng=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_tezheng')." WHERE weid=".$weid." AND mid=".$mid);
				if(!empty($aihao))
				{
					$a_arr=array();
					foreach ($aihao as $key => $value) {
						array_push($a_arr, $value['aihao']);
					}
				}
				if(!empty($tezheng))
				{
					$t_arr=array();
					foreach ($tezheng as $key => $value) {
						array_push($t_arr, $value['tezheng']);
					}
				}

				include $this->template('desc');
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