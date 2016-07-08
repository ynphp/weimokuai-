<?php
/**
 * 摇一摇测财运模块微站定义
 *
 * @author DK
 * @url http://www.dakangblog.com
 */
defined('IN_IA') or exit('Access Denied');

class Dk_yaoModuleSite extends WeModuleSite {

	
	public function doWebSet() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		load()->func('tpl');
		$sql = "SELECT * FROM" .tablename('dk_yao');
		$accounts = pdo_fetch($sql);
		
		if(checksubmit()){
			$content['id'] = $accounts['id'];
			$content['img'] = $_GPC['img'];
			$content['link'] = $_GPC['link'];
			pdo_run("UPDATE  ".tablename('dk_yao')." SET  `img` =  '".$content['img']."',`link` =  '".$content['link']."' WHERE  `id` =".$content['id']);
			message('修改成功！',$this->createWebUrl('Set',array()),'success'); 
		}
		include $this->template('set');
	}

}