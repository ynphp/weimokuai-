<?php
	include "appconfig.php";
 
    $sql=mysql_query("select*from lts_msg order by msgtime desc");
    $info=mysql_fetch_array($sql);
	$nums=mysql_num_rows($sql);
	$num=1;
	do{
?>
            
            
    <div class="panel panel-default">
  		<div class="panel-body">
    		<?php
        		if($info['pic']==false){	
        	?>
            		<span class="label label-primary">时间</span>&nbsp;&nbsp;<?php if($nums==0){ echo date('Y-m-d H:i:s'); } ?><?php echo $info['msgtime']; ?>&nbsp;&nbsp;
            		<span class="label label-primary">用户</span>&nbsp;&nbsp;<?php if($nums==0){ echo "系统"; } ?><?php echo $info['username']; ?>
            		<hr>
            		<?php echo $info['msg']; ?><?php if($nums==0){ echo "暂时没有内容！"; } ?>
            <?php
                }else{
                        $picsql=mysql_query("select*from lts_pic where picname='".$info['picname']."'");
    					$picinfo=mysql_fetch_array($picsql);
            ?>
            		<span class="label label-primary">时间</span>&nbsp;&nbsp;<?php echo $info['msgtime']; ?>&nbsp;&nbsp;
            		<span class="label label-primary">用户</span>&nbsp;&nbsp;<?php echo $info['username']; ?>
            		<hr>
            		<div class="col-md-3">
            			<a href="#" class="thumbnail" data-toggle="modal" data-target="#showpic" onclick="<?php echo "pic.src='".$picinfo['picurl']."';"; ?>">
            				<img src="<?php echo $picinfo['picurl']; ?>">
						</a>
            		</div>
            <?php
                }
        	?>
  		</div>
	</div>
            
            
<?php
        $num++;
        if($num>50){
            break;
        }
	}while($info=mysql_fetch_array($sql));

	mysql_close($link);

?>