/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : wowoxiu

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-06-13 14:09:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_tiger_youzan_ad`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_ad`;
CREATE TABLE `ims_tiger_youzan_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(250) DEFAULT '0',
  `pic` varchar(250) DEFAULT '0',
  `url` varchar(250) DEFAULT '0',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_ad
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_dianyuan`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_dianyuan`;
CREATE TABLE `ims_tiger_youzan_dianyuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `nickname` varchar(50) DEFAULT '0',
  `ename` varchar(50) DEFAULT '0',
  `tel` varchar(50) DEFAULT '0',
  `password` varchar(50) DEFAULT '0',
  `companyname` varchar(200) DEFAULT '0',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_dianyuan
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_goods`;
CREATE TABLE `ims_tiger_youzan_goods` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `px` int(10) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `hot` varchar(50) NOT NULL,
  `hotcolor` varchar(50) NOT NULL,
  `leibei` varchar(10) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `day_sum` int(11) NOT NULL DEFAULT '0',
  `deadline` datetime NOT NULL,
  `per_user_limit` int(11) NOT NULL DEFAULT '0',
  `starttime` varchar(12) DEFAULT NULL,
  `endtime` varchar(12) DEFAULT NULL,
  `cost` int(11) NOT NULL DEFAULT '0',
  `cost_type` int(11) NOT NULL DEFAULT '1' COMMENT '1系统积分 2会员积分 4,8等留作扩展',
  `price` int(11) NOT NULL DEFAULT '100',
  `vip_require` int(10) NOT NULL DEFAULT '0' COMMENT '兑换最低VIP级别',
  `content` text NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '是否需要填写收货地址,1,实物需要填写地址,0虚拟物品不需要填写地址',
  `dj_link` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_hexiao`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_hexiao`;
CREATE TABLE `ims_tiger_youzan_hexiao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `dianyanid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `nickname` varchar(50) DEFAULT '0',
  `ename` varchar(50) DEFAULT '0',
  `companyname` varchar(200) DEFAULT '0',
  `goodname` varchar(200) DEFAULT '0',
  `goodid` int(11) DEFAULT '0',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_hexiao
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_member`;
CREATE TABLE `ims_tiger_youzan_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `from_user` varchar(100) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `helpid` int(11) NOT NULL,
  `unionid` varchar(100) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `follow` tinyint(1) NOT NULL DEFAULT '0',
  `headimgurl` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `time` int(13) DEFAULT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_member
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_order`;
CREATE TABLE `ims_tiger_youzan_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `fans_id` int(11) DEFAULT '0' COMMENT 'weixin_user_id有赞粉丝ID',
  `nickname` varchar(50) DEFAULT '0' COMMENT '粉丝昵称',
  `picurl` varchar(250) DEFAULT '0' COMMENT '粉丝昵称',
  `tid` varchar(32) DEFAULT '0' COMMENT '交易编号',
  `num` int(11) DEFAULT '0' COMMENT '数量',
  `num_iid` int(11) DEFAULT '0' COMMENT '商品数字编号',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '商品价格',
  `pic_path` varchar(255) NOT NULL DEFAULT '' COMMENT '主图',
  `pic_thumb_path` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图地址',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '交易标题',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '交易类型',
  `buyer_type` int(3) DEFAULT '0' COMMENT '买家类型，取值范围：0 为未知，1 为微信粉丝，2 为微博粉丝',
  `buyer_nick` varchar(50) NOT NULL DEFAULT '' COMMENT '买家昵称',
  `trade_memo` varchar(255) NOT NULL DEFAULT '' COMMENT '买家备注',
  `receiver_city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `receiver_district` varchar(255) NOT NULL DEFAULT '' COMMENT '区',
  `receiver_name` varchar(55) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `receiver_address` varchar(55) NOT NULL DEFAULT '' COMMENT '具体地址',
  `receiver_mobile` varchar(20) DEFAULT '0' COMMENT '手机号',
  `feedback` int(3) DEFAULT '0' COMMENT '交易维权状态。0 无维权，1 顾客发起维权',
  `status` varchar(20) DEFAULT '0' COMMENT '交易状态。取值范围：TRADE_BUYER_SIGNED (买家已签收) ',
  `total_fee` decimal(10,2) DEFAULT '0.00' COMMENT '商品总价',
  `payment` decimal(10,2) DEFAULT '0.00' COMMENT '实付金额',
  `created` varchar(22) NOT NULL COMMENT '交易创建时间',
  `update_time` varchar(22) NOT NULL COMMENT '交易更新时间',
  `pay_type` varchar(30) DEFAULT '' COMMENT '支付类型。取值范围：WEIXIN (微信支付)',
  `cengji` int(1) DEFAULT '0' COMMENT '1为1级 2为2级 3为3级',
  `isjs` int(1) DEFAULT '1' COMMENT '结算状态 1未结算  2已结算',
  `bili` decimal(10,2) DEFAULT '0.00' COMMENT '佣金比例',
  `yongjin` decimal(10,2) DEFAULT '0.00' COMMENT '结算佣金',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_fans_id` (`fans_id`),
  KEY `idx_num_iid` (`num_iid`),
  KEY `idx_created` (`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_order
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_paylog`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_paylog`;
CREATE TABLE `ims_tiger_youzan_paylog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `dwnick` varchar(255) DEFAULT NULL COMMENT '微信用户昵称',
  `dopenid` varchar(255) DEFAULT NULL COMMENT '微信用户openid',
  `dtime` int(11) DEFAULT NULL COMMENT '打款时间',
  `dcredit` int(11) DEFAULT NULL COMMENT '消耗积分',
  `dtotal_amount` int(11) DEFAULT NULL COMMENT '金额，分为单位',
  `dmch_billno` varchar(50) DEFAULT NULL COMMENT '生成的商户订单号',
  `dissuccess` tinyint(1) DEFAULT NULL COMMENT '是否打款成功',
  `dresult` varchar(255) DEFAULT NULL COMMENT '失败提示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_paylog
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_poster`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_poster`;
CREATE TABLE `ims_tiger_youzan_poster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `fans_type` int(1) DEFAULT '0',
  `type` int(1) DEFAULT NULL,
  `data` text,
  `createtime` varchar(12) DEFAULT NULL,
  `bg` varchar(200) DEFAULT NULL,
  `tzurl` varchar(250) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `doneurl` varchar(200) DEFAULT NULL,
  `tipsurl` varchar(200) DEFAULT NULL,
  `score` int(11) DEFAULT '0',
  `score2` int(11) DEFAULT '0',
  `cscore` int(11) DEFAULT '0',
  `cscore2` int(11) DEFAULT '0',
  `pscore` int(11) DEFAULT '0',
  `pscore2` int(11) DEFAULT '0',
  `scorehb` float DEFAULT '0',
  `cscorehb` float DEFAULT '0',
  `pscorehb` float DEFAULT '0',
  `mbfont` varchar(50) DEFAULT NULL,
  `gid` int(11) DEFAULT '0',
  `kdtype` tinyint(1) NOT NULL DEFAULT '0',
  `kword` varchar(20) DEFAULT NULL,
  `mtips` varchar(200) DEFAULT NULL,
  `sliders` text,
  `slideH` int(11) DEFAULT '0',
  `credit` int(1) DEFAULT '0',
  `winfo1` varchar(200) DEFAULT NULL,
  `winfo2` varchar(200) DEFAULT NULL,
  `winfo3` varchar(200) DEFAULT NULL,
  `sharekg` int(11) DEFAULT '0',
  `sharetitle` varchar(200) DEFAULT NULL,
  `sharegzurl` varchar(200) DEFAULT NULL,
  `sharedesc` varchar(200) DEFAULT NULL,
  `sharethumb` varchar(200) DEFAULT NULL,
  `stitle` text,
  `sthumb` text,
  `sdesc` text,
  `surl` text,
  `questions` text,
  `rid` int(11) DEFAULT '0',
  `rtype` int(1) DEFAULT '0',
  `ftips` text,
  `utips` text,
  `utips2` text,
  `wtips` text,
  `starttime` varchar(12) DEFAULT NULL,
  `endtime` varchar(12) DEFAULT NULL,
  `mbstyle` varchar(50) DEFAULT NULL,
  `mbcolor` varchar(50) DEFAULT NULL,
  `nostarttips` text,
  `endtips` text,
  `grouptips` text,
  `tztype` tinyint(1) NOT NULL DEFAULT '0',
  `groups` text,
  `rscore` int(11) DEFAULT '0',
  `rtips` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_poster
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_record`;
CREATE TABLE `ims_tiger_youzan_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `score` int(11) DEFAULT '0',
  `surplus` int(11) DEFAULT '0',
  `createtime` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_request`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_request`;
