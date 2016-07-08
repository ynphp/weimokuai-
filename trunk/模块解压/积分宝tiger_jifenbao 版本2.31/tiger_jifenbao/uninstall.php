<?php
$sql="
DROP TABLE IF EXISTS `ims_tiger_jifenbao_ad`;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_dianyuan`;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_goods`;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_hexiao`;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_member`;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_paylog`;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_poster`;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_record`;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_request`;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_share`;
DROP TABLE IF EXISTS `ims_tiger_jifenbao_tixianlog`;
";
pdo_run($sql);
