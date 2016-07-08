<?php
/**
 * 二维码管理模块微站定义
 *
 * @author junsion
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class junsion_qrcodeModuleSite extends WeModuleSite {
	private $qrcodepath = '../attachment/qrcodemanagement/';
	public function doWebIndex() {
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$cates = pdo_fetchall('select * from '.tablename($this->modulename.'_cate')."  where  weid = {$weid}");
		$op = $_GPC['op'];
		if ($op == 'post'){
			$qid = $_GPC['qid'];
			$adv = pdo_fetch('select * from '.tablename($this->modulename."_qrcode")." where id='{$qid}'");
			if (checksubmit()){
				$data = array(
					'weid'=>$weid,
					'title'=>$_GPC['title'],
					'status'=>$_GPC['status'],
					'comment'=>$_GPC['comment'],
					'url'=>$_GPC['url'],
					'deadline'=>$_GPC['deadline'],
					'cate'=>$_GPC['cate'],
					'logo'=>$_GPC['logo'],
					'must_fans'=>$_GPC['must_fans']
				);
				if (empty($qid)){
					if (pdo_insert($this->modulename."_qrcode",$data) === false)
						message('添加二维码失败');
					else{
						//生成二维码
						$this->generateQrcode(pdo_insertid(),$data['logo']);
						message('添加二维码成功',$this->createWebUrl('index'));
					}
				}else{
					if (pdo_update($this->modulename."_qrcode",$data,array('id'=>$qid)) === false)
						message('修改二维码失败');
					else{
						if ($adv['logo'] != $data['logo']){
							//删除二维码文件
							@unlink($this->qrcodepath.$this->modulename."{$qid}.png");
							//生成二维码
							$this->generateQrcode($qid,$data['logo']);
						}
						message('修改二维码成功',$this->createWebUrl('index'));
					}
				}
			}
			if (empty($adv['deadline'])) $adv['deadline'] = date('Y-m-d H:i:s',time()+7*24*3600);
		}else if ($op == 'delete'){
			$qid = $_GPC['qid'];
			if (pdo_delete($this->modulename."_qrcode",array('id'=>$qid)) === false)
				message('删除二维码失败');
			else{
				//删除二维码文件
				@unlink($this->qrcodepath.$this->modulename."{$qid}.png");
				pdo_delete($this->modulename."_record",array('qid'=>$qid));
				message('删除二维码成功',$this->createWebUrl('index'));
			}
		}else{
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$cid = $_GPC['cate'];
			$condition = '';
			if (!empty($cid)){
				$condition .= " and cate={$cid}";
			}
			$list = pdo_fetchall('select * from '.tablename($this->modulename."_qrcode")." q where q.weid={$weid} {$condition} LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			foreach ($list as $key => $value) {
				$list[$key]['scancount'] = pdo_fetchcolumn('select count(*) from '.tablename($this->modulename."_record")." where qid={$value['id']}");
				$list[$key]['scancount2'] = pdo_fetchcolumn('select count(*) from (select * from '.tablename($this->modulename."_record")." where qid={$value['id']} group by openid) b");
			}
			$total = pdo_fetchcolumn('select count(*) from '.tablename($this->modulename."_qrcode")." where weid={$weid} {$condition}");
			$pager = pagination($total, $pindex, $psize);
		}
		include $this->template('index');
	}
	
	public function doWebScan(){
		global $_W,$_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$date = $_GPC['date'];
		$qid = $_GPC['qid'];
		$condition = '';
		if (!empty($qid)){
			$condition = " and qid='{$qid}'";
		}
		if (empty($date)){
			$date = array('start'=>date('Y-m-d H:i:s',time()-24*3600),'end'=>date('Y-m-d H:i:s',time()));
		}
		$condition .= " and createtime >= '{$date['start']}' and createtime <= '{$date['end']}'";
		
		$op = $_GPC['op'];
		$group = '';
		if (!empty($op)) $group = ' group by openid,qid ';
		
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W['uniacid']);
		$account = $accObj->fetchAccountInfo();
		$select = ",(select follow from ".tablename('mc_mapping_fans')." where openid=r.openid limit 1) follow ";
		if ($account['level'] != 4){
			$select = ",(select follow from ".tablename('mc_mapping_fans')." where nickname=r.nickname limit 1) follow ";
		}		
		$list = pdo_fetchall('select r.* '.$select
						.',(select title from '.tablename($this->modulename."_qrcode").' where id=r.qid) title from '
						.tablename($this->modulename."_record")." r where weid='{$_W['uniacid']}' {$condition} {$group} order by createtime desc  LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		if (!empty($op)){
			foreach ($list as $key => $value) {
				$list[$key]['scancount'] = pdo_fetchcolumn('select count(*) from '.tablename($this->modulename."_record")." where openid='{$value['openid']}' and qid='{$value['qid']}'");
			}
		}
		$total = pdo_fetchcolumn('select count(*) from (select * from '.tablename($this->modulename."_record")." where weid='{$_W['uniacid']}' {$condition} {$group}) b");
		$pager = pagination($total, $pindex, $psize);
		$all = pdo_fetchcolumn('select count(*) from '.tablename($this->modulename."_record")." where weid='{$_W['uniacid']}' {$condition}");
		$scan = pdo_fetchcolumn('select count(*) from (select * from '.tablename($this->modulename."_record")." where weid='{$_W['uniacid']}' {$condition} group by openid,qid) b");
		
		include $this->template('scan');
	}
	
	public function doWebDown(){
		global $_W,$_GPC;
		$qid = $_GPC['qid'];
		$adv = pdo_fetch('select * from '.tablename($this->modulename."_qrcode")." where id='{$qid}'");
		/*生成二维码*/
		include "phpqrcode.php";/*引入PHP QR库文件*/
		$imgurl = $this->modulename."{$qid}.png";
		if(!file_exists($imgurl)){
			$url = $this->createMobileUrl('turn',array('qid'=>$qid));
			$value = $_W['siteroot']."app".substr($url,1);
			$errorCorrectionLevel = "M";
			$matrixPointSize = '110';
			QRcode::png($value, $imgurl, $errorCorrectionLevel, $matrixPointSize);
			$this->qrcodeAddLogo($imgurl,toimage($adv['logo']));
		}
		
		header('Content-type: image/jpeg');
		header("Content-Disposition: attachment; filename='qrcode{$qid}.jpg'");
		readfile($imgurl);
		@unlink($imgurl);
		exit;
	}
	
	private function generateQrcode($qid,$logo){
		global $_W,$_GPC;
		$qrcode = $this->qrcodepath;
		if(! is_dir($qrcode)){
			mkdir($qrcode);
			@chmod($qrcode,777);
		}
		/*生成二维码*/
		include "phpqrcode.php";/*引入PHP QR库文件*/
		$imgname = $this->modulename."{$qid}.png";
		$imgurl = $qrcode.$imgname;
		if(!file_exists($imgurl)){
			$url = $this->createMobileUrl('turn',array('qid'=>$qid));
			$value = $_W['siteroot']."app".substr($url,1);
			$errorCorrectionLevel = "M";
			$matrixPointSize = '10';
			QRcode::png($value, $imgurl, $errorCorrectionLevel, $matrixPointSize);
			$this->qrcodeAddLogo($imgurl,toimage($logo));
		}
	}
	
	private function qrcodeAddLogo($imgurl,$logo){
		if (!empty($logo)) {
			$QR = imagecreatefromstring(file_get_contents($imgurl));
			$logo = imagecreatefromstring(file_get_contents($logo));
			$QR_width = imagesx($QR);//二维码图片宽度
			$QR_height = imagesy($QR);//二维码图片高度
			$logo_width = imagesx($logo);//logo图片宽度
			$logo_height = imagesy($logo);//logo图片高度
			$logo_qr_width = $QR_width / 5;
			$scale = $logo_width/$logo_qr_width;
			$logo_qr_height = $logo_height/$scale;
			$from_width = ($QR_width - $logo_qr_width) / 2;
			//重新组合图片并调整大小
			imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
					$logo_qr_height, $logo_width, $logo_height);
		}
		//输出图片
		imagepng($QR,$imgurl);
	}
	
	public function doMobileTurn(){
		global $_W,$_GPC;
		$qid = $_GPC['qid'];
		if (empty($qid)){
			message('该二维码已失效');
		}
		$qrcode = pdo_fetch('select * from '.tablename($this->modulename."_qrcode")." where id={$qid}");
		if (empty($qrcode)){
			message('该二维码已失效');
		}else{
			if (strtotime($qrcode['deadline']) <= time()){
				message('该二维码已过期！');
			}else if (empty($qrcode['status'])){
				message('该二维码已禁用');
			}else{
				load()->model('mc');
				$info = mc_oauth_userinfo();
				if (!empty($info)){
					pdo_insert($this->modulename."_record",array('weid'=>$_W['uniacid'],'qid'=>$qid,'nickname'=>$info['nickname'],'openid'=>$info['openid'],'createtime'=>date('Y-m-d H:i:s',time())));
				}
				if (empty($qrcode['must_fans'])){
					$follow = $_W['fans']['follow'];
					if ($_W['account']['level'] != 4){
						$follow = pdo_fetchcolumn('select follow from '.tablename('mc_mapping_fans')." where nickname='{$info['nickname']}' limit 1");
					}
					
					if (empty($follow)){
						$url = $this->module['config']['describeurl'];
						if (empty($url)) message("请先关注公众号！点击右上角菜单，选择'查看公众号'，点击'关注'即可！");
						else message('请先关注公众号！',$url);
					}
				}
				header('location:'.$qrcode['url']);
			}
		}
	}
	
	public function doWebEditCate(){
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$type = $_GPC['type'];
		$new_cate = $_GPC['new_cate'];
		$cid = $_GPC['cid'];
		$cate = $new_cate;
		if($type == 1)
			$cate = $_GPC['old_cate'];
		$cates = pdo_fetch('select * from '.tablename($this->modulename.'_cate').' where id=:id',array('id'=>$cid));
		if(!empty($cates)){
			if($type == 1){
				if(pdo_update($this->modulename.'_cate',array('title'=>$new_cate),array('id'=>$cates['id'])) == false)
					echo 0;
				else{
					echo 1;
				}
			}else if($type == 2){//删除分类
				if(pdo_delete($this->modulename.'_cate',array('id'=>$cates['id'])) == false)
					echo 0;
				else{//先删除分类 后删除二维码
					$list = pdo_fetchall('select id from '.tablename($this->modulename."_qrcode")." where cate={$cates['id']}");
					foreach ($list as $value) {
						pdo_delete($this->modulename.'_record',array('qid'=>$value['id']));
						@unlink($this->qrcodepath.$this->modulename."{$value['id']}.png");
					}
					pdo_delete($this->modulename.'_qrcode',array('weid'=>$weid,'cate'=>$cates['id']));
					echo 1;
				}
			}
			else echo -1;
		}
		else{
			if($type == 0){
				if(pdo_insert($this->modulename.'_cate',array('weid'=>$weid,'title'=>$new_cate)) == false)
					echo pdo_insertid();
				else echo 1;
			}
		}
	}

}
