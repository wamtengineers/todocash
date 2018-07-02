<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["item_group_edit"])){
	extract($_POST);
	$err="";
	if(empty($item_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update item_group set `item_id`='".slash($item_id)."',`quantity`='".slash($quantity)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["item_group_manage"]["edit"]);
		header('Location: item_group_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["item_group_manage"]["edit"][$key]=$value;
		header('Location: item_group_manage.php?tab=edit&err='.url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from item_group where id='".slash($_GET["id"])."' and group_item_id='".$parent_group_item_id."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["item_group_manage"]["edit"]))
			extract($_SESSION["item_group_manage"]["edit"]);
	}
	else{
		header('Location: item_group_manage.php?tab=list');
		die;
	}
}
else{
	header('Location: item_group_manage.php?tab=list');
	die;
}