<?php









if(!pdo_fieldexists('mon_xsvoice_user', 'uname')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice_user')." ADD `uname` varchar(20) ;");
}



if(!pdo_fieldexists('mon_xsvoice_user', 'company')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice_user')." ADD `company` varchar(20) ;");
}


if(!pdo_fieldexists('mon_xsvoice_user', 'company')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice_user')." ADD `company` varchar(20) ;");
}

if(!pdo_fieldexists('mon_xsvoice', 'title_img')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `title_img` varchar(1000) ;");
}


if(!pdo_fieldexists('mon_xsvoice', 'crp_img')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `crp_img` varchar(1000) ;");
}



if(!pdo_fieldexists('mon_xsvoice', 'img1')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `img1` varchar(1000) ;");
}

if(!pdo_fieldexists('mon_xsvoice', 'img2')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `img2` varchar(1000) ;");
}

if(!pdo_fieldexists('mon_xsvoice', 'img3')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `img3` varchar(1000) ;");
}


if(!pdo_fieldexists('mon_xsvoice', 'img4')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `img4` varchar(1000) ;");
}


if(!pdo_fieldexists('mon_xsvoice', 'follow_msg')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `follow_msg` varchar(1000) ;");
}

if(!pdo_fieldexists('mon_xsvoice', 'follow_btn')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `follow_btn` varchar(200) ;");
}



if(!pdo_fieldexists('mon_xsvoice', 'index_bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `index_bgcolor` varchar(100) ;");
}


if(!pdo_fieldexists('mon_xsvoice', 'style_bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `style_bgcolor` varchar(100) ;");
}

if(!pdo_fieldexists('mon_xsvoice', 'voice_target')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `voice_target` varchar(100) ;");
}

if(!pdo_fieldexists('mon_xsvoice', 'rank_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `rank_title` varchar(100) ;");
}


if(!pdo_fieldexists('mon_xsvoice', 'intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice')." ADD `intro` text ;");
}


if(!pdo_fieldexists('mon_xsvoice_user', 'tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_xsvoice_user')." ADD `tel` varchar(100) ;");
}

/*
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



