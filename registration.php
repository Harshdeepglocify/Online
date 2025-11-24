<?php
include "config/config.php";

if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST['Submit']) && $_POST['Submit'] == 'Register') {
        
        extract($_POST);
        $error = 0;

        if ( !empty( $email ) && $email != $email_confirm) {
            $error = 1;
            $_SESSION['error']['message'] = 'Email does not match';
            $_SESSION['error']['color'] = 'danger';
        }

        if ($password != $password_confirm) {
            $error = 1;
            $_SESSION['error']['message'] = 'Password does not match';
            $_SESSION['error']['color'] = 'danger';
        }

        if ($error == 0) {
            $_firstname = escapeString($firstname);
            $_lastname = escapeString($lastname);
            if (isset($organization) && !empty($organization)) {
                $_organization = escapeString($organization);
            } else {
                $_organization = '';
            }
            $_email = escapeString($email);
            $_password = escapeString($password);

           register_user( $user_type, $_firstname, $_lastname, $username, $_email, $_password, $_organization, $license, $teacher_code );

        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo ADMIN_Text; ?> | Registration</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <?php include "config/css.php"; ?>
        <!-- iCheck -->
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
                background: none repeat scroll 0 0 #fff;
                border-top-left-radius: 4px;
                border-top-right-radius: 4px;
                padding: 1px 1px;
            }
            .no-padding{
                padding:0!important;
            }
            .organization_wrap{
            	display:none;
            }
            .login-box{box-shadow: 0px 0px 35px 10px #182837;padding: 0;}
            <?php 
            if( defined( 'LOGIN_PAGE_IMG' ) && !empty( LOGIN_PAGE_IMG ) ){
            	echo '.login-page, .register-page { 
			  				background: url('.LOGIN_PAGE_IMG.') no-repeat center center fixed; 
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
    </head>

    <body class="hold-transition login-page">
        <div class="container">
            <?php
            if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                ?>
            <!--<div style="font-family: Roboto Slab, serif; background-color: #000;" class="alert alert-<?php // echo $_SESSION['error']['color'] ?> alert-dismissable fade in">-->
                <div aria-live="assertive" style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $_SESSION['error']['message'] ?>
                </div>
            <?php } unset($_SESSION['error']) ?>
        </div>
        <div class="login-box">
            <div class="login-logo">&nbsp;</div><!-- /.login-logo -->

            <div class="login-box-body login-header">
                <span class="logo-lg">
                    <div class="image">
                        <img src="<?php echo ADMIN_URL . 'img/accessibyte-online-logo.png' ?>" class="accessibyte-logo center-block img-responsive" alt="Admin User">
                    </div>
                </span>
            </div>

            <div class="login-box-body">
                <p class="login-box-msg">Please fill out the following fields to register</p>
                <form action="<?php echo ADMIN_URL . 'registration.php' ?>" method="POST" id="registartion" class="margin-bottom">
                    
                    <div aria-live="assertive" id="errorMessage" class="help-block help-block-error"></div>

                    <div class="row">
                    	<div class="col-xs-4">
                    		<label>User Type :</label>
                    	</div>
                    	<div class="col-xs-4 form-group no-padding">
                        	<input type="radio" name="user_type" id="user_type_student" class="form-control user_type" value="student" checked>
                        	<label for="user_type_student">Individual</label>
                        </div>
                    	<div class="col-xs-4 form-group no-padding">
                        	<input type="radio" name="user_type" id="user_type_teacher" class="form-control user_type" value="teacher">
                        	<label for="user_type_teacher">Teacher</label>
                    	</div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-6 form-group no-padding ">
                                <label>First name *</label>
                                <input type="text" name="firstname" id="firstname" autofocus="" required="" class="form-control" placeholder="First name">
                            </div>
                            <div class="col-xs-6 form-group no-padding">
                                <label>Last name </label>
                                <input type="text" name="lastname" id="lastname" required="" class="form-control" placeholder="Last name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label>Username *</label>
                    	<input type="text" name="username" id="username" required="" class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group organization_wrap">
                        <label>Organization</label>
                        <input type="text" name="organization" id="organization" class="form-control" placeholder="Organization">
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" id="email" required="" class="form-control" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label>Email confirm *</label>
                        <input type="email" name="email_confirm" id="email_confirm" required="" class="form-control" placeholder="Email Confirm">
                    </div>
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" required="" id="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label>Password confirm *</label>
                        <input type="password" name="password_confirm" required="" id="password_confirm" class="form-control" placeholder="Password Confirm">
                    </div>
                    <div class="form-group">
                        <label>License *</label>
                        <input type="text" name="license" required="" id="edd_license" class="form-control" placeholder="License">
                    </div>
                    <div class="form-group teacher_code_wrap">
                        <label>Teacher Code</label>
                        <input type="text" name="teacher_code" id="teacher_code" class="form-control" placeholder="Teacher Code">
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="checkbox icheck form-group center">
                                <label>
                                    <input type="checkbox" id="agree" name="agree"> I agree to the terms and privacy conditions
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Register" name="Submit" class="btn btn-primary"/>
                    </div>
                </form>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-left">
                            Already have an account? <a href="<?php echo ADMIN_URL . 'login' ?>" class="accessibyte-link">Login here</a>  
                        </div>
                        <div class="pull-right">
                            <a target="_blank" href="https://www.accessibyte.com/terms" class="accessibyte-link">Terms</a> | <a target="_blank" href="https://www.accessibyte.com/privacy" class="accessibyte-link">Privacy</a>
                        </div>
                        <div class="pull-left">
                            Need help? <a href="https://www.accessibyte.com/help" class="accessibyte-link">Help</a>  
                        </div>
                    </div>
                </div>
                <br>
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->
        <!-- jQuery 2.1.4 -->
        <script src="<?php echo ADMIN_URL; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo ADMIN_URL; ?>bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="<?php echo ADMIN_URL; ?>plugins/iCheck/icheck.min.js"></script>
        <script src="<?php echo ADMIN_URL; ?>plugins/jQuery-validate/jquery.validate.js"></script>
        <script src="<?php echo ADMIN_URL; ?>dist/js/validate.js"></script>

        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    </body>
</html>