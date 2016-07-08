<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '../addons/fm_photosvote/qiniu/uploads'; // Relative to the root

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png','txt'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
    //if (in_array($fileParts['extension'],$fileTypes)) {
        //move_uploaded_file($tempFile,$targetFile);
        //echo '1';
        $storage = new SaeStorage();
 		$domain = 'bndsppqd';
 		$destFileName =  $_FILES["file"]["name"];
 		$srcFileName = $_FILES["file"]["tmp_name"];
 		$attr = array('encoding'=>'gzip');
 		$result = $storage->upload($domain,$destFileName, $srcFileName, -1, $attr, true);
    	echo $result;
    //} else {
    //echo 'Invalid file type.';
    //}
}
?>