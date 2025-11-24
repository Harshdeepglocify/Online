<?php

if (isset($_POST) && !empty($_POST)) {

    extract($_POST);
    include "config.php";
    /**
     * Add new Custom lessons data 
     */
    if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Add-Lessons') {

        global $con, $studentid;
        $result = array();
        parse_str($_POST['form_data'], $form_data);

        if (!empty($form_data['data'])) {
            $data = $form_data['data'];
            $user_type = $_POST['user_type'];
            $studentid = isset($_SESSION['studentid']) && trim($_SESSION['studentid']) != '' ? $_SESSION['studentid'] : '';
            $user_id = isset($form_data['user_id']) && trim($form_data['user_id']) != '' ? $form_data['user_id'] : $studentid;
            $form_data['data'] = mysqli_real_escape_string($con, addslashes($form_data['data']));
            $form_data['title']= mysqli_real_escape_string($con, addslashes($form_data['title']));
            $data = "SELECT id,app,number,title,table_id FROM `text` where id ='" . $user_id . "' AND app='Typio-OL' AND title='" . $form_data['title'] . "'";
            $res_data = query($data);
            $re_data = fetch($res_data);

            $table_id = $re_data['table_id'];

            if (!empty($re_data['title'])) {
                $result = array();
                parse_str($_POST['form_data'], $form_data);
				$form_data['title'] =addslashes($form_data['title']);
                $form_data['data']=addslashes($form_data['data']);
                //Check Fileds data exist
                $data = mysqli_real_escape_string($con, $form_data['data']);

                $result['status'] = query('UPDATE `text` SET app="Typio-OL", title="' . $form_data['title'] . '", data="' . $form_data['data'] . '"  WHERE id="' . $user_id . '" AND title="' . $form_data['title'] . '" ');
                $result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
                $result['type'] = 1;
            } else {
                global $con, $studentid;
                $result = array();
                parse_str($_POST['form_data'], $form_data);

                if (!empty($form_data['data'])) {

                    $data = $form_data['data'];
                    $form_data['data']=addslashes($form_data['data']);
                    $form_data['data'] = mysqli_real_escape_string($con, $form_data['data']);
                    $form_data['title'] =addslashes($form_data['title']);

                    $result['status'] = query("INSERT INTO `text` (`id`, `app`, `number`, `title`, `data`) VALUES ('" . $user_id . "','Typio-OL',0,'{$form_data['title']}','{$form_data['data']}')");

                    $result['id'] = mysqli_insert_id($con);
                    $result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson Added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
                    $table_id = $result['id'];
                    $result['type'] = 2;
                }
                $result['html'] = '';
                $result['html'] .= '<tr>';
                if ($user_type == 1) {
                    $result['html'] .= '<td><input type="checkbox" name="share_typio_ids[]" value="' . $table_id . '" class="share_typio_ids"></td>';
                }

                $result['html'] .= '<td>' . $form_data['title'] . '</td>';
                $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-lessons-modal" data-id="' . $table_id . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
                $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $table_id . '"><i class="fa fa-trash-o"></i></a></td>';
                $result['html'] . '</tr>';
            }
        }
        echo json_encode($result);
        exit;
    }


 if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Add-LessonsBRL') {

        global $con, $studentid;
        $result = array();
        parse_str($_POST['form_data'], $form_data);

        if (!empty($form_data['dataBRL'])) {
            $data = $form_data['dataBRL'];
            $user_type = $_POST['user_typeBRL'];
            $studentid = isset($_SESSION['studentid']) && trim($_SESSION['studentid']) != '' ? $_SESSION['studentid'] : '';
            $user_id = isset($form_data['user_idBRL']) && trim($form_data['user_idBRL']) != '' ? $form_data['user_idBRL'] : $studentid;
            $form_data['dataBRL'] = mysqli_real_escape_string($con, addslashes($form_data['dataBRL']));
            $form_data['titleBRL']= mysqli_real_escape_string($con, addslashes($form_data['titleBRL']));
            $data = "SELECT id,app,number,title,table_id FROM `text` where id ='" . $user_id . "' AND app='Typio-BRL' AND title='" . $form_data['titleBRL'] . "'";
            $res_data = query($data);
            $re_data = fetch($res_data);

            $table_id = $re_data['table_id'];

            if (!empty($re_data['title'])) {
                $result = array();
                parse_str($_POST['form_data'], $form_data);
				$form_data['titleBRL'] =addslashes($form_data['titleBRL']);
                $form_data['dataBRL']=addslashes($form_data['dataBRL']);
                //Check Fileds data exist
                $data = mysqli_real_escape_string($con, $form_data['dataBRL']);

                $result['status'] = query('UPDATE `text` SET app="Typio-BRL", title="' . $form_data['titleBRL'] . '", data="' . $form_data['dataBRL'] . '"  WHERE id="' . $user_id . '" AND title="' . $form_data['titleBRL'] . '" ');
                $result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
                $result['type'] = 1;
            } else {
                global $con, $studentid;
                $result = array();
                parse_str($_POST['form_data'], $form_data);

                if (!empty($form_data['dataBRL'])) {

                    $data = $form_data['dataBRL'];
                    $form_data['dataBRL']=addslashes($form_data['dataBRL']);
                    $form_data['dataBRL'] = mysqli_real_escape_string($con, $form_data['dataBRL']);
                    $form_data['titleBRL'] =addslashes($form_data['titleBRL']);

                    $result['status'] = query("INSERT INTO `text` (`id`, `app`, `number`, `title`, `data`) VALUES ('" . $user_id . "','Typio-BRL',0,'{$form_data['titleBRL']}','{$form_data['dataBRL']}')");

                    $result['id'] = mysqli_insert_id($con);
                    $result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson Added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
                    $table_id = $result['id'];
                    $result['type'] = 2;
                }
                $result['html'] = '';
                $result['html'] .= '<tr>';
                if ($user_type == 1) {
                    $result['html'] .= '<td><input type="checkbox" name="share_Brail_ids[]" value="' . $table_id . '" class="share_Brail_ids"></td>';
                }

                $result['html'] .= '<td>' . $form_data['title'] . '</td>';
                $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-lessons-modalBRL" data-id="' . $table_id . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
                $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $table_id . '"><i class="fa fa-trash-o"></i></a></td>';
                $result['html'] . '</tr>';
            }
        }
        echo json_encode($result);
        exit;
    }
    /**
     * Add new Custom typio test lessons data 
     */
    if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Add-typio-test-Lessons') {

        global $con, $studentid;
        $result = array();
        parse_str($_POST['form_data'], $form_data);

        if (!empty($form_data['data'])) {
            $data = $form_data['data'];
            $user_type = $_POST['user_type'];
            $studentid = isset($_SESSION['studentid']) && trim($_SESSION['studentid']) != '' ? $_SESSION['studentid'] : '';
            $user_id = isset($form_data['user_id']) && trim($form_data['user_id']) != '' ? $form_data['user_id'] : $studentid;
            $form_data['data'] = mysqli_real_escape_string($con, addslashes($form_data['data']));
            $form_data['title']= mysqli_real_escape_string($con, addslashes($form_data['title']));


            $data = "SELECT id,app,number,title,table_id FROM `text` where id ='" . $user_id . "' AND app='Typio-Test' AND title='" . $form_data['title'] . "'";
            $res_data = query($data);
            $re_data = fetch($res_data);

            $table_id = $re_data['table_id'];

            if (!empty($re_data['title'])) {
                $result = array();
                parse_str($_POST['form_data'], $form_data);
                $form_data['title'] =addslashes($form_data['title']);
                $form_data['data']=addslashes($form_data['data']);
                $form_data['attempt']=!empty($form_data['attempt']) ? addslashes($form_data['attempt']):1;
                //Check Fileds data exist
                $data = mysqli_real_escape_string($con, $form_data['data']);

                $result['status'] = query('UPDATE `text` SET app="Typio-Test", title="' . $form_data['title'] . '", number ="' . $form_data['attempt'] . '", data="' . $form_data['data'] . '"  WHERE id="' . $user_id . '" AND title="' . $form_data['title'] . '" ');
                $result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
                $result['type'] = 1;
            } else {
                global $con, $studentid;
                $result = array();
                parse_str($_POST['form_data'], $form_data);

                if (!empty($form_data['data'])) {

                    $data = $form_data['data'];
                    $form_data['data']=addslashes($form_data['data']);
                    $form_data['data'] = mysqli_real_escape_string($con, $form_data['data']);
                    $form_data['title'] =addslashes($form_data['title']);
                    $attempt = (int) $form_data['attempt'];
                    $form_data['attempt']=!empty($attempt) ? addslashes($attempt):1;

                    $result['status'] = query("INSERT INTO `text` (`id`, `app`, `number`, `title`, `data`) VALUES ('" . $user_id . "','Typio-Test','{$form_data['attempt']}','{$form_data['title']}','{$form_data['data']}')");

                    $result['id'] = mysqli_insert_id($con);
                    $result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson Added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
                    $table_id = $result['id'];
                    $result['type'] = 2;
                }
                $result['html'] = '';
                $result['html'] .= '<tr>';
                if ($user_type == 1) {
                    $result['html'] .= '<td><input type="checkbox" name="share_typio_ids[]" value="' . $table_id . '" class="share_typio_ids"></td>';
                }

                $result['html'] .= '<td>' . stripslashes($form_data['title']) . '</td>';
                $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-lessons-modal" data-id="' . $table_id . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
                $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $table_id . '"><i class="fa fa-trash-o"></i></a></td>';
                $result['html'] . '</tr>';
            }
        }
        echo json_encode($result);
        exit;
    }
    

