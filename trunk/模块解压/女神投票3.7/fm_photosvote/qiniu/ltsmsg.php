<?php

	include "appconfig.php";
	$username=htmlspecialchars($_GET['username']);
	$msg=htmlspecialchars($_GET['msg']);
	$time=date('Y-m-d H:i:s');
	mysql_query("insert into lts_msg(pic,msgtime,username,msg) values("."false,'".$time."','".$username."','".$msg.""."')");
	echo mysql_error();
	mysql_close($link);

?>