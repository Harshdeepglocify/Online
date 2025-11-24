<!--   Typio Sectoin one   -->
<?php
$typio_start_date = date('Y-m-d', strtotime('-6 days'));

$typio_end_date = date('Y-m-d');

$current_time = strtotime(date('Y-m-d'));

$typio_start_date = date('Y-m-d', strtotime('Last Monday', $current_time));

$typio_end_date = date('Y-m-d', strtotime('Next Sunday', $current_time));

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

$typio_start_date = getTimezonewiseDate($typio_start_date);
$typio_end_date = getTimezonewiseDate($typio_end_date);
$typio_history_start_date = getTimezonewiseDate($typio_history_start_date);
$typio_history_end_date = getTimezonewiseDate($typio_history_end_date);
$student_id = !empty($_GET['student']) ? $_GET['student'] : '';
$typio_error_start_date = "2019-04-23";   // use Y-m-d format
$typio_error_end_date = "2019-02-15";   // use Y-m-d format
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
?>
<div class="row">
    <br>
    <div class="col-md-8">

        <div class="box-header-init">

            <h2 class="dashboard-h2">This Week ( <?php echo date('m/d/y', strtotime($typio_start_date)); ?> - <?php echo date('m/d/y', strtotime($typio_end_date)); ?> )</h2>

        </div>

    </div>

</div>

<div class="row">

    <?php $clss_typio_weekly = empty($typio_table_data['total_row']) ? ' history_hide ' : ''; ?>

    <!-- table -->

    <div class="col-md-5 lessons_complete_list_noprint <?php echo $clss_typio_weekly; ?>" style="padding: 0px;">



        <div class="box-header">

            <h3 class="dashboard-h3"><strong><?php echo!empty($typio_table_data['total_row']) ? $typio_table_data['total_row'] : 0; ?> Lessons complete</strong></h3>

        </div>

        <div class="box-body no-padding">

            <div class="quick-ajax-response" aria-live="assertive" style="display: none;">
                <div class="alert alert-dismissible fade in" id="alert-success">
                </div>
            </div>

            <!-- <table class="table table-hover table-bordered" id="typio_this_week">
                <?php echo!empty($typio_table_data['html']) ? $typio_table_data['html'] : ''; ?>
            </table> -->
            <div class="table-responsive">
                <table id="student_typio_weekly_test_lesson_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs table-list-custom" border="2">
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

    <div class="col-md-4 lessons_chart_print <?php echo $clss_typio_weekly; ?>">

        <div class="chart chart-p-typio-one-init">

            <div id="areaChartTypio1Tab2" style="height: 325px; width: 333px;" width="333" height="250"></div>

        </div>


        <table id="areaChartTypio1Tab2_table" style="display: none;">

            <?php echo displayAppTypioDataTableAverageAreaChart($typio_start_date . ' 00:00:00', $typio_end_date . ' 23:59:59'); ?>

        </table> 

    </div>

    <?php
    if (!empty($clss_typio_weekly)) {
        echo '<div class="no-activity-msg-wrap">
			<div class="no-activity-msg">No activity this week</div>
		</div>';
    }
    ?>
    <!-- End Area Chart -->
    <!-- BAR chart -->

    <div class="col-md-3 lessons_chart_print <?php echo $clss_typio_weekly; ?>">

        <div class="chart chart-p-typio-init">

            <div id="barChartTypio1Tab2" style="height: 250px; width: 300px;" width="300" height="250"></div>

        </div>

        <table id="barChartTypio1Tab2_table" style="display: none;">

            <?php echo displayAppTypioDataTableAverageBarChart($student_id, $typio_start_date . ' 00:00:00', $typio_end_date . ' 23:59:59'); ?>

        </table>

    </div>

    <!-- End BAR chart -->
    <!-- table for print -->

    <div class="col-md-4 lessons_complete_list_print">

        <div class="box-header">

            <h3 class="dashboard-h3"><strong><?php echo!empty($typio_table_data['total_row']) ? $typio_table_data['total_row'] : 0; ?> Lessons complete</strong></h3>

        </div>

        <div class="box-body table-responsive no-padding">

            <table class="table table-hover table-bordered" id="">

                <?php echo!empty($typio_table_data['html']) ? $typio_table_data['html'] : ''; ?>

            </table>

        </div>

    </div>

    <!-- end table for print -->

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
                        <button type="button" style="margin-top:56px;" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-typio-modal">Create Custom Lesson</button>
                        <button class="new-option-btn" id="new-option-btn" aria-label="more options">
                            <i class="fa fa-chevron-down"></i>
                        </button>
                </div>
                <div class="new-option-section-wrap" style="display:none;" id="new-option-section-wrap">
                        <ul class="new-option">
                            <li><button class="dashboard-settings-btn btn-block  typio-import-modal " data-toggle="modal" data-target="#typio-import-modal">Import Typio Lesson</button></li>                            
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
                    <button type="button" style="margin-top:56px;" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-typio-test-modal">Create Typing Test</button>
                    <button class="new-option-btn" id="new-option-btn-test" aria-label="more options">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                </div>
                <div class="new-option-section-wrap" style="display:none;" id="new-option-section-wrap-test">
                        <ul class="new-option">
                            <li><button class="dashboard-settings-btn btn-block  typio-import-modal " data-toggle="modal" data-target="#typio-import-modal">Import Typio Lesson</button></li>                            
                        </ul>
                </div>
                
            </div>

        </div>

    </div>
