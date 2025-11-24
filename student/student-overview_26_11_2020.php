<?php
include "../config/config-student.php";
include "../config/config-student-arcade.php";
global $con, $studentid;

if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}

$studentid = !empty($_GET['student']) ? $_GET['student'] : '';
$userLast3Activity = getUserLog($studentid, 3);

$TYO_license = checkLicenseStatus($studentid, 5);
$PRO_license = checkLicenseStatus($studentid, 6);

$args = array('user_id' => $studentid);
$student_data = get_users($args);

$expload_license_check = '';
if ($studentid) {
    $expload_license_check = explode('-', $student_data[0]['license']);
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
            var ADMIN_URL = '<?php echo ADMIN_URL; ?>';			var WEB_PATH = '<?php echo WEB_PATH; ?>';
        </script>
        <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>dist/css/print.css" type="text/css" media="print" />
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php include "../config/top-header.php"; ?>
            <!-- Left side column. contains the logo and sidebar -->
            <?php include "../config/left-sidebar.php"; ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <?php
                $teacher_code = !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '';
                if (is_user_belong_to_teacher($_GET['student'], $teacher_code)) {
                    ?>                
                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-12">
                                <div class="student-title-main-init overview">
                                    <h1><?php 
									$username = base64_decode($student_data[0]['username']);
									echo!empty($student_data[0]['firstname']) ? base64_decode($student_data[0]['firstname']) : ''; ?><?php echo!empty($username) ? "'s profile (" . $username . ')' : ''; ?>

                                        <div class="editInline" data-name="variable" data-target="info">
                                            <div id="infodiv">
                                                <span id="info">
                                                    <?php echo getHeaderInfo($studentid, 3) ?>
                                                </span>
                                            </div>
                                        </div>
                                </div></h1>
                            </div>
                            <!-- chart one -->  
                            <div class="col-md-12">
                                <div class="chart-one-main-init">
                                    <!-- start Tab -->
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#overview" data-toggle="tab" aria-expanded="true">Overview</a></li>
                                            <?php
											
											if(!empty($expload_license_check)){
												if (!empty($TYO_license['status']) && ($expload_license_check[0] == 'BDL' || $expload_license_check[0] == 'TCHP' || $expload_license_check[0] == 'TCH' || $expload_license_check[0] == 'TYO') && ( $TYO_license['status'] == "inactive" || $TYO_license['status'] == "active" || $TYO_license['status'] == "expired" || $TYO_license['status'] == "expires" )) {
													echo '<li class=""><a id="typioTab" href="#typio" data-toggle="tab" aria-expanded="false">Typio</a></li>';
												}

												if (!empty($PRO_license['status']) && ($expload_license_check[0] == 'BDL' || $expload_license_check[0] == 'TCHP') && ( $PRO_license['status'] == "inactive" || $PRO_license['status'] == "active" || $PRO_license['status'] == "expired" || $TYO_license['status'] == "expires" )) {
													echo '<li class=""><a id="qcTab" href="#quick_cards" data-toggle="tab" aria-expanded="false">Quick Cards</a></li>';
												}

												if (!empty($expload_license_check) && ($expload_license_check[0] == 'BDL' || $expload_license_check[0] == 'TCHP')) {
													echo '<li class=""><a id="arcadeTab" href="#arcade" data-toggle="tab" aria-expanded="false">Arcade</a></li>';
												}

												if (!empty($PRO_license['status']) && ($expload_license_check[0] == 'BDL' || $expload_license_check[0] == 'TCHP') && ( $PRO_license['status'] == "inactive" || $PRO_license['status'] == "active" || $PRO_license['status'] == "expired" || $TYO_license['status'] == "expires" )) {
													echo '<li class=""><a id="pro-pack-Tab" href="#propack" data-toggle="tab" aria-expanded="false">Pro Pack</a></li>';
												}
											}
                                            ?>                                        
                                        </ul>
                                        <div class="tab-content">
                                            <!-- overview -->

                                            <?php
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
											
                                            if (isset($_REQUEST['OverviewDateSubmit']) && ($_REQUEST['OverviewDateSubmit'] == 'Show')) {
                                                $history_start_date = date('Y-m-d', strtotime($_REQUEST['HistoryDate1']));
                                                $history_end_date = date('Y-m-d', strtotime($_REQUEST['HistoryDate12']));
                                            } else {
                                                $history_start_date = $start_date;
                                                $history_end_date = date('Y-m-d');
                                            }
											$start_date = getTimezonewiseDate($start_date);
											$end_date = getTimezonewiseDate($end_date);
											$history_start_date = getTimezonewiseDate($history_start_date);
											$history_end_date = getTimezonewiseDate($history_end_date);
                                            $student_id = !empty($_GET['student']) ? $_GET['student'] : '';
                                            ?>
											<br>
											<input type="hidden" name="graph_start_date" id="graph_start_date" value="<?php echo $start_date; ?>" />

                                            <input type="hidden" name="graph_end_date" id="graph_end_date" value="<?php echo $end_date;?>" />
                                            
                                            <input type="hidden" name="graph_student_id" id="graph_student_id" value="<?php echo $student_id;?>" />
                                            
                                            <div class="tab-pane active" id="overview">
                                                <div class="row"> 
                                                    <div class="col-md-8"> 
                                                        <div class="box-header-init"> 
                                                            <h2 class="dashboard-h2">This Week ( <?php echo date('m/d/y', strtotime($start_date)); ?> - <?php echo date('m/d/y', strtotime($end_date)); ?> )</h2> 
                                                        </div> 
                                                    </div> 
                                                </div>
                                                <div class="row overview-graph-total">
                                                   <div class="col-md-4"> 
                                                        <center><h3><strong><span class="row overview-graph-total-time"><?php echo getUserActivityLog_graph($student_id,'',$start_date,$end_date,'');?></span></strong></h3></center>
                                                     </div> 
                                                </div>
                                                <div class="row">  
                                                    <!-- Pie chart -->  
                                                <?php
												
                                                $is_data = getapplogforweek_all($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59');
                                                $clss_overview_a_history = empty($is_data)? ' history_hide ' : '';
                                                //if(!empty($is_data)){
                                                    	

                                                    $chart = '';
                                                    if( !$is_data ) {
                                                        $chart = 'style="display: none;"';
                                                    } else {
                                                        $chart = '';
                                                    }
                                                    ?> 
                                                    <div class="col-md-6 lessons_chart_print <?php  echo $clss_overview_a_history; ?>" > 
                                                        <div class="chart chart-p-typio-init" <?php echo $chart; ?>> 
                                                            <div id="piechartOverview_graph_data" style="height: 250px; width: 300px;" width="300" height="250"></div> 
                                                        </div> 

                                                        <table id="piechartOverview_table" style="display: none;" border="2"> 
                                                            <tr><td></td><td></td></tr>
                                                            <?php echo getapplogforweek_all($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59'); ?> 

                                                        </table> 
                                                    </div> 
                                                    <!-- End BAR Pie --> 
                                                    <!-- table --> 
                                                    <div class="col-md-6 lessons_complete_list_noprint overview-graph-tbl  <?php  echo $clss_overview_a_history; ?>"> 
                                                        <?php $table_data = getapplogforweekTable_all($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59'); ?> 
                                                        <div class="box-header"> 
                                                            <h3 class="box-title"><strong><?php echo!empty($table_data['total_row']) ? $table_data['total_row'] : 0; ?> Events </strong></h3>  
                                                        </div>

                                                        <div class="box-body table-responsive no-padding">  
                                                            <table class="table table-hover table-bordered" > 
                                                                <thead>
                                                                    <tr>
                                                                        <th>Activity</th>
                                                                        <th>Date</th>
                                                                        <th>Details</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody> 
                                                                    <?php echo!empty($table_data['html']) ? $table_data['html'] : ''; ?> 
                                                                </tbody>
                                                            </table> 
                                                        </div> 
                                                    </div>
												<?php
                                                if(!empty($clss_overview_a_history)){ ?>
                                                	<div class="no-activity-msg-wrap">
														<div class="no-activity-msg">No activity this week</div>
													</div>
                                                <?php 
                                                } ?>

                                                    <!-- end table -->  
                                                </div>

                                                <div class="row">     
                                                    <div class="space-margin-bottom-50"></div>  
                                                    <div class="wt-cus-dvider"></div> 
                                                    <div class="space-margin-bottom-50"></div> 
                                                </div>

                                                <div class="row" id="HistoryDateSection"> 
                                                    <div class="col-md-12"> 
                                                        <div class="box-header-init"> 
                                                            <h2 class="dashboard-h2">History</h2> 
                                                        </div> 
                                                    </div>

                                                    <form method="post" class="custom-p-form-init"> 
                                                        <div class="form-group col-md-2 col-xs-12"> 
                                                            <label>From:</label> 
                                                            <div class="input-group date"> 
                                                                <input id="HistoryDate1" name="HistoryDate1" value="<?php echo date('m/d/Y', strtotime($history_start_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d"> 
                                                                <input id="student_id" name="student_id" value="<?php echo isset($_GET['student']) ? $_GET['student'] : ''; ?>" type="hidden">
                                                                <input id="user_type" name="user_type" value="0" type="hidden"> 
                                                                <div class="input-group-addon"> 
                                                                    <i class="fa fa-calendar" aria-hidden="true"></i>   
                                                                </div></div> 
                                                            <!-- /.input group -->
                                                        </div>

                                                        <div class="form-group col-md-2 col-xs-12"> 
                                                            <label>To:</label> 
                                                            <div class="input-group date"> 
                                                                <input id="HistoryDate2" name="HistoryDate2" value="<?php echo date('m/d/Y', strtotime($history_end_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d"> 
                                                                <div class="input-group-addon">  
                                                                    <i class="fa fa-calendar" aria-hidden="true"></i> 
                                                                </div> 
                                                            </div> 
                                                            <!-- /.input group --> 
                                                        </div>

                                                        <div class="form-group col-md-2 col-xs-12"> 
                                                            <!-- btn -->  
                                                            <div class="btn-group">  
                                                                <button type="button" name="OverviewDateSubmit" value="Show" id="DateFilterBtn" class="dashboard-settings-btn">Show</button>
                                                            </div> 
                                                            <!-- end btn --> 
                                                        </div> 
                                                    </form>

                                                </div>

                                                <div class="row" id="HistorySection">  
                                                    <!-- Pie chart --> 
                                                    
                                                    <?php $table_data = getapplogforweekTable($student_id, $history_start_date . ' 00:00:00', $history_end_date . ' 23:59:59'); 
                                                    	$clss_history = empty($table_data['total_row'])? ' history_hide ' : '';
                                                    ?> 

                                                    <div class="col-md-6 lessons_chart_print <?php echo $clss_history; ?>"> 
                                                        <div class="chart chart-p-typio-init" > 
                                                            <div id="piechartOverview2" style="height: 250px; width: 300px;" width="300" height="250"></div> 
                                                        </div> 

                                                        <table id="piechartOverview_table2" style="display: none;"  >
                                                            <thead><tr><td></td><td></td></tr></thead>
                                                            <tbody  id="pie_chart_table"> 

                                                                <?php echo getapplogforweek($student_id, $history_start_date . ' 00:00:00', $history_end_date . ' 23:59:59'); ?> 
                                                            </tbody> 
                                                        </table> 
                                                    </div> 
                                                    <!-- End BAR Pie --> 
                                                    <!-- table --> 
                                                    <div class="col-md-6 lessons_complete_list_noprint <?php echo $clss_history; ?>"> 
                                                        
                                                        <div class="box-header"> 
                                                            <h3 class="box-title"><strong><span id="TotalRow"><?php echo!empty($table_data['total_row']) ? $table_data['total_row'] : 0; ?></span> Events </strong></h3>  
                                                        </div>

                                                        <div class="box-body table-responsive no-padding">  
                                                            <table class="table table-hover table-bordered"  > 
                                                                <thead>
                                                                    <tr>
                                                                        <th>Activity</th>
                                                                        <th>Date</th>
                                                                        <th>Details</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="History_table"> 
                                                                    <?php echo!empty($table_data['html']) ? $table_data['html'] : ''; ?> 
                                                                </tbody>
                                                            </table> 
                                                        </div> 
                                                    </div>

                                                    <?php 
                                                    if( !empty( $clss_history ) ){
                                                    	echo '<div class="no-history-msg-wrap">
																<div class="no-history-msg">No history to display</div>
															</div>';
                                                    } ?>
                                                    <!-- end table -->  
                                                </div>

                                            </div> 


                                            <?php
                                            if (!empty($TYO_license['status']) && ($expload_license_check[0] == 'BDL'  || $expload_license_check[0] == 'TCHP' || $expload_license_check[0] == 'TCH' || $expload_license_check[0] == 'TYO') && ( $TYO_license['status'] == "inactive" || $TYO_license['status'] == "active" || $TYO_license['status'] == "expired" || $TYO_license['status'] == "expires" )) {
												
                                                ?>
                                                <!-- ---------- Typio Sectoin ---------- --> 
                                                <div class="tab-pane" id="typio">
                                                    <?php include "student-typio.php"; ?>
                                                </div>
                                                <!-- ---------- End Typio Section four ---------- -->
                                                <?php
                                            }
                                            if (!empty($PRO_license['status']) && ($expload_license_check[0] == 'BDL' || $expload_license_check[0] == 'TCHP') && ( $PRO_license['status'] == "inactive" || $PRO_license['status'] == "active" || $PRO_license['status'] == "expired" || $TYO_license['status'] == "expires" )) {
                                                ?>
                                                <!-- ---------- Quick cards ---------- --> 
                                                <div class="tab-pane" id="quick_cards">
                                                    <!-- ---------- Quick cards Section one ---------- -->
                                                    <?php include "student-quick-cards.php"; ?>
                                                </div>
                                                <?php
											}
                                            if (!empty($expload_license_check) && ($expload_license_check[0] == 'BDL' || $expload_license_check[0] == 'TCHP')) { ?>
                                                <!-- ---------- Arcade Section REMOVED TO FIX ISSUE NEED TO PU BACK---------- --> 
                                                <div class="tab-pane" id="arcade">
                                                    <?php include "student-arcade.php"; ?> 
                                                </div> 
                                                <!-- ---------- End Arcade Sectoin ---------- -->
                                                <?php
                                            } ?>
                                            <!-- /.tab-pane -->
											<?php 
											if (!empty($PRO_license['status']) && ($expload_license_check[0] == 'BDL' || $expload_license_check[0] == 'TCHP') && ( $PRO_license['status'] == "inactive" || $PRO_license['status'] == "active" || $PRO_license['status'] == "expired" || $TYO_license['status'] == "expires" )) {
												?>
												<div class="tab-pane" id="propack">
													<?php include 'student-pro-pack.php'; ?>
												</div>
											<?php
											}
											?>
                                            

                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                    <!-- End Tab -->
                                </div>
                            </div>

                        </div>
                    </section>
                <?php } else { ?>
                    <section class="content">
                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-12">
                                <div class="student-title-main-init">
                                    <div style="padding-top: 10px;font-size: 20px;">You can't access this student data</div>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php } ?>
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
        <script src="<?php echo ADMIN_URL ?>plugins/boot-box/bootbox.min.js"></script>
<!--        <script src="<?php echo ADMIN_URL ?>plugins/chartjs/Chart.bundle.js"></script>-->
        
		<!-- don't chages version of highchart otherwise it's break chart fuctionality -->
        <script src="https://code.highcharts.com/8.0.0/highcharts.js"></script>
        <script src="https://code.highcharts.com/8.0.0/modules/data.js"></script>
        <script src="https://code.highcharts.com/8.0.0/modules/exporting.js"></script>
        <!-- bootstrap-datepicker -->
         <script src="<?php echo ADMIN_URL ?>dist/js/pages/typio.js"></script>
        <script src="<?php echo ADMIN_URL ?>dist/js/pages/student-propack.js"></script>

        <script src="<?php echo ADMIN_URL ?>plugins/datepicker/bootstrap-datepicker.min.js"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->  
        <script src="<?php echo ADMIN_URL ?>dist/js/pages/student-overview.js"></script>
         <script src="<?php echo ADMIN_URL ?>dist/js/pages/student-overview-arcade.js"></script> 
        <script src="<?php echo ADMIN_URL ?>dist/js/pages/student-overview-typio.js"></script>
       

        <script src="<?php echo ADMIN_URL ?>dist/js/pages/student-overview-pie-chart.js"></script>  
        <script src="<?php echo ADMIN_URL ?>dist/js/pages/arcade.js"></script>

    </body>
</html>