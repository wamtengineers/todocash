<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["Submit"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO config_type (title) VALUES ('".slash($title)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		sorttable("config_type", $id, $sortorder, "add");
		unset($_SESSION["config_type_manage"]["add"]);
		header('Location: config_type_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["config_type_manage"]["add"][$key]=$value;
		header('Location: config_type_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}