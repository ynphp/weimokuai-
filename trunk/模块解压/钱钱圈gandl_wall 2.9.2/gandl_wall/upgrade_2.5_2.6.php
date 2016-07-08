<?php

if(!pdo_fieldexists('gandl_wall', 'transfer_min')) {
	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `transfer_min` int(11) NULL COMMENT '������С���' AFTER `top_line`;");
}

if(!pdo_fieldexists('gandl_wall', 'lang')) {
	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `lang` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'չʾ������������' AFTER `css`;");
}

if(!pdo_fieldexists('gandl_wall', 'follow_show')) {
	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `follow_show` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '��ע����ͼ' AFTER `fake_money`;");
}

if(!pdo_fieldexists('gandl_wall_piece', 'password')) {
	pdo_query("ALTER TABLE ".tablename('gandl_wall_piece')." ADD COLUMN `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '����' AFTER `link`;");
}


if(!pdo_fieldexists('gandl_wall', 'piece_model')) {

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." MODIFY COLUMN `city` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '���Ƴ��У������,�ŷָ���' AFTER `over_time`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `piece_model` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '��������Ǯģ�ͣ����ŷָ�1:��ͨ��2:���3:���ţ�' AFTER `city`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `group_rule` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '�Ż������Ĺ���ÿ��һ������������25:2��ƽ��ÿ��25��Ǯ����ʱ���Ż�����Ϊ2�ˣ�' AFTER `piece_model`;");	

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `total_min2` int(11) NOT NULL COMMENT '����ܶ�����Ǯ��(����ģʽ)' AFTER `fee`;");	
	
	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `total_max2` int(11) NOT NULL COMMENT '����ܶ����Ǯ��(����ģʽ)' AFTER `total_min2`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `avg_min2` int(11) NOT NULL COMMENT 'ƽ�������������Ǯ��(����ģʽ)' AFTER `total_max2`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `fee2` float NOT NULL COMMENT '������ʱ���%(����ģʽ)' AFTER `avg_min2`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `total_min3` int(11) NOT NULL COMMENT '����ܶ�����Ǯ��(����ģʽ)' AFTER `fee2`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `total_max3` int(11) NOT NULL COMMENT '����ܶ����Ǯ��(����ģʽ)' AFTER `total_min3`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `avg_min3` int(11) NOT NULL COMMENT 'ƽ�������������Ǯ��(����ģʽ)' AFTER `total_max3`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `fee3` float NOT NULL COMMENT '������ʱ���%(����ģʽ)' AFTER `avg_min3`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `groupmax` smallint(6) NOT NULL COMMENT '�Ż��������' AFTER `piece_model`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `avg_max` smallint(6) NOT NULL COMMENT 'ƽ����������������Ϊƽ�������޵ļ���' AFTER `avg_min`;");

	pdo_query('UPDATE '.tablename('gandl_wall') .' SET piece_model="1" ');
}


if(!pdo_fieldexists('gandl_wall_user', 'nickname')) {

	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `nickname` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `user_id`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `nickname`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `who` tinyint(1) NULL COMMENT '0:δ֪ 1:��ͨ�û��� 2�����ĺ� 3����֤���ĺ� 4������� 5����֤�����' AFTER `avatar`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `home` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `who`;");	
}


