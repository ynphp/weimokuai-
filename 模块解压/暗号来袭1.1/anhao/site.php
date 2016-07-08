<?php

defined('IN_IA') or exit('Access Denied');

class anhaoModuleSite extends WeModuleSite {

    public $table_reply = 'anhao_reply';
    public $table_item = 'anhao_item';
    public $table_order = 'anhao_order';
	public $table_renshu = 'anhao_renshu';
	public $table_ceshi = 'anhao_ceshi';
	public $table_ceshihou = 'anhao_ceshihou';
	public $table_keyword = 'rule_keyword';
	public $table_orders = 'anhao_orders';
	public $table_kaijiang = 'anhao_kaijiang';
	
	

    public function getProfileTiles() {
        
    }

    public function getanhaoTiles($keyword = '') {
        global $_GPC, $_W;
        $urls = array();
        $weid = $_W['uniacid']; //当前公众号ID
		
        $list = pdo_fetchall("SELECT name, id FROM " . tablename('rule') . " WHERE module = 'anhao' and uniacid='$weid'" . (!empty($keyword) ? " AND name LIKE '%{$keyword}%'" : ''));
        if (!empty($list)) {
            foreach ($list as $row) {
                $urls[] = array('title' => $row['name'], 'url' => $this->createMobileUrl('anhao', array('id' => $row['id'])));
            }
        }
        return $urls;
    }
	
