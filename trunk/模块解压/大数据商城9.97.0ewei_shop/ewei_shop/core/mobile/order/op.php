<?php
if (!defined("IN_IA")) {
    exit("Access Denied");
}
global $_W, $_GPC;
$operation = !empty($_GPC["op"]) ? $_GPC["op"] : "display";
$openid    = m("user")->getOpenid();
$uniacid   = $_W["uniacid"];
if ($_W["isajax"]) {
    if ($operation == "cancel") {
        $orderid = intval($_GPC["orderid"]);
        $order   = pdo_fetch("select id,ordersn,openid,status,deductcredit,deductcredit2,deductprice,couponid from " . tablename("ewei_shop_order") . " where id=:id and uniacid=:uniacid and openid=:openid limit 1", array(
            ":id" => $orderid,
            ":uniacid" => $uniacid,
            ":openid" => $openid
        ));
        if (empty($order)) {
            show_json(0, "订单未找到!");
        }
        if ($order["status"] != 0) {
            show_json(0, "订单已支付，不能取消!");
        }
        if ($order['deductprice'] > 0) {
            $shop = m("common")->getSysset("shop");
            m("member")->setCredit($order["openid"], "credit1", $order['deductcredit'], array(
                '0',
                $shop["name"] . "购物返还抵扣积分 积分: {$order['deductcredit']} 抵扣金额: {$order['deductprice']} 订单号: {$order['ordersn']}"
            ));
        }
        m("order")->setDeductCredit2($order);
        if (p("coupon") && !empty($order["couponid"])) {
            p("coupon")->returnConsumeCoupon($orderid);
        }
        m("order")->setStocksAndCredits($orderid, 2);
        pdo_update("ewei_shop_order", array(
            "status" => -1,
            "canceltime" => time()
        ), array(
            "id" => $order["id"],
            "uniacid" => $uniacid
        ));
        m("notice")->sendOrderMessage($orderid);
        show_json(1);
    } else if ($operation == "complete") {
        $orderid = intval($_GPC["orderid"]);
        $order   = pdo_fetch("select id,status,openid,couponid,refundstate,refundid from " . tablename("ewei_shop_order") . " where id=:id and uniacid=:uniacid and openid=:openid limit 1", array(
            ":id" => $orderid,
            ":uniacid" => $uniacid,
            ":openid" => $openid
        ));
        if (empty($order)) {
            show_json(0, "订单未找到!");
        }
        if ($order["status"] != 2) {
            show_json(0, "订单未发货，不能确认收货!");
        }
        if ($order["refundstate"] > 0 && !empty($order["refundid"])) {
            $change_refund               = array();
            $change_refund["status"]     = -2;
            $change_refund["refundtime"] = time();
            pdo_update("ewei_shop_order_refund", $change_refund, array(
                "id" => $order["refundid"],
                "uniacid" => $uniacid
            ));
        }
        pdo_update("ewei_shop_order", array(
            "status" => 3,
            "finishtime" => time(),
            "refundstate" => 0
        ), array(
            "id" => $order["id"],
            "uniacid" => $uniacid
        ));
        m("member")->upgradeLevel($order["openid"]);
        if (p("coupon") && !empty($order["couponid"])) {
            p("coupon")->backConsumeCoupon($orderid);
        }
        m("notice")->sendOrderMessage($orderid);
        if (p("commission")) {
            p("commission")->checkOrderFinish($orderid);
        }
        show_json(1);
    } else if ($operation == "delivery") {
        if ($_W["ispost"]) {
            $refundid = intval($_GPC["id"]);
            $orderid  = intval($_GPC["orderid"]);
            $order    = pdo_fetch("select id,status,price,refundid,goodsprice,dispatchprice,deductprice,deductcredit2,finishtime,isverify,virtual,refundstate from " . tablename("ewei_shop_order") . " where id=:id and uniacid=:uniacid and openid=:openid limit 1", array(
                ":id" => $orderid,
                ":uniacid" => $uniacid,
                ":openid" => $openid
            ));
            if (empty($order)) {
                show_json(0, "订单未找到!");
            }
            $refund = pdo_fetch("select * from " . tablename("ewei_shop_order_refund") . " where id=:id and uniacid=:uniacid and orderid=:orderid limit 1", array(
                ":id" => $refundid,
                ":uniacid" => $uniacid,
                ":orderid" => $orderid
            ));
            if (empty($refund)) {
                show_json(0, "换货申请未找到!");
            }
            $time                      = time();
            $refund_data               = array();
            $refund_data["status"]     = 1;
            $refund_data["refundtime"] = $time;
            pdo_update("ewei_shop_order_refund", $refund_data, array(
                "id" => $refundid,
                "uniacid" => $uniacid
            ));
            $order_data                = array();
            $order_data["refundstate"] = 0;
            $order_data["status"]      = -1;
            $order_data["refundtime"]  = $time;
            pdo_update("ewei_shop_order", $order_data, array(
                "id" => $orderid,
                "uniacid" => $uniacid
            ));
            show_json(1, "成功!");
        }
    } else if ($operation == "express") {
        if ($_W["ispost"]) {
            $refundid   = intval($_GPC["id"]);
            $refunddata = $_GPC["refunddata"];
            $express    = $refunddata["express"];
            $expresscom = $refunddata["expresscom"];
            $expresssn  = $refunddata["expresssn"];
            if (empty($refundid)) {
                show_json(0, "参数错误!");
            }
            if (empty($expresssn)) {
                show_json(0, "请输入快递单号!");
            }
            $refund               = array();
            $refund["status"]     = 4;
            $refund["express"]    = $express;
            $refund["expresscom"] = $expresscom;
            $refund["expresssn"]  = $expresssn;
            $refund["sendtime"]   = time();
            pdo_update("ewei_shop_order_refund", $refund, array(
                "id" => $refundid,
                "uniacid" => $uniacid
            ));
            show_json(1, "成功!");
        }
    } else if ($operation == "refund") {
        $orderid = intval($_GPC["orderid"]);
        $order   = pdo_fetch("select id,status,price,refundid,goodsprice,dispatchprice,deductprice,deductcredit2,finishtime,isverify,virtual,refundstate from " . tablename("ewei_shop_order") . " where id=:id and uniacid=:uniacid and openid=:openid limit 1", array(
            ":id" => $orderid,
            ":uniacid" => $uniacid,
            ":openid" => $openid
        ));
        if (empty($order)) {
            show_json(0, "订单未找到!");
        }
        if ($order["status"] == 0) {
            show_json(0, "订单未付款，不能申请退款!");
        } else {
            if ($order["status"] == 3) {
                if (!empty($order["virtual"]) || $order["isverify"] == 1) {
                    show_json(0, "此订单不允许退款!");
                } else {
                    $tradeset   = m("common")->getSysset("trade");
                    $refunddays = intval($tradeset["refunddays"]);
                    if ($refunddays > 0) {
                        $days = intval((time() - $order["finishtime"]) / 3600 / 24);
                        if ($days > $refunddays) {
                            show_json(0, "订单完成已超过 " . $refunddays . " 天, 无法发起退款申请!");
                        }
                    } else {
                        show_json(0, "订单完成, 无法申请退款!");
                    }
                }
            }
        }
        if ($order["status"] == 1) {
            $order["refund_button"] = "退款";
        } else {
            $order["refund_button"] = "售后";
        }
        $order["refundprice"] = $order["price"] + $order["deductcredit2"];
        if ($order["status"] >= 2) {
            $order["refundprice"] -= $order["dispatchprice"];
        }
        $refundid = $order["refundid"];
        if ($_W["ispost"]) {
            if (!empty($_GPC["cancel"])) {
                $change_refund               = array();
                $change_refund["status"]     = -2;
                $change_refund["refundtime"] = time();
                pdo_update("ewei_shop_order_refund", $change_refund, array(
                    "id" => $refundid,
                    "uniacid" => $uniacid
                ));
                pdo_update("ewei_shop_order", array(
                    "refundstate" => 0
                ), array(
                    "id" => $orderid,
                    "uniacid" => $uniacid
                ));
                show_json(1);
            } else {
                $refunddata = $_GPC["refunddata"];
                $rtype      = $refunddata["rtype"];
                if ($rtype != 2) {
                    $price = $refunddata["price"];
                    if (empty($price)) {
                        show_json(2, "退款金额不能为0元");
                    }
                    if ($price > $order["refundprice"]) {
                        show_json(3, "退款金额不能超过" . $order["refundprice"] . "元");
                    }
                    $price = trim($refunddata["price"]);
                } else {
                    $price = 0;
                }
                if (empty($refunddata["images"])) {
                    $imgs = '';
                } else {
                    $imgs = iserializer($refunddata["images"]);
                }
                $refund = array(
                    "uniacid" => $uniacid,
                    "applyprice" => $price,
                    "rtype" => $rtype,
                    "reason" => trim($refunddata["reason"]),
                    "content" => trim($refunddata["content"]),
                    "imgs" => $imgs
                );
                if ($refund["rtype"] == 2) {
                    $refundstate = 2;
                } else {
                    $refundstate = 1;
                }
                if ($order["refundstate"] == 0) {
                    $refund["createtime"] = time();
                    $refund["orderid"]    = $orderid;
                    $refund["orderprice"] = $order["refundprice"];
                    $refund["refundno"]   = m("common")->createNO("order_refund", "refundno", "SR");
                    pdo_insert("ewei_shop_order_refund", $refund);
                    $refundid = pdo_insertid();
                    pdo_update("ewei_shop_order", array(
                        "refundid" => $refundid,
                        "refundstate" => $refundstate
                    ), array(
                        "id" => $orderid,
                        "uniacid" => $uniacid
                    ));
                } else {
                    pdo_update("ewei_shop_order", array(
                        "refundstate" => $refundstate
                    ), array(
                        "id" => $orderid,
                        "uniacid" => $uniacid
                    ));
                    pdo_update("ewei_shop_order_refund", $refund, array(
                        "id" => $refundid,
                        "uniacid" => $uniacid
                    ));
                }
                m("notice")->sendOrderMessage($orderid, true);
                show_json(1);
            }
        }
        $refund = false;
        $imgnum = 0;
        if ($order["refundstate"] > 0) {
            if (!empty($refundid)) {
                $refund               = pdo_fetch("select * from " . tablename("ewei_shop_order_refund") . " where id=:id and uniacid=:uniacid and orderid=:orderid limit 1", array(
                    ":id" => $refundid,
                    ":uniacid" => $uniacid,
                    ":orderid" => $orderid
                ));
                $refund["createtime"] = date("Y-m-d H:i", $refund["createtime"]);
                if (!empty($refund["refundaddress"])) {
                    $refund["refundaddress"] = iunserializer($refund["refundaddress"]);
                    $refund["address_info"]  = "收件人:" . $refund["refundaddress"]["name"];
                    $refund["address_info"] .= " 手机: " . $refund["refundaddress"]["mobile"];
                    if (!empty($refund["refundaddress"]["tel"])) {
                        $refund["address_info"] .= " 电话: " . $refund["refundaddress"]["tel"];
                    }
                    if (!empty($refund["refundaddress"]["zipcode"])) {
                        $refund["address_info"] .= " 邮政编码: " . $refund["refundaddress"]["zipcode"];
                    }
                    $refund["address_info"] .= " 退货地址: " . $refund["refundaddress"]["province"] . $refund["refundaddress"]["city"] . $refund["refundaddress"]["area"] . " " . $refund["refundaddress"]["address"];
                }
            }
            if (!empty($refund["imgs"])) {
                $refund["imgs"] = iunserializer($refund["imgs"]);
                $imgnum         = count($refund["imgs"]);
                $refund_urls    = array();
                foreach ($refund["imgs"] as $k => $v) {
                    $refund_urls[$k] = tomedia($v);
                }
                $refund["urls"] = $refund_urls;
            }
        }
        if (empty($refund)) {
            $show_price = $order["refundprice"];
        } else {
            $show_price = $refund["applyprice"];
        }
        show_json(1, array(
            "showprice" => $show_price,
            "order" => $order,
            "refund" => $refund,
            "imgnum" => $imgnum
        ));
    } else if ($operation == "comment") {
        $orderid = intval($_GPC["orderid"]);
        $order   = pdo_fetch("select id,status,iscomment from " . tablename("ewei_shop_order") . " where id=:id and uniacid=:uniacid and openid=:openid limit 1", array(
            ":id" => $orderid,
            ":uniacid" => $uniacid,
            ":openid" => $openid
        ));
        if (empty($order)) {
            show_json(0, "订单未找到!");
        }
        if ($order["status"] != 3 && $order["status"] != 4) {
            show_json(0, "订单未收货，不能评价!");
        }
        if ($order["iscomment"] >= 2) {
            show_json(0, "您已经评价了!");
        }
        if ($_W["ispost"]) {
            $member   = m("member")->getMember($openid);
            $comments = $_GPC["comments"];
            if (!is_array($comments)) {
                show_json(0, "数据出错，请重试!");
            }
            foreach ($comments as $c) {
                $old_c = pdo_fetchcolumn("select count(*) from " . tablename("ewei_shop_order_comment") . " where uniacid=:uniacid and orderid=:orderid and goodsid=:goodsid limit 1", array(
                    ":uniacid" => $_W["uniacid"],
                    ":goodsid" => $c["goodsid"],
                    ":orderid" => $orderid
                ));
                if (empty($old_c)) {
                    $comment = array(
                        "uniacid" => $uniacid,
                        "orderid" => $orderid,
                        "goodsid" => $c["goodsid"],
                        "level" => $c["level"],
                        "content" => $c["content"],
                        "images" => is_array($c["images"]) ? iserializer($c["images"]) : iserializer(array()),
                        "openid" => $openid,
                        "nickname" => $member["nickname"],
                        "headimgurl" => $member["avatar"],
                        "createtime" => time()
                    );
                    pdo_insert("ewei_shop_order_comment", $comment);
                } else {
                    $comment = array(
                        "append_content" => $c["content"],
                        "append_images" => is_array($c["images"]) ? iserializer($c["images"]) : iserializer(array())
                    );
                    pdo_update("ewei_shop_order_comment", $comment, array(
                        "uniacid" => $_W["uniacid"],
                        "goodsid" => $c["goodsid"],
                        "orderid" => $orderid
                    ));
                }
            }
            if ($order["iscomment"] <= 0) {
                $d["iscomment"] = 1;
            } else {
                $d["iscomment"] = 2;
            }
            pdo_update("ewei_shop_order", $d, array(
                "id" => $orderid,
                "uniacid" => $uniacid
            ));
            show_json(1);
        }
        $goods = pdo_fetchall("select og.id,og.goodsid,og.price,g.title,g.thumb,og.total,g.credit,og.optionid,o.title as optiontitle from " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on g.id=og.goodsid " . " left join " . tablename("ewei_shop_goods_option") . " o on o.id=og.optionid " . " where og.orderid=:orderid and og.uniacid=:uniacid ", array(
            ":uniacid" => $uniacid,
            ":orderid" => $orderid
        ));
        $goods = set_medias($goods, "thumb");
        show_json(1, array(
            "order" => $order,
            "goods" => $goods
        ));
    } else if ($operation == "delete") {
        $orderid = intval($_GPC["orderid"]);
        $order   = pdo_fetch("select id,status,refundstate,refundid from " . tablename("ewei_shop_order") . " where id=:id and uniacid=:uniacid and openid=:openid limit 1", array(
            ":id" => $orderid,
            ":uniacid" => $uniacid,
            ":openid" => $openid
        ));
        if (empty($order)) {
            show_json(0, "订单未找到!");
        }
        if ($order["status"] != 3 && $order["status"] != -1) {
            show_json(0, "订单无交易，不能删除!");
        }
        if ($order["refundstate"] > 0 && !empty($order["refundid"])) {
            $change_refund               = array();
            $change_refund["status"]     = -2;
            $change_refund["refundtime"] = time();
            pdo_update("ewei_shop_order_refund", $change_refund, array(
                "id" => $order["refundid"],
                "uniacid" => $uniacid
            ));
        }
        pdo_update("ewei_shop_order", array(
            "userdeleted" => 1,
            "refundstate" => 0
        ), array(
            "id" => $order["id"],
            "uniacid" => $uniacid
        ));
        show_json(1);
    }
}
if ($operation == "refund") {
    $tradeset = m("common")->getSysset("trade");
    include $this->template("order/refund");
} else if ($operation == "comment") {
    include $this->template("order/comment");
}
?>
