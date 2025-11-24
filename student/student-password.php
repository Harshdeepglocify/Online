<?php
include "../config/config.php";

if(!$_SESSION['User']){
    header("Location: " . ADMIN_URL . 'login');
    exit;
}

$user_id = !empty($_GET['student']) ? $_GET['student'] : '';
$teacher_code = !empty($_SESSION['User']['teacher']) ? $_SESSION['User']['teacher'] : '';

if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST['Submit']) && $_POST['Submit'] == 'change-password-student') {

        extract($_POST);
        $error = 0;

        if ($password != $password_confirm) {
            $error = 1;
            $_SESSION['error']['message'] = 'Password does not match';
            $_SESSION['error']['color'] = 'danger';
        }

        if ($error == 0) {

            //Set current user teacher code
            $_password = escapeString($password);
            reset_user_pass($_password, $student_token, false);
        }
    }
}


//set rgument for get user details
$args = array('user_id' => $user_id, 'teacher_code' => $teacher_code);
//Get user details based on argument
$user_details = get_users($args);
$user_details = !empty($user_details[0]) ? $user_details[0] : array();

//Check user available
if (empty($user_details)) {
    $_SESSION['error']['message'] = 'Something goes wrong, Student details not available.';
    $_SESSION['error']['color'] = 'danger';
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
                            <div class="student-title-main-init stuPass">
                                <h3>Change Password for <?php echo!empty($user_details) ? base64_decode($user_details['firstname']) . ' (' . base64_decode($user_details['username']) . ')' : '-'; ?></h3>
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
                                            <!--<div style="font-family: Roboto Slab, serif; background-color: #000;" class="alert alert-<?php // echo $_SESSION['error']['color']  ?> alert-dismissable fade in">-->
                                            <div style="background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in" aria-live="assertive" id="alert-success">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <?php echo $_SESSION['error']['message'] ?>
                                            </div>
                                        <?php } unset($_SESSION['error']) ?>
                                        <div class="col-md-6 col-md-offset-3">
                                            <span>Please set a new password for <?php echo!empty($user_details) ? base64_decode($user_details['firstname']) . ' (' . base64_decode($user_details['username']) . ')' : '-'; ?></span><br/><br/>

                                            <div class="form-group">
                                                <label>Password *</label>
                                                <input type="password" name="password" required="" id="password" class="form-control" placeholder="Password" autofocus>
                                            </div>
                                            <div class="form-group">
                                                <label>Password confirm *</label>
                                                <input type="password" name="password_confirm" required="" id="password_confirm" class="form-control" placeholder="Password Confirm">
                                                <input type="hidden" name="student_token" class="form-control" value="<?php echo!empty($user_details['token']) ? encrypt($user_details['token']) : '' ?>">
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" value="change-password-student" class="dashboard-settings-btn btn-block" name="Submit">Change Password</button>
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
        </script>
        <script src="<?php echo ADMIN_URL; ?>plugins/jQuery-validate/jquery.validate.js"></script>
        <script src="<?php echo ADMIN_URL; ?>dist/js/validate.js"></script>
    </body>
</html>