<?php 
include("include/db.php");
include("include/utility.php");
include("modules/login/login.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="login.ico" />
<link type="text/css" rel="stylesheet"  href="css/font-awesome.min.css" />
<link type="text/css" rel="stylesheet"  href="css/font-awesome.css" />
<link type="text/css" rel="stylesheet"  href="css/bootstrap.css" />
<link type="text/css" rel="stylesheet"  href="css/awesome-bootstrap-checkbox.css" />
<link type="text/css" rel="stylesheet"  href="css/style.css" />
<title>Login Admin Panel</title>
<style type="text/css">
	body{background: #F5F5F5;}
</style>
</head>
<body>
	<div id="content">
        	<div class="login-form">
                <form name="loginfrm" method="post">
                    <div class="top">
                        <h1><img src="<?php echo $file_upload_root;?>config/<?php echo get_config("login_logo");?>" /></h1>
                        <h4>Please Login to continue.</h4>
                    </div>
                    <?php
                    if(isset($_SESSION["login"]["err"])){
                        ?>
                        <div class="err"><?php echo $_SESSION["login"]["err"];?></div>
                        <?php
                        
                    }
                    ?>
                    <div class="form-area">
                        <div class="group">
                            <input type="text" class="form-control" placeholder="Username" name="username" value="<?php if(isset($_SESSION["login"]["username"])) echo $_SESSION["login"]["username"]?>">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="group">
                            <input type="password" class="form-control" placeholder="Password" name="password" value="<?php if(isset($_SESSION["login"]["password"])) echo $_SESSION["login"]["password"]?>">
                            <i class="fa fa-key"></i>
                        </div>
                        <div class="checkbox checkbox-primary">
                            <input id="checkbox101" name="remmeber_me" type="checkbox"<?php if(isset($_SESSION["login"]["remmeber_me"])) echo ' checked';?>>
                            <label for="checkbox101"> Remember Me</label>
                        </div>
                        
                        <button type="submit" class="btn btn-default btn-block" name="login_submit">LOGIN</button>
                    </div>
                </form>
                <div class="footer-links row">
                    <div class="col-xs-6"><a href="login_forgot_pass.php"><i class="fa fa-lock"></i> Forgot password</a></div>
                </div>
    		</div>
   		</div>
   		<div class="clr"></div>
	</div>
</body>
</html>