<?php
	include "../config/config.php";
	$file_data = fopen($_FILES["import_file"]["tmp_name"], 'r');
	$type = explode(".",$_FILES['import_file']['name']);
	if(strtolower(end($type)) == 'csv'){
		fgetcsv($file_data);
		$import_teacher_count =0;
		$license =$_SESSION['User']['license'];
		$no_of_licence = get_license_data($license);
		$used = $no_of_licence['no_teacher_use'];
		$no_of_teacher = $no_of_licence['no_teacher'];

		//echo "<pre>";print_r($no_of_licence); exit;
		$i = 1;
		$error_data = array();
		while($row = fgetcsv($file_data))
		{
			$i++;
			$first_name = base64_encode( $row[0] );
			$last_name = base64_encode( $row[1] );
			//$user_name =base64_encode( $row[2] );
			$user_name = $email = base64_encode($row[2]);
			$organization = base64_encode($row[3]);
			$seat_limit = isset($row[4]) ? $row[4] : '0';
			$role = "teacher";
			$license =$_SESSION['User']['license'];
			// if(empty($user_name) ){
			// 	$error_data[] = 'Username empty on line no : '.$i;
			// 	continue;
			// } else
			if(empty($email) ){
				$error_data[] = 'Email empty on line no : '.$i;
				continue;
			} else if(empty($row[5]) ){
				$error_data[] = 'Password empty on line no : '.$i;
				continue;
			}
			if(strtolower($seat_limit) == 'no limit'){
				$seat_limit = '0';
			}
			$password = md5($row[5]);
			$query = "SELECT * FROM user WHERE (email='".$email."' OR username ='".$email."') AND role = 'teacher'";
			$total_teacher_create = $import_teacher_count + $used;
			$query_result = mysqli_query($con, $query);

			if ( mysqli_num_rows( $query_result ) > 0) {
				$error_data[] = 'Username or email already exist on line no : '.$i;
				continue;
			}
			if( $total_teacher_create >= $no_of_teacher ){
				$error_data[] = ' Teacher exceed from limit from line no : '.$i;
				continue;
			}
			$token = getToken(30);
			$teacher_code = generateRandomString();
			query("INSERT INTO user SET
					`username` = '{$user_name}',
					`email`	   = '{$email}',
					`password` = '{$password}',
					`firstname`= '{$first_name}',
					`lastname` = '{$last_name}',
					`organization` = '{$organization}',
					`role`		= 'teacher',
					`teacher`   = '{$teacher_code}',
					`license`	= '{$license}',
					`token`     = '{$token}',
					`login`     = '0',
						`age`       = '0',
					`grade`     = '0',
					`seat_limit` = '{$seat_limit}'
					");
			$user_id = mysqli_insert_id($con);
			/** add history to payment note */
			add_license_note_to_history('', $user_id, 'activate', 'teacher');
			/** add history to payment note */
			$import_teacher_count++;
			
		}
		if($import_teacher_count > 0){
			update_no_license_data_new($license,0,$import_teacher_count);
				
		}
		$error_data = implode('<br/>',$error_data);
		$_SESSION['error_msg'] = $error_data;
		$json_arr =array("status"=>"1","error_data" => $error_data,"import_teacher"=>$import_teacher_count,"msg"=>'Csv Data imported successfully');
	} else {
		$_SESSION['error_msg'] = '';
		$json_arr =array("status"=>"0","msg"=>'Please upload csv file only');
	}	
	echo json_encode($json_arr);
	exit;

?>