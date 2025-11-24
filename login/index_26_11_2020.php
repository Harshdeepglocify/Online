<?php include "../config/config.php"; ?>
<?php
include "Browser.php";
$browser = new Browser();
//Direct Login to user 
if (!empty($_GET['UID']) && !empty($_GET['PW'])) {
    $_POST['email'] = $_GET['UID'];
    $_POST['password'] = $_GET['PW'];
    include './Robot.php';
}
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$microsoft_edge ="";
if (strpos($userAgent, "Edg") !== false) {
    $microsoft_edge ="1";
}


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo ADMIN_Text; ?> | Login</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <?php include "../config/css.php"; ?>
        <!-- iCheck -->

        <!-- cookies assets start -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>dist/css/jquery-eu-cookie-law-popup.css"/>
        <script src="<?php echo ADMIN_URL; ?>dist/js/jquery-eu-cookie-law-popup.js"></script>

        <!-- cookies assets end -->

        <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>plugins/iCheck/square/blue.css">

        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">

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
            
            <?php
            if (defined('LOGIN_PAGE_IMG') && !empty(LOGIN_PAGE_IMG)) {
                echo '.login-page, .register-page { 
			  				background: url(' . LOGIN_PAGE_IMG . ') no-repeat center center fixed; 
			  				-webkit-background-size: cover;
			  				-moz-background-size: cover;
			  				-o-background-size: cover;
			  				background-size: cover;
			  				
						}';
            } else {
                echo '.login-page, .register-page { 
                            background: #FF0066 !important; 
                            -webkit-background-size: cover;
                            -moz-background-size: cover;
                            -o-background-size: cover;
                            background-size: cover;
                            
                        }';
            }
            ?>
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
                            $("#errorMessage").html('<div class="alert_box error"><button class="close" aria-live="polite"></button>' + result + '</div>');
                        }
                    }
                });
            }
        </script>
        <!-- <script>
            (function () {
                var s = document.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = 'https://app.termly.io/embed.min.js';
                s.id = '77c63cce-b493-44dd-914f-168104a58cdc';
                s.setAttribute("data-name", "termly-embed-banner");
                var x = document.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            })();
        </script> -->
    </head>
    <style>
        .alert .close {
            color: #ffffff;
            opacity: 1;
            font-family: Roboto Slab, serif;
        }

    </style>
    <body class="hold-transition login-page eupopup eupopup-bottom">
    <!-- HTML NEEDED FOR THE IMPORTANT MESSAGE MODAL POPUP -->

    <div class="modal fade " id="important-msg" tabindex="-1" role="dialog" aria-labelledby="important-msg-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background:#f06; color:#fff;">
                    <button type="button" class="close" style="color: #fff;" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="important-msg-label">Alert!</h4>
                </div>
                <div class="modal-body">
                    <p>iPhone and iPad users must use the Safari browser.</p>
                </div>
                <div class="modal-footer" style="background:#f06; color:#fff;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- END HTML NEEDED FOR THE IMPORTANT MESSAGE MODAL POPUP -->

        <div class="container" aria-live="polite">
            <?php
            if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                if ($_SESSION['error']['message'] == 'IPHONE_ERROR'){?>
                    <script type="text/javascript">
                        setTimeout(function () {
                            $('#important-msg').modal('show');
                        }, 1000);
                    </script>
                <?php }
                else{
                    ?>
                    <!--<div style="font-family: Roboto Slab, serif; background-color: #000;" class="alert alert-<?php // echo $_SESSION['error']['color']   ?> alert-dismissable fade in">-->
                <div style="font-family: Roboto Slab, serif; background-color: #000; font-size: 20px; color: #FFFFFF" class="alert  alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="Close popup alert" aria-live="polite">&times;</a>
                <?php echo $_SESSION['error']['message'] ?>
                </div>
            <?php }}
              $user_agent = getenv("HTTP_USER_AGENT");
              $os = "";
              if(strpos($user_agent, "Mac") !== FALSE)
             {  $os = "Mac"; }
           $login_aria_label ="Log in button";
           $custom_message ="";
           if ($browser->getBrowser() != Browser::BROWSER_CHROME   && $browser->getBrowser() != Browser::BROWSER_SAFARI ) {
              
                  if ($browser->getBrowser() != Browser::BROWSER_SAFARI && $os == 'Mac') {
                      $custom_message = "Note: The Safari/Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Safari browser";
                      $login_aria_label="Log in button. The Safari/Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Safari/Chrome browser";
                  }else if ($browser->getBrowser() != Browser::BROWSER_SAFARI && $browser->getBrowser() == Browser::BROWSER_IPHONE && $browser->getBrowser() == Browser::BROWSER_IPAD && $browser->getBrowser() == Browser::BROWSER_IPOD) {
                    $custom_message = "Note: The Safari/Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Safari/Chrome browser";
                    $login_aria_label="Log in button. The Safari/Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Safari/Chrome browser";

                  }else{
                    $custom_message= "Note: The Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Chrome browser";
                    $login_aria_label= "Log in button. The Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Chrome browser";

                  }
             }
             else if($microsoft_edge == "1"){
                $custom_message= "Note: The Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Chrome browser";
                 $login_aria_label= "Log in button. The Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Chrome browser";
          }
          

   unset($_SESSION['error']); ?>
			
            
