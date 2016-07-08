<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
		<script src="http://cdn.bootcss.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	</head>
	<body>
	<!--
		<div>
			<img src="./bg.png" width="100%"/>
			<div style="z-index:999;width:100%;text-align:center;position:absolute;top:30%;color:#fff;font-weight:300;"><?php echo $result?></div>
		</div>
			-->
		 <div class="panel panel-primary" style="height:100%">
			<div class="panel-heading" style="height:10%"> 
				<h3 class="panel-title">消息提示</h3>
			</div>
			<div class="panel-body" style="height:80%">
				<div class="jumbotron">
					<div class="container">
					<?php echo $result?>
					</div>
				</div>
			</div>
			<div class="panel-footer" style="text-align:center;height:10%;"><span>版权由本模块开发者所有 &copy; 2015</span></div>
		</div>
	
	</body>
</html>
		
		