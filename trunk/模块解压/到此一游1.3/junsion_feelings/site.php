<?php
/**
 * 情感之窗模块微站定义
 *
 * @author junsion
 * @url http://s.we7.cc/index.php?c=store&a=author&uid=74516
 */
defined('IN_IA') or exit('Access Denied');

class Junsion_feelingsModuleSite extends WeModuleSite {

	public function doWebIndex() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$op = $_GPC['op'];
		$id = $_GPC['id'];
		if ($op == 'post'){
			if (checksubmit('submit')){
				$picurl = $_GPC['picurl'];
				$sliders = array();
				foreach ($picurl as $key => $value) {
					if (!empty($value)){
						$sliders[] = array(
									'picurl'=>$value,
									'displayorder'=>$_GPC['displayorder'][$key],
									'link'=>$_GPC['link'][$key],
							);
					}
				}
				$btns = array();
				$btitle = $_GPC['btitle'];
				foreach ($btitle as $key => $value) {
					if (!empty($value)){
						$btns[] = array(
								'title'=>$value,
								'url'=>$_GPC['burl'][$key],
						);
					}
				}
				$data = array(
					'weid'=>$_W['uniacid'],
					'title'=>$_GPC['title'],
					'defaultImg'=>$_GPC['defaultImg'],
					'type'=>$_GPC['type'],
					'slideH'=>$_GPC['slideH'],
					'bgcolor'=>$_GPC['bgcolor'],
					'checked1'=>$_GPC['checked1'],
					'checked2'=>$_GPC['checked2'],
					'sliders'=>serialize($sliders),
					'btns'=>serialize($btns),
				);
				if ($id){
					if (pdo_update($this->modulename."_rule",$data,array('id'=>$id)) === false){
						message('保存失败');
					}else{
						pdo_delete($this->modulename."_qrcode",array('rid'=>$id));
						$qrcodes = explode(';', $_GPC['qrcode']);
						foreach ($qrcodes as $k => $value) {
							if (empty($value)) continue;
							pdo_insert($this->modulename."_qrcode",array('rid'=>$id,'title'=>$value,'qid'=>$k+1));
						}
						message('保存成功',$this->createWebUrl('index'));
					}
				}else{
					$data['createtime'] = time();
					if (pdo_insert($this->modulename."_rule",$data) === false){
						message('保存失败');
					}else{
						$id = pdo_insertid();
						$qrcodes = explode(';', $_GPC['qrcode']);
						foreach ($qrcodes as $value) {
							if (empty($value)) continue;
							pdo_insert($this->modulename."_qrcode",array('rid'=>$id,'title'=>$value,'qid'=>$k+1));
						}
						message('保存成功',$this->createWebUrl('index'));
					}
				}
			}
			$item = pdo_fetch('select * from '.tablename($this->modulename."_rule")." where id='{$id}'");
			if ($item){
				$qrs = pdo_fetchall('select title from '.tablename($this->modulename."_qrcode")." where rid='{$id}' order by qid",array(),'title');
				$item['qrcode'] = implode(';', array_keys($qrs));
				$slider = unserialize($item['sliders']);
			}
		}elseif ($op == 'qrcode'){
			$qs = pdo_fetchall('select * from '.tablename($this->modulename."_qrcode")." where rid='{$id}'");
			if (empty($qs)) message('二维码标识不存在');
			include 'phpqrcode.php';
			header('Content-type:charset=utf-8');
			foreach($qs as $val){
				$q = "../addons/".$this->modulename."/qrcode/{$id}_{$val['title']}.png";
				$url = $_W['siteroot'].$this->createMobileUrl('qrcode', array('qid'=>$val['qid'],'rid'=>$id));
				$errorCorrectionLevel = "L";
				$matrixPointSize = "110";
				QRcode::png($url, $q, $errorCorrectionLevel, $matrixPointSize);
				$qrs .= $q .",";
			}
			//打包下载
			include 'pclzip.lib.php';
			$archive = new PclZip('qrcode.zip');
			$v_list = $archive->create($qrs,PCLZIP_OPT_REMOVE_ALL_PATH);
			$fileres = file_get_contents('qrcode.zip');
			header('Content-type: x-zip-compressed; charset=utf-8');
			header("Content-Type:application/download");
			header('Content-Disposition:attachment;filename="到此一游.zip"');
			echo $fileres;
			@unlink($fileres);
		}else{
			$list = pdo_fetchall('select *,(select count(1) from '.tablename($this->modulename."_record").' where rid=r.id) counts from '.tablename($this->modulename."_rule")." r where r.weid='{$_W['uniacid']}'");
		}
		
