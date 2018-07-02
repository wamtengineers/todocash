<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["items_manage"]["add"])){
	extract($_SESSION["items_manage"]["add"]);	
}
else{
	$item_category_id="";
	$type="";
	$title="";
	$unit_price="";
	$quantity="";
	$sortorder="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Item</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Item</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="items_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="items_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
	<?php
    	$i=0;
  	?>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="item_category_id">Category</label>
            </div>
            <div class="col-sm-10">
            	<select name="item_category_id" id="item_category_id" title="Choose Option">
                <option value="0">Select Item Category</option>
				 <?php
                  $res=doquery("Select * from item_category where parent_id='0' order by title",$dblink);
                   if(numrows($res)>0){
					   while($rec=dofetch($res)){ ?>
                       <optgroup label="<?php echo unslash($rec["title"]); ?>">
                        <?php
                        $subCat=doquery("Select * from item_category where parent_id='".$rec["id"]."'",$dblink);
						if(numrows($subCat)>0){
							 while($subCatName=dofetch($subCat)){
								 ?>
                                 <option value="<?php echo $subCatName["id"]?>"<?php echo($item_category_id==$subCatName["id"])?"selected":"";?>><?php echo unslash($subCatName["title"]); ?></option>
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
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="type">Product Type</label>
            </div>
            <div class="col-sm-10">
            	<select name="type" id="type" title="Choose Option">
                    <option value="">Select Product Type</option>
                    <?php
                    foreach ($product_type as $key=>$value) {
                        ?>
                        <option value="<?php echo $key?>"<?php echo $key==$type?' selected="selected"':""?>><?php echo $value ?></option>
                        <?php
                    }
                    ?>
                </select>
                
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="title">Title <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Name" value="<?php echo $title; ?>" name="title" id="title" class="form-control" >
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="unit_price">Unit Price </label>
            </div>
            <div class="col-sm-10">
                <input type="text" value="<?php echo $unit_price; ?>" name="unit_price" id="unit_price" class="form-control" title="Enter Unit Price">
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="quantity">Quantity </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Quantity" value="<?php echo $quantity; ?>" name="quantity" id="quantity" class="form-control">
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="Image">Image <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="file" title="Enter Name" name="image" id="image" class="form-control">
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="sortorder">Sortorder</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Sortorder" value="<?php echo $sortorder; ?>" name="sortorder" id="sortorder" class="form-control" >
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="items_add" title="Submit Record" />
            </div>
        </div>
  	</div>
</form>