<!--   Brail Sectoin one   -->
<?php
$Brail_start_date = date('Y-m-d', strtotime('-6 days'));

$Brail_end_date = date('Y-m-d');

$current_time = strtotime(date('Y-m-d'));

$Brail_start_date = date('Y-m-d', strtotime('Last Monday', $current_time));

$Brail_end_date = date('Y-m-d', strtotime('Next Sunday', $current_time));

//check the current day
if (date('D') != 'Mon') {
    //take the last monday
    $Brail_start_date = date('Y-m-d', strtotime('Last Monday', $current_time));
} else {
    $Brail_start_date = date('Y-m-d');
}

//always next saturday

if (date('D') != 'Sat') {
    $Brail_end_date = date('Y-m-d', strtotime('Next Sunday', $current_time));
} else {
    $Brail_end_date = date('Y-m-d');
}

//print_r($_REQUEST);
if (isset($_REQUEST['BrailHistoryDateSubmit']) && ($_REQUEST['BrailHistoryDateSubmit'] == 'Show')) {

    $Brail_history_start_date = date('Y-m-d', strtotime($_REQUEST['BrailHistoryDate1']));

    $Brail_history_end_date = date('Y-m-d', strtotime($_REQUEST['BrailHistoryDate2']));

    $Brail_history_submit_check = 'Checked';
} else {  

    $Brail_history_start_date = $Brail_start_date; //date('Y-m-d', strtotime('-14 days'));

    $Brail_history_end_date = date('Y-m-d');

    $Brail_history_submit_check = 'Unchecked';
}

$Brail_start_date = getTimezonewiseDate($Brail_start_date);
$Brail_end_date = getTimezonewiseDate($Brail_end_date);
$Brail_history_start_date = getTimezonewiseDate($Brail_history_start_date);
$Brail_history_end_date = getTimezonewiseDate($Brail_history_end_date);
$student_id = !empty($_GET['student']) ? $_GET['student'] : '';
$Brail_error_start_date = "2019-04-23";   // use Y-m-d format
$Brail_error_end_date = "2019-02-15";   // use Y-m-d format
?>

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
<?php
$Brail_table_data = displayAppBrailData($Brail_start_date . ' 00:00:00', $Brail_end_date . ' 23:59:59');
?>
<?php $clss_Brail_weekly = empty($Brail_table_data['total_row']) ? ' history_hide ' : ''; ?>

<div class="row">

</div>




<?php

/**

 * Display App Brail-OL Custom Lessons 

 * 

 * @param string $Brail_start_date 

 * @param string $Brail_end_date 

 * @return string Brail-OL table html

 */
function displayAppBrailLessonsData() {

    global $con;

    $text_lessons_table = "text";

    $text_Brail_lessons_query = "SELECT * FROM `" . $text_lessons_table . "` WHERE `app` LIKE 'Typio-BRL' AND `id`='" . $studentid . "'";

    $text_Brail_lessons_result = mysqli_query($con, $text_Brail_lessons_query);

    $text_Brail_lessons_html = '<table class="table table-bordered" id="Brail-custom-lessons"><tbody><tr><th style="width: 10px">#</th><th>Task</th><th style="width: 40px">Edit</th><th style="width: 40px">Delete</th></tr>';


    $text_Brail_lessons_count = 0;

    while ($text_Brail_lessons_row = mysqli_fetch_assoc($text_Brail_lessons_result)) {

        $text_Brail_lessons_count++;

        $text_Brail_lessons_title = $text_Brail_lessons_row['title'];

        $text_Brail_lessons_data = $text_Brail_lessons_row['data'];

        $text_Brail_lessons_html .= '<tr>';

        $text_Brail_lessons_html .= "<td>$text_Brail_lessons_count.</td>";

        $text_Brail_lessons_html .= "<td>$text_Brail_lessons_title</td>";

        $text_Brail_lessons_html .= '<td><a href="#" class="badge bg-green"><i class="fa  fa-edit (alias)" aria-label="Edit lesson" role="button"></i></a></td>';

        $text_Brail_lessons_html .= '<td><a href="#" class="badge bg-red"><i class="fa fa-trash-o" aria-label="Delete" role="button"></i></a></td>';

        $text_Brail_lessons_html .= '</tr>';
    }

    $text_Brail_lessons_html .= '</tbody></table>';

    return $text_Brail_lessons_html;
}
?>
<!-- Brail Progress Sectoin -->

<div class="row" id="BrailKeyboardProgressSection">

    <div class="col-md-12">

        <div class="box-header-init">

            <h2 class="dashboard-h2">Keyboard Progress</h2>

        </div>

    </div>

    <form method="post" class="custom-p-form-init">

        <div class="form-group col-md-2 col-xs-12" >

            <label>From:</label>

            <div class="input-group date">

                <input id="BrailkpDatePicker1" name="BrailkpHistoryDate1" value="<?php echo date('m/d/Y', strtotime($Brail_history_start_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d" aria-label="Beginning date field. Use the following format when typing a custom date range: two digit month, forward slash, two digit day, forward slash, two digit year.">

                <input id="student_idkp" name="student_idkp" value="<?php echo isset($_GET['student']) ? $_GET['student'] : ''; ?>" type="hidden">
                <input id="user_type" name="user_type" value="0" type="hidden">

                <div class="input-group-addon">

                    <i class="fa fa-calendar" aria-hidden="true"></i>

                </div></div>

            <!-- /.input group -->
        </div>

        <div class="form-group col-md-2 col-xs-12">

            <label>To:</label>

            <div class="input-group date">

                <input id="BrailkpDatePicker2" name="BrailkpHistoryDate2" value="<?php echo date('m/d/Y', strtotime($Brail_history_end_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d" aria-label="Beginning date field. Use the following format when typing a custom date range: two digit month, forward slash, two digit day, forward slash, two digit year.">

                <div class="input-group-addon">

                    <i class="fa fa-calendar" aria-hidden="true"></i>

                </div>

            </div>

            <!-- /.input group -->

        </div>

        <div class="form-group col-md-2 col-xs-12 cst-right-padding">

            <!-- btn -->

            <div class="btn-group">

                <input type="hidden" value="<?php echo $Brail_history_submit_check; ?>" id="BrailkpHistoryDateSubmitCheck">

                <input type="button" name="BrailHistorykpDateSubmit" value="Show history" id="BrailkpDateFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show history button">

            </div>
			

            <!-- end btn -->

        </div>
		<div class="form-group col-md-2 col-xs-12 cst-left-padding">
			<div class="btn-group"> 
			   <input type="hidden" value="<?php echo $Brail_history_submit_check; ?>" id="BrailkpHistoryDateSubmitCheck">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($Brail_history_start_date));?>" id="BrailkpThisWeekFilterstart">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($Brail_history_end_date)); ?>" id="BrailkpThisWeekFilterend">
			   <input type="button" name="BrailkpThisWeekSubmit" value="This Week" id="BrailkpThisWeekFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show This Week button">
			
			</div>

            <!-- end btn -->

       </div>

    </form>

</div>

