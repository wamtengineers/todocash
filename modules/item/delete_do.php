<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	$prev_icon=doquery("select image from items where id=$id",$dblink);
	if(numrows($prev_icon)>0){
		$p_icon=dofetch($prev_icon);
		deleteFile($file_upload_root."item/".$p_icon["image"]);
	}
	doquery("delete from items where id='".slash($_GET["id"])."'",$dblink);
	doquery("delete from item_group where item_id='".slash($_GET["id"])."' or group_item_id='".slash($_GET["id"])."'",$dblink);
	header("Location: items_manage.php");
	die;
}