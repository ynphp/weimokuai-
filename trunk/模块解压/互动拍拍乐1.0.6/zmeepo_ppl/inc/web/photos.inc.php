<?php
		global $_W,$_GPC;
		$activity_table = 'meepo_paipaile_activity';
		$photo_table = 'meepo_paipaile_photo';
		$cj_table = 'meepo_paipaile_cj';
		$vote_table = 'meepo_paipaile_vote';
		$user_table = 'meepo_paipaile_user';
		$id = intval($_GPC['id']);
		$actid = intval($_GPC['actid']);
		if(empty($id)){
		   message('错误、规则不存在！');
		}
		
		$pindex = max(1, intval($_GPC['page']));
	  $psize = 20;
		$op = empty($_GPC['op']) ? 'list' : $_GPC['op'];
			 
			if($op == 'list'){
			  $params = array();
        $where = " weid = :weid AND rid = :rid AND actid = :actid";
				$params[':weid'] = $_W['uniacid'];
				$params[':rid'] = $id;
				$params[':actid'] = $actid;
				if(!empty($_GPC['nickname'])){
					$where .= " AND nickname LIKE '%{$_GPC['nickname']}%'";
			  }
			  
				$sql = "SELECT * FROM ".tablename($photo_table)." WHERE {$where} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
				$lists = pdo_fetchall($sql,$params);
				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($photo_table) . " WHERE {$where} ", $params);
			  $pager = pagination($total, $pindex, $psize);
		   }elseif($op == 'delete'){
					$photoid = intval($_GPC['photoid']);
					if(empty($photoid)){
		           message('删除项目不存在',$this->createWebUrl("photos",array('id'=>$id,'page'=>$pindex)),"error");
					}
					pdo_delete($photo_table,array('id'=>$photoid,'actid'=>$actid,'rid'=>$id,'weid'=>$_W['uniacid']));
					pdo_delete($vote_table,array('voteid'=>$photoid,'actid'=>$actid,'rid'=>$id,'weid'=>$_W['uniacid']));
					message('删除成功',$this->createWebUrl("photos",array('id'=>$id,'actid'=>$actid,'page'=>$pindex)),"success");
			 }else{
			   message('访问错误');
			 }

				if(checksubmit('delete')){
					//批量删除
					$select = $_GPC['select'];
					if(empty($select)){
						message('请选择删除项',$this->createWebUrl("photos",array('id'=>$id,'actid'=>$actid,'page'=>$pindex)),"error");
					}
					foreach ($select as $se) {
						pdo_delete($photo_table,array('id'=>$se,'actid'=>$actid,'rid'=>$id,'weid'=>$_W['uniacid']));
						pdo_delete($vote_table,array('voteid'=>$se,'actid'=>$actid,'rid'=>$id,'weid'=>$_W['uniacid']));
					}
					message('批量删除成功',$this->createWebUrl("photos",array('id'=>$id,'actid'=>$actid,'page'=>$pindex)),"success");
				}
				if(checksubmit('upload')){
						$select = $_GPC['select'];
						if(empty($select)){
						   message('请选择导出项');
						}
						foreach($select as $row){
								$sql = "SELECT * FROM ".tablename($cj_table)." WHERE rid = :rid AND weid = :weid AND id = :id";
								$params = array(':rid'=>$id,':weid'=>$_W['uniacid'],':id'=>$row);
								$list[] = pdo_fetch($sql,$params);
            }

						//导出
						include_once ('../framework/library/phpexcel/PHPExcel.php');
						$objPHPExcel = new PHPExcel();
						$objDrawing = new PHPExcel_Worksheet_Drawing();

						$objPHPExcel->getProperties()->setCreator("Meepo");
						$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
						$objPHPExcel->getProperties()->setTitle("Meepo");

						$objPHPExcel->getActiveSheet()->setCellValue('A1', '活动名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '奖品名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '奖项名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						$objPHPExcel->getActiveSheet()->setCellValue('D1', '中奖时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

						foreach ($list as $key => $value) {
							$activityname = pdo_fetchcolumn("SELECT title FROM".tablename('meepo_jgg')." WHERE rid=:rid AND weid=:weid",array(':rid'=>$id,':weid'=>$_W['uniacid']));
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$activityname);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['award_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), $value['award_level']);
							$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2), date("Y-m-d H:i:s",$value['createtime']));

						}

						$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

						header("Pragma: public");
						header("Expires: 0");
						header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
						header("Content-Type:application/force-download");
						header("Content-Type:application/vnd.ms-execl");
						header("Content-Type:application/octet-stream");
						header("Content-Type:application/download");;
						header('Content-Disposition:attachment;filename="resume.xls"');
						header("Content-Transfer-Encoding:binary");
						$objWriter->save('php://output');

						exit();
					}

				if(checksubmit('uploadall')){
					$sql = "SELECT * FROM ".tablename($cj_table)." WHERE rid = :rid AND weid = :weid";
					$params = array(':rid'=>$id,':weid'=>$_W['uniacid']);
					$list = pdo_fetchall($sql,$params);


					//导出
					include_once ('../framework/library/phpexcel/PHPExcel.php');
					$objPHPExcel = new PHPExcel();
					$objDrawing = new PHPExcel_Worksheet_Drawing();

					$objPHPExcel->getProperties()->setCreator("Meepo");
					$objPHPExcel->getProperties()->setLastModifiedBy("Meepo");
					$objPHPExcel->getProperties()->setTitle("Meepo");

					$objPHPExcel->getActiveSheet()->setCellValue('A1', '活动名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
						$objPHPExcel->getActiveSheet()->setCellValue('B1', '奖品名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$objPHPExcel->getActiveSheet()->setCellValue('C1', '奖项名称');
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						$objPHPExcel->getActiveSheet()->setCellValue('D1', '中奖时间');
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

						foreach ($list as $key => $value) {
							$activityname = pdo_fetchcolumn("SELECT title FROM".tablename('meepo_jgg')." WHERE rid=:rid AND weid=:weid",array(':rid'=>$id,':weid'=>$_W['uniacid']));
							$objPHPExcel->getActiveSheet()->setCellValue('A'.($key+2), ' '.$activityname);
							$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+2), $value['award_name']);
							$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+2), $value['award_level']);
							$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+2), date("Y-m-d H:i:s",$value['createtime']));

						}

					$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

					header("Pragma: public");
					header("Expires: 0");
					header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
					header("Content-Type:application/force-download");
					header("Content-Type:application/vnd.ms-execl");
					header("Content-Type:application/octet-stream");
					header("Content-Type:application/download");;
					header('Content-Disposition:attachment;filename="resume.xls"');
					header("Content-Transfer-Encoding:binary");
					$objWriter->save('php://output');

					exit();
				}


    include $this->template('photos');
