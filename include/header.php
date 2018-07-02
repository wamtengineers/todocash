<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="images/favicon.png" />
<title><?php echo $site_title?> - Admin Panel</title>
<link type="text/css" rel="stylesheet" href="css/font-awesome.min.css" />
<link type="text/css" rel="stylesheet" href="css/font-awesome.css" />
<link type="text/css" rel="stylesheet"  href="css/bootstrap.css" />
<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
<link type="text/css" rel="stylesheet"  href="css/awesome-bootstrap-checkbox.css" />
<link href="css/general.css" type="text/css" rel="stylesheet" />
<link type="text/css" rel="stylesheet"  href="css/style.css" />
<script type="text/javascript" src="js/jquery.js"></script> 
<script type="text/javascript" src='js/tinymce/tinymce.js'></script>
<?php include("js/initialize.php");?>
<script type="text/javascript" src="js/popup.js"></script>
</head>
<body>
	<div id="wrapper" class="round_corners">		
    	<div id="top" class="clearfix">
            <div class="applogo">
                <a href="index.php" class="logo"><?php $admin_logo=get_config("admin_logo"); if(empty($admin_logo)) echo $site_title; else { ?><img src="<?php echo $file_upload_root;?>config/<?php echo $admin_logo?>" /><?php }?></a>
            </div>
            <a class="sidebar-open-button" href="#"><i class="fa fa-bars"></i></a>
            <ul class="top-right">
            	<li class="dropdown link">
                	<a class="dropdown-toggle profilebox" data-toggle="dropdown" href="#">
                    	<img alt="img" src="images/profileimg-default.png">
                        <b><?php echo ucfirst((isset($_SESSION["logged_in_admin"]) && $_SESSION["logged_in_admin"]!="")?$_SESSION["logged_in_admin"]["name"]:"Guest");?></b>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-list dropdown-menu-right">
                    	<li class="dropdown-header" role="presentation">Profile</li>
                        <li><a href="admin_manage.php?tab=edit&id=<?php echo $_SESSION["logged_in_admin"]["id"]?>"><i class="fa falist fa-file-o"></i>Edit Profile</a></li>
                        <li><a href="config_manage.php"><i class="fa falist fa-wrench"></i>Settings</a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa falist fa-power-off"></i> Logout</a></li>
                    </ul>
                </li>
           </ul>
           <div class="clr"></div>
        </div>
        <div class="sidebar clearfix">
       		<ul class="sidebar-panel nav">
  				<li class="sidetitle">MAIN</li>
				<?php
					$parents=doquery("select * from menu a inner join menu_2_admin_type b on a.id = b.menu_id where parent_id=0 and admin_type_id='".$_SESSION["logged_in_admin"]["admin_type_id"]."' order by sortorder",$dblink);
					if(numrows($parents)>0){
						while($parent=dofetch($parents)){
						?>
						<li>
                        	<a href="<?php echo unslash($parent["url"])?>"><span class="icon color5"><i class="fa fa-<?php echo unslash($parent["small_icon"])?>"></i></span><?php echo unslash($parent["title"])?>
							<?php
								$submenus=doquery("select * from menu a inner join menu_2_admin_type b on a.id = b.menu_id where parent_id='".$parent["id"]."' and admin_type_id='".$_SESSION["logged_in_admin"]["admin_type_id"]."' order by sortorder",$dblink);
								if(numrows($submenus)>0){
									?>
                                <span class="caret"></span></a>
                                <ul>
                                	<?php
                                    while($submenu=dofetch($submenus)){
										?>
										<li><a href="<?php echo unslash($submenu["url"])?>"><span class="icon color5"><i class="fa fa-<?php echo unslash($submenu["small_icon"])?>"></i></span><?php echo unslash($submenu["title"])?></a></li>
										<?php
									}
									?>
                                </ul>
                                <?php
                            }
							else{
								echo "</a>";
							}
							?>
						</li>	
						<?php
					}
				}
				?>
         	</ul>
            <ul class="sidebar-panel nav">
                <li class="sidetitle">Account</li>
                <li><a href="admin_manage.php?tab=edit&id=<?php echo $_SESSION["logged_in_admin"]["id"]?>"><span class="icon color15"><i class="fa fa-columns"></i></span>Edit Profile</a></li>
                <li><a href="logout.php"><span class="icon color12"><i class="fa falist fa-power-off"></i></span>Logout</a></li>
            </ul>
	    </div>
		<div class="content">
            <div class="page-header page-header-hidden" style="padding:0px;">
           		<?php
                if(isset($_REQUEST["msg"])){
                	?>
                	<div align="center" class="msg"><?php echo url_decode($_REQUEST["msg"]);?></div>	
                	<?php
                }
            	if(isset($_REQUEST["err"])){
            		?>
            		<div align="center" class="err"><?php echo url_decode($_REQUEST["err"])?></div>	
            		<?php
                }
            	?>
            </div>