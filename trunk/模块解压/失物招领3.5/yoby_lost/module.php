<?php
/**
 * 失物招领模块定义
 *
 * @author Yoby
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Yoby_lostModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		$yobyurl = "../addons/yoby_lost/";
		if(checksubmit()) {
			$cfg = array(
				'address' =>$_GPC['address'],	
			);
			if($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}
		if(!isset($settings['address'])) {
			$settings['address'] = 'http://weixin10.sinaapp.com/app/index.php?c=home&i=1&t=1';
		}
		include $this->template('setting');
	}

}