<div class="row" id="BrailkpHistorySection">

    <?php
    $Brail_history_table_data = displayAppBrailData($Brail_history_start_date . ' 00:00:00', $Brail_history_end_date . ' 23:59:59');
    $clss_Brail_history = empty($Brail_history_table_data['total_row']) ? ' history_hide ' : '';
    ?>
    <!-- table -->

    <div class="col-md-6 d-flex align-items-stretch <?php echo $clss_Brail_history; ?>" style="padding: 0px;">

        <div class="box-header">
            
        </div>
        <div class="box-body date-history table-responsive no-padding">
            <div class="quick-ajax-responseBRL" aria-live="assertive" style="display: none;">
                <div class="alert alert-dismissible fade in" id="alert-success">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="BrailkpHistory_table" style="width:100%;">
                    <thead>
                        <tr>
                            <td><h4 class="Students-title-cs"><b>Lesson</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>Date</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>WPM</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>ACC</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>ERR</b></h4></td>
                            <td><h4 class="Students-title-cs"><b></b></h4></td>
                            <td><h4 class="Students-title-cs"><b></b></h4></td>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>            
                </table>
            </div>

        </div>

    </div>

    <!-- end table --> 
    <!-- Area Chart -->
	<div class="col-md-6 d-flex align-items-stretch cst-braillio-graph <?php echo $clss_Brail_history; ?>" style="padding: 0px;" bis_skin_checked="1">
		<div class="col-md-12" style="padding: 0px;" bis_skin_checked="1">
			<div class="col-md-12 d-flex align-items-left" bis_skin_checked="1">
				<div class="col-md-6 cst-dev_lftkp" bis_skin_checked="1">
					<label class="font-italic date1-brailliokp">09/01/2025</label>
						<span class="mx-2">-</span>
					<label class="font-italic date2-brailliokp">09/18/2025</label>
				</div>
			<div class="col-md-6 text-right cst-dev_rgtkp" bis_skin_checked="1">
			 <!-- Small copy button -->
				<img src="<?php echo ADMIN_URL; ?>img/clone.png" alt="icon" id="cst-dev-braillio-copy" class="cst-dev-braillio-copykp" aria-label="Copy averages data">
			 <!-- Small copied message -->
				<span id="braillio_copyMessagekp">Copied!</span>
		    </div>
	  	   </div>

     <div class="col-md-12" bis_skin_checked="1">
	 <p class="text-left braillio_lesson_cst_clskp">Averages out of <b id="braillio_lesson_cstkp">0 Lessons</b></p>
	 </div>
	 <div class="col-md-12" bis_skin_checked="1">
		 <div class="col-md-4" bis_skin_checked="1"><p class="text-center"><b id="braillio_wpm_cst_kp">0 WPM</b></p></div>
		 <div class="col-md-4" bis_skin_checked="1"><p class="text-center"><b id="braillio_acc_cst_kp">0% Accuracy</b></p></div>
		 <div class="col-md-4" bis_skin_checked="1"><p class="text-center"><b id="braillio_err_cst_kp">0 Errors</b></p></div>
	 </div>
	 
	
 </div>

    <div class="col-md-6 <?php echo $clss_Brail_history; ?>">

        <div class="chart chart-p-Brailtwo-init">
	
            <div id="BrailHistoryAreaChart_kp" style="height: 350px; width: 330px;" width="330" height="350"></div>

      
        </div>

        <table id="BrailHistoryAreaChart_table_kp" style="display: none;">

<?php 
echo displayAppBrailDataTableAverageAreaChart($Brail_history_start_date . ' 00:00:00', $Brail_history_end_date . ' 23:59:59'); ?>

        </table>

    </div>

    <!-- End Area Chart -->
    <div class="col-md-6 lessons_chart_print <?php echo $clss_Brail_history; ?>">

        <div class="chart chart-p-Brail-init">

            <div id="historyBrail1Tab2_kp" style="height: 250px; width: 300px;" width="300" height="250"></div>

        </div>

        <table id="historyBrail1Tab2_table_kp" style="display: none;">

<?php 
echo displayAppBrailDataTableAverageBarChart($student_id, $Brail_history_start_date . ' 00:00:00', $Brail_history_end_date . ' 23:59:59'); ?>

        </table>

    </div>
    </div>

    <?php
    if (!empty($clss_Brail_history)) {
        echo '<div class="no-history-msg-wrap cst_stu-brail_kp">
					<div class="no-history-msg">No history to display</div>
				</div>';
    }
    ?>
<?php $Brail_table_data = displayAppBrailData($Brail_history_start_date . ' 00:00:00', $Brail_history_end_date . ' 23:59:59'); ?>


</div>

<div class="row">    

    <div class="space-margin-bottom-50"></div>                        

    <div class="wt-cus-dvider"></div>

    <div class="space-margin-bottom-50"></div>

</div>


<!--   End Brail progress Sectoin -->

<!--   Brail Sectoin one   -->
<div class="row" id="BrailHistoryDateSection">

    <div class="col-md-12">

        <div class="box-header-init">

            <h2 class="dashboard-h2">History5</h2>

        </div>

    </div>

    <form method="post" class="custom-p-form-init">

        <div class="form-group col-md-2 col-xs-12" >

            <label>From:</label>

            <div class="input-group date">

                <input id="BrailDatePicker1" name="BrailHistoryDate1" value="<?php echo date('m/d/Y', strtotime($Brail_history_start_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d" aria-label="Beginning date field. Use the following format when typing a custom date range: two digit month, forward slash, two digit day, forward slash, two digit year.">

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

                <input id="BrailDatePicker2" name="BrailHistoryDate2" value="<?php echo date('m/d/Y', strtotime($Brail_history_end_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d" aria-label="Beginning date field. Use the following format when typing a custom date range: two digit month, forward slash, two digit day, forward slash, two digit year.">

                <div class="input-group-addon">

                    <i class="fa fa-calendar" aria-hidden="true"></i>

                </div>

            </div>

            <!-- /.input group -->

        </div>

        <div class="form-group col-md-2 col-xs-12 cst-right-padding">

            <!-- btn -->

            <div class="btn-group">

                <input type="hidden" value="<?php echo $Brail_history_submit_check; ?>" id="BrailHistoryDateSubmitCheck">

                <input type="button" name="BrailHistoryDateSubmit" value="Show history" id="BrailDateFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show history button">

            </div>
			

            <!-- end btn -->

        </div>
		<div class="form-group col-md-2 col-xs-12 cst-left-padding">
			<div class="btn-group"> 
			   <input type="hidden" value="<?php echo $Brail_history_submit_check; ?>" id="BrailHistoryDateSubmitCheck">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($Brail_history_start_date));?>" id="BrailThisWeekFilterstart">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($Brail_history_end_date)); ?>" id="BrailThisWeekFilterend">
			   <input type="button" name="BrailThisWeekSubmit" value="This Week" id="BrailThisWeekFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show This Week button">
			
			</div>

            <!-- end btn -->

       </div>

    </form>

</div>

