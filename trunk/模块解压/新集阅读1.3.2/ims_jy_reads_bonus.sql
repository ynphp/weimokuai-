/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : weizan

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-05-13 13:45:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_jy_reads_bonus`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_reads_bonus`;
CREATE TABLE `ims_jy_reads_bonus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT '0',
  `bonusname` varchar(50) DEFAULT NULL COMMENT '名称',
  `bonusthumb` varchar(255) DEFAULT NULL COMMENT '图片',
  `bonuscount` int(10) DEFAULT '0' COMMENT '总数量',
  `bonusneed` int(10) DEFAULT '0' COMMENT '需要数量',
  `bonusrest` int(10) DEFAULT '0' COMMENT '剩余数量',
  `bonusvalue` float(10,2) DEFAULT '0.00' COMMENT '价值',
  `islimit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要活动0不需要，1需要',
  `limit` text COMMENT '活动信息',
  `bonusmsg` text COMMENT '红包页信息',
  `location` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要限制地区0不需要，1需要',
  `area` text COMMENT '地理位置信息',
  `wishing` varchar(120) DEFAULT NULL COMMENT '红包祝福语最大长度128',
  `remark` varchar(255) DEFAULT NULL COMMENT '红包备注信息最大长度256',
  `displayorder` int(10) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已删除1为存在',
  `info` text COMMENT '获取的信息',
  `actname` varchar(255) DEFAULT '集阅读',
  `sendname` varchar(255) DEFAULT '集阅读',
  `bonusvaluerange` varchar(255) DEFAULT '',
  `isrange` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `share` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_jy_reads_bonus
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_jy_reads_bonus_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_reads_bonus_log`;
CREATE TABLE `ims_jy_reads_bonus_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `userid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已失败1为成功',
  `log` text COMMENT '日志',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_jy_reads_bonus_log
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_jy_reads_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_reads_coupon`;
CREATE TABLE `ims_jy_reads_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT '0',
  `couponname` varchar(50) DEFAULT NULL COMMENT '名称',
  `couponthumb` varchar(255) DEFAULT NULL COMMENT '图片',
  `couponcount` int(10) DEFAULT '0' COMMENT '总数量',
  `couponneed` int(10) DEFAULT '0' COMMENT '需要数量',
  `couponrest` int(10) DEFAULT '0' COMMENT '剩余数量',
  `couponcode` varchar(255) DEFAULT NULL COMMENT '微信上生成的',
  `couponmsg` varchar(255) DEFAULT NULL COMMENT '卡券页面信息',
  `islimit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要活动0不需要，1需要',
  `limit` text COMMENT '活动信息',
  `location` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要限制地区0不需要，1需要',
  `area` text COMMENT '地理位置信息',
  `displayorder` int(10) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已删除1为存在',
  `info` text COMMENT '获取的信息',
  `share` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_jy_reads_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_jy_reads_coupon_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_reads_coupon_log`;
CREATE TABLE `ims_jy_reads_coupon_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `userid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已失败1为成功',
  `log` text COMMENT '日志',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_jy_reads_coupon_log
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_jy_reads_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_reads_log`;
CREATE TABLE `ims_jy_reads_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `replyid` int(10) unsigned NOT NULL COMMENT '回复ID',
  `popenid` varchar(30) NOT NULL COMMENT '父ID',
  `sopenid` varchar(30) NOT NULL COMMENT '子ID',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ims_jy_reads_log
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_jy_reads_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_reads_prize`;
CREATE TABLE `ims_jy_reads_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT '0',
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `replyid` int(10) unsigned NOT NULL COMMENT '回复ID',
  `prizename` varchar(50) DEFAULT NULL COMMENT '奖品名称',
  `prizethumb` varchar(255) DEFAULT NULL COMMENT '奖品图片',
  `prizecount` int(10) DEFAULT '0' COMMENT '奖品总数量',
  `prizeneed` int(10) DEFAULT '0' COMMENT '兑奖需要数量',
  `prizerest` int(10) DEFAULT '0' COMMENT '奖品剩余数量',
  `displayorder` int(10) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已删除1为存在',
  `info` text COMMENT '获取的信息',
  `prizeurl` varchar(255) DEFAULT NULL,
  `share` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='奖项从属于活动';

-- ----------------------------
-- Records of ims_jy_reads_prize
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_jy_reads_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_reads_reply`;
CREATE TABLE `ims_jy_reads_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态0结束，1正常，2暂停',
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `share_title` varchar(255) NOT NULL COMMENT '分享标题',
  `share_thumb` varchar(255) NOT NULL COMMENT '分享图片',
  `share_description` varchar(255) NOT NULL COMMENT '分享描述',
  `link` varchar(255) NOT NULL COMMENT '当前关注链接',
  `loading` varchar(255) NOT NULL COMMENT '加载时图片',
  `top` varchar(255) NOT NULL COMMENT '内容顶部图片',
  `bottom` varchar(255) NOT NULL COMMENT '内容顶部图片',
  `telephone` varchar(50) NOT NULL COMMENT '点击bottom拨打电话',
  `bgcolor` varchar(10) NOT NULL DEFAULT '#FFFFFF' COMMENT '背景颜色',
  `content` text COMMENT '显示内容',
  `rule` text COMMENT '显示兑奖规则或活动地址',
  `tips` varchar(255) NOT NULL COMMENT '备注',
  `ad` varchar(255) NOT NULL COMMENT '核销页广告',
  `ad_url` varchar(255) NOT NULL COMMENT '核销页URL',
  `prizes` longtext NOT NULL COMMENT '当前回复的文章奖项',
  `follow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要强制关注0不需要，1需要',
  `mutual` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁止相互采集0不需要，1需要',
  `location` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要限制地区0不需要，1需要',
  `area` text COMMENT '地理位置信息',
  `copyright` varchar(20) NOT NULL,
  `starttime` int(10) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '结束时间',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `name` varchar(255) DEFAULT '集阅读',
  `arrow` varchar(255) DEFAULT NULL,
  `share` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `start` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '阅读',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='回复信息表，包含所有回复资料以及活动资料';

