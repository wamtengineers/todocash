<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["supplier_edit"])){
	extract($_POST);
	$err="";
	if(empty($supplier_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update supplier set `supplier_name`='".slash($supplier_name)."',`phone`='".slash($phone)."', `address`='".slash($address)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["supplier_manage"]["edit"]);
		header('Location: supplier_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["supplier_manage"]["edit"][$key]=$value;
		header("Location: supplier_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from supplier where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["supplier_manage"]["edit"]))
			extract($_SESSION["supplier_manage"]["edit"]);
	}
	else{
		header("Location: supplier_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: supplier_manage.php?tab=list");
	die;
}