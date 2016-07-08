<?php
/**
 * 微信红包模块处理程序
 *
 * @author lizhangqu
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');


class Lzq_redpackModuleProcessor extends WeModuleProcessor {
	public function respond() {
		//load()->func("logging"); 
		//logging_run("测试");
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
		$openid=$this->message['from'];
		$type=$this->message['type'];
	
		if($type=='text'){
			$now=date("y-m-d h:i:s");
			$starttime=$this->module['config']['starttime'];
			$endtime=$this->module['config']['endtime'];
			
			if(strtotime($now)<strtotime($starttime)){
				return $this->respText("您来早了，活动还没开始！！！");
			}
			
			if(strtotime($endtime)<strtotime($now)){
				return $this->respText("您来迟了，活动已结束！！！");
			}
			
			$content = $this->message['content'];
			if($content==$this->module['config']['touchkey']){
				$result = pdo_fetchall("SELECT * FROM ".tablename('we7_redpack_reply')." WHERE openid = :openid and appid = :appid ",array(':openid' => $openid,':appid' => $this->module['config']['appid']),'openid');
				if(count($result)==1){
					
					if($result[$openid]['hasSub']==true){
						return $this->respText("您已参与过本活动，请不要重复操作！！！");
					}else{
									
						$result=$this->sendRedpack($openid);
						return $this->respText($result);
					}
				}else if(count($result)==0){
						pdo_insert('we7_redpack_reply', array('appid'=>$this->module['config']['appid'],'openid'=>$openid,'hasSub'=>false),false);
						$result=$this->sendRedpack($openid);
						return $this->respText($result);
				}
			
				//未触发
				return $this->respText("您来迟了，活动已结束！！！");	
			}
		}
	}

	public function sendRedpack($param_openid){
		define('ROOT_PATH', dirname(__FILE__));
		define('DS', DIRECTORY_SEPARATOR);
		define('SIGNTYPE', "sha1");
		define('PARTNERKEY',$this->module['config']['partner']);
		define('APPID',$this->module['config']['appid']);
		
		
		
		
		define('apiclient_cert',$this->module['config']['apiclient_cert']);
		define('apiclient_key',$this->module['config']['apiclient_key']);
		define('rootca',$this->module['config']['rootca']);
		
		
		$money=$this->module['config']['money']+rand(0,$this->module['config']['money_extra']);
		
		$min=$this->module['config']['randmin'];
		$max=$this->module['config']['randmax'];
		$sendNum=$this->module['config']['sendnum'];
		$sendArr= explode(',',$sendNum); 
		$rand=rand($min,$max);				
		$isInclude=in_array($rand,$sendArr);
		
		if($isInclude){

			$mch_billno=$this->module['config']['mchid'].date('YmdHis').rand(1000, 9999);//订单号
			include_once(ROOT_PATH.DS.'pay'.DS.'WxHongBaoHelper.php');
			$commonUtil = new CommonUtil();
			$wxHongBaoHelper = new WxHongBaoHelper();
								
							
			$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串，不长于32位
			$wxHongBaoHelper->setParameter("mch_billno", $mch_billno);//订单号
			$wxHongBaoHelper->setParameter("mch_id", $this->module['config']['mchid']);//商户号
			$wxHongBaoHelper->setParameter("wxappid", $this->module['config']['appid']);
			$wxHongBaoHelper->setParameter("nick_name", $this->module['config']['nick_name']);//提供方名称
			$wxHongBaoHelper->setParameter("send_name", $this->module['config']['send_name']);//红包发送者名称
			$wxHongBaoHelper->setParameter("re_openid", $param_openid);//相对于医脉互通的openid
			$wxHongBaoHelper->setParameter("total_amount", $money);//付款金额，单位分
			$wxHongBaoHelper->setParameter("min_value", $money);//最小红包金额，单位分
			$wxHongBaoHelper->setParameter("max_value", $money);//最大红包金额，单位分
			$wxHongBaoHelper->setParameter("total_num", 1);//红包发放总人数
			$wxHongBaoHelper->setParameter("wishing", $this->module['config']['wishing']);//红包祝福诧
			$wxHongBaoHelper->setParameter("client_ip", '127.0.0.1');//调用接口的机器 Ip 地址
			$wxHongBaoHelper->setParameter("act_name", $this->module['config']['act_name']);//活劢名称
			$wxHongBaoHelper->setParameter("remark", $this->module['config']['remark']);//备注信息
		
			$wxHongBaoHelper->setParameter("logo_imgurl", "https://www.baidu.com/img/bdlogo.png");//商户logo的url
			$wxHongBaoHelper->setParameter("share_content", '分享文案测试');//分享文案
			$wxHongBaoHelper->setParameter("share_url", "http://baidu.com");//分享链接
			$wxHongBaoHelper->setParameter("share_imgurl", "http://avatar.csdn.net/1/4/4/1_sbsujjbcy.jpg");//分享的图片url
			
			
			$postXml = $wxHongBaoHelper->create_hongbao_xml();
			
			
			$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		
			$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
		
			$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
			$return_code=$responseObj->return_code;
			$result_code=$responseObj->result_code;
			
			if($return_code=='SUCCESS'){
				if($result_code=='SUCCESS'){
					$total_amount=$responseObj->total_amount*1.0/100;
					$result = pdo_update('we7_redpack_reply',array('hasSub'=>true,'money' =>$total_amount,'time'=>date('Y-m-d H:i:s', time())),array('openid'=>$param_openid,'appid'=>$this->module['config']['appid']));			
				
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
			pdo_update('we7_redpack_reply',array('hasSub'=>true,'money' =>0,'time'=>date('Y-m-d H:i:s', time())),array('openid'=>$param_openid,'appid'=>$this->module['config']['appid']));				

			return "很遗憾，您没有抢到红包！感谢您的参与！";
		}
		
	}
}
