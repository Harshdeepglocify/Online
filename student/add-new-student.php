<?php
include "../config/config.php";

if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}
/* For expired license not access this page directly */
$is_expired = check_expire_or_not();
if($is_expired){
    header("Location: " . ADMIN_URL);
    exit;
}
if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST['Submit']) && $_POST['Submit'] == 'add-new-student') {

        extract($_POST);
    
    

        $error = 0;
        /* if ( !empty( $email ) && $email != $email_confirm) {
          $error = 1;
          $_SESSION['error']['message'] = 'Email does not match';
          $_SESSION['error']['color'] = 'danger';
          } */


        /* if ($password != $password_confirm) {
          $error = 1;
          $_SESSION['error']['message'] = 'Password does not match';
          $_SESSION['error']['color'] = 'danger';
          } */
        if (empty($age)) {
            $age = 0;
        }
        if (empty($grade)) {
            $grade = 0;
        }
        if ($error == 0) {
            //Set current user teacher code
            $teacher_code = !empty($_SESSION['User']['teacher']) ? $_SESSION['User']['teacher'] : '';
            
          
            $email = !empty($_SESSION['User']['email']) ? $_SESSION['User']['email'] : '';
			$license = $_SESSION['User']['license'];
            $_firstname = escapeString($firstname);
            $_lastname = '';

            if (isset($organization) && !empty($organization)) {
                $_organization = escapeString($organization);
            } else {
                $_organization = '';
            }
            $_email = escapeString($email);
            $_password = escapeString($password);
			$_username = escapeString($username);
			$_username = strtolower($_username);
            
            if(!empty($teacher_code))
            {
                $sql = query('SELECT firstname,lastname,organization,seat_limit FROM user WHERE teacher="' . $teacher_code. '" and role="teacher"');
                $data = fetch($sql);
                if(!empty($data)){
                    $flag = 1;
                    if(!empty($data['seat_limit'])){
                        $studentsql = query('SELECT count(id) as count FROM user WHERE teacher="' . $teacher_code. '" and role="student"');
                        $student_data = fetch($studentsql);
                        if(!empty($student_data) && isset($student_data['count'])){
                            if($student_data['count'] >= $data['seat_limit']){
                                $flag = 0;
                            }
                        }
                    }
                    if(!empty($flag)){
                        register_user($user_type, base64_encode($_firstname), base64_encode($_lastname), base64_encode($_username), $_email, $_password, base64_encode($_organization), $license, $age, $grade, $teacher_code, false);
                    } else {
                        $_SESSION['error']['message'] = 'Student limit reached.';
                    }
                }
            }
            //register_user($user_type, base64_encode($_firstname), base64_encode($_lastname), base64_encode($_username), $_email, $_password, base64_encode($_organization), $license, $age, $grade, $teacher_code, false);
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo ADMIN_Text; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->	
        <?php include "../config/css.php"; ?>
        <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>plugins/iCheck/square/blue.css">
        <script>
            var ADMIN_URL = '<?php echo ADMIN_URL; ?>';
        </script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php include "../config/top-header.php"; ?>
            <!-- Left side column. contains the logo and sidebar -->
            <?php include "../config/left-sidebar.php"; ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="student-title-main-init">              
                                <h3>Add Student</h3>
                            </div>
                        </div>
                        <!-- Title -->
                        <div class="col-md-12">
                            <div class="box">
                                <form action="" method="POST" id="registartion" class="margin-bottom">
                                    <div class="box-body">
                                        <?php
                                        if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                                            ?>
                                            <div aria-live="assertive" style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF;" class="alert  alert-dismissable fade in">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <?php echo $_SESSION['error']['message'] ?> &nbsp;&nbsp;<a href="<?php echo ADMIN_URL . '/student/student-list.php' ?>" style="color:#FF0080">Back to Students page.</a>
                                            </div>
                                        <?php } unset($_SESSION['error']) ?>
                                        <div class="col-md-6 col-md-offset-3">
                                            <span>Please fill out the following fields to register</span>
                                            <span class="col-md-12 p-0 m-0 d-block"  >For your reference. Don't use real names or PII.</span>
                                            <div id="errorMessage" class="help-block help-block-error"></div>

                                            <div class="col-xs-4 form-group no-padding" style="display:none">
                                                <input type="radio" name="user_type" id="user_type_student" class="form-control user_type" value="student"  checked>
                                            </div>

                                            <div class="form-group ">
                                                <label><!-- First name -->Display Name *</label>
                                                <input type="text" name="firstname" id="firstname" autofocus="" required="" class="form-control" placeholder="Display Name" >
                                            </div>

                                            <div class="form-group ">
                                                <label>Username *</label>
                                                <input type="text" name="username" id="username" required="" class="form-control" placeholder="Username" oninput="text_restrictions(this);">
                                            </div>

                                            <div class="form-group">
                                                <label>Password *</label>
                                                <input type="password" name="password" required="" id="password" class="form-control" placeholder="Password">
                                            </div>
                                            <!-- <div class="form-group">
                                                <label>Password confirm *</label>
                                                <input type="password" name="password_confirm" required="" id="password_confirm" class="form-control" placeholder="Password Confirm">
                                            </div>
                                            <div class="form-group">
                                                <label>Age </label>
                                                <input type="text" name="age"  id="edd_age" class="form-control" placeholder="Age">
                                            </div>
                                            <div class="form-group">
                                                <label>Grade </label>
                                                <input type="text" name="grade" id="edd_grade" class="form-control" placeholder="grade">
                        </div> -->
                                            <!--<div class="form-group">
                                                <label>License *</label>
                                                <input type="text" name="license" required="" id="edd_license" class="form-control" placeholder="License" autocomplete="off">
                                            </div>-->
                                            <div class="form-group teacher_code_wrap">
                                                <label>Teacher Code</label>
                                                <input type="text" name="teacher_code" id="teacher_code" class="form-control" placeholder="Teacher Code">
                                            </div>

                                            <div class="form-group">
                                            <?php
                                                $no_of_licence = get_license_data($_SESSION['User']['license']);
                                                if (!empty($no_of_licence) && isset($no_of_licence['no_student_use'])) {

                                                    if ($no_of_licence['no_student_use'] != $no_of_licence['no_student']) {
                                                        ?>
                                                        <button type="submit" value="add-new-student" class="dashboard-settings-btn btn-block" name="Submit">Register New Student</button>
                                                        <?php
                                                    }
                                                } else {
                                                    $licenseArr = get_license_details_all($_SESSION['User']['license']);
                                                    $left = isset($licenseArr['activations_left']) ? $licenseArr['activations_left'] : '';
                                                    if (!empty($left) && $left > 0) {
                                                        ?>
                                                        <button type="submit" value="add-new-student" class="dashboard-settings-btn btn-block" name="Submit">Register New Student</button>
                                                    <?php }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include "../config/footer.php"; ?>

            <!-- Control Sidebar -->
            <?php include "../config/setting.php"; ?>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>

        </div><!-- ./wrapper -->

        <!-- jQuery 2.1.4 -->
        <script src="<?php echo ADMIN_URL ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo ADMIN_URL ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo ADMIN_URL; ?>plugins/iCheck/icheck.min.js"></script>
        <!-- FastClick -->
        <script src="<?php echo ADMIN_URL ?>plugins/fastclick/fastclick.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo ADMIN_URL ?>dist/js/app.min.js"></script>
        <!-- SlimScroll 1.3.0 -->
        <script src="<?php echo ADMIN_URL ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/boot-box/bootbox.min.js"></script>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
		function text_restrictions(el, minLength = 8) {
		// filter value: lowercase + only a–z, 0–9, - and _
		el.value = el.value
			.toLowerCase()
			.replace(/[^a-z0-9\-_]/g, '')
			.slice(0, minLength); // <-- stop at max length

		// error element (id + "-error")
		const errorEl = document.getElementById(el.id + '-error');

		// show or hide error based on min length
		if (el.value.length < minLength) {
			errorEl.textContent = 'Minimum ' + minLength + ' characters required.';
			errorEl.style.display = 'block';
		} else {
			errorEl.style.display = 'none';
		}
	}

        </script>
        <script src="<?php echo ADMIN_URL; ?>plugins/jQuery-validate/jquery.validate.js"></script>
        <script src="<?php echo ADMIN_URL; ?>dist/js/validate.js"></script>
    </body>
</html>