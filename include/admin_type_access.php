<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( "select * from menu where url = '".$filename."'", $dblink );
if( numrows( $rs ) > 0 ) {
	$r = dofetch( $rs );
	if( numrows( doquery( "select menu_id from menu_2_admin_type where menu_id = '".$r["id"]."' and admin_type_id='".$_SESSION["logged_in_admin"]["admin_type_id"]."'", $dblink ) ) == 0 ) {
		header( "Location: index.php?err=".url_encode("You do not have rights to access this resource.") );
	}
}