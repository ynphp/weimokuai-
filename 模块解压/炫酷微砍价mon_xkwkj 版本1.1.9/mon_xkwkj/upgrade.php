<?php







/*
if(!pdo_fieldexists('mon_wkj_firend', 'kh_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `kh_price` float(10,2) ;");
}





if(!pdo_fieldexists('mon_wkj', 'p_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_url` varchar(500) ;");
}

if(!pdo_fieldexists('mon_wkj', 'copyright_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `copyright_url` varchar(500) ;");
}


if(!pdo_fieldexists('mon_wkj', 'hot_tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `hot_tel` varchar(50) ;");
}


if(!pdo_fieldexists('mon_wkj', 'p_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_intro` varchar(1000) ;");
}




if(!pdo_fieldexists('mon_wkj', 'kj_dialog_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `kj_dialog_tip` varchar(1000) ;");
}



if(!pdo_fieldexists('mon_wkj', 'rank_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `rank_tip` varchar(1000) ;");
}



if(!pdo_fieldexists('mon_wkj', 'u_fist_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `u_fist_tip` varchar(1000) ;");
}



if(!pdo_fieldexists('mon_wkj', 'u_already_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `u_already_tip` varchar(1000) ;");
}



if(!pdo_fieldexists('mon_wkj', 'fk_fist_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `fk_fist_tip` varchar(1000) ;");
}


if(!pdo_fieldexists('mon_wkj', 'fk_already_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `fk_already_tip` varchar(1000) ;");
}

if(!pdo_fieldexists('mon_wkj', 'kj_rule')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `kj_rule` varchar(1000) ;");
}


if(!pdo_fieldexists('mon_wkj', 'yf_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `yf_price` float(10,2) default 0 ;");
}

*/
if(!pdo_fieldexists('mon_xkwkj_firend', 'kname')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `kname` int(3) ;");
}



if(!pdo_fieldexists('mon_xkwkj_firend', 'ac')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `ac` varchar(50) ;");
}


if(!pdo_fieldexists('mon_xkwkj', 'kj_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `kj_intro` text ;");
}

if(!pdo_fieldexists('mon_xkwkj_order', 'outno')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `outno` varchar(200) ;");
}

if(!pdo_fieldexists('mon_xkwkj', 'kj_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `kj_intro` text ;");
}


if(!pdo_fieldexists('mon_xkwkj', 'kj_follow_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `kj_follow_enable` int(1) ;");
}

if(!pdo_fieldexists('mon_xkwkj', 'join_follow_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `join_follow_enable` int(1) ;");
}

if(!pdo_fieldexists('mon_xkwkj', 'follow_dlg_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `follow_dlg_tip` varchar(500) ;");
}

if(!pdo_fieldexists('mon_xkwkj', 'follow_btn_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `follow_btn_name` varchar(20) ;");
}


if(!pdo_fieldexists('mon_xkwkj', 'share_bg')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `share_bg` varchar(300) ;");
}

if(!pdo_fieldexists('mon_xkwkj', 'rank_num')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `rank_num` int(10) ;");
}

if(!pdo_fieldexists('mon_xkwkj', 'v_user')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `v_user` int(10);");
}

if(!pdo_fieldexists('mon_xkwkj', 'join_rank_num')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `join_rank_num` int(10) ;");
}

if(!pdo_fieldexists('mon_xkwkj', 'zt_address')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `zt_address` varchar(1000) ;");
}


if(!pdo_fieldexists('mon_xkwkj', 'one_kj_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `one_kj_enable` int(1) ;");
}

if(!pdo_fieldexists('mon_xkwkj', 'day_help_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `day_help_count` int(10) ;");
}

if(!pdo_fieldexists('mon_xkwkj', 'kj_follow_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `kj_follow_enable` int(1);");
}

