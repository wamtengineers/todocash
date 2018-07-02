<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Edit Item Category</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Item Category</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="item_category_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="item_category_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  class="form-horizontal form-horizontal-left">
	<input type="hidden" name="id" value="<?php echo $id;?>">
    <div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="parent_id">Parent</label>
            </div>
            <div class="col-sm-10">
                <select name="parent_id" title="Choose Option">
                    <option value="0">NO Parent</option>
                    <?php
                        $res=doquery("Select * from item_category where parent_id=0 order by title",$dblink);
                        if(numrows($res)>0){
                            while($rec=dofetch($res)){
                    		?>
                            <option value="<?php echo $rec["id"]?>"<?php echo($parent_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
                <label class="form-label" for="item_category_name">Title <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Title" value="<?php echo $title; ?>" name="title" id="title" class="form-control" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="sortorder">Sortorder </label>
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
                <input type="submit" value="UPDATE" class="btn btn-default btn-l" name="item_category_edit" title="Update Record" />
            </div>
        </div>
  	</div>
</form>