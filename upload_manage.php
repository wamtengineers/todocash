<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "delete", "bulk_action", "upload_center");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/upload/add_do.php");
	break;
	case 'delete':
		include("modules/upload/delete_do.php");
	break;
	case 'upload_center':
		include("modules/upload/upload_center_do.php");
	break;
	case 'bulk_action':
		include("modules/upload/bulkactions.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/upload/list.php");
			break;
			case 'add':
				include("modules/upload/add.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>
</body>
</html>