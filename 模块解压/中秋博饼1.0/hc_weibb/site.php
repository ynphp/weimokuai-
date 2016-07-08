<?php
/**
 * 中秋博饼模块
 *
 * 疯狂却不失细腻——微赞科技		www.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class hc_weibbModuleSite extends WeModuleSite {

	public function doWebFormDisplay() {
		global $_W, $_GPC;
		$result = array('error' => 0, 'message' => '', 'content' => '');
		$result['content']['id'] = $GLOBALS['id'] = 'add-row-news-'.$_W['timestamp'];
		$result['content']['html'] = $this->template('item', TEMPLATE_FETCH);
		exit(json_encode($result));
	}
	
	public function doWebCreditlog(){
		global $_W,$_GPC;
		$rid = intval($_GPC['id']);
		$psize = 30;
		$pindex = max(1, intval($_GPC['page']));
		$total = pdo_fetchall( "  SELECT COUNT(*) FROM ".tablename('hc_weibb_dayscredit')." AS d LEFT JOIN ".tablename('hc_weibb_member')." AS m ON d.mid=m.id WHERE d.rid='".$rid."' GROUP BY d.openid " );
		$total = sizeof($total);
		$creditlog = pdo_fetchall(" SELECT SUM(d.credit) as credit, m.mobile, m.createtime, m.nickname FROM ".tablename('hc_weibb_dayscredit')." AS d LEFT JOIN ".tablename('hc_weibb_member')." AS m ON d.mid=m.id WHERE d.rid='".$rid."' GROUP BY d.openid ORDER BY credit  DESC  LIMIT ".($pindex - 1) * $psize.",{$psize}  ");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('creditlog');
	}
	
	public function doWebCreditLogSearch(){
		global $_W,$_GPC;
		$rid = intval($_GPC['id']);
		$key = $_GPC['key'];
		if(empty($key)){
			message("请选择查询条件");
		}
		$keyword = trim($_GPC['keyword']);
		if(empty($keyword) && $keyword !=0){
			message("请输入查询条件");
		}
		if($key == '积分'){
			if(!is_numeric($keyword)){
				message('请输入数字');
			}
			$keyword = intval($keyword);
			$total = pdo_fetchall(" SELECT SUM(d.credit) as credit, m.mobile, m.createtime, m.nickname FROM ".tablename('hc_weibb_dayscredit')." AS d LEFT JOIN ".tablename('hc_weibb_member')." AS m ON d.mid=m.id WHERE d.rid='".$rid."' AND d.credit >= '".$keyword."'GROUP BY d.openid ORDER BY credit  DESC  ");
			$total = sizeof($total);
			$psize = 30;
			$pindex = max(1, intval($_GPC['page']));
			$creditlog = pdo_fetchall(" SELECT SUM(d.credit) as credit, m.mobile, m.createtime, m.nickname FROM ".tablename('hc_weibb_dayscredit')." AS d LEFT JOIN ".tablename('hc_weibb_member')." AS m ON d.mid=m.id WHERE d.rid='".$rid."' AND d.credit >= '".$keyword."'GROUP BY d.openid ORDER BY credit  DESC  LIMIT ".($pindex - 1) * $psize.",{$psize}   ");
			$pager = pagination($total, $pindex, $psize);
	
		}
		if($key == '昵称'){
			$creditlog = pdo_fetchall(" SELECT SUM(d.credit) as credit, m.mobile, m.createtime, m.nickname FROM ".tablename('hc_weibb_dayscredit')." AS d LEFT JOIN ".tablename('hc_weibb_member')." AS m ON d.mid=m.id WHERE d.rid='".$rid."' AND m.nickname like '%".$keyword."%' GROUP BY d.openid ORDER BY credit  DESC  ");
		}
		if($key == '手机号码'){
			$creditlog = pdo_fetchall(" SELECT SUM(d.credit) as credit, m.mobile, m.createtime, m.nickname FROM ".tablename('hc_weibb_dayscredit')." AS d LEFT JOIN ".tablename('hc_weibb_member')." AS m ON d.mid=m.id WHERE d.rid='".$rid."' AND m.mobile like '%".$keyword."%' GROUP BY d.openid ORDER BY credit  DESC  ");
		}
		include $this->template('creditlog');
	}
	
	
	public function doMobileindex(){
		global $_W,$_GPC;
		$rid = intval($_GPC['id']);
		$fromuser = $_W['openid'];
		if(empty($rid)){
			message('游戏不存在');
		}
		$weibb = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_reply')." WHERE rid=".$rid." " );
		//检测浏览器
		$this->checkBowser();
		//为转发者增加次数
		if($_GPC['share'] == 'share'){
			$shareid = intval($_GPC['shareid']);
			if(!empty($shareid)){
				$this->addLotteryNum($shareid,$weibb,$rid);
			}
		}
		
		
		//检测用户是否已关注公众号
		$this->checkFollow($rid);
		//第一次玩，插入member表
		$member = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_member')." WHERE openid='".$fromuser."' AND rid=".$rid." " );
		if(empty($member)){
			$insert = array(
				'weid' => $_W['uniacid'],
				'openid' => $_W['openid'],
				'rid' => $rid,
				'createtime' => TIMESTAMP,
			);
			$flag = pdo_insert('hc_weibb_member',$insert);
			if($flag == false){
				message('非法访问，请重新发送消息进入游戏');
			}
		$member = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_member')." WHERE openid='".$fromuser."' AND rid=".$rid." " );
		}
		include $this->template('index');
	}
	
	//修改资料
	public function doMobileregist(){
		global $_GPC,$_W;
		$this->checkFollow;
		$this->checkBowser;
		$rid = intval($_GPC['id']);
		$weibb = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_reply')." WHERE rid='".$rid."' " );
		$member = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_member')." WHERE openid='".$_W['openid']."' AND rid=".$rid." " );
		if($_GPC['op'] == 'regist'){
			$nickname = trim($_GPC['nickname']);
			$mobile = trim($_GPC['mobile']);
			if(empty($mobile)){
				message('请填写手机号码');
			}
			$chars = "/^((\(\d{2,3}\))|(\d{3}\-))?1(3|5|8|9)\d{9}$/";
			$flag = preg_match($chars, $mobile);
			if($flag == false){
				message("请填写正确的手机格式");
			}
			if(empty($member)){
				$insert = array(
					'weid' => $_W['uniacid'],
					'openid' => $_W['openid'],
					'nickname' => $nickname,
					'mobile' => $mobile,
					'rid' => $rid,
				);
				pdo_insert('hc_weibb_member',$insert);
			}else{
				$member['nickname'] = $nickname;
				$member['mobile'] = $mobile;
				pdo_update('hc_weibb_member',$member,array('id'=>$member['id']));
			}
			message('保存成功',$this->createMobileUrl('Lottery',array('id'=>$rid)),'success');
		}
		
		include $this->template('register');
	}
	
	public function doMobileLottery() {
		global $_GPC, $_W;
		$title = '摇色子抽奖';
		$id = intval($_GPC['id']);
		$rid = intval($_GPC['id']);
		$fromuser = $_W['openid'];
		//检测浏览器
		$this->checkBowser();
		$weibb = pdo_fetch("SELECT * FROM ".tablename('hc_weibb_reply')." WHERE rid = '$id' LIMIT 1");
		if (empty($weibb)) {
			message('非法访问，请重新发送消息进入摇色子页面！');
		}
		
		if(TIMESTAMP <= $weibb['start_time']){
			message('活动尚未开始','','error');
		}if(TIMESTAMP >= $weibb['end_time']){
			message('活动已经结束','','error');
		}
		//检测是否关注
		$this->checkFollow($rid);
		//第一次玩，插入member表
		$member = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_member')." WHERE openid='".$fromuser."' AND rid=".$rid." " );
		if(empty($member)){
			$insert = array(
				'weid' => $_W['uniacid'],
				'openid' => $_W['openid'],
				'rid' => $rid,
				'createtime' => TIMESTAMP,
			);
			$flag = pdo_insert('hc_weibb_member',$insert);
			if($flag == false){
				message('非法访问，请重新发送消息进入游戏');
			}
		$member = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_member')." WHERE openid='".$fromuser."' AND rid=".$rid." " );
		}
		
				/*
		//判断转发
		if(intval($_GPC['uid'])){

			//存入被转发过来的数据，并给转发的人增加次数,使用cookie判断
			//	$from_user = $_W['fans']['from_user'];
			$cookies = "huochiweibb".$id;

			if(!isset($_COOKIE[$cookies])){ 
				//如果cookie不存在，给转发人增加次数，同时存入cookie

				setcookie($cookies,1,time()+86400);
				
				$user = pdo_fetch(" SELECT * FROM ".tablename('weibb_share')." WHERE `id` = '".$_GPC['uid']."' ");
				
				$count = $weibb['sharecount'];
				$user['count'] = $user['count'] + $count;				
				$user['shareFriend'] = $user['shareFriend'] + 1;				
				pdo_update('weibb_share',$user,array('id'=>$user['id']));
			}
			$id = $_GPC['id'];						
		}
		*/
		$weibb['description']=str_replace("\n","",$weibb['description']);
		$weibb['description']=str_replace("\r","",$weibb['description']);
		/*
		$sql="SELECT COUNT(*) FROM ".tablename('hc_weibb_user')." WHERE createtime > '".strtotime(date('Y-m-d'))."' AND from_user = '$fromuser' and rid=".$id;
		
		$totals = pdo_fetchcolumn($sql);
		$arr_times=$this->get_today_times($totals,$weibb['maxlottery'],$weibb['prace_times'],intval($myuser['count']));
		//var_dump($arr_times);
		$shareTotal = $myuser['shareFriend'];
		if($shareTotal == null) $shareTotal =0;
		*/
		//当前剩余次数和朋友加油次数
		$callback = $this->getLotteryNum($id,$fromuser);

		$arr_times['today_has'] = $callback['lotteryNum'];
		$shareTotal = $callback['sharecount'];
		if($callback == "used" ){
			$arr_times['today_has'] = 0;
			$shareTotal = 0;
		}
		if($callback == "error" ){
			message("必须先注册才能进行游戏",$weibb['guanzhuUrl'],'success');
		}
		
		//卡券
		$card_id=$weibb['card_id'];
		$sendopenid = $_W['openid'];
		if(!empty($card_id)){
			$cardArry = $this->getCardTicket($card_id,$sendopenid);
		}else{
			$cardArray = "no";
		}
		
		
		include $this->template('weibb');
	}
	
	
	//ajax轻松，获得博饼结果
	public function doMobileGetAward() {
		global $_GPC, $_W;
		$fromuser = $_W['openid'];
		$this->checkBowser();
		
		if (empty($fromuser)) {
			exit('非法参数1！');
		}
		$id = intval($_GPC['id']);
		if (!$id) {
			exit('非法参数2！');
		}
		$weibb = pdo_fetch("SELECT * FROM ".tablename('hc_weibb_reply')." WHERE rid = '$id' LIMIT 1");
		
		if (empty($weibb)) {
			exit('非法参数2！');
		}
		//用户今日已摇奖次数
		/*
		$sql="SELECT COUNT(*) FROM ".tablename('hc_weibb_user')." WHERE createtime > '".strtotime(date('Y-m-d'))."' AND from_user = '$fromuser' and rid = '$id' ";
		$totals = pdo_fetchcolumn($sql);
		$myuser=pdo_fetch("SELECT id,count FROM ".tablename('hc_weibb_share')." WHERE  from_user = '{$fromuser}' AND rid=".$id);
		
		$arr_times=$this->get_today_times($totals,$weibb['maxlottery'],$weibb['prace_times'],intval($myuser['count']));
		
		$user=array();
		$user['name']='ss';
		$user['num']=$arr_times['today_has']-1;
		$user['usercont']=$arr_times['todayalltimes'];
		*/
		$user=array();
		$user['name']='ss';
		$user['num']=0;
		$user['usercont']=0;
		$callback = $this->getLotteryNum($id,$fromuser);
		if($callback == "error"){
			echo json_encode(array('user'=>$user,'level'=>1,'errmessage'=>'获取会员id失败'));
			exit;
		}
		if($callback == "used"){
			echo json_encode(array('user'=>$user,'level'=>1,'errmessage'=>'今日次数已用光'));
			exit;
		}
		$user['num']=$callback['lotteryNum'];
		//抽奖次数用完
		/*
		if ($arr_times['today_has'] <=0 ) {
			echo json_encode(array('user'=>$user,'level'=>1,'errmessage'=>'今天你的抽奖次数用完了,明天再来吧!'));
			exit;
		}
		*/
		//点数概率
		$level=array();

		
		//$awardList = pdo_fetchall(" SELECT * FROM ims_hc_weibb_award WHERE `rid`=".$_GPC['id']." and total>0");
		$awardList = pdo_fetchall(" SELECT * FROM ims_hc_weibb_award WHERE `rid`=".$_GPC['id']." ");
		/*
		//如果奖品数量为0，则一直未中奖
		if(!$awardList){
				$rand = mt_rand(1,3);
				if($rand == 1){
					$ssssss=array(5,1,2,1,3,3);
				}
				if($rand == 2){
					$ssssss=array(6,2,2,1,1,3);
				}
				if($rand == 3){
					$ssssss=array(5,6,2,1,5,1);
				}
				
				
				$level['a']=$ssssss[0];
				$level['b']=$ssssss[1];
				$level['c']=$ssssss[2];
				$level['d']=$ssssss[3];
				$level['e']=$ssssss[4];
				$level['f']=$ssssss[5];
				$level['title']='weibb';
				
				$level['key']= $level['a'] + $level['b'] + $level['c'] + $level['d'] + $level['e'] + $level['f'] ;
				$userdate = array(
					'rid'=>$id,
					'title'=>'未中奖',
					'from_user'=>$fromuser,
					'iszhuangyuan'=>0,
					'createtime'=>TIMESTAMP,
					'titleid' => 0,
					'iszhuangyuan' => -1
				);
				pdo_insert('hc_weibb_user',$userdate);
				echo json_encode(array('user'=>$user,'level'=>$level,'huode'=>'未中奖','errmessage'=>''));
				exit;
			
			}
			*/
			//var_dump($awardList);
			//global $totalProbaility;
			//global $totalProbaliltyArray;
			//global $result;
			$result = -1;
			$totalProbaility = 0;
			$totalProbaliltyArray = array();
			foreach($awardList as $k=>$list){
					//获取总概率
				$totalProbaility =$totalProbaility + $list['probalilty'];
				$totalProbaliltyArray[$k]['probalilty'] = $list['probalilty'];
				$totalProbaliltyArray[$k]['title'] = $list['title'];
				$totalProbaliltyArray[$k]['credit'] = $list['credit'];
				$totalProbaliltyArray[$k]['id'] = $list['id'];
			}//var_dump($totalProbaility);
			//var_dump($totalProbaliltyArray);
			//如果总概率小于100，添加一个不中奖的概率使之等于100
			if($totalProbaility <100){
					
				$a = sizeof($totalProbaliltyArray);
				$totalProbaliltyArray[$a+1]['probalilty'] = 100-$totalProbaility;
				$totalProbaliltyArray[$a+1]['title'] = '未中奖';
				$totalProbaliltyArray[$a+1]['credit'] = 0;
				$totalProbaility = 100;
			}
			//概率*10000去除小数点
			$totalProbaility = $totalProbaility*10000;
			//	var_dump($totalProbaliltyArray);
			foreach ($totalProbaliltyArray as $key => $probaility) { 
					//mt_srand((double) microtime()*1000000);
					//在1和10000之间取随机数，如果随机数小于等于当前循环概率数组中的概率，这跳出并中奖，否则总概率减去未中奖概率再次循环
					//比如中奖概率分别为10,20,30,40，要中第二个奖的概率是20/100 按照算法为第一次不中奖，第二次中奖 90/100*20*90 = 20/100
					$randNum = mt_rand(1, $totalProbaility); 
					if ($randNum <= $probaility['probalilty']*10000) { 
						$huode = $probaility['title']; 
						$credit = $probaility['credit']; 
						break; 
					} else { 
						$totalProbaility -= $probaility['probalilty']*10000; 
					} 
			} 
				//  unset ($proArr); 
			if($huode == null){
				$huode =  "未中奖";
			}
		$res = $this->getDiceNum($huode);
		$level['a']=$res['a'];
		$level['b']=$res['b'];
		$level['c']=$res['c'];
		$level['d']=$res['d'];
		$level['e']=$res['e'];
		$level['f']=$res['f'];
		$level['title']='weibb';
		$level['key']= $level['a'] + $level['b'] + $level['c'] + $level['d'] + $level['e'] + $level['f'] ;
		
		$userdate = array(
			'rid'=>$id,
			'title'=>$huode,
			'credit'=>intval($credit),
			'from_user'=>$fromuser,
			'iszhuangyuan'=>0,
			'createtime'=>TIMESTAMP,
		);
		//这里是插入中奖名单
		$userdate['iszhuangyuan'] = 0;
		if($huode == '一秀') {
			
			$userdate['titleid'] = 1;
		}
		if($huode == '二举') {
			
			$userdate['titleid'] = 2;

		}
		if($huode == '三红') {
			
			$userdate['titleid'] = 3;

		}
		if($huode == '四进') {
				
			$userdate['titleid'] = 4;

		}
		if($huode == '对堂') {
			
			$userdate['titleid'] = 5;

		}if($huode == '普通状元') {
			$userdate['iszhuangyuan'] = 1;
			$userdate['titleid'] = 6;

		}
		if($huode == '五红'){
			$userdate['iszhuangyuan'] = 2;
			$userdate['titleid'] = 8;

		}
		if($huode == '五子'){
			$userdate['iszhuangyuan'] = 3;
			$userdate['titleid'] = 7;

		}
		if($huode == '六杯红'){
				$userdate['iszhuangyuan'] = 4;
				$userdate['titleid'] = 9;

		}
		if($huode == '状元插金花'){
				$userdate['iszhuangyuan'] = 5;
				$userdate['titleid'] = 10;

		}
		if($huode == '未中奖'){
				$userdate['iszhuangyuan'] = -1;
				$userdate['titleid'] = 0;

		}
		$rid = $_GPC['id'];
		pdo_insert('hc_weibb_user',$userdate);
		//积分插入每天积分记录表
		$todayStart = strtotime(date('Y-m-d'));
		$todayCredit = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_dayscredit')."  WHERE openid='".$fromuser."' AND createtime='".$todayStart."'  AND rid='".$rid."' " );
		if(empty($todayCredit)){
			$mid = pdo_fetchcolumn( " SELECT id FROM ".tablename('hc_weibb_member')." WHERE openid='".$fromuser."' AND rid='".$rid."' " );
			$insert = array(
				'weid' => $_W['uniacid'],
				'openid'=> $fromuser,
				'createtime' => $todayStart,
				'mid' => $mid,
				'credit' => intval($credit),
				'rid' => $rid
			);
			pdo_insert('hc_weibb_dayscredit',$insert);
			
		}else{
			$todayCredit['credit'] = intval($todayCredit['credit']) + intval($credit);
			pdo_update('hc_weibb_dayscredit',$todayCredit,array('id'=>$todayCredit['id']));
		}
		//积分增加记录
		$uid = $_W['member']['uid'];
		if(!empty($uid)){
			mc_credit_update($uid,'credit1',$credit,array(1=>'博饼'));
		}
		
		//中奖奖品数量减一
		/*
		if($huode != '未中奖'){
			pdo_query(" update  ".tablename('hc_weibb_award')." SET total=total-1  WHERE `rid`=".$rid." AND `title`='".$huode."' AND total>0 ");
		
		}
		*/
		$user['num']=$callback['lotteryNum'] - 1;
		if($user['num'] <=0 ){
			$user['num'] = 0;
		}
		$user['usercont'] = $callback['sharecount'];
		$user['isshare'] = $callback['isshare'];
		if($callback['isshare'] == 2){
			//系统的次数用完即减去朋友送的
				pdo_query("UPDATE  ".tablename('hc_weibb_share')." SET count=count-1 WHERE from_user = '{$fromuser}' AND rid=".$id);
				$user['usercont']=$user['usercont']-1;
				if($user['usercont'] <=0 || $user['usercont']==null ){
					$user['usercont'] = 0;
				}
		}
		
		echo json_encode(array('user'=>$user,'level'=>$level,'huode'=>$huode,'errmessage'=>''));
		exit;
	}
	
	//查看我的积分
	public function doMobilemyCredit(){
		global $_W,$_GPC;
		$rid = intval($_GPC['id']);
		$this->checkBowser();
		$mycredit = pdo_fetchall(" SELECT * FROM ".tablename('hc_weibb_dayscredit')." WHERE openid='".$_W['openid']."' AND rid='".$rid."' ");
		include $this->template('myaward');
	}
	//积分排行
	public function doMobilecreditRank(){
		global $_W,$_GPC;
		$rid = intval($_GPC['id']);
		$this->checkBowser();
		$mycredit = pdo_fetchall(" SELECT SUM(d.credit) as credit, m.nickname FROM ".tablename('hc_weibb_dayscredit')." AS d LEFT JOIN ".tablename('hc_weibb_member')." AS m ON d.mid=m.id WHERE d.rid='".$rid."' GROUP BY d.openid ORDER BY credit DESC  LIMIT 50  ");
		/*
		$newArr=array();
		for($j=0;$j<count($mycredit);$j++){
		$newArr[]=$mycredit[$j]['flag'];
		}
		array_multisort($newArr,SORT_DESC,$mycredit,SORT_DESC);
		*/
		
		include $this->template('rank');
	}
	
	//函数部分
	
	
	//检测用户是否已关注公众号
	public function checkFollow($rid){
		global $_W,$_GPC;
		$openid = $_W['openid'];
		$weibb = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_reply')." WHERE rid='".$rid."' " );
		if(empty($openid)){
			message( " 必须先关注本公众号才能进行游戏 ", $weibb['guanzhuUrl'], 'success' );
		}
		$fans = pdo_fetch( " SELECT * FROM ".tablename('mc_mapping_fans')." WHERE openid='".$openid."' AND uniacid=".$_W['uniacid']." " );
		if(empty($fans) || $fans['follow'] == 0){
			message( " 必须先关注本公众号才能进行游戏 ", $weibb['guanzhuUrl'], 'success' );
		}
		
		//为已关注没有uid的用户添加uid

		  $openid = $_W['openid'];
          $uid = $_W['member']['uid'];
          if(!empty($openid) && empty($uid)){
               $default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
                    $row = array(
                         'uniacid' => $_W['uniacid'],
                         'nickname'=>$info['nickname'],
                         'avatar'=>$info['headimgurl'],
                         'realname'=>$info['nickname'],
                         'groupid' => $default_groupid,
                         'email'=>random(32).'@012wz.com',
                         'salt'=>random(8),
                         'createtime'=>time()
                    );
                    pdo_insert('mc_members', $row);
                    $user['uid'] = pdo_insertid();
                    $fan = mc_fansinfo($_W['openid']);
                    //pdo_update('mc_mapping_fans', array('uid'=>$uid), array('openid'=>$_W['openid'], 'uniacid'=>$_W['uniacid']));
                    pdo_update('mc_mapping_fans', array('uid'=>$user['uid']), array('fanid'=>$fan['fanid']));
                    _mc_login($user);
          }
	
	}
	//检测用户浏览器
	public function checkBowser(){
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			echo " 404";
			//exit;
		}
	}
	
	//获取用户当前剩余次数和加油次数
	public function getLotteryNum($rid,$openid){
		$mid = pdo_fetchcolumn( " SELECT id FROM ".tablename('hc_weibb_member')." WHERE openid='".$openid."' AND rid='".$rid."' " );
		if(empty($mid)){
			$callback = "error";
			return $callback;
		}else{
			//$daystotal 系统每天赠送次数,$sharecount 朋友赠送次数, $usedtotal 用户今日已使用次数
			//用户剩余次数 = 系统每天赠送次数 + 朋友赠送次数 - 用户今日已使用次数
			$daystotal = pdo_fetchcolumn( " SELECT maxlottery FROM ".tablename('hc_weibb_reply')." WHERE rid=".$rid." " );
			$daystotal = intval($daystotal);
			$sharecount = pdo_fetchcolumn( " SELECT count FROM ".tablename('hc_weibb_share')." WHERE mid='".$mid."' AND rid='".$rid."' " );
			$sharecount = intval($sharecount);
			$sql="SELECT COUNT(*) FROM ".tablename('hc_weibb_user')." WHERE createtime > '".strtotime(date('Y-m-d'))."' AND from_user = '$openid' and rid=".$rid;
			$usedtotal = intval(pdo_fetchcolumn($sql));
				//当用户每天赠送次数用完后，剩余次数=加油次数
				if($usedtotal >= $daystotal){
				$callback['isshare'] = 2;//=2说明赠送次数已用完，使用的是加油次数
				//如果分享次数小于等于0则摇奖次数用完
				if($sharecount <= 0 ){
						$callback = "used";
						return $callback;
					}
					else{
					$callback['lotteryNum'] = $sharecount;
					$callback['sharecount'] = $sharecount;
					return $callback;
				}
			}else{
				//用户每天赠送次数用完前，剩余次数=赠送次数-已使用次数+加油次数
				$lotteryNum = $daystotal + $sharecount - $usedtotal;
				$callback['lotteryNum'] = $lotteryNum;
				$callback['sharecount'] = $sharecount;
				return $callback;
			}
			
		}
		
	}
	
	//为转发者增加加油次数
	public function addLotteryNum($shareid,$weibb,$rid){
		global $_W;
		//检测member表中是否存在这条数据
		$member = pdo_fetch( " SELECT id,openid FROM ".tablename('hc_weibb_member')." WHERE id='".$shareid."' " );
		$mid = $member['id'];
		$openid = $member['openid'];
		$mid = intval($mid);
		$share = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_share')." WHERE mid='".$mid."' " );
				//如果不存在则插入
				if(empty($share)){
					$insert = array(
						'count' => 0,
						'sharetotal' => 0,
						'from_user' => $openid,
						'createtime' => TIMESTAMP,
						'rid' => $rid,
						'mid' => $mid,
					);
					pdo_insert('hc_weibb_share',$insert);
					$share = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_share')." WHERE mid='".$mid."' " );
				}
				
		if(!empty($mid)){
			//用cookie作为判断依据，cookie不存在说明为首次被转发者，为被转发者增加奖励次数
			$cookies = $_W['uniacid']."hc_weibb15".$mid;
			if(!isset($_COOKIE[$cookies])){
				
				$shareCount = intval($share['count']);					//当前剩余奖励次数
				$prace_times = intval($weibb['prace_times']);			//系统规定最大奖励次数
				$weibbSharecount = intval($weibb['sharecount']);					//系统规定转发一次奖励次数
				$shareTotal = intval($share['sharetotal']);				//用户总共获得的奖励次数
				//当前次数小于系统最大奖励次数
				if($shareTotal < $prace_times){
					if($shareTotal + $weibbSharecount < $prace_times){
						$shareTotal = $shareTotal + $weibbSharecount;
						$shareCount = $shareCount + $weibbSharecount;
					}else{
						$lastcount = $prace_times - $shareTotal;
						$shareCount = $shareCount + $lastcount;
						$shareTotal = $prace_times;
					}
					$share['count'] = $shareCount;
					$share['sharetotal'] = $shareTotal;
					setcookie($cookies,1,time()+86400);
					pdo_update('hc_weibb_share',$share,array('id'=>$share['id']));
				}
			}
		}
		return null;
	}
	
	//根据获奖结果随机显示骰子点数
	public function getDiceNum($huode){
		//根据摇出的奖项显示骰子
			if($huode == '未中奖'){
				$rand = mt_rand(1,3);
				if($rand == 1){
					$level['a']=5;
					$level['b']=1;
					$level['c']=2;
					$level['d']=1;
					$level['e']=6;
					$level['f']=2;
					
				}
				if($rand == 2){
					$level['a']=3;
					$level['b']=2;
					$level['c']=5;
					$level['d']=1;
					$level['e']=5;
					$level['f']=3;
				
				}
				if($rand == 3){
					$level['a']=3;
					$level['b']=2;
					$level['c']=5;
					$level['d']=6;
					$level['e']=6;
					$level['f']=3;
				
				}
			
			}
			//有一个4
			if($huode == '一秀'){
				$rand = mt_rand(1,3);
				if($rand == 1)
				{
					$level['a']=3;
					$level['b']=4;
					$level['c']=5;
					$level['d']=1;
					$level['e']=5;
					$level['f']=6;
					
				}
				if($rand == 2){
					$level['a']=6;
					$level['b']=4;
					$level['c']=5;
					$level['d']=1;
					$level['e']=2;
					$level['f']=6;
					
				}
				if($rand == 3){
					$level['a']=3;
					$level['b']=4;
					$level['c']=5;
					$level['d']=3;
					$level['e']=1;
					$level['f']=6;
					
				}
			
			}
			//有两个4
			if($huode == '二举'){
				$rand = mt_rand(1,3);
				if($rand == 1){
					$level['a']=4;
					$level['b']=4;
					$level['c']=2;
					$level['d']=2;
					$level['e']=5;
					$level['f']=6;
					
				}if($rand == 2){
					$level['a']=4;
					$level['b']=2;
					$level['c']=2;
					$level['d']=4;
					$level['e']=1;
					$level['f']=6;
					
				}if($rand == 3){
					$level['a']=4;
					$level['b']=1;
					$level['c']=2;
					$level['d']=2;
					$level['e']=3;
					$level['f']=4;
					
				}
			
			
			
			}
			//有三个4
			if($huode == '三红'){
				$rand = mt_rand(1,3);
				if($rand == 1){
					$level['a']=4;
					$level['b']=4;
					$level['c']=4;
					$level['d']=3;
					$level['e']=5;
					$level['f']=1;
					
				}if($rand == 2){
					$level['a']=4;
					$level['b']=4;
					$level['c']=3;
					$level['d']=4;
					$level['e']=6;
					$level['f']=1;
					
				}if($rand == 3){
					$level['a']=4;
					$level['b']=4;
					$level['c']=3;
					$level['d']=3;
					$level['e']=2;
					$level['f']=4;
					
				}
			
		
			}
			//除四个4外，有四个点数相同
			if($huode == '四进'){
				$rand = mt_rand(1,3);
				if($rand == 1){
					$level['a']=2;
					$level['b']=2;
					$level['c']=2;
					$level['d']=2;
					$level['e']=5;
					$level['f']=6;
					
				}if($rand == 2){
					$level['a']=1;
					$level['b']=1;
					$level['c']=1;
					$level['d']=1;
					$level['e']=2;
					$level['f']=6;
					
				}if($rand == 3){
					$level['a']=5;
					$level['b']=5;
					$level['c']=5;
					$level['d']=5;
					$level['e']=3;
					$level['f']=6;
					
				}
			
			}
			//六个骰子为123456
			if($huode == '对堂'){
				$level['a']=1;
				$level['b']=2;
				$level['c']=3;
				$level['d']=4;
				$level['e']=5;
				$level['f']=6;
				
			}
			//六个骰子有四个4，另外两个除4和同时为1外
			if($huode == '普通状元'){
				$rand = mt_rand(1,3);
				if($rand == 1){
					$level['a']=4;
					$level['b']=4;
					$level['c']=4;
					$level['d']=4;
					$level['e']=3;
					$level['f']=5;
					
				}
				if($rand == 2){
					$level['a']=4;
					$level['b']=4;
					$level['c']=4;
					$level['d']=2;
					$level['e']=4;
					$level['f']=6;
					
				}
				if($rand == 3){
					$level['a']=4;
					$level['b']=4;
					$level['c']=3;
					$level['d']=2;
					$level['e']=4;
					$level['f']=4;
					
				}
			}

			//六个骰子有5个相同(除4点外)
			if($huode == '五子'){
				$rand = mt_rand(1,3);
				if($rand == 1){
					$level['a']=1;
					$level['b']=1;
					$level['c']=1;
					$level['d']=1;
					$level['e']=1;
					$level['f']=2;
					
				}if($rand == 2){
					$level['a']=5;
					$level['b']=5;
					$level['c']=5;
					$level['d']=5;
					$level['e']=5;
					$level['f']=6;
					
				}if($rand == 3){
					$level['a']=3;
					$level['b']=3;
					$level['c']=3;
					$level['d']=3;
					$level['e']=3;
					$level['f']=5;
					
				}
			}
			//六个骰子有5粒为4
			if($huode == '五红'){	
				$rand = mt_rand(1,3);
				if($rand == 1){
					$level['a']=4;
					$level['b']=4;
					$level['c']=4;
					$level['d']=4;
					$level['e']=4;
					$level['f']=5;
					
				}	
				if($rand == 2){
					$level['a']=4;
					$level['b']=4;
					$level['c']=4;
					$level['d']=4;
					$level['e']=4;
					$level['f']=2;
					
				}	
				if($rand == 3){
					$level['a']=4;
					$level['b']=4;
					$level['c']=4;
					$level['d']=4;
					$level['e']=4;
					$level['f']=3;
					
				}
			
			
			
			
			}
			//六个骰子都是1
			if($huode == '六杯红'){
				$level['a']=1;
				$level['b']=1;
				$level['c']=1;
				$level['d']=1;
				$level['e']=1;
				$level['f']=1;
				
			}
		
			//六个骰子四个为4两个为1
			if($huode == '状元插金花'){
				$level['a']=4;
				$level['b']=4;
				$level['c']=4;
				$level['d']=4;
				$level['e']=1;
				$level['f']=1;
				
			}	
		return $level;
	}
	

	
	//根据openid发送卡券
	
     public function sendWxCard($cardid,$sendopenid,$sendopenid2){
     global $_W,$_GPC;
	 $appid = $_W['account']['key'];
     $appSecret = $_W['account']['secret'];
      load()->func('communication');
     if ($data->expire_time < time()) {    
       $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appSecret."";
      $res = json_decode($this->httpGet($url));
      $tokens = $res->access_token;
          if(empty($tokens))
          {
          return;
          }
         
          //$postarr = '{"touser":["'.$sendopenid.'","'.$sendopenid2.'"],"wxcard":{"card_id":"'.$cardid.'"},"msgtype":"wxcard"}';
          $postarr = '{"card_id":"'.$cardid.'"}';
          $res = ihttp_post('https://api.weixin.qq.com/card/mpnews/gethtml?access_token='.$tokens,$postarr);
     }

	}
	
	private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }
  //随机字符串
  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }
  
  
  //jssdk添加卡券
  //获取api_ticket
  public function getCardTicket($card_id,$openid){
     global $_W,$_GPC;
	 //获取access_token
	 $data = pdo_fetch( " SELECT * FROM ".tablename('hc_weibb_card_ticket')." WHERE weid='".$_W['uniacid']."' " );
	 $appid = $_W['account']['key'];
     $appSecret = $_W['account']['secret'];
     load()->func('communication');
	 //检测ticket是否过期
     if ($data['createtime'] < time()) {    
       $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appSecret."";
      $res = json_decode($this->httpGet($url));
      $tokens = $res->access_token;
          if(empty($tokens))
          {
          return;
          }
         
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$tokens."&type=wx_card";
		$res = json_decode($this->httpGet($url));
		$now = TIMESTAMP;
		$now = intval($now) + 7200;
		$ticket = $res->ticket;
		$insert = array(
			'weid' => $_W['uniacid'],
			'createtime' => $now,
			'ticket' => $ticket,
		);
		if(empty($data)){
			pdo_insert('hc_weibb_card_ticket',$insert);
		}else{
			pdo_update('hc_weibb_card_ticket',$insert,array('id'=>$data['id']));
		}
		
     }else{
		$ticket = $data['ticket'];
	 }
	 
	 //获得ticket后将参数拼成字符串进行sha1加密
		$now = time();
		$timestamp = $now;
		$nonceStr = $this->createNonceStr();
		$card_id = $card_id;
		$openid = $openid;
		$string = "card_id=$card_id&jsapi_ticket=$ticket&noncestr=$nonceStr$openid=$openid&timestamp=$timestamp";

		$arr = array($card_id,$ticket,$nonceStr,$openid,$timestamp);//组装参数
        asort($arr, SORT_STRING);
		$sortString = "";
		 foreach($arr as $temp){
			$sortString = $sortString.$temp;
		 }
		$signature = sha1($sortString);
	 $cardArry = array(
		'code' =>"",
		'openid' => $openid,
		'timestamp' => $now,
		'signature' => $signature,
		'cardId' => $card_id,
		'ticket' => $ticket,
		'nonceStr' => $nonceStr,
	 );
	return $cardArry;
		
  
  }
	
	
	
}