if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Add-Brail-test-Lessons') {


        global $con, $studentid;
        $result = array();
        parse_str($_POST['form_data'], $form_data);

        if (!empty($form_data['dataBRL'])) {
            $data = $form_data['dataBRL'];
            $user_type = $_POST['user_type'];
            $studentid = isset($_SESSION['studentid']) && trim($_SESSION['studentid']) != '' ? $_SESSION['studentid'] : '';
            $user_id = isset($form_data['user_idBRL']) && trim($form_data['user_idBRL']) != '' ? $form_data['user_idBRL'] : $studentid;
            $form_data['dataBRL'] = mysqli_real_escape_string($con, addslashes($form_data['dataBRL']));
            $form_data['titleBRL']= mysqli_real_escape_string($con, addslashes($form_data['titleBRL']));


            $data = "SELECT id,app,number,title,table_id FROM `text` where id ='" . $user_id . "' AND app='Typio-Test-BRL' AND title='" . $form_data['titleBRL'] . "'";
            $res_data = query($data);
            $re_data = fetch($res_data);
//print_r($res_data);
            $table_id = $re_data['table_id'];

            if (!empty($re_data['title'])) {
//print_r('update');
                $result = array();
                parse_str($_POST['form_data'], $form_data);
                $form_data['titleBRL'] =addslashes($form_data['titleBRL']);
                $form_data['dataBRL']=addslashes($form_data['dataBRL']);
                $form_data['attemptBRL']=!empty($form_data['attemptBRL']) ? addslashes($form_data['attemptBRL']):1;
                //Check Fileds data exist
                $data = mysqli_real_escape_string($con, $form_data['dataBRL']);

                $result['status'] = query('UPDATE `text` SET app="Typio-Test", title="' . $form_data['titleBRL'] . '", number ="' . $form_data['attemptBRL'] . '", data="' . $form_data['dataBRL'] . '"  WHERE id="' . $user_id . '" AND title="' . $form_data['titleBRL'] . '" ');
                $result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Test updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
                $result['type'] = 1;
            } else {
//print_r('insert');
                global $con, $studentid;
                $result = array();
                parse_str($_POST['form_data'], $form_data);

                if (!empty($form_data['dataBRL'])) {
//print_r('fbweuibfweg');
                    $data = $form_data['dataBRL'];
                    $form_data['dataBRL']=addslashes($form_data['dataBRL']);
                    $form_data['dataBRL'] = mysqli_real_escape_string($con, $form_data['dataBRL']);
                    $form_data['titleBRL'] =addslashes($form_data['titleBRL']);
                    $attempt = (int) $form_data['attemptBRL'];
                    $form_data['attemptBRL']=!empty($attempt) ? addslashes($attempt):1;

                    $result['status'] = query("INSERT INTO `text` (`id`, `app`, `number`, `title`, `data`) VALUES ('" . $user_id . "','Typio-Test-BRL','{$form_data['attemptBRL']}','{$form_data['titleBRL']}','{$form_data['dataBRL']}')");

                    $result['id'] = mysqli_insert_id($con);
                    $result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Test Added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
                    $table_id = $result['id'];
                    $result['type'] = 2;
                }
                $result['html'] = '';
                $result['html'] .= '<tr>';
                if ($user_type == 1) {
                    $result['html'] .= '<td><input type="checkbox" name="share_Brail_ids[]" value="' . $table_id . '" class="share_Brail_ids"></td>';
                }

                $result['html'] .= '<td>' . stripslashes($form_data['titleBRL']) . '</td>';
                $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-lessons-modalBRL" data-id="' . $table_id . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
                $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $table_id . '"><i class="fa fa-trash-o"></i></a></td>';
                $result['html'] . '</tr>';
//print_r($result);
            }
        }
        echo json_encode($result);
        exit;
    }
    /**
     * Add new Custom lessons data using export
     */

