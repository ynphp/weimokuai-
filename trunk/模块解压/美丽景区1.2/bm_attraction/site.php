<?php
/**
 * 
 *
 * 
 */
defined('IN_IA') or exit('Access Denied');
class bm_attractionModuleSite extends WeModuleSite {
    public $weid;
    public function __construct() {
        global $_W;
        $this->weid = IMS_VERSION<0.6?$_W['weid']:$_W['uniacid'];
    }
	
	public function doWebSlide() {
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		print_r($_W['weid']);
		print_r('ok');
		if(checksubmit('submit')) {
			print_r('0');
			if (!empty($_GPC['slide-new'])) {
				print_r('1');
				foreach ($_GPC['slide-new'] as $index => $row) {
					if (empty($row)) {
						continue;
					}
					$data = array(
						'weid' => $weid,
						'hs_pic' => $_GPC['slide-new'][$index],
					);
					print_r($data);
					pdo_insert('bm_attraction_slide', $data);
				}
			}
			if (!empty($_GPC['attachment'])) {
				print_r('1');
				foreach ($_GPC['attachment'] as $index => $row) {
					if (empty($row)) {
						continue;
					}
					$data = array(
						'weid' => $weid,
						'hs_pic' => $_GPC['attachment'][$index],
					);
					print_r($data);
					pdo_update('bm_attraction_slide', $data, array('id' => $index));
				}
			}
			//exit;
			message('幻灯片更新成功！', $this->createWebUrl('slide'));
		}
		
		if($op=='delete'){
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM " . tablename('bm_attraction_slide') . " WHERE id = :id", array(':id' => $id));
				if (empty($item)) {
					message('图片不存在或是已经被删除！');
				}
				pdo_delete('bm_attraction_slide', array('id' => $item['id']));
			} else {
				$item['attachment'] = $_GPC['attachment'];
			}
			//file_delete($item['attachment']);
			message('删除成功！', referer(), 'success');
		}
		$photos = pdo_fetchall("SELECT * FROM " . tablename('bm_attraction_slide') . " WHERE weid = :weid", array(':weid' => $weid));
		load()->func('tpl');
		include $this->template('slide');
	}

	public function doWebClassify(){
		global $_W,$_GPC;
		load()->model('reply');
		load()->func('tpl');
		$weid=$_W['uniacid'];
		$op = !empty($_GPC['op'])?$_GPC['op']:'display';
		$departments = pdo_fetchAll("SELECT * FROM".tablename('bm_attraction_reply')." WHERE weid='{$weid}'");
		//print_r($departments);exit;
		if ($op == 'post') {
			if (!empty($_GPC['id'])) {
				$item = pdo_fetch("SELECT * FROM".tablename('bm_attraction_classify')." WHERE id='{$_GPC['id']}' order by sort desc");
			}
			
			$data = array(
				'weid'          => $weid,
				'sort'          => intval($_GPC['sort']),
				'spottitle'    => $_GPC['spottitle'],
				'department_id' => $_GPC['department_id'],
				'spotdesc'         => $_GPC['spotdesc'],
				'spotpic'    => $_GPC['spotpic'],
				'spotinfo'      => htmlspecialchars_decode($_GPC['spotinfo']),
				'spotrecord'    => $_GPC['spotrecord'],
				'spotcolor'    => $_GPC['spotcolor'],
				'spottime'    => $_GPC['spottime'],
				'spotdistance'    => $_GPC['spotdistance'],	
				'spotsmallpic'    => $_GPC['spotsmallpic'],
			);
			if ($_W['ispost']) {
				if (empty($_GPC['id'])) {
					pdo_insert('bm_attraction_classify',$data);
				}else{
					//print_r($data);exit;
					pdo_update('bm_attraction_classify',$data,array("id" => $_GPC['id']));
				}
				message("更新成功",referer(),'success');
			}
		}elseif( $op == 'display'){
			$classify = pdo_fetchAll("SELECT * FROM".tablename('bm_attraction_classify')." WHERE weid='{$weid}' order by sort desc");
			$list = array();
			foreach ($classify as $key => $value) {
				$list[$key]['id'] = $value['id'];
				$list[$key]['sort'] = $value['sort'];
				$list[$key]['spottitle'] = $value['spottitle'];
				$departments = pdo_fetch("SELECT * FROM".tablename('bm_attraction_reply')." WHERE id='{$value['department_id']}'");
				$list[$key]['department'] = $departments['department'];
			}
		}elseif( $op == 'delete'){
			pdo_delete("bm_attraction_classify",array('id' => $_GPC['id'] ));
			message("删除成功",referer(),'success');
		}
		
		include $this->template('classify');
	}
	
	public function doWebOther(){
		global $_W,$_GPC;
		load()->model('reply');
		load()->func('tpl');
		$weid=$_W['uniacid'];
		$op = !empty($_GPC['op'])?$_GPC['op']:'display';
		$departments = pdo_fetchAll("SELECT * FROM".tablename('bm_attraction_reply')." WHERE weid='{$weid}'");
		//print_r($departments);exit;
		if ($op == 'post') {
			if (!empty($_GPC['id'])) {
				$item = pdo_fetch("SELECT * FROM".tablename('bm_attraction_other')." WHERE id='{$_GPC['id']}' order by sort desc");
			}
			
			$data = array(
				'weid'          => $weid,
				'sort'          => intval($_GPC['sort']),
				'spottitle'    => $_GPC['spottitle'],
				'department_id' => $_GPC['department_id'],
				'spotdesc'         => $_GPC['spotdesc'],
				'spotpic'    => $_GPC['spotpic'],
				'spotinfo'      => htmlspecialchars_decode($_GPC['spotinfo']),
				'spotrecord'    => $_GPC['spotrecord'],
				'spotcolor'    => $_GPC['spotcolor'],
				'spottime'    => $_GPC['spottime'],
				'spotdistance'    => $_GPC['spotdistance'],	
				'spotsmallpic'    => $_GPC['spotsmallpic'],
			);
			if ($_W['ispost']) {
				if (empty($_GPC['id'])) {
					pdo_insert('bm_attraction_other',$data);
				}else{
					//print_r($data);exit;
					pdo_update('bm_attraction_other',$data,array("id" => $_GPC['id']));
				}
				message("更新成功",referer(),'success');
			}
		}elseif( $op == 'display'){
			$other = pdo_fetchAll("SELECT * FROM".tablename('bm_attraction_other')." WHERE weid='{$weid}' order by sort desc");
			$list = array();
			foreach ($other as $key => $value) {
				$list[$key]['id'] = $value['id'];
				$list[$key]['sort'] = $value['sort'];
				$list[$key]['spottitle'] = $value['spottitle'];
				$departments = pdo_fetch("SELECT * FROM".tablename('bm_attraction_reply')." WHERE id='{$value['department_id']}'");
				$list[$key]['department'] = $departments['department'];
			}
		}elseif( $op == 'delete'){
			pdo_delete("bm_attraction_other",array('id' => $_GPC['id'] ));
			message("删除成功",referer(),'success');
		}
		
		include $this->template('other');
	}
	
	public function doMobileIndex(){
		global $_W,$_GPC;
		$weid=$_W['uniacid'];
		$id = intval($_GPC['id']);
		//print_r($id);print_r('jjj');print_r($weid);exit;
		$slides = pdo_fetchall("select * from ".tablename('bm_attraction_slide')." where weid = ".$weid);
		//print_r($slides);exit;
		$title = pdo_fetchcolumn("SELECT department FROM".tablename('bm_attraction_reply')."WHERE weid = :weid and id = :id", array(':id' => $id,':weid' => $weid));
		$picurl = pdo_fetchcolumn("SELECT picurl FROM".tablename('bm_attraction_reply')."WHERE weid = :weid and id = :id", array(':id' => $id,':weid' => $weid));
		$telephone = pdo_fetchcolumn("SELECT telephone FROM".tablename('bm_attraction_reply')."WHERE weid = :weid and id = :id", array(':id' => $id,':weid' => $_W['weid']));
		$spoturl = pdo_fetchcolumn("SELECT spoturl FROM".tablename('bm_attraction_reply')."WHERE weid = :weid and id = :id", array(':id' => $id,':weid' => $_W['weid']));
		$classify = pdo_fetchAll("SELECT * FROM ".tablename('bm_attraction_classify')." WHERE weid='{$weid}' AND department_id='{$id}' order by sort desc");		
		$sum = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('bm_attraction_classify')." WHERE weid='{$weid}' AND department_id='{$id}' order by sort desc");		
		$other = pdo_fetchAll("SELECT * FROM ".tablename('bm_attraction_other')." WHERE weid='{$weid}' AND department_id='{$id}' order by sort desc");		
		include $this->template('index');
	}
	public function doMobilegonglue(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);		
		$detail = pdo_fetch("SELECT * FROM".tablename('bm_attraction_reply')."WHERE id='{$_GPC['id']}'");
		include $this->template('gonglue');
	}
	public function doMobileimageinfo(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);		
		$detail = pdo_fetch("SELECT * FROM".tablename('bm_attraction_reply')."WHERE id='{$_GPC['id']}'");
		include $this->template('imageinfo');
	}
	public function doMobilemapinfo(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);		
		$detail = pdo_fetch("SELECT * FROM".tablename('bm_attraction_reply')."WHERE id='{$_GPC['id']}'");
		include $this->template('mapinfo');
	}	
	public function doMobiledetail(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);		
		$detail = pdo_fetch("SELECT * FROM".tablename('bm_attraction_reply')."WHERE id='{$_GPC['id']}'");
		include $this->template('detail');
	}		
	public function doMobilestart(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);		
		$detail = pdo_fetch("SELECT * FROM".tablename('bm_attraction_reply')."WHERE id='{$_GPC['id']}'");
		include $this->template('start');
	}	
	public function doMobilespotdetail(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);		
		$detail = pdo_fetch("SELECT * FROM".tablename('bm_attraction_classify')."WHERE id='{$_GPC['id']}'");
		include $this->template('spotdetail');
	}
	public function doMobilespotrecordplay(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);		
		$detail = pdo_fetch("SELECT * FROM".tablename('bm_attraction_classify')."WHERE id='{$_GPC['id']}'");
		include $this->template('spotrecordplay');
	}
	public function doMobileothersdetail(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);		
		$detail = pdo_fetch("SELECT * FROM".tablename('bm_attraction_other')."WHERE id='{$_GPC['id']}'");
		include $this->template('spotdetail');
	}
	public function doMobileotherrecordplay(){
		global $_GPC,$_W;
		$id = intval($_GPC['id']);		
		$detail = pdo_fetch("SELECT * FROM".tablename('bm_attraction_other')."WHERE id='{$_GPC['id']}'");
		include $this->template('spotrecordplay');
	}	
	public  function  doMobileAjaxdelete()
	{
		global $_GPC;
		$delurl = $_GPC['pic'];
		if(file_delete($delurl))
		{echo 1;}
		else 
		{echo 0;}
	}	
}