<?php
	include "appconfig.php";
	$sql = "CREATE TABLE lts_msg (username text,msg text,msgtime datetime,pic tinyint(1),picname text)";
	mysql_query($sql);
	$sql = "CREATE TABLE lts_pic (picname text,picurl text)";
	mysql_query($sql);
      	echo "安装成功，请尽快删除本页面！";
  		echo mysql_error();
	mysql_close($link);    
?>