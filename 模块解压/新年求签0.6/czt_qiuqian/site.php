<?php
/**
 * 新年求签模块微站定义
 *
 * @author czt
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_qiuqianModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W, $_GPC;
		$settings=$this->module['config'];
		if (count($settings)==0) {
			$settings['s_title']='2015乙未羊，为自己摇枚新年签！';
			$settings['s_des']='这是对过去的感悟和对新年的祈望，希望它能为你带来好运！';
			$settings['s_icon']='';
			$settings['bg_image']='';

		}
		load()->classs('weixin.account');
		$accObj = WeiXinAccount::create($_W['acid']);
		$jssdkconfig = $accObj->getJssdkConfig();
		unset($accObj);
		if (!empty($settings['s_icon'])&&substr($settings['s_icon'],0,4)!='http') {
			$settings['s_icon']=$_W['attachurl'].$settings['s_icon'];
		}else{
			$settings['s_icon']=MODULE_URL.'template/mobile/images/ico.gif';
		}
		if (!empty($settings['bg_image'])&&substr($settings['bg_image'],0,4)!='http') {
			$settings['bg_image']=$_W['attachurl'].$settings['bg_image'];
		}else{
			$settings['bg_image']=MODULE_URL.'template/mobile/images/bg.jpg';
		}
		include $this->template('index');
		
	}
	public function doMobileQian() {
		$qian=array(array('name'=>'喜结','pinyin'=>'xijie'), array('name'=>'安产','pinyin'=>'anchan'), array('name'=>'良缘','pinyin'=>'liangyuan'), array('name'=>'佑儿','pinyin'=>'youer'), array('name'=>'气愈','pinyin'=>'qiyu'), array('name'=>'勤学','pinyin'=>'qinxue'), array('name'=>'利事','pinyin'=>'lishi'), array('name'=>'你谢见','pinyin'=>'nixiejian'), array('name'=>'白首','pinyin'=>'baishou'), array('name'=>'勇气','pinyin'=>'yongqi'), array('name'=>'断舍离','pinyin'=>'duansheli'),  array('name'=>'旅行','pinyin'=>'lvxing'),  array('name'=>'蜜恋','pinyin'=>'milian'),  array('name'=>'知足','pinyin'=>'zhizu'), array('name'=>'温柔','pinyin'=>'wenrou'), array('name'=>'孤独','pinyin'=>'gudu'),  array('name'=>'自由','pinyin'=>'ziyou'), array('name'=>'转运','pinyin'=>'zhuanyun'), array('name'=>'任性','pinyin'=>'renxing'));
		global $_W, $_GPC;
		$id=intval($_GPC['id']);
		$settings=$this->module['config'];
		if (!$id||!$qian[$id]) $id=mt_rand(0, 18);
		$qian=$qian[$id];
		$r=pdo_fetch("SELECT rid FROM " . tablename('cover_reply') . " WHERE module ='czt_qiuqian' and uniacid=".$_W['uniacid']);
		if ($r) {
			$r=pdo_fetch("SELECT * FROM " . tablename('rule_keyword') . " WHERE rid=".$r['rid']);
			$keyword=$r['content'];
		}
		include $this->template('qian');

	}
}
function dump($vars, $label = '', $return = false) {
	if (ini_get('html_errors')) {
		$content = "<pre>\n";
		if ($label != '') {
		    $content .= "<strong>{$label} :</strong>\n";
		}
		$content .= htmlspecialchars(print_r($vars, true));
		$content .= "\n</pre>\n";
	} else {
		$content = $label . " :\n" . print_r($vars, true);
	}
	if ($return) { return $content; }
	echo $content;
	return null;
}