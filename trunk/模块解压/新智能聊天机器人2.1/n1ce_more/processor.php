<?php
/**
 * 新智能聊天机器人模块处理程序
 *
 * @author n1ce   QQ：541535641
 * @url http://home.4yuan.cn/
 */
defined('IN_IA') or exit('Access Denied');

class n1ce_moreModuleProcessor extends WeModuleProcessor {
		public function respond() {
		global $_W,$_GPC;
		 $arrtag = $this->message['content'];
		 $win=$this->module['config']['win'];
		 $lose=$this->module['config']['lose'];
		 $draw=$this->module['config']['draw'];
		 $rule=$this->module['config']['rule'];
		 $ad=$this->module['config']['ad'];
		 $aurl=$this->module['config']['aurl'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		if(!$this->inContext){
			$reply="{$win}";
			$this->beginContext();
			return $this->respText($reply);
		}else{
			if($arrtag =="{$lose}"){
				$reply="{$draw}";
				$this->endContext();
				return $this->respText($reply);
			}else{
			$arrtag = $this->message['content'];
			$arrtag =preg_replace("/\s/","",$arrtag);
			$apiKey = "{$rule}"; 
			$apiURL = "http://www.tuling123.com/openapi/api?key=KEY&info=INFO";

			// 设置报文头, 构建请求报文 
			header("Content-type: text/html; charset=utf-8"); 
			$reqInfo = $this->message['content']; 
			$reqInfo = preg_replace("/\s/","",$reqInfo);
			$url = str_replace("INFO", $reqInfo, str_replace("KEY", $apiKey, $apiURL)); 

			/** 方法一、用file_get_contents 以get方式获取内容 */ 
			$res =file_get_contents($url); 
			$strjson=json_decode($res);
			$message=$strjson->text;
			$ti=rand(1,70);
			return $this->respText("$message"."\n-------\n"."你可以输入[{$lose}]退出聊天\n　\n/:heart/:heart/:heart/:heart\n"."<a href='{$aurl}'>{$ad}</a>\n"."/衰耗时0."."{$ti}"."秒");
			//$reply=file_get_contents($url.$inword);
			}
		}
		
			}

}