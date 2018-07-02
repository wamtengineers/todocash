<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["customer_edit"])){
	extract($_POST);
	$err="";
	if(empty($customer_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update customer set `customer_name`='".slash($customer_name)."',`phone`='".slash($phone)."', `address`='".slash($address)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["customer_manage"]["edit"]);
		header('Location: customer_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["customer_manage"]["edit"][$key]=$value;
		header("Location: customer_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from customer where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["customer_manage"]["edit"]))
			extract($_SESSION["customer_manage"]["edit"]);
	}
	else{
		header("Location: customer_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: customer_manage.php?tab=list");
	die;
}