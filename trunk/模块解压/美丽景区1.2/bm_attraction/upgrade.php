<?php
$sql = "
	CREATE TABLE IF NOT EXISTS `ims_bm_attraction_other` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `weid` int(11) NOT NULL,
	  `sort` int(11) NOT NULL,
	  `spottitle` varchar(30) NOT NULL,
	  `department_id` int(11) NOT NULL,
	  `spotdesc` varchar(100) NOT NULL,
	  `spotpic` varchar(200) NOT NULL,
	  `spotinfo` varchar(500) NOT NULL,
	  `spotrecord` varchar(200) NOT NULL,
	  `spotcolor` varchar(100) NOT NULL,
	  `spottime` varchar(100) NOT NULL,
	  `spotdistance` varchar(10) NOT NULL,		  
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);

if(!pdo_fieldexists('modules_bindings', 'eid')) {
	pdo_query("INSERT INTO ".tablename('modules_bindings')." (`eid`, `module`, `entry`, `call`, `title`, `do`, `state`, `direct`) VALUES (NULL, 'bm_attraction', 'menu', '', '其他重要景点', 'other', '', '0');");
}

if(!pdo_fieldexists('bm_attraction_reply', 'telephone')) {  
	pdo_query("ALTER TABLE ".tablename('bm_attraction_reply')." ADD `telephone` varchar(20) DEFAULT '';");
	pdo_query("ALTER TABLE ".tablename('bm_attraction_reply')." ADD `spoturl` varchar(100) DEFAULT '';");
}

if(!pdo_fieldexists('bm_attraction_reply', 'lng')) {  
	pdo_query("ALTER TABLE ".tablename('bm_attraction_reply')." ADD `lng` decimal(10,2) DEFAULT '0.00';");
	pdo_query("ALTER TABLE ".tablename('bm_attraction_reply')." ADD `lat` decimal(10,2) DEFAULT '0.00';");
}

if(!pdo_fieldexists('bm_attraction_classify', 'spotsmallpic')) {  
	pdo_query("ALTER TABLE ".tablename('bm_attraction_classify')." ADD `spotsmallpic` varchar(200) NOT NULL;");
}

if(!pdo_fieldexists('bm_attraction_other', 'spotsmallpic')) {  
	pdo_query("ALTER TABLE ".tablename('bm_attraction_other')." ADD `spotsmallpic` varchar(200) NOT NULL;");
}