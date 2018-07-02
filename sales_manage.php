<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'sales_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "get_unit_price","get_quantity", "print","report");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
switch($tab){
	case 'add':
		include("modules/sales/add_do.php");
	break;
	case 'edit':
		include("modules/sales/edit_do.php");
	break;
	case 'delete':
		include("modules/sales/delete_do.php");
	break;
	case 'status':
		include("modules/sales/status_do.php");
	break;
	case 'bulk_action':
		include("modules/sales/bulkactions.php");
	break;
	case "get_unit_price":
		if(isset($_GET["id"])){
			if(isset($_GET['transcationid']) && $_GET['transcationid'] > 0){
				$getSaleItemPrice=doquery("select unit_price from sales_items where item_id='".slash($_GET["id"])."'", $dblink);
				if(numrows($getSaleItemPrice) > 0){
					$salItemsPrice=dofetch($getSaleItemPrice);	
					echo $salItemsPrice['unit_price'];
				}
				else{	
					$r=dofetch(doquery("select unit_price from items where id='".slash($_GET["id"])."'", $dblink));
					echo $r["unit_price"];
				}
			}
			else{
			  $r=dofetch(doquery("select unit_price from items where id='".slash($_GET["id"])."'", $dblink));
				echo $r["unit_price"];
			}
		}
		die;
	break;
	case "get_quantity":
		if(isset($_GET["id"])){
			if(isset($_GET['transcationid']) && $_GET['transcationid'] > 0){
				$getSaleitemQty=doquery("select quantity from sales_items where item_id='".slash($_GET["id"])."'", $dblink);
				if(numrows($getSaleitemQty) > 0){
					$SaleItemQty=dofetch($getSaleitemQty);
					echo $SaleItemQty['quantity'];
				}
				else{
					$r=dofetch(doquery("select quantity from items where id='".slash($_GET["id"])."'", $dblink));
					echo $r["quantity"];	
				}
			}
			else{
			   $r=dofetch(doquery("select quantity from items where id='".slash($_GET["id"])."'", $dblink));
				echo $r["quantity"];
			}
			
		}
		die;
	break;
	case "print":
		include("modules/sales/print.php");
	break;
	case 'report':
		include("modules/sales/report.php");
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
				include("modules/sales/list.php");
			break;
			case 'add':
				include("modules/sales/add.php");
			break;
			case 'edit':
				include("modules/sales/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php if( isset( $_GET[ "print" ]) ){
	?>
	<iframe style="display:none" src="sales_manage.php?tab=print&id=<?php echo $_GET[ "print" ]?>"></iframe>
	<?php
}?> 
<?php include("include/footer.php");?>