<?php
/**
 * 打鬼子大赛模块微站定义
 *
 * @author strai
 * @url http://www.xxvchuang.com/
 */
defined('IN_IA') or exit('Access Denied');
define('PATH',"../addons/str_dgz/template/mobile/style/");

class Str_dgzModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W;
		include $this->template('index');
	}

}