<?php

/**
 *时间轴
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_timeline') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`rid` int(10) NOT NULL,
 `weid` int(11) NOT NULL,
 `title` varchar(200) NOT NULL,
 `copyright` varchar(50),
 `copyrighturl` varchar(500),
`new_title` varchar(200),
`new_icon` varchar(200),
`new_content` varchar(200),
`share_title` varchar(200),
`share_icon` varchar(200),
`share_content` varchar(200),
`createtime` int(10),
`updatetime` int(10),
`list_bg` varchar(500),
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

/**
 *时间轴项目
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_timeline_item') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`tid` int(10) ,
`ititle` varchar(50) ,
`text` varchar(1000),
`i_time` int(10),
`i_img` varchar(250),
`i_bgcolor` varchar(250) ,
`i_url` varchar(500),
`displayorder` int(10),
`createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);




