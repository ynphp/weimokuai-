<?php
/**
 * 圣诞派礼
 *
 * @author 刘靜
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('CSS_PATH', '../addons/jing_christmas/template/mobile/css/');
define('JS_PATH', '../addons/jing_christmas/template/mobile/js/');
define('IMG_PATH', '../addons/jing_christmas/template/mobile/images/');
define('SRC_PATH', '../addons/jing_christmas/template/mobile/src/');
class Jing_christmasModuleSite extends WeModuleSite {
	public $table_reply = 'jing_christmas_reply';
	public $table_award = 'jing_christmas_award';
	public $table_fans = 'jing_christmas_fans';
	public $table_share = 'jing_christmas_share';

	public function doMobileHome(){
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(':id' => $id));
		if (!empty($reply)) {
			if (empty($_W['fans']['nickname'])) {
				mc_oauth_userinfo();
			}
			if (empty($_W['openid'])) {
				header("Location:".$this->createMobileUrl('home',array('id'=>$id)));
			}
			//用户信息
			$awardfans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE reply_id = '{$id}' AND from_user = '{$_W['openid']}'");
			
			if (empty($awardfans)) {
				if (strpos($_W['fans']['tag']['avatar'],'/0')) {
					$_W['fans']['tag']['avatar'] = rtrim($_W['fans']['tag']['avatar'], '0') . 132;
				}

				$data1 = array(
					'reply_id' => $id,
					'from_user' => $_W['openid'],
					'nickname' => $_W['fans']['tag']['nickname'],
					'headimgurl' => $_W['fans']['tag']['avatar'],
					'totalnum' => '0',
					'awardnum' => '0',
					'last_time' => time(),
					'createtime' => time(),
					);
				pdo_insert($this->table_fans,$data1);
				$awardfans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE reply_id = '{$id}' AND from_user = '{$_W['openid']}'");
			}
			$share_from = base64_decode(urldecode($_GPC['share_from']));
			if (!empty($share_from) && !empty($_W['openid']) && $share_from != $_W['openid']) {//判断不是自己分享给自己
				$share_log = pdo_fetch("SELECT * FROM ".tablename($this->table_share)." WHERE reply_id=:reply_id AND from_user=:from_user AND share_from=:share_from",array(':reply_id'=>$id,':from_user'=>$_W['openid'],':share_from'=>$share_from));
				if (empty($share_log)) {
					$insert = array(
						'uniacid' => $_W['uniacid'],
						'reply_id' => $id,
						'from_user' => $_W['openid'],
						'share_from' => $share_from,
						'share_time' => time()
						);
					pdo_insert($this->table_share, $insert);
				}
			}
			if (empty($awardfans['mobile']) && $reply['needmobile'] == 1) {
				$ss = 5;
			}else{
				$ss = 200;
			}
			include $this->template('home');
		}else{
			exit('活动不存在！');
		}
	}

	public function doMobilesavemobile() {
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		if(preg_match(REGULAR_MOBILE, $_GPC['phone'])) {
			//用户信息
			$awardfans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE reply_id = '{$id}' AND from_user = '{$_W['openid']}'");
			if (!empty($awardfans)) {
				pdo_update($this->table_fans, array('mobile'=>$_GPC['phone']), array('id'=>$awardfans['id']));
				header("Location:".$_GPC['purl']);
			}else{
				header("Location:".$this->createMobileUrl('home',array('id'=>$id)));
			}
		} else {
			message('手机号格式不正确',referer(),'error');
		}
	}

	public function doMobileAwards(){
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(':id' => $id));
		if (!empty($reply)) {
			include $this->template('awards');
		}else{
			exit('活动不存在！');
		}
	}

	public function doMobilePaiming(){
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(':id' => $id));
		if (!empty($reply)) {
			$user_info = pdo_fetch("SELECT * FROM ".tablename($this->table_fans)." WHERE reply_id=:reply_id AND from_user=:from_user",array(':reply_id'=>$id,':from_user'=>$_W['openid']));
			if (empty($user_info)) {
				message('请先参与我们的活动后再来查看排名！', $this->createMobileUrl('Paiming',array('id'=>$id)),'error');
			}
			$paiming = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_fans) . " WHERE reply_id=:reply_id AND best_score >= :best_score", array(':reply_id'=>$id,':best_score'=>$user_info['best_score']));
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->table_fans) . " WHERE reply_id=:reply_id", array(':reply_id'=>$id));
			$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_fans) . " WHERE reply_id=:reply_id ORDER BY best_score DESC LIMIT 10", array(':reply_id'=>$id));
			//print_r($list);
			include $this->template('paiming');
		}else{
			exit('活动不存在！');
		}
	}

	public function doMobileRule(){
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(':id' => $id));
		if (!empty($reply)) {
			include $this->template('rule');
		}else{
			exit('活动不存在！');
		}
	}

	public function doMobileMain() {
		global $_W, $_GPC;
		$uniacid=$_W['uniacid'];
		load()->model('mc');
		$id = intval($_GPC['id']);
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(':id' => $id));
		$opration = $_GPC['op'];
		if ($opration == 'json') {
			exit('{"showFPS": false, "frameRate": 60, "project_type": "javascript", "debugMode": 1, "renderMode": 1, "id": "gameCanvas"}');
		}else{
			$share_from = base64_decode(urldecode($_GPC['share_from']));
			if (!empty($share_from) && !empty($_W['openid']) && $share_from != $_W['openid']) {//判断不是自己分享给自己
				$share_log = pdo_fetch("SELECT * FROM ".tablename($this->table_share)." WHERE reply_id=:reply_id AND from_user=:from_user AND share_from=:share_from",array(':reply_id'=>$id,':from_user'=>$_W['openid'],':share_from'=>$share_from));
				if (empty($share_log)) {
					$insert = array(
						'uniacid' => $_W['uniacid'],
						'reply_id' => $id,
						'from_user' => $_W['openid'],
						'share_from' => $share_from,
						'share_time' => time()
						);
					pdo_insert($this->table_share, $insert);
				}
			}
			if (!empty($reply)) {
				$error = $this->chekstatus($id);
				$errorCode = $error['errorCode'];
				$errorMsg = $error['errorMsg'];
				include $this->template('main');
			}else{
				exit('参数错误');
			}
		}
		
		
	}
	public function doMobileAddPlayNum(){
		global $_W,$_GPC;
		if(empty($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])!="xmlhttprequest"){
			die(json_encode(array('status'=>0,'您无法参加本活动！')));
		}
		$id = intval($_GPC['id']);
		$error = $this->chekstatus($id);
		if ($error['errorCode'] == 1) {
			die(json_encode(array('status'=>0,'msg'=>$error['errorMsg'])));
		}else{
			die(json_encode(array('status'=>1)));
		}
		
	}

	public function doMobileSaveScore() {
		global $_W,$_GPC;
		if(empty($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])!="xmlhttprequest"){
			die(json_encode(array('status'=>0,'您无法参加本活动！')));
		}
		$id = intval($_GPC['id']);
		$last_score = intval($_GPC['score']);
		$error = $this->chekstatus($id);
		if ($error['errorCode'] == 1) {
			die(json_encode(array('status'=>0,'msg'=>$error['errorMsg'])));
		}else{
			$from_user = $_W['fans']['from_user'];
			$awardfans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE reply_id = '{$id}' AND from_user = '{$from_user}'");
			if (!empty($awardfans)) {
				$best_score = $last_score > $awardfans['best_score'] ? $last_score : $awardfans['best_score'];
				pdo_update($this->table_fans, array('totalnum'=>$awardfans['totalnum'] + 1,'last_time' => time(),'last_score'=>intval($_GPC['score']),'best_score'=>$best_score), array('id'=>$awardfans['id']));
				die(json_encode(array('status'=>1)));
			}else{
				die(json_encode(array('status'=>0,'您无法参加本活动！')));
			}
		}
	}

	private function chekstatus($id){
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(':id' => $id));
		if (!empty($reply)) {	
			if (empty($_W['fans']['from_user'])) {
				$errorCode = 1;
				$errorMsg = '抽奖需要您先关注我们的平台哦～';
			}else{
				$from_user = $_W['fans']['from_user'];
				$year = date("Y");
				$month = date("m");
				$day = date("d");
				$dayBegin = mktime(0,0,0,$month,$day,$year);//当天开始时间戳
				$dayEnd = mktime(23,59,59,$month,$day,$year);//当天结束时间戳
				//分享次数（分享增加次数）
				$sharenum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_share) . " WHERE reply_id = '{$id}' AND share_from = '{$from_user}'");
				$addplaytime = floor($sharenum/$reply['zfcs']) * $reply['zjcs'];
				
				//用户信息
				$awardfans = pdo_fetch("SELECT * FROM " . tablename($this->table_fans) . " WHERE reply_id = '{$id}' AND from_user = '{$from_user}'");
				
				if (empty($awardfans)) {
					if (strpos($_W['fans']['tag']['avatar'],'/0')) {
						$_W['fans']['tag']['avatar'] = rtrim($_W['fans']['tag']['avatar'], '0') . 132;
					}
					if (empty($_W['fans']['tag']['nickname'])) {
						header("Location:".$this->createMobileUrl('home',array('id'=>$id)));
					}
					$data1 = array(
						'reply_id' => $id,
						'from_user' => $from_user,
						'nickname' => $_W['fans']['tag']['nickname'],
						'headimgurl' => $_W['fans']['tag']['avatar'],
						'totalnum' => '0',
						'awardnum' => '0',
						'last_time' => time(),
						'createtime' => time(),
						);
					pdo_insert($this->table_fans,$data1);
				}
				if (time() <= $reply['starttime'] || time() >= $reply['endtime'] || $reply['status'] == 0) {
					$errorCode = 1;
					$errorMsg = '本次活动已结束，请关注我们的下一次活动，谢谢～';
				}elseif ($reply['status'] == 2) {
					$errorCode = 1;
					$errorMsg = '本次活动暂停中，请随时关注我们平台的通知信息，谢谢～';
				}elseif ($awardfans['totalnum'] - $addplaytime >= $reply['playnum']) {
					$errorCode = 1;
					$errorMsg = '本次活动最多允许参加'.$reply['playnum'].'次，您已经参加'.$awardfans['totalnum'].'次,分享增加'.$addplaytime.'次，分享给朋友可以获得更多游戏次数哦';
				}else{
					$errorCode = 0;
					$errorMsg = '';
				}
			}
		}else{
			$errorCode = 1;
			$errorMsg = '活动不存在！';
		}
		return array('errorCode'=>$errorCode,'errorMsg'=>$errorMsg);
	}

	public function doWebList(){
		global $_W,$_GPC;
		$rid = intval($_GPC['id']);
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
		if (!empty($reply)) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM ".tablename($this->table_fans)." WHERE reply_id = '{$reply['id']}' ORDER BY best_score DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_fans) . " WHERE reply_id = '{$reply['id']}'");
			$pager = pagination($total, $pindex, $psize);
			include $this->template('awardlist');
		}
	}
}
?>