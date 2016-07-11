<?php
/**
 * E砍价模块微站定义
 *
 * @author healer
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Healer_kjsModuleSite extends WeModuleSite {
	//支付结果返回
	public function payResult($params) {
		//引用自动化
		require_once dirname(__FILE__) . "/core/bootstrap.php";

		if ($params["result"] == "success") {
			//订单
			$order = $DBUtil->getOrder("`uniacid`=:uniacid AND `id`=:id", array(":uniacid" => $_W["uniacid"], ":id" => $params["tid"]));

			//订单存在 | 待付款
			if (!empty($order) && $order["status"] == 1) {
				//砍价
				$kanjia = $DBUtil->getKj("`uniacid`=:uniacid AND `rid`=:rid", array(":uniacid" => $_W["uniacid"], ":rid" => $order["rid"]));

				if ($kanjia["ordermode"] == 1) {
					//订单完成 | 生成卡券 | 跳转到卡券
					if ($DBUtil->updateOrder(array("status" => 4, "type" => $params["type"], "transactionid" => $params["tag"]["transaction_id"]), array("uniacid" => $_W["uniacid"], "id" => $order["id"], "status" => 1))) {
						$DBUtil->saveKq(
							array(
								"uniacid" => $_W["uniacid"],
								"rid" => $kanjia["rid"],
								"bid" => $kanjia["bid"],
								"oid" => $order["id"],
								"openid" => $order["openid"],
								"code" => random(12, 1),
								"createtime" => TIMESTAMP,
							)
						);
					}
				} else {
					//进入等待发货 | 订单状态 | 进入订单详情
					$DBUtil->updateOrder(array("status" => 2, "type" => $params["type"], "transactionid" => $params["tag"]["transaction_id"]), array("uniacid" => $_W["uniacid"], "id" => $order["id"], "status" => 1));
				}
			}
			header("Location:" . $_W["siteroot"] . "app/" . $this->createMobileUrl("orderdetail", array('id' => $order["id"])));
			exit;
		}
	}
}
