<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
if (!class_exists('PosteraModel')){
    class PosteraModel extends PluginModel{
        public function getSceneTicket($weizan_0, $weizan_1){
            global $_W, $_GPC;
            $weizan_2 = m('common') -> getAccount();
            $weizan_3 = '{"expire_seconds":' . $weizan_0 . ',"action_info":{"scene":{"scene_id":' . $weizan_1 . '}},"action_name":"QR_SCENE"}';
            $weizan_4 = $weizan_2 -> fetch_token();
            $weizan_5 = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $weizan_4;
            $weizan_6 = curl_init();
            curl_setopt($weizan_6, CURLOPT_URL, $weizan_5);
            curl_setopt($weizan_6, CURLOPT_POST, 1);
            curl_setopt($weizan_6, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($weizan_6, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($weizan_6, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($weizan_6, CURLOPT_POSTFIELDS, $weizan_3);
            $weizan_7 = curl_exec($weizan_6);
            $weizan_8 = @json_decode($weizan_7, true);
            if (!is_array($weizan_8)){
                return false;
            }
            if (!empty($weizan_8['errcode'])){
                return error(-1, $weizan_8['errmsg']);
            }
            $weizan_9 = $weizan_8['ticket'];
            return array('barcode' => json_decode($weizan_3, true), 'ticket' => $weizan_9);
        }
        function getSceneID(){
            global $_W;
            $weizan_10 = $_W['acid'];
            $weizan_11 = 1;
            $weizan_12 = 2147483647;
            $weizan_1 = rand($weizan_11, $weizan_12);
            if(empty($weizan_1)){
                $weizan_1 = rand($weizan_11, $weizan_12);
            } while(1){
                $weizan_13 = pdo_fetchcolumn('select count(*) from ' . tablename('qrcode') . ' where qrcid=:qrcid and acid=:acid and model=0 limit 1', array(':qrcid' => $weizan_1, ':acid' => $weizan_10));
                if($weizan_13 <= 0){
                    break;
                }
                $weizan_1 = rand($weizan_11, $weizan_12);
                if(empty($weizan_1)){
                    $weizan_1 = rand($weizan_11, $weizan_12);
                }
            }
            return $weizan_1;
        }
        public function getQR($weizan_14, $weizan_15){
            global $_W, $_GPC;
            $weizan_10 = $_W['acid'];
            $weizan_16 = time();
            $weizan_17 = $weizan_14['timeend'];
            $weizan_0 = $weizan_17 - $weizan_16;
            if($weizan_0 > 86400 * 30 -15){
                $weizan_0 = 86400 * 30 -15;
            }
            $weizan_18 = $weizan_16 + $weizan_0;
            $weizan_19 = pdo_fetch('select * from ' . tablename('ewei_shop_postera_qr') . ' where openid=:openid and acid=:acid and posterid=:posterid limit 1', array(':openid' => $weizan_15['openid'], ':acid' => $weizan_10, ':posterid' => $weizan_14['id']));
            if (empty($weizan_19)){
                $weizan_19['current_qrimg'] = '';
                $weizan_1 = $this -> getSceneID();
                $weizan_8 = $this -> getSceneTicket($weizan_0, $weizan_1);
                if (is_error($weizan_8)){
                    return $weizan_8;
                }
                if (empty($weizan_8)){
                    return error(-1, '生成二维码失败');
                }
                $weizan_20 = $weizan_8['barcode'];
                $weizan_9 = $weizan_8['ticket'];
                $weizan_21 = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $weizan_9;
                $weizan_22 = array('uniacid' => $_W['uniacid'], 'acid' => $_W['acid'], 'qrcid' => $weizan_1, 'model' => 0, 'name' => 'EWEI_SHOP_POSTERA_QRCODE', 'keyword' => 'EWEI_SHOP_POSTERA', 'expire' => $weizan_0, 'createtime' => time(), 'status' => 1, 'url' => $weizan_8['url'], 'ticket' => $weizan_8['ticket']);
                pdo_insert('qrcode', $weizan_22);
                $weizan_19 = array('acid' => $weizan_10, 'openid' => $weizan_15['openid'], 'sceneid' => $weizan_1, 'type' => $weizan_14['type'], 'ticket' => $weizan_8['ticket'], 'qrimg' => $weizan_21, 'posterid' => $weizan_14['id'], 'expire' => $weizan_0, 'url' => $weizan_8['url'], 'goodsid' => $weizan_14['goodsid'], 'endtime' => $weizan_18);
                pdo_insert('ewei_shop_postera_qr', $weizan_19);
                $weizan_19['id'] = pdo_insertid();
            }else{
                $weizan_19['current_qrimg'] = $weizan_19['qrimg'];
                if(time() > $weizan_19['endtime']){
                    $weizan_1 = $weizan_19['sceneid'];
                    $weizan_8 = $this -> getSceneTicket($weizan_0, $weizan_1);
                    if (is_error($weizan_8)){
                        return $weizan_8;
                    }
                    if (empty($weizan_8)){
                        return error(-1, '生成二维码失败');
                    }
                    $weizan_20 = $weizan_8['barcode'];
                    $weizan_9 = $weizan_8['ticket'];
                    $weizan_21 = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $weizan_9;
                    pdo_update('qrcode', array('ticket' => $weizan_8['ticket'], 'url' => $weizan_8['url']), array('acid' => $_W['acid'], 'qrcid' => $weizan_1));
                    pdo_update('ewei_shop_postera_qr', array('ticket' => $weizan_9, 'qrimg' => $weizan_21, 'url' => $weizan_8['url'], 'endtime' => $weizan_18), array('id' => $weizan_19['id']));
                    $weizan_19['ticket'] = $weizan_9;
                    $weizan_19['qrimg'] = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $weizan_19['ticket'];
                }
            }
            return $weizan_19;
        }
        public function getRealData($weizan_23){
            $weizan_23['left'] = intval(str_replace('px', '', $weizan_23['left'])) * 2;
            $weizan_23['top'] = intval(str_replace('px', '', $weizan_23['top'])) * 2;
            $weizan_23['width'] = intval(str_replace('px', '', $weizan_23['width'])) * 2;
            $weizan_23['height'] = intval(str_replace('px', '', $weizan_23['height'])) * 2;
            $weizan_23['size'] = intval(str_replace('px', '', $weizan_23['size'])) * 2;
            $weizan_23['src'] = tomedia($weizan_23['src']);
            return $weizan_23;
        }
        public function createImage($weizan_24){
            load() -> func('communication');
            $weizan_25 = ihttp_request($weizan_24);
            return imagecreatefromstring($weizan_25['content']);
        }
        public function mergeImage($weizan_26, $weizan_23, $weizan_24){
            $weizan_27 = $this -> createImage($weizan_24);
            $weizan_28 = imagesx($weizan_27);
            $weizan_29 = imagesy($weizan_27);
            imagecopyresized($weizan_26, $weizan_27, $weizan_23['left'], $weizan_23['top'], 0, 0, $weizan_23['width'], $weizan_23['height'], $weizan_28, $weizan_29);
            imagedestroy($weizan_27);
            return $weizan_26;
        }
        public function mergeText($weizan_26, $weizan_23, $weizan_30){
            $weizan_31 = IA_ROOT . '/addons/ewei_shop/static/fonts/msyh.ttf';
            $weizan_32 = $this -> hex2rgb($weizan_23['color']);
            $weizan_33 = imagecolorallocate($weizan_26, $weizan_32['red'], $weizan_32['green'], $weizan_32['blue']);
            imagettftext($weizan_26, $weizan_23['size'], 0, $weizan_23['left'], $weizan_23['top'] + $weizan_23['size'], $weizan_33, $weizan_31, $weizan_30);
            return $weizan_26;
        }
        function hex2rgb($weizan_34){
            if ($weizan_34[0] == '#'){
                $weizan_34 = substr($weizan_34, 1);
            }
            if (strlen($weizan_34) == 6){
                list($weizan_35, $weizan_36, $weizan_37) = array($weizan_34[0] . $weizan_34[1], $weizan_34[2] . $weizan_34[3], $weizan_34[4] . $weizan_34[5]);
            }elseif (strlen($weizan_34) == 3){
                list($weizan_35, $weizan_36, $weizan_37) = array($weizan_34[0] . $weizan_34[0], $weizan_34[1] . $weizan_34[1], $weizan_34[2] . $weizan_34[2]);
            }else{
                return false;
            }
            $weizan_35 = hexdec($weizan_35);
            $weizan_36 = hexdec($weizan_36);
            $weizan_37 = hexdec($weizan_37);
            return array('red' => $weizan_35, 'green' => $weizan_36, 'blue' => $weizan_37);
        }
        public function createPoster($weizan_14, $weizan_15, $weizan_19, $weizan_38 = true){
            global $_W;
            $weizan_39 = IA_ROOT . '/addons/ewei_shop/data/postera/' . $_W['uniacid'] . '/';
            if (!is_dir($weizan_39)){
                load() -> func('file');
                mkdirs($weizan_39);
            }
            if (!empty($weizan_19['goodsid'])){
                $weizan_40 = pdo_fetch('select id,title,thumb,commission_thumb,marketprice,productprice from ' . tablename('ewei_shop_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $weizan_19['goodsid'], ':uniacid' => $_W['uniacid']));
                if (empty($weizan_40)){
                    m('message') -> sendCustomNotice($weizan_15['openid'], '未找到商品，无法生成海报');
                    exit;
                }
            }
            $weizan_41 = md5(json_encode(array('openid' => $weizan_15['openid'], 'goodsid' => $weizan_19['goodsid'], 'bg' => $weizan_14['bg'], 'data' => $weizan_14['data'], 'version' => 1)));
            $weizan_42 = $weizan_41 . '.png';
            if (!is_file($weizan_39 . $weizan_42) || $weizan_19['qrimg'] != $weizan_19['current_qrimg']){
                set_time_limit(0);
                @ini_set('memory_limit', '256M');
                $weizan_26 = imagecreatetruecolor(640, 1008);
                $weizan_43 = $this -> createImage(tomedia($weizan_14['bg']));
                imagecopy($weizan_26, $weizan_43, 0, 0, 0, 0, 640, 1008);
                imagedestroy($weizan_43);
                $weizan_23 = json_decode(str_replace('&quot;', '\'', $weizan_14['data']), true);
                foreach ($weizan_23 as $weizan_44){
                    $weizan_44 = $this -> getRealData($weizan_44);
                    if ($weizan_44['type'] == 'head'){
                        $weizan_45 = preg_replace('/\/0$/i', '/96', $weizan_15['avatar']);
                        $weizan_26 = $this -> mergeImage($weizan_26, $weizan_44, $weizan_45);
                    }else if ($weizan_44['type'] == 'time'){
                        $weizan_16 = date('Y-m-d H:i', $weizan_19['endtime']);
                        $weizan_26 = $this -> mergeText($weizan_26, $weizan_44, $weizan_16);
                    }else if ($weizan_44['type'] == 'img'){
                        $weizan_26 = $this -> mergeImage($weizan_26, $weizan_44, $weizan_44['src']);
                    }else if ($weizan_44['type'] == 'qr'){
                        $weizan_26 = $this -> mergeImage($weizan_26, $weizan_44, tomedia($weizan_19['qrimg']));
                    }else if ($weizan_44['type'] == 'nickname'){
                        $weizan_26 = $this -> mergeText($weizan_26, $weizan_44, $weizan_15['nickname']);
                    }else{
                        if (!empty($weizan_40)){
                            if ($weizan_44['type'] == 'title'){
                                $weizan_26 = $this -> mergeText($weizan_26, $weizan_44, $weizan_40['title']);
                            }else if ($weizan_44['type'] == 'thumb'){
                                $weizan_46 = !empty($weizan_40['commission_thumb']) ? tomedia($weizan_40['commission_thumb']) : tomedia($weizan_40['thumb']);
                                $weizan_26 = $this -> mergeImage($weizan_26, $weizan_44, $weizan_46);
                            }else if ($weizan_44['type'] == 'marketprice'){
                                $weizan_26 = $this -> mergeText($weizan_26, $weizan_44, $weizan_40['marketprice']);
                            }else if ($weizan_44['type'] == 'productprice'){
                                $weizan_26 = $this -> mergeText($weizan_26, $weizan_44, $weizan_40['productprice']);
                            }
                        }
                    }
                }
                imagepng($weizan_26, $weizan_39 . $weizan_42);
                imagedestroy($weizan_26);
            }
            $weizan_27 = $_W['siteroot'] . 'addons/ewei_shop/data/poster/' . $_W['uniacid'] . '/' . $weizan_42;
            if (!$weizan_38){
                return $weizan_27;
            }
            if ($weizan_19['qrimg'] != $weizan_19['current_qrimg'] || empty($weizan_19['mediaid']) || empty($weizan_19['createtime']) || $weizan_19['createtime'] + 3600 * 24 * 3 - 7200 < time()){
                $weizan_47 = $this -> uploadImage($weizan_39 . $weizan_42);
                $weizan_19['mediaid'] = $weizan_47;
                pdo_update('ewei_shop_postera_qr', array('mediaid' => $weizan_47, 'createtime' => time()), array('id' => $weizan_19['id']));
            }
            return array('img' => $weizan_27, 'mediaid' => $weizan_19['mediaid']);
        }
        public function uploadImage($weizan_27){
            load() -> func('communication');
            $weizan_2 = m('common') -> getAccount();
            $weizan_48 = $weizan_2 -> fetch_token();
            $weizan_5 = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$weizan_48}&type=image";
            $weizan_6 = curl_init();
            $weizan_23 = array('media' => '@' . $weizan_27);
            if (version_compare(PHP_VERSION, '5.5.0', '>')){
                $weizan_23 = array('media' => curl_file_create($weizan_27));
            }
            curl_setopt($weizan_6, CURLOPT_URL, $weizan_5);
            curl_setopt($weizan_6, CURLOPT_POST, 1);
            curl_setopt($weizan_6, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($weizan_6, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($weizan_6, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($weizan_6, CURLOPT_POSTFIELDS, $weizan_23);
            $weizan_49 = @json_decode(curl_exec($weizan_6), true);
            if (!is_array($weizan_49)){
                $weizan_49 = array('media_id' => '');
            }
            curl_close($weizan_6);
            return $weizan_49['media_id'];
        }
        public function getQRByTicket($weizan_9 = ''){
            global $_W;
            if (empty($weizan_9)){
                return false;
            }
            $weizan_50 = pdo_fetchall('select * from ' . tablename('ewei_shop_postera_qr') . ' where ticket=:ticket and acid=:acid limit 1', array(':ticket' => $weizan_9, ':acid' => $_W['acid']));
            $weizan_13 = count($weizan_50);
            if ($weizan_13 <= 0){
                return false;
            }
            if ($weizan_13 == 1){
                return $weizan_50[0];
            }
            return false;
        }
        public function checkMember($weizan_51 = ''){
            global $_W;
            $weizan_52 = WeiXinAccount :: create($_W['acid']);
            $weizan_53 = $weizan_52 -> fansQueryInfo($weizan_51);
            $weizan_53['avatar'] = $weizan_53['headimgurl'];
            load() -> model('mc');
            $weizan_54 = mc_openid2uid($weizan_51);
            if (!empty($weizan_54)){
                pdo_update('mc_members', array('nickname' => $weizan_53['nickname'], 'gender' => $weizan_53['sex'], 'nationality' => $weizan_53['country'], 'resideprovince' => $weizan_53['province'], 'residecity' => $weizan_53['city'], 'avatar' => $weizan_53['headimgurl']), array('uid' => $weizan_54));
            }
            pdo_update('mc_mapping_fans', array('nickname' => $weizan_53['nickname']), array('uniacid' => $_W['uniacid'], 'openid' => $weizan_51));
            $weizan_55 = m('member');
            $weizan_15 = $weizan_55 -> getMember($weizan_51);
            if (empty($weizan_15)){
                $weizan_56 = mc_fetch($weizan_54, array('realname', 'nickname', 'mobile', 'avatar', 'resideprovince', 'residecity', 'residedist'));
                $weizan_15 = array('uniacid' => $_W['uniacid'], 'uid' => $weizan_54, 'openid' => $weizan_51, 'realname' => $weizan_56['realname'], 'mobile' => $weizan_56['mobile'], 'nickname' => !empty($weizan_56['nickname']) ? $weizan_56['nickname'] : $weizan_53['nickname'], 'avatar' => !empty($weizan_56['avatar']) ? $weizan_56['avatar'] : $weizan_53['avatar'], 'gender' => !empty($weizan_56['gender']) ? $weizan_56['gender'] : $weizan_53['sex'], 'province' => !empty($weizan_56['resideprovince']) ? $weizan_56['resideprovince'] : $weizan_53['province'], 'city' => !empty($weizan_56['residecity']) ? $weizan_56['residecity'] : $weizan_53['city'], 'area' => $weizan_56['residedist'], 'createtime' => time(), 'status' => 0);
                pdo_insert('ewei_shop_member', $weizan_15);
                $weizan_15['id'] = pdo_insertid();
                $weizan_15['isnew'] = true;
            }else{
                $weizan_15['nickname'] = $weizan_53['nickname'];
                $weizan_15['avatar'] = $weizan_53['headimgurl'];
                $weizan_15['province'] = $weizan_53['province'];
                $weizan_15['city'] = $weizan_53['city'];
                pdo_update('ewei_shop_member', $weizan_15, array('id' => $weizan_15['id']));
                $weizan_15['isnew'] = false;
            }
            return $weizan_15;
        }
        function perms(){
            return array('postera' => array('text' => $this -> getName(), 'isplugin' => true, 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log', 'log' => '扫描记录', 'clear' => '清除缓存-log', 'setdefault' => '设置默认海报-log'));
        }
    }
}
