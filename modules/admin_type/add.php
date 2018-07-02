<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["admin_type_manage"]["add"])){
	extract($_SESSION["admin_type_manage"]["add"]);	
}
else{
	$title="";
	$can_add="";
	$can_edit="";
	$can_delete="";
	$can_read="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Admin Type</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Admin Type</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="admin_type_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="admin_type_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
	<?php
    	$i=0;
  	?>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label no-padding-right">
                <label class="" for="title">Title <span class="red">*</span> </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Title" value="<?php echo $title; ?>" name="title" id="title" class="col-xs-10" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label no-padding-right">
                <label class="" for="can_add">Can Add </label>
            </div>
            <div class="col-sm-10">
                <select name="can_add" id="can_add" title="Choose Option">
                    <option value="">Select Can Add</option>
                    <?php
                    foreach ($admin_types as $key=>$value) {
                        ?>
                        <option value="<?php echo $key?>"<?php echo $key==$can_add?' selected="selected"':""?>><?php echo $value ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label no-padding-right">
                <label class="" for="can_edit">Can Edit </label>
            </div>
            <div class="col-sm-10">
                <select name="can_edit" id="can_edit" title="Choose Option">
                    <option value="">Select Can Edit</option>
                    <?php
                    foreach ($admin_types as $key=>$value) {
                        ?>
                        <option value="<?php echo $key?>"<?php echo $key==$can_edit?' selected="selected"':""?>><?php echo $value ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label no-padding-right">
                <label class="" for="can_delete">Can Delete </label>
            </div>
            <div class="col-sm-10">
                <select name="can_delete" id="can_delete" title="Choose Option">
                    <option value="">Select Can Delete</option>
                    <?php
                    foreach ($admin_types as $key=>$value) {
                        ?>
                        <option value="<?php echo $key?>"<?php echo $key==$can_delete?' selected="selected"':""?>><?php echo $value ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label no-padding-right">
                <label class="" for="can_read">Can Read </label>
            </div>
            <div class="col-sm-10">
                <select name="can_read" id="can_read" title="Choose Option">
                    <option value="">Select Can Read</option>
                    <?php
                    foreach ($admin_types as $key=>$value) {
                        ?>
                        <option value="<?php echo $key?>"<?php echo $key==$can_read?' selected="selected"':""?>><?php echo $value ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="clearfix form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label for="company" class="form-label"></label>
            </div>
            <div class="col-md-10">
                <button class="btn btn-default btn-l" type="submit" name="admin_type_add" title="Submit Record">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Submit
                </button>
            </div>
        </div>
    </div>
</form>