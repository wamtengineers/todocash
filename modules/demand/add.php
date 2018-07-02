<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["demand_manage"]["add"])){
	extract($_SESSION["demand_manage"]["add"]);	
}
else{
	$date=date("d/m/Y");
}
?>
<div class="page-header">
	<h1 class="title">Add New Demand</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Demand</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="demand_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="demand_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
	<?php
    	$i=0;
  	?>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="title">Date <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" name="date" id="date" value="<?php echo $date; ?>" title="Enter Date" class="form-control date-picker" maxlength="200" />
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" title="Select Order">Items</label>
            </div>
            <div class="col-sm-10 col-head">
                <div class="col-md-4"><strong>Items Name</strong></div>
                <div class="col-md-1"><strong>Stock</strong></div>
                <div class="col-md-1"><strong>Demand</strong></div>
                <div class="col-md-4"><strong>Items Name</strong></div>
                <div class="col-md-1"><strong>Stock</strong></div>
                <div class="col-md-1"><strong>Demand</strong></div>
                <?php
                $rs = doquery( "select * from items where type=0 order by sortorder", $dblink );
                if( numrows( $rs ) > 0 ) {
                    while( $r = dofetch( $rs ) ) {
						if( isset($stock["item_".$r["id"]]) ){
							$stock_value = $stock["item_".$r["id"]];
						}
						else {
							$stock_value = 0;
						}
						if( isset($demand["item_".$r["id"]]) ){
							$demand_value = $demand["item_".$r["id"]];
						}
						else {
							$demand_value = 0;
						}
                        ?>
                        <div class="col-md-4"><?php echo unslash( $r[ "title" ] )?></div>
                        <div class="col-md-1"><input type="text" name="stock[item_<?php echo $r[ "id" ]?>]" id="stock_<?php echo $r["id"]?>" value="<?php echo $stock_value; ?>" class="form-control" /></div>
                        <div class="col-md-1"><input type="text" name="demand[item_<?php echo $r[ "id" ]?>]" id="demand_<?php echo $r["id"]?>" value="<?php echo $demand_value; ?>" class="form-control" /></div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="Submit" title="Submit Record" />
            </div>
        </div>
  	</div>
</form>