<?php
include "../config/config.php";
include "../helper/student_helper.php";

if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}
if (isset($_POST['ActiveLicenseSubmit']) && !empty($_POST['license'])) {
    $license = $_POST['license'];
    $user_id = $_POST['user_id'];
    $result = activate_license($license, $user_id, false);

    // echo "<pre>";
    // print_r($result);
    // exit();
    if ($result) {

        $val = explode("-", $license);
        if ($val[0] == "TYO") {
            query("REPLACE INTO settings VALUES( '{$user_id}', '5', '{$license}')");
        } else if ($val[0] == "BDL") {
            query("REPLACE INTO settings VALUES( '{$user_id}', '5', '{$license}')");
        } else if ($val[0] == "PRO") {
            query("REPLACE INTO settings VALUES( '{$user_id}', '6', '{$license}')");
        } else if ($val[0] == "AAO") {
            query("REPLACE INTO settings VALUES( '{$user_id}', '7', '{$license}')");
        } else if ($val[0] == "QCO") {
            query("REPLACE INTO settings VALUES( '{$user_id}', '8', '{$license}')");
        }

        // Update License in User table
        update_query('user', 'id = "' . $user_id . '" ', array('license' => $license));

        get_license_details($license, $user_id);

        $_SESSION['error']['message'] = "License activated successfully";
        $_SESSION['error']['color'] = "success";
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
        <script>
            var ADMIN_URL = '<?php echo ADMIN_URL; ?>';
        </script>
        <link href='https://fonts.googleapis.com/css?family=Roboto Slab' rel='stylesheet'>

        <style type="text/css">
            #alert-success {
                font-family: 'Roboto Slab';
                background-color: #000 !important;
                border-color: #000 !important;
                 color: #fff !important;
            }
            #alert-success h4{
                font-family: 'Roboto Slab';
                font-weight: normal;
            }
            #alert-success a.close {
                color: #FFFFFF;
                opacity: 1;
                text-decoration: none;
            }

        </style>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <?php include "../config/top-header.php"; ?>

            <!-- Left side column. contains the logo and sidebar -->
            <?php include "../config/left-sidebar.php"; ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <?php
                if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                    ?>
                                            <!--<div style="font-family: Roboto Slab, serif; background-color: #000;" class="alert alert-<?php // echo $_SESSION['error']['color']        ?> alert-dismissable fade in">-->
                    <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" aria-live="assertive" class="alert  alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?php echo $_SESSION['error']['message'] ?>
                    </div>
                    <?php
                } unset($_SESSION['error']);

                //$users = get_user_by_role('student');             
                $args = array(
                    'teacher_code' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
                    'role' => 'student',
                );
                $users = get_users($args);
                ?>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <?php
                        if ($_SESSION['User']['id'] == 63 || $_SESSION['User']['id'] == 77) {          // user-id = 63 is Joe Teacher, user-id = 77 is Demo Teacher
                            ?>
                            <div class="col-lg-12" style="margin-bottom:10px;">
                                <input type="button" class="dashboard-settings-btn" id="sync_lic" value="Sync Licenses" />
                                <span><i class="fa fa-spinner fa-spin" id="icon_loader" style="display:none;" aria-hidden="true"></i></span>
                                <span style="margin-left:10px;" id="sync_lic_msg"></span>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="col-lg-12">
                            <div class="box-body panel">
                                <div class="alert-msg-response">
                                    <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert alert-dismissible fade in">
                                </div>
                                </div>
                                <header class="panel-heading">
                                    <!-- <h4><b>Students</b></h4>
                                    <div class="student-list-head-icon">
                                                                        <img src="<?php //echo ADMIN_URL.'img/user-one.png';        ?>" class="head-typio">
                                                                        <img src="<?php // echo ADMIN_URL.'img/user-two.png';        ?>" class="head-quick-card">
                                                                        <img src="<?php //echo ADMIN_URL.'img/user-three.png';        ?>" class="head-arcade">
                                                                    </div> -->
                                </header>
                                <div class="table-responsive">
                                    <table class="table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs" border="2">
                                        <thead>
                                            <tr>
                                                <td><h4 class="Students-title-cs"><b>Students</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Licenses</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Action</b></h4></td> 
                                            </tr>
                                        </thead> 
                                        <tbody> 
                                            <?php
                                            $license_list = array();
                                            if (isset($users) && !empty($users)) {
                                                foreach ($users as $user) {
                                                    echo '<tr>';
                                                    echo '<td><b>' . ucfirst(base64_decode($user['firstname'])) . '</b> (' . $user['username'] . ')<br>
  <a href="' . ADMIN_URL . 'student/student-overview.php?student=' . $user['id'] . '" aria-label="Overview for ' . ucfirst(base64_decode($user['firstname'])) .' " class="accessibyte-link">Overview</a></td><td>';

//                                                $TYO_license = checkLicenseStatus($user['id'], 5);
//                                                $PRO_license = checkLicenseStatus($user['id'], 6);
//                                                $QCO_license = checkLicenseStatus($user['id'], 8);
//                                                $ACR_license = checkLicenseStatus($user['id'], 7);
//
//// Typio-------------
//                                                if (!empty($TYO_license['status']) && ($TYO_license['status'] == "active" || $TYO_license['status'] == "expires" )) {
//                                                    echo "<div class='studentLicenseTable'><span class='licenseName'>Typio:</span><span class='licenseKey'>" . $TYO_license['license'] . "</span>
//    <span class='licenseDays'> (" . $TYO_license['days'] . " days left)</span></div>";
//                                                    $license_list[$TYO_license['license']] = $TYO_license['days'] . ' days left';
//                                                }
//
//// Pro Pack------------- 
//                                                if (!empty($PRO_license['status']) && ($PRO_license['status'] == "active" || $PRO_license['status'] == "expires" )) {
//                                                    echo "<div class='studentLicenseTable'><span class='licenseName'>ProPack:</span><span class='licenseKey'>" . $PRO_license['license'] . "</span>
//    <span class='licenseDays'>(" . $PRO_license['days'] . " days left)</span></div>";
//                                                    $license_list[$PRO_license['license']] = $PRO_license['days'] . ' days left';
//                                                }
//
//// Quick Cards-------------
//                                                if (!empty($QCO_license['status']) && ( $QCO_license['status'] == "active" || $QCO_license['status'] == "expires" )) {
//                                                    echo "<div class='studentLicenseTable'><span class='licenseName'>Quick Cards:</span><span class='licenseKey'>" . $QCO_license['license'] . "</span>
//    <span class='licenseDays'>(" . $QCO_license['days'] . " days left)</span></div>";
//                                                    $license_list[$QCO_license['license']] = $QCO_license['days'] . ' days left';
//                                                }
//
//// Accessibyte Arcade-------------
//                                                if (!empty($ACR_license['status']) && ( $ACR_license['status'] == "active" || $ACR_license['status'] == "expires" )) {
//                                                    echo "<div class='studentLicenseTable'><span class='licenseName'>Accessibyte Arcade:</span><span class='licenseKey'>" . $ACR_license['license'] . "</span>
//    <span class='licenseDays'>(" . $TYO_license['days'] . " days left)</span></div>";
//                                                    $license_list[$TYO_license['license']] = $TYO_license['days'] . ' days left';
//                                                }
                                                    $users_licenses_details = users_license_list($user['id']);

                                                    $licenses_details = '<div class="studentLicenseTable">';
                                                    if (!empty($users_licenses_details)) {
                                                        $cnt = 1;
                                                        foreach ($users_licenses_details as $value) {
                                                            if (count($users_licenses_details) == $cnt) {
                                                                $line_break = '';
                                                            } else {
                                                                $line_break = '</br>';
                                                            }
                                                            $licenses_details .= '<span class="licenseName">' . $value['item_name'] . ':</span><span class="licenseKey">' . $value['license'] . '</span><span class="licenseDays">(' . $value['days_left'] . ' days left)</span>' . $line_break;
                                                            $cnt++;
                                                        }
                                                    }
                                                    $licenses_details .= '</div>';
                                                    echo $licenses_details;
                                                    echo '</td><td>
  <div class="studentAction">
  <span class="studentActionActivate">
  <a href="javascript:void(0)" aria-label="Activate license for ' . ucfirst(base64_decode($user['firstname'])) .' " data-user_id="' . $user['id'] . '"  class="accessibyte-link active-license-modal">Activate License</a></span>';
                                                    echo '<span class="studentActionLogin">
      <a href="javascript:void(0)" data-name="' . ucfirst(base64_decode($user['firstname'])) . '(' . $user['username'] . ')" data-URL="url_' . $user['id'] . '" class="accessibyte-link one-click-login" aria-label="1-Click login for ' . ucfirst(base64_decode($user['firstname'])) .' ">1-Click login</a><div class="click_one_hide"><input type="text" style="opacity: 0;" id="url_' . $user['id'] . '" class="url_copy" value="' . ADMIN_URL . 'login/?UID=' . $user['username'] . '&PW=' . $user['password'] . '"></div></span>';
                                                    echo '<span>
    <a href="' . ADMIN_URL . 'student/student-password.php?student=' . $user['id'] . '" class="accessibyte-link" aria-label="Change Password for ' . ucfirst(base64_decode($user['firstname'])) .' ">Change Password</a></span>';
                                                    echo '<span>
      <a href="javascript:void(0)" data-id="' . $user['id'] . '" data-name="' . ucfirst(base64_decode($user['firstname'])) . '(' . $user['username'] . ')" class="accessibyte-link delete_student" aria-label="Delete ' . ucfirst(base64_decode($user['firstname'])) .' ">Delete</a>
      </span>
      </div>
      </td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="4">You have no students</td></tr>';
                                            }
                                            ?>                    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
								<?php
								$licenseArr =  get_license_details_all($_SESSION['User']['license']);
								if(!empty($licenseArr)){
									$left = isset($licenseArr['activations_left']) ? $licenseArr['activations_left'] : '';
									if(!empty($left) && $left > 0){
									?>
									<a href="<?php echo ADMIN_URL . "student/add-new-student.php"; ?>" class="dashboard-settings-btn">Add Student</a>
								<?php } else {?>
								<a href="javascript:void(0);" class="dashboard-settings-btn" data-toggle="modal" data-target="#more-license-modal">Add Student</a>
								<?php }
								}?>
								</h3>
                            
                        </div>
                    </div>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box-body panel">
                                <header class="panel-heading">
                                    <!--<h4><b>UnAssign Licenses Keys</b></h4>-->

                                </header>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs" border="2">
                                        <thead>
                                            <tr>
                                                <td>
                                                    <h4 class="Students-title-cs1"><b>Program</b></h4>
                                                </td>
                                                <td ><h4 class="Students-title-cs1"><b>Licenses</b></h4></td>
                                                <td style="width: 20%;">
                                                    <h4 class="Students-title-cs1"><b>Expiration</b></h4>
                                                </td>
                                                <td>
                                                    <h4 class="Students-title-cs1"><b>Seats in Use</b></h4>
                                                </td>
    <!--                                            <td>
                                                    <h4 class="Students-title-cs1"><b>Assigned To</b></h4>
                                                </td> -->
                                            </tr>
                                        </thead> 
                                        <tbody> 

                                            <?php
                                            if ($get_unassign_license_keys_query_result->num_rows > 0) {
                                                while ($row = mysqli_fetch_assoc($get_unassign_license_keys_query_result)) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php
                                                            $license_type = $row['license_type'];
                                                            if (!empty($row['license_key'])) {
                                                                $val = explode("-", trim($row['license_key']));
                                                                if (isset($val[0]) && $val[0]) {
                                                                    if ($val[0] == "BDL") {
                                                                        $license_type = 'All Access';
                                                                    }
                                                                }
                                                            }
                                                            echo $license_type;
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
//                                                        $license_details = checkLicenseStatus_FromWordpress($row['license_key']);
//                                                        $today = date_create(date('Y-m-d'));
//                                                        $expires = date_create(date('Y-m-d', strtotime($row['expires'])));
//                                                        $diff = date_diff($today, $expires);
//                                                        echo $row['license_key'] . '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ( ' . $diff->days . ' days left, ' . $row['license_used'] . ' out of ' . $row['license_limit'] . ' activations remaining )';
                                                            echo $row['license_key'];
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $today = date_create(date('Y-m-d'));
                                                            $expires = date_create(date('Y-m-d', strtotime($row['expires'])));
                                                            $diff = date_diff($today, $expires);
                                                            echo $diff->days . ' days';
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $row['license_used'] . '/' . $row['license_limit']; ?>
                                                        </td>
        <!--                                                    <td>
                                                        <?php
//                                                        $username_array = array();
//                                                        if (!empty($row['user_id'])) {
//                                                            $expload_user = explode(',', $row['user_id']);
//                                                            foreach ($expload_user as $user_value) {
//                                                                $username_array[] = get_user_name($user_value);
//                                                            }
//                                                        }
//                                                        $usrename = '';
//                                                        if (!empty($username_array)) {
//                                                            $usrename = str_replace(',', ', ', implode(',', $username_array));
//                                                        }
//                                                        echo $usrename;
                                                        ?>
                                                        </td>-->
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="4">No data found.</td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button  class="dashboard-settings-btn btn btn-info" id="newlicense_popup" data-toggle="modal" data-target="#add-new-license-modal" >Assign License</button>
                        </div>
                    </div>
                </section>  
            </div>
            <?php include "../config/footer.php"; ?>

            <!-- Control Sidebar -->
            <?php include "../config/setting.php"; ?>

            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>

        </div><!-- ./wrapper -->

        <div class="modal fade" id="active-license-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Add License</h4>
                    </div>
                    <form id="active-license-form" name="active-license-form" method="POST">
                        <div class="modal-body">	            		
                            <input type="hidden" name="user_id" id="user_id" value="">
                            <div class="form-group">
                                <input class="form-control" type="text" name="license" id="edd_license" autofocus>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="ActiveLicenseSubmit" class="dashboard-settings-btn btn btn-primary edit-lessons-btn">Add License</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
		<div class="modal fade" id="more-license-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>      
						<h4 class="modal-title">Additional seat Request</h4>						
                    </div>
					<div class="modal-body">	            		
						<input type="hidden" name="user_id" id="user_id" value="">
						<p>All seats are in use. To add more students you’ll need to delete a current student or purchase additional student seats.</p>
					</div>
					<div class="modal-footer">
					   <a class="dashboard-settings-btn btn btn-primary" href="mailto:sales@accessibyte.com?subject=Additional student seats for [<?php echo $_SESSION['User']['license']; ?>]">Contact us for additional seats</a>
					</div>
                    
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- ADD NEW LICENSE KEY -->
        <div class="modal fade" id="add-new-license-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Assign License</h4>
                    </div>
                    <form id="add-new-license-form" name="add-new-license-form" method="POST">
                        <div class="modal-body">	            		
                            <!--<input type="hidden" name="login_user_id" id="login_user_id" value="">-->
                            <div class="form-group">
                                <input class="form-control" type="text" name="add_new_license" id="add_new_license" autofocus>
                                <div class="fa fa-spinner fa-spin spinner_loader" style="display: none" aria-hidden="true"></div>
                                <span id="license_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" name="AddNewLicenseSubmit" class="btn btn-primary add-new-license-btn">Assign License</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- ADD NEW LICENSE KEY END -->



        <!-- jQuery 2.1.4 -->
        <script src="<?php echo ADMIN_URL ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo ADMIN_URL ?>bootstrap/js/bootstrap.min.js"></script>
        <!-- FastClick -->
        <script src="<?php echo ADMIN_URL ?>plugins/fastclick/fastclick.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo ADMIN_URL ?>dist/js/app.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/boot-box/bootbox.min.js"></script>
        <script src="<?php echo ADMIN_URL; ?>plugins/jQuery-validate/jquery.validate.js"></script>
        <script src="<?php echo ADMIN_URL ?>dist/js/custom-student.js"></script>

        <!-- SlimScroll 1.3.0 -->
        <script src="<?php echo ADMIN_URL ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>

        <script>
            $(document).on('click', '#sync_lic', function () {
                $.ajax({
                    url: '<?php echo ADMIN_URL; ?>cron/fetch_licenses.php',
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function () {
                        $('#sync_lic').attr('disabled', 'disabled');
                        $('#icon_loader').show();
                        $('#sync_lic_msg').html('Synchronization is in progress...');

                    },
                    success: function (result) {
                        location.reload();
                    },
                    complete: function () {
                        $('#icon_loader').hide();
                        $('#sync_lic_msg').html('<span style="color:green;">License Syncronization Successfully.</span>');
                        $('#sync_lic').removeAttr('disabled', 'disabled');
                    }

                });
            });

            $(document).on('click', '#newlicense_popup', function () {
                $('#add_new_license').val('');
                setTimeout(function () {
                    $('#add_new_license').focus();
                }, 500);

            });
        </script>



    </body>
</html>