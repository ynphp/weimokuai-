<?php
/**
 * 语音红包模块定义
 *
 * @author 别具一格
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Ju_redpacModule extends WeModule {
	public $table_reply = 'ju_redpac_reply';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W, $_GPC;
		load()->func('tpl');
		if($rid==0){
			$reply = array(
				'title'=> '语音红包活动开始了!',
				'description' => '语音红包活动开始了',
				'starttime' => time(),
				'endtime' => time() + 10 * 84400,
				'status' => '1',
				'red_num' => '1',
				'min' => '1.00',
				'max' => '5.00',
				'xuni' => '0',
				'xuninum' => '0',
				'desc' => '活动红包',
				'send_name' => $_W['account']['name'],
				'act_name' => '语音红包',
				'wishing' => '感谢您参加语音红包活动，祝您生活愉快！',
				'share_title'=> '语音红包活动开始了',
				'share_title2'=> '我在语音红包活动中获得[score]分！你也来试试吧！',
				'share_content'=> '亲，欢迎参加语音红包活动，祝您好运哦！！',
				'reply_1' => '本次活动尚未开始，感谢您的关注！',
				'reply_2' => '本次活动已结束，感谢您的关注！',
				'reply_3' => '本次活动暂停中，具体活动时间请关注本平台的通知！',
				'reply_4' => '您来晚了一步，红包已经被领取完了哦.',
				'reply_5' => '您已经领取过红包了哦，不要太贪心.',
				'reply_6' => '恭喜您，红包领取成功，红包金额为[money]元。[link]',
			);
		}else{
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->table_reply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W,$_GPC;
		$id = intval($_GPC['reply_id']);
		$insert = array(
				'rid' => $rid,
				'uniacid' => $_W['uniacid'],
				'title' => $_GPC['title'],
				'thumb' => $_GPC['thumb'],
				'description' => $_GPC['description'],
				'voice' => $this->filter_mark($_GPC['voice']),
				'starttime' => strtotime($_GPC['time'][start]),
				'endtime' => strtotime($_GPC['time'][end]),
				'tips' => htmlspecialchars_decode($_GPC['tips']),
				'people' => intval($_GPC['people']),
				'get_number' => intval($_GPC['get_number']),
				'xuni' => $_GPC['xuni'],
				'xuninum' => $_GPC['xuninum'],
				'type' => $_GPC['type'],
				'total' => $_GPC['total'],
				'max' => $_GPC['max'],
				'min' => $_GPC['min'],
				'numbers' => $_GPC['numbers'],
				'miane' => $_GPC['miane'],
				'sendtype' => $_GPC['sendtype'],
				'paydesc' => $_GPC['paydesc'],
				'red_num' => intval($_GPC['red_num']),
				'send_name' => $_GPC['send_name'],
				'act_name' => $_GPC['act_name'],
				'wishing' => $_GPC['wishing'],
				'adimg' => $_GPC['adimg'],
				'adurl' => $_GPC['adurl'],
				'reply_1' => $_GPC['reply_1'],
				'reply_2' => $_GPC['reply_2'],
				'reply_3' => $_GPC['reply_3'],
				'reply_4' => $_GPC['reply_4'],
				'reply_5' => $_GPC['reply_5'],
				'reply_6' => $_GPC['reply_6'],
				'share_title' => $_GPC['share_title'],
				'share_title2' => $_GPC['share_title2'],
				'share_img' => $_GPC['share_img'],
				'share_content'=>$_GPC['share_content'],
				'share_url'=>$_GPC['share_url'],
				'status' => intval($_GPC['status']),
				'createtime' => time(),		
			);
		if (empty($id)) {
			pdo_insert($this->table_reply, $insert);
		} else {
			unset($insert['createtime']);
			pdo_update($this->table_reply, $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		$replies = pdo_fetchall("SELECT id  FROM ".tablename($this->table_reply)." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				$deleteid[] = $row['id'];
			}
		}
		pdo_delete($this->table_reply, "id IN ('".implode("','", $deleteid)."')");
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		$params = array();
		if(empty($_W['isfounder'])) {
			$where = " WHERE `uniacid` IN (SELECT `uniacid` FROM " . tablename('uni_account_users') . " WHERE `uid`=:uid)";
			$params[':uid'] = $_W['uid'];
		}
		$sql = "SELECT * FROM " . tablename('uni_account') . $where;
		$uniaccounts = pdo_fetchall($sql, $params);
		$accounts = array();
		if(!empty($uniaccounts)) {
			foreach($uniaccounts as $uniaccount) {
				$accountlist = uni_accounts($uniaccount['uniacid']);
				if(!empty($accountlist)) {
					foreach($accountlist as $account) {
						if(!empty($account['key'])
						&& !empty($account['secret'])
						&& in_array($account['level'], array(4))) {
							$accounts[$account['acid']] = $account['name'];
						}
					}
				}
			}
		}
		if(checksubmit()) {
			load()->func('file');
            mkdirs(ATTACHMENT_ROOT . '/cert');
            $r = true;
            $pemname = $settings['pemname'];
            $pemname = isset($pemname) ? $pemname : md5(time());
            if(!empty($_GPC['cert'])) {
                $ret = file_put_contents(ATTACHMENT_ROOT . '/cert/apiclient_cert.pem.' . $pemname, trim($_GPC['cert']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(ATTACHMENT_ROOT . '/cert/apiclient_key.pem.' . $pemname, trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['ca'])) {
                $ret = file_put_contents(ATTACHMENT_ROOT . '/cert/rootca.pem.' . $pemname, trim($_GPC['ca']));
                $r = $r && $ret;
            }
            if(!$r) {
                message('证书保存失败, 请保证 /attachment/cert/ 目录可写');
            }
            $cfg = array(
                'oauth' => $_GPC['oauth'],
                'pemname' => $pemname,
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
		}
		include $this->template('setting');
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