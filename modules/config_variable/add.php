<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["config_variable_manage"]["add"])){
	extract($_SESSION["config_variable_manage"]["add"]);	
}
else{
	$title="";
	$config_type_id=0;
	$notes="";
	$type="";
	$default_values="";
	$key="";
	$value="";
	$sortorder=get_new_sort_order("config_variable");
}
?>
<div class="page-header">
	<h1 class="title">Add New Configuration Variable</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Configuration Variables</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="config_variable_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="config_variable_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
	<?php
    	$i=0;
  	?>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="config_type_id">Configuration Type <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="config_type_id" id="config_type_id" title="Choose Option">
                    <option value="0">Select Configuration Type</option>
                    <?php
                        $res=doquery("select * from config_type order by sortorder",$dblink);
                        if(numrows($res)>0){
                            while($rec=dofetch($res)){
                                ?>
                                <option value="<?php echo $rec["id"]?>"<?php echo($config_type_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
            	<label class="form-label" for="title">Title <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" name="title" id="title" value="<?php echo $title; ?>" title="Enter Title" class="form-control" maxlength="200" />
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="notes">Notes</label>
            </div>
            <div class="col-sm-10">
                <input type="text" name="notes" id="notes" title="Note" value="<?php echo $notes; ?>" class="form-control" maxlength="200" />
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="type">Type <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="type" title="Choose Type">
                    <option value="null">Select Type</option>
                    <?php
                    $type_arr=array("text","checkbox","radio","textarea","editor","file","combobox");
                    $i=0;
                    while($i<count($type_arr)){
                        ?>
                        <option value="<?php echo $type_arr[$i];?>"<?php if($type==$type_arr[$i]) echo ' selected="selected"';?>><?php echo $type_arr[$i];?></option>
                        <?php
                        $i++;
                    }
                    ?>
                </select>
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="default_values">Default Values (seprated by semi-colon ';')</label>
            </div>
            <div class="col-sm-10">
                <input type="text" name="default_values" id="default_values" title="Enter Default Value: Seprated by ; semi-colon" value="<?php echo $default_values; ?>" class="form-control" maxlength="1000" />
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="key">Key</label>
            </div>
            <div class="col-sm-10">
                <input type="text" name="key" id="key" title="Enter Key" value="<?php echo $key; ?>" class="form-control" maxlength="50" />
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="value">Value</label>
            </div>
            <div class="col-sm-10">
                <input type="text" name="value" id="value" title="Enter Value" value="<?php echo $value; ?>" class="form-control" maxlength="1000" />
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" title="Select Order">Sort Order</label>
            </div>
            <div class="col-sm-10">
                <?php getSortCombo("config_variable",$sortorder,"add");?>
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="config_variable_manage" title="Submit Record" />
            </div>
        </div>
  	</div>
</form>