<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["supplier_payment_add"])){
	extract($_POST);
	$err="";
	if(empty($supplier_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO supplier_payment (supplier_id, datetime, amount) VALUES ('".slash($supplier_id)."','".slash(datetime_dbconvert($datetime))."','".slash($amount)."')";
		doquery($sql,$dblink);
		unset($_SESSION["supplier_payment_manage"]["add"]);
		header('Location: supplier_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["supplier_payment_manage"]["add"][$key]=$value;
		header('Location: supplier_payment_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}