<div class="row" id="BrailHistorySection">

    <?php
    $Brail_history_table_data = displayAppBrailData($Brail_history_start_date . ' 00:00:00', $Brail_history_end_date . ' 23:59:59');
    $clss_Brail_history = empty($Brail_history_table_data['total_row']) ? ' history_hide ' : '';
    ?>
    <!-- table -->

    <div class="col-md-6 d-flex align-items-stretch <?php echo $clss_Brail_history; ?>" style="padding: 0px;">

        <div class="box-header">
            
        </div>
        <div class="box-body date-history table-responsive no-padding">
            <div class="quick-ajax-responseBRL" aria-live="assertive" style="display: none;">
                <div class="alert alert-dismissible fade in" id="alert-success">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="BrailHistory_table" style="width:100%;">
                    <thead>
                        <tr>
                            <td><h4 class="Students-title-cs"><b>Lesson</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>Date</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>WPM</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>ACC</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>ERR</b></h4></td>
                            <td><h4 class="Students-title-cs"><b></b></h4></td>
                            <td><h4 class="Students-title-cs"><b></b></h4></td>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>            
                </table>
            </div>

        </div>

    </div>

    <!-- end table --> 
    <!-- Area Chart -->
	<div class="col-md-6 d-flex align-items-stretch cst-braillio-graph <?php echo $clss_Brail_history; ?>" style="padding: 0px;" bis_skin_checked="1">
		<div class="col-md-12" style="padding: 0px;" bis_skin_checked="1">
			<div class="col-md-12 d-flex align-items-left" bis_skin_checked="1">
				<div class="col-md-6 cst-dev_lft" bis_skin_checked="1">
					<label class="font-italic date1-braillio">09/01/2025</label>
						<span class="mx-2">-</span>
					<label class="font-italic date2-braillio">09/18/2025</label>
				</div>
		<div class="col-md-6 text-right cst-dev_rgt" bis_skin_checked="1">
		 <!-- Small copy button -->
			<img src="<?php echo ADMIN_URL; ?>img/clone.png" alt="icon" id="cst-dev-braillio-copy" class="cst-dev-braillio-copy" aria-label="Copy averages data">
		  <!-- Small copied message -->
			<span id="braillio_copyMessage">Copied!</span>
	  </div>
	  
	</div>

     <div class="col-md-12" bis_skin_checked="1"><p class="text-left braillio_lesson_cst_cls">Averages out of <b id="braillio_lesson_cst">0 Lessons</b></p>
	 </div>
	 <div class="col-md-12" bis_skin_checked="1">
		 <div class="col-md-4" bis_skin_checked="1"><p class="text-center"><b id="braillio_wpm_cst">0 WPM</b></p></div>
		 <div class="col-md-4" bis_skin_checked="1"><p class="text-center"><b id="braillio_acc_cst">0% Accuracy</b></p></div>
		 <div class="col-md-4" bis_skin_checked="1"><p class="text-center"><b id="braillio_err_cst">0 Errors</b></p></div>
	 </div>
	 
	
 </div>

    <div class="col-md-6 <?php echo $clss_Brail_history; ?>">

        <div class="chart chart-p-Brailtwo-init">
	
            <div id="BrailHistoryAreaChart" style="height: 350px; width: 330px;" width="330" height="350"></div>

      
        </div>

        <table id="BrailHistoryAreaChart_table" style="display: none;">

<?php 
echo displayAppBrailDataTableAverageAreaChart($Brail_history_start_date . ' 00:00:00', $Brail_history_end_date . ' 23:59:59'); ?>

        </table>

    </div>

    <!-- End Area Chart -->
    <div class="col-md-6 lessons_chart_print <?php echo $clss_Brail_history; ?>">

        <div class="chart chart-p-Brail-init">

            <div id="historyBrail1Tab2" style="height: 250px; width: 300px;" width="300" height="250"></div>

        </div>

        <table id="historyBrail1Tab2_table" style="display: none;">

<?php 
echo displayAppBrailDataTableAverageBarChart($student_id, $Brail_history_start_date . ' 00:00:00', $Brail_history_end_date . ' 23:59:59'); ?>

        </table>

    </div>
    </div>

    <?php
    if (!empty($clss_Brail_history)) {
        echo '<div class="no-history-msg-wrap cst_stu-brail">
					<div class="no-history-msg">No history to display</div>
				</div>';
    }
    ?>
<?php $Brail_table_data = displayAppBrailData($Brail_history_start_date . ' 00:00:00', $Brail_history_end_date . ' 23:59:59'); ?>


</div>

<div class="row">    

    <div class="space-margin-bottom-50"></div>                        

    <div class="wt-cus-dvider"></div>

    <div class="space-margin-bottom-50"></div>

</div>


<!--   End Brail Sectoin one   -->
<!--   Brail Sectoin two   -->                     

<div class="row">

    <div class="col-md-6">

        <div class="custom_lessonsBRL" >

            <div class="with-border">

                <h2 class="dashboard-h2">Custom Lessons</h2>

            </div>            

            <div class="quick-ajax-responseBRL" aria-live="assertive" style="display:none;">
                <div class="alert alert-dismissible fade in" id="alert-success">
                </div>
            </div>

            <!-- /.box-header -->

            <div class="box-body Brail-custom-lessons">

                    <div class="table-responsive">
                        <table id="student_Brail_lesson_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs table-list-custom" border="2">
                            <thead>
                                <tr>
                                    <td><h4 class="Students-title-cs"><b>Lesson name</b></h4></td>
                                    <td><h4 class="Students-title-cs"><b>Edit</b></h4></td>
                                    <td><h4 class="Students-title-cs"><b>Delete</b></h4></td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    
               <!-- <button type="button" style="margin-top:15px;" class="dashboard-settings-btn btn-block  Brail-import-modal " data-toggle="modal" data-target="#Brail-import-modal">Import Brail Lesson</button>	-->
               <div class="new-option-section">    
                        <!-- <button id="add-new-text" type="button" style="margin-top:56px;" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-Brail-modal" type="button">Save</button> -->
                        <button type="button" style="" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-Brail-modal">Create Custom Lesson</button>
                        <button class="new-option-btn" id="new-option-btnBRL" aria-label="Create custom lesson more options">
                            <i class="fa fa-chevron-down"></i>
                        </button>
                </div>
                <div class="new-option-section-wrap" style="display:none;" id="new-option-section-wrapBRL">
                        <ul class="new-option">
                            <li><button class="dashboard-settings-btn btn-block  Brail-import-modal " data-toggle="modal" data-target="#Brail-import-modal">Import Braillio Lesson</button></li>                            
                        </ul>
                </div>
            </div>

        </div>

    </div>

    <div class="col-md-6">

        <div class="custom-p-lesson-init custom_test_lessonBRL">

            <div class="with-border">
                <h2 class="dashboard-h2">Typing Test</h2>
            </div>
            <div class="quick-ajax-responseBRL" aria-live="assertive" style="display: none;">
                <div class="alert alert-dismissible fade in" id="alert-success">
                </div>
            </div>
            <div class="box-body Brail-test-lessons">

                <div class="table-responsive">
                    <table id="student_Brail_test_lesson_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs table-list-custom" border="2">
                        <thead>
                            <tr>
                                <td><h4 class="Students-title-cs"><b>Lesson name</b></h4></td>
                                <td><h4 class="Students-title-cs"><b>Attempts</b></h4></td>
                                <td><h4 class="Students-title-cs"><b>Edit</b></h4></td>
                                <td><h4 class="Students-title-cs"><b>Delete</b></h4></td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <!-- <button type="button" style="margin-top:15px;" class="dashboard-settings-btn btn-block  Brail-import-modal " data-toggle="modal" data-target="#Brail-import-modal">Import Braillio Lesson</button>	-->
                <div class="new-option-section">    
                    <!-- <button id="add-new-text" type="button" style="margin-top:56px;" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-Brail-modal" type="button">Save</button> -->
                    <button type="button" style="" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-Brail-test-modal">Create Typing Test</button>
                    <button class="new-option-btn" id="new-option-btn-testBRL" aria-label="Create typing test more options">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                </div>
                <div class="new-option-section-wrap" style="display:none;" id="new-option-section-wrap-testBRL">
                        <ul class="new-option">
                            <li><button class="dashboard-settings-btn btn-block  Brail-import-modal " data-toggle="modal" data-target="#Brail-import-modal">Import Braillio Lesson</button></li>                            
                        </ul>
                </div>
                
            </div>

        </div>

    </div>
