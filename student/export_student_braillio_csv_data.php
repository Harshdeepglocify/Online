<?php
	include "../config/config.php";
	
		$user_id    	= $_SESSION['User']['id'];
		$user_email 	= $_SESSION['User']['email'];
		$status     	= "pending";
		$student_ids 	= isset($_POST['ids']) ? $_POST['ids'] : '';
		$teacher_data 	= !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '';
		//$stuadm      	= isset($_POST['stuadm']) ? $_POST['stuadm'] : '0';		
		//$student_ids_serialized = serialize($student_ids);
		$student_ids_json = json_encode($student_ids);
		$report_type  	= 'student_braillio' ;
		$todaydate 		= date('Y-m-d');
		$reportreq 		= $student_ids_json;	
		
		$downloadquery  = "SELECT report_hash FROM report_downloads WHERE report_type = '$report_type' AND `created_at` LIKE '%".$todaydate."%' ";
		
		$resultdownload = mysqli_query($con, $downloadquery);
		$report_hashs1 = [];
		while($row = mysqli_fetch_assoc($resultdownload)){
			$report_hashs[] = json_decode($row['report_hash']);
			$report_hashs1[] = $row['report_hash'];
			}
		
		if (is_array($student_ids) && count($student_ids) > 1) {
			$reportreq 	= $student_ids_json;	
					
		} else {	 
			
		$query = "SELECT username,firstname,lastname FROM user WHERE `role` = 'student' AND `id` = '".$student_ids[0]."' ";
		
			$resultlusrs = mysqli_query($con, $query);
			$usernames = [];
			$firstname = [];
			$lastname = [];

			if ($resultlusrs && mysqli_num_rows($resultlusrs) > 0) {
				while ($row = mysqli_fetch_assoc($resultlusrs)) {
					$usernames[] = $row['username'];
					$firstname[] = $row['firstname'];
					$lastname[] = $row['lastname'];
				}
			}
			$reportreq = base64_decode($usernames[0]);	
			$first_name = base64_decode($firstname[0]);	
			$last_name = base64_decode($lastname[0]);	
			
			$display_name = $first_name.' '.$last_name;	
			
		}
		
			sort($student_ids);
			$request_data = $report_type . ":" . implode(",", $student_ids);
			$report_hash = hash('sha256', $request_data);
		
			$license 		= $_SESSION['User']['license'];
			$price_opt 		= "SELECT price_option FROM `user` WHERE id = '$user_id' AND license = '$license'";
			$price_opt_qr 	= mysqli_query($con, $price_opt);
			while($row = mysqli_fetch_assoc($price_opt_qr)){
				$priceid[] = $row['price_option'];
					}
					
			$parts = explode("|", $priceid[0]);
			$item_id = $parts[0];

			$site_url = trim("https://accessibyte.com");
			$edd_store_url = trim("https://accessibyte.com"); // EDD store URL

			$post_data = array(
				'edd_action' => 'check_license',
				'license'    => $license,
				'item_id'    => (int)$item_id,
				'url'        => $site_url,
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $edd_store_url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);

			if (curl_errno($ch)) {
				echo 'cURL error: ' . curl_error($ch);
				curl_close($ch);
				exit;
			}

			curl_close($ch);

			$license_data = json_decode($response, true);

			if (!empty($license_data)) {
			  //  $total_seats = isset($license_data['seats']) ? $license_data['seats'] : 5;
				$used_seats  = isset($license_data['site_count']) ? $license_data['site_count'] : 5;
			  // $remaining   = ($total_seats !== 'N/A') ? $total_seats - $used_seats : 'N/A';
			   $license_limit  = isset($license_data['license_limit']) ? $license_data['license_limit'] : 5;

				
			} 
		$check = "SELECT id FROM export_queue WHERE user_id = '$user_id' AND status = 'pending' AND csv_type = 'student_braillio' AND is_single = '$teacher_data' LIMIT 1";
		
		
		$result = mysqli_query($con, $check);
		

		$todayDate =  date('Y-m-d');
		$sql = "SELECT * FROM report_requests WHERE teacher_id = '$user_id' AND report_hash = '$report_hash' AND csv_type = '$report_type' AND is_single = '$teacher_data' AND requested_at LIKE '%".$todayDate."%' ";
		
		$res = mysqli_query($con, $sql);
		
		$request_count = []; 
		$request_hash = []; 
		
		$request_count = 0; 
		
		if ($res && mysqli_num_rows($res) > 0) {
			$row = mysqli_fetch_assoc($res);
			$request_count = (int)$row['request_count'];
			$request_hash[]	= $row['report_hash'];
		}
		if( $request_count != (int)$license_limit  || $request_count != $license_limit ){

		if ($request_hash[0] == $report_hash) {
			if(empty($display_name)){
				$display_name = 'Selected Students';
			}else{
				$display_name = $display_name;
			}
			// Already has a pending request
			echo json_encode([
					"Success" => "200",
					"msg" => "<h4><b>" .$display_name . "</b> is already included in a <b>Braillio</b> report today. Please check the <a href='" . ADMIN_URL . "student/student-reports.php' class='history_hide' id='list_lnk_mystu'>Students Reports</a> page.</h4>"
				]);
		} else {
			$user_id  = $_SESSION['User']['id'];
			if($request_count == '' || $request_count == null || empty($request_count)){
				$request = "INSERT INTO report_requests (teacher_id, report_hash, csv_type , request_count , is_single) 
							VALUES ('$user_id', '$report_hash' ,'$report_type' , '1' , '$teacher_data' )";
				}else{
				$request = "UPDATE report_requests SET request_count = request_count + 1
					   WHERE teacher_id = '$user_id' 
					   AND report_hash = '$report_hash' 
					   AND csv_type = '$report_type'
					   AND is_single = '$teacher_data' ";
				
				}
			$request_result = mysqli_query($con, $request);
			
			$sql_downloads 	= "INSERT INTO report_downloads (user_id, user_email, file_status , report_hash, report_type,  is_single, created_at) 
			VALUES ('$user_id', '$user_email', 'Processing' , '$reportreq' , '$report_type' , '$teacher_data' , NOW())";
			
			$request_downloads = mysqli_query($con, $sql_downloads);	
			
			if($request_downloads){
			$inserted_id = mysqli_insert_id($con); 
			}  
			
			$query = "INSERT INTO export_queue (user_id, user_email, status , csv_type , student_data , dwn_lnk_id ,is_single ) VALUES ('$user_id', '$user_email', '$status' ,'$report_type' , '$student_ids_json' ,'$inserted_id' , '$teacher_data' )";
			
			$query_result = mysqli_query($con, $query);

			if ($query_result) {
				echo json_encode([
					"Success" => "200",
					"msg" => "<h4>Generating <b>Braillio</b> report. Please check the <a href='".ADMIN_URL."student/student-reports.php' class='history_hide' id='list_lnk_mystu' >Students Reports</a> page to access your report.</h4>"
				]);
			} else {
				echo json_encode([
					"Success" => "500",
					"msg" => "Database error: " . mysqli_error($con)
				]);
			}
		}
		}else{
			echo json_encode([
				"Success" => "409",
				"msg" => "<h4>Daily report limit reached.</h4>"
			]);
		}
    /*  $delimiter = ",";

    $f= fopen( 'php://output', 'w' );
                
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=csvfile.csv');    

    //set column headers
    $fields = array('Date','Student Username','Lesson','WPM','Accuracy', 'Errors');
    fputcsv($f, $fields, $delimiter);
       
      //output each row of the data, format line as csv and write to file pointer
    	
    $student_ids = isset($_POST['ids']) ? $_POST['ids'] : ''; 
    if(!empty($student_ids)){
        $custom_date =  custom_student_date();
        
        foreach($student_ids as $studentid){
            
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
    }
    fclose( $f );
    return */
?>