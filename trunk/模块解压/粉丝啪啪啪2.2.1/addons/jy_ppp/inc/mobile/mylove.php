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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mylove'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mylove'))."';
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
			$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');
			$op=$_GPC['op'];
			if($op=='more')
			{
				$lastid=$_GPC['lastid'];
				if(empty($lastid))
				{
					echo json_encode(array('status'=>3));
					exit;
				}
				$last=pdo_fetch("SELECT mid,createtime FROM ".tablename('jy_ppp_attent')." WHERE weid=".$weid." AND id=".$lastid);
				if(empty($last['createtime']))
				{
					echo json_encode(array('status'=>4));
					exit;
				}
				$today=strtotime("today");
				$love=pdo_fetchall("SELECT a.id,a.attentid,a.mid as mid,b.avatar,b.nicheng,b.province,b.brith,b.sex,c.height FROM ".tablename('jy_ppp_attent')." as a left join ".tablename('jy_ppp_member')." as b on a.attentid=b.id left join ".tablename('jy_ppp_basic')." as c on a.attentid=c.mid WHERE a.weid=".$weid." AND a.mid=".$mid." AND a.createtime<".$last['createtime']." GROUP BY a.mid ORDER BY a.createtime DESC LIMIT 10 ");
				$html='';
				if(!empty($love))
				{
					foreach ($love as $key => $l) {
						if(!empty($l['avatar']))
						{
							$avatar=$_W['attachurl'].$l['avatar'];
						}
						else
						{
							if($l['sex']==1)
							{
								$avatar="../addons/jy_ppp/images/boy.png";
							}
							else
							{
								$avatar="../addons/jy_ppp/images/girl.png";
							}
						}
						$ziliao='';
						if(!empty($l['brith']))
						{
							$birthday=$l['brith'];
							$now=time();
						    $month=0;
						    if(date('m', $now)>date('m', $birthday))
						    $month=1;
						    if(date('m', $now)==date('m', $birthday))
						    if(date('d', $now)>=date('d', $birthday))
						    $month=1;
						    $nianlin=date('Y', $now)-date('Y', $birthday)+$month;
							$ziliao.=$nianlin."岁";
						}
						if(!empty($l['province']))
						{
							$ziliao.="·".$province[$l['province']];
						}
						if(!empty($l['height']))
						{
							$ziliao.="·".$l['height']."cm";
						}
						$temp=pdo_fetch("SELECT createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND mid=".$l['mid']." AND type=0 ORDER BY createtime DESC LIMIT 1 ");
						if($temp['createtime']>$today)
						{
							$a_zh='<span class="hello_out" >已打招呼</span>';
						}
						else
						{
							$a_zh='<span class="hello" onclick="sayPeople2(this,'.$l['mid'].')" >打招呼</span>';
						}
						$html.='<ul class="disbox-hor user-list" data-id="'.$l['id'].'"><li class="foot-icon disbox-center"><a href="'.$this->createMobileUrl('detail',array('id'=>$l['mid'])).'"><img src="'.$avatar.'"</a></li><li class="disbox-flex user_mession"><a href="'.$this->createMobileUrl('detail',array('id'=>$l['mid'])).'"><b class="tit">'.$l['nicheng'].'</b><p class="bot">'.$ziliao.'</p></a></li><div class="right">'.$a_zh.'</div></ul>';
					}
				}
				if(count($love)<10)
				{
					//echo 2;
					echo json_encode(array('status'=>2,'log'=>$html));
					exit;
				}
				else
				{
					//echo 1;
					echo json_encode(array('status'=>1,'log'=>$html));
					exit;
				}
			}
			elseif ($op=='zhaohu') {
				$str=$_GPC['str'];
				$zh=explode(",", $str);
				if($member['sex']==1)
				{
					$basic=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_basic')." WHERE weid=".$weid." AND mid=".$mid);
					$desc=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_desc')." WHERE weid=".$weid." AND mid=".$mid);
					$aihao=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_aihao')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");
					$tezheng=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_tezheng')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");
					$temp_wt=array();
					if(!empty($member['brith']))
					{
						array_push($temp_wt, '1','2','3');
						if(!empty($basic['height']))
						{
							array_push($temp_wt, '4');
						}
					}
					if(!empty($member['province']))
					{
						array_push($temp_wt, '7');
						if(!empty($aihao))
						{
							array_push($temp_wt, '6');
						}
						if(!empty($tezheng))
						{
							array_push($temp_wt, '5');
						}
					}
					if(!empty($basic['blood']))
					{
						array_push($temp_wt, '8');
					}
					if(!empty($desc['leixin']))
					{
						array_push($temp_wt, '9');
					}
					if(!empty($aihao))
					{
						array_push($temp_wt, '10');
					}
					if(!empty($tezheng))
					{
						array_push($temp_wt, '11');
					}
					array_push($temp_wt, '12','13','14','15','16','17','18');
					$temp_num=count($temp_wt)-1;
				}

				foreach ($zh as $key => $value) {
					$temp=pdo_fetch("SELECT createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND mid=".$value." AND type=0 ORDER BY createtime DESC LIMIT 1 ");
					if(!empty($temp) && $temp['createtime']>$today)
					{

					}
					else
					{
						$data=array(
							'weid'=>$weid,
							'mid'=>$value,
							'sendid'=>$mid,
							'type'=>0,
							'yuedu'=>0,
							'createtime'=>TIMESTAMP,
						);
						if($member['sex']==2)
						{
							$wenti=pdo_fetch("SELECT id,name FROM ".tablename('jy_ppp_zhaohu')." WHERE weid=".$weid." AND parentid=0 AND enabled=1 "." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_zhaohu')." ) ORDER BY id LIMIT 1");
							$data['content']=$wenti['name'];
							$data['zhaohuid']=$wenti['id'];
							if(empty($data['content']))
							{
								$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
							}
						}
						else
						{
							$xiabiao=mt_rand(0,$temp_num);
							$rand_wt=$temp_wt[$xiabiao];
							switch ($rand_wt) {
								case 1:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,有时间可以聊会儿天么？";
									break;
								case 2:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,希望能够了解你，可以交个朋友吗？";
									break;
								case 3:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,我觉得我们挺合适的。";
									break;
								case 4:
									$data['content']="可以认识一下么？我生于".date('Y',$member['brith'])."年,身高".$basic['height']."cm,我觉得我们挺合适的。";
									break;
								case 5:
									$data['content']="哈喽！我个人很".$tezheng['tezheng'].",现居住在".$province[$member['province']]."，希望能够了解你，你怎么看？";
									break;
								case 6:
									$data['content']="嗨！我居住在".$province[$member['province']]."，平时喜欢".$aihao['aihao']."，希望你能和你交朋友哈！";
									break;
								case 7:
									$data['content']="嗨！我是来自".$province[$member['province']]."的年轻人，希望你会记得我哈~";
									break;
								case 8:
									$data['content']="嗨！我是".$basic['blood']."血型的,不知道你是不是也是这样的呢？";
									break;
								case 9:
									$data['content']="哈喽！我喜欢".$basic['leixin']."的女生,不知道你是不是也是这样的呢？";
									break;
								case 10:
									$data['content']="哈喽！我平时很喜欢".$aihao['aihao'].",不知道你是不是也是这样的呢？";
									break;
								case 11:
									$data['content']="哈喽！我个人很".$tezheng['tezheng'].",希望能够了解你，可以交个朋友吗？";
									break;
								case 12:
									$data['content']="哈喽！美女！我对你很感兴趣，不知道我们有缘分吗？";
									break;
								case 13:
									$data['content']="Hi,我觉得你很不错，有时间可以聊会儿天么？";
									break;
								case 14:
									$data['content']="hello,你很漂亮，我看上你了，我真心想找个合适的女朋友，也许我们能交个朋友哈~";
									break;
								case 15:
									$data['content']="hey，我觉得你很有气质，可以认识你吗？";
									break;
								case 16:
									$data['content']="我们好像在那里见过，可以认识你吗？";
									break;
								case 17:
									$data['content']="hey，我觉得你的打扮很多看，可以认识你吗？";
									break;
								default:
									$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
									break;
							}
						}
						pdo_insert("jy_ppp_xinxi",$data);
					}
				}
				if($member['sex']==1)
				{
					$condition.=" AND sex=2 ";
				}
				else
				{
					$condition.=" AND sex=1 ";
				}
				$match=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_match')." WHERE weid=".$weid." AND mid=".$mid);
				if(!empty($match))
				{
					if($match['province']>0)
					{
						$temp_province=$match['province'];
					}
					else
					{
						if(!empty($member['province']))
						{
							$temp_province=$member['province'];
						}
						else
						{
							$temp_province=11;
						}
					}
					$condition.=" AND province= ".$temp_province;
				}
				$tuijian=pdo_fetchall("SELECT id,avatar,sex,nicheng,province,brith FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid.$condition." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_member')." ) ORDER BY id LIMIT 4");
				if(!empty($tuijian))
				{
					$html.='<h2 class="title">为你推荐</h2><ol class="pto">';
					foreach ($tuijian as $key => $value) {
						$zhaohu.=$value['id'].",";
						if(!empty($value['avatar']))
						{
							$avatar=$_W['attachurl'].$value['avatar'];
						}
						else
						{
							if($value['sex']==1)
							{
								$avatar="../addons/jy_ppp/images/boy.png";
							}
							else
							{
								$avatar="../addons/jy_ppp/images/gril.png";
							}
						}
						$ziliao='';
						if(!empty($value['province']))
						{
							$ziliao.=$province[$value['province']]." ";
						}
						if(!empty($value['brith']))
						{
							$birthday=$value['brith'];
							$now=time();
						    $month=0;
						    if(date('m', $now)>date('m', $birthday))
						    $month=1;
						    if(date('m', $now)==date('m', $birthday))
						    if(date('d', $now)>=date('d', $birthday))
						    $month=1;
						    $nianlin=date('Y', $now)-date('Y', $birthday)+$month;
							$ziliao.=$nianlin."岁";
						}
						$html.='<a href="'.$this->createMobileUrl('detail',array('id'=>$t['id'])).'"><li style="margin-right:7px"><img src="'.$avatar.'" /><p>'.$ziliao.'</p></li></a>';
					}

					$zhaohu=substr($zhaohu,0,-1 );
					$html.='</ol><div class="btn_box"><a class="btn" onclick="sayPeople(this)" data-id="'.$zhaohu.'">群打招呼</a></div>';
				}
				echo json_encode(array('status'=>1,'log'=>$html));
				exit;
			}elseif ($op=='zhaohu2') {
				$id=$_GPC['id'];
				$today=strtotime("today");
				$temp=pdo_fetch("SELECT createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND mid=".$id." AND type=0 ORDER BY createtime DESC LIMIT 1 ");
				if(!empty($temp) && $temp['createtime']>$today)
				{
					echo 2;
				}
				else
				{
					$data=array(
							'weid'=>$weid,
							'mid'=>$id,
							'sendid'=>$mid,
							'type'=>0,
							'yuedu'=>0,
							'createtime'=>TIMESTAMP,
						);
					if($member['sex']==2)
					{
						$wenti=pdo_fetch("SELECT id,name FROM ".tablename('jy_ppp_zhaohu')." WHERE weid=".$weid." AND parentid=0 AND enabled=1 "." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_zhaohu')." ) ORDER BY id LIMIT 1");
						$data['content']=$wenti['name'];
						$data['zhaohuid']=$wenti['id'];
						if(empty($data['content']))
						{
							$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
						}
					}
					else
					{
						$basic=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_basic')." WHERE weid=".$weid." AND mid=".$mid);
						$desc=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_desc')." WHERE weid=".$weid." AND mid=".$mid);
						$aihao=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_aihao')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");
						$tezheng=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_tezheng')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");

						$temp_wt=array();
						if(!empty($member['brith']))
						{
							array_push($temp_wt, '1','2','3');
							if(!empty($basic['height']))
							{
								array_push($temp_wt, '4');
							}
						}
						if(!empty($member['province']))
						{
							array_push($temp_wt, '7');
							if(!empty($aihao))
							{
								array_push($temp_wt, '6');
							}
							if(!empty($tezheng))
							{
								array_push($temp_wt, '5');
							}
						}
						if(!empty($basic['blood']))
						{
							array_push($temp_wt, '8');
						}
						if(!empty($desc['leixin']))
						{
							array_push($temp_wt, '9');
						}
						if(!empty($aihao))
						{
							array_push($temp_wt, '10');
						}
						if(!empty($tezheng))
						{
							array_push($temp_wt, '11');
						}
						array_push($temp_wt, '12','13','14','15','16','17','18');
						$temp_num=count($temp_wt)-1;
						$xiabiao=mt_rand(0,$temp_num);
						$rand_wt=$temp_wt[$xiabiao];
						switch ($rand_wt) {
							case 1:
								$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,有时间可以聊会儿天么？";
								break;
							case 2:
								$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,希望能够了解你，可以交个朋友吗？";
								break;
							case 3:
								$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,我觉得我们挺合适的。";
								break;
							case 4:
								$data['content']="可以认识一下么？我生于".date('Y',$member['brith'])."年,身高".$basic['height']."cm,我觉得我们挺合适的。";
								break;
							case 5:
								$data['content']="哈喽！我个人很".$tezheng['tezheng'].",现居住在".$province[$member['province']]."，希望能够了解你，你怎么看？";
								break;
							case 6:
								$data['content']="嗨！我居住在".$province[$member['province']]."，平时喜欢".$aihao['aihao']."，希望你能和你交朋友哈！";
								break;
							case 7:
								$data['content']="嗨！我是来自".$province[$member['province']]."的年轻人，希望你会记得我哈~";
								break;
							case 8:
								$data['content']="嗨！我是".$basic['blood']."血型的,不知道你是不是也是这样的呢？";
								break;
							case 9:
								$data['content']="哈喽！我喜欢".$basic['leixin']."的女生,不知道你是不是也是这样的呢？";
								break;
							case 10:
								$data['content']="哈喽！我平时很喜欢".$aihao['aihao'].",不知道你是不是也是这样的呢？";
								break;
							case 11:
								$data['content']="哈喽！我个人很".$tezheng['tezheng'].",希望能够了解你，可以交个朋友吗？";
								break;
							case 12:
								$data['content']="哈喽！美女！我对你很感兴趣，不知道我们有缘分吗？";
								break;
							case 13:
								$data['content']="Hi,我觉得你很不错，有时间可以聊会儿天么？";
								break;
							case 14:
								$data['content']="hello,你很漂亮，我看上你了，我真心想找个合适的女朋友，也许我们能交个朋友哈~";
								break;
							case 15:
								$data['content']="hey，我觉得你很有气质，可以认识你吗？";
								break;
							case 16:
								$data['content']="我们好像在那里见过，可以认识你吗？";
								break;
							case 17:
								$data['content']="hey，我觉得你的打扮很多看，可以认识你吗？";
								break;
							default:
								$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
								break;
						}
					}
					pdo_insert("jy_ppp_xinxi",$data);
					echo 1;
				}
				exit;
			}
			else
			{
				$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
				$today=strtotime("today");
				$love=pdo_fetchall("SELECT a.id,a.attentid,a.mid as mid,b.avatar,b.nicheng,b.province,b.brith,b.sex,c.height FROM ".tablename('jy_ppp_attent')." as a left join ".tablename('jy_ppp_member')." as b on a.attentid=b.id left join ".tablename('jy_ppp_basic')." as c on a.attentid=c.mid WHERE a.weid=".$weid." AND a.mid=".$mid." ORDER BY a.createtime DESC LIMIT 10 ");
				if(!empty($love))
				{
					foreach ($love as $key => $value) {
						$temp=pdo_fetch("SELECT createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND mid=".$value['attentid']." AND type=0 ORDER BY createtime DESC LIMIT 1 ");
						if($temp['createtime']>$today)
						{
							$love[$key]['zh']=1;
						}
						else
						{
							$love[$key]['zh']=0;
						}
					}
				}
				else
				{
					if($member['sex']==1)
					{
						$condition.=" AND sex=2 ";
					}
					else
					{
						$condition.=" AND sex=1 ";
					}
					$match=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_match')." WHERE weid=".$weid." AND mid=".$mid);
					if(!empty($match))
					{
						if($match['province']>0)
						{
							$temp_province=$match['province'];
						}
						else
						{
							if(!empty($member['province']))
							{
								$temp_province=$member['province'];
							}
							else
							{
								$temp_province=11;
							}
						}
						$condition.=" AND province= ".$temp_province;
					}
					$tuijian=pdo_fetchall("SELECT id,avatar,sex,nicheng,province,brith FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid.$condition." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_member')." ) ORDER BY id LIMIT 4");
					if(!empty($tuijian))
					{
						foreach ($tuijian as $key => $value) {
							$zhaohu.=$value['id'].",";
						}
						$zhaohu=substr($zhaohu,0,-1 );
					}
				}
				include $this->template('mylove');
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