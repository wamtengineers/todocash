<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
$is_search=false;
if(isset($_GET["config_id"]))
	$config_id=slash($_GET["config_id"]);
else
	$config_id=0;
?>
<div class="page-header">
	<h1 class="title">Configuration</h1>
  	<ol class="breadcrumb">
    	<li class="active">Site Settings.</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="index.php" class="btn btn-light editproject">Home</a> 
        </div>
  	</div>
</div>
<form name="search_form" action="" method="get" class="config-item">
<span style="color:#FFFFFF">Show Items: </span>
    <select onchange="window.location.href='config_manage.php?config_id='+this.value">
		<option value="0" <?php if($config_id==0) echo "selected";?>>All Category</option>
		<?php
        $res=doquery("Select id, title from config_type order by sortorder",$dblink);
        if(numrows($res)>=0){
            while($rec=dofetch($res)){
                ?>
                <option value="<?php echo $rec["id"];?>" <?php echo ($config_id==$rec["id"])?"selected='selected'":"";?>><?php echo unslash($rec["title"])?></option>
                <?php
            
            }
        }	
        1?>
	</select>
</form>
<form action="config_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
	<input type="hidden" value="<?php echo $_GET["config_id"]; ?>" name="config_id" />
  	<div class="list config-form">
		<?php
        if($config_id!=0)
            $extra=" and id='".$config_id."'";
        else
            $extra="";
        $res=doquery("Select * from config_type where 1 $extra order by sortorder ASC",$dblink);
        if(numrows($res)>0){
            $sn=0;
            while($rec=dofetch($res)){
        	?>
    		<div style="text-align:left; font-weight:bold; margin-bottom:40px;"><?php echo htmlentities(unslash($rec["title"]));?></div>
   			<?php
			$res1=doquery("Select * from config_variable where config_type_id='".addslashes($rec["id"])."' order by id ",$dblink);
			if(numrows($res1)>0){
				while($rec1=dofetch($res1)){
					$rec1["value"]=unslash($rec1["value"]);
					?>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-2 control-label">
								<label class="form-label" for="title"><?php echo $rec1["title"]?></label>
							</div>
							<div class="col-sm-10">
							<?php
								switch($rec1["type"]){
									case "text":
									case "submit":
									case "file":
									case "textarea":
									case "button":
										getInputBox($rec1["type"],$rec1["value"],$rec1["id"],"","");
									break;
									case "radio":
									case "checkbox":
									case "combobox":
										getInputBox($rec1["type"],"",$rec1["id"],"",$rec1["default_values"]);
									break;
									case "editor":
										getInputBox($rec1["type"],$rec1["value"],$rec1["id"],"mceEditor","");
									break;
								}
							?>
							</div>
						</div>
					</div>
        			<?php
					$sn++;
				}
				?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-2 control-label">
                            <label for="company" class="form-label"></label>
                        </div>
                        <div class="col-sm-10">
                            <input type="submit" value="UPDATE" class="btn btn-default btn-l" name="config_edit" title="Update Record" />
                        </div>
                    </div>
                </div>
				<?php
			}
			else{
			?>
            <div class="err">No Variables  Found</div>
        	<?php
			}
		}
	}
	?>
	</div>
</form>
