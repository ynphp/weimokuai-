<?php
/**
 * 签到管家模块处理程序
 *
 * @author Yoby
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class YobyqiandaoModuleProcessor extends WeModuleProcessor {
	public function respond() {	
			global $_W;
			load()->model('mc');//0.6会员操作
			load()->app('common');
		$openid = $this->message['from'];	
		
		checkauth();	
		$uid = $_W['member']['uid'];	
		$rid = $this->rule;		
		$sql = "SELECT * FROM " . tablename('yobyqiandao_reply') . " WHERE rid=:rid LIMIT 1";		
		$row = pdo_fetch($sql, array(':rid' => $rid));	
		if (empty($row['id'])) {		
			return array();
		}
	$now = time();//当前时间
	$weid = $_W['uniacid'];
	
	$content = $this->message['content'];//消息	

	//规则参数
	$ad= iunserializer($row['ad']);
	$adstr = "\n----AD----\n";//回复广告字符串
	if(!empty($ad['ad1'])){
		$adstr .=" *<a href='".$ad['url1']."'>".$ad['ad1']."</a> ";
	}
	if(!empty($ad['ad2'])){
		$adstr .="\n *<a href='".$ad['url2']."'>".$ad['ad2']."</a> ";
	}	
	if(!empty($ad['ad3'])){
		$adstr .="\n *<a href='".$ad['url3']."'>".$ad['ad3']."</a> ";
	}	
	if(!empty($ad['ad4'])){
		$adstr .="\n *<a href='".$ad['url4']."'>".$ad['ad4']."</a> ";
	}	
	if(!empty($ad['ad5'])){
		$adstr .="\n *<a href='".$ad['url5']."'>".$ad['ad5']."</a> ";
	}	
	$jifenarr = explode(',',$row['jifen']);
	$jifen = (count($jifenarr)==2)? rand($jifenarr[0],$jifenarr[1]) : $jifenarr[0];//积分参数
	$jifen2 = $row['jifen2'];
	$jifen3 = $row['jifen3'];
	$jifen4 = $row['jifen4'];
	$jifen5 = $row['jifen5'];
	$jifen6 = $row['jifen6'];
	$jifen7= $row['jifen7'];//积分规则
	$oktishi = $row['oktishi'];//签到成功提示
	$errortishi = $row['errortishi'];//失败提示
	$top10 = $row['top10'];//显示排名数量
	$start_time =strtotime($row['start_time']);//开始时间
	$end_time =strtotime($row['end_time']);//结束时间
	
	$today = strtotime(date('Y-m-d'));//今天日期
	
	
	//签到总人数
	$num = pdo_fetchcolumn("SELECT count(*)  FROM " . tablename('yobyqiandao_log') . " WHERE  createtime  >= $today AND rid =$rid");
	$numtop = $num+1;//今天排名
	
	$isqiandao = pdo_fetchcolumn("SELECT count(*)  FROM " . tablename('yobyqiandao_log') . " WHERE openid = :openid and createtime >= :createtime AND rid = :rid", array(':openid' => $openid, ':createtime' => $today, ':rid' => $rid));
	

$fans =mc_fetch($_W['member']['uid'], array('nickname', 'realname'));
$str = "";
$realname = $fans['realname'];//真名字
$nickname = $fans['nickname'];//昵称
if(empty($realname) && empty($nickname)){
	$str .='匿名用户,您好!<a href="'.$_W['siteroot']."app/index.php?i=$weid&c=entry&do=reg&m=yobyqiandao&uid=$uid&openid=$openid".'">改名</a>'."\n";
}else{
  if(empty($realname)){
  $str .= $nickname.",您好!\n";
  }else{
	$str .= $realname.",您好!\n";
	}
}
	
	if($now >= $start_time && $now <= $end_time){//签到时间范围内
	 	if($isqiandao==0){//没有签到
	 	
	 	$goon  = pdo_fetch("SELECT * FROM ".tablename('yobyqiandao_goon')." where weid={$weid} and openid='".$openid."' ");
if(empty($goon['id'])){
	$goon = array('createtime'=>$now,'days'=>0);
}   
        $yestday=$today-24*3600;
        $isqd  = pdo_fetch("SELECT * FROM ".tablename('yobyqiandao_log')." where weid={$weid} and openid='".$openid."' and createtime >'{$yestday}' and createtime < '{$today}' ");//昨天是否签到
	 		if(empty($isqd)){//非连续签到
				$days =1;
			}else{
			$days = intval($goon['days'])+1;//连续签到天数
			}
	 	$insertgoon = array(
	 		'weid'=>$weid,
	 		'createtime'=>$now,
	 		'openid'=>$openid,
	 		'days'=>$days	 	
	 	);
	 	if(empty($goon['id'])){
			pdo_insert('yobyqiandao_goon', $insertgoon);
		}else{
			pdo_update('yobyqiandao_goon', $insertgoon, array('id' =>$goon['id']));
		}
		switch($days){
			case "1":
			$jf = $jifen;
			break;
			case "2":
			$jf = $jifen2;
			break;
			case "3":
			$jf = $jifen3;
			break;	
			case "4":
			$jf = $jifen4;
			break;
			case "5":
			$jf = $jifen5;
			break;
			case "6":
			$jf = $jifen6;
			break;
			case $days>=7:
			$jf = $jifen7;
			break;
			default:
			$jf = $jifen;
			break;											
		}
		$insert = array(					
					'rid' => $rid,					
					'weid' => $weid,					
					'openid' => $openid,										
					'createtime' => $now,
					'jifen'=>$jf,					
					'top10' => $numtop,	//当天排行榜	
					'uid'=>$uid,			
					);
		
		pdo_insert('yobyqiandao_log', $insert);//添加签到记录

			mc_credit_update($uid, 'credit1', $jf, array(0, '使用签到管家,赠送'.$jf.'积分'));
		$str .= $oktishi."+".$jf."积分\n";//签到成功提示
		
		
			
		}
		else{
		
		$str .= "今天你已经签到过了,明天再来吧!";
		
			
		}	
	$str .="";	
			
	}else{
		$str .= $errortishi;
		
	}
	
	$day1= pdo_fetchcolumn("SELECT count(*)  FROM " . tablename('yobyqiandao_log') . " WHERE openid = :openid and weid= :weid", array(':openid' => $openid,  ':weid' => $weid));
	//$fans1 = fans_search($openid);
	$fans1 = mc_fetch($_W['member']['uid'], array('nickname', 'realname','credit1'));
	$goon1  = pdo_fetch("SELECT * FROM ".tablename('yobyqiandao_goon')." where weid={$weid} and openid='".$openid."' ");
	if(empty($goon1['id'])){
	$dayes=0;
	}else{
	$dayes = $goon1['days'];
	}
	$str1 .= "\n已签到: ".$day1." 天\n";
	$str1 .= "已连续签到".$dayes."天\n";
	$str1 .= "获得积分: ".$fans1['credit1']." 分\n";

	$tp_sql = "select (select count(*)+1 as rank from  " . tablename('mc_members') . " as B where B.credit1>A.credit1 and uniacid={$weid}) as rank from " . tablename('mc_members') . " as A where uniacid={$weid} and uid='{$uid}' order by credit1 desc limit 1";
	$topn1 = pdo_fetchcolumn($tp_sql);//当前排名
	$topn2 = pdo_fetchcolumn("select count(*) from ".tablename('mc_members')." where uniacid={$weid}");
	$str1 .= "积分排名: {$topn1}/{$topn2} 位\n";

	$str .="签到时间是每天的".$row['start_time']."-".$row['end_time']."\n";
	
		//当天起床榜
	$up_sql = "SELECT a.*,b.realname,b.nickname FROM " . tablename('yobyqiandao_log') . "  as a left join ".tablename('mc_members')." as b on a.uid=b.uid WHERE a.createtime >= :date AND a.rid = :rid order by a.top10 asc limit $top10";					
	$up_rs = pdo_fetchall($up_sql, array(':date' => $today, ':rid' => $rid));
	$up_str = "\n今日签到排行榜\n";
	if(!empty($up_rs)){
		foreach($up_rs as $up_row){
			$up_rows = $up_row['top10'];
			$up_rows1 = ($up_rows<10 && $up_rows>0)?"0".$up_rows:$up_rows;//补0
			if(empty($up_row['realname']) && empty($up_row['nickname'])){
        $up_name = "匿名用户";
        }else{
        if(empty($up_row['realname']))
          {
        $up_name = $up_row['nickname'];
        }else{
       $up_name =  $up_row['realname'];
          }
        
        }
			$up_str .=$up_rows1.". ".$up_name." ".date('H:i:s',$up_row['createtime'])."\n";
		}
	}
	
	//积分排行榜
	$topsql = "select uid,realname,nickname,credit1,(select count(*)+1 as rank from  ".tablename('mc_members')." as B where B.credit1>A.credit1 and uniacid=".$weid.") as rank from ".tablename('mc_members')." as A where uniacid=".$weid." order by credit1 desc limit $top10";					
	$toprs = pdo_fetchall($topsql);
	$topstr = "\n总积分排行榜\n";
	if(!empty($toprs)){
		foreach($toprs as $k=>$toprow){
			$toprows = $toprow['rank'];
			$toprows1 = ($k<9)?"0".strval($k+1):strval($k+1);
			if(empty($toprow['realname']) && empty($toprow['nickname'])){
        $rowname = "匿名用户";
        }else{
        if(empty($toprow['realname']))
          {
        $rowname = $toprow['nickname'];
        }else{
       $rowname =  $toprow['realname'];
          }
        
        }
			$topstr .=$toprows1.". ".$rowname." ".$toprow['credit1']."分\n";
		}
	}
					
	return $this->respText($str.$str1.$up_str.$topstr.$adstr);	
	}
}