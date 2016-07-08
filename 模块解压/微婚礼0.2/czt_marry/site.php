<?php
/**
 * 微婚礼模块微站定义
 *
 * @author czt
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_marryModuleSite extends WeModuleSite {

	public function doMobileMarry() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		if ($id) {
			$item = pdo_fetch("SELECT * FROM " . tablename('czt_marry') . " WHERE id = :id", array(':id' => $id));
			if (!empty($item)) {
				$item['album']=unserialize($item['album']);
				if (empty($item['bg'])) {
					$item['bg']=MODULE_URL.'template/mobile/img/bg.jpg';
				}else{
					$item['bg']=$_W['attachurl'].$item['bg'];
				}
				if (empty($item['s_icon'])) {
					$item['s_icon']=MODULE_URL.'icon.jpg';
				}else{
					$item['s_icon']=$_W['attachurl'].$item['s_icon'];
				}
				if (empty($item['s_title'])) {
					$item['s_title']=$item['title'];
				}
				$item['info']=htmlspecialchars_decode($item['info']);
				$coordinate=explode('|', $item['coordinate']);
				$item['coordinate']=array();
				if (count($coordinate)==2) {
					$item['coordinate']['lng']=$coordinate[0];
					$item['coordinate']['lat']=$coordinate[1];
				}else{
					$item['coordinate']['lng']='';
					$item['coordinate']['lat']='';
				}
				include $this->template('index');
			}
		}
		
	}
	public function doWebList() {
		global $_GPC,  $_W;
		$uniacid=$_W["uniacid"];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$where ="";
		$list = pdo_fetchall("SELECT *  from ".tablename('czt_marry')." where uniacid='{$uniacid}' $where order by id desc LIMIT ". ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename('czt_marry')." where uniacid='{$uniacid}' $where order by id asc");
		$pager = pagination($total, $pindex, $psize);
		load()->func('tpl');
		include $this->template('list');
	}
	public function doWebAdd() {
		global $_GPC,  $_W;
		load()->func('tpl');
		$id = intval($_GPC['id']);
		if (!empty($id)) {
			$item = pdo_fetch("SELECT * FROM ".tablename('czt_marry')." WHERE id = :id" , array(':id' => $id));
			if (empty($item)) {
				message('抱歉，婚礼不存在或是已经删除！', '', 'error');
			}
			$item['album']=unserialize($item['album']);
			$coordinate=explode('|', $item['coordinate']);
			$item['coordinate']=array();
			if (count($coordinate)==2) {
				$item['coordinate']['lng']=$coordinate[0];
				$item['coordinate']['lat']=$coordinate[1];
			}else{
				$item['coordinate']['lng']='';
				$item['coordinate']['lat']='';
			}
		}
		if(checksubmit('submit')) {
			$data = array(
				'uniacid'	=>	$_W['uniacid'],
				'title'	    =>	$_GPC['title'],
				'date'	    =>	strtotime($_GPC['date']),
				's_title'   =>	$_GPC['s_title'],
				's_des'		=>	$_GPC['s_des'],
				's_icon'		=>	$_GPC['s_icon'],
				'album'		=>	serialize($_GPC['album']),
				'info'      =>	$_GPC['info'],
				'coordinate'=>  $_GPC['coordinate']['lng'].'|'.$_GPC['coordinate']['lat'],
				'bg'	    =>	$_GPC['bg'],
				'music'  	=>	$_GPC['music'],
				'pic'       =>	$_GPC['pic'],			
				);
			if (empty($id)) {
				pdo_insert('czt_marry', $data);
				message('添加成功！', $this->createWebUrl('Add',array('id'=>pdo_insertid())), 'success');
			}else{
				pdo_update('czt_marry', $data, array('id' => $id));
				message('修改成功！', referer(), 'success');
			}
		}
		include $this->template('add');
	}
	public function doWebDelete(){
		global $_GPC, $_W;
		if(!empty($_GPC['id'])){
			pdo_delete('czt_marry', array('id' => $_GPC['id'],'uniacid'=> $_W['uniacid']));	
			message('删除成功', referer(), 'success');	
		}
		if (!empty($_GPC['Deleteall']) && !empty($_GPC['select'])) {
			foreach ($_GPC['select'] as $k => $v) {
				pdo_delete('czt_marry', array('id' => $v,'uniacid'=> $_W['uniacid']));	
			}
			message('删除成功', referer(), 'success');
		}
	}
	public function doWebSearch() {
		global $_W, $_GPC;
		$kw = $_GPC['keyword'];
		$sql = 'SELECT `id`,`date`,`s_title`,`title`,`s_des`,`pic` FROM ' . tablename('czt_marry') . ' WHERE `uniacid`=:uniacid AND `title` LIKE :title order by id desc';
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':title'] = "%{$kw}%";
		$list = pdo_fetchall($sql, $params);
		die(json_encode($list));
	}
}