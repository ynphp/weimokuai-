<?php
		global $_W,$_GPC;
		$activity_table = 'meepo_paipaile_activity';
		$photo_table = 'meepo_paipaile_photo';
		$cj_table = 'meepo_paipaile_user';
		$cj_table = 'meepo_paipaile_cj';
		$vote_table = 'meepo_paipaile_vote';
		$user_table = 'meepo_paipaile_user';
		$id = intval($_GPC['id']);
		$actid = intval($_GPC['actid']);
		$voteid = intval($_GPC['photoid']);
		if(empty($id)){
		   message('错误、规则不存在！');
		}
		
		$pindex = max(1, intval($_GPC['page']));
	  $psize = 20;
		$op = empty($_GPC['op']) ? 'list' : $_GPC['op'];
			 
			if($op == 'list'){
			  $params = array();
        $where = " weid = :weid AND rid = :rid AND actid = :actid AND voteid = :voteid";
				$params[':weid'] = $_W['uniacid'];
				$params[':rid'] = $id;
				$params[':actid'] = $actid;
				$params[':voteid'] = $voteid;
				$sql = "SELECT * FROM ".tablename($vote_table)." WHERE {$where} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
				$lists = pdo_fetchall($sql,$params);
				foreach($lists as &$row){
						$it = pdo_fetch("SELECT `nickname`,`headimgurl` FROM".tablename($user_table)." WHERE openid = :openid AND rid = :rid AND actid = :actid",array(':openid'=>$row['openid'],':rid'=>$id,':actid'=>$actid));//give vote
						$row['nickname'] = $it['nickname'];
						$row['headimgurl'] = $it['headimgurl'];
						$upit = pdo_fetch("SELECT `nickname`,`avatar`,`picurl` FROM".tablename($photo_table)." WHERE  rid = :rid AND id = :id AND actid = :actid",array(':rid'=>$id,':id'=>$row['voteid'],':actid'=>$actid));// vote who
						$row['upnickname'] = $upit['nickname'];
						$row['upheadimgurl'] = $upit['avatar'];
						$row['picurl'] = $upit['picurl'];
				}
				unset($row);
				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($vote_table) . " WHERE {$where} ", $params);
			  $pager = pagination($total, $pindex, $psize);
		   }elseif($op == 'delete'){
					$onevote = intval($_GPC['onevote']);
					if(empty($onevote)){
		           message('删除项目不存在',$this->createWebUrl("vote",array('id'=>$id,'photoid'=>$voteid,'actid'=>$actid,'page'=>$pindex)),"error");
					}
					pdo_delete($vote_table,array('id'=>$onevote,'actid'=>$actid,'rid'=>$id,'weid'=>$_W['uniacid']));
					message('删除成功',$this->createWebUrl("vote",array('id'=>$id,'photoid'=>$voteid,'actid'=>$actid,'page'=>$pindex)),"success");
			 }else{
			   message('访问错误');
			 }

				if(checksubmit('delete')){
					//批量删除
					$select = $_GPC['select'];
					if(empty($select)){
						message('请选择删除项',$this->createWebUrl("vote",array('id'=>$id,'photoid'=>$voteid,'actid'=>$actid,'page'=>$pindex)),"error");
					}
					foreach ($select as $se) {
						pdo_delete($vote_table,array('id'=>$se,'actid'=>$actid,'rid'=>$id,'weid'=>$_W['uniacid']));
					}
					message('批量删除成功',$this->createWebUrl("vote",array('id'=>$id,'photoid'=>$voteid,'actid'=>$actid,'page'=>$pindex)),"success");
				}
				if(checksubmit('upload')){
						$select = $_GPC['select'];
						if(empty($select)){
						   message('请选择导出项');
						}
						foreach($select as $row){
								$sql = "SELECT * FROM ".tablename($vote_table)." WHERE rid = :rid AND weid = :weid AND id = :id";
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


    include $this->template('vote');
