<?php
/**
 * E砍价模块定义
 *
 * @author healer
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Healer_kjsModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		//引入需要的核心方法
		require_once dirname(__FILE__) . "/core/i18n.php";
		require_once dirname(__FILE__) . "/core/db.class.php";

		//初始化对象
		$DBUtil = new DBUtil();

		//全局变量
		global $_W, $_GPC;

		if ($_GPC["op"] == "auth") {
			//商家列表
			$businesslist = $DBUtil->getBusinesss("`uniacid`=:uniacid", array(":uniacid" => $_W["uniacid"]), "`createtime` DESC");
			//砍价
			$kanjia = $DBUtil->getKj("id=:id AND uniacid=:uniacid AND status=:status", array(":id" => intval($_GPC["id"]), ":uniacid" => $_W["uniacid"], ":status" => 0));
			if (empty($kanjia)) {
				exit($i18n["kanjia_empty"]);
			}
			//转换
			$kanjia["rule"] = htmlspecialchars_decode($kanjia["rule"]);
			$kanjia["content"] = htmlspecialchars_decode($kanjia["content"]);
			$kanjia["pimages"] = unserialize($kanjia["pimages"]);
			$kanjia["helprule2"] = unserialize($kanjia["helprule2"]);
			$kanjia["recordarea"] = unserialize($kanjia["recordarea"]);
			$kanjia["helparea"] = unserialize($kanjia["helparea"]);
			$kanjia["starttime"] = date("Y-m-d H:i:s", $kanjia["starttime"]);
			$kanjia["endtime"] = date("Y-m-d H:i:s", $kanjia["endtime"]);
			$kanjia["message"] = unserialize($kanjia["message"]);

			load()->func("tpl");
			include $this->template("auth");
		} else {
			//商家列表
			$businesslist = $DBUtil->getBusinesss("`uniacid`=:uniacid", array(":uniacid" => $_W["uniacid"]), "`createtime` DESC");
			//编辑 or 创建
			if (empty($_GPC["rid"])) {
				//砍价
				$kanjia = array("starttime" => date("Y-m-d", TIMESTAMP), "endtime" => date("Y-m-d", TIMESTAMP));
				$kanjia["pimages"] = array();
				$kanjia["message"] = array();
				$kanjia["helprule2"] = array();
			} else {
				//砍价
				$kanjia = $DBUtil->getKj("rid=:rid AND uniacid=:uniacid", array(":rid" => intval($_GPC["rid"]), ":uniacid" => $_W["uniacid"]));
				//转换
				$kanjia["rule"] = htmlspecialchars_decode($kanjia["rule"]);
				$kanjia["content"] = htmlspecialchars_decode($kanjia["content"]);
				$kanjia["pimages"] = unserialize($kanjia["pimages"]);
				$kanjia["helprule2"] = unserialize($kanjia["helprule2"]);
				$kanjia["recordarea"] = unserialize($kanjia["recordarea"]);
				$kanjia["helparea"] = unserialize($kanjia["helparea"]);
				$kanjia["starttime"] = date("Y-m-d H:i:s", $kanjia["starttime"]);
				$kanjia["endtime"] = date("Y-m-d H:i:s", $kanjia["endtime"]);
				$kanjia["message"] = unserialize($kanjia["message"]);
			}
			load()->func("tpl");
			include $this->template("post");
		}
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		//引入需要的核心方法
		require_once dirname(__FILE__) . "/core/i18n.php";
		require_once dirname(__FILE__) . "/core/db.class.php";

		//初始化对象
		$DBUtil = new DBUtil();

		//全局变量
		global $_W, $_GPC;

		//标题不可为空
		if (empty($_GPC['title'])) {
			return $i18n["kanjia_title_empty"];
		}
		//封面
		if (empty($_GPC['cover'])) {
			return $i18n["kanjia_cover_empty"];
		}
		//详情
		if (empty($_GPC['content'])) {
			return $i18n["kanjia_content_empty"];
		}
		//预计帮砍次数
		if (empty($_GPC['helprule']) && empty($_GPC['helpmode'])) {
			return $i18n["kanjia_helprule_empty"];
		}
		//商品名称
		if (empty($_GPC['pname'])) {
			return $i18n["kanjia_pname_empty"];
		}
		//库存需要大于0
		if ($_GPC['pweight'] < 1) {
			return $i18n["kanjia_pweight_min_one"];
		}
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		//引入需要的核心方法
		require_once dirname(__FILE__) . "/core/i18n.php";
		require_once dirname(__FILE__) . "/core/db.class.php";

		//初始化对象
		$DBUtil = new DBUtil();

		//全局变量
		global $_W, $_GPC;

		//数据包
		$data = array(
			"bid" => intval($_GPC["bid"]),
			"title" => trim($_GPC["title"]),
			"cover" => trim($_GPC["cover"]),
			"rule" => htmlspecialchars($_GPC["rule"]),
			"content" => htmlspecialchars($_GPC["content"]),
			"starttime" => strtotime(trim($_GPC["time"]["start"])),
			"endtime" => strtotime(trim($_GPC["time"]["end"])),
			"helpmode" => intval($_GPC["helpmode"]),
			"helprule" => intval($_GPC["helprule"]),
			"joincredit" => trim($_GPC["joincredit"]),
			"helpcredit" => trim($_GPC["helpcredit"]),
			"pname" => trim($_GPC["pname"]),
			"pad" => trim($_GPC["pad"]),
			"pimages" => empty($_GPC["pimages"]) ? serialize(array()) : serialize($_GPC["pimages"]),
			"pprice" => floatval($_GPC["pprice"]),
			"ppricemin" => floatval($_GPC["ppricemin"]),
			"pweight" => max(1, $_GPC["pweight"]),
			"pfare" => floatval($_GPC["pfare"]),
			"pturl" => trim($_GPC["pturl"]),
			"tuijian" => intval($_GPC["tuijian"]),
			"buymode" => intval($_GPC["buymode"]),
			"paymode" => intval($_GPC["paymode"]),
			"ordermode" => intval($_GPC["ordermode"]),
			"follow" => intval($_GPC["follow"]),
			"followhelp" => intval($_GPC["followhelp"]),
			"followurl" => trim($_GPC["followurl"]),
			"stat" => trim($_GPC["stat"]),
			"maxhelp" => intval($_GPC["maxhelp"]),
			"maxip" => intval($_GPC["maxip"]),
			"recordarea" => serialize(array("province" => trim($_GPC["recordarea"]["province"]), "city" => trim($_GPC["recordarea"]["city"]), "district" => trim($_GPC["recordarea"]["district"]))),
			"helparea" => serialize(array("province" => trim($_GPC["helparea"]["province"]), "city" => trim($_GPC["helparea"]["city"]), "district" => trim($_GPC["helparea"]["district"]))),
			"postermid" => trim($_GPC["postermid"]),
			"sharetitle" => trim($_GPC["sharetitle"]),
			"shareimage" => trim($_GPC["shareimage"]),
			"sharedesc" => trim($_GPC["sharedesc"]),
			"sharelink" => trim($_GPC["sharelink"]),
			"sort" => intval($_GPC["sort"]),
			"status" => intval($_GPC["status"]),
		);
		//砍价规则
		$data["helprule2"] = array();
		foreach ($_GPC["ruleprice"] as $key => $value) {
			if (empty($_GPC["ruleprice"][$key]) || empty($_GPC["rulemin"][$key]) || empty($_GPC["rulemax"][$key])) {
				continue;
			} else {
				$data["helprule2"][] = array(
					"ruleprice" => floatval($_GPC["ruleprice"][$key]),
					"rulemin" => floatval($_GPC["rulemin"][$key]),
					"rulemax" => floatval($_GPC["rulemax"][$key]),
				);
			}
		}
		$data["helprule2"] = serialize($data["helprule2"]);
		//自定义字段
		$data["message"] = array();
		foreach ($_GPC["field_name"] as $key => $value) {
			if (!empty($value)) {
				$data["message"][] = array(
					"field_name" => trim($_GPC["field_name"][$key]),
					"field_type" => trim($_GPC["field_type"][$key]),
					"field_required" => intval($_GPC["field_required"][$key]),
				);
			}
		}
		$data["message"] = serialize($data["message"]);

		if ($_GPC["op"] == "auth") {
			$data["rid"] = $rid;
			//修改
			$DBUtil->updateKj($data, array("id" => intval($_GPC["id"]), "uniacid" => $_W["uniacid"]));
		} else {
			if (empty($_GPC["id"])) {
				//新增
				$data["rid"] = $rid;
				$data["uniacid"] = $_W["uniacid"];
				$data["createtime"] = TIMESTAMP;
				$DBUtil->saveKj($data);
			} else {
				//修改
				$DBUtil->updateKj($data, array("rid" => $rid, "uniacid" => $_W["uniacid"]));
			}
		}
		return true;
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		//引入需要的核心方法
		require_once dirname(__FILE__) . "/core/i18n.php";
		require_once dirname(__FILE__) . "/core/db.class.php";

		//初始化对象
		$DBUtil = new DBUtil();

		//全局变量
		global $_W, $_GPC;
		if (!empty($rid)) {
			//删除
			$DBUtil->deleteKj(array("rid" => $rid, "uniacid" => $_W["uniacid"]));
			//删除其他记录
		}
	}

	public function settingsDisplay($settings) {
		//global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		//引入需要的核心方法
		require_once dirname(__FILE__) . "/core/i18n.php";
		require_once dirname(__FILE__) . "/core/db.class.php";

		//初始化对象
		$DBUtil = new DBUtil();

		//全局变量
		global $_W, $_GPC;

		//文件夹列表
		$templates = array();
		$path = MODULE_ROOT . "/template/mobile/";
		if (is_dir($path)) {
			if ($handle = opendir($path)) {
				while (false !== ($templatepath = readdir($handle))) {
					if ($templatepath != "." && $templatepath != "..") {
						if (is_dir($path . $templatepath)) {
							$templates[] = $templatepath;
						}
					}
				}
			}
		}

		//数据解析
		$settings["advs"] = base64_decode($settings["advs"]);
		$settings["advs"] = unserialize($settings["advs"]);

		if (checksubmit()) {
			//字段验证, 并获得正确的数据$data
			$data = array(
				"page_title_index" => trim($_GPC["page_title_index"]),
				"use_avatar" => trim($_GPC["use_avatar"]),
				"unsubscribe" => intval($_GPC["unsubscribe"]),
				"caiji" => intval($_GPC["caiji"]),
				"pay_hour" => intval($_GPC["pay_hour"]),
				//"auto" => intval($_GPC["auto"]),
				"qqkey" => trim($_GPC["qqkey"]),
				"pricemsg" => trim($_GPC["pricemsg"]),
				"checkmsg" => trim($_GPC["checkmsg"]),
				"openids" => trim($_GPC["openids"]),
				"express" => trim($_GPC["express"]),
				"sharetitle" => trim($_GPC["sharetitle"]),
				"shareimg" => trim($_GPC["shareimg"]),
				"sharedesc" => trim($_GPC["sharedesc"]),
				"sharelink" => trim($_GPC["sharelink"]),
				"themes" => trim($_GPC["themes"]),
			);
			//广告位
			$data["advs"] = array();
			$sort = array();
			foreach ($_GPC["adv_image"] as $key => $value) {
				if (!empty($value)) {
					array_push(
						$data["advs"],
						array(
							"image" => trim($_GPC["adv_image"][$key]),
							"link" => trim($_GPC["adv_link"][$key]),
							"sort" => intval($_GPC["adv_sort"][$key]),
						)
					);
					$sort[$key] = $_GPC["adv_sort"][$key];
				}
			}
			//排序
			array_multisort($sort, SORT_ASC, $data["advs"]);
			unset($sort);
			$data["advs"] = base64_encode(serialize($data["advs"]));
			//广告位end
			if ($this->saveSettings($data)) {
				message($i18n["pdo_success"], referer(), "success");
			} else {
				message($i18n["pdo_error"], "", "error");
			}
		}
		//这里来展示设置项表单
		include $this->template('setting');
	}
}