<?php
/**
 * 微树洞模块微站定义
 *
 * @author yoby
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
function timeshudong($pubtime) {
    $time = time ();
    /** 如果不是同一年 */
    if (idate ( 'Y', $time ) != idate ( 'Y', $pubtime )) {
        return date ( 'Y-m-d', $pubtime );
    }
 
    /** 以下操作同一年的日期 */
    $seconds = $time - $pubtime;
    $days = idate ( 'z', $time ) - idate ( 'z', $pubtime );
 
    /** 如果是同一天 */
    if ($days == 0) {
        /** 如果是一小时内 */
        if ($seconds < 3600) {
            /** 如果是一分钟内 */
            if ($seconds < 60) {
                if (3 > $seconds) {
                    return '刚刚';
                } else {
                    return $seconds . '秒前';
                }
            }
            return intval ( $seconds / 60 ) . '分钟前';
        }
        return idate ( 'H', $time ) - idate ( 'H', $pubtime ) . '小时前';
    }
 
    /** 如果是昨天 */
    if ($days == 1) {
        return '昨天 ' . date ( 'H:i', $pubtime );
    }
 
    /** 如果是前天 */
    if ($days == 2) {
        return '前天 ' . date ( 'H:i', $pubtime );
    }
 
    /** 如果是7天内 */
    if ($days < 7) {
        return $days. '天前';
    }
 
    /** 超过7天 */
    return date ( 'n-j H:i', $pubtime );
}

/**
 * 生成分页数据
 * @param int $currentPage 当前页码
 * @param int $totalCount 总记录数
 * @param string $url 要生成的 url 格式，页码占位符请使用 *，如果未写占位符，系统将自动生成
 * @param int $pageSize 分页大小
 * @return string 分页HTML
 */
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

	$html = '<div class="page_left">';

		$html .= "<div class=\"page_first\"><a {$pdata['faa']} class=\"pager-nav\">首页</a></div>";
		$html .= "<div class=\"page_pre\"><a {$pdata['paa']} class=\"pager-nav\">上一页</a></div>";
	$html .='</div><div class="page_cen">
					' .$pindex.'/'.$pdata['tpage'].'
				</div><div class="page_right">';

		$html .= "<div class=\"page_next\"><a {$pdata['naa']} class=\"pager-nav\">下一页</a></div>";
		$html .= "<div class=\"page_end\"><a {$pdata['laa']} class=\"pager-nav\">尾页</a></div>";
	
	$html .= '</div>';
	return $html;
}
class Yoby_sdModuleSite extends WeModuleSite {

