<?php
global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();

		$op=$_GPC['op'];
		if($op=='huifu')
		{
			$sql = "
				DROP TABLE IF EXISTS `ims_jy_ppp_setting` ;
				DROP TABLE IF EXISTS `ims_jy_ppp_dianyuan`;
				DROP TABLE IF EXISTS `ims_jy_ppp_price`;
				DROP TABLE IF EXISTS `ims_jy_ppp_xuni_member`;
				DROP TABLE IF EXISTS `ims_jy_ppp_member`;
				DROP TABLE IF EXISTS `ims_jy_ppp_basic`;
				DROP TABLE IF EXISTS `ims_jy_ppp_desc`;
				DROP TABLE IF EXISTS `ims_jy_ppp_aihao`;
				DROP TABLE IF EXISTS `ims_jy_ppp_tezheng`;
				DROP TABLE IF EXISTS `ims_jy_ppp_match`;
				DROP TABLE IF EXISTS `ims_jy_ppp_mianrao`;
				DROP TABLE IF EXISTS `ims_jy_ppp_feedback`;
				DROP TABLE IF EXISTS `ims_jy_ppp_visit`;
				DROP TABLE IF EXISTS `ims_jy_ppp_attent`;
				DROP TABLE IF EXISTS `ims_jy_ppp_black`;
				DROP TABLE IF EXISTS `ims_jy_ppp_chat_doubi`;
				DROP TABLE IF EXISTS `ims_jy_ppp_code`;
				DROP TABLE IF EXISTS `ims_jy_ppp_mobile`;
				DROP TABLE IF EXISTS `ims_jy_ppp_idcard`;
				DROP TABLE IF EXISTS `ims_jy_ppp_thumb`;
				DROP TABLE IF EXISTS `ims_jy_ppp_report`;
				DROP TABLE IF EXISTS `ims_jy_ppp_login_log`;
				DROP TABLE IF EXISTS `ims_jy_ppp_credit_log`;
				DROP TABLE IF EXISTS `ims_jy_ppp_baoyue_log`;
				DROP TABLE IF EXISTS `ims_jy_ppp_shouxin_log`;
				DROP TABLE IF EXISTS `ims_jy_ppp_pay_log`;
				DROP TABLE IF EXISTS `ims_jy_ppp_kefu`;
				DROP TABLE IF EXISTS `ims_jy_ppp_xinxi`;
				DROP TABLE IF EXISTS `ims_jy_ppp_help`;
				DROP TABLE IF EXISTS `ims_jy_ppp_safe`;
				DROP TABLE IF EXISTS `ims_jy_ppp_zhaohu`;
				DROP TABLE IF EXISTS `ims_jy_ppp_xuni_thumb`;
				DROP TABLE IF EXISTS `ims_jy_ppp_xunithumb`;
			";
			pdo_query($sql);

			$sql2 = "
				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_setting` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `aname` varchar(255) NOT NULL,
				  `sharetitle` varchar(255) NOT NULL,
				  `sharedesc` varchar(255) NOT NULL,
				  `sharelogo` varchar(255) NOT NULL,
				  `copyright` varchar(255) NOT NULL,
				  `copyrighturl` varchar(255) NOT NULL,
				  `zhuce_bg` varchar(255) NOT NULL,
				  `zhuce_text` varchar(255) NOT NULL,
				  `adminthumb` varchar(255) NOT NULL,
				  `sms_type` int(10) NOT NULL DEFAULT '0' COMMENT '0,1为互亿无线,2为微擎',
				  `sms_sign` varchar(255) NOT NULL,
				  `sms_product` varchar(255) NOT NULL,
				  `sms_username` varchar(255) NOT NULL,
				  `sms_pwd` varchar(255) NOT NULL,
				  `address` int(10) DEFAULT '0',
				  `province` int(10) DEFAULT '11',
				  `city` int(10) DEFAULT '1101',
				  `chat` int(10) DEFAULT '20',
				  `idcard` int(10) DEFAULT '60',
				  `doubi` varchar(255) NOT NULL DEFAULT '豆币',
				  `unit` varchar(255) NOT NULL DEFAULT '豆',
				  `tel` varchar(255) NOT NULL,
				  `kftime` varchar(255) NOT NULL,
				  `jiange` int(10) DEFAULT '30',
			  	  `shangxian` int(10) DEFAULT '12',
				  `rule` text,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_dianyuan` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) unsigned NOT NULL,
				  `from_user` varchar(50) NOT NULL DEFAULT '',
				  `uid` int(10) NOT NULL,
				  `status` int(10) unsigned NOT NULL DEFAULT 1,
				  `username` varchar(50) NOT NULL DEFAULT '',
				  `mobile` varchar(20) NULL,
				  `mail` varchar(200) NULL,
				  `QQ` varchar(200) NULL,
				  `wechat` varchar(200) NULL,
				  `password` varchar(200) NULL,
				  `description` text,
				  `createtime` int(10) unsigned NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_price` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`weid` int(11)  NOT NULL,
					`displayorder` int(11)  NOT NULL DEFAULT '0' COMMENT 'ForOrder',
					`fee` int(10) NOT NULL,
					`credit` int(10) NOT NULL,
					`baoyue` int(10) NOT NULL,
					`shouxin` int(10) NOT NULL,
				  	`log` int(10) NOT NULL COMMENT '1为购买豆币,2为购买包月服务,3为购买收信宝',
					`description` varchar(255) NOT NULL,
					`enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
					PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_xuni_member` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) unsigned NOT NULL,
				  `mid` int(10) NOT NULL,
				  `dyid` int(10) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_xunithumb` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`mid` int(11)  NOT NULL,
					`sex` int(2) NOT NULL COMMENT '1为男,2为女',
					`avatar` int(2) NOT NULL DEFAULT 0 COMMENT '1为头像,0为普通',
					`thumb` text,
					PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_member` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `uid` int(10) NOT NULL,
				  `from_user` varchar(50) NOT NULL,
				  `nicheng` varchar(255) NOT NULL,
				  `avatar` varchar(255) ,
				  `mobile` varchar(11) NOT NULL,
				  `pwd` varchar(200) NOT NULL,
				  `beizhu` varchar(255) NOT NULL,
				  `sex` int(2) NOT NULL COMMENT '1为男,2为女',
				  `status` int(2) NOT NULL COMMENT '封号与否',
				  `brith` int(10) NOT NULL,
				  `province` int(10) NOT NULL,
				  `city` int(10) NOT NULL,
				  `credit` int(10) DEFAULT '0' COMMENT '金币',
				  `baoyue` int(10)  COMMENT '包月过期时间',
				  `shouxin` int(10)  COMMENT '收信宝过期时间',
				  `type` int(2) NOT NULL COMMENT '2为工作人员虚拟用户,,1为微信,0为账户',
				  `mobile_auth` int(2) DEFAULT '0' COMMENT '1为认证,0为未认证',
				  `card_auth` int(2) DEFAULT '0' COMMENT '1为认证,0为未认证',
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`province`),
				  INDEX (`sex`),
				  INDEX (`mobile`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_basic` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `height` int(10) NOT NULL,
				  `weight` int(10) NOT NULL,
				  `blood` varchar(50) NOT NULL,
				  `education` varchar(50) NOT NULL,
				  `job` varchar(200) NOT NULL,
				  `income` varchar(200) NOT NULL,
				  `marriage` varchar(200) NOT NULL,
				  `house` varchar(200) NOT NULL,
				  `blank` int(10) NOT NULL COMMENT '未填写的字段个数',
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_desc` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `child` varchar(200) NOT NULL,
				  `yidi` varchar(200) NOT NULL,
				  `leixin` varchar(200) NOT NULL,
				  `sex` varchar(200) NOT NULL,
				  `fumu` varchar(200) NOT NULL,
				  `meili` varchar(200) NOT NULL,
				  `blank` int(10) NOT NULL COMMENT '未填写的字段个数',
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_aihao` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `aihao` varchar(200) NOT NULL,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_tezheng` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `tezheng` varchar(200) NOT NULL,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_match` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `age` int(10) DEFAULT '0' COMMENT '0为不限,1为18-25,2为26-35,3为36-45,4为46-55,5为55以上',
				  `height` int(10) DEFAULT '0' COMMENT '0为不限,1为160以下,2为161-165,3为166-170,4为170以上',
				  `education` int(10) DEFAULT '0' COMMENT '0为不限,1为高中,中专及以上,2为大专及以上,3为本科及以上',
				  `income` int(10) DEFAULT '0' COMMENT '0为不限,1为2000元以上,2为5000元以上,3为10000元以上',
				  `province` int(10) NOT NULL,
				  `blank` int(10) NOT NULL COMMENT '未填写的字段个数',
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_mianrao` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `zhaohu` int(10) DEFAULT '0' COMMENT '0为不限,1为拒绝异性发来的招呼类信件',
				  `thumb` int(10) DEFAULT '0' COMMENT '0为不限,1为拒绝无头像的异性信件',
				  `province` int(10) DEFAULT '0' COMMENT '0为不限,1为拒绝异省的异性信件',
				  `age` int(10) DEFAULT '0' COMMENT '0为不限,1为拒绝不符合征友条件年龄的异性信件',
				  `mobile` int(10) DEFAULT '0' COMMENT '0为不限,1为拒绝未验证手机的异性信件',
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_feedback` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `feedback` varchar(255) NOT NULL,
				  `mobile` varchar(11) NOT NULL,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_visit` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `visitid` int(10) NOT NULL,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_attent` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `attentid` int(10) NOT NULL,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`),
				  INDEX (`attentid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_black` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `blackid` int(10) NOT NULL,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`),
				  INDEX (`blackid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_chat_doubi` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `chatid` int(10) NOT NULL,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_code` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mobile` varchar(11) NOT NULL,
				  `code` varchar(200) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_mobile` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mobile` varchar(11) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE  IF NOT EXISTS `ims_jy_ppp_idcard` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `idcard` varchar(20) NOT NULL,
				  `realname` varchar(100) NOT NULL,
				  `sex` int(2) NOT NULL COMMENT '1为男,2为女',
				  `brith` int(10) NOT NULL,
				  `province` int(10) NOT NULL,
				  `city` int(10) NOT NULL,
				  `mid` int(10) NOT NULL,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_thumb` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`weid` int(11)  NOT NULL,
					`mid` int(11)  NOT NULL,
					`type` int(2)  DEFAULT '0' COMMENT '0为审核中,1为头像,2为非头像,3为不通过,4为删除',
					`thumb` longtext,
					`createtime` int(10) NOT NULL,
					PRIMARY KEY (`id`),
				  	INDEX (`mid`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_report` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`weid` int(11)  NOT NULL,
					`mid` int(11)  NOT NULL,
					`reportid` int(11)  NOT NULL,
					`report` text,
					`status` int(2)  DEFAULT '0' COMMENT '0为审核中,1审核过',
					`createtime` int(10) NOT NULL,
					PRIMARY KEY (`id`),
				  	INDEX (`mid`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_login_log` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) unsigned NOT NULL,
				  `mid` int(10) NOT NULL,
				  `createtime` int(10) unsigned NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_credit_log` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) unsigned NOT NULL,
				  `mid` int(10) NOT NULL,
				  `credit` int(10) NOT NULL,
				  `type` varchar(255) NOT NULL COMMENT 'add,reduce' ,
				  `log` int(10) NOT NULL COMMENT '1为身份认证消耗积分,2为积分兑换聊天机会,3为系统变更积分,4为用户充值积分',
				  `logid` int(10) NOT NULL COMMENT '对方用户的id或充值记录id',
				  `createtime` int(10) unsigned NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_baoyue_log` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) unsigned NOT NULL,
				  `mid` int(10) NOT NULL,
				  `starttime` int(10) NOT NULL,
				  `endtime` int(10) NOT NULL,
				  `logid` int(10) NOT NULL COMMENT '充值记录id',
				  `createtime` int(10) unsigned NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_shouxin_log` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) unsigned NOT NULL,
				  `mid` int(10) NOT NULL,
				  `starttime` int(10) NOT NULL,
				  `endtime` int(10) NOT NULL,
				  `logid` int(10) NOT NULL COMMENT '充值记录id',
				  `createtime` int(10) unsigned NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_pay_log` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) unsigned NOT NULL,
				  `mid` int(10) NOT NULL,
				  `from_user` varchar(250) COMMENT '付款的from_user,为空时代表自己付的款',
				  `fee` int(10) NOT NULL,
				  `log` int(10) NOT NULL COMMENT '1为购买豆币,2为购买包月服务,3为购买收信宝',
				  `status` int(10) NOT NULL COMMENT '付款状态',
				  `plid` bigint(11) unsigned COMMENT 'core_paylog表的id',
				  `paytime` int(10) unsigned NOT NULL,
				  `createtime` int(10) unsigned NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_kefu` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL DEFAULT '0',
				  `type` varchar(255) NOT NULL DEFAULT 'text' COMMENT '客服接口消息类型',
				  `content` text,
				  `desc` text,
				  `url` text,
				  `picurl` text,
				  `status` int(10) DEFAULT '0' COMMENT '0为成功,其他为错误代码',
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_xinxi` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) NOT NULL,
				  `mid` int(10) NOT NULL DEFAULT '0',
				  `sendid` int(10) NOT NULL DEFAULT '0' COMMENT '发送人员',
				  `content` text,
				  `zhaohuid` int(10) DEFAULT '0' COMMENT '打招呼id',
				  `huifuid` int(10) DEFAULT '0' COMMENT '回复打招呼id',
				  `type` int(10) DEFAULT '0' COMMENT '0为打招呼,1为会员推荐,2为最新回信,3为管理员',
				  `yuedu` tinyint(1) DEFAULT '0' COMMENT '0为未读,1为已读',
				  `yuedutime` int(10) NOT NULL,
				  `createtime` int(10) NOT NULL,
				  PRIMARY KEY (`id`),
				  INDEX (`mid`),
				  INDEX (`sendid`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_help` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
				  `name` varchar(50) NOT NULL COMMENT '名称',
				  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '批次ID,0为第一级',
				  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
				  `description` text COMMENT '描述',
				  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_safe` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
				  `name` varchar(50) NOT NULL COMMENT '名称',
				  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '批次ID,0为第一级',
				  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
				  `description` text COMMENT '描述',
				  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
				  `paixu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

				CREATE TABLE IF NOT EXISTS `ims_jy_ppp_zhaohu` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`weid` int(11)  NOT NULL,
					`displayorder` int(11)  NOT NULL DEFAULT '0' COMMENT 'ForOrder',
					`name` varchar(255) NOT NULL,
					`parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '批次ID,0为第一级',
					`description` varchar(255) NOT NULL,
					`enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
					PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			";

			pdo_query($sql2);
			message("恢复系统成功!",$this->createWebUrl('setting'),'success');
		}
		else
		{

			load()->func('tpl');

			$item=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
			if(empty($item))
			{
				$item['zhuce_bg']='../addons/jy_ppp/images/speed_2015114.jpg';
				$item['adminthumb']='../addons/jy_ppp/images/adminthumb.png';
				$item['kftime']="9:00-21:00 周一至周五";
				$item['doubi']="豆币";
				$item['unit']="豆";
				$item['chat']=20;
				$item['idcard']=60;
				$item['jiange']=30;
				$item['shangxian']=12;
				$item['zhuce_text']="1亿9836万美女帅哥在这里等你哦~";
				$item['rule']="本网站是一个严肃纯净的婚恋交友软件，用户（以下也称“会员”）在此注册为征友会员并在之后进行的征友活动中应遵守以下会员注册条款：<br/><br/>1、注册条款的接受<br/>一旦会员注册即表示会员已经阅读并且同意该协议，并接受所有的注册条款。<br/><br/>2、会员注册条件<br/>1) 申请注册成为会员应同时满足下列全部条件：在注册之日以及此后使用交友服务期间必须以恋爱或者婚姻为目的；在注册之日以及此后使用交友服务期间必须是单身状态，包括未婚、离异或是丧偶；在注册之日必须年满18周岁以上。<br/>2) 为更好的享有提供的服务，会员应：提供本人真实、正确、最新及完整的资料； 随时更新登记资料，保持其真实性及有效性；提供真实有效的联系人手机号码；征友过程中，务必保持征友帐号的唯一性。<br/>3) 若会员提供任何错误、不实或不完整的资料，或被怀疑资料为错误、不实或不完整及违反会员注册条款的，或被怀疑其会员资料、言行等有悖于“严肃纯净的婚恋交友”主题的，官方将有权修改会员的注册昵称、独白等，或暂停或终止该会员的帐号，或暂停或终止提供全部或部分服务。<br/><br/>3、会员账号名称安全<br/>任何注册和使用的账号名称，不得有下列情形：<br/>（一）违反宪法或法律法规规定的；<br/>（二）危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；<br/>（三）损害国家荣誉和利益的，损害公共利益的；<br/>（四）煽动民族仇恨、民族歧视，破坏民族团结的；<br/>（五）破坏国家宗教政策，宣扬邪教和封建迷信的；<br/>（六）散布谣言，扰乱社会秩序，破坏社会稳定的；<br/>（七）散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；<br/>（八）侮辱或者诽谤他人，侵害他人合法权益的；<br/>（九）含有法律、行政法规禁止的其他内容的。<br/>会员以虚假信息骗取账号名称注册，或其账号头像、简介等注册信息存在违法和不良信息的，官方有权采取通知限期改正、暂停使用、注销登记等措施。<br/>对冒用关联机构或社会名人注册账号名称的会员，官方有权注销其账号。<br/><br/>4、服务说明<br/>1) 在提供网络服务时，可能会对部分网络服务收取一定的费用，在此情况下，会在相关页面上做明确的提示。如会员拒绝支付该等费用，则不能使用相关的网络服务。付费业务将在本注册条款的基础上另行规定服务条款，以规范付费业务的内容和双方的权利义务，会员应认真阅读，如会员购买付费业务，则视为接受付费业务的服务条款。<br/>2) 无论是付费业务还是免费提供服务，上述服务均有有效期，有效期结束后服务将自动终止，且有效期不可中断或延期。除非本注册条款另有规定，所有付费业务均不退费。<br/>3) 对于利用服务进行非法活动，或其言行（无论线上或者线下的）背离严肃交友目的的，官方将严肃处理，包括将其列入黑名单、将其被投诉的情形公之于众、删除会员帐号等处罚措施。<br/>4) 官方权向其会员发送广告信，或为组织线下活动等目的，向其会员发送电子邮件、短信或电话通知。由于手机网络的特殊性，官方有权获取会员的手机信息，如手机号码或会员的基站位置等。<br/>5) 为提高会员的交友的成功率和效率的目的，官方有权将会员的交友信息在合作网站上进行展示或其他类似行为。<br/><br/>5、免责条款<br/>1) 不保证其提供的服务一定能满足会员的要求和期望，也不保证服务不会中断，对服务的及时性、安全性、准确性都不作保证。<br/>2) 对于会员通过提供的服务传送的内容，官方会尽合理努力按照国家有关规定严格审查，但无法完全控制经由网站服务传送的内容，不保证内容的正确性、完整性或品质。因此会员在使用服务时，可能会接触到令人不快、不适当或令人厌恶的内容。在任何情况下，官方均不为会员经由网站服务以张贴、发送电子邮件或其它方式传送的任何内容负责。但官方有权依法停止传输任何前述内容并采取相应行动，包括但不限于暂停会员使用网站服务的全部或部分，保存有关记录，并根据国家法律法规、相关政策在必要时向有关机关报告并配合有关机关的行动。<br/>3) 对于网站提供的各种广告信息、链接、资讯等，官方会对广告内容进行初步审核，但是难以确保对方产品真实性、合法性或可靠性，由于产品购买导致的相关责任主要由广告商承担；敬告用户理性看待，如需购买或者交易，请谨慎考虑。并且，对于会员经由服务与广告商进行联系或商业往来，完全属于会员和广告商之间的行为，与官方无关。对于前述商业往来所产生的任何损害或损失，官方不承担任何责任。<br/>4) 对于用户上传的照片、资料、证件等，官方已采用相关措施并已尽合理努力进行审核，但不保证其内容的正确性、合法性或可靠性，相关责任由上传上述内容的会员负责。<br/>5) 会员以自己的独立判断从事与交友相关的行为，并独立承担可能产生的不利后果和责任，官方不承担任何法律责任。<br/>6)依据有关法律法规的规定或依据行政机关、司法机关、检察机关的要求，向其提供会员的基本信息或站内聊天信息，上述行为侵犯会员隐私权的，官方不承担任何法律责任。<br/>7)作为交友平台，帮助用户寻找心仪伴侣是我们的服务内容。官方推出的有缘小助手、红娘服务、收信宝等服务，全都是在用户同意并主动授权的情况下帮助授权用户寻找异性的功能性服务。不能保证用户通过此类服务授权由系统自动发出或接收到的信息完全满足用户交友需求。<br/>有缘小助手，为了提高用户处理私信的能力，看到更多符合自己要求的异性来信，提供了有缘小助手服务。在用户主动授权设置有缘小助手的情况下，有缘小助手会帮助用户过滤掉信箱中异性发来的招呼类信件，此类过滤掉的私信只是设为已读，仍然存于授权用户的信箱中。过滤的同时给符合授权用户征友要求的异性发送系统自动做出的信件回复。回复内容由官方根据用户交友互动中最常用的词语拟定，并由系统随机选取后发送。<br/>红娘服务，为了帮助女用户找到符合自己要求的异性，提供了红娘服务。红娘服务内容包括：优先将红娘会员推荐给优质男性用户，获得更多交流机会；红娘将帮助申请红娘服务的用户向符合其征友要求的异性询问交友意向；实时监控询问总数。<br/>收信宝，为了帮助男用户找到符合自己要求的异性，提供了收信宝服务。收信宝服务内容包括：优先将收信宝用户展示给女用户，通过替男用户打招呼的方式向符合男用户征友要求的女用户介绍男用户，从而增加男用户收信。 <br/>以上三种服务用户可以自行取消。<br/>8)  为了促进用户互动，产品上的组件应用或小游戏都带有触发招呼的功能。用户在玩此功能的组件或小游戏的同时会给符合自己征友要求的异性发去问候招呼。不能保证用户通过此类组件或小游戏发出或接受到的信息完全满足用户的交友需求。<br/><br/>6、会员应遵守以下法律法规：<br/>1) 提醒会员在使用服务时，遵守《中华人民共和国合同法》、《中华人民共和国著作权法》、《全国人民代表大会常务委员会关于维护互联网安全的决定》、《中华人民共和国保守国家秘密法》、《中华人民共和国电信条例》、《中华人民共和国计算机信息系统安全保护条例》、《中华人民共和国计算机信息网络国际联网管理暂行规定》及其实施办法、《计算机信息系统国际联网保密管理规定》、《互联网信息服务管理办法》、《计算机信息网络国际联网安全保护管理办法》、《互联网电子公告服务管理规定》、《互联网用户账号名称管理规定》等相关中国法律法规的规定。<br/>2) 在任何情况下，如果官方有理由认为会员使用服务过程中的任何行为，包括但不限于会员的任何言论和其它行为违反或可能违反上述法律和法规的任何规定，可在任何时候不经任何事先通知终止向该会员提供服务。<br/><br/>7、禁止会员从事下列行为:<br/>1)发布信息或者利用服务时在网页上或者利用服务制作、复制、发布、传播以下信息：反对宪法所确定的基本原则的；危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；损害国家荣誉和利益的；煽动民族仇恨、民族歧视、破坏民族团结的；破坏国家宗教政策，宣扬邪教和封建迷信的；散布谣言，扰乱社会秩序，破坏社会稳定的；散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；侮辱或者诽谤他人，侵害他人合法权利的；含有虚假、有害、胁迫、侵害他人隐私、骚扰、侵害、中伤、粗俗、猥亵、或其它有悖道德、令人反感的内容；含有中国法律、法规、规章、条例以及任何具有法律效力的规范所限制或禁止的其它内容的。<br/>2) 使用服务的过程中，以任何方式危害未成年人的利益的。<br/>3) 冒充任何人或机构，包含但不限于冒充工作人员、版主、主持人，或以虚伪不实的方式陈述或谎称与任何人或机构有关的。<br/>4) 将侵犯任何人的肖像权、名誉权、隐私权、专利权、商标权、著作权、商业秘密或其它专属权利的内容上载、张贴、发送电子邮件或以其它方式传送的。<br/>5) 将病毒或其它计算机代码、档案和程序，加以上载、张贴、发送电子邮件或以其它方式传送的。<br/>6) 跟踪或以其它方式骚扰其他会员的。<br/>7) 未经合法授权而截获、篡改、收集、储存或删除他人个人信息、电子邮件或其它数据资料，或将获知的此类资料用于任何非法或不正当目的。<br/>8) 以任何方式干扰服务的。<br/><br/>8、关于会员在婚恋的上传或张贴的内容<br/>1) 会员上传或张贴的内容（包括照片、文字、交友成功会员的成功故事等），视为会员授予官方免费、非独家的使用权，有权为展示、传播及推广前述张贴内容的目的，对上述内容进行复制、修改、出版等。该使用权持续至会员书面通知官方不得继续使用，且以实际收到该等书面通知时止。官方合作伙伴使用或在现场活动中使用，官方将事先征得会员的同意，但官方使用不受此限。<br/>2) 因会员进行上述上传或张贴，而导致任何第三方提出侵权或索赔要求的，会员承担全部责任。<br/>3) 任何第三方对于会员在公开使用区域张贴的内容进行复制、修改、编辑、传播等行为的，该行为产生的法律后果和责任均由行为人承担，与官方无关。<br/><br/>9、会员注册条款的变更和修改<br/>有权随时对本注册条款进行变更和修改。一旦发生注册条款的变动，将在页面上提示修改的内容，或将最新版本的会员注册条款以邮件的形式发送给会员。会员如果不同意会员注册条款的修改，可以主动取消会员资格（如注销账号），如对部分服务支付了额外的费用，可以申请将费用全额或部分退回。如果会员继续使用会员帐号，则视为会员已经接受会员注册条款的修改。<br/>";
			}

			if(checksubmit()) {
				if(empty($_GPC['city']))
				{
					$city=$_GPC['province']."01";
				}
				$data=array(
						'weid'=>$weid,
						'aname'=>$_GPC['aname'],
						'sharetitle'=>$_GPC['sharetitle'],
						'sharedesc'=>$_GPC['sharedesc'],
						'sharelogo'=>$_GPC['sharelogo'],
						'zhuce_bg'=>$_GPC['zhuce_bg'],
						'zhuce_text'=>$_GPC['zhuce_text'],
						'adminthumb'=>$_GPC['adminthumb'],
						'sms_type'=>$_GPC['sms_type'],
						'sms_sign'=>$_GPC['sms_sign'],
						'sms_username'=>$_GPC['sms_username'],
						'sms_pwd'=>$_GPC['sms_pwd'],
						'sms_product'=>$_GPC['sms_product'],
						'rule'=>$_GPC['rule'],
						'tel'=>$_GPC['tel'],
						'doubi'=>$_GPC['doubi'],
						'unit'=>$_GPC['unit'],
						'chat'=>$_GPC['chat'],
						'idcard'=>$_GPC['idcard'],
						'address'=>$_GPC['address'],
						'province'=>$_GPC['province'],
						'city'=>$city,
						'kftime'=>$_GPC['kftime'],
						'jiange'=>$_GPC['jiange'],
						'shangxian'=>$_GPC['shangxian'],
						'copyright'=>$_GPC['copyright'],
						'copyrighturl'=>$_GPC['copyrighturl'],
						'createtime'=>TIMESTAMP,
					);
				if(empty($item['id']))
				{
					pdo_insert("jy_ppp_setting",$data);
				}
				else
				{
					pdo_update("jy_ppp_setting",$data,array('weid'=>$weid));
				}

				message("设置成功!",$this->createWebUrl('setting'),'success');
			}
			include $this->template('web/setting');
		}