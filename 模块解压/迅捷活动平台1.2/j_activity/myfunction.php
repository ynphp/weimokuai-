<?php 
function saveMedia($url){
	global $_W;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);    
	curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$package = curl_exec($ch);
	$httpinfo = curl_getinfo($ch);
   
	curl_close($ch);
	$media = array_merge(array('mediaBody' => $package), $httpinfo);
	//求出文件格式
	preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
	$extAry=array('jpg','jpeg','png','gif','bmp');
	$fileExt = strtolower($extmatches[1]);
	if(!in_array($fileExt,$extAry)){
		return 0;
	}
	$filename = time().rand(100,999).".{$fileExt}";
	$dirname = "../attachment/images/".$_W['uniacid'].'/'.date("Y")."/".date('m')."/";
	if(!file_exists($dirname)){
		mkdir($dirname,0777,true);
	}
	file_put_contents($dirname.$filename,$media['mediaBody']);
	return $dirname.$filename;
}
?>