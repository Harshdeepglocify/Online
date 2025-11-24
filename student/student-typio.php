<!--   Typio Sectoin one   -->
<?php
$typio_start_date 	= date('Y-m-d', strtotime('-6 days'));

$typio_end_date 	= date('Y-m-d');

$current_time 		= strtotime(date('Y-m-d'));

$typio_start_date 	= date('Y-m-d', strtotime('Last Monday', $current_time));

$typio_end_date 	= date('Y-m-d', strtotime('Next Sunday', $current_time));

$month_start 		= date('Y-m-01'); 

$year_start 		= date('Y-01-01');

$today_date 		= date('Y-m-d');

$typio_month_start_date = $month_start;

$typio_year_start_date = $year_start;

//$typio_month_end_date = date('Y-m-d', strtotime('Next Sunday', $current_time));

//check the current day
if (date('D') != 'Mon') {
    //take the last monday
    $typio_start_date = date('Y-m-d', strtotime('Last Monday', $current_time));
} else {
    $typio_start_date = date('Y-m-d');
}

//always next saturday

if (date('D') != 'Sat') {
    $typio_end_date = date('Y-m-d', strtotime('Next Sunday', $current_time));
} else {
    $typio_end_date = date('Y-m-d');
}


if (isset($_REQUEST['TypioHistoryDateSubmit']) && ($_REQUEST['TypioHistoryDateSubmit'] == 'Show')) {

    $typio_history_start_date = date('Y-m-d', strtotime($_REQUEST['TypioHistoryDate1']));

    $typio_history_end_date = date('Y-m-d', strtotime($_REQUEST['TypioHistoryDate2']));

    $typio_history_submit_check = 'Checked';
} else {

    $typio_history_start_date = $typio_start_date; //date('Y-m-d', strtotime('-14 days'));

    $typio_history_end_date = date('Y-m-d');

    $typio_history_submit_check = 'Unchecked';
}

$typio_start_date 			= getTimezonewiseDate($typio_start_date);
$typio_end_date 			= getTimezonewiseDate($typio_end_date);
$typio_history_start_date 	= getTimezonewiseDate($typio_history_start_date);
$typio_history_end_date 	= getTimezonewiseDate($typio_history_end_date);
$typio_month_start_date 	= getTimezonewiseDate($typio_month_start_date);
$typio_year_start_date	 	= getTimezonewiseDate($typio_year_start_date);
$student_id 				= !empty($_GET['student']) ? $_GET['student'] : '';
$typio_error_start_date 	= "2019-04-23";   // use Y-m-d format
$typio_error_end_date 		= "2019-02-15";   // use Y-m-d format

$alltimestartdate 			= Typio_all_time_student_data($student_id);
$alltimestartdate 			= getTimezonewiseDate($alltimestartdate);
$today_date 				= getTimezonewiseDate($today_date);
$license_key                = $_SESSION['User']['license'];
$teacher_id                 = $_SESSION['User']['id'];
$is_admin					= checkTeacherisAdmin($license_key,$teacher_id);

			
		
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
$typio_table_data = displayAppTypioData($typio_start_date . ' 00:00:00', $typio_end_date . ' 23:59:59');


//$filename = ADMIN_URL."dist/files/Typio-Journey.txt";
/*
$journey = __DIR__ . "/../dist/files/Typio-Journey.txt";
$ol      = __DIR__ . "/../dist/files/Typio-OL.txt";

if (file_exists($journey) && file_exists($ol)) {
    // Read both files
    $content1 = file_get_contents($journey);
    $content2 = file_get_contents($ol);

    // Merge contents
    $merged = $content1 . "\n" . $content2;

    // Normalize encoding (remove bad chars)
    $merged = mb_convert_encoding($merged, 'UTF-8', 'UTF-8');

    // Split into array by lines
     $lines = preg_split('/\r\n|\r|\n/', $merged);

    // Trim lines and remove leading "??"
    $lines = array_map(function($line) {
        $line = trim($line);
        return preg_replace('/^(\?\?)+/', '', $line); // remove leading ??s
    }, $lines);
  
    $lines = array_filter($lines, fn($line) => $line !== '');

    $lines = array_values($lines);
  
    
	
	 foreach ($lines as $user) 
        {
            $error_data = isset($user[2])?$user[2]:"0";
            if($custom_date){
                $error_data = isset($user[2])?$user[2]:"0";
            }
            $log_typio_data_value = explode('|', $user);
            echo "<pre>";
			print_r($log_typio_data_value[0]);
			echo "</pre>";
           $action = '';
            $log_typio_data_html ="";

            if ($user['file'] != 'Free Type' && !empty($log_typio_data_value) && count($log_typio_data_value) > 3) {   
                $title = ($user['file']) ? $user['file'] : '';   
                $log_typio_data_html .= '';
                $log_typio_data_html .= '';
            }
			
			
			/*$exist_data = ADMIN_URL.'img/rec.png';
			$exist_img = '<img src="' . $exist_data . '" class="typio_img_kp" >';	
			$found = false; 			
			foreach ($recordsdata['data'] as $data_user) {
				if ($data_user['file'] == $user['file']) {
					$exist_data = ADMIN_URL.'img/check.png';
					$exist_img = '<img src="' . $exist_data . '" class="typio_img_kp" >';
					$found = true;
					break; 
				}
			}*
			
			if ($firstCheck && !$found) {
				$exist_img = '<span class="typio_text_kp">Next</span>'; 
				$firstCheck = false;
			}
            $data[] = array(
                '<span class="">' . $user['file'] . '<span>',
                '<span class="">' . date('m/d/y', strtotime($user['date'])) . '<span>',
                '<span class="">' . $log_typio_data_value[0] . '<span>',
                '<span class="">' . $log_typio_data_value[1] . '%<span>',
                '<span class="">' . $log_typio_data_value[2] . '<span>',
                '<span class="">'.$exist_img.'<span>',
                '<span class=""><a href="javascript:void(0)" data-type="date-history" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $user['lognr'] . '><i class="fa fa-trash-o"></i></a></span>',
                '<span class="">'.$log_typio_data_html.'</span>',
            );
			
        }
	
	
   
} else {
    
}*/
?>
<!--<div class="row">
    <br>
    <div class="col-md-8">

        <div class="box-header-init">

            <h2 class="dashboard-h2">This Week ( <?php //echo date('m/d/y', strtotime($typio_start_date)); ?> - <?php //echo date('m/d/y', strtotime($typio_end_date)); ?> )</h2>

        </div>

    </div>

</div>-->



<div class="row" id="TypioKeyboardProgressSection">

    <div class="col-md-12">

        <div class="box-header-init">

            <h2 class="dashboard-h2">Keyboard Progress</h2>

        </div>

    </div>

    <form method="post">
	<div class="custom-p-form-init-1">
	<input type="hidden" value="<?php echo date('m/d/Y', strtotime($alltimestartdate)); ?>" class="TypiokpHistoryDate1" id="TypiokpHistoryDate1">
	
          <div class="col-md-2jjj col-xs-12">
            <!--<label>From:</label>-->
            <div class="input-group date">

              <!--<input id="TypiokpDatePicker1" name="TypiokpHistoryDate1" value="<?php echo date('m/d/Y', strtotime($typio_history_start_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d" aria-label="Beginning date field. Use the following format when typing a custom date range: two digit month, forward slash, two digit day, forward slash, two digit year.">-->
                <input id="student_idkp" name="student_idkp" value="<?php echo isset($_GET['student']) ? $_GET['student'] : ''; ?>" type="hidden">
                <input id="user_type" name="user_type" value="0" type="hidden">

               <!--  <div class="input-group-addon">

                    <i class="fa fa-calendar" aria-hidden="true"></i>

                </div> -->
				</div>

           
        </div>

        <div class="col-md-jjj col-xs-12">

          <!--<label>To:</label>-->

            <div class="input-group date">

                <!--<input id="TypiokpDatePicker2" name="TypiokpHistoryDate2" value="<?php echo date('m/d/Y', strtotime($typio_history_end_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d" aria-label="Beginning date field. Use the following format when typing a custom date range: two digit month, forward slash, two digit day, forward slash, two digit year.">-->

                <!-- <div class="input-group-addon">

                    <i class="fa fa-calendar" aria-hidden="true"></i>

                </div> -->

            </div>

       

        </div> 

        <div class="cst-right-padding">

            <!-- btn -->

            <div class="btn-group">
			 <input id="student_idkp" name="student_idkp" value="<?php echo isset($_GET['student']) ? $_GET['student'] : ''; ?>" type="hidden">
                <input id="user_type" name="user_type" value="0" type="hidden">

                <input type="hidden" value="<?php echo $typio_history_submit_check; ?>" id="TypiokpHistoryDateSubmitCheck">

                  <!-- <input type="button" name="TypiokpHistoryDateSubmit" value="Show history" id="TypiokpDateFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show history button"> -->
				  <input type="button" name="TypiokpTypingJourneySubmit" value="Typing Journey" id="TypiokpTypingJourneyBtn" class="dashboard-settings-btn btn-block js_kp" aria-label="Typing Journey Button"> 
				
				
            </div>
		</div>
		<div class="cst-left-padding">
			<div class="btn-group">
			   <input type="hidden" value="<?php echo $typio_history_submit_check; ?>" id="TypiokpHistoryDateSubmitCheck1">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($typio_history_start_date));?>" id="TypiokpThisWeekFilterstart">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($typio_history_end_date)); ?>" id="TypiokpThisWeekFilterend">
			    <!-- <input type="button" name="TypiokpThisWeekSubmit" value="This Week" id="TypiokpThisWeekFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show This Week button"> -->
				
				<input type="button" name="TypiokpBasicModesSubmit" value="Basic Modes" id="TypiokpBasicModesBtn" class="dashboard-settings-btn btn-block bs_kp" aria-label="Basic Modes Button">
			</div>

            <!-- end btn -->

       </div>
