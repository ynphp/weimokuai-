<?php

if(!pdo_fieldexists('hx_lottery_reply', 'zfcs')) {

	pdo_query("ALTER TABLE ".tablename('hx_lottery_reply')." ADD `zfcs` INT( 10 ) UNSIGNED NOT NULL COMMENT  '转发次数' AFTER  `dayplaynum`; ");

}

if(!pdo_fieldexists('hx_lottery_reply', 'zjcs')) {

	pdo_query("ALTER TABLE ".tablename('hx_lottery_reply')." ADD `zjcs` INT( 10 ) UNSIGNED NOT NULL COMMENT  '转发次数' AFTER  `zfcs`; ");

}

if(!pdo_fieldexists('hx_lottery_reply', 'reg')) {

	pdo_query("ALTER TABLE ".tablename('hx_lottery_reply')." ADD `reg` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER  `status`; ");

}

if(!pdo_fieldexists('hx_lottery_reply', 'prizeinfo')) {

	pdo_query("ALTER TABLE ".tablename('hx_lottery_reply')." ADD `prizeinfo` varchar(255) NOT NULL AFTER  `tips`; ");

}
if(!pdo_fieldexists('hx_lottery_reply', 'daytotalnum')) {

	pdo_query("ALTER TABLE ".tablename('hx_lottery_reply')." ADD   `daytotalnum` int(10) unsigned NOT NULL COMMENT '总参加次数'; ");

}

$sql = "
DROP TABLE IF EXISTS `hx_lottery_share`;
CREATE TABLE IF NOT EXISTS `ims_hx_lottery_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uinacid` int(10) unsigned NOT NULL,
  `reply_id` int(10) unsigned NOT NULL,
  `share_from` varchar(50) NOT NULL,
  `share_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
";
pdo_run($sql);