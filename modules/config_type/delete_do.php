<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	sorttable("config_type", $id, 0, "delete");
	doquery("delete from config_type where id='".$id."'",$dblink);
	header("Location: config_type_manage.php");
	die;
}