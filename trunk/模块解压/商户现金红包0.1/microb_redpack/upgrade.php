<?php
pdo_query('ALTER TABLE ' . tablename('mbrp_records') . ' CHANGE `fee` `fee` DECIMAL(10,2) UNSIGNED NOT NULL');
pdo_query('ALTER TABLE ' . tablename('mbrp_fans') . ' ADD INDEX(`openid`)');