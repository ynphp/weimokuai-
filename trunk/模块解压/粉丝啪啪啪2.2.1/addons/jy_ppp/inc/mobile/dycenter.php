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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'dycenter'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'dycenter'))."';
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
				$op=$_GPC['op'];
				if(empty($op))
				{
					$xuni=pdo_fetchall("SELECT b.id,c.attentnum,d.weidunum FROM ".tablename('jy_ppp_xuni_member')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ( SELECT count(id) as attentnum,attentid FROM ".tablename('jy_ppp_attent')." group by attentid ) as c on b.id=c.attentid left join ( SELECT count(id) as weidunum,mid FROM ".tablename('jy_ppp_xinxi')." WHERE yuedu=0 group by mid ) as d on b.id=d.mid WHERE a.weid=".$weid." AND a.dyid=".$dyid);
					$num=count($xuni);
					$weidu=0;
					$attent=0;
					if(!empty($xuni))
					{
						foreach ($xuni as $key => $value) {
							$weidu+=$value['weidunum'];
							$attent+=$value['attent'];
						}
					}

					include $this->template('dycenter');
				}
				if($op=='zhaohu')
				{
					$xuni=pdo_fetchall("SELECT b.* FROM ".tablename('jy_ppp_xuni_member')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.dyid=".$dyid);
					$today=strtotime("today");
					if(empty($xuni))
					{
						echo 2;
						exit;
					}
					else
					{
						foreach ($xuni as $key => $value) {
							$mid=$value['id'];
							$member=$value;
							if($value['sex']==1)
							{
								$js_sex=2;
							}
							else
							{
								$js_sex=1;
							}
							$temp_yifa=pdo_fetchall("SELECT mid FROM ".tablename("jy_ppp_xinxi")." WHERE weid=".$weid." AND type=0 AND createtime>=".$today." AND sendid=".$mid);
							$temp_condtiton='';
							if(!empty($temp_yifa))
							{
								$temp_condtiton=" AND id NOT IN ( ";
								foreach ($temp_yifa as $key3 => $value3) {
									$temp_condtiton.=$value3['mid'].",";
								}
								$temp_condtiton=substr($temp_condtiton, 0 , -1 );
								$temp_condtiton.=") ";
							}
							$js_member=pdo_fetchall("SELECT id FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid.$temp_condtiton." AND sex= ".$js_sex);
							if(!empty($js_member))
							{
								foreach ($js_member as $key2 => $value2) {
									$id=$value2['id'];
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
									$kefu_member=pdo_fetch("SELECT type,from_user,nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
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
								}
							}
						}
						echo 1;
						exit;
					}
				}
				if($op=='tj')
				{
					$xuni=pdo_fetchall("SELECT b.* FROM ".tablename('jy_ppp_xuni_member')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.dyid=".$dyid);
					$today=strtotime("today");
					if(empty($xuni))
					{
						echo 2;
						exit;
					}
					else
					{
						foreach ($xuni as $key => $value) {
							$mid=$value['id'];
							$member=$value;
							if($value['sex']==1)
							{
								$js_sex=2;
							}
							else
							{
								$js_sex=1;
							}
							$temp_yifa=pdo_fetchall("SELECT mid FROM ".tablename("jy_ppp_xinxi")." WHERE weid=".$weid." AND type=1 AND createtime>=".$today." AND sendid=".$mid);
							$temp_condtiton='';
							if(!empty($temp_yifa))
							{
								$temp_condtiton=" AND id NOT IN ( ";
								foreach ($temp_yifa as $key3 => $value3) {
									$temp_condtiton.=$value3['mid'].",";
								}
								$temp_condtiton=substr($temp_condtiton, 0 , -1 );
								$temp_condtiton.=") ";
							}
							$js_member=pdo_fetchall("SELECT id FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid.$temp_condtiton." AND sex= ".$js_sex);
							if(!empty($js_member))
							{
								foreach ($js_member as $key2 => $value2) {
									$id=$value2['id'];
									$data=array(
											'weid'=>$weid,
											'mid'=>$id,
											'sendid'=>$mid,
											'type'=>1,
											'yuedu'=>0,
											'createtime'=>TIMESTAMP,
										);
									if($member['sex']==2)
									{

										$data['content']="她目前在线想聊聊天，发个私信陪陪她吧看看她会和你说什么";
									}
									else
									{
										$data['content']="他目前在线想聊聊天，发个私信陪陪他吧看看他会和你说什么";
									}

									pdo_insert("jy_ppp_xinxi",$data);
									$kefu_member=pdo_fetch("SELECT type,from_user,nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
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
											$text2="系统管理员将'".$send_member['nicheng']."'推荐给你";
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
								}
							}
						}
						echo 1;
						exit;
					}
				}
				if($op=='hf')
				{
					$xuni=pdo_fetchall("SELECT b.* FROM ".tablename('jy_ppp_xuni_member')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.dyid=".$dyid);
					$today=strtotime("today");
					if(empty($xuni))
					{
						echo 2;
						exit;
					}
					else
					{
						$content_arr=array('hello,你好哈~','在干嘛呐？','无聊ing，聊聊天呗','hey','我刚刚看到你在线了哦~','可以交个朋友吗');
						foreach ($xuni as $key => $value) {
							$mid=$value['id'];
							$member=$value;
							if($value['sex']==1)
							{
								$js_sex=2;
							}
							else
							{
								$js_sex=1;
							}
							$temp_yifa=pdo_fetchall("SELECT mid FROM ".tablename("jy_ppp_xinxi")." WHERE weid=".$weid." AND type=2 AND createtime>=".$today." AND sendid=".$mid);
							$temp_condtiton='';
							if(!empty($temp_yifa))
							{
								$temp_condtiton=" AND id NOT IN ( ";
								foreach ($temp_yifa as $key3 => $value3) {
									$temp_condtiton.=$value3['mid'].",";
								}
								$temp_condtiton=substr($temp_condtiton, 0 , -1 );
								$temp_condtiton.=") ";
							}
							$js_member=pdo_fetchall("SELECT id FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid.$temp_condtiton." AND sex= ".$js_sex);
							if(!empty($js_member))
							{
								foreach ($js_member as $key2 => $value2) {
									$id=$value2['id'];
									$data=array(
											'weid'=>$weid,
											'mid'=>$id,
											'sendid'=>$mid,
											'type'=>2,
											'yuedu'=>0,
											'createtime'=>TIMESTAMP,
										);

									$temp_content_num=mt_rand(0,6);
									$data['content']=$content_arr[$temp_content_num];
									if($data['content'])
									{
										$data['content']='hello!';
									}


									pdo_insert("jy_ppp_xinxi",$data);
									$kefu_member=pdo_fetch("SELECT type,from_user,nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
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
											$text2="系统管理员将'".$send_member['nicheng']."'推荐给你";
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
								}
							}
						}
						echo 1;
						exit;
					}
				}
		}
		else
		{

			echo "<script>
					window.location.href = '".$this->createMobileUrl('dy_login')."';
				</script>";
		}