	 public function doMobileanhao() {
		
		global $_GPC, $_W;

        $rid = $_GPC['id'];
		$from_user1=$_GPC['from_user'];
		
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$replyid = pdo_fetch("SELECT * FROM " . tablename($this->table_keyword) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        $items = pdo_fetchall("SELECT * FROM " . tablename($this->table_item) . " WHERE rid = :rid ORDER BY `orderid` ASC", array(':rid' => $rid));
		$haoma = pdo_fetch("SELECT * FROM " . tablename($this->table_kaijiang) . " WHERE  rid = '" . $rid . "'");
		$zhongjhaoma=$haoma['haoma'];
		$changdu=strlen($zhongjhaoma);
		if($changdu==4)
		{
			$zhongjhaoma="0".$zhongjhaoma;
		}
		
		$first = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 1");
		$second = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 2");
		$san = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 3");
	//	$san = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  rid = '" . $rid . "'");
		
		$mobilefirst = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  rid = '" . $rid . "' and state=1");
		$mobilesecond = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  rid = '" . $rid . "' and state=0");
		$mobilesan = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  rid = '" . $rid . "' and state=3");
		
		$gongxi = pdo_fetch("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  rid = '" . $rid . "' and openid='".$from_user1."'");
		
		include $this->template('anhao');
		 
	 }
	
	//分享
	 public function doMobilefenxiang() {

		global $_GPC, $_W;

        $rid = $_GPC['id'];

		$formid=$_GPC['from_user'];
		
		require_once "jssdk.php";
		$jssdk = new JSSDK("wxcb106a9a3a6d0c4d", "67f9c7e95a678c1f25a06f867c95b12b");
		$signPackage = $jssdk->GetSignPackage();
		
		$share = $this->createMobileUrl('friends', array('id' => $rid, 'from_user' => $formid));
	//	$share=str_replace('.','',$share);
	    $share=ltrim($share,".");
		$share="http://wanqizaixian3.gotoip2.com/app".$share;
		$title="快来跟我一起参加吧";
		$desc="发送指定暗号到微信参加,可以赢神秘礼品，一般人我不告诉他....";
		
		$ewanrenshu = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE from_user = '" . $formid . "' and rid = '" . $rid . "'");
		$ewanmax = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE  rid = '" . $rid . "'");
		
		if($ewanrenshu['suiji1']!='0')
		{
			$ewairenshuyqoqing=1;
		}
		if($ewanrenshu['suiji2']!='0')
		{
			$ewairenshuyqoqing=2;
		}
		if($ewanrenshu['suiji3']!='0')
		{
			$ewairenshuyqoqing=3;
		}
		if($ewanrenshu['suiji4']!='0')
		{
			$ewairenshuyqoqing=4;
		}
		if($ewanrenshu['suiji5']!='0')
		{
			$ewairenshuyqoqing=5;
		}
		
		function get_between($input, $start, $end) {
		$substr = substr($input, strlen($start)+strpos($input, $start),
		(strlen($input) - strpos($input, $end))*(-1));
		return $substr;
		}
		$string = $_SERVER['REQUEST_URI'];
		$start = "from_user=";
		$end = "&do";
		$openid1=get_between($string, $start, $end);
		
		/*	if($formid!=$openid1)
			{	
				$tiaozhuan = $this->createMobileUrl('friends', array('id' => $rid, 'from_user' => $openid1));
				
				header("Location:".$tiaozhuan);
				
			}*/

		 include $this->template('fenxiang');
	 }
	 
	 
	  public function doMobilefriends() {
		 
		 global $_GPC, $_W;

        
        $rid = $_GPC['id'];
		//openid对比，确定是否中奖
		
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		
		$first = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 1");
		$second = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 2");
		$san = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 3");
		
		session_start(); //承接anhao.html
		if($_SESSION['openid']!='')
		{
		$formid=$_SESSION['openid'];
		}
		else{
			$str=strstr($_SERVER['REQUEST_URI'],'^');
			$formid=str_replace('^','',$str);
		}
	//	$rid=$_SESSION['rid'];
	
	
		
	
		
		function get_between($input, $start, $end) {
		$substr = substr($input, strlen($start)+strpos($input, $start),
		(strlen($input) - strpos($input, $end))*(-1));
		return $substr;
		}
		$string = $_SERVER['REQUEST_URI'];
		$start = "from_user=";
		$end = "&do";
		$openid1=get_between($string, $start, $end);
		$huya = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE rid = '" . $rid . "' and from_user = '" . $openid1 . "'");
			
		/*	if($formid!=$openid1&&$huya['suiji2']=='0'&&$huya['suiji1']!='0')
			{	
			
				
				
				$suiji2=rand(10000,99999);//随机数
				
				
				$huyaaa = array(
                'suiji2' => $suiji2
					);
					pdo_update($this->table_order, $huyaaa, array('id' => $huya['id']));
				
			}
			
			else*/

			
			
		   if($formid==''){

			
				$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx866123ac38b5f42a&redirect_uri=http://182.254.187.23/oauth13.php?".$openid1.$rid."&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
		        header("Location:".$oauth2_code);
				
				session_start(); 
				$formid=$_SESSION['openid1'];

			}
			
			
			
			if($huya['openidpd']!='')
			{	
				
				$openidpdi = array(
                'openidpd1' => $formid
					);
					pdo_update($this->table_order, $openidpdi, array('id' => $huya['id']));
			}
			
			else
			{
			$openidpdi = array(
                'openidpd' => $formid
					);
					pdo_update($this->table_order, $openidpdi, array('id' => $huya['id']));
			}
			
		//	$huya1 = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE rid = '" . $rid . "' and from_user = '" . $openid1 . "'");
			$renshupanduan = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = '" . $rid . "'");
			if($formid!=$huya['openidpd']&&$formid!=$huya['openidpd1'])
			{
				//人数判断
				
				//额外数为1
				
				if($formid!=$openid1&&$renshupanduan['max']==1)
				{	
					if($huya['suiji1']=='0')
				
					{
					$suiji1=rand(10000,99999);//随机数
				
				
					$huyaa1 = array(
					'suiji1' => $suiji1
								);
					pdo_update($this->table_order, $huyaa1, array('id' => $huya['id']));
					}
				}
				
				////////////////////////////////////////////////////////////////////////////////////////////
				
				//额外数为2
				if($formid!=$openid1&&$renshupanduan['max']==2)
				{
					
					if($huya['suiji1']=='0')
				
					{
					$suiji1=rand(10000,99999);//随机数
				
				
					$huyaa = array(
					'suiji1' => $suiji1
								);
					pdo_update($this->table_order, $huyaa, array('id' => $huya['id']));
					}
					else 
					{
						if($huya['suiji2']=='0')
						{
						$suiji2=rand(10000,99999);//随机数
				
				
						$huyaaa = array(
						'suiji2' => $suiji2
						);
						pdo_update($this->table_order, $huyaaa, array('id' => $huya['id']));
						}
					}
					
				
				
				}
				////////////////////////////////////////////////////////////////////////////
				//额外数为3
				if($formid!=$openid1&&$renshupanduan['max']==3)
				{
					if($huya['suiji3']=='0'&&$huya['suiji2']!='0'&&$huya['suiji1']!='0')
					{
								$suiji3=rand(10000,99999);//随机数
								$huyaaaa1 = array(
								'suiji3' => $suiji3
								);
								pdo_update($this->table_order, $huyaaaa1, array('id' => $huya['id']));
					}
					
					
					
					if($huya['suiji1']=='0')
				
					{
					$suiji1=rand(10000,99999);//随机数
				
				
					$huyaa = array(
					'suiji1' => $suiji1
								);
					pdo_update($this->table_order, $huyaa, array('id' => $huya['id']));
					}
					else 
					{
						if($huya['suiji2']=='0')
						{
						$suiji2=rand(10000,99999);//随机数
				
				
						$huyaaa = array(
						'suiji2' => $suiji2
						);
						pdo_update($this->table_order, $huyaaa, array('id' => $huya['id']));
						}
						
					}
					
					
				
				
				}
				
				
				////////////////////////////////////////////////////////////////////////////
				//额外数为4
				if($formid!=$openid1&&$renshupanduan['max']==4)
				{
					
					if($huya['suiji4']=='0'&&$huya['suiji3']!='0'&&$huya['suiji2']!='0'&&$huya['suiji1']!='0')
					{
								$suiji4=rand(10000,99999);//随机数
								$huyaaaa112 = array(
								'suiji4' => $suiji4
								);
								pdo_update($this->table_order, $huyaaaa112, array('id' => $huya['id']));
					}
					
					if($huya['suiji3']=='0'&&$huya['suiji2']!='0'&&$huya['suiji1']!='0')
					{
								$suiji3=rand(10000,99999);//随机数
								$huyaaaa1 = array(
								'suiji3' => $suiji3
								);
								pdo_update($this->table_order, $huyaaaa1, array('id' => $huya['id']));
					}
					
					
					if($huya['suiji1']=='0')
				
					{
					$suiji1=rand(10000,99999);//随机数
				
				
					$huyaa = array(
					'suiji1' => $suiji1
								);
					pdo_update($this->table_order, $huyaa, array('id' => $huya['id']));
					}
					else 
					{
						if($huya['suiji2']=='0')
						{
						$suiji2=rand(10000,99999);//随机数
				
				
						$huyaaa = array(
						'suiji2' => $suiji2
						);
						pdo_update($this->table_order, $huyaaa, array('id' => $huya['id']));
						}
						/*	else 
							{
								if($huya['suiji3']=='0')
								{
								$suiji3=rand(10000,99999);//随机数
								$huyaaaa = array(
								'suiji3' => $suiji3
								);
								pdo_update($this->table_order, $huyaaaa, array('id' => $huya['id']));
								}
								
								else 
								{
								if($huya['suiji4']=='0')
								{
								$suiji4=rand(10000,99999);//随机数
								$huyaaaaa = array(
								'suiji4' => $suiji4
								);
								pdo_update($this->table_order, $huyaaaaa, array('id' => $huya['id']));
								}
								}
								
							}*/
						
						
						
					}
					
				
				
				}
				
				
				
				
			}
			
			
			
			

		 include $this->template('friends');
	 }
	 
	 
	 
	 //中奖结果
	 public function doMobilezhongjiang() {
		 
		 //zhongjiang.html奖项
		 global $_GPC, $_W;

        $rid = $_GPC['id'];
		
		session_start(); //承接anhao.html
		$formid=$_SESSION['openid'];
		
		//openid对比，确定是否中奖
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		$puanduan = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . "WHERE  rid = '" . $rid . "'");
		foreach ($puanduan as $k => $v) {
			if($fromuser==$v['openid'])
			{
				$puanduan=1;
			}
				
		}
		$localhost1="http://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
		$str=strstr($localhost1,'^');
		$str=str_replace('^','',$str);
		if($str=='110')
		{
			$tiaozhuan1 = $this->createMobileUrl('winlists', array('id' => $rid, 'from_user' => $formid));
				
			header("Location:".$tiaozhuan1);
		}
		
		$jiangxiang = pdo_fetch("SELECT * FROM " . tablename($this->table_ceshihou) . "WHERE  openid = '" . $fromuser . "' and rid = '" . $rid . "'");
		
		$first1 = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 1");
		$second1 = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 2");
		$san1 = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 3");
		 
		 $xinxi = $this->createMobileUrl('xinxi', array('id' => $rid, 'from_user' => $_GPC['from_user']));
		 $winlists = $this->createMobileUrl('winlists', array('id' => $rid, 'from_user' => $_GPC['from_user']));
		//中奖号码
		$mobilefirst = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  rid = '" . $rid . "' and state=1");
		$mobilesecond = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  rid = '" . $rid . "' and state=0");
		$mobilesan = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  rid = '" . $rid . "' and state=3");
		 include $this->template('zhongjiang');
	 }
	
