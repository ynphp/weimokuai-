<?php
/**
 * 签到管家模块微站定义
 *
 * @author Yoby
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class YobyqiandaoModuleSite extends WeModuleSite {
	public function doWebJilu() {
		//这个操作被定义用来呈现 规则列表
	header("Location:".$this->createWebUrl('mjilu'));
	}
	public function doWebMjilu() {
		//这个操作被定义用来呈现 管理中心导航菜单
	global $_GPC, $_W;
$weid = $_W['weid'];
$yobyurl = $_W['siteroot']."addons/yobyqiandao/";

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
 if('del' == $op){//删除
 	
			if(isset($_GPC['delete'])){
				$ids = implode(",",$_GPC['delete']);
				$sqls = "delete from  ".tablename('yobyqiandao_log')."  where id in(".$ids.")"; 
				pdo_query($sqls);
				message('删除成功！', referer(), 'success');
			}else{
	
				$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id FROM ".tablename('yobyqiandao_log')." WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，数据不存在或是已经被删除！', $this->createWebUrl('mjilu', array('op' => 'display')), 'success');
			}else{
			pdo_delete('yobyqiandao_log', array('id' => $id));
			message('删除成功！', referer(), 'success');	
			}
						
			}
			
			
		}else if('display' == $op){//显示
			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示
			
				$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " and top10=".$_GPC['keyword'];
			}
			
			$list = pdo_fetchall("SELECT * FROM ".tablename('yobyqiandao_log')." where weid=".$weid.$condition." ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('yobyqiandao_log')."  where weid=".$weid.$condition);
			$pager = pagination($total, $pindex, $psize);
			include $this->template('jilu');
		}	
	}
	public function doMobileMyqiandao() {
		//这个操作被定义用来呈现 微站个人中心导航链接
	}

	public function doMobileReg() {
		//登记
		global $_W,$_GPC;
		if($_W['container']!='wechat'){message('非法进入,请从微信端进入!');}
		load()->model('mc');		
$weid = $_W['uniacid'];
$uid = $_GPC['uid'];
$yobyurl = $_W['siteroot']."addons/yobyqiandao/";

empty( $_GPC['openid'])?message('非法进入,请点击改名进入!'):$openid = $_GPC['openid'];

	if(checksubmit('submit')){
				if (empty($_GPC['yname'])) {
					message('亲,姓名昵称不能为空!');
				}
	$rs = pdo_fetch("SELECT uid,realname FROM ".tablename('mc_members')." where uniacid=".$weid." and uid='".$uid."'");
	$name = trim($_GPC['yname']);

	$data =array(
	'realname'=>$name,
	);
	if(empty($rs['realname'])){
pdo_update('mc_members',$data,array('uid'=>$rs['uid']));
      die('<script>alert("改名成功!");document.addEventListener("WeixinJSBridgeReady", function onBridgeReady() {
WeixinJSBridge.call("closeWindow");
});</script>');	
	}else{
	die('<script>alert("淘气,不要重复提交!");document.addEventListener("WeixinJSBridgeReady", function onBridgeReady() {
WeixinJSBridge.call("closeWindow");
});</script>');
	}

	}else{	
	
	include $this->template('reg');
	}
	}
}