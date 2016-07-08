<?php
/**
 * 女神来了模块定义
 *
 */
defined('IN_IA') or exit('Access Denied');

class fm_photosvoteModuleProcessor extends WeModuleProcessor {
	public $name = 'fm_photosvoteModuleProcessor';
	public $title = '女神来了';
	public $table_reply  = 'fm_photosvote_reply';//规则 相关设置
	public $table_users   = 'fm_photosvote_provevote';	//投稿参加活动的人
	public $table_log   = 'fm_photosvote_votelog';//投票记录
	public $table_bbsreply   = 'fm_photosvote_bbsreply';//投票记录
	public $table_banners   = 'fm_photosvote_banners';//幻灯片
	public $table_advs   = 'fm_photosvote_advs';//广告
	public $table_gift   = 'fm_photosvote_gift';
	public $table_data   = 'fm_photosvote_data';

	public function isNeedInitContext() {
		return 0;
	}
	
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$from= $this->message['from'];
		$tag = $this->message['content'];
		$uniacid = $_W['uniacid'];//当前公众号ID	
		load()->func('communication');
		
		//推送分享图文内容
		$sql = "SELECT * FROM " . tablename($this->table_reply) . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));
			if (empty($row['id'])) {
				$message = "亲，您还没有设置完成关键字或者未添加活动！";
				return $this->respText($message);		
			}
				
		
		
		

		
		if ($row['status']==0){
			$message = "亲，".$row['title']."活动暂停了！您可以\n";
			$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&rid=".$rid."&m=fm_photosvote&do=paihang&uniacid=".$uniacid."'>看看排行榜</a>\n";
			if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
				$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
			}			
			return $this->respText($message);
		}
		$now = time();
				
				if($now >= $row['start_time'] && $now <= $row['end_time']){
					if ($row['status']==0){
						$message = "亲，".$row['title']."活动暂停了！";
						return $this->respText($message);
					}else{
						
						
						
						$command = $row['command'];
						$ckcommand = $row['ckcommand'];
						$rets = preg_match('/'.$command.'/i', $tag);
						$ckrets = preg_match('/'.$ckcommand.'/i', $tag);
						$zjrets = preg_match('/^[0-9]*$/i', $tag);


						

						if ($_SESSION['ok'] <> 1) {
							if (!$zjrets) {
								

								$picture = $row['picture'];
								if (substr($picture,0,6)=='images'){
									$picture = $_W['attachurl'] . $picture;
								}else{
									$picture = $_W['siteroot'] . $picture;
								}
								return $this->respNews(array(
									'Title' => $row['title'],
									'Description' => htmlspecialchars_decode($row['description']),
									'PicUrl' => $picture,
									'Url' => $this->createMobileUrl('photosvoteview', array('rid' => $rid)),
								));
							}							
						//if ($reply['iscode'] == 1) {
							
						
							$this->beginContext(60);//锁定60秒
							$_SESSION['ok']= 1;	
							$_SESSION['content']= $this->message['content'];						
							$_SESSION['code']=random(4,true);
							return $this->respText("为防止恶意刷票，请回复验证码：".$_SESSION["code"]);	
						
						}else {						
							if($this->message['content']!=$_SESSION['code']){
								return $this->respText("验证码错误，请重新回复验证码：".$_SESSION['code']);	
							}else{
								$tag = $_SESSION['content'];
								$rets = preg_match('/'.$command.'/i', $tag);
								$ckrets = preg_match('/'.$ckcommand.'/i', $tag);
								$this->endContext();
								
								if ($rets || $ckrets || $zjrets) {	

									if ($zjrets)  {
										
										if ($now <= $row['tstart_time']) {							
											return $this->respText($row['ttipstart']);								
										}
										if ($now >= $row['tend_time']) {								
											return $this->respText($row['ttipend']);				
										}

										$starttime=mktime(0,0,0);//当天：00：00：00
										$endtime = mktime(23,59,59);//当天：23：59：59
										$times = '';
										$times .= ' AND createtime >=' .$starttime;
										$times .= ' AND createtime <=' .$endtime;
										
										$daytpxz = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND rid = :rid '.$times.' ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from,':rid' => $rid));
										//echo date('Y-m-d H:i:s', mktime(0,0,0));//当天：00：00：00
										//echo date('Y-m-d H:i:s', mktime(23,59,59));//当天：23：59：59
										
										

										$where .= " AND id = '".$tag."'";
										
										
										$t = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and rid = :rid ".$where." LIMIT 1", array(':uniacid' => $uniacid,':rid' => $rid));					
										if (empty($t)) {
											$message = '未找到参赛者编号为 '.$tag.' 的用户，请重新输入！';
											$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&rid=".$rid."&m=fm_photosvote&do=photosvoteview'>活动首页</a>\n";
											if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
												$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
											}
											return $this->respText($message);
										}

										if($t['status']!='1'){
											
											$message = '您投票的用户编号为 '.$tag.'还未通过审核，请稍后再试,您可以：';
											$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&rid=".$rid."&m=fm_photosvote&do=photosvoteview&uniacid=".$uniacid."'>活动首页</a>\n";
											if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
												$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
											}
											
											return $this->respText($message);
											
											
										}
										if ($row['isdaojishi']) {
											$votetime = $reply['votetime']*3600*24;
											$isvtime = $now - $t['createtime'];
											if($isvtime >= $votetime) {
												
													$message = empty($row['ttipvote']) ? $t['nickname'].' 的投票时间已经结束' : $row['ttipvote'];
												
												return $this->respText($message);
											}
										}
										$tfrom_user = $t['from_user'];
										//$user = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $tfrom_user,':rid' => $rid));
										
										if ($daytpxz >= $row['daytpxz']) {
											$message = '您当前最多可以投'.$row['daytpxz'].'个参赛选手，您当天的次数已经投完，请明天再来';
											return $this->respText($message);
										}	else {
											if ($tfrom_user == $from) {
												//message('您不能为自己投票',referer(),'error');
												$message = '您不能为自己投票';
												return $this->respText($message);
											}else {
												
												
												$dayonetp = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid '.$times.' ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from, ':tfrom_user' => $tfrom_user,':rid' => $rid));
												
												$allonetp = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from, ':tfrom_user' => $tfrom_user,':rid' => $rid));
												if ($allonetp >= $row['allonetp']) {
													$message = "您总共可以给她投票".$row['allonetp']."次，您已经投完！您还可以：\n";
													$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&tfrom_user=".$tfrom_user."&rid=".$rid."&m=fm_photosvote&do=tuserphotos&uniacid=".$uniacid."'>查看她的投票</a>\n";
														if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
															$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
														}
													
													return $this->respText($message);
												} else {
													if ($dayonetp >= $row['dayonetp']) {
														$message = "您当天最多可以给她投票".$row['dayonetp']."次，您已经投完，请明天再来。您还可以：\n";
														$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&tfrom_user=".$tfrom_user."&rid=".$rid."&m=fm_photosvote&do=tuserphotos&uniacid=".$uniacid."'>查看她的投票</a>\n";
														if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
															$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
														}
														return $this->respText($message);
														//exit;
													}else {												
																										
														$atype = 'weixin';
														$token = $_W['account']['access_token']['token'];
														$urls = sprintf("https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN", $token,$from);
														$contents = ihttp_get($urls);
														$dats = $contents['content'];
														$re = @json_decode($dats, true);
														
														$nickname = $re['nickname'];
														$avatar = $re['headimgurl'];
														
														
														$votedate = array(
															'uniacid' => $uniacid,
															'rid' => $rid,
															'tptype' => '2',
															'avatar' => $avatar,
															'nickname' => $nickname,
															'from_user' => $from,
															'afrom_user' => $_COOKIE["user_fromuser_openid"],
															'tfrom_user' => $tfrom_user,
															'ip' => getip(),
															'createtime' => time(),
															
														);
														$votedate['iparr'] = $this->getiparr($votedate['ip']);	
														pdo_insert($this->table_log, $votedate);
														pdo_update($this->table_users, array('hits' => $t['hits']+1,'photosnum'=> $t['photosnum']+1), array('rid' => $rid, 'from_user' => $tfrom_user,'uniacid' => $uniacid));
														//message('您成功的为Ta投了一票！',referer(),'success');
														if (!empty($t['realname'])) {
															$tname = '姓名为： ' . $t['realname'];
														}else {
															$tname = '昵称为： ' . $t['nickname'];
														}
														$message = "恭喜您成功的为编号为： ".$t['id']." , ".$tname." 的参赛者投了一票！";
														//$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&rid=".$rid."&m=fm_photosvote&do=tuserphotos&tfrom_user=".$tfrom_user."'>查看她的投票</a>\n";
																						
														//return $this->respText($message);
														$rowtp = array();
																$rowtp['title'] = $message;
																$rowtp['description'] =htmlspecialchars_decode($message);
																$rowtp['picurl'] = !empty($t['photo']) ? toimage($t['photo']) : toimage($t['avatar']);
																$rowtp['url'] =  $this->createMobileUrl('tuserphotos', array('rid' => $rid, 'tfrom_user' => $tfrom_user));
														
														$news[] = $rowtp;
														if ($row['isindex'] == 1) {
															$advs = pdo_fetchall("SELECT * FROM " . tablename($this->table_advs) . " WHERE enabled=1 AND ismiaoxian = 0 AND uniacid= '{$uniacid}'  AND rid= '{$rid}' ORDER BY displayorder ASC");
															
															foreach($advs as $c) {
																$rowadv = array();
																$rowadv['title'] = $c['advname'];
																$rowadv['description'] = $c['description'];
																$rowadv['picurl'] = toimage($c['thumb']);
																$rowadv['url'] = empty($c['link']) ? $this->createMobileUrl('photosvoteview', array('rid' => $rid)) : $c['link'];
																$news[] = $rowadv;
															}
															
														}	
														if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
															$rowhd = array();
															$rowhd['title'] = $row['huodongname'];
															$rowhd['description'] = $row['huodongname'];
															$rowhd['picurl'] = toimage($row['hhhdpicture']);
															$rowhd['url'] = $row['huodongurl'];
															$news[] = $rowhd;
															
														}
														return $this->respNews($news);												
													}
												}
											}
											
										}
									}

									if ($ckrets)  {
										$ckisret = preg_match('/^'.$ckcommand.'/i', $tag);		
										if (!$ckisret) {
											$message = '请输入1合适的格式, "'.$ckcommand.'+参赛者编号(ID) 或者 参赛者真实姓名", 如: "'.$ckcommand.'186 或者 '.$ckcommand.'菲儿"';
											return $this->respText($message);
										}
										$ckret = preg_match('/(?:'.$ckcommand.')(.*)/i', $tag, $ckmatchs);					
										$ckword = $ckmatchs[1];
										
										$starttime=mktime(0,0,0);//当天：00：00：00
										$endtime = mktime(23,59,59);//当天：23：59：59
										$times = '';
										$times .= ' AND createtime >=' .$starttime;
										$times .= ' AND createtime <=' .$endtime;
										
										$daytpxz = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND rid = :rid '.$times.' ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from,':rid' => $rid));
										//echo date('Y-m-d H:i:s', mktime(0,0,0));//当天：00：00：00
										//echo date('Y-m-d H:i:s', mktime(23,59,59));//当天：23：59：59
										if (is_numeric($ckword)) 
											$where .= " AND id = '".$ckword."'";
										else 				
											$where .= " AND realname = '".$ckword."'";
										
										$t = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and rid = :rid ".$where." LIMIT 1", array(':uniacid' => $uniacid,':rid' => $rid));					
										if (empty($t)) {
											$message = '未查询到参赛者 '.$ckword.' 的信息，请重新输入！';
											$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&rid=".$rid."&m=fm_photosvote&do=photosvoteview&uniacid=".$uniacid."'>活动首页</a>\n";
											if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
												$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
											}
											return $this->respText($message);
										}
										if($t['status']!='1'){
											
											$message = '您查看的用户'.$ckword.'还未通过审核，请稍后再试,您可以：';
											$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&rid=".$rid."&m=fm_photosvote&do=photosvoteview&uniacid=".$uniacid."'>活动首页</a>\n";
											if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
												$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
											}
											
											return $this->respText($message);
											
											
										}
										
										$tfrom_user = $t['from_user'];
										
										$picture = toimage($t['photo']);
										return $this->respNews(array(
											'Title' => $t['nickname'] . ' ' . $t['id'] . '号参赛选手',
											'Description' => '她说： '. htmlspecialchars_decode($t['photoname']),
											'PicUrl' => $picture,
											'Url' => $this->createMobileUrl('tuserphotos', array('rid' => $rid, 'tfrom_user' => $tfrom_user)),
										));
										
										
									}
									
									if ($rets)  {
										$isret = preg_match('/^'.$command.'/i', $tag);					
													
										if (!$isret) {
											$message = '请输入合适的格式, "'.$command.'+参赛者编号(ID) 或者 参赛者真实姓名", 如: "'.$command.'186 或者 '.$command.'菲儿"';
											return $this->respText($message);
										}					
										
										
										
										$ret = preg_match('/(?:'.$command.')(.*)/i', $tag, $matchs);					
										$word = $matchs[1];
										
										$starttime=mktime(0,0,0);//当天：00：00：00
										$endtime = mktime(23,59,59);//当天：23：59：59
										$times = '';
										$times .= ' AND createtime >=' .$starttime;
										$times .= ' AND createtime <=' .$endtime;
										
										$daytpxz = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND rid = :rid '.$times.' ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from,':rid' => $rid));
										//echo date('Y-m-d H:i:s', mktime(0,0,0));//当天：00：00：00
										//echo date('Y-m-d H:i:s', mktime(23,59,59));//当天：23：59：59
										if (is_numeric($word)) 
											$where .= " AND id = '".$word."'";
										else 				
											$where .= " AND realname = '".$word."'";
										
										$t = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and rid = :rid ".$where." LIMIT 1", array(':uniacid' => $uniacid,':rid' => $rid));					
										if (empty($t)) {
											$message = '未找到参赛者 '.$ckword.' ，请重新输入！';
											$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&rid=".$rid."&m=fm_photosvote&do=photosvoteview&uniacid=".$uniacid."'>活动首页</a>\n";
											if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
												$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
											}
											return $this->respText($message);
										}
										if($t['status']!='1'){
											
											$message = '您投票的用户'.$ckword.'还未通过审核，请稍后再试,您可以：';
											$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&rid=".$rid."&m=fm_photosvote&do=photosvoteview&uniacid=".$uniacid."'>活动首页</a>\n";
											if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
												$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
											}
											
											return $this->respText($message);
											
											
										}
										if ($row['isdaojishi']) {
											$votetime = $reply['votetime']*3600*24;
											$isvtime = $now - $t['createtime'];
											if($isvtime >= $votetime) {
												
													$message = empty($row['ttipvote']) ? $t['nickname'].' 的投票时间已经结束' : $row['ttipvote'];
												
												return $this->respText($message);
											}
										}
										$tfrom_user = $t['from_user'];
										//$user = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and from_user = :from_user and rid = :rid", array(':uniacid' => $uniacid,':from_user' => $tfrom_user,':rid' => $rid));
										
										if ($daytpxz >= $row['daytpxz']) {
											$message = '您当前最多可以投票'.$row['daytpxz'].'个参赛选手，您当天的次数已经投完，请明天再来';
											return $this->respText($message);
										}	else {
											if ($tfrom_user == $from) {
												//message('您不能为自己投票',referer(),'error');
												$message = '您不能为自己投票';
												return $this->respText($message);
											}else {
												
												
												$dayonetp = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid '.$times.' ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from, ':tfrom_user' => $tfrom_user,':rid' => $rid));
												
												$allonetp = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename($this->table_log).' WHERE uniacid= :uniacid AND from_user = :from_user AND tfrom_user = :tfrom_user AND rid = :rid ORDER BY createtime DESC', array(':uniacid' => $uniacid, ':from_user' => $from, ':tfrom_user' => $tfrom_user,':rid' => $rid));
												if ($allonetp >= $row['allonetp']) {
													$message = "您总共可以给她投票".$row['allonetp']."次，您已经投完！您还可以：\n";
													$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&tfrom_user=".$tfrom_user."&rid=".$rid."&m=fm_photosvote&do=tuserphotos&uniacid=".$uniacid."'>查看她的投票</a>\n";
														if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
															$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
														}
													
													return $this->respText($message);
												} else {
													if ($dayonetp >= $row['dayonetp']) {
														$message = "您当天最多可以给她投票".$row['dayonetp']."次，您已经投完，请明天再来。您还可以：\n";
														$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&tfrom_user=".$tfrom_user."&rid=".$rid."&m=fm_photosvote&do=tuserphotos&uniacid=".$uniacid."'>查看她的投票</a>\n";
														if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
															$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
														}
														return $this->respText($message);
														//exit;
													}else {												
																										
														$atype = 'weixin';
														$token = $_W['account']['access_token']['token'];
														$urls = sprintf("https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN", $token,$from);
														$contents = ihttp_get($urls);
														$dats = $contents['content'];
														$re = @json_decode($dats, true);
														
														$nickname = $re['nickname'];
														$avatar = $re['headimgurl'];
														
														
														$votedate = array(
															'uniacid' => $uniacid,
															'rid' => $rid,
															'tptype' => '2',
															'avatar' => $avatar,
															'nickname' => $nickname,
															'from_user' => $from,
															'afrom_user' => $_COOKIE["user_fromuser_openid"],
															'tfrom_user' => $tfrom_user,
															'ip' => getip(),
															'createtime' => time(),
															
														);
														$votedate['iparr'] = $this->getiparr($votedate['ip']);	
														pdo_insert($this->table_log, $votedate);
														pdo_update($this->table_users, array('hits' => $t['hits']+1,'photosnum'=> $t['photosnum']+1), array('rid' => $rid, 'from_user' => $tfrom_user,'uniacid' => $uniacid));
														//message('您成功的为Ta投了一票！',referer(),'success');
														if (!empty($t['realname'])) {
															$tname = '姓名为： ' . $t['realname'];
														}else {
															$tname = '昵称为： ' . $t['nickname'];
														}
														$message = "恭喜您成功的为编号为： ".$t['id']." , ".$tname." 的参赛者投了一票！您还可以：\n";
														$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&rid=".$rid."&m=fm_photosvote&do=tuserphotos&tfrom_user=".$tfrom_user."'>查看她的投票</a>\n";
														if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
															$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
														}										
														return $this->respText($message);													
													}
												}
											}
											
										}
									}
																					
								}
							}
						//判断
						}						
						
					}
								
				}else{
					
					if($now <= $row['start_time']){
						$message = "亲，".$row['title']."活动将在".date("Y-m-d H:i:s", $row['start_time'])."时准时开放投票,您可以：\n";
						$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&rid=".$rid."&m=fm_photosvote&do=photosvoteview&uniacid=".$uniacid."'>先睹为快</a>\n";
						if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
							$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
						}
						
					}elseif($now >= $row['end_time']){
						$message = "亲，".$row['title']."活动已经于".date("Y-m-d H:i:s", $row['end_time'])."时结束,您可以：\n";
						$message .= "1、<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&j=".$_W['acid']."&c=entry&rid=".$rid."&m=fm_photosvote&do=paihang&uniacid=".$uniacid."'>看看排行榜</a>\n";
						if ($row['ishuodong'] == 1 && !empty($row['huodongurl'])) {
							$message .= "2、<a href='".$row['huodongurl']."'>".$row['huodongname']."</a>";
						}			
						
					}
					return $this->respText($message);				
				}

	}
	
	public function isNeedSaveContext() {
		return false;
	}

	public function GetIpLookup($ip = ''){  
		if(empty($ip)){  
			$ip = getip();  
		}  
		$res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);  
		if(empty($res)){ return false; }  
		$jsonMatches = array();  
		preg_match('#\{.+?\}#', $res, $jsonMatches);  
		if(!isset($jsonMatches[0])){ return false; }  
		$json = json_decode($jsonMatches[0], true);  
		if(isset($json['ret']) && $json['ret'] == 1){  
			$json['ip'] = $ip;  
			unset($json['ret']);  
		}else{  
			return false;  
		}  
		return $json;  
	}  
	public function getiparr($ip) {
		$ip = $this->GetIpLookup($row['ip']);
		$iparr = array();
		$iparr['country'] .= $ip['country'];
		$iparr['province'] .= $ip['province'];
		$iparr['city'] .= $ip['city'];
		$iparr['district'] .= $ip['district'];
		$iparr['ist'] .= $ip['ist'];
		$iparr = iserializer($iparr);
		return $iparr;
	}


	
}


