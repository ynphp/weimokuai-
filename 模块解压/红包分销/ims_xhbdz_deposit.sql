/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : weizan

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-05-19 17:41:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_xhbdz_deposit`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhbdz_deposit`;
CREATE TABLE `ims_xhbdz_deposit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `appid` varchar(30) NOT NULL,
  `biaoshi` tinyint(1) NOT NULL COMMENT '红包标识',
  `depositsn` varchar(18) NOT NULL COMMENT '红包单号',
  `from_openid` varchar(100) NOT NULL COMMENT '发送者openid',
  `from_nickname` varchar(16) DEFAULT NULL COMMENT '发送者昵称',
  `from_avatar` varchar(255) DEFAULT NULL COMMENT '发送者头像',
  `amount` double(6,2) unsigned NOT NULL COMMENT '红包金额',
  `openid` varchar(100) NOT NULL COMMENT '红包接收openid',
  `state` tinyint(1) unsigned DEFAULT '0' COMMENT '提现状态0:未领取 1:已领取 2:红包异常',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_xhbdz_deposit
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_xhbdz_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhbdz_goods`;
CREATE TABLE `ims_xhbdz_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `title` varchar(60) CHARACTER SET utf8 NOT NULL COMMENT '商品标题',
  `name` varchar(60) CHARACTER SET utf8 NOT NULL COMMENT '商品标题',
  `price` double(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品金额',
  `picimg` varchar(150) CHARACTER SET utf8 NOT NULL COMMENT '商品图片',
  `stock` smallint(6) unsigned NOT NULL COMMENT '库存',
  `content` text CHARACTER SET utf8 COMMENT '商品详情',
  `status` tinyint(1) unsigned NOT NULL COMMENT '1:上架, 2: 下架',
  `del` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ims_xhbdz_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_xhbdz_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhbdz_member`;
CREATE TABLE `ims_xhbdz_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id',
  `nickname` varchar(30) NOT NULL COMMENT '昵称',
  `wechat` varchar(50) DEFAULT NULL COMMENT '微信号',
  `truename` varchar(8) DEFAULT NULL COMMENT '真实姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `openid` varchar(50) NOT NULL COMMENT '微信会员openID',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `level` tinyint(2) unsigned NOT NULL COMMENT '会员等级',
  `shouyi` double(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总收益',
  `parent1` int(10) unsigned DEFAULT NULL COMMENT '上级1',
  `parent2` int(10) unsigned DEFAULT NULL COMMENT '升级2',
  `parent3` int(10) unsigned DEFAULT NULL COMMENT '升级3',
  `parent4` int(10) unsigned DEFAULT NULL COMMENT '升级4',
  `parent5` int(10) unsigned DEFAULT NULL COMMENT '升级5',
  `parent6` int(10) unsigned DEFAULT NULL COMMENT '升级6',
  `parent7` int(10) unsigned DEFAULT NULL COMMENT '升级7',
  `parent8` int(10) unsigned DEFAULT NULL COMMENT '升级8',
  `parent9` int(10) unsigned DEFAULT NULL COMMENT '升级9',
  `add_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `openid` (`openid`),
  KEY `openid_2` (`openid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_xhbdz_member
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_xhbdz_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhbdz_order`;
CREATE TABLE `ims_xhbdz_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '索引',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号id',
  `ordersn` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '订单号',
  `gid` int(10) unsigned NOT NULL COMMENT '购买商品ID',
  `price` double(10,2) unsigned NOT NULL COMMENT '支付金额',
  `title` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '商品标题',
  `turename` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT '支付用户',
  `mobile` varchar(11) CHARACTER SET utf8 NOT NULL COMMENT '支付者手机号',
  `openid` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '购买用户',
  `paystate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为已支付 1为未支付',
  `paytime` int(10) unsigned DEFAULT NULL COMMENT '支付时间',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ordersn` (`ordersn`),
  KEY `openid` (`openid`),
  KEY `ordersn_2` (`ordersn`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='订单';

-- ----------------------------
-- Records of ims_xhbdz_order
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_xhbdz_poster`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhbdz_poster`;
CREATE TABLE `ims_xhbdz_poster` (
  `scene_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned DEFAULT NULL,
  `openid` varchar(100) NOT NULL,
  `qr_url` varchar(500) DEFAULT NULL,
  `media_id` varchar(500) DEFAULT NULL,
  `ticket` varchar(888) DEFAULT NULL,
  `createtime` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`scene_id`),
  KEY `openid` (`openid`),
  KEY `ticket` (`ticket`(255))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='海报';

-- ----------------------------
-- Records of ims_xhbdz_poster
-- ----------------------------