</div>
    </form>



    </div>
	
	
	


   <div class="row d-flex" id="TypiokpHistorySection">

    <?php
    $typio_history_table_data = displayAppTypioData_kp($alltimestartdate . ' 00:00:00', $typio_history_end_date . ' 23:59:59'); //$alltimestartdate
    $clss_typio_history = empty($typio_history_table_data['total_row']) ? ' history_hide ' : '';
	
	
    ?>
    <!-- table -->
 
    <div class="col-md-6 d-flex align-items-stretch <?php echo $clss_typio_history; ?>" style="padding: 0px 25px 0px 15px;">

        <div class="box-header">
            
        </div>
        <div class="box-body date-history table-responsive no-padding">
            <div class="quick-ajax-response" aria-live="assertive" style="display: none;">
                <div class="alert alert-dismissible fade in" id="alert-success">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="TypiokpHistory_table" style="width:100%;">
                    <thead>
                        <tr>
                            <td><h4 class="Students-title-cs"><b></b></h4></td>
                            <td><h4 class="Students-title-cs"><b></b></h4></td>
                            <td><h4 class="Students-title-cs"><b></b></h4></td>
                            <td><h4 class="Students-title-cs"><b></b></h4></td>
                            <td><h4 class="Students-title-cs"><b></b></h4></td>
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
<div class="col-md-6 d-flex align-items-stretch cst-typio-graph" style="padding: 0px;" >
	<div class="col-md-12 <?php echo $clss_typio_history; ?>" style="padding: 0px;">
	<div class="col-md-12">
	<p class="col-md-10 text-left typio_lesson_cst_cls typio_lesson_cstkp" >Averages out of <b id="typio_lesson_cstkp"></b></p>
	 <div class="col-md-2 text-right cst-kpdev_rgt">
	 <!-- Small copy button -->
	  <img src="<?php echo ADMIN_URL; ?>/img/clone.png" alt="icon" id="cst-kpdev-typio-copy" class="cst-kpdev-typio-copy" aria-label="Copy averages data" >
	  <!-- Small copied message -->
		<span id="copyMessagekp" >Copied!</span>
	  </div>
	 </div>
		<div class="col-md-12 d-flex align-items-left">
			<div class="col-md-12 cst-dev_lftkp">
			<p class="cst-typo-fstlbl">From</p>
			<label class="font-italic date1-typiokp"><?php echo date('m/d/Y', strtotime($typio_history_start_date)); ?></label>
				<span class="mx-2">-</span>
			<label class="font-italic date2-typiokp"><?php echo date('m/d/Y', strtotime($typio_history_end_date)); ?></label>
		  </div>
		
	  
	</div>

     
	 <div class="col-md-12 typio_lesson_cstkpdiv ctm-accuracy">
		<!-- <div class="col-md-4"><p class="text-center" ><b id="typiokp_wpm_cst" ></b></p></div>
		 <div class="col-md-4"><p class="text-center" ><b id="typiokp_acc_cst" ></b></p></div>
		 <div class="col-md-4"><p class="text-center" ><b id="typiokp_err_cst" ></b></p></div>-->
		 
		 <div class="">
		 <p class="text-center" ><svg width="8" height="8" style="margin-right:5px;vertical-align: super;" viewBox="0 0 16 16" role="img" aria-label="Status: active">
		 <circle cx="8" cy="8" r="8" fill="#FF6384" /></svg>
		 <b id="typiokp_wpm_cst" ></b></p>
		 </div>
		 <div class="">
		 <p class="text-center" >
		 <svg width="8" height="8" style="margin-right:5px;vertical-align: super;" viewBox="0 0 16 16" role="img" aria-label="Status: active">
		 <circle cx="8" cy="8" r="8" fill="#00A2E8" />
		 </svg>
		 <b id="typiokp_acc_cst" ></b>
		 </p>
		 </div>
		 <div class="">
		 <p class="text-center" >
		 <svg width="8" height="8" style="margin-right:5px;vertical-align: super;" viewBox="0 0 16 16" role="img" aria-label="Status: active">
		 <circle cx="8" cy="8" r="8" fill="#FF9F40" />
		 </svg>
		 <b id="typiokp_err_cst" ></b>
		 </p>
		 </div>
	 </div>
	 
	
 </div>
    <div class="col-md-12 <?php echo $clss_typio_history; ?>">

        <div class="chart chart-p-typiotwo-init">

            <div id="TypiokpHistoryAreaChart" style="height: 350px; width:100%;" width="100%" height="350"></div>

        </div>

        <table id="TypiokpHistoryAreaChart_table" style="display: none;">

<?php //echo displayAppTypioDataTableAverageAreaChart($typio_history_start_date . ' 00:00:00', $typio_history_end_date . ' 23:59:59'); ?>

        </table>

    </div>
	<!--<div class="col-md-6 lessons_chart_print <?php echo $clss_typio_history; ?>" style="">

        <div class="chart chart-p-typio-init">

            <div id="historyTypiokp1Tab2" style="height: 250px; width: 300px;" width="300" height="250"></div>

        </div>

        <table id="historyTypiokp1Tab2_table" style="display: none;">

<?php //echo displayAppTypioDataTableAverageBarChart($student_id, $typio_history_start_date . ' 00:00:00', $typio_history_end_date . ' 23:59:59'); ?>

        </table>

    </div>-->
    </div>

    <?php
	
    if (!empty($clss_typio_history)) {
        echo '<div class="no-history-msg-wrap cst_stu-typiokp" style="">
					<div class="no-history-msg">No history to display</div>
				</div>';
    }
    ?>
<?php $typio_table_data = displayAppTypioData($typio_history_start_date . ' 00:00:00', $typio_history_end_date . ' 23:59:59'); ?>


</div>
<!--   End Typio Sectoin one   -->

<div class="row">    

    <div class="space-margin-bottom-50"></div>                        

    <div class="wt-cus-dvider"></div>

    <div class="space-margin-bottom-50"></div>

</div>

