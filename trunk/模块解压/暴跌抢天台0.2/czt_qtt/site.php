<?php
/**
 * 暴跌抢天台模块微站定义
 *
 * @author 
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_qttModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_GPC, $_W;
		//这个操作被定义用来呈现 功能封面
		include $this->template('index');
	}
	public function doMobileIndex2() {
		global $_GPC, $_W;
		include $this->template('index2');

	}
	public function doMobileIndex3() {
		global $_GPC, $_W;
		$settings=$this->module['config'];
		include $this->template('index3');
	}

}