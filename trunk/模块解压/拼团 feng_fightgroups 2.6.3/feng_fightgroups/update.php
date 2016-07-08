<?php
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('ims_tg_refund_record') . " (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transid` int(11) NOT NULL COMMENT '订单编号',
  `createtime` varchar(45) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0未成功1成功',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;";
pdo_query($sql);