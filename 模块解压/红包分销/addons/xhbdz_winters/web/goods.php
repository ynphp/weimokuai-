<?php
/**
 * 商品列表
 * 
 */

if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W,$_GPC;

$ops = array('list','upgoods', 'edit', 'delete'); // 只支持此4 种操作.
$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
//商品列表显示
if($op == 'list'){
    $uniacid=$_W['uniacid'];
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $goodses = pdo_fetchall("SELECT * FROM ".tablename('xhbdz_goods')." WHERE uniacid = '{$uniacid}' and del = 0 ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xhbdz_goods') . " WHERE uniacid = '{$uniacid}' and del = 0");
    $pager = pagination($total, $pindex, $psize);
    include $this->template('web/goods');
}
//商品编辑
if ($op == 'edit') {
    $id = intval($_GPC['id']);
    if(!empty($id)){
        $sql = 'SELECT * FROM '.tablename('xhbdz_goods').' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
        $params = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
        $goods = pdo_fetch($sql, $params);
        if(empty($goods)){
            message('未找到指定的商品.', $this->createWebUrl('goods'));
        }
    }
    if (checksubmit()) {
        $data = $_GPC['goods']; // 获取打包值
        empty($data['title']) && message('请填写商品标题',null,'error');
        empty($data['picimg']) && message('请上传商品图片',null,'error');
        empty($data['price']) && message('请填写商品金额',null,'error');
        empty($data['stock']) && message('请填写商品库存',null,'error');
        $data['content'] = $_GPC['content'];
        if(empty($goods)){
            $data['uniacid'] = $_W['uniacid'];
            $data['createtime'] = TIMESTAMP;
            $ret = pdo_insert(xhbdz_goods, $data);

    }else {
        $ret = pdo_update(xhbdz_goods, $data, array('id'=>$id));
    }
    if ($ret) {
        message('商品信息保存成功', $this->createWebUrl('goods', array('op'=>'edit', 'id'=>$id)), 'success');
    } else {
        message('商品信息保存失败！！！');
    }
    }

    include $this->template('web/goods_edit');
}

if($op == 'delete') {
 $id = intval($_GPC['id']);
 if(empty($id)){
 message('未找到指定商品分类');
 }
 $result = pdo_update(xhbdz_goods, array('del'=> 1), array('id'=>$id));
 if(intval($result) == 1){
 message('删除商品成功.', $this->createWebUrl('goods'), 'success');
 } else {
 message('删除商品失败.');
 }
 }