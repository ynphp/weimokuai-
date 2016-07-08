/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : weizan

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-05-11 11:24:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_lxy_buildpro_album`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_album`;
CREATE TABLE `ims_lxy_buildpro_album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '相册名称',
  `subtitle` varchar(255) DEFAULT NULL,
  `hid` int(11) DEFAULT NULL COMMENT '楼盘id ims_lxy_buildpro_set table id',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序',
  `jianjie` text,
  `pic` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COMMENT='楼盘相册';

-- ----------------------------
-- Records of ims_lxy_buildpro_album
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lxy_buildpro_bill`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_bill`;
CREATE TABLE `ims_lxy_buildpro_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `pic1` varchar(255) DEFAULT NULL,
  `pic2` varchar(255) DEFAULT NULL,
  `pic3` varchar(255) DEFAULT NULL,
  `pic4` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='楼盘海报';

-- ----------------------------
-- Records of ims_lxy_buildpro_bill
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lxy_buildpro_expert_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_expert_comment`;
CREATE TABLE `ims_lxy_buildpro_expert_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `expert_name` varchar(20) DEFAULT NULL,
  `zhiwei` varchar(255) DEFAULT NULL COMMENT '专家职位',
  `sort` tinyint(4) unsigned DEFAULT NULL COMMENT '排序',
  `jianjie` text,
  `content` text COMMENT '点评内容',
  `thumb` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='楼盘-专家点评';

-- ----------------------------
-- Records of ims_lxy_buildpro_expert_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lxy_buildpro_fell`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_fell`;
CREATE TABLE `ims_lxy_buildpro_fell` (
  `yid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `hid` int(11) DEFAULT NULL COMMENT '楼盘id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序',
  `yinxiang_number` int(11) unsigned DEFAULT '0' COMMENT '印象数',
  `isshow` tinyint(1) DEFAULT '1',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`yid`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COMMENT='房友印象';

-- ----------------------------
-- Records of ims_lxy_buildpro_fell
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lxy_buildpro_fell_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_fell_record`;
CREATE TABLE `ims_lxy_buildpro_fell_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `fromuser` varchar(255) DEFAULT NULL COMMENT '楼盘id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=utf8 COMMENT='房友印象';

-- ----------------------------
-- Records of ims_lxy_buildpro_fell_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lxy_buildpro_full_view`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_full_view`;
CREATE TABLE `ims_lxy_buildpro_full_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `hsid` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `quanjinglink` varchar(500) DEFAULT NULL COMMENT '全景外链',
  `pic_qian` varchar(1023) DEFAULT NULL,
  `pic_hou` varchar(1023) DEFAULT NULL,
  `pic_zuo` varchar(1023) DEFAULT NULL,
  `pic_you` varchar(1023) DEFAULT NULL,
  `pic_shang` varchar(1023) DEFAULT NULL,
  `pic_xia` varchar(1023) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COMMENT='楼盘户型全景';

-- ----------------------------
-- Records of ims_lxy_buildpro_full_view
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lxy_buildpro_head`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_head`;
CREATE TABLE `ims_lxy_buildpro_head` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `xcname` varchar(255) DEFAULT NULL,
  `headpic` varchar(255) DEFAULT NULL,
  `apartpic` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `dist` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `jw_addr` varchar(255) DEFAULT NULL,
  `lng` varchar(12) DEFAULT '116.403694',
  `lat` varchar(12) DEFAULT '39.916042',
  `jianjie` text,
  `xiangmu` text,
  `jiaotong` text,
  `addr` varchar(255) DEFAULT NULL,
  `yyurl` varchar(500) DEFAULT NULL,
  `xwurl` varchar(500) DEFAULT NULL,
  `hyurl` varchar(500) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `lxname` varchar(50) DEFAULT NULL,
  `hyname` varchar(50) DEFAULT NULL,
  `yyname` varchar(50) DEFAULT NULL,
  `xwname` varchar(50) DEFAULT NULL,
  `yxname` varchar(50) DEFAULT NULL,
  `hxname` varchar(50) DEFAULT NULL,
  `jjname` varchar(50) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`hid`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COMMENT='楼盘简介';

-- ----------------------------
-- Records of ims_lxy_buildpro_head
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lxy_buildpro_house`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_house`;
CREATE TABLE `ims_lxy_buildpro_house` (
  `hsid` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '户型名称',
  `sid` int(11) DEFAULT NULL COMMENT '子楼盘 ims_lxy_buildpro_set id',
  `louceng` smallint(1) DEFAULT NULL COMMENT '楼层',
  `mianji` varchar(255) DEFAULT NULL COMMENT '建筑面积',
  `fang` tinyint(4) DEFAULT NULL,
  `ting` tinyint(4) DEFAULT NULL,
  `sort` tinyint(4) unsigned DEFAULT NULL COMMENT '排序',
  `jianjie` text,
  `pic` text,
  `picjson` text,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`hsid`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COMMENT='楼盘户型';

-- ----------------------------
-- Records of ims_lxy_buildpro_house
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lxy_buildpro_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_reply`;
CREATE TABLE `ims_lxy_buildpro_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `hid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lxy_buildpro_reply
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lxy_buildpro_sub`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_sub`;
CREATE TABLE `ims_lxy_buildpro_sub` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `hid` int(11) DEFAULT NULL COMMENT '楼盘id',
  `title` varchar(255) DEFAULT NULL COMMENT '子楼盘名称',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序',
  `jianjie` text,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 COMMENT='子楼盘';

-- ----------------------------
-- Records of ims_lxy_buildpro_sub
-- ----------------------------
