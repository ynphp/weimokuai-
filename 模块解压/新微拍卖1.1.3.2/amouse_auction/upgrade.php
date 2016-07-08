<?php

if(!pdo_fieldexists('amouse_auction_record', 'howmuch')) {
   pdo_query("ALTER TABLE ".tablename('amouse_auction_record')." ADD `howmuch` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '竞拍次数' ;");
}

if(!pdo_fieldexists('amouse_auction_goods', 'now_price')) {
    pdo_query("ALTER TABLE ".tablename('amouse_auction_goods')." ADD `now_price` float(10,2) DEFAULT '0' COMMENT '一口价金额' ;");
}
if(!pdo_fieldexists('amouse_auction_record', 'isnohaggle')) {
    pdo_query("ALTER TABLE ".tablename('amouse_auction_record')." ADD `isnohaggle` int(1) DEFAULT '0' COMMENT '一口价金额' ;");
}
