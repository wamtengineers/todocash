<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["admin_type_edit"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update admin_type set `title`='".slash($title)."',`can_add`='".slash($can_add)."',`can_edit`='".slash($can_edit)."',`can_delete`='".slash($can_delete)."',`can_read`='".slash($can_read)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["admin_type_manage"]["edit"]);
		header('Location: admin_type_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["admin_type_manage"]["edit"][$key]=$value;
		header("Location: admin_type_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from admin_type where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["admin_type_manage"]["edit"]))
			extract($_SESSION["admin_type_manage"]["edit"]);
	}
	else{
		header("Location: admin_type_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: admin_type_manage.php?tab=list");
	die;
}