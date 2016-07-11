<?php
$sql = "
CREATE TABLE IF NOT EXISTS  `ims_hc_moreshop_moneyapply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `mid` int(10) unsigned NOT NULL COMMENT '粉丝ID',
  `money` int(5) NOT NULL DEFAULT '0' COMMENT '积分',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0为正在申请，1为正在审核，2为申请成功',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `checktime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hc_moreshop_coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `description` text,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为代金券，1为折扣券',
  `discount` decimal(10,2) unsigned DEFAULT '0.00',
  `credit` int(10) unsigned DEFAULT '0',
  `number` int(10) unsigned DEFAULT '0',
  `isopen` tinyint(1) unsigned DEFAULT '0',
  `displayorder` int(10) unsigned DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hc_moreshop_mycoupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT '0',
  `mid` int(10) unsigned DEFAULT '0',
  `couponid` int(10) unsigned DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为代金券，1为折扣券',
  `discount` decimal(10,2) unsigned DEFAULT '0.00',
  `isuse` tinyint(1) unsigned DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `ims_hc_moreshop_fangfangkan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT '0',
  `level` tinyint(2) unsigned DEFAULT '0',
  `gametime` int(10) unsigned DEFAULT '0',
  `showtime` int(10) unsigned DEFAULT '0',
  `easycredit` int(10) unsigned DEFAULT '0',
  `normalcredit` int(10) unsigned DEFAULT '0',
  `hardcredit` int(10) unsigned DEFAULT '0',
  `gametimes` int(10) unsigned DEFAULT '0',
  `isopen` tinyint(1) unsigned DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_query($sql);

if(!pdo_fieldexists('hc_moreshop_growmoney', 'yestodaygrowmoney')) {
	pdo_query("ALTER TABLE ".tablename('hc_moreshop_growmoney')." ADD `yestodaygrowmoney` decimal(10,2) DEFAULT '0.00';");
}

if(pdo_fieldexists('hc_moreshop_growmoney', 'yestodaygrowmoney')) {
	pdo_query("ALTER TABLE ".tablename('hc_moreshop_growmoney')." CHANGE   `yestodaygrowmoney`  `yestodaygrowmoney` decimal(10,2) DEFAULT '0.00';");
}