		include $this->template('index');
	}
	
	public function doWebQrcode(){
		global $_W,$_GPC;
		$rid = $_GPC['rid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$list = pdo_fetchall('select *,(select count(1) from '.tablename($this->modulename."_record").' where qid=q.qid and rid='.$rid.') counts from '.tablename($this->modulename."_qrcode")." q where q.rid='{$rid}' order by qid LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename."_qrcode") . " where rid='{$rid}' ");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('qrcode');
	}
	
	public function doWebDel(){
		global $_W,$_GPC;
		$type = $_GPC['type'];
		$rid = $_GPC['rid'];
		if ($type == 1){
			$qrs = pdo_fetch('select * from '.tablename($this->modulename."_qrcode")." where id='{$_GPC['qid']}'");
			pdo_delete($this->modulename."_qrcode",array('id'=>$_GPC['qid']));
			@unlink("../addons/".$this->modulename."/qrcode/{$rid}_{$qrs['title']}.png");
			$re = pdo_fetchall('select * from '.tablename($this->modulename."_record")." where rid={$rid} and qid={$qrs['qid']}");
			foreach ($re as $value) {
				pdo_delete($this->modulename."_record",array('id'=>$value['id']));
				pdo_delete($this->modulename."_comment",array('reid'=>$value['id']));
			}
			rmdir("../addons/".$this->modulename."/upload/{$rid}/{$qrs['qid']}");
			message('删除成功！',$this->createWebUrl('qrcode',array('rid'=>$rid)));
		}elseif ($type == 2){
			pdo_delete($this->modulename."_record",array('id'=>$_GPC['reid']));
			pdo_delete($this->modulename."_comment",array('reid'=>$_GPC['reid']));
			message('删除成功！',$this->createWebUrl('player',array('rid'=>$rid,'qid'=>$_GPC['qid'])));
		}elseif ($type == 3){
			pdo_delete($this->modulename."_comment",array('id'=>$_GPC['cid']));
			message('删除成功！',$this->createWebUrl('comment',array('reid'=>$_GPC['reid'],'rid'=>$rid,'qid'=>$_GPC['qid'])));
		}elseif (empty($type)){//删除整个活动
			if (pdo_delete($this->modulename."_rule",array('id'=>$rid)) === false){
				message('删除失败!');
			}else{
				$qrs = pdo_fetchall('select * from '.tablename($this->modulename."_qrcode")." where rid='{$rid}'");
				foreach ($qrs as $value) {
					@unlink("../addons/".$this->modulename."/qrcode/{$rid}_{$value['title']}.png");
				}
				rmdir("../addons/".$this->modulename."/upload/{$rid}");
				pdo_delete($this->modulename."_qrcode",array('rid'=>$rid));
				pdo_delete($this->modulename."_record",array('rid'=>$rid));
				pdo_delete($this->modulename."_comment",array('rid'=>$rid));
				message('删除成功！',$this->createWebUrl('index'));
			}
		}
	}
	
	public function doWebPlayer(){
		global $_W,$_GPC;
		$rid = $_GPC['rid'];
		$rule = pdo_fetch('select * from '.tablename($this->modulename."_rule")." where id='{$rid}'");
		$qid = $_GPC['qid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$list = pdo_fetchall('select *,(select count(1) from '.tablename($this->modulename."_comment").' where reid=r.id and goods=1) counts,(select count(1) from '.tablename($this->modulename."_comment").' where reid=r.id and goods=0) coms from '.tablename($this->modulename."_record")." r where r.qid='{$qid}' and r.rid='{$rid}' order by r.createtime desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		load()->model('mc');
		foreach ($list as &$value) {
			if (empty($value['nickname']) || empty($value['avatar'])){
				$mc = mc_fetch($value['openid'],array('nickname','avatar'));
				$value['nickname'] = $mc['nickname'];
				$value['avatar'] = $mc['avatar'];
			}
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename."_record") . " where qid='{$qid}' ");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('record');
	}
	
	public function doWebStatus(){
		global $_W,$_GPC;
		if ($_GPC['type']){
			pdo_query('update '.tablename($this->modulename."_comment")." set checked = !checked where id='{$_GPC['id']}'");
		}else{
			pdo_query('update '.tablename($this->modulename."_record")." set checked = !checked where id='{$_GPC['id']}'");
		}
	}
	
	public function doWebComment(){
		global $_W,$_GPC;
		$rid = $_GPC['rid'];
		$rule = pdo_fetch('select * from '.tablename($this->modulename."_rule")." where id='{$rid}'");
		$qid = $_GPC['qid'];
		$reid = $_GPC['reid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$list = pdo_fetchall('select * from '.tablename($this->modulename."_comment")." where reid='{$_GPC['reid']}' and goods=0 order by createtime desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename."_comment") . " where reid='{$_GPC['reid']}' and goods=0 ");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('comment');
	}
	
	public function doMobileQrcode(){
		global $_W,$_GPC;
		$qid = $_GPC['qid'];
		$rid = $_GPC['rid'];
		load()->model('mc');
		$mc = mc_oauth_userinfo();
		$rule = pdo_fetch('select * from '.tablename($this->modulename."_rule")." where id='{$rid}'");
		if ($rule['type'] == 2 && !$_W['fans']['follow']){
			message('请先关注公众号！',$_W['account']['subscribeurl']);
		}
		if (empty($mc)) $mc['openid'] = $_W['clientip'];
		$qrcode = pdo_fetch('select * from '.tablename($this->modulename."_qrcode")." where qid='{$qid}' and rid='{$rid}'");
		if (empty($qrcode)) message('该窗口已关闭咯……');
		$slide = unserialize($rule['sliders']);
		if ($_GPC['op'] == 1){
			$con = " order by counts desc,createtime desc";
		}else $con = " order by createtime desc";
		if ($_GPC['op'] == 2){
			$my = " and r.openid='{$mc['openid']}' ";
		}	

		if ($rule['checked1'] && $_GPC['op'] != 2){
			$check = " and r.checked=1";
		}
		
		$imgs = pdo_fetchall('select *,(select id from '.tablename($this->modulename."_comment")
				.' where reid=r.id and goods=1 and openid="'.$mc['openid'].'" limit 1) good,(select count(1) from '
				.tablename($this->modulename."_comment").' where reid=r.id and goods=1) counts from '
				.tablename($this->modulename."_record")." r where r.qid='{$qid}' and r.rid='{$rid}' {$my} {$check} {$con}");
		include $this->template('index');
	}
	
	public function doMobileMore(){
		global $_W,$_GPC;
		$qid = $_GPC['qid'];
		$rid = $_GPC['rid'];
		$rule = pdo_fetch('select * from '.tablename($this->modulename."_rule")." where id='{$rid}'");
		$pageNo = $_GPC['pageNo'];
		if (empty($pageNo)) $pageNo = 1;
		$mc['openid'] = $_W['openid'];
		if (empty($mc['openid'])) $mc['openid'] = $_W['clientip'];
		if ($_GPC['op'] == 1){
			$con = " order by counts desc,createtime desc";
		}else $con = " order by createtime desc";
	
		if ($rule['checked1']){
			$check = " and r.checked=1";
		}
		
		$imgs = pdo_fetchall('select *,(select id from '.tablename($this->modulename."_comment")
				.' where reid=r.id and goods=1 and openid="'.$mc['openid'].'" limit 1) good,(select count(1) from '
				.tablename($this->modulename."_comment").' where reid=r.id and goods=1) counts from '
				.tablename($this->modulename."_record")." r where r.qid='{$qid}' and r.rid='{$rid}' {$check} {$con} limit ". ($pageNo-1)*4 .",4");
		if (empty($imgs)) die('1');
		foreach ($imgs as &$value) {
			$value['createtime'] = date('m-d H:i',$value['createtime']);
		}
		die(json_encode($imgs));
	}
	
	public function doMobileUpload(){
		global $_W,$_GPC;
		$qid = $_GPC['qid'];
		$rid = $_GPC['rid'];
		$file = $_FILES['imgfile'];
		$name = $file['name'];
		$exs = explode('.',$name);
		$postfix = $exs[count($exs)-1];
		$tmpfile = $file['tmp_name'];  //临时存放文件
		$error = $file['error'];
		if($error) message("上传出现错误");
		if ($file['size'] >= 1024 * 1024){
			message("请上传小于1M的图片");
		}
		include 'resize.php';
		$show_pic_scal = show_pic_scal(640, 0, $tmpfile);
		resize($tmpfile,$show_pic_scal[0],$show_pic_scal[1]);
		$location = '../addons/'.$this->modulename.'/upload/'.$rid."/".$qid."/";
		if (!file_exists($location)) 
			 mkdir($location,0777,true);
		$filename = date('Ymd',time()).rand(1,1000000).".".$postfix;
		$myfile = $location. $filename;
		if (move_uploaded_file($tmpfile,$myfile) === false){
			message('上传失败！');
		}
		
		load()->model('mc');		
		$mc = mc_oauth_userinfo();
		
		pdo_insert($this->modulename."_record",
			array('qid'=>$qid,'rid'=>$rid,'openid'=>$mc['openid'],
				'nickname'=>$mc['nickname'],'avatar'=>$mc['avatar'],
				'uploadImg'=>$myfile,'createtime'=>time(),'word'=>$_GPC['word']));
		$rule = pdo_fetch('select * from '.tablename($this->modulename."_rule")." where id='{$rid}'");
		if ($rule['checked']){
			$check = "，审核后即可显示";
		}
		message("上传成功{$check}！",$this->createMobileUrl('qrcode',array('qid'=>$qid,'rid'=>$rid)));
	}
	
	public function doMobileGood(){
		global $_W,$_GPC;
		$rid = $_GPC['rid'];
		load()->model('mc');
		$mc = mc_oauth_userinfo();
		if (empty($mc)) $mc['openid'] = $_W['clientip'];
		$m = pdo_fetch('select id from '.tablename($this->modulename."_comment")." where rid='{$rid}' and openid='{$mc['openid']}' and reid='{$_GPC['mid']}'");
		if (!empty($m)) return ;
		if (pdo_insert($this->modulename."_comment",
				array('reid'=>$_GPC['mid'],'rid'=>$rid,'openid'=>$mc['openid'],
						'nickname'=>$mc['nickname'],'avatar'=>$mc['avatar'],
						'goods'=>1,'createtime'=>time())) === false){
				die('0');
		}
	}
	
	public function doMobileCom(){
		global $_W,$_GPC;
		$rid = $_GPC['rid'];
		load()->model('mc');
		$mc = mc_oauth_userinfo();
		if (!empty($mc['openid']) && (empty($mc['nickname']) || empty($mc['avatar']))){
			$m = mc_fetch($mc['openid'],array('nickname','avatar'));
			$mc['nickname'] = $m['nickname'];
			$mc['avatar'] = $m['avatar'];
			if (empty($mc['nickname']) || empty($mc['avatar'])){
				$ACCESS_TOKEN = $this->getAccessToken();
				$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$mc['openid']}&lang=zh_CN";
				load()->func('communication');
				$json = ihttp_get($url);
				$userInfo = @json_decode($json['content'], true);
				$mc['nickname'] = $userInfo['nickname'];
				$mc['avatar'] = $userInfo['headimgurl'];
			}
		} 
		$data = array('reid'=>$_GPC['reid'],'rid'=>$rid,'openid'=>$mc['openid'],
						'nickname'=>$mc['nickname'],'avatar'=>$mc['avatar'],'goods'=>0,
						'words'=>$_GPC['word'],'createtime'=>time());
		if (pdo_insert($this->modulename."_comment",$data) === false){
			die('0');
		}else{
			$rule = pdo_fetch('select * from '.tablename($this->modulename."_rule")." where id='{$rid}'");
			if ($rule['checked2']){
				die('1');
			}
			$data['createtime'] = date('m-d H:i',$data['createtime']);
			if (empty($data['nickname'])) $data['nickname'] = '路人甲';
			if (empty($data['avatar'])) $data['avatar'] = $_W['account']['avatar'];
			die(json_encode($data));
		}
	}
	
	private function getAccessToken() {
		global $_W;
		load()->model('account');
		$acid = $_W['acid'];
		if (empty($acid)) {
			$acid = $_W['uniacid'];
		}
		$account = WeAccount::create($acid);
		$token = $account->fetch_available_token();
		return $token;
	}
	
	public function doMobileComment(){
		global $_W,$_GPC;
		$rid = $_GPC['rid'];
		$reid = $_GPC['reid'];
		$rule = pdo_fetch('select * from '.tablename($this->modulename."_rule")." where id='{$rid}'");
		$record = pdo_fetch('select * from '.tablename($this->modulename."_record")." where id='{$reid}'");
		if ($rule['checked2']){
			$check = " and checked=1";
		}
		$comments = pdo_fetchall('select * from '.tablename($this->modulename."_comment")." where reid='{$reid}' and words != '' {$check} order by createtime desc");
		include $this->template('comment');
	}
	
	public function doMobileDel(){
		global $_W,$_GPC;
		$rid = $_GPC['rid'];
		pdo_delete($this->modulename."_record",array('id'=>$_GPC['reid']));
		pdo_delete($this->modulename."_comment",array('reid'=>$_GPC['reid']));
		message('删除成功！',$this->createMobileUrl('qrcode',array('rid'=>$rid,'qid'=>$_GPC['qid'],'op'=>2)));
	}

}