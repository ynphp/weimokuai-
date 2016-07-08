<?php

defined('IN_IA') or exit('Access Denied');

class anhaoModuleProcessor extends WeModuleProcessor {

    public $name = 'anhaoModuleProcessor';
    public $table_reply = 'anhao_reply';
	public $table_order = 'anhao_order';
	public $table_ceshihou = 'anhao_ceshihou';

    public function isNeedInitContext() {
        return 0;
    }

    public function respond() {
        global $_W;
        $rid = $this->rule;
		$fromuser = $this->message['from'];

		session_start(); 
		$_SESSION['openid']=$fromuser;
			
		$content = $this->message['content'];
		$huya="中奖查询";
		
        $sql = "SELECT * FROM " . tablename($this->table_reply) . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));
        if (empty($row['id'])) {
            return array();
        }
        $now = time();
		
	//	$sql1 = "SELECT * FROM " . tablename($this->table_order) . " WHERE `rid`=:rid and `from_user`=:from_user";
    //    $renshupd = pdo_fetch($sql1, array(':rid' => $rid,':from_user' => $fromuser));
		$renshupd = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE from_user = '" . $fromuser . "' and rid = '" . $rid . "'");
        $xinxi = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = '" . $rid . "'");
		$shijian=date('m月d日 H点',$xinxi['end_time']);
		
        if ($now >= $row['start_time'] && $now <= $row['end_time'] && $content != $huya) {
			
			if(empty($renshupd['suiji']))
			{
		
				$suiji=rand(10000,99999);
				$renshu = array(
					'rid' => $rid,
					'suiji' => $suiji,
					'from_user' => $fromuser,
					'havetime' => time()
				);
	
				pdo_insert($this->table_order, $renshu);
			}
			$renshupd1 = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE from_user = '" . $fromuser . "' and rid = '" . $rid . "'");
			
			if($renshupd1['suiji1']==0)
			{
				$renshupd1['suiji1']="";
			}
			if($renshupd1['suiji2']==0)
			{
				$renshupd1['suiji2']="";
			}
			if($renshupd1['suiji3']==0)
			{
				$renshupd1['suiji3']="";
			}
			if($renshupd1['suiji4']==0)
			{
				$renshupd1['suiji4']="";
			}
			$dizhi1="活动页面";
			$add=$_SERVER["HTTP_HOST"];
			$url1="$add"."/app/".$this->createMobileUrl('anhao', array('id' => $rid, 'from_user' => $fromuser));
			$ad_info1= "<a href=\"http://".$url1."\">".$dizhi1."</a>";
			
			$info="\n\n活动将在".$shijian."结束，系统将在体彩排列三排列五开奖后自动公布".'"'.$xinxi['title'].'"'."的中奖名单，请密切关注".$ad_info1."。\n\n发送给小伙伴一起参与，即可获得多次参与资格，增加中奖几率。";
			
			$dizhi="点击获取";
			$url="$add"."/app/".$this->createMobileUrl('fenxiang', array('id' => $rid, 'from_user' => $fromuser));
			$ad_info= "<a href=\"http://".$url."\">".$dizhi."</a>";
			return $this->respText("活动参与成功，你的参与编号是  ".$renshupd1['suiji']."  ".$renshupd1['suiji1']."  ".$renshupd1['suiji2']."  ".$renshupd1['suiji3']."  ".$renshupd1['suiji4']."  ".$info.$ad_info."您的专属分享页面");
			}
		if ($now > $row['end_time'] && $content != $huya){
			$yonghu1 = pdo_fetch("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE rid = '" . $rid . "' and openid = '" . $fromuser . "'");
			$renshupd1 = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE from_user = '" . $fromuser . "' and rid = '" . $rid . "'");
			if($renshupd1['suiji1']==0)
			{
				$renshupd1['suiji1']="";
			}
			if($renshupd1['suiji2']==0)
			{
				$renshupd1['suiji2']="";
			}
			if($renshupd1['suiji3']==0)
			{
				$renshupd1['suiji3']="";
			}
			if($renshupd1['suiji4']==0)
			{
				$renshupd1['suiji4']="";
			}
			
			if($yonghu1['anhao']=='')
			{
			$dizhi1="活动页面";
			$add=$_SERVER["HTTP_HOST"];
			$url1="$add"."/app/".$this->createMobileUrl('anhao', array('id' => $rid, 'from_user' => $fromuser));
			$ad_info1= "\n<a href=\"http://".$url1."\">".$dizhi1."</a>";
			
			$info="\n\n活动将在".$shijian."结束，系统将在体彩排列三排列五开奖后自动公布".'"'.$xinxi['title'].'"'."的中奖名单，请密切关注".$ad_info1."。\n\n发送给小伙伴一起参与，即可获得多次参与资格，增加中奖几率。";
			
			$dizhi="获取个人专属页面";
			$url="$add"."/app/".$this->createMobileUrl('fenxiang', array('id' => $rid, 'from_user' => $fromuser));
			$ad_info= "\n<a href=\"http://".$url."\">".$dizhi."</a>";
			return $this->respText("活动已结束，可惜未中奖。您的号码为  ".$renshupd1['suiji']."  ".$renshupd1['suiji1']."  ".$renshupd1['suiji2']."  ".$renshupd1['suiji3']."  ".$renshupd1['suiji4']."  ".$info.$ad_info);
			}
			else
			{
			$dizhi="填写个人信息";
			$url="$add"."/app/".$this->createMobileUrl('xinxi', array('id' => $rid, 'from_user' => $fromuser));
			$ad_info= "\n<a href=\"http://".$url."\">".$dizhi."</a>";
			return $this->respText("恭喜您中奖了，请点击这里".$ad_info);
			
			}
		}
		if($content == $huya)
		{
			$dizhi="中奖记录页面";
			$add=$_SERVER["HTTP_HOST"];
			$url="$add"."/app/".$this->createMobileUrl('winlists', array('id' => $rid, 'from_user' => $fromuser));
			$ad_info= "<a href=\"http://".$url."\">".$dizhi."</a>";
			return $this->respText("来吧，看看最近15天你有没有中奖纪录\n\n您可以点击查看".$ad_info."详细情况和历史参与信息");
			
		}
		
		
		
		
		
        return $response;
    }

    public function isNeedSaveContext() {
        return false;
    }

}
