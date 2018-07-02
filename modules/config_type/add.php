<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["config_type_manage"]["add"])){
	extract($_SESSION["config_type_manage"]["add"]);	
}
else{
	$title="";
	$sortorder=get_new_sort_order("config_type");
}
?>
<div class="page-header">
	<h1 class="title">Add New Configuration Type</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Configuration Type</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="config_type_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="config_type_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
	<?php
    	$i=0;
  	?>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="title">Title <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" name="title" id="title" value="<?php echo $title; ?>" title="Enter Config Title" class="form-control" maxlength="200" />
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" title="Select Order">Sort Order</label>
            </div>
            <div class="col-sm-10">
                <?php getSortCombo("config_type",$sortorder,"add");?>
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