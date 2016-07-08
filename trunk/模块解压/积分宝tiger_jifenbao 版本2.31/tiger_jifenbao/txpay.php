<?php
  if(!empty($cfg['mchid'])){
	//$mchid=$account['payment']['wechat']['mchid'];
	//$apikey=$account['payment']['wechat']['apikey'];
    $mchid=$cfg['mchid'];
    $apikey=$cfg['apikey'];
    $appid=$cfg['appid'];
    $client_ip=$cfg['client_ip'];
	if(empty($cfg['nbfwxpaypath'])){
		exit(json_encode(array("success"=>4,"msg"=>"证书失败，请联系管理员处理")));
	}
   
	$cert=json_decode($cfg['nbfwxpaypath']);
	$dtotal_amount=$dhPay*100;    
	$wpayconfig=new WxPayConf_micropay(
			$appid,
			$mchid,
			$apikey,
			$cert->certpath,
			$cert->keypath);
	$redpk=new Entpayment_redpack($wpayconfig);    
	$mch_billno=$wpayconfig->mchid.date('Ymd',time()).time();
	$redpk->setParameter("mch_billno",$mch_billno);//商户订单号，需保证唯一,mch_id+yyyymmdd+10位一天内不能重复的数字
	//$redpk->setParameter("re_openid",$_W['fans']['from_user']);//用户openid
    $redpk->setParameter("re_openid",$member['openid']);//借权的用户openid
	$redpk->setParameter("send_name",$_W['account']['name']);//发送者名称
	$redpk->setParameter("total_amount",$dtotal_amount);//发红包，每个红包金额必须大于1元，小于200元
	$redpk->setParameter("total_num",1);
    //$redpk->setParameter("client_ip",$client_ip);
	$redpk->setParameter("wishing","快去邀请好友一起赚红包");
	$redpk->setParameter("act_name","积分兑换红包");
	$redpk->setParameter("remark","来自".$_W['account']['name']."的红包");
	try {
		pdo_begin();
		$dissuccess=0;
		$dresult="继续邀请好友一起赚钱！";
       
		$redpkresult = $redpk->getResult();      
         
		if(IS_DEBUG){            
			logging_run("给用户:[".$_W['fans']['from_user']."]发送红包操作，发送结果:".var_export($redpkresult,true),'info',date('Ymd'));
		}
       
		if($redpkresult["return_code"]=="SUCCESS"){
			if(IS_DEBUG){                
				logging_run("给用户:[".$_W['fans']['from_user']."]发送红包操作1->调用成功!",'info',date('Ymd'));
			}

			if($redpkresult["result_code"]=="FAIL"){
				//失败
				$dissuccess=0;
				if($redpkresult['err_code']=="NO_AUTH"){
				}else if($redpkresult['err_code']=="NOTENOUGH"){
				}else if($redpkresult['err_code']=="MONEY_LIMIT"){
				}else if($redpkresult['err_code']=="TIME_LIMITED"){
				}else if($redpkresult['err_code']=="SEND_FAILED"){
				}else if($redpkresult['err_code']=="FATAL_ERROR"){
				}else if($redpkresult['err_code']=="CA_ERROR"){
				}else if($redpkresult['err_code']=="SIGN_ERROR"){
				}else if($redpkresult['err_code']=="SYSTEMERROR"){
				}else if($redpkresult['err_code']=="XML_ERROR"){
				}else if($redpkresult['err_code']=="FREQ_LIMIT"){
				}else if($redpkresult['err_code']=="OPENID_ERROR"){
				}else if($redpkresult['err_code']=="PARAM_ERROR"){
				}
				$dresult=$redpkresult['err_code'];
				if(IS_DEBUG){                     
					logging_run("给用户:[".$_W['fans']['from_user']."]发送红包操作2->交易失败!",'info',date('Ymd'));
				}
			}else if($redpkresult["result_code"]=="SUCCESS"){
				$dissuccess=1; 
                mc_credit_update($fans['uid'],'credit2',-$dhPay,array($fans['uid'],'余额提现红包'));//记录积分变动 会员积分操作(记录所有的积分变动) 
				if(IS_DEBUG){
					logging_run("给用户:[".$_W['fans']['from_user']."]发送红包操作3->交易成功啦!",'info',date('Ymd'));
				}
			}
		}else if($redpkresult["return_code"]=="FAIL"){
            if($redpkresult['err_code']=="NO_AUTH"){
                $redpkresult['return_msg']='您好，红包自动发送失败，请检查自己微信是否常用，是否异常。';              
            }
			$dresult=$redpkresult['return_msg'];
			if(IS_DEBUG){
				logging_run("给用户:[".$_W['fans']['from_user']."]发送红包操作4->调用接口失败!",'info',date('Ymd'));
			}
		}        
		pdo_insert('tiger_jifenbao_tixianlog',array("uniacid"=>$_W["uniacid"],"dwnick"=>$member['nickname'],"dopenid"=>$_W['fans']['from_user'],"dtime"=>time(),"dcredit"=>$dhPay,"dtotal_amount"=>$dtotal_amount,"dmch_billno"=>$mch_billno,"dissuccess"=>$dissuccess,"dresult"=>$dresult));        
         

		pdo_commit();
		if(IS_DEBUG){
			logging_run("给用户:[".$_W['fans']['from_user']."],昵称：".$_W['fans']['tag']['nickname']."发送红包操作5，插入数据库并提交事务",'info',date('Ymd'));
		}
	} catch (Exception $e) {
		pdo_rollback();
		exit(json_encode(array("success"=>5,"msg"=>$e->getMessage())));
	}
}else{
	exit(json_encode(array("success"=>4,"msg"=>"公众号未开启支付，请联系管理员")));
}
exit(json_encode(array("success"=>1,"msg"=>$dresult)));