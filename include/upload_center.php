<script type="text/javascript" src="js/jquery.form.js"></script> 
<script type="text/javascript"> 
	$(document).ready(function() { 
    	var options = { 
    		success: function(responseText) { 
				$info=responseText.split("#");
				if($info[0]==1){
					document.getElementById("upload_center_form").reset();
					$rand=Math.floor((Math.random()*100)+1);
					$("#file-list ul").prepend('<li><a href="'+$info[1]+'" target="_blank" class="img_thumb"><img src="'+$info[1]+'" title="'+$info[2]+'" alt="'+$info[2]+'" /></a><span id="uploaded'+$rand+'" title="Insert this Image into Editor">'+$info[2]+'</span></li>');
					$("#uploaded"+$rand).click(function(){
						p_editor_insertHTML('<img src="'+$info[1]+'" title="'+$info[2]+'" alt="'+$info[2]+'" />');
						disablePopup();
					});
				}
				else if($info[0]==0){
					alert($info[1]); 
				}
				else{
					alert(responseText);
				}
    		} 
		};	 
		if($('#upload_center_form').length>0){
	 		$('#upload_center_form').ajaxForm(options); 
		}
   	}); 
</script>
<link href="css/general.css" type="text/css" rel="stylesheet" />
<div id="UploadCenter">
<a id="UploadCenterClose" style="cursor:pointer;"><img src="images/close_bttn.png" alt="X" /></a>
<div id="UploadCenterContent">
	<h1>Upload Center</h1>
    <div id="file-list">
    	<ul>
        	<?php
            $rs=doquery("select * from uploads order by filename",$dblink);
			if(numrows($rs)>0){
				$i=0;
				while($r=dofetch($rs)){
					$image_file_list=array("jpg", "png", "gif", "tiff", "bmp");
					$ext=explode(".", $r["filelocation"]);
					$ext=strtolower($ext[count($ext)-1]);
					$i++;
					?>
					<li <?php if($i%4==0) {?>class="last"<?php }?>>
                    	<?php
                        if(in_array($ext, $image_file_list)){
							?>
							<a href="uploads/upload/<?php echo stripslashes(utf8_decode($r["filelocation"]))?>" target="_blank" class="img_thumb"><img src="uploads/upload/<?php echo stripslashes(utf8_decode($r["filelocation"]))?>" title="<?php echo utf8_decode($r["filename"])?>" alt="<?php echo utf8_decode($r["filename"])?>" /></a>
                            <span onclick="<?php echo "p_editor_insertHTML('<img src=\'".$site_url."/uploads/upload/".stripslashes(utf8_decode($r["filelocation"]))."\' title=\'".utf8_decode($r["filename"])."\' alt=\'".utf8_decode($r["filename"])."\' />')";?>; disablePopup();" title="Insert this Image into Editor"><?php echo utf8_decode($r["filename"])?></span>                            
							<?php
						}
						elseif($ext=="pdf"){
							?>
							<a href="../upload/<?php echo stripslashes(utf8_decode($r["filelocation"]))?>" target="_blank" class="img_thumb"><img src="images/pdf.png" title="<?php echo utf8_decode($r["filename"])?>" alt="<?php echo utf8_decode($r["filename"])?>" /></a>
                            <span onclick="<?php echo "p_editor_insertHTML('<a href=\'".$site_url."/upload/".stripslashes(utf8_decode($r["filelocation"]))."\'>".utf8_decode($r["filename"])."</a>')";?>; disablePopup();" title="Insert this PDF into Editor"><?php echo utf8_decode($r["filename"])?></span>                            
							<?php
						}
						else{
							?>
							<a href="../upload/<?php echo stripslashes(utf8_decode($r["filelocation"]))?>" target="_blank" class="img_thumb"><img src="images/file.png" title="<?php echo utf8_decode($r["filename"])?>" alt="<?php echo utf8_decode($r["filename"])?>" /></a>
                            <span onclick="<?php echo "p_editor_insertHTML('<a href=\'".$site_url."/upload/".stripslashes(utf8_decode($r["filelocation"]))."\'>".utf8_decode($r["filename"])."</a>')";?>; disablePopup();" title="Insert this File into Editor"><?php echo utf8_decode($r["filename"])?></span>                           
							<?php
						}
						?>
                    </li>
					<?php
					if($i%4==0){
						?>
						<div class="clr"></div>
						<?php
					}
				}
			}
			?>
        	<li></li>
        </ul>
    </div>
    <div>
    	<form action="upload_center_do.php" method="post" enctype="multipart/form-data" id="upload_center_form">
        	<strong>Add New: </strong>
            Title: <input type="text" name="title" id="title" value="" />&nbsp;File: <input type="file" name="file" id="file" />&nbsp;
            <input type="submit" name="file_submit" value="Submit" /><?php if(isset($err)) echo $err;?>
        </form>
    </div>
</div>
</div>
<div id="backgroundPopup"></div>