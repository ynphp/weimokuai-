<?php
$sql = "
DROP TABLE ims_meepo_tu_data;
DROP TABLE ims_meepo_tu_comment;
DROP TABLE ims_meepo_tu_set;
";
pdo_query($sql);

$sql = "

CREATE TABLE IF NOT EXISTS `ims_meepo_tu_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `class` tinyint(3) NOT NULL,
  `time` int(11) NOT NULL,
  `time_r` int(11) NOT NULL,
  `con` text NOT NULL,
  `com_count` int(11) NOT NULL,
  `good` int(11) NOT NULL,
  `color` varchar(100) NOT NULL,
  `class_name` varchar(250) NOT NULL,
  `top` int(11) DEFAULT '0',
  `click` int(11) DEFAULT '0',
  `nick` varchar(80) NOT NULL,
  `bad` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `ims_meepo_tu_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(120) NOT NULL,
  `wx_name` varchar(80) NOT NULL,
  `wx_num` varchar(100) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_content` text NOT NULL,
  `share_img` varchar(420) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `ims_meepo_tu_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  `bad` int(11) NOT NULL,
  `good` int(11) NOT NULL,
  `time_r` int(11) NOT NULL,
  `con` text NOT NULL,
  `color` varchar(100) NOT NULL,
  `nick` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
";
pdo_query($sql);