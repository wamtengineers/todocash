<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["supplier_payment_edit"])){
	extract($_POST);
	$err="";
	if(empty($supplier_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update supplier_payment set `supplier_id`='".slash($supplier_id)."',`datetime`='".slash(datetime_dbconvert(unslash($datetime)))."', `amount`='".slash($amount)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["supplier_payment_manage"]["edit"]);
		header('Location: supplier_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["supplier_payment_manage"]["edit"][$key]=$value;
		header("Location: supplier_payment_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from supplier_payment where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["supplier_payment_manage"]["edit"]))
			extract($_SESSION["supplier_payment_manage"]["edit"]);
	}
	else{
		header("Location: supplier_payment_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: supplier_payment_manage.php?tab=list");
	die;
}