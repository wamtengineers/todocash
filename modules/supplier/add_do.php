<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["supplier_add"])){
	extract($_POST);
	$err="";
	if(empty($supplier_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO supplier (supplier_name, phone, address) VALUES ('".slash($supplier_name)."','".slash($phone)."','".slash($address)."')";
		doquery($sql,$dblink);
		unset($_SESSION["supplier_manage"]["add"]);
		header('Location: supplier_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["supplier_manage"]["add"][$key]=$value;
		header('Location: supplier_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}