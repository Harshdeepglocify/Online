<?php
include "config/config.php";

if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST['Submit']) && $_POST['Submit'] == 'Register') {

        extract($_POST);
        $error = 0;

        /* if (!empty($email) && $email != $email_confirm) {
          $error = 1;
          $_SESSION['error']['message'] = 'Email does not match';
          $_SESSION['error']['color'] = 'danger';
          } */

        if (empty($email)) {
            $error = 1;
            $_SESSION['error']['message'] = 'Email can not leave empty';
            $_SESSION['error']['color'] = 'danger';
        }

        /* if ($password != $password_confirm) {
          $error = 1;
          $_SESSION['error']['message'] = 'Password does not match';
          $_SESSION['error']['color'] = 'danger';
          } */
        if (empty($age)) {
            $age = 0;
        }

        if ($error == 0) {

            if ($user_type != 'student') {
                $_firstname = escapeString($firstname);
                $_lastname = escapeString($lastname);
                $username = $email;
            } else {
                $_firstname = '';
                $_lastname = '';
            }
            if (isset($organization) && !empty($organization)) {
                $_organization = escapeString($organization);
            } else {
                $_organization = '';
            }
            $_email = escapeString($email);
            $_password = escapeString($password);
            $_username = escapeString($username);
			$_email = strtolower($_email);   /* To prevent case sensetive login */
			$_username = strtolower($_username); /* To prevent case sensetive login */
            $grade = '';
            register_user($user_type, base64_encode($_firstname), base64_encode($_lastname), base64_encode($_username), base64_encode($_email), $_password, base64_encode($_organization), trim($license), $age, $grade, $teacher_code);
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
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- cookies assets start -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>dist/css/jquery-eu-cookie-law-popup.css"/>
        <script src="<?php echo ADMIN_URL; ?>dist/js/jquery-eu-cookie-law-popup.js"></script>
        <!-- cookies assets end -->


        <style>
            .help-block-error{ color:#ff0066; }
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

    <body class="hold-transition login-page eupopup eupopup-bottom">
        <div class="container">
            <?php
            if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                ?>
                            <!--<div style="font-family: Roboto Slab, serif; background-color: #000;" class="alert alert-<?php // echo $_SESSION['error']['color']    ?> alert-dismissable fade in">-->
                <div aria-live="assertive" style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a>
                    <?php echo $_SESSION['error']['message'] ?>
                </div>
            <?php } unset($_SESSION['error']) ?>
        </div>
        <div class="login-box">          
            <div class="login-box-body login-header">
                <span class="logo-lg">
                    <div class="image">
                        <img src="<?php echo ADMIN_URL . 'img/accessibyte-online-logo.png' ?>" class="accessibyte-logo center-block img-responsive" alt="Accessibyte Online logo">
                    </div>
                </span>
            </div>

            <div class="login-box-body">
                <!-- <p class="login-box-msg">Please fill out the following fields to register</p> -->
                <div class="register_header">
                    <h1 class="register-heading" align="center">Which type of license are you registering?</h1>
                    <div class="row text-center">
                        <div class="col-xs-12">
                            <!-- <label>Which type of licence are you registering ?</label> -->
                        </div>
                        <div class="col-xs-12 form-group input_box no-padding">
                            <a class="btn btn-primary btn-block btn-flat btn-accessibyte" href="javascript:void(0)" id="user_type_student" type="button" aria-label="Individual User Registration Button">Individual User</a>
                            <!-- <p>Individual user access without the need for School Edition.</p> -->
                        </div>

                        <div class="col-xs-12 form-group input_box no-padding">

                            <a class="btn btn-primary btn-block btn-flat btn-accessibyte" id="user_type_teacher" href="javascript:void(0)" type="button" aria-label="School Edition Registration Button">Accessibyte School Edition</a>
                            <!-- <p>This is where you create your Teacher Dashboard and manage students.</p>  -->                           
                        </div>

                        <div class="col-xs-12 form-group input_box no-padding">
                            <a class="btn btn-primary btn-block btn-flat btn-accessibyte" href="<?php echo LICENSE_UPDATE_FILE_PATH; ?>" type="button" aria-label="Update existing license Button">Update Existing License</a>
                           <!--  <p>Existing license update.</p> -->

                        </div>    
                    </div>                
                </div>     
                <div class="register_header">
                    <div class="col-xs-12">
                        <hr width="90%" size="6" align="center" color="#313135" border-color="#313135";>
                        <div class="pull-left">
                            <h1 class="register-heading-style-2">Helpful links:</h1>
                            <div class="pull-left">
                                <ul>
                                    <li><a href="https://www.accessibyte.com/knowledge-base/registration-setup/im-a-teacher-how-do-i-get-started-using-accessibyte-online/" class="accessibyte-link">I'm a teacher. How do I get started using Accessibyte Online?</a></li>
                                    <li><a href="https://www.accessibyte.com/knowledge-base/registration-setup/i-just-need-individual-access-to-typio-no-teacher-stuff/" class="accessibyte-link">I need individual access to Accessibyte Online. No teacher stuff.</a></li>
                                    <li> <a href="https://www.accessibyte.com/knowledge-base/registration-setup/how-do-i-renew-access-to-accessibyte-online/" class="accessibyte-link">How do I upgrade my trial or renew access to Accessibyte Online?</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="<?php echo ADMIN_URL . 'register.php' ?>" method="POST" id="registartion" class="margin-bottom">
                    <div id="errorMessage" class="help-block help-block-error" aria-live="assertive"></div>

                    <div class="form-group student_value">
                        <input type="hidden" name="user_type" id="user_type" class="form-control user_type" value="student">
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-6 form-group no-padding" id="Lstname">
                                <label>First name*</label>
                                <input type="text" name="firstname" id="firstname" autofocus="" required="" class="form-control" placeholder="First name" aria-label="First Name, required">
                            </div>
                            <div class="col-xs-6 form-group no-padding" id="Lstname">
                                <label>Last name*</label>
                                <input type="text" name="lastname" id="lastname" required="" class="form-control" placeholder="Last name"aria-label="Last Name, required">
                            </div>
                        </div>
                    </div>
                    <div class="form-group teacherusername">
                        <label>Username*</label>
                        <input type="text" name="username" id="username" required="" class="form-control" placeholder="Username" aria-label="Username, required">
                    </div>

                    <div class="form-group organization_wrap">
                        <label>Organization*</label>
                        <input type="text" name="organization" id="organization" class="form-control" placeholder="Organization" aria-label="Organization, required">
                    </div>
                    <div class="form-group">
                        <label>Email*</label>
                        <input type="email" name="email" id="email" required="" class="form-control" placeholder="Email" aria-label="Email, required">
                    </div>
                    <!-- <div class="form-group">
                        <label>Email confirm *</label>
                        <input type="email" name="email_confirm" id="email_confirm" required="" class="form-control" placeholder="Email Confirm" aria-label="Confirm email, required">
                    </div> -->
                    <div class="form-group">
                        <label>Password*</label>
                        <input type="password" name="password" required="" id="password" class="form-control" placeholder="Password" autocomplete="" aria-label="Password, required">
                    </div>
                    <!-- <div class="form-group">
                        <label>Password confirm *</label>
                        <input type="password" name="password_confirm" required="" id="password_confirm" class="form-control" placeholder="Password Confirm" autocomplete="" aria-label="Confirm password, required">
                    </div> -->
                    <div class="form-group">
                        <label>License*</label><br>
                        <input type="text" name="license" required="" id="edd_license" class="form-control" placeholder="License" keyUp="validateForm();" autocomplete="off" aria-label="License. required">
                    </div>
                    <!-- <div class="form-group" id="age">
                        <label>Age</label>
                        <input type="text" name="age"  id="edd_age" class="form-control" placeholder="Age">
                    </div>
                    <div class="form-group" id="grade">
                        <label>Grade</label>
                        <input type="text" name="grade"  id="edd_grade" class="form-control" placeholder="Grade">
                    </div> -->
                    <div class="form-group teacher_code_wrap">
                        <label>Teacher Code *</label>
                        <input type="text" name="teacher_code" id="teacher_code" class="form-control" placeholder="Teacher Code">
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-center">
						
							<div class="registerCheck form-group">
								<input type="checkbox" id="agree" name="agree" class="teachCheckbox">
								<label for="agree"> I agree to Accessibyte's <a href="<?php echo WP_URL; ?>terms" target="_blank" class="accessibyte-link"> terms and privacy </a>
								</label>
							</div>
                           
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Register" name="Submit" class="btn btn-primary col-md-12" onclick="$(this).closest('form').submit()"/>
                    </div>
                </form>

                <div class="row" id="student_info">
                    <div class="text-box col-xs-12 form-group">
                        <p style='padding-bottom:15px;'>
                            Students can be added to your Teacher Dashboard once you have created your account and logged in.</p><p> The following link will get you up and running:</br>	
                            <a href="<?php echo WP_URL; ?>knowledge-base/im-a-teacher-how-do-i-get-started-using-accessibyte-online/">I'm a teacher. How do I get started using Accessibyte Online?</a>
                        </p>
                        <p>
                            <a href="https://www.accessibyte.com/online/register.php">Go Back</a> 
                        </p>
                    </div>
                </div>

                <div class="row" id="typio_info">
                    <div class="text-box col-xs-12 form-group">
                        <p>
                            Typio for Windows is an offline product and not part of Accessibyte Online.
                        </p>
                        <p>
                            To register, you must first download and install <a href="<?php echo WEB_PATH; ?>/typio">Typio for Windows</a>. Once you've launched the program, you can enter your license and activate your software.
                        </p>
                        <p>
                            <a href="https://www.accessibyte.com/online/register.php">Go Back</a> 
                        </p>
                    </div>
                </div>

                <div class="row" id="bottom_row">
                    <div class="col-xs-12">
                        <div class="pull-left register-center">
                            Already have an account? <a href="<?php echo ADMIN_URL . 'login' ?>" class="accessibyte-link">Log in here</a>  
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
            
//            $(document).on('keydown', function(e) {
//                if (e.target != $('#agree').get(0)) {
//                if (e.which === 32) {
//                    e.preventDefault(); 
//                    $('#agree').prop('checked', function() {
//                       return !this.checked; 
//                    });
//                }
//                }
//            });
            
            $(document).on('keyup','.teachCheckbox', function(e){
                $(this).addClass('focus');
            });
            $(document).on('keyup','.accessibyte-link', function(e){
				$('.teachCheckbox').removeClass('focus');               
            });
            function changeCheckbox(){
                
                if($('#agree'). is(":checked")){
                    $(this).attr('aria-checked','true');
                }else{
                    $(this).attr('aria-checked','false');
                }
                
                $(this).attr('aria-labelledby','agree');
            }
            
            $(function () {
                $('#student_info,#typio_info').hide();
                /*$('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
                $('.icheck').find('.icheckbox_square-blue').attr('aria-labelledby', 'agree');*/
            });


            function validateForm()
            {
                if (trim(document.insert.aname.value) === "")
                {
                    alert("Animal should have a name");
                    document.insert.aname.focus();
                    return false;
                }
            }
            function trim(value) {
                return value.replace(/^\s+|\s+$/g, "");
            }
        </script>
    </body>
</html>