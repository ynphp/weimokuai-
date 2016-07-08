<?php
/**
 * water吸粉按手指
 * @author DONGYUE
 * 2015-08-03
 * QQ491024175
 * @url http://bbs.we7.cc/
 */
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
class water_xf01ModuleSite extends WeModuleSite {
	public $systemtable =  'water_xf01_info';
	
	
	public function dowebSystem() {
		global $_W,$_GPC;
		load()->func('tpl');
		$system = pdo_fetch("SELECT * FROM ".tablename($this->systemtable)." WHERE uniacid= ".$_W['uniacid']);
 		
		if (checksubmit()) {
			$data = array(
					'show1' => $_GPC ['show1'],
					'show2' => $_GPC ['show2'],
					'show3' => $_GPC ['show3'],
					'url1' => $_GPC ['url1'],
					'url2' => $_GPC ['url2'],
					'url3' => $_GPC ['url3'],
			);
			if (!empty($system)) {
				pdo_update($this->systemtable, $data, array('uniacid' => $_W['uniacid']));
			}else {
				$data['uniacid'] = $_W['uniacid'];
				pdo_insert($this->systemtable, $data);
				$system = pdo_fetch("SELECT * FROM ".tablename($this->systemtable)." WHERE uniacid= ".$_W['uniacid']);
			}
			message('更新成功！', referer(), 'success');
		}
		include $this->template('system');
	}
	

	
	public function doMobileIndex() {
		global $_GPC, $_W;
 	    $user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {
			echo "亲，在微信里打开吧";
			exit;
		}
		
		$system = pdo_fetch("SELECT * FROM ".tablename($this->systemtable)." WHERE uniacid= ".$_W['uniacid']);

		include $this->template ( 'index' ); 
	}


	
	
}