if (isset($table_id) && !empty($table_id) && !empty($_POST['type']) && $_POST['type'] == 'Brail-export-entry') {
       
        
		$result = array();
		$test_name = $question_name = '';
		
		$student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
		$code = !empty($_POST['code']) ? $_POST['code'] : '';
		$id = !empty($_POST['table_id']) ? $_POST['table_id'] : '';
		$inserted = '';
		
		$check_exists = query('SELECT * FROM text_data WHERE id = "' . $student_id . '" AND code = "'.$code.'" ');
		if ($check_exists->num_rows > 0) {
			$fetch_exists_data = fetch($check_exists);
			$check_text_exists = query('SELECT * FROM text WHERE id = "' . $student_id . '" AND table_id = "' . $id . '"');
			if ($check_text_exists->num_rows > 0) {
				
				$fetch_text_exists_data = fetch($check_text_exists);
				
				$inserted = $fetch_exists_data['tbl_id'];
				$deck_name = $fetch_text_exists_data['title'];
				$data = $fetch_text_exists_data['data'];
                $number = $fetch_text_exists_data['number'];
				
				$UpdateData = array(
					'title' => $deck_name,
					'data' => $data,
                    'number' => $number,
				);

				update_query('text_data', 'table_id = "' . $inserted . '"', $UpdateData);
			}
			
		} else {
			
			$check_exists = query('SELECT * FROM text WHERE id = "' . $student_id . '" AND table_id = "' . $id . '"');
			if ($check_exists->num_rows > 0) {
				$fetch_exists_data = fetch($check_exists);
				$inserted = $fetch_exists_data['table_id'];
				$deck_name = $fetch_exists_data['title'];
				$data = $fetch_exists_data['data'];
                $number = $fetch_exists_data['number'];
                $app = $fetch_exists_data['app'];
				$date = date('Y-m-d H:i:s');
				
				$query = 'INSERT INTO `text_data` SET 
						  app    = "'.$app.'",
						  title  = "'.$deck_name.'",
						  id 	 = "'.$student_id.'",
						  number = "'.$number.'",
						  code   = "'.$code.'",
						  created_at = "'.$date.'",
						  data 	 =  "'.$data.'"';
				//Insert new data
				$inserted = mysqli_query($con, $query);
				$inserted = mysqli_insert_id($con);
			}
		}

		if ($inserted) {

			$result['status'] = 1;
			$result['table_id'] = $inserted;
			$result['msg'] = '<h4><i class="icon fa fa-check">Code copied to clipboard.</i></h4>';
			
		} else {
			$result['table_id'] = '';
			$result['status'] = 0;
			$result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
		}
		echo json_encode($result);
		exit;

    }
    if (isset($table_id) && !empty($table_id) && !empty($_POST['type']) && $_POST['type'] == 'typio-export-entry') {
       
        
		$result = array();
		$test_name = $question_name = '';
		
		$student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
		$code = !empty($_POST['code']) ? $_POST['code'] : '';
		$id = !empty($_POST['table_id']) ? $_POST['table_id'] : '';
		$inserted = '';
		
		$check_exists = query('SELECT * FROM text_data WHERE id = "' . $student_id . '" AND code = "'.$code.'" ');
		if ($check_exists->num_rows > 0) {
			$fetch_exists_data = fetch($check_exists);
			$check_text_exists = query('SELECT * FROM text WHERE id = "' . $student_id . '" AND table_id = "' . $id . '"');
			if ($check_text_exists->num_rows > 0) {
				
				$fetch_text_exists_data = fetch($check_text_exists);
				
				$inserted = $fetch_exists_data['tbl_id'];
				$deck_name = $fetch_text_exists_data['title'];
				$data = $fetch_text_exists_data['data'];
                $number = $fetch_text_exists_data['number'];
				
				$UpdateData = array(
					'title' => $deck_name,
					'data' => $data,
                    'number' => $number,
				);

				update_query('text_data', 'table_id = "' . $inserted . '"', $UpdateData);
			}
			
		} else {
			
			$check_exists = query('SELECT * FROM text WHERE id = "' . $student_id . '" AND table_id = "' . $id . '"');
			if ($check_exists->num_rows > 0) {
				$fetch_exists_data = fetch($check_exists);
				$inserted = $fetch_exists_data['table_id'];
				$deck_name = $fetch_exists_data['title'];
				$data = $fetch_exists_data['data'];
                $number = $fetch_exists_data['number'];
                $app = $fetch_exists_data['app'];
				$date = date('Y-m-d H:i:s');
				
				$query = 'INSERT INTO `text_data` SET 
						  app    = "'.$app.'",
						  title  = "'.$deck_name.'",
						  id 	 = "'.$student_id.'",
						  number = "'.$number.'",
						  code   = "'.$code.'",
						  created_at = "'.$date.'",
						  data 	 =  "'.$data.'"';
				//Insert new data
				$inserted = mysqli_query($con, $query);
				$inserted = mysqli_insert_id($con);
			}
		}

		if ($inserted) {

			$result['status'] = 1;
			$result['table_id'] = $inserted;
			$result['msg'] = '<h4><i class="icon fa fa-check"></i>Share Code '.$code.' copied to clipboard.</h4>';
			
		} else {
			$result['table_id'] = '';
			$result['status'] = 0;
			$result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
		}
		echo json_encode($result);
		exit;

    }
    /**
     * Add new Custom lessons data using import
     */

