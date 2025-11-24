
<?php
include "config/config.php";

if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST['Submit']) && $_POST['Submit'] == 'Update License') {
        extract($_POST);
        $error = 0;
		
        if (empty($username)) {
            $error = 1;
            $_SESSION['error']['message'] = 'User Name can not be blank';
            $_SESSION['error']['color'] = 'danger';
        }
        if (empty($password)) {
            $error = 1;
            $_SESSION['error']['message'] = 'Password can not be blank';
            $_SESSION['error']['color'] = 'danger';
        }
        if (empty($license)) {
            $error = 1;
            $_SESSION['error']['message'] = 'License can not be blank';
            $_SESSION['error']['color'] = 'danger';
        }
		if(!isset($_POST['agree']) && empty($_POST['agree'])){
			$error = 1;
            $_SESSION['error']['message'] = 'Please agree to terms and privacy policy.';
            $_SESSION['error']['color'] = 'danger';
		}

        if ($error == 0) {
            $username = escapeString($username);
            $password = escapeString($password);
            update_license_multi_teacher($username, $password, trim($license));
        }
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo ADMIN_Text; ?> | Update License</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <?php include "config/css.php"; ?>
        <!-- iCheck -->
         <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
        <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>plugins/iCheck/square/blue.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- cookies assets start -->
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>dist/css/jquery-eu-cookie-law-popup.css"/>
        <script src="<?php echo ADMIN_URL; ?>dist/js/jquery-eu-cookie-law-popup.js"></script>
        <!-- cookies assets end -->


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
            .no-padding{
                padding:0!important;
            }
            .organization_wrap{
                display:none;
            }            
            .text-box{text-align: left;font-size: 15px;padding: 10px !important;}
            #registartion{display: none;}
            <?php
            if (defined('LOGIN_PAGE_IMG') && !empty(LOGIN_PAGE_IMG)) {
                echo '.login-page, .register-page { 
			  				background: url(' . LOGIN_PAGE_IMG . ') no-repeat center center fixed; 
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
    </head>

    <body class="hold-transition login-page  theme-page eupopup eupopup-bottom">
        <div class="container">
            <?php
            if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                ?>
                <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php 
                    echo $_SESSION['error']['message']; 
                    if($_SESSION['error']['message'] == 'License updated successfully.') {
                        $_POST['username'] = '';
                        $_POST['password'] = '';
                        $_POST['license'] = '';
                    }
                    ?>
                </div>
            <?php } unset($_SESSION['error']) ?>
        </div>
         <div class="login-box-body login-header  theme-page">
                <span class="logo-lg">
                    <div class="image">
                        <img src="<?php echo ADMIN_URL . 'img/accessibyte-logo-update.svg' ?>" class="accessibyte-logo center-block img-responsive" alt="Accessibyte Online logo">
                    </div>
                </span>
            </div>
        <div class="login-box">
           
           

            <div class="login-box-body  theme-page">
                <div class="register_header">
                    <h5 class="register-heading" align="center">License expired? Upgrading your service?</h5>
                    <div class="col-xs-12 form-group input_box no-padding">
                        <p style="font-weight: 400; text-align: center;">This form allows you to update your current Accessibyte Online license. Doing so will replace your existing or expired license with the new one. If you don't have a license to active, you'll need to purchase one before filling out this form.</p>
                    </div>
                </div>    

                <div class="update-lic">
                    <form action="<?php echo LICENSE_UPDATE_FILE_PATH ?>" method="POST" id="update_license_frm" class="margin-bottom">
                        <input type="hidden" name="update_license_withoutLogin" value="withoutlogin" />
                        <div class="form-group">
                            <label>Username *</label>
                            <input type="text" name="username" autofocus="" id="username" required="" class="form-control" placeholder="Username" aria-label="Username field" value="<?php if(isset($_POST['username'])){ echo $_POST['username'];}else{ echo '';} ?>" >
                        </div>
                        <div class="form-group">
                            <label>Password *</label>
                            <input type="password" name="password" required="" id="password" class="form-control" placeholder="Password" autocomplete="" aria-label="Password field" value="<?php if(isset($_POST['password'])){ echo $_POST['password'];}else{ echo '';} ?>">
                        </div>
                        <div class="form-group">
                            <label>New License *</label>
                            <input type="text" name="license" required="" id="edd_license" class="form-control" placeholder="License" autocomplete="off" aria-label="License field" value="<?php if(isset($_POST['license'])){ echo $_POST['license'];}else{ echo '';} ?>">
                        </div>
                        <div class="form-group" style="margin-left:18%">
                            <div class="g-recaptcha" data-sitekey="6LfM9nUUAAAAADWkaqgsFvewCxon5HhUEpN8qSVT"></div>
                        </div>
						<div class="form-group text-center">
							
                            <div class="registerCheck form-group">
								<input type="checkbox" id="agree" name="agree" value="1" <?php if(isset($_POST['agree']) && $_POST['agree'] == 1){ echo "checked";}else{ echo '';} ?> class="teachCheckbox">
								<label for="agree"> I agree to Accessibyte's <a href="<?php echo WP_URL; ?>terms-privacy" target="_blank" class="accessibyte-link"> terms and privacy </a>
								</label>
							</div>
                            
						</div>
                        <div class="form-group" >
                            <input type="button" value="Update License" name="Submit" class="btn btn-info pull-right update_license  col-md-12" style="margin-bottom:25px"/>
                            <input type="hidden" value="Update License" name="Submit" />
                        </div>
                    </form>
                </div>
                <br>
                <br>
                <div class="row" id="bottom_row">
                    <div class="col-xs-12">
                        <div class="pull-left register-center">
                            Already have an account? <a href="<?php echo ADMIN_URL . 'login' ?>" class="accessibyte-link">Login here</a>  
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
		$(document).on('keyup','.teachCheckbox', function(e){
                $(this).addClass('focus');
            });
            $(document).on('keyup','.accessibyte-link', function(e){
				$('.teachCheckbox').removeClass('focus');               
            });
			$(document).on('click', '.update_license', function () {
 $('.update_license').prop("disabled", true);
                var username = $('#username').val();
                var edd_license = $('#edd_license').val();
                var password = $('#password').val();
                $('.commonerror').html('');
                if (username == ""){
                    $('.teacher_list').focus();
                }
                if (edd_license == ""){
                    $('.firstname').focus();
                }
                
                if (password == ""){
                    $('.password').focus();
                }
                if(edd_license == "" || username == "" || password == ""){
 $('.update_license').prop("disabled", false);
                    return false;
                }
                formdata = $('#update_license_frm').serialize();
                $.ajax({
                    type: 'POST',
                    url: ADMIN_URL + 'config/config-student.php',
                    data: {form_data:formdata, 'action': 'check_user_as_admin'},
                    async: true,
                    cache: false,
                    timeout: 10000,
                    success: function (response) {
                        var response = $.parseJSON(response);
                       
                        if (response.status == true) {
                            $('#update_license_frm').submit();                         
                        } else {
                            alert(response.msg);
 $('.update_license').prop("disabled", false);

                        }
                    }
                });       
            });
        </script>
    </body>
</html>