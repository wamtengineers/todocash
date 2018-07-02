<?php
if(isset($_POST["login_submit"])){
	$loginname=slash($_POST["username"]);
	$password=slash($_POST["password"]);
	$msg="";
	if(strlen($loginname)<1) $msg.="Enter Your Username <br />";
	if(strlen($password)<1) $msg.="Enter the Password <br />";
	if($msg==""){
		$qr="select * from admin where username='".$loginname."' and password='".$password."' and status=1";
		$res=doquery($qr, $dblink);
		if(numrows($res)>0){
	 		$row=dofetch($res);
	 		$_SESSION["logged_in_admin"]=$row;
			if(isset($_POST["remmeber_me"])){
				setcookie('_admin_logged_in', $row["id"], strtotime('+14 days'));
			}
			header('Location: index.php');
			die;
	 	}
		$_SESSION["login"]["err"]='Invalid User Name / Password';
	}
	else{
		$_SESSION["login"]["err"]=$msg;
	}	
	$_SESSION["login"]["username"]=$_POST["username"];
	$_SESSION["login"]["password"]=$_POST["password"];
	if(isset($_POST["remmeber_me"])) $_SESSION["login"]["remmeber_me"]=$_POST["remmeber_me"]; else unset($_SESSION["login"]["remmeber_me"]);
	header('Location: login.php');
	die;
}