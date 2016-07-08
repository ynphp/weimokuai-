<?php

$installSql = <<<sql
CREATE TABLE IF NOT EXISTS `ims_hsh_tools_tm` (
  `id` INT UNSIGNED NULL AUTO_INCREMENT,
  `weid` INT UNSIGNED NULL,
  `title` VARCHAR(60) NOT NULL,
  `template_id` VARCHAR(50) NOT NULL,
  `url` VARCHAR(500) NOT NULL,
  `topcolor` VARCHAR(10) NOT NULL,
  `data` VARCHAR(800) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `ims_hsh_tools_tm` (`id`, `weid`, `title`, `template_id`, `url`, `topcolor`, `data`) VALUES
(1, 1, '通用新订单通知演示', 'fMEwMMty0lCMi5zFy-b447ZEIUmk9tRxJfazOD1tO6o', 'http://m.hshcs.com', '#ff9900', '{&quot;first&quot;:{&quot;color&quot;:&quot;#fe864b&quot;,&quot;tip&quot;:&quot;顶部提示语&quot;},&quot;tradeDateTime&quot;:{&quot;color&quot;:&quot;#000&quot;,&quot;tip&quot;:&quot;提交时间&quot;},&quot;orderType&quot;:{&quot;color&quot;:&quot;#000&quot;,&quot;tip&quot;:&quot;订单类型&quot;},&quot;customerInfo&quot;:{&quot;color&quot;:&quot;#000&quot;,&quot;tip&quot;:&quot;客户信息&quot;},&quot;orderItemName&quot;:{&quot;color&quot;:&quot;#000&quot;,&quot;tip&quot;:&quot;自定义提示&quot;},&quot;orderItemData&quot;:{&quot;color&quot;:&quot;#000&quot;,&quot;tip&quot;:&quot;自定义内容&quot;},&quot;remark&quot;:{&quot;color&quot;:&quot;#fe864b&quot;,&quot;tip&quot;:&quot;备注&quot;}}');
		
CREATE TABLE IF NOT EXISTS `ims_hsh_tools_tm_log` (
  `id` INT UNSIGNED NULL AUTO_INCREMENT,
  `weid` INT UNSIGNED NOT NULL,
  `template_id` VARCHAR(50) NOT NULL,
  `send_time` INT UNSIGNED NOT NULL,
  `send_data` TEXT NOT NULL,
  `send_state` INT NOT NULL,
  `error_data` VARCHAR(800) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hsh_tools_notice_setting` (
  `id` INT UNSIGNED NULL AUTO_INCREMENT,
  `weid` INT UNSIGNED NOT NULL,
  `title` VARCHAR(90) NOT NULL,
  `field_setting` TEXT NOT NULL,
  `options` TEXT NOT NULL,
  `template_name` VARCHAR(60) NOT NULL,
  `notice_list` VARCHAR(60) NOT NULL,
  `notice_option` TEXT NOT NULL,
  `message_script` VARCHAR(80) NOT NULL,
  `sms_template_id` VARCHAR(45) NOT NULL,
  `foot_info` TEXT NOT NULL,
  `success_hint` VARCHAR(300) NOT NULL,
  `opening_hour_begin` INT(3) NOT NULL,
  `opening_hour_end` INT(3) NOT NULL,
  `closing_hint` VARCHAR(300) NOT NULL,
  `opening_time` INT(11) NOT NULL,
  `pause_hint` VARCHAR(300) NOT NULL,
  `state` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `ims_hsh_tools_notice_setting` (`id`, `weid`, `title`, `field_setting`, `options`, `template_name`, `notice_list`, `notice_option`, `message_script`, `sms_template_id`, `foot_info`, `success_hint`, `opening_hour_begin`, `opening_hour_end`, `closing_hint`, `opening_time`, `pause_hint`, `state`) VALUES
(1, 1, '茶楼预约演示', '{&quot;name&quot;:{&quot;type&quot;:&quot;text&quot;,&quot;prompt&quot;:&quot;联系姓名&quot;,&quot;show&quot;:&quot;1&quot;,&quot;valid&quot;:&quot;empty&quot;,&quot;list_show&quot;:&quot;1&quot;},&quot;tel&quot;:{&quot;type&quot;:&quot;text&quot;,&quot;prompt&quot;:&quot;联系电话&quot;,&quot;show&quot;:&quot;1&quot;,&quot;valid&quot;:&quot;tel&quot;,&quot;list_show&quot;:&quot;1&quot;},&quot;booking_time&quot;:{&quot;type&quot;:&quot;datatime&quot;,&quot;prompt&quot;:&quot;预约时间&quot;,&quot;show&quot;:&quot;1&quot;},&quot;rome_type&quot;:{&quot;type&quot;:&quot;select&quot;,&quot;prompt&quot;:&quot;房间类型&quot;,&quot;options&quot;:{&quot;0&quot;:&quot;左厅大板（6-10）人&quot;,&quot;1&quot;:&quot;左厅茶桌（5-8）人&quot;,&quot;2&quot;:&quot;右厅小板（4-6）人&quot;,&quot;3&quot;:&quot;雪菊包房（6-10）人&quot;,&quot;4&quot;:&quot;大红袍包房（6-12）人&quot;,&quot;5&quot;:&quot;普洱包房（6-12）人&quot;,&quot;6&quot;:&quot;铁观音包房（6-12）人&quot;},&quot;list_show&quot;:&quot;1&quot;,&quot;default&quot;:&quot;2&quot;,&quot;show&quot;:&quot;1&quot;}}', '', '', '["0"]', '[{&quot;name&quot;:&quot;客服1&quot;,&quot;tel&quot;:&quot;13772096228&quot;,&quot;openid&quot;:&quot;od8tRt8f9metnpNx-lHiMzWSjTXE&quot;,&quot;type&quot;:&quot;2&quot;},{&quot;name&quot;:&quot;测试人员&quot;,&quot;tel&quot;:&quot;13772096228&quot;,&quot;openid&quot;:&quot;od8tRt2J8fp2QppgJcgSu2FLbblE&quot;,&quot;type&quot;:&quot;2&quot;}]', 'notice_default', '', '&lt;p&gt;&lt;span style=&quot;color: #333333; font-family: &#039;Helvetica Neue&#039;, Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px;&quot;&gt;茶艺会所是本地唯一一家以养生为目的、 专业功夫茶的茶艺休闲会所， 在这里你可以体会到远离城市的喧嚣， 消除一天忙碌奔波的疲&lt;/span&gt;&lt;/p&gt;', '恭喜您，预定消息发送成功，我们的工作人员稍后会跟您联系', 0, 24, '对不起，当前是非营业时间', 0, '对不起，当前暂停营业', 1);
		

CREATE TABLE IF NOT EXISTS `ims_hsh_tools_notice_order_list` (
  `id` INT UNSIGNED NULL AUTO_INCREMENT,
  `weid` INT UNSIGNED NOT NULL,
  `notice_id` INT UNSIGNED NOT NULL,
  `openid` VARCHAR(50) NOT NULL,
  `uid` VARCHAR(45) NOT NULL,
  `field_value` TEXT NOT NULL,
  `notice_list` VARCHAR(60) NOT NULL,
  `remark` VARCHAR(500) NOT NULL,
  `add_time` INT NOT NULL,
  `state` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		
CREATE TABLE IF NOT EXISTS `ims_hsh_tools_url_redirect` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` INT(11) NOT NULL,
  `title` varchar(180) NOT NULL,
  `go_url` varchar(500) NOT NULL,
  `back_url` varchar(500) NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `redirect_type` varchar(45) NOT NULL,
  `test_mode` int(10) unsigned NOT NULL DEFAULT '0',
  `param_name` varchar(45) NOT NULL,
  `arg_state` VARCHAR(60) NOT NULL DEFAULT '1',
  `state` INT(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

		
CREATE TABLE IF NOT EXISTS `ims_hsh_tools_interaction_time` (
  `id` INT UNSIGNED NULL AUTO_INCREMENT,
  `weid` INT UNSIGNED NOT NULL,
  `scene_id` INT UNSIGNED NOT NULL,
  `openid` VARCHAR(50) NOT NULL,
  `latitude` VARCHAR(20) NOT NULL,
  `longitude` VARCHAR(20) NOT NULL,
  `precision` VARCHAR(20) NOT NULL,
  `update_times` INT unsigned NOT NULL,
  `add_time` INT unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
sql;
$row = pdo_run($installSql);

$apiFileContent = <<<api
<?php
require 'addons/hsh_tools/do/update_interaction_time.do.php';
require 'api.php';
api;
file_put_contents('../api_hsh.php', $apiFileContent);

$redirectPath = '../r/';
if (!is_readable($redirectPath)) {
	is_file($redirectPath) or mkdir($redirectPath, 0700);
}
$redirectFileContetn = <<<redirect
<?php
require '../addons/hsh_tools/do/url_redirect.do.php';
redirect;
file_put_contents($redirectPath.'index.php', $redirectFileContetn);
