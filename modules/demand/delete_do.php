<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	doquery("delete from demand_item where demand_id = '".$id."'", $dblink);
	doquery("delete from demand where id='".$id."'",$dblink);
	header("Location: demand_manage.php");
	die;
}