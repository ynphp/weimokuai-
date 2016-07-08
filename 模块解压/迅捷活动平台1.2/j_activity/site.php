<?php
/**
 * 活动中心模块处理程序
 *
 */
defined('IN_IA') or exit('Access Denied');
include('../addons/j_activity/myfunction.php');
class J_activityModuleSite extends WeModuleSite {
	
	public function doMobileAjax() {
		global $_GPC, $_W;
		$content=empty($_GPC['str'])?"":$_GPC['str'];
		if(empty($content))die(json_encode(array('success'=>false,'msg'=>'无内容！')));
		$strAry=explode("_",$content);
		if(count($strAry)!=2)die(json_encode(array('success'=>false,'msg'=>'编码错误！'.count($strAry))));
		if(!is_numeric($strAry[0]) || !is_numeric($strAry[1]))die(json_encode(array('success'=>false,'msg'=>'编码错误！')));
		$rid=pdo_fetch("SELECT * FROM ".tablename('j_activity_reply')." WHERE id = '".$strAry[1]."' ");
		if(empty($rid))die(json_encode(array('success'=>false,'msg'=>'活动已删除或者不存在！')));
		$item=pdo_fetch("SELECT * FROM ".tablename('j_activity_winner')." WHERE aid = '".$rid['id']."' and from_user='".$_W['openid']."'");
		if(empty($item['id']))die(json_encode(array('success'=>false,'msg'=>'亲，您没有参加本次活动哦~！')));
		if($item['attend']==1)die(json_encode(array('success'=>false,'msg'=>'不需要重复签到哦~！')));
		pdo_update("j_activity_winner",array('attend'=>1),array('id'=>$item['id']));
		if($rid['credit_append']){
			mc_credit_update($_W['member']['uid'],'credit1',$rid['credit_append'],array($_W['user']['uid'],'签到添加积分'));
		}
		die(json_encode(array('success'=>true)));
	}
	//********************//
	public function doMobileHistory() {
		global $_GPC, $_W;
		load()->func('tpl');
		load()->model('mc');
		
		$act_all=pdo_fetchall("select * from ".tablename('j_activity_reply')." where id in(select aid FROM ".tablename('j_activity_winner')." WHERE weid = '{$_W['uniacid']}' and from_user='".$_W['openid']."') ");
		$act_ok=pdo_fetchall("select * from ".tablename('j_activity_reply')." where id in(SELECT aid FROM ".tablename('j_activity_winner')." WHERE weid = '{$_W['uniacid']}' and from_user='".$_W['openid']."' and status>1 order by id desc)");
		$profile = mc_fetch($_W['member']['uid']);
		include $this->template('history');
	}
	public function doWebJoiner() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$rid=intval($_GPC['id']);
		$uid=intval($_GPC['uid']);
		load()->model('mc');
		$item = pdo_fetch("SELECT * FROM ".tablename('j_activity_reply')." WHERE rid = :rid",array(':rid'=>$rid));
		$list = pdo_fetchall("SELECT * FROM ".tablename('j_activity_winner')." WHERE aid = '".$item['id']."' order by status desc,id desc");
		$count0=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_activity_winner')." WHERE aid = '".$item['id']."' and status=-1");
		$count1=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_activity_winner')." WHERE aid = '".$item['id']."' and status=1");
		
