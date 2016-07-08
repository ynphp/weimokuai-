<!DOCTYPE html>
<html>
<head>
<title>聊天室</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>


<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="./css/bootstrap.min.css">

<!-- 可选的Bootstrap主题文件（一般不用引入） -->
<link rel="stylesheet" href="./css/bootstrap-theme.min.css">

<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="./js/jquery.min.js"></script>

<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="./js/bootstrap.min.js"></script>
    
<!-- 加载uploadify -->
<script src="/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
    
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
   

  <!--[if lte IE 6]>
  <link rel="stylesheet" type="text/css" href="dist/css/bootstrap-ie6.css">
  <![endif]-->
  <!--[if lte IE 7]>
  <link rel="stylesheet" type="text/css" href="dist/css/ie.css">
  <![endif]-->

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.min.js"></script>
  <script src="http://cdn.bootcss.com/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->

    
<script src="/js/lts.js"></script>




</head>




<body>
    <style>
        body{
			background-color: #5bc0de;
		}
    </style>
    


    <!--[if lte IE 9]>
    <div id="warning_info" class="alert alert-warning">
        <button data-dismiss="alert" class="close" type="button">&times;</button>
        <strong>您正在使用低版本浏览器，</strong> 在本页面的显示效果可能有差异。
        建议您升级到以下浏览器：
        <a href="http://www.google.cn/intl/zh-CN/chrome/" target="_blank">Chrome</a> /
        <a href="www.mozilla.org/en-US/firefox/‎" target="_blank">Firefox</a> /
        <a href="http://www.apple.com.cn/safari/" target="_blank">Safari</a> /
        <a href="http://www.opera.com/" target="_blank">Opera</a> /
        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie" target="_blank">
            Internet Explorer 10</a>
    </div>
    <![endif]-->

<div class="container">

    <div align="center"><h1><font color="white">聊天室demo</font></h1></div>
    <br>
    <div class="col-md-2"></div>
    
	<div class="col-md-8">
        
		<div name="contents" id="contents" style="height:450px; overflow:auto; background-color: white;">
            
          <?php

			include "ltscontents.php";								//加载聊天室

          ?>

    
		</div>


        <br>
        <form class="form-horizontal" role="form" id="form1">
  			<div class="form-group">
    			<label class="sr-only" for="username">用户名</label>
    			<div class="col-sm-3">
    				<input type="text" class="form-control" id="username" name="username" placeholder="用户名">
    			</div>
    			<div class="col-sm-9">
    				<textarea id="msg" name="msg" class="form-control" rows="5"></textarea>
    			</div>
  			</div>

            
		</form>
            <br>
            <div align="center">
                <button class="btn btn-success" onclick="sendpic();">上传照片</button>
                <button class="btn btn-success" onclick="sendmsg();">发送信息</button>
            </div>
        
	</div>


	<div class="col-md-2"></div>
    
    
    
    
    
    
</div>
    
    
    
    
	<div class="modal fade" id="Modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
    		<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        			<h4 class="modal-title">设置用户名</h4>
      			</div>
      			<div class="modal-body">
        			
                    <form class="form-inline" role="form" id="setuserform" name="setuserform" method="post" action="index.php" enctype="multipart/form-data">
  						<div class="form-group">
    						<label>请输入用户名：</label>
    						<input class="form-control" type="text" name="user" id="user">
  						</div>
					</form>
                    
      			</div>
      			<div class="modal-footer">
            		<button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
        			<button type="button" class="btn btn-success" data-dismiss="modal" onclick="setCookie('ltsuser',setuserform.user.value);form1.username.value=setuserform.user.value;">完成</button>
      			</div>
    		</div><!-- /.modal-content -->
  		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
    





    <div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
    		<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        			<h4 class="modal-title">上传照片</h4>
      			</div>
      			<div class="modal-body">
        			
                    <form class="form-inline" role="form" id="uploadform" name="uploadform" method="post" action="index.php" enctype="multipart/form-data">
  						<div class="form-group">
    						<label>请选择照片：</label>
    						<input class="form-control" type="file" name="file1" id="file1">
  						</div>
					</form>
                    <img src="" id="uploadimg" name="uploadimg">
                    
      			</div>
      			<div class="modal-footer">
            		<button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
        			<button type="button" class="btn btn-success" data-dismiss="modal"onclick="reflash();">确定</button>
      			</div>
    		</div><!-- /.modal-content -->
  		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
    
    <!-- Modal -->
	<div class="modal fade" id="showpic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg">
    		<div class="modal-content">
     		 	<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        			<h4 class="modal-title" id="myModalLabel">照片</h4>
      			</div>
      			<div class="modal-body">

				<img id="pic" name="pic" src="" class="img-thumbnail">
      			</div>
    		</div>
  		</div>
	</div>
    
	<script>
        var ltsuser=getCookie("ltsuser");
        if(ltsuser==null){
        	$('#Modal1').modal('toggle');
        }else{
            form1.username.value=ltsuser;
        }
        setInterval(reflash, 10000);
        
        function sendmsg(){
         	
            if(form1.username.value==""){
                $('#Modal1').modal('toggle');
            }else{
                if(form1.msg.value==""){
                    alert("请输入内容！");
                }else{
                    setCookie('ltsuser',form1.username.value);
                    
                    var r=$.ajax({
  						url: "ltsmsg.php?username=" + form1.username.value + "&msg=" + form1.msg.value,
  						type: "POST",
                        data: {},
  						dataType: "html"
					});
                    r.fail(function(jqXHR, textStatus) {
  						alert( "发送失败: " + textStatus );
					});
                    form1.msg.value="";
                    reflash();
                    
                }
            }
            
        }
        
        
        function sendpic(){
         	
            var ltsuser=getCookie("ltsuser");
            if(form1.username.value=="" && ltsuser==null){
                $('#Modal1').modal('toggle');
            }else{
                if(form1.username.value==""){
                    $('#Modal2').modal('toggle');
                }else{
                    setCookie('ltsuser',form1.username.value);
                    $('#Modal2').modal('toggle');
                }
            }
            
        }
        
        
        function reflash(){

			$.get("ltscontents.php", function(data){
                contents.innerHTML=data;
			});
            
        }
        
    </script>
    
   <script type="text/javascript">
		<?php $timestamp = time();?>
		$(function() {
			$('#file1').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
                'auto'     : true,
                'multi'       : true,
                'buttonText' : '选择文件',
                'fileObjName' : 'file',
                'swf'      : 'uploadify/uploadify.swf',
                'uploader' : 'uploadify.php?username=' + getCookie("ltsuser"),
                'onUploadSuccess' : function(file, data, response) {
                    if(data=="error"){
                        alert("错误的文件类型！");
                    }else{
                    	uploadimg.src=data;
                    }
                }
			});
		});
	</script>
    
    
    
  <!--[if lte IE 6]>
  <script type="text/javascript" src="dist/js/bootstrap-ie.js"></script>
  <![endif]-->

</body>

  



</html>