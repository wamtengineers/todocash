<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["admin_edit"])){
	extract($_POST);
	$err="";
	if(empty($name) || empty($username) || empty($email))
		$err="Fields with (*) are Mandatory.<br />";
	if(!empty($email) && !emailok($email))
		$err.="E-mail is not valid.<br />";
	if(numrows(doquery("select id from admin where username='".slash($username)."' and id<>'".$id."'", $dblink))>0)
		$err.='Username already exists.<br />';
	if(numrows(doquery("select id from admin where email='".slash($email)."' and id<>'".$id."'", $dblink))>0)
		$err.='Email address already exists.<br />';
	if($err==""){
		$sql="Update admin set `admin_type_id`='".slash($admin_type_id)."', `username`='".slash($username)."',`name`='".slash($name)."', `email`='".slash($email)."'".(!empty($password)? ", `password`='".slash($password)."'":"")." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["admin_manage"]["edit"]);
		header('Location: admin_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["admin_manage"]["edit"][$key]=$value;
		header("Location: admin_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from admin where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["admin_manage"]["edit"]))
			extract($_SESSION["admin_manage"]["edit"]);
	}
	else{
		header("Location: admin_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: admin_manage.php?tab=list");
	die;
}