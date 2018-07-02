<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["Submit"])){
	foreach($_POST as $key=>$value)
		$$key=$value;
	$err="";
	if(empty($filename)  || empty($_FILES["filelocation"]["tmp_name"])){
		$err.="Fields with * are manadatory. <br>";
	}	
	if(empty($err)){
		doquery("insert into uploads(`filename`) values('".slash($filename)."')",$dblink);
		$id=inserted_id();
		$newname=getFilename($_FILES["filelocation"]["name"], $filename);
		move_uploaded_file($_FILES["filelocation"]["tmp_name"], $file_upload_root."upload/".$newname);
		doquery("update uploads set filelocation='".$newname."' where id=$id",$dblink);
		unset($_SESSION["upload_manage"]["add"]);
		header('Location:upload_manage.php?tab=list&msg='.url_encode("Sucessfully Uploaded"));		
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["upload_manage"]["add"][$key]=$value;
		header('Location: upload_manage.php?tab=add&err='.url_encode($err));
		die;		
	}
}