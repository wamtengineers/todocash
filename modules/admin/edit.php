<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Edit Admin</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Admin</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="admin_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="admin_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  class="form-horizontal form-horizontal-left">
	<input type="hidden" name="id" value="<?php echo $id;?>">
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="admin_type_id">Admin Type <span class="red">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="admin_type_id" title="Choose Option">
                    <option value="0">Select Admin Type</option>
                    <?php
                    $res=doquery("Select * from admin_type order by title",$dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($admin_type_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
            	<label class="form-label" for="name">Name <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Name" value="<?php echo $name; ?>" name="name" id="name" class="form-control" >
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="username">Username <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" value="<?php echo $username; ?>" name="username" id="username" class="form-control" title="Enter User Name">
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="email">Email <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="email" title="Enter Email" value="<?php echo $email; ?>" name="email" id="email" class="form-control">
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="password">Password <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="password" title="Enter Password" value="" placeholder="Password" name="password" id="password" class="form-control">
                <span id="helpBlock" class="help-block">Leave empty for no change.</span> 
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="UPDATE" class="btn btn-default btn-l" name="admin_edit" title="Update Record" />
            </div>
        </div>
  	</div>
</form>