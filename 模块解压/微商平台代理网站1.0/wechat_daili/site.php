<?php
defined('IN_IA') or exit('Access Denied');

class Wechat_dailiModuleSite extends WeModuleSite {

	public function doMobileFm() {
	global $_W,$_GPC;
	$dailishopurl = $_W['siteroot']."addons/wechat_daili/";
	$topimg =tomedia($this->module['config']['img']);
	$footerimg =tomedia($this->module['config']['eimg']);
	$adimg1 =tomedia($this->module['config']['img1']);
	$adimg2 =tomedia($this->module['config']['img2']);
	$adimg3 =tomedia($this->module['config']['img3']);
	$adimg4 =tomedia($this->module['config']['img4']);
	$adimg5 =tomedia($this->module['config']['img5']);
	$adimg6 =tomedia($this->module['config']['img6']);
	$product0 = $this->module['config']['product0'];
	$product1 = $this->module['config']['product1'];
	$product1s = $this->module['config']['product1s'];
	$product1m = $this->module['config']['product1m'];
	$product2 = $this->module['config']['product2'];
	$product2s = $this->module['config']['product2s'];
	$product2m = $this->module['config']['product2m'];
	$product3 = $this->module['config']['product3'];
	$product3s = $this->module['config']['product3s'];
	$product3m = $this->module['config']['product3m'];
	$product4 = $this->module['config']['product4'];
	$product4s = $this->module['config']['product4s'];
	$product4m = $this->module['config']['product4m'];
	$product5 = $this->module['config']['product5'];
	$product5s = $this->module['config']['product5s'];
	$product5m = $this->module['config']['product5m'];
	$product6 = $this->module['config']['product6'];
	$product6s = $this->module['config']['product6s'];
	$product6m = $this->module['config']['product6m'];
	$product7 = $this->module['config']['product7'];
	$product7s = $this->module['config']['product7s'];
	$product7m = $this->module['config']['product7m'];
	$product8 = $this->module['config']['product8'];
	$product8s = $this->module['config']['product8s'];
	$product8m = $this->module['config']['product8m'];
	$ptitle = $this->module['config']['title'];
	$desc = $this->module['config']['desc'];
	$desd = $this->module['config']['desd'];
	$uuu = $this->module['config']['uuu'];
	$usina = $this->module['config']['usina'];
	$kefuq1 = $this->module['config']['kefuq1'];
	$kefuq2 = $this->module['config']['kefuq2'];
	$kefuq3 = $this->module['config']['kefuq3'];
	$kefutel = $this->module['config']['kefutel'];
	$kefuad = $this->module['config']['kefuad'];
	$weid = $_W['uniacid'];
	$pindex = max(1, intval($_GPC['page']));
			$psize =10;//每页面10条
			$condition = '';
			if (!empty($_GPC['keyword'])) {
	$condition .= " AND (title LIKE '%".$_GPC['keyword']."%' "." OR jianjie LIKE '%".$_GPC['keyword']."%') ";
			}
			$list = pdo_fetchall("SELECT *  FROM ".tablename('wechat_daili')." WHERE isok=1 and weid =".$weid.$condition." ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechat_daili') . " WHERE isok=1  and  weid =".$weid.$condition);
			$pager = pagination($total, $pindex, $psize,$url = '', $context = array('before' =>0, 'after' =>0));
		$sql = "SELECT *  FROM ".tablename('wechat_daili')." WHERE isok=1 and weid =".$weid.$condition;
			$top = pdo_fetchall("SELECT *  FROM ".tablename('wechat_dailitop')." WHERE isok=1 and weid =".$weid.$condition." ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$topal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechat_dailitop') . " WHERE isok=1  and  weid =".$weid.$condition);
			$paper = pagination($total, $pindex, $psize,$url = '', $context = array('before' =>0, 'after' =>0));
		$sql = "SELECT *  FROM ".tablename('wechat_dailitop')." WHERE isok=1 and weid =".$weid.$condition;
			$coop = pdo_fetchall("SELECT *  FROM ".tablename('wechat_dailicoop')." WHERE isok=1 and weid =".$weid.$condition." ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$coopal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechat_dailicoop') . " WHERE isok=1  and  weid =".$weid.$condition);
			$cooper = pagination($total, $pindex, $psize,$url = '', $context = array('before' =>0, 'after' =>0));
		$sql = "SELECT *  FROM ".tablename('wechat_dailicoop')." WHERE isok=1 and weid =".$weid.$condition;
		include $this->template('index');	
	}	
	public function doWebDailishop() {
		global $_W,$_GPC;
		if(defined('SAE')){
	load()->func('filesae');
	}else{
	load()->func('file');
	}
		load()->func('tpl');
		$dailishopurl = $_W['siteroot']."addons/wechat_daili/";
		$weid = $_W['uniacid'];
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		
		if('post' == $op){//添加或修改
			$id = intval($_GPC['id']);
			if(!empty($id)){
			$item = pdo_fetch("SELECT * FROM ".tablename('wechat_daili')." where id=$id");
			empty($item)?message('亲,数据不存在！', '', 'error'):"";	
			}
			
			
			if(checksubmit('submit')){
				empty ($_GPC['title'])?message('亲,账号必填'):$title =$_GPC['title'];
				empty ($_GPC['url'])?message('亲,密码必填'):$url =$_GPC['url'];
				$jianjie = $_GPC['jianjie'];
				$logo = $_GPC['logo'];
				$isok =1;
				if(empty($id)){
						pdo_insert('wechat_daili', array('title'=>$title,'money'=>$money,'url'=>$url,'jianjie'=>$jianjie,'logo'=>$logo,'isok'=>$isok,'weid'=>$weid));//添加数据
						message('添加成功！', $this->createWebUrl('dailishop', array('op' => 'display')), 'success');
				}else{
						pdo_update('wechat_daili', array('title'=>$title,'money'=>$money,'url'=>$url,'jianjie'=>$jianjie,'logo'=>$logo,'isok'=>$isok,'weid'=>$weid), array('id' => $id));
						message('更新成功！', $this->createWebUrl('dailishop', array('op' => 'display')), 'success');
				}
				
				
			}else{
				include $this->template('index');
			}
			
		}else if('del' == $op){//删除
		
		
			if(isset($_GPC['delete'])){
				$ids = implode(",",$_GPC['delete']);
				
				$row1 = pdo_fetchall("SELECT id,logo FROM ".tablename('wechat_daili')." WHERE id in(".$ids.")");
				if(!empty($row1)){
					foreach($row1 as $data1){
					if (!empty($data1['logo'])) {
			file_delete($data1['logo']);
		}	
					}
				}
				$sqls = "delete from  ".tablename('wechat_daili')."  where id in(".$ids.")"; 
				pdo_query($sqls);
				message('删除成功！', referer(), 'success');
			}
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM ".tablename('wechat_daili')." WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，操作不存在或是已经被删除！', $this->createWebUrl('dailishop', array('op' => 'display')), 'error');
			}
				if (!empty($row['logo'])) {
			file_delete($row['logo']);
		}
			pdo_delete('wechat_daili', array('id' => $id));
			message('删除成功！', referer(), 'success');
			
		}else if('display' == $op){//显示
			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND (title LIKE '%".$_GPC['keyword']."%' "." OR jianjie LIKE '%".$_GPC['keyword']."%') ";
			}			
			$list = pdo_fetchall("SELECT *  FROM ".tablename('wechat_daili') ." WHERE weid =". $weid.$condition."  ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechat_daili') ." WHERE weid =". $weid.$condition);
			$pager = pagination($total, $pindex, $psize);
			include $this->template('index');
		}else if('shenhe'==$op){
			
				$id = intval($_GPC['id']);
			$issend =( intval($_GPC['isok'])==1)?0:1;
			$data1 = array('isok'=>$issend,);
			pdo_update('wechat_daili', $data1, array('id' => $id));
			if($issend==1){
				echo json_encode(array('a'=>1));
			}else{
				echo json_encode(array('a'=>0));
			}
			
		}
	}
	public function doWebDailitop() {
		global $_W,$_GPC;
		if(defined('SAE')){
	load()->func('filesae');
	}else{
	load()->func('file');
	}
		load()->func('tpl');
		$Dailitopurl = $_W['siteroot']."addons/wechat_daili/";
		$weid = $_W['uniacid'];
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		
		if('post' == $op){//添加或修改
			$id = intval($_GPC['id']);
			if(!empty($id)){
			$item = pdo_fetch("SELECT * FROM ".tablename('wechat_dailitop')." where id=$id");
			empty($item)?message('亲,数据不存在！', '', 'error'):"";	
			}
			
			
			if(checksubmit('submit')){
				empty ($_GPC['title'])?message('亲,方案名称必填'):$title =$_GPC['title'];
				empty ($_GPC['money'])?message('亲,方案描述必填'):$money =$_GPC['money'];
				empty ($_GPC['sale'])?message('亲,联系文字必填'):$sale =$_GPC['sale'];
				empty ($_GPC['url'])?message('亲,方案链接必填'):$url =$_GPC['url'];
				$jianjie = $_GPC['jianjie'];
				$logo = $_GPC['logo'];
				$isok =1;
				if(empty($id)){
						pdo_insert('wechat_dailitop', array('title'=>$title,'money'=>$money,'sale'=>$sale,'url'=>$url,'jianjie'=>$jianjie,'logo'=>$logo,'isok'=>$isok,'weid'=>$weid));//添加数据
						message('添加成功！', $this->createWebUrl('Dailitop', array('op' => 'display')), 'success');
				}else{
						pdo_update('wechat_dailitop', array('title'=>$title,'money'=>$money,'sale'=>$sale,'url'=>$url,'jianjie'=>$jianjie,'logo'=>$logo,'isok'=>$isok,'weid'=>$weid), array('id' => $id));
						message('更新成功！', $this->createWebUrl('Dailitop', array('op' => 'display')), 'success');
				}
				
				
			}else{
				include $this->template('top');
			}
			
		}else if('del' == $op){//删除
		
		
			if(isset($_GPC['delete'])){
				$ids = implode(",",$_GPC['delete']);
				
				$row1 = pdo_fetchall("SELECT id,logo FROM ".tablename('wechat_dailitop')." WHERE id in(".$ids.")");
				if(!empty($row1)){
					foreach($row1 as $data1){
					if (!empty($data1['logo'])) {
			file_delete($data1['logo']);
		}	
					}
				}
				$sqls = "delete from  ".tablename('wechat_dailitop')."  where id in(".$ids.")"; 
				pdo_query($sqls);
				message('删除成功！', referer(), 'success');
			}
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM ".tablename('wechat_dailitop')." WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，操作不存在或是已经被删除！', $this->createWebUrl('Dailitop', array('op' => 'display')), 'error');
			}
				if (!empty($row['logo'])) {
			file_delete($row['logo']);
		}
			pdo_delete('wechat_dailitop', array('id' => $id));
			message('删除成功！', referer(), 'success');
			
		}else if('display' == $op){//显示
			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND (title LIKE '%".$_GPC['keyword']."%' "." OR jianjie LIKE '%".$_GPC['keyword']."%') ";
			}			
			$top = pdo_fetchall("SELECT *  FROM ".tablename('wechat_dailitop') ." WHERE weid =". $weid.$condition."  ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$topal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechat_dailitop') ." WHERE weid =". $weid.$condition);
			$paper = pagination($total, $pindex, $psize);
			include $this->template('top');
		}else if('shenhe'==$op){
			
				$id = intval($_GPC['id']);
			$issend =( intval($_GPC['isok'])==1)?0:1;
			$data1 = array('isok'=>$issend,);
			pdo_update('wechat_dailitop', $data1, array('id' => $id));
			if($issend==1){
				echo json_encode(array('a'=>1));
			}else{
				echo json_encode(array('a'=>0));
			}
			
		}
	}
	public function doWebDailicoop() {
		global $_W,$_GPC;
		if(defined('SAE')){
	load()->func('filesae');
	}else{
	load()->func('file');
	}
		load()->func('tpl');
		$Dailicoopurl = $_W['siteroot']."addons/wechat_daili/";
		$weid = $_W['uniacid'];
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		
		if('post' == $op){//添加或修改
			$id = intval($_GPC['id']);
			if(!empty($id)){
			$item = pdo_fetch("SELECT * FROM ".tablename('wechat_dailicoop')." where id=$id");
			empty($item)?message('亲,数据不存在！', '', 'error'):"";	
			}
			
			
			if(checksubmit('submit')){
				empty ($_GPC['title'])?message('亲,合作单位名称必填'):$title =$_GPC['title'];
				empty ($_GPC['money'])?message('亲,合作描述必填'):$money =$_GPC['money'];
				empty ($_GPC['sale'])?message('亲,联系电话必填'):$sale =$_GPC['sale'];
				empty ($_GPC['url'])?message('亲,链接必填'):$url =$_GPC['url'];
				$jianjie = $_GPC['jianjie'];
				$logo = $_GPC['logo'];
				$isok =1;
				if(empty($id)){
						pdo_insert('wechat_dailicoop', array('title'=>$title,'money'=>$money,'sale'=>$sale,'url'=>$url,'jianjie'=>$jianjie,'logo'=>$logo,'isok'=>$isok,'weid'=>$weid));//添加数据
						message('添加成功！', $this->createWebUrl('Dailicoop', array('op' => 'display')), 'success');
				}else{
						pdo_update('wechat_dailicoop', array('title'=>$title,'money'=>$money,'sale'=>$sale,'url'=>$url,'jianjie'=>$jianjie,'logo'=>$logo,'isok'=>$isok,'weid'=>$weid), array('id' => $id));
						message('更新成功！', $this->createWebUrl('Dailicoop', array('op' => 'display')), 'success');
				}
				
				
			}else{
				include $this->template('coop');
			}
			
		}else if('del' == $op){//删除
		
		
			if(isset($_GPC['delete'])){
				$ids = implode(",",$_GPC['delete']);
				
				$row1 = pdo_fetchall("SELECT id,logo FROM ".tablename('wechat_dailicoop')." WHERE id in(".$ids.")");
				if(!empty($row1)){
					foreach($row1 as $data1){
					if (!empty($data1['logo'])) {
			file_delete($data1['logo']);
		}	
					}
				}
				$sqls = "delete from  ".tablename('wechat_dailicoop')."  where id in(".$ids.")"; 
				pdo_query($sqls);
				message('删除成功！', referer(), 'success');
			}
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM ".tablename('wechat_dailicoop')." WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，操作不存在或是已经被删除！', $this->createWebUrl('Dailicoop', array('op' => 'display')), 'error');
			}
				if (!empty($row['logo'])) {
			file_delete($row['logo']);
		}
			pdo_delete('wechat_dailicoop', array('id' => $id));
			message('删除成功！', referer(), 'success');
			
		}else if('display' == $op){//显示
			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND (title LIKE '%".$_GPC['keyword']."%' "." OR jianjie LIKE '%".$_GPC['keyword']."%') ";
			}			
			$coop = pdo_fetchall("SELECT *  FROM ".tablename('wechat_dailicoop') ." WHERE weid =". $weid.$condition."  ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$coopal = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechat_dailicoop') ." WHERE weid =". $weid.$condition);
			$cooper = pagination($total, $pindex, $psize);
			include $this->template('coop');
		}else if('shenhe'==$op){
			
				$id = intval($_GPC['id']);
			$issend =( intval($_GPC['isok'])==1)?0:1;
			$data1 = array('isok'=>$issend,);
			pdo_update('wechat_dailicoop', $data1, array('id' => $id));
			if($issend==1){
				echo json_encode(array('a'=>1));
			}else{
				echo json_encode(array('a'=>0));
			}
			
		}
	}
	public function doWebDailimob() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		include $this->template('mob');
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
	$dailishopurl = $_W['siteroot']."addons/wechat_daili/";
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
	 pdo_insert('wechat_daili', $data);
	die('<script>alert("申请成功,请等待审核!");location.href="'.$this->createMobileUrl('fm').'"</script>');
	}else{
	include $this->template('fabu');		
	}
	}

}
