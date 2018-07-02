<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from customer where id='".slash($_GET["id"])."'",$dblink);
	header("Location: customer_manage.php");
	die;
}