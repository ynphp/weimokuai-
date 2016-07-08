<?php
if(!pdo_fieldexists('j_run_reply', 'gametime')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `gametime` int(2) NOT NULL DEFAULT '10'  COMMENT '游戏时间';");
}
if(!pdo_fieldexists('j_run_reply', 'slogan')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `slogan` varchar(500) NOT NULL COMMENT '口号';");
}
if(!pdo_fieldexists('j_run_reply', 'ad')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `ad` varchar(250) NOT NULL COMMENT '广告语';");
}
if(!pdo_fieldexists('j_run_reply', 'img_loadImg')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_loadImg` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'img_personImg')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_personImg` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'img_personsImg')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_personsImg` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'img_treeImage')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_treeImage` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'img_green')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_green` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'img_road')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_road` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'img_sun')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_sun` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'img_cloud1')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_cloud1` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'img_cloud2')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_cloud2` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'img_cloud3')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_cloud3` varchar(250) NOT NULL COMMENT '游戏图片';");
}
/*1.5*/
if(!pdo_fieldexists('j_run_reply', 'img_fullbg')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_fullbg` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'img_personImg_girl')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_personImg_girl` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'img_personsImg_girl')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `img_personsImg_girl` varchar(250) NOT NULL COMMENT '游戏图片';");
}
if(!pdo_fieldexists('j_run_reply', 'open_bg')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `open_bg` tinyint(1) NOT NULL DEFAULT '0';");
}
/*1.6*/
if(!pdo_fieldexists('j_run_reply', 'music')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `music` varchar(250) NOT NULL COMMENT '背景音乐';");
}
if(!pdo_fieldexists('j_run_reply', 'modol')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `modol` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('j_run_reply', 'speed')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `speed` varchar(10) NOT NULL COMMENT '加速度';");
}
if(!pdo_fieldexists('j_run_reply', 'speedStep')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `speedStep` varchar(10) NOT NULL COMMENT '步速';");
}
/*2.0*/
if(!pdo_fieldexists('j_run_reply', 'issex')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `issex` tinyint(1) NOT NULL DEFAULT '0';");
}
/*2.3*/
if(!pdo_fieldexists('j_run_reply', 'share_title')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `share_title` varchar(200) NOT NULL COMMENT '分享内容';");
}
if(!pdo_fieldexists('j_run_reply', 'helpnum')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `helpnum` int(3) NOT NULL DEFAULT '0' COMMENT '助力限制人数';");
}

if(!pdo_fieldexists('j_run_reply', 'appid')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `appid` varchar(200) NOT NULL COMMENT 'appid';");
}
if(!pdo_fieldexists('j_run_reply', 'secret')) {
	pdo_query("ALTER TABLE ".tablename('j_run_reply')." ADD `secret` varchar(200) NOT NULL COMMENT 'secret';");
}
/*2.5*/
if(!pdo_fieldexists('j_run_member', 'enable')) {
	pdo_query("ALTER TABLE ".tablename('j_run_member')." ADD `enable` tinyint(1) NOT NULL DEFAULT '0';");
}
/*2.9*/
$sql="
CREATE TABLE IF NOT EXISTS `ims_j_run_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL DEFAULT '0',
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(80) NOT NULL ,
  `nickname` varchar(80) NOT NULL ,
  `headimgurl` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

";
pdo_run($sql);


































