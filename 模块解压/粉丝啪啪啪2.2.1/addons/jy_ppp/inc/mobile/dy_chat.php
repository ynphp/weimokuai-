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
				window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'dy_chat','id'=>$_GPC['id']))."';
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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'dy_chat','id'=>$_GPC['id']))."';
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
		$id=$_GPC['id'];
		$xinxi=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND id=".$id);
		if(empty($xinxi))
		{
			message("该消息已经不存在!");
		}
		$op=$_GPC['op'];
		if($op=='ans2')
		{
			$id=$xinxi['sendid'];
			$str=$_GPC['str'];
			if(empty($id))
			{
				echo json_encode(array('status'=>2));
				exit;
			}
			else
			{
				$data=array(
						'weid'=>$weid,
						'mid'=>$id,
						'sendid'=>$xinxi['mid'],
						'content'=>$str,
						'type'=>2,
						'yuedu'=>0,
						'createtime'=>TIMESTAMP,
					);
				pdo_insert("jy_ppp_xinxi",$data);
				echo json_encode(array('status'=>1,'time'=>date('Y-m-d G:i')));
				$mid=$xinxi['mid'];
				$kefu_member=pdo_fetch("SELECT status,type,from_user,nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
				$send_member=pdo_fetch("SELECT nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);
				if($kefu_member['type']!=3 && !empty($kefu_member['from_user']) && !empty($kefu_member['status']))
				{
					$send_kefu_tf=false;
					$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
					if($sitem['jiange']>0)
					{
						$temp_kefu=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." ORDER BY createtime DESC LIMIT 1 ");
						$temp_jg_time=time()-$temp_kefu['createtime'];
						if($temp_jg_time>$sitem['jiange']*60)
						{
							$today=strtotime('today');
							$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
							if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
							{
								$send_kefu_tf=true;
							}
						}
					}
					else
					{
						$today=strtotime('today');
						$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
						if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
						{
							$send_kefu_tf=true;
						}
					}

					if($send_kefu_tf==true)
					{
						$text2="附近的'".$send_member['nicheng']."'给你发了一封表白信";
						$text=urlencode($text2);
						$desc2="点击下方【信箱】按钮查看所有来信";
						$desc=urlencode($desc2);
						$pic2=$_W['siteroot']."addons/jy_ppp/images/notice.jpg";
						$pic=urlencode($pic2);
						$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('mail'), 2);
						$url=urlencode($url2);
						$access_token = WeAccount::token();
						$data = array(
						  "touser"=>$kefu_member['from_user'],
						  "msgtype"=>"news",
						  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$desc,"url"=>$url,'picurl'=>$pic))))
						);
						$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
						load()->func('communication');
						$response = ihttp_request($url, urldecode(json_encode($data)));
						$errcode=json_decode($response['content'],true);
						$data3=array(
								'weid'=>$weid,
								'mid'=>$id,
								'type'=>'news',
								'content'=>$text2,
								'desc'=>$desc2,
								'url'=>$url2,
								'picurl'=>$pic2,
								'status'=>$errcode['errcode'],
								'createtime'=>TIMESTAMP,
							);
						pdo_insert("jy_ppp_kefu",$data3);
					}
				}
				exit;
			}
		}
		elseif ($op=='more') {
				$id=$xinxi['sendid'];
				$mid=$xinxi['mid'];
				$lastid=$_GPC['lastid'];
				if(empty($lastid))
				{
					echo json_encode(array('status'=>3));
					exit;
				}
				$temp=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND id=".$lastid);
				if(empty($temp['createtime']))
				{
					echo json_encode(array('status'=>4));
					exit;
				}
				if(!empty($temp['sendid']))
				{
					$send=pdo_fetch("SELECT sex,avatar FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
					if(!empty($send['avatar']))
					{
						$avatar2=$_W['attachurl'].$send['avatar'];
					}
					else
					{
						if($send['sex']==1)
						{
							$avatar2="../addons/jy_ppp/images/boy.png";
						}
						else
						{
							$avatar2="../addons/jy_ppp/images/girl.png";
						}
					}
				}
				$list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND (( mid=".$mid." AND sendid=".$id." ) OR ( mid=".$id." AND sendid=".$mid." ) ) AND createtime<".$temp['createtime']." ORDER BY createtime DESC LIMIT 3");
				sort($list);
				$html='';
				if(!empty($member['avatar']))
				{
					$avatar=$_W['attachurl'].$member['avatar'];
				}
				else
				{
					if($member['sex']==1)
					{
						$avatar="../addons/jy_ppp/images/boy.png";
					}
					else
					{
						$avatar="../addons/jy_ppp/images/girl.png";
					}
				}
				if(!empty($list))
				{
					foreach ($list as $key => $l) {
						$html.='<li class="time" data-id="'.$l['id'].'">'.date('Y-m-d G:i',$l['createtime']).'</li>';
						if($l['sendid']==$mid)
						{
							$html.='<li class="me disbox-hor dis_block"><div class="f_right" style="position: relative;"><img src="'.$avatar.'"></div><div class="disbox-flex f_right no_padding"><p class="talk">'.$l['content'].'</p></div><div class="f_right"></div></li>';
						}
						else
						{
							$html.='<li class="you disbox-hor dis_block "><div class="f_left" style="position: relative;"><img src="'.$avatar2.'"></div><div class="disbox-flex f_left no_padding "><p class="talk">'.$l['content'].'</p></div><div class="f_left"></div></li>';
						}
					}
				}

				if(count($list)<3)
				{
					echo json_encode(array('status'=>2,'log'=>$html));
					exit;
				}
				else
				{
					echo json_encode(array('status'=>1,'log'=>$html));
					exit;
				}
			}
		else
		{
			$detail=pdo_fetch("SELECT a.*,b.height,b.income,b.marriage FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_basic')." as b on a.id=b.mid  WHERE a.weid=".$weid." AND a.id= ".$xinxi['sendid']);
			if(!empty($member['avatar']))
			{
				$avatar=$_W['attachurl'].$member['avatar'];
			}
			else
			{
				if($member['sex']==1)
				{
					$avatar="../addons/jy_ppp/images/boy.png";
				}
				else
				{
					$avatar="../addons/jy_ppp/images/girl.png";
				}
			}

			//1为打招呼,2为写信,3为回复并索要联系方式
			$list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND ( ( mid=".$xinxi['sendid']." AND sendid=".$xinxi['mid']." ) OR ( mid=".$xinxi['mid']." AND sendid=".$xinxi['sendid']." ) ) ORDER BY createtime DESC LIMIT 3");
			if($member['sex']==1 && empty($list[0]['type']) && !empty($list[0]['zhaohuid']) && empty($list[0]['huifuid']))
			{
				$zhaohuid=$list[0]['zhaohuid'];
				$wt=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_zhaohu')." WHERE weid=".$weid." AND parentid=".$list[0]['zhaohuid']." AND enabled=1");
			}

			sort($list);
			pdo_update("jy_ppp_xinxi",array('yuedu'=>1,'yuedutime'=>TIMESTAMP),array('weid'=>$weid,'mid'=>$xinxi['mid'],'sendid'=>$xinxi['sendid']));

			$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
			$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');

			include $this->template('dy_chat');
		}
	}
	else
	{

		echo "<script>
				window.location.href = '".$this->createMobileUrl('dy_login')."';
			</script>";
	}