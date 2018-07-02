<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["menu_edit"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if(!empty($_FILES["icon"]["tmp_name"])){
		if(!in_array($_FILES["icon"]["type"],$imagetypes)){
			$err.="Icon format not supported. <br>";
		}
	}
	if($err==""){
		if($parent_id==0)
			$depth=0;
		else{
			$p_depth=dofetch(doquery("select depth from menu where id=".$parent_id,$dblink));
			$depth=$p_depth["depth"];
			$depth+=1;
		}
		$sql="Update menu set `title`='".slash($title)."',`url`='".slash($url)."', `parent_id`='".slash($parent_id)."', `small_icon`='".slash($small_icon)."', `depth`='".slash($depth)."' where id='".$id."'";
		doquery($sql,$dblink);
		sorttable("menu",$id,$sortorder,"edit", "parent_id=".$parent_id);
		
		if(!empty($_FILES["icon"]["tmp_name"])){
			$prev_icon=doquery("select icon from menu where id=$id",$dblink);
			if(numrows($prev_icon)>0){
				$p_icon=dofetch($prev_icon);
				deleteFile($file_upload_root."menu/".$p_icon["icon"]);
			}
			$icon=getFilename($_FILES["icon"]["name"], $title);
			move_uploaded_file($_FILES["icon"]["tmp_name"], $file_upload_root."menu/".$icon);
			createThumb($file_upload_root."/menu/".$icon, $_FILES["icon"]["type"], "48" ,$file_upload_root."/menu/".$icon,"48");
			$sql="Update menu set icon='".slash($icon)."' where id='".$id."'";
			doquery($sql,$dblink);
		}
		doquery("delete from menu_2_admin_type where menu_id='".$id."'", $dblink);
		foreach($admin_type_ids as $admin_type_id){
			doquery( "insert into menu_2_admin_type values('".$id."', '".$admin_type_id."')", $dblink );
		}
		unset($_SESSION["menu_manage"]["edit"]);
		header('Location: menu_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["menu_manage"]["edit"][$key]=$value;
		if( !isset($_POST["admin_type_ids"]) )
			$_SESSION["menu_manage"]["edit"]["admin_type_ids"] = array();
		header("Location: menu_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from menu where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		$admin_type_ids = array();
		$rs =doquery("select admin_type_id from menu_2_admin_type where menu_id='".$id."'", $dblink);
		if( numrows( $rs ) > 0 ) {
			while( $r = dofetch( $rs ) ) {
				$admin_type_ids[] = $r[ "admin_type_id" ];
			}
		}
		if(isset($_SESSION["menu_manage"]["edit"]))
			extract($_SESSION["menu_manage"]["edit"]);
	}
	else{
		header("Location: menu_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: menu_manage.php?tab=list");
	die;
}