<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["menu_add"])){
	extract($_POST);
	$err="";
	if(empty($title)  || empty($_FILES["icon"]["tmp_name"]))
		$err="Fields with (*) are Mandatory.<br />";
	if(!in_array($_FILES["icon"]["type"],$imagetypes)){
		$err.="Icon format not supported. <br>";
	}
	if($err==""){
		if($parent_id==0)
			$depth=0;
		else{
			$p_depth=dofetch(doquery("select depth from menu where id=".$parent_id,$dblink));
			$depth=$p_depth["depth"];
			$depth+=1;
		}
		
		$sql="INSERT INTO menu (title, url, parent_id, small_icon, depth) VALUES ('".slash($title)."','".slash($url)."','".slash($parent_id)."','".slash($small_icon)."','".slash($depth)."')";
		doquery($sql,$dblink);
		
		$id=inserted_id();
		sorttable("menu",$id,$sortorder,"add", "parent_id=".$parent_id);
		
		$icon=getFilename($_FILES["icon"]["name"], $title);
		move_uploaded_file($_FILES["icon"]["tmp_name"], $file_upload_root."menu/".$icon);		
		createThumb($file_upload_root."/menu/".$icon, $_FILES["icon"]["type"], "48" ,$file_upload_root."/menu/".$icon,"48");
		
		$sql="Update menu set icon='".$icon."' where id=$id";
		doquery($sql,$dblink);
		foreach($admin_type_ids as $admin_type_id){
			doquery( "insert into menu_2_admin_type values('".$id."', '".$admin_type_id."')", $dblink );
		}
		
		unset($_SESSION["menu_manage"]["add"]);
		header('Location: menu_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["menu_manage"]["add"][$key]=$value;
		if( !isset($_POST["admin_type_ids"]) )
			$_SESSION["menu_manage"]["add"]["admin_type_ids"] = array();
		header('Location: menu_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}