<?php 
$HostName = "localhost";
$DbUser = "devaccessibyte_copyuser";
$DbPassword = "P?fpg2fZL~G-";
$Database = "devaccessibyte_onlinec";
$con = mysqli_connect($HostName, $DbUser, $DbPassword, $Database);

if (!$con) {
 
}else{
	function get_users($arg) {
	global $con;
   

    $user_data = $query_val = array();

    //Set Table Name
    $table = "user";

    //Get data based on teacher code
    if (!empty($arg['teacher_code'])) {
        $query_val[] = " `teacher`='" . $arg['teacher_code'] . "'";
    }

    //Get data based on role
    if (!empty($arg['role'])) {
        $query_val[] = " `role`='" . $arg['role'] . "'";
    }

    //Get data based on role
    if (!empty($arg['username'])) {
        $query_val[] = " `username`='" . $arg['username'] . "'";
    }

    //Get data based on user id
    if (!empty($arg['user_id'])) {
        $query_val[] = " `id`='" . $arg['user_id'] . "'";
    }

    if (!empty($arg['license'])) {
        $query_val[] = " `license`='" . $arg['license'] . "'";
    }

    if (!empty($query_val)) {
        $where = ' WHERE ' . implode(' AND', $query_val);
    }

    //Build Query
    $query = "SELECT * FROM `" . $table . "` $where ";



    $user_data_rows = mysqli_query($con, $query);

    while ($user_data_row = mysqli_fetch_assoc($user_data_rows)) {

        $user_data[] = $user_data_row;
    }
	//print_r($user_data);die();
    return $user_data;
}
function getTimtstampDiff($timestamp) {

    $now = time();
    $datediff = $now - $timestamp;
    $time_ago = floor($datediff / (60 * 60 * 24));

    if ($time_ago == 0 || $time_ago < 0) {
        $time_ago = 'Today';
    } elseif ($time_ago == 1) {
        $time_ago = 'Yesterday';
    } elseif ($time_ago > 5) {
        $time_ago = date('M d, Y', $timestamp);
    } elseif ($time_ago < 5 && $time_ago > 1) {
        $time_ago = $time_ago.' days ago';
    }
    return $time_ago;
}
	$check = "SELECT user_id, user_email 
          FROM export_queue 
          WHERE status = 'pending'";

$result = mysqli_query($con, $check);

$existing_user_id = [];
$existing_email   = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $existing_user_id[] = array("user" => $row['user_id'] , "email" => $row['user_email']);
        //$existing_email[]   = $row['user_email'];
    }

		$delimiter = ",";
		$csvFile = sys_get_temp_dir() . "/Accessibyte Student List.csv";
		$f = fopen($csvFile, "w");
		//$f= fopen( 'php://output', 'w' );
                

      //set column headers
      $fields = array('Display Name','Username','Teacher', 'School','Last Active');
      fputcsv($f, $fields, $delimiter);
	  
	foreach($existing_user_id as $alldata){
	
		$license = "SELECT license FROM users_licenses WHERE user_id = '".$alldata['user']."'";
		$resultlicense = mysqli_query($con, $license);
		$teacherid = "SELECT teacher,organization FROM user WHERE id = ".$alldata['user'];
		$resultteacherid = mysqli_query($con, $teacherid);
		while ($row = mysqli_fetch_assoc($resultteacherid)) { $teahcerid = $row['teacher'];$organization = $row['organization'];}
		//print_r($teahcerid);die();
		if ($resultlicense && mysqli_num_rows($resultlicense) > 0) {
		while ($row = mysqli_fetch_assoc($resultlicense)) {
			$license = $row['license'];
			//$existing_email[]   = $row['user_email'];
		}
		$query = "SELECT * FROM user WHERE `role` ='student' AND organization  = '".$organization."' AND`license` ='".$license."'  AND `is_admin` != '1' ORDER BY firstname ASC";
		$resultlusrs = mysqli_query($con, $query);
		if ($resultlusrs && mysqli_num_rows($resultlusrs) > 0) {
    while ($student_row = mysqli_fetch_assoc($resultlusrs)) {
					//print_r($student_row );die();
			$firstname 		= ucfirst(base64_decode($student_row['firstname']));
            $nickname 		= $firstname;
            $username 		= base64_decode($student_row['username']);
            $teacher_name 	= base64_decode($student_row['teacher_name']);
            $organization 	= base64_decode($student_row['organization']);
            $username 		= base64_decode($student_row['username']);

            $args = array(
                        'teacher_code' => $teahcerid,
                       // 'teacher_code' => $student_row['teacher'],
                        'role' => 'teacher',
             );
			
              $teacher_data = get_users($args);
			  
               if(!empty($teacher_data)){
                 $teacher_name = isset($teacher_data[0]['username']) ? base64_decode($teacher_data[0]['username']) : '';
                    }
                    $email = isset( $student_row['email'] )?base64_decode( $student_row['email'] ): "";
                    $license =$student_row['license'];

                    $activity = !empty($student_row['login']) ? getTimtstampDiff($student_row['login']):"";

		            $fields_value = array($nickname, $username,$teacher_name, $organization,$activity);
		            fputcsv($f, $fields_value , $delimiter);
    }
	 fclose( $f );
	 
	}
	
}else{
	
}

	// 2. Prepare email details
$to      = "teacherasscbytedev@yopmail.com";  
$subject = "Your CSV Report is Ready";  
$message = "Hello,\n\nPlease find attached your CSV export.\n\nThanks.";  
$from    = "no-reply@clone.accessibyte.com";

// 3. Read file & encode
$file_content = file_get_contents($csvFile);
$content = chunk_split(base64_encode($file_content));
$uid = md5(uniqid(time()));
$filename = basename($csvFile);

// 4. Build headers
$headers  = "From: Accessibyte <".$from.">\r\n";
$headers .= "Reply-To: support@clone.accessibyte.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";

// 5. Message Body
$body  = "--".$uid."\r\n";
$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= $message."\r\n\r\n";

// 6. Attachment
$body .= "--".$uid."\r\n";
$body .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; 
$body .= "Content-Transfer-Encoding: base64\r\n";
$body .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
$body .= $content."\r\n\r\n";
$body .= "--".$uid."--";

// 7. Send mail
if (mail($to, $subject, $body, $headers)) {
    //echo "Mail with CSV sent!";
	 foreach ($existing_user_id as $alldata) {
        $delete = "DELETE FROM export_queue WHERE user_id = '".$alldata['user']."' AND user_email = '".$alldata['email']."'";
        mysqli_query($con, $delete);
    }
} else {
   // echo "Mail failed!";
}

// 8. Cleanup temp file
unlink($csvFile);
}




// Debug: print everything
//print_r($existing_user_id[0]);
//print_r($existing_email);

}
}
?>