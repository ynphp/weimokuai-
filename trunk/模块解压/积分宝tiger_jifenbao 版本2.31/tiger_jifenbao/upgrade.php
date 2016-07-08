<?php
if(!pdo_tableexists('ims_tiger_jifenbao_dianyuan')){
$sql="
CREATE TABLE IF NOT EXISTS `ims_tiger_jifenbao_dianyuan` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `weid` int(11) DEFAULT 0,
   `openid` varchar(50) DEFAULT 0,
   `nickname` varchar(50) DEFAULT 0,
   `ename` varchar(50) DEFAULT 0,
   `tel` varchar(50) DEFAULT 0,
   `password` varchar(50) DEFAULT 0,
   `companyname` varchar(200) DEFAULT 0,	
   `createtime` int(10) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
}

if(!pdo_tableexists('ims_tiger_jifenbao_hexiao')){
$sql1="
CREATE TABLE IF NOT EXISTS `ims_tiger_jifenbao_hexiao` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `weid` int(11) DEFAULT 0,
   `dianyanid` int(11) DEFAULT 0,
   `openid` varchar(50) DEFAULT 0,
   `nickname` varchar(50) DEFAULT 0,
   `ename` varchar(50) DEFAULT 0,
   `companyname` varchar(200) DEFAULT 0,	
   `goodname` varchar(200) DEFAULT 0,
   `goodid` int(11) DEFAULT 0, 
   `createtime` int(10) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql1);
}

if(!pdo_tableexists('ims_tiger_jifenbao_member')){
$sql2="
CREATE TABLE IF NOT EXISTS `ims_tiger_jifenbao_member` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
";
pdo_run($sql2);
}

if(!pdo_tableexists('ims_tiger_jifenbao_paylog')){
$sql3="
CREATE TABLE IF NOT EXISTS `ims_tiger_jifenbao_paylog` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;
";
pdo_run($sql3);
}

if(!pdo_tableexists('ims_tiger_jifenbao_ad')){
$sql4="
CREATE TABLE IF NOT EXISTS `ims_tiger_jifenbao_ad` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `weid` int(11) DEFAULT 0,
   `title` varchar(250) DEFAULT 0,
   `pic` varchar(250) DEFAULT 0,
   `url` varchar(250) DEFAULT 0,	
   `createtime` int(10) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql4);
}

if(!pdo_tableexists('ims_tiger_jifenbao_tixianlog')){
$sql5="
CREATE TABLE IF NOT EXISTS `ims_tiger_jifenbao_tixianlog` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;
";
pdo_run($sql5);
}
if (!pdo_fieldexists('tiger_jifenbao_poster', 'mbcolor')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_poster') . " ADD  `mbcolor` varchar(50);");
}

if (!pdo_fieldexists('tiger_jifenbao_poster', 'mbstyle')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_poster') . " ADD  `mbstyle` varchar(50);");
}

if (!pdo_fieldexists('tiger_jifenbao_poster', 'sharekg')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_poster') . " ADD  `sharekg` int(11);");
}

if (!pdo_fieldexists('tiger_jifenbao_poster', 'sharegzurl')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_poster') . " ADD  `sharegzurl` varchar(200);");
}

if (!pdo_fieldexists('tiger_jifenbao_goods', 'px')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_goods') . " ADD  `px` int(10) NOT NULL DEFAULT '0';");
}

if (!pdo_fieldexists('tiger_jifenbao_goods', 'day_sum')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_goods') . " ADD  `day_sum` int(5) NOT NULL DEFAULT '0';");
}

if (!pdo_fieldexists('tiger_jifenbao_poster', 'scorehb')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_poster') . " ADD  `scorehb` float(11) DEFAULT '0';");
}
if (!pdo_fieldexists('tiger_jifenbao_poster', 'cscorehb')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_poster') . " ADD  `cscorehb` float(11) DEFAULT '0';");
}
if (!pdo_fieldexists('tiger_jifenbao_poster', 'pscorehb')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_poster') . " ADD  `pscorehb` float(11) DEFAULT '0';");
}
if (!pdo_fieldexists('tiger_jifenbao_poster', 'mbfont')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_poster') . " ADD  `mbfont` varchar(50) DEFAULT NULL;");
}
if (!pdo_fieldexists('tiger_jifenbao_share', 'from_user')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_share') . " ADD  `from_user` varchar(100) DEFAULT NULL;");
}
if (!pdo_fieldexists('tiger_jifenbao_share', 'jqfrom_user')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_share') . " ADD  `jqfrom_user` varchar(100) DEFAULT NULL;");
}
if (!pdo_fieldexists('tiger_jifenbao_share', 'helpid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_share') . " ADD  `helpid` int(11) DEFAULT 0;");
}
if (!pdo_fieldexists('tiger_jifenbao_share', 'follow')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_share') . " ADD `follow` tinyint(1) NOT NULL DEFAULT '0';");
}
if (!pdo_fieldexists('tiger_jifenbao_poster', 'tzurl')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_poster') . " ADD  `tzurl` varchar(250) DEFAULT NULL;");
}
if (!pdo_fieldexists('tiger_jifenbao_member', 'helpid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_member') . " ADD  `helpid` int(11) NOT NULL;");
}

if (!pdo_fieldexists('tiger_jifenbao_goods', 'hot')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_goods') . " ADD  `hot` varchar(50) NOT NULL;");
}

if (!pdo_fieldexists('tiger_jifenbao_goods', 'hotcolor')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_goods') . " ADD  `hotcolor` varchar(50) NOT NULL;");
}
if (!pdo_fieldexists('tiger_jifenbao_poster', 'kdtype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_poster') . " ADD  `kdtype` tinyint(1) NOT NULL DEFAULT '0';");
}
if (!pdo_fieldexists('tiger_jifenbao_poster', 'tztype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_jifenbao_poster') . " ADD  `tztype` tinyint(1) NOT NULL DEFAULT '0';");
}