</div>

<!--   End Brail Sectoin two   -->





<div class="row">    

    <div class="space-margin-bottom-50"></div>                        

    <div class="wt-cus-dvider"></div>

    <div class="space-margin-bottom-50"></div>

</div>

<!--   Brail Sectoin three   -->   

<div class="row" id="BrailSettingsSection">



</div>

<?php

/* START : SET FOR ALL STUDENTS ADDED BY PHP DEV 6 ON 03-09-2021 */

// die();
// if (isset($_REQUEST['BrailSettingSubmit'])) {
//   print_r($_REQUEST['BrailSettingSubmitfield']);
if (isset($_REQUEST['BrailSettingSubmitfield'])) {
//print_r('hello');
    if(isset($_REQUEST['BrailSettingSubmit']) && $_REQUEST['BrailSettingSubmit'] == 'Save Braillio Settings For All Students'){
//print_r('helloq');
        $query = "SELECT * FROM user WHERE `role` ='student' AND id ='".$_REQUEST['student']."' ";
        // echo $query;
        // die('11');
        $query_result = mysqli_query($con, $query);
   
        if (mysqli_num_rows($query_result) > 0 ) {
             while ($student_row = mysqli_fetch_assoc($query_result)) {

                $teacher_code =$student_row['teacher'];

                
                $student_query = "SELECT id FROM user WHERE `role` ='student' AND teacher ='".$teacher_code."' ";
                 $student_result = mysqli_query($con, $student_query);
                  while ($student_row = mysqli_fetch_assoc($student_result)) {
                    
                    $_REQUEST['student'] = $student_row['id'];
                    updateAppBrailSettingData($_REQUEST);
                  }
             }
        }else{
//print_r('helldo');
            // die('121wwww2');
           updateAppBrailSettingData($_REQUEST);
        }

    }
    else{
//print_r('hedadasllo');
        // die('121eedsffdsf2');
       updateAppBrailSettingData($_REQUEST);
    }

    $Brail_setting_submit_check = 'Checked';
} else {

    $Brail_setting_submit_check = 'Unhecked';
}


$ty_options_value = array();

$ty_settings_options = mysqli_query($con, "SELECT * FROM `settings` WHERE `id` = '" . $studentid . "' AND `item` IN (559,565,564,563,562,561,560,515,517,520,521,522,516,518,519,525,526,527,531,529,530,533,534,5,528,538,521,522,536,541,542,543,548,549,550,551,539)");

while ($settings_options = mysqli_fetch_assoc($ty_settings_options)) {
//console.log($settings_options);
//print_r($settings_options);
    $ty_options_value[$settings_options['item']] = $settings_options['variable'];
}

//while end

$Brail_keypress_options = array(4 => 'Read', 1 => 'Pop', 2 => 'Click', 3 => 'Theme');

$Brail_sfx_options = array('Default' => 'Default', 'Jungle' => 'Jungle', 'Cool' => 'Cool', 'Ninja' => 'Ninja', 'Chicken' => 'Chicken', 'Boxing' => 'Boxing', 'Vibraphone' => 'Vibraphone', 'Soft' => 'Soft', 'Silent' => 'Silent');

$Brail_highlight_options = array(1 => 'On', 0 => 'Off');

$Brail_goal_lock_options = array(1 => 'On', 0 => 'Off');

$Brail_game_lock_options = array(1 => 'On', 0 => 'Off');

$Brail_setting_lock_options = array(1 => 'On', 0 => 'Off');

//$typing_pet_coins = array(1 => 'On', 0 => 'Off');
// $Brail_visual_fx = array(1 => 'On', 0 => 'Off');
// $Brail_subtitles = array(1 => 'On', 0 => 'Off');
?>

