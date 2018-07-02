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
		include("modules/demand/add_do.php");
	break;
	case 'edit':
		include("modules/demand/edit_do.php");
	break;
	case 'delete':
		include("modules/demand/delete_do.php");
	break;
	case 'bulk_action':
		include("modules/demand/bulkactions.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/demand/list.php");
			break;
			case 'add':
				include("modules/demand/add.php");
			break;
			case 'edit':
				include("modules/demand/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<style>

.col-head strong {
    background-color: #ddd;
    display: block;
    padding: 10px;
}
.col-head > div {
    margin-bottom: 10px;
}

</style>
<?php include("include/footer.php");?>