<?php
if(!defined("APP_START")) die("No Direct Access");

if(isset($_GET["action"]) && $_GET["action"]!=""){
	$bulk_action=$_GET["action"];
	$id=explode(",",urldecode($_GET["Ids"]));	
	$err="";
	if($bulk_action=="null"){
		$err.="Select Action. <br>";
	}
	if(!isset($_GET["Ids"]) || $_GET["Ids"]==""){
		$err.="Select Records. <br>";	
	}
	if(empty($err)){
		if($bulk_action=="delete"){
			$i=0;
			while($i<count($id)){
				$prev_icon=doquery("select icon from menu where id='".$id[$i]."'",$dblink);
				if(numrows($prev_icon)>0){
					$p_icon=dofetch($prev_icon);
					deleteFile($file_upload_root."menu/".$p_icon["icon"]);
				}
				$r=dofetch(doquery("select parent_id from menu where id='".$id[$i]."'", $dblink));
				sorttable("menu",$id,"","delete", "parent_id='".$r["parent_id"]."'");
				doquery("delete from menus where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: menu_manage.php?tab=list&msg=".url_encode("Records Deleted."));
			die;
		}
	}
	else{
		header("Location: menu_manage.php?tab=list&err=".url_encode($err));
		die;					
	}
}
else{
	header("Location: index.php");
	die;	
}