<?php
/**
 * 微信红包模块定义
 *
 * @author lizhangqu
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Lzq_redpackModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			// $_GPC 可以用来获取 Cookies,表单中以及地址栏参数
			$dat = $_GPC['data'];
			// 验证表单, 通过 message() 方法提示用户操作错误信息

			if($dat['money']<100||$dat['money']>20000){
				message('红包金额必需在1元~200元之间');
			}
			if($dat['clear']=='on'){
				pdo_query("TRUNCATE `ims_we7_redpack_reply`");
			}
			load()->func('file');
			$appid=$dat['appid'];
			$apiclient_cert=$dat['apiclient_cert'];
			$apiclient_key=$dat['apiclient_key'];
			$rootca=$dat['rootca'];
			
			$starttime=$dat['starttime'];
			$endtime=$dat['endtime'];
			if(strtotime($starttime)>strtotime($endtime)){
				message('结束时间必须比开始时间晚！！！');
			}
			
			
			file_write("./certs/index.html", "");
			file_write("./certs/".$appid."apiclient_cert.pem", $apiclient_cert);
			file_write("./certs/".$appid."apiclient_key.pem", $apiclient_key);
			file_write("./certs/".$appid."rootca.pem", $rootca);
			
			
			//字段验证, 并获得正确的数据$dat
			if (!$this->saveSettings($dat)) {
				message('保存信息失败','','error');   // 保存失败
			} else {
				message('保存信息成功','','success'); // 保存成功
			}
			
			
			
		}
		load()->func('tpl');
		//这里来展示设置项表单
		include $this->template('settings');
	}

}
