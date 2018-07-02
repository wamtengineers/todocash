<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Edit Item Group</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Item Group</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="item_group_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="item_group_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  class="form-horizontal form-horizontal-left">
	<input type="hidden" name="id" value="<?php echo $id;?>">
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="item_id">Items <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="item_id" id="item_id" class="margin-btm-5">
                    <option value="">Select Items</option>
                    <?php
                    $res=doquery("Select * from items where type=0 order by title",$dblink);
					if(numrows($res)>0){
						while($rec=dofetch($res)){
							?>
                            <option value="<?php echo $rec["id"]?>"<?php echo($item_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="UPDATE" class="btn btn-default btn-l" name="item_group_edit" title="Update Record" />
            </div>
        </div>
  	</div>
</form>