<?php
global $_W,$_GPC;

		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

			$dyid=$_SESSION['dyid'];
			if(!empty($dyid))
			{
				$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND id=".$dyid);
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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'dy_msg'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'dy_msg'))."';
					</script>";
				}
				else
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
					$dyid=$member['id'];
				}
			}
		}

		if(!empty($member))
		{

				$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
				$xuni=pdo_fetchall("SELECT b.* FROM ".tablename('jy_ppp_xuni_member')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.dyid=".$dyid);
				$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');
				$userid=$_GPC['userid'];
				$condition='';
				$msg=$_GPC['msg'];
				if($msg=='done')
				{
					$condition.=" AND yuedu=1 ";
				}
				else
				{
					$condition.=" AND yuedu=0 ";
				}
				if(!empty($userid))
				{
					$condition.=" AND a.mid=".$userid;
					$user_member=pdo_fetch("SELECT nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$userid);
				}
				else
				{
					$condition.=" AND a.mid IN ( ";
					foreach ($xuni as $key => $value) {
						$condition.=$value['id'].",";
					}
					$condition=substr($condition, 0,-1);
					$condition.=") ";
				}
				$xinxi=pdo_fetchall("SELECT a.id,a.sendid as mid,a.type,a.createtime,a.yuedu,b.avatar,b.nicheng,b.province,b.brith,b.sex,c.height FROM ".tablename('jy_ppp_xinxi')." as a left join ".tablename('jy_ppp_member')." as b on a.sendid=b.id left join ".tablename('jy_ppp_basic')." as c on a.sendid=c.mid WHERE a.weid=".$weid.$condition." GROUP BY a.sendid ORDER BY a.createtime DESC");
				include $this->template('dy_msg');

		}
		else
		{

			echo "<script>
					window.location.href = '".$this->createMobileUrl('dy_login')."';
				</script>";
		}