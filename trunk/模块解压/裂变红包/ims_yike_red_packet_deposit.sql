/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : weizan

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-05-19 17:56:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_yike_red_packet_deposit`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yike_red_packet_deposit`;
CREATE TABLE `ims_yike_red_packet_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uiniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` int(10) NOT NULL,
  `remark` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of ims_yike_red_packet_deposit
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yike_red_packet_level`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yike_red_packet_level`;
CREATE TABLE `ims_yike_red_packet_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL DEFAULT '',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `level1_count` int(10) NOT NULL DEFAULT '0',
  `level1_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `level2_count` int(10) NOT NULL DEFAULT '0',
  `level2_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `level3_count` int(10) NOT NULL DEFAULT '0',
  `level3_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `other_count` int(10) NOT NULL DEFAULT '0',
  `other_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of ims_yike_red_packet_level
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yike_red_packet_qr`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yike_red_packet_qr`;
CREATE TABLE `ims_yike_red_packet_qr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL DEFAULT '',
  `type` tinyint(3) DEFAULT '0',
  `sceneid` int(11) DEFAULT '0',
  `mediaid` varchar(255) DEFAULT '',
  `ticket` varchar(250) NOT NULL,
  `url` varchar(80) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `qrimg` varchar(1000) DEFAULT '',
  `scenestr` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_acid` (`acid`),
  KEY `idx_sceneid` (`sceneid`),
  KEY `idx_type` (`type`),
  FULLTEXT KEY `idx_openid` (`openid`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_yike_red_packet_qr
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yike_red_packet_rebates`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yike_red_packet_rebates`;
CREATE TABLE `ims_yike_red_packet_rebates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` int(10) NOT NULL,
  `remark` text,
  `level` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of ims_yike_red_packet_rebates
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yike_red_packet_recharge`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yike_red_packet_recharge`;
CREATE TABLE `ims_yike_red_packet_recharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` int(10) NOT NULL,
  `remark` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of ims_yike_red_packet_recharge
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yike_red_packet_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yike_red_packet_setting`;
CREATE TABLE `ims_yike_red_packet_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `deposit_limit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_level_limit` tinyint(1) NOT NULL DEFAULT '0',
  `sec` text NOT NULL,
  `sets` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of ims_yike_red_packet_setting
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yike_red_packet_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yike_red_packet_user`;
CREATE TABLE `ims_yike_red_packet_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `level1` int(11) DEFAULT NULL,
  `level2` int(11) DEFAULT NULL,
  `level3` int(11) DEFAULT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '',
  `realname` varchar(32) DEFAULT NULL,
  `mobile` varchar(16) DEFAULT NULL,
  `wx` varchar(32) DEFAULT NULL,
  `created_time` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `is_inviter` tinyint(1) NOT NULL DEFAULT '0',
  `click_count` int(11) NOT NULL DEFAULT '0',
  `inviter_level` int(11) NOT NULL DEFAULT '0',
  `point` decimal(10,2) NOT NULL DEFAULT '0.00',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `withdraw` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of ims_yike_red_packet_user
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_yike_red_packet_withdraw`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yike_red_packet_withdraw`;
CREATE TABLE `ims_yike_red_packet_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_time` int(10) NOT NULL,
  `remark` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of ims_yike_red_packet_withdraw
-- ----------------------------
