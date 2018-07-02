<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["sales_manage"]["add"])){
	extract($_SESSION["sales_manage"]["add"]);	
}
else{
	$customer_id="";
	$customer_name="";
	$phone="";
	$address="";
	$date=date("d/m/Y H:i A");
	$items=array();
	$discount = 0;
}
?>
<div class="page-header">
	<h1 class="title">Add New Sale</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Sales</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="sales_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="sales_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
	<?php
    	$i=0;
  	?>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="date">Date <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Date" value="<?php echo $date; ?>" name="date" id="date" class="form-control date-timepicker" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="supplier_name">Customer Name <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="customer_id" id="customer_id" class="margin-btm-5">
                	<option value="">Select Customer</option>
                    <?php
                    $rs = doquery( "select * from customer where status=1 order by id", $dblink );
					if( numrows( $rs ) > 0 ) {
						while( $r = dofetch( $rs ) ) {
							?>
							<option value="<?php echo $r[ "id" ]?>" data-customer_name="<?php echo htmlspecialchars(unslash($r[ "customer_name" ]))?>" data-phone="<?php echo htmlspecialchars(unslash($r[ "phone" ]))?>" data-address="<?php echo htmlspecialchars(unslash($r[ "address" ]))?>"><?php echo $r[ "id" ]?> - <?php echo unslash($r[ "customer_name" ])?></option>
							<?php
						}
					}
					?>
                </select>
                <input type="text" title="Enter Name" value="<?php echo $customer_name; ?>" name="customer_name" id="customer_name" class="form-control" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="phone">Phone </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Contact Number" value="<?php echo $phone; ?>" name="phone" id="phone" class="form-control" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="address">Address </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Address" value="<?php echo $address; ?>" name="address" id="address" class="form-control" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label">Items <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <div class="panel-body table-responsive">
                    <table class="table table-hover list">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">S.no</th>
                                <th width="45%">Item</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Total Items</th>
                                <th class="text-right">Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sn=1;
                            if(count($items)>0){
                                foreach($items as $item){
                                    ?>
                                    <tr class="sale_item">
                                        <td class="text-center serial_number"><?php echo $sn;?></td>
                                        <td>
                                           <select name="items[]" class="item_select"  title="Choose Option" >
                                               <option value="0">Select Item Category</option>
                                               <?php
                                                $res=doquery("Select * from item_category where parent_id <> '0' order by title",$dblink);
                                                if(numrows($res)>0){
                                                    while($rec=dofetch($res)){ 
                                                        ?>
                                                        <optgroup label="<?php echo unslash($rec["title"]); ?>">
                                                        <?php
                                                        $subCat=doquery("Select * from items where item_category_id='".$rec["id"]."'",$dblink);
                                                        if(numrows($subCat)>0){
                                                            while($subCatName=dofetch($subCat)){
                                                            ?>
                                                            <option value="<?php echo $subCatName["id"]?>"><?php echo unslash($subCatName["title"]); ?></option><?php 
                                                            }
                                                        }?> 
                                                        </optgroup>
                                                        <?php			
                                                    }			
                                                }
                                                ?>
                                            </select>    
                                        </td>
                                        <td class="text-right"><input type="text" class="unit_price" id="unit_price<?php echo $sn?>" name="unit_price[]" value="" /></td>
                                        <td class="text-right"><input type="text" class="quantity" name="quantity[]" id="quantity<?php echo $sn?>" value="<?php echo $quantity[$sn-1]?>" /></td>
                                        <td class="text-right"><input type="text" class="total_price"  id="total_price<?php echo $sn?>" value="" /></td>                        
                                        <td class="text-center">
                                        	<a href="#" data-id="<?php echo $sn?>" class="add_list_item" data-container_class="sale_item">Add</a> - 
                                            <a href="#" data-id="<?php echo $sn?>" class="delete_list_item" data-container_class="sale_item">Delete</a>
                                        </td>
                                    </tr>
                                    <?php 
                                    $sn++;
                                }
                            }
                            else{
                            ?>
                            <tr class="sale_item">
                                <td class="text-center serial_number"><?php echo $sn;?></td>
                                <td>
                                    <select name="items[]" class="item_select"  title="Choose Option" >
                                        <option value="0">Select Item Category</option>
                                        <?php
                                        $res=doquery("Select * from item_category where parent_id <> '0' order by title",$dblink);
                                        if(numrows($res)>0){
                                            while($rec=dofetch($res)){ 
                                                ?>
                                                <optgroup label="<?php echo unslash($rec["title"]); ?>">
                                                    <?php
                                                    $subCat=doquery("Select * from items where item_category_id='".$rec["id"]."'",$dblink);
                                                    if(numrows($subCat)>0){
                                                        while($subCatName=dofetch($subCat)){
                                                        ?>
                                                        <option value="<?php echo $subCatName["id"]?>"><?php echo unslash($subCatName["title"]); ?></option>
                                                        <?php 
                                                        }
                                                    }
                                                    ?> 
                                                </optgroup>
                                                <?php			
                                            }			
                                         }
                                         ?>
                                    </select>
                                </td>
                                <td class="text-right"><input type="text" class="unit_price" name="unit_price[]" id="unit_price<?php echo $sn?>"  value="" /></td>
                                <td class="text-right"><input type="text" class="quantity" name="quantity[]" id="quantity<?php echo $sn?>" value="1" /></td>
                                <td class="text-right"><input type="text" class="total_price" id="total_price<?php echo $sn?>" value="" /></td>                        
                                <td class="text-center">
                                	<a href="#" data-id="<?php echo $sn?>" class="add_list_item" data-container_class="sale_item">Add</a> - 
                                    <a href="#" class="delete_list_item" data-container_class="sale_item">Delete</a>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <th colspan="4" class="text-right">Total Items</th>
                                <th class="text-right grand_total_item"></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Discount</th>
                                <th class="text-right"><input type="number" class="discount" name="discount" id="discount" value="<?php echo $discount?>" style="text-align:right" data-container_class="sale_item" /></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Total Price</th>
                                <th class="text-right grand_total_price"></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    	</div>
    </div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="sales_add" title="Submit Record" />
            </div>
        </div>
  	</div>
</form>