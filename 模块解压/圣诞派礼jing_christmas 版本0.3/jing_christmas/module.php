<?php
/**
 * 圣诞派礼
 *
 * @author 刘靜
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Jing_christmasModule extends WeModule {
	public $table_reply = 'jing_christmas_reply';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W, $_GPC;
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		$creditnames = uni_setting($_W['uniacid'], array('creditnames'));
		if($creditnames) {
			foreach($creditnames['creditnames'] as $index=>$creditname) {
				if($creditname['enabled'] == 0) {
					unset($creditnames['creditnames'][$index]);
				}
			}
			$scredit = implode(', ', array_keys($creditnames['creditnames']));
		} else {
			$scredit = '';
		}
		load()->func('tpl');
		if($rid==0){
			$reply = array(
				'title'=> '圣诞派礼活动开始了!',
				'description' => '圣诞派礼活动开始了',
				'starttime' => time(),
				'endtime' => time() + 10 * 84400,
				'status' => '1',
				'gametime' => '59',
				'awardnum'	=>	'1',
				'playnum'	=>	'5',
				'zfcs'		=>  '1',
				'zjcs'		=>  '1',
				'homelogo'	=>  '../addons/jing_christmas/template/mobile/images/homelogo.png',
				'audio'		=>	'../addons/jing_christmas/template/mobile/src/bg.mp3',
				'share_title'=> '圣诞派礼活动开始了',
				'share_title2'=> '我在圣诞派礼活动中获得[score]分！你也来试试吧！',
				'share_content'=> '亲，欢迎参加圣诞派礼活动，祝您好运哦！！',
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
				'starttime' => strtotime($_GPC['time'][start]),
				'endtime' => strtotime($_GPC['time'][end]),
				'status' => intval($_GPC['status']),
				'playnum' => intval($_GPC['playnum']),
				'zfcs' => intval($_GPC['zfcs']),
				'zjcs' => intval($_GPC['zjcs']),
				'totalnum' => intval($_GPC['totalnum']),
				'gametime' => intval($_GPC['gametime']),
				'homelogo' => $_GPC['homelogo'],
				'tips' => htmlspecialchars_decode($_GPC['tips']),
				'prizeinfo' => htmlspecialchars_decode($_GPC['prizeinfo']),
				'share_title' => $_GPC['share_title'],
				'share_title2' => $_GPC['share_title2'],
				'share_img' => $_GPC['share_img'],
				'audio' => $_GPC['audio'],
				'share_content'=>$_GPC['share_content'],
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


}