<?php


if (!pdo_fieldexists('mon_house_order', 'tel')) {
    pdo_query("ALTER TABLE " . tablename('mon_house_order') . "ADD  `tel` varchar(20) NOT NULL;");

}





/***
    1.4
 */

if (!pdo_fieldexists('mon_house', 'dt_img')) {
    pdo_query("ALTER TABLE " . tablename('mon_house') . "ADD  `dt_img` varchar(300);");

}



if (!pdo_fieldexists('mon_house', 'dt_intro')) {
    pdo_query("ALTER TABLE " . tablename('mon_house') . "ADD  `dt_intro` varchar(2000) ;");

}







//1.4
// 相册

$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_house_pic_type') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `hid` int(11) default 0 ,
  `rid` int(11) default 0 ,
 `pname` varchar(255) NOT NULL,
 `sort` int(3) default 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);


// 相册图片

$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_house_pic_image') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `hid` int(11) default 0 ,
 `pid` int(11) default 0 ,
`pre_img` varchar(255) NOT NULL,
`img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);