CREATE TABLE `ims_tiger_youzan_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user_realname` varchar(50) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(200) NOT NULL,
  `mobile` varchar(200) NOT NULL,
  `residedist` varchar(200) NOT NULL,
  `alipay` varchar(200) NOT NULL,
  `note` varchar(200) NOT NULL,
  `goods_id` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) NOT NULL,
  `kuaidi` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_request
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_set`;
CREATE TABLE `ims_tiger_youzan_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `z1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '自购普通',
  `z2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '自购高级',
  `z3` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '自购金牌',
  `p1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '普通1级',
  `g1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '高级1级',
  `j1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金牌1级',
  `p2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `g2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `j2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `p3` decimal(10,2) NOT NULL DEFAULT '0.00',
  `g3` decimal(10,2) NOT NULL DEFAULT '0.00',
  `j3` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_set
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_share`;
CREATE TABLE `ims_tiger_youzan_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `fans_id` int(11) DEFAULT '0',
  `fans_type` int(1) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `from_user` varchar(100) NOT NULL,
  `jqfrom_user` varchar(100) NOT NULL,
  `nickname` varchar(50) DEFAULT '0',
  `avatar` varchar(200) DEFAULT NULL,
  `score` int(11) DEFAULT '0',
  `cscore` int(11) DEFAULT '0',
  `pscore` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `sceneid` int(11) DEFAULT '0',
  `ticketid` varchar(200) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `parentid` int(11) DEFAULT '0',
  `helpid` int(11) DEFAULT '0',
  `follow` tinyint(1) NOT NULL DEFAULT '0',
  `status` int(1) DEFAULT '0',
  `hasdel` int(1) DEFAULT '0',
  `createtime` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`pid`,`openid`),
  KEY `idx_weid` (`weid`),
  KEY `idx_helpid` (`helpid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_share
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_tixianlog`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_tixianlog`;
CREATE TABLE `ims_tiger_youzan_tixianlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `dwnick` varchar(255) DEFAULT NULL COMMENT '微信用户昵称',
  `dopenid` varchar(255) DEFAULT NULL COMMENT '微信用户openid',
  `dtime` int(11) DEFAULT NULL COMMENT '打款时间',
  `dcredit` int(11) DEFAULT NULL COMMENT '消耗积分',
  `dtotal_amount` int(11) DEFAULT NULL COMMENT '金额，分为单位',
  `dmch_billno` varchar(50) DEFAULT NULL COMMENT '生成的商户订单号',
  `dissuccess` tinyint(1) DEFAULT NULL COMMENT '是否打款成功',
  `dresult` varchar(255) DEFAULT NULL COMMENT '失败提示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_tixianlog
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_tiger_youzan_yzgoods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tiger_youzan_yzgoods`;
CREATE TABLE `ims_tiger_youzan_yzgoods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num_iid` int(10) unsigned NOT NULL COMMENT '有赞商品ID',
  `weid` int(10) unsigned NOT NULL,
  `px` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `tj` int(2) NOT NULL DEFAULT '0' COMMENT '是否推荐 1为推荐 ',
  `title` varchar(200) NOT NULL COMMENT '标题',
  `pic_url` varchar(255) NOT NULL COMMENT '商品大图',
  `pic_thumb_url` varchar(255) NOT NULL COMMENT '商品120小图',
  `detail_url` varchar(255) NOT NULL COMMENT '商品链接',
  `zg` decimal(10,2) NOT NULL DEFAULT '100.00' COMMENT '自购',
  `level1` decimal(10,2) NOT NULL DEFAULT '100.00' COMMENT '一级系数',
  `level2` decimal(10,2) NOT NULL DEFAULT '100.00' COMMENT '二级系数',
  `level3` decimal(10,2) NOT NULL DEFAULT '100.00' COMMENT '三级系数',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_tiger_youzan_yzgoods
-- ----------------------------