<div class="row" id="TypioHistoryDateSection">

    <div class="col-md-12">

        <div class="box-header-init">

            <h2 class="dashboard-h2">History</h2>

        </div>

    </div>

    <form method="post" class="custom-p-form-init">
		<div class="custom-p-form-init-child-1">
        <div class="" >
            <label>From:</label>
            <div class="input-group date">
                <input id="TypioDatePicker1" name="TypioHistoryDate1" value="<?php echo date('m/d/Y', strtotime($typio_history_start_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d" aria-label="Beginning date field. Use the following format when typing a custom date range: two digit month, forward slash, two digit day, forward slash, two digit year.">

                <input id="student_id" name="student_id" value="<?php echo isset($_GET['student']) ? $_GET['student'] : ''; ?>" type="hidden">
                <input id="user_type" name="user_type" value="0" type="hidden">

                <div class="input-group-addon">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                </div></div>

            <!-- /.input group -->
        </div>

        <div class="">
            <label>To:</label>
            <div class="input-group date">
                <input id="TypioDatePicker2" name="TypioHistoryDate2" value="<?php echo date('m/d/Y', strtotime($typio_history_end_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d" aria-label="Beginning date field. Use the following format when typing a custom date range: two digit month, forward slash, two digit day, forward slash, two digit year.">

                <div class="input-group-addon">

                    <i class="fa fa-calendar" aria-hidden="true"></i>

                </div>

            </div>

            <!-- /.input group -->
        </div>
		<div class="typio-cst-btn">
            <!-- btn -->
            <div class="btn-group">
                <input type="hidden" value="<?php echo $typio_history_submit_check; ?>" id="TypioHistoryDateSubmitCheck">
                <input type="button" name="TypioHistoryDateSubmit" value="Show Custom Range" id="TypioDateFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show Custom Range button">
            </div>
		</div>
		</div>
        <div class="custom-p-form-init-child">
        
		<?php if($is_admin[0] == 1 || $is_admin[0] == "1"){ ?>
			<div class="">
			<div class="btn-group">
			  
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($today_date ));?>" id="TypioTodayFilterstart">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($typio_history_end_date)); ?>" id="TypioTodayFilterend">
			   <input type="button" name="TypioTodaySubmit" value="Today" id="TypioTodayFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show Today button">
			</div>

       </div>
		<div class="">
			<div class="btn-group">
			   <input type="hidden" value="<?php echo $typio_history_submit_check; ?>" id="TypioHistoryDateSubmitCheck1">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($typio_history_start_date));?>" id="TypioThisWeekFilterstart">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($typio_history_end_date)); ?>" id="TypioThisWeekFilterend">
			   <input type="button" name="TypioThisWeekSubmit" value="This Week" id="TypioThisWeekFilterBtn" class="dashboard-settings-btn btn-block TypioActiveBtn" aria-label="Show This Week button">
			</div>
			
			
            <!-- end btn -->

       </div>
	   
	   <div class="">
			<div class="btn-group">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($typio_month_start_date ));?>" id="TypioThisMonthFilterstart">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($typio_history_end_date)); ?>" id="TypioThisMonthFilterend">
			   <input type="button" name="TypioThisMonthSubmit" value="This Month" id="TypioThisMonthFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show This Month button">
			</div>
	   </div>
	   
	   <div class="">
			<div class="btn-group">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($typio_year_start_date));?>" id="TypioThisYearFilterstart">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($typio_history_end_date)); ?>" id="TypioThisYearFilterend">
			   <input type="button" name="TypioThisYearSubmit" value="This Year" id="TypioThisYearFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show This Year button">
			</div>
			
	   </div>
	   
	   <div class="">
			<div class="btn-group">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($alltimestartdate));?>" id="TypioAllTimeFilterstart">
			   <input type="hidden" value="<?php echo date('m/d/Y', strtotime($typio_history_end_date)); ?>" id="TypioAllTimeFilterend">
			   <input type="button" name="TypioAllTimeSubmit" value="All Time" id="TypioAllTimeFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show All Time button">
			</div>
	   </div>
		<?php } ?>
        </div>

    </form>



    </div>
	
	
	


   <div class="row d-flex align-items-stretch" id="TypioHistorySection">

    <?php
    $typio_history_table_data = displayAppTypioData($typio_history_start_date . ' 00:00:00', $typio_history_end_date . ' 23:59:59');
    $clss_typio_history = empty($typio_history_table_data['total_row']) ? ' history_hide ' : '';
    ?>
    <!-- table -->
 
    <div class="col-md-6 d-flex <?php echo $clss_typio_history; ?> TypioHistory-section-one" style="padding: 0px 25px 0px 15px;">

       
        <div class="box-body date-history table-responsive no-padding flex-fill">
            <div class="quick-ajax-response" aria-live="assertive" style="display: none;">
                <div class="alert alert-dismissible fade in" id="alert-success">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="TypioHistory_table" style="width:100%;">
                    <thead>
                        <tr>
                            <td><h4 class="Students-title-cs"><b>Lesson</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>Date</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>WPM</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>ACC</b></h4></td>
                            <td><h4 class="Students-title-cs"><b>ERR</b></h4></td>
                            <td><h4 class="Students-title-cs"><b></b></h4></td>
                            <!--<td><h4 class="Students-title-cs"><b></b></h4></td>-->
                            

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
<div class="col-md-6 d-flex cst-typio-graph TypioHistory-section-two" style="padding: 0px;" >
<div class="col-md-12 <?php echo $clss_typio_history; ?>" style="padding: 0px;">
 <div class="col-md-12 ctm-averages">
 <div class="cst-dev_lft">
   <div class="col-md-12 cst-typ-padlft">
   <p class="text-left typio_lesson_cst_cls typio_lesson_main_cls" >Averages out of <b id="typio_lesson_cst"></b></p>
	 </div>
 
  </div>
 <div class="text-right cst-dev_rgt">
	<!-- Small copy button -->
	<img src="<?php echo ADMIN_URL; ?>/img/clone.png" alt="icon" id="cst-dev-typio-copy" class="cst-dev-typio-copy" aria-label="Copy averages data" >
	<!-- Small copied message -->
	<span id="copyMessage" >Copied!</span>
  </div>
  
 </div>
		<div class="col-md-12 cst-typo-lbl">
			<label class="cst-typo-fstlbl">From</label>
			<label class="fw-bold date1-typio"><?php echo date('m/d/Y', strtotime($typio_history_start_date)); ?></label>
			<span class="mx-2">-</span>
			<label class="fw-bold date2-typio"><?php echo date('m/d/Y', strtotime($typio_history_end_date)); ?></label>
	   </div>
	 <div class="ctm-accuracy">
		 <div class=""><p class="text-center" ><svg width="8" height="8" style="margin-right:5px;vertical-align: super;" viewBox="0 0 16 16" role="img" aria-label="Status: active"><circle cx="8" cy="8" r="8" fill="#FF6384" /></svg><b id="typio_wpm_cst" ></b></p></div>
		 <div class=""><p class="text-center" ><svg width="8" height="8" style="margin-right:5px;vertical-align: super;" viewBox="0 0 16 16" role="img" aria-label="Status: active"><circle cx="8" cy="8" r="8" fill="#00A2E8" /></svg><b id="typio_acc_cst" ></b></p></div>
		 <div class=""><p class="text-center" ><svg width="8" height="8" style="margin-right:5px;vertical-align: super;" viewBox="0 0 16 16" role="img" aria-label="Status: active"><circle cx="8" cy="8" r="8" fill="#FF9F40" /></svg><b id="typio_err_cst" ></b></p></div>
	 </div>
	 
	
 </div>
    <div class="col-md-12 <?php echo $clss_typio_history; ?> flex-fill" >

        <div class="chart chart-p-typiotwo-init flex-fill">

            <div id="TypioHistoryAreaChart" style="height: 350px; width:100%;" width="100%" height="350"></div>

        </div>

        <table id="TypioHistoryAreaChart_table" style="display: none;">

<?php //echo displayAppTypioDataTableAverageAreaChart($typio_history_start_date . ' 00:00:00', $typio_history_end_date . ' 23:59:59'); ?>

        </table>

    </div>
	 <!--<div class="col-md-6 lessons_chart_print <?php echo $clss_typio_history; ?>" style="">

        <div class="chart chart-p-typio-init">

            <div id="historyTypio1Tab2" style="height: 250px; width: 300px;" width="300" height="250"></div>

        </div>

        <table id="historyTypio1Tab2_table" style="display: none;">

<?php //echo displayAppTypioDataTableAverageBarChart($student_id, $typio_history_start_date . ' 00:00:00', $typio_history_end_date . ' 23:59:59'); ?>

        </table>

    </div>-->
    </div>

    <?php
	
    if (!empty($clss_typio_history)) {
        echo '<div class="no-history-msg-wrap cst_stu-typio history_hide" style="">
					<div class="no-history-msg">No history to display</div>
				</div>';
    }
    ?>
<?php $typio_table_data = displayAppTypioData($typio_history_start_date . ' 00:00:00', $typio_history_end_date . ' 23:59:59'); ?>


</div>
<!--   End Typio Sectoin one   -->

<div class="row">    

    <div class="space-margin-bottom-50"></div>                        

    <div class="wt-cus-dvider"></div>

    <div class="space-margin-bottom-50"></div>

</div>




<?php

/**

 * Display App Typio-OL Custom Lessons 

 * 

 * @param string $typio_start_date 

 * @param string $typio_end_date 

 * @return string Typio-OL table html

 */
function displayAppTypioLessonsData() {

    global $con;

    $text_lessons_table = "text";

    $text_typio_lessons_query = "SELECT * FROM `" . $text_lessons_table . "` WHERE `app` LIKE 'Typio-OL' AND `id`='" . $studentid . "'";

    $text_typio_lessons_result = mysqli_query($con, $text_typio_lessons_query);

    $text_typio_lessons_html = '<table class="table table-bordered" id="typio-custom-lessons"><tbody><tr><th style="width: 10px">#</th><th>Task</th><th style="width: 40px">Edit</th><th style="width: 40px">Delete</th></tr>';


    $text_typio_lessons_count = 0;

    while ($text_typio_lessons_row = mysqli_fetch_assoc($text_typio_lessons_result)) {

        $text_typio_lessons_count++;

        $text_typio_lessons_title = $text_typio_lessons_row['title'];

        $text_typio_lessons_data = $text_typio_lessons_row['data'];

        $text_typio_lessons_html .= '<tr>';

        $text_typio_lessons_html .= "<td>$text_typio_lessons_count.</td>";

        $text_typio_lessons_html .= "<td>$text_typio_lessons_title</td>";

        $text_typio_lessons_html .= '<td><a href="#" class="badge bg-green"><i class="fa  fa-edit (alias)" aria-label="Edit lesson" role="button"></i></a></td>';

        $text_typio_lessons_html .= '<td><a href="#" class="badge bg-red"><i class="fa fa-trash-o" aria-label="Delete" role="button"></i></a></td>';

        $text_typio_lessons_html .= '</tr>';
    }

    $text_typio_lessons_html .= '</tbody></table>';

    return $text_typio_lessons_html;
}
?>

