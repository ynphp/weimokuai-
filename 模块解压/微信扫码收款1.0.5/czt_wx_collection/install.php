<?php
/**
 *
 * @author czt
 * @url http://bbs.we7.cc/
 */
$table1=tablename('czt_wx_collection_self_record');
$table2=tablename('czt_wx_collection_scan_record');
$table3=tablename('czt_wx_collection_scan_class');

$sql =<<<EOF
CREATE TABLE IF NOT EXISTS {$table1} (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `uniacid` int(11) NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `tid` varchar(64) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
EOF;
pdo_run($sql);

$sql =<<<EOF
CREATE TABLE IF NOT EXISTS {$table2} (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `class_id` tinyint(4) NOT NULL DEFAULT '0',
  `uniacid` int(11) NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `founder_openid` varchar(40) NOT NULL DEFAULT '',
  `tid` varchar(64) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `scan_type`  tinyint(1) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `code_url` varchar(512) NOT NULL DEFAULT '',
  `type` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
EOF;
pdo_run($sql);

$sql =<<<EOF
CREATE TABLE IF NOT EXISTS {$table3} (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(1) default 1,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
EOF;
pdo_run($sql);