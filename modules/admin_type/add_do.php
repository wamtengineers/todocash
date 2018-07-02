<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["admin_type_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO admin_type (title, can_add, can_edit, can_delete, can_read) VALUES ('".slash($title)."','".slash($can_add)."','".slash($can_edit)."','".slash($can_delete)."','".slash($can_read)."')";
		doquery($sql,$dblink);
		unset($_SESSION["admin_type_manage"]["add"]);
		header('Location: admin_type_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["admin_type_manage"]["add"][$key]=$value;
		header('Location: admin_type_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}