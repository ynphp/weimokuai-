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