<?php

include "config.php";

if (isset($_POST['action']) && $_POST['action'] == 'load_typio_table_data') {
    // Your PHP function logic
    $data = ['status' => 'success', 'message' => 'Data processed successfully'];

    // Return the result as JSON
    echo json_encode($data);
    exit;
}

function custom_date($start_date){
    $custom_date = "2020-08-01";   /* Y-m-d format */
    if(!empty($start_date)){
        $start_date = date('Y-m-d', strtotime($start_date));
        if($custom_date <= $start_date){
            return true;
        }
    }    
    return false;
}
function Typio_average_chart_write($student_id = 0, $typio_start_date = 0, $typio_end_date = 0) {
    global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;
	$_average_scale = array();

    $log_data_table = "log";
    $log_data_table_wpm_title = ' WPM';
    $log_data_table_accuracy_title = '% Accuracy';
    
    $custom_date =  custom_date($typio_start_date);
    $log_data_table_combo_title = " Combo";
    if($custom_date){
        $log_data_table_combo_title = " Errors";
    }
    
    $log_data_table_wpm_value = 0;
    $log_data_table_accuracy_value = 0;
    $log_data_table_combo_value = 0;

    $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE `app` LIKE 'Typio-OL' AND id='" . $studentid . "'";
    if (($typio_start_date != 0) && ($typio_end_date != 0)) {
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }

    $log_typio_data = mysqli_query($con, $log_typio_query);
    $log_typio_data_count = 0;
    $log_typio_data_html = '';

    while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
        $log_typio_data_count++;
        $log_typio_data_value = explode('|', $log_typio_data_row['data']);
        $log_data_table_wpm_value += ($log_typio_data_value[0]) ? $log_typio_data_value[0] : 0;
        $log_data_table_accuracy_value += ($log_typio_data_value[1]) ? $log_typio_data_value[1] : 0;
        $log_data_table_combo_value += ($log_typio_data_value[2]) ? $log_typio_data_value[2] : 0;
    }
    $combo_data = $log_data_table_combo_value;
    if($custom_date){
        $combo_data = $log_data_table_combo_value;
    }
    $_average_scale['start_date'] = !empty($typio_start_date) ? date('m/d/y', strtotime($typio_start_date)) : '';
    $_average_scale['end_date'] = !empty($typio_end_date)? date('m/d/y', strtotime($typio_end_date)) : '';
    $_average_scale['WPM'] = !empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0';
    $_average_scale['Accuracy'] = !empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ;
    $_average_scale['Combo'] = !empty($combo_data) ? floor(($combo_data / $log_typio_data_count)) : '0' ;

    return $_average_scale;
}
function Typio_average_chart_write_kp($student_id = 0, $typio_start_date = 0, $typio_end_date = 0) {
    global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;
	$_average_scale = array();

    $log_data_table = "log";
    $log_data_table_wpm_title = ' WPM';
    $log_data_table_accuracy_title = '% Accuracy';
    
    $custom_date =  custom_date($typio_start_date);
    $log_data_table_combo_title = " Combo";
    if($custom_date){
        $log_data_table_combo_title = " Errors";
    }
    
    $log_data_table_wpm_value = 0;
    $log_data_table_accuracy_value = 0;
    $log_data_table_combo_value = 0;

    $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE `app` LIKE 'Typio-OL' AND id='" . $studentid . "'";
    if (($typio_start_date != 0) && ($typio_end_date != 0)) {
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }

    $log_typio_data = mysqli_query($con, $log_typio_query);
    $log_typio_data_count = 0;
    $log_typio_data_html = '';

    while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
        $log_typio_data_count++;
        $log_typio_data_value = explode('|', $log_typio_data_row['data']);
        $log_data_table_wpm_value += ($log_typio_data_value[0]) ? $log_typio_data_value[0] : 0;
        $log_data_table_accuracy_value += ($log_typio_data_value[1]) ? $log_typio_data_value[1] : 0;
        $log_data_table_combo_value += ($log_typio_data_value[2]) ? $log_typio_data_value[2] : 0;
    }
    $combo_data = $log_data_table_combo_value;
    if($custom_date){
        $combo_data = $log_data_table_combo_value;
    }
    $_average_scale['start_date'] = !empty($typio_start_date) ? date('m/d/y', strtotime($typio_start_date)) : '';
    $_average_scale['end_date'] = !empty($typio_end_date)? date('m/d/y', strtotime($typio_end_date)) : '';
    $_average_scale['WPM'] = !empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0';
    $_average_scale['Accuracy'] = !empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ;
    $_average_scale['Combo'] = !empty($combo_data) ? floor(($combo_data / $log_typio_data_count)) : '0' ;

    return $_average_scale;
}


// *
//  * Display App Typio-OL data
//  * 
//  * @param string $typio_start_date 
//  * @param string $typio_end_date 
//  * @return string Typio-OL table html
function displayAppBraiillioData($Braiillio_start_date = 0, $Braiillio_end_date = 0, $student_id = '') {

global $con, $studentid;
$table_data = array();
$studentid = !empty($student_id) ? $student_id : $studentid;
$log_Braiillio_query = "SELECT * FROM `log` WHERE (`app` LIKE 'Braiillio-OL' OR `app` LIKE 'Braiillio-Journey' OR `app` LIKE 'Braiillio-BRL')  AND id ='" . $studentid . "'";

$custom_date = '';

$custom_date =  custom_date($Braiillio_start_date);
    
$lbl = "COM";
if($custom_date){
    $lbl = "ERR";
}
if (( $Braiillio_start_date != 0 ) && ( $Braiillio_end_date != 0 )) {
    
    $Braiillio_start_date = date('Y-m-d', strtotime($Braiillio_start_date)) . ' 00:00:00';
    $Braiillio_end_date = date('Y-m-d', strtotime($Braiillio_end_date)) . ' 23:59:59';
    $log_Braiillio_query .= " AND `date` BETWEEN '" . $Braiillio_start_date . "' AND '" . $Braiillio_end_date . "' ";
}

$log_Braiillio_data = mysqli_query($con, $log_Braiillio_query);
$_average_scale = array();

$_average_scale = Braiillio_average_chart_write($student_id = 0, $Braiillio_start_date, $Braiillio_end_date);
$_average_scale['start_date'] = !empty($Braiillio_start_date) ? date('m/d/y', strtotime($Braiillio_start_date)) : '';
$_average_scale['end_date'] = !empty($Braiillio_end_date)? date('m/d/y', strtotime($Braiillio_end_date)) : '';

$log_Braiillio_data_html = '<tbody><tr><th>Lesson</th><th>Date</th><th>WPM</th><th>ACC</th><th>'.$lbl.'</th><th></th></tr>';
$log_Braiillio_data_html .= '<tr>
                            <td colspan="2">Average </td><td>'.$_average_scale['WPM'].'</td><td>'.$_average_scale['Accuracy'].'</td><td>'.$_average_scale['Combo'].'</td></tr>';

$table_data['total_row'] = mysqli_num_rows($log_Braiillio_data);

while ($log_Braiillio_data_row = mysqli_fetch_assoc($log_Braiillio_data)) {
    $error_data = '';
    $title = ($log_Braiillio_data_row['file']) ? $log_Braiillio_data_row['file'] : '';
    $log_Braiillio_data_value = explode('|', $log_Braiillio_data_row['data']);
    $log_Braiillio_data_html .= '<tr><td>';
    $log_Braiillio_data_html .= ($log_Braiillio_data_row['file']) ? $log_Braiillio_data_row['file'] : '';
    $log_Braiillio_data_html .= '</td><td>';
    $log_Braiillio_data_html .= ($log_Braiillio_data_row['date']) ? date('m/d/y', strtotime($log_Braiillio_data_row['date'])) : '';
    $log_Braiillio_data_html .= '</td><td>';
    $log_Braiillio_data_html .= ($log_Braiillio_data_value[0]) ? $log_Braiillio_data_value[0] : '';
    $log_Braiillio_data_html .= '</td><td>';
    $log_Braiillio_data_html .= ($log_Braiillio_data_value[1]) ? intval($log_Braiillio_data_value[1]) . '%' : '';
    $log_Braiillio_data_html .= '</td><td>';
    $error_data = ($log_Braiillio_data_value[2]) ? $log_Braiillio_data_value[2] : '';
    if($custom_date){
        $error_data = ($log_Braiillio_data_value[2]) ? $log_Braiillio_data_value[2] : '';
    }
    $log_Braiillio_data_html .= $error_data;
    $log_Braiillio_data_html .= '</td>';

    $log_Braiillio_data_html .= '<td>';
    $log_Braiillio_data_html .= '<a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $log_Braiillio_data_row['lognr'] . '><i class="fa fa-trash-o"></i></a>';
    $log_Braiillio_data_html .= '</td>';
    if ($log_Braiillio_data_row['file'] != 'Free Type' && !empty($log_Braiillio_data_value) && count($log_Braiillio_data_value) > 3) {		
        $log_Braiillio_data_html .= '<td>';
        $log_Braiillio_data_html .= '<a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $log_Braiillio_data_row['lognr'] . '><i class="fa fa-play"></i></a>';
        $log_Braiillio_data_html .= '</td>';
    }
    $log_Braiillio_data_html .= '</tr>';
}

$log_Braiillio_data_html .= '</tbody>';

$table_data['html'] = $log_Braiillio_data_html;

return $table_data;
}

function displayAppTypioData($typio_start_date = 0, $typio_end_date = 0, $student_id = '') {

    global $con, $studentid;
    $table_data = array();
    $studentid = !empty($student_id) ? $student_id : $studentid;
    /*$log_typio_query = "SELECT * FROM `log` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')  AND id ='" . $studentid . "'";*/
	$log_typio_query = "SELECT * FROM `log` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey')  AND id ='" . $studentid . "'";

    $custom_date = '';
    
    $custom_date =  custom_date($typio_start_date);
        
    $lbl = "COM";
    if($custom_date){
        $lbl = "ERR";
    }
    if (( $typio_start_date != 0 ) && ( $typio_end_date != 0 )) {
        
        $typio_start_date = date('Y-m-d', strtotime($typio_start_date)) . ' 00:00:00';
        $typio_end_date = date('Y-m-d', strtotime($typio_end_date)) . ' 23:59:59';
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }
	
    $log_typio_data = mysqli_query($con, $log_typio_query);
	$_average_scale = array();
	
    $_average_scale = Typio_average_chart_write_kp($student_id = 0, $typio_start_date, $typio_end_date);
    $_average_scale['start_date'] = !empty($typio_start_date) ? date('m/d/y', strtotime($typio_start_date)) : '';
    $_average_scale['end_date'] = !empty($typio_end_date)? date('m/d/y', strtotime($typio_end_date)) : '';

    $log_typio_data_html = '<tbody><tr><th>Lesson</th><th>Date</th><th>WPM</th><th>ACC</th><th>'.$lbl.'</th><th></th></tr>';
    $log_typio_data_html .= '<tr>
    							<td colspan="2">Average </td><td>'.$_average_scale['WPM'].'</td><td>'.$_average_scale['Accuracy'].'</td><td>'.$_average_scale['Combo'].'</td></tr>';

    $table_data['total_row'] = mysqli_num_rows($log_typio_data);

    while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
        $error_data = '';
		$title = ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
        $log_typio_data_value = explode('|', $log_typio_data_row['data']);
        $log_typio_data_html .= '<tr><td>';
        $log_typio_data_html .= ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
        $log_typio_data_html .= '</td><td>';
        $log_typio_data_html .= ($log_typio_data_row['date']) ? date('m/d/y', strtotime($log_typio_data_row['date'])) : '';
        $log_typio_data_html .= '</td><td>';
        $log_typio_data_html .= ($log_typio_data_value[0]) ? $log_typio_data_value[0] : '';
        $log_typio_data_html .= '</td><td>';
        $log_typio_data_html .= ($log_typio_data_value[1]) ? intval($log_typio_data_value[1]) . '%' : '';
        $log_typio_data_html .= '</td><td>';
		$error_data = ($log_typio_data_value[2]) ? $log_typio_data_value[2] : '';
        if($custom_date){
            $error_data = ($log_typio_data_value[2]) ? $log_typio_data_value[2] : '';
        }
        $log_typio_data_html .= $error_data;
        $log_typio_data_html .= '</td>';

        $log_typio_data_html .= '<td>';
        $log_typio_data_html .= '<a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $log_typio_data_row['lognr'] . '><i class="fa fa-trash-o"></i></a>';
        $log_typio_data_html .= '</td>';
		if ($log_typio_data_row['file'] != 'Free Type' && !empty($log_typio_data_value) && count($log_typio_data_value) > 3) {		
			//$log_typio_data_html .= '<td>';
			//$log_typio_data_html .= '<a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $log_typio_data_row['lognr'] . '><i class="fa fa-play"></i></a>';
			//$log_typio_data_html .= '</td>';
		}
        $log_typio_data_html .= '</tr>';
    }

    $log_typio_data_html .= '</tbody>';

    $table_data['html'] = $log_typio_data_html;

    return $table_data;
}
function displayAppTypioData_kp($typio_start_date = 0, $typio_end_date = 0, $student_id = '') {

    global $con, $studentid;
    $table_data = array();
    $studentid = !empty($student_id) ? $student_id : $studentid;
    /*$log_typio_query = "SELECT * FROM `log` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')  AND id ='" . $studentid . "'";*/
	$log_typio_query = "SELECT * FROM `log` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' )  AND id ='" . $studentid . "'";

    $custom_date = '';
    
    $custom_date =  custom_date($typio_start_date);
        
    $lbl = "COM";
    if($custom_date){
        $lbl = "ERR";
    }
    if (( $typio_start_date != 0 ) && ( $typio_end_date != 0 )) {
        
        $typio_start_date = date('Y-m-d', strtotime($typio_start_date)) . ' 00:00:00';
        $typio_end_date = date('Y-m-d', strtotime($typio_end_date)) . ' 23:59:59';
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }
	
    $log_typio_data = mysqli_query($con, $log_typio_query);
	$_average_scale = array();
	
    $_average_scale = Typio_average_chart_write($student_id = 0, $typio_start_date, $typio_end_date);
    $_average_scale['start_date'] = !empty($typio_start_date) ? date('m/d/y', strtotime($typio_start_date)) : '';
    $_average_scale['end_date'] = !empty($typio_end_date)? date('m/d/y', strtotime($typio_end_date)) : '';

    $log_typio_data_html = '<tbody><tr><th>Lesson</th><th>Date</th><th>WPM</th><th>ACC</th><th>'.$lbl.'</th><th></th></tr>';
    $log_typio_data_html .= '<tr>
    							<td colspan="2">Average </td><td>'.$_average_scale['WPM'].'</td><td>'.$_average_scale['Accuracy'].'</td><td>'.$_average_scale['Combo'].'</td></tr>';

    $table_data['total_row'] = mysqli_num_rows($log_typio_data);

    /*while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
        $error_data = '';
		$title = ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
        $log_typio_data_value = explode('|', $log_typio_data_row['data']);
        $log_typio_data_html .= '<tr><td>';
        $log_typio_data_html .= ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
        $log_typio_data_html .= '</td><td>';
        $log_typio_data_html .= ($log_typio_data_row['date']) ? date('m/d/y', strtotime($log_typio_data_row['date'])) : '';
        $log_typio_data_html .= '</td><td>';
        $log_typio_data_html .= ($log_typio_data_value[0]) ? $log_typio_data_value[0] : '';
        $log_typio_data_html .= '</td><td>';
        $log_typio_data_html .= ($log_typio_data_value[1]) ? intval($log_typio_data_value[1]) . '%' : '';
        $log_typio_data_html .= '</td><td>';
		$error_data = ($log_typio_data_value[2]) ? $log_typio_data_value[2] : '';
        if($custom_date){
            $error_data = ($log_typio_data_value[2]) ? $log_typio_data_value[2] : '';
        }
        $log_typio_data_html .= $error_data;
        $log_typio_data_html .= '</td>';

        $log_typio_data_html .= '<td>';
        $log_typio_data_html .= '<a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $log_typio_data_row['lognr'] . '><i class="fa fa-trash-o"></i></a>';
        $log_typio_data_html .= '</td>';
		if ($log_typio_data_row['file'] != 'Free Type' && !empty($log_typio_data_value) && count($log_typio_data_value) > 3) {		
			//$log_typio_data_html .= '<td>';
			//$log_typio_data_html .= '<a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $log_typio_data_row['lognr'] . '><i class="fa fa-play"></i></a>';
			//$log_typio_data_html .= '</td>';
		}
        $log_typio_data_html .= '</tr>';
    }*/
	$seen_logs = array();

	while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
		if (in_array($log_typio_data_row['lognr'], $seen_logs)) {
			continue; // skip duplicates
		}
		$seen_logs[] = $log_typio_data_row['lognr'];
		
		$error_data = '';
		$title = $log_typio_data_row['file'] ?? '';
		$log_typio_data_value = explode('|', $log_typio_data_row['data']);

		$log_typio_data_html .= '<tr><td>';
		$log_typio_data_html .= $title;
		$log_typio_data_html .= '</td><td>';
		$log_typio_data_html .= !empty($log_typio_data_row['date']) ? date('m/d/y', strtotime($log_typio_data_row['date'])) : '';
		$log_typio_data_html .= '</td><td>';
		$log_typio_data_html .= $log_typio_data_value[0] ?? '';
		$log_typio_data_html .= '</td><td>';
		$log_typio_data_html .= isset($log_typio_data_value[1]) ? intval($log_typio_data_value[1]) . '%' : '';
		$log_typio_data_html .= '</td><td>';

		$error_data = $log_typio_data_value[2] ?? '';
		if ($custom_date) {
			$error_data = $log_typio_data_value[2] ?? '';
		}
		$log_typio_data_html .= $error_data;
		$log_typio_data_html .= '</td><td>';
		$log_typio_data_html .= '<a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $log_typio_data_row['lognr'] . '><i class="fa fa-trash-o"></i></a>';
		$log_typio_data_html .= '</td>';
		$log_typio_data_html .= '</tr>';
	}

    $log_typio_data_html .= '</tbody>';

    $table_data['html'] = $log_typio_data_html;

    return $table_data;
}

/**
 * Display Keyboard Progress with lesson lists
 * Shows full lesson list with completed/incomplete status
 */
function displayKeyboardProgressData($student_id = '', $app_type = 'Typio-Journey') {
    global $con, $studentid;
    
    $studentid = !empty($student_id) ? $student_id : $studentid;
    
	// $lesson_file = __DIR__ . '/../dist/files/Typio-Journey.txt';
	 $lesson_file = __DIR__ . '/../../dist/files/Typio-Journey.txt';

	echo $lesson_file; die();
    // Debug logging
   // error_log("displayKeyboardProgressData called with student_id: $student_id, app_type: $app_type");
    
    // Get lesson list based on app type
    $lesson_file = '';
    switch($app_type) {
        case 'Typio-Journey':
            $lesson_file = __DIR__ . '/../dist/files/Typio-Journey.txt';
            break;
        case 'Typio-OL':
            $lesson_file = __DIR__ . '/../dist/files/Typio-OL.txt';
            break;
        case 'Typio-BRL':
            $lesson_file = __DIR__ . '/../dist/files/Typio-BRL.txt';
            break;
    }
    
    if (!file_exists($lesson_file)) {
        error_log("Lesson file not found: $lesson_file");
        return array('html' => '<div style="padding: 20px; text-align: center; color: #666;">Lesson file not found: ' . basename($lesson_file) . '</div>', 'total_lessons' => 0, 'completed_lessons' => 0, 'next_lesson' => '', 'keys_progress' => 0);
    }
    
    // Read lesson list
    $lesson_content = file_get_contents($lesson_file);
    $lessons = array_filter(array_map('trim', explode("\n", $lesson_content)));
    
    // Get completed lessons from data table
    $data_query = "SELECT * FROM `data` WHERE `app` = '$app_type' AND `id` = '$studentid' ORDER BY `date` DESC";
    $data_result = mysqli_query($con, $data_query);
    $completed_lessons = array();
    
    while ($data_row = mysqli_fetch_assoc($data_result)) {
        $completed_lessons[$data_row['file']] = $data_row;
    }
    
    // Build lesson list HTML
    $lesson_html = '';
    $completed_count = 0;
    $next_lesson = '';
    $first_incomplete = true;
    $keys_progress = 0;
    
    foreach ($lessons as $index => $lesson) {
        $lesson_parts = explode('|', $lesson);
        $lesson_name = trim($lesson_parts[0]);
        $lesson_category = isset($lesson_parts[1]) ? trim($lesson_parts[1]) : '';
        
        $is_completed = isset($completed_lessons[$lesson_name]);
        $status_html = '';
        $lesson_number = $index + 1;
        
        if ($is_completed) {
            $completed_count++;
            $keys_progress += 2; // Approximate keys per lesson
            $status_html = '<div style="width: 20px; height: 20px; border-radius: 50%; background: #333; display: flex; align-items: center; justify-content: center; margin-left: auto;">
                <i class="fa fa-check" style="color: white; font-size: 10px;"></i>
            </div>';
        } else {
            if ($first_incomplete) {
                $next_lesson = $lesson_name;
                $status_html = '<div style="width: 20px; height: 20px; border-radius: 50%; background: #ff4444; display: flex; align-items: center; justify-content: center; margin-left: auto;">
                    <span style="color: white; font-size: 10px; font-weight: bold;">Next</span>
                </div>';
                $first_incomplete = false;
            } else {
                $status_html = '<div style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #ddd; background: white; margin-left: auto;"></div>';
            }
        }
        
        $lesson_html .= '<div style="display: flex; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f0f0;">
            <div style="flex: 1;">
                <div style="font-weight: 500; color: #333; margin-bottom: 2px;">' . $lesson_number . '. ' . $lesson_name . '</div>
                <div style="font-size: 12px; color: #666;">' . $lesson_category . '</div>
            </div>
            ' . $status_html . '
        </div>';
    }
    
    // Remove the last border from the last item
    if (!empty($lesson_html)) {
        $lesson_html = rtrim($lesson_html, '<div style="display: flex; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f0f0;">');
        $lesson_html = str_replace('<div style="display: flex; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f0f0;">', '', $lesson_html);
        $lesson_html = '<div style="display: flex; align-items: center; padding: 12px 0;">' . $lesson_html;
    }
    
    return array(
        'html' => $lesson_html,
        'total_lessons' => count($lessons),
        'completed_lessons' => $completed_count,
        'next_lesson' => $next_lesson,
        'keys_progress' => $keys_progress
    );
}

 function displayAppBrailData($Brail_start_date = 0, $Brail_end_date = 0, $student_id = '') {

global $con, $studentid;
$table_data = array();
$studentid = !empty($student_id) ? $student_id : $studentid;
$log_Brail_query = "SELECT * FROM `log` WHERE (`app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')  AND id ='" . $studentid . "'";

$custom_date = '';

$custom_date =  custom_date($Brail_start_date);
    
$lbl = "COM";
if($custom_date){
    $lbl = "ERR";
}
if (( $Brail_start_date != 0 ) && ( $Brail_end_date != 0 )) {
    
    $Brail_start_date = date('Y-m-d', strtotime($Brail_start_date)) . ' 00:00:00';
    $Brail_end_date = date('Y-m-d', strtotime($Brail_end_date)) . ' 23:59:59';
    $log_Brail_query .= " AND `date` BETWEEN '" . $Brail_start_date . "' AND '" . $Brail_end_date . "' ";
}

$log_Brail_data = mysqli_query($con, $log_Brail_query);
$_average_scale = array();

$_average_scale = Brail_average_chart_write($student_id = 0, $Brail_start_date, $Brail_end_date);
$_average_scale['start_date'] = !empty($Brail_start_date) ? date('m/d/y', strtotime($Brail_start_date)) : '';
$_average_scale['end_date'] = !empty($Brail_end_date)? date('m/d/y', strtotime($Brail_end_date)) : '';

$log_Brail_data_html = '<tbody><tr><th>Lesson</th><th>Date</th><th>WPM</th><th>ACC</th><th>'.$lbl.'</th><th></th></tr>';
$log_Brail_data_html .= '<tr>
                            <td colspan="2">Average </td><td>'.$_average_scale['WPM'].'</td><td>'.$_average_scale['Accuracy'].'</td><td>'.$_average_scale['Combo'].'</td></tr>';

$table_data['total_row'] = mysqli_num_rows($log_Brail_data);

while ($log_Brail_data_row = mysqli_fetch_assoc($log_Brail_data)) {
    $error_data = '';
    $title = ($log_Brail_data_row['file']) ? $log_Brail_data_row['file'] : '';
    $log_Brail_data_value = explode('|', $log_Brail_data_row['data']);
    $log_Brail_data_html .= '<tr><td>';
    $log_Brail_data_html .= ($log_Brail_data_row['file']) ? $log_Brail_data_row['file'] : '';
    $log_Brail_data_html .= '</td><td>';
    $log_Brail_data_html .= ($log_Brail_data_row['date']) ? date('m/d/y', strtotime($log_Brail_data_row['date'])) : '';
    $log_Brail_data_html .= '</td><td>';
    $log_Brail_data_html .= ($log_Brail_data_value[0]) ? $log_Brail_data_value[0] : '';
    $log_Brail_data_html .= '</td><td>';
    $log_Brail_data_html .= ($log_Brail_data_value[1]) ? intval($log_Brail_data_value[1]) . '%' : '';
    $log_Brail_data_html .= '</td><td>';
    $error_data = ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : '';
    if($custom_date){
        $error_data = ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : '';
    }
    $log_Brail_data_html .= $error_data;
    $log_Brail_data_html .= '</td>';

    $log_Brail_data_html .= '<td>';
    $log_Brail_data_html .= '<a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $log_Brail_data_row['lognr'] . '><i class="fa fa-trash-o"></i></a>';
    $log_Brail_data_html .= '</td>';
    if ($log_Brail_data_row['file'] != 'Free Type' && !empty($log_Brail_data_value) && count($log_Brail_data_value) > 3) {		
        $log_Brail_data_html .= '<td>';
        $log_Brail_data_html .= '<a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $log_Brail_data_row['lognr'] . '><i class="fa fa-play"></i></a>';
        $log_Brail_data_html .= '</td>';
    }
    $log_Brail_data_html .= '</tr>';
}

$log_Brail_data_html .= '</tbody>';

$table_data['html'] = $log_Brail_data_html;

return $table_data;
}
/**
 * Display App Arcade-OL data
 * 
 * @param string $arcade_start_date 
 * @param string $arcade_end_date 
 * @return string Arcade-OL table html
 */
function displayAppArcadeData($arcade_start_date = 0, $arcade_end_date = 0 , $student_id="") {

    global $con, $studentid;

    $student_id = isset($student_id)?$student_id:$studentid;

    $log_data_table = "log";
    $log_arcade_query = "SELECT * FROM `" . $log_data_table . "` WHERE `app` LIKE 'Arcade-OL' AND id='" . $studentid . "'";

    if (( $arcade_start_date != 0 ) && ( $arcade_end_date != 0 )) {
        $arcade_start_date = date('y-m-d', strtotime($arcade_start_date));
        $arcade_end_date = date('y-m-d', strtotime($arcade_end_date));
        $log_arcade_query .= " AND `date` BETWEEN '" . $arcade_start_date . "' AND '" . $arcade_end_date . "' ";
    }

    $log_arcade_data = mysqli_query($con, $log_arcade_query);

    $log_arcade_data_html = '<tbody><tr><th>Name</th><th>Date</th></tr>';

    while ($log_arcade_data_row = mysqli_fetch_assoc($log_arcade_data)) {
        $log_arcade_data_html .= '<tr><td>';
        $log_arcade_data_html .= ($log_arcade_data_row['file']) ? $log_arcade_data_row['file'] : '';
        $log_arcade_data_html .= '</td><td>';
        $log_arcade_data_html .= ($log_arcade_data_row['date']) ? $log_arcade_data_row['date'] : '';
        $log_arcade_data_html .= '</td>';
        $log_arcade_data_html .= '</tr>';
    }
    $log_arcade_data_html .= '</tbody>';
    return $log_arcade_data_html;
}

/**
 * Display App Typio-OL data table for barChart
 * 
 * @param string $typio_start_date 
 * @param string $typio_end_date 
 * @return string Typio-OL table html table
 */
function displayAppTypioDataTableAverageBarChart($student_id = 0, $typio_start_date = 0, $typio_end_date = 0) {
    global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;

    $log_data_table = "log";
    //$log_data_table = "data";
    $log_data_table_wpm_title = ' WPM';
    $log_data_table_accuracy_title = '% Accuracy';
    //$log_data_table_combo_title = 'Errors';
    $log_data_table_combo_title = " Combo";
    $custom_date =  custom_date($typio_start_date);
    if($custom_date){
        $log_data_table_combo_title = " Errors";
    }   
    $log_data_table_wpm_value = 0;
    $log_data_table_accuracy_value = 0;
    $log_data_table_combo_value = 0;
    $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' ) AND id='" . $studentid . "'";
	/*$log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";*/
    if (($typio_start_date != 0) && ($typio_end_date != 0)) {
         
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }

    $log_typio_data = mysqli_query($con, $log_typio_query);
    $log_typio_data_count = 0;
    $log_typio_data_html = '';
    while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
        $log_typio_data_count++;
        $log_typio_data_value = explode('|', $log_typio_data_row['data']);
        $log_data_table_wpm_value += ($log_typio_data_value[0]) ? $log_typio_data_value[0] : 0;
        $log_data_table_accuracy_value += ($log_typio_data_value[1]) ? $log_typio_data_value[1] : 0;
        $log_data_table_combo_value += ($log_typio_data_value[2]) ? $log_typio_data_value[2] : 1;
    }
    $combo_data = $log_data_table_combo_value;
    if($custom_date){
        $combo_data = $log_data_table_combo_value;
    }
    $log_typio_data_html .= "<tr></tr>";
    $log_typio_data_html .= '<tr><td>' . $log_data_table_wpm_title . '</td><td>' . (!empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0') . '</td></tr>';
    $log_typio_data_html .= '<tr><td>' . $log_data_table_accuracy_title . '</td><td>' . (!empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ) . '</td></tr>';
    $log_typio_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' . (!empty($combo_data) ? round(($combo_data / $log_typio_data_count)) : '0' ) . '</td></tr>';
	/*$log_typio_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' . (!empty($combo_data) ? floor(($combo_data / $log_typio_data_count)) : '0' ) . '</td></tr>';*/
	
	/*$combodata  = 'wpm ' . (!empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0').' acc '. (!empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ). "err".(!empty($combo_data) ? floor(($combo_data / $log_typio_data_count)) : '0');*/
    return $log_typio_data_html;
    //return $combodata;
}
function displayAppTypioDataTableAverageBarChart_kp($student_id = 0, $typio_start_date = 0, $typio_end_date = 0) {
    global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;

    $log_data_table = "log";
    //$log_data_table = "data";
    $log_data_table_wpm_title = ' WPM';
    $log_data_table_accuracy_title = '% Accuracy';
    //$log_data_table_combo_title = 'Errors';
    $log_data_table_combo_title = " Combo";
    $custom_date =  custom_date($typio_start_date);
    if($custom_date){
        $log_data_table_combo_title = " Errors";
    }   
    $log_data_table_wpm_value = 0;
    $log_data_table_accuracy_value = 0;
    $log_data_table_combo_value = 0;
    $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' ) AND id='" . $studentid . "'";
	/*$log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";*/
    if (($typio_start_date != 0) && ($typio_end_date != 0)) {
         
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }

    $log_typio_data = mysqli_query($con, $log_typio_query);
    $log_typio_data_count = 0;
    $log_typio_data_html = '';
    while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
        $log_typio_data_count++;
        $log_typio_data_value = explode('|', $log_typio_data_row['data']);
        $log_data_table_wpm_value += ($log_typio_data_value[0]) ? $log_typio_data_value[0] : 0;
        $log_data_table_accuracy_value += ($log_typio_data_value[1]) ? $log_typio_data_value[1] : 0;
        $log_data_table_combo_value += ($log_typio_data_value[2]) ? $log_typio_data_value[2] : 1;
    }
    $combo_data = $log_data_table_combo_value;
    if($custom_date){
        $combo_data = $log_data_table_combo_value;
    }
    $log_typio_data_html .= "<tr></tr>";
    $log_typio_data_html .= '<tr><td>' . $log_data_table_wpm_title . '</td><td>' . (!empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0') . '</td></tr>';
    $log_typio_data_html .= '<tr><td>' . $log_data_table_accuracy_title . '</td><td>' . (!empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ) . '</td></tr>';
    $log_typio_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' . (!empty($combo_data) ? round(($combo_data / $log_typio_data_count)) : '0' ) . '</td></tr>';
	/*$log_typio_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' . (!empty($combo_data) ? floor(($combo_data / $log_typio_data_count)) : '0' ) . '</td></tr>';*/
	
	/*$combodata  = 'wpm ' . (!empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0').' acc '. (!empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ). "err".(!empty($combo_data) ? floor(($combo_data / $log_typio_data_count)) : '0');*/
    return $log_typio_data_html;
    //return $combodata;
}
function displayAppTypioDataTableAverageBarChartkp($student_id = 0, $typio_start_date = 0, $typio_end_date = 0) {
    global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;

    $log_data_table = "log";
    //$log_data_table = "data";
    $log_data_table_wpm_title = ' WPM';
    $log_data_table_accuracy_title = '% Accuracy';
    //$log_data_table_combo_title = 'Errors';
    $log_data_table_combo_title = " Combo";
    $custom_date =  custom_date($typio_start_date);
    if($custom_date){
        $log_data_table_combo_title = " Errors";
    }   
    $log_data_table_wpm_value = 0;
    $log_data_table_accuracy_value = 0;
    $log_data_table_combo_value = 0;
    $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey') AND id='" . $studentid . "'";
    if (($typio_start_date != 0) && ($typio_end_date != 0)) {
         
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }

    $log_typio_data = mysqli_query($con, $log_typio_query);
    $log_typio_data_count = 0;
    $log_typio_data_html = '';
    while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
        $log_typio_data_count++;
        $log_typio_data_value = explode('|', $log_typio_data_row['data']);
        $log_data_table_wpm_value += ($log_typio_data_value[0]) ? $log_typio_data_value[0] : 0;
        $log_data_table_accuracy_value += ($log_typio_data_value[1]) ? $log_typio_data_value[1] : 0;
        $log_data_table_combo_value += ($log_typio_data_value[2]) ? $log_typio_data_value[2] : 1;
    }
    $combo_data = $log_data_table_combo_value;
    if($custom_date){
        $combo_data = $log_data_table_combo_value;
    }
    $log_typio_data_html .= "<tr></tr>";
    $log_typio_data_html .= '<tr><td>' . $log_data_table_wpm_title . '</td><td>' . (!empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0') . '</td></tr>';
    $log_typio_data_html .= '<tr><td>' . $log_data_table_accuracy_title . '</td><td>' . (!empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ) . '</td></tr>';
    $log_typio_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' . (!empty($combo_data) ? round(($combo_data / $log_typio_data_count)) : '0' ) . '</td></tr>';
	/*$log_typio_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' . (!empty($combo_data) ? floor(($combo_data / $log_typio_data_count)) : '0' ) . '</td></tr>';*/
	
	/*$combodata  = 'wpm ' . (!empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0').' acc '. (!empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ). "err".(!empty($combo_data) ? floor(($combo_data / $log_typio_data_count)) : '0');*/
    return $log_typio_data_html;
    //return $combodata;
}