	//用户提交信息
	 public function doMobilexinxi() {
		global $_GPC, $_W;
        $rid = $_GPC['id'];
		 $tname = $_POST['name'];
		 $ttel = $_POST['tel'];
		 $fromuser = $_GPC['from_user'];
		 if($tname!='')
		 {
		 
		$yonghu = pdo_fetch("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE rid = '" . $rid . "' and openid = '" . $fromuser . "'");
		
		$yceshi = array(
                'name' => $tname,
				'tel' => $ttel
                
            );
		pdo_update($this->table_ceshihou, $yceshi, array('id' => $yonghu['id']));
		echo "<script language=javascript>alert('提交成功');</script>";
		 }
	
		$yonghu1 = pdo_fetch("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE rid = '" . $rid . "' and openid = '" . $fromuser . "'");
	//	if($yonghu1['name'])
		
		
		 include $this->template('xinxi');
		 
	 }
	 
	  public function doMobilewinlists() {
		
		global $_GPC, $_W;
        $rid = $_GPC['id'];
		$from_user1=$_GPC['from_user'];
		
		function get_between($input, $start, $end) {
		$substr = substr($input, strlen($start)+strpos($input, $start),
		(strlen($input) - strpos($input, $end))*(-1));
		return $substr;
		}
		$string = $_SERVER['REQUEST_URI'];
		$start = "from_user=";
		$end = "&do";
		$formid=get_between($string, $start, $end);
		
		$huya = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE  from_user = '" . $formid . "'");
		$huya11 = pdo_fetch("SELECT * FROM " . tablename($this->table_orders) . " WHERE  from_user = '" . $formid . "'");
		
		////////////
		
		
		foreach ($huya as $k => $v) 
		{
				
		$huya1 = pdo_fetch("SELECT * FROM " . tablename($this->table_keyword) . " WHERE  rid = '" . $v['rid'] . "'");
				$renshu = array(
                'rid' => $v['rid'],
                'suiji' => $v['suiji'],
                'suiji1' => $v['suiji1'],
				'suiji2' => $v['suiji2'],
				'suiji3' => $v['suiji3'],
				'suiji4' => $v['suiji4'],
				'suiji5' => $v['suiji5'],
				'state' => $v['state'],
				'anhao' => $huya1['content'],
                'from_user' => $formid
                
                    //'ip' => getip()
            );
		if($huya11['anhao']!=$huya1['content'])
		{
		pdo_insert($this->table_orders, $renshu);
		pdo_delete($this->table_orders, "anhao=''");
		}
		}
		/////////////
	//	$huyaa = pdo_fetchall("SELECT * FROM " . tablename($this->table_orders) . " WHERE  from_user = '" . $formid . "'");
		$huyaa = pdo_fetchall("select *, count(distinct suiji) from ims_anhao_orders where from_user = '" . $formid . "'  group by suiji  ORDER BY `id` DESC");
		$huyaaa = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  openid = '" . $formid . "'");
		$huyadan = pdo_fetch("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  openid = '" . $formid . "'");
		
		
		  
		  include $this->template('winlists');
	  }
	


