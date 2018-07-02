<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'purchase_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "get_unit_price","get_quantity", "print", "report");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/purchase/add_do.php");
	break;
	case 'edit':
		include("modules/purchase/edit_do.php");
	break;
	case 'delete':
		include("modules/purchase/delete_do.php");
	break;
	case 'status':
		include("modules/purchase/status_do.php");
	break;
	case 'bulk_action':
		include("modules/purchase/bulkactions.php");
	break;
	case "print":
		include("modules/purchase/print.php");
	break;
	case 'report':
		include("modules/purchase/report.php");
		die;
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/purchase/list.php");
			break;
			case 'add':
				include("modules/purchase/add.php");
			break;
			case 'edit':
				include("modules/purchase/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>