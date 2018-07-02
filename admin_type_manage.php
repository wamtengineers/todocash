<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'admin_type_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "send_email");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/admin_type/add_do.php");
	break;
	case 'edit':
		include("modules/admin_type/edit_do.php");
	break;
	case 'delete':
		include("modules/admin_type/delete_do.php");
	break;
	case 'status':
		include("modules/admin_type/status_do.php");
	break;
	case 'bulk_action':
		include("modules/admin_type/bulkactions.php");
	break;
}
?>
<?php include("include/header.php");?>
		<div class="container-widget row">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/admin_type/list.php");
                break;
                case 'add':
                    include("modules/admin_type/add.php");
                break;
                case 'edit':
                    include("modules/admin_type/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>