    public function doWebanhaolist($rid, $state) {
        global $_GPC, $_W;
        checklogin();
        $weid = $_W['account']['weid']; //当前公众号ID
        $id = intval($_GPC['id']);
        if (checksubmit('delete')) {
            pdo_delete($this->table_order, " id IN ('" . implode("','", $_GPC['select']) . "')");
            message('删除成功！', $this->createWebUrl('site/module', array('do' => 'anhaolist', 'name' => 'anhao', 'id' => $id, 'page' => $_GPC['page'])));
        }
        $rules = pdo_fetchall('SELECT `id`,`name` FROM ' . tablename('rule') . ' WHERE `module`=\'anhao\'');
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        //当前公众号的表单字段
        $items = pdo_fetchall('select `id`,`fieldname`,`orderid` from ' . tablename($this->table_item) . ' where rid = :rid order by `orderid` asc', array(':rid' => $id));
        $fields = array();
        foreach ($items as $k => $v) {
            $fields[$v['orderid']] = field . $v['orderid'];
        }
        //print_r($fields);
        //取得暗号列表
        $list_order = pdo_fetchall('SELECT * FROM '.tablename($this->table_order).' WHERE rid= :rid order by `id` desc', array(':rid' => $id) );	
        //$list_order = pdo_fetchall('SELECT * FROM ' . tablename($this->table_order) . ' order by `id` desc');
        $items_total = count($items);
        $total = count($list_order);
        $pager = pagination($total, $pindex, $psize);
        include $this->template('anhaolist');
    }
	
	
	public function doWebkaijiang() {
        global $_GPC, $_W;
        $rid = $_GPC['id'];//规则
        
		$anhao1 = $_POST['anhao'];
		
		$ceshi = array(
				'rid' => $rid,
                'haoma' => $anhao1
                    //'ip' => getip()
            );

		pdo_insert($this->table_kaijiang, $ceshi);
		pdo_delete($this->table_kaijiang, "haoma = ('0')");
		
		
		
		
		//排序暂未一人待定
		$duibi = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE  rid = '" . $rid . "'");
		$paixus = array();
		foreach ($duibi as $k => $v) {
			$from_user=$v['from_user'];
            $zhi=$anhao1-$v['suiji'];
			$zhi=abs($zhi);
			$wanqi=$v['suiji'];
			$ceshi = array(
				'openid' => $from_user,
				'rid' => $rid,
				'yuananhao' => $v['suiji'],
                'anhao' => $zhi
                
            );
			pdo_insert($this->table_ceshi, $ceshi);
			
			if($anhao1==$v['suiji'])
			{
				$judui0 = array(
				'openid' => $from_user,
				'rid' => $rid,
				'yuananhao' => $v['suiji'],
				'state' => 1,
                'anhao' => 0
                
            );
			pdo_insert($this->table_ceshihou, $judui0);
			}
				
			
			
			pdo_delete($this->table_ceshi, "anhao = ('0')");
			pdo_delete($this->table_ceshi, "anhao = '$wanqi'");
        }	/*
			$suijia=$duibi['suiji'];
			$from_user=$duibi['from_user'];
			$zhi=$anhao1-$suijia;
			$zhi=abs($zhi);
			$ceshi = array(
				'openid' => $from_user,
				'rid' => $rid,
                'anhao' => $zhi
                
            );
			pdo_insert($this->table_ceshi, $ceshi);
			pdo_delete($this->table_ceshi, "anhao = ('0')");*/
			
		
		//////////////////////////////////20150308
		$duibi1 = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE  rid = '" . $rid . "'");
		foreach ($duibi1 as $k => $v1) {
			$from_user1=$v1['from_user'];
            $zhi1=$anhao1-$v1['suiji1'];
			$zhi1=abs($zhi1);
			$wanqi1=$v1['suiji1'];
			$ceshi1 = array(
				'openid' => $from_user1,
				'rid' => $rid,
				'yuananhao' => $v1['suiji1'],
                'anhao' => $zhi1
                
            );
			pdo_insert($this->table_ceshi, $ceshi1);
			
			if($anhao1==$v1['suiji1'])
			{
				$judui1 = array(
				'openid' => $from_user,
				'rid' => $rid,
				'yuananhao' => $v1['suiji1'],
				'state' => 1,
                'anhao' => 0
                
            );
			pdo_insert($this->table_ceshihou, $judui1);
			}
			
			pdo_delete($this->table_ceshi, "anhao = ('0')");
			pdo_delete($this->table_ceshi, "anhao = '$wanqi1'");
        }
		
		$duibi2 = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE  rid = '" . $rid . "'");
		foreach ($duibi2 as $k => $v2) {
			$from_user2=$v2['from_user'];
            $zhi2=$anhao1-$v2['suiji2'];
			$zhi2=abs($zhi2);
			$wanqi2=$v2['suiji2'];
			$ceshi2 = array(
				'openid' => $from_user2,
				'rid' => $rid,
				'yuananhao' => $v2['suiji2'],
                'anhao' => $zhi2
                
            );
			pdo_insert($this->table_ceshi, $ceshi2);
			
			if($anhao1==$v2['suiji2'])
			{
				$judui2 = array(
				'openid' => $from_user,
				'rid' => $rid,
				'yuananhao' => $v2['suiji2'],
				'state' => 1,
                'anhao' => 0
                
            );
			pdo_insert($this->table_ceshihou, $judui2);
			}
			
			pdo_delete($this->table_ceshi, "anhao = ('0')");
			pdo_delete($this->table_ceshi, "anhao = '$wanqi2'");
        }
		
		$duibi3 = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE  rid = '" . $rid . "'");
		foreach ($duibi3 as $k => $v3) {
			$from_user3=$v3['from_user'];
            $zhi3=$anhao1-$v3['suiji3'];
			$zhi3=abs($zhi3);
			$wanqi3=$v3['suiji3'];
			$ceshi3 = array(
				'openid' => $from_user3,
				'rid' => $rid,
				'yuananhao' => $v3['suiji3'],
                'anhao' => $zhi3
                
            );
			pdo_insert($this->table_ceshi, $ceshi3);
			
			if($anhao1==$v3['suiji3'])
			{
				$judui3 = array(
				'openid' => $from_user,
				'rid' => $rid,
				'yuananhao' => $v3['suiji3'],
				'state' => 1,
                'anhao' => 0
                
            );
			pdo_insert($this->table_ceshihou, $judui3);
			}
			
			pdo_delete($this->table_ceshi, "anhao = ('0')");
			pdo_delete($this->table_ceshi, "anhao = '$wanqi3'");
        }
		
		$duibi4 = pdo_fetchall("SELECT * FROM " . tablename($this->table_order) . " WHERE  rid = '" . $rid . "'");
		foreach ($duibi4 as $k => $v4) {
			$from_user4=$v4['from_user'];
            $zhi4=$anhao1-$v4['suiji4'];
			$zhi4=abs($zhi4);
			$wanqi4=$v4['suiji4'];
			$ceshi4 = array(
				'openid' => $from_user4,
				'rid' => $rid,
				'yuananhao' => $v4['suiji4'],
                'anhao' => $zhi4
                
            );
			pdo_insert($this->table_ceshi, $ceshi4);
			
			if($anhao1==$v4['suiji4'])
			{
				$judui4 = array(
				'openid' => $from_user,
				'rid' => $rid,
				'yuananhao' => $v4['suiji4'],
				'state' => 1,
                'anhao' => 0
                
            );
			pdo_insert($this->table_ceshihou, $judui4);
			}
			
			pdo_delete($this->table_ceshi, "anhao = ('0')");
			pdo_delete($this->table_ceshi, "anhao = '$wanqi4'");
        }
		
		pdo_delete($this->table_ceshi, "yuananhao=0");
		
		
		
			
		//奖品人员数量待定
		$jiangpin1 = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 1");
		
		$jiangpin2 = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 2");
		
		$jiangpin3 = pdo_fetch("SELECT * FROM " . tablename($this->table_item) . " WHERE  rid = '" . $rid . "' and type = 3");
		//总人数
		
		if($jiangpin2['fieldname']!=''&&$jiangpin3['fieldname']!='')
		{
		$renshu=$jiangpin1['fieldname']+$jiangpin2['fieldname']+$jiangpin3['fieldname'];
		$renshu1=$jiangpin1['fieldname'];
		$renshu2=$jiangpin2['fieldname'];
		$renshu3=$jiangpin3['fieldname'];
		}
		if($jiangpin2['fieldname']=='')
		{
		
		$jiangpin2['fieldname']=0;
		$jiangpin3['fieldname']=0;
		
		$renshu=$jiangpin1['fieldname']+$jiangpin2['fieldname']+$jiangpin3['fieldname'];
		$renshu1=$jiangpin1['fieldname'];
		$renshu2=$jiangpin2['fieldname'];
		$renshu3=$jiangpin3['fieldname'];
		}
		
		if($jiangpin3['fieldname']=='')
		{

		$jiangpin3['fieldname']=0;
		
		$renshu=$jiangpin1['fieldname']+$jiangpin2['fieldname']+$jiangpin3['fieldname'];
		$renshu1=$jiangpin1['fieldname'];
		$renshu2=$jiangpin2['fieldname'];
		$renshu3=$jiangpin3['fieldname'];
		}
		
		
		
		

		
		//提取
		$quanbu = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshi) . "  where rid = '" . $rid . "' ORDER BY `anhao` ASC limit $renshu");
		
		
		foreach ($quanbu as $k => $v) 
		{
			$qanhao=$v['anhao'];
			$qrid=$v['rid'];
			$qopenid=$v['openid'];
			
			$qceshi = array(
				'openid' => $qopenid,
				'rid' => $qrid,
				'yuananhao' => $v['yuananhao'],
                'anhao' => $qanhao
                
            );
			pdo_insert($this->table_ceshihou, $qceshi);
			
			
				
		}
		
		//一等奖
		
		$quanbu1 = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE rid = '" . $rid . "' ORDER BY `anhao` ASC limit $renshu1");
		
		//更新字段
		foreach ($quanbu1 as $k => $v) 
		{
		$yid=$v['id'];
		$yceshi = array(
                'state' => 1
                
            );
		pdo_update($this->table_ceshihou, $yceshi, array('id' => $yid));
		
		}
		//三等奖
		
		$quanbu3 = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE rid = '" . $rid . "' ORDER BY `anhao` DESC limit $renshu3");
		
		//更新字段
		foreach ($quanbu3 as $k => $v) 
		{
		$sid=$v['id'];
		$eceshi = array(
                'state' => 3
                
            );
		pdo_update($this->table_ceshihou, $eceshi, array('id' => $sid));
		}
		
		if($renshu2=='')
		{
		pdo_delete($this->table_ceshihou, "rid =".$rid." and(state='')");
		}
		
		
		$list_orderzt = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  rid = '" . $rid . "'");
		//更新状态
		foreach ($list_orderzt as $kk => $vk) 
		{
		$oid=$vk['rid'];
		$open=$vk['openid'];
		$eceshizt = array(
                'state' => 1
                
            );
		pdo_update($this->table_order, $eceshizt, array('rid' => $oid,'from_user' => $open));
		}
		
		
		
		if($anhao1!='')
		{
		echo "<script language=javascript>alert('提交成功');</script>";
		}
        include $this->template('kaijiang');
    }
	
	
	
	
	//获奖者名单
	    public function doWebhoujiang($rid, $state) {
        global $_GPC, $_W;
        checklogin();
        $weid = $_W['account']['weid']; //当前公众号ID
        $rid = $_GPC['id'];//规则
        
        $list_order = pdo_fetchall("SELECT * FROM " . tablename($this->table_ceshihou) . " WHERE  rid = '" . $rid . "'");
        $items_total = count($items);
        $total = count($list_order);
        $pager = pagination($total, $pindex, $psize);
		
			
        
        include $this->template('houjiang');
    }
	
	
	
	

    public function doWebstatus($rid = 0) {
        global $_GPC;
        $rid = $_GPC['rid'];
        echo $rid;
        $insert = array(
            'status' => $_GPC['status']
        );

        pdo_update($this->table_reply, $insert, array('rid' => $rid));
        message('模块操作成功！', referer(), 'success');
    }

    public function doWebdos($id = 0) {
        global $_GPC;
        $rid = $_GPC['rid'];
        $id = $_GPC['id'];
        echo $id;
        $insert = array(
            'status' => $_GPC['status']
        );
        pdo_update($this->table_order, $insert, array('id' => $id, 'rid' => $rid));
        message('暗号处理操作成功！', $this->createWebUrl('anhaolist', array('name' => 'anhao', 'id' => $rid, 'page' => $_GPC['page'])));
    }

    public function doWebdelete() {
        global $_GPC;
        $id = $_GPC['id'];
        pdo_delete($this->table_item, "id = ('" . $id . "')");
        message('模块操作成功！', referer(), 'success');
        //return true;
    }

    public function ruleDeleted($rid) {
        //删除规则时调用，这里 $rid 为对应的规则编号
        global $_W;
        $replies = pdo_fetchall("SELECT id, picture, headimage FROM " . tablename($this->table_reply) . " WHERE rid = '$rid'");
        $deleteid = array();
        if (!empty($replies)) {
            foreach ($replies as $index => $row) {
                file_delete($row['picture']);
                file_delete($row['headimage']);
                $deleteid[] = $row['id'];
            }
        }
        pdo_delete($this->table_reply, "id IN ('" . implode("','", $deleteid) . "')");
        return true;
    }

}
