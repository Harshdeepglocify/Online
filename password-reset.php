<?php
include "config/config.php";
$class = '';
if( (!isset( $_GET['token'] ) || $_GET['token'] == '') && empty($_POST) ){
    $_SESSION['error']['message'] = "Something went wrong.";
    $_SESSION['error']['color'] = "danger";
    $class = 'hide';
} else if( (isset( $_GET['token'] ) || $_GET['token'] != '') && empty($_POST)  ) {
	$token = $_GET['token'];
    $decrypetToken = rawurldecode(decrypt($token));
    $verifyToken = query("SELECT count(*) as total FROM user where user.token='$decrypetToken'");
    $data = fetch($verifyToken);
    if ($data['total'] == 0) {
        //Token does not match
        $_SESSION['error']['message'] = "Invalid Token";
        $_SESSION['error']['color'] = "danger";
        $class = 'hide';
    }
}else if (isset($_POST) && !empty($_POST)) {
    if (isset($_POST['reset']) && $_POST['reset'] == 'Reset Password') {
        reset_user_pass($_POST['password'],$_POST['token']);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo ADMIN_Text; ?>| Reset Password</title>
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
            /*.login-box{box-shadow: 0px 0px 35px 10px #182837;padding: 0;}*/
            <?php 
            if( defined( 'LOGIN_PAGE_IMG' ) && !empty(LOGIN_PAGE_IMG) ){
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
            
            <div class="login-box-body login-header">
                <span class="logo-lg">
                    <div class="image">
                        <img src="<?php echo ADMIN_URL . '/img/logo.png' ?>" class="accessibyte-logo center-block img-responsive" alt="Admin User">
                    </div>
                </span>
            </div>

            <div class="login-box-body">
                <p class="login-box-msg">Reset password</p>

                <?php
                    if( $class != 'hide' ){
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="password-reset-form">
                    <div aria-live="assertive" id="errorMessage" class="help-block help-block-error"></div>
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" required="" id="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label>Password confirm *</label>
                        <input type="password" name="password_confirm" required="" id="password_confirm" class="form-control" placeholder="Password Confirm">
                    </div>
                    <div class="row">
                        <input type="hidden" name="token" value="<?php echo $_GET['token'] ?>">
                        <div class="col-xs-6 pull-right">
                            <input type="submit" value="Reset Password" name="reset" class="btn btn-primary btn-block btn-flat col-md-12"/>
                      
                    </div>
                </form>
                    <?php } ?>
                <div class="row" id="bottom_row">
                    <div class="col-xs-12">
                        <div class="pull-left register-center">
                            Need an account? <a href="<?php echo ADMIN_URL . 'register.php'; ?>" class="accessibyte-link">Register here</a> 
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

