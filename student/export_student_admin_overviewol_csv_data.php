<?php
	include "../config/config.php";
      $delimiter = ",";

    $f= fopen( 'php://output', 'w' );
                
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=csvfile.csv');    

    //set column headers
    //$fields = array('Date','Student Username','Lesson','WPM','ACC', 'ERR');
	//Date, App ("File" column), Activity ("data" column).
    $fields = array('Date', 'Student Username', 'App', 'Activity');
    fputcsv($f, $fields, $delimiter);
       
      //output each row of the data, format line as csv and write to file pointer
    	
    $student_ids = isset($_POST['ids']) ? $_POST['ids'] : ''; 
    if(!empty($student_ids)){
        $custom_date =  custom_student_date();
        $seen_entries = [];
        foreach($student_ids as $studentid){
            /*$log_typio_query = "SELECT * FROM `log` WHERE (`app` LIKE 'Typio-BRL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id ='" . $studentid . "'";*/
            $log_typio_query = "SELECT * FROM `log` WHERE (`app` LIKE 'Overview-OL') AND id ='" . $studentid . "'";
	        $query_result = mysqli_query($con, $log_typio_query);
            if (mysqli_num_rows($query_result) > 0 ) {
                while ($log_typio_data_row = mysqli_fetch_assoc($query_result)) {
                    $app = ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
                    $activity = ($log_typio_data_row['data']) ? $log_typio_data_row['data'] : '';
                    //$log_typio_data_value = explode('|', $log_typio_data_row['data']);
                    $date = ($log_typio_data_row['date']) ? date('m/d/Y', strtotime($log_typio_data_row['date'])) : '';
					$unique_key = $date . '|' . $app . '|' . $activity;

					// Skip if we've already written this combination
					if (isset($seen_entries[$unique_key])) {
						continue;
					}

					// Mark as seen
					$seen_entries[$unique_key] = true;
					$query = "SELECT username FROM user WHERE id='" . $studentid . "'";
                    $student_data = get_query_data($query);
                    $username = isset($student_data[0]) ? base64_decode($student_data[0]['username']) : '';
					
                    $fields_value = array($date,$username,$app,$activity );
		            fputcsv($f, $fields_value , $delimiter);
                }
            }
        }
    }
    fclose( $f );
    return 
?>