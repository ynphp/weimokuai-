/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : wowoxiu

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-06-03 01:11:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_lianhu_applist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_applist`;
CREATE TABLE `ims_lianhu_applist` (
  `list_id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `content` text,
  `status` tinyint(1) DEFAULT '0' COMMENT '预约状态0:：待审核1=》通过，2=》未通过',
  `reason` text,
  `teacher_reason` text,
  `teacher_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`list_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_applist
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_appointment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_appointment`;
CREATE TABLE `ims_lianhu_appointment` (
  `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_limit` text COMMENT '限制',
  `appointment_type_limit` tinyint(1) DEFAULT NULL,
  `appointment_grade_class` text,
  `appointment_mutex` text COMMENT '互斥',
  `appointment_name` tinytext,
  `appointment_intro` text,
  `appointment_content` text,
  `appointment_start` int(11) DEFAULT NULL,
  `appointment_end` int(11) DEFAULT NULL,
  `appointment_addtime` int(11) DEFAULT NULL,
  `appointment_type` tinyint(1) DEFAULT '1' COMMENT '预约类型;1=>课程；2=》活动；3=》兴趣小组',
  `appointment_max_num` int(11) DEFAULT NULL,
  `appointment_join_num` int(11) DEFAULT '0',
  `teacher_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '预约状态1=>正常，0=》失效，2=》完成',
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`appointment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_appointment
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_appointment_course`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_appointment_course`;
CREATE TABLE `ims_lianhu_appointment_course` (
  `course_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) unsigned DEFAULT NULL,
  `uniacid` int(11) unsigned DEFAULT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `course_type` tinyint(1) unsigned DEFAULT '1' COMMENT '1=>长课程，2=>短课程',
  `course_time` text,
  `course_num` int(1) DEFAULT '0',
  `course_content` text,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_appointment_course
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_class`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_class`;
CREATE TABLE `ims_lianhu_class` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `grade_id` int(11) DEFAULT '0',
  `course_ids` text,
  `class_name` varchar(20) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `video_ids` text,
  PRIMARY KEY (`class_id`),
  KEY `grade_id` (`grade_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_class
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_cookbook`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_cookbook`;
CREATE TABLE `ims_lianhu_cookbook` (
  `cookbook_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned DEFAULT '0',
  `school_id` int(11) unsigned DEFAULT '0',
  `cookbook_week` char(30) DEFAULT NULL,
  `cookbooK_breakfast` text,
  `cookbook_lunch` text,
  `cookbook_dinner` text,
  `add_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`cookbook_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_cookbook
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_course`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_course`;
CREATE TABLE `ims_lianhu_course` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(60) DEFAULT NULL,
  `course_basic` tinyint(1) DEFAULT '0',
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_course
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_grade`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_grade`;
CREATE TABLE `ims_lianhu_grade` (
  `grade_id` int(11) NOT NULL AUTO_INCREMENT,
  `grade_name` char(40) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `sort_order` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`grade_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_grade
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_grade_change`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_grade_change`;
CREATE TABLE `ims_lianhu_grade_change` (
  `change_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `grade_id` int(11) unsigned DEFAULT NULL,
  `grade_name` varchar(100) DEFAULT NULL,
  `sort_order` tinyint(1) unsigned DEFAULT NULL,
  `add_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`change_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_grade_change
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_homework`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_homework`;
CREATE TABLE `ims_lianhu_homework` (
  `homework_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) unsigned DEFAULT NULL,
  `uniacid` int(11) unsigned DEFAULT NULL,
  `course_id` int(11) unsigned DEFAULT NULL,
  `teacher_id` int(11) unsigned DEFAULT NULL,
  `content` text,
  `add_time` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `img` text,
  `vocie` text,
  `class_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`homework_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_homework
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_jilv_class`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_jilv_class`;
CREATE TABLE `ims_lianhu_jilv_class` (
  `class_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class_name` varchar(60) DEFAULT NULL,
  `add_time` int(11) DEFAULT '1',
  `class_status` tinyint(1) DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_jilv_class
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_jinbu`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_jinbu`;
CREATE TABLE `ims_lianhu_jinbu` (
  `jinbu_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT '0',
  `teacher_id` int(11) DEFAULT '0' COMMENT '后台uid',
  `course_name` varchar(200) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `content` text,
  `content1` text,
  `content2` text,
  `addtime` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`jinbu_id`),
  KEY `student_id` (`student_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_jinbu
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_leave`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_leave`;
CREATE TABLE `ims_lianhu_leave` (
  `leave_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `member_uid` int(11) unsigned DEFAULT NULL COMMENT '发起请假的人',
  `student_id` int(11) unsigned DEFAULT '0',
  `class_id` int(11) unsigned DEFAULT '0',
  `teacher_id` int(11) unsigned DEFAULT '0',
  `leave_reason` text COMMENT '请假理由',
  `time_date` text,
  `teacher_text` text COMMENT '教师备注',
  `add_time` int(11) unsigned DEFAULT NULL,
  `leave_status` tinyint(1) DEFAULT '1' COMMENT '1=>申请中，2=>教师确认',
  `time_date_begin` int(11) DEFAULT '1',
  `time_date_end` int(11) DEFAULT '1',
  PRIMARY KEY (`leave_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_leave
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_line`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_line`;
CREATE TABLE `ims_lianhu_line` (
  `line_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `teacher_intro` text,
  `line_title` tinytext,
  `line_content` text,
  `line_look` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `addtime` int(11) DEFAULT NULL,
  `line_type` tinyint(1) DEFAULT '1',
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`line_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_line
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_log`;
CREATE TABLE `ims_lianhu_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_type` tinytext,
  `log_content` text,
  `addtime` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_log
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_money_limit`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_money_limit`;
CREATE TABLE `ims_lianhu_money_limit` (
  `limit_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `limit_name` varchar(30) DEFAULT NULL COMMENT '限制名字',
  `limit_module` varchar(30) DEFAULT NULL COMMENT '限制的模块',
  `limit_type` tinyint(1) DEFAULT '1' COMMENT '限制类型：1=》永远；2=》每年；3=》每月',
  `limit_much` float(8,2) DEFAULT '0.00',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态；1=》有效；2=》失效',
  `addtime` int(11) DEFAULT '0',
  PRIMARY KEY (`limit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_money_limit
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_money_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_money_record`;
CREATE TABLE `ims_lianhu_money_record` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `school_id` int(11) DEFAULT '0',
  `limit_id` int(11) DEFAULT NULL,
  `limit_much` float(8,2) DEFAULT '0.00',
  `student_id` int(11) DEFAULT NULL COMMENT '学生id',
  `fan_id` int(11) DEFAULT NULL COMMENT '绑定的用户id',
  `uid` int(11) DEFAULT NULL COMMENT '支付人的uid',
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '支付状态;0=>未支付；1=》支付成功',
  PRIMARY KEY (`record_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_money_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_msg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_msg`;
CREATE TABLE `ims_lianhu_msg` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_title` tinytext,
  `msg_content` text,
  `status` tinyint(1) DEFAULT '1',
  `addtime` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`msg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_msg
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_msg_queue`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_msg_queue`;
CREATE TABLE `ims_lianhu_msg_queue` (
  `queue_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `queue_num` char(32) DEFAULT NULL,
  `openid` char(50) DEFAULT NULL,
  `mobile` char(12) DEFAULT NULL,
  `queue_type` tinyint(1) DEFAULT '1' COMMENT '1=>模板消息；2=>客服消息;3=》短信消息',
  `queue_content` text COMMENT '消息内容',
  `url` text,
  `mu_id` char(60) DEFAULT NULL COMMENT '模板ID',
  `add_time` int(11) DEFAULT '1',
  `end_time` int(11) DEFAULT '0',
  `queue_status` tinyint(1) DEFAULT '1' COMMENT '1=>未发送；2=>已经发送；3=>发送失败',
  PRIMARY KEY (`queue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_msg_queue
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_msg_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_msg_record`;
CREATE TABLE `ims_lianhu_msg_record` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1' COMMENT '1=>全体，2=》已绑定的，3=》未绑定的，4=》单个用户',
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_msg_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_school`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_school`;
CREATE TABLE `ims_lianhu_school` (
  `school_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `school_name` varchar(200) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `mu_str` varchar(30) DEFAULT NULL,
  `line_status` tinyint(1) DEFAULT '1',
  `cookbook_status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`school_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_school
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_school_admin`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_school_admin`;
CREATE TABLE `ims_lianhu_school_admin` (
  `admin_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned DEFAULT NULL,
  `school_id` int(11) unsigned DEFAULT NULL,
  `uid` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_school_admin
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_scorejilv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_scorejilv`;
CREATE TABLE `ims_lianhu_scorejilv` (
  `scorejilv_id` int(11) NOT NULL AUTO_INCREMENT,
  `scorejilv_name` tinytext,
  `grade_id` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`scorejilv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_scorejilv
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_scorelist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_scorelist`;
CREATE TABLE `ims_lianhu_scorelist` (
  `scorelist_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` tinytext,
  `course_id` int(11) DEFAULT '0',
  `teacher_name` tinytext,
  `teacher_id` int(11) DEFAULT '0',
  `grade_id` int(11) DEFAULT '0',
  `class_name` tinytext,
  `class_id` int(11) DEFAULT '0',
  `student_name` tinytext,
  `student_id` int(11) DEFAULT '0',
  `score` float(5,1) DEFAULT NULL,
  `ji_lv_id` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`scorelist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_scorelist
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_send`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_send`;
CREATE TABLE `ims_lianhu_send` (
  `send_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned DEFAULT NULL,
  `school_id` int(11) unsigned DEFAULT NULL,
  `class_id` int(11) unsigned DEFAULT NULL,
  `send_uid` int(11) unsigned DEFAULT NULL,
  `send_content` text,
  `send_image` text,
  `send_status` tinyint(1) DEFAULT '1' COMMENT '1=>ok ,3=>delete',
  `send_like` int(1) DEFAULT '1',
  `add_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`send_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_send
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_send_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_send_comment`;
CREATE TABLE `ims_lianhu_send_comment` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `send_id` int(11) unsigned DEFAULT NULL,
  `comment_uid` int(11) unsigned DEFAULT NULL,
  `comment_text` text,
  `add_time` int(11) unsigned DEFAULT NULL,
  `comment_status` tinyint(1) DEFAULT '1' COMMENT '1=>ok ,3=>delete',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_send_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_send_like`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_send_like`;
CREATE TABLE `ims_lianhu_send_like` (
  `like_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `send_id` int(11) unsigned DEFAULT NULL,
  `uid` int(11) unsigned DEFAULT NULL,
  `add_time` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`like_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_send_like
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_set`;
CREATE TABLE `ims_lianhu_set` (
  `set_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned DEFAULT NULL,
  `school_id` int(11) unsigned DEFAULT NULL,
  `keyword` char(20) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_set
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_sms_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_sms_record`;
CREATE TABLE `ims_lianhu_sms_record` (
  `record_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `school_id` int(11) DEFAULT '0',
  `student_id` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `content` text,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_sms_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_student`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_student`;
CREATE TABLE `ims_lianhu_student` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `fanid` int(11) DEFAULT '0',
  `fanid1` int(11) DEFAULT '0',
  `fanid2` int(11) DEFAULT '0',
  `grade_id` int(11) DEFAULT '0',
  `class_id` int(11) DEFAULT '0',
  `xuehao` char(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `student_link` varchar(20) DEFAULT NULL,
  `student_name` char(20) DEFAULT NULL,
  `student_img` char(60) DEFAULT NULL,
  `student_passport` char(40) DEFAULT NULL,
  `parent_name` char(20) DEFAULT NULL,
  `parent_phone` char(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `addtime` int(11) DEFAULT NULL,
  `msg_id_str` text COMMENT '站内信ID',
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  KEY `fanid` (`fanid`),
  KEY `fanid1` (`fanid1`),
  KEY `fanid2` (`fanid2`),
  KEY `grade_id` (`grade_id`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_student
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_student_live`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_student_live`;
CREATE TABLE `ims_lianhu_student_live` (
  `live_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) unsigned DEFAULT NULL,
  `teacher_id` int(11) unsigned DEFAULT NULL,
  `uniacid` int(11) unsigned DEFAULT NULL,
  `student_id` int(11) unsigned DEFAULT NULL,
  `addtime` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`live_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_student_live
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_syllabus`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_syllabus`;
CREATE TABLE `ims_lianhu_syllabus` (
  `syllabus_id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_uid` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `on_school` tinyint(1) DEFAULT '5' COMMENT '在校天数',
  `am_much` tinyint(1) DEFAULT '0',
  `pm_much` tinyint(1) DEFAULT '0',
  `ye_much` tinyint(1) DEFAULT '0',
  `content` text,
  `addtime` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `url` text,
  PRIMARY KEY (`syllabus_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_syllabus
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_teacher`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_teacher`;
CREATE TABLE `ims_lianhu_teacher` (
  `teacher_id` int(11) NOT NULL AUTO_INCREMENT,
  `fanid` int(11) DEFAULT '0' COMMENT '系统登陆ID',
  `uid` int(11) DEFAULT '0' COMMENT '微信uid',
  `course_id` text,
  `teacher_realname` char(20) DEFAULT NULL,
  `teacher_telphone` char(11) DEFAULT NULL,
  `teacher_email` char(30) DEFAULT NULL,
  `teacher_img` char(60) DEFAULT NULL,
  `teacher_word` text,
  `teacher_introduce` text,
  `teacher_model_power` text,
  `teacher_other_power` text,
  `weixin_code` varchar(60) DEFAULT NULL,
  `addtime` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `msg_id_str` text,
  PRIMARY KEY (`teacher_id`),
  KEY `fanid` (`fanid`),
  KEY `teacher_realname` (`teacher_realname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_teacher
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_test`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_test`;
CREATE TABLE `ims_lianhu_test` (
  `test_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT '0',
  `teacher_id` int(11) DEFAULT '0' COMMENT '后台uid',
  `score` float(5,1) DEFAULT '0.0',
  `course_name` varchar(200) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `word` text,
  `img` char(60) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `content` text,
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`test_id`),
  KEY `student_id` (`student_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_test
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_video`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_video`;
CREATE TABLE `ims_lianhu_video` (
  `video_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) unsigned DEFAULT NULL,
  `uniacid` int(11) unsigned DEFAULT NULL,
  `video_name` tinytext COMMENT '视频名',
  `video_url` tinytext COMMENT '视频流地址',
  `video_img` tinytext COMMENT '视频封面',
  `begin_time` char(20) DEFAULT NULL COMMENT '开始时间24小时制',
  `end_time` char(20) DEFAULT NULL COMMENT '结束时间',
  `add_time` int(11) unsigned DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_video
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_weak`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_weak`;
CREATE TABLE `ims_lianhu_weak` (
  `weak_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT '0',
  `teacher_id` int(11) DEFAULT '0' COMMENT '后台uid',
  `course_name` varchar(200) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `content` text,
  `content1` text,
  `content2` text,
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`weak_id`),
  KEY `student_id` (`student_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_weak
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_wechat`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_wechat`;
CREATE TABLE `ims_lianhu_wechat` (
  `wechat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `code` char(40) DEFAULT NULL,
  `type` tinyint(1) unsigned DEFAULT NULL COMMENT '1=>access_token,2=>jsapi_ticket',
  `addtime` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`wechat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_wechat
-- ----------------------------

-- ----------------------------
-- Table structure for `ims_lianhu_work`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lianhu_work`;
CREATE TABLE `ims_lianhu_work` (
  `work_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT '0',
  `teacher_id` int(11) DEFAULT '0' COMMENT '后台uid',
  `course_name` varchar(200) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `word` text,
  `img` char(60) DEFAULT NULL,
  `content` text,
  `addtime` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `jilv_class` int(11) DEFAULT '1',
  `voice` text,
  PRIMARY KEY (`work_id`),
  KEY `student_id` (`student_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_lianhu_work
-- ----------------------------
