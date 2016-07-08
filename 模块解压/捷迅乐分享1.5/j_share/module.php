<?php
/**
 * 捷讯乐分享模块定义
 *
 * @author 捷讯设计
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class J_shareModule extends WeModule {
	public $tablename = 'j_share_reply';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid limit 1", array(':rid' => $rid));
		}
		load()->func('tpl');
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W, $_GPC;
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
			'weid'=> $_W['uniacid'],
			'cover' => $_GPC['cover'],
			'title' => $_GPC['title'],
			'shareimg' => $_GPC['shareimg'],
			'description' => $_GPC['description'],
			'url' => $_GPC['url'],
		);
		if (empty($id)) {
			$insert['status']=1;
			pdo_insert($this->tablename, $insert);
		} else {
			pdo_update($this->tablename, $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		pdo_delete($this->tablename, array('rid'=>$rid));
	}


}