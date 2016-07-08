<?php
/**
 * 失物招领模块微站定义
 *
 * @author Yoby
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
function pager($tcount, $pindex, $psize = 15, $url = '', $context = array('before' => 5, 'after' => 4, 'ajaxcallback' => '')) {
	global $_W;
	$pdata = array(
		'tcount' => 0,
		'tpage' => 0,
		'cindex' => 0,
		'findex' => 0,
		'pindex' => 0,
		'nindex' => 0,
		'lindex' => 0,
		'options' => ''
	);
	if($context['ajaxcallback']) {
		$context['isajax'] = true;
	}

	$pdata['tcount'] = $tcount;
	$pdata['tpage'] = ceil($tcount / $psize);
	if($pdata['tpage'] <= 1) {
		return '';
	}
	$cindex = $pindex;
	$cindex = min($cindex, $pdata['tpage']);
	$cindex = max($cindex, 1);
	$pdata['cindex'] = $cindex;
	$pdata['findex'] = 1;
	$pdata['pindex'] = $cindex > 1 ? $cindex - 1 : 1;
	$pdata['nindex'] = $cindex < $pdata['tpage'] ? $cindex + 1 : $pdata['tpage'];
	$pdata['lindex'] = $pdata['tpage'];

	if($context['isajax']) {
		if(!$url) {
			$url = $_W['script_name'] . '?' . http_build_query($_GET);
		}
		$pdata['faa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['findex'] . '\', ' . $context['ajaxcallback'] . ')"';
		$pdata['paa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['pindex'] . '\', ' . $context['ajaxcallback'] . ')"';
		$pdata['naa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['nindex'] . '\', ' . $context['ajaxcallback'] . ')"';
		$pdata['laa'] = 'href="javascript:;" onclick="p(\'' . $_W['script_name'] . $url . '\', \'' . $pdata['lindex'] . '\', ' . $context['ajaxcallback'] . ')"';
	} else {
		if($url) {
			$pdata['faa'] = 'href="?' . str_replace('*', $pdata['findex'], $url) . '"';
			$pdata['paa'] = 'href="?' . str_replace('*', $pdata['pindex'], $url) . '"';
			$pdata['naa'] = 'href="?' . str_replace('*', $pdata['nindex'], $url) . '"';
			$pdata['laa'] = 'href="?' . str_replace('*', $pdata['lindex'], $url) . '"';
		} else {
			$_GET['page'] = $pdata['findex'];
			$pdata['faa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			$_GET['page'] = $pdata['pindex'];
			$pdata['paa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			$_GET['page'] = $pdata['nindex'];
			$pdata['naa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
			$_GET['page'] = $pdata['lindex'];
			$pdata['laa'] = 'href="' . $_W['script_name'] . '?' . http_build_query($_GET) . '"';
		}
	}

	$html = '<div class="pager-left">';

		$html .= "<div class=\"pager-first\"><a {$pdata['faa']} class=\"pager-nav\">首页</a></div>";
		$html .= "<div class=\"pager-pre\"><a {$pdata['paa']} class=\"pager-nav\">上一页</a></div>";
	$html .='</div><div class="pager-cen">
					' .$pindex.'/'.$pdata['tpage'].'
				</div><div class="pager-right">';

		$html .= "<div class=\"pager-next\"><a {$pdata['naa']} class=\"pager-nav\">下一页</a></div>";
		$html .= "<div class=\"pager-end\"><a {$pdata['laa']} class=\"pager-nav\">尾页</a></div>";
	
	$html .= '</div>';
	return $html;
}
class Yoby_lostModuleSite extends WeModuleSite {

	public function doMobileFm() {
	global $_W,$_GPC;
	$yobyurl = $_W['siteroot']."addons/yoby_lost/";
		$url =$this->module['config']['address'];
		$weid = $_W['uniacid'];
	$pindex = max(1, intval($_GPC['page']));
			$psize =10;//每页面10条
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND content LIKE '%".$_GPC['keyword']."%'";
			}
			$list = pdo_fetchall("SELECT *  FROM ".tablename('lost')." WHERE weid = '{$weid}' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('lost') . " WHERE weid = '{$weid}' $condition ");
			$pager = pager($total, $pindex, $psize);
		
		include $this->template('index');	
	}
	public function doMobileFabu() {
	global $_W,$_GPC;
	load()->func('file');

	$yobyurl = $_W['siteroot']."addons/yoby_lost/";
		$url =$this->module['config']['address'];
		$weid = $_W['uniacid'];
	if(checksubmit('submit')){
				if (empty($_GPC['createtime'])) {
					message('亲,日期必须填写哦!');
				}
				if (empty($_GPC['xm'])) {
					message('亲,联系人必须填写哦!');
				}
								if (empty($_GPC['mobile'])) {
					message('亲,手机或电话号码填一个吧!');
				}
								if (empty($_GPC['content'])) {
					message('亲,详情认真填写哦!');
				}
						if (empty($_W['fans']['from_user'])) {
					message('亲,请关注后从菜单进入或发送[失物招领]进入!');
				}
				if (strlen($_GPC['createtime'])<5) {
					message('亲,多填写几个字吧!');
				}
			
			$type =$_GPC['type'];//类型 0招领
			$createtime = $_GPC['createtime'];//丢失日期
			$content = $_GPC['content'];//详情
			$xm = $_GPC['xm'];//联系人
			$mobile = $_GPC['mobile'];
			$qq = $_GPC['qq'];
			$wx = $_GPC['wx'];
			$openid = $_W['fans']['from_user'];
						if (!empty($_FILES['img']['tmp_name'])) {

				

					$upload = file_upload($_FILES['img'],'image');

					if (is_error($upload)) {

						message('上传出错', '', 'error');

					}

					$img = $upload['path'];

				}
			
			$data = array(
			'weid'=>$weid,
			'type'=>$type,
			'createtime'=>$createtime,
			'xm'=>$xm,
			'qq'=>$qq,
			'content'=>$content,
			'mobile'=>$mobile,
			'wx'=>$wx,
			'isok'=>0,//未认领
			'img'=>$img,
			'openid'=>$openid,
			);	
    $n = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('lost')." where xm='{$xm}' and mobile='{$mobile}' and content='{$content}'");
    if($n>0){
    die('<script>alert("数据已存在,请不要重复提交!");location.href="'.$this->createMobileUrl('fabu').'"</script>');
    }else{
    pdo_insert('lost', $data);
			$id = pdo_insertid(); 
      die('<script>alert("发布成功!");location.href="'.$this->createMobileUrl('fm').'"</script>');

		}
		
	
		
	}else{
        include $this->template('fabu');
	}
	}
	public function doMobileChakan() {
	global $_W,$_GPC;
	$yobyurl = $_W['siteroot']."addons/yoby_lost/";
	$url =$this->module['config']['address'];
$weixin = "搜索[". $_W['account']['name']."]关注我,更多好玩等着你.";
	$weid = $_W['uniacid'];
	$id = $_GPC['id'];
	$sql = "SELECT * FROM ".tablename('lost')." WHERE id = ".$id;
	$data = pdo_fetch($sql);
	
	include $this->template('chakan');	
	
	}
	public function doMobileRen(){//认领
		global $_GPC,$_W;
		
		$id = intval($_GPC['id']);
		if($_W['isajax'] ){
		$openid = $_W['fans']['from_user'];
		$oid = pdo_fetchcolumn("SELECT openid FROM ".tablename('lost')." where id=$id");
		if($openid==$oid){
			pdo_update('lost', array('isok' =>1), array('id' => $id));
			echo 1;
			}else{
			echo 0;
			}
		}
		
		}
	public function doWebShiwu() {
			
		global $_W,$_GPC;

	load()->func('file');

		$yobyurl = $_W['siteroot']."addons/yoby_lost/";
	$weid = $_W['uniacid'];;
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if('display' ==$op){//失物管理
			$pindex = max(1, intval($_GPC['page']));
			$psize =20;
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND content LIKE '%".$_GPC['keyword']."%' "." OR xm LIKE '%".$_GPC['keyword']."%' "." OR mobile LIKE '%".$_GPC['keyword']."%' ";
			}
			
			$list = pdo_fetchall("SELECT * FROM ".tablename('lost')." WHERE weid = '{$weid}' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('lost') . " WHERE weid = '{$weid}'");
			$pager = pagination($total, $pindex, $psize);
			include $this->template('index');
		}elseif('del' ==$op){
		
			if(isset($_GPC['delete'])){
				$ids = implode(",",$_GPC['delete']);
				$row1 = pdo_fetchall("SELECT id,img FROM ".tablename('lost')." WHERE id in(".$ids.")");
				if(!empty($row1)){
					foreach($row1 as $data1){
					if (!empty($data1['img'])) {
			file_delete($data1['img']);
		}	
					}
				}
				
				$sqls = "delete from  ".tablename('lost')."  where id in(".$ids.")"; 
				pdo_query($sqls);
				
				message('删除成功！', referer(), 'success');
			}
			$id = intval($_GPC['id']);
			pdo_delete('lost', array('id' => $id));
			if (!empty($row['img'])) {
			file_delete($row['img']);
		}
			message('删除失物成功！', referer(), 'success');
		}
	}

}