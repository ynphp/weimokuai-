<?php
$bucket = "tanghao123";														//替换为自己的buckeet
$qiniuurl = "7xico7.com2.z0.glb.qiniucdn.com";								//替换为自己的buckeet
$accessKey = 'J7FDt_5EAWHoedW1ARFb2-67wk3KPJwYpyi3ktgL';					//替换为自己的accessKey
$secretKey = 'tQCFErvkuRkU6ivYWU9d1LOwIN_vDEpt8-tSv1qd';					//替换为自己的secretKey

$dbname = "test";														//替换为自己的MySQL数据库名
$host = "localhost";                      								//MySQL数据库地址
$port = "3306";                                						//MySQL数据库端口
$user = "root";            											//MySQL数据库用户名
$pwd = "cwz77585200";     													//MySQL数据库密码


$link = @mysql_connect("{$host}:{$port}",$user,$pwd,true);
if(!$link) {
    die("Connect Server Failed: " . mysql_error());
}



if(!mysql_select_db($dbname,$link)) {
    die("Select Database Failed: " . mysql_error($link));
}
?>