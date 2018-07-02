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
				$prev_icon=doquery("select image from items where id='".$id[$i]."'",$dblink);
				if(numrows($prev_icon)>0){
					$p_icon=dofetch($prev_icon);
					deleteFile($file_upload_root."item/".$p_icon["image"]);
				}
				doquery("delete from items where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: items_manage.php?tab=list&msg=".url_encode("Records Deleted."));
			die;
		}
		if($bulk_action=="statuson"){
			$i=0;
			while($i<count($id)){
				doquery("update items set status=1 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: items_manage.php?tab=list&msg=".url_encode("Records Status On."));
			die;
		}
		if($bulk_action=="statusof"){
			$i=0;
			while($i<count($id)){
				doquery("update items set status=0 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: items_manage.php?tab=list&msg=".url_encode("Records Status Off."));
			die;
		}
	}
	else{
		header("Location: items_manage.php?tab=list&err=".url_encode($err));
		die;					
	}
}
else{
	header("Location: index.php");
	die;	
}