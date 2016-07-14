/*
Navicat MySQL Data Transfer

Source Server         : 文武 qdm111422278.my3w.com
Source Server Version : 50148
Source Host           : qdm111422278.my3w.com:3306
Source Database       : qdm111422278_db

Target Server Type    : MYSQL
Target Server Version : 50148
File Encoding         : 65001

Date: 2015-08-26 11:28:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_kang_h5egg_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_kang_h5egg_award`;
CREATE TABLE `ims_kang_h5egg_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `description` varchar(200) DEFAULT '' COMMENT '描述',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `prize` int(11) DEFAULT '0' COMMENT '奖品ID',
  `award_sn` varchar(50) DEFAULT '' COMMENT 'SN',
  `createtime` int(10) DEFAULT '0' COMMENT '中奖时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '使用时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '兑奖状态',
  `xuni` tinyint(1) DEFAULT '0' COMMENT '虚拟奖品标记',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=225 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_kang_h5egg_award
-- ----------------------------
INSERT INTO `ims_kang_h5egg_award` VALUES ('66', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'zW16cp5F2C1O9Z7F', '1440290125', '1440290125', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('67', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'NvV8cP971Hr18VP7', '1440290203', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('68', '2', '13', '7', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '三等奖', '良品铺子代金券', '3', '3', 'M6V0E6v1rnx6H551', '1440291874', '1440291874', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('69', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'Gdn78Z55dZHDd6eE', '1440292193', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('70', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '三等奖', '良品铺子代金券', '3', '3', 'hx5325SFsl28lK5S', '1440292222', '1440292222', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('71', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '三等奖', '良品铺子代金券', '3', '3', 'vmmZP3rsufymUAjF', '1440292228', '1440292228', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('72', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'NoBBQ3Q08OZ8xbCw', '1440295701', '1440295701', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('73', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'eooXMOx2mjH87dLk', '1440295719', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('74', '2', '13', '4', 'fromUser', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 's2377z6mWl7tc2U7', '1440298272', '1440298272', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('75', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '六等奖', '潮T', '6', '6', 'D82Cj7gkMnRR199g', '1440300582', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('76', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'hfLPl77FWlesyfmF', '1440300588', '1440300588', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('77', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '五等奖', '良品铺子代金券', '5', '5', 'WSv6XJ4Ma4F6Ux6U', '1440300833', '1440300833', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('78', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '六等奖', '潮T', '6', '6', 'a7FaZgZfgJ1PgA5P', '1440300850', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('79', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'u3h3e3liNnDSb3SH', '1440300924', '1440300924', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('80', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'tvi60BI6q6DKUIBj', '1440300957', '1440300957', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('81', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '二等奖', '瓶盖加湿器', '2', '2', 'pAa0HUxa4x9U3kkk', '1440301659', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('82', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'EeYSVZYl99cdczqL', '1440301753', '1440301753', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('83', '2', '13', '10', 'ozlbZs9AKHjyDRz1I0YIfwMDCoWk', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'E35kXULp15o2cjqz', '1440324938', '1440324938', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('84', '2', '13', '10', 'ozlbZs9AKHjyDRz1I0YIfwMDCoWk', '五等奖', '良品铺子代金券', '5', '5', 'Pm0M080ATbtt78W7', '1440325082', '1440325082', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('85', '2', '13', '10', 'ozlbZs9AKHjyDRz1I0YIfwMDCoWk', '三等奖', '良品铺子代金券', '3', '3', 'RbHBr78Br8GOu8rE', '1440325150', '1440325150', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('86', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'Izx5AX5K47E51o77', '1440325388', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('87', '2', '13', '7', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '五等奖', '良品铺子代金券', '5', '5', 'd15sZeJeEoe7cEin', '1440325401', '1440325401', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('88', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'buQDN8yQtUny8j08', '1440335549', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('89', '2', '13', '4', 'fromUser', '二等奖', '瓶盖加湿器', '2', '2', 'Ey3hwX1cwA1YyOZQ', '1440335713', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('90', '2', '13', '4', 'fromUser', '三等奖', '良品铺子代金券', '3', '3', 'vZj71wa8bvQpQ70z', '1440336104', '1440336104', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('91', '2', '13', '4', 'fromUser', '五等奖', '良品铺子代金券', '5', '5', 'QJE0DaG4tf4GgGC0', '1440336406', '1440336406', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('92', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'r4Pv4bTCPTOapE7a', '1440336540', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('93', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'RPjJ0LPZqOJpLUSj', '1440336630', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('94', '2', '13', '4', 'fromUser', '五等奖', '良品铺子代金券', '5', '5', 'p3OEOYCq2tHtyuqQ', '1440336835', '1440336835', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('95', '2', '13', '4', 'fromUser', '二等奖', '瓶盖加湿器', '2', '2', 'U6OpnNQ1Nx7xXQs9', '1440342953', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('96', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'vB1Zq1Cj6B8QuCU1', '1440343000', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('97', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'Gnm95zpp0BnvbNg2', '1440344486', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('98', '2', '13', '4', 'fromUser', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'yhp41jJsh771G7tp', '1440417812', '1440417812', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('99', '2', '13', '4', 'fromUser', '五等奖', '良品铺子代金券', '5', '5', 'TXmK3Ys3g3F7x2f3', '1440417868', '1440417868', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('100', '2', '13', '4', 'fromUser', '三等奖', '良品铺子代金券', '3', '3', 'JhmteMmhTte1T0G0', '1440417976', '1440417976', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('101', '2', '13', '4', 'fromUser', '三等奖', '良品铺子代金券', '3', '3', 'yP6oM7pzH56t11hm', '1440418067', '1440418067', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('102', '2', '13', '4', 'fromUser', '三等奖', '良品铺子代金券', '3', '3', 'o6VrVv1Rw5RIwsRo', '1440418132', '1440418132', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('103', '2', '13', '4', 'fromUser', '五等奖', '良品铺子代金券', '5', '5', 'Z3D2Vk31grLg2VrB', '1440418141', '1440418141', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('104', '2', '13', '4', 'fromUser', '五等奖', '良品铺子代金券', '5', '5', 'o7hVWgFpOmIZKiKo', '1440418151', '1440418151', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('105', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'a9959a8mHfy99hza', '1440418233', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('106', '2', '13', '4', 'fromUser', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'Uo6JfJ8N18LgJhUX', '1440418370', '1440418370', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('107', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'e1r8dDr0851nrR2Y', '1440418558', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('108', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'o9C899cl968B69hk', '1440420572', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('109', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'B3i302g306mGDudy', '1440420582', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('110', '2', '13', '4', 'fromUser', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'M8b6T7JC2dmJ97bd', '1440421183', '1440421183', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('111', '2', '13', '4', 'fromUser', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'ujvJBUu5AQT29Ub5', '1440421201', '1440421201', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('112', '2', '13', '4', 'fromUser', '三等奖', '良品铺子代金券', '3', '3', 'GErSsZ4NOBZOwIvR', '1440421360', '1440421360', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('113', '2', '13', '4', 'fromUser', '三等奖', '良品铺子代金券', '3', '3', 'E5k68xLCmM3ZxTTQ', '1440421926', '1440421926', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('114', '2', '13', '4', 'fromUser', '二等奖', '瓶盖加湿器', '2', '2', 'Xtl11CcSTyXjSCiu', '1440422639', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('115', '2', '13', '4', 'fromUser', '三等奖', '良品铺子代金券', '3', '3', 'dEeZCeKHtrM3hTcK', '1440422988', '1440422988', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('116', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'xZlZz0KRY4g6L39z', '1440425726', '1440425726', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('117', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '六等奖', '潮T', '6', '6', 'td0MlNmmazxKxhxk', '1440425887', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('118', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '五等奖', '良品铺子代金券', '5', '5', 'lywzz76v2DDuBWW8', '1440425892', '1440425892', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('119', '2', '13', '4', 'fromUser', '三等奖', '良品铺子代金券', '3', '3', 'XXkktCjT4PkOa614', '1440426708', '1440426708', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('120', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'zNyevK6ivWYF0V6O', '1440426712', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('121', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'X5p59M5VkRvPIvzU', '1440426733', '1440426733', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('122', '2', '13', '4', 'fromUser', '三等奖', '良品铺子代金券', '3', '3', 'wTF4kBKG41wL1404', '1440426772', '1440426772', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('123', '2', '13', '4', 'fromUser', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'bgmM1l899V9kmG3L', '1440426803', '1440426803', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('124', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '五等奖', '良品铺子代金券', '5', '5', 'SP6OPnb9UUN27oOO', '1440426824', '1440426824', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('125', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'Y7LlkzL2SjKEEYhs', '1440427126', '1440427126', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('126', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'kFFcuZYAoUu2rl6u', '1440427135', '1440427135', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('127', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '五等奖', '良品铺子代金券', '5', '5', 'mK0z70eE1IuV9A1D', '1440427143', '1440427143', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('128', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '五等奖', '良品铺子代金券', '5', '5', 'mj3RljJLWrwUVB9F', '1440427341', '1440427341', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('129', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'B3Z3CSR5O3Cj3jL2', '1440427352', '1440427352', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('130', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '三等奖', '良品铺子代金券', '3', '3', 'mY55mT1s9ht5YWSH', '1440427786', '1440427786', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('131', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '五等奖', '良品铺子代金券', '5', '5', 'YmBrJJz6B09EXRJ3', '1440427789', '1440427789', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('132', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'Nw0l79fAAl6ALwdF', '1440428299', '1440428299', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('133', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '六等奖', '潮T', '6', '6', 'u041Kh0f9FffFU9C', '1440428308', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('134', '2', '13', '4', 'fromUser', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'I6za9EzK3LuqCAA7', '1440434415', '1440434415', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('135', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'Y70j01xAlvuBuI9O', '1440434883', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('136', '2', '13', '4', 'fromUser', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'nigIEfxF3Xq0X0xo', '1440435578', '1440435578', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('137', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'EfpeT1nla932l2p9', '1440435583', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('138', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '五等奖', '良品铺子代金券', '5', '5', 'cZXSoms1L4mLr1Pr', '1440436118', '1440436118', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('139', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'oM3FnHH1NnxhXdDh', '1440436138', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('140', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '三等奖', '良品铺子代金券', '3', '3', 'EFbjF9oBJxXJYmZo', '1440436285', '1440436285', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('141', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '六等奖', '潮T', '6', '6', 'ztSyKwWcq0QeYRY2', '1440436289', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('142', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'Nl9Yt59BJ6W5469h', '1440436319', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('143', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'qtBf3LgtlJOn3fO1', '1440436340', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('144', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'chYIYmr87c8cfH8c', '1440436379', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('145', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'TWwrHhn8YRSWrAva', '1440436446', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('146', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'gCIE3i23p3uyiL43', '1440436462', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('147', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '三等奖', '良品铺子代金券', '3', '3', 'N7VBV1HlbHpu2X8h', '1440436613', '1440436613', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('148', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '五等奖', '良品铺子代金券', '5', '5', 'j7P570pSF00WPSHp', '1440436653', '1440436653', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('149', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '三等奖', '良品铺子代金券', '3', '3', 'Z04jgX2f6y60F396', '1440436664', '1440436664', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('150', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'cJ1P6NqYWjvN6vcW', '1440436674', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('151', '2', '13', '7', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'o5YuL6QN57nL6NTL', '1440454249', '1440454249', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('152', '2', '13', '7', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '五等奖', '良品铺子代金券', '5', '5', 'NxZ319RV11g9G79C', '1440454378', '1440454378', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('153', '2', '13', '7', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '六等奖', '潮T', '6', '6', 'MuopK13puK1Pz8pU', '1440454442', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('154', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '六等奖', '潮T', '6', '6', 'm7sY222vT0FZ4sfV', '1440455761', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('155', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'ZctWqt2HTTKWH6lt', '1440455841', '1440455841', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('156', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '三等奖', '良品铺子代金券', '3', '3', 't826t8KGXqxRlXX2', '1440455864', '1440455864', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('157', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '六等奖', '潮T', '6', '6', 'QN7sJjPkgdhsVVOS', '1440456053', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('158', '2', '13', '4', 'fromUser', '二等奖', '瓶盖加湿器', '2', '2', 'lpKC2wwa1QHWCKw9', '1440464184', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('159', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'A1Z9m9Wrk2gcCKCg', '1440468730', '1440468730', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('160', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '六等奖', '潮T', '6', '6', 'bJT1z2zt9bjdtz0z', '1440468744', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('161', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'o35orKFu5wPx3UwR', '1440468885', '1440468885', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('162', '2', '13', '7', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '五等奖', '良品铺子代金券', '5', '5', 'w0qoYcZVRj0rBziy', '1440475758', '1440475758', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('163', '2', '13', '4', 'fromUser', '五等奖', '良品铺子代金券', '5', '5', 'x99Fs1lqA9SSZxrP', '1440476639', '1440476639', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('164', '2', '13', '4', 'fromUser', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'e38A8M4azw3i811V', '1440477252', '1440477252', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('165', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'vX4E27w6tzq7D49E', '1440477460', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('166', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '三等奖', '良品铺子代金券', '3', '3', 'K5UZOmUobrO3bAOP', '1440480350', '1440480350', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('167', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'LRWpvQzckVq6wzRa', '1440480384', '1440480384', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('168', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '六等奖', '潮T', '6', '6', 'TaR9QIj9ooJJAiIg', '1440480434', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('169', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'fUMUTOl2UrTP2RDM', '1440480462', '1440480462', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('170', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'hJVL56iAknj5566y', '1440483973', '1440483973', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('171', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'P7T4234WU72s4u12', '1440484121', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('172', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '六等奖', '潮T', '6', '6', 'qz00w6iLDzIPaRH9', '1440491309', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('173', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'r7gfRV1fll77LhmL', '1440491331', '1440491331', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('174', '2', '13', '2', 'ozlbZs2NgoTLCdatCGkjFtyVHXVM', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'Nr1Pk0X0hlOfo6gL', '1440493203', '1440493203', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('175', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'EM7y8oC78bhOZsYZ', '1440505543', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('176', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'QkfKxjkRJKBK3KBe', '1440505823', '1440505823', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('177', '2', '13', '14', 'dfadfag', '六等奖', '潮T', '6', '6', 'uW9mFw85GbEP5CKq', '1440508096', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('178', '2', '13', '14', 'dfadfag', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'qkF71D84d330zd10', '1440508202', '1440508202', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('179', '2', '13', '14', 'dfadfag', '五等奖', '良品铺子代金券', '5', '5', 'IbpZ6bBemEPgEgcU', '1440508333', '1440508333', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('180', '2', '13', '14', 'dfadfag', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'hEDX7d0wpDJL07No', '1440508373', '1440508373', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('181', '2', '13', '14', 'dfadfag', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'U1w701mh5M1RmdTz', '1440508412', '1440508412', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('182', '2', '13', '14', 'dfadfag', '二等奖', '瓶盖加湿器', '2', '2', 'D5jvmqQ7b5bTLySY', '1440508425', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('183', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'BIvJJlM1jMvcjFqu', '1440515173', '1440515173', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('184', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '二等奖', '瓶盖加湿器', '2', '2', 'XDoOY5SfDt5XytSP', '1440516381', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('185', '2', '13', '15', 'fromU点点滴滴', '二等奖', '瓶盖加湿器', '2', '2', 'qW2bAYX2p9vSd3W9', '1440516438', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('186', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'Sv0ZCHqXev20v3LI', '1440516443', '1440516443', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('187', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '二等奖', '瓶盖加湿器', '2', '2', 'qAj9OB9MFaBmbVb9', '1440516659', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('188', '2', '13', '15', 'fromU点点滴滴', '六等奖', '潮T', '6', '6', 'aj4WS6U4D6Dizjdi', '1440516957', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('189', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '二等奖', '瓶盖加湿器', '2', '2', 'k5FI5cnK5dT6Skk5', '1440517062', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('190', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '六等奖', '潮T', '6', '6', 'TxikyB3Rrk1wW9kk', '1440517108', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('191', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '六等奖', '潮T', '6', '6', 'Uwb0000IjKx5zacx', '1440517194', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('192', '2', '13', '15', 'fromU点点滴滴', '六等奖', '潮T', '6', '6', 'lkMOvgy97OiygzcI', '1440517797', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('193', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '二等奖', '瓶盖加湿器', '2', '2', 'DYzuiwSuUmY2wms2', '1440519419', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('194', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '六等奖', '潮T', '6', '6', 'gRvVJ3NHrvHr8638', '1440519436', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('195', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '三等奖', '良品铺子代金券', '3', '3', 'Z486046l08Ga0I42', '1440519451', '1440519451', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('196', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '六等奖', '潮T', '6', '6', 'zcrCAU7CFCfCrcCu', '1440519469', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('197', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'h7Isgulxl99uGllg', '1440519480', '1440519480', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('198', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'TwqWZWzi414K8GPP', '1440519711', '1440519711', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('199', '2', '13', '4', 'fromUser', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'BZUK8u61tFU4TURD', '1440520552', '1440520552', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('200', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'D6yEEfZy2akkYFei', '1440520623', '1440520623', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('201', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '二等奖', '瓶盖加湿器', '2', '2', 'R393bqJG9q6zG00S', '1440520688', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('202', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '六等奖', '潮T', '6', '6', 'GjXVVw0FlIwiX8Bx', '1440520736', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('203', '2', '13', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '三等奖', '良品铺子代金券', '3', '3', 'FXyz3OhXOkCTVtzA', '1440520752', '1440520752', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('204', '2', '13', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 't0yKL446cz0UBK9V', '1440538765', '1440538765', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('205', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'uwSiOKWep430004w', '1440538847', '1440538847', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('206', '2', '13', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '六等奖', '潮T', '6', '6', 'd7eqzq1Yy4tarTKr', '1440538884', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('207', '2', '13', '4', 'fromUser', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'uwYJdWIAAXVYYjnJ', '1440547565', '1440547565', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('208', '2', '13', '4', 'fromUser', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'MM904B54zRrz02VR', '1440547580', '1440547580', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('209', '2', '13', '4', 'fromUser', '四等奖', '吴亦凡粉丝见面会入场券', '4', '4', 'yhs1b3HZvguZi9Gs', '1440547614', '1440547614', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('210', '2', '13', '16', 'ozlbZs4VQ8Z0cvTWeEx6R1D91Nik', '六等奖', '潮T', '6', '6', 'GxFM24l319mYxk93', '1440551135', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('211', '2', '13', '16', 'ozlbZs4VQ8Z0cvTWeEx6R1D91Nik', '五等奖', '良品铺子代金券', '5', '5', 'uytt471JRTqr19R1', '1440551354', '1440551354', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('212', '2', '13', '17', 'ozlbZszROHBaNAQU9DmgDgWysh8w', '五等奖', '良品铺子代金券', '5', '5', 'LDo9AvpAK9o9hv4a', '1440551563', '1440551563', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('213', '2', '13', '17', 'ozlbZszROHBaNAQU9DmgDgWysh8w', '五等奖', '良品铺子代金券', '5', '5', 'E8ouOU85QSsBaUu2', '1440551580', '1440551580', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('214', '2', '13', '17', 'ozlbZszROHBaNAQU9DmgDgWysh8w', '六等奖', '潮T', '6', '6', 'e583nJ5Zxg9n3o5J', '1440551678', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('215', '2', '13', '18', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '五等奖', '良品铺子代金券', '5', '5', 'eS9PsFkik9VKgXRP', '1440554877', '1440554877', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('216', '2', '13', '18', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '六等奖', '潮T', '6', '6', 'dlBqVWf5relElb00', '1440554975', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('217', '2', '13', '19', 'ozlbZs6D8bbH8z6TXKWngdUwg7LE', '六等奖', '潮T', '6', '6', 'VtqvqVDcRGq9rK8q', '1440555260', '0', '1', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('218', '2', '13', '19', 'ozlbZs6D8bbH8z6TXKWngdUwg7LE', '五等奖', '良品铺子代金券', '5', '5', 'rZcv9czRd52HlARz', '1440555357', '1440555357', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('219', '2', '13', '19', 'ozlbZs6D8bbH8z6TXKWngdUwg7LE', '五等奖', '良品铺子代金券', '5', '5', 'Sr5RToIdDPoa5ACd', '1440555377', '1440555377', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('220', '2', '13', '20', 'ozlbZs0UHOInTuqJaNg177rx7VlI', '五等奖', '良品铺子代金券', '5', '5', 'Af5v944fMv1MPa1C', '1440555506', '1440555506', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('221', '2', '13', '20', 'ozlbZs0UHOInTuqJaNg177rx7VlI', '三等奖', '良品铺子代金券', '3', '3', 'i224kXGGJl5zW2W6', '1440555562', '1440555562', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('222', '2', '13', '21', 'ozlbZs1_W_ryYIi-MW_r2AQ1QDbE', '五等奖', '良品铺子代金券', '5', '5', 'yBw7qIYAba76q1AK', '1440555702', '1440555702', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('223', '2', '13', '21', 'ozlbZs1_W_ryYIi-MW_r2AQ1QDbE', '一等奖', '吴亦凡粉丝见面会入场券', '1', '1', 'Bzs0b84H5O50Zd0c', '1440555734', '1440555734', '2', '0');
INSERT INTO `ims_kang_h5egg_award` VALUES ('224', '2', '13', '4', 'fromUser', '六等奖', '潮T', '6', '6', 'k7IwTIReRIiOXZiw', '1440557008', '0', '1', '0');

-- ----------------------------
-- Table structure for `ims_kang_h5egg_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_kang_h5egg_data`;
CREATE TABLE `ims_kang_h5egg_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=197 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_kang_h5egg_data
-- ----------------------------
INSERT INTO `ims_kang_h5egg_data` VALUES ('1', '13', '2', 'ozlbZs75ACd3J8R090bprA2w665U', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440260919', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('2', '13', '2', 'we7_I41Soan4No1eG4K08GGASqTgo', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.206.35', '1440260919', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('3', '13', '2', 'we7_AVYVUoAqQq4UD4Vg22KkoEE4z', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.206.27', '1440260943', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('4', '13', '2', 'we7_b1lCtkkk688tGT3gGNLN8zW4B', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '119.147.146.189', '1440261104', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('5', '13', '2', 'we7_x4x0zjPph6P6i5qO2z3XzPJ38', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.66.191', '1440261750', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('6', '13', '2', 'we7_PJHBtap6H1Wib21sd1jctT1i1', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '36.23.41.109', '1440266450', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('7', '13', '2', 'we7_I00Dm5GFJOPHd0O3PJm65JDRd', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.212.13', '1440266450', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('8', '13', '2', 'we7_dawHZvbt5JTYyVb1VVe0BfHbh', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.89.123', '1440283238', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('9', '13', '2', 'we7_ewww3bZA83963bzzAU0M39Wtw', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440283307', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('10', '13', '2', 'we7_DdZBdnJwsIRdKcBJDrJs75N4O', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.206.17', '1440283329', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('11', '13', '2', 'we7_os159s5Yy1y5GRi8mi5I8QsM9', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.66.191', '1440284044', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('12', '13', '2', 'we7_X3ejrxu733hrSF6XrP6ouFX94', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440285108', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('13', '13', '2', 'we7_Jp5695Kx5npnEOu989nPcp898', 'we7_X3ejrxu733hrSF6XrP6ouFX94', '', '', '112.67.230.116', '1440285865', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('14', '13', '2', 'we7_cF9s1Obd0b0qb3s0cFkgY3S3g', 'we7_X3ejrxu733hrSF6XrP6ouFX94', '', '', '101.226.33.219', '1440285865', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('15', '13', '2', 'we7_r5U8OBtj2lmJ755J8hoX6086o', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.226.66.178', '1440285991', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('16', '13', '2', 'we7_ZKHuMNnTkhscIttkUUKdite0s', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.226.89.117', '1440286041', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('17', '13', '2', 'we7_kpSMd9fsXxv005I2x9f0m0DfZ', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440286318', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('18', '13', '2', 'we7_z9QtsTRX7FgEneMRG3SEoxMG7', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.33.204', '1440286318', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('19', '13', '2', 'we7_zgZ2op1r2jLoII17t2zJ7LhIP', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.33.218', '1440286318', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('20', '13', '2', 'we7_b8P0tKH8333978b3BeQ9pGhg2', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.206.38', '1440286403', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('21', '13', '2', 'ozlbZs1Vh331hDMSABhDCUZgNbko', 'we7_X3ejrxu733hrSF6XrP6ouFX94', '', '', '112.67.230.116', '1440286688', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('22', '13', '2', 'we7_x72ZR278S32m7sR7RRQrXd1Ur', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.226.66.21', '1440286848', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('23', '13', '2', 'we7_Q0q0yshy337haP3HaNhsM3h3n', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.66.191', '1440287209', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('24', '13', '2', 'we7_ZeV4xtWJXzXoE58JLTfGwNffo', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.206.27', '1440289712', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('25', '13', '2', 'we7_oRp5y5939G8hRDxDYWAh7y8Yg', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.69.108', '1440290130', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('26', '13', '2', 'we7_pCK67hd7riKC0ZNbHwddP7200', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.163.187', '1440290130', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('27', '13', '2', 'we7_Fh7v6w1gvv676rab6vubWaw1w', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.51.229', '1440291103', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('28', '13', '2', 'we7_V3Q6xWOMkzffQT3M3bR7wZ357', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440291129', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('29', '13', '2', 'we7_Uu7W5EauluLa00G5u5u6u66C6', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.64.235.91', '1440291130', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('30', '13', '2', 'we7_ASMZ3qShxq5AG2Z3Qo0I2S1hC', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.214.152', '1440291163', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('31', '13', '2', 'ozlbZs1Vh331hDMSABhDCUZgNbko', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440291751', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('32', '13', '2', 'we7_fG1V5NVO5OoBq06I6sNG50z6O', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '180.153.214.152', '1440291751', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('33', '13', '2', 'we7_JhmMVOW68Vv88MbmM8MS88mMM', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440291762', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('34', '13', '2', 'we7_A6H04kPYu54j5YY4j49DrE54y', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440291778', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('35', '13', '2', 'we7_dN005Djd54wj7qHn0U84UO7Uc', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.66.21', '1440291937', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('36', '13', '2', 'we7_I2egwex3d2e32Je7dgmP73YFT', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.214.178', '1440292219', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('37', '13', '2', 'we7_sy3PFu8U838YuFoGCR8pV3zzv', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.226.33.221', '1440293088', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('38', '13', '2', 'we7_MpEP2EU2q421lhuKarsUZuLUp', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.64.235.86', '1440295108', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('39', '13', '2', 'we7_F4q24yTxT79Py2Qq7QPXS29ps', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '101.226.65.108', '1440295233', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('40', '13', '2', 'we7_h63TwPp6dj1Pf339nP31ND6Ei', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.64.235.248', '1440295255', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('41', '13', '2', 'we7_y6FFp88gn3E8N263L80LLf0p3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '36.23.41.109', '1440295255', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('42', '13', '2', 'we7_dnlhWB1X9pBxzLxh1pww1xN6I', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.212.13', '1440295350', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('43', '13', '2', 'we7_KjcY4oYyGjC393H24h3g3yhjT', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.65.193.16', '1440295350', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('44', '13', '2', 'we7_V0OV072dCzPD8a36ZAZDpP0oy', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.89.122', '1440295510', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('45', '13', '2', 'we7_iVIuow909fUwChTFECxVH0xVO', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440297431', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('46', '13', '2', 'we7_lDzXzI9dvt5yXR0dDxdCD0Dv0', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440297431', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('47', '13', '2', 'we7_bbP7SJPJ8607QsBaSqff88fpJ', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440297431', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('48', '13', '2', 'we7_l1ov35llIEK6LgmowE617L133', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.226.102.97', '1440297515', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('49', '13', '2', 'we7_QC8PQ0JiU6E68JDu56J6JF58i', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.64.235.253', '1440297583', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('50', '13', '2', 'we7_Hki9ikSK2fX1fOVGVaT0hZULs', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '140.240.53.108', '1440297761', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('51', '13', '2', 'we7_s3N998DD9669x39odUz9LDXrN', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '180.153.163.189', '1440297762', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('52', '13', '2', 'we7_JJ14E63SFMQMMJEMVCQ4jQMqm', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.226.66.178', '1440298201', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('53', '13', '2', 'we7_VNhKwSpTFtlrIKnSSTKswzllW', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '27.149.205.105', '1440298201', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('54', '13', '2', 'we7_VcMa0dGKlM6ZAmMMGBK0aMdRR', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.226.66.191', '1440298319', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('55', '13', '2', 'we7_Bad41Da1W41ya1aFQ31zfk1TY', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.226.66.175', '1440298389', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('56', '13', '2', 'we7_DmJwd00ZWm7MRW91817070seW', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.66.174', '1440298749', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('57', '13', '2', 'we7_dnxx3drPBdqm9Z1h13cAnH5an', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.89.116', '1440298941', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('58', '13', '2', 'we7_d9QWEe9E8GglJq196BQy9mM6k', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.163.208', '1440298970', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('59', '13', '2', 'we7_z30dXc3NCVyQ7313OJ733nQVV', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.38.205.202', '1440299389', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('60', '13', '2', 'we7_xYIUtsCjX8gQ4uZXGXxCx7xgQ', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.206.24', '1440299789', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('61', '13', '2', 'we7_r7Bj4IS2b22ZGY2g2beSw7WfB', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '180.153.206.16', '1440300047', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('62', '13', '2', 'we7_DC5qF837reYo38X98fcE888j9', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '111.28.3.82', '1440300056', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('63', '13', '2', 'we7_JDUuAKKKkzbbAagDTkRRVNebk', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '180.153.206.18', '1440300743', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('64', '13', '2', 'we7_f8Erxxkwzv3uK9xX23KX91021', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '180.153.206.37', '1440301889', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('65', '13', '2', 'we7_b4A9nak56pw3L557P5Zql43O6', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '211.100.51.211', '1440302752', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('66', '13', '2', 'we7_JMr9E5aD86m9rE8MN36D3hsHR', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '180.153.201.214', '1440303241', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('67', '13', '2', 'we7_zq86I9Q6pQa4csvcZ1EI68q66', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440303263', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('68', '13', '2', 'we7_rIM8Tmj4TTumy6rVtiRu6Suyr', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '180.153.214.181', '1440303263', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('69', '13', '2', 'we7_mO1WgLLU7uL0Uo9SIfuZ67NlS', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440303926', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('70', '13', '2', 'we7_zdp1s65718V5CVD562XSeu51x', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '180.153.201.79', '1440303926', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('71', '13', '2', 'we7_m5D1IvV627D3dd7e2d7dD72tx', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.226.33.221', '1440303972', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('72', '13', '2', 'we7_u9Z9p3QH5mcSSzrJCp59zrmjj', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440306198', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('73', '13', '2', 'we7_rjrGtTHvySRfMe3ZTcTg4rFYf', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.65.193.16', '1440306358', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('74', '13', '2', 'we7_R3lkd7kseajIFeFXxxy3TKt3g', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.214.190', '1440311925', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('75', '13', '2', 'we7_n86XHIyH94vERXR8HYh0eiLB9', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440311925', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('76', '13', '2', 'we7_oyx26vhgPjPx5z7R86dXXdPxP', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440315254', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('77', '13', '2', 'we7_YzZN5ErPd8ZHbjd8mMQd7E7jd', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440315256', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('78', '13', '2', 'we7_e5OHHP2pxR9pveE6p9JHpdrqb', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440315264', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('79', '13', '2', 'we7_oyx26vhgPjPx5z7R86dXXdPxP', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440316025', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('80', '13', '2', 'we7_P95xcX90990b8929Ob5c924o2', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.64.235.86', '1440316025', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('81', '13', '2', 'we7_nwklmVZEzyl4UVhvueKZvYuOY', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.64.235.89', '1440319595', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('82', '13', '2', 'we7_PEEs2P0e2eHeHeTz0EeRW8w8s', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440319652', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('83', '13', '2', 'we7_OVF6DT8WXZJf3HexgKLsPExhV', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440319679', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('84', '13', '2', 'we7_ZWwGw33cJbRyTBWy3j3gwC3Sb', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440319681', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('85', '13', '2', 'we7_oF054YrJRr0XkAHafiOYOJAk0', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.65.193.16', '1440319681', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('86', '13', '2', 'we7_NyOicCyiBoodFTYFOvDwyfifw', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '180.153.214.192', '1440319681', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('87', '13', '2', 'we7_p5QK1JJjQjNNqhizzQ007kKjK', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.226.66.175', '1440320488', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('88', '13', '2', 'we7_Q7SeEqq7q78L5298O05Sw0fcS', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440321816', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('89', '13', '2', 'we7_p3EiI78z44mj6IezPc2geS3Jc', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440321886', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('90', '13', '2', 'we7_Aze8ZiK3e3mQ8nNmoEexOxQ9j', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440322877', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('91', '13', '2', 'we7_hS2DLk5AUao1SSL77zknZlw42', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440322877', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('92', '13', '2', 'we7_sF03mU316TIfY09044tM85f9t', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '101.226.51.228', '1440322877', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('93', '13', '2', 'we7_Fn2cBN17TfnmccMNYbQrLXZMn', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440322897', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('94', '13', '2', 'we7_P26pnn2FZ2A2wPpHcsBP2OhcB', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.230.116', '1440322907', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('95', '13', '2', 'we7_zy710KH6YHWmMX7yWxyA0YXxr', 'ozlbZs9AKHjyDRz1I0YIfwMDCoWk', '', '', '101.226.66.193', '1440325078', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('96', '13', '2', 'we7_hS2DLk5AUao1SSL77zknZlw42', 'ozlbZs9AKHjyDRz1I0YIfwMDCoWk', '', '', '112.67.230.116', '1440325175', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('97', '13', '2', 'we7_a3HeeEwH2LWL2tJWWt3jxS33m', 'ozlbZs9AKHjyDRz1I0YIfwMDCoWk', '', '', '112.65.193.16', '1440325249', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('98', '13', '2', 'we7_bYwYXQYdgx6GVi6WWXTJ7RXy6', 'ozlbZs9AKHjyDRz1I0YIfwMDCoWk', '', '', '180.153.201.216', '1440325252', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('99', '13', '2', 'we7_P7hVMMC9b261039HCV2Hb6cUH', 'ozlbZs9AKHjyDRz1I0YIfwMDCoWk', '', '', '153.0.3.88', '1440330239', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('100', '13', '2', 'we7_q2rIaiDbcmw8Zg2DLWXde1lVG', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '153.0.3.88', '1440331234', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('101', '13', '2', 'we7_jaasSOsEboSjBMPE47wpEeqsp', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.65.106', '1440340768', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('102', '13', '2', 'we7_Lxmy65mxTR0xyr66H2cD6NFNR', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.89.123', '1440340822', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('103', '13', '2', 'we7_d2p5yWJ88K88w2FwvKF9jGf25', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440343414', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('104', '13', '2', 'we7_kwYhXZXaH8I4ZKt4Hj0ttIXhJ', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440343826', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('105', '13', '2', 'we7_X696Jn9lc6r6wwQJF4v9RqvJj', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440343849', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('106', '13', '2', 'we7_p761MICFxO1qAAAjmJZCPmX1d', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.64.235.249', '1440344783', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('107', '13', '2', 'we7_z4zqiQ1n1QAr19A8nranno6L2', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440344784', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('108', '13', '2', 'we7_ISw6b0y046g12aS29a00IYY3y', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.205.252', '1440344784', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('109', '13', '2', 'we7_iHxKkTt0945xAxzxz4kzE59xy', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.201.214', '1440344784', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('110', '13', '2', 'we7_z4zqiQ1n1QAr19A8nranno6L2', 'we7_kwYhXZXaH8I4ZKt4Hj0ttIXhJ', '', '', '112.67.230.116', '1440344845', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('111', '13', '2', 'we7_z4zqiQ1n1QAr19A8nranno6L2', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '112.67.230.116', '1440345090', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('112', '13', '2', 'we7_YzWGXxKoxgJ5jSNgjcX5sSJSq', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '112.65.193.14', '1440345090', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('113', '13', '2', 'we7_RG6T2DtLHa8Haf2TAZShuuu22', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.69.108', '1440345155', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('114', '13', '2', 'we7_kwYhXZXaH8I4ZKt4Hj0ttIXhJ', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '112.67.230.116', '1440345234', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('115', '13', '2', 'we7_hy6469W90g883yD9YdIiz8QK9', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '180.153.206.27', '1440345309', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('116', '13', '2', 'ozlbZs75ACd3J8R090bprA2w665U', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '112.67.230.116', '1440346537', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('117', '13', '2', 'we7_v1h595tBVj5Q393ebe5I33831', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '180.153.214.199', '1440348982', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('118', '13', '2', 'we7_O8q866z6666F76AUu66cW6uWO', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '180.153.161.55', '1440348982', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('119', '13', '2', 'we7_Pju53T101LqTP555XmJuQ03RQ', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '180.153.206.25', '1440349205', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('120', '13', '2', 'we7_eiWuwu1cUl8Pzjl5D5p8d7ulz', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '101.226.93.234', '1440349788', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('121', '13', '2', 'we7_nLVO3rJBovlkqqKf3RvnxFQVV', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.69.108', '1440349902', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('122', '13', '2', 'we7_v9OJaO14UYyYKNKxK993Ad1Bs', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '180.153.214.188', '1440349902', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('123', '13', '2', 'we7_TaECCanDeeu7zNaeCVjA7tCQc', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '112.67.230.116', '1440350631', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('124', '13', '2', 'we7_scj787cj7JAZ7Fpj3Jc3837DP', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.64.235.86', '1440350646', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('125', '13', '2', 'we7_TaECCanDeeu7zNaeCVjA7tCQc', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.230.116', '1440350646', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('126', '13', '2', 'we7_SX8Gx8KwXNKKz8557TPZxuxAk', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.64.235.87', '1440350666', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('127', '13', '2', 'we7_wTzMrT66B67ffa5h6u86a68OU', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '36.101.224.124', '1440381030', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('128', '13', '2', 'we7_DwY79tQ9Ut7ee9Q989j4ZQz8W', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '36.101.224.124', '1440382157', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('129', '13', '2', 'we7_Z46SGuU1j641s4JCL2BJ8H48z', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440389985', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('130', '13', '2', 'we7_Ka488NH4Z58zqZ5mnh4m8488w', 'we7_Z46SGuU1j641s4JCL2BJ8H48z', '', '', '211.100.51.211', '1440390003', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('131', '13', '2', 'we7_w9h317NvaL31cnc1070gVV7dN', 'we7_Z46SGuU1j641s4JCL2BJ8H48z', '', '', '112.67.231.108', '1440390052', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('132', '13', '2', 'we7_u7uIYkmgojIJtjyjwjjiLbymW', 'we7_Z46SGuU1j641s4JCL2BJ8H48z', '', '', '101.226.66.174', '1440390823', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('133', '13', '2', 'we7_fipS81vtrEZ8hI1pq81pHihxr', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440392336', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('134', '13', '2', 'we7_Xu79YZT777963l33u2X6Rx33Z', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.231.108', '1440392998', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('135', '13', '2', 'we7_tKqKrj5gh5wzJLjQG3VRJRGhl', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440393259', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('136', '13', '2', 'we7_VMfo2TfNtdtFi2qi4D82MA4TT', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.231.108', '1440397327', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('137', '13', '2', 'we7_eL6DT50j5uDcJ1S0cMzy6lxA0', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.231.108', '1440397579', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('138', '13', '2', 'we7_oF2ZuAA115FF5s21TE2DLd1eX', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440397590', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('139', '13', '2', 'we7_pKKZ50SNB108aGXk4b80HYx4x', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440397590', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('140', '13', '2', 'we7_Cvw22VsmB2ui77EX76eGv7v22', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440397590', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('141', '13', '2', 'we7_MHhAFkUFckVCycZQKqHC5ICAi', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440397590', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('142', '13', '2', 'we7_QQQhxlv9Q21QS5XQrR15hV152', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440397590', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('143', '13', '2', 'we7_qSEpP6va6oVA00wkv8szoB0wJ', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.65.193.16', '1440397591', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('144', '13', '2', 'we7_YhSUgXge1Ef0fPirrbfehuI1r', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440397648', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('145', '13', '2', 'we7_cBjJJtlumSJS9BB3bmJtxBC6s', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.64.235.86', '1440398401', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('146', '13', '2', 'we7_m4P8cnp7bsddsCJzdPvpvC7Cw', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '36.101.224.124', '1440401395', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('147', '13', '2', 'we7_o9gqTOKg4ttAbOosbB9S24K3s', 'we7_wTzMrT66B67ffa5h6u86a68OU', '', '', '112.65.193.16', '1440401866', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('148', '13', '2', 'we7_vH97czog6C76g7x7XHxX37Xa3', 'we7_wTzMrT66B67ffa5h6u86a68OU', '', '', '36.101.224.124', '1440401868', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('149', '13', '2', 'we7_EEW9v5dmlN5YtEW5YMz4r4DzV', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '36.101.224.124', '1440402270', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('150', '13', '2', 'we7_kdRDTWoR9TWpl3O9pXiDAZT33', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '101.226.33.203', '1440403087', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('151', '13', '2', 'we7_t8mM3LUdDXuwGmwMeU8AZ4F7u', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '36.101.224.124', '1440404571', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('152', '13', '2', 'we7_bQQOoa7wAg0a0Z7io7BGRl74g', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '36.101.224.124', '1440404581', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('153', '13', '2', 'we7_pEJ4EO6LXIsIwW4JrBCdsgLe3', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '36.101.224.124', '1440404630', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('154', '13', '2', 'we7_dId5YFgGUtfGFZc7FffEG7keG', 'we7_vH97czog6C76g7x7XHxX37Xa3', '', '', '36.101.224.124', '1440406234', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('155', '13', '2', 'we7_Or1RQ000774k9KoO7e00707Hq', 'we7_vH97czog6C76g7x7XHxX37Xa3', '', '', '112.65.193.16', '1440406235', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('156', '13', '2', 'we7_JJPa5pJO9E9a6P8aa63ou4l29', 'we7_vH97czog6C76g7x7XHxX37Xa3', '', '', '112.64.235.90', '1440407045', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('157', '13', '2', 'we7_tGx7fFOOwl6623NKO63onDCZd', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.231.108', '1440411749', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('158', '13', '2', 'we7_iPJiQOspui3jKiilqSiQJ3QpK', 'we7_vH97czog6C76g7x7XHxX37Xa3', '', '', '112.67.231.108', '1440413343', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('159', '13', '2', 'we7_l89dsD8Dds7D1LdF2S01Dsj1H', 'we7_vH97czog6C76g7x7XHxX37Xa3', '', '', '112.67.231.108', '1440413952', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('160', '13', '2', 'we7_Bi11rouGiGQ856RiuQaw311Zo', 'we7_vH97czog6C76g7x7XHxX37Xa3', '', '', '101.226.66.181', '1440414128', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('161', '13', '2', 'we7_Co9gbg779ZnaAp9cr1LcbPa6c', 'we7_vH97czog6C76g7x7XHxX37Xa3', '', '', '101.226.102.97', '1440414153', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('162', '13', '2', 'we7_b8MVYS0hBdVvYMB2ahsyN6EVW', 'we7_vH97czog6C76g7x7XHxX37Xa3', '', '', '112.67.231.108', '1440421744', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('163', '13', '2', 'we7_aGGAbc2Ou8mkzbG5yKV85uYZ2', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440425624', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('164', '13', '2', 'we7_dg72z2zo9029gC57SS6pH2zFH', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440425624', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('165', '13', '2', 'we7_x7O4iuRDrerL4ZlIelU4i4uLz', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '112.67.231.108', '1440425624', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('166', '13', '2', 'we7_ACDu2467T7stDsW5Z7sM72S4C', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '180.153.214.176', '1440426435', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('167', '13', '2', 'we7_IgGoJClcaJPNkjgPkJnJ0Lfg2', 'we7_vH97czog6C76g7x7XHxX37Xa3', '', '', '112.67.231.108', '1440455574', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('168', '13', '2', 'we7_BxT5U0CP2qVUf5vF5Hl2CuP2L', 'we7_vH97czog6C76g7x7XHxX37Xa3', '', '', '180.153.206.13', '1440455678', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('169', '13', '2', 'we7_IgGoJClcaJPNkjgPkJnJ0Lfg2', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '112.67.231.108', '1440455729', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('170', '13', '2', 'we7_kgXP0CxP1GXPp01gsPHPcLL1r', 'we7_vH97czog6C76g7x7XHxX37Xa3', '', '', '112.64.235.249', '1440456494', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('171', '13', '2', 'we7_WnoSLhfh3o0FSZYCChHOyh01i', 'we7_l89dsD8Dds7D1LdF2S01Dsj1H', '', '', '112.67.231.108', '1440475661', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('172', '13', '2', 'we7_e10Pi1hHVWpMNmZi1mzjmOZ73', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '49.230.119.217', '1440516742', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('173', '13', '2', 'we7_X1Fk2poFx4FfQHlOhxFH24uFF', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '112.64.235.91', '1440520682', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('174', '13', '2', 'we7_PHv88L9a5vJlkZ69lK55vvHa5', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '119.147.146.189', '1440520860', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('175', '13', '2', 'we7_PQy23tyqtt5Qg23d2yd3G35yL', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '112.65.193.16', '1440521497', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('176', '13', '2', 'we7_l2pPdpPLdpZipPR816Mp5PaIy', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '125.84.182.121', '1440549745', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('177', '13', '2', 'we7_zLlLuZa7Cmug7UuCiWy7Zliem', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '171.43.211.229', '1440552885', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('178', '13', '2', 'we7_DXQNQToNoNn3xNNEBqX3sOlIE', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '116.113.84.108', '1440552961', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('179', '13', '2', 'we7_y2kd88XslX2Z12iJjkdDKmkZy', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '171.43.211.229', '1440553249', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('180', '13', '2', 'we7_lty97v3SKmVy70xpzaA80vKNv', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '180.153.163.206', '1440553264', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('181', '13', '2', 'we7_nES3W19aalvdavBW1amVA39ww', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '171.43.211.229', '1440553439', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('182', '13', '2', 'we7_jt76Zm3knPlPk3kKNPB36KKOp', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '171.43.211.229', '1440553828', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('183', '13', '2', 'we7_iAtPac7lubcCBauUblcc887LC', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '112.65.193.16', '1440553925', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('184', '13', '2', 'we7_pDqBBVxi8y5Foxo18yHqq1Bhm', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '101.226.51.227', '1440553965', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('185', '13', '2', 'we7_Z0tZQQwWeQQEeT548HjeQbvZ8', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '101.226.66.173', '1440553972', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('186', '13', '2', 'we7_uf3ovz606A9A3mTVSaFaEFA5A', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '171.43.211.229', '1440554894', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('187', '13', '2', 'we7_pNMDEEy83NLavNeNUem5eEdaN', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '171.43.211.229', '1440554961', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('188', '13', '2', 'we7_pEff5QBoQFkv9op4io5M4VC3P', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '113.57.225.39', '1440555012', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('189', '13', '2', 'we7_mUEIiLgE3Fp0uLM7tUuJlFIqZ', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '171.43.211.229', '1440555218', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('190', '13', '2', 'we7_xrIexrgK1EJ52XS84zSF2181Z', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '171.43.211.229', '1440555439', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('191', '13', '2', 'we7_c93Y6uL4Ud4CLjm4Qom1z39l9', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '101.226.66.21', '1440555589', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('192', '13', '2', 'we7_FZaJsyt7zJu7DSw7SaJd5WuYJ', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '58.19.5.50', '1440555651', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('193', '13', '2', 'we7_lYnTTTLTenSiBbbTs01Vty5RS', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '101.226.33.219', '1440555820', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('194', '13', '2', 'ozlbZs1_W_ryYIi-MW_r2AQ1QDbE', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '58.19.5.50', '1440555953', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('195', '13', '2', 'we7_FYII07YZACy0HzGTtQyII2Gn0', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '171.43.211.229', '1440556222', '1');
INSERT INTO `ims_kang_h5egg_data` VALUES ('196', '13', '2', 'we7_YMF4PG4i4EibE4pMX49X9CdPl', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '101.226.66.173', '1440557029', '1');

-- ----------------------------
-- Table structure for `ims_kang_h5egg_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_kang_h5egg_fans`;
CREATE TABLE `ims_kang_h5egg_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码',
  `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称',
  `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业',
  `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `sharetime` int(10) DEFAULT '0' COMMENT '最后分享时间',
  `awardingid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑奖地址ID',
  `todaynum` int(11) DEFAULT '0' COMMENT '今日抽奖次数',
  `totalnum` int(11) DEFAULT '0' COMMENT '总共抽奖次数',
  `awardnum` int(11) DEFAULT '0' COMMENT '中奖次数',
  `last_time` int(10) DEFAULT '0',
  `zhongjiang` tinyint(1) DEFAULT '0' COMMENT '中奖标记',
  `xuni` tinyint(1) DEFAULT '0' COMMENT '虚拟用户标记',
  `createtime` int(10) DEFAULT '0' COMMENT '参与时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_kang_h5egg_fans
-- ----------------------------
INSERT INTO `ims_kang_h5egg_fans` VALUES ('85', '13', '2', '1', 'ozlbZs75ACd3J8R090bprA2w665U', '', '', '肿了', '13612341234', '', '', '', '0', '', '', '', '', '', '1', '1440516676', '0', '2', '25', '20', '1440518400', '1', '0', '1440436282');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('86', '13', '2', '7', 'ozlbZs8awlU6uPWk8fS1z67I5CBs', '', '', '我哦哦', '13612361254', '', '', '', '0', '', '', '', '', '', '0', '0', '0', '4', '4', '4', '1440432000', '1', '0', '1440454237');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('84', '13', '2', '3', 'ozlbZs1Vh331hDMSABhDCUZgNbko', '', '', '久久', '13985214725', '', '', '就霍建华', '0', '', '', '', '', '', '0', '0', '0', '2', '19', '18', '1440518400', '1', '0', '1440435575');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('83', '13', '2', '4', 'fromUser', '', '', '靠靠靠', '13814725896', '', '', '未登记', '0', '', '', '', '', '', '0', '0', '0', '5', '14', '12', '1440518400', '1', '0', '1440434450');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('87', '13', '2', '2', 'ozlbZs2NgoTLCdatCGkjFtyVHXVM', '', '', '', '', '', '', '', '0', '', '', '', '', '', '0', '0', '0', '1', '1', '1', '1440432000', '1', '0', '1440493193');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('88', '13', '2', '14', 'dfadfag', '', '', '', '', '', '', '', '0', '', '', '', '', '', '0', '0', '0', '6', '6', '6', '1440432000', '1', '0', '1440508084');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('89', '13', '2', '15', 'fromU点点滴滴', '', '', '抖动', '13612341234', '', '', '文档的', '0', '', '', '', '', '', '0', '0', '0', '3', '3', '3', '1440432000', '1', '0', '1440516406');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('90', '13', '2', '12', 'ozlbZswnrer5Spbhtni78xWyOq14', '', '', '张三', '13677604320', '', '', '健健康康可', '0', '', '', '', '', '', '1', '1440520575', '0', '9', '12', '12', '1440518400', '1', '0', '1440517052');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('91', '13', '2', '16', 'ozlbZs4VQ8Z0cvTWeEx6R1D91Nik', '', '', '彭宝琴', '15623231296', '', '', '中南国际城', '0', '', '', '', '', '', '0', '0', '0', '3', '3', '2', '1440518400', '1', '0', '1440551115');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('92', '13', '2', '17', 'ozlbZszROHBaNAQU9DmgDgWysh8w', '', '', '', '', '', '', '', '0', '', '', '', '', '', '0', '0', '0', '3', '3', '3', '1440518400', '1', '0', '1440551523');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('93', '13', '2', '18', 'ozlbZs2uOj2wusSJhn8tnOkx124Y', '', '', '刚回家', '15548861781', '', '', '规范', '0', '', '', '', '', '', '1', '1440554868', '0', '4', '4', '2', '1440518400', '1', '0', '1440554719');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('94', '13', '2', '19', 'ozlbZs6D8bbH8z6TXKWngdUwg7LE', '', '', '进来了', '15548861781', '', '', '可口可乐', '0', '', '', '', '', '', '0', '0', '0', '3', '3', '3', '1440518400', '1', '0', '1440555252');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('95', '13', '2', '20', 'ozlbZs0UHOInTuqJaNg177rx7VlI', '', '', '', '', '', '', '', '0', '', '', '', '', '', '0', '0', '0', '3', '3', '2', '1440518400', '1', '0', '1440555486');
INSERT INTO `ims_kang_h5egg_fans` VALUES ('96', '13', '2', '21', 'ozlbZs1_W_ryYIi-MW_r2AQ1QDbE', '', '', '兔兔', '15548861781', '', '', '未登记', '0', '', '', '', '', '', '0', '0', '0', '3', '3', '2', '1440518400', '1', '0', '1440555689');

-- ----------------------------
-- Table structure for `ims_kang_h5egg_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_kang_h5egg_prize`;
CREATE TABLE `ims_kang_h5egg_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `turntable` int(10) unsigned NOT NULL COMMENT '转盘类型',
  `prizetype` varchar(50) NOT NULL COMMENT '奖品类别',
  `prizename` varchar(50) NOT NULL COMMENT '奖品名称',
  `prizepro` double DEFAULT '0' COMMENT '奖品概率',
  `prizetotal` int(10) NOT NULL COMMENT '奖品数量',
  `prizedraw` int(10) NOT NULL COMMENT '中奖数量',
  `prizepic` varchar(255) NOT NULL COMMENT '奖品图片',
  `prizetxt` text NOT NULL COMMENT '奖品说明',
  `credit` int(10) NOT NULL COMMENT '积分',
  `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_kang_h5egg_prize
-- ----------------------------
INSERT INTO `ims_kang_h5egg_prize` VALUES ('1', '2', '13', '0', '一等奖', '吴亦凡粉丝见面会入场券', '50', '50', '23', 'images/2/2015/08/z94s54AtoK41B9LD2PSk5aMszTm4oM.jpg', '', '0', 'credit1');
INSERT INTO `ims_kang_h5egg_prize` VALUES ('2', '2', '13', '0', '二等奖', '瓶盖加湿器', '50', '100', '12', 'images/2/2015/08/Sww8UFFVRfIuwwVUdzIeIinpwDfwwp.jpg', '', '0', 'physical');
INSERT INTO `ims_kang_h5egg_prize` VALUES ('3', '2', '13', '0', '三等奖', '良品铺子代金券', '50', '500', '22', 'images/2/2015/08/ndVphVgt11tsa1VxTt8QtqNGZA8zXh.jpg', '', '10', 'credit2');
INSERT INTO `ims_kang_h5egg_prize` VALUES ('4', '2', '13', '0', '四等奖', '吴亦凡粉丝见面会入场券', '50', '50', '24', 'images/2/2015/08/lLzFXlZl29F2f05GQwJ5kq727WJXvF.jpg', '', '0', 'credit1');
INSERT INTO `ims_kang_h5egg_prize` VALUES ('5', '2', '13', '0', '五等奖', '良品铺子代金券', '50', '5000', '27', 'images/2/2015/08/ndVphVgt11tsa1VxTt8QtqNGZA8zXh.jpg', '', '0', 'credit2');
INSERT INTO `ims_kang_h5egg_prize` VALUES ('6', '2', '13', '0', '六等奖', '潮T', '50', '200', '50', 'images/2/2015/08/K5tH5hBUHfCuwHgFwvfLoqUHPvZqhv.jpg', '', '0', 'physical');
INSERT INTO `ims_kang_h5egg_prize` VALUES ('7', '2', '13', '1', '七等奖', '', '0', '0', '0', '', '', '0', 'physical');
INSERT INTO `ims_kang_h5egg_prize` VALUES ('8', '2', '13', '1', '八等奖', '', '0', '0', '0', '', '', '0', 'physical');
INSERT INTO `ims_kang_h5egg_prize` VALUES ('9', '2', '13', '1', '九等奖', '', '0', '0', '0', '', '', '0', 'physical');
INSERT INTO `ims_kang_h5egg_prize` VALUES ('10', '2', '13', '1', '十等奖', '', '0', '0', '0', '', '', '0', 'physical');
INSERT INTO `ims_kang_h5egg_prize` VALUES ('11', '2', '13', '1', '参与奖', '', '0', '0', '0', '', '', '0', 'physical');
INSERT INTO `ims_kang_h5egg_prize` VALUES ('12', '2', '13', '1', '幸运奖', '', '0', '0', '0', '', '', '0', 'physical');

-- ----------------------------
-- Table structure for `ims_kang_h5egg_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_kang_h5egg_reply`;
CREATE TABLE `ims_kang_h5egg_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `start_picurl` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `ticket_information` varchar(200) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `repeat_lottery_reply` varchar(50) DEFAULT '',
  `end_theme` varchar(50) DEFAULT '',
  `end_instruction` varchar(200) DEFAULT '',
  `end_picurl` varchar(200) DEFAULT '',
  `turntable` tinyint(1) DEFAULT '0',
  `turntablenum` tinyint(1) DEFAULT '6',
  `adpic` varchar(200) DEFAULT '',
  `adpicurl` varchar(200) DEFAULT '',
  `total_num` int(11) DEFAULT '0' COMMENT '总获奖人数(自动加)',
  `award_info` text NOT NULL,
  `probability` double DEFAULT '0',
  `award_times` int(11) DEFAULT '0' COMMENT '每人最多获奖次数',
  `number_times` int(11) DEFAULT '0' COMMENT '每人最多抽奖次数',
  `most_num_times` int(11) DEFAULT '0' COMMENT '每人每天最多抽奖次数',
  `credit_times` int(11) DEFAULT '0',
  `credittype` varchar(20) DEFAULT '' COMMENT '未中奖赠送积分类型',
  `credit_type` varchar(20) DEFAULT '' COMMENT '未中奖赠送积分类型',
  `credit1` int(11) DEFAULT '0',
  `credit2` int(11) DEFAULT '0',
  `sn_code` tinyint(4) DEFAULT '0',
  `sn_rename` varchar(20) DEFAULT '',
  `copyright` varchar(20) DEFAULT '',
  `show_num` tinyint(1) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0' COMMENT '浏览次数',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页滚动中奖人数显示',
  `fansnum` int(11) DEFAULT '0' COMMENT '参与人数',
  `createtime` int(10) DEFAULT '0',
  `share_acid` int(10) DEFAULT '0',
  `ticketinfo` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要',
  `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要',
  `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要',
  `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要',
  `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `homepictime` int(3) unsigned NOT NULL COMMENT '首页秒显图片显示时间',
  `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片',
  `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '抽奖次数选项 0活动设置次数1商户赠送次数',
  `opportunity_txt` text NOT NULL COMMENT '商户赠送参数说明',
  `bigwheelpic` varchar(225) NOT NULL COMMENT '转盘图',
  `bigwheelimg` varchar(225) NOT NULL COMMENT '指针图',
  `bigwheelimgan` varchar(225) NOT NULL COMMENT '九宫格按钮',
  `bigwheelimgbg` varchar(225) NOT NULL COMMENT '九宫格转动背景图',
  `prizeDeg` varchar(225) NOT NULL COMMENT '中奖角度设置',
  `lostDeg` varchar(225) NOT NULL COMMENT '未中奖角度设置',
  `showparameters` varchar(1000) NOT NULL COMMENT '显示界面参数：背景色、背景图以及文字色等',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_kang_h5egg_reply
-- ----------------------------
INSERT INTO `ims_kang_h5egg_reply` VALUES ('2', '13', '2', '幸运砸金蛋活动开始了!', '', '../addons/kang_h5egg/template/images/activity-lottery-start.jpg', '1', '兑奖请联系我们,电话: 13512341234', '1440259200', '1443109140', '亲，继续努力哦~~', '幸运砸金蛋活动已经结束了', '幸运砸金蛋活动已经结束了', '../addons/kang_h5egg/template/images/activity-lottery-end.jpg', '0', '6', 'images/2/2015/08/iWEuIRu15r116r2H7qz6weT4uz0Gee.png', '', '5900', '&lt;p&gt;&lt;span style=&quot;color: #333333; font-family: arial, &#039;Hiragino Sans GB&#039;, &#039;Microsoft Yahei&#039;, 微软雅黑, 宋体, 宋体, Tahoma, Arial, Helvetica, STHeiti; font-size: 14px; font-weight: bold; line-height: 20px; text-align: right;&quot;&gt;奖品详细介绍&lt;/span&gt;&lt;span style=&quot;color: #333333; font-family: arial, &#039;Hiragino Sans GB&#039;, &#039;Microsoft Yahei&#039;, 微软雅黑, 宋体, 宋体, Tahoma, Arial, Helvetica, STHeiti; font-size: 14px; font-weight: bold; line-height: 20px; text-align: right;&quot;&gt;奖品详细介绍&lt;/span&gt;&lt;/p&gt;', '0', '0', '100', '100', '5', 'credit1', 'credit1', '0', '0', '0', 'SN码', '', '1', '821', '50', '33', '1440556782', '2', '请输入详细资料，兑换奖品', '1', '1', '0', '0', '1', '0', '0', '0', '0', '0', '0', '1', '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位', '589', '6400', '10', '100', '1440556223', '0', 'images/2/2015/08/Kn2V6cN9v000g009NklVaxv1776k9N.png', '0', '', '../addons/kang_h5egg/template/images/activity-lottery-6.png', '../addons/kang_h5egg/template/images/activity-inner.png', '../addons/kang_h5egg/template/images/activity.png', '../addons/kang_h5egg/template/images/activity_bg.png', '1, 60, 120, 180, 240, 300', '30, 90, 150, 210, 270, 330', '');

-- ----------------------------
-- Table structure for `ims_kang_h5egg_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_kang_h5egg_share`;
CREATE TABLE `ims_kang_h5egg_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `acid` int(11) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(100) DEFAULT '',
  `share_txt` text NOT NULL COMMENT '参与活动规则',
  `share_imgurl` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图',
  `share_picurl` varchar(255) NOT NULL COMMENT '分享图片按钮',
  `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片',
  `sharenumtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分享赠送抽奖类型',
  `sharenum` int(11) DEFAULT '0' COMMENT '分享赠送抽奖基数',
  `most_share_times` int(11) DEFAULT '0' COMMENT '每人每天分享有效次数',
  `sharetype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分享赠送类型',
  `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语',
  `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语',
  `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_acid` (`acid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_kang_h5egg_share
-- ----------------------------
INSERT INTO `ims_kang_h5egg_share` VALUES ('1', '2', '13', '2', '我是第#参与人数#名玩转统一冰红茶砸金蛋的参与者，大家快来参加啊！', '', 'http://www.dwz.cn/1nnBO4', '&lt;p&gt;&lt;span style=&quot;color: #333333; font-family: arial, &#039;Hiragino Sans GB&#039;, &#039;Microsoft Yahei&#039;, 微软雅黑, 宋体, 宋体, Tahoma, Arial, Helvetica, STHeiti; font-size: 14px; font-weight: bold; line-height: 20px; text-align: right;&quot;&gt;参与规则&lt;/span&gt;&lt;span style=&quot;color: #333333; font-family: arial, &#039;Hiragino Sans GB&#039;, &#039;Microsoft Yahei&#039;, 微软雅黑, 宋体, 宋体, Tahoma, Arial, Helvetica, STHeiti; font-size: 14px; font-weight: bold; line-height: 20px; text-align: right;&quot;&gt;参与规则&lt;/span&gt;&lt;span style=&quot;color: #333333; font-family: arial, &#039;Hiragino Sans GB&#039;, &#039;Microsoft Yahei&#039;, 微软雅黑, 宋体, 宋体, Tahoma, Arial, Helvetica, STHeiti; font-size: 14px; font-weight: bold; line-height: 20px; text-align: right;&quot;&gt;参与规则&lt;/span&gt;&lt;/p&gt;', '', '../addons/kang_h5egg/template/images/share.png', '../addons/kang_h5egg/template/images/img_share.png', '2', '1', '1', '0', '分享成功，增加一次抽奖机会咯', '分享失败，再试一次吧', '不要取消啊');
