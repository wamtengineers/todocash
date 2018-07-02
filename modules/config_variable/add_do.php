<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["config_variable_manage"])){
	extract($_POST);
	$err="";
	if(empty($title) || $type=="null" || $config_type_id==0)
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO config_variable (config_type_id, title, notes, type, default_values, `key`, `value`) VALUES ('".slash($config_type_id)."','".slash($title)."','".slash($notes)."','".slash($type)."','".slash($default_values)."','".slash($key)."','".slash($value)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		sorttable("config_variable", $id, $sortorder, "add", "config_type_id='".$config_type_id."'");
		unset($_SESSION["config_variable_manage"]["add"]);
		header('Location: config_variable_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["config_variable_manage"]["add"][$key]=$value;
		header('Location: config_variable_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}