		$parama=json_decode($item['parama'],true);
		$field=array();
		foreach($parama as $index=>$row){
			array_push($field,$index);
		}
		if($operation=='in'){
			if(!empty($uid)){
				pdo_update('j_activity_winner',array('status'=>'2'),array('id'=>$uid));
				if($item['credit_in']){
					$openid = pdo_fetchcolumn("SELECT from_user FROM ".tablename('j_activity_winner')." WHERE id = '".$uid."' ");
					$uuid = pdo_fetchcolumn("SELECT uid FROM ".tablename('mc_mapping_fans')." WHERE openid = '".$openid."' ");
					if($uuid)mc_credit_update($uuid,'credit1',$item['credit_in'],array($_W['user']['uid'],'入选添加积分'));
				}
				message('操作成功！',$this->createWebUrl('joiner',array('id'=>$rid)), 'success');
			}
		}elseif($operation=='out'){
			if(!empty($uid)){
				pdo_update('j_activity_winner',array('status'=>'-1'),array('id'=>$uid));
				
				message('操作成功！',$this->createWebUrl('joiner',array('id'=>$rid)), 'success');
			}
		}elseif($operation=='delete'){
			if(!empty($uid)){
				pdo_delete('j_activity_winner',array('id'=>$uid));
				message('操作成功！',$this->createWebUrl('joiner',array('id'=>$rid)), 'success');
			}
		}
		include $this->template('joiner');
	}
	public function doMobileAppend() {
		global $_GPC, $_W;
		include('../addons/j_activity/phpqrcode.php');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$id=intval($_GPC['id']);
		if(empty($id))message('异常访问！');
		$_showMenu=1;
		$item=pdo_fetch("SELECT * FROM ".tablename('j_activity_reply')." WHERE id = :id",array(':id'=>$id));
		if($operation=='ok'){
			$list=pdo_fetchall("SELECT * FROM ".tablename('j_activity_winner')." WHERE aid = :aid order by status desc",array(':aid'=>$item['id']));
			$parama=json_decode($item['parama'],true);
			$codename=$_W['uniacid']."_.png";
			$value = "j_activity#"."ajax#".$_W['uniacid']."#".$id;
			QRcode::png($value, $codename, "L", 10);
		}
		include $this->template('append');
	}
	public function doMobileInfo() {
		//这个操作被定义用来呈现 微站首页导航图标
		global $_GPC, $_W;
		load()->model('mc');
		$keyword=$_GPC['keyword'];
		$today=strtotime(date("Y-m-d"));
		$condition=" ";
		if($keyword)$condition.=" and (title like '%".$keyword."%' or description like '%".$keyword."%' or info like '%".$keyword."%' or rule like '%".$keyword."%' )";
		$list = pdo_fetchall("SELECT * FROM ".tablename('j_activity_reply')." WHERE weid = '{$_W['uniacid']}' $condition order by id desc ");
		$title="活动中心";
		
		include $this->template('list');
	}
	public function doMobileList() {
		//这个操作被定义用来呈现 微站首页导航图标
		$this->doMobileInfo();
	}
	
	public function doMobileView() {
		//这个操作被定义用来呈现 微站首页导航图标
		global $_GPC, $_W;
		$id=intval($_GPC['id']);
		$item=pdo_fetch("SELECT * FROM ".tablename('j_activity_reply')." WHERE id = :id",array(':id'=>$id));
		$today=strtotime(date("Y-m-d"));
		if($today>$item['endtime']){
			if($item['status']!=2)pdo_update('j_activity_reply',array('status'=>2),array('id'=>$id));
		}else{
			switch($item['status']){
				case -1:
				break;
				case 0:
				if($today>=$item['joinstarttime'] && $today<=$item['joinendtime'])pdo_update('j_activity_reply',array('status'=>1),array('id'=>$id));
				break;
				case 1:
				//if($today>=$item['joinstarttime'] && $today<=$item['joinendtime'])pdo_update('j_activity_reply',array('status'=>1),array('id'=>$id));
				break;
			}
		}
		
		$item=pdo_fetch("SELECT * FROM ".tablename('j_activity_reply')." WHERE id = :id",array(':id'=>$id));
		$parama=json_decode($item["parama"],true);
		if(!empty($_W['openid']))$isjoin=pdo_fetchcolumn("SELECT status FROM ".tablename('j_activity_winner')." WHERE aid = '".$item['id']."' and from_user='".$_W['openid']."'");
		$title=$item['title'];
		include $this->template('view');
	}
	public function doMobileReg() {
		//这个操作被定义用来呈现 微站首页导航图标
		global $_GPC, $_W;
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		load()->model('mc');
		$id=intval($_GPC['id']);
		$item = pdo_fetch("SELECT * FROM ".tablename('j_activity_reply')." WHERE id = :id",array(':id'=>$id));
		$title="报名".$item['title'];
		if(!$_W['openid'])message('尊敬的用户 ，由于第一次采用报名系统，为了更好地说明本次活动操作，现在将跳转到活动操作说明页，请跟着流程操作，谢谢~', pdo_fetchcolumn("SELECT subscribeurl FROM ".tablename('account_wechats')." WHERE uniacid = '".$_W['uniacid']."'"), 'error');
		$isjoin=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_activity_winner')." WHERE aid = '".$item['id']."' and from_user='".$_W['openid']."'");
		if($isjoin>0)message('您已经报名了本活动咯', $this->createMobileUrl('info'), 'error');
		if($_W['member']['uid'])$profile=mc_fetch($_W['member']['uid'],array('mobile','realname','nickname','gender','groupid',));
		$profile['nickname']=$openAry['nickname'];
		if($item['usertype']>-1){
			$groupname=pdo_fetchcolumn("SELECT title FROM ".tablename("mc_groups")." WHERE groupid = '".$item['usertype']."' ");
			$usergroup=pdo_fetchcolumn("SELECT title FROM ".tablename("mc_groups")." WHERE groupid = '".$profile['groupid']."' ");
			if($profile['groupid']!=$item['usertype'])message("抱歉，本次活动只允许<b>".$groupname."</b>级别的粉丝参加！您的级别是<b>".$usergroup."</b>，无法参与本次活动。", $this->createMobileUrl('list'), 'error');
		}
		$parmaTemp="";
		$parma=json_decode($item["parama"],true);
		foreach($parma as $index=>$row) {
			$parmaTemp.="<div class='form-group'><label class='col-xs-3 col-sm-3 col-md-3 control-label'>".$index."</label><div class='col-xs-9 col-sm-9 col-md-9'>";
			switch($row){
				case 1:
					$parmaTemp.="<input type='text' name='parama-key[".$index."]' class='form-control'  /></div></div>";
				break;
				case 2:
					$parmaTemp.="<input type='hidden' name='parama-key[".$index."]'/><button type='button' onclick=\"uploadWimg('".$index."')\" class='btn btn-info btn-block'  />选择图片</button></div></div>";
				break;
			}
		}
		
		
		if ($operation == 'ok') {
			if (checksubmit('submit')) {
				$age=date("Y")-$_GPC['age'];
				if(empty($_GPC['mobile']) || empty($_GPC['realname']) ){
					message('资料没有填写完整！','', 'error');
				}
				$data=array(
					'mobile'=>trim($_GPC['mobile']),
					'realname'=>$_GPC['realname'],
					'nickname'=>$_GPC['nickname'],
					'gender'=>$_GPC['gender'],
				);
				if($_W['member']['uid'])mc_update($_W['member']['uid'], $data);
				
				$insert=array(
					'mobile'=>trim($_GPC['mobile']),
					'realname'=>$_GPC['realname'],
					'nickname'=>$_GPC['nickname'],
					'gender'=>$_GPC['gender'],
					'aid'=>$item['id'],
					'weid'=>$_W['uniacid'],
					'from_user'=>$_W['openid'],
					'createtime'=>strtotime(date('Y-m-d H:i:s')),
					'status'=>1,
				);
				$parama=array();
				if(isset($_GPC['parama-key'])){
					foreach ($_GPC['parama-key'] as $index => $row) {
						if(empty($row))continue;
						$parama[urlencode($index)]=urlencode($row);
					}
				}
				$insert['parama']=urldecode(json_encode($parama));
				pdo_insert("j_activity_winner", $insert);
				
				if($item['credit_join'])mc_credit_update($_W['member']['uid'],'credit1',$item['credit_join'],array($_W['user']['uid'],'活动报名成功添加积分'));
				
				message('报名提交成功！感谢您的参与！如果审核通过，我们将有专人与您联系！', $this->createMobileUrl('info'), 'success');
			}
		}
		include $this->template('reg');
		
	}

	public function doMobileAjaxupload() {
		global $_GPC, $_W;
		$media_id=$_GPC['media_id'];
		load()->func('communication');
		$ACCESS_TOKEN=$_W['account']['access_token']['token'];
		$url="http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$ACCESS_TOKEN."&media_id=".$media_id."";
		echo saveMedia($url);
	}
}