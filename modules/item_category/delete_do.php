<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from item_category where id='".slash($_GET["id"])."'",$dblink);
	header("Location: item_category_manage.php");
	die;
}