<?php
/**
 * 【超人】积分商城模块定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Superman_creditmall_doWebOrder extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC;
        $title = '订单管理';
        $act = in_array($_GPC['act'], array('display', 'post', 'delete', 'export'))?$_GPC['act']:'display';

        $order_status = superman_order_status();
        if ($act == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 20;
            $start = ($pindex - 1) * $pagesize;
            $status = in_array($_GPC['status'], array('-2', '-1', '0', '1', '2', '3', '4', 'all'))?$_GPC['status']:'all';
            $ordersn = $_GPC['ordersn']==''?'':$_GPC['ordersn'];
            $product_title = $_GPC['product_title']==''?'':$_GPC['product_title'];
            $filter = array(
                'uniacid' => $_W['uniacid'],
            );
            if ($status != 'all') {
                $filter['status'] = $status;
            }
            if ($ordersn) {
                $filter['ordersn'] = $ordersn;
            }
            if (!empty($product_title)) {
                $product = superman_product_fetch_title($product_title);
                if ($product) {
                    $filter['product_id'] = $product['id'];
                }
            }
            $total = superman_order_count($filter);
            $list = array();
            if ($total > 0) {
                if (isset($_GPC['export'])) {
                    $pagesize = -1;
                }
                $list = superman_order_fetchall($filter, 'ORDER BY id DESC', $start, $pagesize);
                if ($list) {
                    foreach ($list as &$item) {
                        $item['member'] = mc_fetch($item['uid'],array('nickname','avatar'));
                        $product = superman_product_fetch($item['product_id']);
                        $item['title'] = $product?$product['title']:'[商品已删除]';
                        if (!isset($_GPC['export']) && !$product) {
                            $item['title'] = '<span class="label label-danger">'.$item['title'].'</span>';
                        }
                        superman_order_set($item);
                    }
                    unset($item);
                }
                $pager = pagination($total, $pindex, $pagesize);
            }
            if (isset($_GPC['export'])) {
                $this->export_order($list);
            }
        } else if ($act == 'post') {
            $id = intval($_GPC['id']);
            $order = superman_order_fetch($id);
            if (!$order) {
                message('订单不存在',referer(),'error');
            }
            superman_order_set($order);
            $product = superman_product_fetch($order['product_id'], 'title');
            $order['product']['title'] = $product?$product['title']:'<span class="label label-danger">[商品已删除]</span>';
            $order['member'] = mc_fetch($order['uid'], array('nickname', 'avatar'));
            if (checksubmit('submit')) {
                //退钱条件：取消订单 && 已支付
                if ($_GPC['return_credit'] == 1 && $_GPC['status'] == '-1' && $order['status'] > 0) {
                    $ret = $this->returnCredit($order, "取消订单({$order['ordersn']})");
                    if ($ret !== true) {
                        message("取消订单({$order['ordersn']})退积分失败！", '', 'error');
                    }
                }
                $data = array(
                    'express_no' => $_GPC['express_no'],
                    'remark' => trim($_GPC['remark']),
                    'status' => $_GPC['status'],
                );
                if ($_GPC['virtual_key']) {
                    $data['extend'] = $order['extend'];
                    $data['extend']['virtual_result']['key'] = $_GPC['virtual_key'];
                    $data['extend'] = iserializer($data['extend']);
                }

                //修改为已发货状态 发送客服消息
                if ($_GPC['status'] == 2 && !isset($order['extend']['virtual_result'])) {
                    if ($_W['account']['level'] == 3 || $_W['account']['level'] == 4) { //已认证
                        $order_url = $_W['siteroot'].'app/'.$this->createMobileUrl('order', array('act' => 'detail', 'orderid' => $id));
                        $this->sendCustomerStatusNotice($order['uid'], $order['ordersn'], $_GPC['status'], $order_url);
                    }
                }

                pdo_update('superman_creditmall_order', $data, array('id' => $id));
                message('修改成功！', $this->createWebUrl('order'), 'success');
            }
        } else if ($act == 'delete') {
            $id = intval($_GPC['id']);
            $ret = pdo_delete('superman_creditmall_order', array('id' => $id));
            if ($ret) {
                message('删除成功！', referer(), 'success');
            }
            message('删除失败！', referer(), 'error');
        }
        include $this->template('web/order');
    }

    private function export_order($list) {
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/IOFactory.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Writer/Excel5.php';
        $resultPHPExcel = new PHPExcel();
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => PHPExcel_Style_Border::BORDER_THIN,//细边框
                    //'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );
        $style_fill = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => '0xFFFF00')
            ),
        );
        $resultPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray(($styleArray + $style_fill));
        $resultPHPExcel->getActiveSheet()->setCellValue('A1', '订单号');
        $resultPHPExcel->getActiveSheet()->setCellValue('B1', '商品名');
        $resultPHPExcel->getActiveSheet()->setCellValue('C1', '件数');
        $resultPHPExcel->getActiveSheet()->setCellValue('D1', '姓名');
        $resultPHPExcel->getActiveSheet()->setCellValue('E1', '地址');
        $resultPHPExcel->getActiveSheet()->setCellValue('F1', '电话');
        $resultPHPExcel->getActiveSheet()->setCellValue('G1', '配送方式');
        $resultPHPExcel->getActiveSheet()->setCellValue('H1', 'UID');
        $resultPHPExcel->getActiveSheet()->setCellValue('I1', '昵称');
        $resultPHPExcel->getActiveSheet()->setCellValue('J1', '创建时间');
        $resultPHPExcel->getActiveSheet()->setCellValue('K1', '状态');
        $resultPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $resultPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $i = 2;
        foreach ($list as $item) {
            $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $item['ordersn']);
            $resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item['title']);
            $resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item['total']);
            $resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, $item['username']);
            $resultPHPExcel->getActiveSheet()->setCellValue('E' . $i, $item['address']);
            $resultPHPExcel->getActiveSheet()->setCellValue('F' . $i, $item['mobile']);
            $resultPHPExcel->getActiveSheet()->setCellValue('G' . $i, $item['express_title'].'（快递费：'.$item['express_fee']);
            $resultPHPExcel->getActiveSheet()->setCellValue('H' . $i, $item['uid']);
            $resultPHPExcel->getActiveSheet()->setCellValue('I' . $i, $item['member']['nickname']);
            $resultPHPExcel->getActiveSheet()->setCellValue('J' . $i, $item['dateline']);
            $status_title = superman_order_status($item['status']);
            $resultPHPExcel->getActiveSheet()->setCellValue('K' . $i, $status_title);
            $resultPHPExcel->getActiveSheet()->getStyle('A' . $i . ':K' . $i)->applyFromArray($styleArray);
            $i++;
        }
        $resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, '导出订单数：' . count($list));
        $resultPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray(array('font' => array('bold' => true)));

        $outputFileName = 'data'.date('YmdHi').'.xls';
        $xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $outputFileName . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $xlsWriter->save("php://output");
        exit;
    }
}

$obj = new Superman_creditmall_doWebOrder;
$obj->exec();
