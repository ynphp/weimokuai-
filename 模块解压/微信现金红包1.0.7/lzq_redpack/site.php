<?php
/**
 * 红包模块数据库管理
 *
 * @author Gorden
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Lzq_redpackModuleSite extends WeModuleSite {

	/** 商品分类表 */
	private $redpack = 'we7_redpack_reply';
	private $advertice = 'we7_redpack_advertice';
	private $activity = 'we7_redpack_activity';
	private $share = 'we7_redpack_share';
	public function doWebUseage() {
		include $this->template('useage_display');
	}
	public function doWebDeleteMine() {
		$appid=$this->module['config']['appid'];
		$result = pdo_delete($this->redpack, array('appid'=>$appid));
		message('删除数据成功.共删除'.$result.'条数据！', $this->createWebUrl('redpack'), 'success');
	}
	
	public function doMobileActivity() {
		global $_W;
		$attachurl=$_W['attachurl'];
		$appid=$this->module['config']['appid'];
		$sql = "SELECT * FROM ".tablename($this->activity)." WHERE appid=:appid LIMIT 1";
		$params = array(':appid'=>$appid);
		$result = pdo_fetch($sql, $params);
		
		$sql1 = "SELECT * FROM ".tablename($this->share)." WHERE appid=:appid LIMIT 1";
		$params1 = array(':appid'=>$appid);
		$result1 = pdo_fetch($sql1, $params1);
		
		$now=date("y-m-d h:i:s");
		$starttime=$this->module['config']['starttime'];
		$endtime=$this->module['config']['endtime'];
		if(strtotime($now)<strtotime($starttime)){
			$_flag="活动未开始";//未开始
		}
		if(strtotime($endtime)<strtotime($now)){
			$_flag="活动已结束";//已结束
		}
		load()->model('mc');
		$isExist=mc_fansinfo($_W['openid'],$_W['account']['acid']);
		if($isExist==null){
			$_flag="请先关注公众号！！！";
		}

		$_shake=$_W['siteroot']."app/".$this->createMobileUrl('shake', array('op'=>'display'));
		if($result==null){
			$_title='请至后台活动页面标题';
			$_bg=MODULE_URL."template/mobile/img/bg.jpg";
			$_link='#';
			$_comment="1，关注公众号\\n2，发送关键字\\n3，领取红包";
		}else{
			$_title=$result['title'];
			$_bg=$attachurl.$result['bg'];
			$_link=$result['link'];
			$_comment=$result['comment'];
		}
		if($result1==null){
			$share_title='快来一起抢红包！';
			$share_description="快来一起抢红包！";
			$share_link=$_W['siteroot']."app/".$this->createMobileUrl('activity', array('op'=>'display'));
			$share_icon=MODULE_URL."template/mobile/img/bg.jpg";
		}else{
			$share_title=$result1['title'];
			$share_description=$result1['description'];
			$share_link=$result1['link'];
			$share_icon=$attachurl.$result1['icon'];
		}
		
		
		load()->func('tpl');
		include $this->template('activity');
	}
	
	
	public function doMobileShare() {
		include $this->template('share');
	}
	
	
	public function doMobileShake() {
		include $this->template('shake_index');
	}
	public function doMobileAjax() {
		global $_W, $_GPC;
		$cha=$_GPC['time']-$_GPC['last'];
		if($cha<4000){
			$result=array(
				'icon'=>MODULE_URL."/template/mobile/img/icon.jpg",
				'content'=>"歇一会，您摇得过于频繁了",
				'url'=>''
			);
			return json_encode($result);
		}
		
		$mysession=$_SESSION['openid'];
		if(empty($mysession)||$mysession==''||$mysession==null){
			$img=MODULE_URL."/template/mobile/img/error.png";
			return "{'icon':'','content':'请在微信客户端中打开！！','url':''}";
		}

		$min=$this->module['config']['randmin'];
		$max=$this->module['config']['randmax'];
		$sendNum=$this->module['config']['sendnum'];
		$sendArr= explode(',',$sendNum); 
		$rand=rand($min,$max);				
		$isInclude=in_array($rand,$sendArr);
		if($isInclude){
			
			$now=date("y-m-d h:i:s");
			
			$starttime=$this->module['config']['starttime'];
			$endtime=$this->module['config']['endtime'];
			if(strtotime($now)<strtotime($starttime)){
				$result=array(
				'icon'=>MODULE_URL."/template/mobile/img/icon.jpg",
				'content'=>'您来早了，活动还没开始！！！',
				'url'=>''
				);
				return json_encode($result);
			}
			if(strtotime($endtime)<strtotime($now)){
				$result=array(
				'icon'=>MODULE_URL."/template/mobile/img/icon.jpg",
				'content'=>'您来迟了，活动已结束！！！',
				'url'=>''
				);
				return json_encode($result);
			}else{
				$appid=$this->module['config']['appid'];
				$result = pdo_fetchall("SELECT * FROM ".tablename('we7_redpack_reply')." WHERE openid = :openid and appid = :appid ",array(':openid' => $mysession,':appid' => $appid),'openid');
				if(count($result)==1){
					if($result[$mysession]['hasSub']==true){
						$temp="您已参与过本活动，请不要重复操作！！！";
						$result=array(
							'icon'=>MODULE_URL."/template/mobile/img/icon.jpg",
							'content'=>$temp,
							'url'=>''
						);
					}else{			
						$temp=$this->sendRedpack($mysession);
						$result=array(
							'icon'=>MODULE_URL."/template/mobile/img/icon.jpg",
							'content'=>$temp,
							'url'=>''
						);
					}
				}else if(count($result)==0){
						pdo_insert('we7_redpack_reply', array('appid'=>$appid,'openid'=>$mysession,'hasSub'=>false),false);
						$temp=$this->sendRedpack($mysession);
						$result=array(
							'icon'=>MODULE_URL."/template/mobile/img/icon.jpg",
							'content'=>$temp,
							'url'=>''
						);
				}
				//$temp=$this->sendRedpack($mysession);
				
				return json_encode($result);	
			}	
		}else{
			$sql = 'SELECT COUNT(*) FROM '.tablename($this->advertice).' WHERE appid = :appid';
			$total = pdo_fetchcolumn($sql, array(':appid'=>$this->module['config']['appid']));
			if($total==0){
				$result=array(
				'icon'=>MODULE_URL."/template/mobile/img/icon.jpg",
				'content'=>'什么都没摇到~~~',
				'url'=>''
				);
				return json_encode($result);
			}
			$pagesize=1;
			$pageindex=rand(0,$total-1);
			$sql = 'SELECT * FROM '.tablename($this->advertice).' WHERE appid=:appid LIMIT '.$pageindex.','.$pagesize;
			$params = array(':appid'=>$this->module['config']['appid']);
			$temp = pdo_fetch($sql, $params);
			$result=array(
				'icon'=>$_W['attachurl'].'/'.$temp['icon'],
				'content'=>$temp['content'],
				'url'=>$temp['url']
			);
			return json_encode($result);
			
		}
	}
	public function doWebNetshake() {
		global $_W, $_GPC;
		$ops = array('display', 'edit', 'delete'); // 只支持此 3 种操作.
		$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
		
		if($op == 'display'){
			$url=$_W['siteroot']."app/".$this->createMobileUrl('activity', array('op'=>'display'));
		
			// 处理 GET 提交
			$pageindex = max(intval($_GPC['page']), 1); // 当前页码
			$pagesize = 10; // 设置分页大小
	
			
			$sql = 'SELECT COUNT(*) FROM '.tablename($this->advertice).' where appid = :appid';
			$total = pdo_fetchcolumn($sql, array(':appid'=>$this->module['config']['appid']));
			$pager = pagination($total, $pageindex, $pagesize);
			
			$sql = 'SELECT * FROM '.tablename($this->advertice)." where appid = :appid"." ORDER BY id asc LIMIT ".(($pageindex -1) * $pagesize).','. $pagesize;
			$advertices = pdo_fetchall($sql, array(':appid'=>$this->module['config']['appid']), 'id');
			load()->func('tpl');
			include $this->template('netshake_display');
		}
		if ($op == 'edit') {
			$id = intval($_GPC['id']);
			if(!empty($id)){
				$sql = 'SELECT * FROM '.tablename($this->advertice).' WHERE id=:id  and appid=:appid'.' LIMIT 1';
				$params = array(':id'=>$id,':appid'=>$this->module['config']['appid']);
				$advertice = pdo_fetch($sql, $params);
				
				if(empty($advertice)){
					message('未找到指定的数据.', $this->createWebUrl('netshake'));
				}
			}
			
			if (checksubmit()) {
				$data = $_GPC['advertice']; // 获取打包值
				
				
				(!isset($data['icon'])) && message('图标不能为空');
				(!isset($data['content'])) && message('广告内容不能为空');
				(!isset($data['url'])) && message('广告链接不能为空');
				
				
				
				
				if(empty($advertice)){
					if($data['appid']==''){
						$data['appid']=$this->module['config']['appid'];
					}
					$ret = pdo_insert($this->advertice, $data);
					if (!empty($ret)) {
						$id = pdo_insertid();
					}
				} else {
					$ret = pdo_update($this->advertice, $data, array('id'=>$id));
				}
				
				if (!empty($ret)) {
					message('广告数据保存成功', $this->createWebUrl('netshake', array('op'=>'edit', 'id'=>$id)), 'success');
				} else {
					message('广告数据保存失败');
				}
			}
			
			load()->func('tpl');
			include $this->template('netshake_edit');
		}
		if($op == 'delete') {
			$id = intval($_GPC['id']);
			if(empty($id)){
				message('未找到指定的数据');
			}
			$result = pdo_delete($this->advertice, array('id'=>$id));
			if(intval($result) == 1){
				message('删除数据成功.', $this->createWebUrl('netshake'), 'success');
			} else {
				message('删除数据失败.');
			}
		}
	}
	
	public function doWebShake() {
		global $_W;
		$url=$_W['siteroot']."app/".$this->createMobileUrl('share', array('op'=>'display'));
		message("请复制下面的地址作为周边摇一摇的地址！设置打开客户端蓝牙，在ibeacon模块范围内摇一摇即可！</br>".$url, '', 'success');
	
	}
	public function doWebShakesetting() {
		global $_W, $_GPC;
		$appid=$this->module['config']['appid'];
		$sql = "SELECT * FROM ".tablename($this->activity)." WHERE appid=:appid LIMIT 1";
		$params = array(':appid'=>$appid);
		$result = pdo_fetch($sql, $params);
		$_title=$result['title'];
		$_bg=$result['bg'];
		$_link=$result['link'];
		$_comment=$result['comment'];
		if (checksubmit()) {
			$data = $_GPC['activity']; // 获取打包值	
			if($result==null){
				pdo_insert($this->activity, array('appid'=>$appid,'title'=>$data['title'],'bg'=>$data['bg'],'link'=>$data['link'],'comment'=>$data['comment']),false);
			}else{
				pdo_update($this->activity,array('title'=>$data['title'],'bg'=>$data['bg'],'link'=>$data['link'],'comment'=>$data['comment']),array('id'=>$result['id'],'appid'=>$appid));			
			}
			$_title=$data['title'];
			$_bg=$data['bg'];
			$_link=$data['link'];
			$_comment=$data['comment'];
			message('活动页面数据保存成功', $this->createWebUrl('shakesetting', array()), 'success');
		}
		load()->func('tpl');
		include $this->template('shakesetting');
	}
	
	public function doWebSharesetting() {
		global $_W, $_GPC;
		$appid=$this->module['config']['appid'];
		$sql = "SELECT * FROM ".tablename($this->share)." WHERE appid=:appid LIMIT 1";
		$params = array(':appid'=>$appid);
		$result = pdo_fetch($sql, $params);
		$_title=$result['title'];
		$_description=$result['description'];
		$_icon=$result['icon'];
		$_link=$result['link'];
		$_url=$_W['siteroot']."app/".$this->createMobileUrl('activity', array('op'=>'display'));
		if (checksubmit()) {
			$data = $_GPC['share']; // 获取打包值	
			if($result==null){
				pdo_insert($this->share, array('appid'=>$appid,'title'=>$data['title'],'description'=>$data['description'],'icon'=>$data['icon'],'link'=>$data['link']),false);
			}else{
				pdo_update($this->share,array('title'=>$data['title'],'description'=>$data['description'],'icon'=>$data['icon'],'link'=>$data['link']),array('id'=>$result['id'],'appid'=>$appid));			
			}
			$_title=$data['title'];
			$_description=$data['description'];
			$_icon=$data['icon'];
			$_link=$data['link'];
			message('分享数据保存成功', $this->createWebUrl('sharesetting', array()), 'success');
		}
		load()->func('tpl');
		include $this->template('sharesetting');
	}
	public function doWebRedpack() {
		global $_W, $_GPC;
		
		$ops = array('display', 'edit', 'delete'); // 只支持此 3 种操作.
		$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
		
		if($op == 'display'){
			
			// 处理 GET 提交
			$pageindex = max(intval($_GPC['page']), 1); // 当前页码
			$pagesize = 10; // 设置分页大小
	
			
			$sql = 'SELECT COUNT(*) FROM '.tablename($this->redpack).' where appid = :appid';
			$total = pdo_fetchcolumn($sql, array(':appid'=>$this->module['config']['appid']));
			$pager = pagination($total, $pageindex, $pagesize);
			
			$sql = 'SELECT * FROM '.tablename($this->redpack)." where appid = :appid"." ORDER BY id asc LIMIT ".(($pageindex -1) * $pagesize).','. $pagesize;
			$redpacks = pdo_fetchall($sql,  array(':appid'=>$this->module['config']['appid']), 'id');

			load()->func('tpl');
			include $this->template('redpack_display');
		}
		
		if ($op == 'edit') {
			
			$id = intval($_GPC['id']);
			if(!empty($id)){
				$sql = 'SELECT * FROM '.tablename($this->redpack).' WHERE id=:id and appid=:appid'.' LIMIT 1';
				$params = array(':id'=>$id,':appid'=>$this->module['config']['appid']);
				$redpack = pdo_fetch($sql, $params);
				
				if(empty($redpack)){
					message('未找到指定的数据.', $this->createWebUrl('redpack'));
				}
			}
			
			if (checksubmit()) {
				$data = $_GPC['redpack']; // 获取打包值
				
				
				(!isset($data['openid'])) && message('请填写openid');
				(!isset($data['hasSub'])) && message('请选择是否领取过红包');
				(!isset($data['money'])) && message('请填写领取过的红包金额');
				(!isset($data['time']) )&& message('请填写领取过的红包时间');
				
				
				if(empty($redpack)){
					if($data['appid']==''){
						$data['appid']=$this->module['config']['appid'];
					}
					$ret = pdo_insert($this->redpack, $data);
					if (!empty($ret)) {
						$id = pdo_insertid();
					}
				} else {
					$ret = pdo_update($this->redpack, $data, array('id'=>$id));
				}
				
				if (!empty($ret)) {
					message('红包数据保存成功', $this->createWebUrl('redpack', array('op'=>'edit', 'id'=>$id)), 'success');
				} else {
					message('红包数据保存失败');
				}
			}
			
			load()->func('tpl');
			include $this->template('redpack_edit');
		}
		
		if($op == 'delete') {
			$id = intval($_GPC['id']);
			if(empty($id)){
				message('未找到指定的数据');
			}
			$result = pdo_delete($this->redpack, array('id'=>$id));
			if(intval($result) == 1){
				message('删除数据成功.', $this->createWebUrl('redpack'), 'success');
			} else {
				message('删除数据失败.');
			}
		}
	}
	
	public function sendRedpack($param_openid){
		define('DS', DIRECTORY_SEPARATOR);
		define('SIGNTYPE', "sha1");
		define('APPID',$this->module['config']['appid']);
		define('MCHID',$this->module['config']['mchid']);
		define('PARTNERKEY',$this->module['config']['partner']);
		define('NICK_NAME',$this->module['config']['nick_name']);
		define('SEND_NAME',$this->module['config']['send_name']);
		define('WISHING',$this->module['config']['wishing']);
		define('ACT_NAME',$this->module['config']['act_name']);
		define('REMARK',$this->module['config']['remark']);

		define('apiclient_cert',$this->module['config']['apiclient_cert']);
		define('apiclient_key',$this->module['config']['apiclient_key']);
		define('rootca',$this->module['config']['rootca']);

		define('money',$this->module['config']['money']);
		define('money_extra',$this->module['config']['money_extra']);
		define('min',$this->module['config']['randmin']);
		define('max',$this->module['config']['randmax']);
		define('sendNum',$this->module['config']['sendnum']);
		$money=money+rand(0,money_extra);
		$min=min;
		$max=max;
		$sendNum=sendnum;
		$sendArr= explode(',',sendNum); 
		$rand=rand(min,max);				
		$isInclude=in_array($rand,$sendArr);
		
		if($isInclude){

			$mch_billno=MCHID.date('YmdHis').rand(1000, 9999);//订单号
			include_once(MODULE_ROOT.'/pay'.DS.'WxHongBaoHelper.php');
			$commonUtil = new CommonUtil();
			$wxHongBaoHelper = new WxHongBaoHelper();
								
							
			$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串，不长于32位
			$wxHongBaoHelper->setParameter("mch_billno", $mch_billno);//订单号
			$wxHongBaoHelper->setParameter("mch_id", MCHID);//商户号
			$wxHongBaoHelper->setParameter("wxappid", APPID);
			$wxHongBaoHelper->setParameter("nick_name",NICK_NAME);//提供方名称
			$wxHongBaoHelper->setParameter("send_name", SEND_NAME);//红包发送者名称
			$wxHongBaoHelper->setParameter("re_openid", $param_openid);//相对于医脉互通的openid
			$wxHongBaoHelper->setParameter("total_amount", $money);//付款金额，单位分
			$wxHongBaoHelper->setParameter("min_value", $money);//最小红包金额，单位分
			$wxHongBaoHelper->setParameter("max_value", $money);//最大红包金额，单位分
			$wxHongBaoHelper->setParameter("total_num", 1);//红包发放总人数
			$wxHongBaoHelper->setParameter("wishing",WISHING );//红包祝福诧
			$wxHongBaoHelper->setParameter("client_ip", '127.0.0.1');//调用接口的机器 Ip 地址
			$wxHongBaoHelper->setParameter("act_name", ACT_NAME);//活劢名称
			$wxHongBaoHelper->setParameter("remark",REMARK);//备注信息

			$postXml = $wxHongBaoHelper->create_hongbao_xml();
			
			
			$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		
			$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
		
			$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
			$return_code=$responseObj->return_code;
			$result_code=$responseObj->result_code;
			
			if($return_code=='SUCCESS'){
				if($result_code=='SUCCESS'){
					$total_amount=$responseObj->total_amount*1.0/100;
					$result = pdo_update('we7_redpack_reply',array('hasSub'=>true,'money' =>$total_amount,'time'=>date('Y-m-d H:i:s', time())),array('openid'=>$param_openid,'appid'=>APPID));			
				
					return "红包发放成功！金额为：".$total_amount."元！拆开发放的红包即可领取红包！";
				}else{
				
				
					if($responseObj->err_code=='NOTENOUGH'){
						return "您来迟了，红包已经发完！！！";
					}else if($responseObj->err_code=='TIME_LIMITED'){
						return "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取";
					}else if($responseObj->err_code=='SYSTEMERROR'){
						return "系统繁忙，请稍后再试！";
					}else if($responseObj->err_code=='DAY_OVER_LIMITED'){
						return "今日红包已达上限，请明日再试！";
					}else if($responseObj->err_code=='SECOND_OVER_LIMITED'){
						return "每分钟红包已达上限，请稍后再试！";
					}

					return "红包发放失败！".$responseObj->return_msg."！请稍后再试！";
				}
			}else{
					
				if($responseObj->err_code=='NOTENOUGH'){
					return "您来迟了，红包已经发放完！！!";
				}else if($responseObj->err_code=='TIME_LIMITED'){
					return "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取";
				}else if($responseObj->err_code=='SYSTEMERROR'){
					return "系统繁忙，请稍后再试！";
				}else if($responseObj->err_code=='DAY_OVER_LIMITED'){
					return "今日红包已达上限，请明日再试！";
				}else if($responseObj->err_code=='SECOND_OVER_LIMITED'){
					return "每分钟红包已达上限，请稍后再试！";
				}
				return "红包发放失败！".$responseObj->return_msg."！请稍后再试！";
			}
		}else{
			$result = pdo_update('we7_redpack_reply',array('hasSub'=>true,'money' =>0,'time'=>date('Y-m-d H:i:s', time())),array('openid'=>$param_openid,'appid'=>APPID));				
			return "很遗憾，您没有抢到红包！感谢您的参与！";
		}
		
	}
}