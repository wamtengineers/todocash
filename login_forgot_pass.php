<?php 
include("include/db.php");
include("include/utility.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="login.ico" />
<link type="text/css" rel="stylesheet"  href="css/font-awesome.min.css" />
<link type="text/css" rel="stylesheet"  href="css/font-awesome.css" />
<link type="text/css" rel="stylesheet"  href="css/bootstrap.css" />
<link type="text/css" rel="stylesheet"  href="css/style.css" />
<title>Forgot Password - <?php echo $site_title?></title>
<style type="text/css">
	body{background: #F5F5F5;}
</style>
</head>
<body>
	<div id="content">
        	<div class="login-form">
      		<form name="loginfrm" action="login.php" method="post">
        		<div class="top">
          			<a href="index.php" class=""><img src="<?php echo $file_upload_root;?>config/<?php echo get_config("login_logo");?>" /></a>
          			<h4>Please Login to continue.</h4>
        		</div>
        		<div class="form-area">
                    <div class="group">
                        <input type="text" class="form-control" placeholder="Username" name="username">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="group">
                        <input type="text" class="form-control" placeholder="E-mail" name="email">
            			<i class="fa fa-envelope-o"></i>
                    </div>
                    
                    <button type="submit" class="btn btn-default btn-block" name="login_submit">RESET PASSWORD</button>
            	</div>
          	</form>
      		<div class="footer-links row">
        		<div class="col-xs-6"><a href="login.php"><i class="fa fa-sign-in"></i> Login</a></div>
      		</div>
    	</div>
   		</div>
   		<div class="clr"></div>
	</div>
</body>
</html>