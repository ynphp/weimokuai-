<?php
/**
 * 告白解码器模块微站定义
 *
 * @author 柒|柒|源|码
 * @url http://H770.com/
 */
defined('IN_IA') or exit('Access Denied');
define('CSS_PATH', '../addons/hx_lovedecipher/template/mobile/style/css/');
define('JS_PATH', '../addons/hx_lovedecipher/template/mobile/style/js/');
define('IMG_PATH', '../addons/hx_lovedecipher/template/mobile/style/images/');
define('SOUND_PATH', '../addons/hx_lovedecipher/template/mobile/style/sound/');
@require_once('pingying.php');
class Hx_lovedecipherModuleSite extends WeModuleSite {
	public $table_reply = 'hx_lovedecipher_reply';
	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}

	public function doMobileIndex () {
		global $_W,$_GPC;
		$acc = WeAccount::create($_W['acid']);
		$jssdk = $acc->getJssdkConfig();
		$reply_id = intval($_GPC['id']);
		load()->func('logging');

		$reply = pdo_fetch("SELECT * FROM ".tablename($this->table_reply)." WHERE id = :id ORDER BY `id` DESC", array(':id' => $reply_id));
		if (empty($reply)) {
			message('您访问的活动不存在');
		}
		logging_run('111','','111');
		include $this->template('index');
	}

	public function doMobilePost() {
		global $_GPC,$_W;
		$insert = array(
			'uniacid' => $_W['uniacid'],
			'reply_id' => intval($_GPC['reply_id']),
			'openid' => $_W['openid'],
			'content' => $_GPC['content'],
			'department' => intval($_GPC['department']),
			'category' => intval($_GPC['category']),
			'createtime' => TIMESTAMP,
			);
		pdo_insert('hx_lovedecipher_data', $insert);
		$id = pdo_insertid();
		$py = new CUtf8_PY();
		if (!empty($id)) {
			$result = array(
				'result' => 100,
				'content_pingyin' => $py->encode($_GPC['content'],'all'),
				'content_binary' => '',
				'id' => $id
				);
		}else{
			$result = array(
				'result' => 101,
				);
		}
		return json_encode($result);
	}

	public function doMobileGet() {
		global $_GPC,$_W;
		$py = new CUtf8_PY();
		$id = intval($_GPC['id']);
		$log = pdo_fetch("SELECT * FROM ".tablename('hx_lovedecipher_data')." WHERE id=:id",array(':id'=>$id));
		$return = array(
			'result' => 100,
			'data' => array(
				'id' => $log['id'],
				'content' => $log['content'],
				'content_pinyin' => $py->encode($log['content'],'all'),
				'content_binary' => '',
				'department' => $log['department'],
				'category' => $log['category'],
				'ip' => '192.168.1.1',
				'city' => '西安'
				),
			);
		return json_encode($return);
	}

}