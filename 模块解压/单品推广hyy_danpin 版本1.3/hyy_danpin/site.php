<?php
/**
 * 单品推广模块微站定义
 *
 */
defined('IN_IA') or exit('Access Denied');

class Hyy_danpinModuleSite extends WeModuleSite {
    public function Checkedservername()
    {
    }
    public function Checkeduseragent()
    {
        $useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false) {
            message('非法访问，请通过微信打开！');
            die();
        }
    }
	public function doMobileIndex() {
		//$this->Checkeduseragent();
		global $_W,$_GPC;
		//这个操作被定义用来呈现 功能封面
		$sql="SELECT * FROM ".tablename('hyy_danpin_goods')." where weid = '{$_W['uniacid']}' order by id desc limit 0,1";
		$goods = pdo_fetch($sql);
		$url_ad = $this->createmobileUrl('ad',array('do'=>'ad','id'=>$goods['id']));
		include $this->template('test_index');
	}
	//产品广告封面，每一个产品对应一个封面，内容为产品的广告图片
	public function doMobileAd()
	{
		//$this->Checkeduseragent();
		global $_W,$_GPC;
		$good_id = $_GPC['id'];
		$sql="SELECT * FROM ".tablename('hyy_danpin_goods')." WHERE id=$good_id ";
		$goods = pdo_fetch($sql);
		$url_xiadan = $this->createmobileUrl('xiadan',array('do'=>'xiadan','id'=>$good_id));

        /** weixin js */
        require_once IA_ROOT."/addons/hyy_danpin/jssdk.class.php";
        $weixin = new jssdk($jie='0',$url='');
        $wx = $weixin->get_sign();
		
		include $this->template('goods_ad');
	}
	//产品下单页面，接收一个产品id，下单成功后，转向该产品设置的转向链接
	public function doMobileXiadan()
	{
		//$this->Checkeduseragent();
		global $_W,$_GPC;
		$good_id = $_GPC['id'];
		$openid="111";//isset($_W['fans']['from_user']) && !empty($_W['fans']['from_user']) ? $_W['fans']['from_user'] : "111";
		$weid=$_W['uniacid'];
		$sql="SELECT * FROM ".tablename('hyy_danpin_goods')." WHERE id=$good_id ";
		$goods = pdo_fetch($sql);
		
		if ($_GPC['opp'] == 'xiadan') {//下单
			$content = $goods['title'];
			$price = $goods['item_sel_price'];
			$tel = $_GPC['tel'];
			//查询是否有重复订单
			$sql="SELECT * FROM ".tablename('hyy_danpin_dingdan')." WHERE phone='$tel' ";
			$dd = pdo_fetch($sql);	
			if($dd)
			{
				message("你已经下过单了，把机会留给别人吧!","","error");
				exit;
			}
			$shouhuoren = $_GPC['name'];
			$address1 = $_GPC['address1'];
			$address2 = $_GPC['address2'];
			
			if(!empty($_GPC['item_sku_id']))
			{
				$content .= "<br/>规格：".$goods['guige'.$_GPC['item_sku_id']];
				$price = $goods['price'.$_GPC['item_sku_id']];
			}
            $data = array('weid'=>$weid,'goods_id'=>$good_id,'content'=>$content,'price'=>$price,'openid'=>$openid,'phone'=>$tel,'shouhuoren'=>$shouhuoren,'address1'=>$address1,'address2'=>$address2,'create_time'=>time());
            pdo_insert(hyy_danpin_dingdan, $data);
			$item_id = pdo_insertid();			
			if(empty($goods['redirect_url']))
				$url = $goods['share_link'];
			else
				$url = $goods['redirect_url'];
			
			message("订单提交成功，请等待送货",$url,'success');
		}
		
		include $this->template('xiadan');			
	}
	public function doWebAdminGoods() {
		//这个操作被定义用来呈现 管理中心导航菜单
        global $_W,$_GPC;
        $this->Checkedservername();
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;

        $condition = "weid = '{$_W['uniacid']}'";
        /*
        if (!empty($_GPC['keywords'])) {
            $condition .= " AND id = '{$_GPC['keywords']}'";
        }
        */
        $sql="SELECT * FROM ".tablename('hyy_danpin_goods')." WHERE $condition ORDER BY id desc LIMIT ".($pindex - 1) * $psize.','.$psize;
        $list = pdo_fetchall($sql);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hyy_danpin_goods')." WHERE $condition");
        $pager = pagination($total, $pindex, $psize);
		//广告页面地址
		$url_ad = $this->createmobileUrl('ad',array('do'=>'ad'));
		$url_ad = "http://".$_SERVER['HTTP_HOST']."/app".substr($url_ad,1,strlen($url_ad)-40);
        include $this->template('adminGoods');	
	}
	public function doWebAdminDingdan() {
		//这个操作被定义用来呈现 管理中心导航菜单
        global $_W,$_GPC;
        $this->Checkedservername();
        $pindex = max(1, intval($_GPC['page']));
        $psize = 50;

        $condition = "weid = '{$_W['uniacid']}'";
        /*
        if (!empty($_GPC['keywords'])) {
            $condition .= " AND id = '{$_GPC['keywords']}'";
        }
        */
        $sql="SELECT * FROM ".tablename('hyy_danpin_dingdan')." WHERE $condition ORDER BY id desc LIMIT ".($pindex - 1) * $psize.','.$psize;
        $list = pdo_fetchall($sql);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hyy_danpin_dingdan')." WHERE $condition");
        $pager = pagination($total, $pindex, $psize);
        include $this->template('adminDingdan');	
	}
	public function doWebExportDingdan()
	{	set_time_limit(0);
		global $_W,$_GPC;
        $pindex = max(1, intval($_GPC['page']));
        $psize = 50;		
		$weid = $_W['uniacid'];
		
		//下面的路径按照你PHPExcel的路径来修改
		set_include_path('.'. PATH_SEPARATOR .IA_ROOT."/addons/hyy_danpin/PHPExcel" . PATH_SEPARATOR .get_include_path()); 
		  
		require_once IA_ROOT."/addons/hyy_danpin/PHPExcel.php";
		require_once IA_ROOT.'/addons/hyy_danpin/PHPExcel/IOFactory.php';
		require_once IA_ROOT."/addons/hyy_danpin/PHPExcel/Writer/Excel5.php";//excel 2003
		require_once IA_ROOT."/addons/hyy_danpin/PHPExcel/Writer/Excel2007.php";//excel 2007

		/*
		*/
		$cols_title = array(
			"id"=>"标号",
			"create_time"=>"下单时间",
			"price"=>"金额",
			'content'=>"订单商品",
			"phone"=>"手机号",
			"shouhuoren"=>"收货人",
			"address"=>"收货人地址",

		);
		$c_f = array();
		$c_t = array();
		$cols_t =array();

		$cols_t = $cols_title;
			
		foreach($cols_t as $field=>$title)
		{
			$c_f[] = $field;
			$c_t[] = $title;
		}
		//var_dump($cols_t);
		//exit;
		$phpexcel = new PHPExcel();
		$phpexcel->setActiveSheetIndex(0);
		$phpactiveSheet = $phpexcel->getActiveSheet();
		
		//设置标题
		$cols_number = count($cols_t);
		
		for($i=0,$j=0;$i<$cols_number,$j<$cols_number;$i++,$j++)
		{
		//输出列名
			$phpactiveSheet->setCellValueByColumnAndRow($j,1,$c_t[$i]);
			//echo $j."<br/>";
		}
		//var_dump("hello");
		
		$phpactiveSheet -> setTitle("订单信息");
		$i=2;//hang
		$j=0;//lie
		$dingdans = array();
		$sql = "select * from ".tablename(hyy_danpin_dingdan)." where weid = {$weid} "." ORDER BY id desc LIMIT ".($pindex - 1) * $psize.','.$psize;
		$dingdans = pdo_fetchall($sql);
			
		foreach ($dingdans as $user)
		{
			foreach($c_f as $f)
			{
				//$asheet->setCellValueByColumnAndRow($j,$i,$newrs[$col]);
				if($f == "create_time")
				{
					$ct = date('Y-m-d H:i',$user[$f]);
					$phpactiveSheet->getCellByColumnAndRow($j,$i)->setValueExplicit($ct,PHPExcel_Cell_DataType::TYPE_STRING);
				}
				else if($f == "address")
				{
					$ct = $user["address1"]." ".$user["address2"];
					$phpactiveSheet->getCellByColumnAndRow($j,$i)->setValueExplicit($ct,PHPExcel_Cell_DataType::TYPE_STRING);
				}
				else if($f == "content")
				{
					$ct = str_replace("<br/>","  ",$user["content"]);
					$phpactiveSheet->getCellByColumnAndRow($j,$i)->setValueExplicit($ct,PHPExcel_Cell_DataType::TYPE_STRING);
				}				
				else
					$phpactiveSheet->getCellByColumnAndRow($j,$i)->setValueExplicit($user[$f],PHPExcel_Cell_DataType::TYPE_STRING);
				//$newrs[$col]
				$j++;
			}
			$i++;
			$j=0;
		}

		//$objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
		//$objWriter->save('php://output');
		$outputFileName = "订单信息.xls";
		
		//$outputFileName = $city_name."-".$outputFileName;
		/**/
		header('Content-Type: application/vnd.ms-excel;charset=utf-8');
		header("Content-Disposition: attachment;filename=".$outputFileName); 
		header('Cache-Control: max-age=0');
		
		//
		$objWriter = PHPExcel_IOFactory::createWriter($phpexcel,"Excel5");

		//var_dump($objWriter);
		//exit;
		$objWriter->save('php://output');
		exit;
	}
	public function doWebDelDingdan()
	{
        global $_W,$_GPC;
        $this->Checkedservername();
        // 删除
        if($_GPC['op']=='delete'){
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM ".tablename(hyy_danpin_dingdan)." WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_delete(hyy_danpin_dingdan, array('id' => $id));
            message('删除成功！', referer(), 'success');
        }
		else
			message('非法操作',referer(),'error');
	}
	public function doWebCreateGoods() {
		//这个操作被定义用来呈现 管理中心导航菜单
        global $_W,$_GPC;
        $this->Checkedservername();
        $id = (int) $_GPC['id'];
        
        if($id){
            $goods = pdo_fetch("SELECT * FROM ".tablename('hyy_danpin_goods')." WHERE id={$id} ORDER BY id DESC LIMIT 1");
        }
        // 删除
        if($_GPC['op']=='delete'){
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM ".tablename(hyy_danpin_goods)." WHERE id = :id", array(':id' => $id));
            if (empty($row)) {
                message('抱歉，信息不存在或是已经被删除！');
            }
            pdo_delete(hyy_danpin_goods, array('id' => $id));
            message('删除成功！', referer(), 'success');
        }
        if (checksubmit()) {
			
			$create_time = time();
			
			
            $data = array(
                'title' => $_GPC['title'],
                'title2' => $_GPC['title2'],
				'price'=>$_GPC['price'],
				'img_small'=>$_GPC['img_small'],
				'img_bigAd'=>$_GPC['img_bigAd'],
				'share_title'=>$_GPC['share_title'],
				'share_link'=>$_GPC['share_link'],
				'redirect_url'=>$_GPC['redirect_url'],
				'guige1'=>$_GPC['guige1'],
				'price1'=>$_GPC['price1'],
				'guige2'=>$_GPC['guige2'],
				'price2'=>$_GPC['price2'],		
				'guige3'=>$_GPC['guige3'],
				'price3'=>$_GPC['price3'],		

                'create_time' => $create_time,

            );
            if (!empty($id)) {
                pdo_update(hyy_danpin_goods, $data, array('id' => $id));
            }else {
                $data['weid'] = $_W['uniacid'];
                pdo_insert(hyy_danpin_goods, $data);
                $id = pdo_insertid();
            }

            message('更新成功！', referer(), 'success');
        }
		
		load()->func('tpl');
        include $this->template('addGoods');	
	}

}