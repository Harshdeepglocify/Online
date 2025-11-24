<?php
include "../config/config-student.php";
include "../config/config-student-arcade.php";

if(!$_SESSION['User']){
    header("Location: " . ADMIN_URL . 'login');
    exit;
}

$student_id = !empty($_GET['student']) ? $_GET['student'] : '';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo ADMIN_Text; ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?php include "../config/css.php"; ?> 
        <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>plugins/jQueryUI/jquery-ui.min.css">
        <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>plugins/jquery-ui-daterangepicker/jquery.comiseo.daterangepicker.css">
        <script>
            var ADMIN_URL = '<?php echo ADMIN_URL; ?>';
        </script> 
        <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>dist/css/print.css" type="text/css" media="print" />
        <style>
            div#pieChartHomeHistory {width: auto !important;}
        </style>
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
                    <div class="container">
                        <?php
                        if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                            ?>
                            <!--<div style="font-family: Roboto Slab, serif; background-color: #000;" class="alert alert-<?php // echo $_SESSION['error']['color']  ?> alert-dismissable fade in">-->
                            <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" aria-live="assertive" class="alert  alert-dismissable fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?php echo $_SESSION['error']['message'] ?>
                            </div>
                        <?php } unset($_SESSION['error']) ?>
                    </div>
                    <div class="row">
                        <!-- Title -->
                        <div class="col-md-12">
                            <div class="student-title-main-init">              
                                <h3><?php echo getHeaderInfo($student_id, 2) ?></h3><h3><?php echo getHeaderInfo($student_id, 1) ?></h3>
                                <div class="editInline" data-name="variable" data-target="info">
                                    <div id="infodiv">
                                        <span id="info">
                                            <?php echo getHeaderInfo($student_id, 3) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- chart section one -->  
                        <div class="col-md-12">
                            <div class="chart-one-main-init">
                                <h3>History</h3>
                                <div class="row">
                                    <form method="POST" id="stdoverview">

                                        <input id="student_id" name="student_id" value="<?php echo $student_id; ?>" type="hidden">
                                        <div class="form-group col-md-2 col-xs-12">                    
                                            <div class="input-group date">                            
                                                <input id="report_start_date" name="report_start_date" value="" type="text" class="form-control pull-right datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <label>From:</label>
                                        </div>
                                        <div class="form-group col-md-2 col-xs-12">
                                            <div class="input-group date">         
                                                <input id="report_end_date" name="report_end_date" value="" type="text" class="form-control pull-right datepicker" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <label>To:</label>
                                        </div>
                                        <div class="col-md-5">
                                            <!-- Checkbox -->
                                            <div class="form-group checkbox-init-custome">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="this_month" value="1" id="this_month" class="checked"> This Month
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="this_week " value="1" id="this_week" class="checked"> This Week
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="typio" value="Typio-OL" id="HomeHistoryAppTypio"> Typio
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="quick" value="Quick-Cards-OL" id="HomeHistoryAppQC"> Quick-Cards
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="arcade" value="Arcade-OL" id="HomeHistoryAppAC"> Arcade
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- End Checkbox -->
                                        </div>

                                        <div class="col-md-1">
                                            <!-- btn -->
                                            <div class="btn-group">
                                                <button id="HomeHistoryDateFilterBtn" type="submit" name="filter_data" class="btn btn-primary">Show</button>
                                            </div>
                                            <!-- end btn -->
                                        </div>
                                    </form>
                                    <?php
                                    $table_data = displayAppDataChart($student_id);
                                    ?>
                                    <div class="col-md-2">
                                        <div class="action-btn-chart-init">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-danger">Action</button>
                                                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="#">Print</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#">Save</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-3 pie-chart-wrap">
                                        <div id="pieChartHomeHistory" style="height: 265px; width: 530px;" width="150" height="150"></div>  
                                    </div>
                                    <table id="pieChartHomeHistory_table" style="display:none;">
                                        <?php echo $table_data['html']; ?>
                                    </table>
                                    <table id="nodata_table" style="display:none;"></table>
                                </div>                                
                            </div>
                        </div>
                        <!-- chart section two -->
                        <div class="col-md-12">
                            <?php
                            $typio_start_date = date('Y-m-d', strtotime('-6 days'));
                            $typio_end_date = date('Y-m-d');
                            ?>
                            <div class="chart-two-main-init">
                                <!-- img -->
                                <div class="chart-title-header-img">
                                    <img class="" src="<?php echo ADMIN_URL . 'img/user-one-init.png'; ?> " alt="">
                                    <?php
                                    $lessons = getUserLog($student_id, '', 'DESC', 'Typio-OL');
                                    echo '<p>' . (!empty($lessons) ? count($lessons) : 0 ) . ' Lessons</p>';
                                    ?>
                                </div>
                                <!-- img over -->
                                <div class="row">
                                    <!-- BAR chart -->
                                    <div class="col-md-3 bar-chart-wrapper home_lessons_chart_print">
                                        <div class="chart barChartHomeTypio1">
                                            <div id="barChartHomeTypio1" style="height: 230px; width: 250px;"></div>
                                        </div>
                                        <table id="barChartHomeTypio1_table" style="display: none;">
                                            <?php echo displayAppTypioDataTableAverageBarChart($student_id, $typio_start_date . ' 00:00:00', $typio_end_date . ' 23:59:59'); ?>
                                        </table>
                                    </div>
                                    <!-- End BAR chart -->
                                    <!-- table -->
                                    <div class="col-md-5 home_lessons_list_noprint">
                                        <?php $typio_table_data = displayAppTypioData($typio_start_date . ' 00:00:00', $typio_end_date . ' 23:59:59', $student_id); ?>
                                        <div class="box-header">
                                            <h3 class="box-title typio_table_html_count"><strong><?php echo $typio_table_data['total_row']; ?> Lessons complete</strong></h3>
                                        </div>
                                        <div class="box-body table-responsive no-padding">
                                            <table id="typio_table_html" class="table table-hover table-bordered">
                                                <?php echo!empty($typio_table_data['html']) ? $typio_table_data['html'] : ''; ?>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- end table -->
                                    <!-- Area Chart -->
                                    <div class="col-md-4 area-chart-wrap home_lessons_chart_print">
                                        <div class="chart areaChartHomeTypio1">
                                            <div id="areaChartHomeTypio1" style="height: 250px; width: 320px;" width="320" height="250"></div>
                                        </div>
                                        <table id="areaChartHomeTypio1_table" style="display: none;">
                                            <?php echo displayAppTypioDataTableAverageAreaChart($typio_start_date . ' 00:00:00', $typio_end_date . ' 23:59:59'); ?>
                                        </table> 
                                    </div>
                                    <!-- End Area Chart -->
                                    <!-- table for print -->
                                    <div class="col-md-5 home_lessons_list_print">
                                        <div class="box-header">
                                            <h3 class="box-title"><strong><?php echo $typio_table_data['total_row']; ?> Lessons complete</strong></h3>
                                        </div>
                                        <div class="box-body table-responsive no-padding">
                                            <table class="table table-hover table-bordered">
                                                <?php echo!empty($typio_table_data['html']) ? $typio_table_data['html'] : ''; ?>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- end table for print -->
                                </div>
                                <!-- chart over -->
                            </div>          
                        </div>
                        <!-- chart section three -->
                        <div class="col-md-12">
                            <div class="chart-two-main-init">
                                <!-- img -->
                                <div class="chart-title-header-img">
                                    <img class="" src="<?php echo ADMIN_URL . 'img/user-two-init.png'; ?> " alt="">
                                    <?php
                                    $lessons = getUserLog($student_id, '', 'DESC', 'Quick-Cards-OL');
                                    echo '<p>' . (!empty($lessons) ? count($lessons) : 0 ) . ' Decks</p>';
                                    ?>
                                </div>
                                <!-- img over -->
                                <div class="row">
                                    <?php $QuickCards_data = get_QuickCards_data($student_id, $start_date, $end_date); ?>
                                    <!-- Area Chart -->
                                    <div class="col-md-4 area-chart-wrap">
                                        <div class="chart">
                                            <div id="areaChartHomeQuickcards" style="height: 250px; width: 320px;" width="320" height="250"></div>
                                        </div>
                                        <table id="areaChartHomeQuickcards_table" style="display:none;">
                                            <tbody>
                                                <tr>
                                                    <th></th>
                                                    <th>Percent</th>
                                                </tr>
                                                <?php
                                                if (!empty($QuickCards_data)) {
                                                    foreach ($QuickCards_data as $key => $value) {

                                                        echo '<tr>';
                                                        echo '<td>' . $value['file'] . '</td>';
                                                        echo '<td>' . $value['percent_score'] . '</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="box-header">
                                            <h3 class="box-title quick_history_count"><strong><?php echo!empty($QuickCards_data) ? count($QuickCards_data) : 0; ?> Lessons complete</strong></h3>
                                        </div>
                                        <div class="box-body table-responsive no-padding">
                                            <table class="table table-hover table-bordered" id="QuickCards_data_html">
                                                <tbody>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Score</th>
                                                        <th># Missed</th>
                                                        <th>Missed Cards</th>                            
                                                        <th>Date</th>                            
                                                    </tr>
                                                    <?php
                                                    if (!empty($QuickCards_data)) {

                                                        foreach ($QuickCards_data as $key => $value) {

                                                            echo '<tr>';
                                                            echo '<td>' . $value['file'] . '</td>';
                                                            echo '<td>' . $value['percent_score'] . '%</td>';
                                                            echo '<td>' . $value['incorrect'] . '</td>';
                                                            echo '<td>' . implode(', ', $value['cards_missed']) . '</td>';
                                                            echo '<td>' . date('m/d/y', strtotime($value['date'])) . '</td>';
                                                            echo '</tr>';
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- end table -->                  
                                </div>
                                <!-- chart over -->
                            </div>          
                        </div>
                        <!-- chart section four -->
                        <div class="col-md-12" id="HomeArcadeHistoryDateSection">
                            <div class="chart-two-main-init">
                                <!-- img -->
                                <div class="chart-title-header-img">
                                    <img class="" src="<?php echo ADMIN_URL . 'img/user-three-init.png'; ?> " alt="">
                                    <?php
                                    $lessons = getUserLog($student_id, '', 'DESC', 'Arcade-OL');
                                    echo '<p>' . (!empty($lessons) ? count($lessons) : 0 ) . ' Games</p>';
                                    ?>
                                </div>
                                <!-- img over -->
                                <div class="row">

                                    <?php
                                    $arcade_history = getArcadeHistory($student_id, $arcade_history_from, $arcade_history_to);
                                    ?>
                                    <!-- table -->
                                    <div class="col-md-12">
                                        <div class="box-header">
                                            <h3 class="box-title arcade_history_count"><strong><?php echo!empty($arcade_history) ? count($arcade_history) : 0; ?> Games played</strong></h3>
                                        </div>
                                        <div class="box-body table-responsive no-padding">
                                            <table class="table table-hover table-bordered" id="HomeArcadeHistory_table">
                                                <tbody>
                                                    <tr>
                                                        <th>Game</th>
                                                        <th>Date</th>                       
                                                    </tr>
                                                    <?php
                                                    if (!empty($arcade_history)) {

                                                        foreach ($arcade_history as $key => $value) {

                                                            echo '<tr>';
                                                            echo '<td>' . $value['file'] . '</td>';
                                                            echo '<td>' . date('m/d/y', strtotime($value['date'])) . '</td>';
                                                            echo '</tr>';
                                                        }
                                                    }
                                                    ?>                    
                                                </tbody></table>
                                        </div>
                                    </div>
                                    <!-- end table -->                  
                                </div>
                                <!-- chart over -->
                            </div>          
                        </div>
                    </div>
                </section>
            </div>
            <?php include "../config/footer.php"; ?>
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
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/data.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/highchart/no-data-to-display.js"></script>
        <!-- bootstrap-datepicker -->
        <!--<script src="<?php echo ADMIN_URL ?>plugins/jQueryUI/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo ADMIN_URL ?>plugins/daterangepicker/moment.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/jquery-ui-daterangepicker/jquery.comiseo.daterangepicker.min"></script>-->
        <!-- Include Date Range Picker -->
        <script src="<?php echo ADMIN_URL ?>plugins/datepicker/bootstrap-datepicker.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>dist/js/pages/student-main.js"></script>
    </body>
</html>
