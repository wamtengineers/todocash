<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["item_category_edit"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update item_category set `parent_id`='".slash($parent_id)."', `title`='".slash($title)."', `sortorder`='".slash($sortorder)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["item_category_manage"]["edit"]);
		header('Location: item_category_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["item_category_manage"]["edit"][$key]=$value;
		header("Location: item_category_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from item_category where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["item_category_manage"]["edit"]))
			extract($_SESSION["item_category_manage"]["edit"]);
	}
	else{
		header("Location: item_category_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: item_category_manage.php?tab=list");
	die;
}