<!--   Typio Sectoin two   -->                     

<div class="row">

    <div class="col-md-6">

        <div class="custom_lessons" >

            <div class="with-border">

                <h2 class="dashboard-h2">Custom Lessons</h2>

            </div>            

            <div class="quick-ajax-response" aria-live="assertive">
                <div class="alert alert-dismissible fade in" id="alert-success">
                </div>
            </div>

            <!-- /.box-header -->

            <div class="box-body typio-custom-lessons">

                    <div class="table-responsive">
                        <table id="student_typio_lesson_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs table-list-custom" border="2">
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
                    
               <!-- <button type="button" style="margin-top:15px;" class="dashboard-settings-btn btn-block  typio-import-modal " data-toggle="modal" data-target="#typio-import-modal">Import Typio Lesson</button>	-->
               <div class="new-option-section">    
                        <!-- <button id="add-new-text" type="button" style="margin-top:56px;" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-typio-modal" type="button">Save</button> -->
                        <button type="button" style="" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-typio-modal">Create Custom Lesson</button>
                        <button class="new-option-btn" id="new-option-btn" aria-label="Create custom lesson more options">
                            <i class="fa fa-chevron-down"></i>
                        </button>
                </div>
                <div class="new-option-section-wrap" style="display:none;" id="new-option-section-wrap">
                        <ul class="new-option">
                            <li><button class="dashboard-settings-btn btn-block  typio-import-modal " data-toggle="modal" data-target="#typio-import-modal"  data-cstheading="lesson" >Import Typio Lesson</button></li>                            
                        </ul>
                </div>
            </div>

        </div>

    </div>

    <div class="col-md-6">

        <div class="custom-p-lesson-init custom_test_lesson">

            <div class="with-border">
                <h2 class="dashboard-h2">Typing Test</h2>
            </div>
            <div class="quick-ajax-response" aria-live="assertive">
                <div class="alert alert-dismissible fade in" id="alert-success">
                </div>
            </div>
            <div class="box-body typio-test-lessons">

                <div class="table-responsive">
                    <table id="student_typio_test_lesson_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs table-list-custom" border="2">
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

                <!-- <button type="button" style="margin-top:15px;" class="dashboard-settings-btn btn-block  typio-import-modal " data-toggle="modal" data-target="#typio-import-modal">Import Typio Lesson</button>	-->
                <div class="new-option-section">    
                    <!-- <button id="add-new-text" type="button" style="margin-top:56px;" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-typio-modal" type="button">Save</button> -->
                    <button type="button" style="" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-typio-test-modal">Create Typing Test</button>
                    <button class="new-option-btn" id="new-option-btn-test" aria-label="Create typing test more options">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                </div>
                <div class="new-option-section-wrap" style="display:none;" id="new-option-section-wrap-test">
                        <ul class="new-option">
                            <li><button class="dashboard-settings-btn btn-block  typio-import-modal " data-toggle="modal" data-target="#typio-import-modal">Import Typio Test</button></li>                            
                        </ul>
                </div>
                
            </div>

        </div>

    </div>
</div>

<!--   End Typio Sectoin two   -->

<div class="row">    
 <?php $clss_typio_weekly = empty($typio_table_data['total_row']) ? ' history_hide ' : ''; ?>
    <div class="space-margin-bottom-50"></div>                        

    <div class="wt-cus-dvider"></div>

    <div class="space-margin-bottom-50"></div>

</div>

<!--   Typio Sectoin three   -->



<!--   End Typio Sectoin three   --> 



<!--   End Typio Sectoin three   

<div class="row">    

    <div class="space-margin-bottom-50"></div>                        

    <div class="wt-cus-dvider"></div>

    <div class="space-margin-bottom-50"></div>

</div>

<!--   Typio Sectoin four   -->   

<div class="row" id="TypioSettingsSection">



</div>

<?php

/* START : SET FOR ALL STUDENTS ADDED BY PHP DEV 6 ON 03-09-2021 */

// die();
// if (isset($_REQUEST['TypioSettingSubmit'])) {
//   print_r($_REQUEST['TypioSettingSubmitfield']);
if (isset($_REQUEST['TypioSettingSubmitfield'])) {

    if(isset($_REQUEST['TypioSettingSubmit']) && $_REQUEST['TypioSettingSubmit'] == 'Save Typio Settings For All Students'){
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
                    updateAppTypioSettingData($_REQUEST);
                  }
             }
        }else{
            // die('121wwww2');
            updateAppTypioSettingData($_REQUEST);
        }

    }
    else{
        // die('121eedsffdsf2');
        updateAppTypioSettingData($_REQUEST);
    }

    $typio_setting_submit_check = 'Checked';
} else {

    $typio_setting_submit_check = 'Unhecked';
}


$ty_options_value = array();

$ty_settings_options = mysqli_query($con, "SELECT * FROM `settings` WHERE `id` = '" . $studentid . "' AND `item` IN (159,165,164,163,162,161,160,115,117,120,121,122,116,118,119,125,126,127,131,129,130,133,134,5,128,138,121,122,136,141,142,143,148,149,150,151,139,167)");

while ($settings_options = mysqli_fetch_assoc($ty_settings_options)) {
//console.log($settings_options);
//print_r($settings_options);
    $ty_options_value[$settings_options['item']] = $settings_options['variable'];
}

//while end

$typio_keypress_options = array(4 => 'Read', 1 => 'Pop', 2 => 'Click', 3 => 'Theme');

$typio_sfx_options = array('Default' => 'Default', 'Jungle' => 'Jungle', 'Cool' => 'Cool', 'Ninja' => 'Ninja', 'Chicken' => 'Chicken', 'Boxing' => 'Boxing', 'Vibraphone' => 'Vibraphone', 'Soft' => 'Soft', 'Silent' => 'Silent');

$typio_highlight_options = array(1 => 'On', 0 => 'Off');

$typio_goal_lock_options = array(1 => 'On', 0 => 'Off');

$typio_game_lock_options = array(1 => 'Typio Pro', 0 => 'Typio (Pets)');

$typio_setting_lock_options = array(1 => 'On', 0 => 'Off');

$typio_simple_score_options = array(1 => 'On', 0 => 'Off');

