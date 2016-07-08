<?php
if(!pdo_fieldexists('superman_floor_winner', 'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD   `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superman_floor_winner', 'uid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD     `uid` int(4) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superman_floor_winner', 'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD    INDEX `indx_uniacid` (`uniacid`);");
}