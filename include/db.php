<?php
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();
$db_host="HOST";
$db_username="USER";
$db_password="PASSWORD";
$db_name="DATABASE_NAME";

$dblink=mysqli_connect($db_host,$db_username,$db_password,$db_name);
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

function doquery($qr,$lnk){
	@$res=mysqli_query($lnk, $qr) or die($qr." - ".mysqli_error($lnk));
	return $res;
}

function dofetch($rs){
	@$rec=mysqli_fetch_array($rs);
	return $rec;
}

function numrows($rs){
	return mysqli_num_rows($rs);
}
function inserted_id(){
	global $dblink;
	return mysqli_insert_id($dblink);
}