</div>

<!--   End Typio Sectoin two   -->

<div class="row">    

    <div class="space-margin-bottom-50"></div>                        

    <div class="wt-cus-dvider"></div>

    <div class="space-margin-bottom-50"></div>

</div>

<!--   Typio Sectoin three   -->

<div class="row" id="TypioHistoryDateSection">

    <div class="col-md-12">

        <div class="box-header-init">

            <h2 class="dashboard-h2">History</h2>

        </div>

    </div>

    <form method="post" class="custom-p-form-init">

        <div class="form-group col-md-2 col-xs-12" >

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

        <div class="form-group col-md-2 col-xs-12">

            <label>To:</label>

            <div class="input-group date">

                <input id="TypioDatePicker2" name="TypioHistoryDate2" value="<?php echo date('m/d/Y', strtotime($typio_history_end_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d" aria-label="Beginning date field. Use the following format when typing a custom date range: two digit month, forward slash, two digit day, forward slash, two digit year.">

                <div class="input-group-addon">

                    <i class="fa fa-calendar" aria-hidden="true"></i>

                </div>

            </div>

            <!-- /.input group -->

        </div>

        <div class="form-group col-md-2 col-xs-12">

            <!-- btn -->

            <div class="btn-group">

                <input type="hidden" value="<?php echo $typio_history_submit_check; ?>" id="TypioHistoryDateSubmitCheck">

                <input type="button" name="TypioHistoryDateSubmit" value="Show history" id="TypioDateFilterBtn" class="dashboard-settings-btn btn-block" aria-label="Show history button">

            </div>

            <!-- end btn -->

        </div>

    </form>

</div>

<div class="row" id="TypioHistorySection">

    <?php
    $typio_history_table_data = displayAppTypioData($typio_history_start_date . ' 00:00:00', $typio_history_end_date . ' 23:59:59');
    $clss_typio_history = empty($typio_history_table_data['total_row']) ? ' history_hide ' : '';
    ?>
    <!-- table -->

    <div class="col-md-5 <?php echo $clss_typio_history; ?>" style="padding: 0px;">

        <div class="box-header">
            
        </div>
        <div class="box-body date-history table-responsive no-padding">
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

    <div class="col-md-4 <?php echo $clss_typio_history; ?>">

        <div class="chart chart-p-typiotwo-init">

            <div id="TypioHistoryAreaChart" style="height: 350px; width: 330px;" width="330" height="350"></div>

        </div>

        <table id="TypioHistoryAreaChart_table" style="display: none;">

