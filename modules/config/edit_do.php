<?php
if(!defined("APP_START")) die("No Direct Access");

//----------------------------------			
if(isset($_POST["config_edit"])){
	extract($_POST);
	$type="";
	$default_values="";
	$value="";
//--------------------------------------------------------------------------------	
	if(isset($config_id) && $config_id!=0 && !empty($config_id))
		$extra="and config_type_id=".$config_id;
	else
		$extra="";
	//echo $extra;
//-------------------------------------------------------------------------------------	
	$sql="Select * from config_variable where 1 $extra";
	$res=doquery($sql,$dblink);
	if(numrows($res)>0){
		while($rec=dofetch($res)){
			$type=unslash($rec["type"]);
			$value=unslash($rec["value"]);
			$default_values=unslash($rec["default_values"]);
			switch($type){
				case "text":
				case "editor":
				case "textarea":
					$sql="Update config_variable set value='".slash($_POST[$type."_".$rec["id"]])."' where id='".$rec["id"]."'";
					doquery($sql,$dblink);
				break;
				case "checkbox":
					$f_value="";
					if(isset($_POST[$type."_".$rec["id"]])){
						$checkarray=explode(";",$default_values);
						foreach($checkarray as $check){
							$check=str_replace(":selected", "", $check);
							if(in_array($check,$_POST[$type."_".$rec["id"]])){
								$f_value.=$check.":selected;";
							}
							else{
								$f_value.=$check.";";
							}
						}
						$f_value=substr($f_value,0,strlen($f_value)-1);
					}
					else{
						$f_value.=str_replace(":selected","",$default_values);
						}
					$sql="Update config_variable set default_values='".slash($f_value)."' where id='".$rec["id"]."'";
					doquery($sql,$dblink);
				break;
				case "radio":
				case "combobox":
					$f_value="";
					if(isset($_POST[$type."_".$rec["id"]])){
						$checkarray=explode(";",$default_values);
						foreach($checkarray as $check){
							$check=str_replace(":selected", "", $check);
							if($check==$_POST[$type."_".$rec["id"]]){
								$f_value.=$check.":selected;";
							}
							else{
								$f_value.=$check.";";
							}
						}
						$f_value=substr($f_value,0,strlen($f_value)-1);
					}
					$sql="Update config_variable set default_values='".slash($f_value)."' where id='".$rec["id"]."'";
					doquery($sql,$dblink);
				break;
				case "file":
					if(!empty($_FILES[$type."_".$rec["id"]]["tmp_name"])){
						if($value!=""){
							if(is_file($file_upload_root."config/".$value))	unlink($file_upload_root."config/".$value);
						}
						$img_ext=explode(".",$_FILES[$type."_".$rec["id"]]["name"]);
						$img=unslash($rec["key"]).".".$img_ext[count($img_ext)-1];	
						move_uploaded_file($_FILES[$type."_".$rec["id"]]["tmp_name"],$file_upload_root."config/".$img);
						$sql="Update config_variable set value='".$img."' where id='".$rec["id"]."'";
						doquery($sql,$dblink);
						
					}
			}
		}
		header("location:config_manage.php?msg=".url_encode("Settings updated.").((isset($config_id) && $config_id!=0 && !empty($config_id))? "&config_id=".$config_id:""));
		die;		
	}
	else{
		header("location:config_manage.php?err=".url_encode("No configuration variable found."));
		die;				
		}
}
?>