function displayAppBrailDataTableAverageBarChart($student_id = 0, $Brail_start_date = 0, $Brail_end_date = 0) {
    global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;

    $log_data_table = "log";
    $log_data_table_wpm_title = ' WPM';
    $log_data_table_accuracy_title = '% Accuracy';
    //$log_data_table_combo_title = 'Errors';
    $log_data_table_combo_title = " Combo";
    $custom_date =  custom_date($Brail_start_date);
    if($custom_date){
        $log_data_table_combo_title = " Errors";
    }   
    $log_data_table_wpm_value = 0;
    $log_data_table_accuracy_value = 0;
    //$log_data_table_combo_value = 0;
   /* $log_Brail_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL' ) AND ( `file` LIKE 'Typio-BRL' OR `file` LIKE 'Braillio' ) AND id='" . $studentid . "'";*/
	$log_Brail_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";
    if (($Brail_start_date != 0) && ($Brail_end_date != 0)) {
         
        $log_Brail_query .= " AND `date` BETWEEN '" . $Brail_start_date . "' AND '" . $Brail_end_date . "' ";
    }
	//print_r($log_Brail_query);die();
    $log_Brail_data = mysqli_query($con, $log_Brail_query);
    $log_Brail_data_count = 0;
    $log_Brail_data_html = '';
	/*print_r($log_Brail_data);die();*/
    while ($log_Brail_data_row = mysqli_fetch_assoc($log_Brail_data)) {
        $log_Brail_data_count++;
        $log_Brail_data_value = explode('|', $log_Brail_data_row['data']);
        $log_data_table_wpm_value += ($log_Brail_data_value[0]) ? $log_Brail_data_value[0] : 0;
        $log_data_table_accuracy_value += ($log_Brail_data_value[1]) ? $log_Brail_data_value[1] : 0;
        $log_data_table_combo_value += ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : 0;
		
    }
	
	
    $combo_data = $log_data_table_combo_value;
    if($custom_date){
        $combo_data = $log_data_table_combo_value;
    }
    $log_Brail_data_html .= "<tr></tr>";
    $log_Brail_data_html .= '<tr><td>' . $log_data_table_wpm_title . '</td><td>' . (!empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_Brail_data_count)) : '0') . '</td></tr>';
    $log_Brail_data_html .= '<tr><td>' . $log_data_table_accuracy_title . '</td><td>' . (!empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_Brail_data_count)) : '0' ) . '</td></tr>';
    $log_Brail_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' .
	(!empty($combo_data) ? round(($combo_data / $log_Brail_data_count)) : '0' ) . '</td></tr>';
	/*
	$log_Brail_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' .
	(!empty($combo_data) ? floor(($combo_data / $log_Brail_data_count)) : '0' ) . '</td></tr>';
	*/
   

    return $log_Brail_data_html;
    //return $log_Brail_query;
}
function displayAppBrailDataTableAverageBarChartkp($student_id = 0, $Brail_start_date = 0, $Brail_end_date = 0) {
    global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;

   // $log_data_table = "log";
    $log_data_table = "data";
    $log_data_table_wpm_title = ' WPM';
    $log_data_table_accuracy_title = '% Accuracy';
    //$log_data_table_combo_title = 'Errors';
    $log_data_table_combo_title = " Combo";
    $custom_date =  custom_date($Brail_start_date);
    if($custom_date){
        $log_data_table_combo_title = " Errors";
    }   
    $log_data_table_wpm_value = 0;
    $log_data_table_accuracy_value = 0;
    //$log_data_table_combo_value = 0;
   /* $log_Brail_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL' ) AND ( `file` LIKE 'Typio-BRL' OR `file` LIKE 'Braillio' ) AND id='" . $studentid . "'";*/
	$log_Brail_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";
    if (($Brail_start_date != 0) && ($Brail_end_date != 0)) {
         
        $log_Brail_query .= " AND `date` BETWEEN '" . $Brail_start_date . "' AND '" . $Brail_end_date . "' ";
    }
	//print_r($log_Brail_query);die();
    $log_Brail_data = mysqli_query($con, $log_Brail_query);
    $log_Brail_data_count = 0;
    $log_Brail_data_html = '';
	/*print_r($log_Brail_data);die();*/
    while ($log_Brail_data_row = mysqli_fetch_assoc($log_Brail_data)) {
        $log_Brail_data_count++;
        $log_Brail_data_value = explode('|', $log_Brail_data_row['data']);
        $log_data_table_wpm_value += ($log_Brail_data_value[0]) ? $log_Brail_data_value[0] : 0;
        $log_data_table_accuracy_value += ($log_Brail_data_value[1]) ? $log_Brail_data_value[1] : 0;
        $log_data_table_combo_value += ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : 0;
		
    }
	
	
    $combo_data = $log_data_table_combo_value;
    if($custom_date){
        $combo_data = $log_data_table_combo_value;
    }
    $log_Brail_data_html .= "<tr></tr>";
    $log_Brail_data_html .= '<tr><td>' . $log_data_table_wpm_title . '</td><td>' . (!empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_Brail_data_count)) : '0') . '</td></tr>';
    $log_Brail_data_html .= '<tr><td>' . $log_data_table_accuracy_title . '</td><td>' . (!empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_Brail_data_count)) : '0' ) . '</td></tr>';
    $log_Brail_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' .
	(!empty($combo_data) ? round(($combo_data / $log_Brail_data_count)) : '0' ) . '</td></tr>';
	/*
	$log_Brail_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' .
	(!empty($combo_data) ? floor(($combo_data / $log_Brail_data_count)) : '0' ) . '</td></tr>';
	*/
   

    return $log_Brail_data_html;
    //return $log_Brail_query;
}
/**
 * Display App Typio-OL data table for Area Chart
 * 
 * @param string $typio_start_date 
 * @param string $typio_end_date 
 * @return string Typio-OL table html table
 */
function displayAppTypioDataTableAverageAreaChart($typio_start_date = 0, $typio_end_date = 0, $student_id = '') {

    global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;

    $log_data_table = "log";
    //$log_data_table = "data";
    $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' ) AND id='" . $studentid . "'";
	/*$log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";*/
    
    $custom_date =  custom_date($typio_start_date);
    $lbl = " Combo";
    if($custom_date){
        $lbl = " Errors";
    } 
    if (( $typio_start_date != 0 ) && ( $typio_end_date != 0 )) {
        $typio_start_date = date('Y-m-d', strtotime($typio_start_date)) . ' 00:00:00';
        $typio_end_date = date('Y-m-d', strtotime($typio_end_date)) . ' 23:59:00';
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }

    $log_typio_data = mysqli_query($con, $log_typio_query);

    $_average_scale = Typio_average_chart_write($student_id, $typio_start_date, $typio_end_date);
    $_average_scale['start_date'] = !empty($typio_start_date) ? date('m/d/y', strtotime($typio_start_date)) : '';
    $_average_scale['end_date'] = !empty($typio_end_date)? date('m/d/y', strtotime($typio_end_date)) : '';

    $log_typio_data_html = '<tbody><tr><th>Name</th><th> WPM</th><th>% Accuracy</th><th>'.$lbl.'</th></tr>';

	//echo "<pre>"; print_r($log_typio_data); echo "</pre>";

    if ($log_typio_data) {
		//echo "data";die();
    	//$log_typio_data_html .= '<tr><td colspan="2"> Average : ('.$_average_scale['start_date'].' - '.$_average_scale['end_date'].')</td><td>'.$_average_scale['WPM'].'</td><td>'.$_average_scale['Accuracy'].'</td><td>'.$_average_scale['Combo'].'</td></tr>';
        while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
            
            $log_typio_data_value = explode('|', $log_typio_data_row['data']);
            $log_typio_data_html .= '<tr><td>';
            $log_typio_data_html .= ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
            $log_typio_data_html .= '</td><td>';
            $log_typio_data_html .= ($log_typio_data_value[0]) ? $log_typio_data_value[0] : '';
            $log_typio_data_html .= '</td><td>';
            $log_typio_data_html .= ($log_typio_data_value[1]) ? $log_typio_data_value[1] : '';
            $log_typio_data_html .= '</td>';
            $log_typio_data_html .= '<td>';
			$error_data = ($log_typio_data_value[2]) ? $log_typio_data_value[2] : '';
            if($custom_date){
                $error_data = ($log_typio_data_value[2]) ? $log_typio_data_value[2] : '';
            }
            $log_typio_data_html .= $error_data;
            $log_typio_data_html .= '</td>';
            $log_typio_data_html .= '</tr>';
        }
    } else {
        $log_typio_data_html .= '<tr><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
    }

    if ($log_typio_data_html == '<tbody><tr><th>Name</th><th>WPM</th><th>ACC(%)</th><th>'.$lbl.'</th></tr>') {
        $log_typio_data_html .= '<tr><td></td><td></td><td></td><td></td></tr>';
        return $log_typio_data_html;
    }

    $log_typio_data_html .= '</tbody>';

    return $log_typio_data_html;
}
function displayAppTypioDataTableAverageAreaChart_kp($typio_start_date = 0, $typio_end_date = 0, $student_id = '') {

    global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;

    $log_data_table = "log";
    //$log_data_table = "data";
    $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' ) AND id='" . $studentid . "'";
	/*$log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";*/
    
    $custom_date =  custom_date($typio_start_date);
    $lbl = " Combo";
    if($custom_date){
        $lbl = " Errors";
    } 
    if (( $typio_start_date != 0 ) && ( $typio_end_date != 0 )) {
        $typio_start_date = date('Y-m-d', strtotime($typio_start_date)) . ' 00:00:00';
        $typio_end_date = date('Y-m-d', strtotime($typio_end_date)) . ' 23:59:00';
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }

    $log_typio_data = mysqli_query($con, $log_typio_query);

    $_average_scale = Typio_average_chart_write($student_id, $typio_start_date, $typio_end_date);
    $_average_scale['start_date'] = !empty($typio_start_date) ? date('m/d/y', strtotime($typio_start_date)) : '';
    $_average_scale['end_date'] = !empty($typio_end_date)? date('m/d/y', strtotime($typio_end_date)) : '';

    $log_typio_data_html = '<tbody><tr><th>Name</th><th> WPM</th><th>% Accuracy</th><th>'.$lbl.'</th></tr>';

	//echo "<pre>"; print_r($log_typio_data); echo "</pre>";

    if ($log_typio_data) {
		//echo "data";die();
    	//$log_typio_data_html .= '<tr><td colspan="2"> Average : ('.$_average_scale['start_date'].' - '.$_average_scale['end_date'].')</td><td>'.$_average_scale['WPM'].'</td><td>'.$_average_scale['Accuracy'].'</td><td>'.$_average_scale['Combo'].'</td></tr>';
        while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
            
            $log_typio_data_value = explode('|', $log_typio_data_row['data']);
            $log_typio_data_html .= '<tr><td>';
            $log_typio_data_html .= ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
            $log_typio_data_html .= '</td><td>';
            $log_typio_data_html .= ($log_typio_data_value[0]) ? $log_typio_data_value[0] : '';
            $log_typio_data_html .= '</td><td>';
            $log_typio_data_html .= ($log_typio_data_value[1]) ? $log_typio_data_value[1] : '';
            $log_typio_data_html .= '</td>';
            $log_typio_data_html .= '<td>';
			$error_data = ($log_typio_data_value[2]) ? $log_typio_data_value[2] : '';
            if($custom_date){
                $error_data = ($log_typio_data_value[2]) ? $log_typio_data_value[2] : '';
            }
            $log_typio_data_html .= $error_data;
            $log_typio_data_html .= '</td>';
            $log_typio_data_html .= '</tr>';
        }
    } else {
        $log_typio_data_html .= '<tr><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
    }

    if ($log_typio_data_html == '<tbody><tr><th>Name</th><th>WPM</th><th>ACC(%)</th><th>'.$lbl.'</th></tr>') {
        $log_typio_data_html .= '<tr><td></td><td></td><td></td><td></td></tr>';
        return $log_typio_data_html;
    }

    $log_typio_data_html .= '</tbody>';

    return $log_typio_data_html;
}
function displayAppTypioDataTableAverageAreaChartkp($typio_start_date = 0, $typio_end_date = 0, $student_id = '') {

    global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;

    //$log_data_table = "log";
    $log_data_table = "data";
    //$log_data_table = "data";Typio-OL', 'Typio-Journey', 'Typio-BRL
    $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' ) AND id='" . $studentid . "'";
    
    $custom_date =  custom_date($typio_start_date);
    $lbl = " Combo";
    if($custom_date){
        $lbl = " Errors";
    } 
   /* if (( $typio_start_date != 0 ) && ( $typio_end_date != 0 )) {
        $typio_start_date = date('Y-m-d', strtotime($typio_start_date)) . ' 00:00:00';
        $typio_end_date = date('Y-m-d', strtotime($typio_end_date)) . ' 23:59:00';
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }*/

    $log_typio_data = mysqli_query($con, $log_typio_query);

    $_average_scale = Typio_average_chart_write($student_id, $typio_start_date, $typio_end_date);
    $_average_scale['start_date'] = !empty($typio_start_date) ? date('m/d/y', strtotime($typio_start_date)) : '';
    $_average_scale['end_date'] = !empty($typio_end_date)? date('m/d/y', strtotime($typio_end_date)) : '';

    //$log_typio_data_html = '<tbody><tr><th>Name</th><th> WPM</th><th>% Accuracy</th><th>'.$lbl.'</th></tr>';
		$log_typio_data_html = '<tbody>';
	//echo "<pre>"; print_r($log_typio_data); echo "</pre>";

    if ($log_typio_data) {
		//echo "data";die();
    	//$log_typio_data_html .= '<tr><td colspan="2"> Average : ('.$_average_scale['start_date'].' - '.$_average_scale['end_date'].')</td><td>'.$_average_scale['WPM'].'</td><td>'.$_average_scale['Accuracy'].'</td><td>'.$_average_scale['Combo'].'</td></tr>';
		
			
			
			
			$typiobrl 		= file_get_contents(ADMIN_URL.'/dist/lessonfiles/typiobrl.txt');
			$typiojourney 	= file_get_contents(ADMIN_URL.'/dist/lessonfiles/typiojourney.txt');
			$typiool	 	= file_get_contents(ADMIN_URL.'/dist/lessonfiles/typiool.txt');
			$content1 		= preg_replace('/[^\x20-\x7E\r\n]/', '', $typiobrl); 
			$content2 		= preg_replace('/[^\x20-\x7E\r\n]/', '', $typiojourney); 
			$content3		= preg_replace('/[^\x20-\x7E\r\n]/', '', $typiool); 
			$array1 		= array_filter(array_map('trim', explode("\n", $content1)));
			$array2 		= array_filter(array_map('trim', explode("\n", $content2)));
			$array3 		= array_filter(array_map('trim', explode("\n", $content3)));

			// Combine all three arrays
			$combinedArray = array_merge($array1, $array2, $array3);
			$fileName = [];
			while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
				$fileName[] = trim($log_typio_data_row['file']);
			}
        /*while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
            $fileName = trim($log_typio_data_row['file']);
            $log_typio_data_value = explode('|', $log_typio_data_row['data']);
            $log_typio_data_html .= '<tr><td>';
            $log_typio_data_html .= ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
            $log_typio_data_html .= '</td>';
			 // Check if file name exists in combined array
			if (in_array($fileName, $combinedArray)) {
				$exist_img = '<img src="' . ADMIN_URL . 'img/check.png" alt="Exists" width="20">';
			} else {
				$exist_img = '';
			}
            $log_typio_data_html .= '<td>'.$exist_img.'</td>';
            $log_typio_data_html .= '</tr>';
        }*/
		$matchedFiles = [];
		$unmatchedFiles = [];

		// Separate matched and unmatched
		foreach ($combinedArray as $combinedArrayfile) {
			$combinedArrayfile = trim($combinedArrayfile);

			if (in_array($combinedArrayfile, $fileName)) {
				$matchedFiles[] = $combinedArrayfile;
			} else {
				$unmatchedFiles[] = $combinedArrayfile;
			}
		}

		// Merge so matched first, then unmatched
		$orderedFiles = array_merge($matchedFiles, $unmatchedFiles);

		$log_typio_data_html = '';
		$nextAdded = false; // Flag to add "Next" text only once

		foreach ($orderedFiles as $combinedArrayfile) {
			$combinedArrayfile = trim($combinedArrayfile);

			$log_typio_data_html .= '<tr><td>';
			$log_typio_data_html .= $combinedArrayfile ?: '';
			$log_typio_data_html .= '</td>';

			// Default: no image
			$exist_img = '';

			// Check if file exists
			if (in_array($combinedArrayfile, $fileName)) {
				$exist_img = '<img src="' . ADMIN_URL . 'img/check.png" alt="Exists" width="20">';
			} else {
				// For first unmatched, add text "Next" next to image (or alone)
				if (!$nextAdded) {
					$exist_img = 'Next';
					$nextAdded = true;
				}
			}

			$log_typio_data_html .= '<td>' . $exist_img . '</td>';
			$log_typio_data_html .= '</tr>';
		}
    } else {
        $log_typio_data_html .= '<tr><td>0</td><td>0</td></tr>';
    }

    if ($log_typio_data_html == '<tbody><tr><th>Name</th><th>WPM</th><th>ACC(%)</th><th>'.$lbl.'</th></tr>') {
        $log_typio_data_html .= '<tr><td></td><td></td><td></td><td></td></tr>';
      //  return $log_typio_data_html;
    }

    $log_typio_data_html .= '</tbody>';

   return $log_typio_data_html;
    //return $log_typio_query;
}
function displayAppTypioDataTableAverageAreaChartkp_nw($typio_start_date = 0, $typio_end_date = 0, $student_id = '') {

    global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;

    //$log_data_table = "log";
    $log_data_table = "data";
    //$log_data_table = "data";Typio-OL', 'Typio-Journey', 'Typio-BRL
    $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' ) AND id='" . $studentid . "'";
    
    $custom_date =  custom_date($typio_start_date);
    $lbl = " Combo";
    if($custom_date){
        $lbl = " Errors";
    } 
   /* if (( $typio_start_date != 0 ) && ( $typio_end_date != 0 )) {
        $typio_start_date = date('Y-m-d', strtotime($typio_start_date)) . ' 00:00:00';
        $typio_end_date = date('Y-m-d', strtotime($typio_end_date)) . ' 23:59:00';
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }*/

    $log_typio_data = mysqli_query($con, $log_typio_query);

    $_average_scale = Typio_average_chart_write($student_id, $typio_start_date, $typio_end_date);
    $_average_scale['start_date'] = !empty($typio_start_date) ? date('m/d/y', strtotime($typio_start_date)) : '';
    $_average_scale['end_date'] = !empty($typio_end_date)? date('m/d/y', strtotime($typio_end_date)) : '';

    $log_typio_data_html = '<tbody><tr><th>Name</th><th> WPM</th><th>% Accuracy</th><th>'.$lbl.'</th></tr>';

	//echo "<pre>"; print_r($log_typio_data); echo "</pre>";

    if ($log_typio_data) {
		//echo "data";die();
    	//$log_typio_data_html .= '<tr><td colspan="2"> Average : ('.$_average_scale['start_date'].' - '.$_average_scale['end_date'].')</td><td>'.$_average_scale['WPM'].'</td><td>'.$_average_scale['Accuracy'].'</td><td>'.$_average_scale['Combo'].'</td></tr>';
        while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
            
            $log_typio_data_value = explode('|', $log_typio_data_row['data']);
            $log_typio_data_html .= '<tr><td>';
            $log_typio_data_html .= ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
            $log_typio_data_html .= '</td><td>';
            $log_typio_data_html .= ($log_typio_data_value[0]) ? $log_typio_data_value[0] : '';
            $log_typio_data_html .= '</td><td>';
            $log_typio_data_html .= ($log_typio_data_value[1]) ? $log_typio_data_value[1] : '';
            $log_typio_data_html .= '</td>';
            $log_typio_data_html .= '<td>';
			$error_data = ($log_typio_data_value[2]) ? $log_typio_data_value[2] : '';
            if($custom_date){
                $error_data = ($log_typio_data_value[2]) ? $log_typio_data_value[2] : '';
            }
            $log_typio_data_html .= $error_data;
            $log_typio_data_html .= '</td>';
            $log_typio_data_html .= '</tr>';
        }
    } else {
        $log_typio_data_html .= '<tr><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
    }

    if ($log_typio_data_html == '<tbody><tr><th>Name</th><th>WPM</th><th>ACC(%)</th><th>'.$lbl.'</th></tr>') {
        $log_typio_data_html .= '<tr><td></td><td></td><td></td><td></td></tr>';
        return $log_typio_data_html;
    }

    $log_typio_data_html .= '</tbody>';

   return $log_typio_data_html;
    //return $log_typio_query;
}


function displayAppBrailDataTableAverageAreaChart($Brail_start_date = 0, $Brail_end_date = 0, $student_id = '') {

global $con, $studentid;

$studentid = !empty($student_id) ? $student_id : $studentid;

$log_data_table = "log";

$log_Brail_query = "SELECT * FROM `" . $log_data_table . "` WHERE ( `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";

$custom_date =  custom_date($Brail_start_date);
$lbl = " Combo";
if($custom_date){
    $lbl = " Errors";
} 
if (( $Brail_start_date != 0 ) && ( $Brail_end_date != 0 )) {
    $Brail_start_date = date('Y-m-d', strtotime($Brail_start_date)) . ' 00:00:00';
    $Brail_end_date = date('Y-m-d', strtotime($Brail_end_date)) . ' 23:59:00';
    $log_Brail_query .= " AND `date` BETWEEN '" . $Brail_start_date . "' AND '" . $Brail_end_date . "' ";
}

$log_Brail_data = mysqli_query($con, $log_Brail_query);

/*"SELECT * FROM `log` WHERE ( `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL' ) AND (`file` LIKE 'Typio-BRL' OR `file` LIKE 'Braillio') AND id='78' AND `date` BETWEEN '2025-09-15 00:00:00' AND '2025-09-16 23:59:00' "*/

$_average_scale = Brail_average_chart_write($student_id, $Brail_start_date, $Brail_end_date);
$_average_scale['start_date'] = !empty($Brail_start_date) ? date('m/d/y', strtotime($Brail_start_date)) : '';
$_average_scale['end_date'] = !empty($Brail_end_date)? date('m/d/y', strtotime($Brail_end_date)) : '';
/**/
$log_Brail_data_html = '<tbody><tr><th>Name</th><th> WPM</th><th>% Accuracy</th><th>'.$lbl.'</th></tr>';

if ($log_Brail_data) {
    //$log_Brail_data_html .= '<tr><td colspan="2"> Average : ('.$_average_scale['start_date'].' - '.$_average_scale['end_date'].')</td><td>'.$_average_scale['WPM'].'</td><td>'.$_average_scale['Accuracy'].'</td><td>'.$_average_scale['Combo'].'</td></tr>';
    while ($log_Brail_data_row = mysqli_fetch_assoc($log_Brail_data)) {
        
        $log_Brail_data_value = explode('|', $log_Brail_data_row['data']);
        $log_Brail_data_html .= '<tr><td>';
        $log_Brail_data_html .= ($log_Brail_data_row['file']) ? $log_Brail_data_row['file'] : '';
        $log_Brail_data_html .= '</td><td>';
        $log_Brail_data_html .= ($log_Brail_data_value[0]) ? $log_Brail_data_value[0] : '';
        $log_Brail_data_html .= '</td><td>';
        $log_Brail_data_html .= ($log_Brail_data_value[1]) ? $log_Brail_data_value[1] : '';
        $log_Brail_data_html .= '</td>';
        $log_Brail_data_html .= '<td>';
        $error_data 	   	  = ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : '';
        if($custom_date){
            $error_data		  = ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : '';
        }
        $log_Brail_data_html .= $error_data;
        $log_Brail_data_html .= '</td>';
        $log_Brail_data_html .= '</tr>';
		
    }
	
} else {
    $log_Brail_data_html .= '<tr><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
}
//echo "<pre>"; print_r($log_Brail_data_html); exit;
if ($log_Brail_data_html == '<tbody><tr><th>Name</th><th>WPM</th><th>ACC(%)</th><th>'.$lbl.'</th></tr>') {
    $log_Brail_data_html .= '<tr><td></td><td></td><td></td><td></td></tr>';
    return $log_Brail_data_html;
}
/**/
$log_Brail_data_html .= '</tbody>';

return $log_Brail_data_html;
//return $log_Brail_query;
}
function displayAppBrailDataTableAverageAreaChartkp($Brail_start_date = 0, $Brail_end_date = 0, $student_id = '') {

global $con, $studentid;

$studentid = !empty($student_id) ? $student_id : $studentid;

//$log_data_table = "log";
$log_data_table = "data";

/*$log_Brail_query = "SELECT * FROM `" . $log_data_table . "` WHERE ( `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";*/
$log_Brail_query = "SELECT * FROM `" . $log_data_table . "` WHERE ( `app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";

$custom_date =  custom_date($Brail_start_date);
$lbl = " Combo";
if($custom_date){
    $lbl = " Errors";
} 
if (( $Brail_start_date != 0 ) && ( $Brail_end_date != 0 )) {
    $Brail_start_date = date('Y-m-d', strtotime($Brail_start_date)) . ' 00:00:00';
    $Brail_end_date = date('Y-m-d', strtotime($Brail_end_date)) . ' 23:59:00';
    $log_Brail_query .= " AND `date` BETWEEN '" . $Brail_start_date . "' AND '" . $Brail_end_date . "' ";
}

$log_Brail_data = mysqli_query($con, $log_Brail_query);

/*"SELECT * FROM `log` WHERE ( `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL' ) AND (`file` LIKE 'Typio-BRL' OR `file` LIKE 'Braillio') AND id='78' AND `date` BETWEEN '2025-09-15 00:00:00' AND '2025-09-16 23:59:00' "*/

$_average_scale = Brail_average_chart_write($student_id, $Brail_start_date, $Brail_end_date);
$_average_scale['start_date'] = !empty($Brail_start_date) ? date('m/d/y', strtotime($Brail_start_date)) : '';
$_average_scale['end_date'] = !empty($Brail_end_date)? date('m/d/y', strtotime($Brail_end_date)) : '';
/**/
$log_Brail_data_html = '<tbody><tr><th>Name</th><th> WPM</th><th>% Accuracy</th><th>'.$lbl.'</th></tr>';

if ($log_Brail_data) {
    //$log_Brail_data_html .= '<tr><td colspan="2"> Average : ('.$_average_scale['start_date'].' - '.$_average_scale['end_date'].')</td><td>'.$_average_scale['WPM'].'</td><td>'.$_average_scale['Accuracy'].'</td><td>'.$_average_scale['Combo'].'</td></tr>';
    while ($log_Brail_data_row = mysqli_fetch_assoc($log_Brail_data)) {
        
        $log_Brail_data_value = explode('|', $log_Brail_data_row['data']);
        $log_Brail_data_html .= '<tr><td>';
        $log_Brail_data_html .= ($log_Brail_data_row['file']) ? $log_Brail_data_row['file'] : '';
        $log_Brail_data_html .= '</td><td>';
        $log_Brail_data_html .= ($log_Brail_data_value[0]) ? $log_Brail_data_value[0] : '';
        $log_Brail_data_html .= '</td><td>';
        $log_Brail_data_html .= ($log_Brail_data_value[1]) ? $log_Brail_data_value[1] : '';
        $log_Brail_data_html .= '</td>';
        $log_Brail_data_html .= '<td>';
        $error_data 	   	  = ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : '';
        if($custom_date){
            $error_data		  = ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : '';
        }
        $log_Brail_data_html .= $error_data;
        $log_Brail_data_html .= '</td>';
        $log_Brail_data_html .= '</tr>';
		
    }
	
} else {
    $log_Brail_data_html .= '<tr><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
}
//echo "<pre>"; print_r($log_Brail_data_html); exit;
if ($log_Brail_data_html == '<tbody><tr><th>Name</th><th>WPM</th><th>ACC(%)</th><th>'.$lbl.'</th></tr>') {
    $log_Brail_data_html .= '<tr><td></td><td></td><td></td><td></td></tr>';
    return $log_Brail_data_html;
}
/**/
$log_Brail_data_html .= '</tbody>';

return $log_Brail_data_html;
//return $log_Brail_query;
}
/**
 * AJAX return for html of App Typio-OL data table for Area Chart
 */
if (!empty($_POST['action_type']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])) {

    //global $con;
    $html = '';
    if ($_POST['action_type'] == 'get_history_data') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $typio_table_data = displayAppTypioData($_POST['start_date'] . ' 00:00:00', $_POST['end_date'] . ' 23:59:59', $student_id);
        $html .= $typio_table_data['html'];
        $total_row = $typio_table_data['total_row'];
        echo json_encode(array('status' => 200, 'html' => $html, 'total_row' => $total_row));
        exit;
    } elseif ($_POST['action_type'] == 'get_history_chart_data') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $html .= displayAppTypioDataTableAverageAreaChart($_POST['start_date'] . ' 00:00:00', $_POST['end_date'] . ' 23:59:59', $student_id);
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }
	elseif ($_POST['action_type'] == 'get_history_chart_data_kp') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $html .= displayAppTypioDataTableAverageAreaChartkp_nw($_POST['start_date'] . ' 00:00:00', $_POST['end_date'] . ' 23:59:59', $student_id);
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }

	elseif ($_POST['action_type'] == 'get_history_chart_data_brail') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $html .= displayAppBrailDataTableAverageAreaChart($_POST['start_date'] . ' 00:00:00', $_POST['end_date'] . ' 23:59:59', $student_id);
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }elseif ($_POST['action_type'] == 'get_history_chart_data_brail_kp') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $html .= displayAppBrailDataTableAverageAreaChartkp($_POST['start_date'] . ' 00:00:00', $_POST['end_date'] . ' 23:59:59', $student_id);
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    } elseif ($_POST['action_type'] == 'get_history_average_chart_data') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $html .= displayAppTypioDataTableAverageBarChart($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59');
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }elseif ($_POST['action_type'] == 'get_history_average_chart_data_kp') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));		
        $html .= displayAppTypioDataTableAverageBarChartkp($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59');
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }elseif ($_POST['action_type'] == 'get_history_average_chart_data_brail') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $html .= displayAppBrailDataTableAverageBarChart($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59');
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    } 
	elseif ($_POST['action_type'] == 'get_history_average_chart_data_brail_kp') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $html .= displayAppBrailDataTableAverageBarChartkp($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59');
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }  elseif ($_POST['action_type'] == 'get_overview_pie_char_history') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $html .= getapplogforweek($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59');
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    } elseif ($_POST['action_type'] == 'get_overview_table_history') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $resultView = getapplogforweekTable($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59');
        echo json_encode(array('status' => 200, 'html' => $resultView['html'], 'total_row' => $resultView['total_row']));
        exit;
    }
    /* START : Get Student Overview graph data */
    else if($_POST['action_type'] == 'get_all_bar_history_data'){
        
        global $con;
        $propack_query = "SELECT IF(sum(value)> 0, sum(value), 0) as sum FROM `activity` where date between '".$_POST['start_date']."' AND '".$_POST['end_date']."' AND id ='".$_POST['student_id']."' and app like 'PP%'";
        $propack_data_result = mysqli_query($con, $propack_query);
        $propack_data_rows = mysqli_num_rows($propack_data_result);
        $propack_data =$arcade_data = $typio_data = $quick_card_data =0;
        if($propack_data_rows > 0){
          $propack_data_arr = mysqli_fetch_assoc($propack_data_result);
           $propack_data =$propack_data_arr['sum'];
           
        }

         $arcade_query = "SELECT IF(sum(value)> 0, sum(value), 0) as sum FROM `activity` where date between '".$_POST['start_date']."' AND '".$_POST['end_date']."' AND id ='".$_POST['student_id']."' and app like 'AA%'";
        $arcade_data_result = mysqli_query($con, $arcade_query);
        $arcade_data_rows = mysqli_num_rows($arcade_data_result);
        if($arcade_data_rows > 0){
            $arcade_data_arr = mysqli_fetch_assoc($arcade_data_result);
            $arcade_data =$arcade_data_arr['sum'];
           
        }

       $quick_card_query = "SELECT IF(sum(value)> 0, sum(value), 0) as sum FROM `activity` where date between '".$_POST['start_date']."' AND '".$_POST['end_date']."' AND id ='".$_POST['student_id']."' and app like 'QC%'";
        $quick_card_result = mysqli_query($con, $quick_card_query);
        $quick_card_data_rows = mysqli_num_rows($quick_card_result);
         if($arcade_data_rows > 0){
            $quick_card_data_arr = mysqli_fetch_assoc($quick_card_result);
             $quick_card_data =$quick_card_data_arr['sum'];
        }


        $typio_query = "SELECT IF(sum(value)> 0, sum(value), 0) as sum FROM `activity` where date between '".$_POST['start_date']."' AND '".$_POST['end_date']."' AND id ='".$_POST['student_id']."' and app like 'TY%'";

        $typio_result = mysqli_query($con, $typio_query);
        $typio_data_rows = mysqli_num_rows($typio_result);
         if($typio_data_rows > 0){
            $typio_data_arr = mysqli_fetch_assoc($typio_result);
            $typio_data =$typio_data_arr['sum'];
        }
        $braillio_query = "SELECT IF(sum(value)> 0, sum(value), 0) as sum FROM `activity` where date between '".$_POST['start_date']."' AND '".$_POST['end_date']."' AND id ='".$_POST['student_id']."' and app like 'BRL%'";

        $braillio_result = mysqli_query($con, $braillio_query);
        $braillio_data_rows = mysqli_num_rows($braillio_result);
         if($braillio_data_rows  > 0){
           $braillio_data_arr = mysqli_fetch_assoc($braillio_result);
           $braillio_data =$braillio_data_arr['sum'];
        }
        
        $json_data =array();
        $data_available ="";
        if($propack_data_rows > 0 || $arcade_data_rows > 0 || $quick_card_data_rows > 0 || $typio_data_rows > 0 || $braillio_data_rows > 0){
            $data_available ="1";

        }
       
       
      
        
        $json_data['0']['name']="Braillio";
        $json_data['0']['data']=[intval($braillio_data)];
       

$json_data['1']['name']="Propack";
        $json_data['1']['data']=[intval($propack_data)];
$json_data['2']['name']="Arcade";
        $json_data['2']['data']=[intval($arcade_data)];
 $json_data['3']['name']="Quick Cards";
        $json_data['3']['data']=[intval($quick_card_data)];
 $json_data['4']['name']="Typio";
        $json_data['4']['data']=[intval($typio_data)];
        echo json_encode(array('status' => 200, 'html' => $json_data,'data_available'=>  $data_available));
        exit;

    }
     /* END : Get Student Overview graph data */
    /* START : Get Student History Overview graph data */
    else if($_POST['action_type'] == 'get_all_bar_student_history_data'){
        
        global $con;
       $start_date= date("Y-m-d",strtotime($_POST['start_date']));
       $end_date= date("Y-m-d",strtotime($_POST['end_date']));
         
     $propack_query = "SELECT IF(sum(value)> 0, sum(value), 0) as sum FROM `activity` where date between '".$start_date."' AND '".$end_date."' AND id ='".$_POST['student_id']."' and app like 'PP%'";
     
        $propack_data_result = mysqli_query($con, $propack_query);
        $propack_data_rows = mysqli_num_rows($propack_data_result);
        $propack_data =$arcade_data = $typio_data = $quick_card_data =0;
        if($propack_data_rows > 0){
          $propack_data_arr = mysqli_fetch_assoc($propack_data_result);
           $propack_data =$propack_data_arr['sum'];
           
        }

         $arcade_query = "SELECT IF(sum(value)> 0, sum(value), 0) as sum FROM `activity` where date between '".$start_date."' AND '".$end_date."' AND id ='".$_POST['student_id']."' and app like 'AA%'";
        $arcade_data_result = mysqli_query($con, $arcade_query);
        $arcade_data_rows = mysqli_num_rows($arcade_data_result);
        if($arcade_data_rows > 0){
            $arcade_data_arr = mysqli_fetch_assoc($arcade_data_result);
            $arcade_data =$arcade_data_arr['sum'];
           
        }

       $quick_card_query = "SELECT IF(sum(value)> 0, sum(value), 0) as sum FROM `activity` where date between '".$start_date."' AND '".$end_date."' AND id ='".$_POST['student_id']."' and app like 'QC%'";
        $quick_card_result = mysqli_query($con, $quick_card_query);
        $quick_card_data_rows = mysqli_num_rows($quick_card_result);
         if($arcade_data_rows > 0){
            $quick_card_data_arr = mysqli_fetch_assoc($quick_card_result);
             $quick_card_data =$quick_card_data_arr['sum'];
        }


        $typio_query = "SELECT IF(sum(value)> 0, sum(value), 0) as sum FROM `activity` where date between '".$start_date."' AND '".$end_date."' AND id ='".$_POST['student_id']."' and app like 'TY%'";

        $typio_result = mysqli_query($con, $typio_query);
        $typio_data_rows = mysqli_num_rows($typio_result);
         if($typio_data_rows > 0){
            $typio_data_arr = mysqli_fetch_assoc($typio_result);
            $typio_data =$typio_data_arr['sum'];
        }
        
      $braillio_query = "SELECT IF(sum(value)> 0, sum(value), 0) as sum FROM `activity` where date between '".$start_date."' AND '".$end_date."' AND id ='".$_POST['student_id']."' and app like 'BRL%'";

        $braillio_result = mysqli_query($con, $braillio_query);
        $braillio_data_rows = mysqli_num_rows($braillio_result);
         if($braillio_data_rows > 0){
            $braillio_data_arr = mysqli_fetch_assoc($braillio_result);
            $braillio_data =$braillio_data_arr['sum'];
        }
        
        $json_data =array();
        $data_available ="";
        if($propack_data_rows > 0 || $arcade_data_rows > 0 || $quick_card_data_rows > 0 || $typio_data_rows > 0 || $braillio_data_rows > 0){
            $data_available ="1";

        }
         $json_data['0']['name']="Braillio";
        $json_data['0']['data']=[intval($braillio_data)];
       

$json_data['1']['name']="Propack";
        $json_data['1']['data']=[intval($propack_data)];
$json_data['2']['name']="Arcade";
        $json_data['2']['data']=[intval($arcade_data)];
 $json_data['3']['name']="Quick Cards";
        $json_data['3']['data']=[intval($quick_card_data)];
 $json_data['4']['name']="Typio";
        $json_data['4']['data']=[intval($typio_data)];
        echo json_encode(array('status' => 200, 'html' => $json_data,'data_available'=>  $data_available));
        exit;

    }
     /* END : Get Student History Overview graph data */
    /* START : Calculate Overview time for student */
    else if($_POST['action_type'] == 'get_all_bar_history_hours'){
        $start_date =date("Y-m-d",strtotime($_POST['start_date']));
        $end_date =date("Y-m-d",strtotime($_POST['end_date']));
        $student_id =$_POST['student_id'];
        $display_type =isset($_POST['display_type'])?$_POST['display_type']:"";
        $get_log = $query_var = array();

    $where = '';
    $current_date = date('Y-m-d');

    //Check user id exist
    if (!empty($student_id)) {
        $query_var[] = " log.id ='" . $student_id . "'";
    }

    if (!empty($start_date) && !empty($end_date)) {
        $query_var[] = " log.date >= '" . $start_date . "' AND log.date <= '" . $end_date . "'";
    }
    $query_var[] = " user.role ='student'";
    $display_type_arr =array();
    if(!empty($display_type)){
        $display_type_arr =explode(",",$display_type);
    }
    
    $type_str ="";
    if(in_array('Typio-OL',$display_type_arr)){
      if($type_str == ""){
            $type_str ="app like 'TY%'";
       }
       else{
            $type_str .=" || app like 'TY%'";
       }
    }
    if(in_array('Propack',$display_type_arr)){
      if($type_str == ""){
            $type_str ="app like 'PP%'";
       }
       else{
            $type_str .=" || app like 'PP%'";
       }
    }
    if(in_array('Quick-Cards-OL',$display_type_arr)){
      if($type_str == ""){
            $type_str ="app like 'QC%'";
       }
       else{
            $type_str .=" || app like 'QC%'";
       }
    }
    if(in_array('Arcade-OL',$display_type_arr)){
      if($type_str == ""){
            $type_str ="app like 'AA%'";
       }
       else{
            $type_str .=" || app like 'AA%'";
       }
    }
if(in_array('Typio-BRL',$display_type_arr)){
      if($type_str == ""){
            $type_str ="app like 'BRL%'";
       }
       else{
            $type_str .=" || app like 'BRL%'";
       }
    }
    if(isset($_POST['search_graph']) && $_POST['search_graph'] ==1 ){
        $type_str =" app like 'TY%' || app like 'PP%' || app like 'QC%' || app like 'AA%' || app like 'BRL%' ";
    }

    if(!empty($display_type) || !empty($type_str))
    {
       $query_var[] = "( ".$type_str." )";
    }

    if (!empty($query_var)) {
        $where = 'WHERE ' . implode(' AND ', $query_var);
    }

    //Get log data to table

    $query = query("SELECT SUM(value) as total FROM activity as log INNER JOIN user ON log.id = user.id $where ORDER BY date DESC ");
   
    //get rows and store on blank data of log
    $logData = '';
    while ($row = mysqli_fetch_array($query)) {
        if (!empty($log)) {
            $logData = $row['app'];
        } else {
            $total = $row['total'];
            if (!empty($total) && $total > 60) {
                $min = $total % 60;
                $hours = floor($total / 60);
                ;
                if ($min) {
                    $min = $min . " min";
                }
                $logData = $hours . " hrs " . $min;
            } else if (!empty($total) && $total < 60) {
                $logData = $total . " min";
            }
        }
    }
    if(!empty($display_type_arr) || !empty($type_str)){
            echo json_encode(array('status' => 200, 'data' => $logData));
    }else{
        echo json_encode(array('status' => 200, 'data' => '0m'));
    }
    exit;
    
    }
     /* END : Calculate Overview time for student */

    if ($_POST['action_type'] == 'get_arcade_history_data') {
        $html .= displayAppArcadeData($_POST['start_date'] . ' 00:00:00', $_POST['end_date'] . ' 23:59:59',$_POST['student_id']);
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }
    echo json_encode(array('status' => 200, 'html' => $html));
    exit;
}