<!--            <div style="font-family: Roboto Slab, serif; background-color: #000; font-size: 20px; color: #FFFFFF" class="alert  alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="Close popup alert" aria-live="polite">&times;</a>
                This is where I type the popup notification text Click <a href="//www.accessibyte.com/accessibyte-online">here</a> for details.
            </div>-->           
        </div>
        <div class="login-box">
            <!-- <div class="login-logo">&nbsp;</div> --> <!-- /.login-logo -->
            <div class="login-box-body login-header">
                <span class="logo-lg">
                    <div class="image">
                        <img src="<?php echo ADMIN_URL . 'img/accessibyte-online-logo.png' ?>" class="accessibyte-logo center-block img-responsive" alt="Accessibyte Online logo">
                    </div>
                </span>
            </div>
            <div class="login-box-body">
                <p class="login-box-msg">Log in to your account</p>
                <form action="<?php echo ADMIN_URL . 'login/Robot.php' ?>" method="post" id="login-form">
                    <div id="errorMessage" class="help-block help-block-error"></div>
                    <div class="form-group has-feedback ">
						<label>Username</label>
                        <input type="text" name="email" id="email" autofocus="" required="" class="form-control" aria-label="Username field" placeholder="Enter Username">
                    </div>
                    <div class="form-group has-feedback ">
						<label>Password</label>
                        <input type="password" name="password" required="" id="password" class="form-control" aria-label="Password field" placeholder="Password">
                    </div>
					<?php
                        if(isset($custom_message) && $custom_message !=""){
                    ?>
                    <div style="font-family: Roboto Slab, serif; font-size: 14px;" class="form-group has-feedback">
                        <span aria-label="<?php echo $custom_message;?>"><strong><?php echo $custom_message;?></strong></span>
                    </div>
                    <?php } ?>
                    <div class="row">
                        

                        <div class="col-md-12 col-xs-12">
                            <button type="submit" name="Sign In" aria-label="<?php echo $login_aria_label;?>" class="btn btn-primary btn-block btn-flat"/>Log In<i class="fa fa-long-arrow-right" aria-hidden="true"></i> </button>                      

                        <br></div><!-- /.col -->
			
						<div class="col-md-12 col-xs-12">

                            <a href="<?php echo ADMIN_URL . 'register.php'; ?>" aria-label="Register or update account button." class="btn btn-primary btn-block btn-flat btn-accessibyte">Register or Update Account</a>
                            <div class="terms_conditions">
                                <a href="<?php echo ADMIN_URL . 'forgot-pass.php'; ?>" class="accessibyte-link">Forgot Password?</a><br>

                            </div>
                        </div><!-- /.col -->
                    </div>
                </form>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-left">
                            <!-- Need an account? <a href="<?php //echo ADMIN_URL . 'registration.php';  ?>" class="accessibyte-link">Register here</a> -->
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

