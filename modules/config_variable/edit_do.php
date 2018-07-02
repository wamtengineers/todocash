<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["Submit"])){
	extract($_POST);
	$err="";
	if(empty($title) || $type=="null" || $config_type_id==0){
		$err.="Fields with * are manadatory. <br>";
	}
	if($err==""){
		$sql="Update config_variable set `config_type_id`='".slash($config_type_id)."',`title`='".slash($title)."',`notes`='".slash($notes)."',`type`='".slash($type)."',`default_values`='".slash($default_values)."',`key`='".slash($key)."',`value`='".slash($value)."' where id='".$id."'";
		doquery($sql,$dblink);
		sorttable("config_variable", $id, $sortorder, "edit", "config_type_id='".$config_type_id."'");
		unset($_SESSION["config_variable_manage"]["edit"]);
		header('Location: config_variable_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["config_variable_manage"]["edit"][$key]=$value;
		header("Location: config_variable_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from config_variable where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $k=>$v)
			$$k=htmlspecialchars(unslash($v));
		if(isset($_SESSION["config_variable_manage"]["edit"]))
			extract($_SESSION["config_variable_manage"]["edit"]);
	}
	else{
		header("Location: config_variable_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: config_variable_manage.php?tab=list");
	die;
}