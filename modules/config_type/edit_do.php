<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["Submit"])){
	extract($_POST);
	$err="";
	if(empty($title)){
		$err.="Fields with * are manadatory. <br>";
	}
	if($err==""){
		$sql="Update config_type set `title`='".$title."' where id='".$id."'";
		doquery($sql,$dblink);
		sorttable("config_type", $id, $sortorder, "edit");
		unset($_SESSION["config_type_manage"]["edit"]);
		header('Location: config_type_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["config_type_manage"]["edit"][$key]=$value;
		header("Location: config_type_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from config_type where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["config_type_manage"]["edit"]))
			extract($_SESSION["config_type_manage"]["edit"]);
	}
	else{
		header("Location: config_type_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: config_type_manage.php?tab=list");
	die;
}