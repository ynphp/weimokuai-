<?php
/**
 * 新年求签模块定义
 *
 * @author czt
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_qiuqianModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_GPC,  $_W;
		//$uniacid=$_W["uniacid"];
		load()->func('tpl');
		if($rid==0){
			$reply = array();
		}else{
			$reply = pdo_fetch("SELECT * FROM ".tablename('czt_qiuqian_reply')." WHERE rid = :rid ", array(':rid' => $rid));
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$insert = array(
			'rid'		=> $rid,
			'uniacid'   => $_W['uniacid'],
			's_title'   =>	$_GPC['s_title'],
			's_des'		=>	$_GPC['s_des'],
			's_icon'	=>	$_GPC['s_icon'],
			'bg_image'	=>	$_GPC['bg_image'],
			'subscribe_url'	=>	$_GPC['subscribe_url']
		);
		if (empty($id)) {
			pdo_insert('czt_qiuqian_reply', $insert);

		}else{
			pdo_update('czt_qiuqian_reply', $insert, array('id' => $id,'uniacid'   => $_W['uniacid']));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		global $_W;
		pdo_delete('czt_qiuqian_reply', array('rid' => $rid,'uniacid'=> $_W['uniacid']));
		return true;
	}
	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			//字段验证, 并获得正确的数据$dat
			$data = array(
				's_title'   =>	$_GPC['s_title'],
				's_des'		=>	$_GPC['s_des'],
				's_icon'	=>	$_GPC['s_icon'],
				'bg_image'	=>	$_GPC['bg_image'],
				'subscribe_url'	=>	$_GPC['subscribe_url']
			);
			if($this->saveSettings($data)){
				message('保存成功', 'refresh');
			}
		}
		//这里来展示设置项表单
		load()->func('tpl');
		include $this->template('settings');
	}
}