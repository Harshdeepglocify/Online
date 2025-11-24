<?php

include "config-student.php";
global $con;

/**
 * Update setting for Quick Cards field
 * 
 * @param int $item_id 
 * @param string $update_typio_setting_variable 
 * @return boolean Typio-OL table update status
 */
function update_qc_setting_field($student_id, $item_id, $value) {
    global $con;

    $table_name = 'settings';
    $query = "SELECT * FROM `" . $table_name . "` WHERE `id`='" . $student_id . "' AND `item`=" . $item_id;
    $result = mysqli_fetch_assoc(mysqli_query($con, $query));

    if (!empty($result)) {
        $query = "UPDATE `" . $table_name . "` SET `variable`='" . $value . "' WHERE `id`='" . $student_id . "' AND `item`=" . $item_id;
        $result = mysqli_query($con, $query);
    } else {
        $query = "INSERT INTO `" . $table_name . "` SET `variable`='" . $value . "', `id`='" . $student_id . "',  `item`=" . $item_id;
        $result = mysqli_query($con, $query);
    }

    return $result;
}

/**
 * Edit Form Process
 */
if (!empty($_POST['table_id']) && !empty($_POST['type']) && $_POST['type'] == 'Decks-edit') {

    $data = mysqli_fetch_assoc(mysqli_query($con, 'SELECT * FROM text WHERE table_id="' . $_POST['table_id'] . '"'));

    if (!empty($data)) {

        $html = '';
        $html .= '<div class="decks-edit">';

        $html .= '<form id="decks-edit-form" name="decks-edit">';
        $html .= '<input type="hidden" name="table_id" value="' . $_POST['table_id'] . '">';

        if (!empty($data['data'])) {
            $html .= '<p>Name</p>';
            $html .= '<p><input class="form-control" type="text" value="' . $data['title'] . '" name="title"></p>';
            $get_row = explode('`', $data['data']);
            $html .= '<p>Your Decks List</p>';
            foreach ($get_row as $key => $value) {
                $html .= '<p><input class="form-control" type="text" value="' . $value . '" name="fields[' . $key . ']"></p>';
            }
        }
        $html .= '</form>';
        $html .= '</div>';
    }
    echo json_encode(array('status' => 200, 'html' => $html));
    exit;
}

/**
 * Edit Form Save Process
 */