-- ----------------------------
-- Records of ims_jy_reads_reply
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_jy_reads_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_reads_user`;
CREATE TABLE `ims_jy_reads_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `replyid` int(10) unsigned NOT NULL COMMENT '回复ID',
  `uid` int(10) unsigned NOT NULL COMMENT '微赞系统memberID',
  `openid` varchar(30) NOT NULL COMMENT 'openid',
  `userinfo` text COMMENT '用户信息',
  `status` tinyint(1) unsigned NOT NULL COMMENT '用户状态',
  `sn` varchar(20) NOT NULL COMMENT 'SN奖品唯一码',
  `prizeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖项ID，未兑奖时为0',
  `prize` text COMMENT '序列化信息',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='点开文章的用户';

-- ----------------------------
-- Records of ims_jy_reads_user
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_jy_reads_user_info`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_reads_user_info`;
CREATE TABLE `ims_jy_reads_user_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uid` int(10) unsigned NOT NULL COMMENT '微赞系统memberID',
  `propertyid` int(10) NOT NULL COMMENT '检索ID',
  `value` varchar(255) NOT NULL COMMENT '检索值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户属性';

-- ----------------------------
-- Records of ims_jy_reads_user_info
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_jy_reads_user_property`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_reads_user_property`;
CREATE TABLE `ims_jy_reads_user_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `propertykey` varchar(255) NOT NULL COMMENT '检索键名',
  `propertyvalue` varchar(255) NOT NULL COMMENT '检索值名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户属性';

-- ----------------------------
-- Records of ims_jy_reads_user_property
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_jy_reads_verifier`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_reads_verifier`;
CREATE TABLE `ims_jy_reads_verifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL COMMENT '公众号ID',
  `rid` int(10) NOT NULL COMMENT '规则ID',
  `replyid` int(10) NOT NULL COMMENT '回复ID',
  `uid` int(10) NOT NULL COMMENT '微赞用户表memberID',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `prizeid` int(10) NOT NULL COMMENT '奖项ID',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常，0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='活动核销人员';

-- ----------------------------
-- Records of ims_jy_reads_verifier
-- ----------------------------
