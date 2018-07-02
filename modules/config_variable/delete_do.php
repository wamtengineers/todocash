<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	$r=dofetch(doquery("select config_type_id from config_variable where id='".$id."'", $dblink));
	sorttable("config_variable", $id, 0, "delete", "config_type_id='".$r["config_type_id"]."'");
	doquery("delete from config_variable where id='".$id."'",$dblink);
	header("Location: config_variable_manage.php");
	die;
}