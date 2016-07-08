/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : weizan

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-05-06 15:36:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_junsion_netcollect_player`
-- ----------------------------
DROP TABLE IF EXISTS `ims_junsion_netcollect_player`;
CREATE TABLE `ims_junsion_netcollect_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `oauth_openid` varchar(50) DEFAULT '',
  `avatar` varchar(200) DEFAULT '',
  `nickname` varchar(50) DEFAULT '',
  `realname` varchar(50) DEFAULT '',
  `mobile` varchar(50) DEFAULT '',
  `qq` varchar(50) DEFAULT '',
  `email` varchar(50) DEFAULT '',
  `address` varchar(50) DEFAULT '',
  `status` int(1) DEFAULT '0',
  `wordcount` int(11) DEFAULT '0',
  `sharecount` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0' COMMENT '剩余游戏次数',
  `createtime` int(11) DEFAULT '0',
  `award` int(11) DEFAULT '0',
  `astatus` tinyint(1) DEFAULT '0',
  `choice` tinyint(1) DEFAULT '0',
  `lasttime` int(11) DEFAULT '0' COMMENT '最后增长时间',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`,`rid`),
  KEY `award` (`award`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_junsion_netcollect_player
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_junsion_netcollect_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_junsion_netcollect_prize`;
CREATE TABLE `ims_junsion_netcollect_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `prizepro` varchar(200) DEFAULT '',
  `prizetotal` int(11) DEFAULT '0',
  `prizetype` int(1) DEFAULT '0',
  `prizename` varchar(50) DEFAULT '' COMMENT '奖品 q名称，当奖品为其他模块东西时，这里为ID',
  `prizepic` varchar(200) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_junsion_netcollect_prize
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_junsion_netcollect_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_junsion_netcollect_record`;
CREATE TABLE `ims_junsion_netcollect_record` (
  `rid` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `word` varchar(11) DEFAULT '',
  `createtime` varchar(20) DEFAULT NULL,
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_junsion_netcollect_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_junsion_netcollect_rule`
-- ----------------------------
DROP TABLE IF EXISTS `ims_junsion_netcollect_rule`;
CREATE TABLE `ims_junsion_netcollect_rule` (
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(250) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `hword` varchar(20) DEFAULT '',
  `title2` varchar(50) DEFAULT '',
  `thumb2` varchar(250) DEFAULT '',
  `description2` varchar(255) DEFAULT '',
  `stitle` varchar(50) DEFAULT '',
  `sthumb` varchar(250) DEFAULT '',
  `sdesc` varchar(255) DEFAULT '',
  `atitle` varchar(50) DEFAULT '',
  `athumb` varchar(250) DEFAULT '',
  `adesc` varchar(255) DEFAULT '',
  `words` text,
  `sliders` text,
  `rank` int(11) DEFAULT '0',
  `awardNum` int(11) DEFAULT '0',
  `isinfo` int(1) DEFAULT '0',
  `isinfo2` int(1) DEFAULT '0',
  `awardtips` varchar(200) DEFAULT '',
  `isrealname` int(1) DEFAULT '0',
  `ismobile` int(1) DEFAULT '0',
  `isqq` int(1) DEFAULT '0',
  `isemail` int(1) DEFAULT '0',
  `isaddress` int(1) DEFAULT '0',
  `isfans` int(1) DEFAULT '0',
  `lastshow` int(1) DEFAULT '0',
  `describelimit` tinyint(1) DEFAULT '0',
  `describelimit2` tinyint(1) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `starttime` int(10) DEFAULT '0',
  `content` text,
  `firstnum` int(11) DEFAULT '0',
  `slideH` int(11) DEFAULT '0',
  `sharenum1` int(11) DEFAULT '0',
  `sharenum2` int(11) DEFAULT '0',
  `daynum` int(11) DEFAULT '0',
  `password` varchar(20) DEFAULT '',
  `citys` text,
  `outtips` varchar(200) DEFAULT '',
  `outurl` varchar(250) DEFAULT '',
  `advImg` varchar(200) DEFAULT '',
  `selword` varchar(200) DEFAULT '',
  `bgsong` varchar(255) DEFAULT '',
  `colsong` varchar(255) DEFAULT '',
  `copyright` varchar(255) DEFAULT '',
  `rmode` tinyint(1) DEFAULT '0',
  `advTime` int(10) DEFAULT '0',
  `prizetime` int(10) DEFAULT '0',
  `checked` tinyint(1) DEFAULT '0',
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_junsion_netcollect_rule
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_junsion_netcollect_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_junsion_netcollect_share`;
CREATE TABLE `ims_junsion_netcollect_share` (
  `openid` varchar(50) DEFAULT '',
  `avatar` varchar(200) DEFAULT '',
  `nickname` varchar(50) DEFAULT '',
  `pid` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  KEY `openid` (`openid`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_junsion_netcollect_share
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_junsion_netcollect_slog`
-- ----------------------------
DROP TABLE IF EXISTS `ims_junsion_netcollect_slog`;
CREATE TABLE `ims_junsion_netcollect_slog` (
  `rid` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_junsion_netcollect_slog
-- ----------------------------
