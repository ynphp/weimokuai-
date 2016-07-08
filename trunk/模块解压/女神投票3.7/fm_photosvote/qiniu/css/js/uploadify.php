<?php

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {

    


   	include "appconfig.php";
    $arrType=array(".jpg",".gif",".bmp",".jpeg",".png");
    if(strstr($_FILES['file']['name'],".jpg") or strstr($_FILES['file']['name'],".gif") or strstr($_FILES['file']['name'],".bmp") or strstr($_FILES['file']['name'],".jpeg") or strstr($_FILES['file']['name'],".png")){
        date_default_timezone_set('PRC');

		$time=date('Y-m-d H:i:s');
        $nfilename=$time."_".rand(10000,99999)."_".$_FILES["file"]["name"];
    
    	require_once("qiniu/io.php");
		require_once("qiniu/rs.php");

		
        $key1 = $nfilename;											//$_FILES["file"]["name"];


		Qiniu_SetKeys($accessKey, $secretKey);
		$putPolicy = new Qiniu_RS_PutPolicy($bucket);
		$upToken = $putPolicy->Token(null);
		$putExtra = new Qiniu_PutExtra();
		$putExtra->Crc32 = 1;
		list($ret, $err) = Qiniu_PutFile($upToken, $key1, $_FILES["file"]["tmp_name"], $putExtra);
    	//echo "====> Qiniu_PutFile result: \n";
		if ($err !== null) {
    		var_dump($err);
		} else {
            echo "http://".$qiniuurl."/".$nfilename;
            //var_dump($ret);
            $mediaurl = "http://".$qiniuurl."/".$nfilename;

        

    		mysql_query("insert into lts_pic(picname,picurl) values("."'".$nfilename."','". $mediaurl."')");
    		mysql_query("insert into lts_msg(pic,picname,msgtime,username) values("."true,'".$nfilename."','".$time."','".htmlspecialchars($_COOKIE['ltsuser']).""."')");

    		
		}
        
    }else{
        echo "error";
    }
    
    mysql_close($link);
        

    
}
?>