<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
class Welian_Indiana_Member {
	public function getInfoByMember($id = ''){
		//ͨ��ims_weliam_indiana_member�������Ϣ
		global $_W;
		$uid = intval($id);
		if(empty($uid)){
			$info = pdo_fetch("select * from".tablename('weliam_indiana_member')."where uniacid = '{$_W['uniacid']}' and openid = '{$id}' limit 1");
		}else{
			$info = pdo_fetch("select * from".tablename('weliam_indiana_member')."where uniacid = '{$_W['uniacid']}' and mid = '{$id}' limit 1");
		}
		return $info;
	}
	public function getInfoByOpenid($openid = '') {
		global $_W;
		load()->model('mc');
		$uid = mc_openid2uid($openid);
		$members = self::getInfoByMember($openid);
		if(!empty($members)){
			//��member���в�ѯ����
			$info['openid'] = $openid;
			$info['nickname'] = $members['nickname'];
			$info['avatar'] = $members['avatar'];
			$info['uid'] = $members['mid'];
			$member_info = mc_fetch($uid, array('credit1','credit2','nickname','avatar'));
			$info['credit1'] = $member_info['credit1'];
			$info['credit2'] = $member_info['credit2'];
			if(empty($info['nickname'])) {
				$info['nickname'] = $member_info['nickname'];
			}
			if(empty($info['avatar'])) {
				$info['avatar'] = $member_info['avatar'];
			}
		}else{
			//ԭ����member��
			$fansinfo = mc_fansinfo($uid,$_W['acid'],$_W['uniacid']);
			$info['openid'] = $openid; 
			$info['nickname'] = $fansinfo['tag']['nickname']; 
			$info['avatar'] = $fansinfo['tag']['avatar'];
			$member_info = mc_fetch($uid, array('credit1','credit2','nickname','avatar'));
			$info['uid'] = $member_info['uid'];
			$info['credit1'] = $member_info['credit1'];
			$info['credit2'] = $member_info['credit2'];
			if(empty($info['nickname'])) {
				$info['nickname'] = $member_info['nickname'];
			}
			if(empty($info['avatar'])) {
				$info['avatar'] = $member_info['avatar'];
			}
		}
		return $info;
	}
	public function getMember($openid = ''){
		global $_W;
		$uid = intval($openid);
		if(empty($uid)){
			$info = pdo_fetch("select * from".tablename('weliam_indiana_member')."where uniacid = '{$_W['uniacid']}' and openid = '{$openid}' limit 1");
		}else{
			$info = pdo_fetch("select * from".tablename('weliam_indiana_member')."where uniacid = '{$_W['uniacid']}' and mid = '{$openid}' limit 1");
		}
		return $info;
	}
	public function getInfoById($id = 0) {
		global $_W;
		$info = pdo_fetch('select * from ' . tablename('weliam_indiana_member') . ' where  uniacid=:uniacid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $id));
		return $info;
	}
	
	public function updateInfoByOpenid($data=array(),$openid='') {
		global $_W;
		pdo_update('weliam_indiana_member',$data,array('openid'=>$openid));
	}
	
	public function updateInfoById($data=array(),$id=0) {
		global $_W;
		pdo_update('weliam_indiana_member',$data,array('id'=>$id));
	}
	
	function getList($array=array()) {
		global $_W;
		$page = !empty($array['pindex'])? intval($array['pindex']): 1;
		$pagesize = !empty($array['psize'])? intval($array['psize']): 10;
		$random = !empty($array['random'])? $array['random'] : false;
		$orderby = !empty($array['orderby'])? $array['orderby'] : '';
		$condition =  " and uniacid = :uniacid ";
		$params = array(':uniacid' => $_W['uniacid']);
		if (!$random) {
			$sql = "SELECT * FROM " . tablename('weliam_indiana_member') . " where 1 {$condition} order by '{$orderby}' LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;
		} else{
			$sql = "SELECT * FROM " . tablename('weliam_indiana_member') . " where 1 {$condition}";
		}
		$list = pdo_fetchall($sql, $params);
		return $list;
	}
	function getListNum($array=array()) {
		global $_W;
		$condition =  "and uniacid = :uniacid";
		$params = array(':uniacid' => $_W['uniacid']);
		$sql = "SELECT count(*) FROM " . tablename('weliam_indiana_member') . " where 1 {$condition}";
		$totle = pdo_fetchcolumn($sql, $params);
		return $totle;
	}
	function checkMember($openid = ''){
		//�����û�����Ϣ
		global $_W,$_GPC;
		//�ж��Ƿ���ֻ�����
		if(strexists($_SERVER['REQUEST_URI'], '/web/')){
			return;
		}
		if(empty($openid)){
			$openid = m('user')-> getOpenid();
		}
		if(empty($openid)){
			return;
		}
		$member = m('member')->getMember($openid);
		$userinfo = m('user') -> getInfo();
		$followed = m('user') -> followed($openid);
		$uid = 0;
		$mc = array();
		load()->model('mc');
		if ($followed) {
			$uid = mc_openid2uid($openid);
			$mc = mc_fetch($uid, array('realname', 'mobile', 'avatar', 'resideprovince', 'residecity'));
		}
		if (empty($member)) {
			$invid = $_GPC['mid'];
			if(empty($invid)){
				$invid = -1;
			}
			$member = array(
				'uniacid' => $_W['uniacid'], 
				'openid' => $openid, 
				'realname' => !empty($mc['realname'])?$mc['realname']:'', 
				'mobile' => !empty($mc['mobile'])?$mc['mobile']:'', 
				'nickname' => !empty($mc['nickname'])?$mc['nickname']:$userinfo['nickname'], 
				'avatar' => !empty($mc['avatar'])?$mc['avatar']:$userinfo['avatar'], 
				'gender' => !empty($mc['gender'])?$mc['gender']:$userinfo['sex'], 
				'createtime' => time(), 
				'status' => 1,
				'type'=>1
			);
			pdo_insert('weliam_indiana_member', $member);
			if($invid != -1){
				pdo_update('weliam_indiana_member', array('credit1' => $_GPC['cd2']), array('id' => $invid));
			}
		}else{
			if (!empty($uid)) {
				$upgrade = array();
				if ($userinfo['nickname'] != $member['nickname']) {
					$upgrade['nickname'] = $userinfo['nickname'];
				} 
				if ($userinfo['avatar'] != $member['avatar']) {
					$upgrade['avatar'] = $userinfo['avatar'];
				}
				if ($member['credit1'] > 0) {
					mc_credit_update($uid, 'credit1', $member['credit1']);
					$upgrade['credit1'] = 0;
				} 
				if ($member['credit2'] > 0) {
					mc_credit_update($uid, 'credit2', $member['credit2']);
					$upgrade['credit2'] = 0;
				} 
				if (!empty($upgrade)) {
					pdo_update('weliam_indiana_member', $upgrade, array('mid' => $member['mid']));
				} 
			} 
		}
	}
} 