/**
 * Update setting for Typio-OL query
 * 
 * @param string $update_typio_setting_data
 * @return boolean Typio-OL table update status
 */
function updateAppBrailSettingData($update_Brail_setting_data = '') {
//print_r('fbqwoiufbuiqowbfiu');
//print_r($update_Brail_setting_data);

   if($update_Brail_setting_data['Brail_hands_stylenew'] == 0){
$update_Brail_setting_data['Brail_hands_style'] = 2;
$update_Brail_setting_data['visual_keyboard'] = 1;
}else if($update_Brail_setting_data['Brail_hands_stylenew'] == 1){
$update_Brail_setting_data['visual_keyboard'] = 1 ;
$update_Brail_setting_data['Brail_hands_style'] = 0;
}else{
$update_Brail_setting_data['visual_keyboard'] = 0 ;
$update_Brail_setting_data['Brail_hands_style'] = 0;
}

if($update_Brail_setting_data['Brail_theme'] == 'Light'){
$update_Brail_setting_data['Brail_bg_color'] = '0, 0, 8';
$update_Brail_setting_data['Brail_accessory_color'] = '255, 0, 120';
$update_Brail_setting_data['Brail_font_color'] = '255, 255, 254';

}else if($update_Brail_setting_data['Brail_theme'] == 'Dark'){
$update_Brail_setting_data['Brail_bg_color'] ='255, 255, 254';
$update_Brail_setting_data['Brail_accessory_color'] = '255, 0, 120';
$update_Brail_setting_data['Brail_font_color'] = '0, 0, 8';
}
if($update_Brail_setting_data['Brail_keypress'] == 'Speak'){
$update_Brail_setting_data['Brail_keypress'] ='4';
$update_Brail_setting_data['Brail_keypressing'] = '0';

}else if($update_Brail_setting_data['Brail_keypress'] == 'Pet Speech'){
$update_Brail_setting_data['Brail_keypress'] ='0';
$update_Brail_setting_data['Brail_keypressing'] = '1';
}else if($update_Brail_setting_data['Brail_keypress'] == 'Off'){
$update_Brail_setting_data['Brail_keypress'] ='0';
$update_Brail_setting_data['Brail_keypressing'] = '0';
} else {
$update_Brail_setting_data['Brail_keypress'] ='2';
$update_Brail_setting_data['Brail_keypressing'] = '0';
}


if($update_Brail_setting_data['Brail_voice_rate'] == 5){
$update_Brail_setting_data['Brail_voice_rate1'] = 'Slow';
}else  if($update_Brail_setting_data['Brail_voice_rate'] == 10){
$update_Brail_setting_data['Brail_voice_rate1'] = 'Medium';
}else{
$update_Brail_setting_data['Brail_voice_rate1'] = 'Fast';
}
if($update_Brail_setting_data['Brail_voice_pitch'] == 5){
$update_Brail_setting_data['Brail_voice_pitch1'] = 'Low';
}else  if($update_Brail_setting_data['Brail_voice_pitch'] == 9){
$update_Brail_setting_data['Brail_voice_pitch1'] = 'Medium';
}else{
$update_Brail_setting_data['Brail_voice_pitch1'] = 'High';
}


    $update_Brail_setting_ready_data = array(

     564=> $update_Brail_setting_data['acc_colors'], 559 => $update_Brail_setting_data['soundeffect'], 517 => $update_Brail_setting_data['Brail_font_style'], 565 => $update_Brail_setting_data['Brail_keypressing'],562 => $update_Brail_setting_data['Brail_voice_rate1'], 563 => $update_Brail_setting_data['Brail_voice_pitch1'], 516 => $update_Brail_setting_data['Brail_font_color'], 560 => $update_Brail_setting_data['Brail_theme'], 
        518 => $update_Brail_setting_data['Brail_bg_color'], 519 => $update_Brail_setting_data['Brail_accessory_color'], 525 => $update_Brail_setting_data['Brail_keypress'], 
         531 => $update_Brail_setting_data['Brail_accuracy_goal'], 
        529 => $update_Brail_setting_data['Brail_wpm_goal'], 530 => $update_Brail_setting_data['Brail_goal_lock'], 533 => $update_Brail_setting_data['Brail_game_lock'], 
        534 => $update_Brail_setting_data['Brail_setting_lock'], 528 => $update_Brail_setting_data['visual_keyboard'], 538 => $update_Brail_setting_data['Brail_smart_wpm'], 
        521 => $update_Brail_setting_data['Brail_voice_rate'], 522 => $update_Brail_setting_data['Brail_voice_pitch'], 550 => $update_Brail_setting_data['Brail_curriculumn'],
        542 => $update_Brail_setting_data['Brail_visual_fx'],541 => $update_Brail_setting_data['Brail_subtitles'], 543 => $update_Brail_setting_data['Brail_selection_color'],
       549 => $update_Brail_setting_data['Brail_visual_hands'],551 => $update_Brail_setting_data['Brail_hands_style'],
        539 => $update_Brail_setting_data['Brail_short_lesson'],548 => $update_Brail_setting_data['Brail_short_lesson'],520 => $update_Brail_setting_data['Brail_voice'],
   561 => $update_Brail_setting_data['Brail_font_size'], 515 => $update_Brail_setting_data['Brail_font_size']
    );
    /* START : SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    $student_id ='';
    if ($update_Brail_setting_data['BrailSettingSubmit']  == 'Save Braillio Settings For All Students') {
         $student_id =$update_Brail_setting_data['student'];

    }else{

$update_Brail_setting_ready_data = array(

     564=> $update_Brail_setting_data['acc_colors'], 559 => $update_Brail_setting_data['soundeffect'], 517 => $update_Brail_setting_data['Brail_font_style'], 565 => $update_Brail_setting_data['Brail_keypressing'],562 => $update_Brail_setting_data['Brail_voice_rate1'], 563 => $update_Brail_setting_data['Brail_voice_pitch1'], 516 => $update_Brail_setting_data['Brail_font_color'], 560 => $update_Brail_setting_data['Brail_theme'], 
        518 => $update_Brail_setting_data['Brail_bg_color'], 519 => $update_Brail_setting_data['Brail_accessory_color'], 525 => $update_Brail_setting_data['Brail_keypress'], 
         531 => $update_Brail_setting_data['Brail_accuracy_goal'], 
        529 => $update_Brail_setting_data['Brail_wpm_goal'], 530 => $update_Brail_setting_data['Brail_goal_lock'], 533 => $update_Brail_setting_data['Brail_game_lock'], 
        534 => $update_Brail_setting_data['Brail_setting_lock'], 528 => $update_Brail_setting_data['visual_keyboard'], 538 => $update_Brail_setting_data['Brail_smart_wpm'], 
        521 => $update_Brail_setting_data['Brail_voice_rate'], 522 => $update_Brail_setting_data['Brail_voice_pitch'], 550 => $update_Brail_setting_data['Brail_curriculumn'],
        542 => $update_Brail_setting_data['Brail_visual_fx'],541 => $update_Brail_setting_data['Brail_subtitles'], 543 => $update_Brail_setting_data['Brail_selection_color'],
        536 => $update_Brail_setting_data['Brail_pet_coins'],549 => $update_Brail_setting_data['Brail_visual_hands'],551 => $update_Brail_setting_data['Brail_hands_style'],
        539 => $update_Brail_setting_data['Brail_short_lesson'],548 => $update_Brail_setting_data['Brail_short_lesson'],520 => $update_Brail_setting_data['Brail_voice'],
   561 => $update_Brail_setting_data['Brail_font_size'], 515 => $update_Brail_setting_data['Brail_font_size']
    );

}
    /* END : SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    $update_Brail_setting_final_status = 1;
    foreach ($update_Brail_setting_ready_data as $update_Brail_setting_data_id => $update_Brail_setting_data_variable) {
        $update_Brail_setting_status = updateAppBrailSettingField($update_Brail_setting_data_id, $update_Brail_setting_data_variable,$student_id);
        if ($update_Brail_setting_status == 0) {
            $update_Brail_setting_final_status = 0;
        }
    }
    return $update_Brail_setting_final_status;
}

/**
 * Update setting for Brail-OL field
 * 
 * @param int $update_Brail_setting_item_id 
 * @param string $update_Brail_setting_variable 
 * @return boolean Brail-OL table update status
 */
function updateAppBrailSettingField($update_Brail_setting_item_id = 0, $update_Brail_setting_variable = '',$student_id='') {
    global $con, $studentid;
    /* START: SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    if( $student_id !="" && $student_id > 0){
        $studentid =$student_id;
    }
    /* END: SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    $Brail_settings = 'settings';
    //$Brail_settings_update_query = "UPDATE `" . $Brail_settings . "` SET `variable`='" . $update_Brail_setting_variable . "' WHERE `id`='" . $studentid . "' AND `item`=" . $update_Brail_setting_item_id;
    $Brail_settings_update_query = "REPLACE INTO `" . $Brail_settings . "` ( `variable`, `id`, `item` ) VALUES ( '" . $update_Brail_setting_variable . "', '" . $studentid . "', '" . $update_Brail_setting_item_id . "' )";
    $Brail_settings_update_query_run = mysqli_query($con, $Brail_settings_update_query);
    return $Brail_settings_update_query_run;
}

/**
 * Update setting for Typio-OL query
 * 
 * @param string $update_typio_setting_data
 * @return boolean Typio-OL table update status
 */
function updateAppTypioSettingData($update_typio_setting_data = '') {
   if($update_typio_setting_data['typio_hands_stylenew'] == 0){
$update_typio_setting_data['typio_hands_style'] = 2;
$update_typio_setting_data['visual_keyboard'] = 1;
}else if($update_typio_setting_data['typio_hands_stylenew'] == 1){
$update_typio_setting_data['visual_keyboard'] = 1 ;
$update_typio_setting_data['typio_hands_style'] = 0;
}else{
$update_typio_setting_data['visual_keyboard'] = 0 ;
$update_typio_setting_data['typio_hands_style'] = 0;
}

if($update_typio_setting_data['typio_theme'] == 'Light'){
$update_typio_setting_data['typio_bg_color'] = '0, 0, 8';
$update_typio_setting_data['typio_accessory_color'] = '255, 0, 120';
$update_typio_setting_data['typio_font_color'] = '255, 255, 254';

}else if($update_typio_setting_data['typio_theme'] == 'Dark'){
$update_typio_setting_data['typio_bg_color'] ='255, 255, 254';
$update_typio_setting_data['typio_accessory_color'] = '255, 0, 120';
$update_typio_setting_data['typio_font_color'] = '0, 0, 8';
}
if($update_typio_setting_data['typio_keypress'] == 'Speak'){
$update_typio_setting_data['typio_keypress'] ='4';
$update_typio_setting_data['typio_keypressing'] = '0';

}else if($update_typio_setting_data['typio_keypress'] == 'Pet Speech'){
$update_typio_setting_data['typio_keypress'] ='0';
$update_typio_setting_data['typio_keypressing'] = '1';
}else if($update_typio_setting_data['typio_keypress'] == 'Off'){
$update_typio_setting_data['typio_keypress'] ='0';
$update_typio_setting_data['typio_keypressing'] = '0';
} else {
$update_typio_setting_data['typio_keypress'] ='2';
$update_typio_setting_data['typio_keypressing'] = '0';
}


if($update_typio_setting_data['typio_voice_rate'] == 5){
$update_typio_setting_data['typio_voice_rate1'] = 'Slow';
}else  if($update_typio_setting_data['typio_voice_rate'] == 10){
$update_typio_setting_data['typio_voice_rate1'] = 'Medium';
}else{
$update_typio_setting_data['typio_voice_rate1'] = 'Fast';
}
if($update_typio_setting_data['typio_voice_pitch'] == 5){
$update_typio_setting_data['typio_voice_pitch1'] = 'Low';
}else  if($update_typio_setting_data['typio_voice_pitch'] == 9){
$update_typio_setting_data['typio_voice_pitch1'] = 'Medium';
}else{
$update_typio_setting_data['typio_voice_pitch1'] = 'High';
}


    $update_typio_setting_ready_data = array(

     164=> $update_typio_setting_data['acc_colors'], 159 => $update_typio_setting_data['soundeffect'], 117 => $update_typio_setting_data['typio_font_style'], 165 => $update_typio_setting_data['typio_keypressing'],162 => $update_typio_setting_data['typio_voice_rate1'], 163 => $update_typio_setting_data['typio_voice_pitch1'], 116 => $update_typio_setting_data['typio_font_color'], 160 => $update_typio_setting_data['typio_theme'], 
        118 => $update_typio_setting_data['typio_bg_color'], 119 => $update_typio_setting_data['typio_accessory_color'], 125 => $update_typio_setting_data['typio_keypress'], 
        131 => $update_typio_setting_data['typio_accuracy_goal'], 
        129 => $update_typio_setting_data['typio_wpm_goal'], 130 => $update_typio_setting_data['typio_goal_lock'], 133 => $update_typio_setting_data['typio_game_lock'], 
        134 => $update_typio_setting_data['typio_setting_lock'], 128 => $update_typio_setting_data['visual_keyboard'], 138 => $update_typio_setting_data['typio_smart_wpm'], 
        121 => $update_typio_setting_data['typio_voice_rate'], 122 => $update_typio_setting_data['typio_voice_pitch'], 150 => $update_typio_setting_data['typio_curriculumn'],
        142 => $update_typio_setting_data['typio_visual_fx'], 141 => $update_typio_setting_data['typio_subtitles'], 143 => $update_typio_setting_data['typio_selection_color'],
        149 => $update_typio_setting_data['typio_visual_hands'],151 => $update_typio_setting_data['typio_hands_style'],
        139 => $update_typio_setting_data['typio_short_lesson'],148 => $update_typio_setting_data['typio_short_lesson'],120 => $update_typio_setting_data['typio_voice'],
   161 => $update_typio_setting_data['typio_font_size'], 115 => $update_typio_setting_data['typio_font_size'],167 => $update_typio_setting_data['acc_simple_score']
    );
    /* START : SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    $student_id ='';
    if ($update_typio_setting_data['TypioSettingSubmit']  == 'Save Typio Settings For All Students') {
         $student_id =$update_typio_setting_data['student'];

    }else{

 $update_typio_setting_ready_data = array(

     164=> $update_typio_setting_data['acc_colors'], 159 => $update_typio_setting_data['soundeffect'], 117 => $update_typio_setting_data['typio_font_style'], 165 => $update_typio_setting_data['typio_keypressing'],162 => $update_typio_setting_data['typio_voice_rate1'], 163 => $update_typio_setting_data['typio_voice_pitch1'], 116 => $update_typio_setting_data['typio_font_color'], 160 => $update_typio_setting_data['typio_theme'], 
        118 => $update_typio_setting_data['typio_bg_color'], 119 => $update_typio_setting_data['typio_accessory_color'], 125 => $update_typio_setting_data['typio_keypress'], 
       131 => $update_typio_setting_data['typio_accuracy_goal'], 
        129 => $update_typio_setting_data['typio_wpm_goal'], 130 => $update_typio_setting_data['typio_goal_lock'], 133 => $update_typio_setting_data['typio_game_lock'], 
        134 => $update_typio_setting_data['typio_setting_lock'], 128 => $update_typio_setting_data['visual_keyboard'], 138 => $update_typio_setting_data['typio_smart_wpm'], 
        121 => $update_typio_setting_data['typio_voice_rate'], 122 => $update_typio_setting_data['typio_voice_pitch'], 150 => $update_typio_setting_data['typio_curriculumn'],
        142 => $update_typio_setting_data['typio_visual_fx'], 141 => $update_typio_setting_data['typio_subtitles'], 143 => $update_typio_setting_data['typio_selection_color'],
        136 => $update_typio_setting_data['typio_pet_coins'],149 => $update_typio_setting_data['typio_visual_hands'],151 => $update_typio_setting_data['typio_hands_style'],
        139 => $update_typio_setting_data['typio_short_lesson'],148 => $update_typio_setting_data['typio_short_lesson'],120 => $update_typio_setting_data['typio_voice'],
   161 => $update_typio_setting_data['typio_font_size'], 115 => $update_typio_setting_data['typio_font_size'],167 => $update_typio_setting_data['acc_simple_score']
    );
}
    /* END : SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    $update_typio_setting_final_status = 1;
    foreach ($update_typio_setting_ready_data as $update_typio_setting_data_id => $update_typio_setting_data_variable) {
        $update_typio_setting_status = updateAppTypioSettingField($update_typio_setting_data_id, $update_typio_setting_data_variable,$student_id);
        if ($update_typio_setting_status == 0) {
            $update_typio_setting_final_status = 0;
        }
    }
    return $update_typio_setting_final_status;
}

/**
 * Update setting for Typio-OL field
 * 
 * @param int $update_typio_setting_item_id 
 * @param string $update_typio_setting_variable 
 * @return boolean Typio-OL table update status
 */
function updateAppTypioSettingField($update_typio_setting_item_id = 0, $update_typio_setting_variable = '',$student_id='') {
    global $con, $studentid;
    /* START: SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    if( $student_id !="" && $student_id > 0){
        $studentid =$student_id;
    }
    /* END: SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    $typio_settings = 'settings';
    //$typio_settings_update_query = "UPDATE `" . $typio_settings . "` SET `variable`='" . $update_typio_setting_variable . "' WHERE `id`='" . $studentid . "' AND `item`=" . $update_typio_setting_item_id;
    $typio_settings_update_query = "REPLACE INTO `" . $typio_settings . "` ( `variable`, `id`, `item` ) VALUES ( '" . $update_typio_setting_variable . "', '" . $studentid . "', '" . $update_typio_setting_item_id . "' )";
    $typio_settings_update_query_run = mysqli_query($con, $typio_settings_update_query);
    return $typio_settings_update_query_run;
}

function updateAppTypioSettingData_old($update_typio_setting_data = '') {

    $update_typio_setting_ready_data = array(
          115 => $update_typio_setting_data['typio_font_size'], 117 => $update_typio_setting_data['typio_font_style'], 116 => $update_typio_setting_data['typio_font_color'], 
        118 => $update_typio_setting_data['typio_bg_color'], 119 => $update_typio_setting_data['typio_accessory_color'], 125 => $update_typio_setting_data['typio_keypress'], 
       131 => $update_typio_setting_data['typio_accuracy_goal'], 
        129 => $update_typio_setting_data['typio_wpm_goal'], 130 => $update_typio_setting_data['typio_goal_lock'], 133 => $update_typio_setting_data['typio_game_lock'], 
        134 => $update_typio_setting_data['typio_setting_lock'], 128 => $update_typio_setting_data['visual_keyboard'], 138 => $update_typio_setting_data['typio_smart_wpm'], 
        121 => $update_typio_setting_data['typio_voice_rate'], 122 => $update_typio_setting_data['typio_voice_pitch'], 150 => $update_typio_setting_data['typio_curriculumn'],
        142 => $update_typio_setting_data['typio_visual_fx'], 141 => $update_typio_setting_data['typio_subtitles'], 143 => $update_typio_setting_data['typio_selection_color'],
        136 => $update_typio_setting_data['typio_pet_coins'],149 => $update_typio_setting_data['typio_visual_hands'],151 => $update_typio_setting_data['typio_hands_style'],
        139 => $update_typio_setting_data['typio_short_lesson'],148 => $update_typio_setting_data['typio_spell_mode'],120 => $update_typio_setting_data['typio_voice']

    );
    /* START : SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    $student_id ='';
    if ($update_typio_setting_data['TypioSettingSubmit']  == 'Save Typio Settings For All Students') {
         $student_id =$update_typio_setting_data['student'];

    }
    /* END : SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    $update_typio_setting_final_status = 1;
    foreach ($update_typio_setting_ready_data as $update_typio_setting_data_id => $update_typio_setting_data_variable) {
        $update_typio_setting_status = updateAppTypioSettingField_old($update_typio_setting_data_id, $update_typio_setting_data_variable,$student_id);
        if ($update_typio_setting_status == 0) {
            $update_typio_setting_final_status = 0;
        }
    }
    return $update_typio_setting_final_status;
}

/**
 * Update setting for Typio-OL field
 * 
 * @param int $update_typio_setting_item_id 
 * @param string $update_typio_setting_variable 
 * @return boolean Typio-OL table update status
 */
function updateAppTypioSettingField_old($update_typio_setting_item_id = 0, $update_typio_setting_variable = '',$student_id='') {
    global $con, $studentid;
    /* START: SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    if( $student_id !="" && $student_id > 0){
        $studentid =$student_id;
    }
    /* END: SET FOR ALL STUDENT ADDED BY PHP DEV 6 ON 03-09-2021 */
    $typio_settings = 'settings';
    //$typio_settings_update_query = "UPDATE `" . $typio_settings . "` SET `variable`='" . $update_typio_setting_variable . "' WHERE `id`='" . $studentid . "' AND `item`=" . $update_typio_setting_item_id;
    $typio_settings_update_query = "REPLACE INTO `" . $typio_settings . "` ( `variable`, `id`, `item` ) VALUES ( '" . $update_typio_setting_variable . "', '" . $studentid . "', '" . $update_typio_setting_item_id . "' )";
    $typio_settings_update_query_run = mysqli_query($con, $typio_settings_update_query);
    return $typio_settings_update_query_run;
}

/**
 * Display App Typio-OL data
 * 
 * @param string $typio_start_date 
 * @param string $typio_end_date 
 * @return string Typio-OL table html
 */
function displayAppLessonsData($typio_start_date = 0, $typio_end_date = 0) {
    global $con, $studentid;
    $text_data_table = "text";
    $text_typio_query = "SELECT * FROM `" . $text_data_table . "` WHERE `app` LIKE 'Typio-OL' AND id='" . $studentid . "'";
    /* if(($typio_start_date!=0) && ($typio_end_date!=0)){
      $log_typio_query .= " AND `date` BETWEEN '".$typio_start_date."' AND '".$typio_end_date."' ";
      } */
    $text_typio_data = mysqli_query($con, $text_typio_query);
    $text_typio_data_html = '';
    $text_typio_data_count = 0;
    while ($text_typio_data_row = mysqli_fetch_assoc($text_typio_data)) {
        $text_typio_data_count++;

        $text_typio_data_html .= '<tr><td>' . $text_typio_data_count . '</td><td>';
        $text_typio_data_html .= ($text_typio_data_row['title']) ? $text_typio_data_row['title'] : '';
        $text_typio_data_html .= '</td><td>';
        $text_typio_data_html .= '<td>';
        $text_typio_data_html .= '<td>';
        $text_typio_data_html .= '<td>';
        $text_typio_data_html .= '<td><tr>';
    }
    return $log_typio_data_html;
}

/* * **Quick Cards Page ** */

/**
 * Get App Quick Card data
 *
 * @param $user_id
 * @param $start_date
 * @param $end_date
 * @return string
 */
function get_QuickCards_data($user_id = 0, $start_date = 0, $end_date = 0) {

    global $con;
    $Quickcard_data = array();

    //Set Table Name
    $table = "log";

    //Build Query
    $query = "SELECT * FROM `" . $table . "` WHERE `app` = 'Quick-Cards-OL' ";
    
    //Check and pass extra param
    if (( $start_date != 0 ) && ( $end_date != 0 )) {
        $query .= " AND `date` BETWEEN '" . $start_date . "' AND '" . $end_date . "' ";
    }

    if (!empty($user_id)) {
        $query .= " AND `id` = '" . $user_id . "' ";
    }    
    $Quickcard_data_rows = mysqli_query($con, $query);

    while ($Quickcard_data_row = mysqli_fetch_assoc($Quickcard_data_rows)) {

        $data_value = explode('|', $Quickcard_data_row['data']);
        if (!empty($data_value[0])) {
            $Quickcard_data_row['percent_score'] = $data_value[0];
        }
        if (!empty($data_value[1])) {
            $Quickcard_data_row['incorrect'] = $data_value[1];
        }
        if (!empty($data_value[2])) {
            $cards_missed = explode('~', $data_value[2]);
            $Quickcard_data_row['cards_missed'] = $cards_missed;
        }

        $Quickcard_data[] = $Quickcard_data_row;
    }
    return $Quickcard_data;
}

/**
 * Get App Quick Card data
 *
 * @param $user_id
 * @param $start_date
 * @param $end_date
 * @return string
 */
function get_data_from_text_table($user_id = 0, $app_type, $number = 0) {

    global $con;
    $text_data = $query_val = array();

    //Set Table Name
    $table = "text";

    if (!empty($user_id)) {
        $query_val[] = " `id`='" . $user_id . "'";
    }

    if (!empty($app_type)) {
        $query_val[] = " `app`='" . $app_type . "'";
    }

    if (!empty($number) || $number == 0) {
        $query_val[] = " `number`='" . $number . "'";
    }

    if (!empty($query_val)) {
        $where = ' WHERE ' . implode(' AND', $query_val);
    }

    //Build Query
    $query = "SELECT * FROM `" . $table . "` $where ";

    $text_data_rows = mysqli_query($con, $query);

    while ($text_data_row = mysqli_fetch_assoc($text_data_rows)) {

        $text_data[] = $text_data_row;
    }

    return $text_data_rows;
}

if (isset($_POST) && !empty($_POST)) {
    extract($_POST);
    // *
    //  * Display data to edit in modal popup
    //Renew license process 
    if (!empty($user_id) && isset($type) && $type == 'student-license-updating') {

        $html = '';

        $html .= '<div class="active-license-wrap">';
        $html .= '<p>Add your license key</p>';
        $html .= '<form id="active-license-form" name="active-license-form">';
        $html .= '<input type="hidden" name="user_id" value="' . $user_id . '">';

        $html .= '<p><input class="form-control" type="text" name="edd_license"></p>';
        $html .= '<div class="submit_wrap">
	                	<button type="submit" class="btn btn-primary edit-lessons-btn">Add License</button>
	            	</div>';
        $html .= '</form>';
        $html .= '</div>';

        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }



    if (isset($_POST['startdate']) && isset($_POST['enddate']) && isset($_POST['typio']) && isset($_POST['qc']) && isset($_POST['ac'])) {

        array_filter($_POST);

        extract($_POST);

        $where = '';
        $query_where_build = $result = array();

        if ($typio != '') {
            $app1 = 'Typio-OL';
        } else {
            $app1 = '';
        }
        if ($qc != '') {
            $app2 = 'Quick-Cards-OL';
        } else {
            $app2 = '';
        }
        if ($ac != '') {
            $app3 = 'Arcade-OL';
        } else {
            $app3 = '';
        }

        if (!empty($_POST['this_month'])) {
            $startdate = date('Y-m-d', strtotime('first day of this month'));
            $enddate = date('Y-m-d', strtotime('last day of this month'));
        }

        if (!empty($_POST['this_week'])) {
            $monday = strtotime("last monday");
            $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;

            $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");

            $startdate = date("Y-m-d", $monday);
            $enddate = date("Y-m-d", $sunday);
        }

        if (!empty($startdate) && !empty($enddate)) {
            $query_where_build[] = "log.date BETWEEN '{$startdate}'  AND '{$enddate}' ";
        }

        if (!empty($student_id)) {
            $query_where_build[] = "id='" . $student_id . "'";
        }

        if (!empty($app1) || !empty($app2) || !empty($app3)) {
            $query_where_build[] = "log.app IN ('$app1','$app2','$app3')";
        }

        $where = implode(' AND ', $query_where_build);

        if (!empty($where)) {
            $where = 'WHERE ' . $where;
        }

        //$where .= " WHERE log.date BETWEEN '{$startdate}' AND '{$enddate}' AND id='". $student_id ."'";
        //$where .= " AND log.app IN ('$app1','$app2','$app3')";

        $sql = query("SELECT log.id,log.app,log.date FROM log $where");
        $data = array();

        while ($row = fetch($sql)) {
            $data[] = $row;
        }

        $table = "<tbody><tr><th></th><td></td></tr>";
        $t = 0;
        $q = 0;
        $a = 0;

        foreach ($data as $key => $value) {
            if ($value['app'] == 'Typio-OL') {
                $t++;
            }
            if ($value['app'] == 'Quick-Cards-OL') {
                $q++;
            }
            if ($value['app'] == 'Arcade-OL') {
                $a++;
            }
        }

        $table .= '<tr><th>Typio-OL</th><td>' . $t . '</td></tr>';
        $table .= '<tr><th>Quick-Cards-OL</th><td>' . $q . '</td></tr>';
        $table .= '<tr><th>Arcade-OL</th><td>' . $a . '</td></tr>';
        $table .= '</tbody>';
        $table_data = array();
        $table_data['html'] = $table;
        $table_data['status'] = 0;
        if ($a == 0 && $q == 0 && $t == 0) {
            $table_data['data'] = 'no data';
        }

        //all types chart data
        $result['main_chart'] = $table_data;

        $log_data_table = "log";
        $log_data_table_wpm_title = 'WPM';
        $log_data_table_accuracy_title = 'ACC(%)';
        $log_data_table_combo_title = 'COM';
        $log_data_table_wpm_value = 0;
        $log_data_table_accuracy_value = 0;
        $log_data_table_combo_value = 0;
        $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE `app` LIKE 'Typio-OL' AND id='" . $student_id . "'";

        if (!empty($startdate) && !empty($enddate)) {
            $log_typio_query .= " AND `date` BETWEEN '" . $startdate . "' AND '" . $enddate . "' ";
        }

        $log_typio_data = mysqli_query($con, $log_typio_query);
        $log_typio_data1 = mysqli_query($con, $log_typio_query);
        $log_typio_data2 = mysqli_query($con, $log_typio_query);

        $log_typio_data_count = 0;
        $log_typio_data_html = '';

        while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
            $log_typio_data_count++;
            $log_typio_data_value = explode('|', $log_typio_data_row['data']);
            $log_data_table_wpm_value += ($log_typio_data_value[0]) ? $log_typio_data_value[0] : 0;
            $log_data_table_accuracy_value += ($log_typio_data_value[1]) ? $log_typio_data_value[1] : 0;
            $log_data_table_combo_value += ($log_typio_data_value[2]) ? $log_typio_data_value[2] : 0;
        }

        $log_typio_data_html .= "<tr></tr>";
        $log_typio_data_html .= '<tr><td>' . $log_data_table_wpm_title . '</td><td>' . (!empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : 0) . '</td></tr>';
        $log_typio_data_html .= '<tr><td>' . $log_data_table_accuracy_title . '</td><td>' . floor(($log_data_table_accuracy_value / $log_typio_data_count)) . '</td></tr>';
        $log_typio_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' . floor(($log_data_table_combo_value / $log_typio_data_count)) . '</td></tr>';

        $result['typio_chart'] = $log_typio_data_html;

        $log_typio_html = '<tbody><tr><th>Lesson</th><th>Date</th><th>WPM</th><th>ACC</th><th>COM</th></tr>';

        $result['typio_table_html_total_row'] = mysqli_num_rows($log_typio_data1);

        while ($log_typio_data_row = mysqli_fetch_array($log_typio_data1)) {

            $log_typio_data_value = explode('|', $log_typio_data_row['data']);
            $log_typio_html .= '<tr><td>';
            $log_typio_html .= ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
            $log_typio_html .= '</td><td>';
            $log_typio_html .= ($log_typio_data_row['date']) ? date('m/d/y', strtotime($log_typio_data_row['date'])) : '';
            $log_typio_html .= '</td><td>';
            $log_typio_html .= ($log_typio_data_value[0]) ? $log_typio_data_value[0] : '';
            $log_typio_html .= '</td><td>';
            $log_typio_html .= ($log_typio_data_value[1]) ? $log_typio_data_value[1] . '%' : '';
            $log_typio_html .= '</td><td>';
            $log_typio_html .= ($log_typio_data_value[2]) ? $log_typio_data_value[2] : '';
            $log_typio_html .= '</td>';

            $log_typio_html .= '</tr>';
        }

        $log_typio_html .= '</tbody>';

        $result['typio_table_html_count'] = '<strong>' . $result['typio_table_html_total_row'] . ' Lessons complete </strong>';
        $result['typio_table_html'] = $log_typio_html;


        $log_typio_area_chart_html = '<tbody><tr><th>Name</th><th>WPM</th><th>ACC(%)</th></tr>';

        if ($log_typio_data2) {
            while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data2)) {

                $log_typio_data_value = explode('|', $log_typio_data_row['data']);
                $log_typio_area_chart_html .= '<tr><td>';
                $log_typio_area_chart_html .= ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
                $log_typio_area_chart_html .= '</td><td>';
                $log_typio_area_chart_html .= ($log_typio_data_value[0]) ? $log_typio_data_value[0] : '';
                $log_typio_area_chart_html .= '</td><td>';
                $log_typio_area_chart_html .= ($log_typio_data_value[1]) ? $log_typio_data_value[1] : '';
                $log_typio_area_chart_html .= '</td>';
                $log_typio_area_chart_html .= '</tr>';
            }
        } else {
            $log_typio_area_chart_html .= '<tr><th></th><th></th><th></th></tr>';
        }
        $log_typio_area_chart_html .= '</tbody>';

        $result['typio_area_chart_html'] = $log_typio_area_chart_html;

        $QuickCards_data = get_QuickCards_data($student_id, $startdate, $enddate);

        $QuickCards_chart_data = '';
        $QuickCards_chart_data .= '<tbody> <tr> <th></th> <th>Percent</th></tr>';

        if (!empty($QuickCards_data)) {
            foreach ($QuickCards_data as $key => $value) {

                $QuickCards_chart_data .= '<tr>';
                $QuickCards_chart_data .= '<td>' . $value['file'] . '</td>';
                $QuickCards_chart_data .= '<td>' . $value['percent_score'] . '</td>';
                $QuickCards_chart_data .= '</tr>';
            }
        }

        $QuickCards_chart_data .= '</tbody>';
        $result['QuickCards_chart_data'] = $QuickCards_chart_data;

        $QuickCards_data_html = '';
        $QuickCards_data_html .= '<tbody><tr><th>Name</th><th>Score</th><th># Missed</th><th>Missed Cards</th><th>Date</th><th>Action</th></tr>';

        if (!empty($QuickCards_data)) {

            foreach ($QuickCards_data as $key => $value) {
                $QuickCards_data_html .= '<tr>';
                $QuickCards_data_html .= '<td>' . $value['file'] . '</td>';
                $QuickCards_data_html .= '<td>' . $value['percent_score'] . '%</td>';
                $QuickCards_data_html .= '<td>' . $value['incorrect'] . '</td>';
                $QuickCards_data_html .= '<td>' . implode(', ', $value['cards_missed']) . '</td>';
                $QuickCards_data_html .= '<td>' . date('m/d/y', strtotime($value['date'])) . '</td>';
                $QuickCards_data_html .= '</tr>';
            }
        }
        $QuickCards_data_html .= '</tbody>';

        $result['QuickCards_data_html_count'] = '<strong>' . count($QuickCards_data) . ' Lessons complete </strong>';
        $result['QuickCards_data_html'] = $QuickCards_data_html;

        $arcade_history = getArcadeHistory($student_id, $startdate, $enddate);

        $arcade_data_html = '';
        $arcade_data_html .= '<tbody><tr><th>Game</th><th>Date</th> </tr>';

        if (!empty($arcade_history)) {

            foreach ($arcade_history as $key => $value) {

                $arcade_data_html .= '<tr>';
                $arcade_data_html .= '<td>' . $value['file'] . '</td>';
                $arcade_data_html .= '<td>' . date('m/d/y', strtotime($value['date'])) . '</td>';
                $arcade_data_html .= '</tr>';
            }
        }

        $arcade_data_html .= '</tbody>';

        $result['arcade_data_html_count'] = '<strong>' . count($arcade_history) . '  Games played </strong>';
        $result['arcade_data_html'] = $arcade_data_html;
        echo json_encode($result);
        exit;
    }

    /**
     * Delete Student record
     */
    if (!empty($_POST['action']) && $_POST['action'] == "delete_student_account" && !empty($_POST['student_delete_id'])) {

        $studentid = $_POST['student_delete_id'];
//        $item_ids = array(5, 6, 7, 8);
        if ($studentid) {
            $query = 'SELECT id,license FROM user where `id`=' . $studentid;
//            $query = 'SELECT * FROM settings where `id`=' . $studentid . ' AND item IN (' . implode(',', $item_ids) . ')';
            $query_result = mysqli_query($con, $query);

//            $LICENSCE_TYPE_AND_ITEM_ID = array(
//                '5' => 'Typio',
//                '6' => 'ProPack',
//                '7' => 'Accessibyte Arcade',
//                '8' => 'Quick Cards',
//            );

            $license_key_array = array();
            while ($row = mysqli_fetch_assoc($query_result)) {
                $ItemType = '';
                if ($row['license'] != '') {
                    $val = explode("-", $row['license']);

                    if ($val[0] == "PRO")
                        $ItemType = "ProPack";
                    else if ($val[0] == "BDL")
                        $ItemType = "All Access";
                    elseif ($val[0] == "TYO")
                        $ItemType = "Typio";
                    else if ($val[0] == "TCH")
                        $ItemType = "Teacher";
                    else if ($val[0] == "QCO")
                        $ItemType = "Quick Cards";
                    else if ($val[0] == "AA")
                        $ItemType = "Accessibyte Arcade";
                }


                $check_license_key_exists = query('SELECT license_key FROM unassign_license_keys where `license_key`="' . trim($row['license']) . '" order by license_key desc');
                if ($check_license_key_exists->num_rows <= 0 || $check_license_key_exists->num_rows == '') {
                    query("INSERT INTO `unassign_license_keys` (`license_type`, `license_key`, `created_by`) VALUES ('" . $ItemType . "','" . trim($row['license']) . "','" . $_SESSION['User']['id'] . "')");
                }

                $license_key_array[] = trim($row['license']);
				if(!empty($row['license'])){
                    update_no_student_license($row['license'],'student');
                }
            }
			//echo "<pre>";print_r($license_key_array);
            $wp_result = deactive_licenses($license_key_array, $studentid);
			
            /*if ($wp_result->success == 1) {*/

                query('DELETE FROM `user` WHERE id="' . $studentid . '"');
                query('DELETE FROM `settings` WHERE id="' . $studentid . '"');
                query('DELETE FROM `data` WHERE id="' . $studentid . '"');
                query('DELETE FROM `text` WHERE id="' . $studentid . '"');

                $check_user_exists = query('SELECT user_id FROM wordpress_licenses where `user_id`="' . $studentid . '"');
                if ($check_user_exists->num_rows > 0) {
                    query("UPDATE `wordpress_licenses` SET `user_id`=0 WHERE `user_id`='" . $studentid . "'");
                }

//            if(!empty($license_key_array)){
//                foreach($license_key_array as $value){
//                    
//                    $user_ids = query('SELECT GROUP_CONCAT(DISTINCT settings.id) as user_ids FROM settings JOIN user ON user.id=settings.id where `variable`="' . $value . '" and `teacher`="' . $_SESSION["User"]["teacher"] . '" group by variable');
//                    $userid = null;
//                    
//                    if($user_ids->num_rows > 0){
//                        while ($row = mysqli_fetch_assoc($user_ids)) {
//                            $userid = $row['user_ids'];
//                        }
//                    }
//                    
//                    $check_license_key_exists = query('SELECT license_key FROM unassign_license_keys where `license_key`="' . $value . '" order by license_key desc') ;
//                    if($check_license_key_exists->num_rows > 0){
//                        mysqli_query($con, "UPDATE `unassign_license_keys` SET `user_id` ='".$userid."' WHERE `license_key`='" . $value . "'");
//                    }
//                }
//            }

                $result['status'] = TRUE;
                $result['msg'] = 'Student deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';

                echo json_encode($result);
                exit;
            /*} else {
                $result['status'] = FALSE;
                $result['msg'] = 'License key not valid OR not available.';

                echo json_encode($result);
                exit;
            }*/
        }

//        $result['status'] = mysqli_query($con, "UPDATE `user` SET `teacher` ='' WHERE `id`='" . $studentid . "' AND `role`='student'");
//        $result['msg'] = 'Student deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
//
//        echo json_encode($result);
        exit;
    }
}

/**
 * Share DATA 
  START-----
 */
// END-----

/**
 * Display App Typio-OL data
 * 
 * @param string $typio_start_date 
 * @param string $typio_end_date 
 * @return string Typio-OL table html
 */
function displayAppDataChart($student_id = 0, $typio_start_date = 0, $typio_end_date = 0) {

    global $con, $studentid;
    $table_data = array();

    $studentid = !empty($student_id) ? $student_id : $studentid;

    $log_typio_query = "SELECT COUNT(`app`) as total_app, `app` FROM `log` GROUP BY `app` AND id='" . $studentid . "'";

    if (( $typio_start_date != 0 ) && ( $typio_end_date != 0 )) {
        $log_typio_query .= " WHERE `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }

    $log_typio_data = mysqli_query($con, $log_typio_query);

    $log_typio_data_html = "<tbody><tr><th></th><td></td></tr>";

    $table_data['total_row'] = mysqli_num_rows($log_typio_data);
    while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
        $log_typio_data_html .= "<tr><th>" . str_replace('-OL', ' ', $log_typio_data_row['app']) . "</th><td>" . $log_typio_data_row['total_app'] . "</td></tr>";
    }

    $log_typio_data_html .= '</tbody>';

    $table_data['html'] = $log_typio_data_html;

    return $table_data;
}

function getArcadeHistory($student_id, $start_date = '', $end_date = '') {

    global $con;
    $arcade_history = array();

    //Set Table Name
    $table = "log";

    // Initial Query
    $query = "SELECT * FROM " . $table . " WHERE app = 'Arcade-OL' AND id='" . $student_id . "'";

    // Add start date and end date
    if (( $start_date != 0 ) && ( $end_date != 0 )) {
        $query .= " AND `date` BETWEEN '" . $start_date . "' AND '" . $end_date . "' ";
    }

    if (!empty($student_id)) {
        $query .= " AND `id` = '" . $student_id . "' ";
    }
    $arcade_history_data_rows = mysqli_query($con, $query);

    while ($arcade_history_data_row = mysqli_fetch_assoc($arcade_history_data_rows)) {

        $arcade_history[] = $data = array(
            'file' => $arcade_history_data_row['file'],
            'date' => $arcade_history_data_row['date']
        );
    }
    return $arcade_history;
}

//---------------------Start Pro Pack----------------------------------------------------------------------------
/**
 * Inserting Prp Pack Section data
 */
if (!empty($_POST['form_data']) && !empty($_POST['action']) && $_POST['action'] == 'pro-pack-add-new') {

    global $con;
    parse_str($_POST['form_data'], $form_data);

    if (!empty($form_data['user_id'])) {

        $text = '';
        $user_type = $form_data['user_type'];
        $user_id = $form_data['user_id'];
        $title = !empty($form_data['title']) ? $form_data['title'] : '';
        $data = !empty($form_data['data']) ? mysqli_real_escape_string($con, $form_data['data']) : '';
        $pro_pack_type = !empty($form_data['pro_pack_type']) ? $form_data['pro_pack_type'] : '';
        $teacher_area = !empty($form_data['teacher_area']) ? $form_data['teacher_area'] : '';

        if (empty($title) || trim($title) == '') {
            $result['status'] = 0;
            $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Title is rquired field. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4></h4>';
            $result['teacher_area'] = $teacher_area;
            echo json_encode($result);
            exit;
        }

        if (!empty($pro_pack_type) && $pro_pack_type == 'reader_doc') {
            $pro_pack_type = 0;
            $text = 'Reader Doc';
        } elseif (!empty($pro_pack_type) && $pro_pack_type == 'notepad_doc') {
            $pro_pack_type = 1;
            $text = 'Notepad Doc';
        } elseif (!empty($pro_pack_type) && $pro_pack_type == 'to_do_doc') {
            $pro_pack_type = 2;
            $text = 'To-Do';
            $form_data['data'] = '';
        }

        $data1 = "SELECT id,app,number,title ,table_id FROM `text` where id ='" . $user_id . "' AND app='PP-OL' AND number='" . $pro_pack_type . "' AND title='" . $form_data['title'] . "'";

        $res_data = query($data1);
        $re_data = fetch($res_data);
        $table_id = $re_data['table_id'];


        if (!empty($re_data['title'])) {

            $result = array();
            parse_str($_POST['form_data'], $form_data);
            //Check Fileds data exist
            //$data = mysqli_real_escape_string($con,$form_data['data']);

            $result['status'] = query('UPDATE `text` SET app="PP-OL", number="' . $pro_pack_type . '", title="' . $form_data['title'] . '", data="' . $data . '"  WHERE id="' . $user_id . '" AND title="' . $form_data['title'] . '" ');
            $result['msg'] = '<h4><i class="icon fa fa-check"></i> ' . $text . ' updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4></h4>';
            $result['type'] = 1;
        } else {

            global $con, $studentid;
            $result = array();
            parse_str($_POST['form_data'], $form_data);

            if (!empty($form_data['data'])) {

                $form_data['data'] = mysqli_real_escape_string($con, $form_data['data']);

                $result['status'] = query("INSERT INTO `text` (`id`, `app`, `number`, `title`, `data`) VALUES ('" . $user_id . "','PP-OL','" . $pro_pack_type . "','{$title}','{$data}')");

                $result['id'] = mysqli_insert_id($con);
                $table_id = $result['id'];
                $result['type'] = 2;

                $result['msg'] = '<h4><i class="icon fa fa-check"></i> ' . $text . ' Added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4></h4>';
                $result['teacher_area'] = $teacher_area;

                $result['html'] = '';
                $result['html'] .= '<tr class="todo_tr_' . $result['id'] . '">';
                if ($user_type == 1) {
                    $result['html'] .= '<td><input type="checkbox" name="share_pro_pack_ids[]" value="' . $table_id . '" class="share_pro_pack_ids"></td>';
                }

                $result['html'] .= '<td>' . $title . '</td>';
                $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-pro-pack-modal" data-id="' . $table_id . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
                $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-type="Reader Doc" data-target="#delete-modal-set" class="badge bg-red delete-pro-pack-modal" data-id="' . $table_id . '"><i class="fa fa-trash-o"></i></a></td>';
                $result['html'] .= '</tr>';

                $result['table_id'] = $table_id;
            }
        }
    } else {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4></h4>';
        $result['teacher_area'] = $teacher_area;
    }
    if ($result['status'] == false) {
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4></h4>';
    }
    echo json_encode($result);
    exit;
}
/**
 * Update Prp Pack Section data
 */
if (!empty($_POST['table_id']) && !empty($_POST['type']) && $_POST['type'] == 'Update-Pro-Pack-Content') {

    $sql = query('SELECT * FROM text WHERE table_id="' . $_POST['table_id'] . '"');
    $data = fetch($sql);
    $html = '';
    if (!empty($data)) {
        $html .= '<div class="lessions-edit">';
        $html .= '<form id="pro-pack-edit-form" name="pro-pack-edit">';
        $html .= '<input type="text" id="reader-docs-title-edit" name="title" class="form-control title reader_docs_title_edit_' . $_POST['table_id'] . '" placeholder="Reader docs title here..." required="" value="' . $data['title'] . '" onkeyup="check_duplicate_reader_docs_edit(' . $_POST['table_id'] . ')"><span class="reader_docs_title_error' . $_POST['table_id'] . '" style="color:red;"></span>';
        $html .= '<input type="hidden" name="table_id" value="' . $_POST['table_id'] . '">';
        $html .= '<input type="hidden" id="type_number" value="' . $data['number'] . '">';
        if (!empty($data['data'])) {
            if ($data['number'] != 2) {
                $html .= '<p><textarea style="margin-top: 20px;" id="textareaID2" placeholder="Reader docs text here..."  class="form-control" cols="55" rows="10" name="data">' . $data['data'] . '</textarea></p>';
            }
        }
        $html .= '</form>';
        $html .= '</div>';
    }
    echo json_encode(array('status' => 200, 'html' => $html));
    exit;
}
/**
 * Update Pro pack data from modal popup
 */
if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Update-Pro-Pack-form') {
    $result = array();
    $tbl_tr = $_POST['tbl_tr'];

    if ($tbl_tr == 'todo_tr_') {
        $msg_text = 'To-Do';
    } else if ($tbl_tr == 'notepad_tr_') {
        $msg_text = 'Note Pad Docs';
    } else if ($tbl_tr == 'pro_pack_tr_') {
        $msg_text = 'Reader docs';
    } else {
        $msg_text = '';
    }

    parse_str($_POST['form_data'], $form_data);
    //Check Fileds data exist
    if (empty($form_data['data'])) {
        $form_data['data'] = "1";
    }
    //if (!empty($form_data['data'])) {
    if (!empty($form_data['table_id'])) {
        $data = mysqli_real_escape_string($con, $form_data['data']);
        $result['status'] = query('UPDATE `text` SET title = "' . $form_data['title'] . '" , data = "' . $data . '" WHERE table_id="' . $form_data['table_id'] . '"');
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> ' . $msg_text . ' data updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4></h4>';
    }
    //}
    if (empty($result['status'])) {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4></h4>';
    }

    $result['html'] = '';
    $result['html'] .= '<tr class="' . $tbl_tr . $form_data['table_id'] . '">';
    if ($user_type == 1) {
        $result['html'] .= '<td><input type="checkbox" name="share_pro_pack_ids[]" value="' . $form_data['table_id'] . '" class="share_pro_pack_ids"></td>';
    }

    $result['html'] .= '<td>' . $form_data['title'] . '</td>';
    $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-pro-pack-modal" data-id="' . $form_data['table_id'] . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
    $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-type="Reader Doc" data-target="#delete-modal-set" class="badge bg-red delete-pro-pack-modal" data-id="' . $form_data['table_id'] . '"><i class="fa fa-trash-o"></i></a></td>';
    $result['html'] .= '</tr>';

    $result['table_id'] = $form_data['table_id'];
    // echo "<pre>";
    // print_r($result); 
    echo json_encode($result);
    exit;
}
/**
 * Delete Pro pack data from modal popup
 */
