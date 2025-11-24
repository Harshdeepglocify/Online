<?php include "config/config.php"; ?>
<?php
if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}
/* Expire login  */
if(!empty($_SESSION['User']['is_expired'])){
    if($_SESSION['User']['is_admin'] ==1){
        header("Location: " . ADMIN_URL . 'student/student-list-admin.php');
    } else {
        header("Location: " . ADMIN_URL . 'student/student-list.php');
    }
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
                        <div class="col-md-12">
                            <?php
                            
                            //Argument for data
                            // $args = array(
                            //     'teacher_code' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
                            //     'role' => 'student',
                            // );

                            $args = array(
                                'teacher_code' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
                                'role' => 'student',
                            );
                            
                            if(isset($_SESSION['User']['is_admin']) && !empty($_SESSION['User']['is_admin'])){
                                $args = array(
                                    'license' => !empty($_SESSION['User']['license']) ? $_SESSION['User']['license'] : '',
                                    'role' => 'student',
                                );
                            } 
                            //echo "<pre>";print_r($_SESSION);die;
                            $start_date = date('Y-m-d', strtotime('-6 days'));
                            $end_date = date('Y-m-d');
                            $current_time = strtotime(date('Y-m-d'));
                            $start_date = date('Y-m-d', strtotime('Last Monday', $current_time));
                            $end_date = date('Y-m-d', strtotime('Next Sunday', $current_time));
                            //check the current day
                            if (date('D') != 'Mon') {
                                //take the last monday
                                $start_date = date('Y-m-d', strtotime('Last Monday', $current_time));
                            } else {
                                $start_date = date('Y-m-d');
                            }
                            //always next saturday
                            if (date('D') != 'Sat') {
                                $end_date = date('Y-m-d', strtotime('Next Sunday', $current_time));
                            } else {
                                $end_date = date('Y-m-d');
                            }
                            /*$start_date = getTimezonewiseDate($start_date);
                            $end_date = getTimezonewiseDate($end_date);*/

                            $student_data = get_users($args);

                            if (!empty($student_data)) {
                                echo '<div class="row">';
                                foreach ($student_data as $k => $student) {
                                    if ($k % 2 == 0) {
                                        echo '</div>';
                                        echo '<div class="row">';
                                    }
                                    // now commented - $setting_data = get_settings_details(array('id' => $student['id']));

                                    //$log_data = getUserLog($student['id'], 3);
                                    $log_data = getUserLog($student['id'], 3, 'DESC', false, false, date('Y-m-d', strtotime('-6 days')), date('Y-m-d'));
                                    $weekly_log_data = ''; //getUserActivityLog($student['id'], 1, $start_date, $end_date, '1');
                                    $weekly_log_hour = ''; //getUserActivityLog($student['id'], 1, $start_date, $end_date);
                                    $today_log_hour = ''; //getUserActivityLog($student['id'], 1);

                                    // now commented -$name = !empty($setting_data['2']) ? $setting_data['2'] : '';
                                    $username = !empty($student['username']) ? $student['username'] : '';
                                    $first_name = !empty($student['firstname']) ? base64_decode($student['firstname']) : '';
                                    // now commented - $current_status = !empty($setting_data['4']) ? $setting_data['4'] : '';

                                    /* $typio_complete = getUserLog($student['id'], '', '', 'Typio-OL');
                                      $typio_total = !empty($typio_complete) ? count($typio_complete) : 0;

                                      $decks_complete = getUserLog($student['id'], '', '', 'Quick-Cards-OL');
                                      $decks_total = !empty($decks_complete) ? count($decks_complete) : 0;

                                      $arcade_complete = getUserLog($student['id'], '', '', 'Arcade-OL');
                                      $arcade_total = !empty($arcade_complete) ? count($arcade_complete) : 0; */
                                    $todayHour = $todayMin = '';
                                    $class = 'no-week-activity';
                                    /* now commented - 
                                    $status_arr = explode('@', $current_status);
                                    $last_task = "";
                                    $one_hours = 0;
                                    
                                    if (isset($status_arr[1])) {

                                        $one_hours = time() - $status_arr[1];

                                        $online = floor($one_hours / (60));

                                        if ($online <= 5) {

                                            $last_task = $status_arr[0];
                                        }
                                    }
                                    $class = 'no-week-activity';
                                    if (!empty($last_task)) {
                                        $class = 'week-activity';
                                    }*/
                                    ?>
                                    <div class="col-md-6">
                                        <div class="box box-warning box-solid">
                                            <div class="box-header with-border student-profile-bar <?php echo $class; ?>">
                                                <h2 class="box-title profile-title" style="font-weight:600 !important;font-size:26px !important"><?php echo $first_name; ?> (<?php echo base64_decode($username); ?>)
                                                    <!-- <a class="std_href"  href="<?php echo ADMIN_URL . 'student/student-overview.php?student=' . $student['id']; ?>">Overview </a> --> </h2>

                                                <div class="box-tools pull-right">
                                                   <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus" aria-hidden="true"></i></button> -->
                                                </div>
                                            </div>

                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="box-footer box-comments border-0">
                                                            <?php
                                                            if ($weekly_log_hour) {
                                                                ?>
                                                                <div class="today-main-wrap">
                                                                    <div class="column-wrap today-column-wrap">
                                                                        <p>Today</p>
                                                                        <h3><?php echo!empty($today_log_hour) ? $today_log_hour : '0 min'; ?></h3>
                                                                    </div>
                                                                    <div class="column-wrap week-column-wrap">
                                                                        <?php if (!empty($weekly_log_hour)) { ?>
                                                                            <p>This week</p>
                                                                            <h3><?php echo $weekly_log_hour; ?></h3>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="box-comment">
                                                                    <?php if (!empty($last_task)) { ?>
                                                                        <!-- User image -->
                                                                        <div style="display: table-row;">
                                                                            <div style="display: table-cell;vertical-align: middle;"><i class="fa fa-clock-o fa-2x" style=" " aria-hidden="true"></i></div>
                                                                            <div style="display: table-cell; vertical-align:middle;word-break: break-all;">
                                                                                <div class="comment-text">

                                                                                    <span class="dashboard-span-time"><?php echo 'Active Now'; ?><br/></span>

                                                                                    <span class="dashboard-span-detail word-break-normal"><?php echo $last_task; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                                <?php
                                                            } else {
                                                                if (!empty($log_data)) {

                                                                    foreach ($log_data as $log) {

                                                                        echo '<div class="box-comment">
                                    <!-- User image -->
    <div style="display: table-row;">
       <div style="display: table-cell;vertical-align: middle;"><i class="fa fa-clock-o fa-2x" style=" " aria-hidden="true"></i></div>
        <div style="display: table-cell; vertical-align:middle;word-break: break-all;">
                                   
                                    <div class="comment-text">
                                        <span class="dashboard-span-time">' . $log['time_ago'] . '<br/></span>
                                        <span class="dashboard-span-detail word-break-normal">' . $log['data'] . '</span>
    </div>
    </div>
                                    </div>
                                  </div>';
                                                                    }
                                                                } else {
                                                                    echo 'No recent activity.';
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <a class="accessibyte-link user-profile-overview dashboard-settings-btn"  href="<?php echo ADMIN_URL . 'student/student-overview.php?student=' . $student['id']; ?>">View <?php echo $first_name; ?>'s full profile</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                   
                                }
                                echo "</div>";
                            } else {
                                ?>
                                <div class="row"><div style="margin-top: 20%;text-align: center;font-size: 30px;">You have no students <br/><a style="text-decoration:underline;" href="<?php echo ADMIN_URL . "student/add-new-student.php" ?>">Create your first student</a></div></div>

                            <?php } ?>



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
