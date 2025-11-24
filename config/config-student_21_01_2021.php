<?php

include "config.php";

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


// *
//  * Display App Typio-OL data
//  * 
//  * @param string $typio_start_date 
//  * @param string $typio_end_date 
//  * @return string Typio-OL table html

function displayAppTypioData($typio_start_date = 0, $typio_end_date = 0, $student_id = '') {

    global $con, $studentid;
    $table_data = array();
    $studentid = !empty($student_id) ? $student_id : $studentid;
    $log_typio_query = "SELECT * FROM `log` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL')  AND id ='" . $studentid . "'";
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
			$log_typio_data_html .= '<td>';
			$log_typio_data_html .= '<a href="javascript:void(0)" aria-label="Replay ' . $title . '" class="badge bg-red history-play" data-id=' . $log_typio_data_row['lognr'] . '><i class="fa fa-play"></i></a>';
			$log_typio_data_html .= '</td>';
		}
        $log_typio_data_html .= '</tr>';
    }

    $log_typio_data_html .= '</tbody>';

    $table_data['html'] = $log_typio_data_html;

    return $table_data;
}

/**
 * Display App Arcade-OL data
 * 
 * @param string $arcade_start_date 
 * @param string $arcade_end_date 
 * @return string Arcade-OL table html
 */
function displayAppArcadeData($arcade_start_date = 0, $arcade_end_date = 0) {

    global $con, $studentid;

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
    $log_typio_data_html .= "<tr></tr>";
    $log_typio_data_html .= '<tr><td>' . $log_data_table_wpm_title . '</td><td>' . (!empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0') . '</td></tr>';
    $log_typio_data_html .= '<tr><td>' . $log_data_table_accuracy_title . '</td><td>' . (!empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ) . '</td></tr>';
    $log_typio_data_html .= '<tr><td>' . $log_data_table_combo_title . '</td><td>' . (!empty($combo_data) ? floor(($combo_data / $log_typio_data_count)) : '0' ) . '</td></tr>';

    return $log_typio_data_html;
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
    $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id='" . $studentid . "'";
    
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

    if ($log_typio_data) {
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
    } elseif ($_POST['action_type'] == 'get_history_average_chart_data') {
        $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
        $start_date = date('Y-m-d', strtotime($_POST['start_date']));
        $end_date = date('Y-m-d', strtotime($_POST['end_date']));
        $html .= displayAppTypioDataTableAverageBarChart($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59');
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    } elseif ($_POST['action_type'] == 'get_overview_pie_char_history') {
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
        
        $json_data =array();
        $data_available ="";
        if($propack_data_rows > 0 || $arcade_data_rows > 0 || $quick_card_data_rows > 0 || $typio_data_rows > 0){
            $data_available ="1";

        }
        $json_data['0']['name']="Typio";
        $json_data['0']['data']=[intval($typio_data)];
        $json_data['1']['name']="Quick Cards";
        $json_data['1']['data']=[intval($quick_card_data)];
        $json_data['2']['name']="Propack";
        $json_data['2']['data']=[intval($propack_data)];
        $json_data['3']['name']="Arcade";
        $json_data['3']['data']=[intval($arcade_data)];
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
        
        $json_data =array();
        $data_available ="";
        if($propack_data_rows > 0 || $arcade_data_rows > 0 || $quick_card_data_rows > 0 || $typio_data_rows > 0){
            $data_available ="1";

        }
        $json_data['0']['name']="Typio";
        $json_data['0']['data']=[intval($typio_data)];
        $json_data['1']['name']="Quick Cards";
        $json_data['1']['data']=[intval($quick_card_data)];
        $json_data['2']['name']="Propack";
        $json_data['2']['data']=[intval($propack_data)];
        $json_data['3']['name']="Arcade";
        $json_data['3']['data']=[intval($arcade_data)];
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
    if(isset($_POST['search_graph']) && $_POST['search_graph'] ==1 ){
        $type_str =" app like 'TY%' || app like 'PP%' || app like 'QC%' || app like 'AA%' ";
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
                    $min = $min . "m";
                }
                $logData = $hours . "hrs " . $min;
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
        $html .= displayAppArcadeData($_POST['start_date'] . ' 00:00:00', $_POST['end_date'] . ' 23:59:59');
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
function updateAppTypioSettingData($update_typio_setting_data = '') {

    $update_typio_setting_ready_data = array(115 => $update_typio_setting_data['typio_font_size'], 117 => $update_typio_setting_data['typio_font_style'], 116 => $update_typio_setting_data['typio_font_color'], 118 => $update_typio_setting_data['typio_bg_color'], 119 => $update_typio_setting_data['typio_accessory_color'], 125 => $update_typio_setting_data['typio_keypress'], 126 => $update_typio_setting_data['typio_sfx'], 127 => $update_typio_setting_data['typio_highlight'], 131 => $update_typio_setting_data['typio_accuracy_goal'], 129 => $update_typio_setting_data['typio_wpm_goal'], 130 => $update_typio_setting_data['typio_goal_lock'], 133 => $update_typio_setting_data['typio_game_lock'], 134 => $update_typio_setting_data['typio_setting_lock'], 128 => $update_typio_setting_data['visual_keyboard'], 138 => $update_typio_setting_data['typio_smart_wpm'], 121 => $update_typio_setting_data['typio_voice_rate'], 122 => $update_typio_setting_data['typio_voice_pitch'],
        142 => $update_typio_setting_data['typio_visual_fx'], 141 => $update_typio_setting_data['typio_subtitles'], 143 => $update_typio_setting_data['typio_selection_color'],
        136 => $update_typio_setting_data['typio_pet_coins'],149 => $update_typio_setting_data['typio_visual_hands'],151 => $update_typio_setting_data['typio_hands_style'],148 => $update_typio_setting_data['typio_spell_mode'],150 => $update_typio_setting_data['typio_curriculumn']
    );
    $update_typio_setting_final_status = 1;
    foreach ($update_typio_setting_ready_data as $update_typio_setting_data_id => $update_typio_setting_data_variable) {
        $update_typio_setting_status = updateAppTypioSettingField($update_typio_setting_data_id, $update_typio_setting_data_variable);
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
function updateAppTypioSettingField($update_typio_setting_item_id = 0, $update_typio_setting_variable = '') {
    global $con, $studentid;
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
?>