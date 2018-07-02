<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["Submit"])){
	extract($_POST);
	$err="";
	if(empty($date))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO demand (date) VALUES ('".date_dbconvert($date)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		$rs = doquery( "select * from items where type=0 order by sortorder", $dblink );
		if( numrows( $rs ) > 0 ) {
			while( $r = dofetch( $rs ) ) {
				if( isset( $stock["item_".$r["id"]] ) && isset( $demand["item_".$r["id"]] ) ){
					doquery( "insert into demand_item( demand_id, items_id, stock, demand ) values( '".$id."', '".$r[ "id" ]."', '".slash($stock["item_".$r["id"]])."', '".slash($demand["item_".$r["id"]])."' )", $dblink );
				}
			}
		}
		unset($_SESSION["demand_manage"]["add"]);
		header('Location: demand_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["demand_manage"]["add"][$key]=$value;
		header('Location: demand_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}