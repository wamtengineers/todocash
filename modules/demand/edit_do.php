<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["Submit"])){
	extract($_POST);
	$err="";
	if(empty($date)){
		$err.="Fields with * are manadatory. <br>";
	}
	if($err==""){
		$sql="Update demand set `date`='".date_dbconvert($date)."' where id='".$id."'";
		doquery($sql,$dblink);
		doquery("delete from demand_item where demand_id = '".$id."'", $dblink);
		$rs = doquery( "select * from items where type=0 order by sortorder", $dblink );
		if( numrows( $rs ) > 0 ) {
			while( $r = dofetch( $rs ) ) {
				if( isset( $stock["item_".$r["id"]] ) && isset( $demand["item_".$r["id"]] ) ){
					doquery( "insert into demand_item( demand_id, items_id, stock, demand ) values( '".$id."', '".$r[ "id" ]."', '".slash($stock["item_".$r["id"]])."', '".slash($demand["item_".$r["id"]])."' )", $dblink );
				}
			}
		}
		unset($_SESSION["demand_manage"]["edit"]);
		header('Location: demand_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["demand_manage"]["edit"][$key]=$value;
		header("Location: demand_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from demand where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		$date = date_convert($date);
		if(isset($_SESSION["demand_manage"]["edit"]))
			extract($_SESSION["demand_manage"]["edit"]);
	}
	else{
		header("Location: demand_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: demand_manage.php?tab=list");
	die;
}