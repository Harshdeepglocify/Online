<?php
include "../config/config.php";

if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}
$is_expired = check_expire_or_not();
if($is_expired){
    header("Location: " . ADMIN_URL);
    exit;
}
if (empty($_SESSION['User']['is_admin'])) {
    header("Location: " . ADMIN_URL);
    exit;
}
if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST['Submit']) && $_POST['Submit'] == 'add-new-teacher') {

        extract($_POST);

 $email1 = strtolower($email);
        
        $email1 = base64_encode($email1);

 $query = query("SELECT count(*) as total FROM user where user.email='$email1' and role = 'teacher'");
    $data = fetch($query);

        $error = 0;
        if (empty($age)) {
            $age = 0;
        }
        if (empty($grade)) {
            $grade = 0;
        }
        if ($error == 0) {
            //Set current user teacher code
            $teacher_code = !empty($_SESSION['User']['teacher']) ? $_SESSION['User']['teacher'] : '';
          //  $email = !empty($_SESSION['User']['email']) ? $_SESSION['User']['email'] : '';
            $license = $_SESSION['User']['license'];
            $_firstname = escapeString($firstname);
            $_lastname = escapeString($lastname);
            $_organization = escapeString($organization);
            $_email = escapeString($email);
            $_password = escapeString($password);
            $_username = escapeString($email);
            $_seatlimit = escapeString($seat_limit);
            $student_count=0;
            $query = query("SELECT sum(seat_limit) student_count  FROM user where license='".$_SESSION['User']['license']."' AND role ='teacher'");
             if (!empty($query->num_rows)) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $student_count=$row['student_count'];
                }
            } 

            $no_of_licence = get_license_data($_SESSION['User']['license']);
            $total_student = $total_student_use = $left = "";
            if(!empty($no_of_licence)){
                $total_student = $no_of_licence['no_student'];
                $total_student_use = $no_of_licence['no_student_use'];
                $left = $total_student - $total_student_use;
            }
            
            if($total_student  > 0 && $_seatlimit !=""){
                if(($total_student < ($student_count+$_seatlimit)) || ($left > 0 && $left < $_seatlimit)){
                    $error =1;
                }

            }

  if ($data['total'] > 0) {
                $error = 1;
            }
            //echo $error;die;
            if($error == 1 ){
                $_SESSION['error']['message'] = "Not enough student seats available. Either choose a lower student seat limit or leave that field blank.";
                $_SESSION['error']['color'] = "danger";

            }else{
                if($_seatlimit ==""){
                    $_seatlimit ="0";
                }

				$_username = strtolower($_username);		
				$_email = strtolower($_email);
               register_user($user_type, base64_encode($_firstname), base64_encode($_lastname), base64_encode($_username), base64_encode($_email), $_password, base64_encode($_organization), $license, $age, $grade, $teacher_code, false,$_seatlimit); 
            }

            
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
                                <h3>Add Teacher</h3>
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
                                            <div aria-live="assertive" style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <?php echo $_SESSION['error']['message'] ?> &nbsp;&nbsp;<a href="<?php echo ADMIN_URL . '/teacher/teacher-list.php' ?>" style="color:#FF0080">Back to Teachers page.</a>
                                            </div>
                                        <?php } unset($_SESSION['error']) ?>
                                        <div class="col-md-6 col-md-offset-3">
                                            <span>Please fill out the following fields to register</span>
                                            <div id="errorMessage" class="help-block help-block-error"></div>

                                             <div class="col-xs-4 form-group no-padding" style="display:none">
                                                <!-- <input type="radio" name="user_type" id="user_type_student" class="form-control user_type" value="teacher"  checked> -->
                                            </div>
                                            <input type="hidden"  name="user_type" id="user_type"  value="teacher" required="" class="form-control" >
                                            

                                            <div class="form-group ">
                                                <label><!-- First name -->First Name *</label>
                                                <input type="text" name="firstname" id="firstname" autofocus="" value="<?php echo isset($_POST['firstname']) && !empty($_POST['firstname']) ? $_POST['firstname'] : '';?>" required="" class="form-control" placeholder="First Name">
                                                <label><!-- First name -->Last Name *</label>
                                                <input type="text" name="lastname" id="lastname" autofocus="" value="<?php echo isset($_POST['lastname']) && !empty($_POST['lastname']) ? $_POST['lastname'] : '';?>" required="" class="form-control" placeholder="Last Name">

                                            </div>

                                            <div class="form-group ">
                                                <label>Organization *</label>
                                                <input type="text" name="organization" id="organization" value="<?php echo isset($_POST['organization']) && !empty($_POST['organization']) ? $_POST['organization'] : '';?>" required="" class="form-control" placeholder="organization">
                                            </div>
                                            <div class="form-group">
                                                <label>Email *</label>
                                                <input type="text" name="email" required="" id="email" value="<?php echo isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : '';?>" class="form-control" placeholder="Email">
                                            </div>
                                            <div class="form-group">
                                                <label>Password *</label>
                                                <input type="password" name="password" required="" id="password" class="form-control" placeholder="Password">
                                            </div>
                                            <?php
                                           
                                                $no_of_licence = get_license_data($_SESSION['User']['license']);
                                                $no_of_student_use = $no_of_student = '0';
                                                if(!empty($no_of_licence)){
                                                    $no_of_student = isset($no_of_licence['no_student']) ? $no_of_licence['no_student'] : '0';
                                                    $no_of_student_use = isset($no_of_licence['no_student_use']) ? $no_of_licence['no_student_use'] : '0';
                                                }
                                                $sql = query('SELECT sum(seat_limit) as total_seat FROM user WHERE license="' . $_SESSION['User']['license']. '" and role="teacher"');
                                                $totallimitdata = fetch($sql);
                                                $available = $no_of_student - $totallimitdata['total_seat'];
                                            ?>
                                            <div class="form-group">
                                                <label>Student seat limit (<?php echo $available;?> of <?php echo $no_of_student;?> student seats available to be assigned. leave blank if no limit)</label>
                                                <input type="number" min="0" name="seat_limit" id="seat_limit" class="form-control" placeholder="Seat Limit">
                                            </div>
                                            <div class="form-group">
                                                <?php
                                                if ($no_of_licence['no_teacher_use'] != $no_of_licence['no_teacher']) { ?>
                                                <button type="submit" value="add-new-teacher" class="dashboard-settings-btn btn-block" name="Submit">Register New Teacher</button>
                                                <?php }?>
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