<?php echo displayAppTypioDataTableAverageAreaChart($typio_history_start_date . ' 00:00:00', $typio_history_end_date . ' 23:59:59'); ?>

        </table>

    </div>

    <!-- End Area Chart -->
    <div class="col-md-3 lessons_chart_print <?php echo $clss_typio_history; ?>">

        <div class="chart chart-p-typio-init">

            <div id="historyTypio1Tab2" style="height: 250px; width: 300px;" width="300" height="250"></div>

        </div>

        <table id="historyTypio1Tab2_table" style="display: none;">

<?php echo displayAppTypioDataTableAverageBarChart($student_id, $typio_history_start_date . ' 00:00:00', $typio_history_end_date . ' 23:59:59'); ?>

        </table>

    </div>

    <?php
    if (!empty($clss_typio_history)) {
        echo '<div class="no-history-msg-wrap">
					<div class="no-history-msg">No history to display</div>
				</div>';
    }
    ?>
<?php $typio_table_data = displayAppTypioData($typio_history_start_date . ' 00:00:00', $typio_history_end_date . ' 23:59:59'); ?>


</div>

<!--   End Typio Sectoin three   --> 

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

//if (isset($_REQUEST['TypioSettingSubmit'])) {


if (isset($_REQUEST['TypioSettingSubmitfield'])) {

    if(isset($_REQUEST['TypioSettingSubmit']) && $_REQUEST['TypioSettingSubmit'] == 'Save Typio Settings For All Settings'){
        $query = "SELECT * FROM user WHERE `role` ='student' AND id ='".$_REQUEST['student']."' ";
        
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
            updateAppTypioSettingData($_REQUEST);
        }

    }
    else{
        updateAppTypioSettingData($_REQUEST);
    }

    $typio_setting_submit_check = 'Checked';
} else {

    $typio_setting_submit_check = 'Unhecked';
}


$ty_options_value = array();

$ty_settings_options = mysqli_query($con, "SELECT * FROM `settings` WHERE `id` = '" . $studentid . "' AND `item` IN (115,117,120,121,122,116,118,119,125,126,127,131,129,130,133,134,5,128,138,121,122,136,141,142,143,148,149,150,151,139)");

while ($settings_options = mysqli_fetch_assoc($ty_settings_options)) {

    $ty_options_value[$settings_options['item']] = $settings_options['variable'];
}

//while end

$typio_keypress_options = array(4 => 'Read', 1 => 'Pop', 2 => 'Click', 3 => 'Theme');

$typio_sfx_options = array('Default' => 'Default', 'Jungle' => 'Jungle', 'Cool' => 'Cool', 'Ninja' => 'Ninja', 'Chicken' => 'Chicken', 'Boxing' => 'Boxing', 'Vibraphone' => 'Vibraphone', 'Soft' => 'Soft', 'Silent' => 'Silent');

$typio_highlight_options = array(1 => 'On', 0 => 'Off');

$typio_goal_lock_options = array(1 => 'On', 0 => 'Off');

$typio_game_lock_options = array(1 => 'On', 0 => 'Off');

