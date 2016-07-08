<?php
/**
 * 捷讯公交客运模块微站定义
 *
 * @author 捷讯设计
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class J_shareModuleSite extends WeModuleSite {
	public function doMobileIndex() {
		global $_GPC, $_W;
		$rid=intval($_GPC['rid']);
		$reply=pdo_fetch("SELECT * FROM ".tablename('j_share_reply')." WHERE weid = '{$_W['uniacid']}' and rid=:rid",array(":rid"=>$rid));
		$is_number=0;
		if(is_numeric($reply['url'])){
			header("Location:tel://".$reply['url']);
			exit();
		}
		if(substr($reply['url'],0,6)=="weixin"){
			header("Location:".$reply['url']);
			exit();
		}
		include $this->template('index');
	}
	
	public function doWebManage() {
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if($operation=="display"){
			if (checksubmit('submit')) {
				if(!pdo_fieldexists('mc_mapping_fans', 'unionid')) {
					pdo_query("ALTER TABLE ".tablename('mc_mapping_fans')." ADD `unionid` varchar(255) NOT NULL COMMENT '联动';");
				}
				$syncfile=IA_ROOT.'/web/source/utility/sync.ctrl.php';
				$fansfile=IA_ROOT.'/web/source/mc/fans.ctrl.php';
				$new_syncfile=IA_ROOT.'/addons/j_share/temp/sync.ctrl-'.IMS_VERSION.'.php';
				$new_fansfile=IA_ROOT.'/addons/j_share/temp/fans.ctrl-'.IMS_VERSION.'.php';
				if(file_exists($syncfile))unlink($syncfile);
				if(file_exists($fansfile))unlink($fansfile);
				if(file_exists($syncfile) || file_exists($fansfile))message("您的系统无法修改文件。请使用手动模式");
				copy($new_syncfile,$syncfile);
				copy($new_fansfile,$fansfile);
				if(!file_exists($syncfile) || !file_exists($fansfile))message("您的系统无法修改文件。请使用手动模式");
				message("修改完成！请到【粉丝营销】-【粉丝】同步或者下载所有粉丝数据。","./index.php?c=mc&a=fans","success");
			}
		}
		include $this->template('manage');
	}
}