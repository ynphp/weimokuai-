<?php
if (!pdo_fieldexists('quick_verify_order', 'shopid')) {
	pdo_query("ALTER TABLE " . tablename('quick_verify_order') . " ADD `shopid` int(10) NOT NULL COMMENT '店铺ID';");
}
if (!pdo_fieldexists('quick_verify_clerk', 'shopid')) {
	pdo_query("ALTER TABLE " . tablename('quick_verify_clerk') . " ADD `shopid` int(10) NOT NULL COMMENT '店铺ID';");
}
if (!pdo_fieldexists('quick_verify_order', 'price')) {
	pdo_query("ALTER TABLE " . tablename('quick_verify_order') . " ADD `price` decimal(10,2) default 0 COMMENT '售价';");
}
if (!pdo_fieldexists('quick_verify_order', 'settlestatus')) {
	pdo_query("ALTER TABLE " . tablename('quick_verify_order') . " ADD `settlestatus` tinyint(4) NOT NULL DEFAULT 1 COMMENT '订单结算状态，用于判断是否和商家结算, 1未结算，2已结算';");
}
if (!pdo_fieldexists('quick_verify_order', 'settletime')) {
	pdo_query("ALTER TABLE " . tablename('quick_verify_order') . " ADD `settletime` int(10) NOT NULL DEFAULT 0 COMMENT '结算时间'");
}
$sql = "
  CREATE TABLE IF NOT EXISTS `ims_quick_verify_shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `shopname` varchar(300) NOT NULL,
  `remark` varchar(300) NOT NULL,
  `printers` varchar(300) NOT NULL COMMENT '店铺对应的打印机，可包含多个打印机ID',
  `enabled` tinyint(2) NOT NULL DEFAULT 1,
  `createtime` int(10) unsigned NOT NULL COMMENT '店铺创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);