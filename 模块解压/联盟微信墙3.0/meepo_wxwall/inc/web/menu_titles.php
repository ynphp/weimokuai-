<?php
$menus = array();
$menus[] = array(
	'title'=>'基本设置',
	'url'=>$this->createwebUrl('set'),
	'do'=>'set'
);

$menus[] = array(
	'title'=>'微信墙',
	'url'=>$this->createwebUrl('index'),
	'do'=>'index'
);
