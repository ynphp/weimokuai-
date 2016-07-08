<?php
 
if(!pdo_fieldexists('ewei_dream_oversee', 'fansid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_dream_oversee')." ADD `fansid` int(11) NOT NULL DEFAULT '0';");
}