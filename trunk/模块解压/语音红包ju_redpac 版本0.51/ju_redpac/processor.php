<?php
/**
 * 语音红包模块处理程序
 *
 * @author 别具一格
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Ju_redpacModuleProcessor extends WeModuleProcessor {
	public $table_reply = 'ju_redpac_reply';
	public $table_user = 'ju_redpac_user';
	public function respond() {
		global $_W;
		$content = $this->message['content'];
		$fromuser = $this->message['from'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		//print_r($this->message);
		if ($this->message['msgtype'] == 'voice') {
			$voice = $this->filter_mark($this->message['recognition']);
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE voice  LIKE :voice", array(':voice' => $voice));
			if($reply) {
				$rid = $reply['rid'];
				$time = time();
				//print_r($reply);
				if ($reply['status'] == 2) {
					return $this->respText($reply['reply_3']);
				} else if ($reply['status'] == 0) {
					return $this->respText($reply['reply_2']);
				} else {
					if ($time < $reply['starttime']) {
						return $this->respText($reply['reply_1']);
					} elseif ($time > $reply['endtime']) {
						return $this->respText($reply['reply_2']);
					} else {
						$total_num = pdo_fetchcolumn("select count(*) from " . tablename($this->table_user) . " where rid='{$rid}'");
						$number = $total_num + 1;
						$total_money = pdo_fetchcolumn("select sum(money) from " . tablename($this->table_user) . " where rid='{$rid}'");
						$total_money = $total_money + $reply['xuni'] ;
						$my_num = pdo_fetchcolumn("select count(*) from " . tablename($this->table_user) . " where rid='{$rid}' and fromuser='{$fromuser}'");
						if ($my_num >= $reply['get_number']) {
							return $this->respText($reply['reply_5']);
						}
						if ($reply['type'] == 1) {//手气红包
							if ($total_money >= $reply['total']) {
								return $this->respText($reply['reply_4']);
							}else{
								$min = $reply['min'] * 100;
								$max = $reply['max'] * 100;
								$money = rand($min,$max);
								$money = $money/100;
								if ($money > $reply['total'] - $total_money) {
									$money = $reply['total'] - $total_money;
									if ($money < 1) {
										$money = 1;
									}
								}
								$sn = md5($fromuser.time());
								$insert = array(
									'rid' => $rid,
									'sn' => $sn,
									'fromuser' => $fromuser,
									'money' => $money,
									'status' => 1,
									'createtime' => time()
									);
								pdo_insert($this->table_user, $insert);
								$user_id = pdo_insertid();
								$link = "\n".'<a href="'.$_W['siteroot'].'app'.str_replace('./', '/', $this->createMobileUrl('redpac',array('sn'=>$sn))).'">领取红包</a>';
								$reply_msg = $reply['reply_6'];
								$reply_msg = str_replace(array('[number]','[money]','[link]'), array($number,$money,$link), $reply_msg);
								return $this->respText($reply_msg);
							}
						}else{ //普通红包
							if ($total_num >= $reply['numbers']) {
								return $this->respText($reply['reply_4']);
							}else{
								$sn = md5($fromuser.time());
								$insert = array(
									'rid' => $rid,
									'sn' => $sn,
									'fromuser' => $fromuser,
									'money' => $reply['miane'],
									'status' => 1,
									'createtime' => time()
									);
								pdo_insert($this->table_user, $insert);
								$user_id = pdo_insertid();
								$link = "\n".'<a href="'.$_W['siteroot'].'app'.str_replace('./', '/', $this->createMobileUrl('redpac',array('sn'=>$sn))).'">领取红包</a>';
								$reply_msg = $reply['reply_6'];
								$reply_msg = str_replace(array('[number]','[money]','[link]'), array($number,$reply['miane'],$link), $reply_msg);
								return $this->respText($reply_msg.$number);
							}
						}
					}
				}
			}else{
				return $this->respText('识别结果：'.$voice.'.请重试！');
			}
		} else {
			return $this->respText('请使用语音回复“'.$content.'”参与活动！');
		}
	}

	private function filter_mark($text){ 
		if(trim($text)=='')return ''; 
		$text=preg_replace("/[[:punct:]\s]/",' ',$text); 
		$text=urlencode($text); 
		$text=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99|%EF%BD%9E|%EF%BC%8E|%EF%BC%88)+/",' ',$text); 
		$text=urldecode($text); 
		return trim($text); 
	}
}