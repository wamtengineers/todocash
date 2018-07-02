<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && isset($_GET["tab"]) && $_GET["id"]!=0){
	$id=slash($_GET["id"]);
		$prev_img=doquery("select filelocation from uploads where id='".$id."'",$dblink);
		if(numrows($prev_img)>0){
			$p_img=dofetch($prev_img);
			deleteFile($file_upload_root."upload/".$p_img["filelocation"]);	
		}
		doquery("delete from uploads where id=".$id,$dblink);
		header("Location: upload_manage.php?tab=list&msg=".url_encode("File Deleted."));
	}
?>
