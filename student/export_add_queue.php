<?php 
include "../config/config.php";

// Values
$user_id    = $_SESSION['User']['id'];
$user_email = $_SESSION['User']['email'];
$status     = "pending";
$site_url = "https://accessibyte.com/";
$license = $_SESSION['User']['license'];
$todayDate 	= date('Y-m-d');

$report_type  = 'student_export' ;

$request_data = $report_type . ":" . $user_id;
$report_hash = hash('sha256', $request_data);

$price_opt = "SELECT price_option FROM `user` WHERE id = '$user_id' AND license = '$license'";

$downloadquery  = "SELECT report_hash FROM report_downloads WHERE report_type = '$report_type' AND `created_at` LIKE '%".$todaydate."%' ";
		
$resultdownload = mysqli_query($con, $downloadquery);
		
$report_hashs1 = [];
while($row = mysqli_fetch_assoc($resultdownload)){
			$report_hashs[] = json_decode($row['report_hash']);
			$report_hashs1[] = $row['report_hash'];
			}
			
$price_opt_qr = mysqli_query($con, $price_opt);
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



		$reportreq = "Multiple";
		$check = "SELECT id FROM export_queue WHERE user_id = '$user_id' AND status = 'pending' AND csv_type = 'student_export' AND is_single = '0' LIMIT  1";
		$result = mysqli_query($con, $check);

		

		$sql = "SELECT * FROM report_requests WHERE teacher_id = '$user_id' AND report_hash = '$report_hash' AND csv_type = '$report_type' AND is_single = '0' AND requested_at LIKE '%".$todayDate."%' ";
		
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
			
				$display_name = 'All Students';
			
			// Already has a pending request
			echo json_encode([
					"Success" => "200",
					"msg" => "<h4><b>" .$display_name . "</b> is already included in a <b>Export</b> report today. Please check the <a href='" . ADMIN_URL . "student/student-reports.php' class='' id='list_lnk_mystu'>Students Reports</a> page.</h4>"
				]);
		} else {
    
			  
	if($request_count == 0 || $request_count == "0"){
		$request = "INSERT INTO report_requests (teacher_id, report_hash, csv_type , request_count , is_single) 
							VALUES ('$user_id', '$report_hash' ,'$report_type' , '1' , '0' )";
	}else{
		$request = "UPDATE report_requests SET request_count = request_count + 1
					   WHERE teacher_id = '$user_id' 
					   AND report_hash = '$report_hash' 
					   AND csv_type = '$report_type' 
					   AND is_single = '0' ";
				
		}
	
	$sql_downloads 	= "INSERT INTO report_downloads (user_id, user_email, file_status , report_hash, report_type, is_single) 
        VALUES ('$user_id', '$user_email', 'Processing' , 'Multiple' , '$report_type', '0' )";
	
	$request_downloads = mysqli_query($con, $sql_downloads);	
	
	if($request_downloads){
	$inserted_id 	= mysqli_insert_id($con); 
	}
	
	$request_result = mysqli_query($con, $request);		
	
	$query = "INSERT INTO export_queue (user_id, user_email, status , csv_type , dwn_lnk_id ,is_single) 
              VALUES ('$user_id', '$user_email', '$status' ,'$report_type' ,'$inserted_id' , '0' )";
    $query_result = mysqli_query($con, $query);

    if ($query_result) {
       echo json_encode([
					"Success" => "200",
					"msg" => "<h4>Generating <b>Exports</b> report. Please check the <a href='".ADMIN_URL."student/student-reports.php' class='' id='list_lnk_mystu' >Students Reports</a> page to access your report.</h4>"
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

/*
if ($query_result) {
	$result =  array("Success"=>"200","msg"=>"We’ll email you the report when it’s ready.");
	echo json_encode($result);
    //echo "Job added. ID: " . mysqli_insert_id($con);
} else {
	$result =  array("Error"=>"400","msg"=>"Error in report. Please try again");
	echo json_encode($result);
   // echo "Error: " . mysqli_error($con);
}
*/
exit;

?>