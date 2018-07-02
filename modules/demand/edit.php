<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Update Configuration Type</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Configuration Type</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="demand_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="demand_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  class="form-horizontal form-horizontal-left">
	<input type="hidden" name="id" value="<?php echo $id;?>">
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
						$stock_check = doquery("select * from demand_item where demand_id ='".$id."' and items_id='".$r["id"]."'", $dblink);
						if( numrows( $stock_check ) > 0 ){
							$stock_check = dofetch( $stock_check );
							$stock_value = unslash($stock_check[ "stock" ]);
							$demand_value = unslash($stock_check[ "demand" ]);
						}
						else{
							$stock_value = 0;
							$demand_value = 0;
						}
						if( isset($stock["item_".$r["id"]]) ){
							$stock_value = $stock["item_".$r["id"]];
						}
						if( isset($demand["item_".$r["id"]]) ){
							$demand_value = $demand["item_".$r["id"]];
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
                <input type="submit" value="UPDATE" class="btn btn-default btn-l" name="Submit" title="Update Record" />
            </div>
        </div>
  	</div>
</form>