<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from item_group where id='".slash($_GET["id"])."' and group_item_id='".$parent_group_item_id."'",$dblink);
	header("Location: item_group_manage.php?tab=list&msg=".url_encode("Record Deleted."));
	die;
}