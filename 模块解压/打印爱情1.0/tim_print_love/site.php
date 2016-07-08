<?php
/**
 * Timfan design模块处理程序
 *
 * @author Tim Fan
 * QQ:1026073477
 * @url http://i-fanr.com/
 */
defined('IN_IA') or exit('Access Denied');

class Tim_print_loveModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W,$_GPC;
		$uniacid =$_W['uniacid'];
		$info = pdo_fetch("SELECT * FROM ".tablename('tim_print_love'). " WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
		include $this->template('index');
	}
	public function doMobileCover1() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$uniacid =$_W['uniacid'];
		$info = pdo_fetch("SELECT * FROM ".tablename('tim_print_love'). " WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
		include $this->template('index');
	}
	
	public function doWebSet() {
		global $_W,$_GPC;
		$uniacid = $_W['uniacid'];
		load()->func('tpl');
		$info = pdo_fetch("SELECT * FROM ".tablename('tim_print_love'). " WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
		if(checksubmit('submit')){ 
				$title = $_GPC['title'];
				$text_img1 = $_GPC['text_img1'];
				$text_img2 = $_GPC['text_img2'];
				$text_img3 = $_GPC['text_img3'];
				$text_img4 = $_GPC['text_img4'];
				$text_img5 = $_GPC['text_img5'];
				$text_img6 = $_GPC['text_img6'];
				$vedio_link = $_GPC['vedio_link'];
				$share_content = $_GPC['share_content'];
				$share_title = $_GPC['share_title'];
				$share_icon = $_GPC['share_icon'];
				$infos = array(
					'uniacid' => $uniacid,
					'title' => $title,
					'text_img1' => $text_img1,
					'text_img2' => $text_img2,
					'text_img3' => $text_img3,
					'text_img4' => $text_img4,
					'text_img5' => $text_img5, 
					'text_img6' => $text_img6,
					'vedio_link' => $vedio_link,    
					'share_content' => $share_content, 
					'share_title' => $share_title, 
					'share_icon' => $share_icon
				);
				if(empty($info)){
						pdo_insert('tim_print_love', $infos);//添加数据
						message('数据添加成功！', $this->createWebUrl('set'), 'success');
				}else{
						$id = $info['id'];
						pdo_update('tim_print_love', $infos, array('id' => $id));
						message('数据更新成功！', $this->createWebUrl('set'), 'success');
				}	
		}
		include $this->template('param_set');
	}

}
