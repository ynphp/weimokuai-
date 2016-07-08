<?php


/**
 *声音活动
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_xsvoice') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`rid` int(10) NOT NULL,
 `weid` int(11) NOT NULL,
 `title` varchar(200) NOT NULL,
 `starttime` int(10) DEFAULT 0,
 `endtime` int(10) DEFAULT 0,
 `follow_url` varchar(200),
  `copyright` varchar(100) NOT NULL,
  `new_title` varchar(200),
`new_icon` varchar(200),
`new_content` varchar(200),
 `share_title` varchar(200),
`share_icon` varchar(200),
`share_content` varchar(200),
`createtime` int(10) DEFAULT 0,
`title_img` varchar(1000),
`crp_img` varchar(1000),
`img1` varchar(1000),
`img2` varchar(1000),
`img3` varchar(1000),
`img4` varchar(1000),
`follow_msg` varchar(1000),
`follow_btn` varchar(200),
`index_bgcolor` varchar(100),
`style_bgcolor` varchar(100),
`voice_target` varchar(100),
`rank_title` varchar(100),
`intro` text,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


/**
 * 声音用户
 */

$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_xsvoice_user') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`vid` int(10) NOT NULL,
`uname` varchar(20) ,
`company` varchar(20),
 `openid` varchar(200) NOT NULL,
 `nickname` varchar(100) NOT NULL,
 `headimgurl` varchar(200) NOT NULL,
 `media_id` varchar(200) ,
 `media_path` varchar(200) ,
 `createtime` int(10) DEFAULT 0,
 `zan` int(10) default 0,
 `tel` varchar(100),
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);



/**
 * 点赞好友
 */

$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_xsvoice_firend') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`vid` int(10) NOT NULL,
`uid` varchar(20) ,
 `fopenid` varchar(200) NOT NULL,
 `nickname` varchar(100) NOT NULL,
 `headimgurl` varchar(200) NOT NULL,
 `createtime` int(10) DEFAULT 0,
 `zan` int(10) default 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);








