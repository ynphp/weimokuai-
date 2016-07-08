<?phpdefined('IN_IA') or exit('Access Denied');class We7_shakeModuleSite extends WeModuleSite {	public function doMobileWelcome() {		global $_W, $_GPC;		checkauth();		$rid = intval($_GPC['rid']);		$reply = pdo_fetch("SELECT * FROM " . tablename('shake_reply') . " WHERE rid = :rid", array(':rid' => $rid));		if (empty($reply)) {			message('抱歉，此活动不存在或是还未开始！', 'refresh', 'error');		}		load()->model('mc');		$profile = mc_fetch($_W['member']['uid'], array('realname', 'mobile', 'avatar'));		if (empty($profile['avatar'])) {			mc_oauth_userinfo();		}		if (empty($profile['realname']) || empty($profile['mobile'])) {			mc_require($_W['member']['uid'], array('realname', 'mobile'));		}		$member = pdo_fetch("SELECT * FROM " . tablename('shake_member') . " WHERE rid = :rid AND openid = :openid", array(':rid' => $reply['rid'], ':openid' => $_W['member']['uid']));		if (!empty($member)) {			header('Location: '.$this->createMobileUrl('detail', array('rid' => $reply['rid'])));			exit;		}		$reply['rule'] = html_entity_decode($reply['rule']);		include $this->template('welcome');	}	public function doMobileDetail() {		global $_W, $_GPC;		checkauth();		$rid = intval($_GPC['rid']);		$reply = pdo_fetch("SELECT * FROM " . tablename('shake_reply') . " WHERE rid = :rid", array(':rid' => $rid));		if (empty($reply)) {			message('抱歉，此活动不存在或是还未开始！', 'refresh', 'error');		}		$member = pdo_fetch("SELECT * FROM " . tablename('shake_member') . " WHERE rid = :rid AND openid = :openid", array(':rid' => $reply['rid'], ':openid' => $_W['member']['uid']));		if (empty($member)) {			$member = array(				'rid' => $rid,				'openid' => $_W['member']['uid'],				'createtime' => TIMESTAMP,				'shakecount' => 0,				'status' => 0,			);			$maxjoin = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('shake_member')." WHERE rid = '{$reply['rid']}' AND status = '1'");			if ($maxjoin < $reply['maxjoin']) {				mt_srand((double) microtime()*1000000);				$rand = mt_rand(1, 100);				if ($rand <= $reply['joinprobability']) {					$member['status'] = 1;				}			}			pdo_insert('shake_member', $member);		}		if (strexists(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) {			$reply['speed'] = $reply['speedandroid'];		}		include $this->template('detail');	}	public function doMobileShake() {		global $_W, $_GPC;		$rid = $_GPC['rid'];		$reply = pdo_fetch("SELECT * FROM " . tablename('shake_reply') . " WHERE rid = :rid AND status = 1", array(':rid' => $rid));		if (empty($reply)) {			message(array('status' => 0), '', 'ajax');		}		$maxshakecount = pdo_fetchcolumn("SELECT max(shakecount) FROM " . tablename('shake_member') . " WHERE rid =  :rid", array(':rid' => $rid));		if ($maxshakecount >= $reply['maxshake']) {			pdo_update('shake_reply', array('status' => 2), array('rid' => $rid));			message(array('status' => 2), '', 'ajax');		} else {			pdo_query("UPDATE " . tablename('shake_member') . " SET shakecount = shakecount + 1, lastupdate = '" . TIMESTAMP . "' WHERE openid = :openid AND rid = :rid", array(':rid' => $rid, ':openid' => $_W['member']['uid']));			$member = pdo_fetch("SELECT * FROM " . tablename('shake_member') . " WHERE rid = :rid AND openid = :openid", array(':rid' => $rid, ':openid' => $_W['member']['uid']));			message(array('status' => 1, 'shakecount' => $member['shakecount'], 'lastupdate' => $member['lastupdate']), '', 'ajax');		}	}	public function doWebDetail() {		global $_W, $_GPC;		checklogin();		$id = intval($_GPC['id']);		$reply = pdo_fetch("SELECT * FROM " . tablename('shake_reply') . " WHERE rid = :id", array(':id' => $id));		if (empty($reply)) {			message('抱歉，此活动不存在或是还未开始！', 'refresh', 'error');		}		load()->model('mc');		if (empty($reply['status'])) {			$list = pdo_fetchall("SELECT openid, shakecount FROM " . tablename('shake_member') . " WHERE rid = :rid ORDER BY id ASC", array(':rid' => $reply['rid']), 'openid');			$total = count($list);			$fans = mc_fetch(array_keys($list), array('realname', 'mobile', 'avatar'));			$lastupdatetime = pdo_fetchcolumn("SELECT createtime FROM " . tablename('shake_member') . " WHERE rid = :rid ORDER BY createtime DESC LIMIT 1", array(':rid' => $reply['rid']));		} else {			$reply['rule'] = htmlspecialchars_decode($reply['rule']);			$limit = empty($reply['maxwinner']) ? 10 : $reply['maxwinner'];			$list = pdo_fetchall("SELECT openid, shakecount FROM " . tablename('shake_member') . " WHERE rid = :rid ORDER BY shakecount DESC LIMIT $limit", array(':rid' => $reply['rid']), 'openid');			$fans = mc_fetch(array_keys($list), array('realname', 'mobile', 'avatar', 'nickname'));		}		$reply['keyword'] = pdo_fetchall("SELECT content FROM " . tablename('rule_keyword') . " WHERE rid = '{$reply['rid']}'");		include $this->template('detail');	}	public function doWebGetRank() {		global $_GPC, $_W;		load()->model('mc');		checklogin();		$result = array('status' => 0, 'message' => '');		$id = intval($_GPC['id']);		$reply = pdo_fetch("SELECT * FROM " . tablename('shake_reply') . " WHERE id = :id", array(':id' => $id));		if (empty($reply['status'])) {			$result['message'] = '活动还未开始！';			message($result, $this->createWebUrl('detail', array('id' => $reply['rid'], 'weid' => $_W['uniacid'], 'status' => 0)), 'ajax');		}		if ($reply['status'] == 2) {			$result['status'] = 2;			$result['message'] = '活动已经结束！';			message($result, $this->createWebUrl('detail', array('id' => $reply['rid'], 'weid' => $_W['uniacid'])), 'ajax');		}		$limit = empty($reply['maxwinner']) ? 10 : $reply['maxwinner'];		$result['message'] = pdo_fetchall("SELECT openid, shakecount FROM " . tablename('shake_member') . " WHERE rid = :rid ORDER BY shakecount DESC LIMIT $limit", array(':rid' => $reply['rid']));		$result['status'] = 1;		message($result, '', 'ajax');	}	public function doWebGetJoin() {		global $_W, $_GPC;		load()->model('mc');		$rid = intval($_GPC['rid']);		$lastupdatetime = $_GPC['lastupdatetime'];		$list = pdo_fetchall("SELECT openid, shakecount FROM " . tablename('shake_member') . " WHERE rid = :rid AND createtime > '{$lastupdatetime}' ORDER BY id ASC", array(':rid' => $rid), 'openid');		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('shake_member')." WHERE rid = :rid", array(':rid' => $rid));		$fans = mc_fetch(array_keys($list), array('realname', 'mobile', 'avatar'));		if (!empty($fans)) {			foreach ($fans as $uid => $row) {				$list[$uid]['avatar'] = $row['avatar'];			}		}		message(array('list' => $list, 'total' => $total), '', 'ajax');	}	public function doWebManage() {		global $_W, $_GPC;		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';		$rid = intval($_GPC['id']);		if ($operation == 'display') {			$pindex = max(1, intval($_GPC['page']));			$psize = 50;			if (!empty($_GPC['nickname'])) {				$openids = pdo_fetchall("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE nickname LIKE :nickname", array(':nickname' => '%' . $_GPC['nickname'] . '%'), 'openid');				if (!empty($openids)) {					$condition = " AND openid IN ('" . implode("','", array_keys($openids)) . "')";				}			}			if (!empty($condition) || empty($_GPC['nickname'])) {				$list = pdo_fetchall("SELECT * FROM " . tablename('shake_member') . " WHERE rid = :rid $condition ORDER BY shakecount DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':rid' => $rid), 'openid');				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('shake_member') . " WHERE rid = :rid $condition", array(':rid' => $rid));				$pager = pagination($total, $pindex, $psize);				load()->model('mc');				$fans = mc_fetch(array_keys($list), array('realname', 'mobile'));			}		} elseif ($operation == 'post') {			$id = intval($_GPC['id']);			$item = pdo_fetch("SELECT * FROM " . tablename('shake_member') . " WHERE id = '$id'");			if (checksubmit('submit')) {				pdo_update('shake_member', array(					'remark' => $_GPC['remark'],				), array('id' => $id));				message('更新信息成功！', $this->createWebUrl('manage', array('id' => $item['rid'])), 'success');			}			$item['profile'] = fans_search($item['openid'], array('mobile', 'realname'));		}		include $this->template('manage');	}	public function doWebAddShakecount() {		global $_GPC, $_W;		checklogin();		$id = intval($_GPC['id']);		$shake = pdo_fetch("SELECT shakecount, rid FROM " . tablename('shake_member') . " WHERE id = :id", array(':id' => $id));		$item = pdo_fetch("SELECT maxshake FROM " . tablename('shake_reply') . " WHERE rid = '{$shake['rid']}'");		if ($item['maxshake'] > $shake['shakecount']) {			pdo_update('shake_member', array('shakecount' => $shake['shakecount'] + 1), array('id' => $id));		}		message($shake['shakecount'], '', 'ajax');	}	public function doWebChangeStatus() {		global $_W, $_GPC;		checklogin();		$id = intval($_GPC['id']);		$status = intval($_GPC['status']);		pdo_update('shake_reply', array('status' => $status), array('rid' => $id));		message(array('status' => 1), '', 'ajax');	}}