<?php
/**
 * 签到管家模块定义
 *
 * @author Yoby
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class YobyqiandaoModule extends WeModule {
	public $title="签到管家";
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
	global $_W,$_GPC;

	if (!empty($rid)) {
	$reply = pdo_fetch("SELECT * FROM ".tablename('yobyqiandao_reply')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
}
$reply['jifen'] = empty($reply['jifen']) ? "1,5" : $reply['jifen'];
$reply['jifen2'] = empty($reply['jifen2']) ? "8" : $reply['jifen2'];
$reply['jifen3'] = empty($reply['jifen3']) ? "11" : $reply['jifen3'];
$reply['jifen4'] = empty($reply['jifen4']) ? "15" : $reply['jifen4'];
$reply['jifen5'] = empty($reply['jifen5']) ? "19" : $reply['jifen5'];
$reply['jifen6'] = empty($reply['jifen6']) ? "24" : $reply['jifen6'];
$reply['jifen7'] = empty($reply['jifen7']) ? "29" : $reply['jifen7'];

$reply['oktishi'] = empty($reply['oktishi']) ? "签到成功!" : $reply['oktishi'];
$reply['errortishi'] = empty($reply['errortishi']) ? "不在签到时间内!" : $reply['errortishi'];

$reply['start_time'] = empty($reply['start_time']) ? "00:00:00" : $reply['start_time'];
$reply['end_time'] = empty($reply['end_time']) ? "23:59:59" : $reply['end_time'];

$reply['top10'] = empty($reply['top10']) ? "10" : $reply['top10'];//排行榜显示数

$ad = iunserializer($reply['ad']);//广告数组
include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return true;
	}

	public function fieldsFormSubmit($rid=0) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
	global $_GPC, $_W;
	$id = intval($_GPC['reply_id']);
	$weid = $_W['weid'];
	$ad = array(
			'ad1'  => $_GPC['ad1'],
			'url1'  => $_GPC['url1'],
			'ad2'  => $_GPC['ad2'],
			'url2'  => $_GPC['url2'],
			'ad3'  => $_GPC['ad3'],
			'url3'  => $_GPC['url3'],
			'ad4'  => $_GPC['ad4'],
			'url4'  => $_GPC['url4'],
			'ad5'  => $_GPC['ad5'],
			'url5'  => $_GPC['url5'],
			);
	$insert = array(
			'rid' => $rid,
			'weid'             => $weid,
			'start_time' =>trim($_GPC['start_time']),
			'end_time' => trim($_GPC['end_time']),
			'jifen' =>trim($_GPC['jifen']),
			'jifen2' => intval($_GPC['jifen2']),
			'jifen3' => intval($_GPC['jifen3']),
			'jifen4' => intval($_GPC['jifen4']),
			'jifen5' => intval($_GPC['jifen5']),
			'jifen6' => intval($_GPC['jifen6']),
			'jifen7' => intval($_GPC['jifen7']),
			'top10' => intval($_GPC['top10']),
			'oktishi' => trim($_GPC['oktishi']),
			'errortishi' => trim($_GPC['errortishi']),
			'ad' =>iserializer($ad),
			
			);
	if (empty($id)) {
	pdo_insert('yobyqiandao_reply', $insert);
	}else{
	pdo_update('yobyqiandao_reply', $insert, array('id' => $id));
	}			
			
	}

	public function ruleDeleted($rid=0) {
		//删除规则时调用，这里 $rid 为对应的规则编号
pdo_delete('yobyqiandao_reply', array('rid' => $rid));
	}


}