<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
if (!pdo_fieldexists('ewei_shop_goods', 'taotaoid')) {
    pdo_query('ALTER TABLE ' . tablename('ewei_shop_goods') . " ADD `taotaoid` varchar(255) DEFAULT '';");
}
if (!pdo_fieldexists('ewei_shop_goods', 'taobaourl')) {
    pdo_query('ALTER TABLE ' . tablename('ewei_shop_goods') . " ADD `taobaourl` varchar(255) DEFAULT '';");
}
if (!pdo_fieldexists('ewei_shop_goods', 'updatetime')) {
    pdo_query('ALTER TABLE ' . tablename('ewei_shop_goods') . ' ADD `updatetime` int(11) default 0;');
}
if (!pdo_fieldexists('ewei_shop_goods', 'updatetime')) {
    pdo_query('ALTER TABLE ' . tablename('ewei_shop_goods') . ' ADD `updatetime` int(11) default 0;');
}
if (!pdo_fieldexists('ewei_shop_goods_option', 'skuId')) {
    pdo_query('ALTER TABLE ' . tablename('ewei_shop_goods_option') . " ADD `skuId` varchar(255) DEFAULT '';");
}
if (!pdo_fieldexists('ewei_shop_goods_spec', 'propId')) {
    pdo_query('ALTER TABLE ' . tablename('ewei_shop_goods_spec') . " ADD `propId` varchar(255) DEFAULT '';");
}
if (!pdo_fieldexists('ewei_shop_goods_spec_item', 'valueId')) {
    pdo_query('ALTER TABLE ' . tablename('ewei_shop_goods_spec_item') . " ADD `valueId` varchar(255) DEFAULT '';");
}