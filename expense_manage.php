<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'expense_manage.php';
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
		include("modules/expense/add_do.php");
	break;
	case 'edit':
		include("modules/expense/edit_do.php");
	break;
	case 'delete':
		include("modules/expense/delete_do.php");
	break;
	case 'status':
		include("modules/expense/status_do.php");
	break;
	case 'bulk_action':
		include("modules/expense/bulkactions.php");
	break;
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/expense/list.php");
                break;
                case 'add':
                    include("modules/expense/add.php");
                break;
                case 'edit':
                    include("modules/expense/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>