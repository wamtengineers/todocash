<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["item_category_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO item_category (parent_id, title, sortorder) VALUES ('".slash($parent_id)."', '".slash($title)."', '".slash($sortorder)."')";
		doquery($sql,$dblink);
		unset($_SESSION["item_category_manage"]["add"]);
		header('Location: item_category_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["item_category_manage"]["add"][$key]=$value;
		header('Location: item_category_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}