if(!pdo_fieldexists('gandl_wall_piece', 'model')) {

	pdo_query("ALTER TABLE ".tablename('gandl_wall_piece')." ADD COLUMN `model` tinyint(1) NULL COMMENT 'ģ��(1:��ͨģ�ͣ�2���Ż�ģ��)' AFTER `user_id`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall_piece')." ADD COLUMN `password_show` tinyint(1) NULL COMMENT '0:����ʾ��Ǯ���1����ʾ��Ǯ����' AFTER `password`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall_piece')." ADD COLUMN `group_size` smallint(6) NULL COMMENT '�Ż�����' AFTER `password_show`;");
}


pdo_query("CREATE TABLE IF NOT EXISTS `ims_gandl_wall_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `wall_id` int(11) NOT NULL,
  `piece_id` int(11) NOT NULL,
  `captain_id` int(11) NOT NULL COMMENT '�ų��ڵ�ǰȦ���е�ID',
  `mine_id` int(11) NOT NULL COMMENT '����Ȧ���е�ID',
  `user_id` int(11) NOT NULL COMMENT '�Ŷ��û�ID',
  `nickname` varchar(80) DEFAULT NULL COMMENT '��Ա�ǳ�',
  `avatar` varchar(255) DEFAULT NULL COMMENT '��Աͷ��',
  `create_time` int(11) NOT NULL COMMENT '����ʱ��',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='��Ǯ�ű�';");

// 2.2-2.3
if(!pdo_fieldexists('gandl_wall', 'slider')) {
	pdo_query("ALTER TABLE ".tablename('gandl_wall')." MODIFY COLUMN `banner` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '��������ͼ' AFTER `topic`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `slider` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '�õ�Ƭ' AFTER `banner`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `up_rob_fee` float NOT NULL COMMENT '��Ǯ�ϼ����' AFTER `transfer_min`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `up_send_fee` float NOT NULL COMMENT '��Ǯ�ϼ����' AFTER `up_rob_fee`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `fake_online` int(11) NULL COMMENT '���������������' AFTER `fake_money`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')." ADD COLUMN `static` tinyint(1) NOT NULL COMMENT 'ͳ�ƿ���0:�رգ�1������' AFTER `slider`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `black` tinyint(1) NOT NULL COMMENT '0:�Ǻ�����,1:������' AFTER `create_time`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `black_why` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '�������������ԭ��' AFTER `black`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `inviter_id` int(11) NOT NULL COMMENT '������ID' AFTER `create_time`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `last_active_time` int(11) NOT NULL COMMENT '�ϴλʱ��' AFTER `create_time`;");
}
// 2.2-2.4 ���²���
if(!pdo_fieldexists('gandl_wall_rob', 'up_money')) {
	pdo_query("ALTER TABLE ".tablename('gandl_wall_rob')."  ADD COLUMN `up_money` int(11) NULL COMMENT '�Ͻ��Ľ��' AFTER `money`;"); 
	pdo_query("ALTER TABLE ".tablename('gandl_wall_rob')." 	ADD COLUMN `get_money` int(11) NULL COMMENT 'ʵ�ʻ�õĽ��' AFTER `money`;");

	pdo_query("CREATE TABLE IF NOT EXISTS `ims_gandl_wall_up_rob` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`uniacid` int(11) NOT NULL,
		`wall_id` int(11) NOT NULL,
		`piece_id` int(11) NOT NULL,
		`mine_id` int(11) NOT NULL,
		`user_id` int(11) NOT NULL,
		`up_id` int(11) NOT NULL,
		`up_fee` float NOT NULL COMMENT '�Ͻ��ı�����%��',
		`up_money` int(11) NOT NULL COMMENT '�Ͻ���Ǯ',
		`rob_money` int(11) NOT NULL COMMENT '������Ǯ',
		`create_time` int(11) NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM
	CHECKSUM=0
	DELAY_KEY_WRITE=0;");
}


// 2.5-2.6
if(!pdo_fieldexists('gandl_wall_user', 'in_position')) {
	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `in_position` tinyint(1) NULL COMMENT '0:δ��λ 1:�ڷ�Χ�� 2�����ڷ�Χ��' AFTER `send_last_time`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `last_position` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '�ϴζ�λ���ڵ�' AFTER `last_city`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `last_position_exp` int(11) NOT NULL COMMENT '�ϴζ�λ����ʱ��' AFTER `last_position`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `follow` tinyint(1) NOT NULL DEFAULT -1 COMMENT '�Ƿ����ڹ�ע��-1:δ֪��δ��0,1������ע��������������Ż����ܣ���ʹ�ò�ѯfan����ʵ���˽��û��Ƿ�����ע���ں�' AFTER `followed`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `notify_newpiece` tinyint(1) NOT NULL DEFAULT 1 COMMENT '����Ǯ֪ͨ��0:�رգ�1������' AFTER `home`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall_user')." ADD COLUMN `notify_newpiece_time` int(11) NOT NULL COMMENT '�ϴη�������Ǯ֪ͨʱ��' AFTER `notify_newpiece`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall')."	ADD COLUMN `province` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '����ʡ�������,�ŷָ���' AFTER `over_time`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')."	ADD COLUMN `district` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '�������أ������,�ŷָ���' AFTER `city`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')."	ADD COLUMN `reply_on` tinyint(1) NULL COMMENT '�Ƿ������ۣ�0�رգ�1������' AFTER `static`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')."	ADD COLUMN `reply_verify` tinyint(1) NULL COMMENT '�������ģʽ��0��Ĭ�Ϻ�����ˣ�1��ǰ�����' AFTER `reply_on`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')."	ADD COLUMN `reply_mana` tinyint(1) NULL COMMENT '���۹���0���������̻�����1�������̻�����' AFTER `reply_verify`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')."	ADD COLUMN `task_follow` smallint(6) NOT NULL DEFAULT 600 COMMENT '��ע��������ȴʱ�䣬�룬Ĭ��10����' AFTER `reply_mana`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')."	ADD COLUMN `task_invite` smallint(6) NOT NULL DEFAULT 60 COMMENT '����һ�����ѿ�������ȴʱ�䣬�룬Ĭ��60��' AFTER `task_follow`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')."	ADD COLUMN `task_invite_max` smallint(6) NOT NULL DEFAULT 600 COMMENT '���ܺ����������ޣ��룬Ĭ��10����' AFTER `task_invite`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')."	ADD COLUMN `notify` tinyint(1) NOT NULL COMMENT '��Ϣ֪ͨ����,0:�ر� 1������' AFTER `follow_url`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall')."	ADD COLUMN `notify_tpl` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '��Ϣ֪ͨģ�����л��洢' AFTER `notify`;");

	pdo_query("ALTER TABLE ".tablename('gandl_wall_piece')." ADD COLUMN `notify_time` int(11) NOT NULL COMMENT '�ϴ�����ʱ��' AFTER `create_time`;");
	pdo_query("ALTER TABLE ".tablename('gandl_wall_piece')." ADD COLUMN `notify_cnt` int(11) NOT NULL COMMENT '���͵�������' AFTER `notify_time`;");
}

pdo_query("CREATE TABLE IF NOT EXISTS `ims_gandl_wall_reply` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`uniacid` int(11) NOT NULL,
	`wall_id` int(11) NOT NULL,
	`piece_id` int(11) NOT NULL,
	`mine_id` int(11) NOT NULL,
	`user_id` int(11) NOT NULL,
	`content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`create_time` int(11) NOT NULL,
	`update_time` int(11) NOT NULL,
	`status` tinyint(1) NOT NULL COMMENT '1��δ��� 2�����ͨ�� 3����˲�ͨ��',
	`status_time` int(11) NOT NULL,
	`op_id` int(11) NULL,
	`op_time` int(11) NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
DELAY_KEY_WRITE=0;");