if (!empty($_POST['delete_id']) && !empty($_POST['type']) && $_POST['type'] == 'pro-pack-delete') {

    global $con;

    if (!empty($_POST['delete_id'])) {
        mysqli_query($con, 'DELETE FROM `text` WHERE table_id="' . $_POST['delete_id'] . '"');
        $result['status'] = 1;
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> ' . $_POST['delete_text'] . ' deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4></h4>';
    }

    if (empty($result['status'])) {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4></h4>';
    }
    echo json_encode($result);
    exit;
}

if (isset($_POST['share_ids']) && isset($_POST['share_user_list']) && !empty($_POST['type'])) {

    $result = array();
    $share_ids = $_POST['share_ids'];
    $user_list = $_POST['share_user_list'];
    $type = $_POST['type'];


    if (!empty($share_ids)) {

        foreach ($share_ids as $lession_id) {

            if (!empty($user_list)) {

                $text_query = query(" SELECT * FROM text WHERE table_id = '$lession_id' ");
                $text_row = mysqli_fetch_assoc($text_query);

                foreach ($user_list as $user_id) {

                    $result[] = query(" insert into text SET
                        id     = '$user_id',
                        app    = '" . $text_row['app'] . "',
                        number = '" . $text_row['number'] . "',
                        title  = '" . $text_row['title'] . "',
                        data   = '" . mysqli_real_escape_string($con, $text_row['data']) . "'
                      ");
                }
            }
        }
        if($type == 'hangman_arcade'){
            $type = 'Hangman lesson';
        }
        $result['status'] = 1;
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> Selected ' . $type . ' shared successfully.</h4></h4>';
    }

    if (empty($result)) {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. </h4></h4>';
    }

    echo json_encode($result);
    exit;
}

/**
 * Get the all param data based on param passed
 * @param type $student_id
 * @param type $pro_pack_type 0|1|2
 * @param type $extra_param
 */
function get_ProPack_data($student_id, $pro_pack_type = '', $extra_param = array()) {
    $query_var = $final_array = array();

    if (!empty($student_id)) {
        $query_var[] = " id ='" . $student_id . "'";
    }
    if (isset($pro_pack_type) && trim($pro_pack_type) != '') {
        $query_var[] = " number ='" . $pro_pack_type . "'";
    }
    $query_var[] = " app ='PP-OL'";

    if (!empty($query_var)) {
        $where = 'WHERE ' . implode(' AND ', $query_var);
    }

    $query = query("SELECT * FROM text  $where ORDER BY table_id DESC  ");

    if (!empty($query->num_rows)) {

        while ($row = mysqli_fetch_assoc($query)) {
            $final_array[] = $row;
        }
    }
    return $final_array;
}

//---------------------End Pro Pack----------------------------------------------------------------------------
if ( isset($_POST['action']) && $_POST['action'] == 'ajax_OverviewTimeSpent') {
    
    $student_id = $_POST['userid'];
    $start_date = $_POST['startdate']; 
    $end_date = $_POST['enddate'];
    $html = '';
    $weekly_log_data = getUserActivityLog($student_id, 1, $start_date, $end_date, '1');
    $weekly_log_hour = getUserActivityLog($student_id, 1, $start_date, $end_date);
    $today_log_hour = getUserActivityLog($student_id, 1);

    $todaylog = !empty($today_log_hour) ? $today_log_hour : '0 min'; 
    $weeklog = !empty($weekly_log_hour) ? $weekly_log_hour : '0 min'; 
    //if ($weekly_log_hour) {
        $html .= '<div class="today-main-wrap">
        <div class="column-wrap today-column-wrap">
        <p>Today</p>
        <h3>'.$todaylog.'</h3>
        </div><div class="column-wrap week-column-wrap">
            <p>This week</p>
            <h3>'.$weeklog.'</h3>
        </div>
        </div>';
    //}
    echo json_encode($html);
    exit;
}

if ( isset($_POST['action']) && $_POST['action'] == 'ajax_OverviewChartTable') {

    $userid = $_POST['userid'];
    $startdate = $_POST['startdate']; 
    $enddate = $_POST['enddate'];
    if (empty($userid) || empty($startdate) || empty($enddate)) {
        return;
    }
    $startdate = date('Y-m-d', strtotime($startdate));
    $enddate = date('Y-m-d', strtotime($enddate));
    //$startdate = '2017-01-01';
    global $con;
    $total = "SELECT * FROM log WHERE log.id ='" . $userid . "'  AND log.date >= '" . $startdate . "' AND log.date <= '" . $enddate . "' ORDER BY date DESC";
    $total_row = mysqli_query($con, $total);
    $total_rows = mysqli_num_rows($total_row);

    $data = "SELECT count(*) as total,file FROM log WHERE log.id ='" . $userid . "' AND log.date >= '" . $startdate . "' AND log.date <= '" . $enddate . "' group by file ORDER BY date DESC";

    $data_rows = mysqli_query($con, $data);
    $html = "";
    $avrg = 0;
    //$html .="<tr><td>1<td><td>232</td></tr>";

    while ($row = mysqli_fetch_assoc($data_rows)) {
        $avrg = round($row['total'] / $total_rows, 2) * 100;
        $html .= "<tr><td> " . $row['file'] . " </td><td> " . $avrg . " </td></tr>";
    }

    echo json_encode($html);
    exit;
}
/** Overview tabs - Typio Start */
if ( isset($_POST['action']) && $_POST['action'] == 'ajax_TypioDataTableAverageBarChart') {

    global $con, $studentid;
    $userid = $_POST['student_id'];
    $startdate = $_POST['typio_start_date'];
    $enddate = $_POST['typio_end_date'];
    //$typio_history_start_date = $_POST['typio_history_start_date'];
    //$typio_history_end_date = $_POST['typio_history_end_date'];
    

    $displayAppTypioDataTableAverageBarChart = displayAppTypioDataTableAverageBarChart($userid, $startdate, $enddate);
    $displayAppTypioDataTableAverageAreaChart = displayAppTypioDataTableAverageAreaChart($startdate, $enddate);

    $result = array(
        'displayAppTypioDataTableAverageBarChart' => $displayAppTypioDataTableAverageBarChart,
        'displayAppTypioDataTableAverageAreaChart' => $displayAppTypioDataTableAverageAreaChart,
    );
    echo json_encode($result);
    exit;
}


if ( isset($_POST['action']) && $_POST['action'] == 'ajax_TypioDataTableAverageBarChart_kp') {

    global $con, $studentid;
    $userid = $_POST['student_id'];
    $startdate = $_POST['typio_start_date'];
    $enddate = $_POST['typio_end_date'];
    //$typio_history_start_date = $_POST['typio_history_start_date'];
    //$typio_history_end_date = $_POST['typio_history_end_date'];
   
    $displayAppTypioDataTableAverageBarChart = displayAppTypioDataTableAverageBarChart_kp($userid, $startdate, $enddate);
    $displayAppTypioDataTableAverageAreaChart = displayAppTypioDataTableAverageAreaChart_kp($startdate, $enddate);

    $result = array(
        'displayAppTypioDataTableAverageBarChart' => $displayAppTypioDataTableAverageBarChart,
        'displayAppTypioDataTableAverageAreaChart' => $displayAppTypioDataTableAverageAreaChart,
    );
    echo json_encode($result);
    exit;
}
if ( isset($_POST['action']) && $_POST['action'] == 'ajax_BrailoDataTableAverageBarChart') {

    global $con, $studentid;
    $userid = $_POST['student_id'];
    $startdate = $_POST['typio_start_date'];
    $enddate = $_POST['typio_end_date'];
    //$typio_history_start_date = $_POST['typio_history_start_date'];
    //$typio_history_end_date = $_POST['typio_history_end_date'];
    

    $displayAppTypioDataTableAverageBarChart = displayAppTypioDataTableAverageBarChart($userid, $startdate, $enddate);
    $displayAppTypioDataTableAverageAreaChart = displayAppTypioDataTableAverageAreaChart($startdate, $enddate);

    $result = array(
        'displayAppTypioDataTableAverageBarChart' => $displayAppTypioDataTableAverageBarChart,
        'displayAppTypioDataTableAverageAreaChart' => $displayAppTypioDataTableAverageAreaChart,
    );
    echo json_encode($result);
    exit;
}

if ( isset($_POST['action']) && $_POST['action'] == 'ajax_HistroryTypioDataTableAverageBarChart') {

    global $con, $studentid;
    $userid = $_POST['student_id'];
    $startdate = $_POST['typio_start_date'];
    $enddate = $_POST['typio_end_date'];
    

    $displayAppTypioDataTableAverageBarChart = displayAppTypioDataTableAverageBarChart($userid, $startdate, $enddate);
    $displayAppTypioDataTableAverageAreaChart = displayAppTypioDataTableAverageAreaChart($startdate, $enddate);

    $result = array(
        'displayAppTypioDataTableAverageBarHistoryChart' => $displayAppTypioDataTableAverageBarChart,
        'displayAppTypioDataTableAverageAreaHistoryChart' => $displayAppTypioDataTableAverageAreaChart,
    );
    echo json_encode($result);
    exit;
}
if ( isset($_POST['action']) && $_POST['action'] == 'ajax_HistroryBrallioDataTableAverageBarChart') {

    global $con, $studentid;
    $userid = $_POST['student_id'];
    $startdate = $_POST['typio_start_date'];
    $enddate = $_POST['typio_end_date'];
    

    $displayAppTypioDataTableAverageBarChart = displayAppBrailDataTableAverageBarChart($userid, $startdate, $enddate);
    $displayAppTypioDataTableAverageAreaChart = displayAppBrailDataTableAverageAreaChart($startdate, $enddate);

    $result = array(
        'displayAppTypioDataTableAverageBarHistoryChart' => $displayAppTypioDataTableAverageBarChart,
        'displayAppTypioDataTableAverageAreaHistoryChart' => $displayAppTypioDataTableAverageAreaChart,
    );
    echo json_encode($result);
    exit;
}
if ( isset($_POST['action']) && $_POST['action'] == 'ajax_HistroryBrallioDataTableAverageBarChart_kp') {

    global $con, $studentid;
    $userid = $_POST['student_id'];
    $startdate = $_POST['typio_start_date'];
    $enddate = $_POST['typio_end_date'];
    

    $displayAppTypioDataTableAverageBarChart 	= displayAppBrailDataTableAverageBarChartkp($userid, $startdate, $enddate);
    $displayAppTypioDataTableAverageAreaChart 	= displayAppBrailDataTableAverageAreaChartkp($startdate, $enddate);

    $result = array(
        'displayAppTypioDataTableAverageBarHistoryChart' => $displayAppTypioDataTableAverageBarChart,
        'displayAppTypioDataTableAverageAreaHistoryChart' => $displayAppTypioDataTableAverageAreaChart,
    );
    echo json_encode($result);
    exit;
}
if ( isset($_POST['action']) && $_POST['action'] == 'ajax_HistroryTypioDataTableAverageBarChart_kp') {

    global $con, $studentid;
    $userid = $_POST['student_id'];
    $startdate = $_POST['typio_start_date'];
    $enddate = $_POST['typio_end_date'];
    

    $displayAppTypioDataTableAverageBarChart = displayAppTypioDataTableAverageBarChartkp($userid, $startdate, $enddate);
    $displayAppTypioDataTableAverageAreaChart = displayAppTypioDataTableAverageAreaChartkp($startdate, $enddate);

    $result = array(
        'displayAppTypioDataTableAverageBarHistoryChart' => $displayAppTypioDataTableAverageBarChart,
        'displayAppTypioDataTableAverageAreaHistoryChart' => $displayAppTypioDataTableAverageAreaChart,
    );
    echo json_encode($result);
    exit;
}
/** Overview tabs - Typio Ends Start */

if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_list') {

    $result = array();
    $is_expired = check_expire_or_not();
    $args = array(
        'teacher' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
        'role' => 'student',
    );
    $search_columns = 'username,firstname';
    $order_by = 'l.id desc';

    $where = array();
    $joins = array('log as l','l.id = u.id');
    $groupby = '';
    $records = json_datatable('user', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    if (!empty($records)) {
        $data = array();
        
        foreach ($records['data'] as $user) 
		{
            //if($_SERVER['REMOTE_ADDR'] == '122.169.107.99'){
            //if($user['id'] =! '9365')
            // now commented -  $activityArr = getUserLog($user['id'],1);
           // }
            $activity = '';
            if(!empty($user['login']))
            
            $activity = getTimtstampDiff($user['login']);
            // now commented - $activityData = isset($activityArr) && !empty($activityArr) ? $activityArr[0]['data'] : '';
            $firstname =  ucfirst(base64_decode($user['firstname']));
            //$username = base64_encode($user['username'] . '-' . $user['password']);
            //$username = generateRandomString();
            $deleteuser = ucfirst(base64_decode($user['firstname'])) .' ('.ucfirst(base64_decode($user['username'])).")";
			$action = '';
            //$activityData = '';

            if($user['wait_page'] == 'Yes') {
                $wait = 'Includes Wait Page.';
                } else {  
                $wait = '';
                }
                $val = '';
                $url = $user['Login_url'];
                $value = ADMIN_URL . 'login?UID=' . $user['Login_url'];
                if($user['link_val'] == 'Never') {
                $expires = 'Never. '.$wait;
                } else {
                $expires = date("m/d/Y h:i a",strtotime($user['link_expiry'])).'.'.$wait;
                }
              
                $val = "<div class='col-md-12'><div class='col-md-2'><input class='form-control' style='margin-top:0px!important;    border: 2px solid #e5e5e5;' id = 'numurl_".$user['id']. "' type='number' name='exp_cal' id='exp_cal' value='".$user['link_exp_num']."'></div><div class='col-md-3'><select class='form-control' id='intvurl_".$user['id']. "'>";
                
                if($user['link_val']  == 'Hours') {
                $val .= "<option value='Hours' selected > Hours</option>";
                
                } else {
                $val .= "<option value='Hours' > Hours</option>";
                
                }
                if($user['link_val']   == 'Days') {
                $val .= "<option value='Days' selected >Days</option>";
                
                } else {
                $val .= "<option value='Days'>Days</option>";
                
                }
                if($user['link_val']   == 'Months') {
                $val .= "<option value='Months' selected >Months</option>";
                
                } else {
                $val .= "<option value='Months'>Months</option>";
                
                }
                if($user['link_val']   == 'Never') {
                $val .= "<option value='Never' selected >Never</option>";
                
                } else {
                $val .= "<option value='Never'>Never</option>";
                
                }
                $val .= " </select></div><div class='col-md-3'><select class='form-control' id='wt_url_".$user['id'] ."'>";
                if( $user['wait_page'] == 'Yes') {
                $val .= "<option value='Yes' selected >Yes</option>";
                
                } else {
                $val .= "<option value='Yes' >Yes</option>";
                }
                if( $user['wait_page'] == 'No') {
                $val .= "<option value ='No' selected >No</option>";
                
                } else {
                $val .= "<option value ='No'>No</option>";
                }
                $val .=  " </select></div><div class='col-md-4'><button type='button' class='btn dashboard-settings-btn create_clk_link' id='btn_url_".$user['id']."' >Create Link</button></div> </div>";
                if($url != '') {

                $val .= "<div class='col-md-12'><br/><h4 style='font-weight:bold'>Current Link</h4><p>Expires: ".$expires ."</p></div> </div> <br/><br/><input type='text' class='form-control urlcls' value='https://www.accessibyte.com/online/login?UID=$url'>";
                                
                }  
            if(!$is_expired) {
                $action = '<div class="studentAction">
                <span> <a href="' . ADMIN_URL . 'student/student-overview.php?student=' . $user['id'] . '" aria-label="Overview for ' . ucfirst(base64_decode($user['firstname'])) . ' " class="accessibyte-link">Overview</a>
                </span><span class="studentActionLogin"><a href="javascript:void(0)" data-name="' . ucfirst(base64_decode($user['firstname'])) . '(' . base64_decode($user['username']) . ')" data-URL="url_' . $user['id'] . '" class="accessibyte-link one-click-login" aria-label="1-Click login for ' . ucfirst(base64_decode($user['firstname'])) . ' ">1-Click login</a><div class="click_one_hide"><input type="text" style="opacity:0;" id="exp_'.$user['id'].'" class="exp_link_Copy" value="'.$expires .'"><input type="text" style="opacity:0;" id="numval_'.$user['id'].'" class="num_link_Copy" value="'.$val.'"><input type="text" style="opacity: 0;" id="url_' . $user['id'] . '" class="url_copy" aria-label="You can paste this link…" value="' . ADMIN_URL . 'login?UID=' . $user['Login_url'] . '"></div></span></div>';
            }
            /* now commented - $data[] = array(
                '<div class="icheck-primary d-inline"><input type="checkbox" aria-label="Checkbox ' . ucfirst(base64_decode($user['firstname'])) . ' " data-name="' . $deleteuser . ' " class="chkbox" name="ids[]" value="' . $user['id'] . '" id="all_chkbox'.$user['id'].'"><label for="all_chkbox'.$user['id'].'">'.$firstname.'</label></div>',
                '<span class="">' . base64_decode($user['username']) . '<span>',
                '<span class="">'.$activity.'<span>',
                '<span class="">'.$activityData.'<span>',
                $action
            ); */

            $data[] = array(
                '<div class="icheck-primary d-inline"><input type="checkbox" aria-label="Checkbox ' . ucfirst(base64_decode($user['firstname'])) . ' " data-name="' . $deleteuser . ' " class="chkbox" name="ids[]" value="' . $user['id'] . '" id="all_chkbox'.$user['id'].'"><label for="all_chkbox'.$user['id'].'">'.$firstname.'</label></div>',
                '<span class="">' . base64_decode($user['username']) . '<span>',
                '<span class="">'.$activity.'<span>',
                $action
            );
        }
        
        /*$records['data'] = $data;        
        $records['status'] = 1;*/
        $draw = $_POST['draw'];
        $start = $_POST['start'];
        $length = $_POST['length'];


        $alldata = array_slice($data, $start, $length);

        $records['data'] = $data;        
        $records['status'] = 1;
        $records['draw'] = $draw;
        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $alldata
        ]);

    }

    if (empty($records)) {
        $records['status'] = 0;
        echo json_encode($records);
    }

    //echo json_encode($records);
    exit;
}

