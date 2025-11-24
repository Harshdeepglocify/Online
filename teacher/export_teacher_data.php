<?php
	include "../config/config.php";
      $delimiter = ",";
      $filename = "teacher_csv" . time() . ".csv"; // Create file name
       
      //create a file pointer
      $f= fopen( 'php://output', 'w' );

      
       
      //set column headers
      $fields = array('First Name','Last Name','Username','Email', 'Organization', 'Student Seat Limit');
      fputcsv($f, $fields, $delimiter);
       
      //output each row of the data, format line as csv and write to file pointer
    	
		$query = "SELECT * FROM user WHERE `role` ='teacher' AND `license` ='".$_SESSION['User']['license']."' ORDER BY firstname ";
      	$query_result = mysqli_query($con, $query);
		if (mysqli_num_rows($query_result) > 0 ) {
			while ($teacher_row = mysqli_fetch_assoc($query_result)) {
			
				$firstname =  ucfirst(base64_decode($teacher_row['firstname']));
				$lastname =  ucfirst(base64_decode($teacher_row['lastname']));
				$username =  base64_decode($teacher_row['username']);
				$email =base64_decode($teacher_row['email']);
				$license =$teacher_row['license'];
				$organization= base64_decode($teacher_row['organization']);
				
				$student_count = '0';
				$query = query("SELECT count(*) student_count  FROM user where teacher='".$teacher_row['teacher']."' AND role ='student'");
				if (!empty($query->num_rows)) {
					while ($row = mysqli_fetch_assoc($query)) {
						$student_count=$row['student_count'];
					}
				}
				$limit = $teacher_row['seat_limit'];
				if($teacher_row['seat_limit'] == '0'){
					$limit = 'No Limit';
				}

				$fields_value = array($firstname,$lastname,$username,$email, $organization, $limit);
				fputcsv($f, $fields_value , $delimiter);
			}
		}
       	fclose( $f );
      	return 
     
?>