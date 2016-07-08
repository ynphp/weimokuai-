<?php
 if(!pdo_fieldexists('quickmoney_goods', 'type')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_goods') . " ADD `type` int(10) NOT NULL DEFAULT '1' AFTER `content`;");
}
if(!pdo_fieldexists('quickmoney_request', 'exchangetype')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_request') . " ADD `exchangetype` int(10) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('quickmoney_request', 'cost')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_request') . " ADD `cost` Decimal(10,2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('quickmoney_goods', 'vip_require')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_goods') . " ADD `vip_require` int(10) NOT NULL DEFAULT '0' COMMENT '兑换最低VIP级别';");
}
if(!pdo_fieldexists('quickmoney_goods', 'userchangecost')){
    pdo_query("ALTER TABLE " . tablename('quickmoney_goods') . " ADD `userchangecost` int(10) NOT NULL DEFAULT 0 COMMENT '用户是否可以修改兑换值'");
}
