<?php 
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
	function get_query_data($query){
    $user_data_rows = query($query);
    $student_data = array();
    while ($user_data_row = mysqli_fetch_assoc($user_data_rows)) {

        $student_data[] = $user_data_row;
    }
    return $student_data;
	}
	
	function query($Data) {
		global $con;
		return mysqli_query($con, $Data);
	}
	
	function custom_student_date($start_date = ''){
    $custom_date = "2019-03-01";   /* Y-m-d format */
    if(!empty($start_date)){
        $start_date = date('Y-m-d', strtotime($start_date));
        if($custom_date <= $start_date){
            return true;
        }
    }    
    return false;
	}
	
	$check = "SELECT user_id, user_email ,student_data , dwn_lnk_id ,is_single
          FROM export_queue 
          WHERE status = 'pending' AND csv_type = 'student_braillio' LIMIT 100 ";

$result = mysqli_query($con, $check);

$existing_user_id = [];
$existing_email   = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $existing_user_id[] = array("user" => $row['user_id'] , "email" => $row['user_email'] , "studentid" => $row['student_data'], 'download_id' => $row['dwn_lnk_id'] , 'is_teacher' => $row['is_single']);
        //$existing_email[]   = $row['user_email'];
    }

		$delimiter = ",";
		$root = dirname(__DIR__, 1);
		$csvFolder = $root."/uploads/reports";

		// Create folder if it doesn’t exist
		if ( ! file_exists( $csvFolder ) ) {
			mkdir( $csvFolder, 0777, true );
		}
		$unique = uniqid();
		$filename = "Accessibyte_Braillio_History_" . $unique.".csv";
		$csvFile  = $csvFolder . "/" . $filename;
		$f = fopen($csvFile, "w");
		//$f = fopen($csvFile, "w");        

      //set column headers
      $fields = array('Date','Student Username','Lesson','WPM','Accuracy', 'Errors');
				
     
	 fputcsv($f, $fields, $delimiter);
	 
	 $custom_date =  custom_student_date();

foreach($existing_user_id as $alldata){
	
	

	$studentids = json_decode($alldata['studentid']);
	
	foreach($studentids as $studentid){
		
		  $log_typio_query = "SELECT * FROM `log` WHERE (`app` LIKE 'Typio-BRL') AND id ='" . $studentid . "'";
	        $query_result = mysqli_query($con, $log_typio_query);
            if (mysqli_num_rows($query_result) > 0 ) {
                while ($log_typio_data_row = mysqli_fetch_assoc($query_result)) {
                    $title = ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
                    $log_typio_data_value = explode('|', $log_typio_data_row['data']);
                    $date = ($log_typio_data_row['date']) ? date('m/d/Y', strtotime($log_typio_data_row['date'])) : '';
                    $wpm = ($log_typio_data_value[0]) ? $log_typio_data_value[0] : '';
                    $acc = ($log_typio_data_value[1]) ? intval($log_typio_data_value[1]) . '%' : '';
                    $error_data = $log_typio_data_value[2];
                    if($custom_date){
                        $error_data = $log_typio_data_value[2];
                    }
                    $query = "SELECT username FROM user WHERE id='" . $studentid . "'";
                    $student_data = get_query_data($query);
                    $username = isset($student_data[0]) ? base64_decode($student_data[0]['username']) : '';

                    $fields_value = array($date,$username,$title, $wpm ,$acc , $error_data);
		            fputcsv($f, $fields_value , $delimiter);
                }
				 
            }
		}
		$download_link = "online/uploads/reports/" . $filename;  
	
	$createdate =  date("j M, Y");
	$update_sql = "UPDATE report_downloads 
               SET download_link = '$download_link' ,
                file_status = '$createdate',
				created_at = NOW()
               WHERE id = '".$alldata['download_id']."'";
		// 2. Prepare email details
		// $alldata['studentid']
	if (mysqli_query($con, $update_sql)) {
		$delete = "DELETE FROM export_queue WHERE user_id = '".$alldata['user']."' AND user_email = '".$alldata['email']."'  AND csv_type = 'student_braillio' AND is_single = '".$alldata['is_teacher']."'";
		    mysqli_query($con, $delete);
	//$to      = "teacherasscbytedev@yopmail.com";  
	//$to      = "joe@accessibyte.com";  
	/*$to      = base64_decode($alldata['email']);  
	$subject = "Your Accessibyte Export Data is Ready";  

	$message = "
	<!DOCTYPE html>
	<html>
	<head>
	  <meta charset='UTF-8'>
	  <title>Your Export Data is Ready</title>
	</head>
	<body style='font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0;'>
	  <table width='100%' cellspacing='0' cellpadding='0' border='0' style='background-color:#f8f9fa; padding: 40px 0;'>
		<tr>
		  <td align='center'>
			<!-- Centered Card -->
			<table width='600' cellspacing='0' cellpadding='0' border='0' 
				   style='background:#ffffff; border-radius: 8px; 
						  box-shadow: 0 2px 8px rgba(0,0,0,0.1); 
						  margin:0 auto; display:block;'>
			  <tr>
				<td style='padding: 30px; text-align: center;'>
				  <h2 style='color:#333; margin:0 0 20px;'>Your Accessibyte Export Data is Ready 🎉</h2>
				  <p style='color:#555; font-size:15px; line-height:1.6;'>
				   
					Your Accessibyte data is ready.  
					To access it, log in to your teacher dashboard and click the Student Reports sidebar link.
				  </p>
				  <p style='margin: 30px 0;'>
					<a href='https://www.accessibyte.com/online/login/' 
					   style='background-color:#007bff; color:#fff; text-decoration:none; padding:12px 24px; 
							  border-radius:5px; font-size:16px; font-weight:bold; display:inline-block;'>
					   log in to your teacher dashboard
					</a>
				  </p>
				  <p style='color:#888; font-size:13px;'>
					
				  </p>
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
	</body>
	</html>
	";


	$from     = "no-reply@accessibyte.com";
	$headers  = "From: Accessibyte <".$from.">\r\n";
	$headers .= "Reply-To: support@accessibyte.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";


	
    if (mail($to, $subject, $message, $headers)) {
		//echo "Mail with CSV sent!";
		
			$delete = "DELETE FROM export_queue WHERE user_id = '".$alldata['user']."' AND user_email = '".$alldata['email']."'  AND csv_type = 'student_braillio' AND is_single = '".$alldata['is_teacher']."'";
		    mysqli_query($con, $delete);
		
	} else {
	   // echo "Mail failed!";
	}*/

	// 8. Cleanup temp file
	//unlink($csvFile);
		}
	}
	
// Debug: print everything
//print_r($existing_user_id[0]);
//print_r($existing_email);
}
fclose($f);
}