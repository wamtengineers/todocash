<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from supplier_payment where id='".slash($_GET["id"])."'",$dblink);
	header("Location: supplier_payment_manage.php");
	die;
}