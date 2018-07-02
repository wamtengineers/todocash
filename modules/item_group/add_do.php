<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["item_group_add"])){
	extract($_POST);
	$err="";
	if(empty($item_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO item_group (group_item_id, item_id, quantity) VALUES ('".slash($parent_group_item_id)."', '".slash($item_id)."', '".slash($quantity)."')";
		doquery($sql,$dblink);
		unset($_SESSION["item_group_manage"]["add"]);
		header('Location: item_group_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["item_group_manage"]["add"][$key]=$value;
		header('Location: item_group_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}