if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_download_list') {

    $result = array();
    $is_expired = check_expire_or_not();
    $args = array(
        'user_id' => !empty($_SESSION['User']) ? $_SESSION['User']['id'] : '',
        
    );
    //$search_columns = 'report_type';
    $order_by = 'id desc';

    $where = array();
    $joins = array();
	//reporttype
    $groupby = '';
	/*
	$table, $columns, $where = '', $order_by = '', $search_columns = '',$groupby = '',$join = array(),$oredr_by_pass_argument ="0"
	*/
    $records = json_datatable_download('report_downloads', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array(),"0");
	
    if (!empty($records)) {
        $data = array();
        
        foreach ($records['data'] as $user) 
		{
			$license = $_SESSION['User']['license'];
			
			$final_students = [];
			
				$time = '';
				$action = '';
				$student = 'My Students';
				$iss  = '';
				if ($user['report_type'] == 'student_export'){
				if($user['is_single'] !=0 || $user['is_single']!= '0'){
					$student = 'My Students';	
					$query_stuids1 = "SELECT * FROM user WHERE `role` ='student' AND `teacher` ='".$user['is_single']."' ORDER BY firstname ASC";
					$stuied = mysqli_query($con,$query_stuids1);
					while ($row = mysqli_fetch_assoc($stuied)) {
					$final_students[] = $row['id'];
					$iss  = json_encode($final_students);
					}
				}else{
					$student = 'All Students';
					$query_stuids1 = "SELECT * FROM user WHERE `role` ='student' AND`license` ='".$license."'  AND `is_admin` != '1' ORDER BY firstname ASC";
					$stuied = mysqli_query($con,$query_stuids1);
					while ($row = mysqli_fetch_assoc($stuied)) {
					$final_students[] = $row['id'];
					
					}
					$iss  = json_encode($final_students);
				}
				}
			
					
			 if($user['file_status'] == 'Processing'){
				$action = '
				<div class="student_process_div_cancel">
                <span>
				<a href="javascript:void(0)" aria-label="Cancel Request" class="accessibyte-link cancel_req" data-ID="'.$user['id'].'" data-name="cancel_file_manual" >Cancel Request</a>';
				$time = '7 days';
				}
				else
				{
				$user['download_link'] = str_replace('online/uploads/reports/', '', $user['download_link']);	
				$action = '<div class="student_download_div">
                <span>
				<a href="' .ADMIN_URL.'student/student-download.php?file='.$user['download_link'].'" aria-label="Download File" class="accessibyte-link">Download</a>
				
                </span>
				<span class="studentActiondownload">
				<a href="javascript:void(0)" data-name="delete_file_manual" data-ID="'.$user['id'].'" class="accessibyte-link confirm-delete-file" aria-label="Click Delete file" >Delete</a>';
				$start = new DateTime($user['created_at']);
				//$start =  date('Y-m-d');
				// End date = start + 30 days
				$end = clone $start;
				$end->modify("+7 days");

				// Current date/time
				$now = new DateTime(date('Y-m-d'));

				// If still within countdown
				if ($now < $end) {
					$diff = $now->diff($end);
					if($diff->days == 0 || $diff->days == "0"){
						$time =  "Last day";
					}
					else{
					$time = $diff->days." days";
					}
				} else {
					$time =  "Last day";
				}
				}
				//$start = new DateTime($user['created_at']);
				
			
			
			
			
			$rptype  = ucwords(str_replace("_", " ", str_replace("student_", "", $user['report_type'])));
			if($user['report_hash'] == 'Multiple' || $user['report_hash'] == 'multiple'){
					$usernamem = "Multiple Students";
					$lbl = " <i class='fa fa-info-circle' name='student_icon_report' data-studentsid='".$iss."' id='report_student_icon_id' class='report_student_icon' aria-label='student icon'></i>";
				}
				else if( is_array(json_decode($user['report_hash'], true))){
				//else if($user['report_hash']){
					$usernamem = "Multiple Students";
					$lbl = " <i class='fa fa-info-circle' name='student_icon_report' data-studentsid='".$user['report_hash']."' id='report_student_icon_id' class='report_student_icon' aria-label='student icon'>";
				}
				else{
					$usernamem = $user['report_hash'];
					$lbl = '';
				}
				
            $data[] = array(
                '<input type="checkbox" name="report_all_delete[]" id="all_chkbox'.$user['id'].'" class="report_all_delete_cls" data-id="'.$user['id'].'" >
				<label for="all_chkbox'.$user['id'].'">'.$usernamem.'</label>'.$lbl.'</div>',
                '<span class="">' . $rptype = str_replace(
							["Overviewol", "History"], 
							["Activity", "Typio"], 
							$rptype) . '<span>',
				'<span class="">' . $user['file_status'] . '<span>',
                '<span class="">'.$time.'<span>',
                $action
            );
        }
         
        /*$records['data'] = $data;        
        $records['status'] = 1;*/
        $draw = $_POST['draw'];
        $start = $_POST['start'];
        $length = $_POST['length'];


        $alldata = array_slice($data, $start, $length);

        $records['data'] = $data;        
        $records['status'] = 1;
        $records['draw'] = $draw;
        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $alldata
        ]);

    }

    if (empty($records)) {
        $records['status'] = 0;
        echo json_encode($records);
    }

    //echo json_encode($records);
    exit;
}

function json_datatable_download ($table, $columns, $where = '', $order_by = '', $search_columns = '',$groupby = '',$join = array(),$oredr_by_pass_argument ="0") {
    //pr($_POST);die;
    
    $datatable_search_value = trim($_POST['search']['value']);
    $datatable_columns = $_POST['columns'];
    $datatable_limit = $_POST['length'];
    $datatable_offset = $_POST['start'];
    $datatable_order_name = $_POST['columns'][$_POST['order'][0]['column']]['name'];
    $datatable_order_by = $_POST['order'][0]['dir'];
    $datatable_draw = $_POST['draw'];
    
    if (!empty($where)) {
        foreach ($where as $key => $val) {
            if($key == "app_condition"){
               $query_var[] = $val;  
            }
            else if (strpos($key, '=') !== false) {
                $newkeyName = str_replace(' !=', '', $key);
                $query_var[] = "`".$newkeyName."` != '" . $val . "'";
            } 
            else if(strpos($key, 'BETWEEN') !== false) {
                //$val =str_replace("_"," ",$val);
                $val_arr=explode("_",$val);
                $query_var[] = "".$key." ' " . $val_arr[0] . "' AND '".$val_arr[1]."'";
            }
            else {
                $query_var[] = "`".$key."` = '" . $val . "'";
            }
        }
    }
    $where = '';
    if (!empty($query_var)) {
        $where .= 'WHERE ' . implode(' AND ', $query_var);
    }

    $SQL = '';
    if (!empty($datatable_search_value)) {
        $qry = array();
        if ($search_columns != '') {
            if (!is_array($search_columns)) {
                $search_columns = explode(',', $search_columns);
            }
            foreach ($search_columns as $s_cl) {
                $qry[] = " `" . $s_cl . "` like '%" . base64_encode($datatable_search_value) . "%' ";
            }
        } else {
            foreach ($datatable_columns as $cl) {
                if ($cl['searchable'] == 'true')
                    $qry[] = "`" . $cl['name'] . "` like '%" . base64_encode($datatable_search_value) . "%' ";
            }
        }

        $SQL .= "( ";
        $SQL .= implode("OR", $qry);
        $SQL .= " )";
    }
    if ($SQL != '') {
        $where .= " AND ".$SQL;
    }
    
    $order_by_array = array();
//        if ($datatable_draw == 1) {
    if ($order_by) {
        
        if ($_POST['order'][0]['column'] != 0) {
            $order_by_array[] = $datatable_order_name . ' ' . $datatable_order_by;
        } else if($_POST['order'][0]['column'] == 0){
            $order_by_array[] = $datatable_order_name . ' ' . $datatable_order_by;
        } else {
            if (is_array($order_by)) {
                foreach ($order_by as $k => $v) {
                    $order_by_array[] = $k . ' ' . $v;
                }
            } else {
                $order_by_array[] = $order_by;
            }
        }
    } else {
        if ($_POST['order'][0]['column'] != 0) {
            $order_by_array[] = $datatable_order_name . ' ' . $datatable_order_by;
        } else {
            $order_by_array[] = $order_by;
        }
    }

    if (!empty($order_by_array)) {
        $order_by = implode(',', $order_by_array);
    }
    $joinQuery = '';
    if(!empty($join)){
        $joinTable = $join[0];
        $joinCondition = $join[1];
        $joinQuery = " left join $joinTable on $joinCondition";
    }

    

    //$query = query("SELECT $columns FROM $table $joinQuery $where $groupby ORDER BY $order_by limit $datatable_offset,$datatable_limit");   
    $query = query("SELECT $columns FROM $table $joinQuery $where $groupby ORDER BY created_at DESC");
    
    $total_query = query("SELECT COUNT(*) as total FROM $table $where");

    $total_data = fetch($total_query);
    $total = 0;
    if (!empty($total_data)) {
        $total =  $total_data['total'];
    }
    $data = array();
    if (!empty($query->num_rows)) {

        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
    }    
    //$data = custom_sort($data,$datatable_order_name,$datatable_order_by);
	//print_r(array("recordsTotal" => $total, "recordsFiltered" => $total, 'data' => "SELECT $columns FROM $table $joinQuery $where $groupby ORDER BY $order_by")); die();
    return array("recordsTotal" => $total, "recordsFiltered" => $total, 'data' => $data);
}


if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_delete_report_data') {
		
		global $con;
		
		$ids 		= $_POST['report_id'];
		$ids_list 	= implode(",", $ids);
	
		$sql_check 	= "SELECT id FROM export_queue WHERE dwn_lnk_id IN ($ids_list)";
		$result 	= mysqli_query($con, $sql_check);

		$existing_ids = [];

		while ($row = mysqli_fetch_assoc($result)) {
			$existing_ids[] = $row['id'];
		}

		if (!empty($existing_ids)) {
			$delete_ids = implode(",", $existing_ids);
			$sql_delete_queue = "DELETE FROM export_queue WHERE id IN ($delete_ids)";
			mysqli_query($con, $sql_delete_queue);
		}

		
		query("DELETE FROM report_downloads WHERE id IN ($ids_list)");
		$msg['status'] = true;
        $msg['msg'] = 'Reports deleted successfully.';
		echo json_encode($msg);
		exit;
		
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_list_old') {

    $result = array();
    $is_expired = check_expire_or_not();
    $args = array(
        'teacher' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
        'role' => 'student',
    );
    $search_columns = 'username,firstname';
    $order_by = 'l.id desc';

    $where = array();
    $joins = array('log as l','l.id = u.id');
    $groupby = '';
    $records = json_datatable('user', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    if (!empty($records)) {
        $data = array();
        
        foreach ($records['data'] as $user) 
		{
            //if($_SERVER['REMOTE_ADDR'] == '122.169.107.99'){
                //if($user['id'] =! '9365')
                $activityArr = getUserLog($user['id'],1);
           // }
            $activity = '';
            if(!empty($user['login']))
            $activity = getTimtstampDiff($user['login']);
            $activityData = isset($activityArr) && !empty($activityArr) ? $activityArr[0]['data'] : '';
            $firstname =  ucfirst(base64_decode($user['firstname']));
            $username = base64_encode($user['username'] . '-' . $user['password']);
            $deleteuser = ucfirst(base64_decode($user['firstname'])) .' ('.ucfirst(base64_decode($user['username'])).")";
			$action = '';
            //$activityData = '';
            if(!$is_expired){
                $action = '<div class="studentAction">
                <span> <a href="' . ADMIN_URL . 'student/student-overview.php?student=' . $user['id'] . '" aria-label="Overview for ' . ucfirst(base64_decode($user['firstname'])) . ' " class="accessibyte-link">Overview</a>
                </span><span class="studentActionLogin"><a href="javascript:void(0)" data-name="' . ucfirst(base64_decode($user['firstname'])) . '(' . base64_decode($user['username']) . ')" data-URL="url_' . $user['id'] . '" class="accessibyte-link one-click-login" aria-label="1-Click login for ' . ucfirst(base64_decode($user['firstname'])) . ' ">1-Click login</a><div class="click_one_hide"><input type="text" style="opacity: 0;" id="url_' . $user['id'] . '" class="url_copy" aria-label="You can paste this link…" value="' . ADMIN_URL . 'login?UID=' . $username . '"></div></span></div>';
            }
            $data[] = array(
                '<div class="icheck-primary d-inline"><input type="checkbox" aria-label="Checkbox ' . ucfirst(base64_decode($user['firstname'])) . ' " data-name="' . $deleteuser . ' " class="chkbox" name="ids[]" value="' . $user['id'] . '" id="all_chkbox'.$user['id'].'"><label for="all_chkbox'.$user['id'].'">'.$firstname.'</label></div>',
                '<span class="">' . base64_decode($user['username']) . '<span>',
                '<span class="">'.$activity.'<span>',
                '<span class="">'.$activityData.'<span>',
                $action
            );
        }
        
        $records['data'] = $data;        
        $records['status'] = 1;

    }

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}



function json_datatable($table, $columns, $where = '', $order_by = '', $search_columns = '',$groupby = '',$join = array(),$oredr_by_pass_argument ="0") {
    //pr($_POST);die;
    
    $datatable_search_value = trim($_POST['search']['value']);
	$datatable_search_value_arr =[
	"search1" => 	trim(ucfirst($_POST['search']['value'])), 
	"search2" => 	trim(ucwords($_POST['search']['value'])), 
	"search3" =>   	trim(strtoupper($_POST['search']['value'])),
	"search4" =>  	trim($_POST['search']['value'])
	
	];
	/*echo  $datatable_search_value;
	die();*/
    $datatable_columns 		= $_POST['columns'];
    $datatable_limit 		= $_POST['length'];
    $datatable_offset 		= $_POST['start'];
    $datatable_order_name 	= $_POST['columns'][$_POST['order'][0]['column']]['name'];
    $datatable_order_by 	= $_POST['order'][0]['dir'];
    $datatable_draw 		= $_POST['draw'];
    
    if (!empty($where)) {
        foreach ($where as $key => $val) {
            if($key == "app_condition"){
               $query_var[] = $val;  
            }
            else if (strpos($key, '=') !== false) {
                $newkeyName = str_replace(' !=', '', $key);
                $query_var[] = "`".$newkeyName."` != '" . $val . "'";
            } 
            else if(strpos($key, 'BETWEEN') !== false) {
                //$val =str_replace("_"," ",$val);
                $val_arr=explode("_",$val);
                $query_var[] = "".$key." ' " . $val_arr[0] . "' AND '".$val_arr[1]."'";
            }
            else {
                $query_var[] = "`".$key."` = '" . $val . "'";
            }
        }
    }
    $where = '';
    if (!empty($query_var)) {
        $where .= 'WHERE ' . implode(' AND ', $query_var);
    }

    $SQL = '';
    if (!empty($datatable_search_value)) {
        $qry = array();
        if ($search_columns != '') {
            if (!is_array($search_columns)) {
                $search_columns = explode(',', $search_columns);
            }
            /*foreach ($search_columns as $s_cl) {
                $qry[] = " `" . $s_cl . "` like '%" . base64_encode($datatable_search_value) . "%' ";
				
				
				
            }*/
			 foreach ($datatable_search_value_arr as $search_key => $search_value) {
			foreach ($search_columns as $s_cl) {
                // Search with all variations
               
                    if (!empty($search_value)) {
                        $qry[] = " `" . $s_cl . "` like '%" . base64_encode($search_value) . "%' ";
                    }
                }
            }
        } else {
			foreach ($datatable_search_value_arr as $search_key => $search_value1) {
            foreach ($datatable_columns as $cl) {
                if ($cl['searchable'] == 'true')
                   // $qry[] = "`" . $cl['name'] . "` like '%" . base64_encode($datatable_search_value) . "%' ";
				 
                    if (!empty($search_value1)) {
						$qry[] = "`" . $cl['name'] . "` like '%" . base64_encode($search_value1) . "%' ";
                        //$qry[] = " `" . $s_cl . "` like '%" . base64_encode($search_value) . "%' ";
                    }
                }
            }
        }

        $SQL .= "( ";
        $SQL .= implode("OR", $qry);
        $SQL .= " )";
    }
	
    if ($SQL != '') {
        $where .= " AND ".$SQL;
    }
   
    $order_by_array = array();
//        if ($datatable_draw == 1) {
    if ($order_by) {
        
        if ($_POST['order'][0]['column'] != 0) {
            $order_by_array[] = $datatable_order_name . ' ' . $datatable_order_by;
        } else if($_POST['order'][0]['column'] == 0){
            $order_by_array[] = $datatable_order_name . ' ' . $datatable_order_by;
        } else {
            if (is_array($order_by)) {
                foreach ($order_by as $k => $v) {
                    $order_by_array[] = $k . ' ' . $v;
                }
            } else {
                $order_by_array[] = $order_by;
            }
        }
    } else {
        if ($_POST['order'][0]['column'] != 0) {
            $order_by_array[] = $datatable_order_name . ' ' . $datatable_order_by;
        } else {
            $order_by_array[] = $order_by;
        }
    }

    if (!empty($order_by_array)) {
        $order_by = implode(',', $order_by_array);
    }
    $joinQuery = '';
    if(!empty($join)){
        $joinTable = $join[0];
        $joinCondition = $join[1];
        $joinQuery = " left join $joinTable on $joinCondition";
    }

    $order_by =str_replace('wpm','data',$order_by);
    $order_by =str_replace('acc','data',$order_by);
    $order_by =str_replace('err','data',$order_by);

    //$query = query("SELECT $columns FROM $table $joinQuery $where $groupby ORDER BY $order_by limit $datatable_offset,$datatable_limit");   
	 //print_r("SELECT $columns FROM $table $joinQuery $where $groupby ORDER BY $order_by");
	//print_r($SQL);
	//die();
    $query = query("SELECT $columns FROM $table $joinQuery $where $groupby ORDER BY $order_by");
    
    $total_query = query("SELECT COUNT(*) as total FROM $table $where");

    $total_data = fetch($total_query);
    $total = 0;
    if (!empty($total_data)) {
        $total =  $total_data['total'];
    }
    $data = array();
    if (!empty($query->num_rows)) {

        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
    }    
    //$data = custom_sort($data,$datatable_order_name,$datatable_order_by);
    return array("recordsTotal" => $total, "recordsFiltered" => $total, 'data' => $data);
}
if (!empty($_POST['action']) && $_POST['action'] == "delete_multi_student_account" && !empty($_POST['student_delete_id'])) {

    $studentidArr = $_POST['student_delete_id'];
    if (!empty($studentidArr)) {
        foreach($studentidArr as $studentid){
            $query = 'SELECT id,license FROM user where `id`=' . $studentid;
            $query_result = mysqli_query($con, $query);
            $license_key_array = array();
            while ($row = mysqli_fetch_assoc($query_result)) {
                $ItemType = '';
                if ($row['license'] != '') {
                    $val = explode("-", $row['license']);

                    if ($val[0] == "PRO")
                        $ItemType = "ProPack";
                    else if ($val[0] == "BDL")
                        $ItemType = "All Access";
                    elseif ($val[0] == "TYO")
                        $ItemType = "Typio";
                    else if ($val[0] == "TCH")
                        $ItemType = "Teacher";
                    else if ($val[0] == "QCO")
                        $ItemType = "Quick Cards";
                    else if ($val[0] == "AA")
                        $ItemType = "Accessibyte Arcade";
                    else if ($val[0] == "TCHP")
                        $ItemType = "Teacher All Access";    
                }
                $check_license_key_exists = query('SELECT license_key FROM unassign_license_keys where `license_key`="' . trim($row['license']) . '" order by license_key desc');
                if ($check_license_key_exists->num_rows <= 0 || $check_license_key_exists->num_rows == '') {
                    query("INSERT INTO `unassign_license_keys` (`license_type`, `license_key`, `created_by`) VALUES ('" . $ItemType . "','" . trim($row['license']) . "','" . $_SESSION['User']['id'] . "')");
                }

                $license_key_array[] = trim($row['license']);
                if(!empty($row['license'])){
                    update_no_student_license($row['license'],'student');
                }
            }
            
            
			query('INSERT INTO `olduser` SELECT * FROM `user` WHERE id="' . $studentid . '"');
			query("UPDATE `olduser` SET `username`= '',`email`='',`teacher_name` = '',`firstname` = '',`password` ='' WHERE `id`='" . $studentid . "'");
        
			query('DELETE FROM `user` WHERE id="' . $studentid . '"');
            query('INSERT INTO `oldsettings` SELECT * FROM `settings` WHERE id="' . $studentid . '"');
            query('DELETE FROM `settings` WHERE id="' . $studentid . '"');
            query('INSERT INTO `olddata` SELECT * FROM `data` WHERE id="' . $studentid . '"');
			query('DELETE FROM `oldsettings` WHERE id="' . $studentid . '" and item = 1');
			query('DELETE FROM `oldsettings` WHERE id="' . $studentid . '" and item = 2');           
			query('DELETE FROM `data` WHERE id="' . $studentid . '"');
            query('DELETE FROM `text` WHERE id="' . $studentid . '"');
            query('DELETE FROM `text_data` WHERE id="' . $studentid . '"');
            query('DELETE FROM `pia_user_login_try_ip` WHERE id="' . $studentid . '"');

            query('INSERT INTO `oldactivity` SELECT * FROM `activity` WHERE id="' . $studentid . '"');
            query('DELETE FROM `activity` WHERE id="' . $studentid . '"');
			query('INSERT INTO `oldlog` SELECT * FROM `log` WHERE id="' . $studentid . '"');
            query('DELETE FROM `log` WHERE id="' . $teacherid . '"');
            query('INSERT INTO `olduser_login_detail` SELECT * FROM `user_login_detail` WHERE user_id="' . $studentid . '"');
            query('DELETE FROM `user_login_detail` WHERE user_id="' . $studentid . '"');

            $check_user_exists = query('SELECT user_id FROM wordpress_licenses where `user_id`="' . $studentid . '"');
            if ($check_user_exists->num_rows > 0) {
                query("UPDATE `wordpress_licenses` SET `user_id`=0 WHERE `user_id`='" . $studentid . "'");
            }
            /** delete teacher then add note  */   
            add_license_note_to_history('', $studentid , 'deactive' , 'student');
            /** delete teacher then add note end here  */   
        }
        $result['status'] = TRUE;
        $result['msg'] = 'Student deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';

        echo json_encode($result);
        exit;    
    }
    exit;
}
if (!empty($_POST['action']) && $_POST['action'] == "update_multi_student_password" && !empty($_POST['student_id'])) {

    $studentidArr = $_POST['student_id'];
    $password = $_POST['password'];
    if (!empty($studentidArr) && !empty($password)) {
        foreach($studentidArr as $studentid){
            $encPass = md5($password);
            $newToken = getToken(30);
            //Good to update token after password reset for security purpose
            query("UPDATE user set `password`='" . $encPass . "', `token`='" . $newToken . "'  WHERE id=$studentid");         
        }
        $result['status'] = TRUE;
        $result['msg'] = 'Student password update successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';

        echo json_encode($result);
        exit;    
    }
    exit;
}
/*05SEPT2025*/
if (!empty($_POST['action']) && $_POST['action'] == "delete_report_user" && !empty($_POST['report_delete_id'])) {
	
	$report_id = intval($_POST['report_delete_id']); // secure it

	/*$check = "SELECT download_link FROM report_downloads WHERE id = '$report_id'";
	$dbResult = mysqli_query($con, $check);

	// If running from browser, this is okay
	//$root = $_SERVER['DOCUMENT_ROOT']; 
	//$csvFolder = $root . "/online/uploads/reports";

	$response = ['status' => false, 'msg' => 'Something went wrong'];

	if ($dbResult && mysqli_num_rows($dbResult) > 0) {
		while ($row = mysqli_fetch_assoc($dbResult)) {
			$filename = basename($row['download_link']); 
			$file = $csvFolder . "/" . $filename;

			//if (!empty($file) && file_exists($file)) {
				if (unlink($file)) {
					// delete DB record
					mysqli_query($con, "DELETE FROM report_downloads WHERE id = '$report_id'");

					$response['status'] = true;
					$response['msg'] = 'Report deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
				} 
				//else {
					//$response['msg'] = "Error deleting file: $file";
				//}
			//} else {
			//	$response['msg'] = "File not found: $file";
			//}
		}
	} else {
		$response['msg'] = "Report not found in database.";
	}*/
	mysqli_query($con, "DELETE FROM report_downloads WHERE id = '$report_id'");

	$response['status'] = true;
	$response['msg'] = 'Report deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
	echo json_encode($response);
	exit;
	
}

if (!empty($_POST['action']) && $_POST['action'] == "cancel_report_user" && !empty($_POST['report_cancel_id'])) {
	
	$report_id = intval($_POST['report_cancel_id']); // secure it
    
    // Delete from export_queue
    $del1 = "DELETE FROM export_queue WHERE dwn_lnk_id = '$report_id'"; 
    $del1i = mysqli_query($con, $del1);
    
    // Delete from report_downloads
    $del2 = "DELETE FROM report_downloads WHERE id = '$report_id'"; 
    $del2i = mysqli_query($con, $del2);

    $response = ['success' => true]; // simple response
    echo json_encode($response);
    exit;
	
}

if (!empty($_POST['user_id']) && !empty($_POST['user_id']) && !empty($_POST['action']) && $_POST['action'] == 'Generate_login_link') {

  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 15; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $randomString = base64_encode($randomString );

$string = base64_decode($randomString);
$today_date = date('Y-m-d H:i:s');

if($_POST['exp_per'] == 'Months'){
$days = $_POST['exp_Val'] * 30;
$expiry_date = date("Y-m-d H:i:s", strtotime('+'.$_POST['exp_Val'].' months'));

}elseif($_POST['exp_per'] == 'Hours'){

//$expiry_date = date("Y-m-d H:i:s", strtotime('+'.$_POST['exp_Val'].' hours'));
$expiry_date = date('Y-m-d H:i:s',strtotime('+'.$_POST['exp_Val'].' hour',strtotime($today_date)));
}elseif($_POST['exp_per'] == 'Days'){


//$expiry_date = date('Y-m-d H:i:s',strtotime('+'.$_POST['exp_Val'].' day',strtotime(today_date )));

$expiry_date = date("Y-m-d H:i:s", strtotime('+'.$_POST['exp_Val'].' days'));

}else{
$expiry_date = '';

}
    $sql = query('SELECT * FROM user WHERE id="' . $_POST['user_id'] . '"');
    $data = fetch($sql);
 
if($_POST['user_id'] > 0 && !empty($data)){
query('UPDATE `user` SET wait_page = "'.$_POST["wait"].'" ,link_expiry = "'.$expiry_date.'" ,link_created = "'.$today_date.'" ,Login_url = "'.$randomString.'" ,link_val = "' . $_POST['exp_per'] . '" ,link_exp_num = "' . $_POST['exp_Val'] . '" WHERE id="' . $_POST['user_id'] . '"');
   

$val = '';


if( $_POST['wait'] == 'Yes'){

$wait2 = ' Includes Wait Page.';
}else{

$wait2 = '';
}
if(!empty( $randomString)){
$value = ADMIN_URL . 'login?UID=' . $randomString ;
$olddate = strtotime($expiry_date);
if($_POST['exp_per'] == 'Never'){
$expires = 'Never. '.$wait2;
}else{
$expires = date("m/d/Y h:i a",$olddate).'.'.$wait2;
}

//$expires = 'fwegfwef';
}else{

$value = '';
$expires = '';
}
$val = "<p style='font-size:18px'>This link will log your student in without needing to type in their username and password. Be careful! Anyone can log in to the student's account using this link. Treat it like a password and share it carefully.</p><p style='font-size:18px'>The link will no longer be vaild once it expires, a new link is created, or if the student's password is changed.</p><p style='font-size:18px'>You can optionally include a wait page that requires the user to click a single button before they are logged in. This is useful if creating a home screen shortcut on IOS devices.</p><div class='col-md-12'> <div class='col-md-6'><h4 style='font-weight:bold'>Link Expiration</h4></div><div class='col-md-6'><h4 style='font-weight:bold'>Wait Page</h4></div></div><div class='col-md-12'><div class='col-md-2'><input class='form-control' style='margin-top:0px!important;    border: 2px solid #e5e5e5;' id = 'num".$_POST['inputID']. "' type='number' name='exp_cal' id='exp_cal' value='".$_POST['exp_Val']."'></div><div class='col-md-3'><select class='form-control' id='intv".$_POST['inputID']. "'>";

if($_POST['exp_per']  == 'Hours'){
$val .= "<option value='Hours' selected > Hours</option>";

}else{
$val .= "<option value='Hours' > Hours</option>";

}
if($_POST['exp_per']  == 'Days'){
$val .= "<option value='Days' selected >Days</option>";

}else{
$val .= "<option value='Days'>Days</option>";

}
if($_POST['exp_per']  == 'Months'){
$val .= "<option value='Months' selected >Months</option>";

}else{
$val .= "<option value='Months'>Months</option>";

}
if($_POST['exp_per']  == 'Never'){
$val .= "<option value='Never' selected >Never</option>";

}else{
$val .= "<option value='Never'>Never</option>";

}
$val .= " </select></div><div class='col-md-3'><select class='form-control' id='wt_".$_POST['inputID'] ."'>";
if( $_POST['wait'] == 'Yes'){
$val .= "<option value='Yes' selected >Yes</option>";

}else{
$val .= "<option value='Yes' >Yes</option>";
}
if( $_POST['wait'] == 'No'){
$val .= "<option value ='No' selected >No</option>";

}else{
$val .= "<option value ='No'>No</option>";
}
$val .= " </select></div><div class='col-md-4'><button type='button' class='btn dashboard-settings-btn create_clk_link' id='btn_".$_POST['inputID']."' >Create Link</button></div> </div><div class='col-md-12'><br/><h4 style='font-weight:bold'>Current Link</h4><p>Expires: ".$expires ."</p></div> </div> <br/><br/><input type='text' class='form-control urlcls' value='https://www.accessibyte.com/online/login?UID=$randomString'>";

  echo json_encode(array('status' => 200, 'url' =>  $val ));

}else{

  echo json_encode(array('status' => 400, 'url' => 'https://www.accessibyte.com/online/login?UID='.$randomString ));

}
  
    exit;
}