//$typing_pet_coins = array(1 => 'On', 0 => 'Off');
// $typio_visual_fx = array(1 => 'On', 0 => 'Off');
// $typio_subtitles = array(1 => 'On', 0 => 'Off');
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

                <select class="form-control" name="typio_font_size" aria-label="Font Size">

                    <?php
                    if (!empty($font_size_options)) {

                        foreach ($font_size_options as $key => $value) {


                            echo '<option ' . (!empty($ty_options_value[161]) && $ty_options_value[161] == $value ? 'selected="selected"' : '' ) . ' value="' . $value. '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div> 

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Text Style</h3></label>

                <select class="form-control" name="typio_font_style" aria-label="Font Style">

                    <?php
                    if (!empty($font_style_options)) {

                        foreach ($font_style_options as $key => $value) {
                            echo '<option ' . (!empty($ty_options_value[117]) && $ty_options_value[117] == $key ? ' selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div> 
<!--
            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Font Color</h3></label>

                <select class="form-control" name="typio_font_color" aria-label="Font Color">

                    <?php
                    if (isset($color_options)) {

                        foreach ($color_options as $key => $value) {
                            
// print_r([$ty_options_value[116],$key]);
                            

                            echo '<option ' . (isset($ty_options_value[116]) && $ty_options_value[116] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div> 

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">BG Color</h3></label>

                <select class="form-control" name="typio_bg_color" aria-label="Background Color">

                    <?php
                    if (isset($color_options)) {

                        foreach ($color_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[118]) && $ty_options_value[118] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div> 

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Accessory Color</h3></label>

                <select class="form-control" name="typio_accessory_color" aria-label="Accessory Color">

                    <?php
                    if (isset($color_options)) {

                        foreach ($color_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[119]) && $ty_options_value[119] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div>

            <div class="form-group col-md-4 ">

                <label><h3 class="dashboard-h3">Selection Color</h3></label>

                <select class="form-control" name="typio_selection_color" aria-label="Selection Style">

                    <option value="0" <?php echo (isset($ty_options_value[143]) && $ty_options_value[143] == 0) ? 'selected="selected"' : ''; ?>>Invert</option>

                    <option value="1" <?php echo (isset($ty_options_value[143]) && $ty_options_value[143] == 1) ? 'selected="selected"' : ''; ?>>Accessory Color</option>

                </select>

            </div> 

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Subtitles</h3></label>

                <select class="form-control" name="typio_subtitles" aria-label="Subtitles">

                    <option value="0" <?php echo (isset($ty_options_value[141]) && $ty_options_value[141] == 0) ? 'selected="selected"' : ''; ?>>Off</option>

                    <option value="1" <?php echo (isset($ty_options_value[141]) && $ty_options_value[141] == 1) ? 'selected="selected"' : ''; ?>>On</option>

                </select>

            </div>


            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Visual Keyboard</h3></label>

                <select class="form-control" name="visual_keyboard" aria-label="Visual Keyboard">

                    <option value="0" <?php echo (isset($ty_options_value[128]) && $ty_options_value[128] == 0) ? 'selected="selected"' : ''; ?>>Off</option>

                    <option value="1" <?php echo (isset($ty_options_value[128]) && $ty_options_value[128] == 1) ? 'selected="selected"' : ''; ?>>On</option>

                </select>

            </div> -->
<!--
            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Highlight</h3></label>
                <select class="form-control" name="typio_highlight" aria-label="Highlight">
                    <?php foreach ($typio_highlight_options as $key => $value) { ?>
                    
                        <option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[127]) && $ty_options_value[127] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>
-->

           
<!--
            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Visual Hands</h3></label>

                <select class="form-control" name="typio_visual_hands" aria-label="Visual Hands">
                    <?php
                    if (!empty($color_options)) {

                        foreach ($color_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[149]) && $ty_options_value[149] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div>
            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Hands Style</h3></label>

                <select class="form-control" name="typio_hands_style" aria-label="Hands Style">
                    <option value="0" <?php echo (!empty($ty_options_value[151]) && $ty_options_value[151] == '0') ? "selected" : ''; ?>>Off</option>
                    <option value="1" <?php echo (!empty($ty_options_value[151]) && $ty_options_value[151] == '1') ? "selected" : ''; ?>>Solid</option>
                    <option value="2" <?php echo (!empty($ty_options_value[151]) && $ty_options_value[151] == '2') ? "selected" : ''; ?>>Clear</option>

                </select>

            </div> -->
 <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Keyboard</h3></label>

                <select class="form-control" name="typio_hands_stylenew" aria-label="Keyboard">
                    <option value="0" <?php echo (isset($ty_options_value[151]) && $ty_options_value[151] == '2' && isset($ty_options_value[128]) && $ty_options_value[128] == '1') ? "selected" : ''; ?>>Keyboard & Hands</option>
                    <option value="1" <?php echo (isset($ty_options_value[151]) && $ty_options_value[151] == '0' && isset($ty_options_value[125]) && $ty_options_value[128] == '1') ? "selected" : ''; ?>>Keyboard Only</option>
                    <option value="2" <?php echo (isset($ty_options_value[151]) && $ty_options_value[151] == '0' && isset($ty_options_value[128]) && $ty_options_value[128] == '0') ? "selected" : ''; ?>>Off</option>

                </select>

            </div>
 <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Theme</h3></label>

                <select class="form-control" name="typio_theme" aria-label="Theme">
                    <option value="Light" <?php echo (isset($ty_options_value[116]) && $ty_options_value[116] == '255, 255, 254' && isset($ty_options_value[118]) && $ty_options_value[118] == '0, 0, 8' && isset($ty_options_value[119]) && $ty_options_value[119] == '255, 0, 120' && isset($ty_options_value[160]) && $ty_options_value[160] == 'Light') ? "selected" : ''; ?>>Light</option>
                    <option value="Dark" <?php if( $ty_options_value[116] == '0, 0, 8'  && $ty_options_value[118] == '255, 255, 254'  && $ty_options_value[119] == '255, 0, 120' && $ty_options_value[160] == 'Dark'){ ?> selected <?php } ?>>Dark</option>
                    <option value="Yellow" <?php  if(isset($ty_options_value[160]) && $ty_options_value[160] == 'Yellow') { ?> selected <?php } ?>>Yellow</option>
                   <option value="Blue" <?php if(isset($ty_options_value[160]) &&  $ty_options_value[160] == 'Blue'){ ?> selected <?php } ?>>Blue</option>
                   
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

                <select class="form-control" name="typio_voice" aria-label="Voice">

                    <?php
                    if (!empty($voice_options)) {

                        foreach ($voice_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[120]) && $ty_options_value[120] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                    <?php
                     if(!empty($ty_options_value[120]) && $ty_options_value[120] !="Off" && $ty_options_value[120] !="Default") { ?>
                        <option value="<?php echo $ty_options_value[120]; ?>" selected><?php echo $ty_options_value[120]; ?></option>
                   <?php } ?>

                </select>

            </div>
<?php if($ty_options_value[120] != 'Off' ){ ?>

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Voice Rate</h3></label>
<input type='hidden' name='typio_voice_pitch1' id='typio_voice_rate1' value='' />
                <select class="form-control" name="typio_voice_rate"  aria-label="Voice Rate" >

                    <?php
                    if (!empty($voice_rate_options)) {

                        foreach ($voice_rate_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[162]) && $ty_options_value[162] == $value ? 'selected="selected"' : '' ) . ' value="' . $key . '" >' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div>


            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Voice Pitch</h3></label>

                <select class="form-control" name="typio_voice_pitch" aria-label="Voice Pitch">

                    <?php
                    if (!empty($voice_pitch_options)) {

                        foreach ($voice_pitch_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[163]) && $ty_options_value[163] ==  $value ? 'selected="selected"' : '' ) . ' value="' . $key . '" >' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div>

<?php } ?>
            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Keypress</h3></label>

                <select class="form-control" name="typio_keypress" aria-label="Typio Keypress">

<option value="Speak" <?php echo (isset($ty_options_value[125]) && $ty_options_value[125] == '4' && isset($ty_options_value[165]) && $ty_options_value[165] == '0') ? 'selected="selected"' : ''; ?>>Speak</option>
<?php if($ty_options_value[133] != 1 ){?>
<option value="Pet Speech" <?php echo (isset($ty_options_value[125]) && $ty_options_value[125] == '0' && isset($ty_options_value[165]) && $ty_options_value[165] == '1') ? 'selected="selected"' : ''; ?>>Pet Speech</option>
<?php } ?> 
<option value="Off" <?php echo (isset($ty_options_value[125]) && $ty_options_value[125] == '0' &&  isset($ty_options_value[165]) && $ty_options_value[165] == '0') ? 'selected="selected"' : ''; ?>>Off</option>
<option value="Click" <?php echo (isset($ty_options_value[125]) && $ty_options_value[125] == '2' && isset($ty_options_value[165]) && $ty_options_value[165] == '0') ? 'selected="selected"' : ''; ?>>Click</option>



                </select>


            </div>
<div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Sound Effects</h3></label>

                <select class="form-control" name="soundeffect" aria-label="Sound Effects">

<?php foreach ($soundeffects_options as $key => $value) { ?>

                        <option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[159]) && $ty_options_value[159] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>

<?php } ?>

                </select>
            </div>
<!--

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">SFX</h3></label>

                <select class="form-control" name="typio_sfx" aria-label="Sound Effects">

<?php foreach ($typio_sfx_options as $key => $value) { ?>

                        <option value="<?php echo $key; ?>" <?php echo (!empty($ty_options_value[126]) && $ty_options_value[126] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>

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

                <input type="number" min="1" max="100" class="form-control" aria-label="Words Per Minute Goal. Enter a value between 1 and 100." name="typio_wpm_goal" value="<?php echo!empty($ty_options_value[129]) ? $ty_options_value[129] : ''; ?>" />

            </div> 


            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Accuracy Goal</h3></label>

                <input type="number" min="1" max="100" class="form-control" aria-label="Accuracy Goal. Enter a value between 1 and 100." name="typio_accuracy_goal" value="<?php echo!empty($ty_options_value[131]) ? $ty_options_value[131] : ''; ?>" />

            </div>

    

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Setting Lock</h3></label>
                <select class="form-control" name="typio_setting_lock" aria-label="Setting Lock">
                    <?php foreach ($typio_setting_lock_options as $key => $value) { ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[134]) && $ty_options_value[134] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>
       

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Goal Lock</h3></label>

                <select class="form-control" name="typio_goal_lock" aria-label="Goal Lock">

                <?php foreach ($typio_goal_lock_options as $key => $value) { ?>

<option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[130]) && $ty_options_value[130] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>

<?php } ?>

</select>

</div>

            <!--<div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Typio Version</h3></label>
                <select class="form-control" name="typio_game_lock" aria-label="Typio Version">
                    <?php foreach ($typio_game_lock_options as $key => $value) { ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[133]) && $ty_options_value[133] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>-->
			<div class="form-group  col-md-4">
			  <label><h3 class="dashboard-h3">Typio Version</h3></label>
			<select class="form-control" name="typio_game_lock" aria-label="Typio Version">
    <?php foreach ($typio_game_lock_options as $key => $value) { ?>
        <option value="<?php echo $key; ?>"
            <?php 
                echo (!isset($ty_options_value[133]) || $ty_options_value[133] === '' || $ty_options_value[133] === null
                    ? ($key == 0 ? 'selected="selected"' : '')
                    : ($ty_options_value[133] == $key ? 'selected="selected"' : '')); 
            ?>>
            <?php echo $value; ?>
        </option>
    <?php } ?>
</select>
</div>
			<?php 
			//7813
			//if($ty_options_value[133] == '0'){ ?>
            <div class="form-group  col-md-4 <?php if($ty_options_value[133] == '1'){ ?>typio-hide <?php } ?>">
                <label><h3 class="dashboard-h3">Typing Pet Coins</h3></label>
                <input type="number" min="1" max="9999" class="form-control" aria-label="Typio Pet Coins" name="typio_pet_coins" value="<?php echo !empty($ty_options_value[136]) ? $ty_options_value[136] : ''; ?>" />
            </div>
<?php //}?>
            
<!--
            <div class="form-group  col-md-4">
            
                            <label><h3 class="dashboard-h3">Braille Mode</h3></label>
            
                            <select class="form-control" name="typio_curriculumn" aria-label="Braille Mode">
            
                                <option value="0" <?php  echo (isset($ty_options_value[150]) && $ty_options_value[150] == '0') ? 'selected="selected"' : '';   ?>>Off</option>
                                <option value="1" <?php  echo (isset($ty_options_value[150]) && $ty_options_value[150] == '1') ? 'selected="selected"' : '';   ?>>On</option>
            
                            </select>
            
                        </div> -->

<?php if($ty_options_value[133] == "0" || $ty_options_value[133] == 0){ ?>
				<div class="typio_reset_div col-md-12">
					  <div class="col-md-12 text-end no-padding">
							<p id="typio_reset_pro">Reset Typing Progress</p>
					  </div>
				</div>
<?php }?>

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
                <select class="form-control" name="typio_visual_fx" aria-label="Simplified Visuals">
                    <option value="1" <?php echo (isset($ty_options_value[142]) && $ty_options_value[142] == 1) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="0" <?php echo (isset($ty_options_value[142]) && $ty_options_value[142] == 0) ? 'selected="selected"' : ''; ?>>On</option>
                </select>
            </div>

<!--
<div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Spell Mode</h3></label>

                <select class="form-control" name="typio_spell_mode" aria-label="Spell Mode">

                    <option value="0" <?php echo (isset($ty_options_value[148]) && $ty_options_value[148] == 0) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="1" <?php echo (isset($ty_options_value[148]) && $ty_options_value[148] == 1) ? 'selected="selected"' : ''; ?>>On</option>

                </select>

            </div>

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Short Lessons</h3></label>

                <select class="form-control" name="typio_short_lesson" aria-label="Short Lessons">

                    <option value="0" <?php echo (isset($ty_options_value[139]) && $ty_options_value[139] == 0) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="1" <?php echo (isset($ty_options_value[139]) && $ty_options_value[139] == 1) ? 'selected="selected"' : ''; ?>>On</option>

                </select>

            </div>


-->
 <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Single Word Mode</h3></label>

                <select class="form-control" name="typio_short_lesson" aria-label="Single Word Mode">

                    <option value="0" <?php echo (isset($ty_options_value[148]) && $ty_options_value[148] == 0) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="1" <?php echo (isset($ty_options_value[148]) && $ty_options_value[148] == 1) ? 'selected="selected"' : ''; ?>>On</option>

                </select>

            </div>
<div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Smart WPM</h3></label>
                <select class="form-control" name="typio_smart_wpm" aria-label="Smart WPM">
                    <option value="0" <?php echo (isset($ty_options_value[138]) && $ty_options_value[138] == 0) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="1" <?php echo (isset($ty_options_value[138]) && $ty_options_value[138] == 1) ? 'selected="selected"' : ''; ?>>On</option>
                </select>
            </div>
<div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Color Filter</h3></label>
                <select class="form-control" name="acc_colors" aria-label="Colours">
                    <?php foreach ($acc_colored  as $key => $value) { ?>

                        <option value="<?php echo $value; ?>" <?php echo (isset($ty_options_value[164]) && $ty_options_value[164] == $value) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>
			
			
			<div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Simple Score</h3></label>
                <select class="form-control" name="acc_simple_score" aria-label="Simple Score">
                    <?php foreach ($typio_simple_score_options as $key => $value) { ?>
                         <option value="<?php echo $key; ?>" 
					 <?php 
							echo (!isset($ty_options_value[167]) || $ty_options_value[167] === '' || $ty_options_value[167] === null
								? ($key == 0 ? 'selected="selected"' : '')
								: ($ty_options_value[167] == $key ? 'selected="selected"' : '')); 
						?>>
						<?php echo $value; ?>
				</option>
					<?php } ?>
                </select>
            </div>
</div>
        <div class="col-md-4 col-md-offset-4">

            <div class="form-group"><br>
                <!-- START :Added by PHP Dev 6 ON 06-09-2021 -->
                <div class="comman-button-input-option-wrap">
                <input type="submit" name="TypioSettingSubmit" value="Save Typio Settings" id="TypioSettingSubmitBtn" aria-label="Save typio settings button" class="dashboard-settings-btn btn-block" >
                    <input type="hidden" name="TypioSettingSubmitfield" id="TypioSettingSubmitfield" value="1" />
                    <span name="common_option" class="common-option-btn" aria-label="Save Typio Settings more options" aria-role="button">
                        <i class="fa fa-chevron-down"></i>
                        </span>
                </div> 
                <div class="common-option-section-wrap" style="display: none;">
                    <ul class="new-option">
                        <li><input type="submit" name="TypioSettingSubmit" value="Save Typio Settings For All Students" id="TypioSettingSubmitAllSettingBtn" aria-label="Save typio settings button for all student" class="dashboard-settings-btn btn-block all-student-settings" ></li>
                    </ul>
                </div>
                <!-- END :Added by PHP Dev 6 ON 06-09-2021 -->
            </div>

        </div>

    </form>

</div>

<!-- add typio-modal -->
<div class="modal fade" id="add-typio-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title color">Create New Lesson </h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="lessons-add-form">

                    <div class="form-group">

                        <label>Lesson Title</label>

                        <input type="text" id="title-text-new" name="title" aria-label="Lesson Name text field." class="form-control" placeholder="Type lesson title here..." required=""/>
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo!empty($_GET['student']) ? $_GET['student'] : ''; ?>"/>
                    </div>

                    <div class="form-group">

                        <label>Lesson text</label>

                        <textarea class="form-control" name="data" aria-label="Lesson text text field. This is where you type the text for the typing lesson." id="data-new" rows="5" placeholder="Type lesson text here..." required=""></textarea>

                    </div>

                </form>
            </div>
            <div class="modal-footer">

                <button id="add-new-text" type="button" aria-label="Save New Lesson" class="dashboard-settings-btn btn-block">Save</button>
                
            </div>
        </div>
    </div>
</div>

<!-- add typio model-->
<!-- add typio Delete Settings modal -->

<div class="modal fade" id="typio-delete-settngs-modal" aria-hidden="false" style="display: none;">

    <div class="modal-dialog">

        <div class="modal-content">

           

            <div class="modal-body">

                <form method="POST" id="typio-delete-settngs-form">

                    <div class="form-group">

                        <label>Do you want to reset your student's Typing Journey?
                        They will start over at the first lesson. This cannot be undone
                        Previous typing history will still be available in the History section of your Teacher Dashboard.</label>

                    </div>
                </form>

            </div>

            <div class="modal-footer">

                <div class="col-md-12">


                   
					<input type="hidden" value="<?php echo $_REQUEST['student'];?>" class="data_delete_student_typio" id="data_delete_student_typio">
                    <button type="button" class="dashboard-settings-btn btn-block delete-typio-btn" name="confirm-delete-app" id="confirm-delete-app-btn" aria-label="Confirm Delete Button" >Delete</button>
					 <button type="button" data-dismiss="modal" aria-label="Close" class="close dashboard-settings-btn btn-block cancel-delete-typio-btn" name="cancel-delete-app">Cancel</button>
					<label class="delet_data_typio history_hide"  id="delet_data_typio_id" ></label>

                </div>



            </div>

        </div>

    </div>

</div>

<!-- add typio model-->
<!-- add typio test modal -->
<div class="modal fade" id="add-typio-test-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title color">Create New Test </h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="test-lessons-add-form">

                    <div class="form-group">

                        <label>Test Title</label>

                        <input type="text" id="title-text-test" name="title" class="form-control" placeholder="Test title…" required=""/>

                        <input type="hidden" id="user_id" name="user_id" value="<?php echo!empty($_GET['student']) ? $_GET['student'] : ''; ?>"/>

                        <input type="hidden" id="user_type" name="user_type" value="1"/>

                    </div>

                    <div class="form-group">

                        <label>Test Text</label>

                        <textarea class="form-control" name="data" id="data-test" rows="7" aria-label="Type lesson text here" placeholder="Type test text here..." required=""></textarea>

                    </div>
                    <div class="form-group">

                        <label>Attempts</label>

                        <input type="number" id="attempt" name="attempt" min="1" class="form-control" placeholder="Attempts" required=""/>

                    </div>

                </form>
            </div>
            <div class="modal-footer">

                <button id="add-new-typio-test" type="button" aria-label="Save New Lesson" class="dashboard-settings-btn btn-block">Save</button>
                
            </div>
        </div>
    </div>
</div>

<!-- add typio test model-->
<!-- add typio import model-->

<div class="modal fade" id="typio-import-modal" aria-hidden="false" style="display: none;">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >

                    <span aria-hidden="true">&times;</span>

                </button>

                <h4 class="modal-title" id="modal_cst_test" style="display:none;" >Import Typio Test</h4>
                <h4 class="modal-title" id="modal_cst_lesson" style="display:none;" >Import Typio Lesson</h4>

            </div>

            <div class="modal-body">

                <form method="POST" id="add-typio-import-form">

                    <div class="form-group">

                        <label>Share Code</label>

                        <input class="form-control" id="typio_import_code" name="typio_import_code" type="text"  placeholder="Type or paste Share Code here..." required="" value="" />

                        <p id="error_desk_title_alert" class="error" style="display:none;">Import code field required</p>

                    </div>
                </form>

            </div>

            <div class="modal-footer">

                <div class="col-md-12">


                    <button type="button" class="dashboard-settings-btn btn-block add-typio-import-btn" name="add-students">Import</button>


                </div>



            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="typio-test-import-modal" aria-hidden="false" style="display: none;">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >

                    <span aria-hidden="true">&times;</span>

                </button>

                <h4 class="modal-title">Typio Test import</h4>

            </div>

            <div class="modal-body">

                <form method="POST" id="add-typio-test-import-form">

                    <div class="form-group">

                        <label>Typio Import Code</label>
                        <input type="hidden" name="typio_test" class="typio_type" value="typio_test_lesson">
                        <input class="form-control" id="typio_import_code" name="typio_import_code" type="text"  placeholder="Type Typio import code here..." required="" value="" />

                        <p id="error_desk_title_alert" class="error" style="display:none;">Import code field required</p>

                    </div>
                </form>

            </div>

            <div class="modal-footer">

                <div class="col-md-12">


                    <button type="button" class="dashboard-settings-btn btn-block add-typio-import-btn" name="add-students">Save Typio</button>


                </div>



            </div>

        </div>

    </div>

</div>
<!--   End Typio Sectoin four   --> 

<div class="modal fade" id="edit-lessons-modal">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

                <h2 class="dashboard-h2">Edit Custom Lessons</h2>

            </div>

            <div class="modal-body">

            </div>

            <div class="modal-footer">
			<button type="button" class="edit-lessons-btn dashboard-settings-btn btn-block">Save changes</button>
                <div class='export-div'>
                    <div class='typio_code_copy col-md-6' style="display:none!important;"></div>
                    <button type="button" class="dashboard-settings-btn btn-block typio-export-modal ">Copy Share Code to Clipboard</button>
                    <p class='typio-msg col-md-12' style="display:none;text-align: center;">Code copied To click Board</p>
                </div>  
                
            </div>

        </div>
        <script>
            function check_duplicate_lesson_edit(table_id) {
                var title = $('.lesson_title_edit_' + table_id).val();
                var student_id = '<?php echo $_GET['student']; ?>';
                $.ajax({
                    type: 'POST',
                    url: ADMIN_URL + 'config/check_duplicate_entry.php',
                    data: {table_id: table_id, title: title, item_name: 'Typio-OL', student: student_id},
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
	<iframe src="<?php echo WEB_PATH; ?>apps/typio_replay/index.html" frameborder="0" height="500" width="780"></iframe>            </div>
	</div>
	<!-- /.modal-content --> 
	</div>
    <!-- /.modal-dialog --></div>

<script src="<?php echo ADMIN_URL ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>  
    <script>
            var typioDatePickerFrom 	= $("#TypioDatePicker1").val();
            var typioDatePickerkp	 	= $("#TypiokpHistoryDate1").val();
			var typioDatePickerTo   	= $("#TypioDatePicker2").val();
			var student_idkp   			= $("#student_idkp").val();
			var Typiokpend  			= $("#TypiokpThisWeekFilterend").val();
           
function trigger_vpitch(value){

$('#typio_voice_rate1').val(value);

}
            $(document).ready(function() {
				/*  $('#TypioThisWeekFilterBtn').click(function()
            {
					/*var student_id = '<?php echo $student_id; ?>';
                    var typio_start_date = "<?php echo $typio_start_date . ' 00:00:00'; ?>"; 
                    var typio_end_date = "<?php echo $typio_end_date . ' 23:59:59'; ?>";
                    var typio_history_start_date = "<?php echo $typio_history_start_date . ' 23:59:59'; ?>";
                    var typio_history_end_date = "<?php echo $typio_history_end_date . ' 23:59:59'; ?>";

                    $.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/config-student.php',
                        data: {'action': 'ajax_TypioDataTableAverageBarChart', 'student_id': student_id, 'typio_start_date': typio_start_date, 'typio_end_date': typio_end_date},
                        dataType: 'json',
                        success: function (result) {
							console.log(result);
                            $('#barChartTypio1Tab2_table').html(result.displayAppTypioDataTableAverageBarChart);
                            $('#areaChartTypio1Tab2_table').html(result.displayAppTypioDataTableAverageAreaChart);
                            //$('#TypioHistoryAreaChart_table').html(result.historyTableAverageAreaChart);
                           
                        }
                    });
			});*/

            $('#typioTab').click(function()
            {
                if ($('#typio').attr('data-load') !== undefined) {
                
                    var typio_table = $('#student_typio_lesson_list_table').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                            "type": "POST",
                            data: {'action': 'ajax_student_typio_lesson','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>",'data-type':"custom-lessons"},
                        },
                        "order": [[0, 'DESC']],
                        "columnDefs": [
                            {"targets": 0, "name": "title", 'searchable': false, 'orderable': true},
                            {"targets": 1, "name": "activity", 'searchable': false, 'orderable': false},
                            {"targets": 2, "name": "activity", 'searchable': false, 'orderable': false}
                            
                        ]
                    });           
                    var typio_test_table = $('#student_typio_test_lesson_list_table').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                            "type": "POST",
                            data: {'action': 'ajax_student_typio_test_lesson','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>",'data-type':"typing-test-lessons"},
                        },
                        "order": [[0, 'DESC']],
                        "columnDefs": [
                            {"targets": 0, "name": "title", 'searchable': false, 'orderable': true},
                            {"targets": 1, "name": "number", 'searchable': false, 'orderable': true},
                            {"targets": 2, "name": "activity", 'searchable': false, 'orderable': false},
                            {"targets": 3, "name": "activity", 'searchable': false, 'orderable': false}
                            
                        ]
                    });
                
                
                    var weekly_table = $('#student_typio_weekly_test_lesson_list_table').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                            "type": "POST",
                            data: {'action': 'ajax_student_weekly_typio_test_lesson','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>"},
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
                    var history_table = $('#TypioHistory_table').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                            "type": "POST",
                            data: {'action': 'ajax_student_typio_history','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>" , 'typioDatePickerFrom' : typioDatePickerFrom, 'typioDatePickerTo' : typioDatePickerTo},
                        },
                        "order": [[0, 'DESC']],
                        "columnDefs": [
                            {"targets": 0, "name": "file", 'searchable': false, 'orderable': true},
                            {"targets": 1, "name": "date", 'searchable': false, 'orderable': true},
                            {"targets": 2, "name": "wpm", 'searchable': false, 'orderable': true},
                            {"targets": 3, "name": "acc", 'searchable': false, 'orderable': true},
                            {"targets": 4, "name": "err", 'searchable': false, 'orderable': true},
                            {"targets": 5, "name": "activity", 'searchable': false, 'orderable': false},  
                                              
                        ]
                    });
					
					
					 /*var history_table1 = $('#TypiokpHistory_table').DataTable({
					 //var history_table1 = $('#TypioHistory_table').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                            "type": "POST",
                            data: {'action': 'ajax_student_typio_history_kp','is_ajax' : '1','student_id':student_idkp,'typioDatePickerFrom':typioDatePickerkp, 'typioDatePickerTo' : typioDatePickerTo},
                        },
                        "order": [[0, 'DESC']],
                        "columnDefs": [
                            {"targets": 0, "name": "file", 		'searchable': false, 'orderable': true},
                            {"targets": 1, "name": "date", 		'searchable': false, 'orderable': true},
                            {"targets": 2, "name": "wpm", 		'searchable': false, 'orderable': true},
                            {"targets": 3, "name": "acc", 		'searchable': false, 'orderable': true},
                            {"targets": 4, "name": "err", 		'searchable': false, 'orderable': true},
                            {"targets": 5, "name": "activity", 	'searchable': false, 'orderable': false},  
                            {"targets": 6, "name": "activity", 	'searchable': false, 'orderable': false},  
                                              
                        ]
                    });*/

                    var student_id = '<?php echo $student_id; ?>';
                    var typio_start_date = "<?php echo $typio_start_date . ' 00:00:00'; ?>"; 
                    var typio_end_date = "<?php echo $typio_end_date . ' 23:59:59'; ?>";
                    var typio_history_start_date = "<?php echo $typio_history_start_date . ' 23:59:59'; ?>";
                    var typio_history_end_date = "<?php echo $typio_history_end_date . ' 23:59:59'; ?>";

                    $.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/config-student.php',
                        data: {'action': 'ajax_TypioDataTableAverageBarChart', 'student_id': student_id, 'typio_start_date': typio_start_date, 'typio_end_date': typio_end_date},
                        dataType: 'json',
                        success: function (result) {
                            $('#barChartTypio1Tab2_table').html(result.displayAppTypioDataTableAverageBarChart);
                            $('#areaChartTypio1Tab2_table').html(result.displayAppTypioDataTableAverageAreaChart);
                            //$('#TypioHistoryAreaChart_table').html(result.historyTableAverageAreaChart);
                           
                        }
                    });
					
					

                    $.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/config-student.php',
                        data: {'action': 'ajax_HistroryTypioDataTableAverageBarChart', 'student_id': student_id, 'typio_start_date': typio_history_start_date, 'typio_end_date': typio_history_end_date},
                        dataType: 'json',
                        success: function (result) {
                            $('#historyTypio1Tab2_table').html(result.displayAppTypioDataTableAverageBarHistoryChart);
                            $('#TypioHistoryAreaChart_table').html(result.displayAppTypioDataTableAverageAreaHistoryChart);
                            //$('#TypioHistoryAreaChart_table').html(result.historyTableAverageAreaChart);
                            $.getScript("<?php echo ADMIN_URL ?>dist/js/pages/student-overview-typio.js", function() {
                                
                            });
                        }
                    });
					
					 $.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/config-student.php',
                        data: {'action': 'ajax_TypioDataTableAverageBarChart_kp', 'student_id': student_idkp, 'typio_start_date': typioDatePickerkp, 'typio_end_date': typio_end_date},
                        dataType: 'json',
                        success: function (result) {
							console.log(result);
                            $('#historyTypiokp1Tab2_table').html(result.displayAppTypioDataTableAverageBarChart);
                            $('#areaChartTypio1Tab2_table').html(result.displayAppTypioDataTableAverageAreaChart);
                            //$('#TypioHistoryAreaChart_table').html(result.historyTableAverageAreaChart);
                            $.getScript("<?php echo ADMIN_URL ?>dist/js/pages/student-overview-typio-kp.js", function() {
                                
                            });
                        }
                    });
				// TypiokpHistoryAreaChart TypiokpHistoryAreaChart_table
					$.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/config-student.php',
                        data: {'action': 'ajax_HistroryTypioDataTableAverageBarChart_kp', 'student_id': student_idkp, 'typio_start_date': typioDatePickerkp, 'typio_end_date': typio_history_end_date},
                        dataType: 'json',
                        success: function (result) {
							//var response = $.parseJSON(result);
							//console.log('*********++');
							console.log(result);
                            $('#TypiokpHistoryAreaChart').html(result.displayAppTypioDataTableAverageBarHistoryChart);
                            $('#TypiokpHistory_table').html(result.displayAppTypioDataTableAverageAreaHistoryChart);
                            //$('#TypioHistoryAreaChart_table').html(result.historyTableAverageAreaChart);
                            $.getScript("<?php echo ADMIN_URL ?>dist/js/pages/student-overview-typio-kp.js", function() {
                                
                            });
                        }
                    });
					
                $('#typio').removeAttr('data-load');
                }
				 $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data_kp', 'start_date': typioDatePickerkp, 'end_date': Typiokpend, 'student_id': student_idkp},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypiokpHistorySection').find('#TypiokpHistoryAreaChart_table').html(response.html);
                   // $('#TypioKeyboardProgressSection').removeClass('ajax_loading');
                }
              
				//var charts = $('#TypiokpHistoryAreaChart').highcharts();
              //  var options = charts.options;
               // charts = new Highcharts.Chart(options);
                //charts.redraw();
				
				

            }
        });
				
				
				
				
				$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
					if(response.recordsTotal != 0 || response.recordsTotal != "0"){
						$('#typio_wpm_cst').text(response.data1['wpm']+" WPM");
						$('#typio_acc_cst').text(response.data1['accu']+"% Accuracy");
						$('#typio_err_cst').text(response.data1['combo']+" Errors");
						$('#typio_lesson_cst').text(response.recordsFiltered+" Lessons");
						$('.TypioHistory-section-one').removeClass('history_hide');
						$('.TypioHistory-section-two').removeClass('history_hide');
						$('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
						$('#TypioTodayFilterBtn').removeClass('TypioActiveBtn');
						$('#TypioThisWeekFilterBtn').addClass('TypioActiveBtn');
						$('#TypioThisMonthFilterBtn').removeClass('TypioActiveBtn');
						$('#TypioThisYearFilterBtn').removeClass('TypioActiveBtn');
						$('#TypioAllTimeFilterBtn').removeClass('TypioActiveBtn');
					}else{
						$('.TypioHistory-section-one').addClass('history_hide');
						$('.TypioHistory-section-two').addClass('history_hide');
						$('#TypioHistorySection .cst_stu-typio').removeClass('history_hide');
						$('#TypioTodayFilterBtn').removeClass('TypioActiveBtn');
						$('#TypioThisWeekFilterBtn').addClass('TypioActiveBtn');
						$('#TypioThisMonthFilterBtn').removeClass('TypioActiveBtn');
						$('#TypioThisYearFilterBtn').removeClass('TypioActiveBtn');
						$('#TypioAllTimeFilterBtn').removeClass('TypioActiveBtn');
					}
					//console.log(response.recordsFiltered+'--');
					//console.log(response.data1['wpm']);
					//console.log(response.data1['wpm']);
                   // var history_table = $('#TypioHistory_table').DataTable();
                    //history_table.ajax.reload(null, false);
                }
            });
			
			
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
					$('.typio_lesson_cstkp #typiokp_wpm_cst').text(response.data1['wpm']+" WPM");
					$('.typio_lesson_cstkp #typiokp_acc_cst').text(response.data1['accu']+"% Accuracy");
					$('.typio_lesson_cstkp #typiokp_err_cst').text(response.data1['combo']+" Errors");
					$('.typio_lesson_cstkp #typio_lesson_cst').text(response.recordsFiltered+" Lessons");
					//console.log(response.recordsFiltered+'--');
					//console.log(response.data1['wpm']);
					//console.log(response.data1['wpm']);
                   // var history_table = $('#TypioHistory_table').DataTable();
                    //history_table.ajax.reload(null, false);
                }
            });
			
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history_kp','is_ajax' : '1','student_id':student_idkp,'typioDatePickerFrom':typioDatePickerkp,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
				//console.log(response);
					$('#typiokp_wpm_cst').text(response.data2['wpm']+" WPM");
					$('#typiokp_acc_cst').text(response.data2['accu']+"% Accuracy");
					$('#typiokp_err_cst').text(response.data2['combo']+" Errors");
					$('#typio_lesson_cstkp').text(response.recordsFiltered+" Lessons");
					
                }
            });

            });   
			/*$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
					$('#typio_wpm_cst').text(response.data1['wpm']+" WPM");
					$('#typio_acc_cst').text(response.data1['accu']+"% Accuracy");
					$('#typio_err_cst').text(response.data1['combo']+" Errors");
					$('#typio_lesson_cst').text(response.recordsFiltered+" Lessons");
					//console.log(response.recordsFiltered+'--');
					//console.log(response.data1['wpm']);
					//console.log(response.data1['wpm']);
                   // var history_table = $('#TypioHistory_table').DataTable();
                    //history_table.ajax.reload(null, false);
                }
            });*/

                
            });
            
            
             
    </script>