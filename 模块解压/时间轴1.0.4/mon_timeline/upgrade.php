<?php


/*
if (!pdo_fieldexists('mon_shake', 'follow_dlg_tip')) {
    pdo_query("ALTER TABLE " . tablename('mon_shake') . " ADD `follow_dlg_tip` varchar(500) ;");

}


if (!pdo_fieldexists('mon_shake', 'follow_btn_name')) {
    pdo_query("ALTER TABLE " . tablename('mon_shake') . " ADD  `follow_btn_name` varchar(20) ;");

}



if (!pdo_fieldexists('mon_shake', 'shake_day_limit')) {
    pdo_query("ALTER TABLE " . tablename('mon_shake') . " ADD  `shake_day_limit` int(3) default 0 ;");

}


if (!pdo_fieldexists('mon_shake', 'total_limit')) {
    pdo_query("ALTER TABLE " . tablename('mon_shake') . " ADD  `total_limit` int(3) default 0 ;");

}*/

if (!pdo_fieldexists('mon_timeline_item', 'i_time')) {
    pdo_query("ALTER TABLE " . tablename('mon_timeline_item') . " ADD `i_time` int(10);");
}



if (!pdo_fieldexists('mon_timeline_item', 'text')) {
    pdo_query("ALTER TABLE " . tablename('mon_timeline_item') . " ADD `text` varchar(1000);");
}