if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_admin_student_list') {

    $result = array();
    $is_expired = check_expire_or_not();
    $args = array(
        'license' => !empty($_SESSION['User']['license']) ? $_SESSION['User']['license'] : '',
        'role' => 'student',
        'is_admin !=' => '1',
    );
     $search_columns = 'username,firstname,teacher_name,organization';

    //
    // --- 1) BUILD YOUR CASE-INSENSITIVE SEARCH CLAUSE -------------------------
    //
    $where_sql   = '';
    $where_param = [];
    $q           = trim($_POST['search']['value'] ?? '');
    if ($q !== '') {
        // lowercase once
        $term = '%' . mb_strtolower($q, 'UTF-8') . '%';
        $where_sql = " AND ("
                   . " LOWER(`firstname`)      LIKE :_search"
                   . " OR LOWER(`username`)     LIKE :_search"
                   . " OR LOWER(`teacher_name`) LIKE :_search"
                   . " OR LOWER(`organization`) LIKE :_search"
                   . ")";
        $where_param[':_search'] = $term;
    }
	
    //
    // --- 2) BUILD YOUR CASE-INSENSITIVE ORDER BY -----------------------------
    //
   $columns = [
      0 => 'firstname',
      1 => 'username',
      2 => 'teacher_name',
      3 => 'organization',
      4 => 'login',
    ];
    $orderClauses = [];
    foreach ($_POST['order'] ?? [] as $ord) {
        $i   = (int)$ord['column'];
        $dir = $ord['dir'] === 'asc' ? 'ASC' : 'DESC';
        if (!isset($columns[$i])) continue;
        // force LOWER() on the two text columns:
        if ($i === 0 || $i === 1) {
          $orderClauses[] = "LOWER(`{$columns[$i]}`) $dir";
        } else {
          $orderClauses[] = "`{$columns[$i]}` $dir";
        }
    }
    if (!empty($orderClauses)) {
    // add a stable tie-breaker
    $order_by = implode(', ', $orderClauses)
              . ', LOWER(`username`) ASC';
} else {
    $order_by = 'l.id DESC';
}
	//$order_by = 'l.id DESC';
    $where = array();
    $groupby = '';
	$extra_bind_data = '';
    $records = json_datatable('user', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array(), $extra_where, $extra_bind_data);
	
    //$records = json_datatable('user', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
		{
           
            //$activityArr = getUserLog($user['id'],1);
            
            $activity = '';
            if(!empty($user['login']))
            $activity = getTimtstampDiff($user['login']);
            $firstname =  ucfirst(base64_decode($user['firstname']));
         //   $username = base64_encode($user['username'] . '-' . $user['password']);
            $deleteuser = ucfirst(base64_decode($user['firstname'])) .' ('.ucfirst(base64_decode($user['username'])).")";

if($user['wait_page'] == 'Yes'){

$wait2 = 'Includes Wait Page.';
}else{

$wait2 = '';
}
	

$val = '';
$url = $user['Login_url'];
$value = ADMIN_URL . 'login?UID=' . $user['Login_url'];
$olddate = strtotime($user['link_expiry']);

if($user['link_val'] == 'Never'){
  $expires = 'Never. '.$wait2;
}else{
$expires = date("m/d/Y h:i a",$olddate).'.'.$wait2;
}
$val = "<div class='col-md-12'><div class='col-md-2'><input class='form-control' style='margin-top:0px!important;    border: 2px solid #e5e5e5;' id = 'numurl_".$user['id']. "' type='number' name='exp_cal' id='exp_cal' value='".$user['link_exp_num']."'></div><div class='col-md-3'><select class='form-control' id='intvurl_".$user['id']. "'>";

if($user['link_val']  == 'Hours'){
$val .= "<option value='Hours' selected > Hours</option>";

}else{
$val .= "<option value='Hours' > Hours</option>";

}
if($user['link_val']   == 'Days'){
$val .= "<option value='Days' selected >Days</option>";

}else{
$val .= "<option value='Days'>Days</option>";

}
if($user['link_val']   == 'Month'){
$val .= "<option value='Months' selected >Months</option>";

}else{
$val .= "<option value='Months'>Months</option>";

}
if($user['link_val']   == 'Never'){
$val .= "<option value='Never' selected >Never</option>";

}else{
$val .= "<option value='Never'>Never</option>";

}
$val .= " </select></div><div class='col-md-3'><select class='form-control' id='wt_url_".$user['id'] ."'>";
if( $user['wait_page'] == 'Yes'){
$val .= "<option value='Yes' selected >Yes</option>";

}else{
$val .= "<option value='Yes' >Yes</option>";
}
if( $user['wait_page'] == 'No'){
$val .= "<option value ='No' selected >No</option>";

}else{
$val .= "<option value ='No'>No</option>";
}
$val .= " </select></div><div class='col-md-4'><button type='button' class='btn dashboard-settings-btn create_clk_link' id='btn_url_".$user['id']."' >Create Link</button></div> </div>";
if($url != ""){
$val .= "<div class='col-md-12'><br/><h4 style='font-weight:bold'>Current Link</h4><p>Expires: ".$expires ."</p></div> </div> <br/><br/><input type='text' class='form-control urlcls' value='https://www.accessibyte.com/online/login?UID=$url'>";
}
	
			/* For expired license not display action button */
            $action = '';
            if(!$is_expired){
                $action = '<div class="studentAction">
                <span> <a href="' . ADMIN_URL . 'student/student-overview.php?student=' . $user['id'] . '" aria-label="Overview for ' . ucfirst(base64_decode($user['firstname'])) . ' " class="accessibyte-link">Overview</a>
                </span><span class="studentActionLogin"><a href="javascript:void(0)" data-name="' . ucfirst(base64_decode($user['firstname'])) . '(' . base64_decode($user['username']) . ')" data-URL="url_' . $user['id'] . '" class="accessibyte-link one-click-login" aria-label="1-Click login for ' . ucfirst(base64_decode($user['firstname'])) . ' ">1-Click login</a><div class="click_one_hide"><input type="text" style="opacity:0;" id="exp_'.$user['id'].'" class="exp_link_Copy" value="'.$expires.'"><input type="text" style="opacity:0;" id="numval_'.$user['id'].'" class="num_link_Copy" value="'.$val.'"><input type="text" style="opacity: 0;" id="url_' . $user['id'] . '" class="url_copy" aria-label="You can paste this link…" value="' . $value. '"></div></span></div>';
            }
            $data[] = array(
                '<div class="icheck-primary d-inline"><input type="checkbox" aria-label="Checkbox ' . ucfirst(base64_decode($user['firstname'])) . ' " data-name="' . $deleteuser . ' " class="chkbox" name="ids[]" value="' . $user['id'] . '" id="all_chkbox'.$user['id'].'"><label for="all_chkbox'.$user['id'].'">'.$firstname.'</label></div>',
                '<span class="">' . base64_decode($user['username']) . '<span>',
                '<span class="">' . base64_decode($user['teacher_name']) . '<span>',
                '<span class="">' . base64_decode($user['organization']) . '<span>',
                '<span class="">'.$activity.'<span>',                
                $action
            );
            $dataforUse[] = array(
                $user['id'],
            );
        }
        /*$records['data'] = $data;        
        $records['status'] = 1;*/

        $draw = $_POST['draw'];
        $start = $_POST['start'];
        $length = $_POST['length'];


        $alldata = array_slice($data, $start, $length);

        $records['data'] = $data;        
        $records['status'] = 1;
        $records['draw'] = $draw;
        echo json_encode([
            "draw" => intval($draw),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $alldata,
            'dataforUse'=>$dataforUse
        ]);

    }
    
    if (empty($records)) {
        $records['status'] = 0;
        echo json_encode($records);
    }

    //echo json_encode($records);
    exit;
}


