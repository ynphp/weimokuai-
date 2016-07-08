<?php
/**
 * 捷讯收银台模块定义
 *
 * @author 捷讯设计
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class J_moneyModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename("j_money_reply")." WHERE rid = :rid limit 1", array(':rid' => $rid));
		}
		load()->func('tpl');
		#include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W, $_GPC;
		$insert = array(
			'weid'=> $_W['uniacid'],
		);
		if (empty($id)) {
			$insert['rid']=$rid;
			$insert['status']=1;
			pdo_insert("j_money_reply", $insert);
		} else {
			pdo_update("j_money_reply", $insert, array('rid' => $rid));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		pdo_delete("j_money_reply", array('id'=>$rid));
	}
	
	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if (checksubmit()) {
			$wxcard=array();
			if(isset($_GPC['wxcard-key'])){
				foreach ($_GPC['wxcard-key'] as $index => $row) {
					if(empty($row))continue;
					$wxcard[$row]=$_GPC['wxcard-val'][$index];
				}
			}
			if(isset($_GPC['wxcard-key-new'])){
				foreach ($_GPC['wxcard-key-new'] as $index => $row) {
					if(empty($row))continue;
					$wxcard[$row]=$_GPC['wxcard-val-new'][$index];
				}
			}
			$parama=array();
			if(isset($_GPC['parama-key'])){
				foreach ($_GPC['parama-key'] as $index => $row) {
					if(empty($row))continue;
					$parama[$row]['value']=urlencode($_GPC['parama-val'][$index]);
					$parama[$row]['color']=$_GPC['parama-color'][$index];
				}
			}
			$cfg = array(
				'debug' => trim($_GPC['debug']),
				'copyright' => trim($_GPC['copyright']),
				
				'appid' => trim($_GPC['appid']),
				'sub_appid' => trim($_GPC['sub_appid']),
				'sub_mch_id' => trim($_GPC['sub_mch_id']),
				'cookiehold' => intval($settings["cookiehold"]) ? intval($settings["cookiehold"]) : 8,
				'logo' => trim($_GPC['logo']),
				'printpagewidth' => trim($_GPC['printpagewidth']),
				'appid' => trim($_GPC['appid']),
				'appsecret' => trim($_GPC['appsecret']),
				'pay_name' => $_GPC['pay_name'],
				'pay_mchid' => $_GPC['pay_mchid'],
				'pay_signkey' => $_GPC['pay_signkey'],
				'pay_ip' => $_GPC['pay_ip'],
				'notify_url' => $_GPC['notify_url'],
				
				'paycheck' => intval($_GPC['paycheck']),
				'openidcheck' => intval($_GPC['openidcheck']),
				'wxcard' =>json_encode($wxcard),
				'printnum' => intval($_GPC['printnum']) ? intval($_GPC['printnum'])>9 ? 9 : intval($_GPC['printnum']) : 1,
				'bg' => $_GPC['bg'],
				/*支付宝参数*/
				'app_id' => trim($_GPC['app_id']),
				'charset' => "GBK",
				'pemurl' => '../attachment/j_money/cert_2/'.$_W['uniacid']."/",
				'gatewayUrl' => trim($_GPC['gatewayUrl']) ? trim($_GPC['gatewayUrl']) :"https://openapi.alipay.com/gateway.do",
				/*模板消息*/
				'tempid'=>trim($_GPC['tempid']),
				'tempcontent'=>htmlspecialchars_decode($_GPC['tempcontent']),
				'tempurl'=>trim($_GPC['tempurl']),
				'tempparama'=>urldecode(json_encode($parama)),
				/*移动端参数*/
				'needtable' => intval($_GPC['needtable']),
				/*坐席限制*/
				'groupnum' => intval($_GPC['groupnum']),
				'usernum' => intval($_GPC['usernum']),
				/*积分*/
				'creadit' => intval($_GPC['creadit']),
				'creadittype' => strval($_GPC['creadittype']),
				/*退款*/
				'tempid2'=>trim($_GPC['tempid2']),
				'refunder'=>trim($_GPC['refunder']),
            );
			//----
			load()->func('file');
			$dir_url='../attachment/j_money/cert_2/'.$_W['uniacid']."/";
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
			/**/
			$cfg['alipay_public_key_file']=$_GPC['alipay_public_key_file2'];
			$cfg['merchant_private_key_file']=$_GPC['merchant_private_key_file2'];
			$cfg['merchant_public_key_file']=$_GPC['merchant_public_key_file2'];
			if ($_FILES["alipay_public_key_file"]["name"]){
				if(file_exists($dir_url.$settings["alipay_public_key_file"]))@unlink ($dir_url.$settings["alipay_public_key_file"]);
				$cfg['alipay_public_key_file']="ali_public".TIMESTAMP.".pem";
				move_uploaded_file($_FILES["alipay_public_key_file"]["tmp_name"],$dir_url.$cfg['alipay_public_key_file']);
			}
			if ($_FILES["merchant_private_key_file"]["name"]){
				if(file_exists($dir_url.$settings["merchant_private_key_file"]))@unlink ($dir_url.$settings["merchant_private_key_file"]);
				$cfg['merchant_private_key_file']="private".TIMESTAMP.".pem";
				move_uploaded_file($_FILES["merchant_private_key_file"]["tmp_name"],$dir_url.$cfg['merchant_private_key_file']);
			}
			if ($_FILES["merchant_public_key_file"]["name"]){
				if(file_exists($dir_url.$settings["merchant_public_key_file"]))@unlink ($dir_url.$settings["merchant_public_key_file"]);
				$cfg['merchant_public_key_file']="public".TIMESTAMP.".pem";
				move_uploaded_file($_FILES["merchant_public_key_file"]["tmp_name"],$dir_url.$cfg['merchant_public_key_file']);
			}
            if ($this->saveSettings($cfg))message('保存成功', 'refresh');
        }
		load()->func('tpl');
		//这里来展示设置项表单
		include $this->template('setting');
	}

}