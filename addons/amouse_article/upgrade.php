<?php
/**
 */
$sql = "
CREATE TABLE IF NOT EXISTS `ims_fineness_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `musicurl` varchar(100) NOT NULL DEFAULT '' COMMENT '上传音乐',
  `content` mediumtext NOT NULL,
  `credit` varchar(255) DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类',
  `template` VARCHAR(300) NOT NULL DEFAULT '' COMMENT '内容模板目录',
  `templatefile` VARCHAR(300) NOT NULL DEFAULT '' COMMENT '分类模板名称',
  `bg_music_switch` varchar(1)  NOT NULL DEFAULT '1',
  `clickNum` int(10) unsigned NOT NULL  DEFAULT '0',
  `zanNum` int(10) unsigned NOT NULL  DEFAULT '0',
  `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '缩略图',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '简介',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `outLink` varchar(500)   DEFAULT '0' COMMENT '外链',
  `author` varchar(100)   DEFAULT '' COMMENT '作者',

  `type` varchar(10) NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `tel` varchar(15)  NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fineness_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分类图片',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述',
  `template` VARCHAR(300) NOT NULL DEFAULT '' COMMENT '分类模板目录',
  `templatefile` VARCHAR(300) NOT NULL DEFAULT '' COMMENT '分类模板名称',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wx_tuijian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '公众号名称',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '公众号名称',
  `guanzhuUrl` varchar(255) NOT NULL DEFAULT '' COMMENT '引导关注',
  `type` varchar(1)  NOT NULL DEFAULT '1',
  `clickNum` int(10) unsigned NOT NULL  DEFAULT '0',
  `ipclient` varchar(50) NOT NULL DEFAULT '' COMMENT 'ip',
  `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '缩略图',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `hot` int(1) NOT NULL COMMENT '是否热门 0默认 1热门',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fineness_adv_er` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '广告标题',
  `thumb` varchar(500) NOT NULL COMMENT '广告图片',
  `link` varchar(500) NOT NULL COMMENT '广告外链',
  `type` tinyint(1) unsigned NOT NULL COMMENT '0商品推广1推荐公众',
  `description` varchar(500) NOT NULL COMMENT '广告外链',
  `status` varchar(2) NOT NULL COMMENT '是否显示',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='随机广告';
";
pdo_query($sql);

if(!pdo_fieldexists('fineness_article', 'credit')) {
   pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `credit` varchar(255) DEFAULT '0' ;");
}
if(!pdo_fieldexists('fineness_article', 'type')) {
   pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `type` varchar(10) NOT NULL ;");
}
if(!pdo_fieldexists('fineness_article', 'kid')) {
   pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `kid` int(10) unsigned NOT NULL ;");
}
if(!pdo_fieldexists('fineness_article', 'rid')) {
   pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `rid` int(10) unsigned NOT NULL ;");
}
if(!pdo_fieldexists('fineness_article', 'tel')) {
    pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `tel` varchar(15)  NOT NULL ;");
}
if(!pdo_fieldexists('fineness_article_category', 'type')) {
    pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `type` varchar(10) NOT NULL ;");
}
if(!pdo_fieldexists('fineness_article_category', 'kid')) {
    pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `kid` int(10) unsigned NOT NULL ;");
}
if(!pdo_fieldexists('fineness_article_category', 'rid')) {
    pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `rid` int(10) unsigned NOT NULL ;");
}
if(!pdo_fieldexists('fineness_article', 'pcate')) {
  pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类' ;");
}
if(pdo_fieldexists('fineness_article', 'content')) {
    pdo_query("ALTER TABLE ".tablename('fineness_article')."  MODIFY COLUMN content mediumtext  ;");
}

if(!pdo_fieldexists('fineness_article', 'ccate')) {
 pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类' ;");
}
if(!pdo_fieldexists('fineness_article', 'template')) {
 pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `template` VARCHAR(300) NOT NULL DEFAULT '' COMMENT '内容模板目录' ;");
}
if(!pdo_fieldexists('fineness_article', 'templatefile')) {
    pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `templatefile` VARCHAR(300) NOT NULL DEFAULT '' COMMENT '内容模板目录' ;");
}
if(!pdo_fieldexists('fineness_article', 'description')) {
  pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `description` varchar(500) NOT NULL DEFAULT '' COMMENT '简介';");
}
if(!pdo_fieldexists('fineness_article', 'author')) {
    pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `author` varchar(100)   DEFAULT '' COMMENT '简介';");
}
if(!pdo_fieldexists('wx_tuijian', 'hot')) {
    pdo_query("ALTER TABLE ".tablename('wx_tuijian')." ADD `hot` int(1) DEFAULT '0' ;");
}
if(!pdo_fieldexists('fineness_sysset', 'isopen')) {
    pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `isopen` varchar(1) default '1' ;");
}
if(!pdo_fieldexists('fineness_sysset', 'title')) {
    pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `title` varchar(255) DEFAULT '' ;");
}
if(!pdo_fieldexists('fineness_sysset', 'footlogo')) {
    pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `footlogo` varchar(255) DEFAULT '' ;");
}
if(!pdo_fieldexists('fineness_adv', 'pid')) {
    pdo_query("ALTER TABLE ".tablename('fineness_adv')." ADD `pid` int(10) unsigned  DEFAULT '0' COMMENT '父ID';");
}
if(!pdo_fieldexists('fineness_article', 'zanNum')) {
    pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `zanNum` int(10) unsigned NOT NULL  DEFAULT '0';");
}