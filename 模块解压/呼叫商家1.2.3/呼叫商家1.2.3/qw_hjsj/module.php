<?php
/**
 * 呼叫商家模块定义
 *
 * @author xiaozhao
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Qw_hjsjModule extends WeModule {
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
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			$lefttpl = $_GPC['lefttpl'];
			$righttpl = $_GPC['righttpl'];
			array_pop($lefttpl);
			array_pop($righttpl);
			$succlefttpl = $_GPC['succlefttpl'];
			$succrighttpl = $_GPC['succrighttpl'];
			array_pop($succlefttpl);
			array_pop($succrighttpl);
			$shenhelefttpl = $_GPC['shenhelefttpl'];
			$shenherighttpl = $_GPC['shenherighttpl'];
			array_pop($shenhelefttpl);
			array_pop($shenherighttpl);
			$jiedanlefttpl = $_GPC['jiedanlefttpl'];
			$jiedanrighttpl = $_GPC['jiedanrighttpl'];
			array_pop($jiedanlefttpl);
			array_pop($jiedanrighttpl);
			$dat= array(
				'lefttpl'=>json_encode($lefttpl),
				'righttpl'=>json_encode($righttpl),
				'tplid'=>$_GPC['tplid'],
				'succlefttpl'=>json_encode($succlefttpl),
				'succrighttpl'=>json_encode($succrighttpl),
				'succtplid'=>$_GPC['succtplid'],
				'shenhelefttpl'=>json_encode($shenhelefttpl),
				'shenherighttpl'=>json_encode($shenherighttpl),
				'shenhetplid'=>$_GPC['shenhetplid'],
				'jiedanlefttpl'=>json_encode($jiedanlefttpl),
				'jiedanrighttpl'=>json_encode($jiedanrighttpl),
				'jiedantplid'=>$_GPC['jiedantplid'],
				//首页基本设置保存
				'sytitle'=>trim($_GPC['sytitle']),
				'sypic'=>$_GPC['sypic'],
				'djinfo'=>trim($_GPC['djinfo']),
				'fxtitle'=>$_GPC['fxtitle'],
				'fxdes'=>$_GPC['fxdes'],
				'fxpic'=>$_GPC['fxpic'],
				'merchant_id' => $_GPC['merchant_id'],
				'api_secret' => $_GPC['api_secret'],
				//提现资金
				'yjbl'=>$_GPC['yjbl'],
				'txinfo'=>$_GPC['txinfo'],
				'hjdj'=>$_GPC['hjdj'],
				'txxz'=>$_GPC['txxz'],
				'sxmoney'=>$_GPC['sxmoney'],
				'txsuccid'=>$_GPC['txsuccid'],
				'txtjid'=>$_GPC['txtjid'],
				'jssuccid'=>$_GPC['jssuccid']
			);
			//支付证书配置
			$success = true;
			if ( ! empty($_GPC['cert'])) {
				$ret = file_put_contents(MODULE_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
				$success = $success && $ret;
			}

			if ( ! empty($_GPC['key'])) {
				$ret = file_put_contents(MODULE_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
				$success = $success && $ret;
			}
			if ( ! empty($_GPC['ca'])) {
				$ret = file_put_contents(MODULE_ROOT . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
				$success = $success && $ret;
			}
			if ( ! $success) {
				message("证书保存失败, 请保证 /addons/{$this->modulename}/cert/ 目录可写");
				exit;
			}
			if ($this->saveSettings($dat)) {
				message('保存成功', 'refresh');
			}
		}
		$lefttpl = json_decode($settings['lefttpl']);
		$righttpl = json_decode($settings['righttpl']);
		$count = count($lefttpl);
		$num = $count-1;
		$succlefttpl = json_decode($settings['succlefttpl']);
		$succrighttpl = json_decode($settings['succrighttpl']);
		$succcount = count($succlefttpl);
		$succnum = $succcount-1;
		$shenhelefttpl = json_decode($settings['shenhelefttpl']);
		$shenherighttpl = json_decode($settings['shenherighttpl']);
		$shenhecount = count($shenhelefttpl);
		$shenhenum = $shenhecount-1;
		$jiedanlefttpl = json_decode($settings['jiedanlefttpl']);
		$jiedanrighttpl = json_decode($settings['jiedanrighttpl']);
		$jiedancount = count($jiedanlefttpl);
		$jiedannum = $jiedancount-1;
		//支付方面的配置
		$is_apiclient_cert = file_exists(MODULE_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid']);
		$is_apiclient_key = file_exists(MODULE_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid']);
		$is_rootca = file_exists(MODULE_ROOT . '/cert/rootca.pem.' . $_W['uniacid']);
		//这里来展示设置项表单
		include $this->template('setting');
	}
}