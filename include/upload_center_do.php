<?php
include("../include/db.php");
include("../include/session.php");
include("../include/utility.php");
if(isset($_POST["file_submit"])){
	extract($_POST);
	if(empty($title) || empty($_FILES["file"]["tmp_name"])){
		echo "0#Please enter title and select file.";
		die;
	}
	doquery("Insert into uploads(`filename`) values('".utf8_encode(addslashes($title))."')",$dblink);
	$id=inserted_id();
	$newname=getFilename($_FILES["file"]["name"], $title);
	move_uploaded_file($_FILES["file"]["tmp_name"], $file_upload_root."upload/".$newname);
	doquery("Update uploads set filelocation='".$newname."' where id=$id",$dblink);
	echo "1#".$site_url."/uploads/upload/".$newname."#".$title;
	die;
}
?>