if (!empty($_POST['code']) && !empty($_POST['student_id']) && !empty($_POST['type']) && $_POST['type'] == 'Brail-import-entry') {
        
        $result = array();
        

		$student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
		$import_code = !empty($_POST['code']) ? trim($_POST['code']) : '';
		
		
		$inserted = false;
		
		$check_exists = query('SELECT * FROM text_data WHERE (app = "Typio-BRL" OR app = "Typio-Test") and code = "'.$import_code.'" ');
		if ($check_exists->num_rows > 0) {
			$fetch_text_exists_data = fetch($check_exists);
			$title = $fetch_text_exists_data['title'];
			$data = $fetch_text_exists_data['data'];
            $number = $fetch_text_exists_data['number'];
            $app = $fetch_text_exists_data['app'];
			$result['status'] = query("INSERT INTO `text` (`id`, `app`, `number`, `title`, `data`) VALUES ('" . $student_id . "','{$app}','{$number}','{$title}','{$data}')");
			//Insert new data
			$result['id'] = mysqli_insert_id($con);
			$result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson Added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
            $table_id = $result['id'];
            $result['html'] = '';
            $result['html'] .= '<tr class="lessons_tr_'.$table_id.'">';
            if ($_POST['student_id'] == $_SESSION['User']['id']) {
                $result['html'] .= '<td><input type="checkbox" name="share_Brail_ids[]" value="' . $table_id . '" class="share_Brail_ids"></td>';
            }
            $result['html'] .= '<td>' . $title . '</td>';
            $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-lessons-modalBRL" data-id="' . $table_id . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
            $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $table_id . '"><i class="fa fa-trash-o"></i></a></td>';
            $result['html'] . '</tr>';    
		} else {
			$result['table_id'] = '';
			$result['status'] = 0;
			$result['msg'] = '<h4><i class="icon fa fa-warning"></i> Code invalid, please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
		}
		echo json_encode($result);
		exit;
    }

    if (!empty($_POST['code']) && !empty($_POST['student_id']) && !empty($_POST['type']) && $_POST['type'] == 'typio-import-entry') {
        
        $result = array();
        

		$student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
		$import_code = !empty($_POST['code']) ? trim($_POST['code']) : '';
		
		
		$inserted = false;
		
		$check_exists = query('SELECT * FROM text_data WHERE (app = "Typio-OL" OR app = "Typio-Test") and code = "'.$import_code.'" ');
		if ($check_exists->num_rows > 0) {
			$fetch_text_exists_data = fetch($check_exists);
			$title = $fetch_text_exists_data['title'];
			$data = $fetch_text_exists_data['data'];
            $number = $fetch_text_exists_data['number'];
            $app = $fetch_text_exists_data['app'];
			$result['status'] = query("INSERT INTO `text` (`id`, `app`, `number`, `title`, `data`) VALUES ('" . $student_id . "','{$app}','{$number}','{$title}','{$data}')");
			//Insert new data
			$result['id'] = mysqli_insert_id($con);
			$result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson Added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
            $table_id = $result['id'];
            $result['html'] = '';
            $result['html'] .= '<tr class="lessons_tr_'.$table_id.'">';
            if ($_POST['student_id'] == $_SESSION['User']['id']) {
                $result['html'] .= '<td><input type="checkbox" name="share_typio_ids[]" value="' . $table_id . '" class="share_typio_ids"></td>';
            }
            $result['html'] .= '<td>' . $title . '</td>';
            $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-lessons-modal" data-id="' . $table_id . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
            $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $table_id . '"><i class="fa fa-trash-o"></i></a></td>';
            $result['html'] . '</tr>';    
		} else {
			$result['table_id'] = '';
			$result['status'] = 0;
			$result['msg'] = '<h4><i class="icon fa fa-warning"></i> Code invalid, please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
		}
		echo json_encode($result);
		exit;
    }
    
    /**
     * Update custom lessons data from modal popup
     */

