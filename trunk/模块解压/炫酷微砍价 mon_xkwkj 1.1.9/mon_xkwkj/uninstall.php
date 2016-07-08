<?php

pdo_query("DROP TABLE IF EXISTS ".tablename('mon_xkwkj').";");

pdo_query("DROP TABLE IF EXISTS ".tablename('mon_xkwkj_user').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_xkwkj_firend').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_xkwkj_order').";");
pdo_query("DROP TABLE IF EXISTS ".tablename('mon_xkwkj_setting').";");