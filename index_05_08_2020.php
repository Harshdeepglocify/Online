<?php include "config/config.php"; ?>
<?php

if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
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
                        <!--  -->
                        <div class="col-md-9">
                            <?php
                            //Argument for data
                            $args = array(
                                'teacher_code' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
                                'role' => 'student',
                            );

                            $student_data = get_users($args);

                            echo '<div>';

                            if (!empty($student_data)) {

                                foreach ($student_data as $student) {

                                    $setting_data = get_settings_details(array('id' => $student['id']));
                                    $log_data = getUserLog($student['id'], 3);

                                    $name = !empty($setting_data['2']) ? $setting_data['2'] : '';
                                    $username = !empty($student['username']) ? $student['username'] : '';
                                    $first_name = !empty($student['firstname']) ? base64_decode($student['firstname']) : '';
                                    $current_status = !empty($setting_data['4']) ? $setting_data['4'] : '';

                                    $typio_complete = getUserLog($student['id'], '', '', 'Typio-OL');
                                    $typio_total = !empty($typio_complete) ? count($typio_complete) : 0;

                                    $decks_complete = getUserLog($student['id'], '', '', 'Quick-Cards-OL');
                                    $decks_total = !empty($decks_complete) ? count($decks_complete) : 0;

                                    $arcade_complete = getUserLog($student['id'], '', '', 'Arcade-OL');
                                    $arcade_total = !empty($arcade_complete) ? count($arcade_complete) : 0;

                                    $status_arr = explode('@', $current_status);
                                    $last_task = "";
                                    $one_hours = 0;
                                    if (isset($status_arr[1])) {
                                        $one_hours = time() - $status_arr[1];
                                        if ($one_hours <= 3600) {
                                            $last_task = $status_arr[0];
                                        }
                                    }
                                    ?>
                                    <div class="box box-warning box-solid">
                                        <div class="box-header with-border student-profile-bar">
                                            <h2 class="box-title profile-title" font-weight="600 !important" font-size="20px !important"><?php echo $first_name; ?> (<?php echo base64_decode($username); ?>)
                                                <!-- <a class="std_href"  href="<?php echo ADMIN_URL . 'student/student-overview.php?student=' . $student['id']; ?>">Overview </a> --> </h2>
                                            <h3 class="box-title"> <?php echo $last_task; ?></h3> 
                                            <div class="box-tools pull-right">
                                               <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus" aria-hidden="true"></i></button> -->
                                            </div>
                                        </div>

                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="box-footer box-comments">
                                                        <?php
                                                        if (!empty($log_data)) {

                                                            foreach ($log_data as $log) {

                                                                echo '<div class="box-comment">
                                <!-- User image -->
<div style="display: table-row;">
   <div style="display: table-cell;vertical-align: middle;"><i class="fa fa-clock-o fa-2x" style=" " aria-hidden="true"></i></div>
    <div style="display: table-cell; vertical-align:middle;word-break: break-all;">
                               <!-- <img class="img-circle img-sm" src="' . ADMIN_URL . 'img/clock.png" alt="clock icon" aria-hidden="true"> -->
                                <div class="comment-text">
                                    <!-- <span class="username"> -->				      
                                      <span class="dashboard-span-time">' . $log['time_ago'] . '<br/></span>
                                   <!-- </span> --><!-- /.username -->
                                    <!-- <a class="username-name-notification-init" href="' . ADMIN_URL . 'student/student-overview.php?student=' . $log['id'] . '">' . ucfirst(base64_decode($log['firstname'])) . '</a> -->
                                    <span class="dashboard-span-detail word-break-normal">' . $log['data'] . '</span>
</div>
</div>
                                </div>
                              </div>';
                                                            }
                                                        } else {
                                                            echo 'No recent activity.';
                                                        }
                                                        ?>
                                                    </div>
                                                	<a class="accessibyte-link user-profile-overview"  href="<?php echo ADMIN_URL . 'student/student-overview.php?student=' . $student['id']; ?>"><?php echo $first_name; ?>'s full profile...</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="box-body no-padding">
                                                        <ul class="users-list clearfix users-list-init">

                                                            <?php
                                                            $users_licenses = users_license_list($student['id']);
                                                            $value_status = '';
                                                            if (!empty($users_licenses)) {
                                                                foreach ($users_licenses as $value) {


                                                                    $val = explode("-", trim($value['license']));
                                                                    if ($val[0] == 'TYO') {
                                                                        // $icon_name = 'typio';
                                                                        $iconaarr = array(
                                                                            '0' => 'typio',
                                                                        );
                                                                    } elseif ($val[0] == 'PRO') {
                                                                        //$icon_name = 'propack';
                                                                        $iconaarr = array(
                                                                            '0' => 'propack',
                                                                        );
                                                                    } elseif ($val[0] == 'BDL') {                                                                         
                                                                        $iconaarr = array(
                                                                            '0' => 'typio',
                                                                            '1' => 'propack',
                                                                            '2' => 'quickcards',
                                                                            '3' => 'arcade',
                                                                        );
                                                                    } if ($val[0] == 'QCO') {
                                                                        $iconaarr = array(
                                                                            '0' => 'quickcards',
                                                                        );
                                                                        //$icon_name = 'quickcards';
                                                                    }if ($val[0] == 'AAO') {
                                                                        $iconaarr = array(
                                                                            '0' => 'arcade',
                                                                        );
                                                                        // $icon_name = 'arcade';
                                                                    }

                                                                    if (!empty($iconaarr)) {
                                                                        ?>
																		<?php foreach ($iconaarr as $iconval) { ?>
                                                                        <li> 
                                                                            <img src="<?php echo ADMIN_URL . 'img/' . $iconval . '-icon.png'; ?>" alt="Typio">
                                                                            <span class="users-list-date <?php echo $class; ?> "><?php echo !empty($msg) ? $msg : ''; ?></span>
                                                                        </li>
																		<?php } ?>
                                                                        <?php
                                                                    }
                                                                }
                                                            } else {
                                                                $msg = "Not Active";
                                                                $class = 'users-list-expires-init';
                                                            }

//                                                            echo '<pre>';
//                                                            print_r($users_licenses);
//                                                            exit;
//                                                            $status = '';
//                                                            $status = checkLicenseStatus($student['id'], 5);
//                                                            if ($status['status'] == 'active') {
//                                                                $class = 'users-list-active-init';
//                                                                $msg = "Active";
//                                                            } elseif ($status['status'] == 'expires') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "Expires in " . $status['days'] . " days";
//                                                            } elseif ($status['status'] == 'none') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "Not Active";
//                                                            } elseif ($status['status'] == 'invalid') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "License invalid";
//                                                            } elseif ($status['status'] == 'expired') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "Expired";
//                                                            }
//                                                            if (!empty($status['status']) && ($status['status'] == "active" || $status['status'] == "expires" )) {
                                                            ?>
                                                            <!--                                                                <li> 
                                                                                                                                <img src="<?php // echo ADMIN_URL . 'img/typio-icon.png';  ?>" alt="Typio Logo">
                                                                                                                                <span class="users-list-date <?php // echo $class;   ?> "><?php // echo $msg;   ?></span>
                                                                                                                            </li>-->
                                                            <?php // }  ?>    

                                                            <?php
//                                                            $status = '';
//                                                            $status = checkLicenseStatus($student['id'], 8);
//                                                            if ($status['status'] == 'active') {
//                                                                $class = 'users-list-active-init';
//                                                                $msg = "Active";
//                                                            } elseif ($status['status'] == 'expires') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "Expires in " . $status['days'] . " days";
//                                                            } elseif ($status['status'] == 'none') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "Not Active";
//                                                            } elseif ($status['status'] == 'invalid') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "License invalid";
//                                                            }
//                                                            if (!empty($status['status']) && ($status['status'] == "active" || $status['status'] == "expires" )) {
                                                            ?>
                                                            <!--                                                                <li> 
                                                                                                                                <img src="<?php // echo ADMIN_URL . 'img/quickcards-icon.png';  ?>" alt="Quick Cards Logo">
                                                                                                                                <span class="users-list-date <?php // echo $class;  ?> "><?php // echo $msg;  ?></span>
                                                                                                                            </li>-->
                                                            <?php // } ?>   

                                                            <?php
//                                                            $status = '';
//                                                            $status = checkLicenseStatus($student['id'], 6);
//                                                            if ($status['status'] == 'active') {
//                                                                $class = 'users-list-active-init';
//                                                                $msg = "Active";
//                                                            } elseif ($status['status'] == 'expires') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "Expires in " . $status['days'] . " days";
//                                                            } elseif ($status['status'] == 'none') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "Not Active";
//                                                            } elseif ($status['status'] == 'invalid') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "License invalid";
//                                                            }
//                                                            if (!empty($status['status']) && ( $status['status'] == "active" || $status['status'] == "expires" )) {
                                                            ?>
                                                            <!--                                                                <li> 
                                                                                                                                <img src="<?php // echo ADMIN_URL . 'img/propack-icon.png';  ?>" alt="ProPack Logo">
                                                                                                                                <span class="users-list-date <?php // echo $class;  ?> "><?php // echo $msg;  ?></span>
                                                                                                                            </li>-->
                                                            <?php // } ?>   

                                                            <?php
//                                                            $status = '';
//                                                            $status = checkLicenseStatus($student['id'], 7);
//                                                            if ($status['status'] == 'active') {
//                                                                $class = 'users-list-active-init';
//                                                                $msg = "Active";
//                                                            } elseif ($status['status'] == 'expires') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "Expires in " . $status['days'] . " days";
//                                                            } elseif ($status['status'] == 'none') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "Not Active";
//                                                            } elseif ($status['status'] == 'invalid') {
//                                                                $class = 'users-list-expires-init';
//                                                                $msg = "License invalid";
//                                                            }
//                                                            if (!empty($status['status']) && ( $status['status'] == "active" || $status['status'] == "expires" )) {
                                                            ?>
                                                            <!--                                                                <li> 
                                                                                                                                <img src="<?php // echo ADMIN_URL . 'img/arcade-icon.png';  ?>" alt="Accessibyte Arcade Logo">
                                                                                                                                <span class="users-list-date <?php // echo $class;  ?> "><?php // echo $msg;  ?></span>
                                                                                                                            </li>-->
                                                            <?php // } ?>  

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div style="margin-top: 20%;text-align: center;font-size: 30px;">You have no students <br/><a style="text-decoration:underline;" href="<?php echo ADMIN_URL . "student/add-new-student.php"?>">Create your first student</a></div>
                            <?php } ?>            
                        </div>

                    </div>

                    <!-- side-content -->
                    <div class="col-md-3">            

                        <div class="box-footer box-comments">
                            <div class="dashboard-all-activity">
                                <h3>All Student Activity</h3>
                            </div>
                            <?php
                            $teacher_code = !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '';
                            $all_user_notification = getUserLog('', 15, 'DESC', false, $teacher_code);

                            if (!empty($all_user_notification)) {

                                foreach ($all_user_notification as $notification) {

                                    echo '<div class="box-comment">
                      <!-- User image -->
<div style="display: table-row;">
   <div style="display: table-cell;vertical-align: middle;" class="activity-clock-icon"><i class="fa fa-clock-o fa-2x" style=" " aria-hidden="true"></i></div>
    <div style="display: table-cell; vertical-align:middle;word-break: break-all;">
<!--                      <img class="img-circle img-sm" src="' . ADMIN_URL . 'img/clock.png" alt="Timestamp icon of clock"> -->
<!--                      <i class="fa fa-dashboard fa-2x"></i> -->
                      <div class="comment-text">
                          <span class="username">
                            <span class="dashboard-span-time">' . $notification['time_ago'] . '<br/></span>
                          </span><!-- /.username -->
                          <span class="dashboard-span-detail" "' . $notification['id'] . '">' . ucfirst(base64_decode($notification['firstname'])) . '</span>
                          <span class="dashboard-span-detail word-break-normal">' . $notification['data'] . '</span>
</div>
</div>
                      </div>
                    </div>';
                                }
                            } else {
                                echo '<div class="box-comment">
                        You have no Notifications
                    </div>';
                            }
                            ?>

                            <!-- /.box-comment -->
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

</body>
</html>
