<?php
//引用自动化
require_once dirname(__FILE__) . "/../../core/bootstrap.php";
//手机端自动化
require_once dirname(__FILE__) . "/../../core/mobilebootstrap.php";

//分页
$page = max(1, intval($_GPC["page"]));
$pagesize = 10;

//分支
$ops = array("now", "past");
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : $ops[0];

//砍价活动
if ($op == "past") {
	//往期活动
	$list = $DBUtil->getKjs("`uniacid`=:uniacid AND `status`=:status AND `endtime`<=:endtime", array(":uniacid" => $_W["uniacid"], ":status" => 1, ":endtime" => TIMESTAMP), "`tuijian` DESC, `sort` DESC, `endtime` DESC", $page, $pagesize);
} else {
	//正在进行
	$list = $DBUtil->getKjs("`uniacid`=:uniacid AND `status`=:status AND `endtime`> :endtime", array(":uniacid" => $_W["uniacid"], ":status" => 1, ":endtime" => TIMESTAMP), "`tuijian` DESC, `sort` DESC, `endtime` DESC", $page, $pagesize);
}

//post
if ($_W["ispost"]) {
	$temp = array();
	foreach ($list as $key => $value) {
		$temp[] = array(
			"href" => $this->createMobileUrl("detail", array("rid" => $value["rid"])),
			"rid" => $value["rid"],
			"cover" => tomedia($value["cover"]),
			"tuijian" => $value["tuijian"],
			"title" => $value["title"],
			"pprice" => $value["pprice"],
			"ppricemin" => $value["ppricemin"],
			"pweight" => $value["pweight"],
			"psold" => $value["psold"],
			"join" => $value["join"],
			"help" => $value["help"],
		);
	}
	echo json_encode($temp);
	unset($list, $temp);
	exit;
}

//页面标题
$_W["page"]["title"] = empty($settings["page_title_index"]) ? $i18n["page_title_index"] : $settings["page_title_index"];

//加载页面
include $this->template($settings["themes"] . "/index");
?>
