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
				$r=dofetch(doquery("select config_type_id from config_variable where id='".$id[$i]."'", $dblink));
				sorttable("config_variable", $id[$i], 0, "delete", "config_type_id='".$r["config_type_id"]."'");
				doquery("delete from config_variable where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: config_variable_manage.php?tab=list&msg=".url_encode("Records Deleted."));
			die;
		}
	}
	else{
		header("Location: config_variable_manage.php?tab=list&err=".url_encode($err));
		die;					
	}
}
else{
	header("Location: index.php");
	die;	
}