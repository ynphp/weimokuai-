<?php
/**
 * 一分猜红包模块微站定义
 *
 * @author enjoy
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/enjoy_guess');
class Enjoy_guessModuleSite extends WeModuleSite {

// 	public function doMobileEntry() {
// 		//这个操作被定义用来呈现 功能封面
// 	}
// 	public function doWebAct() {
// 		//这个操作被定义用来呈现 管理中心导航菜单
// 	}
// 	public function doWebRed() {
// 		//这个操作被定义用来呈现 管理中心导航菜单
// 	}
public function auth($uniacid,$openid){
	global $_W;
	$userlist=pdo_fetch("select * from ".tablename('enjoy_guess_fans')." where uniacid=".$uniacid." and openid='".$openid."'");
	$userinfo1 = $_W['fans'];
	if(empty($userlist)){
		$userinfo = mc_oauth_userinfo();
		$data=array(
				'uniacid'=>$uniacid,
				'subscribe'=>$userinfo['subscribe'],
				'openid'=>$userinfo['openid'],
				'nickname'=>$userinfo['nickname'],
				'gender'=>$userinfo['sex'],
				'city'=>$userinfo['city'],
				'state'=>$userinfo['province'],
				'country'=>$userinfo['country'],
				'subscribe_time'=>$userinfo['subscribe_time'],
				'avatar'=>$userinfo['avatar'],
				'ip'=>CLIENT_IP
		);
		pdo_insert('enjoy_guess_fans',$data);
		$userlist=pdo_fetch("select * from ".tablename('enjoy_guess_fans')." where uniacid=".$uniacid." and openid='".$openid."'");
	}

	//判断关注状态是否改变
	if($userinfo1['follow']!=$userlist['subscribe']){
	
		$res=pdo_update('enjoy_guess_fans',array('subscribe'=>$userinfo1['follow']),array('uniacid'=>$uniacid,'openid'=>$openid));
		$userlist=pdo_fetch("select * from ".tablename('enjoy_guess_fans')." where uniacid=".$uniacid." and openid='".$openid."'");
	}

	return $userlist;
}
//支付参数
public function doMobilepaylog(){
	global $_W,$_GPC;
	$uniacid=$_W['uniacid'];
	$openid=$_W['openid'];
	$user=$this->auth($uniacid, $openid);
	//生成订单
	$uid=$_GPC['uid'];
	$flag=$_GPC['flag'];
	require_once MB_ROOT . '/controller/Act.class.php';
	$act = new Act();
	$actdetail=$act->getact();
	//判断是否有位置限制
	if(!empty($actdetail['state']) && !empty($user['state'])) {
		$valid = false;
	
	
		if(!empty($actdetail['state']) && !empty($actdetail['city'])) {
			if($actdetail['state'] == $user['state'] && $actdetail['city'] == $user['city']) {
				$valid = true;
			}
		} elseif (!empty($actdetail['state'])) {
			if($actdetail['state'] == $user['state']) {
				$valid = true;
			}
		}
	
	
		if(!$valid) {
			//	return error(-3, "<h4>你的位置是: {$verifyParams['user']['state']}-{$verifyParams['user']['city']}</h4><br><h5>不在本次活动范围. 请期待我们下一次活动</h5>");
// 			$res['user']['state']=$user['state'];
// 			$res['user']['city']=$user['city'];
			$res['error']="您的位置是:".$user['state']."省".$user['city']."市,不在本次活动范围. 请期待我们下一次活动";
			echo json_encode($res);
			exit();
		}
	}

	if($flag==0){
		//说明是老红包

		$rid=$_GPC['rid'];
	}else{
		//说明是新红包
		//先创建红包
		$orid=$_GPC['rid'];
		//查出老红包的范围
		$olist=pdo_fetch("select * from ".tablename('enjoy_guess_red')." where uniacid=".$uniacid." and id=".$orid."");
		$money=rand($olist['cmin'],$olist['cmax']);

		//创建新红包
		$data = array(
				'uniacid' => $uniacid,
				'title'=>$user['nickname']."的红包",
				'joinnum'=>0,
				'money'=>$money,
				'cmin'=>$olist['cmin'],
				'cmax'=>$olist['cmax'],
				'pay'=>$olist['pay'],
				'virtual'=>$olist['virtual'],
				'brush'=>$olist['brush'],
				'times'=>$olist['times'],
				'status'=>-1,
				'img'=>$user['avatar'],
				'stime'=>TIMESTAMP
				//'etime'=>strtotime($_GPC['etime'])+59
		);
		pdo_insert('enjoy_guess_red', $data);
		$rid = pdo_insertid();
		
	}
	//$message=json_encode($data);
	$puid=0;
	$user = $this->auth($uniacid,$openid);
	//$openid=$user['openid'];
	//先查询是否存在订单
	$tid=pdo_fetchcolumn("select id from ".tablename('enjoy_guess_paylog')." where uniacid=".$uniacid." and uid=".$uid." and rid=".$rid."");
	//查询支付的钱
	$red=pdo_fetch("select * from ".tablename('enjoy_guess_red')." where id=".$rid." and uniacid=".$uniacid."");
	$fee=$red['pay']*0.01;

	if($fee==0){
		$fee=0.01;
	}
	if(empty($tid)){
		//生成订单
		$data=array(
				'uniacid'=>$uniacid,
				'uid'=>$uid,
				'rid'=>$rid,
				'fee'=>$fee,
				'status'=>0,
				'createtime'=>TIMESTAMP

		);
		pdo_insert('enjoy_guess_paylog',$data);
		$tid=pdo_insertid();
	}
	// 		echo "select id from ".tablename('enjoy_guess_paylog')." where uniacid=".$uniacid." and uid=".$uid." and rid=".$rid."";
	// 		exit();

	$params=array();
	$params['tid'] = $tid;
	$params['user'] = $openid;
	$params['fee'] = $fee;
	$params['title'] = '支付'.$fee.'元猜红包';
	$params['ordersn'] = '12345789';
	$params['virtual'] = 1;

	$params['module'] = $this->module['name'];
	$pars = array();
	$pars[':uniacid'] = $_W['uniacid'];
	$pars[':module'] = $params['module'];
	$pars[':tid'] = $params['tid'];

	if($params['fee'] <= 0) {
		$pars['from'] = 'return';
		$pars['result'] = 'success';
		$pars['type'] = 'alipay';
		$pars['tid'] = $params['tid'];
		$site = WeUtility::createModuleSite($pars[':module']);
		$method = 'payResult';
		if (method_exists($site, $method)) {
			exit($site->$method($pars));
		}
	}

	$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
	$log = pdo_fetch($sql, $pars);
	if(!empty($log) && $log['status'] == '1') {
		$message='这个订单已经支付成功, 不需要重复支付.';
	}
	$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
	if(!is_array($setting['payment'])) {
		$message='没有有效的支付方式, 请联系网站管理员.';
	}
	$pay = $setting['payment'];
	if (!empty($pay['credit']['switch'])) {
		$credtis = mc_credit_fetch($_W['member']['uid']);
	}
	$you = 0;

	$res['error']=$message;
	$res['params']=base64_encode(json_encode($params));;
	echo json_encode($res);
}	

public function payResult($params) {
	global $_W;

	//$fee = intval($params['fee']);
	$data = array('status' => $params['result'] == 'success' ? 1 : 0);
	$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');



	if ($params['result'] == 'success' && $params['from'] == 'notify') {
		//if($params['result'] == 'success'){

		$paylist=pdo_fetch("select rid,uid from ".tablename('enjoy_guess_paylog')." where id=".$params['tid']."");
		$uid=intval($paylist['uid']);
		$rid=intval($paylist['rid']);
		$uniacid=intval($params['uniacid']);
		//先查询是否已经将返现存入数据库了
// 		$count=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_guess_moneylog')." where rid=".$rid." and
// 			 uniacid=".$uniacid." and uid=".$uid." ");
// 		if($count<1){
			//支付成功，修改支付状态
			$ycode=$this->str16(4);
			pdo_update('enjoy_guess_paylog',array('status'=>1,'ycode'=>$ycode,'createtime'=>TIMESTAMP),array('id'=>$params['tid']));
			//奖品的购买数量++
			pdo_query("update ".tablename('enjoy_guess_red')." set joinnum=joinnum+1 where uniacid=".$params['uniacid']." and id=".$rid."");
			//插入机会表
			$data=array(
				'uniacid'=>$uniacid,
				'uid'=>$uid,
				'rid'=>$rid,
				'createtime'=>TIMESTAMP	
			);
			pdo_insert('enjoy_guess_log',$data);
			//红包显现
			pdo_update('enjoy_guess_red',array('status'=>0),array('uniacid'=>$uniacid,'id'=>$rid));
			//还要生成一个兑奖码
// 			$gnum=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_guess_dcode')." where uniacid=".$params['uniacid']." and rid=".$rid."");
// 			$gnum=$gnum+1;
// 			$dcode=strreplace($gnum);
// 			//查看邀请码是不是已经存在了
// 			$existid=pdo_fetchcolumn("select id from ".tablename('enjoy_guess_dcode')." where uniacid=".$uniacid." and rid=".$rid." and dcode='".$dcode."'");
// 			if($existid){
// 				$dcode='X'.strreplace($gnum);
// 			}
// 			$data=array(
// 					'uniacid'=>$params['uniacid'],
// 					'rid'=>$rid,
// 					'uid'=>$uid,
// 					'gnum'=>$gnum,
// 					'dcode'=>$dcode,
// 					'createtime'=>TIMESTAMP
// 			);
// 			$insucess=pdo_insert('enjoy_guess_dcode',$data);

// 			if($insucess>0){

// 				//没有返现
// 				//开始发钱
// 				//金额
// 				$red=pdo_fetch("select * from ".tablename('enjoy_guess_red')." where id=".$rid." and uniacid=".$uniacid."");
					
					
// 				$fee=rand($red['gmin'],$red['gmax']);

// 				//记录到数据库里
// 				$realfee=$fee*0.01;

// 				$data=array(
// 						'uniacid'=>$uniacid,
// 						'rid'=>$rid,
// 						'uid'=>$uid,
// 						'money'=>$realfee,
// 						'status'=>0,
// 						//	'backno'=>$pars['partner_trade_no'],
// 						'createtime'=>TIMESTAMP
// 				);
// 				$minsert=pdo_insert('enjoy_guess_moneylog',$data);



					
// 			}




			// 							header("location:../../app/" .$this->createMobileUrl('entry',array('rid'=>$rid,'dcode'=>$dcode))."");
		//}
	}

	$data['paytype'] = $paytype[$params['type']];
	if ($params['type'] == 'wechat') {
		$data['transid'] = $params['tag']['transaction_id'];
	}
	if ($params['type'] == 'delivery') {
		$data['status'] = 1;

	}
	if ($params['from'] == 'return') {

		$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
		$credit = $setting['creditbehaviors']['currency'];
		$paylist=pdo_fetch("select rid,uid from ".tablename('enjoy_guess_paylog')." where id=".$params['tid']."");
		$uid=$paylist['uid'];
		$rid=$paylist['rid'];
		$uniacid=$params['uniacid'];
		//查询返现金额
// 		
			
			
			
			
			
			
		//$dcode=pdo_fetchcolumn("select dcode from ".tablename('enjoy_guess_dcode')." where uniacid=".$uniacid." and rid=".$rid." and uid=".$uid."");
		header("location:../../app/" .$this->createMobileUrl('detail',array('rid'=>$rid))."");

	}
}



function trimall($str)//删除空格
{
	$qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");

	return str_replace($qian,$hou,$str);
}
function shareth($str,$n,$y,$s)//删除空格
{
	if($s==1){
		$s="猜低了";
	}elseif($s==2){
		$s="猜对了";
	}elseif($s==3){
		$s="猜高了";
	}else{
		$s="想请你一起来猜";
	}
	if(empty($y)){
		$y="还没有";
	}
	$qian=array("#user#","#ycode#","#res#");$hou=array($n,$y,$s);

	return str_replace($qian,$hou,$str);
}


// function str16($str){
// 	//生成6位邀请码
// 	$str=dechex($str);
// 	//向左补齐0
// 	$str= str_pad($str, 4, "v", STR_PAD_LEFT);
// 	return trim($str);
// }
function str16($len)
{
	$chars_array = array(
			"0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
			"l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
			"w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
			"H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
			"S", "T", "U", "V", "W", "X", "Y", "Z",
	);
	$charsLen = count($chars_array) - 1;

	$outputstr = "";
	for ($i=0; $i<$len; $i++)
	{
		$outputstr .= $chars_array[mt_rand(0, $charsLen)];
	}
	return $outputstr;
}
public function doMobileinpcode(){
	global $_W,$_GPC;
	$ycode=$this->trimall($_GPC['yq_code']);
	//$ycode = preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/","",$ycode);
	$rid=$_GPC['rid'];
	$uid=$_GPC['uid'];
	$uniacid=$_W['uniacid'];
	
	//看此人的邀请次数有没有达到上限
	$act=pdo_fetch("select * from ".tablename('enjoy_guess_reply')." where uniacid=".$_W['uniacid']."");
	
	$countchance=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_guess_log')." where uniacid=".$_W['uniacid']."
			and uid=".$uid." and rid=".$rid."");
	if($act['chance']<=$countchance){
		//没有机会了
		$res['msg']="您猜红包次数已经达到上限，去猜猜其他红包吧";
		echo json_encode($res);
		exit();
	}
	//接收邀请码
	//查询邀请码是否存在
	$countY=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_guess_paylog')." where uniacid=".$uniacid." and rid=".$rid." and ycode='".$ycode."'
			and uid!=".$uid."");

	if($countY>0){
		//存在该邀请码,看用户是否已经用过此邀请码
		$countX=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_guess_log')." where uniacid=".$uniacid." and rid=".$rid." and uid=".$uid."
				and ycode='".$ycode."'");

		if($countX>0){
			$res['msg']="您已经使用过此邀请码了";
		}else{
			//插入数据库
			$data=array(
					'uniacid'=>$uniacid,
					'rid'=>$rid,
					'uid'=>$uid,
					'ycode'=>$ycode,
					'createtime'=>TIMESTAMP
			);
			$inset=pdo_insert('enjoy_guess_log',$data);
			if($inset==0){
				$res['msg']="系统忙，请重新输入";
			}else{
				$res['flag']=1;
			}
		}

	}else{
		$res['msg']="邀请码无效";
	}

	echo json_encode($res);
}

//开启仿刷模式
public function brush($red=array(),$tmp){
	global $_W;
	$uniacid=$_W['uniacid'];

	//检测下范围差
	if($red['brush']>=$tmp){
	
		//更新次数
		pdo_query("update ".tablename('enjoy_guess_red')." set realtimes=realtimes+1 where id=".$red['id']);
		//查找次数
		$realtimes=pdo_fetchcolumn("select realtimes from ".tablename('enjoy_guess_red')." where id=".$red['id']);
		//对比
		if($realtimes>=$red['times']){
			//机器人自动中奖
			//在会员库里面随机找寻昵称和头像
			$nickname=pdo_fetchcolumn("select nickname from ".tablename('mc_members')." where nickname!='' ORDER BY RAND() LIMIT 1");
			$avatar=pdo_fetchcolumn("select avatar from ".tablename('mc_members')." where avatar!='' ORDER BY RAND() LIMIT 1");
			//然后让它中奖，结束此次活动
			$data = array(
					'uniacid' => $uniacid,
					'rid' => $red['id'],
					'nickname'=>$nickname,
					'uid'=>0,
					'img'=>$avatar,
					'money'=>$red['money']*0.01,
					'title'=>$red['title'],
					'createtime'=>TIMESTAMP
			);
			
			//添加2条记录到log表里
			$datalog=array(
					'uniacid' => $uniacid,
					'rid' => $red['id'],
					'nickname'=>$nickname,
					'uid'=>0,
					'avatar'=>$avatar,
					'createtime'=>TIMESTAMP,
					'gtime'=>TIMESTAMP
			);
	
			$a=array();
			while(count($a)<2) $a[rand(-$red['brush'],$red['brush'])]=null; //利用键的唯一性，确保不同的值
			$a=array_keys($a);
			for($i=0;$i<2;$i++){
				$ftemp=$a[$i];
				$fee=$red['money']+$ftemp;
			if($ftemp>0){
				$status=3;
			}elseif($ftemp<0){
				$status=1;
			}else{
				$status=0;
			}
			$datalog['fee']=$fee*0.01;
			$datalog['status']=$status;
			pdo_insert('enjoy_guess_log', $datalog);
			}

			pdo_insert('enjoy_guess_roll', $data);
			pdo_update('enjoy_guess_red',array('etime'=>TIMESTAMP,'status'=>1),array('id'=>$red['id'],'uniacid'=>$uniacid));
		
		}
	}
}

























	
	
}