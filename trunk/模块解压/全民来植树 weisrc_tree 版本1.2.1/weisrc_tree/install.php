<?php

$sql = "
CREATE TABLE IF NOT EXISTS `ims_weisrc_tree_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `prizetype` varchar(50) DEFAULT '' COMMENT '',
  `prizename` varchar(50) DEFAULT '' COMMENT '',
  `prizetotal` int(11) DEFAULT '0',
  `prizepic` varchar(500) DEFAULT '' COMMENT '',
  `dateline` int(10) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_tree_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `headimgurl` varchar(500) NOT NULL DEFAULT '' COMMENT '头像',
  `nickname` varchar(50) DEFAULT '',
  `username` varchar(50) DEFAULT '',
  `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)',
  `address` varchar(200) DEFAULT '',
  `todaynum` int(11) DEFAULT '0',
  `totalnum` int(11) DEFAULT '0',
  `awardnum` int(11) DEFAULT '0',
  `last_time` int(10) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  `success_time` int(10) DEFAULT '0',
  `issuccess` int(10) DEFAULT '0',
  `issend` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_tree_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `help_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `prizetype` varchar(50) DEFAULT '' COMMENT '字类型',
  `total` int(10) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_tree_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `content` varchar(200) DEFAULT '',
  `rule` text,
  `rule2` text,
  `start_picurl` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `ticket_information` varchar(200) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `tips` varchar(500) DEFAULT '',
  `repeat_lottery_reply` varchar(50) DEFAULT '',
  `end_theme` varchar(50) DEFAULT '',
  `end_instruction` varchar(200) DEFAULT '',
  `end_picurl` varchar(200) DEFAULT '',
  `c_type_one` varchar(20) DEFAULT '',
  `c_img1_one` varchar(200) DEFAULT '',
  `c_img2_one` varchar(200) DEFAULT '',
  `c_name_one` varchar(50) DEFAULT '',
  `c_num_one` int(11) DEFAULT '0',
  `c_draw_one` int(11) DEFAULT '0',
  `c_rate_one` double DEFAULT '0',
  `c_type_two` varchar(20) DEFAULT '',
  `c_img1_two` varchar(200) DEFAULT '',
  `c_img2_two` varchar(200) DEFAULT '',
  `c_name_two` varchar(50) DEFAULT '',
  `c_num_two` int(11) DEFAULT '0',
  `c_draw_two` int(11) DEFAULT '0',
  `c_rate_two` double DEFAULT '0',
  `c_type_three` varchar(20) DEFAULT '',
  `c_img1_three` varchar(200) DEFAULT '',
  `c_img2_three` varchar(200) DEFAULT '',
  `c_name_three` varchar(50) DEFAULT '',
  `c_num_three` int(11) DEFAULT '0',
  `c_draw_three` int(11) DEFAULT '0',
  `c_rate_three` double DEFAULT '0',
  `c_type_four` varchar(20) DEFAULT '',
  `c_img1_four` varchar(200) DEFAULT '',
  `c_img2_four` varchar(200) DEFAULT '',
  `c_name_four` varchar(50) DEFAULT '',
  `c_num_four` int(11) DEFAULT '0',
  `c_draw_four` int(11) DEFAULT '0',
  `c_rate_four` double DEFAULT '0',
  `c_type_five` varchar(20) DEFAULT '',
  `c_img1_five` varchar(200) DEFAULT '',
  `c_img2_five` varchar(200) DEFAULT '',
  `c_name_five` varchar(50) DEFAULT '',
  `c_num_five` int(11) DEFAULT '0',
  `c_draw_five` int(11) DEFAULT '0',
  `c_rate_five` double DEFAULT '0',
  `c_type_six` varchar(20) DEFAULT '',
  `c_img1_six` varchar(200) DEFAULT '',
  `c_img2_six` varchar(200) DEFAULT '',
  `c_name_six` varchar(50) DEFAULT '',
  `c_num_six` int(11) DEFAULT '0',
  `c_draw_six` int(10) DEFAULT '0',
  `c_rate_six` double DEFAULT '0',
  `c_type_seven` varchar(20) DEFAULT '',
  `c_img1_seven` varchar(200) DEFAULT '',
  `c_img2_seven` varchar(200) DEFAULT '',
  `c_name_seven` varchar(50) DEFAULT '',
  `c_num_seven` int(11) DEFAULT '0',
  `c_draw_seven` int(10) DEFAULT '0',
  `c_rate_seven` double DEFAULT '0',
  `c_type_eight` varchar(20) DEFAULT '',
  `c_img1_eight` varchar(200) DEFAULT '',
  `c_img2_eight` varchar(200) DEFAULT '',
  `c_name_eight` varchar(50) DEFAULT '',
  `c_num_eight` int(11) DEFAULT '0',
  `c_draw_eight` int(10) DEFAULT '0',
  `c_rate_eight` double DEFAULT '0',
  `c_unit_one` varchar(20) DEFAULT '',
  `c_unit_two` varchar(20) DEFAULT '',
  `c_unit_three` varchar(20) DEFAULT '',
  `c_unit_four` varchar(20) DEFAULT '',
  `c_unit_five` varchar(20) DEFAULT '',
  `logo` varchar(500) DEFAULT '',
  `bg` varchar(500) DEFAULT '',
  `light` varchar(500) DEFAULT '',
  `light2` varchar(500) DEFAULT '',
  `paper` varchar(500) DEFAULT '',
  `qrcode` varchar(500) DEFAULT '',
  `music_url` varchar(500) DEFAULT '',
  `total_num` int(11) DEFAULT '0' COMMENT '总获奖人数(自动加)',
  `probability` double DEFAULT '0',
  `total_award` int(11) DEFAULT '0' COMMENT '总奖品数量',
  `award_times` int(11) DEFAULT '0',
  `number_times` int(11) DEFAULT '0',
  `most_num_times` int(11) DEFAULT '0',
  `day_times` int(11) DEFAULT '5',
  `total_times` int(11) DEFAULT '10',
  `copyright` varchar(50) DEFAULT '',
  `copyrighturl` varchar(200) DEFAULT '',
  `address` varchar(200) DEFAULT '',
  `show_num` tinyint(2) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `fansnum` int(11) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  `isage` tinyint(1) DEFAULT '0',
  `isweixin` tinyint(1) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_image` varchar(500) DEFAULT '',
  `share_url` varchar(500) DEFAULT '',
  `follow_url` varchar(500) DEFAULT '',
  `isneedfollow` tinyint(1) DEFAULT '1',
  `mode` tinyint(1) DEFAULT '1',
  `isusername` tinyint(1) DEFAULT '1',
  `istel` tinyint(1) DEFAULT '1',
  `isaddress` tinyint(1) DEFAULT '1',
  `share_txt` varchar(500) DEFAULT '',
  `award_url` varchar(500) DEFAULT '',
  `award_tip` varchar(200) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";
pdo_query($sql);
