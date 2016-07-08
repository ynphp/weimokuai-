<?php
/**
 *
 * @author czt
 * @url http://bbs.012wz.com/
 */

$table_reply=tablename('czt_zhuanfa_reply');
$sql =<<<EOF
CREATE TABLE IF NOT EXISTS {$table_reply} (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `url` varchar(500) NOT NULL,
  `token` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
EOF;
pdo_run($sql);