<div class="row setting-p-init">

    <form method="post"  id="save-setting-form" name="ave-setting-form"> 
        <div class="col-md-12"> 
            <div class="box-header-init"> 
                <h2 class="dashboard-h2">Visual Settings</h2> 
            </div> 
        </div>
        <div class="col-md-12">

            <div class="form-group col-md-4">

                <label><h3 class="dashboard-h3">Text Size</h3></label>

                <select class="form-control" name="Brail_font_size" aria-label="Font Size">

                    <?php
                    if (!empty($font_size_options)) {

                        foreach ($font_size_options as $key => $value) {


                            echo '<option ' . (!empty($ty_options_value[561]) && $ty_options_value[561] == $value ? 'selected="selected"' : '' ) . ' value="' . $value. '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div> 

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Text Style</h3></label>

                <select class="form-control" name="Brail_font_style" aria-label="Font Style">

                    <?php
                    if (!empty($font_style_options)) {

                        foreach ($font_style_options as $key => $value) {
                            echo '<option ' . (!empty($ty_options_value[517]) && $ty_options_value[517] == $key ? ' selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div> 
<!--
            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Font Color</h3></label>

                <select class="form-control" name="Brail_font_color" aria-label="Font Color">

                    <?php
                    if (isset($color_options)) {

                        foreach ($color_options as $key => $value) {
                            
// print_r([$ty_options_value[516],$key]);
                            

                            echo '<option ' . (isset($ty_options_value[516]) && $ty_options_value[516] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div> 

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">BG Color</h3></label>

                <select class="form-control" name="Brail_bg_color" aria-label="Background Color">

                    <?php
                    if (isset($color_options)) {

                        foreach ($color_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[518]) && $ty_options_value[518] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div> 

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Accessory Color</h3></label>

                <select class="form-control" name="Brail_accessory_color" aria-label="Accessory Color">

                    <?php
                    if (isset($color_options)) {

                        foreach ($color_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[519]) && $ty_options_value[519] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div>

            <div class="form-group col-md-4 ">

                <label><h3 class="dashboard-h3">Selection Color</h3></label>

                <select class="form-control" name="Brail_selection_color" aria-label="Selection Style">

                    <option value="0" <?php echo (isset($ty_options_value[543]) && $ty_options_value[543] == 0) ? 'selected="selected"' : ''; ?>>Invert</option>

                    <option value="1" <?php echo (isset($ty_options_value[543]) && $ty_options_value[543] == 1) ? 'selected="selected"' : ''; ?>>Accessory Color</option>

                </select>

            </div> 

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Subtitles</h3></label>

                <select class="form-control" name="Brail_subtitles" aria-label="Subtitles">

                    <option value="0" <?php echo (isset($ty_options_value[541]) && $ty_options_value[541] == 0) ? 'selected="selected"' : ''; ?>>Off</option>

                    <option value="1" <?php echo (isset($ty_options_value[541]) && $ty_options_value[541] == 1) ? 'selected="selected"' : ''; ?>>On</option>

                </select>

            </div>


            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Visual Keyboard</h3></label>

                <select class="form-control" name="visual_keyboard" aria-label="Visual Keyboard">

                    <option value="0" <?php echo (isset($ty_options_value[528]) && $ty_options_value[528] == 0) ? 'selected="selected"' : ''; ?>>Off</option>

                    <option value="1" <?php echo (isset($ty_options_value[528]) && $ty_options_value[528] == 1) ? 'selected="selected"' : ''; ?>>On</option>

                </select>

            </div> -->
<!--
            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Highlight</h3></label>
                <select class="form-control" name="Brail_highlight" aria-label="Highlight">
                    <?php foreach ($Brail_highlight_options as $key => $value) { ?>
                    
                        <option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[527]) && $ty_options_value[527] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>
-->

           
<!--
            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Visual Hands</h3></label>

                <select class="form-control" name="Brail_visual_hands" aria-label="Visual Hands">
                    <?php
                    if (!empty($color_options)) {

                        foreach ($color_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[549]) && $ty_options_value[549] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div>
            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Hands Style</h3></label>

                <select class="form-control" name="Brail_hands_style" aria-label="Hands Style">
                    <option value="0" <?php echo (!empty($ty_options_value[551]) && $ty_options_value[551] == '0') ? "selected" : ''; ?>>Off</option>
                    <option value="1" <?php echo (!empty($ty_options_value[551]) && $ty_options_value[551] == '1') ? "selected" : ''; ?>>Solid</option>
                    <option value="2" <?php echo (!empty($ty_options_value[551]) && $ty_options_value[551] == '2') ? "selected" : ''; ?>>Clear</option>

                </select>

            </div> -->
 <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Keyboard</h3></label>

                <select class="form-control" name="Brail_hands_stylenew" aria-label="Keyboard">
                    <option value="0" <?php echo (isset($ty_options_value[551]) && $ty_options_value[551] == '2' && isset($ty_options_value[528]) && $ty_options_value[528] == '1') ? "selected" : ''; ?>>Keyboard & Hands</option>
                    <option value="1" <?php echo (isset($ty_options_value[551]) && $ty_options_value[551] == '0' && isset($ty_options_value[528]) && $ty_options_value[528] == '1') ? "selected" : ''; ?>>Keyboard Only</option>
                    <option value="2" <?php echo (isset($ty_options_value[551]) && $ty_options_value[551] == '0' && isset($ty_options_value[528]) && $ty_options_value[528] == '0') ? "selected" : ''; ?>>Off</option>

                </select>

            </div>
 <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Theme</h3></label>

                <select class="form-control" name="Brail_theme" aria-label="Theme">
                    <option value="Light" <?php echo (isset($ty_options_value[516]) && $ty_options_value[516] == '255, 255, 254' && isset($ty_options_value[518]) && $ty_options_value[518] == '0, 0, 8' && isset($ty_options_value[519]) && $ty_options_value[519] == '255, 0, 120' && isset($ty_options_value[560]) && $ty_options_value[560] == 'Light') ? "selected" : ''; ?>>Light</option>
                    <option value="Dark" <?php if( $ty_options_value[516] == '0, 0, 8'  && $ty_options_value[518] == '255, 255, 254'  && $ty_options_value[519] == '255, 0, 120' && $ty_options_value[560] == 'Dark'){ ?> selected <?php } ?>>Dark</option>
                    <option value="Yellow" <?php  if(isset($ty_options_value[560]) && $ty_options_value[560] == 'Yellow') { ?> selected <?php } ?>>Yellow</option>
                   <option value="Blue" <?php if(isset($ty_options_value[560]) &&  $ty_options_value[560] == 'Blue'){ ?> selected <?php } ?>>Blue</option>
                   
                </select>

            </div>

        </div> 
        <!-- end first col-md-4 -->
        <br>
        <hr width="90%" size="6" align="center" color="#e5e5e5" border-color="#e5e5e5">
        <div class="col-md-12"> 
            <div class="box-header-init"><br> 
                <h2 class="dashboard-h2">Audio Settings</h2> 
            </div> 
        </div>

        <div class="col-md-12">

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Voice</h3></label>

                <select class="form-control" name="Brail_voice" aria-label="Voice">

                    <?php
                    if (!empty($voice_options)) {

                        foreach ($voice_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[520]) && $ty_options_value[520] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                    <?php
                     if(!empty($ty_options_value[520]) && $ty_options_value[520] !="Off" && $ty_options_value[520] !="Default") { ?>
                        <option value="<?php echo $ty_options_value[520]; ?>" selected><?php echo $ty_options_value[520]; ?></option>
                   <?php } ?>

                </select>

            </div>
<?php if($ty_options_value[520] != 'Off' ){ ?>

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Voice Rate</h3></label>
<input type='hidden' name='Brail_voice_pitch1' id='Brail_voice_rate1' value='' />
                <select class="form-control" name="Brail_voice_rate"  aria-label="Voice Rate" >

                    <?php
                    if (!empty($voice_rate_options)) {

                        foreach ($voice_rate_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[562]) && $ty_options_value[562] == $value ? 'selected="selected"' : '' ) . ' value="' . $key . '" >' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div>


            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Voice Pitch</h3></label>

                <select class="form-control" name="Brail_voice_pitch" aria-label="Voice Pitch">

                    <?php
                    if (!empty($voice_pitch_options)) {

                        foreach ($voice_pitch_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[563]) && $ty_options_value[563] ==  $value ? 'selected="selected"' : '' ) . ' value="' . $key . '" >' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div>

<?php } ?>
            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Keypress</h3></label>

                <select class="form-control" name="Brail_keypress" aria-label="Brail Keypress">

<option value="Speak" <?php echo (isset($ty_options_value[525]) && $ty_options_value[525] == '4' && isset($ty_options_value[565]) && $ty_options_value[565] == '0') ? 'selected="selected"' : ''; ?>>Speak</option>
<?php if($ty_options_value[533] != '1' ){?>
<option value="Pet Speech" <?php echo (isset($ty_options_value[525]) && $ty_options_value[525] == '0' && isset($ty_options_value[565]) && $ty_options_value[565] == '1') ? 'selected="selected"' : ''; ?>>Pet Speech</option>
<?php } ?> 
<option value="Off" <?php echo (isset($ty_options_value[525]) && $ty_options_value[525] == '0' &&  isset($ty_options_value[565]) && $ty_options_value[565] == '0') ? 'selected="selected"' : ''; ?>>Off</option>
<option value="Click" <?php echo (isset($ty_options_value[525]) && $ty_options_value[525] == '2' && isset($ty_options_value[565]) && $ty_options_value[565] == '0') ? 'selected="selected"' : ''; ?>>Click</option>



                </select>


            </div>
<div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Sound Effects</h3></label>

                <select class="form-control" name="soundeffect" aria-label="Sound Effects">

<?php foreach ($soundeffects_options as $key => $value) { ?>

                        <option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[559]) && $ty_options_value[559] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>

<?php } ?>

                </select>
            </div>
<!--

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">SFX</h3></label>

                <select class="form-control" name="Brail_sfx" aria-label="Sound Effects">

<?php foreach ($Brail_sfx_options as $key => $value) { ?>

                        <option value="<?php echo $key; ?>" <?php echo (!empty($ty_options_value[526]) && $ty_options_value[526] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>

<?php } ?>

                </select>
            </div>
-->

        </div>

        <!-- end first col-md-4 -->
        <br>
        <hr width="90%" size="6" align="center" color="#e5e5e5" border-color="#e5e5e5">

        <div class="col-md-12"> 
            <div class="box-header-init"> <br>
                <h2 class="dashboard-h2">App Settings</h2> 
            </div> 
        </div>
<div class="col-md-12">
       

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">WPM Goal</h3></label>

                <input type="number" min="1" max="100" class="form-control" aria-label="Words Per Minute Goal. Enter a value between 1 and 100." name="Brail_wpm_goal" value="<?php echo!empty($ty_options_value[529]) ? $ty_options_value[529] : ''; ?>" />

            </div> 


            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Accuracy Goal</h3></label>

                <input type="number" min="1" max="100" class="form-control" aria-label="Accuracy Goal. Enter a value between 1 and 100." name="Brail_accuracy_goal" value="<?php echo!empty($ty_options_value[531]) ? $ty_options_value[531] : ''; ?>" />

            </div>

   

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Setting Lock</h3></label>
                <select class="form-control" name="Brail_setting_lock" aria-label="Setting Lock">
                    <?php foreach ($Brail_setting_lock_options as $key => $value) { ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[534]) && $ty_options_value[534] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>

        

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Goal Lock</h3></label>

                <select class="form-control" name="Brail_goal_lock" aria-label="Goal Lock">

                <?php foreach ($Brail_goal_lock_options as $key => $value) { ?>

<option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[530]) && $ty_options_value[530] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>

<?php } ?>

</select>

</div>


            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Disable Typing Pet</h3></label>
                <select class="form-control" name="Brail_game_lock" aria-label="Disable Typing Pet">
                    <?php foreach ($Brail_game_lock_options as $key => $value) { ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[533]) && $ty_options_value[533] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Typing Pet Coins</h3></label>
                <input type="number" min="1" max="9999" class="form-control" aria-label="Brail Pet Coins" name="Brail_pet_coins" value="<?php echo!empty($ty_options_value[536]) ? $ty_options_value[536] : ''; ?>" />
            </div>
 
            
<!--
            <div class="form-group  col-md-4">
            
                            <label><h3 class="dashboard-h3">Braille Mode</h3></label>
            
                            <select class="form-control" name="Brail_curriculumn" aria-label="Braille Mode">
            
                                <option value="0" <?php  echo (isset($ty_options_value[550]) && $ty_options_value[550] == '0') ? 'selected="selected"' : '';   ?>>Off</option>
                                <option value="1" <?php  echo (isset($ty_options_value[550]) && $ty_options_value[550] == '1') ? 'selected="selected"' : '';   ?>>On</option>
            
                            </select>
            
                        </div> -->

        </div>
<br>
        <hr width="90%" size="6" align="center" color="#e5e5e5" border-color="#e5e5e5">
        <div class="col-md-12"> 
            <div class="box-header-init"><br> 
                <h2 class="dashboard-h2">Accessibility</h2> 
            </div> 
        </div>

        <div class="col-md-12">

             <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Simplified Visuals</h3></label>
                <select class="form-control" name="Brail_visual_fx" aria-label="Simplified Visuals">
                    <option value="1" <?php echo (isset($ty_options_value[542]) && $ty_options_value[542] == 1) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="0" <?php echo (isset($ty_options_value[542]) && $ty_options_value[542] == 0) ? 'selected="selected"' : ''; ?>>On</option>
                </select>
            </div>

<!--
<div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Spell Mode</h3></label>

                <select class="form-control" name="Brail_spell_mode" aria-label="Spell Mode">

                    <option value="0" <?php echo (isset($ty_options_value[548]) && $ty_options_value[548] == 0) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="1" <?php echo (isset($ty_options_value[548]) && $ty_options_value[548] == 1) ? 'selected="selected"' : ''; ?>>On</option>

                </select>

            </div>


            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Short Lessons</h3></label>

                <select class="form-control" name="Brail_short_lesson" aria-label="Short Lessons">

                    <option value="0" <?php echo (isset($ty_options_value[539]) && $ty_options_value[539] == 0) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="1" <?php echo (isset($ty_options_value[539]) && $ty_options_value[539] == 1) ? 'selected="selected"' : ''; ?>>On</option>

                </select>

            </div>
-->

 <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Single Word Mode</h3></label>

                <select class="form-control" name="Brail_short_lesson" aria-label="Single Word Mode">

                    <option value="0" <?php echo (isset($ty_options_value[548]) && $ty_options_value[548] == 0) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="1" <?php echo (isset($ty_options_value[548]) && $ty_options_value[548] == 1) ? 'selected="selected"' : ''; ?>>On</option>

                </select>

            </div>
<div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Smart WPM</h3></label>
                <select class="form-control" name="Brail_smart_wpm" aria-label="Smart WPM">
                    <option value="0" <?php echo (isset($ty_options_value[538]) && $ty_options_value[538] == 0) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="1" <?php echo (isset($ty_options_value[538]) && $ty_options_value[538] == 1) ? 'selected="selected"' : ''; ?>>On</option>
                </select>
            </div>
<div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Color Filter</h3></label>
                <select class="form-control" name="acc_colors" aria-label="Colours">
                    <?php foreach ($acc_colored  as $key => $value) { ?>

                        <option value="<?php echo $value; ?>" <?php echo (isset($ty_options_value[564]) && $ty_options_value[564] == $value) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>
</div>
        <div class="col-md-4 col-md-offset-4">

            <div class="form-group"><br>
                <!-- START :Added by PHP Dev 6 ON 06-09-2021 -->
                <div class="comman-button-input-option-wrap">
                <input type="submit" name="BrailSettingSubmit" value="Save Braillio Settings" id="BrailSettingSubmitBtn" aria-label="Save Brail settings button" class="dashboard-settings-btn btn-block" >
                    <input type="hidden" name="BrailSettingSubmitfield" id="BrailSettingSubmitfield" value="1" />
                    <span name="common_option" class="common-option-btn" aria-label="Save braillio settings more options" aria-role="button">
                        <i class="fa fa-chevron-down"></i>
                        </span>
                </div> 
                <div class="common-option-section-wrap" style="display: none;">
                    <ul class="new-option">
                        <li><input type="submit" name="BrailSettingSubmit" value="Save Braillio Settings For All Students" id="BrailSettingSubmitAllSettingBtn" aria-label="Save Braillio settings button for all student" class="dashboard-settings-btn btn-block all-student-settings" ></li>
                    </ul>
                </div>
                <!-- END :Added by PHP Dev 6 ON 06-09-2021 -->
            </div>

        </div>

    </form>

</div>

<!-- add Brail-modal -->
<div class="modal fade" id="add-Brail-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title color">Create New Lesson </h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="lessons-add-formBRL">

                    <div class="form-group">

                        <label>Lesson Title</label>

                        <input type="text" id="title-text-newBRL" name="titleBRL" aria-label="Lesson Name text field." class="form-control" placeholder="Type lesson title here..." required=""/>
                        <input type="hidden" id="user_idBRL" name="user_idBRL" value="<?php echo!empty($_GET['student']) ? $_GET['student'] : ''; ?>"/>
                    </div>

                    <div class="form-group">

                        <label>Lesson text</label>

                        <textarea class="form-control" name="dataBRL" aria-label="Lesson text text field. This is where you type the text for the typing lesson." id="data-newBRL" rows="5" placeholder="Type lesson text here..." required=""></textarea>

                    </div>

                </form>
            </div>
            <div class="modal-footer">

                <button id="add-new-textBRL" type="button" aria-label="Save New Lesson" class="dashboard-settings-btn btn-block">Save</button>
                
            </div>
        </div>
    </div>
</div>

<!-- add Brail model-->
<!-- add Brail test modal -->
<div class="modal fade" id="add-Brail-test-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title color">Create New Test </h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="test-lessons-add-formBRL">

                    <div class="form-group">

                        <label>Type lesson title here...</label>

                        <input type="text" id="title-text-testBRL" name="titleBRL" class="form-control" placeholder="Type lesson title here..." required=""/>

                        <input type="hidden" id="user_idBRL" name="user_idBRL" value="<?php echo!empty($_GET['student']) ? $_GET['student'] : ''; ?>"/>

                        <input type="hidden" id="user_typeBRL" name="user_typeBRL" value="1"/>

                    </div>

                    <div class="form-group">

                        <label>Type lesson text here...</label>

                        <textarea class="form-control" name="dataBRL" id="data-testBRL" rows="7" aria-label="Type lesson text here" placeholder="Type lesson text here..." required=""></textarea>

                    </div>
                    <div class="form-group">

                        <label>No of attempts...</label>

                        <input type="number" id="attemptBRL" name="attemptBRL" min="1" class="form-control" placeholder="No of attempts" required=""/>

                    </div>

                </form>
            </div>
            <div class="modal-footer">

                <button id="add-new-Brail-test" type="button" aria-label="Save New Lesson" class="dashboard-settings-btn btn-block">Save</button>
                
            </div>
        </div>
    </div>
</div>

<!-- add Brail test model-->
<!-- add Brail import model-->

<div class="modal fade" id="Brail-import-modal" aria-hidden="false" style="display: none;">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >

                    <span aria-hidden="true">&times;</span>

                </button>

                <h4 class="modal-title">Brail import</h4>

            </div>

            <div class="modal-body">

                <form method="POST" id="add-Brail-import-form">

                    <div class="form-group">

                        <label>Brail Import Code</label>

                        <input class="form-control" id="Brail_import_code" name="Brail_import_code" type="text"  placeholder="Type Brail import code here..." required="" value="" />

                        <p id="error_desk_title_alert" class="error" style="display:none;">Import code field required</p>

                    </div>
                </form>

            </div>

            <div class="modal-footer">

                <div class="col-md-12">


                    <button type="button" class="dashboard-settings-btn btn-block add-Brail-import-btn" name="add-students">Save Braillio</button>


                </div>



            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="Brail-test-import-modal" aria-hidden="false" style="display: none;">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >

                    <span aria-hidden="true">&times;</span>

                </button>

                <h4 class="modal-title">Brail Test import</h4>

            </div>

            <div class="modal-body">

                <form method="POST" id="add-Brail-test-import-form">

                    <div class="form-group">

                        <label>Brail Import Code</label>
                        <input type="hidden" name="Brail_test" class="Brail_type" value="Brail_test_lesson">
                        <input class="form-control" id="Brail_import_code" name="Brail_import_code" type="text"  placeholder="Type Brail import code here..." required="" value="" />

                        <p id="error_desk_title_alert" class="error" style="display:none;">Import code field required</p>

                    </div>
                </form>

            </div>

            <div class="modal-footer">

                <div class="col-md-12">


                    <button type="button" class="dashboard-settings-btn btn-block add-Brail-import-btn" name="add-students">Save Braillio</button>


                </div>



            </div>

        </div>

    </div>

</div>
<!--   End Brail Sectoin three   --> 

<div class="modal fade" id="edit-lessons-modalBRL">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

                <h2 class="dashboard-h2">Edit Custom Lessons</h2>

            </div>

            <div class="modal-bodyBRL">

            </div>

            <div class="modal-footer">
                <div class='export-div'>
                    <div class='Brail_code_copy col-md-6'></div>
                    <button type="button" class="dashboard-settings-btn btn-block Brail-export-modal ">Export Brail</button>
                    <p class='Brail-msg col-md-12' style="display:none;text-align: center;">Code copied To click Board</p>
                </div>  
                <button type="button" class="edit-lessons-btnBRL dashboard-settings-btn btn-block">Save changes</button>
            </div>

        </div>
        <script>
            function check_duplicate_lesson_edit(table_id) {
                var title = $('.lesson_title_edit_' + table_id).val();
                var student_id = '<?php echo $_GET['student']; ?>';
                $.ajax({
                    type: 'POST',
                    url: ADMIN_URL + 'config/check_duplicate_entry.php',
                    data: {table_id: table_id, title: title, item_name: 'Typio-BRL', student: student_id},
                    dataType: 'json',
                    success: function (result) {
                        $('.lesson_title_error_' + table_id).html(result);
                        if (result) {
                            $('.lesson_hidded_input_error').val('1');
                            $('.lesson_title_error_' + table_id).show();
                            $('.edit-lessons-btn').attr('disabled', 'disabled');
                        } else {
                            $('.lesson_hidded_input_error').val('');
                            $('.lesson_title_error_' + table_id).hide();
                            $('.edit-lessons-btn').removeAttr('disabled');
                        }
                    }
                });
            }
        </script>
        <!-- /.modal-content -->

    </div>

    <!-- /.modal-dialog -->

</div>

<!-- /.modal --><!-- /.modal -->
<div class="modal fade" id="play-history-modal">
    <div class="modal-dialog">
	<div class="modal-content" style="width:800px;">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	<span aria-hidden="true">&times;</span>
	</button>
	<h2 class="dashboard-h2">Play</h2>
	</div> 
	<div class="modal-body">
	<iframe src="<?php echo WEB_PATH; ?>apps/typio_replay/index.html" frameborder="0" height="500" width="780"></iframe>            
	</div>
	</div> 
	<!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
	</div>

<script src="<?php echo ADMIN_URL ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>  
    <script>
            var BrailDatePickerFrom = $("#BrailDatePicker1").val();
            var BrailDatePickerTo   = $("#BrailDatePicker2").val(); 
           
        $(document).ready(function() {
            $('#Braillio-Tab').click(function(){
                if($('#Braillio').attr('data-load') !== undefined) {
                    var Brail_table = $('#student_Brail_lesson_list_table').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                            "type": "POST",
                            data: {'action': 'ajax_student_Brail_lesson','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>",'data-type':"custom-lessonsBRL"},
                        },
                        "order": [[0, 'DESC']],
                        "columnDefs": [
                            {"targets": 0, "name": "title", 'searchable': false, 'orderable': true},
                            {"targets": 1, "name": "activity", 'searchable': false, 'orderable': false},
                            {"targets": 2, "name": "activity", 'searchable': false, 'orderable': false}
                            
                        ]
                    });           
                    var Brail_test_table = $('#student_Brail_test_lesson_list_table').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                            "type": "POST",
                            data: {'action': 'ajax_student_Brail_test_lesson','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>",'data-type':"typing-test-lessonsBRL"},
                        },
                        "order": [[0, 'DESC']],
                        "columnDefs": [
                            {"targets": 0, "name": "title", 'searchable': false, 'orderable': true},
                            {"targets": 1, "name": "number", 'searchable': false, 'orderable': true},
                            {"targets": 2, "name": "activity", 'searchable': false, 'orderable': false},
                            {"targets": 3, "name": "activity", 'searchable': false, 'orderable': false}
                            
                        ]
                    });
            
        
                    var weekly_table = $('#student_Brail_weekly_test_lesson_list_table').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                            "type": "POST",
                            data: {'action': 'ajax_student_weekly_Brail_test_lesson','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>"},
                        },
                        "order": [[0, 'DESC']],
                        "columnDefs": [
                            {"targets": 0, "name": "file", 'searchable': false, 'orderable': true},
                            {"targets": 1, "name": "date", 'searchable': false, 'orderable': true},
                            {"targets": 2, "name": "wpm", 'searchable': false, 'orderable': true},
                            {"targets": 3, "name": "acc", 'searchable': false, 'orderable': true},
                            {"targets": 4, "name": "err", 'searchable': false, 'orderable': true},
                            {"targets": 5, "name": "activity", 'searchable': false, 'orderable': false},  
                            {"targets": 6, "name": "activity", 'searchable': false, 'orderable': false},                   
                        ]
                    });   
                    var history_table = $('#BrailHistory_table').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                            "type": "POST",
                            data: {'action': 'ajax_student_Brail_history','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>" , 'BrailDatePickerFrom' : BrailDatePickerFrom, 'BrailDatePickerTo' : BrailDatePickerTo},
                        },
                        "order": [[0, 'DESC']],
                        "columnDefs": [
                            {"targets": 0, "name": "file", 'searchable': false, 'orderable': true},
                            {"targets": 1, "name": "date", 'searchable': false, 'orderable': true},
                            {"targets": 2, "name": "wpm", 'searchable': false, 'orderable': true},
                            {"targets": 3, "name": "acc", 'searchable': false, 'orderable': true},
                            {"targets": 4, "name": "err", 'searchable': false, 'orderable': true},
                            {"targets": 5, "name": "activity", 'searchable': false, 'orderable': false},  
                        {"targets": 6, "name": "activity", 'searchable': false, 'orderable': false},                   
                        ]
                    });
					var history_table = $('#BrailkpHistory_table').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                            "type": "POST",
                            data: {'action': 'ajax_student_Brail_history_kp','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>" , 'BrailDatePickerFrom' : BrailDatePickerFrom, 'BrailDatePickerTo' : BrailDatePickerTo},
                        },
                        "order": [[0, 'DESC']],
                        "columnDefs": [
                            {"targets": 0, "name": "file", 'searchable': false, 'orderable': true},
                            {"targets": 1, "name": "date", 'searchable': false, 'orderable': true},
                            {"targets": 2, "name": "wpm", 'searchable': false, 'orderable': true},
                            {"targets": 3, "name": "acc", 'searchable': false, 'orderable': true},
                            {"targets": 4, "name": "err", 'searchable': false, 'orderable': true},
                            {"targets": 5, "name": "activity", 'searchable': false, 'orderable': false},  
                        {"targets": 6, "name": "activity", 'searchable': false, 'orderable': false},                   
                        ]
                    });
                    
                
            
                
					var student_id = '<?php echo $student_id; ?>';
                    var typio_start_date = "<?php echo $Brail_start_date . ' 00:00:00'; ?>"; 
                    var typio_end_date = "<?php echo $Brail_end_date . ' 23:59:59'; ?>";
                    var typio_history_start_date = "<?php echo $Brail_history_start_date . ' 23:59:59'; ?>";
                    var typio_history_end_date = "<?php echo $Brail_history_end_date . ' 23:59:59'; ?>";

                    /*$.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/config-student.php',
                        data: {'action': 'ajax_TypioDataTableAverageBarChart', 'student_id': student_id, 'typio_start_date': typio_start_date, 'typio_end_date': typio_end_date}, 
                        dataType: 'json',
                        success: function (result) {
                            $('#barChartBrail1Tab2_table').html(result.displayAppTypioDataTableAverageBarChart);
                            $('#areaChartBrail1Tab2_table').html(result.displayAppTypioDataTableAverageAreaChart);
                            //$('#TypioHistoryAreaChart_table').html(result.historyTableAverageAreaChart);
                           
                        }
                    });*/
					
					

                    $.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/config-student.php',
                        data: {'action': 'ajax_HistroryBrallioDataTableAverageBarChart', 'student_id': student_id, 'typio_start_date': typio_history_start_date, 'typio_end_date': typio_history_end_date},
                        dataType: 'json',
                        success: function (result) {
                            $('#historyBrail1Tab2_table').html(result.displayAppTypioDataTableAverageBarHistoryChart);
                            $('#BrailHistoryAreaChart_table').html(result.displayAppTypioDataTableAverageAreaHistoryChart);
                            //$('#TypioHistoryAreaChart_table').html(result.historyTableAverageAreaChart);
                            $.getScript("<?php echo ADMIN_URL ?>dist/js/pages/student-overview-braillio.js", function() {
                                
                            });
                        }
                    });
					
					 $.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/config-student.php',
                        data: {'action': 'ajax_HistroryBrallioDataTableAverageBarChart_kp', 'student_id': student_id, 'typio_start_date': typio_history_start_date, 'typio_end_date': typio_history_end_date},
                        dataType: 'json',
                        success: function (result) {
                            $('#historyBrail1Tab2_table_kp').html(result.displayAppTypioDataTableAverageBarHistoryChart);
                            $('#BrailHistoryAreaChart_table_kp').html(result.displayAppTypioDataTableAverageAreaHistoryChart);
                            //$('#TypioHistoryAreaChart_table').html(result.historyTableAverageAreaChart);
                            $.getScript("<?php echo ADMIN_URL ?>dist/js/pages/student-overview-braillio-kp.js", function() {
                                
                            });
                        }
                    });
                $('#Braillio').removeAttr('data-load');
                }
				
				
				/**/
				$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_brail_history','is_ajax' : '1','student_id':student_id,'BrailDatePickerFrom':BrailDatePickerFrom,'BrailDatePickerTo':BrailDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
					$('#braillio_wpm_cst').text(response.data1['wpm']+" WPM");
					$('#typio_acc_cst').text(response.data1['acc']+"% Accuracy");
					$('#typio_err_cst').text(response.data1['combo']+" Errors");
					$('#typio_lesson_cst').text(response.recordsFiltered+" Lessons");
					
                }
            });
			
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_brail_history_kp','is_ajax' : '1','student_id':student_id,'BrailDatePickerFrom':BrailDatePickerFrom,'BrailDatePickerTo':BrailDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
					$('#braillio_wpm_cst').text(response.data1['wpm']+" WPM");
					$('#braillio_acc_cst').text(response.data1['acc']+"% Accuracy");
					$('#braillio_err_cst').text(response.data1['combo']+" Errors");
					$('#braillio_lesson_cst').text(response.recordsFiltered+" Lessons");
					
                }
            });
			/**/

            });   
            });   
			

                
          
            
            
             
    </script>