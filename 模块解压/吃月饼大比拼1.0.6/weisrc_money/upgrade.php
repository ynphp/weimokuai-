<?php
defined('IN_IA') or exit('Access Denied');

if(!pdo_fieldexists('weisrc_money_reply', 'cover')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_money_reply')." ADD `cover` varchar(500) DEFAULT '';");
}

if(!pdo_fieldexists('weisrc_money_reply', 'showusernum')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_money_reply')." ADD `showusernum` int(11) DEFAULT '20';");
}

if(!pdo_fieldexists('weisrc_money_reply', 'isneedfollow')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_money_reply')." ADD `isneedfollow` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('weisrc_money_reply', 'btn_start')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_money_reply')." ADD `btn_start` varchar(500) DEFAULT '';");
}

if(!pdo_fieldexists('weisrc_money_reply', 'game_page_bg')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_money_reply')." ADD `game_page_bg` varchar(500) DEFAULT '';");
}

if(!pdo_fieldexists('weisrc_money_reply', 'result_page_bg')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_money_reply')." ADD `result_page_bg` varchar(500) DEFAULT '';");
}

if(!pdo_fieldexists('weisrc_money_reply', 'game_tile')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_money_reply')." ADD `game_tile` varchar(500) DEFAULT '';");
}

if(!pdo_fieldexists('weisrc_money_fans', 'isblack')) {
    pdo_query("ALTER TABLE ".tablename('weisrc_money_fans')." ADD `isblack` tinyint(1) DEFAULT '0';");
}
