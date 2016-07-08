<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
if (!class_exists('PosterModel')){
    class PosterModel extends PluginModel{
        public function checkScan(){
            global $_W, $_GPC;
            $weizan_0 = m('user') -> getOpenid();
            $weizan_1 = intval($_GPC['posterid']);
            if (empty($weizan_1)){
                return;
            }
            $weizan_2 = pdo_fetch('select id,times from ' . tablename('ewei_shop_poster') . ' where id=:id and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $weizan_1));
            if (empty($weizan_2)){
                return;
            }
            $weizan_3 = intval($_GPC['mid']);
            if (empty($weizan_3)){
                return;
            }
            $weizan_4 = m('member') -> getMember($weizan_3);
            if (empty($weizan_4)){
                return;
            }
            $this -> scanTime($weizan_0, $weizan_4['openid'], $weizan_2);
        }
        public function scanTime($weizan_0, $weizan_5, $weizan_2){
            if ($weizan_0 == $weizan_5){
                return;
            }
            global $_W, $_GPC;
            $weizan_6 = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_poster_scan') . ' where openid=:openid  and posterid=:posterid and uniacid=:uniacid limit 1', array(':openid' => $weizan_0, ':posterid' => $weizan_2['id'], ':uniacid' => $_W['uniacid']));
            if ($weizan_6 <= 0){
                $weizan_7 = array('uniacid' => $_W['uniacid'], 'posterid' => $weizan_2['id'], 'openid' => $weizan_0, 'from_openid' => $weizan_5, 'scantime' => time());
                pdo_insert('ewei_shop_poster_scan', $weizan_7);
                pdo_update('ewei_shop_poster', array('times' => $weizan_2['times'] + 1), array('id' => $weizan_2['id']));
            }
        }
        public function createCommissionPoster($weizan_0, $weizan_8 = 0){
            global $_W;
            $weizan_9 = 2;
            if (!empty($weizan_8)){
                $weizan_9 = 3;
            }
            $weizan_2 = pdo_fetch('select * from ' . tablename('ewei_shop_poster') . ' where uniacid=:uniacid and type=:type and isdefault=1 limit 1', array(':uniacid' => $_W['uniacid'], ':type' => $weizan_9));
            if (empty($weizan_2)){
                return '';
            }
            $weizan_10 = m('member') -> getMember($weizan_0);
            if (empty($weizan_2)){
                return "";
            }
            $weizan_11 = $this -> getQR($weizan_2, $weizan_10, $weizan_8);
            if (empty($weizan_11)){
                return "";
            }
            return $this -> createPoster($weizan_2, $weizan_10, $weizan_11, false);
        }
        public function getFixedTicket($weizan_2, $weizan_10, $weizan_12){
            global $_W, $_GPC;
            $weizan_13 = md5("ewei_shop_poster:{$_W['uniacid']}:{$weizan_10['openid']}:{$weizan_2['id']}");
            $weizan_14 = '{"action_info":{"scene":{"scene_str":"' . $weizan_13 . '"} },"action_name":"QR_LIMIT_STR_SCENE"}';
            $weizan_15 = $weizan_12 -> fetch_token();
            $weizan_16 = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $weizan_15;
            $weizan_17 = curl_init();
            curl_setopt($weizan_17, CURLOPT_URL, $weizan_16);
            curl_setopt($weizan_17, CURLOPT_POST, 1);
            curl_setopt($weizan_17, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($weizan_17, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($weizan_17, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($weizan_17, CURLOPT_POSTFIELDS, $weizan_14);
            $weizan_18 = curl_exec($weizan_17);
            $weizan_19 = @json_decode($weizan_18, true);
            if(!is_array($weizan_19)){
                return false;
            }
            if (!empty($weizan_19['errcode'])){
                return error(-1, $weizan_19['errmsg']);
            }
            $weizan_20 = $weizan_19['ticket'];
            return array('barcode' => json_decode($weizan_14, true), 'ticket' => $weizan_20);
        }
        public function getQR($weizan_2, $weizan_10, $weizan_8 = 0){
            global $_W, $_GPC;
            $weizan_21 = $_W['acid'];
            if ($weizan_2['type'] == 1){
                $weizan_22 = m('qrcode') -> createShopQrcode($weizan_10['id'], $weizan_2['id']);
                $weizan_11 = pdo_fetch('select * from ' . tablename('ewei_shop_poster_qr') . ' where openid=:openid and acid=:acid and type=:type limit 1', array(':openid' => $weizan_10['openid'], ':acid' => $_W['acid'], ':type' => 1));
                if (empty($weizan_11)){
                    $weizan_11 = array('acid' => $weizan_21, 'openid' => $weizan_10['openid'], 'type' => 1, 'qrimg' => $weizan_22,);
                    pdo_insert('ewei_shop_poster_qr', $weizan_11);
                    $weizan_11['id'] = pdo_insertid();
                }
                $weizan_11['current_qrimg'] = $weizan_22;
                return $weizan_11;
            }else if ($weizan_2['type'] == 2){
                $weizan_23 = p('commission');
                if ($weizan_23){
                    $weizan_22 = $weizan_23 -> createMyShopQrcode($weizan_10['id'], $weizan_2['id']);
                    $weizan_11 = pdo_fetch('select * from ' . tablename('ewei_shop_poster_qr') . ' where openid=:openid and acid=:acid and type=:type limit 1', array(':openid' => $weizan_10['openid'], ':acid' => $_W['acid'], ':type' => 2));
                    if (empty($weizan_11)){
                        $weizan_11 = array('acid' => $weizan_21, 'openid' => $weizan_10['openid'], 'type' => 2, 'qrimg' => $weizan_22);
                        pdo_insert('ewei_shop_poster_qr', $weizan_11);
                        $weizan_11['id'] = pdo_insertid();
                    }
                    $weizan_11['current_qrimg'] = $weizan_22;
                    return $weizan_11;
                }
            }else if ($weizan_2['type'] == 3){
                $weizan_22 = m('qrcode') -> createGoodsQrcode($weizan_10['id'], $weizan_8, $weizan_2['id']);
                $weizan_11 = pdo_fetch('select * from ' . tablename('ewei_shop_poster_qr') . ' where openid=:openid and acid=:acid and type=:type and goodsid=:goodsid limit 1', array(':openid' => $weizan_10['openid'], ':acid' => $_W['acid'], ':type' => 3, ':goodsid' => $weizan_8));
                if (empty($weizan_11)){
                    $weizan_11 = array('acid' => $weizan_21, 'openid' => $weizan_10['openid'], 'type' => 3, 'goodsid' => $weizan_8, 'qrimg' => $weizan_22);
                    pdo_insert('ewei_shop_poster_qr', $weizan_11);
                    $weizan_11['id'] = pdo_insertid();
                }
                $weizan_11['current_qrimg'] = $weizan_22;
                return $weizan_11;
            }else if ($weizan_2['type'] == 4){
                $weizan_12 = WeAccount :: create($weizan_21);
                $weizan_11 = pdo_fetch('select * from ' . tablename('ewei_shop_poster_qr') . ' where openid=:openid and acid=:acid and type=4 limit 1', array(':openid' => $weizan_10['openid'], ':acid' => $weizan_21));
                if (empty($weizan_11)){
                    $weizan_19 = $this -> getFixedTicket($weizan_2, $weizan_10, $weizan_12);
                    if (is_error($weizan_19)){
                        return $weizan_19;
                    }
                    if (empty($weizan_19)){
                        return error(-1, '生成二维码失败');
                    }
                    $weizan_24 = $weizan_19['barcode'];
                    $weizan_20 = $weizan_19['ticket'];
                    $weizan_22 = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $weizan_20;
                    $weizan_25 = array('uniacid' => $_W['uniacid'], 'acid' => $_W['acid'], 'scene_str' => $weizan_24['action_info']['scene']['scene_str'], 'model' => 2, 'name' => 'EWEI_SHOP_POSTER_QRCODE', 'keyword' => 'EWEI_SHOP_POSTER', 'expire' => 0, 'createtime' => time(), 'status' => 1, 'url' => $weizan_19['url'], 'ticket' => $weizan_19['ticket']);
                    pdo_insert('qrcode', $weizan_25);
                    $weizan_11 = array('acid' => $weizan_21, 'openid' => $weizan_10['openid'], 'type' => 4, 'scenestr' => $weizan_24['action_info']['scene']['scene_str'], 'ticket' => $weizan_19['ticket'], 'qrimg' => $weizan_22, 'url' => $weizan_19['url']);
                    pdo_insert('ewei_shop_poster_qr', $weizan_11);
                    $weizan_11['id'] = pdo_insertid();
                    $weizan_11['current_qrimg'] = $weizan_22;
                }else{
                    $weizan_11['current_qrimg'] = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $weizan_11['ticket'];
                }
                return $weizan_11;
            }
        }
        public function getRealData($weizan_26){
            $weizan_26['left'] = intval(str_replace('px', '', $weizan_26['left'])) * 2;
            $weizan_26['top'] = intval(str_replace('px', '', $weizan_26['top'])) * 2;
            $weizan_26['width'] = intval(str_replace('px', '', $weizan_26['width'])) * 2;
            $weizan_26['height'] = intval(str_replace('px', '', $weizan_26['height'])) * 2;
            $weizan_26['size'] = intval(str_replace('px', '', $weizan_26['size'])) * 2;
            $weizan_26['src'] = tomedia($weizan_26['src']);
            return $weizan_26;
        }
        public function createImage($weizan_27){
            load() -> func('communication');
            $weizan_28 = ihttp_request($weizan_27);
            return imagecreatefromstring($weizan_28['content']);
        }
        public function mergeImage($weizan_29, $weizan_26, $weizan_27){
            $weizan_30 = $this -> createImage($weizan_27);
            $weizan_31 = imagesx($weizan_30);
            $weizan_32 = imagesy($weizan_30);
            imagecopyresized($weizan_29, $weizan_30, $weizan_26['left'], $weizan_26['top'], 0, 0, $weizan_26['width'], $weizan_26['height'], $weizan_31, $weizan_32);
            imagedestroy($weizan_30);
            return $weizan_29;
        }
        public function mergeText($weizan_29, $weizan_26, $weizan_33){
            $weizan_34 = IA_ROOT . '/addons/ewei_shop/static/fonts/msyh.ttf';
            $weizan_35 = $this -> hex2rgb($weizan_26['color']);
            $weizan_36 = imagecolorallocate($weizan_29, $weizan_35['red'], $weizan_35['green'], $weizan_35['blue']);
            imagettftext($weizan_29, $weizan_26['size'], 0, $weizan_26['left'], $weizan_26['top'] + $weizan_26['size'], $weizan_36, $weizan_34, $weizan_33);
            return $weizan_29;
        }
        function hex2rgb($weizan_37){
            if ($weizan_37[0] == '#'){
                $weizan_37 = substr($weizan_37, 1);
            }
            if (strlen($weizan_37) == 6){
                list($weizan_38, $weizan_39, $weizan_40) = array($weizan_37[0] . $weizan_37[1], $weizan_37[2] . $weizan_37[3], $weizan_37[4] . $weizan_37[5]);
            }elseif (strlen($weizan_37) == 3){
                list($weizan_38, $weizan_39, $weizan_40) = array($weizan_37[0] . $weizan_37[0], $weizan_37[1] . $weizan_37[1], $weizan_37[2] . $weizan_37[2]);
            }else{
                return false;
            }
            $weizan_38 = hexdec($weizan_38);
            $weizan_39 = hexdec($weizan_39);
            $weizan_40 = hexdec($weizan_40);
            return array('red' => $weizan_38, 'green' => $weizan_39, 'blue' => $weizan_40);
        }
        public function createPoster($weizan_2, $weizan_10, $weizan_11, $weizan_41 = true){
            global $_W;
            $weizan_42 = IA_ROOT . '/addons/ewei_shop/data/poster/' . $_W['uniacid'] . '/';
            if (!is_dir($weizan_42)){
                load() -> func('file');
                mkdirs($weizan_42);
            }
            if (!empty($weizan_11['goodsid'])){
                $weizan_43 = pdo_fetch('select id,title,thumb,commission_thumb,marketprice,productprice from ' . tablename('ewei_shop_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $weizan_11['goodsid'], ':uniacid' => $_W['uniacid']));
                if (empty($weizan_43)){
                    m('message') -> sendCustomNotice($weizan_10['openid'], '未找到商品，无法生成海报');
                    exit;
                }
            }
            $weizan_44 = md5(json_encode(array('openid' => $weizan_10['openid'], 'goodsid' => $weizan_11['goodsid'], 'bg' => $weizan_2['bg'], 'data' => $weizan_2['data'], 'version' => 1)));
            $weizan_45 = $weizan_44 . '.png';
            if (!is_file($weizan_42 . $weizan_45) || $weizan_11['qrimg'] != $weizan_11['current_qrimg']){
                set_time_limit(0);
                @ini_set('memory_limit', '256M');
                $weizan_29 = imagecreatetruecolor(640, 1008);
                $weizan_46 = $this -> createImage(tomedia($weizan_2['bg']));
                imagecopy($weizan_29, $weizan_46, 0, 0, 0, 0, 640, 1008);
                imagedestroy($weizan_46);
                $weizan_26 = json_decode(str_replace('&quot;', '\'', $weizan_2['data']), true);
                foreach ($weizan_26 as $weizan_47){
                    $weizan_47 = $this -> getRealData($weizan_47);
                    if ($weizan_47['type'] == 'head'){
                        $weizan_48 = preg_replace('/\/0$/i', '/96', $weizan_10['avatar']);
                        $weizan_29 = $this -> mergeImage($weizan_29, $weizan_47, $weizan_48);
                    }else if ($weizan_47['type'] == 'img'){
                        $weizan_29 = $this -> mergeImage($weizan_29, $weizan_47, $weizan_47['src']);
                    }else if ($weizan_47['type'] == 'qr'){
                        $weizan_29 = $this -> mergeImage($weizan_29, $weizan_47, tomedia($weizan_11['current_qrimg']));
                    }else if ($weizan_47['type'] == 'nickname'){
                        $weizan_29 = $this -> mergeText($weizan_29, $weizan_47, $weizan_10['nickname']);
                    }else{
                        if (!empty($weizan_43)){
                            if ($weizan_47['type'] == 'title'){
                                $weizan_29 = $this -> mergeText($weizan_29, $weizan_47, $weizan_43['title']);
                            }else if ($weizan_47['type'] == 'thumb'){
                                $weizan_49 = !empty($weizan_43['commission_thumb']) ? tomedia($weizan_43['commission_thumb']) : tomedia($weizan_43['thumb']);
                                $weizan_29 = $this -> mergeImage($weizan_29, $weizan_47, $weizan_49);
                            }else if ($weizan_47['type'] == 'marketprice'){
                                $weizan_29 = $this -> mergeText($weizan_29, $weizan_47, $weizan_43['marketprice']);
                            }else if ($weizan_47['type'] == 'productprice'){
                                $weizan_29 = $this -> mergeText($weizan_29, $weizan_47, $weizan_43['productprice']);
                            }
                        }
                    }
                }
                imagepng($weizan_29, $weizan_42 . $weizan_45);
                imagedestroy($weizan_29);
                if ($weizan_11['qrimg'] != $weizan_11['current_qrimg']){
                    pdo_update('ewei_shop_poster_qr', array('qrimg' => $weizan_11['current_qrimg']), array('id' => $weizan_11['id']));
                }
            }
            $weizan_30 = $_W['siteroot'] . 'addons/ewei_shop/data/poster/' . $_W['uniacid'] . '/' . $weizan_45;
            if (!$weizan_41){
                return $weizan_30;
            }
            if ($weizan_11['qrimg'] != $weizan_11['current_qrimg'] || empty($weizan_11['mediaid']) || empty($weizan_11['createtime']) || $weizan_11['createtime'] + 3600 * 24 * 3 - 7200 < time()){
                $weizan_50 = $this -> uploadImage($weizan_42 . $weizan_45);
                $weizan_11['mediaid'] = $weizan_50;
                pdo_update('ewei_shop_poster_qr', array('mediaid' => $weizan_50, 'createtime' => time()), array('id' => $weizan_11['id']));
            }
            return array('img' => $weizan_30, 'mediaid' => $weizan_11['mediaid']);
        }
        public function uploadImage($weizan_30){
            load() -> func('communication');
            $weizan_51 = m('common') -> getAccount();
            $weizan_52 = $weizan_51 -> fetch_token();
            $weizan_16 = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$weizan_52}&type=image";
            $weizan_17 = curl_init();
            $weizan_26 = array('media' => '@' . $weizan_30);
            if (version_compare(PHP_VERSION, '5.5.0', '>')){
                $weizan_26 = array('media' => curl_file_create($weizan_30));
            }
            curl_setopt($weizan_17, CURLOPT_URL, $weizan_16);
            curl_setopt($weizan_17, CURLOPT_POST, 1);
            curl_setopt($weizan_17, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($weizan_17, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($weizan_17, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($weizan_17, CURLOPT_POSTFIELDS, $weizan_26);
            $weizan_53 = @json_decode(curl_exec($weizan_17), true);
            if (!is_array($weizan_53)){
                $weizan_53 = array('media_id' => '');
            }
            curl_close($weizan_17);
            return $weizan_53['media_id'];
        }
        public function getQRByTicket($weizan_20 = ''){
            global $_W;
            if (empty($weizan_20)){
                return false;
            }
            $weizan_54 = pdo_fetchall('select * from ' . tablename('ewei_shop_poster_qr') . ' where ticket=:ticket and acid=:acid and type=4 limit 1', array(':ticket' => $weizan_20, ':acid' => $_W['acid']));
            $weizan_55 = count($weizan_54);
            if ($weizan_55 <= 0){
                return false;
            }
            if ($weizan_55 == 1){
                return $weizan_54[0];
            }
            return false;
        }
        public function checkMember($weizan_0 = ''){
            global $_W;
            $weizan_56 = WeiXinAccount :: create($_W['acid']);
            $weizan_57 = $weizan_56 -> fansQueryInfo($weizan_0);
            $weizan_57['avatar'] = $weizan_57['headimgurl'];
            load() -> model('mc');
            $weizan_58 = mc_openid2uid($weizan_0);
            if (!empty($weizan_58)){
                pdo_update('mc_members', array('nickname' => $weizan_57['nickname'], 'gender' => $weizan_57['sex'], 'nationality' => $weizan_57['country'], 'resideprovince' => $weizan_57['province'], 'residecity' => $weizan_57['city'], 'avatar' => $weizan_57['headimgurl']), array('uid' => $weizan_58));
            }
            pdo_update('mc_mapping_fans', array('nickname' => $weizan_57['nickname']), array('uniacid' => $_W['uniacid'], 'openid' => $weizan_0));
            $weizan_59 = m('member');
            $weizan_10 = $weizan_59 -> getMember($weizan_0);
            if (empty($weizan_10)){
                $weizan_60 = mc_fetch($weizan_58, array('realname', 'nickname', 'mobile', 'avatar', 'resideprovince', 'residecity', 'residedist'));
                $weizan_10 = array('uniacid' => $_W['uniacid'], 'uid' => $weizan_58, 'openid' => $weizan_0, 'realname' => $weizan_60['realname'], 'mobile' => $weizan_60['mobile'], 'nickname' => !empty($weizan_60['nickname']) ? $weizan_60['nickname'] : $weizan_57['nickname'], 'avatar' => !empty($weizan_60['avatar']) ? $weizan_60['avatar'] : $weizan_57['avatar'], 'gender' => !empty($weizan_60['gender']) ? $weizan_60['gender'] : $weizan_57['sex'], 'province' => !empty($weizan_60['resideprovince']) ? $weizan_60['resideprovince'] : $weizan_57['province'], 'city' => !empty($weizan_60['residecity']) ? $weizan_60['residecity'] : $weizan_57['city'], 'area' => $weizan_60['residedist'], 'createtime' => time(), 'status' => 0);
                pdo_insert('ewei_shop_member', $weizan_10);
                $weizan_10['id'] = pdo_insertid();
                $weizan_10['isnew'] = true;
            }else{
                $weizan_10['nickname'] = $weizan_57['nickname'];
                $weizan_10['avatar'] = $weizan_57['headimgurl'];
                $weizan_10['province'] = $weizan_57['province'];
                $weizan_10['city'] = $weizan_57['city'];
                pdo_update('ewei_shop_member', $weizan_10, array('id' => $weizan_10['id']));
                $weizan_10['isnew'] = false;
            }
            return $weizan_10;
        }
        function perms(){
            return array('poster' => array('text' => $this -> getName(), 'isplugin' => true, 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log', 'log' => '扫描记录', 'clear' => '清除缓存-log', 'setdefault' => '设置默认海报-log'));
        }
    }
}
