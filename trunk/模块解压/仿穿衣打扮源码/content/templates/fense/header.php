<?php
/*
Template Name:粉色模板
Description:粉色清新模板 ……
Version:1.0
Author:蓝叶
Author Url:http://lanyes.org
Sidebar Amount:1
ForEmlog:5.1.2
*/
if(!defined('EMLOG_ROOT')) {exit('error!');}
require_once View::getView('module');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $site_title; ?></title>
<meta name="keywords" content="<?php echo $site_key; ?>" />
<meta name="description" content="<?php echo $site_description; ?>" />
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php echo BLOG_URL; ?>xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="<?php echo BLOG_URL; ?>wlwmanifest.xml" />
<link rel="alternate" type="application/rss+xml" title="RSS"  href="<?php echo BLOG_URL; ?>rss.php" />
<link href="<?php echo TEMPLATE_URL; ?>css/styles.css" rel="stylesheet" type="text/css" />
<link href="<?php echo BLOG_URL; ?>admin/editor/plugins/code/prettify.css" rel="stylesheet" type="text/css" />
<script src="<?php echo BLOG_URL; ?>admin/editor/plugins/code/prettify.js" type="text/javascript"></script>
<script src="<?php echo BLOG_URL; ?>include/lib/js/common_tpl.js" type="text/javascript"></script>
<?php doAction('index_head'); ?>
</head>
<body>
<div id="wrapper">
<div id="innerWrapper">
<!--头部区域开始-->
<div id="header">
<div id="innerHeader">
<div class="blog-header">
<div class="blog-logo"><a href="<?php echo BLOG_URL; ?>" title="<?php echo $blogname; ?>"><img src="<?php echo TEMPLATE_URL; ?>images/logo.gif" width="85" height="84" alt="<?php echo $blogname; ?>" /></a></div>
<h2><a href="<?php echo BLOG_URL; ?>"><?php echo $blogname; ?></a></h2>
<div class="blog-desc"><?php echo $bloginfo; ?></div>
</div>
<!--头部广告区域-->
<div id="bbsup"></div>
<!--头部广告区域结束-->
<div id="menu"><?php blog_navi();?></div>
</div>
</div>
<!--头部区域结束-->
<!--主体内容区开始-->
<div id="mainWrapper">