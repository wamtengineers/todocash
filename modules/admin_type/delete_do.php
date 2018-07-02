<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from admin_type where id='".slash($_GET["id"])."'",$dblink);
	header("Location: admin_type_manage.php");
	die;
}