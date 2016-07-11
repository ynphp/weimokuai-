<?php
if (!defined("IN_IA")) {
    exit("Access Denied");
}
if (!class_exists("PosterModel")) {
    class PosterModel extends PluginModel
    {
        public function checkScan()
        {
            global $_W, $_GPC;
            $val2 = m("user")->getOpenid();
            $val3 = intval($_GPC["posterid"]);
            if (empty($val3)) {
                return;
            }
            $val6 = pdo_fetch("select id,times from " . tablename("ewei_shop_poster") . " where id=:id and uniacid=:uniacid limit 1", array(
                ":uniacid" => $_W['uniacid'],
                ":id" => $val3
            ));
            if (empty($val6)) {
                return;
            }
            $val10 = intval($_GPC["mid"]);
            if (empty($val10)) {
                return;
            }
            $val13 = m("member")->getMember($val10);
            if (empty($val13)) {
                return;
            }
            $this->scanTime($val2, $val13['openid'], $val6);
        }
        public function scanTime($val2, $val20, $val6)
        {
            if ($val2 == $val20) {
                return;
            }
            global $_W, $_GPC;
            $val26 = pdo_fetchcolumn("select count(*) from " . tablename("ewei_shop_poster_scan") . " where openid=:openid  and posterid=:posterid and uniacid=:uniacid limit 1", array(
                ":openid" => $val2,
                ":posterid" => $val6['id'],
                ":uniacid" => $_W['uniacid']
            ));
            if ($val26 <= 0) {
                $val31 = array(
                    'uniacid' => $_W['uniacid'],
                    "posterid" => $val6['id'],
                    'openid' => $val2,
                    "from_openid" => $val20,
                    "scantime" => time()
                );
                pdo_insert("ewei_shop_poster_scan", $val31);
                pdo_update("ewei_shop_poster", array(
                    "times" => $val6["times"] + 1
                ), array(
                    'id' => $val6['id']
                ));
            }
        }
        public function createCommissionPoster($val2, $val40 = 0)
        {
            global $_W;
            $val42 = 2;
            if (!empty($val40)) {
                $val42 = 3;
            }
            $val6 = pdo_fetch("select * from " . tablename("ewei_shop_poster") . " where uniacid=:uniacid and type=:type and isdefault=1 limit 1", array(
                ":uniacid" => $_W['uniacid'],
                ":type" => $val42
            ));
            if (empty($val6)) {
                return '';
            }
            $val49 = m("member")->getMember($val2);
            if (empty($val6)) {
                return "";
            }
            $val52 = $this->getQR($val6, $val49, $val40);
            if (empty($val52)) {
                return "";
            }
            return $this->createPoster($val6, $val49, $val52, false);
        }
        public function getFixedTicket($val6, $val49, $val62)
        {
            global $_W, $_GPC;
            $val65 = md5("ewei_shop_poster:{$_W['uniacid']}:{$val49['openid']}:{$val6['id']}");
            $val69 = '{"action_info":{"scene":{"scene_str":"' . $val65 . '"} },"action_name":"QR_LIMIT_STR_SCENE"}';
            $val71 = $val62->fetch_token();
            $val73 = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . $val71;
            $val75 = curl_init();
            curl_setopt($val75, CURLOPT_URL, $val73);
            curl_setopt($val75, CURLOPT_POST, 1);
            curl_setopt($val75, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($val75, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($val75, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($val75, CURLOPT_POSTFIELDS, $val69);
            $val84 = curl_exec($val75);
            $val86 = @json_decode($val84, true);
            if (!is_array($val86)) {
                return false;
            }
            if (!empty($val86["errcode"])) {
                return error(-1, $val86["errmsg"]);
            }
            $val91 = $val86["ticket"];
            return array(
                "barcode" => json_decode($val69, true),
                "ticket" => $val91
            );
        }
        public function getQR($val6, $val49, $val40 = 0)
        {
            global $_W, $_GPC;
            $val100 = $_W["acid"];
            if ($val6["type"] == 1) {
                $val103 = m("qrcode")->createShopQrcode($val49['id'], $val6['id']);
                $val52  = pdo_fetch("select * from " . tablename("ewei_shop_poster_qr") . " where openid=:openid and acid=:acid and type=:type limit 1", array(
                    ":openid" => $val49['openid'],
                    ":acid" => $_W["acid"],
                    ":type" => 1
                ));
                if (empty($val52)) {
                    $val52 = array(
                        "acid" => $val100,
                        'openid' => $val49['openid'],
                        "type" => 1,
                        "qrimg" => $val103
                    );
                    pdo_insert("ewei_shop_poster_qr", $val52);
                    $val52['id'] = pdo_insertid();
                }
                $val52["current_qrimg"] = $val103;
                return $val52;
            } else if ($val6["type"] == 2) {
                $val120 = p("commission");
                if ($val120) {
                    $val103 = $val120->createMyShopQrcode($val49['id'], $val6['id']);
                    $val52  = pdo_fetch("select * from " . tablename("ewei_shop_poster_qr") . " where openid=:openid and acid=:acid and type=:type limit 1", array(
                        ":openid" => $val49['openid'],
                        ":acid" => $_W["acid"],
                        ":type" => 2
                    ));
                    if (empty($val52)) {
                        $val52 = array(
                            "acid" => $val100,
                            'openid' => $val49['openid'],
                            "type" => 2,
                            "qrimg" => $val103
                        );
                        pdo_insert("ewei_shop_poster_qr", $val52);
                        $val52['id'] = pdo_insertid();
                    }
                    $val52["current_qrimg"] = $val103;
                    return $val52;
                }
            } else if ($val6["type"] == 3) {
                $val103 = m("qrcode")->createGoodsQrcode($val49['id'], $val40, $val6['id']);
                $val52  = pdo_fetch("select * from " . tablename("ewei_shop_poster_qr") . " where openid=:openid and acid=:acid and type=:type and goodsid=:goodsid limit 1", array(
                    ":openid" => $val49['openid'],
                    ":acid" => $_W["acid"],
                    ":type" => 3,
                    ":goodsid" => $val40
                ));
                if (empty($val52)) {
                    $val52 = array(
                        "acid" => $val100,
                        'openid' => $val49['openid'],
                        "type" => 3,
                        "goodsid" => $val40,
                        "qrimg" => $val103
                    );
                    pdo_insert("ewei_shop_poster_qr", $val52);
                    $val52['id'] = pdo_insertid();
                }
                $val52["current_qrimg"] = $val103;
                return $val52;
            } else if ($val6["type"] == 4) {
                $val62 = WeAccount::create($val100);
                $val52 = pdo_fetch("select * from " . tablename("ewei_shop_poster_qr") . " where openid=:openid and acid=:acid and type=4 limit 1", array(
                    ":openid" => $val49['openid'],
                    ":acid" => $val100
                ));
                if (empty($val52)) {
                    $val86 = $this->getFixedTicket($val6, $val49, $val62);
                    if (is_error($val86)) {
                        return $val86;
                    }
                    if (empty($val86)) {
                        return error(-1, "生成二维码失败");
                    }
                    $val172 = $val86["barcode"];
                    $val91  = $val86["ticket"];
                    $val103 = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $val91;
                    $val178 = array(
                        'uniacid' => $_W['uniacid'],
                        "acid" => $_W["acid"],
                        'scene_str' => $val172['action_info']['scene']['scene_str'],
                        "model" => 2,
                        "name" => "EWEI_SHOP_POSTER_QRCODE",
                        "keyword" => "EWEI_SHOP_POSTER",
                        "expire" => 0,
                        "createtime" => time(),
                        "status" => 1,
                        "url" => $val86["url"],
                        "ticket" => $val86["ticket"]
                    );
                    pdo_insert("qrcode", $val178);
                    $val52 = array(
                        "acid" => $val100,
                        'openid' => $val49['openid'],
                        "type" => 4,
                        "scenestr" => $val172['action_info']['scene']['scene_str'],
                        "ticket" => $val86["ticket"],
                        "qrimg" => $val103,
                        "url" => $val86["url"]
                    );
                    pdo_insert("ewei_shop_poster_qr", $val52);
                    $val52['id']            = pdo_insertid();
                    $val52["current_qrimg"] = $val103;
                } else {
                    $val52["current_qrimg"] = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $val52["ticket"];
                }
                return $val52;
            }
        }
        public function getRealData($val199)
        {
            $val199["left"]   = intval(str_replace("px", '', $val199["left"])) * 2;
            $val199["top"]    = intval(str_replace("px", '', $val199["top"])) * 2;
            $val199["width"]  = intval(str_replace("px", '', $val199["width"])) * 2;
            $val199["height"] = intval(str_replace("px", '', $val199["height"])) * 2;
            $val199["size"]   = intval(str_replace("px", '', $val199["size"])) * 2;
            $val199["src"]    = tomedia($val199["src"]);
            return $val199;
        }
        public function createImage($val213)
        {
            load()->func("communication");
            $val214 = ihttp_request($val213);
            if ($val214["code"] == 200 && !empty($val214["content"])) {
                return imagecreatefromstring($val214["content"]);
            }
            $val219 = 0;
            while ($val219 < 3) {
                $val214 = ihttp_request($val213);
                if ($val214["code"] == 200 && !empty($val214["content"])) {
                    return imagecreatefromstring($val214["content"]);
                }
                $val219++;
            }
            return "";
        }
        public function mergeImage($val227, $val199, $val213)
        {
            $val230 = $this->createImage($val213);
            $val232 = imagesx($val230);
            $val234 = imagesy($val230);
            imagecopyresized($val227, $val230, $val199["left"], $val199["top"], 0, 0, $val199["width"], $val199["height"], $val232, $val234);
            imagedestroy($val230);
            return $val227;
        }
        public function mergeText($val227, $val199, $val248)
        {
            $val249 = IA_ROOT . "/addons/ewei_shop/static/fonts/msyh.ttf";
            $val250 = $this->hex2rgb($val199["color"]);
            $val252 = imagecolorallocate($val227, $val250["red"], $val250["green"], $val250["blue"]);
            imagettftext($val227, $val199["size"], 0, $val199["left"], $val199["top"] + $val199["size"], $val252, $val249, $val248);
            return $val227;
        }
        function hex2rgb($val266)
        {
            if ($val266[0] == "#") {
                $val266 = substr($val266, 1);
            }
            if (strlen($val266) == 6) {
                list($val271, $val272, $val273) = array(
                    $val266[0] . $val266[1],
                    $val266[2] . $val266[3],
                    $val266[4] . $val266[5]
                );
            } elseif (strlen($val266) == 3) {
                list($val271, $val272, $val273) = array(
                    $val266[0] . $val266[0],
                    $val266[1] . $val266[1],
                    $val266[2] . $val266[2]
                );
            } else {
                return false;
            }
            $val271 = hexdec($val271);
            $val272 = hexdec($val272);
            $val273 = hexdec($val273);
            return array(
                "red" => $val271,
                "green" => $val272,
                "blue" => $val273
            );
        }
        public function createPoster($val6, $val49, $val52, $val302 = true)
        {
            global $_W;
            $val304 = IA_ROOT . "/addons/ewei_shop/data/poster/" . $_W['uniacid'] . "/";
            if (!is_dir($val304)) {
                load()->func("file");
                mkdirs($val304);
            }
            if (!empty($val52["goodsid"])) {
                $val309 = pdo_fetch("select id,title,thumb,commission_thumb,marketprice,productprice from " . tablename("ewei_shop_goods") . " where id=:id and uniacid=:uniacid limit 1", array(
                    ":id" => $val52["goodsid"],
                    ":uniacid" => $_W['uniacid']
                ));
                if (empty($val309)) {
                    m("message")->sendCustomNotice($val49['openid'], "未找到商品，无法生成海报");
                    exit;
                }
            }
            $val314 = md5(json_encode(array(
                'openid' => $val49['openid'],
                "goodsid" => $val52["goodsid"],
                "bg" => $val6["bg"],
                "data" => $val6["data"],
                "version" => 1
            )));
            $val319 = $val314 . ".png";
            if (!is_file($val304 . $val319) || $val52["qrimg"] != $val52["current_qrimg"]) {
                set_time_limit(0);
                @ini_set("memory_limit", "256M");
                $val227 = imagecreatetruecolor(640, 1008);
                $val326 = $this->createImage(tomedia($val6["bg"]));
                imagecopy($val227, $val326, 0, 0, 0, 0, 640, 1008);
                imagedestroy($val326);
                $val199 = json_decode(str_replace("&quot;", "'", $val6["data"]), true);
                foreach ($val199 as $val334) {
                    $val334 = $this->getRealData($val334);
                    if ($val334["type"] == "head") {
                        $val338 = preg_replace("/\/0$/i", "/96", $val49["avatar"]);
                        $val227 = $this->mergeImage($val227, $val334, $val338);
                    } else if ($val334["type"] == "img") {
                        $val227 = $this->mergeImage($val227, $val334, $val334["src"]);
                    } else if ($val334["type"] == "qr") {
                        $val227 = $this->mergeImage($val227, $val334, tomedia($val52["current_qrimg"]));
                    } else if ($val334["type"] == "nickname") {
                        $val227 = $this->mergeText($val227, $val334, $val49["nickname"]);
                    } else {
                        if (!empty($val309)) {
                            if ($val334["type"] == "title") {
                                $val227 = $this->mergeText($val227, $val334, $val309["title"]);
                            } else if ($val334["type"] == "thumb") {
                                $val367 = !empty($val309["commission_thumb"]) ? tomedia($val309["commission_thumb"]) : tomedia($val309["thumb"]);
                                $val227 = $this->mergeImage($val227, $val334, $val367);
                            } else if ($val334["type"] == "marketprice") {
                                $val227 = $this->mergeText($val227, $val334, $val309["marketprice"]);
                            } else if ($val334["type"] == "productprice") {
                                $val227 = $this->mergeText($val227, $val334, $val309["productprice"]);
                            }
                        }
                    }
                }
                imagepng($val227, $val304 . $val319);
                imagedestroy($val227);
                if ($val52["qrimg"] != $val52["current_qrimg"]) {
                    pdo_update("ewei_shop_poster_qr", array(
                        "qrimg" => $val52["current_qrimg"]
                    ), array(
                        'id' => $val52['id']
                    ));
                }
            }
            $val230 = $_W["siteroot"] . "addons/ewei_shop/data/poster/" . $_W['uniacid'] . "/" . $val319;
            if (!$val302) {
                return $val230;
            }
            if ($val52["qrimg"] != $val52["current_qrimg"] || empty($val52["mediaid"]) || empty($val52["createtime"]) || $val52["createtime"] + 3600 * 24 * 3 - 7200 < time()) {
                $val404           = $this->uploadImage($val304 . $val319);
                $val52["mediaid"] = $val404;
                pdo_update("ewei_shop_poster_qr", array(
                    "mediaid" => $val404,
                    "createtime" => time()
                ), array(
                    'id' => $val52['id']
                ));
            }
            return array(
                "img" => $val230,
                "mediaid" => $val52["mediaid"]
            );
        }
        public function uploadImage($val230)
        {
            load()->func("communication");
            $val414 = m("common")->getAccount();
            $val415 = $val414->fetch_token();
            $val73  = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$val415}&type=image";
            $val75  = curl_init();
            $val199 = array(
                "media" => "@" . $val230
            );
            if (version_compare(PHP_VERSION, "5.5.0", ">")) {
                $val199 = array(
                    "media" => curl_file_create($val230)
                );
            }
            curl_setopt($val75, CURLOPT_URL, $val73);
            curl_setopt($val75, CURLOPT_POST, 1);
            curl_setopt($val75, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($val75, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($val75, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($val75, CURLOPT_POSTFIELDS, $val199);
            $val432 = @json_decode(curl_exec($val75), true);
            if (!is_array($val432)) {
                $val432 = array(
                    "media_id" => ''
                );
            }
            curl_close($val75);
            return $val432["media_id"];
        }
        public function getQRByTicket($val91 = '')
        {
            global $_W;
            if (empty($val91)) {
                return false;
            }
            $val441 = pdo_fetchall("select * from " . tablename("ewei_shop_poster_qr") . " where ticket=:ticket and acid=:acid and type=4 limit 1", array(
                ":ticket" => $val91,
                ":acid" => $_W["acid"]
            ));
            $val444 = count($val441);
            if ($val444 <= 0) {
                return false;
            }
            if ($val444 == 1) {
                return $val441[0];
            }
            return false;
        }
        public function checkMember($val2 = '')
        {
            global $_W;
            $val451           = WeiXinAccount::create($_W["acid"]);
            $val453           = $val451->fansQueryInfo($val2);
            $val453["avatar"] = $val453["headimgurl"];
            load()->model("mc");
            $val457 = mc_openid2uid($val2);
            if (!empty($val457)) {
                pdo_update("mc_members", array(
                    "nickname" => $val453["nickname"],
                    "gender" => $val453["sex"],
                    "nationality" => $val453["country"],
                    "resideprovince" => $val453["province"],
                    "residecity" => $val453["city"],
                    "avatar" => $val453["headimgurl"]
                ), array(
                    "uid" => $val457
                ));
            }
            pdo_update("mc_mapping_fans", array(
                "nickname" => $val453["nickname"]
            ), array(
                'uniacid' => $_W['uniacid'],
                'openid' => $val2
            ));
            $val470 = m("member");
            $val49  = $val470->getMember($val2);
            if (empty($val49)) {
                $val474 = mc_fetch($val457, array(
                    "realname",
                    "nickname",
                    "mobile",
                    "avatar",
                    "resideprovince",
                    "residecity",
                    "residedist"
                ));
                $val49  = array(
                    'uniacid' => $_W['uniacid'],
                    "uid" => $val457,
                    'openid' => $val2,
                    "realname" => $val474["realname"],
                    "mobile" => $val474["mobile"],
                    "nickname" => !empty($val474["nickname"]) ? $val474["nickname"] : $val453["nickname"],
                    "avatar" => !empty($val474["avatar"]) ? $val474["avatar"] : $val453["avatar"],
                    "gender" => !empty($val474["gender"]) ? $val474["gender"] : $val453["sex"],
                    "province" => !empty($val474["resideprovince"]) ? $val474["resideprovince"] : $val453["province"],
                    "city" => !empty($val474["residecity"]) ? $val474["residecity"] : $val453["city"],
                    "area" => $val474["residedist"],
                    "createtime" => time(),
                    "status" => 0
                );
                pdo_insert("ewei_shop_member", $val49);
                $val49['id']    = pdo_insertid();
                $val49["isnew"] = true;
            } else {
                $val49["nickname"] = $val453["nickname"];
                $val49["avatar"]   = $val453["headimgurl"];
                $val49["province"] = $val453["province"];
                $val49["city"]     = $val453["city"];
                pdo_update("ewei_shop_member", $val49, array(
                    'id' => $val49['id']
                ));
                $val49["isnew"] = false;
            }
            return $val49;
        }
        function perms()
        {
            return array(
                "poster" => array(
                    "text" => $this->getName(),
                    "isplugin" => true,
                    "view" => "浏览",
                    "add" => "添加-log",
                    "edit" => "修改-log",
                    "delete" => "删除-log",
                    "log" => "扫描记录",
                    "clear" => "清除缓存-log",
                    "setdefault" => "设置默认海报-log"
                )
            );
        }
    }
}
?>