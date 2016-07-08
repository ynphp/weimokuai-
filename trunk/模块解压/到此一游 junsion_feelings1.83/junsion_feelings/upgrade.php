<?php
if (!pdo_fieldexists('junsion_feelings_rule', 'checked1')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_rule') . " ADD  `checked1` tinyint(1) DEFAULT 0;");
}
if (!pdo_fieldexists('junsion_feelings_rule', 'checked2')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_rule') . " ADD  `checked2` tinyint(1) DEFAULT 0;");
}
if (!pdo_fieldexists('junsion_feelings_record', 'checked')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_record') . " ADD  `checked` tinyint(1) DEFAULT 0;");
}
if (!pdo_fieldexists('junsion_feelings_comment', 'checked')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_comment') . " ADD  `checked` tinyint(1) DEFAULT 0;");
}
if (!pdo_fieldexists('junsion_feelings_rule', 'maxsize')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_rule') . " ADD  `maxsize` int(11) DEFAULT 0;");
}
if (!pdo_fieldexists('junsion_feelings_rule', 'stitle')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_rule') . " ADD  `stitle` varchar(255) DEFAULT '';");
}
if (!pdo_fieldexists('junsion_feelings_rule', 'sthumb')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_rule') . " ADD  `sthumb` varchar(255) DEFAULT '';");
}
if (!pdo_fieldexists('junsion_feelings_rule', 'sdesc')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_rule') . " ADD  `sdesc` varchar(255) DEFAULT '';");
}
if (!pdo_fieldexists('junsion_feelings_rule', 'adv')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_rule') . " ADD  `adv` varchar(255) DEFAULT '';");
}
if (!pdo_fieldexists('junsion_feelings_rule', 'savetype')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_rule') . " ADD  `savetype` tinyint(1) DEFAULT '0';");
}
if (!pdo_fieldexists('junsion_feelings_rule', 'aid')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_rule') . " ADD  `aid` varchar(10) DEFAULT '';");
}
if (!pdo_fieldexists('junsion_feelings_rule', 'atoken')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_rule') . " ADD  `atoken` varchar(255) DEFAULT '';");
}
if (!pdo_fieldexists('junsion_feelings_rule', 'qlimit')) {
	pdo_query("ALTER TABLE " . tablename('junsion_feelings_rule') . " ADD  `qlimit` tinyint(1) DEFAULT '0';");
}
