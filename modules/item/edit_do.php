<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["items_edit"])){
	extract($_POST);
	$err="";
	if(empty($title) || empty($unit_price) || $quantity == "")
		$err="Fields with (*) are Mandatory.<br />";
	if(!empty($_FILES["image"]["tmp_name"]) && !in_array($_FILES["image"]["type"],$imagetypes)){
		$err.="Image format not supported. <br>";
	}
	if($err==""){
		$sql="Update items set `item_category_id`='".slash($item_category_id)."',`type`='".slash($type)."',`title`='".slash($title)."',`unit_price`='".slash($unit_price)."', `quantity`='".slash($quantity)."', `sortorder`='".slash($sortorder)."' where id='".$id."'";
		doquery($sql,$dblink);
		if(!empty($_FILES["image"]["tmp_name"]) || isset($delete_image)){
			$prev_icon=doquery("select image from items where id='".$id."'",$dblink);
			if(numrows($prev_icon)>0){
				$p_icon=dofetch($prev_icon);
				deleteFile($file_upload_root."item/".$p_icon["image"]);
				$sql="Update items set image='' where id='".$id."'";
				doquery($sql,$dblink);
			}
			if(!empty($_FILES["image"]["tmp_name"])){
				$image=getFilename($_FILES["image"]["name"], $id." ".$title);
				move_uploaded_file($_FILES["image"]["tmp_name"], $file_upload_root."item/".$image);
				$sql="Update items set image='".slash($image)."' where id='".$id."'";
				doquery($sql,$dblink);
			}
		}
		unset($_SESSION["items_manage"]["edit"]);
		header('Location: items_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["items_manage"]["edit"][$key]=$value;
		header("Location: items_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from items where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["items_manage"]["edit"]))
			extract($_SESSION["items_manage"]["edit"]);
	}
	else{
		header("Location: items_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: items_manage.php?tab=list");
	die;
}