/*
MySQL Data Transfer
Source Host: localhost
Source Database: a0124183617
Target Host: localhost
Target Database: a0124183617
Date: 2015-5-28 16:05:23
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for ims_xcommunity_activity
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_activity`;
CREATE TABLE `ims_xcommunity_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `regionid` varchar(500) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `enddate` varchar(30) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `number` int(11) NOT NULL DEFAULT '1',
  `content` varchar(2000) NOT NULL,
  `status` int(1) NOT NULL,
  `createtime` int(11) unsigned NOT NULL,
  `resnumber` int(11) unsigned NOT NULL COMMENT '报名人数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_admap
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_admap`;
CREATE TABLE `ims_xcommunity_admap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `regionid` int(11) NOT NULL,
  `adid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_announcement
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_announcement`;
CREATE TABLE `ims_xcommunity_announcement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `regionid` int(10) unsigned NOT NULL COMMENT '小区编号',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `createtime` int(10) unsigned NOT NULL,
  `starttime` int(11) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(11) unsigned NOT NULL COMMENT '结束时间',
  `status` tinyint(1) NOT NULL COMMENT '1禁用，2启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='发布公告';

-- ----------------------------
-- Table structure for ims_xcommunity_business
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_business`;
CREATE TABLE `ims_xcommunity_business` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `qq` int(11) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` int(1) unsigned NOT NULL COMMENT '0未审核，1审核',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_carpool
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_carpool`;
CREATE TABLE `ims_xcommunity_carpool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `seat` int(2) unsigned NOT NULL,
  `sprice` int(10) unsigned NOT NULL,
  `month` int(2) unsigned NOT NULL,
  `yday` int(2) unsigned NOT NULL,
  `contact` varchar(50) NOT NULL,
  `mobile` varchar(13) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `start_position` varchar(100) NOT NULL,
  `end_position` varchar(100) NOT NULL,
  `startMinute` int(10) unsigned NOT NULL,
  `startSeconds` int(10) unsigned NOT NULL,
  `license_number` varchar(100) NOT NULL,
  `car_model` varchar(100) NOT NULL,
  `car_brand` varchar(100) NOT NULL,
  `content` varchar(300) NOT NULL,
  `enable` int(1) NOT NULL COMMENT '1开启,0关闭',
  `createtime` int(10) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_fled
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_fled`;
CREATE TABLE `ims_xcommunity_fled` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `title` varchar(20) NOT NULL,
  `rolex` varchar(30) NOT NULL,
  `category` varchar(30) NOT NULL,
  `yprice` int(10) NOT NULL,
  `zprice` int(10) NOT NULL,
  `realname` varchar(18) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `description` varchar(100) NOT NULL,
  `regionid` int(10) NOT NULL,
  `createtime` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `images` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_member
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_member`;
CREATE TABLE `ims_xcommunity_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL COMMENT '小区编号',
  `openid` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL COMMENT '真实姓名',
  `mobile` varchar(15) NOT NULL COMMENT '手机号',
  `regionname` varchar(50) NOT NULL COMMENT '小区名称',
  `address` varchar(100) NOT NULL COMMENT '楼栋门牌',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `manage_status` tinyint(1) unsigned NOT NULL COMMENT '授权管理员',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=MyISAM AUTO_INCREMENT=121 DEFAULT CHARSET=utf8 COMMENT='注册用户';

-- ----------------------------
-- Table structure for ims_xcommunity_navextension
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_navextension`;
CREATE TABLE `ims_xcommunity_navextension` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `navurl` varchar(100) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `content` text NOT NULL COMMENT '说明',
  `cate` int(1) NOT NULL,
  `bgcolor` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_phone
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_phone`;
CREATE TABLE `ims_xcommunity_phone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `regionid` int(10) unsigned NOT NULL COMMENT '小区编号',
  `weid` int(11) unsigned NOT NULL COMMENT '公众号',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `phone` varchar(50) NOT NULL COMMENT '号码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='常用电话';

-- ----------------------------
-- Table structure for ims_xcommunity_property
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_property`;
CREATE TABLE `ims_xcommunity_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `topPicture` varchar(255) NOT NULL COMMENT '照片',
  `mcommunity` varchar(255) NOT NULL COMMENT '微社区URL',
  `content` varchar(2000) NOT NULL COMMENT '内容',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物业介绍';

-- ----------------------------
-- Table structure for ims_xcommunity_propertyfree
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_propertyfree`;
CREATE TABLE `ims_xcommunity_propertyfree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `mobile` varchar(13) NOT NULL,
  `username` varchar(30) NOT NULL,
  `homenumber` int(4) NOT NULL,
  `profree` int(4) NOT NULL,
  `tcf` int(4) NOT NULL,
  `gtsf` int(4) NOT NULL,
  `gtdf` int(4) NOT NULL,
  `protimeid` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_protime
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_protime`;
CREATE TABLE `ims_xcommunity_protime` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `protime` varchar(30) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_region
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_region`;
CREATE TABLE `ims_xcommunity_region` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `linkmen` varchar(50) NOT NULL COMMENT '联系人',
  `linkway` varchar(50) NOT NULL COMMENT '联系电话',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=utf8 COMMENT='添加小区信息';

-- ----------------------------
-- Table structure for ims_xcommunity_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_reply`;
CREATE TABLE `ims_xcommunity_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `reportid` int(10) unsigned NOT NULL COMMENT '报告ID',
  `isreply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是回复',
  `content` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_report
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_report`;
CREATE TABLE `ims_xcommunity_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL COMMENT '用户身份',
  `weid` int(11) unsigned NOT NULL COMMENT '公众号ID',
  `regionid` int(10) unsigned NOT NULL COMMENT '小区编号',
  `type` tinyint(1) NOT NULL COMMENT '1为报修，2为投诉',
  `category` varchar(50) NOT NULL DEFAULT '' COMMENT '类目',
  `content` varchar(255) NOT NULL COMMENT '投诉内容',
  `requirement` varchar(1000) NOT NULL,
  `createtime` int(11) unsigned NOT NULL COMMENT '投诉日期',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态,1已解决,0未解决,2为用户取消',
  `newmsg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有新信息',
  `rank` tinyint(3) unsigned NOT NULL COMMENT '评级 1满意，2一般，3不满意',
  `comment` varchar(1000) NOT NULL,
  `resolve` varchar(1000) NOT NULL COMMENT '处理结果',
  `resolver` varchar(50) NOT NULL COMMENT '处理人',
  `resolvetime` int(10) NOT NULL COMMENT '处理时间',
  `images` text,
  `print_sta` int(3) NOT NULL COMMENT '打印状态',
  PRIMARY KEY (`id`),
  KEY `idx_weid_regionid` (`weid`,`regionid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_res
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_res`;
CREATE TABLE `ims_xcommunity_res` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL,
  `truename` varchar(30) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `num` int(2) unsigned NOT NULL,
  `rid` int(11) unsigned NOT NULL,
  `sex` varchar(6) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_search
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_search`;
CREATE TABLE `ims_xcommunity_search` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `sname` varchar(30) NOT NULL,
  `surl` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  `icon` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_service
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_service`;
CREATE TABLE `ims_xcommunity_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `servicecategory` int(10) unsigned NOT NULL COMMENT '生活服务大分类 1家政服务，2租赁服务',
  `servicesmallcategory` varchar(50) NOT NULL COMMENT '生活服务小分类',
  `requirement` varchar(255) NOT NULL COMMENT '精准要求,如保洁需要填写 平米大小',
  `remark` varchar(500) NOT NULL COMMENT '备注',
  `contacttype` int(10) unsigned NOT NULL COMMENT '联系类型:1.随时联系;2.白天联系;3:晚上联系;4:自定义',
  `contactdesc` varchar(255) NOT NULL COMMENT '联系描述',
  `status` int(10) unsigned NOT NULL COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL,
  `images` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_servicecategory
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_servicecategory`;
CREATE TABLE `ims_xcommunity_servicecategory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `description` varchar(50) NOT NULL COMMENT '分类描述',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_address
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_address`;
CREATE TABLE `ims_xcommunity_shopping_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `area` varchar(30) NOT NULL,
  `address` varchar(300) NOT NULL,
  `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_cart
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_cart`;
CREATE TABLE `ims_xcommunity_shopping_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_category
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_category`;
CREATE TABLE `ims_xcommunity_shopping_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='超市商品分类';

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_dispatch
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_dispatch`;
CREATE TABLE `ims_xcommunity_shopping_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` int(11) DEFAULT '0',
  `description` text,
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_express
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_express`;
CREATE TABLE `ims_xcommunity_shopping_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_goods`;
CREATE TABLE `ims_xcommunity_shopping_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `unit` varchar(5) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `goodssn` varchar(50) NOT NULL DEFAULT '',
  `productsn` varchar(50) NOT NULL DEFAULT '',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `originalprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `total` int(10) NOT NULL DEFAULT '0',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `spec` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit` int(11) DEFAULT '0',
  `maxbuy` int(11) DEFAULT '0',
  `hasoption` int(11) DEFAULT '0',
  `dispatch` int(11) DEFAULT '0',
  `thumb_url` text,
  `isnew` int(11) DEFAULT '0',
  `ishot` int(11) DEFAULT '0',
  `isdiscount` int(11) DEFAULT '0',
  `isrecommand` int(11) DEFAULT '0',
  `istime` int(11) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `viewcount` int(11) DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_goods_option
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_goods_option`;
CREATE TABLE `ims_xcommunity_shopping_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_goods_param
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_goods_param`;
CREATE TABLE `ims_xcommunity_shopping_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_order
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_order`;
CREATE TABLE `ims_xcommunity_shopping_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `addressid` int(10) unsigned NOT NULL,
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(200) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatch` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_order_goods`;
CREATE TABLE `ims_xcommunity_shopping_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `optionname` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_product
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_product`;
CREATE TABLE `ims_xcommunity_shopping_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `productsn` varchar(50) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `marketprice` decimal(10,0) unsigned NOT NULL,
  `productprice` decimal(10,0) unsigned NOT NULL,
  `total` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `spec` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goodsid` (`goodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_slide
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_slide`;
CREATE TABLE `ims_xcommunity_shopping_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_spec
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_spec`;
CREATE TABLE `ims_xcommunity_shopping_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_shopping_spec_item
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_spec_item`;
CREATE TABLE `ims_xcommunity_shopping_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_sjdp
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_sjdp`;
CREATE TABLE `ims_xcommunity_sjdp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uid` int(11) NOT NULL,
  `regionid` int(11) NOT NULL,
  `sjname` varchar(30) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `contactname` varchar(30) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `qq` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  `address` varchar(150) NOT NULL,
  `shopdesc` varchar(500) NOT NULL,
  `businnesstime` varchar(20) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_slide
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_slide`;
CREATE TABLE `ims_xcommunity_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(10) NOT NULL,
  `title` varchar(30) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `url` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xcommunity_verifycode
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_verifycode`;
CREATE TABLE `ims_xcommunity_verifycode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `verifycode` varchar(6) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `total` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ims_xfcommunity_images
-- ----------------------------
DROP TABLE IF EXISTS `ims_xfcommunity_images`;
CREATE TABLE `ims_xfcommunity_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `src` varchar(255) DEFAULT NULL,
  `file` longtext,
  `type` int(11) NOT NULL COMMENT '报修1，租赁2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `ims_xcommunity_announcement` VALUES ('2', '5', '1', '天气转暖  注意安全', '<p>尊敬的小区业主：</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;近期天气变幻，降水频繁，冷暖差异较大，造成积冰融化、冻结再融化的现象。给出行安全带来很大隐患，新鹤物业公司在此提醒您注意高空落冰，远离楼根、高架线及落水管等易发生落物的建筑物行车、行走，以免造成不必要的事件发生。另外，请您关注天气情况，适时增减衣物，并注意个人卫生，防止流行性感冒。</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;新鹤物业公司祝您和您的家人&nbsp;身体健康，合家欢乐！</p>\r\n<p style=\"text-align: right;\">新鹤物业公司</p>\r\n<p style=\"text-align: right;\">2015年4月7日</p>', '鹤岗微小区', '1428370178', '1428336000', '1428422399', '2');
INSERT INTO `ims_xcommunity_announcement` VALUES ('3', '5', '1', '停电通知', '亲爱的业主：\r\n       因配合电业春季检修，新鹤小区4月24日早6:00-8:00进行停电施工，停电期间带来的不便，敬请谅解。\r\n如遇下雨，则可能改期，请各位业主奔走相告。', '鹤岗微小区', '1429499005', '1429459200', '1433001600', '2');
INSERT INTO `ims_xcommunity_announcement` VALUES ('4', '5', '2', '停电通知', '<p>亲爱的业主： 因配合电业春季检修，成龙小区4月23日早6:00-8:00进行停电施工，停电期间带来的不便，敬请谅解。 如遇下雨，则可能改期，请各位业主奔走相告。</p>', '鹤岗微小区', '1429499691', '1429459200', '1433001600', '2');
INSERT INTO `ims_xcommunity_announcement` VALUES ('5', '5', '3', '停电通知', '<p>亲爱的业主： 因配合电业春季检修，昌盛小区4月24日早6:00-8:00进行停电施工，停电期间带来的不便，敬请谅解。 如遇下雨，则可能改期，请各位业主奔走相告。</p>', '鹤岗微小区', '1429499907', '1429459200', '1433001600', '2');
INSERT INTO `ims_xcommunity_announcement` VALUES ('6', '5', '4', '天气转暖 注意安全', '<p>尊敬的小区业主：</p>\r\n<p>近期天气变幻，降水频繁，冷暖差异较大，造成积冰融化、冻结再融化的现象。给出行安全带来很大隐患，绿洲物业公司在此提醒您注意高空落冰，远离楼根、高架线及落水管等易发生落物的建筑物行车、行走，以免造成不必要的事件发生。另外，请您关注天气情况，适时增减衣物，并注意个人卫生，防止流行性感冒。</p>\r\n<p>绿洲物业公司祝您和您的家人 身体健康，合家欢乐！</p>', '鹤岗微小区', '1429500214', '1427990400', '1429459200', '2');
INSERT INTO `ims_xcommunity_announcement` VALUES ('7', '5', '5', '停电通知', '<p>亲爱的业主： 因配合电业春季检修，叠翠小区4月23日早6:00-8:00进行停电施工，停电期间带来的不便，敬请谅解。 如遇下雨，则可能改期，请各位业主奔走相告。</p>', '鹤岗微小区', '1429500467', '1429459200', '1429545599', '2');
INSERT INTO `ims_xcommunity_announcement` VALUES ('8', '5', '8', '停电通知', '<p>亲爱的业主： 因配合电业春季检修，光宇A区、B区将于4月22日7:30-15:00进行停电施工，停电期间带来的不便，敬请谅解。 如遇下雨，则可能改期，请各位业主奔走相告。</p>', '鹤岗微小区', '1429500696', '1429459200', '1429545599', '2');
INSERT INTO `ims_xcommunity_announcement` VALUES ('10', '5', '10', '停电通知', '<p>亲爱的业主： 因配合电业春季检修，德政小区4月21日7:30-17:30进行停电施工，停电期间带来的不便，敬请谅解。 如遇下雨，则可能改期，请各位业主奔走相告。</p>', '鹤岗微小区', '1429501209', '1429459200', '1429545599', '2');
INSERT INTO `ims_xcommunity_business` VALUES ('1', '5', '18714623416', '姜永超', '1c45599b593d52a0451c24b0375268b4', '', '0', '1427709275', '1');
INSERT INTO `ims_xcommunity_carpool` VALUES ('1', '5', '每天下班拼车', '3', '0', '4', '2', '张先生', '18104685399', 'o2Y6NuJTmX1e28poVzADVVqZu78k', '新鹤B区', '老街基', '17', '1', '', '', '', '本人到胜利街', '1', '1428707669', '1', '0');
INSERT INTO `ims_xcommunity_fled` VALUES ('1', '5', '', '家用双核电脑 ', '八成新', '', '0', '1200', '', '13846894410', '家用双核电脑，19显示器，带电脑桌椅和音箱。120​‌‌0元不讲价。上门自取！', '1', '1428707493', '0', 'a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}');
INSERT INTO `ims_xcommunity_fled` VALUES ('2', '5', '', '二手麻将机,质量好,物超所值', '九成新', '', '0', '800', '', '15846698699', '诚心出售二手麻将机，四四大麻将，质量好，非常的物超​‌‌所值，可以打电话过来看机器，再加200元可带4个椅子。', '1', '1428707790', '0', '');
INSERT INTO `ims_xcommunity_fled` VALUES ('3', '5', '', '低价出售鞋店圆柜', '九成新', '', '0', '200', '', '18714623007', '低价出售鞋店圆柜，一大一小。​‌‌', '1', '1428707985', '0', 'a:3:{i:0;s:1:\"4\";i:1;s:1:\"5\";i:2;s:1:\"6\";}');
INSERT INTO `ims_xcommunity_member` VALUES ('5', '5', '1', 'o2Y6NuJTmX1e28poVzADVVqZu78k', '黄丽娟', '18104685366', '', '', '', '1', '1427965417', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('2', '5', '1', 'o2Y6NuOD32-GOkMcgvMaij1FMkEA', '张铁峰', '', '', '', '', '1', '1431569370', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('3', '5', '1', 'o2Y6NuFRg4ClBI85-VKZLZIE2BFg', '孙宇', '18250050795', '', '23号楼2单元405', '', '1', '1427675911', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('4', '5', '1', 'o2Y6NuJoZntg-5xRVSZfXLjHBl8E', '姜永超', '18714623416', '', '41号3单元502', '', '1', '1427722212', '1');
INSERT INTO `ims_xcommunity_member` VALUES ('6', '5', '2', 'o2Y6NuG9UemixvhzTA8smf1vvZ9M', '王欣', '13803683111', '', '12号楼1单元302', '', '1', '1427978510', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('7', '5', '1', 'o2Y6NuK-VIGzUV6ORIec_i34i4l4', 'hu', '13634680000', '', '', '', '1', '1428027689', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('8', '5', '1', 'o2Y6NuA8HjHuJlK2BXTT2O91u2lk', '吴至尊', '13846892003', '', '', '', '1', '1427984998', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('9', '5', '3', 'o2Y6NuMtqlHv_sXvy5vkv9k6OZkk', '任殿龙', '13199618666', '', '31号楼1单元301室', '', '1', '1427990090', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('10', '5', '2', 'o2Y6NuFirCopKpcM1XAylnNEp9FQ', '姜涛', '13634685959', '', '4号楼3单元206', '', '1', '1428016996', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('11', '5', '13', '', '李景臣', '15845466039', '', '', '', '1', '1429779334', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('12', '5', '1', '', 'yi', '15888866662', '', '', '', '1', '1428025052', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('13', '5', '1', 'o2Y6NuLbnh5lycA0Y48zm2KN5G4s', '赵金龙', '15184652220', '', '', '', '1', '1428029826', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('14', '5', '1', 'o2Y6NuLbnh5lycA0Y48zm2KN5G4s', '赵金龙', '15184652220', '', '', '', '1', '1428029977', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('15', '5', '1', 'o2Y6NuMLSOU9D6kUQVsS3AjPjDYc', '佟利平', '13946726603', '', '8号楼7单元714', '', '1', '1428036322', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('16', '5', '1', 'o2Y6NuCmQYHHvp5LTuxFDU4t5j0w', '杨铁军', '13251687658', '', '', '', '1', '1428039730', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('17', '5', '1', 'o2Y6NuDV21_zd80TkmoayuvNwVNU', '刘文旭', '18945751300', '', '23号楼402', '', '1', '1428039765', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('18', '5', '1', 'o2Y6NuI2RaCwP1P2FysZh4Ea65aA', '张玉霞', '13384687630', '', '', '', '1', '1428054257', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('19', '5', '1', 'o2Y6NuPHPlSn5Y64Z89ODj91Uq6w', 'yuhuai', '15094506608', '', '22号楼5单元811', '', '1', '1428064878', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('20', '5', '1', 'o2Y6NuPHPlSn5Y64Z89ODj91Uq6w', 'yuhuai', '15094506608', '', '22号楼5单元811', '', '1', '1428064878', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('21', '5', '1', 'o2Y6NuPHPlSn5Y64Z89ODj91Uq6w', 'yuhuai', '15094506608', '', '22号楼5单元811', '', '1', '1428064883', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('22', '5', '1', 'o2Y6NuN75LH3NQ1JsKfe__dmSeX8', '徐胜军', '13945757773', '', '8号楼3单元502', '', '1', '1428108430', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('23', '5', '1', 'o2Y6NuN75LH3NQ1JsKfe__dmSeX8', '徐胜军', '13945757773', '', '8号楼3单元502', '', '1', '1428108544', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('24', '5', '1', 'o2Y6NuJMCmXiPWCc4Gz0KFM9Ur7U', '张桂荣', '13904883458', '', '28号楼4单元307', '', '1', '1428124137', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('25', '5', '1', 'o2Y6NuJMCmXiPWCc4Gz0KFM9Ur7U', '张桂荣', '13904883458', '', '28号楼4单元307', '', '1', '1428124342', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('26', '5', '1', 'o2Y6NuEgOv1dfFE-XBr8YFXJHlO8', '王伟', '15636799888', '', '', '', '1', '1428145474', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('27', '5', '13', 'o2Y6NuFZZ025RTiF0TgLk2C4uHPI', '崔秀丽', '13039730883', '', '隆祥家园5号楼5#501', '', '1', '1428147633', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('28', '5', '1', 'o2Y6NuNyKesZpeKhcQKAqOZL5GK4', '仲华', '18604684670', '', '', '', '1', '1428300927', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('29', '5', '1', 'o2Y6NuM_TZHkVu4BzzKJ7QlOlq80', '姚云霞', '18714652321', '', '23号楼2单元402', '', '1', '1428321634', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('30', '5', '1', 'o2Y6NuHlHaTF_zeFtzkJvZ_4YizQ', '杨明', '15545843335', '', '', '', '1', '1428384819', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('31', '5', '1', 'o2Y6NuHlHaTF_zeFtzkJvZ_4YizQ', '杨明', '15545843335', '', '', '', '1', '1428384820', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('32', '5', '13', 'o2Y6NuP6shnF2jXjf5HMdpeAGzBQ', '杨雪松', '18746553254', '', '', '', '1', '1428728479', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('33', '5', '1', 'o2Y6NuA-ZpyJEaxHITtyzhQ87nAs', '张铁雷', '15254537999', '', '', '', '1', '1428881780', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('34', '5', '1', 'o2Y6NuGO1bfxiD41YjncMndfIVsk', '夏福军', '15545888886', '', '新鹤C区A4号楼101室', '', '1', '1428891094', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('35', '5', '13', 'o2Y6NuFQ9Xe2XuO3K1S7NoA7PylA', '李刚', '18304689763', '', '兴安台滨河南小区', '', '1', '1428979734', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('36', '5', '13', 'o2Y6NuN_sURXRZoeuauYENg--DbM', '丛先生', '18646896888', '', '', '', '1', '1429002402', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('37', '5', '1', 'o2Y6NuJaAGq7IMbd0H3mliE4lczM', '超博', '13946727754', '', '22楼超博发廊', '', '1', '1429145965', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('38', '5', '1', 'o2Y6NuIDxmCK3BrKM4W-mRKXOLFQ', '庞维霞', '13199618778', '', '', '本店经营，保健按摩～足疗～拔火罐', '1', '1429543658', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('39', '5', '1', 'o2Y6NuNo0Ss7l57YcTk5GoUKtRc0', '胡秋菊', '13304687558', '', '新鹤b区19号楼6单元217', '', '1', '1429182336', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('40', '5', '1', 'o2Y6NuPoKE5B5jPHxrs3_lo-l_jg', '刘吉芳', '13796476586', '', 'c区、49号楼1单元401', '', '1', '1429190944', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('41', '5', '1', 'o2Y6NuD22xt7f9Q-oqzCkWrpT3Ig', '李世民', '13214681458', '', '新鹤c区26号楼106', '艺博钢琴音乐学校', '1', '1429409103', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('42', '5', '1', 'o2Y6NuOnULvjJfkKjs_x0goshQTA', '郭巍', '13846837772', '', '', '', '1', '1429417633', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('43', '5', '1', 'o2Y6NuOnULvjJfkKjs_x0goshQTA', '郭巍', '13846837772', '', '', '', '1', '1429417721', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('44', '5', '1', 'o2Y6NuFqG5OEjzuWTBVoEsQwOOIc', '边疆', '15246847311', '', '', '', '1', '1429417982', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('45', '5', '2', 'o2Y6NuCphtv9wHpqffER-J2sHbxE', '', '13946729197', '', '', '', '1', '1429532423', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('46', '5', '1', 'o2Y6NuMq9RAHcsrDmeZdHbZ0i8Ek', '张福军', '18646857077', '', '新鹤B区14号', '', '1', '1429532506', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('47', '5', '1', 'o2Y6NuNB5oBZAQu7fgwRXrOHRhFk', '王婧瑶', '18646871600', '', '27号楼', '', '1', '1429532611', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('48', '5', '1', 'o2Y6NuELV1S0olZv7sMLGDLKCHAc', '李佳芮', '15704682333', '', '22号楼4单元302', '', '1', '1429532727', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('49', '5', '1', 'o2Y6NuERL1SHaK4YJiZaPTO7NsSU', '马江涛', '13945762628', '', '30号楼4单元207室', '', '1', '1429532891', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('50', '5', '1', 'o2Y6NuHMLv0QmBXUx1XGrObbCTFA', '连心心', '18714625117', '', '新鹤B区21号楼', '', '1', '1429533060', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('51', '5', '1', 'o2Y6NuFCl81oi_j-LrAHd0uKDEtk', '邢云有', '13946720915', '', '', '', '1', '1429533087', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('52', '5', '1', 'o2Y6NuJTp9BtiVA52Zhc8jalN4pM', '周桂英', '13946796682', '', '29号楼3单元502', '', '1', '1429533980', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('53', '5', '1', 'o2Y6NuA_Gbk_wTla8qmj9gRhzCUE', '郭吉芬', '13624689058', '', '21号楼4单元708室', '', '1', '1429534181', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('54', '5', '2', 'o2Y6NuAjJWJHRahJXI6a_ZkNEXsY', '孙玉清', '13946714166', '', '4号楼512', '', '1', '1429534364', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('55', '5', '1', 'o2Y6NuCLz90RJx8nLmq7e6ELcmiU', '陈昌利', '13904880053', '', '10号楼3单元705', '', '1', '1429534930', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('56', '5', '1', 'o2Y6NuCvM9DV290IbnQ5GFwrlW0A', '于畔', '15846692444', '', '', '', '1', '1429535098', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('57', '5', '1', 'o2Y6NuEwjqsK-VEKEgS4qSPkmvWY', '宣立峰', '13846875881', '', '23号803室', '', '1', '1429535425', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('58', '5', '1', 'o2Y6NuGcqZYpZANWht8V1kOuOcxI', '常炳红', '13504588496', '', '17号楼3单元103室', '', '1', '1429535791', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('59', '5', '1', 'o2Y6NuNuNb2NYvcSpexobZCNXhvQ', '石', '15145845190', '', '36号楼4单元', '', '1', '1429536684', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('60', '5', '1', 'o2Y6NuLwtwHC8cnTfCz_b87iaUGE', '董典文', '13946740331', '', '33号楼1单元102', '', '1', '1429536811', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('61', '5', '1', 'o2Y6NuDOrnueH0-N3xzaYS9T9o0k', '于长泽', '18646884589', '', '19号楼一单元402', '', '1', '1429536950', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('62', '5', '1', 'o2Y6NuN_we-_ekfl_I2qBvGoyK5Y', '鄂禹', '13613688878', '', '15号楼5单元604户', '', '1', '1429537225', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('63', '5', '1', 'o2Y6NuH8i0BmiuR2hGyiMR-BKpqs', '王蕾', '18604687291', '', '', '', '1', '1429537397', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('64', '5', '1', 'o2Y6NuB8aT9bVfTIYYpJWHaIgoAQ', '梦影', '13946716501', '', '32号楼一单元', '', '1', '1429537973', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('65', '5', '1', 'o2Y6NuOvtgQFszu5Vj1dTbf2gGpI', '房永健', '18945173453', '', '19号楼3单元307', '', '1', '1429538081', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('66', '5', '1', 'o2Y6NuOvtgQFszu5Vj1dTbf2gGpI', '房永健', '18945173453', '', '19号楼3单元307', '', '1', '1429538236', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('67', '5', '1', 'o2Y6NuHA_d2r7b5_vxL8vQTeldcI', '苏航', '15164616615', '', '一号楼 3单元301', '', '1', '1429539102', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('68', '5', '1', 'o2Y6NuHYQh5PS3__sUYrSiVkniBQ', '王庆霞', '13846837090', '', '22号楼3单元605', '', '1', '1429539245', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('69', '5', '1', 'o2Y6NuBvwREOKqkUngU3UR9JW17Q', '刘瑞杰', '13946716126', '', '49号楼1单元702', '', '1', '1429539530', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('70', '5', '1', 'o2Y6NuBvwREOKqkUngU3UR9JW17Q', '刘瑞杰', '13946716126', '', '49号楼1单元702', '', '1', '1429539556', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('71', '5', '1', 'o2Y6NuLwSDYD0mJesn8CF8dfmoRE', '李琼', '13946796333', '', '', '', '1', '1429539592', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('72', '5', '1', 'o2Y6NuHikpFLmhAmv5joQIfZO1YI', '杨福财', '15545801718', '', '8单元302', '', '1', '1429540506', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('73', '5', '1', 'o2Y6NuFO0rUc3a0Y18tPJe0aaX-U', '关桂英', '13029768867', '', '8号楼5单元210', '', '1', '1429540840', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('74', '5', '1', 'o2Y6NuFO0rUc3a0Y18tPJe0aaX-U', '关桂英', '13029768867', '', '8号楼5单元210', '', '1', '1429541102', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('75', '5', '1', 'o2Y6NuFO0rUc3a0Y18tPJe0aaX-U', '关桂英', '13029768867', '', '8号楼5单元210', '', '1', '1429541107', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('76', '5', '1', 'o2Y6NuDj7qKREpR4ccdYMKimPspY', '刘洋', '15765110396', '', '52号楼三单元202', '', '1', '1429542172', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('77', '5', '1', 'o2Y6NuI4b4kVkCD1MbKCAkYe9KXg', '王志芹', '15946628543', '', '27 号楼105  室', '停电告知', '1', '1429553656', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('78', '5', '1', 'o2Y6NuNz4zkR-qHVfy9JguKJWSpI', '', '13684690789', '', '', '', '1', '1429562563', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('79', '5', '1', 'o2Y6NuBJknoeUhde4wCAobzaICzc', '于海波', '13945768866', '', '22号楼510', '', '1', '1429563899', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('80', '5', '1', 'o2Y6NuBcDyl16jNhOUAQ82PM0mJI', '张海艳', '18045874150', '', '25号楼2单元504', '', '1', '1429568485', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('81', '5', '1', 'o2Y6NuB0Hl_b9U-2K539VX6Pnp1g', '杨国良', '15545809609', '', '', '', '1', '1429568579', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('82', '5', '1', 'o2Y6NuJhk_2-IvMXv2RmCBMTB4Fk', '李绍辉', '15904685136', '', '14号楼5单元311室', '', '1', '1429570087', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('83', '5', '2', 'o2Y6NuKuvWXdWw9hXJy-u6xO8Cko', '赵慧', '13946780089', '', '3单元502室', '', '1', '1429570294', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('84', '5', '1', 'o2Y6NuCCWZWoiqAhYUcg8zggFX_4', '童子荣', '15845473033', '', '23楼2单元501', '', '1', '1429574527', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('85', '5', '1', 'o2Y6NuN6ZwHySnCHPZYAqC6NfZMA', '沈洪源', '18646886795', '', 'C区55号楼', '', '1', '1429574776', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('86', '5', '1', 'o2Y6NuHjsspyHxOjYg2bqKgcJqV4', '吴秀凡', '13946787223', '', '', '', '1', '1429576697', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('87', '5', '1', 'o2Y6NuDtUn0JbwPbjLhRPw3N8UAs', '姜路', '13624680188', '', '8号楼', '', '1', '1429579400', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('88', '5', '1', 'o2Y6NuClZ88NwJjSGBXNZ4I2svjg', '路彬', '13351966777', '', '32号楼2单元501', '', '1', '1429592381', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('89', '5', '1', 'o2Y6NuPp1IE-l7zg9SqJjUi8yLco', '梅玲', '15764684469', '', '17号楼', '', '1', '1429611135', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('90', '5', '1', 'o2Y6NuA_i5qoSmI-RDKjmh5W35Kg', '杨文革', '15636789116', '', '28#楼', '', '1', '1429941855', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('91', '5', '1', 'o2Y6NuA_i5qoSmI-RDKjmh5W35Kg', '杨文革', '15636789116', '', '28#楼', '', '1', '1429941899', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('92', '5', '13', 'o2Y6NuE9hcyJVimeaTzqBvmqDLQI', '橘子', '15094526226', '', '', '', '1', '1429963489', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('93', '5', '13', 'o2Y6NuLCD5TJyLtTemKyGKKKl6u4', '张铁刚', '13796530288', '', '', '黑河国际代购', '1', '1430005416', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('94', '5', '1', 'o2Y6NuLd1QsaUX-e2LbNCwa6rsJA', '王淑杰', '13946759116', '', '23号楼2单元401', '', '1', '1430046857', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('95', '5', '1', 'o2Y6NuBvn8N8vnpW0TiAnq8fky00', '任强', '13846844447', '', '22号楼1单元502', '', '1', '1430119043', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('96', '5', '1', 'o2Y6NuJkJ02Z_OkkU7D-wY2pX84k', '', '13946725548', '', '', '', '1', '1430138882', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('97', '5', '1', 'o2Y6NuLY86sDcSbqvdwv51ZAwSww', '', '15535352587', '', '13', '', '1', '1430168233', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('98', '5', '1', 'o2Y6NuLY86sDcSbqvdwv51ZAwSww', '', '15535352587', '', '13', '', '1', '1430168330', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('99', '5', '1', 'o2Y6NuLY86sDcSbqvdwv51ZAwSww', '', '15535352587', '', '13', '', '1', '1430168334', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('100', '5', '1', 'o2Y6NuARB7aEizn97vmMYPqNRU1g', '高小峰', '15946602233', '', '', '', '1', '1430200968', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('101', '5', '1', 'o2Y6NuLWS5Hh25w57U_uAcvudDOI', '', '13904683439', '', '', '', '1', '1430425635', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('102', '5', '1', 'o2Y6NuKfjDv0rWCn5o9C8a6k311g', '王文涛', '13804622512', '', '', '', '1', '1430705933', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('103', '5', '1', 'we7_iShl7x3E87a8Nh836XEEL4836', '张铁峰', '18104685399', '', '', '', '1', '1430789133', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('104', '5', '1', 'o2Y6NuKK0Dj3NdNfsKteFMz-_IGA', '于秋菊', '15636768188', '', '', '', '1', '1431054172', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('105', '5', '105', 'o2Y6NuGLorcb5x72nO94yRJ7Vhi8', '马立秋', '18767850789', '', '8号楼109室', '', '1', '1431125625', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('106', '5', '105', 'o2Y6NuGLorcb5x72nO94yRJ7Vhi8', '马立秋', '18767850789', '', '8号楼109室', '', '1', '1431125760', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('107', '5', '105', 'o2Y6NuGLorcb5x72nO94yRJ7Vhi8', '马立秋', '18767850789', '', '8号楼109室', '', '1', '1431125766', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('108', '5', '14', 'o2Y6NuNH2KUbFyq-c72xs3tV9-z0', '吕长杰', '13846832154', '', '', '', '1', '1431140347', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('109', '5', '1', 'o2Y6NuGVTo3tuvAZsWGVEX62TODg', '测试', '13512345678', '', '1号楼', '测试', '1', '1431508151', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('110', '5', '1', 'o2Y6NuPk4cW4c4-7jA9SXcy8nKT4', '郑双名', '18600030018', '', '1单元1楼101', '', '1', '1431572706', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('111', '5', '1', 'o2Y6NuIghJAiCOFtyBbtikL08pNo', '法国糊涂', '14678337890', '', '1号楼3单元501', '', '1', '1431586134', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('112', '5', '1', 'o2Y6NuIghJAiCOFtyBbtikL08pNo', '法国糊涂', '14678337890', '', '1号楼3单元501', '', '1', '1431586256', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('113', '5', '14', 'o2Y6NuCFZJEjhYUoDeFD_5sRFV48', '吕辉', '13613684231', '', '', '', '1', '1431588920', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('114', '5', '1', 'o2Y6NuFZApJFXstvxdfKDaebH1O8', '章程', '13814838438', '', '', '', '1', '1431675437', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('115', '5', '1', 'o2Y6NuAuTlTNWCHQshg8LzEZr7Tg', '张丹', '13945757757', '', '', '', '1', '1431925925', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('116', '5', '1', 'o2Y6NuAuTlTNWCHQshg8LzEZr7Tg', '张丹', '13945757757', '', '', '', '1', '1431925926', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('117', '5', '1', 'o2Y6NuAuTlTNWCHQshg8LzEZr7Tg', '张丹', '13945757757', '', '', '', '1', '1431925927', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('118', '5', '9', 'o2Y6NuKUqSCmbTKUCbp6Ds8hvhGg', '周小妹', '13846804401', '', '', '', '1', '1431976626', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('119', '5', '9', 'o2Y6NuB7rDyuWj2tjRAonS3xhJVA', '博博', '15592718666', '', '9号楼1单元0808', '', '1', '1432127168', '0');
INSERT INTO `ims_xcommunity_member` VALUES ('120', '5', '1', 'o2Y6NuNkLSzdDuZNORwGKH_GgISQ', '李涛', '13634683998', '', '', '', '1', '1432275542', '0');
INSERT INTO `ims_xcommunity_navextension` VALUES ('26', '5', '求助站', 'http://www.xiaoquzhineng.com/app/index.php?i=5&j=5&c=site&a=site&do=list&cid=3&wxref=mp.weixin.qq.co', 'fa fa-plus-square', '', '2', '#6d9eeb');
INSERT INTO `ims_xcommunity_phone` VALUES ('1', '0', '5', '新鹤物业', '04686198110');
INSERT INTO `ims_xcommunity_phone` VALUES ('2', '0', '5', '鹤岗市办事中心 ', '0468-340050');
INSERT INTO `ims_xcommunity_phone` VALUES ('3', '0', '5', '鹤岗市政府', '0468-3216069');
INSERT INTO `ims_xcommunity_phone` VALUES ('4', '0', '5', '工农区政府', '0468-3357660');
INSERT INTO `ims_xcommunity_phone` VALUES ('5', '0', '5', '鹤岗公安局', '0468-3340600-2087');
INSERT INTO `ims_xcommunity_phone` VALUES ('6', '0', '5', '鹤岗发改委', '0468-3216117');
INSERT INTO `ims_xcommunity_phone` VALUES ('7', '0', '5', '鹤岗城乡建设局', '0468-3415810');
INSERT INTO `ims_xcommunity_phone` VALUES ('8', '0', '5', '鹤岗卫生局', '0468-3853878');
INSERT INTO `ims_xcommunity_phone` VALUES ('9', '0', '5', '鹤岗交通运输局', '0468-3349368');
INSERT INTO `ims_xcommunity_phone` VALUES ('10', '0', '5', '鹤岗国税局', '0468-3358081');
INSERT INTO `ims_xcommunity_phone` VALUES ('11', '0', '5', '鹤岗地税局', '0468-3346065');
INSERT INTO `ims_xcommunity_phone` VALUES ('12', '0', '5', '鹤岗财政局', '0468-3450117');
INSERT INTO `ims_xcommunity_phone` VALUES ('13', '0', '5', '鹤岗民政局', '0468-3340967');
INSERT INTO `ims_xcommunity_phone` VALUES ('14', '0', '5', '鹤岗劳动局', '0468-3357038');
INSERT INTO `ims_xcommunity_phone` VALUES ('15', '0', '5', '鹤岗火车票预售', '0468-6821075');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('1', '5', '13900000001', '晓锋', '101', '89', '121', '23', '5', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('2', '5', '13900000002', '晓锋', '102', '90', '122', '24', '6', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('3', '5', '13900000003', '晓锋', '103', '91', '123', '25', '7', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('4', '5', '13900000004', '晓锋', '104', '92', '124', '26', '8', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('5', '5', '13900000005', '晓锋', '105', '93', '125', '27', '9', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('6', '5', '13900000006', '晓锋', '106', '94', '126', '28', '10', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('7', '5', '13900000007', '晓锋', '107', '95', '127', '29', '11', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('8', '5', '13900000008', '晓锋', '108', '96', '128', '30', '12', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('9', '5', '13900000009', '晓锋', '109', '97', '129', '31', '13', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('10', '5', '13900000010', '晓锋', '110', '98', '130', '32', '14', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('11', '5', '13900000011', '晓锋', '111', '99', '131', '33', '15', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('12', '5', '13900000012', '晓锋', '112', '100', '132', '34', '16', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('13', '5', '13900000013', '晓锋', '113', '101', '133', '35', '17', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('14', '5', '13900000014', '晓锋', '114', '102', '134', '36', '18', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('15', '5', '13900000015', '晓锋', '115', '103', '135', '37', '19', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('16', '5', '13900000016', '晓锋', '116', '104', '136', '38', '20', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('17', '5', '13900000017', '晓锋', '117', '105', '137', '39', '21', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('18', '5', '13900000018', '晓锋', '118', '106', '138', '40', '22', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('19', '5', '13900000019', '晓锋', '119', '107', '139', '41', '23', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('20', '5', '13900000020', '晓锋', '120', '108', '140', '42', '24', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('21', '5', '13900000021', '晓锋', '121', '109', '141', '43', '25', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('22', '5', '13900000022', '晓锋', '122', '110', '142', '44', '26', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('23', '5', '13900000023', '晓锋', '123', '111', '143', '45', '27', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('24', '5', '13900000024', '晓锋', '124', '112', '144', '46', '28', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('25', '5', '13900000025', '晓锋', '125', '113', '145', '47', '29', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('26', '5', '13900000026', '晓锋', '126', '114', '146', '48', '30', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('27', '5', '13900000027', '晓锋', '127', '115', '147', '49', '31', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('28', '5', '13900000028', '晓锋', '128', '116', '148', '50', '32', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('29', '5', '13900000029', '晓锋', '129', '117', '149', '51', '33', '1', '0');
INSERT INTO `ims_xcommunity_propertyfree` VALUES ('30', '5', '13900000030', '晓锋', '130', '118', '150', '52', '34', '1', '0');
INSERT INTO `ims_xcommunity_protime` VALUES ('1', '5', '2015-3', '1427709179');
INSERT INTO `ims_xcommunity_region` VALUES ('1', '5', '新鹤小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('2', '5', '成龙小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('3', '5', '昌盛小区', '李艳梅', '0468-6198119');
INSERT INTO `ims_xcommunity_region` VALUES ('4', '5', '绿洲小区', '李艳梅', '0468-6198119');
INSERT INTO `ims_xcommunity_region` VALUES ('5', '5', '叠翠小区', '姜春林', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('6', '5', '福临家园', '姜春林', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('7', '5', '二至九马路小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('8', '5', '光宇小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('9', '5', '北国名珠', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('10', '5', '德政小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('11', '5', '兴建小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('12', '5', '阳光家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('13', '5', '其它小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('14', '5', '德政小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('15', '5', '北国明珠小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('16', '5', '兴建小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('17', '5', '财富家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('18', '5', '九州新城', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('19', '5', '锦华公馆', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('20', '5', '经纬小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('21', '5', '黎明小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('22', '5', '永丰国际', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('23', '5', '兴东花园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('24', '5', '民族小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('25', '5', '丰泽小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('26', '5', '湖滨小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('27', '5', '新南小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('28', '5', '松鹤小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('29', '5', '先峰小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('30', '5', '弘景小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('31', '5', '育才小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('32', '5', '三江小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('33', '5', '胜利小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('34', '5', '光明小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('35', '5', '宇南小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('36', '5', '五指山花园小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('37', '5', '沿河北小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('38', '5', '沿河南小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('40', '5', '大陆南组团', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('41', '5', '兴安台建兴小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('42', '5', '跃进小区胜利组团', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('43', '5', '陆兴小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('44', '5', '书香苑', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('45', '5', '上层国际', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('46', '5', '紫霞小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('48', '5', '天水湖休闲花园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('49', '5', '站前小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('50', '5', '解放路小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('51', '5', '二道街综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('52', '5', '南四道街综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('53', '5', '财政小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('54', '5', '龙苑逸居', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('55', '5', '华苑', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('56', '5', '东永小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('57', '5', '兴山香苑小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('58', '5', '丰麟家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('59', '5', '金座', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('60', '5', '银座', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('61', '5', '红军小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('62', '5', '友谊小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('63', '5', '文化小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('64', '5', '兴东小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('65', '5', '荣阳花园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('66', '5', '向阳小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('67', '5', '崎峰家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('68', '5', '峰威家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('69', '5', '祥瑞花园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('70', '5', '隆祥家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('71', '5', '兴山休闲小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('72', '5', '南翼红河组团', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('73', '5', '大陆芙蓉花园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('74', '5', '园溪小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('75', '5', '大成家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('76', '5', '平安小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('77', '5', '新创小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('78', '5', '康悦居小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('79', '5', '兴泽花园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('80', '5', '振鹤小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('81', '5', '福泰家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('82', '5', '荣华小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('83', '5', '建安嘉苑', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('84', '5', '群利家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('87', '5', '林业小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('88', '5', '王府楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('89', '5', '振兴家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('90', '5', '金鹤组团', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('91', '5', '永青家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('92', '5', '大陆廉租房', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('93', '5', '麓林山小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('94', '5', '隆华小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('95', '5', '南岗小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('96', '5', '振兴花园小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('97', '5', '兴山关荣小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('98', '5', '天时家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('99', '5', '西山小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('100', '5', '桃园家居', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('101', '5', '文化博园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('102', '5', '鸿运家园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('103', '5', '祥和新居', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('104', '5', '兴安小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('105', '5', '龙宇花园', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('106', '5', '铁西路综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('107', '5', '西内环路综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('108', '5', '瓦厂综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('109', '5', '新一综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('110', '5', '文苑小区综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('111', '5', '群楼综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('112', '5', '自来水综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('113', '5', '北疆综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('114', '5', '特教中心', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('115', '5', '宏宇综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('116', '5', '沿湖居组团', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('117', '5', '祥泰名苑综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('118', '5', '新华建华小区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('119', '5', '新华A区', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('120', '5', '富力矿住宅', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('121', '5', '峻德南综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('122', '5', '三兴名苑综合楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('123', '5', '双鹤物流住宅楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_region` VALUES ('124', '5', '龙兴住宅楼', '小刘', '0468-6198110');
INSERT INTO `ims_xcommunity_report` VALUES ('1', 'o2Y6NuFZApJFXstvxdfKDaebH1O8', '5', '1', '1', '个人报修', '弄', '无', '1431675702', '0', '0', '0', '0', '', '', '0', 'N;', '0');
INSERT INTO `ims_xcommunity_service` VALUES ('4', '5', 'o2Y6NuOD32-GOkMcgvMaij1FMkEA', '1', '1', '快递', '1公斤内8元，3公斤内15元。', '收件地址：鹤岗微小区新鹤运营中心（新鹤物业斜对面）', '2', '', '2', '1428709610', '');
INSERT INTO `ims_xcommunity_service` VALUES ('6', '5', 'o2Y6NuFRg4ClBI85-VKZLZIE2BFg', '1', '2', '求租', '求租房子价格在500以内，地点不要太偏，基本家电都要有，因为是学生准备备考，如果在一中附近也可以合租，不过合租的必须是学生（女生）', '联系人:魏女士       电话:13199616622', '1', '', '0', '1430048872', 'N;');
INSERT INTO `ims_xcommunity_service` VALUES ('7', '5', 'o2Y6NuOD32-GOkMcgvMaij1FMkEA', '1', '2', '出租', '新鹤B区18号楼6楼70平米，正房，刚装修完。一中、四中、十七中、时代广场，交通便利。出租出卖。', '联系人：王先生   电话：13359588668', '1', '', '0', '1431566955', 'a:4:{i:0;s:1:\"7\";i:1;s:1:\"8\";i:2;s:1:\"9\";i:3;s:2:\"10\";}');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('1', '0', '家政服务', '', '0', '0', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('2', '0', '租赁服务', '', '0', '0', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('3', '0', '报修类型', '', '0', '0', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('4', '0', '投诉类型', '', '0', '0', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('5', '0', '二手交易类型', '', '0', '0', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('7', '5', '保姆月嫂', '', '1', '1', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('8', '5', '开锁换锁', '', '1', '2', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('9', '5', '快递', '', '1', '3', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('10', '5', '出租', '', '2', '0', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('11', '5', '求租', '', '2', '1', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('12', '5', '个人报修', '', '3', '1', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('13', '5', '公共报修', '', '3', '0', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('14', '5', '管理问题', '', '4', '0', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('15', '5', '设施问题', '', '4', '1', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('16', '5', '二手手机', '', '5', '0', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('17', '5', '二手家具', '', '5', '1', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('18', '5', '电脑数码', '', '5', '2', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('19', '5', '服饰箱包', '', '5', '3', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('20', '5', '母婴玩具', '', '5', '4', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('21', '5', '日常用品', '', '5', '6', '1');
INSERT INTO `ims_xcommunity_servicecategory` VALUES ('22', '5', '', '租房', '0', '0', '1');
INSERT INTO `ims_xcommunity_shopping_address` VALUES ('2', '5', 'o2Y6NuJoZntg-5xRVSZfXLjHBl8E', '候林', '15825249639', '黑龙江省', '鹤岗市', '工农区', '文化小区', '1', '0', '0');
INSERT INTO `ims_xcommunity_shopping_address` VALUES ('3', '5', 'o2Y6NuOD32-GOkMcgvMaij1FMkEA', '张铁峰', '18104685399', '黑龙江省', '鹤岗市', '工农区', '新鹤B区', '1', '0', '0');
INSERT INTO `ims_xcommunity_shopping_cart` VALUES ('7', '5', '6', '1', 'o2Y6NuIghJAiCOFtyBbtikL08pNo', '1', '0', '32.00');
INSERT INTO `ims_xcommunity_shopping_cart` VALUES ('5', '5', '3', '1', '', '1', '0', '0.01');
INSERT INTO `ims_xcommunity_shopping_cart` VALUES ('8', '5', '6', '1', '', '1', '0', '32.00');
INSERT INTO `ims_xcommunity_shopping_cart` VALUES ('9', '5', '2', '1', '', '1', '0', '125.00');
INSERT INTO `ims_xcommunity_shopping_cart` VALUES ('10', '5', '4', '1', '', '1', '0', '16.80');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('2', '5', '酒类', 'images/5/2015/05/xSSMdgtgOgZz99gaALm18tM1NFSOJ1.jpg', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('3', '5', '饮料', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('4', '5', '冲饮品', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('5', '5', '饼干糕点', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('6', '5', '肉干豆干', '', '0', '1', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('7', '5', '坚果炒货', '', '0', '1', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('8', '5', '糖果布丁', '', '0', '1', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('9', '5', '巧克力', '', '0', '1', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('10', '5', '蜜饯果脯', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('11', '5', '奶酪乳品', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('12', '5', '水果', '', '0', '1', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('13', '5', '家庭清洁', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('14', '5', '个人护理', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('15', '5', '纸品', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('16', '5', '调味品', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('17', '5', '方便速食', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('18', '5', '干货土特产', '', '0', '1', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('19', '5', '食用油', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('20', '5', '米面杂粮', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('21', '5', '海产制品', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('22', '5', '生肉/鲜肉', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('23', '5', '禽蛋', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_category` VALUES ('24', '5', '婴幼用品', '', '0', '0', '', '0', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_dispatch` VALUES ('2', '5', '同城配送', '1', '0', '0.00', '0.00', '1000', '1000', '1', '', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_express` VALUES ('1', '5', '同城配送', '0', '', '', '', 'a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}');
INSERT INTO `ims_xcommunity_shopping_goods` VALUES ('2', '5', '9', '0', '1', '1', '0', '魔吻纯可可脂手工DIY巧克力礼盒装', 'images/5/2015/05/uFHgNNbiCDG2NGHt8GWCVgVBPHWeCU.png', '盒', '', '<div id=\"description\" class=\"J_DetailSection tshop-psm tshop-psm-bdetaildes\" style=\"margin: 0px; padding: 0px; width: auto;\">\r\n<div class=\"content ke-post\" style=\"margin: 10px 0px 0px; padding: 0px; font-stretch: normal; font-size: 14px; line-height: 1.5; font-family: tahoma, arial, 宋体, sans-serif; width: 790px; overflow: hidden; height: auto;\"><img id=\"desc-module-2\" class=\"desc_anchor\" style=\"margin: 0px; padding: 0px; border: 0px; height: 1px; display: block; clear: both; vertical-align: top;\" src=\"http://a.tbcdn.cn/kissy/1.0.0/build/imglazyload/spaceball.gif\" alt=\"\" />\r\n<p style=\"margin: 1.12em 0px; padding: 0px; line-height: 1.4;\"><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img02.taobaocdn.com/imgextra/i2/99100076/TB2y8VUbFXXXXXsXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB2A_XObFXXXXbIXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB2JpJVbFXXXXXcXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /></p>\r\n<img id=\"desc-module-3\" class=\"desc_anchor\" style=\"margin: 0px; padding: 0px; border: 0px; height: 1px; display: block; clear: both; vertical-align: top;\" src=\"http://a.tbcdn.cn/kissy/1.0.0/build/imglazyload/spaceball.gif\" alt=\"\" />\r\n<p style=\"margin: 1.12em 0px; padding: 0px; line-height: 1.4;\"><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB2TRpRbFXXXXacXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img01.taobaocdn.com/imgextra/i1/99100076/TB2csBObFXXXXcbXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB2p6dJbFXXXXaqXpXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB2YqtQbFXXXXaWXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB2yA4LbFXXXXXKXpXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB20JBNbFXXXXcXXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB21eNQbFXXXXaYXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img01.taobaocdn.com/imgextra/i1/99100076/TB2kv6qbFXXXXaOXXXXXXXXXXXX_!!99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img04.taobaocdn.com/imgextra/i4/99100076/TB26la.bFXXXXbYXpXXXXXXXXXX_!!99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img01.taobaocdn.com/imgextra/i1/99100076/TB22byLcXXXXXa6XXXXXXXXXXXX_!!99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB2IWGOcXXXXXcrXXXXXXXXXXXX_!!99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img02.taobaocdn.com/imgextra/i2/99100076/TB2iLuMcXXXXXX.XpXXXXXXXXXX_!!99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /></p>\r\n<img id=\"desc-module-4\" class=\"desc_anchor\" style=\"margin: 0px; padding: 0px; border: 0px; height: 1px; display: block; clear: both; vertical-align: top;\" src=\"http://a.tbcdn.cn/kissy/1.0.0/build/imglazyload/spaceball.gif\" alt=\"\" />\r\n<p style=\"margin: 1.12em 0px; padding: 0px; line-height: 1.4;\"><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB2eU4EbFXXXXchXpXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB2btJNbFXXXXcSXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /></p>\r\n<img id=\"desc-module-5\" class=\"desc_anchor\" style=\"margin: 0px; padding: 0px; border: 0px; height: 1px; display: block; clear: both; vertical-align: top;\" src=\"http://a.tbcdn.cn/kissy/1.0.0/build/imglazyload/spaceball.gif\" alt=\"\" />\r\n<p style=\"margin: 1.12em 0px; padding: 0px; line-height: 1.4;\"><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img02.taobaocdn.com/imgextra/i2/99100076/TB2XmpNbFXXXXcoXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img02.taobaocdn.com/imgextra/i2/99100076/TB2wk4FbFXXXXb9XpXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /></p>\r\n<img id=\"desc-module-6\" class=\"desc_anchor\" style=\"margin: 0px; padding: 0px; border: 0px; height: 1px; display: block; clear: both; vertical-align: top;\" src=\"http://a.tbcdn.cn/kissy/1.0.0/build/imglazyload/spaceball.gif\" alt=\"\" />\r\n<p style=\"margin: 1.12em 0px; padding: 0px; line-height: 1.4;\"><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img04.taobaocdn.com/imgextra/i4/99100076/TB2DMtQbFXXXXaMXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img03.taobaocdn.com/imgextra/i3/99100076/TB2j48IbFXXXXaAXpXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: top; float: none;\" src=\"http://img02.taobaocdn.com/imgextra/i2/99100076/TB2lYFVbFXXXXXxXXXXXXXXXXXX-99100076.jpg_.webp\" alt=\"\" align=\"absmiddle\" /></p>\r\n</div>\r\n</div>\r\n<div id=\"J_DcBottomRightWrap\" style=\"margin: 0px; padding: 0px; width: 790px; position: relative; overflow: hidden;\">\r\n<div id=\"J_DcBottomRight\" class=\"J_DcAsyn tb-shop\" style=\"margin: 0px; padding: 0px;\">\r\n<div id=\"shop3321746264\" class=\"J_TModule\" style=\"margin: 0px; padding: 0px;\" data-widgetid=\"3321746264\" data-componentid=\"5003\" data-spm=\"110.0.5003-3321746264\" data-title=\"自定义内容区\">\r\n<div class=\"skin-box tb-module tshop-pbsm tshop-pbsm-shop-self-defined\" style=\"margin: 0px 0px 10px; padding: 0px; position: relative; z-index: 2;\"><s class=\"skin-box-tp\" style=\"margin: 0px; padding: 0px;\"></s>\r\n<div class=\"skin-box-bd clear-fix\" style=\"margin: 0px; padding: 0px; border: 0px solid #d5d5d5; color: #2953a6; line-height: 1.2; overflow: hidden; width: 790px; background: none 0px 0px no-repeat transparent;\">\r\n<p style=\"margin: 0px; padding: 0px; line-height: 1.4; font-family: tahoma, arial, 微软雅黑, sans-serif; font-size: 12px;\"><a style=\"margin: 0px; padding: 0px; text-decoration: none; color: #2953a6; outline: 0px;\" href=\"http://amovo.tmall.com/p/rd358113.htm?spm=a1z10.4.w5001-3277297774.5.D5sAXX&&&&&&&&&&&&&&&&&&&&&&&&&scene=taobao_shop\" target=\"_blank\"><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: middle; float: none;\" src=\"http://gdp.alicdn.com/imgextra/i1/99100076/T23Mb8XdpbXXXXXXXX_!!99100076.jpg\" alt=\"\" /></a><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: middle;\" src=\"http://gdp.alicdn.com/imgextra/i1/99100076/TB24.o5aXXXXXbyXXXXXXXXXXXX_!!99100076.jpg\" alt=\"\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: middle;\" src=\"http://gdp.alicdn.com/imgextra/i2/99100076/TB291flaVXXXXXCXXXXXXXXXXXX_!!99100076.jpg\" alt=\"\" usemap=\"#Map11\" border=\"0\" /><img style=\"margin: 0px; padding: 0px; border: 0px; vertical-align: middle;\" src=\"http://gdp.alicdn.com/imgextra/i1/99100076/TB2l9feaVXXXXadXpXXXXXXXXXX_!!99100076.gif\" alt=\"\" /><br style=\"margin: 0px; padding: 0px;\" />&nbsp;&nbsp;</p>\r\n<p>&nbsp;</p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>', '', '', '125.00', '180.00', '0.00', '0.00', '348', '0', '6', '', '1431346400', '250.00', '0', '0', '0', '0', 'a:2:{i:0;s:51:\"images/5/2015/05/GM2ta76IPPb3qq1bQMBftafoat2OAp.jpg\";i:1;s:51:\"images/5/2015/05/mnnjiVMf7sJTjN8OKOokRjNnrO9IEj.png\";}', '1', '1', '0', '1', '0', '1431345840', '1431345840', '17', '0', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_goods` VALUES ('3', '5', '4', '0', '1', '1', '0', '测试', '', '', '', '', '', '', '0.01', '0.00', '0.00', '0.00', '7', '0', '3', '', '1431347227', '0.00', '0', '0', '0', '0', 'a:0:{}', '0', '0', '0', '0', '0', '1431347160', '1431347160', '8', '0', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_goods` VALUES ('4', '5', '8', '0', '1', '1', '0', 'Q儿糍团干吃汤圆凤梨/绿茶/芝麻味糕点 ', 'images/5/2015/05/c08Lvul89xA0V9T099q9Q206qumq06.jpg', '', '', '<p style=\"margin: 0px 0px 5px; width: 0px; height: 0px; overflow: hidden;\">&nbsp;</p>\r\n<p><img style=\"margin: 0px; width: 750px; float: none;\" src=\"http://gd2.alicdn.com/imgextra/i2/484490364/TB2ft9maVXXXXXTXpXXXXXXXXXX_!!484490364.png\" alt=\"\" align=\"absMiddle\" /><img style=\"margin: 0px; width: 750px; float: none;\" src=\"http://gd3.alicdn.com/imgextra/i3/484490364/TB25c5taVXXXXXLXXXXXXXXXXXX_!!484490364.jpg\" alt=\"\" align=\"absMiddle\" /><img style=\"margin: 0px; width: 750px; float: none;\" src=\"http://gd4.alicdn.com/imgextra/i4/484490364/TB2DOapaVXXXXcfXXXXXXXXXXXX_!!484490364.jpg\" alt=\"\" align=\"absMiddle\" /><img style=\"margin: 0px; width: 750px; float: none;\" src=\"http://gd4.alicdn.com/imgextra/i4/484490364/TB21UioaVXXXXcfXXXXXXXXXXXX_!!484490364.jpg\" alt=\"\" align=\"absMiddle\" /><img style=\"margin: 0px; width: 750px; float: none;\" src=\"http://gd3.alicdn.com/imgextra/i3/484490364/TB2OhytaVXXXXXMXXXXXXXXXXXX_!!484490364.jpg\" alt=\"\" align=\"absMiddle\" /></p>\r\n<p>&nbsp;</p>', '', '', '16.80', '0.00', '0.00', '0.00', '310', '0', '8', '', '1431474259', '500.00', '0', '0', '0', '0', 'a:3:{i:0;s:51:\"images/5/2015/05/OPu8oo6Az8bfPWP8Tb6Eu3AAO88rY8.jpg\";i:1;s:51:\"images/5/2015/05/ujEEZZ9cBI9LyEnluehpZJCynC8B8Z.jpg\";i:2;s:51:\"images/5/2015/05/DyCc26V5596S6WVwCyr5snnyCcsx6y.jpg\";}', '1', '1', '0', '1', '0', '1431473760', '1431473760', '7', '0', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_goods` VALUES ('5', '5', '10', '0', '1', '1', '0', '夏威夷果  澳洲坚果炒货  特产零食 送开口器 248g', 'images/5/2015/05/f9PvE8NEm1NA9PZ3enilqnvQWZWwPN.jpg', '', '', '<p><img style=\"float: left;\" src=\"http://gd4.alicdn.com/imgextra/i4/362935307/TB2K9XObpXXXXbyXXXXXXXXXXXX_!!362935307.jpg\" alt=\"\" align=\"absmiddle\" /><img src=\"http://gd2.alicdn.com/imgextra/i2/362935307/TB2TiJKbpXXXXXYXpXXXXXXXXXX_!!362935307.jpg\" alt=\"\" align=\"absmiddle\" /><img src=\"http://gd4.alicdn.com/imgextra/i4/362935307/TB2adlKbpXXXXX1XpXXXXXXXXXX_!!362935307.jpg\" alt=\"\" align=\"absmiddle\" /><img src=\"http://gd3.alicdn.com/imgextra/i3/362935307/TB2iU4PbpXXXXaVXXXXXXXXXXXX_!!362935307.jpg\" alt=\"\" align=\"absmiddle\" /><img src=\"http://gd1.alicdn.com/imgextra/i1/362935307/TB29JFMbpXXXXcWXXXXXXXXXXXX_!!362935307.jpg\" alt=\"\" align=\"absmiddle\" /><img src=\"http://gd4.alicdn.com/imgextra/i4/362935307/TB2wtxJbpXXXXajXpXXXXXXXXXX_!!362935307.jpg\" alt=\"\" align=\"absmiddle\" /><img src=\"http://gd3.alicdn.com/imgextra/i3/362935307/TB2xWRNbpXXXXb2XXXXXXXXXXXX_!!362935307.jpg\" alt=\"\" align=\"absmiddle\" /><img src=\"http://gd2.alicdn.com/imgextra/i2/362935307/TB2EwXPbpXXXXbmXXXXXXXXXXXX_!!362935307.jpg\" alt=\"\" align=\"absmiddle\" /><img src=\"http://gd3.alicdn.com/imgextra/i3/362935307/TB2MIdIbpXXXXbrXpXXXXXXXXXX_!!362935307.jpg\" alt=\"\" align=\"absmiddle\" /></p>\r\n<p><img src=\"http://gd3.alicdn.com/imgextra/i3/362935307/TB2dsiIbFXXXXX9XpXXXXXXXXXX_!!362935307.jpg\" alt=\"\" align=\"absmiddle\" /><img src=\"http://gd4.alicdn.com/imgextra/i4/362935307/TB2.oqHbFXXXXaNXXXXXXXXXXXX_!!362935307.jpg\" alt=\"\" align=\"absmiddle\" /></p>', '', '', '26.80', '32.00', '0.00', '0.00', '198', '0', '6', '', '1431474815', '248.00', '0', '0', '0', '0', 'a:2:{i:0;s:51:\"images/5/2015/05/wLMis5RDKiZLKDZNdM9LNsSxnS7Llx.jpg\";i:1;s:51:\"images/5/2015/05/q000010b8Q0BQNB0c190C912P00e5Q.jpg\";}', '1', '1', '0', '1', '0', '1431474600', '1431474600', '6', '0', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_goods` VALUES ('6', '5', '4', '0', '1', '1', '0', '蜂蜜柠檬干片 柠檬茶 70g', 'images/5/2015/05/fvCh0cOwA3UU66WHh0vyTJ38hjU08t.png', '', '', '<p><img src=\"http://img04.taobaocdn.com/imgextra/i4/683867002/TB24JaXapXXXXboXpXXXXXXXXXX_!!683867002.jpg_.webp\" alt=\"alt\" width=\"790\" height=\"516\" /><img src=\"http://img01.taobaocdn.com/imgextra/i1/683867002/TB2KuLNbXXXXXaWXpXXXXXXXXXX_!!683867002.jpg_.webp\" alt=\"alt\" width=\"790\" height=\"938\" /><a href=\"http://mdetail.tmall.com/comboMeal.htm?spm=0.0.0.0.PHxdSZ&comboId=270020000017462993&mainItemId=20635271003\" target=\"_blank\"><img src=\"http://img04.taobaocdn.com/imgextra/i4/683867002/TB2GOcLbVXXXXX9XXXXXXXXXXXX_!!683867002.jpg_.webp\" alt=\"alt\" width=\"790\" height=\"825\" /></a><img src=\"http://img01.taobaocdn.com/imgextra/i1/683867002/TB2aV9.XVXXXXakXpXXXXXXXXXX-683867002.jpg_.webp\" alt=\"alt\" width=\"790\" height=\"1232\" /><img src=\"http://img04.taobaocdn.com/imgextra/i4/683867002/TB2qIK.XVXXXXciXXXXXXXXXXXX-683867002.jpg_.webp\" alt=\"alt\" width=\"790\" height=\"1243\" /><img src=\"http://img03.taobaocdn.com/imgextra/i3/683867002/TB23eO.XVXXXXaVXXXXXXXXXXXX-683867002.jpg_.webp\" alt=\"alt\" width=\"790\" height=\"729\" /><img src=\"http://img01.taobaocdn.com/imgextra/i1/683867002/TB2Gta.XVXXXXbYXXXXXXXXXXXX-683867002.jpg_.webp\" alt=\"alt\" width=\"790\" height=\"1512\" /></p>\r\n<p><img src=\"http://img04.taobaocdn.com/imgextra/i4/683867002/TB2pwm.XVXXXXXPXXXXXXXXXXXX-683867002.jpg_.webp\" alt=\"alt\" width=\"790\" height=\"632\" /></p>', '', '', '32.00', '38.00', '0.00', '0.00', '224', '0', '5', '', '1431475808', '70.00', '0', '0', '0', '0', 'a:2:{i:0;s:51:\"images/5/2015/05/u99T5sUv60Ckv58LxT656AVg5N675A.jpg\";i:1;s:51:\"images/5/2015/05/KXrux5R8xR4yL4UZkwPRQMz0LurMWR.png\";}', '1', '1', '0', '1', '0', '1431475080', '1431475080', '6', '0', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_shopping_goods_param` VALUES ('1', '2', '', '', '0');
INSERT INTO `ims_xcommunity_shopping_goods_param` VALUES ('2', '4', '生产许可证编号', '311724010209', '0');
INSERT INTO `ims_xcommunity_shopping_goods_param` VALUES ('3', '4', '产品标准号', 'GB/20977', '1');
INSERT INTO `ims_xcommunity_shopping_goods_param` VALUES ('4', '4', '厂址', '上海市松江区松胜路116号', '2');
INSERT INTO `ims_xcommunity_shopping_goods_param` VALUES ('5', '4', '保质期', '90', '3');
INSERT INTO `ims_xcommunity_shopping_order` VALUES ('25', '5', 'o2Y6NuJoZntg-5xRVSZfXLjHBl8E', '05113588', '125', '0', '1', '0', '0', '0', '', '2', '', '', '', '125.00', '0.00', '2', '1431346597', '0');
INSERT INTO `ims_xcommunity_shopping_order` VALUES ('26', '5', 'o2Y6NuJoZntg-5xRVSZfXLjHBl8E', '05118756', '0.02', '3', '1', '2', '0', '0', '', '2', '', '', '', '0.02', '0.00', '2', '1431347338', '0');
INSERT INTO `ims_xcommunity_shopping_order` VALUES ('27', '5', 'o2Y6NuOD32-GOkMcgvMaij1FMkEA', '05123665', '0.01', '0', '1', '0', '0', '0', '', '3', '', '', '', '0.01', '0.00', '2', '1431433730', '0');
INSERT INTO `ims_xcommunity_shopping_order` VALUES ('28', '5', 'o2Y6NuJoZntg-5xRVSZfXLjHBl8E', '05138024', '33.6', '0', '1', '0', '0', '0', '', '2', '', '', '', '33.60', '0.00', '2', '1431476213', '0');
INSERT INTO `ims_xcommunity_shopping_order` VALUES ('29', '5', 'o2Y6NuJoZntg-5xRVSZfXLjHBl8E', '05130844', '26.8', '1', '1', '2', '0', '0', '', '2', '', '', '', '26.80', '0.00', '2', '1431476312', '0');
INSERT INTO `ims_xcommunity_shopping_order` VALUES ('30', '5', 'o2Y6NuJoZntg-5xRVSZfXLjHBl8E', '05150916', '32', '0', '1', '0', '0', '0', '', '2', '', '', '', '32.00', '0.00', '2', '1431654819', '0');
INSERT INTO `ims_xcommunity_shopping_order_goods` VALUES ('25', '5', '25', '2', '125.00', '1', '0', '1431346597', null);
INSERT INTO `ims_xcommunity_shopping_order_goods` VALUES ('26', '5', '26', '3', '0.01', '2', '0', '1431347338', null);
INSERT INTO `ims_xcommunity_shopping_order_goods` VALUES ('27', '5', '27', '3', '0.01', '1', '0', '1431433730', null);
INSERT INTO `ims_xcommunity_shopping_order_goods` VALUES ('28', '5', '28', '4', '16.80', '2', '0', '1431476213', null);
INSERT INTO `ims_xcommunity_shopping_order_goods` VALUES ('29', '5', '29', '5', '26.80', '1', '0', '1431476312', null);
INSERT INTO `ims_xcommunity_shopping_order_goods` VALUES ('30', '5', '30', '6', '32.00', '1', '0', '1431654819', null);
INSERT INTO `ims_xcommunity_shopping_slide` VALUES ('1', '5', '干果', '', 'images/5/2015/05/EehiNNiL1G9GSLN7E7A9Sh337Y08ka.jpg', '5', '0', 'N;');
INSERT INTO `ims_xcommunity_shopping_slide` VALUES ('2', '5', '果汁', '', 'images/5/2015/05/h6NFThj6RP8fPZKRbMrPIZIPaKIR05.jpg', '4', '0', 'N;');
INSERT INTO `ims_xcommunity_shopping_slide` VALUES ('3', '5', '德国教士黑啤酒', '', 'images/5/2015/05/Bn433i23sIUIQNgqEB2sj32WqiMQ00.jpg', '3', '0', 'N;');
INSERT INTO `ims_xcommunity_shopping_slide` VALUES ('4', '5', '零食', '', 'images/5/2015/05/EuZ4N9p641NUGV4TB4zpn9IjpR64RN.jpg', '4', '1', 'a:120:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"6\";i:6;s:1:\"7\";i:7;s:1:\"8\";i:8;s:1:\"9\";i:9;s:2:\"10\";i:10;s:2:\"11\";i:11;s:2:\"12\";i:12;s:2:\"13\";i:13;s:2:\"14\";i:14;s:2:\"15\";i:15;s:2:\"16\";i:16;s:2:\"17\";i:17;s:2:\"18\";i:18;s:2:\"19\";i:19;s:2:\"20\";i:20;s:2:\"21\";i:21;s:2:\"22\";i:22;s:2:\"23\";i:23;s:2:\"24\";i:24;s:2:\"25\";i:25;s:2:\"26\";i:26;s:2:\"27\";i:27;s:2:\"28\";i:28;s:2:\"29\";i:29;s:2:\"30\";i:30;s:2:\"31\";i:31;s:2:\"32\";i:32;s:2:\"33\";i:33;s:2:\"34\";i:34;s:2:\"35\";i:35;s:2:\"36\";i:36;s:2:\"37\";i:37;s:2:\"38\";i:38;s:2:\"40\";i:39;s:2:\"41\";i:40;s:2:\"42\";i:41;s:2:\"43\";i:42;s:2:\"44\";i:43;s:2:\"45\";i:44;s:2:\"46\";i:45;s:2:\"48\";i:46;s:2:\"49\";i:47;s:2:\"50\";i:48;s:2:\"51\";i:49;s:2:\"52\";i:50;s:2:\"53\";i:51;s:2:\"54\";i:52;s:2:\"55\";i:53;s:2:\"56\";i:54;s:2:\"57\";i:55;s:2:\"58\";i:56;s:2:\"59\";i:57;s:2:\"60\";i:58;s:2:\"61\";i:59;s:2:\"62\";i:60;s:2:\"63\";i:61;s:2:\"64\";i:62;s:2:\"65\";i:63;s:2:\"66\";i:64;s:2:\"67\";i:65;s:2:\"68\";i:66;s:2:\"69\";i:67;s:2:\"70\";i:68;s:2:\"71\";i:69;s:2:\"72\";i:70;s:2:\"73\";i:71;s:2:\"74\";i:72');
INSERT INTO `ims_xcommunity_sjdp` VALUES ('1', '5', '1', '1', '超市', 'images/5/2015/03/it6R4MNAN5a4Aa45949n8ap5GotS6g.jpg', '姜永超', '18714623416', '', '0', '', '', '', '鹤岗市工农区文化路52号', '<p>\r\n	<span style=\"font-size:16px;\">酒水饮料</span><span style=\"font-size:16px;\">&nbsp;</span><span style=\"font-size:16px;\">休闲零食</span><span style=\"font-size:16px;\">&nbsp;</span><span style=\"font-size:16px;\">水产鲜肉</span><span style=\"font-size:16px;\">&nbsp;</span><span style=\"font-size:16px;\">粮油米面</span><span style=\"font-size:16px;\">&nbsp;</span><span style=\"font-size:16px;\">洗护清洁</span><span style=\"font-size:16px;\">&nbsp;</span><span style=\"font-size:16px;\">新鲜水果</span><span style=\"font-size:16px;\">&nb', '9:00-21:00', '1427710349');
INSERT INTO `ims_xcommunity_slide` VALUES ('3', '5', '10', '快送业务', 'images/5/2015/04/F7j5TDEJA5jHVJa5oLG77H77NV4jTv.jpg', 'http://www.xiaoquzhineng.com/app/index.php?i=5&c=entry&do=index&m=weisrc_businesscenter');
INSERT INTO `ims_xcommunity_slide` VALUES ('4', '5', '2', '俄罗斯纯野生椴树蜜蜂', 'images/5/2015/04/o12bs1AHeU6uaAf6bUfsbz10D1s2BP.png', 'http://www.xiaoquzhineng.com/app/index.php?i=5&j=5&c=entry&id=1&do=detail&m=wdl_shopping');
INSERT INTO `ims_xcommunity_slide` VALUES ('5', '5', '1', '商家入驻', 'images/5/2015/04/GaKpuj0luk6os0AwsOJ6600Splxjkk.jpg', 'http://www.xiaoquzhineng.com/app/index.php?i=5&c=entry&do=index&m=weisrc_businesscenter');
INSERT INTO `ims_xfcommunity_images` VALUES ('1', 'http://www.zhinengweixiaoqu.com/attachment//images/bl142870738779529.jpeg', '', '0');
INSERT INTO `ims_xfcommunity_images` VALUES ('2', 'http://www.zhinengweixiaoqu.com/attachment//images/bl142870747884432.jpeg', '', '0');
INSERT INTO `ims_xfcommunity_images` VALUES ('3', 'http://www.zhinengweixiaoqu.com/attachment//images/bl142870748563322.jpeg', '', '0');
INSERT INTO `ims_xfcommunity_images` VALUES ('4', 'http://www.zhinengweixiaoqu.com/attachment//images/bl142870794260729.jpeg', '', '0');
INSERT INTO `ims_xfcommunity_images` VALUES ('5', 'http://www.zhinengweixiaoqu.com/attachment//images/bl142870795084767.jpeg', '', '0');
INSERT INTO `ims_xfcommunity_images` VALUES ('6', 'http://www.zhinengweixiaoqu.com/attachment//images/bl142870796946966.jpeg', '', '0');
INSERT INTO `ims_xfcommunity_images` VALUES ('7', 'http://www.xiaoquzhineng.com/attachment//images/bl143156676822266.jpeg', null, '0');
INSERT INTO `ims_xfcommunity_images` VALUES ('8', 'http://www.xiaoquzhineng.com/attachment//images/bl143156678750759.jpeg', null, '0');
INSERT INTO `ims_xfcommunity_images` VALUES ('9', 'http://www.xiaoquzhineng.com/attachment//images/bl143156679820865.jpeg', null, '0');
INSERT INTO `ims_xfcommunity_images` VALUES ('10', 'http://www.xiaoquzhineng.com/attachment//images/bl143156680982427.jpeg', null, '0');
