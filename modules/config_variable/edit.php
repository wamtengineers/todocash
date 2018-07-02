<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Update Configuration Variable</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Configuration Variables</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="config_variable_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="config_variable_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  class="form-horizontal form-horizontal-left">
	<input type="hidden" name="id" value="<?php echo $id;?>">
  	<div class="form-group">
    	<label class="col-sm-2 control-label form-label" for="config_type_id">Configuration Type <span class="manadatory">*</span></label>
    	<div class="col-sm-10">
      		<select name="config_type_id" title="Choose Option">
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
  	<div class="form-group">
    	<label class="col-sm-2 control-label form-label" for="title">Title <span class="manadatory">*</span></label>
    	<div class="col-sm-10">
      		<input type="text" name="title" id="title" value="<?php echo $title; ?>" title="Enter Title" class="form-control" maxlength="200" />
    	</div>
  	</div>
  	<div class="form-group">
    	<label class="col-sm-2 control-label form-label" for="notes">Notes</label>
    	<div class="col-sm-10">
      		<input type="text" name="notes" id="notes" title="Note" value="<?php echo $notes; ?>" class="form-control" maxlength="200" />
    	</div>
  	</div>
  	<div class="form-group">
    	<label class="col-sm-2 control-label form-label" for="type">Type <span class="manadatory">*</span></label>
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
    <div class="form-group">
    	<label class="col-sm-2 control-label form-label" for="default_values">Default Values (seprated by semi-colon ';')</label>
    	<div class="col-sm-10">
      		<input type="text" name="default_values" id="default_values" title="Enter Default Value: Seprated by ; semi-colon" value="<?php echo $default_values; ?>" class="form-control" maxlength="1000" />
    	</div>
  	</div>
    <div class="form-group">
    	<label class="col-sm-2 control-label form-label" for="key">Key</label>
    	<div class="col-sm-10">
      		<input type="text" name="key" id="key" title="Enter Key" value="<?php echo $key; ?>" class="form-control" maxlength="50" />
    	</div>
  	</div>
    <div class="form-group">
    	<label class="col-sm-2 control-label form-label" for="value">Value</label>
    	<div class="col-sm-10">
      		<input type="text" name="value" id="value" title="Enter Value" value="<?php echo $value; ?>" class="form-control" maxlength="1000" />
    	</div>
  	</div>
    <div class="form-group">
    	<label class="col-sm-2 control-label form-label" title="Select Order">Sort Order</label>
    	<div class="col-sm-10">
      		<?php getSortCombo("config_variable",$sortorder,"edit");?>
    	</div>
  	</div>
  	<div class="form-group">
    	<label for="company" class="col-sm-2 control-label form-label"></label>
    	<div class="col-sm-10">
     		<input type="submit" value="UPDATE" class="btn btn-default btn-l" name="Submit" title="Update Record" />
    	</div>
  	</div>
</form>