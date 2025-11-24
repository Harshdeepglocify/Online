<?php include "config/config.php"; ?>
<?php
if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}

$get_license_details =  get_license_details_all($_SESSION['User']['license']);

$id = $_SESSION['User']['id'];
$args = array(
    'teacher_code' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
    'role' => 'student',
);
$students_list = get_users($args);

$timezone = query('SELECT * FROM settings WHERE id = "' . $id . '" AND item = "555" ');
$timezoneName = '';
if ($timezone->num_rows > 0) {
    $timezone_data = fetch($timezone);
    $timezoneName = $timezone_data['variable'];
}
if (isset($_POST) && !empty($_POST)) {
   
    if (isset($_POST['Submit']) && $_POST['Submit'] == 'Activate New License') {
        extract($_POST);
        $error = 0;

        if (empty($license)) {
            $error = 1;
            $_SESSION['error']['message'] = 'License can not leave empty';
            $_SESSION['error']['color'] = 'danger';
        }
        if ($error == 0) {

            $username = $_SESSION['User']['username'];
            $username = escapeString($username);
            update_license_new($username, trim($license));
        }
    } else if (isset($_POST['Submit']) && $_POST['Submit'] == 'Update Timezone') {
        extract($_POST);
        $error = 0;

        if (empty($timezone)) {
            $error = 1;
            $_SESSION['error']['message'] = 'Please select timezone';
            $_SESSION['error']['color'] = 'danger';
        }

        if ($error == 0) {


            update_timezone(trim($timezone), $id);
        }
    }
}
//echo "<pre>";print_r($_SESSION);
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

        <?php include "config/css.php"; ?> 

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <?php include "config/top-header.php"; ?>

            <!-- Left side column. contains the logo and sidebar -->
            <?php include "config/left-sidebar.php"; ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <!-- Main content -->
                <section class="content">
                    <div class="row">

                        <div class="col-md-12">

                            <div class="box-body panel student-title-main-init">              

                                <h3>My Account</h3>

                            </div>

                        </div>
                        <div class="col-md-12">

                            <div class="box-body panel my-account-main-init">

                                <div class="typio-sidebar-alert alert alert-dismissible" aria-live="assertive"></div>
                                <div class="col-md-12 row errorView" >

                                    <?php
                                    if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                                        ?>
                                        <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">
                                            <h4><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <?php echo $_SESSION['error']['message'] ?></h4>
                                        </div>
                                        <?php
                                    }
                                    unset($_SESSION['error']);
                                    ?>
                                </div>
                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="">

                                            <div class="with-border">

                                                <h3 class="box-title"><b><?php
                                                        if (isset($_SESSION['User'])) {
                                                            echo base64_decode($_SESSION['User']['firstname']) . ' ' . base64_decode($_SESSION['User']['lastname']);
                                                        }
                                                        ?></b></h3>
                                                <h3 class="user-info-sub-rock"><?php echo base64_decode($_SESSION['User']['organization']); ?></h3>
                                            </div>



                                        </div>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="account-lbl">
                                            <h3>Username :</h3>
                                            <p><?php echo base64_decode($_SESSION['User']['username']); ?></p>
                                        </div>

                                        <?php
                                        $users_licenses_details = users_license_list($_SESSION['User']['id']);
                                        ?>
                                        <div class="account-lbl">
                                            <h3>License :</h3>
                                            <p><input type="hidden" id="licenseid"><span class="hide_encrypt">***************</span><span class="view_encrypt" style="display:none;"><?php echo $users_licenses_details['0']['license']; ?></span>&nbsp;<span class="licenseChk"><label for="checkval" onclick="myFunction()" class="checkid"><i class="fa fa-eye" aria-hidden="true"></i> show<label></span></p>
                                                            </div>
                                                            <div class="account-lbl">
                                                                <h3>Expiration :</h3>
                                                                <p><?php
                                                                    $_SESSION['User']['license'] = $users_licenses_details['0']['license'];
                                                                    if ($users_licenses_details['0']['days_left'] == 'inactive') {
                                                                        echo '(Inactive)';
                                                                    } else if ($users_licenses_details['0']['days_left'] == 'expired') {
                                                                        echo '(Expired)';
                                                                    } else if ($users_licenses_details['0']['expires'] == 'lifetime') {
                                                                        echo '(Lifetime)';
                                                                    } else {
                                                                        echo '(' . $users_licenses_details['0']['days_left'] . ' days remaining)';
                                                                    }
                                                                    ?>
                                                                </p>
                                                            </div>

                                                            <div class="account-lbl account-btn">
                                                                <form action="" method="POST" id="update_license_frm" class="margin-bottom">
                                                                    <input type="hidden" value="<?php echo count($students_list); ?>" id="number_of_students" />
                                                                    <h3>Update License :</h3>
                                                                    <div class="form-group">
                                                                        <input type="text" name="license" required="" id="edd_license" class="form-control" placeholder="License" autocomplete="off" aria-label="License field">
                                                                        <span id="update_lic_spinner" style="display: none; font-size: 6px;" ><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <input type="button" value="Activate New License" name="Submit" class="dashboard-settings-btn btn-block" id="update-license-btn" />
                                                                        <input type="hidden" value="Activate New License" name="Submit" />
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="account-lbl account-btn">
                                                                    <form action="" method="POST" id="update_timezone_frm" class="margin-bottom">
                                                                        <h3>Select Timezone :</h3>
                                                                        <p>
                                                                            <select name="timezone" id="timezone_setting" class="form-control">
                                                                                <option value="">Select Timezone</option>
                                                                                <?php
                                                                                $allTimezone = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                                                                                if (!empty($allTimezone)) {
                                                                                    foreach ($allTimezone as $key => $val) {
                                                                                        $selected = "";
                                                                                        if (!empty($timezoneName) && $timezoneName == $val) {
                                                                                            $selected = "selected";
                                                                                        }
                                                                                        echo "<option value=" . $val . " " . $selected . ">" . toUtcOffset($val) . ' ' . $val . "</option>";
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </p>
                                                                        <div class="form-group" style="margin-top:10px;">
                                                                            <input type="submit" value="Update Timezone" name="Submit" class="dashboard-settings-btn btn-block" />
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>

                                                            </div>
                                                            <div class="row">

                                                                <div class="space-margin-bottom-50"></div>

                                                            </div>                

                                                            </div>

                                                            </div>
                                                            </div>
                                                            </section>
                                                            </div>

                                                            <?php include "config/footer.php"; ?>

                                                            <!-- Control Sidebar -->
                                                            <?php include "config/setting.php"; ?>
                                                            <!-- /.control-sidebar -->
                                                            <!-- Add the sidebar's background. This div must be placed
                                                                 immediately after the control sidebar -->
                                                            <div class="control-sidebar-bg"></div>

                                                            </div><!-- ./wrapper -->

<script>
    function myFunction() {
        var x = document.getElementById("licenseid");
        if (x.type === "hidden") {
            x.type = "text";
            $('#licenseid').hide();
            $('.checkid').html('<i class="fa fa-eye-slash" aria-hidden="true"></i> Hide');
            $('.view_encrypt').show();
            $('.hide_encrypt').hide();
        } else {
            x.type = "hidden";
            $('.checkid').html('<i class="fa fa-eye" aria-hidden="true"></i> Show');
            $('.hide_encrypt').show();
            $('.view_encrypt').hide();
        }
    }
        
</script>
<!-- jQuery 2.1.4 -->
<script src="<?php echo ADMIN_URL ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="<?php echo ADMIN_URL ?>bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo ADMIN_URL ?>plugins/fastclick/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo ADMIN_URL ?>dist/js/app.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo ADMIN_URL ?>plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo ADMIN_URL ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo ADMIN_URL ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo ADMIN_URL ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="<?php echo ADMIN_URL ?>plugins/chartjs/Chart.min.js"></script>

<script>
    $(document).on('click','#update-license-btn',function(){
        
        var license_key = $('#edd_license').val().trim();
        var number_of_students = $('#number_of_students').val();
        var msg = '';
        
        if(license_key){
            
            $.ajax({
                url: '<?php echo ADMIN_URL; ?>common.php',
                type: 'POST',
                dataType: 'json',
                data:{licenseCheck:'license_check',license_key:license_key},
                beforeSend: function () {
                    $('#update_lic_spinner').show();
                },
                success: function (result) {
                    if(result.license_status == 'valid'){
                        
                        var update_licenseLimit = parseInt(result.license_limit) - 1;
                        
                        if(parseInt(result.license_limit) > 0 && parseInt(update_licenseLimit) < parseInt(number_of_students)){
                            var get_diff = parseInt(number_of_students) - parseInt(result.license_limit);
                            if(get_diff > 0){
                                msg = 'Not enough seats on this license. You have '+number_of_students+' students and this license has '+result.license_limit+' seats. Please delete '+get_diff+' students before applying the license.';
                            }else{
                                $('#update_license_frm').submit();
                            }
                        }else{
                            $('#update_license_frm').submit();
                        }
                    }else{
                        msg = 'Your license key is '+result.license_status;
                    }
                    
                    if(msg){
                        var erroMassage = '<div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">\n\
                                            <h4><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                            '+msg+'\n\
                                        </div>';
                        $('.errorView').html(erroMassage);
                    }
                },
                complete: function () {
                    $('#update_lic_spinner').hide();
                }
            });
        }
    });
</script>

</body>
</html>
