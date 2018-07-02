<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["filename"])){
	extract($_SESSION);
}
else{
	$filename="";
	$filelocation="";
}
?>
<div class="page-header">
	<h1 class="title">Upload New File</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Upload</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="upload_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="upload_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
	<?php
    	$i=0;
  	?>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="filename">File Name <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter File Name" value="<?php echo $filename; ?>" name="filename" id="filename" class="form-control" >
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="filelocation">Upload New File <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="file" name="filelocation" id="filelocation"  title="Upload New File" class="form-control" size="30" />
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="UPLOAD FILE" class="btn btn-default btn-l" name="Submit" title="Upload File" />
            </div>
        </div>
  	</div>
</form>