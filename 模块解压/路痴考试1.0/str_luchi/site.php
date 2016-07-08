<?php
/**
 * 路痴考试模块微站定义
 *
 * @author strai
 * @url http://H770.com/
 */
defined('IN_IA') or exit('Access Denied');
define('PATH',"../addons/str_luchi/template/mobile/style/");

class Str_luchiModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W;
		include $this->template('index');
	}

}