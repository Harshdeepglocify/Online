<?php include "config/config.php"; 
if( isset( $_POST ) ){
    if( isset( $_POST['forgot'] ) && $_POST['forgot'] == 'Send Email' ){		
        send_token_to_reset_password($_POST['username']);exit;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo ADMIN_Text; ?>| Forgot Password</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <?php include "config/css.php"; ?>
        <!-- iCheck -->
            <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
        <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>plugins/iCheck/square/blue.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
            .help-block-error{ color:#a94442; }
            .login-header {
                  margin: 2px;
                display:flex;
                background: none repeat scroll 0 0 #fff;
                border-top-left-radius: 4px;
                border-top-right-radius: 4px;
                padding: 1px 1px;
            }
            <?php 
            if( defined( 'LOGIN_PAGE_IMG' ) && !empty(LOGIN_PAGE_IMG) ){
            	echo '.login-page, .register-page { 
			  			 background: #fff ; 
                            -webkit-background-size: cover;
                            -moz-background-size: cover;
                            -o-background-size: cover;
                            background-size: cover;
			  				
						}';
            } ?>
        </style>
        <script>
            var ADMIN_URL = '<?php echo ADMIN_URL; ?>';
        </script>
        <script type="text/javascript">
            function validLogin() {
                var email = $('#email').val();
                var password = $('#password').val();
                var dataString = 'email=' + email + '&password=' + password;
                $.ajax({
                    type: "POST",
                    url: "Robot.php",
                    data: dataString,
                    cache: false,
                    success: function (result) {

                        if (result == 1) {
                            window.location = '<?php echo ADMIN_URL; ?>';
                        } else {
                            $("#errorMessage").html('<div class="alert_box error"><button class="close"></button>' + result + '</div>');
                        }
                    }
                });
            }
        </script>
    </head>

    <body class="hold-transition login-page">
        <div class="container" aria-live="assertive">
            <?php
                if(isset($_SESSION['error']) && !empty( $_SESSION['error'] )) {
            ?>
            <!-- <div class="alert alert-<?php echo $_SESSION['error']['color'] ?> alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> -->
            <div aria-live="assertive" style="font-family: Roboto Slab, serif; background-color: #000; font-size: 20px; color: #FFFFFF;" class="alert  alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" style="color: #FFFFFF; opacity: 1.2;">&times;</a>
                <?php echo $_SESSION['error']['message'] ?>
            </div>
                <?php } unset($_SESSION['error']) ?>
        </div>
        <div class="login-box-body login-header">
                <span class="logo-lg">
          			<div class="image">
              			<img src="<?php echo ADMIN_URL.'img/accessibyte-logo-update.svg' ?>" class="accessibyte-logo center-block img-responsive" alt="Accessibyte Online Logo">
            		</div>
          		</span>
            </div>
        <div class="login-box">
            <div class="login-logo">&nbsp;</div><!-- /.login-logo -->

            

            <div class="login-box-body">
                <p class="login-box-msg">Please provide username that you are registered with</p>

                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="frogot-pass-form">
                    <div id="errorMessage" aria-live="assertive" class="help-block help-block-error"></div>
                    <div class="form-group has-feedback ">
                        <input type="text" name="username" id="username" required="" class="form-control" placeholder="Username">
						<input type="hidden" name="hidden_forgot_pass" id="hidden_forgot_pass" value="forgotpass" required="" class="form-control" placeholder="forgotpass">
                        <!--<span class="glyphicon glyphicon-envelope form-control-feedback"></span>-->
                    </div>

                    <div class="row">
                        <div class="col-xs-12 pull-right">
                            <input type="submit" value="Send Email" name="forgot" aria-label="Send password reset email button" class="btn btn-primary btn-block btn-flat"/>
                        </div><!-- /.col -->
                    </div>
                </form>
<div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right">
                            <a href="<?php echo ADMIN_URL; ?>" class="accessibyte-link">Go back</a>
                        </div>
                    </div>
                </div>

            </div><!-- /.login-box-body -->

        </div><!-- /.login-box -->

        <!-- jQuery 2.1.4 -->
        <script src="<?php echo ADMIN_URL; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo ADMIN_URL; ?>bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="<?php echo ADMIN_URL; ?>plugins/jQuery-validate/jquery.validate.js"></script>
        <script src="<?php echo ADMIN_URL; ?>dist/js/validate.js"></script>

    </body>

</html>

