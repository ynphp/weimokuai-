/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : weizan

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-05-06 15:23:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_yuexiage_community_accumulate`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yuexiage_community_accumulate`;
CREATE TABLE `ims_yuexiage_community_accumulate` (
  `itemid` varchar(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_yuexiage_community_accumulate
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yuexiage_community_blacklist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yuexiage_community_blacklist`;
CREATE TABLE `ims_yuexiage_community_blacklist` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `status` int(5) DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL,
  `time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_yuexiage_community_blacklist
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yuexiage_community_comments`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yuexiage_community_comments`;
CREATE TABLE `ims_yuexiage_community_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '内容id',
  `ownerid` int(11) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `commentdate` datetime NOT NULL,
  `commenttext` text NOT NULL,
  `level` int(5) DEFAULT NULL,
  `parent` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `modifyid` int(10) DEFAULT NULL COMMENT '修改者ID',
  `modify` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '改者修名字',
  `lastmodified` int(10) DEFAULT NULL COMMENT '最后修改时间',
  `publishedid` int(10) DEFAULT NULL,
  `published` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastpublished` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `itemID` (`cid`),
  KEY `userID` (`ownerid`),
  KEY `published` (`status`),
  KEY `latestComments` (`status`,`commentdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_yuexiage_community_comments
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yuexiage_community_contents`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yuexiage_community_contents`;
CREATE TABLE `ims_yuexiage_community_contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `iscommend` tinyint(1) NOT NULL,
  `ishot` tinyint(1) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `incontent` tinyint(1) NOT NULL,
  `source` varchar(10) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `linkurl` varchar(500) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  `type` varchar(10) NOT NULL,
  `credit` varchar(255) NOT NULL,
  `status` int(5) DEFAULT '0' COMMENT '状态',
  `modifyid` int(10) DEFAULT NULL COMMENT '修改者ID',
  `modify` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '改者修名字',
  `lastmodified` int(10) DEFAULT NULL COMMENT '最后修改时间',
  `publishedid` int(10) DEFAULT NULL,
  `published` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastpublished` int(10) DEFAULT NULL,
  `createby` int(10) DEFAULT NULL,
  `createname` varchar(50) DEFAULT NULL,
  `hits` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_iscommend` (`iscommend`),
  KEY `idx_ishot` (`ishot`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_yuexiage_community_contents
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yuexiage_community_hit`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yuexiage_community_hit`;
CREATE TABLE `ims_yuexiage_community_hit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `cid` int(10) DEFAULT NULL,
  `hits` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_yuexiage_community_hit
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yuexiage_community_slide`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yuexiage_community_slide`;
CREATE TABLE `ims_yuexiage_community_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_yuexiage_community_slide
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yuexiage_community_tabs`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yuexiage_community_tabs`;
CREATE TABLE `ims_yuexiage_community_tabs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `weid` int(11) NOT NULL COMMENT '公众号id',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '字段名',
  `status` tinyint(1) NOT NULL COMMENT '字段展示名称',
  `ownerid` int(10) DEFAULT NULL,
  `owner` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '创建者',
  `datetime` datetime NOT NULL,
  `deleted` tinyint(1) DEFAULT '0' COMMENT '逻辑删除',
  `top` tinyint(1) DEFAULT '0' COMMENT '顶置',
  `sys` tinyint(1) DEFAULT '0' COMMENT '是否属于系统标签',
  `thumb` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `modifyid` int(10) DEFAULT NULL COMMENT '修改者ID',
  `modify` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '改者修名字',
  `lastmodified` datetime DEFAULT NULL COMMENT '最后修改时间',
  `delid` int(10) DEFAULT NULL,
  `del` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastdel` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='多客服字段表';

-- ----------------------------
-- Records of ims_yuexiage_community_tabs
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yuexiage_community_tabs_contents`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yuexiage_community_tabs_contents`;
CREATE TABLE `ims_yuexiage_community_tabs_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(10) DEFAULT NULL COMMENT '内容ID',
  `name` varchar(50) DEFAULT NULL COMMENT '标签名字',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_yuexiage_community_tabs_contents
-- ----------------------------