if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_admin_student_list_old') {

    $result = array();
    $is_expired = check_expire_or_not();
    $args = array(
        'license' => !empty($_SESSION['User']['license']) ? $_SESSION['User']['license'] : '',
        'role' => 'student',
        'is_admin !=' => '1',
    );
    $search_columns = 'username,firstname,teacher_name,organization';
    $order_by = 'l.id desc';

    $where = array();
    $groupby = '';
    $records = json_datatable('user', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
		{
            $activityArr = getUserLog($user['id'],1);
            
            $activity = '';
            if(!empty($user['login']))
            $activity = getTimtstampDiff($user['login']);
            $firstname =  ucfirst(base64_decode($user['firstname']));
            $username = base64_encode($user['username'] . '-' . $user['password']);
            $deleteuser = ucfirst(base64_decode($user['firstname'])) .' ('.ucfirst(base64_decode($user['username'])).")";
			
			/* For expired license not display action button */
            $action = '';
            if(!$is_expired){
                $action = '<div class="studentAction">
                <span> <a href="' . ADMIN_URL . 'student/student-overview.php?student=' . $user['id'] . '" aria-label="Overview for ' . ucfirst(base64_decode($user['firstname'])) . ' " class="accessibyte-link">Overview</a>
                </span><span class="studentActionLogin"><a href="javascript:void(0)" data-name="' . ucfirst(base64_decode($user['firstname'])) . '(' . base64_decode($user['username']) . ')" data-URL="url_' . $user['id'] . '" class="accessibyte-link one-click-login" aria-label="1-Click login for ' . ucfirst(base64_decode($user['firstname'])) . ' ">1-Click login</a><div class="click_one_hide"><input type="text" style="opacity: 0;" id="url_' . $user['id'] . '" class="url_copy" aria-label="You can paste this link…" value="' . ADMIN_URL . 'login?UID=' . $username . '"></div></span></div>';
            }
            $data[] = array(
                '<div class="icheck-primary d-inline"><input type="checkbox" aria-label="Checkbox ' . ucfirst(base64_decode($user['firstname'])) . ' " data-name="' . $deleteuser . ' " class="chkbox" name="ids[]" value="' . $user['id'] . '" id="all_chkbox'.$user['id'].'"><label for="all_chkbox'.$user['id'].'">'.$firstname.'</label></div>',
                '<span class="">' . base64_decode($user['username']) . '<span>',
                '<span class="">' . base64_decode($user['teacher_name']) . '<span>',
                '<span class="">' . base64_decode($user['organization']) . '<span>',
                '<span class="">'.$activity.'<span>',                
                $action
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_typio_lesson') {

    

    $result = array();
    $args = array(
        'app' => 'Typio-OL',
        'id' => $_POST['student_id'],
        'number' =>'0',
    );
    $search_columns = 'title';
    $order_by = 'l.id desc';

    $where = array();
    $groupby = '';

    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    //echo "<pre>";print_r($records['data']);die;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
        {
            /* For expired license not display action button */
            $action = '';
            
            $data[] = array(
                '<span class="">' . $user['title'] . '<span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-green edit-lessons-modal" data-id="' . $user['table_id'] . '" data-type="'.$_POST['data-type'].'"> <i class="fa  fa-edit (alias)" aria-label="Edit lesson" role="button"></i></a><span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $user['table_id'] . '"><i class="fa fa-trash-o" aria-label="Delete" role="button"></i></a><span>',
               
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

   // echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}


if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_Brail_lesson') {

    

$result = array();
$args = array(
    'app' => 'Typio-BRL',
    'id' => $_POST['student_id'],
    'number' =>'0',
);
$search_columns = 'title';
$order_by = 'l.id desc';

$where = array();
$groupby = '';

$records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());

//echo "<pre>";print_r($records['data']);die;
if (!empty($records)) {
    $data = array();
    foreach ($records['data'] as $user) 
    {
        /* For expired license not display action button */
        $action = '';
        
        $data[] = array(
            '<span class="">' . $user['title'] . '<span> ',
            '<span class=""><a href="javascript:void(0)" class="badge bg-green edit-lessons-modalBRL" data-id="' . $user['table_id'] . '" data-type="'.$_POST['data-type'].'"> <i class="fa  fa-edit (alias)" aria-label="Edit lesson" role="button"></i></a><span>',
            '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $user['table_id'] . '"><i class="fa fa-trash-o" aria-label="Delete" role="button"></i></a><span>',
           
        );
    }
    $records['data'] = $data;        
    $records['status'] = 1;

}

// echo "<pre>"; print_r($records); exit;

if (empty($records)) {
    $records['status'] = 0;
}

echo json_encode($records);
exit;
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_typio_test_lesson') {

    $result = array();
    $args = array(
        'app' => 'Typio-Test',
        'id' => $_POST['student_id'],
    );
    $search_columns = 'file';
    $order_by = 'l.id desc';

    $where = array();
    $groupby = '';

    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    //echo "<pre>";print_r($records['data']);die;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
        {
            /* For expired license not display action button */
            $action = '';
            
            $data[] = array(
                '<span class="">' . $user['title'] . '<span>',
                '<span class="">' . $user['number'] . '<span>',
                '<span class=""><a href="javascript:void(0)" data-label="Custom Test Editor" class="badge bg-green edit-lessons-modal" data-id="' . $user['table_id'] . '" data-type="'.$_POST['data-type'].'"> <i class="fa  fa-edit (alias)" aria-label="Edit lesson" role="button"></i></a><span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $user['table_id'] . '"><i class="fa fa-trash-o" aria-label="Delete" role="button"></i></a><span>',
               
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

   // echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}


 if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_Brail_test_lesson') {

$result = array();
$args = array(
    'app' => 'Typio-Test-BRL',
    'id' => $_POST['student_id'],
);
$search_columns = 'file';
$order_by = 'l.id desc';

$where = array();
$groupby = '';

$records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());

//echo "<pre>";print_r($records['data']);die;
if (!empty($records)) {
    $data = array();
    foreach ($records['data'] as $user) 
    {
        /* For expired license not display action button */
        $action = '';
        
        $data[] = array(
            '<span class="">' . $user['title'] . '<span>',
            '<span class="">' . $user['number'] . '<span>',
            '<span class=""><a href="javascript:void(0)" data-label="Custom Test Editor" class="badge bg-green edit-lessons-modalBRL" data-id="' . $user['table_id'] . '" data-type="'.$_POST['data-type'].'"> <i class="fa  fa-edit (alias)" aria-label="Edit lesson" role="button"></i></a><span>',
            '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $user['table_id'] . '"><i class="fa fa-trash-o" aria-label="Delete" role="button"></i></a><span>',
           
        );
    }
    $records['data'] = $data;        
    $records['status'] = 1;

}

// echo "<pre>"; print_r($records); exit;

if (empty($records)) {
    $records['status'] = 0;
}

echo json_encode($records);
exit;
}


 if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_weekly_Brail_test_lesson') {

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

$Brail_start_date = getTimezonewiseDate($Brail_start_date);
$Brail_end_date = getTimezonewiseDate($Brail_end_date);
//$Brail_start_date="2021-01-01";

$result = array();
$args = array(
    'id' => $_POST['student_id'],
    'date BETWEEN' =>$Brail_start_date."_".$Brail_end_date,
    'app_condition' => "( `app` LIKE 'Typio-Test' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL')"
);

//echo "<pre>"; print_r($args); exit;

$search_columns = 'title';
$order_by = 'l.id desc';

$where = array();
$groupby = '';
//  echo "<pre>"; print_r($args); exit;

$records = json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
$_average_scale = Brail_average_chart_write($_POST['student_id'], $Brail_start_date, $Brail_end_date);


if (!empty($records)) {
    $data = array();
    $data[] = array(
            '<span class="">Average<span>',
            '<span class=""><span>',
             '<span class="">' . $_average_scale['WPM'] . '<span>',
            '<span class="">' . $_average_scale['Accuracy'] . '<span>',
            '<span class="">' . $_average_scale['Combo'] . '<span>',
            '<span class=""><span>',
            '<span class=""><span>',
        );


      foreach($records['data'] as $key_data =>$user_data ){

        $log_Brail_data_value = explode('|', $user_data['data']);
        $records['data'][$key_data]['file'] = $user_data['file'];
        $records['data'][$key_data]['date'] = $user_data['date'];
        $records['data'][$key_data]['wpm'] = $log_Brail_data_value[0];
        $records['data'][$key_data]['acc'] = $log_Brail_data_value[1];
        $records['data'][$key_data]['err'] = $log_Brail_data_value[2];

     }
     //echo "<pre>"; print_r($records['data']); 
   $order_sort = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:"";
   $order_column =isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:"";

   //$column_nm = isset( $_POST['columns'][$order_column]['name'] )$_POST['columns'][$order_column]['name'] :"";
  $column_nm = $_POST['columns'][$order_column]['name'];

   if($order_sort == 'asc'){
        $order_sort_data =SORT_ASC;
   }else{
        $order_sort_data =SORT_DESC;
   }

    //echo "column name::".$column_nm = $_POST['columns'][$order_column]['name'];
   // exit;
   // $sort_column = array_column($records['data'], $column_nm);

   /*if ( !empty($order_column )){
    echo "sdsds".$order_column;
    exit;
        $price = array_column($records['data'], $order_column );
        array_multisort($price, $order_sort_data, $records['data']);
   }*/

   $price = array_column($records['data'], $column_nm);
  array_multisort($price, $order_sort_data, $records['data']);
    
    
    /* if( $_POST['order'][0]['dir'] == 'asc'){
        array_multisort($sort_column, SORT_ASC, $records['data']);
        $sort_order=SORT_ASC;
     }else{
        array_multisort($sort_column, SORT_DESC, $records['data']);
        $sort_order=SORT_DESC;
     }*/
    // echo "sort option:::".$sort_order;
     //exit;


   /* foreach($records['data'] as $key_data =>$user_data ){

        $log_Brail_data_value = explode('|', $user_data['data']);

        $records['data'][$key_data]['file'] = $user_data['file'];
        $records['data'][$key_data]['date'] = $user_data['date'];
        $records['data'][$key_data]['wpm'] = $log_Brail_data_value[0];
        $records['data'][$key_data]['acc'] = $log_Brail_data_value[1];
        $records['data'][$key_data]['err'] = $log_Brail_data_value[2];

     }
     //echo "<pre>"; print_r($records['data']); 
     
    // $sort_column = array_column($records['data'], 'wpm');
     //array_multisort($price, SORT_ASC, $records['data']);

     */   
     //echo "<pre>"; print_r($records['data']); 
    foreach ($records['data'] as $user) 
    {
        $log_Brail_data_value = explode('|', $user['data']);

        /* For expired license not display action button */
        $action = '';
        $title = ($user['file']) ? $user['file'] : '';
       $log_Brail_data_value = explode('|', $user['data']);
       $custom_date =  custom_date($Brail_start_date);

    $log_Brail_data_html="";
    if ($user['file'] != 'Free Type' && !empty($log_Brail_data_value) && count($log_Brail_data_value) > 3) {      
            $log_Brail_data_html .= '<a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $user['lognr'] . '><i class="fa fa-play"></i></a>';
        }
        
        $data[] = array(
            '<span class="">' . $user['file'] . '<span>',
            '<span class="">' . date('m/d/y', strtotime($user['date'])) . '<span>',
             '<span class="">' . $log_Brail_data_value[0] . '<span>',
            '<span class="">' . $log_Brail_data_value[1] . '%<span>',
            '<span class="">' . $log_Brail_data_value[2] . '<span>',
            '<span class=""><a href="javascript:void(0)" data-type="week-history" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $user['lognr'] . '><i class="fa fa-trash-o"></i></a><span>',
             '<span>'.$log_Brail_data_html.'</span>',
        );
    }
    $records['data'] = $data;        
    $records['status'] = 1;

}

// echo "<pre>"; print_r($records); exit;

if (empty($records)) {
    $records['status'] = 0;
}

echo json_encode($records);
exit;
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_weekly_typio_test_lesson') {

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
    
    $typio_start_date = getTimezonewiseDate($typio_start_date);
    $typio_end_date = getTimezonewiseDate($typio_end_date);
    //$typio_start_date="2021-01-01";
   
    $result = array();
    $args = array(
        'id' => $_POST['student_id'],
        'date BETWEEN' =>$typio_start_date."_".$typio_end_date,
        'app_condition' => "(`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Test' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')"
		
    );
    //echo "<pre>"; print_r($args); exit;
   
    $search_columns = 'title';
    $order_by = 'l.id desc';

    $where = array();
    $groupby = '';
  //  echo "<pre>"; print_r($args); exit;

    $records = json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    $_average_scale = Typio_average_chart_write($_POST['student_id'], $typio_start_date, $typio_end_date);

    
    if (!empty($records)) {
        $data = array();
        $data[] = array(
                '<span class="">Average<span>',
                '<span class=""><span>',
                 '<span class="">' . $_average_scale['WPM'] . '<span>',
                '<span class="">' . $_average_scale['Accuracy'] . '<span>',
                '<span class="">' . $_average_scale['Combo'] . '<span>',
                '<span class=""><span>',
                '<span class=""><span>',
            );


          foreach($records['data'] as $key_data =>$user_data ){

            $log_typio_data_value = explode('|', $user_data['data']);
            $records['data'][$key_data]['file'] = $user_data['file'];
            $records['data'][$key_data]['date'] = $user_data['date'];
            $records['data'][$key_data]['wpm'] = $log_typio_data_value[0];
            $records['data'][$key_data]['acc'] = $log_typio_data_value[1];
            $records['data'][$key_data]['err'] = $log_typio_data_value[2];

         }
         //echo "<pre>"; print_r($records['data']); 
       $order_sort = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:"";
       $order_column =isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:"";

       //$column_nm = isset( $_POST['columns'][$order_column]['name'] )$_POST['columns'][$order_column]['name'] :"";
      $column_nm = $_POST['columns'][$order_column]['name'];

       if($order_sort == 'asc'){
            $order_sort_data =SORT_ASC;
       }else{
            $order_sort_data =SORT_DESC;
       }

        //echo "column name::".$column_nm = $_POST['columns'][$order_column]['name'];
       // exit;
       // $sort_column = array_column($records['data'], $column_nm);

       /*if ( !empty($order_column )){
        echo "sdsds".$order_column;
        exit;
            $price = array_column($records['data'], $order_column );
            array_multisort($price, $order_sort_data, $records['data']);
       }*/

       $price = array_column($records['data'], $column_nm);
      array_multisort($price, $order_sort_data, $records['data']);
        
        
        /* if( $_POST['order'][0]['dir'] == 'asc'){
            array_multisort($sort_column, SORT_ASC, $records['data']);
            $sort_order=SORT_ASC;
         }else{
            array_multisort($sort_column, SORT_DESC, $records['data']);
            $sort_order=SORT_DESC;
         }*/
        // echo "sort option:::".$sort_order;
         //exit;


       /* foreach($records['data'] as $key_data =>$user_data ){

            $log_typio_data_value = explode('|', $user_data['data']);

            $records['data'][$key_data]['file'] = $user_data['file'];
            $records['data'][$key_data]['date'] = $user_data['date'];
            $records['data'][$key_data]['wpm'] = $log_typio_data_value[0];
            $records['data'][$key_data]['acc'] = $log_typio_data_value[1];
            $records['data'][$key_data]['err'] = $log_typio_data_value[2];

         }
         //echo "<pre>"; print_r($records['data']); 
         
        // $sort_column = array_column($records['data'], 'wpm');
         //array_multisort($price, SORT_ASC, $records['data']);

         */   
         //echo "<pre>"; print_r($records['data']); 
        foreach ($records['data'] as $user) 
        {
            $log_typio_data_value = explode('|', $user['data']);

            /* For expired license not display action button */
            $action = '';
            $title = ($user['file']) ? $user['file'] : '';
           $log_typio_data_value = explode('|', $user['data']);
           $custom_date =  custom_date($typio_start_date);

        $log_typio_data_html="";
        if ($user['file'] != 'Free Type' && !empty($log_typio_data_value) && count($log_typio_data_value) > 3) {      
                //$log_typio_data_html .= '<a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $user['lognr'] . '><i class="fa fa-play"></i></a>';
            }
            
            $data[] = array(
                '<span class="">' . $user['file'] . '<span>',
                '<span class="">' . date('m/d/y', strtotime($user['date'])) . '<span>',
                 '<span class="">' . $log_typio_data_value[0] . '<span>',
                '<span class="">' . $log_typio_data_value[1] . '%<span>',
                '<span class="">' . $log_typio_data_value[2] . '<span>',
                '<span class=""><a href="javascript:void(0)" data-type="week-history" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $user['lognr'] . '><i class="fa fa-trash-o"></i></a><span>',
                 '<span>'.$log_typio_data_html.'</span>',
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

   // echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_arcade_history') {
   // echo "<pre>"; print_r($_SESSION); 
    

    $result = array();

    $ArcadeDatePickerFrom =isset( $_SESSION['ArcadeDatePickerFrom'] ) ? $_SESSION['ArcadeDatePickerFrom']: $_POST['ArcadeDatePickerFrom'];
    $ArcadeDatePickerTo =isset( $_SESSION['ArcadeDatePickerTo'] ) ? $_SESSION['ArcadeDatePickerTo']: $_POST['ArcadeDatePickerTo'];


    $start_data =isset($ArcadeDatePickerFrom )?date("Y-m-d",strtotime($ArcadeDatePickerFrom )):"";
    $end_data =isset($ArcadeDatePickerTo)?date("Y-m-d",strtotime($ArcadeDatePickerTo )):"";
    $args = array(
        'app' => 'Arcade-OL',
        'id' => $_POST['student_id'],
        'date BETWEEN' =>$start_data."_".$end_data,
    );
   
    $search_columns = 'title';
    $order_by = 'l.id desc';

    $where = array();
    $groupby = '';
  //  echo "<pre>"; print_r($args); exit;

    $records = json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    //echo "<pre>";print_r($records['data']);die;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
        {
            /* For expired license not display action button */
            $action = '';
            
            $data[] = array(
                '<span class="">' . $user['file'] . '<span>',
                '<span class="">' . $user['date'] . '<span>',
               
               
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

   // echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}

if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_quick_card_week_history') {
  
  
    $result = array();

    
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



    $start_date = getTimezonewiseDate($start_date);
    $end_date = getTimezonewiseDate($end_date);




    $args = array(
        'app' => 'Quick-Cards-OL',
        'id' => $_POST['student_id'],
        'date BETWEEN' =>$start_date."_".$end_date,
    );
   
    $search_columns = 'title';
    $order_by = 'l.id desc';

    $where = array();
    $groupby = '';
  //  echo "<pre>"; print_r($args); exit;

    $records = json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    //echo "<pre>";print_r($records['data']);die;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
        {
            /* For expired license not display action button */

              $percent_score = $incorrect = $cards_missed = "";
              $data_value = explode('|', $user['data']);
                if (!empty($data_value[0])) {
                    $percent_score = $data_value[0];
                }
                if (!empty($data_value[1])) {
                    $incorrect = $data_value[1];
                }
                if (!empty($data_value[2])) {
                    $cards_missed = explode('~', $data_value[2]);
                    $cards_missed = trim($cards_missed);
                }

            $action = '';
            
            $data[] = array(
                '<span class="">' . $user['file'] . '<span>',
                '<span class="">' . $percent_score . '<span>',
                '<span class="">' . $incorrect . '<span>',
                '<span class="">' . implode(', ', $cards_missed ) . '<span>',
                '<span class="">' .  date('m/d/y', strtotime($user['date'])). '<span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red history-delete" data-id=' . $user['lognr'] . '><i class="fa fa-trash-o" aria-label="Delete"></i></a><span>',
               
               
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

    //echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}

if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_quick_card_history') {

        $result = array();

    
        $qc_start_date =isset( $_SESSION['qc_start_date'] ) ? $_SESSION['qc_start_date']: $_POST['qc_start_date'];
        $qc_end_date =isset( $_SESSION['qc_end_date'] ) ? $_SESSION['qc_end_date']: $_POST['qc_end_date'];


        $start_data =isset($qc_start_date )?date("Y-m-d",strtotime($qc_start_date )):"";
        $end_date =isset($qc_end_date)?date("Y-m-d",strtotime($qc_end_date )):"";


    $args = array(
        'app' => 'Quick-Cards-OL',
        'id' => $_POST['student_id'],
        'date BETWEEN' =>$start_data."_".$end_date,
    );

   
    $search_columns = 'title';
    $order_by = 'l.id desc';

    $where = array();
    $groupby = '';
  //  echo "<pre>"; print_r($args); exit;

    $records = json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
        {
            /* For expired license not display action button */

              $percent_score = $incorrect = $cards_missed = "";
              $data_value = explode('|', $user['data']);
                if (!empty($data_value[0])) {
                    $percent_score = $data_value[0];
                }
                if (!empty($data_value[1])) {
                    $incorrect = $data_value[1];
                }
                if (!empty($data_value[2])) {
                    $cards_missed = explode('~', $data_value[2]);
                    $cards_missed = trim($cards_missed);
                }

            $action = '';
            
            $data[] = array(
                '<span class="">' . $user['file'] . '<span>',
                '<span class="">' . $percent_score . '<span>',
                '<span class="">' . $incorrect . '<span>',
                '<span class="">' . implode(', ', $cards_missed ) . '<span>',
                '<span class="">' .  date('m/d/y', strtotime($user['date'])). '<span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red history-delete" data-id=' . $user['lognr'] . '><i class="fa fa-trash-o" aria-label="Delete"></i></a><span>',
               
               
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

   // echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }
//echo "<pre>";print_r($records);die;
    echo json_encode($records);
    exit;
}


if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'set_ajax_date_field') {

    $_SESSION['ArcadeDatePickerFrom']=isset( $_POST['ArcadeDatePickerFrom'] ) ? $_POST['ArcadeDatePickerFrom'] : "";
    $_SESSION['ArcadeDatePickerTo']=isset( $_POST['ArcadeDatePickerTo'] ) ? $_POST['ArcadeDatePickerTo'] : "";
    echo "1";
    exit;
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'set_ajax_quick_card_history_date_field') {

    $_SESSION['qc_start_date']=isset( $_POST['qc_start_date'] ) ? $_POST['qc_start_date'] : "";
    $_SESSION['qc_end_date']=isset( $_POST['qc_end_date'] ) ? $_POST['qc_end_date'] : "";
    $result = array();
    $qc_start_date =isset( $_SESSION['qc_start_date'] ) ? $_SESSION['qc_start_date']: $_POST['qc_start_date'];
    $qc_end_date =isset( $_SESSION['qc_end_date'] ) ? $_SESSION['qc_end_date']: $_POST['qc_end_date'];
    $start_data =isset($qc_start_date )?date("Y-m-d",strtotime($qc_start_date )):"";
    $end_date =isset($qc_end_date)?date("Y-m-d",strtotime($qc_end_date )):"";
    
    $records = get_QuickCards_data($student_id,$start_data, $end_date);
	$totalCount = count($records);
	$records['totalCount'] = $totalCount;
    $chart_html = '';
    if (!empty($records)) {
        $data = array();
        foreach ($records as $user) 
        {
            $score = isset($user['percent_score']) ? $user['percent_score'] : '';
            /* For expired license not display action button */            
            $chart_html .= '<tr> <td>' . $user['file'] . '</td>  <td>' . $score . '</td>  </tr>';            

        }
		//$totalCount = count($records);
        $records['status'] = 1;
		
    }
    $records['chart_html'] = $chart_html;
    
	
    echo json_encode($records);
    exit;  
}

if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'set_Brail_ajax_date_field') {

    $_SESSION['BrailDatePickerFrom']= isset( $_POST['BrailDatePickerFrom'] ) ? $_POST['BrailDatePickerFrom'] : "";
    $_SESSION['BrailDatePickerTo']= isset( $_POST['BrailDatePickerTo'] ) ? $_POST['BrailDatePickerTo'] : "";
    $result = array();
    $qc_start_date =isset( $_SESSION['BrailDatePickerFrom'] ) ? $_SESSION['BrailDatePickerFrom']: $_POST['BrailDatePickerFrom'];
    $qc_end_date =isset( $_SESSION['BrailDatePickerTo'] ) ? $_SESSION['BrailDatePickerTo']: $_POST['BrailDatePickerTo'];
    $start_data =isset($qc_start_date )?date("Y-m-d",strtotime($qc_start_date )):"";
    $end_date =isset($qc_end_date)?date("Y-m-d",strtotime($qc_end_date )):"";
    
   /* $records = get_QuickCards_data($student_id,$start_data, $end_date);
	$totalCount = count($records);
	$records['totalCount'] = $totalCount;
    $chart_html = '';
    if (!empty($records)) {
        $data = array();
        foreach ($records as $user) 
        {
            $score = isset($user['percent_score']) ? $user['percent_score'] : '';
            /* For expired license not display action button */            
            /*$chart_html .= '<tr> <td>' . $user['file'] . '</td>  <td>' . $score . '</td>  </tr>';            

        }
		//$totalCount = count($records);
        $records['status'] = 1;
		
    }
    $records['chart_html'] = $chart_html;
    
	
    echo json_encode($records);*/
    exit;  
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'set_Brail_ajax_date_field_kp') {

    $_SESSION['BrailDatePickerFrom']= isset( $_POST['BrailDatePickerFrom'] ) ? $_POST['BrailDatePickerFrom'] : "";
    $_SESSION['BrailDatePickerTo']= isset( $_POST['BrailDatePickerTo'] ) ? $_POST['BrailDatePickerTo'] : "";
    $result = array();
    $qc_start_date =isset( $_SESSION['BrailDatePickerFrom'] ) ? $_SESSION['BrailDatePickerFrom']: $_POST['BrailDatePickerFrom'];
    $qc_end_date =isset( $_SESSION['BrailDatePickerTo'] ) ? $_SESSION['BrailDatePickerTo']: $_POST['BrailDatePickerTo'];
    $start_data =isset($qc_start_date )?date("Y-m-d",strtotime($qc_start_date )):"";
    $end_date =isset($qc_end_date)?date("Y-m-d",strtotime($qc_end_date )):"";
    
   /* $records = get_QuickCards_data($student_id,$start_data, $end_date);
	$totalCount = count($records);
	$records['totalCount'] = $totalCount;
    $chart_html = '';
    if (!empty($records)) {
        $data = array();
        foreach ($records as $user) 
        {
            $score = isset($user['percent_score']) ? $user['percent_score'] : '';
            /* For expired license not display action button */            
            /*$chart_html .= '<tr> <td>' . $user['file'] . '</td>  <td>' . $score . '</td>  </tr>';            

        }
		//$totalCount = count($records);
        $records['status'] = 1;
		
    }
    $records['chart_html'] = $chart_html;
    
	
    echo json_encode($records);*/
    exit;  
}

if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_quick_card_lesson') {

    

    $result = array();
    $args = array(
        'app' => 'QC-OL',
        'id' => $_POST['student_id'],
        'number' =>'0',
    );
    $search_columns = 'title';
    $order_by = 'id';

    $where = array();
    $groupby = '';

    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    //echo "<pre>";print_r($records['data']);die;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
        {
            /* For expired license not display action button */
            $action = '';
            $card_data = (!empty($user['data']) ? count(explode('|', $user['data'])) : 0);
            
            $data[] = array(
                '<span class="">' . $user['title'] . '<span>',
                '<span class="">' . $card_data . '<span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-green student-decks-maker_type_1_edit" data-id="' . $user['table_id'] . '">
                            <i class="fa fa-edit (alias)" aria-label="Edit"></i></a><span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-red student-decks-maker_type_1_delete" data-id="' . $user['table_id'] . '">
                                              <i class="fa fa-trash-o" aria-label="Delete"></i><span>',
               
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

   // echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_quick_card_test') {

    

    $result = array();
    $args = array(
        'app' => 'QC-OL',
        'id' => $_POST['student_id'],
        'number' =>'1',
    );
    $search_columns = 'title';
    $order_by = 'id';

    $where = array();
    $groupby = '';

    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    //echo "<pre>";print_r($records['data']);die;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
        {
            /* For expired license not display action button */
            $action = '';
            $card_data =  (!empty($user['data']) ? count(explode('|', $user['data'])) - 1 : 0);

            
            $data[] = array(
                '<span class="">' . $user['title'] . '<span>',
                '<span class="">' . $card_data . '<span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-green student-decks-maker_type_1_edit" data-id="' . $user['table_id'] . '">
                            <i class="fa fa-edit (alias)" aria-label="Edit"></i></a><span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-red student-decks-maker_type_1_delete" data-id="' . $user['table_id'] . '">
                                              <i class="fa fa-trash-o" aria-label="Delete"></i><span>',
               
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

   // echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}

if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_hangman_lesson') {

    
    $result = array();
    $args = array(
        'app' => 'Arcade-OL',
        'id' => $_POST['student_id'],
        'number' =>'2',
    );
    $search_columns = 'title';
    $order_by = 'id';

    $where = array();
    $groupby = '';

    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    //echo "<pre>";print_r($records['data']);die;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
        {
            /* For expired license not display action button */
            $action = '';
            
            $data[] = array(
                '<span class="">' . $user['title'] . '<span>',
                '<span class=""><a href="#" class="badge bg-green edit-hangman-modal" data-id="'.$user['table_id'].'"><i class="fa  fa-edit"></i><span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-red student-decks-maker_type_1_delete" data-id="' . $user['table_id'] . '">
                                              <i class="fa fa-trash-o" aria-label="Delete"></i><span>',
               
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

   // echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_propack_reader_file') {


    $result = array();
    $args = array(
        'app' => 'PP-OL',
        'id' => $_POST['student_id'],
        'number' =>'0',
    );
    $search_columns = 'title';
    $order_by = 'id';

    $where = array();
    $groupby = '';

    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    //echo "<pre>";print_r($records['data']);die;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
        {
            /* For expired license not display action button */
            $action = '';
            
            $data[] = array(
                '<span class="">' . $user['title'] . '<span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-green edit-pro-pack-modal" data-id="'.$user['table_id'].'"><i class="fa fa-edit (alias)" aria-label="Edit"></i></a><span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-type="Reader Doc" data-target="#delete-modal-set" class="badge bg-red delete-pro-pack-modal" data-id="'.$user['table_id'].'"><i class="fa fa-trash-o" aria-label="Delete"></i></a><span>',
               
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

   // echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_propack_notpad_file') {


    $result = array();
    $args = array(
        'app' => 'PP-OL',
        'id' => $_POST['student_id'],
        'number' =>'1',
    );
    $search_columns = 'title';
    $order_by = 'id';

    $where = array();
    $groupby = '';

    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    //echo "<pre>";print_r($records['data']);die;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
        {
            /* For expired license not display action button */
            $action = '';
            
            $data[] = array(
                '<span class="">' . $user['title'] . '<span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-green edit-pro-pack-modal" data-id="'.$user['table_id'].'"><i class="fa fa-edit (alias)" aria-label="Edit"></i></a><span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-type="Notepad Docs" data-target="#delete-modal-set" class="badge bg-red delete-pro-pack-modal" data-id="'.$user['table_id'].'"><i class="fa fa-trash-o" aria-label="Delete"></i></a><span>',
               
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

   // echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}

if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_propack_todo_list') {


    $result = array();
    $args = array(
        'app' => 'PP-OL',
        'id' => $_POST['student_id'],
        'number' =>'2',
    );
    $search_columns = 'title';
    $order_by = 'id';

    $where = array();
    $groupby = '';

    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    
    //echo "<pre>";print_r($records['data']);die;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user) 
        {
            /* For expired license not display action button */
            $action = '';
            $data[] = array(
                '<span class="">' . $user['title'] . '<span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-green edit-pro-pack-modal" data-id="'.$user['table_id'].'"><i class="fa fa-edit (alias)" aria-label="Edit"></i></a><span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-type="Notepad Docs" data-target="#delete-modal-set" class="badge bg-red delete-pro-pack-modal" data-id="'.$user['table_id'].'"><i class="fa fa-trash-o" aria-label="Delete"></i></a><span>',
               
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }

   // echo "<pre>"; print_r($records); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}
if (isset($_POST['action']) && !empty($_POST['teacher_code']) && $_POST['action'] == 'assign_student_to_teacher') {

    $result = array();
    $studentidArr = $_POST['student_id'];
    $teacher_code = trim($_POST['teacher_code']);
    
    if (!empty($studentidArr) && !empty($teacher_code)) {
        $sql = query('SELECT firstname,lastname,organization,seat_limit FROM user WHERE teacher="' . $teacher_code. '" and role="teacher"');
        $data = fetch($sql);
        $teachername = $_organization = '';
        if(!empty($data)){
            $teacher_first_name=base64_decode($data['firstname']);
            $teacher_last_name=base64_decode($data['lastname']);
            $_organization = $data['organization'];
            $teachername=base64_encode($teacher_first_name." ".$teacher_last_name);
            $flag = 1;
            if(!empty($data['seat_limit'])){
                $studentsql = query('SELECT count(id) as count FROM user WHERE teacher="' . $teacher_code. '" and role="student"');
				$student_data = fetch($studentsql);
				if(!empty($student_data) && isset($student_data['count'])){
					if($student_data['count'] >= $data['seat_limit']){
						$flag = 0;
					}
				}
            }
            if(!empty($flag)){
                foreach($studentidArr as $studentid){
                    //Good to update token after password reset for security purpose
                    query("UPDATE user set `teacher`='" . $teacher_code . "'  WHERE id=$studentid");                     
                    query("UPDATE user set `teacher_name`='" . $teachername . "', `organization` = '" . $_organization . "'  WHERE id=$studentid");
                }
                $result['status'] = TRUE;
                $result['msg'] = 'Student reassigned successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
            } else {
                $result['status'] = TRUE;
                $result['msg'] = 'Selected teacher has reached their student seat limit. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
            }
        }
        echo json_encode($result);
        exit;    
    } else {
        $result['status'] = false;        
        echo json_encode($result);
        exit;
    }
}
if (isset($_POST['action']) && !empty($_POST['form_data']) && $_POST['action'] == 'add_student_to_teacher') {

    parse_str($_POST['form_data'], $formDataArr);
    $result = array();
    if (!empty($formDataArr)) {

        $teacher_code = !empty($formDataArr['teacher_list']) ? $formDataArr['teacher_list'] : '';
        $email = !empty($_SESSION['User']['email']) ? $_SESSION['User']['email'] : '';
        $license = $_SESSION['User']['license'];
        $_firstname = escapeString($formDataArr['firstname']);
        $_username = escapeString($formDataArr['username']);
        $_lastname = '';

        $_organization = '';
        $_email = $email;
        $_password = escapeString($formDataArr['password']);
        $teachername = '';
        if(!empty($teacher_code)){
            $sql = query('SELECT firstname,lastname,organization,seat_limit FROM user WHERE teacher="' . $teacher_code. '" and role="teacher"');
            $data = fetch($sql);
            if(!empty($data)){
                $flag = 1;
                if(!empty($data['seat_limit'])){
                    $studentsql = query('SELECT count(id) as count FROM user WHERE teacher="' . $teacher_code. '" and role="student"');
					$student_data = fetch($studentsql);
					if(!empty($student_data) && isset($student_data['count'])){
                        if($student_data['count'] >= $data['seat_limit']){
                            $flag = 0;
                        }
                    }
                }
                if(!empty($flag)){
                    $teacher_first_name=base64_decode($data['firstname']);
                    $teacher_last_name=base64_decode($data['lastname']);
                    $_organization = base64_decode($data['organization']);
                    $teachername=base64_encode($teacher_first_name." ".$teacher_last_name);
                    $_username = strtolower($_username);
                    register_user('student', base64_encode($_firstname), base64_encode($_lastname), base64_encode($_username), $_email, $_password, base64_encode($_organization), $license, 0, 0, $teacher_code, false,0,$teachername);
                
                    $result['status'] = TRUE;
                    $result['msg'] = 'Student account was created successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
                } else {
                    $result['status'] = TRUE;
                    $result['msg'] = 'Selected teacher student reach limits. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
                }
            }
        }
        echo json_encode($result);
        exit;    
    } else {
        $result['status'] = false;        
        echo json_encode($result);
        exit;
    }
}
if (isset($_POST['action']) && !empty($_POST['form_data']) && $_POST['action'] == 'check_user_as_admin') {

    parse_str($_POST['form_data'], $formDataArr);
    $result['status'] = false;
    if (!empty($formDataArr)) {

        $email = escapeString($formDataArr['username']);
        $password = md5($formDataArr['password']);
        $license = $formDataArr['license'];
        
        $total_query = query("SELECT is_admin,role,is_lock,license FROM user where (`username` = '" . base64_encode($email) . "' or `email` = '" . base64_encode($email) . "') and `password` = '".$password ."'");
        $total_data = fetch($total_query); 
		if(!empty($total_data)){
			if($total_data['role'] != 'student'){
				
				$multi_teacher_license = !empty($total_data) ? get_license_data($total_data['license']) :'';
				if(!empty($multi_teacher_license) && $multi_teacher_license['no_teacher'] > 1){
					if(!empty($total_data) && isset($total_data['is_admin']) && !empty($total_data['is_admin'])){
						$result['status'] = TRUE;                    
					} else if(!empty($total_data) && isset($total_data['is_lock']) && empty($total_data['is_lock'])){
						$result['status'] = TRUE;                  
					} else{
						$result['status'] = false; 
						$result['msg'] = 'Only Admin users can apply a license update.';						
					}
				} else {   
					if(!empty($multi_teacher_license) && $multi_teacher_license['no_teacher'] == 1){
						$result['status'] = TRUE;                   
					} else {
						$result['status'] = false;
						$result['msg'] = 'Only Admin users can apply a license update.';							
					}
				}
			} else if($total_data['role'] == 'student') {
				$result['status'] = TRUE;           
			} 
		} else {
			$result['status'] = false;
			$result['msg'] = 'Username or password is not found, Please try again.';		
		}
    } else {
        $result['status'] = false;
		$result['msg'] = 'Something went wrong, Please try again!';		
    }
    echo json_encode($result);
    exit;
}



if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_Brail_history') {

   
$Brail_start_date = isset( $_SESSION['BrailDatePickerFrom'] ) ? $_SESSION['BrailDatePickerFrom']: '';
$Brail_end_date = isset( $_SESSION['BrailDatePickerTo'] ) ? $_SESSION['BrailDatePickerTo']: '';

 
if(empty($Brail_start_date) && empty($Brail_end_date)){
   
    $Brail_start_date = isset( $_POST['BrailDatePickerFrom'] ) ? $_POST['BrailDatePickerFrom']: '';
    $Brail_end_date = isset( $_POST['BrailDatePickerTo'] ) ? $_POST['BrailDatePickerTo']: '';
}

global $con, $studentid;
$table_data = array();
$studentid = !empty($student_id) ? $student_id : $studentid;

if (( $Brail_start_date != 0 ) && ( $Brail_end_date != 0 )) {
    
    $Brail_start_date = date('Y-m-d', strtotime($Brail_start_date)) . ' 00:00:00';
    $Brail_end_date = date('Y-m-d', strtotime($Brail_end_date)) . ' 23:59:59';
}
$custom_date =  custom_date($Brail_start_date);

$result = array();
$args = array(
    'id' => $_POST['student_id'],
    'date BETWEEN' =>$Brail_start_date."_".$Brail_end_date,
    'app_condition' => "(`app` LIKE 'Typio-Test' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')"
   );
   
/*SELECT * FROM `log` WHERE ( `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL' ) AND (`file` LIKE 'Typio-BRL' OR `file` LIKE 'Braillio') AND id='78' AND*/
$search_columns = 'title';
$order_by = 'l.id desc';

$where = array();
$groupby = '';
//  echo "<pre>"; print_r($args); exit;

$records = json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
$_average_scale = Brail_average_chart_write($_POST['student_id'], $Brail_start_date, $Brail_end_date);



  foreach($records['data'] as $key_data =>$user_data ){

        $log_Brail_data_value = explode('|', $user_data['data']);
        $records['data'][$key_data]['file'] = $user_data['file'];
        $records['data'][$key_data]['date'] = $user_data['date'];
        $records['data'][$key_data]['wpm'] = $log_Brail_data_value[0];
        $records['data'][$key_data]['acc'] = $log_Brail_data_value[1];
        $records['data'][$key_data]['err'] = $log_Brail_data_value[2];

     }
     //echo "<pre>"; print_r($records['data']); 
   $order_sort = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:"";
   $order_column =isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:"";

   //$column_nm = isset( $_POST['columns'][$order_column]['name'] )$_POST['columns'][$order_column]['name'] :"";
  $column_nm = $_POST['columns'][$order_column]['name'];

   if($order_sort == 'asc'){
        $order_sort_data =SORT_ASC;
   }else{
        $order_sort_data =SORT_DESC;
   }


  $price = array_column($records['data'], $column_nm);
  array_multisort($price, $order_sort_data, $records['data']);

if (!empty($records)) {
    $data  = array();
	$data1 = array();
	$data1 = array(
            'wpm' => $_average_scale['WPM'],
            'acc' => $_average_scale['Accuracy'],
            'combo' => $_average_scale['Combo'],
           
        );
    /*$data[] = array(
            '<span class="">Average<span>',
            '<span class=""><span>',
             '<span class="">' . $_average_scale['WPM'] . '<span>',
            '<span class="">' . $_average_scale['Accuracy'] . '<span>',
            '<span class="">' . $_average_scale['Combo'] . '<span>',
            '<span class=""><span>',
            '<span class=""><span>',
        );*/
   // echo "<pre>"; print_r($records['data']); exit; 
    foreach ($records['data'] as $user) 
    {
        $error_data = isset($user[2])?$user[2]:"0";
        if($custom_date){
            $error_data = isset($user[2])?$user[2]:"0";
        }
        $log_Brail_data_value = explode('|', $user['data']);
        

        /* For expired license not display action button */
        $action = '';
        $log_Brail_data_html ="";

        if ($user['file'] != 'Free Type' && !empty($log_Brail_data_value) && count($log_Brail_data_value) > 3) {   
            $title = ($user['file']) ? $user['file'] : '';   
            $log_Brail_data_html .= '';
            $log_Brail_data_html .= '<span><a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $user['lognr'] . '><i class="fa fa-play"></i></a></span>';
            $log_Brail_data_html .= '';
        }

        
        $data[] = array(
            '<span class="">' . $user['file'] . '<span>',
            '<span class="">' . date('m/d/y', strtotime($user['date'])) . '<span>',
             '<span class="">' . $log_Brail_data_value[0] . '<span>',
            '<span class="">' . $log_Brail_data_value[1] . '%<span>',
            '<span class="">' . $log_Brail_data_value[2] . '<span>',
            '<span class=""><a href="javascript:void(0)" data-type="date-history" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $user['lognr'] . '><i class="fa fa-trash-o"></i></a></span>',
            '<span class="">'.$log_Brail_data_html.'</span>',
        );
    }

    $records['data'] = $data;        
    $records['data1'] = $data1;        
    $records['status'] = 1;

}
// echo "<pre>"; print_r($records); exit;
if (empty($records)) {
    $records['status'] = 0;
}


echo json_encode($records);
exit;
}

if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_Brail_history_kp') {

   
$Brail_start_date = isset( $_SESSION['BrailDatePickerFrom'] ) ? $_SESSION['BrailDatePickerFrom']: '';
$Brail_end_date = isset( $_SESSION['BrailDatePickerTo'] ) ? $_SESSION['BrailDatePickerTo']: '';

 
if(empty($Brail_start_date) && empty($Brail_end_date)){
   
    $Brail_start_date = isset( $_POST['BrailDatePickerFrom'] ) ? $_POST['BrailDatePickerFrom']: '';
    $Brail_end_date = isset( $_POST['BrailDatePickerTo'] ) ? $_POST['BrailDatePickerTo']: '';
}

global $con, $studentid;
$table_data = array();
$studentid = !empty($student_id) ? $student_id : $studentid;

if (( $Brail_start_date != 0 ) && ( $Brail_end_date != 0 )) {
    
    $Brail_start_date = date('Y-m-d', strtotime($Brail_start_date)) . ' 00:00:00';
    $Brail_end_date = date('Y-m-d', strtotime($Brail_end_date)) . ' 23:59:59';
}
$custom_date =  custom_date($Brail_start_date);

$result = array();
$args = array(
    'id' => $_POST['student_id'],
    'date BETWEEN' =>$Brail_start_date."_".$Brail_end_date,
    //'app_condition' => "(`app` LIKE 'Typio-Test' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')"
    'app_condition' => "(`app` LIKE 'Typio-BRL')"
   );
   
/*SELECT * FROM `log` WHERE ( `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL' ) AND (`file` LIKE 'Typio-BRL' OR `file` LIKE 'Braillio') AND id='78' AND*/
$search_columns = 'title';
$order_by = 'l.id desc';

$where = array();
$groupby = '';
//  echo "<pre>"; print_r($args); exit;

//$records = json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
$records = json_datatable('data', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
//$_average_scale = Brail_average_chart_write($_POST['student_id'], $Brail_start_date, $Brail_end_date);
$_average_scale = Brail_average_chart_write_kp($_POST['student_id'], $Brail_start_date, $Brail_end_date);



  foreach($records['data'] as $key_data =>$user_data ){

        $log_Brail_data_value = explode('|', $user_data['data']);
        $records['data'][$key_data]['file'] = $user_data['file'];
        $records['data'][$key_data]['date'] = $user_data['date'];
        $records['data'][$key_data]['wpm'] = $log_Brail_data_value[0];
        $records['data'][$key_data]['acc'] = $log_Brail_data_value[1];
        $records['data'][$key_data]['err'] = $log_Brail_data_value[2];

     }
     //echo "<pre>"; print_r($records['data']); 
   $order_sort = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:"";
   $order_column =isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:"";

   //$column_nm = isset( $_POST['columns'][$order_column]['name'] )$_POST['columns'][$order_column]['name'] :"";
  $column_nm = $_POST['columns'][$order_column]['name'];

   if($order_sort == 'asc'){
        $order_sort_data =SORT_ASC;
   }else{
        $order_sort_data =SORT_DESC;
   }


  $price = array_column($records['data'], $column_nm);
  array_multisort($price, $order_sort_data, $records['data']);

if (!empty($records)) {
    $data  = array();
	$data1 = array();
	$data1 = array(
            'wpm' => $_average_scale['WPM'],
            'acc' => $_average_scale['Accuracy'],
            'combo' => $_average_scale['Combo'],
           
        );
    /*$data[] = array(
            '<span class="">Average<span>',
            '<span class=""><span>',
             '<span class="">' . $_average_scale['WPM'] . '<span>',
            '<span class="">' . $_average_scale['Accuracy'] . '<span>',
            '<span class="">' . $_average_scale['Combo'] . '<span>',
            '<span class=""><span>',
            '<span class=""><span>',
        );*/
   // echo "<pre>"; print_r($records['data']); exit; 
    foreach ($records['data'] as $user) 
    {
        $error_data = isset($user[2])?$user[2]:"0";
        if($custom_date){
            $error_data = isset($user[2])?$user[2]:"0";
        }
        $log_Brail_data_value = explode('|', $user['data']);
        

        /* For expired license not display action button */
        $action = '';
        $log_Brail_data_html ="";

        if ($user['file'] != 'Free Type' && !empty($log_Brail_data_value) && count($log_Brail_data_value) > 3) {   
            $title = ($user['file']) ? $user['file'] : '';   
            $log_Brail_data_html .= '';
            $log_Brail_data_html .= '<span><a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $user['lognr'] . '><i class="fa fa-play"></i></a></span>';
            $log_Brail_data_html .= '';
        }

        
        $data[] = array(
            '<span class="">' . $user['file'] . '<span>',
            '<span class="">' . date('m/d/y', strtotime($user['date'])) . '<span>',
             '<span class="">' . $log_Brail_data_value[0] . '<span>',
            '<span class="">' . $log_Brail_data_value[1] . '%<span>',
            '<span class="">' . $log_Brail_data_value[2] . '<span>',
            '<span class=""><a href="javascript:void(0)" data-type="date-history" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $user['lognr'] . '><i class="fa fa-trash-o"></i></a></span>',
            '<span class="">'.$log_Brail_data_html.'</span>',
        );
    }

    $records['data'] = $data;        
    $records['data1'] = $data1;        
    $records['status'] = 1;

}
// echo "<pre>"; print_r($records); exit;
if (empty($records)) {
    $records['status'] = 0;
}


echo json_encode($records);
exit;
}





function Brail_average_chart_write($student_id = 0, $Brail_start_date = 0, $Brail_end_date = 0) {
     global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;
	$_average_scale = array();

    $log_data_table = "log";
    $log_data_table_wpm_title = ' WPM';
    $log_data_table_accuracy_title = '% Accuracy';
    
    $custom_date =  custom_date($Brail_start_date);
    $log_data_table_combo_title = " Combo";
    if($custom_date){
        $log_data_table_combo_title = " Errors";
    }
    
    $log_data_table_wpm_value = 0;
    $log_data_table_accuracy_value = 0;
    $log_data_table_combo_value = 0;

    $log_Brail_query = "SELECT * FROM `" . $log_data_table . "` WHERE `app` LIKE 'Typio-BRL' AND id='" . $studentid . "'";
    if (($Brail_start_date != 0) && ($Brail_end_date != 0)) {
        $log_Brail_query .= " AND `date` BETWEEN '" . $Brail_start_date . "' AND '" . $Brail_end_date . "' ";
    }

    $log_Brail_data = mysqli_query($con, $log_Brail_query);
    $log_Brail_data_count = 0;
    $log_Brail_data_html = '';

    while ($log_Brail_data_row = mysqli_fetch_assoc($log_Brail_data)) {
        $log_Brail_data_count++;
        $log_Brail_data_value = explode('|', $log_Brail_data_row['data']);
        $log_data_table_wpm_value += ($log_Brail_data_value[0]) ? $log_Brail_data_value[0] : 0;
        $log_data_table_accuracy_value += ($log_Brail_data_value[1]) ? $log_Brail_data_value[1] : 0;
        $log_data_table_combo_value += ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : 0;
    }
    $combo_data = $log_data_table_combo_value;
    if($custom_date){
        $combo_data = $log_data_table_combo_value;
    }
    $_average_scale['start_date'] = !empty($Brail_start_date) ? date('m/d/y', strtotime($Brail_start_date)) : '';
    $_average_scale['end_date'] = !empty($Brail_end_date)? date('m/d/y', strtotime($Brail_end_date)) : '';
    $_average_scale['WPM'] = !empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_Brail_data_count)) : '0';
    $_average_scale['Accuracy'] = !empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_Brail_data_count)) : '0' ;
    $_average_scale['Combo'] = !empty($combo_data) ? round(($combo_data / $log_Brail_data_count)) : '0' ;

    return $_average_scale;
}
function Brail_average_chart_write_kp($student_id = 0, $Brail_start_date = 0, $Brail_end_date = 0) {
     global $con, $studentid;

    $studentid = !empty($student_id) ? $student_id : $studentid;
	$_average_scale = array();

    //$log_data_table = "log";
    $log_data_table = "data";
    $log_data_table_wpm_title = ' WPM';
    $log_data_table_accuracy_title = '% Accuracy';
    
    $custom_date =  custom_date($Brail_start_date);
    $log_data_table_combo_title = " Combo";
    if($custom_date){
        $log_data_table_combo_title = " Errors";
    }
    
    $log_data_table_wpm_value = 0;
    $log_data_table_accuracy_value = 0;
    $log_data_table_combo_value = 0;

    $log_Brail_query = "SELECT * FROM `" . $log_data_table . "` WHERE `app` LIKE 'Typio-BRL' AND id='" . $studentid . "'";
    if (($Brail_start_date != 0) && ($Brail_end_date != 0)) {
        $log_Brail_query .= " AND `date` BETWEEN '" . $Brail_start_date . "' AND '" . $Brail_end_date . "' ";
    }

    $log_Brail_data = mysqli_query($con, $log_Brail_query);
    $log_Brail_data_count = 0;
    $log_Brail_data_html = '';

    while ($log_Brail_data_row = mysqli_fetch_assoc($log_Brail_data)) {
        $log_Brail_data_count++;
        $log_Brail_data_value = explode('|', $log_Brail_data_row['data']);
        $log_data_table_wpm_value += ($log_Brail_data_value[0]) ? $log_Brail_data_value[0] : 0;
        $log_data_table_accuracy_value += ($log_Brail_data_value[1]) ? $log_Brail_data_value[1] : 0;
        $log_data_table_combo_value += ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : 0;
    }
    $combo_data = $log_data_table_combo_value;
    if($custom_date){
        $combo_data = $log_data_table_combo_value;
    }
    $_average_scale['start_date'] = !empty($Brail_start_date) ? date('m/d/y', strtotime($Brail_start_date)) : '';
    $_average_scale['end_date'] = !empty($Brail_end_date)? date('m/d/y', strtotime($Brail_end_date)) : '';
    $_average_scale['WPM'] = !empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_Brail_data_count)) : '0';
    $_average_scale['Accuracy'] = !empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_Brail_data_count)) : '0' ;
    $_average_scale['Combo'] = !empty($combo_data) ? round(($combo_data / $log_Brail_data_count)) : '0' ;

    return $_average_scale;
}

function Typio_all_time_student_data($userId){
	
	global $con;
	
	$log_data_table = "log";
    
    /*$log_typio_query = "SELECT `date` FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' ) AND id='" . $userId . "'  ORDER BY date ASC LIMIT 1";*/
	$log_typio_query = "SELECT date FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' ) AND `id` = " . $userId . " AND `date` <> '0000-00-00' ORDER BY date ASC LIMIT 1";

	$log_typio_data = mysqli_query($con, $log_typio_query);
	 
	 $log_Typio_data_row_date = [];
	 
	 while ($log_Typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
       
        $log_Typio_data_row_date[] = $log_Typio_data_row['date'];
      
    }
	
	return $log_Typio_data_row_date[0];
	
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_typio_history') {

    
    $typio_start_date = isset( $_SESSION['typioDatePickerFrom'] ) ? $_SESSION['typioDatePickerFrom']: '';
    $typio_end_date = isset( $_SESSION['typioDatePickerTo'] ) ? $_SESSION['typioDatePickerTo']: '';

    if(empty($typio_start_date) && empty($typio_end_date)){
       
        $typio_start_date = isset( $_POST['typioDatePickerFrom'] ) ? $_POST['typioDatePickerFrom']: '';
        $typio_end_date = isset( $_POST['typioDatePickerTo'] ) ? $_POST['typioDatePickerTo']: '';
    }

    global $con, $studentid;
    $table_data = array();
    $studentid = !empty($student_id) ? $student_id : $studentid;
    
    if (( $typio_start_date != 0 ) && ( $typio_end_date != 0 )) {
        
        $typio_start_date = date('Y-m-d', strtotime($typio_start_date)) . ' 00:00:00';
        $typio_end_date = date('Y-m-d', strtotime($typio_end_date)) . ' 23:59:59';
    }
    $custom_date =  custom_date($typio_start_date);

    $result = array();
    $args = array(
        'id' => $_POST['student_id'],
        'date BETWEEN' =>$typio_start_date."_".$typio_end_date,
        'app_condition' => "(`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Test' OR `app` LIKE 'Typio-Journey')"
    );
    /*'app_condition' => "(`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Test' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')"*/
    $search_columns = 'title';
    //$order_by = 'l.id desc';
     $order_by = 'l.date desc';

    $where = array();
    $groupby = '';
  //  echo "<pre>"; print_r($args); exit;

    //$records = json_datatable('data', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    $records = json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    $_average_scale = Typio_average_chart_write($_POST['student_id'], $typio_start_date, $typio_end_date);

      foreach($records['data'] as $key_data =>$user_data ){

            $log_typio_data_value = explode('|', $user_data['data']);
            $records['data'][$key_data]['file'] = $user_data['file'];
            $records['data'][$key_data]['date'] = $user_data['date'];
            $records['data'][$key_data]['wpm'] = $log_typio_data_value[0];
            $records['data'][$key_data]['acc'] = $log_typio_data_value[1];
            $records['data'][$key_data]['err'] = $log_typio_data_value[2];

         }
         //echo "<pre>"; print_r($records['data']); 
       $order_sort = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:"";
       $order_column =isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:"";

       //$column_nm = isset( $_POST['columns'][$order_column]['name'] )$_POST['columns'][$order_column]['name'] :"";
      $column_nm = $_POST['columns'][$order_column]['name'];

       if($order_sort == 'asc'){
            $order_sort_data =SORT_ASC;
       }else{
            $order_sort_data =SORT_DESC;
       }

    
      $price = array_column($records['data'], $column_nm);
      array_multisort($price, $order_sort_data, $records['data']);

    if (!empty($records)) {
        $data1 = array();
        $data = array();
       
			$data1 = array("wpm"=>$_average_scale['WPM'],"accu"=>$_average_scale['Accuracy'],"combo"=>$_average_scale['Combo']);
			
       // echo "<pre>"; print_r($records['data']); exit; 
        foreach ($records['data'] as $user) 
        {
            $error_data = isset($user[2])?$user[2]:"0";
            if($custom_date){
                $error_data = isset($user[2])?$user[2]:"0";
            }
            $log_typio_data_value = explode('|', $user['data']);
            

            $action = '';
            $log_typio_data_html ="";

            if ($user['file'] != 'Free Type' && !empty($log_typio_data_value) && count($log_typio_data_value) > 3) {   
                $title = ($user['file']) ? $user['file'] : '';   
                $log_typio_data_html .= '';
                //$log_typio_data_html .= '<span><a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $user['lognr'] . '><i class="fa fa-play"></i></a></span>';
                $log_typio_data_html .= '';
            }

            
            $data[] = array(
                '<span class="">' . $user['file'] . '<span>',
                '<span class="">' . date('m/d/y', strtotime($user['date'])) . '<span>',
                '<span class="">' . $log_typio_data_value[0] . '<span>',
                '<span class="">' . $log_typio_data_value[1] . '%<span>',
                '<span class="">' . $log_typio_data_value[2] . '<span>',
                
                '<span class=""><a href="javascript:void(0)" data-type="date-history" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $user['lognr'] . '><i class="fa fa-trash-o"></i></a></span>',
                '<span class="">'.$log_typio_data_html.'</span>',
            );
        }
		$log_data_table = "log";
		//$log_data_table = "data";
		$log_data_table_wpm_value = 0;
		$log_data_table_accuracy_value = 0;
		$log_data_table_combo_value = 0;
		$log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' ) AND id='" . $studentid . "'";
		/*$log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";*/
		if (($typio_start_date != 0) && ($typio_end_date != 0)) {
			 
			$log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
		}

		$log_typio_data = mysqli_query($con, $log_typio_query);
		$log_typio_data_count = 0;
		$log_typio_data_html = '';
		while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
			$log_typio_data_count++;
			$log_typio_data_value = explode('|', $log_typio_data_row['data']);
			$log_data_table_wpm_value += ($log_typio_data_value[0]) ? $log_typio_data_value[0] : 0;
			$log_data_table_accuracy_value += ($log_typio_data_value[1]) ? $log_typio_data_value[1] : 0;
			$log_data_table_combo_value += ($log_typio_data_value[2]) ? $log_typio_data_value[2] : 0;
		}
		$combo_data = $log_data_table_combo_value;
		if($custom_date){
			$combo_data = $log_data_table_combo_value;
		}
		
		$data_wpm 	.= !empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0';
		$data_acc 	.= !empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ;
		$data_count .= !empty($combo_data) ? round(($combo_data / $log_typio_data_count)) : '0' ;
		$data2 = array("wpm"=>$data_wpm,"accu"=>$data_acc,"combo"=>$data_count);
		
        $records['data'] 	= $data;        
        $records['data1'] 	= $data1;
        $records['data2'] 	= $data2;     
        $records['status'] 	= 1;

    }

    //echo "<pre>"; print_r($typio_start_date); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_typio_history_kp') {

    
    //$typio_start_date 	= isset( $_SESSION['typioDatePickerFrom'] ) ? $_SESSION['typioDatePickerFrom']: '';
    //$typio_end_date 	= isset( $_SESSION['typioDatePickerTo'] ) ? $_SESSION['typioDatePickerTo']: '';



    if(empty($typio_start_date) && empty($typio_end_date)){
       
        $typio_start_date 	= isset( $_POST['typioDatePickerFrom'] ) ? $_POST['typioDatePickerFrom']: '';
        $typio_end_date 	= isset( $_POST['typioDatePickerTo'] ) ? $_POST['typioDatePickerTo']: '';
    }

    global $con, $studentid;
    $table_data = array();
    $studentid 	= !empty($student_id) ? $student_id : $studentid;
    
    if (( $typio_start_date != 0 ) && ( $typio_end_date != 0 )) {
        
        $typio_start_date 	= date('Y-m-d', strtotime($typio_start_date)) . ' 00:00:00';
        $typio_end_date 	= date('Y-m-d', strtotime($typio_end_date)) . ' 23:59:59';
    }
		$custom_date 		= custom_date($typio_start_date);

    $result = array();
    $args = array(
        'id' => $_POST['student_id'],
        //'date BETWEEN' =>$typio_start_date."_".$typio_end_date,
        'app_condition' => "(`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')"
    );
    
    $search_columns = 'title';
    $order_by = 'l.id desc';

    $where = array();
    $groupby = '';
	// echo "<pre>"; print_r($args); exit;
	// echo "<pre>"; print_r($recordsdata); exit;
   $recordsdata = json_datatable('data', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());

    $records 	= json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
	
    $_average_scale = Typio_average_chart_write($_POST['student_id'], $typio_start_date, $typio_end_date);

      foreach($records['data'] as $key_data =>$user_data ){

            $log_typio_data_value = explode('|', $user_data['data']);
            $records['data'][$key_data]['file'] = $user_data['file'];
            $records['data'][$key_data]['date'] = $user_data['date'];
            $records['data'][$key_data]['wpm'] = $log_typio_data_value[0];
            $records['data'][$key_data]['acc'] = $log_typio_data_value[1];
            $records['data'][$key_data]['err'] = $log_typio_data_value[2];

         }
         //echo "<pre>"; print_r($records['data']); 
       $order_sort = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:"";
       $order_column =isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:"";

       //$column_nm = isset( $_POST['columns'][$order_column]['name'] )$_POST['columns'][$order_column]['name'] :"";
      $column_nm = $_POST['columns'][$order_column]['name'];

       if($order_sort == 'asc'){
            $order_sort_data =SORT_ASC;
       }else{
            $order_sort_data =SORT_DESC;
       }

    
      $price = array_column($records['data'], $column_nm);
      array_multisort($price, $order_sort_data, $records['data']);

    if (!empty($records)) {
        $data1 = array();
        $data = array();
       
			$data1 = array("wpm"=>$_average_scale['WPM'],"accu"=>$_average_scale['Accuracy'],"combo"=>$_average_scale['Combo']);
			$exist_data= '';
			$firstCheck = true;
       // echo "<pre>"; print_r($records['data']); exit; 
        foreach ($records['data'] as $user) 
        {
            $error_data = isset($user[2])?$user[2]:"0";
            if($custom_date){
                $error_data = isset($user[2])?$user[2]:"0";
            }
            $log_typio_data_value = explode('|', $user['data']);
            

            $action = '';
            $log_typio_data_html ="";

            if ($user['file'] != 'Free Type' && !empty($log_typio_data_value) && count($log_typio_data_value) > 3) {   
                $title = ($user['file']) ? $user['file'] : '';   
                $log_typio_data_html .= '';
                //$log_typio_data_html .= '<span><a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $user['lognr'] . '><i class="fa fa-play"></i></a></span>';
                $log_typio_data_html .= '';
            }
			
			
			$exist_data = ADMIN_URL.'img/rec.png';
			$exist_img = '<img src="' . $exist_data . '" class="typio_img_kp" >';	
			$found = false; 			
			foreach ($recordsdata['data'] as $data_user) {
				if ($data_user['file'] == $user['file']) {
					$exist_data = ADMIN_URL.'img/check.png';
					$exist_img = '<img src="' . $exist_data . '" class="typio_img_kp" >';
					$found = true;
					break; 
				}
			}
			
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
		$log_data_table = "log";
		//$log_data_table = "data";
		$log_data_table_wpm_value = 0;
		$log_data_table_accuracy_value = 0;
		$log_data_table_combo_value = 0;
		$log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' ) AND id='" . $studentid . "'";
		if (($typio_start_date != 0) && ($typio_end_date != 0)) {
			 
			$log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
		}

		$log_typio_data = mysqli_query($con, $log_typio_query);
		$log_typio_data_count = 0;
		$log_typio_data_html = '';
		while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
			$log_typio_data_count++;
			$log_typio_data_value = explode('|', $log_typio_data_row['data']);
			$log_data_table_wpm_value += ($log_typio_data_value[0]) ? $log_typio_data_value[0] : 0;
			$log_data_table_accuracy_value += ($log_typio_data_value[1]) ? $log_typio_data_value[1] : 0;
			$log_data_table_combo_value += ($log_typio_data_value[2]) ? $log_typio_data_value[2] : 0;
		}
		$combo_data = $log_data_table_combo_value;
		if($custom_date){
			$combo_data = $log_data_table_combo_value;
		}
		
		$data_wpm 	.= !empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0';
		$data_acc 	.= !empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ;
		$data_count .= !empty($combo_data) ? round(($combo_data / $log_typio_data_count)) : '0' ;
		$data2 = array("wpm"=>$data_wpm,"accu"=>$data_acc,"combo"=>$data_count);
		
        $records['data'] 	= $data;        
        $records['data1'] 	= $data1;
        $records['data2'] 	= $data2;     
        $records['status'] 	= 1;

    }

   // echo "<pre>"; print_r($typio_start_date); 
    //echo "<pre>"; print_r($typio_end_date); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}

// AJAX handler for keyboard progress data
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_keyboard_progress_data') {
//if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_keyboard_progress_data') {
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : '';
    $app_type = isset($_POST['app_type']) ? $_POST['app_type'] : 'Typio-Journey';
   
   // Debug logging
   // error_log("AJAX keyboard progress data called with student_id: $student_id, app_type: $app_type");
    
    $progress_data = displayKeyboardProgressData($student_id, $app_type);
    
    // Debug logging
    //error_log("Progress data result: " . print_r($progress_data, true));
    
    echo json_encode(array(
        'status' => '200',
        'html' => $progress_data['html'],
        'total_lessons' => $progress_data['total_lessons'],
        'completed_lessons' => $progress_data['completed_lessons'],
        'next_lesson' => $progress_data['next_lesson'],
        'keys_progress' => $progress_data['keys_progress']
    ));
    exit;
}
/**/
// AJAX handler for deleting lesson
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_delete_lesson') {
    $lesson_id = isset($_POST['lesson_id']) ? $_POST['lesson_id'] : '';
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : '';
    
    if (!empty($lesson_id) && !empty($student_id)) {
        $delete_query = "DELETE FROM `data` WHERE `id` = '$lesson_id' AND `id` = '$student_id'";
        $delete_result = mysqli_query($con, $delete_query);
        
        if ($delete_result) {
            echo json_encode(array('status' => '200', 'message' => 'Lesson deleted successfully'));
        } else {
            echo json_encode(array('status' => '500', 'message' => 'Error deleting lesson'));
        }
    } else {
        echo json_encode(array('status' => '400', 'message' => 'Invalid parameters'));
    }
    exit;
}

// AJAX handler for checking which app types have data
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_check_app_types') {
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : '';
    
    if (!empty($student_id)) {
        // Check for Typio-Journey data
        $journey_query = "SELECT COUNT(*) as count FROM `data` WHERE `app` = 'Typio-Journey' AND `id` = '$student_id'";
        $journey_result = mysqli_query($con, $journey_query);
        $journey_count = mysqli_fetch_assoc($journey_result)['count'];
        
        // Check for Typio-OL data
        $ol_query = "SELECT COUNT(*) as count FROM `data` WHERE `app` = 'Typio-OL' AND `id` = '$student_id'";
        $ol_result = mysqli_query($con, $ol_query);
        $ol_count = mysqli_fetch_assoc($ol_result)['count'];
        
        echo json_encode(array(
            'status' => '200',
            'has_journey' => $journey_count > 0,
            'has_ol' => $ol_count > 0
        ));
    } else {
        echo json_encode(array('status' => '400', 'message' => 'Invalid student ID'));
    }
    exit;
}

/*
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_typio_history_kp') {

    
    $typio_start_date = isset( $_SESSION['typioDatePickerFrom'] ) ? $_SESSION['typioDatePickerFrom']: '';
    $typio_end_date = isset( $_SESSION['typioDatePickerTo'] ) ? $_SESSION['typioDatePickerTo']: '';

    if(empty($typio_start_date) && empty($typio_end_date)){
       
        $typio_start_date = isset( $_POST['typioDatePickerFrom'] ) ? $_POST['typioDatePickerFrom']: '';
        $typio_end_date = isset( $_POST['typioDatePickerTo'] ) ? $_POST['typioDatePickerTo']: '';
    }

    global $con, $studentid;
    $table_data = array();
    $studentid = !empty($student_id) ? $student_id : $studentid;
    
    if (( $typio_start_date != 0 ) && ( $typio_end_date != 0 )) {
        
        $typio_start_date = date('Y-m-d', strtotime($typio_start_date)) . ' 00:00:00';
        $typio_end_date = date('Y-m-d', strtotime($typio_end_date)) . ' 23:59:59';
    }
    $custom_date =  custom_date($typio_start_date);

    $result = array();
    $args = array(
        'id' => $_POST['student_id'],
        'date BETWEEN' =>$typio_start_date."_".$typio_end_date,
        'app_condition' => "(`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Test' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')"
    );
    
    $search_columns = 'title';
    $order_by = 'l.id desc';

    $where = array();
    $groupby = '';
  //  echo "<pre>"; print_r($args); exit;

    //$records = json_datatable('data', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    $records = json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    $_average_scale = Typio_average_chart_write($_POST['student_id'], $typio_start_date, $typio_end_date);

      foreach($records['data'] as $key_data =>$user_data ){

            $log_typio_data_value = explode('|', $user_data['data']);
            $records['data'][$key_data]['file'] = $user_data['file'];
            $records['data'][$key_data]['date'] = $user_data['date'];
            $records['data'][$key_data]['wpm'] = $log_typio_data_value[0];
            $records['data'][$key_data]['acc'] = $log_typio_data_value[1];
            $records['data'][$key_data]['err'] = $log_typio_data_value[2];

         }
         //echo "<pre>"; print_r($records['data']); 
       $order_sort = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:"";
       $order_column =isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:"";

       //$column_nm = isset( $_POST['columns'][$order_column]['name'] )$_POST['columns'][$order_column]['name'] :"";
      $column_nm = $_POST['columns'][$order_column]['name'];

       if($order_sort == 'asc'){
            $order_sort_data =SORT_ASC;
       }else{
            $order_sort_data =SORT_DESC;
       }

    
      $price = array_column($records['data'], $column_nm);
      array_multisort($price, $order_sort_data, $records['data']);

    if (!empty($records)) {
        $data1 = array();
        $data = array();
       
			$data1 = array("wpm"=>$_average_scale['WPM'],"accu"=>$_average_scale['Accuracy'],"combo"=>$_average_scale['Combo']);
			
       // echo "<pre>"; print_r($records['data']); exit; 
        foreach ($records['data'] as $user) 
        {
            $error_data = isset($user[2])?$user[2]:"0";
            if($custom_date){
                $error_data = isset($user[2])?$user[2]:"0";
            }
            $log_typio_data_value = explode('|', $user['data']);
            

            $action = '';
            $log_typio_data_html ="";

            if ($user['file'] != 'Free Type' && !empty($log_typio_data_value) && count($log_typio_data_value) > 3) {   
                $title = ($user['file']) ? $user['file'] : '';   
                $log_typio_data_html .= '';
                //$log_typio_data_html .= '<span><a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $user['lognr'] . '><i class="fa fa-play"></i></a></span>';
                $log_typio_data_html .= '';
            }

            
            $data[] = array(
                '<span class="">' . $user['file'] . '<span>',
                '<span class="">' . date('m/d/y', strtotime($user['date'])) . '<span>',
                '<span class="">' . $log_typio_data_value[0] . '<span>',
                '<span class="">' . $log_typio_data_value[1] . '%<span>',
                '<span class="">' . $log_typio_data_value[2] . '<span>',
                '<span class="">gfgf<span>',
                '<span class=""><a href="javascript:void(0)" data-type="date-history" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $user['lognr'] . '><i class="fa fa-trash-o"></i></a></span>',
                '<span class="">'.$log_typio_data_html.'</span>',
            );
        }
		$log_data_table = "log";
		//$log_data_table = "data";
		$log_data_table_wpm_value = 0;
		$log_data_table_accuracy_value = 0;
		$log_data_table_combo_value = 0;
		$log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";
		if (($typio_start_date != 0) && ($typio_end_date != 0)) {
			 
			$log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
		}

		$log_typio_data = mysqli_query($con, $log_typio_query);
		$log_typio_data_count = 0;
		$log_typio_data_html = '';
		while ($log_typio_data_row = mysqli_fetch_assoc($log_typio_data)) {
			$log_typio_data_count++;
			$log_typio_data_value = explode('|', $log_typio_data_row['data']);
			$log_data_table_wpm_value += ($log_typio_data_value[0]) ? $log_typio_data_value[0] : 0;
			$log_data_table_accuracy_value += ($log_typio_data_value[1]) ? $log_typio_data_value[1] : 0;
			$log_data_table_combo_value += ($log_typio_data_value[2]) ? $log_typio_data_value[2] : 0;
		}
		$combo_data = $log_data_table_combo_value;
		if($custom_date){
			$combo_data = $log_data_table_combo_value;
		}
		
		$data_wpm 	.= !empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0';
		$data_acc 	.= !empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ;
		$data_count .= !empty($combo_data) ? floor(($combo_data / $log_typio_data_count)) : '0' ;
		$data2 = array("wpm"=>$data_wpm,"accu"=>$data_acc,"combo"=>$data_count);
		
        $records['data'] 	= $data;        
        $records['data1'] 	= $data1;
        $records['data2'] 	= $data2;     
        $records['status'] 	= 1;

    }

    //echo "<pre>"; print_r($typio_start_date); exit;

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}
*/


if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_brail_history') {

    
 $Brail_start_date = isset( $_SESSION['BrailDatePickerFrom'] ) ? $_SESSION['BrailDatePickerFrom']: '';
$Brail_end_date = isset( $_SESSION['BrailDatePickerTo'] ) ? $_SESSION['BrailDatePickerTo']: '';

 
if(empty($Brail_start_date) && empty($Brail_end_date)){
   
    $Brail_start_date = isset( $_POST['BrailDatePickerFrom'] ) ? $_POST['BrailDatePickerFrom']: '';
    $Brail_end_date = isset( $_POST['BrailDatePickerTo'] ) ? $_POST['BrailDatePickerTo']: '';
}

global $con, $studentid;
$table_data = array();
$studentid = !empty($student_id) ? $student_id : $studentid;

if (( $Brail_start_date != 0 ) && ( $Brail_end_date != 0 )) {
    
    $Brail_start_date = date('Y-m-d', strtotime($Brail_start_date)) . ' 00:00:00';
    $Brail_end_date = date('Y-m-d', strtotime($Brail_end_date)) . ' 23:59:59';
}
$custom_date =  custom_date($Brail_start_date);

$result = array();
$args = array(
    'id' => $_POST['student_id'],
    'date BETWEEN' =>$Brail_start_date."_".$Brail_end_date,
    'app_condition' => "(`app` LIKE 'Typio-Test' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')"
   );
   
/*SELECT * FROM `log` WHERE ( `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL' ) AND (`file` LIKE 'Typio-BRL' OR `file` LIKE 'Braillio') AND id='78' AND*/
$search_columns = 'title';
$order_by = 'l.id desc';

$where = array();
$groupby = '';
//  echo "<pre>"; print_r($args); exit;

$records = json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
$_average_scale = Brail_average_chart_write($_POST['student_id'], $Brail_start_date, $Brail_end_date);



  foreach($records['data'] as $key_data =>$user_data ){

        $log_Brail_data_value = explode('|', $user_data['data']);
        $records['data'][$key_data]['file'] = $user_data['file'];
        $records['data'][$key_data]['date'] = $user_data['date'];
        $records['data'][$key_data]['wpm'] = $log_Brail_data_value[0];
        $records['data'][$key_data]['acc'] = $log_Brail_data_value[1];
        $records['data'][$key_data]['err'] = $log_Brail_data_value[2];

     }
     //echo "<pre>"; print_r($records['data']); 
   $order_sort = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:"";
   $order_column =isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:"";

   //$column_nm = isset( $_POST['columns'][$order_column]['name'] )$_POST['columns'][$order_column]['name'] :"";
  $column_nm = $_POST['columns'][$order_column]['name'];

   if($order_sort == 'asc'){
        $order_sort_data =SORT_ASC;
   }else{
        $order_sort_data =SORT_DESC;
   }


  $price = array_column($records['data'], $column_nm);
  array_multisort($price, $order_sort_data, $records['data']);

if (!empty($records)) {
    $data  = array();
	$data1 = array();
	$data1 = array(
            'wpm' => $_average_scale['WPM'],
            'acc' => $_average_scale['Accuracy'],
            'combo' => $_average_scale['Combo'],
           
        );
    /*$data[] = array(
            '<span class="">Average<span>',
            '<span class=""><span>',
             '<span class="">' . $_average_scale['WPM'] . '<span>',
            '<span class="">' . $_average_scale['Accuracy'] . '<span>',
            '<span class="">' . $_average_scale['Combo'] . '<span>',
            '<span class=""><span>',
            '<span class=""><span>',
        );*/
   // echo "<pre>"; print_r($records['data']); exit; 
    foreach ($records['data'] as $user) 
    {
        $error_data = isset($user[2])?$user[2]:"0";
        if($custom_date){
            $error_data = isset($user[2])?$user[2]:"0";
        }
        $log_Brail_data_value = explode('|', $user['data']);
        

        /* For expired license not display action button */
        $action = '';
        $log_Brail_data_html ="";

        if ($user['file'] != 'Free Type' && !empty($log_Brail_data_value) && count($log_Brail_data_value) > 3) {   
            $title = ($user['file']) ? $user['file'] : '';   
            $log_Brail_data_html .= '';
            $log_Brail_data_html .= '<span><a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $user['lognr'] . '><i class="fa fa-play"></i></a></span>';
            $log_Brail_data_html .= '';
        }

        
        $data[] = array(
            '<span class="">' . $user['file'] . '<span>',
            '<span class="">' . date('m/d/y', strtotime($user['date'])) . '<span>',
             '<span class="">' . $log_Brail_data_value[0] . '<span>',
            '<span class="">' . $log_Brail_data_value[1] . '%<span>',
            '<span class="">' . $log_Brail_data_value[2] . '<span>',
            '<span class=""><a href="javascript:void(0)" data-type="date-history" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $user['lognr'] . '><i class="fa fa-trash-o"></i></a></span>',
            '<span class="">'.$log_Brail_data_html.'</span>',
        );
    }

    $records['data'] = $data;        
    $records['data1'] = $data1;        
    $records['status'] = 1;

}
// echo "<pre>"; print_r($records); exit;
if (empty($records)) {
    $records['status'] = 0;
}


echo json_encode($records);
exit;

}

if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_student_brail_history_kp') {

    
 $Brail_start_date = isset( $_SESSION['BrailDatePickerFrom'] ) ? $_SESSION['BrailDatePickerFrom']: '';
$Brail_end_date = isset( $_SESSION['BrailDatePickerTo'] ) ? $_SESSION['BrailDatePickerTo']: '';

 
if(empty($Brail_start_date) && empty($Brail_end_date)){
   
    $Brail_start_date = isset( $_POST['BrailDatePickerFrom'] ) ? $_POST['BrailDatePickerFrom']: '';
    $Brail_end_date = isset( $_POST['BrailDatePickerTo'] ) ? $_POST['BrailDatePickerTo']: '';
}

global $con, $studentid;
$table_data = array();
$studentid = !empty($student_id) ? $student_id : $studentid;

if (( $Brail_start_date != 0 ) && ( $Brail_end_date != 0 )) {
    
    $Brail_start_date = date('Y-m-d', strtotime($Brail_start_date)) . ' 00:00:00';
    $Brail_end_date = date('Y-m-d', strtotime($Brail_end_date)) . ' 23:59:59';
}
$custom_date =  custom_date($Brail_start_date);

$result = array();
$args = array(
    'id' => $_POST['student_id'],
    'date BETWEEN' =>$Brail_start_date."_".$Brail_end_date,
    'app_condition' => "(`app` LIKE 'Typio-Test' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')"
   );
   
/*SELECT * FROM `log` WHERE ( `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL' ) AND (`file` LIKE 'Typio-BRL' OR `file` LIKE 'Braillio') AND id='78' AND*/
$search_columns = 'title';
$order_by = 'l.id desc';

$where = array();
$groupby = '';
//  echo "<pre>"; print_r($args); exit;

$records = json_datatable('log', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
//$_average_scale = Brail_average_chart_write($_POST['student_id'], $Brail_start_date, $Brail_end_date);
$_average_scale = Brail_average_chart_write_kp($_POST['student_id'], $Brail_start_date, $Brail_end_date);



  foreach($records['data'] as $key_data =>$user_data ){

        $log_Brail_data_value = explode('|', $user_data['data']);
        $records['data'][$key_data]['file'] = $user_data['file'];
        $records['data'][$key_data]['date'] = $user_data['date'];
        $records['data'][$key_data]['wpm'] = $log_Brail_data_value[0];
        $records['data'][$key_data]['acc'] = $log_Brail_data_value[1];
        $records['data'][$key_data]['err'] = $log_Brail_data_value[2];

     }
     //echo "<pre>"; print_r($records['data']); 
   $order_sort = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:"";
   $order_column =isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:"";

   //$column_nm = isset( $_POST['columns'][$order_column]['name'] )$_POST['columns'][$order_column]['name'] :"";
  $column_nm = $_POST['columns'][$order_column]['name'];

   if($order_sort == 'asc'){
        $order_sort_data =SORT_ASC;
   }else{
        $order_sort_data =SORT_DESC;
   }


  $price = array_column($records['data'], $column_nm);
  array_multisort($price, $order_sort_data, $records['data']);

if (!empty($records)) {
    $data  = array();
	$data1 = array();
	$data1 = array(
            'wpm' => $_average_scale['WPM'],
            'acc' => $_average_scale['Accuracy'],
            'combo' => $_average_scale['Combo'],
           
        );
    /*$data[] = array(
            '<span class="">Average<span>',
            '<span class=""><span>',
             '<span class="">' . $_average_scale['WPM'] . '<span>',
            '<span class="">' . $_average_scale['Accuracy'] . '<span>',
            '<span class="">' . $_average_scale['Combo'] . '<span>',
            '<span class=""><span>',
            '<span class=""><span>',
        );*/
   // echo "<pre>"; print_r($records['data']); exit; 
    foreach ($records['data'] as $user) 
    {
        $error_data = isset($user[2])?$user[2]:"0";
        if($custom_date){
            $error_data = isset($user[2])?$user[2]:"0";
        }
        $log_Brail_data_value = explode('|', $user['data']);
        

        /* For expired license not display action button */
        $action = '';
        $log_Brail_data_html ="";

        if ($user['file'] != 'Free Type' && !empty($log_Brail_data_value) && count($log_Brail_data_value) > 3) {   
            $title = ($user['file']) ? $user['file'] : '';   
            $log_Brail_data_html .= '';
            $log_Brail_data_html .= '<span><a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $user['lognr'] . '><i class="fa fa-play"></i></a></span>';
            $log_Brail_data_html .= '';
        }

        
        $data[] = array(
            '<span class="">' . $user['file'] . '<span>',
            '<span class="">' . date('m/d/y', strtotime($user['date'])) . '<span>',
             '<span class="">' . $log_Brail_data_value[0] . '<span>',
            '<span class="">' . $log_Brail_data_value[1] . '%<span>',
            '<span class="">' . $log_Brail_data_value[2] . '<span>',
            '<span class=""><a href="javascript:void(0)" data-type="date-history" data-toggle="modal" data-target="#delete-modal-set" aria-label="Delete button" class="badge bg-red history-delete" data-id=' . $user['lognr'] . '><i class="fa fa-trash-o"></i></a></span>',
            '<span class="">'.$log_Brail_data_html.'</span>',
        );
    }

    $records['data'] = $data;        
    $records['data1'] = $data1;        
    $records['status'] = 1;

}
// echo "<pre>"; print_r($records); exit;
if (empty($records)) {
    $records['status'] = 0;
}


echo json_encode($records);
exit;

}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'set_typio_ajax_date_field') {

    $_SESSION['typioDatePickerFrom']= isset( $_POST['typioDatePickerFrom'] ) ? $_POST['typioDatePickerFrom'] : "";
    $_SESSION['typioDatePickerTo']= isset( $_POST['typioDatePickerTo'] ) ? $_POST['typioDatePickerTo'] : "";
    echo "1";
    exit;
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'set_typio_ajax_date_field_kp') {

    $_SESSION['typioDatePickerFrom']= isset( $_POST['typioDatePickerFrom'] ) ? $_POST['typioDatePickerFrom'] : "";
    $_SESSION['typioDatePickerTo']= isset( $_POST['typioDatePickerTo'] ) ? $_POST['typioDatePickerTo'] : "";
    echo "1";
    exit;
}

if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_typio_lesson') {

    $result = array();
    $args = array(
        'id' => $_SESSION['User']['id'],
        'app_condition' => "(`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Test')"
    );
    $search_columns = 'title,number';
    $order_by = 'table_id desc';
    $where = array();
    $groupby = '';
    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    $records['status'] = 0;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $typioval) 
		{
            $id = $typioval['table_id'];
            $title = $typioval['title'];
            $label = "Custom Test Editor";
            $type = "typing-test-lessons";
            if(empty($typioval['number'])){
                $typioval['number'] = '';
                $label = "Custom Lesson Editor";
                $type = "custom-lessons";
            }

            $data[] = array(
                '<div class="icheck-primary d-inline"><input type="checkbox" aria-label="Checkbox ' . ucfirst($typioval['title']) . ' " class="chkbox share_typio_ids" name="ids[]" value="' . $id . '" id="all_chkbox'.$id.'"><label for="all_chkbox'.$id.'"></label></div>',
                '<span class="">' . $typioval['title'] . '<span>',
                '<span class="">' . $typioval['number'] . '<span>',
                '<span class=""><a href="javascript:void(0)" data-type="'.$type.'" data-label="'.$label.'" class="badge bg-green edit-lessons-modal" data-id="' . $id . '" aria-label="Edit ['.$title.'] lesson"><i class="fa  fa-edit (alias)"></i></a></span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $id . '" aria-label="Delete  ['.$title.'] lesson"><i class="fa fa-trash-o"></i></a></span>',
            );
        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }
    echo json_encode($records);
    exit;
}
if (isset($_POST['is_ajax_card']) && isset($_POST['action']) && $_POST['action'] == 'ajax_quick_card_lesson') {

    $result = array();
    $args = array(
        'id' => $_SESSION['User']['id'],
        'app_condition' => "(`app` LIKE 'QC-OL')"
    );
    $search_columns = 'title,number';
    $order_by = 'table_id asc';
    $where = array();
    $groupby = '';
    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    $records['status'] = 0;


      foreach($records['data'] as $key_data =>$user_data ){
            $records['data'][$key_data]['number'] = (!empty($user_data['data']) ? count(explode('|', $user_data['data'])) : 0);
        }
         //echo "<pre>"; print_r($records['data']); 
       $order_sort = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:"";
       $order_column =isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:"";

      $column_nm = $_POST['columns'][$order_column]['name'];

       if($order_sort == 'asc'){
            $order_sort_data =SORT_ASC;
       }else{
            $order_sort_data =SORT_DESC;
       }
       
    
      $price = array_column($records['data'], $column_nm);
      array_multisort($price, $order_sort_data, $records['data']);


    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $typioval) 
        {
            $id = $typioval['table_id'];
            $title = $typioval['title'];
            $data[] = array(
                '<div class="icheck-primary d-inline"><input type="checkbox" aria-label="Checkbox ' . ucfirst($typioval['title']) . ' " class="chkbox share_quick_card_deck_ids" name="share_quick_card_deck_ids[]" value="' . $id . '" id="all_chkbox'.$id.'"><label for="all_chkbox'.$id.'"></label></div>',
                '<span class="">' . $typioval['title'] . '<span>',
                '<span class="">' . $typioval['number'] . '<span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-green  student-decks-maker_type_1_edit" data-id="' . $id . '" aria-label="Edit ['.$title.'] lesson" ><i class="fa fa-edit (alias)" aria-label="Edit"></i></a></span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red student-decks-maker_type_1_delete" data-id="' . $id . '" aria-label="Delete  ['.$title.'] lesson"><i class="fa fa-trash-o" aria-label="Delete"></i></a></span>',
            );

        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }
    echo json_encode($records);
    exit;
}

if (isset($_POST['is_ajax_test_card']) && isset($_POST['action']) && $_POST['action'] == 'ajax_quick_card_test_tbl_lesson') {

    $result = array();
    $args = array(
        'id' => $_SESSION['User']['id'],
        'app_condition' => "(`app` LIKE 'QC-OL' and `number` = 1)"
    );
    $search_columns = 'title,number';
    $order_by = 'table_id desc';
    $where = array();
    $groupby = '';
    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());
    $records['status'] = 0;

    foreach($records['data'] as $key_data =>$user_data ){
            $records['data'][$key_data]['number'] = (!empty($user_data['data']) ? count(explode('|', $user_data['data'])) : 0);
        }
         //echo "<pre>"; print_r($records['data']); 
       $order_sort = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir']:"";
       $order_column =isset($_POST['order'][0]['column'])?$_POST['order'][0]['column']:"";

      $column_nm = $_POST['columns'][$order_column]['name'];

       if($order_sort == 'asc'){
            $order_sort_data =SORT_ASC;
       }else{
            $order_sort_data =SORT_DESC;
       }
       
    
      $price = array_column($records['data'], $column_nm);
      array_multisort($price, $order_sort_data, $records['data']);
      
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $typioval) 
        {
            $id = $typioval['table_id'];
            $title = $typioval['title'];
            $data[] = array(
                '<div class="icheck-primary d-inline"><input type="checkbox" aria-label="Checkbox ' . ucfirst($typioval['title']) . ' " class="chkbox share_quick_card_test_ids" name="share_quick_card_test_ids[]" value="' . $id . '" id="all_chkbox'.$id.'"><label for="all_chkbox'.$id.'"></label></div>',
                '<span class="">' . $typioval['title'] . '<span>',
                '<span class="">' . (!empty($typioval['data']) ? count(explode('|', $typioval['data'])) - 1 : 0) . '<span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-green student-decks-maker_type_2_edit" data-id="' . $id . '" aria-label="Edit ['.$title.'] lesson" ><i class="fa fa-edit (alias)" aria-label="Edit"></i></a></span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red delete student-decks-maker_type_2_delete" data-id="' . $id . '" aria-label="Delete  ['.$title.'] lesson"><i class="fa fa-trash-o" aria-label="Delete"></i></a></span>',
            );

        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }
    echo json_encode($records);
    exit;
}

if (isset($_POST['is_arcade_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_arcade_lesson') {

    $result = array();
    $args = array(
        'id' => $_SESSION['User']['id'],
        'app_condition' => "(`app` LIKE 'Arcade-OL' and `number` = '2' )"
    );
    $search_columns = 'title';
    $order_by = 'table_id desc';
    $where = array();
    $groupby = '';
    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());

    $records['status'] = 0;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $typioval) 
        {
            $id = $typioval['table_id'];
            $title = $typioval['title'];
            $data[] = array(
                '<div class="icheck-primary d-inline"><input type="checkbox" aria-label="Checkbox ' . ucfirst($typioval['title']) . ' " class="chkbox share_hangman_ids" name="share_hangman_ids[]" value="' . $id . '" id="all_chkbox'.$id.'"><label for="all_chkbox'.$id.'"></label></div>',
                '<span class="">' . $typioval['title'] . '<span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-green edit-hangman-modal" data-id="' . $id . '" aria-label="Edit ['.$title.'] lesson" ><i class="fa fa-edit" aria-label="Edit"></i></a></span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-hangman" class="badge bg-red delete-hangman" data-id="' . $id . '" aria-label="Delete  ['.$title.'] lesson"><i class="fa fa-trash-o" aria-label="Delete"></i></a></span>',
            );

        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }
    echo json_encode($records);
    exit;
}

if (isset($_POST['is_reader_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_reader_doc_listing_lesson') {

    $result = array();
    $args = array(
        'id' => $_SESSION['User']['id'],
        'app_condition' => "(`app` LIKE 'PP-OL' and `number` = '0' )"
    );
    $search_columns = 'title';
    $order_by = 'table_id desc';
    $where = array();
    $groupby = '';
    $records = json_datatable('text', '*', $args, $order_by, $search_columns,$groupby = '',$joins = array());

    $records['status'] = 0;
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $typioval) 
        {
            $id = $typioval['table_id'];
            $title = $typioval['title'];
            $data[] = array(
                '<div class="icheck-primary d-inline"><input type="checkbox" aria-label="Checkbox ' . ucfirst($typioval['title']) . ' " class="chkbox share_pro_pack_ids" name="share_pro_pack_ids[]" value="' . $id . '" id="all_chkbox'.$id.'"><label for="all_chkbox'.$id.'"></label></div>',
                '<span class="">' . $typioval['title'] . '<span>',
                '<span class=""><a href="javascript:void(0)" class="badge bg-green edit-pro-pack-modal" data-id="' . $id . '" aria-label="Edit ['.$title.'] lesson" ><i class="fa  fa-edit (alias)"></i></a></span>',
                '<span class=""><a href="javascript:void(0)" data-toggle="modal" data-type="Reader Doc" data-target="#delete-modal-set" class="badge bg-red delete-pro-pack-modal" data-id="' . $id . '" aria-label="Delete  ['.$title.'] lesson"><i class="fa fa-trash-o" aria-label="Delete"></i></a></span>',
            );

        }
        $records['data'] = $data;        
        $records['status'] = 1;

    }
    echo json_encode($records);
    exit;
}


if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_delete_app_data') {
    
    // Sanitize student_id
    $student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;

    if ($student_id > 0) {
        //$sql = "DELETE FROM `data` WHERE `id` = $student_id AND `app` LIKE 'Typio-Journey'";
		   $sql = "UPDATE `data` SET `file` = '' WHERE `id` = $student_id AND `app` LIKE 'Typio-Journey'";
        
        if (mysqli_query($con, $sql)) {
            // Return success response
            echo json_encode([
                'status' => 'success',
                'message' => 'Record deleted successfully.'
            ]);
        } else {
            // Return error if query fails
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to delete record: ' . mysqli_error($con)
            ]);
        }
    } else {
        // Invalid student_id
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid student ID.'
        ]);
    }

    exit;
}
// Handle new deck saving functionality
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_save_deck') {
    $student_id = $_POST['student_id'];
    $deck_name = $_POST['deck_name'];
    $cards = json_decode($_POST['cards'], true);
    $number = '0'; // Always save as deck
    
    if (empty($deck_name) || empty($cards)) {
        echo json_encode(['status' => '400', 'message' => 'Deck name and cards are required']);
        exit;
    }
    
    // Prepare card data with asterisk for correct answer
    $card_data = '';
    foreach ($cards as $card) {
        $sideA = $card['sideA'];
        $sideB = '*' . $card['sideB']; // Add asterisk to correct answer
        $optional1 = !empty($card['optional1']) ? $card['optional1'] : '';
        $optional2 = !empty($card['optional2']) ? $card['optional2'] : '';
        $optional3 = !empty($card['optional3']) ? $card['optional3'] : '';
        
        $card_data .= $sideA . '|' . $sideB;
        if ($optional1) $card_data .= '|' . $optional1;
        if ($optional2) $card_data .= '|' . $optional2;
        if ($optional3) $card_data .= '|' . $optional3;
        $card_data .= '||'; // Card separator
    }
    
    // Save to database
    $query = "INSERT INTO `text` (`app`, `id`, `number`, `title`, `data`, `created_at`) VALUES ('QC-OL', '$student_id', '$number', '$deck_name', '$card_data', NOW())";
    
    if (mysqli_query($con, $query)) {
        echo json_encode(['status' => '200', 'message' => 'Deck saved successfully']);
    } else {
        echo json_encode(['status' => '500', 'message' => 'Error saving deck: ' . mysqli_error($con)]);
    }
    exit;
}

