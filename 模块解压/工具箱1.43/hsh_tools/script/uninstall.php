<?php

$uninstallSql = <<<uninstallSql
	DROP TABLE IF EXISTS `ims_hsh_tools_tm`;
	DROP TABLE IF EXISTS `ims_hsh_tools_tm_log`;
	DROP TABLE IF EXISTS `ims_hsh_tools_notice_setting`;
	DROP TABLE IF EXISTS `ims_hsh_tools_notice_order_list`;
	DROP TABLE IF EXISTS `ims_hsh_tools_url_redirect`;
	DROP TABLE IF EXISTS `ims_hsh_tools_interaction_time`;
uninstallSql;
$row = pdo_run($uninstallSql);
$files = array(
	"/api_hsh.php",
	"/r/index.php",
);
foreach($files as $file) {
	$a = IA_ROOT . $file;
	if (file_exists($a)) {
		$result = @unlink($a);
	}
}