<?php 
global $_W;
$sql = "SELECT * FROM" .tablename('dk_yao');
$accounts = pdo_fetch($sql);
include $this->template('index');

?>