// Handle deck update functionality
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_update_deck') {
    $deck_id = $_POST['deck_id'];
    $deck_name = $_POST['deck_name'];
    $cards = json_decode($_POST['cards'], true);
    $number = '0'; // Always save as deck
    
    if (empty($deck_name) || empty($cards)) {
        echo json_encode(['status' => '400', 'message' => 'Deck name and cards are required']);
        exit;
    }
    
    // Prepare card data with asterisk for correct answer
    $card_data = '';
    foreach ($cards as $card) {
        $sideA = $card['sideA'];
        $sideB = '*' . $card['sideB']; // Add asterisk to correct answer
        $optional1 = !empty($card['optional1']) ? $card['optional1'] : '';
        $optional2 = !empty($card['optional2']) ? $card['optional2'] : '';
        $optional3 = !empty($card['optional3']) ? $card['optional3'] : '';
        
        $card_data .= $sideA . '|' . $sideB;
        if ($optional1) $card_data .= '|' . $optional1;
        if ($optional2) $card_data .= '|' . $optional2;
        if ($optional3) $card_data .= '|' . $optional3;
        $card_data .= '||'; // Card separator
    }
    
    // Update database
    $query = "UPDATE `text` SET `title` = '$deck_name', `data` = '$card_data' WHERE `table_id` = '$deck_id' AND `app` = 'QC-OL'";
    
    if (mysqli_query($con, $query)) {
        echo json_encode(['status' => '200', 'message' => 'Deck updated successfully']);
    } else {
        echo json_encode(['status' => '500', 'message' => 'Error updating deck: ' . mysqli_error($con)]);
    }
    exit;
}

// Handle get deck data for editing
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_get_deck_data') {
    $deck_id = $_POST['deck_id'];
    
    $query = "SELECT * FROM `text` WHERE `table_id` = '$deck_id' AND `app` = 'QC-OL'";
    $result = mysqli_query($con, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $deck_name = $row['title'];
        $data = $row['data'];
        
        // Parse card data
        $cards = [];
        $card_entries = explode('||', $data);
        
        foreach ($card_entries as $card_entry) {
            if (trim($card_entry)) {
                $parts = explode('|', $card_entry);
                if (count($parts) >= 2) {
                    $sideA = $parts[0];
                    $sideB = $parts[1]; // Keep asterisk in data
                    $optional1 = isset($parts[2]) ? $parts[2] : '';
                    $optional2 = isset($parts[3]) ? $parts[3] : '';
                    $optional3 = isset($parts[4]) ? $parts[4] : '';
                    
                    $cards[] = [
                        'sideA' => $sideA,
                        'sideB' => $sideB,
                        'optional1' => $optional1,
                        'optional2' => $optional2,
                        'optional3' => $optional3
                    ];
                }
            }
        }
        
        echo json_encode([
            'status' => '200',
            'deck_name' => $deck_name,
            'cards' => $cards
        ]);
    } else {
        echo json_encode(['status' => '404', 'message' => 'Deck not found']);
    }
    exit;
}
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_get_student_data') {
	//global $con;
	$students_id = $_POST['students_id'];
	$filtered_students = array_map('intval', $students_id);
		
		$html = "";

		foreach ($filtered_students as $filtered_ids) {
			$filterstu = "SELECT * FROM `user` WHERE id = '$filtered_ids'";
			$result = mysqli_query($con, $filterstu);
			
			if ($row = mysqli_fetch_assoc($result)) {
				$html .= "<tr>
							<td>".base64_decode($row['firstname']).' '.base64_decode($row['lastname'])."</td>
							<td>".base64_decode($row['username'])."</td>
							
						  </tr>";
						  
						  /*<td><a href='".ADMIN_URL."student/student-overview.php?student=".$row['id']."' class='accessibyte-link' >Overview</a></td>*/
			}
		}

		//$html .= "</tbody>";

		echo json_encode([
			'status' => 200,
			'html' => $html
		]);
}
?>