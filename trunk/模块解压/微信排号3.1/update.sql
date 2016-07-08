-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 06 月 25 日 13:20
-- 服务器版本: 5.5.36
-- PHP 版本: 5.3.28

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `weizan01`
--

-- --------------------------------------------------------



--
-- 表的结构 `ims_xwz_queue_data`
--

CREATE TABLE IF NOT EXISTS `ims_xwz_queue_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `typeid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `number` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `giveuptime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ims_xwz_queue_fans`
--

CREATE TABLE IF NOT EXISTS `ims_xwz_queue_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `headimgurl` varchar(255) DEFAULT '' COMMENT '头像',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 -1 黑名单 0 正常',
  `suc` int(11) DEFAULT '0' COMMENT '取号次数',
  `past` int(11) DEFAULT '0' COMMENT '过号次数',
  `cancel` int(11) DEFAULT '0' COMMENT '取消次数',
  `createtime` int(11) DEFAULT '0' COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ims_xwz_queue_manager`
--

CREATE TABLE IF NOT EXISTS `ims_xwz_queue_manager` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `username` varchar(100) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ims_xwz_queue_reply`
--

CREATE TABLE IF NOT EXISTS `ims_xwz_queue_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(200) DEFAULT '',
  `heading` varchar(255) DEFAULT '',
  `smallheading` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `followurl` varchar(255) DEFAULT '',
  `intro` text,
  `status` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `num` int(11) DEFAULT '0',
  `beforenum` int(11) DEFAULT '0',
  `screenbg` varchar(255) DEFAULT '',
  `qrcode` varchar(1000) DEFAULT '',
  `qrcodetype` tinyint(3) DEFAULT '0',
  `templateid` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `ims_xwz_queue_type`
--

CREATE TABLE IF NOT EXISTS `ims_xwz_queue_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `tag` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `num` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
