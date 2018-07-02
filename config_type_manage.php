<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "edit", "delete", "bulk_action");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/config_type/add_do.php");
	break;
	case 'edit':
		include("modules/config_type/edit_do.php");
	break;
	case 'delete':
		include("modules/config_type/delete_do.php");
	break;
	case 'bulk_action':
		include("modules/config_type/bulkactions.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/config_type/list.php");
			break;
			case 'add':
				include("modules/config_type/add.php");
			break;
			case 'edit':
				include("modules/config_type/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>