if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Update-LessonsBRL') {

        $result = array();
        parse_str($_POST['form_data'], $form_data);

        //Check Fileds data exist
        if (!empty($form_data['dataBRL'])) {
            if (!empty($form_data['table_idBRL'])) {
                $user_type = $_POST['user_type'];

                $data = mysqli_real_escape_string($con, $form_data['dataBRL']);
                $result['status'] = query('UPDATE `text` SET title = "' . $form_data['titleBRL'] . '", data = "' . $data . '" WHERE table_id="' . $form_data['table_idBRL'] . '"');
                $result['msg'] = '<h4><i class="icon fa fa-check"></i> Custom lesson updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';

                $result['html'] = '';
                $result['html'] .= '<tr class="lessons_tr_' . $form_data['table_id'] . '">';
                if ($user_type == 1) {
                    $result['html'] .= '<td><input type="checkbox" name="share_Brail_ids[]" value="' . $form_data['table_idBRL'] . '" class="share_Brail_ids"></td>';
                }
                $result['html'] .= '<td>' . $form_data['title'] . '</td>';
                $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-lessons-modalBRL" data-id="' . $form_data['table_idBRL'] . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
                $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $form_data['table_idBRL'] . '"><i class="fa fa-trash-o"></i></a></td>';
                $result['html'] . '</tr>';

                $result['table_id'] = $form_data['table_idBRL'];
            }
        }
        if (empty($result['status'])) {
            $result['status'] = 0;
            $result['msg'] = '<h2><i class="icon fa fa-warning" aria-hidden="true"></i> Somethings went wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h2>';
        }
        echo json_encode($result);
        exit;
    }

