<?php
defined('IN_IA') or exit('Access Denied');

class Wechat_dialogueModuleSite extends WeModuleSite {

	public function doMobileFm() {
	global $_W,$_GPC;
	$renrenshopurl = $_W['siteroot']."addons/wechat_dialogue/";
	$topimg =tomedia($this->module['config']['img']);
	$ptitle = $this->module['config']['title'];
	$desc = $this->module['config']['desc'];
	$uuu = $this->module['config']['uuu'];
	$kefuq1 = $this->module['config']['kefuq1'];
	$kefuq2 = $this->module['config']['kefuq2'];
	$kefuq3 = $this->module['config']['kefuq3'];
	$kefutel = $this->module['config']['kefutel'];
	$weid = $_W['uniacid'];
	$pindex = max(1, intval($_GPC['page']));
			$psize =10;//每页面10条
			$condition = '';
			if (!empty($_GPC['keyword'])) {
	$condition .= " AND (title LIKE '%".$_GPC['keyword']."%' "." OR jianjie LIKE '%".$_GPC['keyword']."%') ";
			}
			$list = pdo_fetchall("SELECT *  FROM ".tablename('wechat_dialogue')." WHERE isok=1 and weid =".$weid.$condition." ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechat_dialogue') . " WHERE isok=1  and  weid =".$weid.$condition);
			$pager = pagination($total, $pindex, $psize,$url = '', $context = array('before' =>0, 'after' =>0));
		$sql = "SELECT *  FROM ".tablename('wechat_dialogue')." WHERE isok=1 and weid =".$weid.$condition;
			$top = pdo_fetchall("SELECT *  FROM ".tablename('wechat_dialogue')." WHERE isok=1 and weid =".$weid.$condition." ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$topal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechat_rentop') . " WHERE isok=1  and  weid =".$weid.$condition);
			$paper = pagination($total, $pindex, $psize,$url = '', $context = array('before' =>0, 'after' =>0));
		$sql = "SELECT *  FROM ".tablename('wechat_dialogue')." WHERE isok=1 and weid =".$weid.$condition;
		include $this->template('index');	
	}	
	public function doWebXiumi() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		include $this->template('index');
	}
	public function doWebXiups() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		include $this->template('ps');
	}
	public function doWebXiutu() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		include $this->template('tu');
	}
	public function doWebXiuzazhi() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		include $this->template('zazhi');
	}
	public function doMobileDh() {

header("location:".$_W['siteroot'].$this->createMobileUrl('fm'));
	}

public function doMobileFabu() {
	global $_W,$_GPC;
	if(defined('SAE')){
	load()->func('filesae');
	}else{
	load()->func('file');
	}
	$renrenshopurl = $_W['siteroot']."addons/wechat_dialogue/";
	$weid = $_W['uniacid'];
	if(checksubmit('submit')){
	empty ($_GPC['title'])?message('亲,名称必填'):$title =$_GPC['title'];
				empty ($_GPC['url'])?message('亲,链接必填'):$url =$_GPC['url'];
				$des = $_GPC['des'];
				$isok =0;
			if (!empty($_FILES['pic']['tmp_name'])) {
					$upload = file_upload($_FILES['pic'],'image');
					if (is_error($upload)) {
						message('上传出错', '', 'error');
					}
					$img = $upload['url'];
				}

	$data = array(
	'weid'=>$weid,
	'title'=>$title,
	'url'=>$url,
	'jianjie'=>$des,
	'logo'=>$img,
	'isok'=>$isok,
	);
	 pdo_insert('wechat_dialogue', $data);
	die('<script>alert("申请成功,请等待审核!");location.href="'.$this->createMobileUrl('fm').'"</script>');
	}else{
	include $this->template('fabu');		
	}
	}

}
