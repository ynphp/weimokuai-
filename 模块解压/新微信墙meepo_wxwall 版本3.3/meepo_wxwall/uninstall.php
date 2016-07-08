<?php
global $_W;

$sql = "
DROP TABLE ims_meepo_tu_data;
DROP TABLE ims_meepo_tu_comment;
DROP TABLE ims_meepo_tu_set;
";
pdo_query($sql);