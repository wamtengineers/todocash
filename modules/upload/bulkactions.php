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
				$prev_file=doquery("select filelocation from uploads where id=".$id[$i],$dblink);
				if(numrows($prev_file)>0){
					$p_file=dofetch($prev_file);
					if(is_file($file_upload_root."upload/".$p_file["filelocation"]))
						unlink($file_upload_root."upload/".$p_file["filelocation"]);
				}			
				doquery("delete from uploads where id=".$id[$i],$dblink);
				$i++;
			}
			header("location:upload_manage.php?tab=list&msg=".url_encode("Files Deleted."));
			die;
		}
	}
	else{
		header("location:upload_manage.php?tab=list&err=".url_encode($err));
		die;					
	}
	
}

else{
	header("location:index.php");
	die;	
}
?>