if (isset($table_id) && !empty($table_id) && isset($type) && $type == 'Update-LessonsBRL') {
        $sql = query('SELECT * FROM text WHERE table_id="' . $table_id . '"');
        $data = fetch($sql);
        $html = '';
        
        if (!empty($data)) {
            $html .= '<div class="lessions-editBRL">';
            $html .= '<form id="lessons-edit-formBRL" name="lessions-editBRL" class="tets">';
            $html .= '<div class="form-group"><input type="text" id="title-text-editBRL" name="titleBRL" class="form-control lesson_title_edit_' . $_POST['table_id'] . '" placeholder="Type lesson title here..." aria-label="Lesson title field. Type lesson title here." required="" value="' . stripslashes($data['title']) . '" onkeyup="check_duplicate_lesson_edit(' . $_POST['table_id'] . ')"><input type="hidden" class="lesson_hidded_input_errorBRL" value="" /><span class="lesson_title_error lesson_title_error_' . $_POST['table_id'] . '" style="color:red;"></span></div>';
            $html .= '<input type="hidden" class="table_id" name="table_idBRL" value="' . $_POST['table_id'] . '">';
            $html .= '<input type="hidden" class="data_type" name="data_typeBRL" value="' . (isset($data_type) ? $data_type : '') . '">';
            if (!empty($data['data'])) {
                $html .= '<p><textarea id="textareaID2BRL" placeholder="Type lesson text here..." class="form-control" cols="55" rows="10" name="dataBRL" aria-label="Lesson text field. Type lesson text here.">' . stripslashes($data['data']) . '</textarea></p>';
            }
            if (!empty($data['number'])) {
                $html .= '<p><input type="number" name="lesson_edit_attemptBRL" id="lesson_edit_attemptBRL" value="'.$data['number'].'" class="form-control" min="1"></p>';
            }else{
                if(isset($data_type) && $data_type == "typing-test-lessons"){
                    $html .= '<p><input type="number" name="lesson_edit_attemptBRL" id="lesson_edit_attemptBRL" value="1" class="form-control" min="1" ></p>';
                }
            }
            $html .= '</form>';
            $html .= '</div>';
        }
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }
    if (isset($table_id) && !empty($table_id) && isset($type) && $type == 'Update-Lessons') {
        $sql = query('SELECT * FROM text WHERE table_id="' . $table_id . '"');
        $data = fetch($sql);
        $html = '';
        
        if (!empty($data)) {
            $html .= '<div class="lessions-edit">';
            $html .= '<form id="lessons-edit-form" name="lessions-edit" class="tets">';
            $html .= '<label id="" >Lesson Title</label>';
            
            $html .= '<div class="form-group"><input type="text" id="title-text-edit" name="title" class="form-control lesson_title_edit_' . $_POST['table_id'] . '" placeholder="Type lesson title here..." aria-label="Lesson title field. Type lesson title here." required="" value="' . stripslashes($data['title']) . '" onkeyup="check_duplicate_lesson_edit(' . $_POST['table_id'] . ')"><input type="hidden" class="lesson_hidded_input_error" value="" /><span class="lesson_title_error lesson_title_error_' . $_POST['table_id'] . '" style="color:red;"></span></div>';
            $html .= '<input type="hidden" class="table_id" name="table_id" value="' . $_POST['table_id'] . '">';
            $html .= '<input type="hidden" class="data_type" name="data_type" value="' . (isset($data_type) ? $data_type : '') . '">';
            if (!empty($data['data'])) {
				$html .= '<label id="" >Lesson Text</label>';
                $html .= '<p><textarea id="textareaID2" placeholder="Type lesson text here..." class="form-control" cols="55" rows="10" name="data" aria-label="Lesson text field. Type lesson text here.">' . stripslashes($data['data']) . '</textarea></p>';
            }
			
            if (!empty($data['number'])) {
				$html .= '<label>Attempts</label>';
                $html .= '<p><input type="number" name="lesson_edit_attempt" id="lesson_edit_attempt" value="'.$data['number'].'" class="form-control" min="1"></p>';
            }else{
                if(isset($data_type) && $data_type == "typing-test-lessons"){
                    $html .= '<p><input type="number" name="lesson_edit_attempt" id="lesson_edit_attempt" value="1" class="form-control" min="1" ></p>';
                }
            }
            $html .= '</form>';
            $html .= '</div>';
        }
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }
    /**
     * Update custom lessons data from modal popup
     */
    if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Update-Lessons') {
        $result = array();
        parse_str($_POST['form_data'], $form_data);

        //Check Fileds data exist
        if (!empty($form_data['data'])) {
            if (!empty($form_data['table_id'])) {
                $user_type = $_POST['user_type'];

                $data = mysqli_real_escape_string($con, $form_data['data']);
                $result['status'] = query('UPDATE `text` SET title = "' . $form_data['title'] . '", data = "' . $data . '" WHERE table_id="' . $form_data['table_id'] . '"');
                $result['msg'] = '<h4><i class="icon fa fa-check"></i> Custom lesson updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';

                $result['html'] = '';
                $result['html'] .= '<tr class="lessons_tr_' . $form_data['table_id'] . '">';
                if ($user_type == 1) {
                    $result['html'] .= '<td><input type="checkbox" name="share_typio_ids[]" value="' . $form_data['table_id'] . '" class="share_typio_ids"></td>';
                }
                $result['html'] .= '<td>' . $form_data['title'] . '</td>';
                $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-lessons-modal" data-id="' . $form_data['table_id'] . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
                $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $form_data['table_id'] . '"><i class="fa fa-trash-o"></i></a></td>';
                $result['html'] . '</tr>';

                $result['table_id'] = $form_data['table_id'];
            }
        }
        if (empty($result['status'])) {
            $result['status'] = 0;
            $result['msg'] = '<h2><i class="icon fa fa-warning" aria-hidden="true"></i> Somethings went wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h2>';
        }
        echo json_encode($result);
        exit;
    }

    /**
     * Delete custom lessons data
     */
    if (!empty($_POST['delete_id']) && !empty($_POST['type']) && $_POST['type'] == 'lessons-delete') {

        global $con;

        if (!empty($_POST['delete_id'])) {
            mysqli_query($con, 'DELETE FROM `text` WHERE table_id="' . $_POST['delete_id'] . '"');
            $result['status'] = 1;
            $result['msg'] = '<h2><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h2>';
        }

        if (empty($result['status'])) {
            $result['status'] = 0;
            $result['msg'] = '<h2><i class="icon fa fa-warning" aria-hidden="true"></i> Somethings went wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h2>';
        }
        echo json_encode($result);
        exit;
    }




    if (!empty($_POST['delete_id']) && !empty($_POST['type']) && $_POST['type'] == 'historys-delete') {

        global $con;

        if (!empty($_POST['delete_id'])) {

            $delete = mysqli_query($con, 'DELETE FROM `log` WHERE lognr = "' . $_POST['delete_id'] . '"');
            $result['status'] = 1;
            $result['msg'] = '<h2><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h2>';
        }
        if (empty($result['status'])) {
            $result['status'] = 0;
            $result['msg'] = '<h2><i class="icon fa fa-warning" aria-hidden="true"></i> Somethings went wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h2>';
        }
        echo json_encode($result);
        exit;
    }	
	
	
	if (!empty($_POST['delete_id']) && !empty($_POST['type']) && $_POST['type'] == 'play') {
		
        global $con;
		
        $cookie_name = "TY-Replay";
		
        $data = "SELECT id,data FROM `log` where lognr='" . $_POST['delete_id'] . "'";
        $res_data = query($data);
        $re_data = fetch($res_data);
		
        $result['status'] = 0;
        if (!empty($re_data) && !empty($re_data['data'])) {
			//$data = explode('|',$re_data['data']);
            //$lastData = end($data);            
			 $lastData = $re_data['data'];
			// print_r($lastData);die;
            //if (!isset($_COOKIE[$cookie_name])) {                
                unset($_COOKIE[$cookie_name]);
                //setrawcookie($cookie_name, $lastData, time() + (86400 * 30), "/"); // 1 day
                //setcookie($cookie_name, $lastData, time() + (86400 * 30), "/"); // 1 day
            //}
            $result['status'] = 1;     
            $result['TY_Replay'] =   $lastData;   
		
        }        
        echo json_encode($result);
        exit;
    }
	 /**
     * Add new text data import using csv
     */
    /*if (!empty($_POST['type']) && $_POST['type'] == 'typio-csv-import-entry') {
        $student_id =$_POST['student_id'];
        $insert_type =$_POST['insert_type'];
        $student_overview_page =$_POST['student_overview_page'];

        $row = 1;
        if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {
          $i=0;
          $field_value ="";
          $html ="";

          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            $csv_type="";
            //if($i == 0){
                $insert_data ="insert into text(id,app,title,number,data)values";
           // }

             if($i > 0){
                $field_value =$title= $csv_data_count="";
                
                    for ($c=0; $c < $num; $c++) {
                        
                            if($c == 0){
                                $csv_type  =$data[$c];
                               /* if($insert_type  != $data[$c]){

                                    break;
                                }*/
                              /* $field_value .="(".'"'.$student_id.'"'.',"QC-OL",'; 
                            }
                            if($c +1 == $num){
                               if($insert_type == "Typio-OL"){
                                    $number  ="0";
                               }
                               else if($insert_type == "QC-OL"){
                                     $number  ="0";
                               }
                               else if($insert_type == "Arcade-OL"){
                                     $number  ="2";
                               }
                               $field_value .='"'.$number.'"'.","; 
                               $field_value .='"'.$data[$c].'"'; 
                            }
                            else{
                                $field_value .='"'.$data[$c].'"'.",";
                                }
                            
                            if($c +1 == $num){
                               // $field_value .="),"; 
                                $field_value .=")";
                            }
                            if($c==0){
                                $title =$data[$c];
                            }
                            if($insert_type == "QC-OL"){
                                if($c ==1){
                                    $csv_data =$data[$c];
                                    $str_pos =strpos($csv_data,"|");
                                    $csv_data_count =0;
                                    if($str_pos > 0){
                                        $csv_data_count=count(explode('|',  $csv_data));
                                    }

                                }
                            } 
                     }

                    if($insert_type == "Typio-OL"){
                        $model_name ="edit-lessons-modal";
                        $delete_model_name ="delete-modal-set";
                        $delete_class ="lesson-delete";
                    }
                    else if($insert_type == "QC-OL"){
                       $model_name ="student-decks-maker_type_1_edit"; 
                    }
                    else if($insert_type == "Arcade-OL"){
                       $model_name ="edit-hangman-modal"; 
                    }
                    $inserted = mysqli_query($con, $insert_data.trim($field_value,","));
                    $last_inserted_id = mysqli_insert_id($con);
                  //  if($insert_type  == $csv_type){
                            $html .="<tr>";
                            if($student_overview_page !='1'){
                                $html .='<td><input type="checkbox" name="share_hangman_ids[]" value="'.$last_inserted_id.'" class="share_hangman_ids"></td>';
                             }
                             $html .="<td>". $title."</td>";
                             if($insert_type == "QC-OL"){
                                 $html .="<td>".$csv_data_count . '</td>';
                             }

                             $html .='<td><a href="javascript:void(0)" class="badge bg-green '.$model_name.'" data-id="'.$last_inserted_id.'"><i class="fa fa-edit" aria-label="Edit"></i></a></td>';
                             if($insert_type == "Typio-OL"){ 
                                    $html .='<td><a href="javascript:void(0)" class="badge bg-red '.$delete_class.'" data-toggle="modal" data-target="#'. $delete_model_name.'" data-id="'.$last_inserted_id.'"><i class="fa fa-trash-o" aria-label="Delete"></i></a></td>';
                              }
                              if($insert_type == "QC-OL"){ 
                                    $html .='<td><a href="javascript:void(0)" class="badge bg-red student-decks-maker_type_1_delete" data-id="'.$last_inserted_id.'"><i class="fa fa-trash-o" aria-label="Delete"></i></a>';
                              }
                              if($insert_type == "Arcade-OL"){ 
                                    $html .='<td><a href="javascript:void(0)" class="badge bg-red delete-hangman" data-toggle="modal" data-target="#delete-modal-hangman" data-id="'.$last_inserted_id.'">
                                        <i class="fa fa-trash-o" aria-label="Delete"></i></a></td>';
                              }

                             $html .='</tr>';
                   //  }
              }
              $i++;
          }
          fclose($handle);
         
          $result['status'] = 1;
          $result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Csv data is imported successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
          $result['html']=$html;

        }
        
        echo json_encode($result);
        exit;
    }*/
    if (!empty($_POST['type']) && $_POST['type'] == 'typio-csv-import-entry') {
        $student_id =$_POST['student_id'];
        $insert_type =$_POST['insert_type'];
        $student_overview_page =$_POST['student_overview_page'];

        $row = 1;

        //$file_extension = get_file_extension($_FILES['file']['name']);
        $file_extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {
          $i=0;
          $field_value ="";
          $html =$data1="";
          $insert_data ="insert into text(id,app,title,number,data)values";
           // }
                //$field_value .="(".'"'.$student_id.'"'.',"QC-OL",'; 
          $file=$_FILES['file']['name'];
          $file_name = substr($file, 0, strrpos($file, '.')); 
          $field_value .="(".'"'.$student_id.'"'.',"QC-OL",'.'"'.$file_name.'"'; 
           $title= $csv_data_count="";
           $title =$file_name;
          if($file_extension == "csv"){
             while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { 
            
            $num = count($data);
            $csv_type="";
            //if($i == 0){

             //if($i > 0){
              
                    if($i > 0){
                        $data1 .=" | ";
                    }
                    for ($c=0; $c < $num; $c++) {
                        
                            if($c== 0){
                                $data1 .=$data[$c];
                            }else{
                                $data1 .="`".$data[$c];
                            }
                            
                            if($insert_type == "QC-OL"){
                                if($c ==1){
                                    $csv_data =$data[$c];
                                    $str_pos =strpos($csv_data,"|");
                                    $csv_data_count =0;
                                    if($str_pos > 0){
                                        $csv_data_count=count(explode('|',  $csv_data));
                                    }

                                }
                            } 
                     }

              //}
              $i++;
            }
         } 
         if($file_extension == "txt"){
             while ($line = fgets($handle)) {
            
            $data =explode(",",$line);
            //echo "<pre>"; print_r($data); exit;
            $num = count($data);
            $csv_type="";
            //if($i == 0){

             //if($i > 0){
              
                    if($i > 0){
                        $data1 .=" | ";
                    }
                    for ($c=0; $c < $num; $c++) {
                        
                            if($c== 0){
                                $data1 .=$data[$c];
                            }else{
                                $data1 .="`".$data[$c];
                            }
                            
                            if($insert_type == "QC-OL"){
                                if($c ==1){
                                    $csv_data =$data[$c];
                                    $str_pos =strpos($csv_data,"|");
                                    $csv_data_count =0;
                                    if($str_pos > 0){
                                        $csv_data_count=count(explode('|',  $csv_data));
                                    }

                                }
                            } 
                     }

              //}
              $i++;
            }
         } 
          $str_pos =strpos($data1,"|");
          $csv_data_count =0;
          if($str_pos > 0){
              $csv_data_count=count(explode('|',  $data1));
          }
          $model_name ="student-decks-maker_type_1_edit"; 
          $inserted = mysqli_query($con, $insert_data.trim($field_value,",").",0,'".$data1."')");
                $last_inserted_id = mysqli_insert_id($con);

               $html .="<tr>";
                            if($student_overview_page !='1'){
                                $html .='<td><input type="checkbox" name="share_hangman_ids[]" value="'.$last_inserted_id.'" class="share_hangman_ids"></td>';
                             }
                             $html .="<td>". $title."</td>";
                             if($insert_type == "QC-OL"){
                                 $html .="<td>".$csv_data_count . '</td>';
                             }

                            $html .='<td><a href="javascript:void(0)" class="badge bg-green '.$model_name.'" data-id="'.$last_inserted_id.'"><i class="fa fa-edit" aria-label="Edit"></i></a></td>';
                            
                                   $html .='<td><a href="javascript:void(0)" class="badge bg-red student-decks-maker_type_1_delete" data-id="'.$last_inserted_id.'"><i class="fa fa-trash-o" aria-label="Delete"></i></a></td>';
                           
                             $html .='</tr>';

          fclose($handle);
         
          $result['status'] = 1;
          $result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Csv data is imported successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';
          $result['html']=$html;

        }
        
        echo json_encode($result);
        exit;
    }

     /**
     * Add new test data import using csv
     */
    if (!empty($_POST['type']) && $_POST['type'] == 'typio-csv-import-entry-test') {
        $student_id =$_POST['student_id'];
        $insert_type =$_POST['insert_type'];

        $row = 1;
        if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {
          $i=0;
          $field_value ="";
          $html ="";
          $inserted_data_str ="";
          $insert_data ="insert into text(id,app,title,number,data)values";
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            $field_value="";
             if($i > 0){

                    for ($c=0; $c < $num; $c++) {
                        
                            if($c == 0){
                               /* if($insert_type  != $data[$c]){
                                    break;
                                }*/
                               $field_value .="(".'"'.$student_id.'"'.',"QC-OL",'; 
                            }
                            if($c +1 == $num){
                               $field_value .='1,'; 
                               $field_value .='"'.$data[$c].'"'; 
                            }
                            else{
                                $field_value .='"'.$data[$c].'"'.",";
                                }
                            if($c ==0){
                                $title =$data[$c];
                            }
                            if($c==1){
                                $data =$data[$c];
                                    $csv_data =$data[$c];
                                    $str_pos =strpos($csv_data,"|");
                                    $csv_data_count =0;
                                    if($str_pos > 0){
                                        $csv_data_count=count(explode('|',  $csv_data));
                                    }

                            }
                            
                            if($c +1 == $num){
                               // $field_value .="),"; 
                                 $field_value .=")";
                            }
                    }
                    $inserted = mysqli_query($con, $insert_data.trim($field_value,","));
                    $last_inserted_record = mysqli_insert_id($con);
                    if($inserted_data_str !=""){
                         $inserted_data_str .=",".$last_inserted_record; 
                    }else{
                        $inserted_data_str =$last_inserted_record;
                    }
                   
                    $html .='<tr class="test_'.$last_inserted_record.'"><td><input type="checkbox" name="share_quick_card_test_ids[]" value="'.$last_inserted_record.'" class="share_quick_card_test_ids"></td><td>'. $title.'</td><td>'. $csv_data_count.'</td><td><a href="javascript:void(0)" class="badge bg-green student-decks-maker_type_2_edit" data-id="'.$last_inserted_record.'"><i class="fa  fa-edit (alias)"></i></a></td><td><a href="javascript:void(0)" class="badge bg-red delete student-decks-maker_type_2_delete" data-id="'.$last_inserted_record.'"><i class="fa fa-trash-o" aria-label="Delete"></i></a></td></tr>';
              }
              $i++;
          }
          fclose($handle);
          
          $result['status'] = 1;
          $result['html'] =$html; 
          $result['inserted_ids'] =$inserted_data_str; 
          $result['msg'] = '<h4><i class="icon fa fa-check" aria-hidden="true"></i> Csv data is imported successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h4>';

        }
        
        echo json_encode($result);
        exit;
    }
}
?>