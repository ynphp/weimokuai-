<?php

if(!pdo_fieldexists('hx_cards_reply', 'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('hx_cards_reply')." ADD `sharenum` int(5) NOT NULL DEFAULT '0' COMMENT '最多分享数';");
}
if(!pdo_fieldexists('hx_cards_reply', 'isfollow')) {
	pdo_query("ALTER TABLE ".tablename('hx_cards_reply')." ADD `isfollow` int(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_cards_fans', 'todaysharenum')) {
	pdo_query("ALTER TABLE ".tablename('hx_cards_fans')." ADD `todaysharenum` int(10) unsigned NOT NULL DEFAULT '0';");
}

