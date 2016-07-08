<?php
/**
 * 【超人】积分商城模块定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Superman_creditmall_doMobileProfile extends Superman_creditmallModuleSite {
    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display', 'setting', 'logout'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '个人中心';
            //我的积分
            if ($_W['member']['uid']) {
                $mycredit = superman_mycredit($_W['member']['uid']);
                //待支付角标
                $filter = array(
                    'uid' => $_W['member']['uid'],
                    'status' => 0,
                );
                $order_total['no_pay'] = superman_order_count($filter);
                //待收货角标
                $filter = array(
                    'uid' => $_W['member']['uid'],
                    'in_status' => array(1,2),
                );
                $order_total['no_receive'] = superman_order_count($filter);
                //待评价角标
                $filter = array(
                    'uid' => $_W['member']['uid'],
                    'status' => 3,
                );
                $order_total['no_comment'] = superman_order_count($filter);
            } else {
                for ($i=0; $i<4; $i++) {
                    $mycredit[] = array(
                        'value' => 0,
                        'title' => '',
                    );
                }
                $order_total['no_pay'] = 0;
                $order_total['no_receive'] = 0;
                $order_total['no_comment'] = 0;
            }
            //print_r($order_total);

            //推荐商品
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
                'ishot' => 1,
                'in_type' => array(1,2,3,4,7)
            );
            $hotlist = superman_product_fetchall($filter, '', 0, 30);
            if ($hotlist) {
                foreach ($hotlist as &$item) {
                    superman_product_set($item);
                }
                unset($item);
            }
            //var_dump($hotlist)

        } else if ($act == 'setting') {
            $header_title = '个人信息';
            checkauth();
            $has_email = $this->member['email']?true:false;
            if ($this->member['email'] == md5($_W['fans']['openid']).'@we7.cc') {
                $has_email = false;
            }
            if (checksubmit('submit')) {
                //表单验证
                $serverId = $_GPC['serverId'];
                $nickname = $_GPC['nickname'];
                $mobile = $_GPC['mobile'];
                $email = $_GPC['email'];
                if ($mobile != '') {
                    if (!preg_match(REGULAR_MOBILE, $mobile)) {
                        $this->json_output(ERRNO::MOBILE_INVALID);
                    }
                    //检查手机是否存在
                    $sql = "SELECT uid FROM ".tablename('mc_members')." WHERE mobile = :mobile AND uniacid = :uniacid AND uid != :uid";
                    $params = array(
                        ':mobile' => $mobile,
                        ':uniacid' => $_W['uniacid'],
                        ':uid' => $_W['member']['uid'],
                    );
                    $exists = pdo_fetchcolumn($sql, $params);
                    if ($exists) {
                        $this->json_output(ERRNO::MOBILE_EXISTS);
                    }
                }
                if ($email != '') {
                    if (!preg_match(REGULAR_EMAIL, $email)) {
                        $this->json_output(ERRNO::EMAIL_INVALID);
                    }
                    //检查邮箱是否存在
                    $sql = "SELECT uid FROM ".tablename('mc_members')." WHERE email = :email AND uniacid = :uniacid AND uid != :uid";
                    $params = array(
                        ':email' => $email,
                        ':uniacid' => $_W['uniacid'],
                        ':uid' => $_W['member']['uid'],
                    );
                    $emailexists = pdo_fetchcolumn($sql, $params);
                    if ($emailexists) {
                        $this->json_output(ERRNO::EMAIL_EXISTS);
                    }
                }

                //下载微信图片
                if ($serverId != '') {
					//初始化路径
					$path = "images/{$_W['uniacid']}/".date('Y/m');
					$filename = md5($serverId).'.jpg';
					if (IMS_VERSION == 0.6) {
						$avatar_file = ATTACHMENT_ROOT.'/'.$path.'/'.$filename;
						$allpath = ATTACHMENT_ROOT.'/'.$path;
					} else {
						$avatar_file = ATTACHMENT_ROOT.$path.'/'.$filename;
						$allpath = ATTACHMENT_ROOT.$path;
					}
					mkdirs($allpath);

					//下载微信图片
                    load()->model('account');
                    $acc = WeAccount::create($_W['account']['acid']);
                    $token = $acc->getAccessToken();
                    if (is_error($token)) {
						WeUtility::logging('fatal', 'token error, message='.$token['message']);
                        $this->json_output(ERRNO::SYSTEM_ERROR, 'token error');
                    }
                    load()->func('communication');
					$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$token}&media_id={$serverId}";
                    $resp = ihttp_request($url);
                    if (is_error($resp)) {
						WeUtility::logging('fatal', 'request error, message='.$resp['message']);
                        $this->json_output(ERRNO::SYSTEM_ERROR, 'request error');
                    }

					//保存头像图片
					$fp = @fopen($avatar_file, 'wb');
					@fwrite($fp, $resp['content']);
					@fclose($fp);
					$new_avatar = $path.'/'.$filename;
					mc_update($_W['member']['uid'], array(
						'avatar' => $new_avatar,
					));
                }

                //更新数据
                $data = array();
                if (isset($_GPC['mobile']) && $_GPC['mobile'] != '') {
                    $data['mobile'] = trim($_GPC['mobile']);
                }
                if (isset($_GPC['nickname']) && $_GPC['nickname'] != '') {
                    $data['nickname'] = trim($_GPC['nickname']);
                }
                if (isset($_GPC['email']) && $_GPC['email'] != '') {
                    $data['email'] = trim($_GPC['email']);
                }
                if (!empty($data)) {
                    mc_update($_W['member']['uid'], $data);
                }
                if ($_GPC['__from'] == 'taskcenter') {
                    $this->json_output(ERRNO::OK, '', array('url' => $this->createMobileUrl('task')));
                } else {
                    $this->json_output(ERRNO::OK, '', array('url' => $this->createMobileUrl('profile')));
                }
            }
        }
        include $this->template('profile');
    }
}

$obj = new Superman_creditmall_doMobileProfile;
$obj->exec();
