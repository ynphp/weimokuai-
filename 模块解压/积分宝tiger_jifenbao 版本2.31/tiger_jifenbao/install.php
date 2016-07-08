<?php
$sql="
DROP TABLE IF EXISTS `ims_tiger_jifenbao_ad`;
CREATE TABLE `ims_tiger_jifenbao_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(250) DEFAULT '0',
  `pic` varchar(250) DEFAULT '0',
  `url` varchar(250) DEFAULT '0',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_dianyuan`;
CREATE TABLE `ims_tiger_jifenbao_dianyuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `nickname` varchar(50) DEFAULT '0',
  `ename` varchar(50) DEFAULT '0',
  `tel` varchar(50) DEFAULT '0',
  `password` varchar(50) DEFAULT '0',
  `companyname` varchar(200) DEFAULT '0',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_goods`;
CREATE TABLE `ims_tiger_jifenbao_goods` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `px` int(10) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `hot` varchar(50) NOT NULL,
  `hotcolor` varchar(50) NOT NULL,
  `leibei` varchar(10) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `day_sum` int(11) NOT NULL DEFAULT '0',
  `deadline` datetime NOT NULL,
  `per_user_limit` int(11) NOT NULL DEFAULT '0',
  `starttime` varchar(12) DEFAULT NULL,
  `endtime` varchar(12) DEFAULT NULL,
  `cost` int(11) NOT NULL DEFAULT '0',
  `cost_type` int(11) NOT NULL DEFAULT '1' COMMENT '1系统积分 2会员积分 4,8等留作扩展',
  `price` int(11) NOT NULL DEFAULT '100',
  `vip_require` int(10) NOT NULL DEFAULT '0' COMMENT '兑换最低VIP级别',
  `content` text NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '是否需要填写收货地址,1,实物需要填写地址,0虚拟物品不需要填写地址',
  `dj_link` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_hexiao`;
CREATE TABLE `ims_tiger_jifenbao_hexiao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `dianyanid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `nickname` varchar(50) DEFAULT '0',
  `ename` varchar(50) DEFAULT '0',
  `companyname` varchar(200) DEFAULT '0',
  `goodname` varchar(200) DEFAULT '0',
  `goodid` int(11) DEFAULT '0',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_member`;
CREATE TABLE `ims_tiger_jifenbao_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `from_user` varchar(100) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `helpid` int(11) NOT NULL,
  `unionid` varchar(100) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `follow` tinyint(1) NOT NULL DEFAULT '0',
  `headimgurl` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `time` int(13) DEFAULT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_paylog`;
CREATE TABLE `ims_tiger_jifenbao_paylog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `dwnick` varchar(255) DEFAULT NULL COMMENT '微信用户昵称',
  `dopenid` varchar(255) DEFAULT NULL COMMENT '微信用户openid',
  `dtime` int(11) DEFAULT NULL COMMENT '打款时间',
  `dcredit` int(11) DEFAULT NULL COMMENT '消耗积分',
  `dtotal_amount` int(11) DEFAULT NULL COMMENT '金额，分为单位',
  `dmch_billno` varchar(50) DEFAULT NULL COMMENT '生成的商户订单号',
  `dissuccess` tinyint(1) DEFAULT NULL COMMENT '是否打款成功',
  `dresult` varchar(255) DEFAULT NULL COMMENT '失败提示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_poster`;