$typio_setting_lock_options = array(1 => 'On', 0 => 'Off');

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

                <label><h3 class="dashboard-h3">Font Size</h3></label>

                <select class="form-control" name="typio_font_size" aria-label="Font Size">

                    <?php
                    if (!empty($font_size_options)) {

                        foreach ($font_size_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[115]) && $ty_options_value[115] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div> 

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Font Style</h3></label>

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

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Font Color</h3></label>

                <select class="form-control" name="typio_font_color" aria-label="Font Color">

                    <?php
                    if (!empty($color_options)) {

                        foreach ($color_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[116]) && $ty_options_value[116] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div> 

            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">BG Color</h3></label>

                <select class="form-control" name="typio_bg_color" aria-label="Background Color">

                    <?php
                    if (!empty($color_options)) {

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
                    if (!empty($color_options)) {

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

            </div>

             <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Highlight</h3></label>
                <select class="form-control" name="typio_highlight" aria-label="Highlight">
                    <?php foreach ($typio_highlight_options as $key => $value) { ?>
                    
                        <option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[127]) && $ty_options_value[127] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Visual FX</h3></label>
                <select class="form-control" name="typio_visual_fx" aria-label="Visual FX">
                    <option value="0" <?php echo (isset($ty_options_value[142]) && $ty_options_value[142] == 0) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="1" <?php echo (isset($ty_options_value[142]) && $ty_options_value[142] == 1) ? 'selected="selected"' : ''; ?>>On</option>
                </select>
            </div>
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


            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Voice Rate</h3></label>

                <select class="form-control" name="typio_voice_rate"  aria-label="Voice Rate">

                    <?php
                    if (!empty($voice_rate_options)) {

                        foreach ($voice_rate_options as $key => $value) {

                            echo '<option ' . (!empty($ty_options_value[121]) && $ty_options_value[121] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
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

                            echo '<option ' . (!empty($ty_options_value[122]) && $ty_options_value[122] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>

            </div>


            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">Keypress</h3></label>

                <select class="form-control" name="typio_keypress" aria-label="Typio Keypress">

<?php foreach ($typio_keypress_options as $key => $value) { ?>

                        <option value="<?php echo $key; ?>" <?php echo (!empty($ty_options_value[125]) && $ty_options_value[125] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>

<?php } ?>

                </select>

            </div>


            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">SFX</h3></label>

                <select class="form-control" name="typio_sfx" aria-label="Sound Effects">

<?php foreach ($typio_sfx_options as $key => $value) { ?>

                        <option value="<?php echo $key; ?>" <?php echo (!empty($ty_options_value[126]) && $ty_options_value[126] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>

<?php } ?>

                </select>
            </div>

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

                <label><h3 class="dashboard-h3">Accuracy Goal</h3></label>

                <input type="number" min="1" max="100" class="form-control" aria-label="Accuracy Goal. Enter a value between 1 and 100." name="typio_accuracy_goal" value="<?php echo!empty($ty_options_value[131]) ? $ty_options_value[131] : ''; ?>" />

            </div>


            <div class="form-group  col-md-4">

                <label><h3 class="dashboard-h3">WPM Goal</h3></label>

                <input type="number" min="1" max="100" class="form-control" aria-label="Words Per Minute Goal. Enter a value between 1 and 100." name="typio_wpm_goal" value="<?php echo!empty($ty_options_value[129]) ? $ty_options_value[129] : ''; ?>" />

            </div> 
            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Smart WPM</h3></label>
                <select class="form-control" name="typio_smart_wpm" aria-label="Smart WPM">
                    <option value="0" <?php echo (isset($ty_options_value[138]) && $ty_options_value[138] == 0) ? 'selected="selected"' : ''; ?>>Off</option>
                    <option value="1" <?php echo (isset($ty_options_value[138]) && $ty_options_value[138] == 1) ? 'selected="selected"' : ''; ?>>On</option>
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

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Setting Lock</h3></label>
                <select class="form-control" name="typio_setting_lock" aria-label="Setting Lock">
                    <?php foreach ($typio_setting_lock_options as $key => $value) { ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[134]) && $ty_options_value[134] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Game Lock</h3></label>
                <select class="form-control" name="typio_game_lock" aria-label="Game Lock">
                    <?php foreach ($typio_game_lock_options as $key => $value) { ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($ty_options_value[133]) && $ty_options_value[133] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
<?php } ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Typing Pet Coins</h3></label>
                <input type="number" min="1" max="999" class="form-control" aria-label="Typio Pet Coins" name="typio_pet_coins" value="<?php echo!empty($ty_options_value[136]) ? $ty_options_value[136] : ''; ?>" />
            </div>
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
            <div class="form-group  col-md-4">
            
                            <label><h3 class="dashboard-h3">Braille Mode</h3></label>
            
                            <select class="form-control" name="typio_curriculumn" aria-label="Braille Mode">
            
                                <option value="0" <?php  echo (isset($ty_options_value[150]) && $ty_options_value[150] == '0') ? 'selected="selected"' : '';   ?>>Off</option>
                                <option value="1" <?php  echo (isset($ty_options_value[150]) && $ty_options_value[150] == '1') ? 'selected="selected"' : '';   ?>>On</option>
            
                            </select>
            
                        </div>

        </div>

        <div class="col-md-4 col-md-offset-4">

            <div class="form-group"><br>
                <!-- START :Added by PHP Dev 6 ON 06-09-2021 -->
                <div class="comman-button-input-option-wrap">
                    <input type="submit" name="TypioSettingSubmit" value="Save Typio Settings" id="TypioSettingSubmitBtn" aria-label="Save typio settings button" class="dashboard-settings-btn btn-block" >
                    <input type="hidden" name="TypioSettingSubmitfield" id="TypioSettingSubmitfield" value="1" />
                    <span name="common_option" class="common-option-btn" aria-label="more options">
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

                        <label>Type lesson title here...</label>

                        <input type="text" id="title-text-test" name="title" class="form-control" placeholder="Type lesson title here..." required=""/>

                        <input type="hidden" id="user_id" name="user_id" value="<?php echo!empty($_GET['student']) ? $_GET['student'] : ''; ?>"/>

                        <input type="hidden" id="user_type" name="user_type" value="1"/>

                    </div>

                    <div class="form-group">

                        <label>Type lesson text here...</label>

                        <textarea class="form-control" name="data" id="data-test" rows="7" aria-label="Type lesson text here" placeholder="Type lesson text here..." required=""></textarea>

                    </div>
                    <div class="form-group">

                        <label>No of attempts...</label>

                        <input type="number" id="attempt" name="attempt" min="1" class="form-control" placeholder="No of attempts" required=""/>

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

                <h4 class="modal-title">Typio import</h4>

            </div>

            <div class="modal-body">

                <form method="POST" id="add-typio-import-form">

                    <div class="form-group">

                        <label>Typio Import Code</label>

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
                <div class='export-div'>
                    <div class='typio_code_copy col-md-6'></div>
                    <button type="button" class="dashboard-settings-btn btn-block typio-export-modal ">Export Typio</button>
                    <p class='typio-msg col-md-12' style="display:none;text-align: center;">Code copied To click Board</p>
                </div>  
                <button type="button" class="edit-lessons-btn dashboard-settings-btn btn-block">Save changes</button>
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

<!-- /.modal --><!-- /.modal --><div class="modal fade" id="play-history-modal">    <div class="modal-dialog">        <div class="modal-content" style="width:800px;">            <div class="modal-header">                <button type="button" class="close" data-dismiss="modal" aria-label="Close">                    <span aria-hidden="true">&times;</span>                </button>                <h2 class="dashboard-h2">Play</h2>            </div>            <div class="modal-body">                <iframe src="<?php echo WEB_PATH; ?>apps/typio_replay/index.html" frameborder="0" height="500" width="780"></iframe>            </div>        </div>               <!-- /.modal-content -->    </div>    <!-- /.modal-dialog --></div>

<script src="<?php echo ADMIN_URL ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>  
    <script>
             var typioDatePickerFrom = $("#TypioDatePicker1").val();
            var typioDatePickerTo   = $("#TypioDatePicker2").val();
            
            $(document).ready(function() {
               
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
                       {"targets": 6, "name": "activity", 'searchable': false, 'orderable': false},                   
                    ]
                });
                
                
            });
            
            
             
    </script>