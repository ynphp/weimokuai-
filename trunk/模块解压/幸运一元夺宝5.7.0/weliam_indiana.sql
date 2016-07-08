/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : wowoxiu

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-06-13 15:29:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_weliam_indiana_address`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_address`;
CREATE TABLE `ims_weliam_indiana_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  `openid` varchar(150) NOT NULL COMMENT '微信号',
  `username` varchar(20) NOT NULL COMMENT '收货人',
  `mobile` varchar(11) NOT NULL COMMENT '邮箱',
  `zipcode` varchar(6) NOT NULL COMMENT '邮编',
  `province` varchar(32) NOT NULL COMMENT '省份',
  `city` varchar(32) NOT NULL COMMENT '城市',
  `district` varchar(32) NOT NULL COMMENT '县区',
  `address` varchar(512) NOT NULL COMMENT '详细地址',
  `isdefault` tinyint(1) NOT NULL COMMENT '默认地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_address
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_adv`;
CREATE TABLE `ims_weliam_indiana_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_adv
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_cart`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_cart`;
CREATE TABLE `ims_weliam_indiana_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(115) NOT NULL,
  `period_number` varchar(145) NOT NULL COMMENT '期号',
  `uniacid` int(11) NOT NULL,
  `title` varchar(145) NOT NULL,
  `ip` varchar(145) NOT NULL,
  `ipaddress` varchar(145) NOT NULL,
  `num` int(11) NOT NULL COMMENT '购买人次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_cart
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_category`;
CREATE TABLE `ims_weliam_indiana_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_category
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_comcode`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_comcode`;
CREATE TABLE `ims_weliam_indiana_comcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `numa` varchar(20) NOT NULL,
  `numb` varchar(11) NOT NULL,
  `periods` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `wincode` int(11) NOT NULL,
  `arecord` longtext NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_comcode
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_consumerecord`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_consumerecord`;
CREATE TABLE `ims_weliam_indiana_consumerecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(145) NOT NULL,
  `num` int(11) NOT NULL COMMENT '消费夺宝币数量',
  `status` int(2) NOT NULL COMMENT '0消费失败1消费成功',
  `period_number` varchar(145) NOT NULL COMMENT '期号',
  `createtime` varchar(145) NOT NULL COMMENT '消费时间',
  `ip` varchar(45) NOT NULL,
  `ipaddress` varchar(145) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_consumerecord
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_coupon`;
CREATE TABLE `ims_weliam_indiana_coupon` (
  `couponid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL COMMENT '商家ID',
  `uniacid` int(10) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL,
  `title` varchar(30) NOT NULL,
  `couponsn` varchar(50) NOT NULL,
  `description` text,
  `discount` decimal(10,2) NOT NULL,
  `condition` decimal(10,2) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `limit` int(11) NOT NULL,
  `dosage` int(11) unsigned NOT NULL,
  `amount` int(11) unsigned NOT NULL,
  `thumb` varchar(500) NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `credittype` varchar(20) NOT NULL,
  `module` varchar(30) NOT NULL,
  `use_module` tinyint(3) unsigned NOT NULL,
  `daylimit` int(11) NOT NULL COMMENT '领取后几天有效',
  PRIMARY KEY (`couponid`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_coupon_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_coupon_record`;
CREATE TABLE `ims_weliam_indiana_coupon_record` (
  `recid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `grantmodule` varchar(50) NOT NULL,
  `granttime` int(10) unsigned NOT NULL,
  `usemodule` varchar(50) NOT NULL,
  `usetime` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1未使用2已使用',
  `operator` varchar(30) NOT NULL,
  `remark` varchar(300) NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `merchantid` int(11) NOT NULL COMMENT '商家ID',
  `firstopenid` varchar(145) NOT NULL COMMENT '拥有者opneid',
  `secondopenid` varchar(145) NOT NULL COMMENT '持有者openid',
  `gettime` varchar(45) NOT NULL COMMENT '生成时间',
  `endtime` varchar(45) NOT NULL COMMENT '结束时间',
  `couponnum` int(11) NOT NULL COMMENT '优惠卷数量',
  `coupon_number` varchar(145) NOT NULL COMMENT '优惠卷唯一编号',
  `usedcouponnum` int(11) NOT NULL COMMENT '已使用的优惠券数量',
  PRIMARY KEY (`recid`),
  KEY `couponid` (`uid`,`grantmodule`,`usemodule`,`status`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_coupon_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_goods_atlas`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_goods_atlas`;
CREATE TABLE `ims_weliam_indiana_goods_atlas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `g_id` int(11) NOT NULL COMMENT '商品id',
  `thumb` varchar(145) NOT NULL COMMENT '图片路径',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_goods_atlas
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_goodslist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_goodslist`;
CREATE TABLE `ims_weliam_indiana_goodslist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `merchant_id` int(11) NOT NULL COMMENT '所属商家ID',
  `title` varchar(100) DEFAULT NULL COMMENT '商品标题',
  `category_parentid` int(11) NOT NULL,
  `category_childid` int(11) NOT NULL,
  `price` int(10) DEFAULT '0' COMMENT '金额',
  `canyurenshu` int(10) unsigned DEFAULT '0' COMMENT '已参与总人次数',
  `periods` smallint(6) unsigned DEFAULT '0' COMMENT '期数',
  `maxperiods` smallint(5) unsigned DEFAULT '1' COMMENT ' 最大期数',
  `picarr` text COMMENT '商品图片',
  `content` mediumtext COMMENT '商品详情',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `pos` tinyint(4) unsigned DEFAULT NULL COMMENT '是否推荐',
  `status` int(11) NOT NULL COMMENT '0:删除,1:下架, 2: 上架',
  `isnew` int(11) NOT NULL,
  `ishot` int(11) NOT NULL,
  `jiexiao_time` int(11) NOT NULL COMMENT '多少分钟后揭晓（整数）',
  `couponid` int(11) NOT NULL COMMENT '优惠卷ID',
  `init_money` int(11) NOT NULL COMMENT '几元专区',
  `maxnum` int(11) NOT NULL COMMENT '最大购买数量',
  `sort` int(11) NOT NULL,
  `next_init_money` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_goodslist
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_hexiao`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_hexiao`;
CREATE TABLE `ims_weliam_indiana_hexiao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '核销id',
  `name` varchar(50) NOT NULL COMMENT '核销名称',
  `discount` decimal(10,2) NOT NULL COMMENT '核销金额',
  `hexiaoperson` varchar(32) NOT NULL COMMENT '核销人',
  `usedperson` varchar(32) NOT NULL COMMENT '被核销人',
  `createtime` int(11) NOT NULL COMMENT '核销时间',
  `uniacid` int(10) DEFAULT NULL COMMENT '公众号id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_hexiao
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_invite`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_invite`;
CREATE TABLE `ims_weliam_indiana_invite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `beinvited_openid` varchar(145) NOT NULL COMMENT '被邀请人家的openid',
  `invite_openid` varchar(145) NOT NULL COMMENT '邀请人的openid',
  `createtime` varchar(145) NOT NULL,
  `credit1` int(11) NOT NULL COMMENT '已返利积分数',
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_invite
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_machineset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_machineset`;
CREATE TABLE `ims_weliam_indiana_machineset` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `period_number` varchar(145) NOT NULL COMMENT '商品期号',
  `machine_num` int(11) NOT NULL COMMENT '使用机器人个数',
  `createtime` varchar(145) NOT NULL COMMENT '创建时间',
  `start_time` varchar(145) NOT NULL COMMENT '开始时间',
  `end_time` varchar(145) NOT NULL COMMENT '结束时间',
  `next_time` varchar(145) NOT NULL COMMENT '机器人下个自动购买时间',
  `status` int(2) NOT NULL COMMENT '机器人状态',
  `max_num` int(11) NOT NULL,
  `timebucket` varchar(145) NOT NULL,
  `is_followed` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_machineset
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_member`;
CREATE TABLE `ims_weliam_indiana_member` (
  `mid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `openid` varchar(145) NOT NULL COMMENT '用户openid',
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `mobile` varchar(25) NOT NULL COMMENT '手机',
  `email` varchar(145) NOT NULL COMMENT '电子邮件',
  `credit1` decimal(10,2) NOT NULL COMMENT '积分',
  `credit2` decimal(10,2) NOT NULL COMMENT '余额',
  `createtime` varchar(145) NOT NULL COMMENT '创建时间',
  `nickname` varchar(145) NOT NULL COMMENT '昵称',
  `realname` varchar(145) NOT NULL COMMENT '真实姓名',
  `avatar` varchar(445) NOT NULL COMMENT '头像',
  `gender` int(2) NOT NULL COMMENT '性别',
  `vip` int(2) NOT NULL COMMENT 'vip等级',
  `address` varchar(225) NOT NULL COMMENT '地址',
  `nationality` varchar(30) NOT NULL COMMENT '国家',
  `resideprovince` varchar(30) NOT NULL COMMENT '省份',
  `residecity` varchar(30) NOT NULL COMMENT '城市',
  `residedist` varchar(30) NOT NULL COMMENT '地区',
  `account` varchar(145) NOT NULL COMMENT '账号',
  `password` varchar(145) NOT NULL COMMENT '密码',
  `status` int(2) NOT NULL COMMENT '用户状态',
  `type` int(2) NOT NULL COMMENT '用户类型',
  `ip` varchar(35) NOT NULL COMMENT '固定IP',
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_member
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_merchant`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_merchant`;
CREATE TABLE `ims_weliam_indiana_merchant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `logo` varchar(225) NOT NULL,
  `industry` varchar(45) NOT NULL,
  `address` varchar(115) NOT NULL,
  `linkman_name` varchar(145) NOT NULL,
  `linkman_mobile` varchar(145) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `createtime` varchar(115) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `detail` varchar(1222) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_merchant
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_navi`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_navi`;
CREATE TABLE `ims_weliam_indiana_navi` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
  `uniacid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `link` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_navi
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_period`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_period`;
CREATE TABLE `ims_weliam_indiana_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL COMMENT '本期商品ID',
  `periods` int(11) NOT NULL COMMENT '该商品第几期',
  `nickname` varchar(145) NOT NULL COMMENT '获奖人昵称',
  `avatar` varchar(225) NOT NULL COMMENT '获奖人头像',
  `openid` varchar(145) NOT NULL COMMENT '获奖人openid',
  `partakes` int(11) NOT NULL COMMENT '获奖人参与次数',
  `code` varchar(45) NOT NULL COMMENT '获奖码',
  `endtime` varchar(145) NOT NULL COMMENT '本期结束时间',
  `jiexiao_time` int(11) NOT NULL COMMENT '多少分钟后揭晓（整数）',
  `confirmtime` int(11) NOT NULL COMMENT '确认地址时间',
  `taketime` int(11) NOT NULL COMMENT '确认收货时间',
  `realname` varchar(20) NOT NULL COMMENT '中奖人姓名',
  `mobile` varchar(11) NOT NULL COMMENT '中奖人电话',
  `address` varchar(200) NOT NULL COMMENT '中奖人地址',
  `express` varchar(45) NOT NULL COMMENT '快递公司',
  `expressn` varchar(145) NOT NULL COMMENT '快递单号',
  `sendtime` varchar(145) NOT NULL COMMENT '发货时间',
  `codes` longtext NOT NULL COMMENT '本期商品剩余夺宝码',
  `uniacid` int(11) NOT NULL,
  `shengyu_codes` int(11) NOT NULL COMMENT '剩余夺宝码个数',
  `zong_codes` int(11) NOT NULL COMMENT '总个数',
  `period_number` varchar(145) NOT NULL COMMENT '期号',
  `canyurenshu` int(11) NOT NULL COMMENT '参与人次数',
  `status` int(4) NOT NULL COMMENT '1进行中，2待揭晓，3已揭晓，4待发货，5已发货，6已完成，7已晒单',
  `scale` int(11) NOT NULL COMMENT '参与比例',
  `createtime` varchar(145) NOT NULL,
  `recordid` int(11) NOT NULL COMMENT '购买记录id',
  `allcodes` longtext NOT NULL COMMENT '备份总夺宝码',
  `comment` varchar(2048) NOT NULL,
  `machine_time` varchar(145) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `period_number` (`period_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_period
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_rechargerecord`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_rechargerecord`;
CREATE TABLE `ims_weliam_indiana_rechargerecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(245) NOT NULL,
  `num` int(11) NOT NULL COMMENT '充值夺宝币个数',
  `createtime` varchar(145) NOT NULL,
  `transid` varchar(145) NOT NULL,
  `status` int(11) NOT NULL,
  `paytype` int(2) NOT NULL,
  `ordersn` varchar(145) NOT NULL COMMENT '订单号',
  `type` int(2) NOT NULL COMMENT '0充值并消费1仅充值3积分兑换',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_rechargerecord
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_record`;
CREATE TABLE `ims_weliam_indiana_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `goodsid` int(10) unsigned NOT NULL COMMENT '商品ID',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `status` smallint(4) NOT NULL DEFAULT '0' COMMENT '0未支付，1为已付款,2待发货3待收货4已完成',
  `transid` varchar(30) NOT NULL COMMENT '微信订单号',
  `count` int(10) unsigned NOT NULL COMMENT '商品数量',
  `code` longtext COMMENT '获得的夺宝码',
  `createtime` int(10) unsigned NOT NULL COMMENT '购买时间',
  `period_number` varchar(145) NOT NULL COMMENT '期号',
  PRIMARY KEY (`id`),
  KEY `ordersn` (`ordersn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_saler`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_saler`;
CREATE TABLE `ims_weliam_indiana_saler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` varchar(225) DEFAULT '',
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `nickname` varchar(145) NOT NULL,
  `avatar` varchar(225) NOT NULL,
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_storeid` (`storeid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_saler
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_showprize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_showprize`;
CREATE TABLE `ims_weliam_indiana_showprize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(300) NOT NULL,
  `title` varchar(200) NOT NULL,
  `detail` varchar(1000) NOT NULL,
  `period_number` varchar(45) NOT NULL COMMENT '期号',
  `createtime` varchar(145) NOT NULL COMMENT '晒单时间',
  `status` int(11) NOT NULL COMMENT '1待审核2通过3未通过',
  `goodstitle` varchar(145) NOT NULL,
  `thumbs` varchar(2048) NOT NULL COMMENT '图集',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_showprize
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_weliam_indiana_withdraw`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weliam_indiana_withdraw`;
CREATE TABLE `ims_weliam_indiana_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `openid` varchar(225) NOT NULL COMMENT '提现人',
  `createtime` varchar(45) NOT NULL COMMENT '提现时间',
  `number` int(11) NOT NULL COMMENT '金额',
  `status` int(2) NOT NULL COMMENT '提现状态（1：等待提现；2：提现成功；3提现失败）',
  `type` int(2) NOT NULL COMMENT '提现方式（1：微信；2支付宝；3京东钱包；4：百度钱包）',
  `order_no` varchar(225) NOT NULL COMMENT '提现订单号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_weliam_indiana_withdraw
-- ----------------------------
