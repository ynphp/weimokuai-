<?php
/*1.41  to 1.42
$upgradeSql = <<<sql
ALTER TABLE `ims_hsh_tools_notice_setting` 
ADD COLUMN `message_script` VARCHAR(80) NOT NULL AFTER `notice_option`;
sql;
$row = pdo_run($upgradeSql);*/


/*1.3 to 1.41
$upgradeSql = <<<sql
ALTER TABLE `ims_hsh_tools_notice_setting` 
ADD COLUMN `sms_template_id` VARCHAR(45) NOT NULL AFTER `notice_option`;

ALTER TABLE `ims_hsh_tools_notice_order_list` 
ADD COLUMN `add_time` INT NOT NULL AFTER `remark`;
sql;
$row = pdo_run($upgradeSql);*/
/*  1.1 to 1.2
$upgradeSql = <<<sql
ALTER TABLE `ims_hsh_tools_url_redirect` 
ADD COLUMN `arg_state` VARCHAR(60) NOT NULL DEFAULT '1' AFTER `param_name`;
sql;
$row = pdo_run($upgradeSql);*/