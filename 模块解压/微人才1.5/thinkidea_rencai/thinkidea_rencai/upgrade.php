<?php

if (!pdo_fieldexists('thinkidea_rencai_person', 'workaddress')) {
    pdo_query("ALTER TABLE " . tablename('thinkidea_rencai_person') . " ADD  `workaddress` varchar(200) NOT NULL;");
}
if (!pdo_fieldexists('thinkidea_rencai_job', 'workaddress')) {
    pdo_query("ALTER TABLE " . tablename('thinkidea_rencai_person') . " ADD  `workaddress` varchar(200) NOT NULL;");
}
if (!pdo_fieldexists('thinkidea_rencai_job', 'ishot')) {
    pdo_query("ALTER TABLE " . tablename('thinkidea_rencai_job') . " ADD  `ishot` SMALLINT(1) NOT NULL DEFAULT '0';");
}
$sql = 
"CREATE TABLE if not exists `ims_thinkidea_rencai_jobs_comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `weid` SMALLINT(6) NULL DEFAULT NULL,
  `mid` INT(11) NULL DEFAULT NULL COMMENT '用户id',
  `jobid` INT(11) NULL DEFAULT NULL COMMENT '职位id',
  `content` VARCHAR(250) NULL DEFAULT NULL,
  `status` TINYINT(1) NULL DEFAULT '0',
  `dateline` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) COMMENT='评论表' COLLATE='utf8_general_ci' ENGINE=MyISAM;";
pdo_query($sql);

   