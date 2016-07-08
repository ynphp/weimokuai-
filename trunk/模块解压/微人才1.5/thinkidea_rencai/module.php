<?php
/**
 * 微招聘模块定义
 *
 * @author Thinkidea
 * @url http://bbs.012WZ.COM/
 * @公众号 qixintong365
 * @微赞企业QQ:800083075
 * 
 */
defined('IN_IA') or exit('Access Denied');

class Thinkidea_rencaiModule extends WeModule {
	/**
	 * 自定义回复表
	 * @var unknown
	 */
	public $tablename = 'thinkidea_rencai_reply';
	
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->tablename)." WHERE acid = :acid AND rid = :rid ORDER BY `id` DESC", array(':acid' => $_W['uniacid'], ':rid' => $rid));
		}
		load()->func('tpl');
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		return true;
	}

	public function fieldsFormSubmit($rid) {
		global $_GPC, $_W;
		$id = intval($_GPC['reply_id']);
		$data = array(
			'acid' => $_W['uniacid'],
			'rid' => $rid,
			'title' => $_GPC['title'],
			'avatar' => $_GPC['avatar'],
			'description' => $_GPC['description'],
			'dateline' => time()
		);
		if(empty($id)) {
			pdo_insert($this->tablename, $data);
		}else {
			pdo_update($this->tablename, $data, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
           
		if(checksubmit()) {
           
			$dat = array(
					'qrcode' => $_GPC['qrcode'],
					'telephone' => $_GPC['telephone'],
					'viewresumenums' => $_GPC['viewresumenums'],
					'isopenlicense' => $_GPC['isopenlicense'],
					'maxfilesize' => $_GPC['maxfilesize'],
					'headimgurlsize' => $_GPC['headimgurlsize'],
					'headimgurlwidth' => $_GPC['headimgurlwidth']
			);
			$this->saveSettings($dat);
			message('配置参数更新成功！', referer(), 'success');
		}
		load()->func('tpl');     echo("abcd=".json_encode($settings));
		include $this->template('setting');
	}

}