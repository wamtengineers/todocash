<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["items_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if(!empty($_FILES["image"]["tmp_name"]) && !in_array($_FILES["image"]["type"],$imagetypes)){
		$err.="Image format not supported. <br>";
	}
	if($err==""){
		$sql="INSERT INTO items (item_category_id, type, title, unit_price, quantity, sortorder) VALUES ('".slash($item_category_id)."','".slash($type)."','".slash($title)."','".slash($unit_price)."','".slash($quantity)."', '".slash($sortorder)."')";
		doquery($sql,$dblink);
		$id = inserted_id();
		if(!empty($_FILES["image"]["tmp_name"])){
			$image=getFilename($_FILES["image"]["name"], $id." ".$title);
			move_uploaded_file($_FILES["image"]["tmp_name"], $file_upload_root."item/".$image);
			$sql="Update items set image='".$image."' where id=$id";
			doquery($sql,$dblink);
		}
		unset($_SESSION["items_manage"]["add"]);
		header('Location: items_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["items_manage"]["add"][$key]=$value;
		header('Location: items_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}