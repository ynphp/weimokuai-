<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>点餐台</title>
<meta name="format-detection" content="telephone=no, address=no">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-touch-fullscreen" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<link href="./resource/css/bootstrap.min.css" rel="stylesheet">
<link href="./resource/css/font-awesome.min.css" rel="stylesheet">
<link href="./resource/css/animate.css" rel="stylesheet">
<link href="./resource/css/common.css" rel="stylesheet">
<script src="../addons/j_money/template/js/jquery-2.1.1.min.js"></script>
<script src="../addons/j_money/template/js/sweetalert.min.js"></script>
<link type="text/css" rel="stylesheet" href="../addons/j_money/template/js/sweetalert.css"/>
</head>
<style>
html{ height:100%;}
body{ height:100%; background:url(../addons/j_money/template/img/bg.jpg); background-size:cover;}
.jtable{ display:table; width:100%;}
.jrow{ display:table-row;}
.jcell{ display:table-cell;}
.body_left{ width:40px; vertical-align:top; background:#CCC}
.body_left ul,.body_left ul li{list-style:none; margin:0; padding:0;}
.body_left ul li{ font-size:20px; text-align:center;}
.body_right{vertical-align:top;}
.r_top_menu{ height:60px; line-height:60px;}

/**/
.r_menubox{ vertical-align:top;}
.r_menubox ul,.r_menubox ul li{list-style:none; margin:0; padding:0;}
.r_menubox ul{ width:100%;background:#FFF;}
.r_menubox ul li{width:24%;max-width:130px;text-align:center;display:inline-block;height:190px;padding:2px;margin:2px;border-radius:4px}
.r_menubox ul li div{ width:100%; height:130px;}
.r_menubox ul li div img{width:100%; display:block; max-height:130px;}
.r_menubox ul li h2{ font-size:12px; margin:5px 0; line-height:18px; padding:0; text-align:left; width:100%; overflow:hidden;}
.r_menubox ul li p{ margin:0; padding:0; line-height:18px; color:#F60; text-align:right;}
.r_tab_menu{ width:40px; vertical-align:top}
.r_mune_l_box{ background:#FFF; border-radius:4px; padding:20px 0 10px 0;}
.r_mune_l_box_1{background: linear-gradient(to bottom, #F2891C 0%,#F06641 100%);background:-webkit-linear-gradient(top, #F2891C 0%,#F06641 100%); padding:5px 0; text-align:center; color:#FFF;}
.r_mune_l_box_box{ padding:10px 5px;}
.r_mune_l_box_box_1{ font-size:14px; font-weight:bold; margin-bottom:10px;}
.r_mune_l_box_box_1 span{color:#F00}
.r_mune_l_box_box_2 span{ display:inline-block; margin-right:10px;}
.r_mune_l_box_box_3{ border:#CCC 1px solid; margin:5px 0;}
.r_mune_l_menu{ margin-top:5px;}
.r_mune_l_menu button{ font-size:18px; font-weight:bold; line-height:24px}
</style>
<body>
<div class="jtable topbox">
  <div class="jrow"> 
    <!--左边栏-->
    <div class="jcell body_left">
      <ul>
        <li><i class="fa fa-user"></i></li>
      </ul>
    </div>
    <!--右边栏-->
    <div class="jcell body_right">
      <div class="r_top_menu"></div>
      <div>
        <div class="row" style="margin:0; padding:0;">
          <div class="col-sm-4">
            <div class="r_mune_l_box">
              <div class="r_mune_l_box_1">d</div>
              <div class="r_mune_l_box_box">
                <div class="r_mune_l_box_box_1">单号：<span>9999999999999999999</span></div>
                <div class="r_mune_l_box_box_2"><span>桌位：4号</span> <span>人数：4人</span> <span>时间：2016-99-99 00:00</span></div>
                <div class="r_mune_l_box_box_3">
                	<table class="table table-hover" style="margin-bottom:0;">
                      <thead>
                        <tr>
                          <th style="width:80px;">#</th>
                          <th>菜品品名</th>
                          <th>数量</th>
                          <th style="text-align:right">金额</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>dd</td>
                          <td>dd</td>
                          <td>dd</td>
                          <td style="text-align:right">999.00</td>
                        </tr>
                      </tbody>
                      <tr>
                        <td colspan="2" ><strong>合计</strong></td>
                        <td>0</td>
                        <td style="text-align:right">0.00</td>
                      </tr>
                  </table>
                </div>
                <div class="r_mune_l_box_box_4">状态：</div>
              </div>
            </div>
            <div class="r_mune_l_menu">
              <div class="row" style="margin:0; padding:0;">
          	    <div class="col-sm-3" style="margin:0; padding:0 0 0 0;"><button type="button" class="btn btn-warning btn-block">改单</button></div>
                <div class="col-sm-3" style="margin:0; padding:0 5px 0 5px;"><button type="button" class="btn btn-warning btn-block">下单</button></div>
                <div class="col-sm-3" style="margin:0; padding:0 5px 0 0;"><button type="button" class="btn btn-warning btn-block">打印</button></div>
                <div class="col-sm-3" style="margin:0; padding:0 0 0 0;"><button type="button" class="btn btn-success btn-block">结账</button></div>
              </div>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="jtable">
              <div class="jrow">
                <div class="r_menubox">
                  <ul>
                    <li>
                      <div><img src="../addons/j_money/template/img/img.jpg"/></div>
                      <h2>凉拌黑木耳炒粉木耳</h2>
                      <p>9999.99元/份</p>
                    </li>
                  </ul>
                </div>
                <div class="jcell r_tab_menu"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
<script>
$(document).ready(function(e) {
	var _h=$(window).height()
    $(".body_left,.body_right").height(_h);
	$(".r_mune_l_box,.r_menubox ul").height(_h-180);
});
</script>
</html>