<?php




if(!pdo_fieldexists('zhh_hong', 'prize_sharebtn_name')) {
	pdo_query("ALTER TABLE ".tablename('zhh_hong')." ADD `prize_sharebtn_name` varchar(50) ;");
}





if(!pdo_fieldexists('zhh_hong', 'prize_sharebtn_name')) {
    pdo_query("ALTER TABLE ".tablename('zhh_hong')." ADD `luck_sharebtn_name` varchar(50);");
}


if(!pdo_fieldexists('zhh_hong', 'day_play_count')) {
	pdo_query("ALTER TABLE ".tablename('zhh_hong')." ADD `day_play_count` int(3);");
}







