<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
global $_GPC, $_W;
yload()->classs('quick_shop', 'order');
yload()->classs('quick_shop', 'dispatch');
yload()->classs('quick_shop', 'address');
yload()->classs('quick_shop', 'express');
yload()->classs('quick_verify', 'verifiedorder');
yload()->classs('quick_verify', 'clerk');
$_order = new Order();
$_dispatch = new Dispatch();
$_address = new Address();
$_express = new Express();
$_verifiedorder = new VerifiedOrder();
$_clerk = new Clerk();
$sendtype = !isset($_GPC['sendtype']) ? 1 : $_GPC['sendtype'];
$pindex = 1;
$psize = 1024000;
list($vorder, $idsize) = $_verifiedorder->batchGet($_W['weid'], array(), $pindex, $psize, 'orderid');
$ids = array_keys($vorder);
list($list, $total) = $_order->batchGetById($_W['weid'], $ids, null, $pindex, $psize);
if (!empty($list)) {
	foreach ($list as &$row) {
		$address = $_address->get($row['addressid']);
		$row['realname'] = $address['realname'];
		$row['mobile'] = $address['mobile'];
		$row['address'] = $address['province'] . $address['city'] . $address['area'] . $address['address'];
		$goods = $_order->getDetailedGoods($row['id']);
		$body = '';
		foreach ($goods as $g) {
			$body .= " 销售价: {$g['marketprice']} 成本价：{$g['costprice']}  x 数量:{$g['total']} x {$g['title']} " . "\n";
		}
		$row['clerk'] = $_clerk->find($_W['weid'], $vorder[$row['id']]['clerk_openid']);
		$row['goods'] = $body;
		$body = '';
		unset($row);
		unset($body);
	}
}
$sheet_title = $_W['account']['name'] . '-全部订单数据';
foreach ($list as &$row) {
	$row['status'] = '验证过的订单';
}
unset($row);
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("XiaoChu")->setLastModifiedBy("XiaoChu")->setTitle("Office 2007 XLSX Document")->setSubject("Office 2007 XLSX Document")->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")->setKeywords("office 2007 openxml php")->setCategory("Order File");
$cmap = array('id' => 'A', 'ordersn' => 'B', 'status' => 'C', 'realname' => 'D', 'mobile' => 'E', 'openid' => 'F', 'clerkname' => 'G', 'shopname' => 'H', 'verifytime' => 'I', 'address' => 'J', 'price' => 'K', 'createtime' => 'L', 'detail' => 'M', 'remark' => 'N');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cmap['id'] . '1', 'ID')->setCellValue($cmap['ordersn'] . '1', '订单号')->setCellValue($cmap['status'] . '1', '状态')->setCellValue($cmap['realname'] . '1', '姓名')->setCellValue($cmap['mobile'] . '1', '手机')->setCellValue($cmap['openid'] . '1', 'OPENID')->setCellValue($cmap['address'] . '1', '快递地址')->setCellValue($cmap['price'] . '1', '总价')->setCellValue($cmap['createtime'] . '1', '下单时间')->setCellValue($cmap['detail'] . '1', '订单详情')->setCellValue($cmap['remark'] . '1', '备注')->setCellValue($cmap['clerkname'] . '1', '店员名')->setCellValue($cmap['shopname'] . '1', '店铺名称')->setCellValue($cmap['verifytime'] . '1', '验证时间');
$i = 2;
foreach ($list as $listrow) {
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cmap['id'] . $i, $listrow['id'])->setCellValueExplicit($cmap['ordersn'] . $i, $listrow['ordersn'], PHPExcel_Cell_DataType::TYPE_STRING)->setCellValue($cmap['status'] . $i, $listrow['status'])->setCellValue($cmap['realname'] . $i, $listrow['realname'])->setCellValueExplicit($cmap['mobile'] . $i, $listrow['mobile'], PHPExcel_Cell_DataType::TYPE_STRING)->setCellValue($cmap['openid'] . $i, $listrow['from_user'])->setCellValue($cmap['address'] . $i, $listrow['address'])->setCellValue($cmap['price'] . $i, $listrow['price'])->setCellValue($cmap['createtime'] . $i, date('Y-m-d H:i', $listrow['createtime']))->setCellValue($cmap['detail'] . $i, $listrow['goods'])->setCellValue($cmap['remark'] . $i, $listrow['remark'])->setCellValue($cmap['clerkname'] . $i, $listrow['clerk']['clerk_realname'])->setCellValue($cmap['shopname'] . $i, $listrow['clerk']['shopname'])->setCellValue($cmap['verifytime'] . $i, date('Y-m-d H:i', $vorder[$listrow['id']]['createtime']));
	$i++;
	unset($listrow);
}
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['id'])->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['ordersn'])->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['status'])->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['realname'])->setWidth(22);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['mobile'])->setWidth(22);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['openid'])->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['address'])->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['price'])->setWidth(7);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['createtime'])->setWidth(22);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['detail'])->setWidth(70);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['remark'])->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['clerkname'])->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['shopname'])->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension($cmap['verifytime'])->setWidth(22);
$objPHPExcel->getActiveSheet()->setTitle($sheet_title);
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $sheet_title . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_clean();
$objWriter->save('php://output');
exit;