<?php
/**
 * 微婚礼模块定义
 *
 * @author czt
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_marryModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_GPC,  $_W;
		$uniacid=$_W["uniacid"];
		load()->func('tpl');
		if($rid==0){
			$reply = array();
		}else{
			$reply = pdo_fetch("SELECT * FROM ".tablename('czt_marry')." b left join ".tablename('czt_marry_reply')." a on a.marry_id=b.id WHERE rid = :rid ", array(':rid' => $rid));
		}
		$list = pdo_fetchall("SELECT *  from ".tablename('czt_marry')." where uniacid='{$uniacid}'  order by id desc LIMIT 0,20" );
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		global $_GPC, $_W;
		$r = pdo_fetch("SELECT * FROM ".tablename('czt_marry')." WHERE id = :id" , array(':id' => intval($_GPC['marry_id'])));
		if (empty($r)) return '抱歉，婚礼不存在或是已经删除！';
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$insert = array(
			'rid'		=> $rid,
			'uniacid'   => $_W['uniacid'],
			'marry_id'	=> intval($_GPC['marry_id'])
		);
		if (empty($id)) {
			pdo_insert('czt_marry_reply', $insert);

		}else{
			pdo_update('czt_marry_reply', $insert, array('id' => $id,'uniacid'   => $_W['uniacid']));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		global $_W;
		pdo_delete('czt_marry_reply', array('rid' => $rid,'uniacid'=> $_W['uniacid']));
		return true;
	}
}