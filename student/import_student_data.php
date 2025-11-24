<?php
	include "../config/config.php";

	$type = explode(".",$_FILES['import_file']['name']);
	if(strtolower(end($type)) == 'csv'){
		$file_data = fopen($_FILES["import_file"]["tmp_name"], 'r');
		
		fgetcsv($file_data);
		$import_student_count = 0;
		$license = $_SESSION['User']['license'];
		$no_of_licence = get_license_data($license);
		$used = $no_of_licence['no_student_use'];
		$no_of_student = $no_of_licence['no_student'];
		//echo "<pre>"; print_r(fgetcsv($file_data)); die(0);

		$i = 1;
		
		while($row = fgetcsv($file_data)) { 
			$i++;
			$first_name = base64_encode( $row[0] );
			$user_name = base64_encode( $row[1] );
			if(empty($user_name) ) {
				$error_data[] = 'Username empty on line no : '.$i;
				continue;
			} else if(empty($row[3]) ) {
				$error_data[] = 'Password empty on line no : '.$i;
				continue;
			}
			$password = md5($row[3]);
			//$organization = base64_encode($row[5]);
			$teacher_username = $row[2];
			$role = "teacher";

			$args = array(
				'username' => base64_encode($teacher_username),
				'role' => 'teacher',
			);
			$teacher_user = get_users($args); 

			if(!empty($teacher_user) && isset($teacher_user[0])) {
				$teacher_user_name = $teacher_user[0]['username'];
			} else {
				$teacher_user_name = $_SESSION['User']['username'];   /* if teacher not found then assign student as */
			}
			
			$query = "SELECT * FROM user WHERE username ='".$user_name."'"; /* check if user already exist */ 
			$total_student_create = $import_student_count + $used;
			$query_result = mysqli_query($con, $query);

			if ( mysqli_num_rows( $query_result ) > 0 ) {
				$error_data[] = 'Student username already exist on line no : '.$i;
				continue;
			}
			if ( $total_student_create >= $no_of_student ) {
				$error_data[] = 'Student exceed from limit from line no : '.$i;
				continue;
			}
			$token = getToken(30);
			$args = array(
				'license' => $license,
				'role' => 'teacher',
			);
			$get_teacher = get_users($args); /*Get all teachers of the license */
			if (!empty($get_teacher)) {
				foreach ($get_teacher as $stud) {				
					if($teacher_user_name == $stud['username']){ /* check if sheet's teacher username matched with the license teachers */
						$student_count_query = query("SELECT COUNT(*) as total FROM user WHERE teacher ='".$stud['teacher']."' and role='student'"); /** fetch the existing students of that teacher */
						$data = fetch($student_count_query);
						$total = 0;
						if (!empty($data)) {
							$total = $data['total'];
						}
						if(($stud['seat_limit'] > $total) || empty($stud['seat_limit'])) {     /* check teacher student seat limit */
							$organization = $stud['organization'];
							$teacher_first_name = base64_decode($stud['firstname']);
							$email=$stud['email'];
							$last_name = '';
							$teacher_code = $stud['teacher'];
							$teacher_last_name = base64_decode($stud['lastname']);
							$teacher_name = base64_encode($teacher_first_name." ".$teacher_last_name);
							query("INSERT INTO user SET
									`username` = '{$user_name}',
									`email`	   = '{$email}',
									`password` = '{$password}',
									`firstname`= '{$first_name}',
									`lastname` = '{$last_name}',
									`organization` = '{$organization}',
									`role`		= 'student',
									`license`	= '{$license}',
									`token`     = '{$token}',
									`teacher`   = '{$teacher_code}',
									`teacher_name`   = '{$teacher_name}',
									`login`     = '0',
									`age`       = '0',
									`grade`     = '0'
									");
							$user_id = mysqli_insert_id($con);

							/** add history to payment note */
							add_license_note_to_history('', $user_id, 'activate', 'student');
							/** add history to payment note */
							$import_student_count++;
						} else { 
							$error_data[] = 'Teacher seat limit is exceed limit from line no : '.$i;
							break 2;
						}
					}
				}
				
			}	
			// } else {
			// 	$error_data[] = 'Teacher username does not exist line no : '.$i;
			// 	continue;
			// }	 
		}
		
		if($import_student_count > 0) {
			update_no_license_data_new($license,$import_student_count,0);
		}
		/* Update license nos'of student and teacher data */
		$error_data = implode('<br/>',$error_data);
		$_SESSION['error_msg'] = $error_data;
		$json_arr = array("status"=>"1", "error_data" => $error_data, "import_student"=>$import_student_count, "msg"=>'Csv Data imported successfully');
	} else {
		$_SESSION['error_msg'] = '';
		$json_arr = array("status"=>"0","msg"=>'Please upload csv file only');
	}	
	echo json_encode($json_arr);
	exit;

?>