if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Decks-edit') {

    $result = array();

    parse_str($_POST['form_data'], $form_data);

    //Check Fileds data exist
    if (!empty($form_data['fields'])) {

        foreach ($form_data['fields'] as $key => $value) {

            //Check fields value blank then remove
            if (empty($value)) {
                unset($form_data['fields'][$key]);
            }
        }

        if (!empty($form_data['table_id'])) {
            $data = implode('`', $form_data['fields']);
            $result['status'] = mysqli_query($con, 'UPDATE `text` SET data = "' . $data . '" WHERE table_id="' . $form_data['table_id'] . '"');
            $result['msg'] = '<h4><i class="icon fa fa-check"></i> Decks updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        }
    }

    //Update Title fileds
    if (!empty($form_data['title'])) {

        $result['status'] = mysqli_query($con, 'UPDATE `text` SET title = "' . $form_data['title'] . '" WHERE table_id="' . $form_data['table_id'] . '"');
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> Decks updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }

    if (empty($result['status'])) {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    echo json_encode($result);
    exit;
}

/**
 * Edit Form Save Process
 */
if (!empty($_POST['delete_id']) && !empty($_POST['type']) && $_POST['type'] == 'Decks-delete') {

    if (!empty($_POST['delete_id'])) {

        mysqli_query($con, 'DELETE FROM `text` WHERE table_id="' . $_POST['delete_id'] . '"');
        $result['status'] = 1;
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> Decks deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </h4> ';
    }

    if (empty($result['status'])) {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }

    echo json_encode($result);
    exit;
}

//Get History of Quick cards
if (!empty($_POST['start_date']) && !empty($_POST['end_date']) && $_POST['type'] == 'quick-card-history') {

    $chart_html = $table_html = '';
    $result = array();

    $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
    $start_date = !empty($_POST['start_date']) ? date('Y-m-d', strtotime($_POST['start_date'])) : '';
    $end_date = !empty($_POST['end_date']) ? date('Y-m-d', strtotime($_POST['end_date'])) : '';

    //History data filter
    $qc_history_data = get_QuickCards_data($student_id, $start_date, $end_date);

    $chart_html .= '<tr>  <th></th>  <th>Percent</th> </tr>';
    
    $table_html .= '<tr>  <th>Name</th> <th>Percent</th> <th># Incorrect</th> <th>Missed Cards</th><th>Date</th> </tr>';
    if (!empty($qc_history_data)) {
        foreach ($qc_history_data as $key => $value) {
            
            $chart_html .= '<tr> <td>' . $value['file'] . '</td>  <td>' . $value['percent_score'] . '</td>  </tr>';
            $table_html .= '<tr>';
            $table_html .= '<td>' . $value['file'] . '</td>';
            $table_html .= '<td>' . $value['percent_score'] . '%</td>';
            $table_html .= '<td>' . $value['incorrect'] . '</td>';
            $table_html .= '<td>' . implode(',', $value['cards_missed']) . '</td>';
            $table_html .= '<td>' . date('m/d/y', strtotime($value['date'])) . '</td>';
            $table_html .= '<td>';
            $table_html .= '<a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red history-delete" data-id=' . $value['lognr'] . '><i class="fa fa-trash-o"></i></a>';
            $table_html .= '</td>';
            $table_html .= '</tr>';
               
        }
    }

    //Check table data avaliable
    if (!empty($table_html)) {
        $result['table_html'] = $table_html;
        $result['table_count_msg'] = count($qc_history_data) . ' Lessons complete';
        $result['status'] = 1;
    }

    //Check Chart data avaliable
    if (!empty($chart_html)) {
        $result['chart_html'] = $chart_html;
        $result['status'] = 1;
    }

    //Check result exist another send error
    if (empty($result)) {
        $result['error'] = 1;
        $result['table_count_msg'] = '0 Lessons complete';
    }

    echo json_encode($result);
    exit;
}

//Get History of Quick cards
if (!empty($_POST['student_id']) && !empty($_POST['form_data']) && $_POST['type'] == 'qc-settings-process') {

    $result = array();
    $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
    $form_data = !empty($_POST['form_data']) ? $_POST['form_data'] : '';
    $data_type = !empty($_POST['data_type']) ? $_POST['data_type'] : '';

    parse_str($form_data, $form_data);

    if (!empty($form_data['qc-options'])) {

        $form_data = $form_data['qc-options'];

        $args = array(
            'user_id' => $student_id,
            'role' => 'student',
        );
        $student_data = get_users($args);
        foreach ($form_data as $key => $value) {
            if($data_type == 'all'){                 
                if(isset($student_data[0]) && !empty($student_data[0])){
                    $args = array(
                        'teacher_code' => $student_data[0]['teacher'],
                        'role' => 'student',
                    );
                    $student_all_data = get_users($args);
                    foreach($student_all_data as $sval){    
                        update_qc_setting_field($sval['id'], $key, $value);                        
                    }    
                } else {
                    update_qc_setting_field($student_id, $key, $value);    
                }                
            } else {
                update_qc_setting_field($student_id, $key, $value);
            }
        }

        $result['status'] = 1;
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> Settings updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }

    if (empty($result)) {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    echo json_encode($result);
    exit;
}




//Save new decks entry
if (!empty($_POST['student_id']) && !empty($_POST['decks_new_data']) && $_POST['type'] == 'decks-new-entry') {

    $result = array();
    $card_new_data = $decks_title = $number = $html = '';

    $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
    $decks_new_data = !empty($_POST['decks_new_data']) ? $_POST['decks_new_data'] : '';

    parse_str($decks_new_data, $decks_new_data);

    //Check new cards added
    if (!empty($decks_new_data['qc-cards-new'])) {

        $card_new_data = $decks_new_data['qc-cards-new'];

        foreach ($card_new_data as $key => $value) {
            //Check and remove blank entrty
            if (empty($value)) {
                unset($card_new_data[$key]);
            }
        }

        //Count cards
        $number = count($card_new_data);
        $card_new_data = implode('`', $card_new_data);
    }

    $decks_title = !empty($decks_new_data['title']) ? $decks_new_data['title'] : '';

    $query = "INSERT INTO `text` SET 
			  app    = 'Quick-Cards-OL',
			  title  = '$decks_title',
			  id 	 = '$student_id',
			  number = '$number',
			  data 	 =  '$card_new_data'";

    //Insert new data
    $inserted = mysqli_query($con, $query);

    if ($inserted) {
        $inserted = mysqli_insert_id($con);
        $result['status'] = 1;
        $result['msg'] = '<h4><i class="icon fa fa-check"></i>New lessons added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        $result['html'] = '<tr><td><input name="share_quick_card_ids[]" value="' . $inserted . '" class="share_quick_card_ids" type="checkbox"></td>
								<td>' . $decks_title . '</td>
								<td>' . $number . '</td>
								<td><a href="javascript:void(0)" class="badge bg-green edit-data-modal" data-id="' . $inserted . '"> <i class="fa  fa-edit (alias)"></i></a></td>
                    			<td><a href="javascript:void(0)" class="badge bg-red delete" data-id="' . $inserted . '"><i class="fa fa-trash-o"></i></a></td>
                   			</tr>';
    } else {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again.</h4>';
    }
    echo json_encode($result);
    exit;
}


//Save new decks entry for student
if (!empty($_POST['student_id']) && !empty($_POST['students_decks_new_data']) && $_POST['type'] == 'student-decks-new-entry') {
	
    $result = array();
    $side_b_data = $side_a_data = $decks_title = $number = $html = '';
    $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
    $students_decks_new_data = !empty($_POST['students_decks_new_data']) ? $_POST['students_decks_new_data'] : '';

    parse_str($students_decks_new_data, $students_decks_new_data);

    //Check new cards added
    if (!empty($students_decks_new_data['side_A_data']) && !empty($students_decks_new_data['side_B_data'])) {
        $side_a_data = $students_decks_new_data['side_A_data'];
        $side_b_data = $students_decks_new_data['side_B_data'];
        // $all_array = [$side_a_data,$side_b_data];
        // $length = count($all_array);
        $data = '';
        for ($i = 0; $i < count($side_a_data); $i++) {
            $data .= addslashes($side_a_data[$i]) . '`';
            $data .= addslashes($side_b_data[$i]) . '|';
        }
        $data = rtrim($data, '|');
        //$number = count($data);
        //$number = count( $data );
        //$data = implode( '`', $data );
    }

    $deck_name = !empty($students_decks_new_data['deck_name']) ? addslashes($students_decks_new_data['deck_name']) : '';

    $check_exists = query('SELECT * FROM text WHERE id = "' . $student_id . '" AND app = "QC-OL" AND title = "' . $deck_name . '" AND number = 0 ');
    if ($check_exists->num_rows > 0) {
        $fetch_exists_data = fetch($check_exists);
        $inserted = $fetch_exists_data['table_id'];

        $UpdateData = array(
            'title' => $deck_name,
            'data' => $data,
        );

        update_query('text', 'table_id = "' . $inserted . '"', $UpdateData);

        $get_data_query = query('SELECT * FROM text WHERE table_id = "' . $inserted . '" ');
        $data_result = fetch($get_data_query);

        $deck_name = $data_result['title'];
        $data = $data_result['data'];
    } else {
    $query = "INSERT INTO `text` SET 
			  app    = 'QC-OL',
			  title  = '$deck_name',
			  id 	 = '$student_id',
			  number =  0,
			  data 	 =  '$data'";
    //Insert new data
    $inserted = mysqli_query($con, $query);
        $inserted = mysqli_insert_id($con);
    }

    if ($inserted) {

        $result['status'] = 1;
        $result['table_id'] = $inserted;
        $result['msg'] = '<h4><i class="icon fa fa-check"></i>New decks added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </h4>';
        $result['html'] = '<tr class="deck_' . $inserted . '">';
        if ($_POST['student_id'] == $_SESSION['User']['id']) {
            $result['html'] .= '<td><input type="checkbox" name="share_quick_card_deck_ids[]" value=' . $inserted . ' class="share_quick_card_deck_ids"></td>';
        }
        $result['html'] .= '<td>' . stripslashes($deck_name) . '</td>
								<td>' . (!empty($data) ? count(explode('|', $data)) : 0) . '</td>
								<td><a href="javascript:void(0)" class="badge bg-green student-decks-maker_type_1_edit" data-id="' . $inserted . '"> <i class="fa fa-edit (alias)"></i></a></td>
                    			<td><a href="javascript:void(0)" class="badge bg-red student-decks-maker_type_1_delete" data-id="' . $inserted . '"><i class="fa fa-trash-o"></i></a></td>
                   			</tr>';
    } else {
        $result['table_id'] = '';
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    echo json_encode($result);
    exit;
}
//Save new decks entry for student using code

if (!empty($_POST['student_id']) && !empty($_POST['students_decks_new_data']) && $_POST['type'] == 'student-decks-new-import-entry') {
	
    $result = array();
    $side_b_data = $side_a_data = $decks_title = $number = $html = '';
    $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
    $import_code = !empty($_POST['students_decks_new_data']) ? trim($_POST['students_decks_new_data']) : '';
	
    
	$inserted = false;
	
    $check_exists = query('SELECT * FROM text_data WHERE number = 0 and code = "'.$import_code.'" ');
    if ($check_exists->num_rows > 0) {
		    $fetch_text_exists_data = fetch($check_exists);
			$deck_name = $fetch_text_exists_data['title'];
			$data = $fetch_text_exists_data['data'];
		    $query = 'INSERT INTO `text` SET 
				  app    = "QC-OL",
				  title  = "'.$deck_name.'",
				  id 	 = "'.$student_id.'",
				  number =  0,
				  data 	 =  "'.$data.'"';
		//Insert new data
		$inserted = mysqli_query($con, $query);
		$inserted = mysqli_insert_id($con);

    }

    if ($inserted) {

        $result['status'] = 1;
        $result['table_id'] = $inserted;
        $result['msg'] = '<h4><i class="icon fa fa-check"></i>New decks added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </h4>';
        $result['html'] = '<tr class="deck_' . $inserted . '">';
        if ($_POST['student_id'] == $_SESSION['User']['id']) {
            $result['html'] .= '<td><input type="checkbox" name="share_quick_card_deck_ids[]" value=' . $inserted . ' class="share_quick_card_deck_ids"></td>';
        }
        $result['html'] .= '<td>' . $deck_name . '</td>
								<td>' . (!empty($data) ? count(explode('|', $data)) : 0) . '</td>
								<td><a href="javascript:void(0)" class="badge bg-green student-decks-maker_type_1_edit" data-id="' . $inserted . '"> <i class="fa fa-edit (alias)"></i></a></td>
                    			<td><a href="javascript:void(0)" class="badge bg-red student-decks-maker_type_1_delete" data-id="' . $inserted . '"><i class="fa fa-trash-o"></i></a></td>
                   			</tr>';
    } else {
        $result['table_id'] = '';
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Code invalid, please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    echo json_encode($result);
    exit;
}

//Save export entry decks entry for student using code

if (!empty($_POST['student_id']) && !empty($_POST['students_decks_new_data']) && $_POST['type'] == 'student-decks-export-entry') {
	
    $result = array();
    $side_b_data = $side_a_data = $decks_title = $number = $html = '';
    $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
    $code = !empty($_POST['students_decks_new_data']) ? trim($_POST['students_decks_new_data']) : '';
	$id = !empty($_POST['table_id']) ? $_POST['table_id'] : '';
	
    $check_exists = query('SELECT * FROM text_data WHERE id = "' . $student_id . '" AND code = "'.$code.'" ');
    if ($check_exists->num_rows > 0) {
		$fetch_exists_data = fetch($check_exists);
        $check_text_exists = query('SELECT * FROM text WHERE id = "' . $student_id . '" AND table_id = "' . $id . '"');
		if ($check_text_exists->num_rows > 0) {
			
			$fetch_text_exists_data = fetch($check_text_exists);
			
			$inserted = $fetch_exists_data['tbl_id'];
			$deck_name = $fetch_text_exists_data['title'];
			$data = $fetch_text_exists_data['data'];
			
			$UpdateData = array(
				'title' => $deck_name,
				'data' => $data,
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
			$date = date('Y-m-d H:i:s');
			
			$query = 'INSERT INTO `text_data` SET 
					  app    = "QC-OL",
					  title  = "'.$deck_name.'",
					  id 	 = "'.$student_id.'",
					  number =  0,
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

if (!empty($_POST['table_id']) && !empty($_POST['type']) && $_POST['type'] == 'Decks-maker-edit') {
    $data = mysqli_fetch_assoc(mysqli_query($con, 'SELECT * FROM text WHERE table_id="' . $_POST['table_id'] . '"'));
    if (!empty($data)) {
        $html = '';
        $html .= '<div class="decks-edit">';
        $html .= '<form id="decks-maker-edit-form" name="decks-edit">';
        $html .= '<input type="hidden" name="table_id" class="table_id" value="' . $_POST['table_id'] . '">';

        if (!empty($data['data'])) {
            $html .= '<label>Deck Name</label>';
            $html .= '<p><input class="form-control deck_title_' . $_POST['table_id'] . '" type="text" id="deck_name_edit" value="' . $data['title'] . '" name="title" placeholder="Type Deck Name here..." onkeyup="check_duplicate_dock_edit(' . $_POST['table_id'] . ')" ><span class="deck_title_edit_' . $_POST['table_id'] . '" style="color:red;"></span></p>';
            $question_ans = explode('|', $data['data']);
            if (!empty($question_ans)) {
                $html .= '<div id="append_data_edit">';
                foreach ($question_ans as $key => $value) {
                    $final_array = explode('`', $value);

                    $html .= '<div class="edit_deck_side_row">
                                        <label class="color desk_maker_label" style="margin-top: 2px;" >Side A:</label>
                                        <input class="form-control desk_maker_input_2"   type="text" placeholder="Type Side A..." value="' . (isset($final_array[0]) ? $final_array[0] : '') . '" name="side_A_data[]" />
                                        <a href="javascript:void(0)"  aria-label="Delete Answer" id="edit_side_box_remove" ><i class="fa fa-remove" style="font-size:16px;color:grey"></i></a>
                                        <label class="color desk_maker_label" style="margin: 1px;margin-left: 0px;">Side B:</label>
                                        <input class="form-control desk_maker_input_2" placeholder="Type Side B..." type="text" value="' . (isset($final_array[1]) ? $final_array[1] : '') . '" name="side_B_data[]">
                                </div>';
                }
            }
        }
        $html .= '</div></form>';
        $html .= '</div>';
    }
    echo json_encode(array('status' => 200, 'html' => $html));
    exit;
}

if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Decks-maker-edit') {
    // print_r($_POST);
    // exit;	
    $result = array();
    parse_str($_POST['form_data'], $form_data);

    if (!empty($form_data['side_A_data']) && !empty($form_data['side_B_data'])) {
        $side_a_data = $form_data['side_A_data'];
        $side_b_data = $form_data['side_B_data'];
        // $all_array = [$side_a_data,$side_b_data];
        // $length = count($all_array);
        $data = '';
        for ($i = 0; $i < count($side_a_data); $i++) {
            $data .= $side_a_data[$i] . '`';
            $data .= $side_b_data[$i] . '|';
        }
        $data = rtrim($data, '|');
        $result['status'] = mysqli_query($con, 'UPDATE `text` SET data = "' . $data . '" WHERE table_id="' . $form_data['table_id'] . '"');
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> Decks updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    //Update Title fileds
    if (!empty($form_data['title'])) {
        $result['status'] = mysqli_query($con, 'UPDATE `text` SET title = "' . $form_data['title'] . '" WHERE table_id="' . $form_data['table_id'] . '"');
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> Decks updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    if (empty($result['status'])) {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    $result['html'] = '';
    if ($user_type == 1) {
        $student_id = $_SESSION['User']['id'];
    } else {
        $student_id = $_POST['student_id'];
    }

    $text_data = get_data_from_text_table($student_id, 'QC-OL', 0);
    if (!empty($text_data)) {
        foreach ($text_data as $key => $value) {
            $result['html'] .= '<tr>';
            if ($user_type == 1) {
                $result['html'] .= '<td><input type="checkbox" name="share_quick_card_deck_ids[]" value=' . $value['table_id'] . ' class="share_quick_card_deck_ids"></td>';
            }
            $result['html'] .= '<td>' . $value['title'] . '</td>';
            $result['html'] .= '<td>' . (!empty($value['data']) ? count(explode('|', $value['data'])) : 0) . '</td>';
            $result['html'] .= '<td><a href="javascript:void(0)" 
									class="badge bg-green student-decks-maker_type_1_edit" data-id="' . $value['table_id'] . '"><i class="fa fa-edit (alias)"></i></a></td><td><a href="javascript:void(0)" class="badge bg-red student-decks-maker_type_1_delete" data-id="' . $value['table_id'] . '"><i class="fa fa-trash-o"></i></a></td>';
            $result['html'] .= '</tr>';
        }
    }



    echo json_encode($result);
    exit;
}

//save new decks for teacher 
//Save new test entry
if (!empty($_POST['student_id']) && !empty($_POST['students_test_data']) && $_POST['type'] == 'student-test-new-entry') {

    $result = array();
    $test_name = $question_name = '';
    $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
    $students_test_data = !empty($_POST['students_test_data']) ? $_POST['students_test_data'] : '';

    parse_str($students_test_data, $students_test_data);
    // echo "<pre>";
    // print_r($students_test_data);
    //Check new cards added
    if (!empty($students_test_data['question_arr'])) {
        $question_arr = $students_test_data['question_arr'];
        $data = '';
        // for ($i = 0; $i < count($question_arr); $i++) {
        // 	if(isset($question_arr[$i])){ 			
        // 		$data .= $question_arr[$i].'`';	
        // 		$question_option = $students_test_data['question_option'][$i];  
        // 		for ($j = 0; $j < count($question_option); $j++) { 
        // 			$data .= $question_option[$j].'`';	
        // 		} 
        // 		$data .= '|';
        // 	}
        // }  
        foreach ($question_arr as $qNo => $questionValue) {
             $data .= addslashes($questionValue) . '`';
            $question_option = $students_test_data['question_option'][$qNo];
            foreach ($question_option as $optionValue) {
                $data .= addslashes($optionValue) . '`';
            }
            $data .= '|';
        }
    }

    $test_name = !empty($students_test_data['test_name']) ? addslashes($students_test_data['test_name']) : '';

    $check_exists = query('SELECT * FROM text WHERE id = "' . $student_id . '" AND app = "QC-OL" AND title = "' . $test_name . '" AND number = 1 ');
    if ($check_exists->num_rows > 0) {
        $fetch_exists_data = fetch($check_exists);
        $inserted = $fetch_exists_data['table_id'];

        $UpdateData = array(
            'title' => $test_name,
            'data' => $data,
        );

        update_query('text', 'table_id = "' . $inserted . '"', $UpdateData);

        $get_data_query = query('SELECT * FROM text WHERE table_id = "' . $inserted . '" ');
        $data_result = fetch($get_data_query);

        $test_name = $data_result['title'];
        $data = $data_result['data'];
    } else {
    $query = "INSERT INTO `text` SET 
			  app    = 'QC-OL',
			  title  = '$test_name',
			  id 	 = '$student_id',
			  number =  1,
			  data 	 =  '$data'";
    //Insert new data
    $inserted = mysqli_query($con, $query);
        $inserted = mysqli_insert_id($con);
    }

    if ($inserted) {
        $result['status'] = 1;
        $result['table_id'] = $inserted;
        $result['msg'] = '<h4><i class="icon fa fa-check"></i>New test added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        $result['html'] = '<tr class="test_'.$inserted.'">';
        if ($_POST['student_id'] == $_SESSION['User']['id']) {
            $result['html'] .= '<td><input type="checkbox" name="share_quick_card_test_ids[]" value=' . $inserted . ' class="share_quick_card_test_ids"></td>';
        }

        $result['html'] .= '<td>' . stripslashes($test_name) . '</td>
													<td>' . (!empty($data) ? count(explode('|', $data)) - 1 : 0) . '</td>
													<td><a href="javascript:void(0)" class="badge bg-green student-decks-maker_type_2_edit" data-id="' . $inserted . '"> <i class="fa fa-edit (alias)"></i></a></td>
													<td><a href="javascript:void(0)" class="badge bg-red student-decks-maker_type_2_delete" data-id="' . $inserted . '"><i class="fa fa-trash-o"></i></a></td>
												</tr>';
    } else {
        $result['table_id'] = '';
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    echo json_encode($result);
    exit;
}
// dack test export code entry here

if (!empty($_POST['student_id']) && !empty($_POST['students_test_data']) && $_POST['type'] == 'student-decks-test-export-entry') {

    $result = array();
    $test_name = $question_name = '';
	
	$student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
    $code = !empty($_POST['students_test_data']) ? trim($_POST['students_test_data']) : '';
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
			
			$UpdateData = array(
				'title' => $deck_name,
				'data' => $data,
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
			$date = date('Y-m-d H:i:s');
			
			$query = 'INSERT INTO `text_data` SET 
					  app    = "QC-OL",
					  title  = "'.$deck_name.'",
					  id 	 = "'.$student_id.'",
					  number =  1,
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

//Save import new test entry for student using code

if (!empty($_POST['student_id']) && !empty($_POST['students_test_data']) && $_POST['type'] == 'student-decks-test-new-import-entry') {
	
    $result = array();
    $side_b_data = $side_a_data = $decks_title = $number = $html = '';
    $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
    $import_code = !empty($_POST['students_test_data']) ? trim($_POST['students_test_data']) : '';
	
    
	$inserted = false;
	
    $check_exists = query('SELECT * FROM text_data WHERE number = 1 and code = "'.$import_code.'" ');
    if ($check_exists->num_rows > 0) {
		    $fetch_text_exists_data = fetch($check_exists);
			$test_name = $fetch_text_exists_data['title'];
			$data = $fetch_text_exists_data['data'];
		    $query = 'INSERT INTO `text` SET 
				  app    = "QC-OL",
				  title  = "'.$test_name.'",
				  id 	 = "'.$student_id.'",
				  number =  1,
				  data 	 =  "'.$data.'"';
		//Insert new data
		$inserted = mysqli_query($con, $query);
		$inserted = mysqli_insert_id($con);

    }

    if ($inserted) {

        $result['status'] = 1;
        $result['table_id'] = $inserted;
        $result['msg'] = '<h4><i class="icon fa fa-check"></i>New test added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        $result['html'] = '<tr class="test_'.$inserted.'">';
        if ($_POST['student_id'] == $_SESSION['User']['id']) {
            $result['html'] .= '<td><input type="checkbox" name="share_quick_card_test_ids[]" value=' . $inserted . ' class="share_quick_card_test_ids"></td>';
        }

        $result['html'] .= '<td>' . $test_name . '</td>
													<td>' . (!empty($data) ? count(explode('|', $data)) - 1 : 0) . '</td>
													<td><a href="javascript:void(0)" class="badge bg-green student-decks-maker_type_2_edit" data-id="' . $inserted . '"> <i class="fa fa-edit (alias)"></i></a></td>
													<td><a href="javascript:void(0)" class="badge bg-red student-decks-maker_type_2_delete" data-id="' . $inserted . '"><i class="fa fa-trash-o"></i></a></td>
												</tr>';
    } else {
        $result['table_id'] = '';
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Code invalid, please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    echo json_encode($result);
    exit;
}

// Delete new test enty

if (!empty($_POST['delete_id']) && !empty($_POST['type']) && $_POST['type'] == 'Test-delete') {

    if (!empty($_POST['delete_id'])) {

        mysqli_query($con, 'DELETE FROM `text` WHERE table_id="' . $_POST['delete_id'] . '"');
        $result['status'] = 1;
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> Test deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }

    if (empty($result['status'])) {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }

    echo json_encode($result);
    exit;
}

// eidt new test entry
if (!empty($_POST['table_id']) && !empty($_POST['type']) && $_POST['type'] == 'Tests-maker-edit') {
    $data = mysqli_fetch_assoc(mysqli_query($con, 'SELECT * FROM text WHERE table_id="' . $_POST['table_id'] . '"'));
    if (!empty($data)) {
        $html = '';
        $html .= '<div class="tests-edit">';
        $html .= '<form id="tests-maker-edit-form" name="tests-edit">';
        $html .= '<input type="hidden" name="table_id" class="table_id_in_edit" id="table_id_in_edit" value="' . $_POST['table_id'] . '">';
        if (!empty($data['data'])) {
            $html .= '<p><b>Test\'s Title</b></p>';
            $html .= '<p><input class="form-control test_title_' . $_POST['table_id'] . '" type="text" id="title_name_edit" value="' . $data['title'] . '" name="title" onkeyup="check_duplicate_test_edit(' . $_POST['table_id'] . ')"><span class="test_title_edit_' . $_POST['table_id'] . '" style="color:red;"></span></p>';
            $question_ans = explode('|', $data['data']);
            $question_count = count($question_ans);

            if (!empty($question_ans)) {
                for ($q = 0; $q < $question_count - 1; $q++) {
                    $final_array = explode('`', $question_ans[$q]);
                    $option_count = count($final_array);

                    $html .= '<section class="edit_question_row">
											<div>
												<h3 class="color question_number" >
													<span style="width: 5%;float: left;" >Q: </span>
													<input style="margin-bottom: 8px; width: 85%;float: left;" class="form-control question_row_edit"  type="text" id="question_name_edit_' . $q . '" value="' . $final_array[0] . '" name="question_arr[' . $q . ']" placeholder="Type question here..."  />
												</h3>
												<div style="margin-left:20px" class="optionRow">
													<a href="javascript:void(0)" aria-label="Delete Answer" id="edit_question_remove" >
														<i class="fa fa-remove" style="font-size:16px;color:grey;padding: 10px;padding-bottom: 20px;"></i>
													</a>
												<div class="optionRow2">';

                    for ($i = 1; $i < $option_count - 1; $i++) {
                        $checked = 'checked';
                        $j = $i - 1;
                        $check_value = '1';

                        if (strpos($final_array[$i], '*') === false) {
                            $checked = 'unchecked';
                            $check_value = 0;
                        }
                        $html .= '<div  class="row edit_answer_row_' . $q . ' remove_answer" >
						<div class="answer_filed_row test_maker_q"> 
							<input aria-label="Correct Answer Checkbox" type="checkbox" ' . $checked . ' class="rightQuetion change_answer questionRight' . $q . ' " 
							name="questionRight[' . $q . '][' . $j . ']"  />  
						</div>
						<div class="test_maker_q_answer">
							<input class="form-control optionTextBoxRow_edit' . $q . '"  type="text" name="optionTextBox_edit[' . $q . '][' . $j . ']"   value="' . str_replace('*', '', $final_array[$i]) . '"  placeholder="Type answer..." required=""/>
						</div>
						<div class="test_maker_q_answer_remove">
							<button aria-label="Delete Answer" type="button" class="edit_remove_btn edit_question_answer_remove" value="' . $q . '"><i class="fa fa-remove" style="font-size:16px;color:grey"></i></button>
						</div>
					</div>';
                    }
                    $html .= '<input class="form-control" id="question_number_edit" name="question_number_edit" type="hidden"  value="' . $q . '" />';

                    $html .= '<div id="edit_answer_row_id' . $q . '"></div>';
                    $html .= '<button type="button" style="float:right;margin-bottom10px" class="dashboard-settings-btn btn btn-sm  add-more-answer_in_edit"   value="' . $q . '">Add An Answer</button></div></div></section>';
                }
            }
        }
        $html .= '<div id="append_data_for_test_edit"></div></form>';
        $html .= '   <input class="form-control" id="question_number_in_edit" type="hidden"  value="' . $q . '" />';
        $html .= '</div>';
    }
    echo json_encode(array('status' => 200, 'html' => $html));
    exit;
}

if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Tests-maker-edit') {
    $result = array();
    parse_str($_POST['form_data'], $form_data);
    // echo "<pre>";
    // print_r($form_data);exit;
    if (!empty($form_data['question_arr'])) {

        $question_arr = $form_data['question_arr'];
        $data = '';

        foreach ($question_arr as $q_key => $question) {
            if (isset($question_arr[$q_key])) {
                $data .= $question . '`';
                $question_option_arr = $form_data['optionTextBox_edit'][$q_key];
                foreach ($question_option_arr as $answerKey => $question_option) {
                    $right_option = '';
                    if (isset($form_data['questionRight'][$q_key][$answerKey])) {
                        $right_option = '*';
                    }
                    $data .= $right_option . '' . $question_option . '`';
                }
            }
            $data .= '|';
        }

        $result['status'] = mysqli_query($con, 'UPDATE `text` SET data = "' . $data . '" WHERE table_id="' . $form_data['table_id'] . '"');
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> Decks updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    //Update Title fileds
    if (!empty($form_data['title'])) {
        $result['status'] = mysqli_query($con, 'UPDATE `text` SET title = "' . $form_data['title'] . '" WHERE table_id="' . $form_data['table_id'] . '"');
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> Tests updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    if (empty($result['status'])) {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    $result['html'] = '';

    if ($user_type == 1) {
        $student_id = $_SESSION['User']['id'];
    } else {
        $student_id = $_POST['student_id'];
    }

    $text_data = get_data_from_text_table($student_id, 'QC-OL', 1);
    if (!empty($text_data)) {
        foreach ($text_data as $key => $value) {
            $result['html'] .= '<tr>';
            if ($user_type == 1) {
                $result['html'] .= '<td><input type="checkbox" name="share_quick_card_test_ids[]" value=' . $value['table_id'] . ' class="share_quick_card_test_ids"></td>';
            }
            $result['html'] .= '<td>' . $value['title'] . '</td>';
            $result['html'] .= '<td>' . (!empty($value['data']) ? count(explode('|', $value['data'])) - 1 : 0) . '</td>';
            $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green student-decks-maker_type_2_edit" data-id="' . $value['table_id'] . '">
	                        <i class="fa  fa-edit (alias)"></i></a>
	                    </td>
	                    <td><a href="javascript:void(0)" class="badge bg-red delete student-decks-maker_type_2_delete" data-id="' . $value['table_id'] . '">
	                        <i class="fa fa-trash-o"></i></a>
	                    </td>';
            $result['html'] .= '</tr>';
        }
    }



    echo json_encode($result);
    exit;
}

/**
 * Saving Pro Pack settings data
 */
if (!empty($_POST['student_id']) && !empty($_POST['form_data']) && $_POST['type'] == 'pro-pack-settings-process') {

    $result = array();
    $student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
    $form_data = !empty($_POST['form_data']) ? $_POST['form_data'] : '';
    $data_for = !empty($_POST['data_for']) ? $_POST['data_for'] : '';
    parse_str($form_data, $form_data);

    if (!empty($form_data['pro-pack-options'])) {

        $args = array(
            'user_id' => $student_id,
            'role' => 'student',
        );
        $student_data = get_users($args);
        $form_data = $form_data['pro-pack-options'];

        foreach ($form_data as $key => $value) {
            if($data_for == 'all'){                 
                if(isset($student_data[0]) && !empty($student_data[0])){
                    $args = array(
                        'teacher_code' => $student_data[0]['teacher'],
                        'role' => 'student',
                    );
                    $student_all_data = get_users($args);
                    foreach($student_all_data as $sval){    
                        update_qc_setting_field($sval['id'], $key, $value);                        
                    }    
                } else {
                    update_qc_setting_field($student_id, $key, $value);    
                }                
            } else {
                update_qc_setting_field($student_id, $key, $value);
            }
        }

        $result['status'] = 1;
        $result['msg'] = '<h4><i class="icon fa fa-check"></i> Settings updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }

    if (empty($result)) {
        $result['status'] = 0;
        $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
    }
    echo json_encode($result);
    exit;
}
?> 