CREATE TABLE `ims_tiger_jifenbao_poster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `type` int(1) DEFAULT NULL,
  `data` text,
  `createtime` varchar(12) DEFAULT NULL,
  `bg` varchar(200) DEFAULT NULL,
  `tzurl` varchar(250) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `doneurl` varchar(200) DEFAULT NULL,
  `tipsurl` varchar(200) DEFAULT NULL,
  `score` int(11) DEFAULT '0',
  `score2` int(11) DEFAULT '0',
  `cscore` int(11) DEFAULT '0',
  `cscore2` int(11) DEFAULT '0',
  `pscore` int(11) DEFAULT '0',
  `pscore2` int(11) DEFAULT '0',
  `scorehb` float DEFAULT '0',
  `cscorehb` float DEFAULT '0',
  `pscorehb` float DEFAULT '0',
  `mbfont` varchar(50) DEFAULT NULL,
  `gid` int(11) DEFAULT '0',
  `kdtype` tinyint(1) NOT NULL DEFAULT '0',
  `kword` varchar(20) DEFAULT NULL,
  `mtips` varchar(200) DEFAULT NULL,
  `sliders` text,
  `slideH` int(11) DEFAULT '0',
  `credit` int(1) DEFAULT '0',
  `winfo1` varchar(200) DEFAULT NULL,
  `winfo2` varchar(200) DEFAULT NULL,
  `winfo3` varchar(200) DEFAULT NULL,
  `sharekg` int(11) DEFAULT '0',
  `sharetitle` varchar(200) DEFAULT NULL,
  `sharegzurl` varchar(200) DEFAULT NULL,
  `sharedesc` varchar(200) DEFAULT NULL,
  `sharethumb` varchar(200) DEFAULT NULL,
  `stitle` text,
  `sthumb` text,
  `sdesc` text,
  `surl` text,
  `questions` text,
  `rid` int(11) DEFAULT '0',
  `rtype` int(1) DEFAULT '0',
  `ftips` text,
  `utips` text,
  `utips2` text,
  `wtips` text,
  `starttime` varchar(12) DEFAULT NULL,
  `endtime` varchar(12) DEFAULT NULL,
  `mbstyle` varchar(50) DEFAULT NULL,
  `mbcolor` varchar(50) DEFAULT NULL,
  `nostarttips` text,
  `endtips` text,
  `grouptips` text,
  `tztype` tinyint(1) NOT NULL DEFAULT '0',
  `groups` text,
  `rscore` int(11) DEFAULT '0',
  `rtips` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_record`;
CREATE TABLE `ims_tiger_jifenbao_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `score` int(11) DEFAULT '0',
  `surplus` int(11) DEFAULT '0',
  `createtime` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_request`;
CREATE TABLE `ims_tiger_jifenbao_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user_realname` varchar(50) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(200) NOT NULL,
  `mobile` varchar(200) NOT NULL,
  `residedist` varchar(200) NOT NULL,
  `alipay` varchar(200) NOT NULL,
  `note` varchar(200) NOT NULL,
  `goods_id` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) NOT NULL,
  `kuaidi` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_share`;
CREATE TABLE `ims_tiger_jifenbao_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `from_user` varchar(100) NOT NULL,
  `jqfrom_user` varchar(100) NOT NULL,
  `nickname` varchar(50) DEFAULT '0',
  `avatar` varchar(200) DEFAULT NULL,
  `score` int(11) DEFAULT '0',
  `cscore` int(11) DEFAULT '0',
  `pscore` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `sceneid` int(11) DEFAULT '0',
  `ticketid` varchar(200) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `parentid` int(11) DEFAULT '0',
  `helpid` int(11) DEFAULT '0',
  `follow` tinyint(1) NOT NULL DEFAULT '0',
  `status` int(1) DEFAULT '0',
  `hasdel` int(1) DEFAULT '0',
  `createtime` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`pid`,`openid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_tixianlog`;
CREATE TABLE `ims_tiger_jifenbao_tixianlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `dwnick` varchar(255) DEFAULT NULL COMMENT '微信用户昵称',
  `dopenid` varchar(255) DEFAULT NULL COMMENT '微信用户openid',
  `dtime` int(11) DEFAULT NULL COMMENT '打款时间',
  `dcredit` int(11) DEFAULT NULL COMMENT '消耗积分',
  `dtotal_amount` int(11) DEFAULT NULL COMMENT '金额，分为单位',
  `dmch_billno` varchar(50) DEFAULT NULL COMMENT '生成的商户订单号',
  `dissuccess` tinyint(1) DEFAULT NULL COMMENT '是否打款成功',
  `dresult` varchar(255) DEFAULT NULL COMMENT '失败提示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
";
pdo_run($sql);