<?php
		if ($tablestr="msyou_facedoubi_paraset"||$stablestr=''){
			if (!pdo_tableexists('msyou_facedoubi_paraset')){
				$sqlstr="CREATE TABLE ".tablename('msyou_facedoubi_paraset')." (
		 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		 `uniacid` int(11) unsigned NOT NULL,
		 `appid` varchar(20) NOT NULL DEFAULT '',
		 `appsecret` varchar(50) NOT NULL DEFAULT '',
		 `appid_share` varchar(20) NOT NULL DEFAULT '',
		 `appsecret_share` varchar(50) NOT NULL DEFAULT '',
		 `resroot` varchar(200) NOT NULL DEFAULT '',
		 PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				pdo_run($sqlstr);
			}
		}
		if ($tablestr="msyou_facedoubi_reply"||$stablestr=''){
			if (!pdo_tableexists('msyou_facedoubi_reply')){
				$sqlstr="CREATE TABLE ".tablename('msyou_facedoubi_reply')." (
		 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) unsigned NOT NULL,
  `uniacid` int(11) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `thumb` text NOT NULL DEFAULT '',
  `contact` text NOT NULL DEFAULT '',
  `detail` text NOT NULL DEFAULT '',
  `crinfo` text NOT NULL DEFAULT '',
  `bgurl` text NOT NULL DEFAULT '',
  `defaultshareurl` text NOT NULL DEFAULT '',
  `starttime` int(10) NOT NULL DEFAULT 0,
  `endtime` int(10) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT '0' ,
  `joincount` int(11) NOT NULL DEFAULT 0,
  `viewcount` int(11) NOT NULL DEFAULT 0,
  `createtime` int(10) NOT NULL ,
  `doubix` float NOT NULL DEFAULT 0,
  `zanx` float NOT NULL DEFAULT 0,
  `sharex` float NOT NULL DEFAULT 0,
  `viewx` float NOT NULL DEFAULT 0,
		 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				pdo_run($sqlstr);
			}else{
				if (!pdo_fieldexists('msyou_facedoubi_reply','doubix')){
					$sqlstr="alter table ".tablename('msyou_facedoubi_reply')." add `doubix` float NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_facedoubi_reply','zanx')){
  					$sqlstr="alter table ".tablename('msyou_facedoubi_reply')." add `zanx` float NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
				}
				if (!pdo_fieldexists('msyou_facedoubi_reply','sharex')){
					$sqlstr="alter table ".tablename('msyou_facedoubi_reply')." add `sharex` float NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_facedoubi_reply','viewx')){
					$sqlstr="alter table ".tablename('msyou_facedoubi_reply')." add `viewx` float NOT NULL DEFAULT 0;";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_facedoubi_reply','defaultshareurl')){
					$sqlstr="alter table ".tablename('msyou_facedoubi_reply')." add `defaultshareurl` text NOT NULL DEFAULT '';";
	  				pdo_run($sqlstr);
	  			}
			}
		}




		if ($tablestr="msyou_facedoubi_lists"||$stablestr=''){
			if (!pdo_tableexists('msyou_facedoubi_lists')){
				$sqlstr="CREATE TABLE ".tablename('msyou_facedoubi_lists')." (
		 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) unsigned NOT NULL,
  `uniacid` int(11) unsigned NOT NULL,
  `fanid` int(11) unsigned NOT NULL,
  `fullname` varchar(20) NOT NULL DEFAULT '',
  `phonenum` varchar(20) NOT NULL DEFAULT '',
  `imgurl` text NOT NULL DEFAULT '',
  `contact` text NOT NULL DEFAULT '',
  `doubival` int(11) NOT NULL DEFAULT 0,
  `sharecount` int(11) NOT NULL DEFAULT 0,
  `zancount` int(11) NOT NULL DEFAULT 0,
  `viewcount` int(11) NOT NULL DEFAULT 0,
  `issubmit` int(1) NOT NULL DEFAULT 0,
  `jiang` int(1) NOT NULL DEFAULT 0,
  `createtime` int(10) NOT NULL ,
		 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				pdo_run($sqlstr);
			}else{
				if (!pdo_fieldexists('msyou_facedoubi_lists','fullname')){
					$sqlstr="alter table ".tablename('msyou_facedoubi_lists')." add `fullname` varchar(20) NOT NULL DEFAULT '';";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_facedoubi_lists','phonenum')){
					$sqlstr="alter table ".tablename('msyou_facedoubi_lists')." add `phonenum` varchar(20) NOT NULL DEFAULT '';";
	  				pdo_run($sqlstr);
	  			}
				if (!pdo_fieldexists('msyou_facedoubi_lists','remark')){
					$sqlstr="alter table ".tablename('msyou_facedoubi_lists')." add `remark` text NOT NULL DEFAULT '';";
	  				pdo_run($sqlstr);
	  			}
			}
		}
		
		if ($tablestr="msyou_facedoubi_lists_zan"||$stablestr=''){
			if (!pdo_tableexists('msyou_facedoubi_lists_zan')){
				$sqlstr="CREATE TABLE ".tablename('msyou_facedoubi_lists_zan')." (
		 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) unsigned NOT NULL,
  `uniacid` int(11) unsigned NOT NULL,
  `listid` int(11) unsigned NOT NULL,
  `fanid` int(11) NOT NULL DEFAULT 0,
  `zan` int(1) NOT NULL DEFAULT 0,
  `contact` text NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL ,
		 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				pdo_run($sqlstr);
			}
		}
		
		if ($tablestr="msyou_facedoubi_imgs"||$stablestr=''){
			if (!pdo_tableexists('msyou_facedoubi_imgs')){
				$sqlstr="CREATE TABLE ".tablename('msyou_facedoubi_imgs')." (
		 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `thumb` text NOT NULL DEFAULT '',
  `smileval` float NOT NULL DEFAULT 0,
  `createtime` int(10) NOT NULL DEFAULT 0,
		 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				pdo_run($sqlstr);
				
				$sqlstr="
INSERT INTO ".tablename('msyou_facedoubi_imgs')." (`uniacid`, `title`, `thumb`, `smileval`) VALUES
(".$_W['uniacid'].", '001', '".MODULE_URL."template/style/demo/imgs/001.png', 1),
(".$_W['uniacid'].", '002', '".MODULE_URL."template/style/demo/imgs/002.png', 1),
(".$_W['uniacid'].", '003', '".MODULE_URL."template/style/demo/imgs/003.png', 1),
(".$_W['uniacid'].", '004', '".MODULE_URL."template/style/demo/imgs/004.png', 1),
(".$_W['uniacid'].", '005', '".MODULE_URL."template/style/demo/imgs/005.png', 1),
(".$_W['uniacid'].", '006', '".MODULE_URL."template/style/demo/imgs/006.png', 1),
(".$_W['uniacid'].", '007', '".MODULE_URL."template/style/demo/imgs/007.png', 1),
(".$_W['uniacid'].", '008', '".MODULE_URL."template/style/demo/imgs/008.png', 1),
(".$_W['uniacid'].", '009', '".MODULE_URL."template/style/demo/imgs/009.png', 1),
(".$_W['uniacid'].", '010', '".MODULE_URL."template/style/demo/imgs/010.png', 1),
(".$_W['uniacid'].", '011', '".MODULE_URL."template/style/demo/imgs/011.png', 1),
(".$_W['uniacid'].", '012', '".MODULE_URL."template/style/demo/imgs/012.png', 1),
(".$_W['uniacid'].", '013', '".MODULE_URL."template/style/demo/imgs/013.png', 1),
(".$_W['uniacid'].", '014', '".MODULE_URL."template/style/demo/imgs/014.png', 1),
(".$_W['uniacid'].", '015', '".MODULE_URL."template/style/demo/imgs/015.png', 1),
(".$_W['uniacid'].", '016', '".MODULE_URL."template/style/demo/imgs/016.png', 1),
(".$_W['uniacid'].", '017', '".MODULE_URL."template/style/demo/imgs/017.png', 1),
(".$_W['uniacid'].", '018', '".MODULE_URL."template/style/demo/imgs/018.png', 1);
";
				pdo_run($sqlstr);
			}
		}
		if ($tablestr="msyou_facedoubi_fans"||$stablestr=''){
			if (!pdo_tableexists('msyou_facedoubi_fans')){
				$sqlstr="CREATE TABLE ".tablename('msyou_facedoubi_fans')." (
		 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL DEFAULT 0,
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `realname` varchar(50) NOT NULL DEFAULT '',
  `headurl` text NOT NULL DEFAULT '',
  `emailname` text NOT NULL DEFAULT '',
  `phonenum` varchar(15) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL ,
		 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				pdo_run($sqlstr);
			}
		}
