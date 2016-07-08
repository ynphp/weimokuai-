<?php

if(!pdo_fieldexists('fwei_nearshop', 'outlink')) {
	pdo_query("ALTER TABLE ".tablename('fwei_nearshop')." ADD   `outlink` varchar(200) NOT NULL DEFAULT '';");
}

