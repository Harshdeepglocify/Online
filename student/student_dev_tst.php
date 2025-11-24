<?php 
error_reporting(E_ALL);
ini_set('display_errors', 0);
$HostName = "localhost";
$DbUser = "devaccessibyte_copyuser";
$DbPassword = "P?fpg2fZL~G-";
$Database = "devaccessibyte_onlinec";
//header('Content-Type: text/csv; charset=utf-8');
//header('Content-Disposition: attachment; filename=students_export_' . date("Y-m-d_H-i-s") . '.csv');
global $con;
$con = mysqli_connect($HostName, $DbUser, $DbPassword, $Database);
if (!$con) {
 
}else{
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
function Brail_average_chart_write($student_id = 0, $Brail_start_date = 0, $Brail_end_date = 0) {
   $studentid = '78';

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
    $_average_scale['Combo'] = !empty($combo_data) ? floor(($combo_data / $log_Brail_data_count)) : '0' ;

    return $_average_scale;
}	
	
$studentid = '78';

$studentid = !empty($student_id) ? $student_id : $studentid;

$log_data_table = "log";
$log_Brail_query = "SELECT * FROM `" . $log_data_table . "` WHERE ( `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL' ) AND (`file` LIKE 'Typio-BRL' OR `file` LIKE 'Braillio') AND id='" . $studentid . "'";

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
/*SELECT * FROM `log` WHERE ( `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL' ) AND (`file` LIKE 'Typio-BRL' OR `file` LIKE 'Braillio') AND id='78' AND `date` BETWEEN '2025-09-15 00:00:00' AND '2025-09-16 23:59:00';*/


/*"SELECT * FROM `log` WHERE ( `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL' OR `app` LIKE 'Overview-OL' ) AND (`file` LIKE 'Typio-BRL' OR `file` LIKE 'Braillio') AND id='78' AND `date` BETWEEN '2025-09-15 00:00:00' AND '2025-09-16 23:59:00' "*/

$_average_scale = Brail_average_chart_write($student_id, $Brail_start_date, $Brail_end_date);
$_average_scale['start_date'] = !empty($Brail_start_date) ? date('m/d/y', strtotime($Brail_start_date)) : '';
$_average_scale['end_date'] = !empty($Brail_end_date)? date('m/d/y', strtotime($Brail_end_date)) : '';
/*
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
        $error_data = ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : '';
        if($custom_date){
            $error_data = ($log_Brail_data_value[2]) ? $log_Brail_data_value[2] : '';
        }
        $log_Brail_data_html .= $error_data;
        $log_Brail_data_html .= '</td>';
        $log_Brail_data_html .= '</tr>';
		
		print_r($log_Brail_data_row);
    }
} else {
    $log_Brail_data_html .= '<tr><td>0</td><td>0</td><td>0</td><td>0</td></tr>';
}

if ($log_Brail_data_html == '<tbody><tr><th>Name</th><th>WPM</th><th>ACC(%)</th><th>'.$lbl.'</th></tr>') {
    $log_Brail_data_html .= '<tr><td></td><td></td><td></td><td></td></tr>';
   // return $log_Brail_data_html;
}

$log_Brail_data_html .= '</tbody>';*/

echo $log_Brail_query;

}