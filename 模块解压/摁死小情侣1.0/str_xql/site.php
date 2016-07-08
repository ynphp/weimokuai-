<?php
/**
 * 按死小情侣模块微站定义
 *
 * @author 柒|柒|源|码
 * @url http://H770.com/
 */
defined('IN_IA') or exit('Access Denied');
define('PATH',"../addons/str_xql/template/mobile/");

class str_xqlModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W;
		include $this->template('index');
	}

}