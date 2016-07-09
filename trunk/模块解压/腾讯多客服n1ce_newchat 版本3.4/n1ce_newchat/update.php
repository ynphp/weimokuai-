<?php
$sql = "
CREATE TABLE IF NOT EXISTS `ims_n1ce_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `wait` varchar(255) NOT NULL,
  `start1` int(10) unsigned NOT NULL,
  `end1` int(10) unsigned NOT NULL,
  `start2` int(10) unsigned NOT NULL,
  `end2` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";

pdo_query($sql);


