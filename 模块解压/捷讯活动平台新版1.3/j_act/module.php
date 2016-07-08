<?php
/**
 * 捷讯活动平台模块定义
 *
 * @author 捷讯设计
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class J_actModule extends WeModule {
	public $tablename = 'j_act_reply';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid limit 1", array(':rid' => $rid));
		}
		$list=pdo_fetchall("SELECT id,title FROM ".tablename('j_act_activity')." WHERE weid = '{$_W['uniacid']}' order by displayorder desc,id desc ");
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
		if(!intval($_GPC['aid']))message("编号不能为空");
		$insert = array(
			'rid' => $rid,
			'aid' => $_GPC['aid'],
			'weid'=> $_W['uniacid'],
			'cover' => $_GPC['cover'],
			'title' => $_GPC['title'],
			'description' => $_GPC['description'],
			'atype' => intval($_GPC['atype']),
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
		pdo_delete($this->tablename, array('id'=>$rid));
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if (checksubmit()) {
			$cfg = array(
				'title' => $_GPC['title'],
				'share_title' => $_GPC['share_title'],
				'share_info' => $_GPC['share_info'],
				'share_img' => $_GPC['share_img'],
				'gzurl' => $_GPC['gzurl'],
				'jfurl' => trim($_GPC['jfurl']),
				'sjurl' => trim($_GPC['sjurl']),
				'user_oauth' => intval($_GPC['user_oauth']),
				'appid' => trim($_GPC['appid']),
				'appsecret' => trim($_GPC['appsecret']),
				'pay_name' => $_GPC['pay_name'],
				'pay_mchid' => $_GPC['pay_mchid'],
				'pay_signkey' => $_GPC['pay_signkey'],
				'pay_ip' => $_GPC['pay_ip'],
				'paycheck' => intval($_GPC['paycheck']),
				'msg_id' => $_GPC['msg_id'],
				'msg_shopname' => $_GPC['msg_shopname'],
				
				'btn1_icon' => trim($_GPC['btn1_icon']),
				'btn1_title' => trim($_GPC['btn1_title']),
				'btn1_link' => trim($_GPC['btn1_link']),
				'btn2_icon' => trim($_GPC['btn2_icon']),
				'btn2_title' => trim($_GPC['btn2_title']),
				'btn2_link' => trim($_GPC['btn2_link']),
				'extend_js' => trim($_GPC['extend_js']),
            );
			load()->func('file');
			$dir_url='../attachment/j_act/cert_2/'.$_W['uniacid']."/";
			mkdirs($dir_url);
			$cfg['rootca']=$_GPC['rootca2'];
			$cfg['apiclient_cert']=$_GPC['apiclient_cert2'];
			$cfg['apiclient_key']=$_GPC['apiclient_key2'];
			if ($_FILES["rootca"]["name"]){
				if(file_exists($dir_url.$settings["rootca"]))@unlink ($dir_url.$settings["rootca"]);
				$cfg['rootca']=TIMESTAMP.".pem";
				move_uploaded_file($_FILES["rootca"]["tmp_name"],$dir_url.$cfg['rootca']);
			}
			if ($_FILES["apiclient_cert"]["name"]){
				if(file_exists($dir_url.$settings["apiclient_cert"]))@unlink ($dir_url.$settings["apiclient_cert"]);
				$cfg['apiclient_cert']="cert".TIMESTAMP.".pem";
				move_uploaded_file($_FILES["apiclient_cert"]["tmp_name"],$dir_url.$cfg['apiclient_cert']);
			}
			if ($_FILES["apiclient_key"]["name"]){
				if(file_exists($dir_url.$settings["apiclient_key"]))@unlink ($dir_url.$settings["apiclient_key"]);
				$cfg['apiclient_key']="key".TIMESTAMP.".pem";
				move_uploaded_file($_FILES["apiclient_key"]["tmp_name"],$dir_url.$cfg['apiclient_key']);
			}
            if ($this->saveSettings($cfg))message('保存成功', 'refresh');
        }
		load()->func('tpl');
		//这里来展示设置项表单
		include $this->template('setting');
	}

}