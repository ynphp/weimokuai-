<?php
if(!pdo_fieldexists('tg_order', 'transid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `transid` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'remark')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `remark` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'success')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `success` INT NOT NULL AFTER `remark`;");
}
if(!pdo_fieldexists('tg_order', 'addname')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `addname` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'mobile')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `mobile` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
}
if(!pdo_fieldexists('tg_order', 'address')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `address` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
}
if(!pdo_fieldexists('tg_goods', 'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `displayorder` INT NOT NULL AFTER `ishot`;");
}
if(!pdo_fieldexists('tg_goods', 'freight')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `freight` DECIMAL( 10, 2 ) NOT NULL AFTER `oprice`;");
}
//if(!pdo_indexexists('tg_goods', 'gname')) {
//	pdo_query("ALTER TABLE ".tablename('tg_goods')." DROP INDEX `gname`");
//}
pdo_query("ALTER TABLE `ims_tg_goods` CHANGE `gdesc` `gdesc` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品简介'");
pdo_query("ALTER TABLE `ims_tg_order` CHANGE `transid` `transid` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ");
pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_order_print` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  `pid` int(3) NOT NULL,
  `oid` int(10) NOT NULL,
  `foid` varchar(50) NOT NULL,
  `status` int(3) NOT NULL,
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_print` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  `name` varchar(45) NOT NULL,
  `print_no` varchar(50) NOT NULL,
  `key` varchar(50) NOT NULL,
  `member_code` varchar(50) NOT NULL,
  `print_nums` int(3) NOT NULL,
  `qrcode_link` varchar(100) NOT NULL,
  `status` int(3) NOT NULL,
  `mode` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;");

pdo_query("CREATE TABLE IF NOT EXISTS `ims_tg_refund_record` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `transid` int(11) NOT NULL COMMENT '订单编号',
 `createtime` varchar(45) NOT NULL,
 `status` int(11) NOT NULL COMMENT '0未成功1成功',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;");
