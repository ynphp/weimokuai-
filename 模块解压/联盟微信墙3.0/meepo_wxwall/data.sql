--
-- 表的结构 `ims_meepo_tu_comment`
--

CREATE TABLE IF NOT EXISTS `ims_meepo_tu_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  `bad` int(11) NOT NULL,
  `good` int(11) NOT NULL,
  `time_r` int(11) NOT NULL,
  `con` text NOT NULL,
  `color` varchar(100) NOT NULL,
  `nick` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ims_meepo_tu_data`
--

CREATE TABLE IF NOT EXISTS `ims_meepo_tu_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `class` tinyint(3) NOT NULL,
  `time` int(11) NOT NULL,
  `time_r` int(11) NOT NULL,
  `con` text NOT NULL,
  `com_count` int(11) NOT NULL,
  `good` int(11) NOT NULL,
  `color` varchar(100) NOT NULL,
  `class_name` varchar(250) NOT NULL,
  `top` int(11) DEFAULT '0',
  `click` int(11) DEFAULT '0',
  `nick` varchar(80) NOT NULL,
  `bad` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ims_meepo_tu_set`
--

CREATE TABLE IF NOT EXISTS `ims_meepo_tu_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(120) NOT NULL,
  `wx_name` varchar(80) NOT NULL,
  `wx_num` varchar(100) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_content` text NOT NULL,
  `share_img` varchar(420) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `ims_modules` (`name`, `type`, `title`, `version`, `ability`, `description`, `author`, `url`, `settings`, `subscribes`, `handles`, `isrulefields`, `issystem`, `issolution`, `target`, `iscard`) VALUES
('meepo_wxwall', 'services', '联盟微信墙', '3.0', '联盟微信墙', '联盟微信墙', 'meepo', 'http://bbs.012wz.com/', 0, 'a:1:{i:0;s:4:"text";}', 'a:2:{i:0;s:5:"image";i:1;s:4:"text";}', 1, 0, 0, 0, 0);

INSERT INTO `ims_modules_bindings` ( `module`, `entry`, `call`, `title`, `do`, `state`, `direct`) VALUES
('meepo_wxwall', 'cover', '', '微信墙入口', 'index', '', 0),
('meepo_wxwall', 'menu', 'getMenuTiles', '', '', '', 0);

INSERT INTO `ims_uni_account_modules` ( `uniacid`, `module`, `enabled`, `settings`) VALUES
( 1, 'meepo_wxwall', 1, '');

INSERT INTO `ims_uni_group` (`name`, `modules`, `templates`) VALUES
('体验套餐服务', 'a:3:{i:0;s:12:"wdl_shopping";i:1;s:10:"fwei_forms";i:2;s:12:"meepo_wxwall";}', 'N;');