	public function doMobileFm() {
header("Location:".$this->createMobileUrl('index'));
	}

public function doMobileDh() {
		//这个操作被定义用来呈现 微站首页导航图标
	header("Location:".$this->createMobileUrl('index'));
	}
public function doMobileIndex() {//树洞首页
		global $_W,$_GPC;
		$yobyurl = $_W['siteroot']."addons/yoby_sd/";
		$weid = $_W['uniacid'];
		$bgimg =toimage($this->module['config']['img']);
		$n1=intval($this->module['config']['n1']);
		$condition=" ";
		$pindex = max(1, intval($_GPC['page']));
			$psize =$n1;
			$list = pdo_fetchall("SELECT * FROM ".tablename('yoby_sd')." WHERE weid = {$weid} $condition ORDER BY id desc LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('yoby_sd') . " WHERE weid ={$weid}  $condition ");
			$pager = pager($total, $pindex, $psize);
		
		include  $this->template('index');
	}	

public function doMobileIndex2() {//树洞最热
		global $_W,$_GPC;
		$yobyurl = $_W['siteroot']."addons/yoby_sd/";
		$weid = $_W['uniacid'];
		$bgimg =toimage($this->module['config']['img']);
		$n1=intval($this->module['config']['n1']);
		$condition=" ";
		$pindex = max(1, intval($_GPC['page']));
			$psize =$n1;
			$list = pdo_fetchall("SELECT * FROM ".tablename('yoby_sd')." WHERE weid = {$weid} $condition ORDER BY say desc LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('yoby_sd') . " WHERE weid ={$weid}  $condition ");
			$pager = pager($total, $pindex, $psize);
		
		include  $this->template('index');
	}

public function doMobileIndex3() {//树洞关注
		global $_W,$_GPC;
		$yobyurl = $_W['siteroot']."addons/yoby_sd/";
		$weid = $_W['uniacid'];
		$bgimg =toimage($this->module['config']['img']);
		$n1=intval($this->module['config']['n1']);
		$openid = $_W['fans']['from_user'];

		$condition="   and openid='{$openid}' ";
		$pindex = max(1, intval($_GPC['page']));
			$psize =$n1;
			$list = pdo_fetchall("SELECT * FROM ".tablename('yoby_sd')." WHERE weid = {$weid} $condition ORDER BY id desc LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('yoby_sd') . " WHERE weid ={$weid}  $condition ");
			$pager = pager($total, $pindex, $psize);
		
		include  $this->template('index');
	}
	
public function doMobileFabu() {//发布树洞
		global $_W,$_GPC;
		$yobyurl = $_W['siteroot']."addons/yoby_sd/";
		$weid = $_W['uniacid'];
		$openid = $_W['fans']['from_user'];
		if($_W['isajax'] ){
		if(empty($openid)){
			die('<script>alert("发送失败,你的openid不存在,请从封面进入!");</script>');
		}
		$content = trim($_GPC['content']);
		$bgcolor = $_GPC['bgcolor'];
		$bid = pdo_fetchcolumn("SELECT  count(*)  FROM ".tablename('yoby_sd')."  where  weid={$weid}");//计算记录数
		$bid = $bid+1;
		$data = array(
			'weid'=>$weid,
			'content'=>$content,
			'bgcolor'=>$bgcolor,
			'createtime'=>time(),
			'openid'=>$openid,
			'bid'=>$bid,
		);
		$rs= pdo_fetchcolumn("SELECT  count(*)  FROM ".tablename('yoby_sd')."  where  weid={$weid} and openid='{$openid}' and content='{$content}' ");
		if($rs>0){
			die('<script>alert("发送失败!");</script>');
		}else{
			pdo_insert('yoby_sd', $data);
		}
		
		}
	}
	
	
public function doMobileSay() {//树洞评论
		global $_W,$_GPC;
		$yobyurl = $_W['siteroot']."addons/yoby_sd/";
		$weid = $_W['uniacid'];
		$bgimg =toimage($this->module['config']['img']);
		$n2 =intval($this->module['config']['n2']);
		$id = intval($_GPC['id']);
		if(!empty($id)){
			$rs =  pdo_fetch("select * from ".tablename('yoby_sd')." where id=$id");
		}
		$condition=" ";
		$pindex = max(1, intval($_GPC['page']));
			$psize =$n2;
			$list = pdo_fetchall("SELECT * FROM ".tablename('yoby_sd_say')." WHERE weid = {$weid} and  sid=$id  $condition ORDER BY id desc LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('yoby_sd_say') . " WHERE weid ={$weid} and  sid=$id  $condition ");
			$pager = pager($total, $pindex, $psize);
		include  $this->template('say');
	}

public function doMobilePing() {//发布树洞评论
		global $_W,$_GPC;
		$yobyurl = $_W['siteroot']."addons/yoby_sd/";
		$weid = $_W['uniacid'];
		$openid = $_W['fans']['from_user'];
		
		if($_W['isajax'] ){
		if(empty($openid)){
			die('<script>alert("发送失败,你的openid不存在,请从封面进入!");</script>');
		}
		$content = trim($_GPC['content']);
		$bgcolor = $_GPC['bgcolor'];
		$sid = $_GPC['sid'];
		$pid = pdo_fetchcolumn("SELECT  count(*)  FROM ".tablename('yoby_sd_say')."  where  weid={$weid} and sid=$sid");//计算记录数
		$pid = $pid+1;
		$data = array(
			'weid'=>$weid,
			'content'=>$content,
			'bgcolor'=>$bgcolor,
			'createtime'=>time(),
			'openid'=>$openid,
			'pid'=>$pid,
			'sid'=>$sid,
		);
		$data1 = array('say'=>$pid);
			pdo_update('yoby_sd', $data1,array('id'=>$sid));
			pdo_insert('yoby_sd_say', $data);
		
		
		}
	}
	public function doWebGl() {//后台管理
		global $_W,$_GPC;
$weid = $_W['uniacid'];
$yobyurl = $_W['siteroot']."addons/yoby_sd/";

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
 if('del' == $op){//删除
 	
			if(isset($_GPC['delete'])){			
				foreach($_GPC['delete'] as $del){
					pdo_delete('yoby_sd', array('id'=>$del));
					pdo_delete('yoby_sd_say', array('weid' => $weid,'sid'=>$del));
				}
				pdo_query("update ".tablename('yoby_sd')." M,(select id,(select count(*)+1 as rank from ".tablename('yoby_sd')." b where weid={$weid} and b.id<a.id order by createtime asc) as rank from ".tablename('yoby_sd')." a) N set M.bid=N.rank where M.id=N.id and weid=$weid");
				message('删除成功！', referer(), 'success');
			}else{
	
				$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM ".tablename('yoby_sd')." WHERE id = :id", array(':id' => $id));
			if ($row['id']<1) {
				message('删除成功！', $this->createWebUrl('gl', array('op' => 'display')), 'success');
			}else{
			pdo_delete('yoby_sd', array('id'=>$id));
			pdo_delete('yoby_sd_say', array('weid' => $weid,'sid'=>$id));
			pdo_query("update ".tablename('yoby_sd')." M,(select id,(select count(*)+1 as rank from ".tablename('yoby_sd')." b where weid={$weid} and b.id<a.id order by createtime asc) as rank from ".tablename('yoby_sd')." a) N set M.bid=N.rank where M.id=N.id and weid=$weid");
			message('删除成功！', referer(), 'success');	
			}
						
			}
			
			
		}else if('display' == $op){//显示
			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示
			
				$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " and (bid ='".$_GPC['keyword']."' OR content like '%".$_GPC['keyword']."%' ) ";
			}
			
			$list = pdo_fetchall("SELECT * FROM ".tablename('yoby_sd')." where weid=".$weid.$condition." ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('yoby_sd')."  where weid=".$weid.$condition);
			$pager = pagination($total, $pindex, $psize);
			include $this->template('index');
		}	
	}
}