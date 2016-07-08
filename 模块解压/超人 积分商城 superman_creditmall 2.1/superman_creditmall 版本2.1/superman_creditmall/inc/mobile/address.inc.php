<?php
/**
 * 【超人】积分商城模块定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Superman_creditmall_doMobileAddress extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct();
        checkauth();
    }
    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display', 'post', 'delete'))?$_GPC['act']:'display';
        $uid = $_W['member']['uid'];
        if ($act == 'display') {
            $header_title = '收货地址';
            if ($_GPC['isdefault'] == 1) {
                $id = intval($_GPC['id']);
                $row = superman_mc_address_fetch($id);
                if (!$row) {
                    message('地址不存在或已删除！', referer(), 'error');
                }
                //去掉默认地址
                $condition = array(
                    'uid' => $uid,
                    'isdefault' => '1',
                );
                pdo_update('mc_member_address', array('isdefault' => 0), $condition);

                //设置新默认地址
                pdo_update('mc_member_address', array('isdefault' => 1), array('id' => $id));
                if (isset($_GPC['_from']) && $_GPC['_from'] == 'confirm') {
                    message('更新成功！',$this->createMobileUrl('confirm', array('id' => intval($_GPC['product_id']))), 'success');
                }
                message('更新成功！', referer(), 'success');
            }
            $list = superman_mc_address_fetchall_uid($uid);
            if ($list) {
                foreach ($list as &$ad) {
                    $ad['mobile'] = superman_hide_mobile($ad['mobile']);
                    if ($ad['province'] == $ad['city']) {
                        $ad['address'] = $ad['city'].$ad['district'].$ad['address'];
                    } else {
                        $ad['address'] = $ad['province'].$ad['city'].$ad['district'].$ad['address'];
                    }
                }
                unset($ad);
            }
        } else if ($act == 'post') {
            $id = intval($_GPC['id']);
            if ($id) {
                $header_title = '编辑地址';
                $item = superman_mc_address_fetch($id);
                if ($item  && $item['uid'] == $uid) {
                    if ($item['province'] == $item['city']) {
                        $item['city'] = $item['city'].' '.$item['district'];
                    } else {
                        $item['city'] = $item['province'].' '.$item['city'].' '.$item['district'];
                    }
                } else {
                    message('非法请求',$this->createMobileUrl('address'),'error');
                }
            } else {
                $header_title = '添加地址';
            }

            if (checksubmit('submit')) {
                //表单参数检查
                $username = trim($_GPC['username']);
                if ($username == '') {
                    message('',referer(),'error');
                }
                $mobile = $_GPC['mobile'];
                if ($mobile == '') {
                    message('',referer(),'error');
                }
                if (!preg_match('/^([0-9]{11})?$/', $mobile)) {
                    message('',referer(),'error');
                }
                $address = trim($_GPC['address']);

                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $uid,
                    'username' => trim($_GPC['username']),
                    'mobile' => $_GPC['mobile'],
                    'address' => trim($_GPC['address']),
                    'isdefault' => 0
                );
                $isdefault = $_GPC['isdefault']=='on'?1:0;
                if ($isdefault == 1) {
                    $row = superman_mc_address_fetch_uid($uid);
                    if ($row) {
                        pdo_update('mc_member_address',array('isdefault' => 0),array('id' => $row['id']));
                    }
                    $data['isdefault'] = 1;
                }

                $city = trim($_GPC['city']);
                if (!$city) {
                    message('请选择地区信息',referer(),'error');
                }
                $city = explode(' ',$city);
                if (count($city) == 3) {
                    $data['province'] = $city[0];
                    $data['city'] = $city[1];
                    $data['district'] = $city[2];
                } elseif (count($city)==2) {
                    $data['province'] = $city[0];
                    $data['city'] = $city[0];
                    $data['district'] = $city[1];
                } else {
                    message('请选择地区信息',referer(),'error');
                }

                if ($id) {
                    $ret = pdo_update('mc_member_address',$data,array('id' => $id));
                } else {
                    $ret = pdo_insert('mc_member_address',$data);
                }
                if ($ret === false) {
                    message('更新失败，请重试', referer(), 'error');
                }
                if (isset($_GPC['_from']) && $_GPC['_from'] == 'confirm') {
                    message('更新成功！',$this->createMobileUrl('confirm', array('id' => intval($_GPC['product_id']))), 'success');
                }
                message('更新成功！',$this->createMobileUrl('address'), 'success');
            }
        } else if ($act == 'delete') {
            $id = intval($_GPC['id']);
            if (!$id) {
                message('非法请求！',$this->createMobileUrl('address'),'error');
            }
            $address = superman_mc_address_fetch($id);
            if (!$address || $address['uid'] != $uid) {
                message('收货地址不存在或已删除！', $this->createMobileUrl('address'), 'error');
            }
            $ret = pdo_delete('mc_member_address', array('id' => $id));
            if ($ret === false) {
                message('删除失败，请重试', referer(), 'error');
            }
            message('删除成功！', $this->createMobileUrl('address'), 'success');
        }
        include $this->template('address');
    }
}

$obj = new Superman_creditmall_doMobileAddress;
$obj->exec();
