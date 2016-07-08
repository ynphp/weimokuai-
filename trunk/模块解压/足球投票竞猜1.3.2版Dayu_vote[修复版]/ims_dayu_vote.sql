/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : ceshi

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-06-20 20:23:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_dayu_vote`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_vote`;
CREATE TABLE `ims_dayu_vote` (
  `reid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `giftdec` varchar(1000) NOT NULL,
  `information` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `bg` varchar(200) NOT NULL DEFAULT '',
  `inhome` tinyint(4) NOT NULL DEFAULT '0',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `pretotal` int(10) unsigned NOT NULL DEFAULT '1',
  `noticeemail` varchar(50) NOT NULL DEFAULT '',
  `k_templateid` varchar(50) NOT NULL DEFAULT '',
  `kfid` varchar(50) NOT NULL DEFAULT '',
  `m_templateid` varchar(50) NOT NULL DEFAULT '',
  `kfirst` varchar(100) NOT NULL COMMENT '客服模板页头',
  `kfoot` varchar(100) NOT NULL COMMENT '客服模板页尾',
  `mfirst` varchar(100) NOT NULL COMMENT '客户模板页头',
  `mfoot` varchar(100) NOT NULL COMMENT '客户模板页尾',
  `mname` varchar(10) NOT NULL DEFAULT '',
  `skins` varchar(20) NOT NULL DEFAULT 'submit',
  `custom_status` int(1) NOT NULL DEFAULT '0' COMMENT '客服消息状态',
  `follow` tinyint(1) NOT NULL DEFAULT '0',
  `share_title` varchar(500) NOT NULL,
  `share_desc` varchar(500) NOT NULL,
  `view` int(11) NOT NULL DEFAULT '0',
  `num` int(11) NOT NULL DEFAULT '0',
  `sharenum` int(11) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  `select` tinyint(1) NOT NULL DEFAULT '1',
  `votenum` int(2) NOT NULL DEFAULT '1',
  `reward` tinyint(1) NOT NULL DEFAULT '0',
  `iscredit` tinyint(1) NOT NULL DEFAULT '0',
  `isreplace` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reid`),
  KEY `weid` (`weid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_dayu_vote
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_dayu_vote_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_vote_data`;
CREATE TABLE `ims_dayu_vote_data` (
  `redid` bigint(20) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `rerid` int(11) NOT NULL,
  `refid` int(11) NOT NULL,
  `data` varchar(800) NOT NULL,
  PRIMARY KEY (`redid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_dayu_vote_data
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_dayu_vote_fields`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_vote_fields`;
CREATE TABLE `ims_dayu_vote_fields` (
  `refid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `essential` tinyint(4) NOT NULL DEFAULT '0',
  `bind` varchar(30) NOT NULL DEFAULT '',
  `value` varchar(300) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`refid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_dayu_vote_fields
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_dayu_vote_gift`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_vote_gift`;
CREATE TABLE `ims_dayu_vote_gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `reid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `displayorder` int(10) NOT NULL DEFAULT '0',
  `num` int(11) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_dayu_vote_gift
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_dayu_vote_info`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_vote_info`;
CREATE TABLE `ims_dayu_vote_info` (
  `rerid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `member` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '保留字段',
  `kfinfo` varchar(100) NOT NULL COMMENT '客服信息',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `checkbox` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`rerid`),
  KEY `reid` (`reid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_dayu_vote_info
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_dayu_vote_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_vote_item`;
CREATE TABLE `ims_dayu_vote_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `reid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `displayorder` int(10) NOT NULL DEFAULT '0',
  `num` int(11) NOT NULL DEFAULT '0',
  `falsenum` int(11) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_dayu_vote_item
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_dayu_vote_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_vote_reply`;
CREATE TABLE `ims_dayu_vote_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `reid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_dayu_vote_reply
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_dayu_vote_staff`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_vote_staff`;
CREATE TABLE `ims_dayu_vote_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `reid` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_dayu_vote_staff
-- ----------------------------
