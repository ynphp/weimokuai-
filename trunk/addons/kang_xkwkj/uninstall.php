<?php

pdo_query("DROP TABLE IF EXISTS " . tablename('kang_xkwkj') . ";");

pdo_query("DROP TABLE IF EXISTS " . tablename('kang_xkwkj_user') . ";");
pdo_query("DROP TABLE IF EXISTS " . tablename('kang_xkwkj_firend') . ";");
pdo_query("DROP TABLE IF EXISTS " . tablename('kang_xkwkj_order') . ";");
pdo_query("DROP TABLE IF EXISTS " . tablename('kang_xkwkj_setting') . ";");