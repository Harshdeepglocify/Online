<?php

if (isset($_POST) && !empty($_POST)) {

    extract($_POST);

    /**
     * Add new Custom lessons data 
     */
    if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Add-Hangman') {
        include "config.php";
        $result = array();
        parse_str($_POST['form_data'], $form_data);
        if (!empty($form_data['fields'])) {
            foreach ($form_data['fields'] as $key => $value) {
                //Check fields value blank then remove
                if (empty($value)) {
                    unset($form_data['fields'][$key]);
                }
            }
        }

        $data = "SELECT id,app,number,title FROM `text` where id ='" . $form_data['user_id'] . "' AND app='Arcade-OL' AND number='2' AND title='" . $form_data['title'] . "'";
        $res_data = query($data);
        $re_data = fetch($res_data);


        if (!empty($re_data['title'])) {
            $data = implode('`', $form_data['fields']);

            $result['status'] = query('UPDATE `text` SET app="Arcade-OL", number="2", title="' . $form_data['title'] . '", data="' . $data . '"  WHERE id="' . $form_data['user_id'] . '" AND title="' . $form_data['title'] . '" ');
            $result['msg'] = '<h4><i class="icon fa fa-check"></i> Hangman updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        } else {
            $data = implode('`', $form_data['fields']);
            $result['status'] = query("INSERT INTO text (`id`, `app`, `number`, `title`, `data`) VALUES ('" . $form_data['user_id'] . "','Arcade-OL',2,'{$form_data['title']}','{$data}')");
            $result['id'] = mysqli_insert_id($con);
            $result['msg'] = '<h4><i class="icon fa fa-check"></i> Hangman added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
            $result['html'] = '';
            $result['html'] .= '<tr class="hangman_tr_' . $result['id']. '">';
            $result['html'] .= '<td>' . $form_data['title'] . '</td>';
            $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-hangman-modal" data-id="' . $result['id'] . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
            $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-hangman" class="badge bg-red delete-hangman" data-id="' . $result['id'] . '"><i class="fa fa-trash-o"></i></a></td>';
            $result['html'] .= '</tr>';
            $result['type'] = 2;
        }


        echo json_encode($result);
        exit;
    }
	
	/**
     * Add new Custom lessons data using export
     */
    if (isset($table_id) && !empty($table_id) && !empty($_POST['type']) && $_POST['type'] == 'hangman-export-entry') {
        include "config.php";
        
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
						  app    = "Arcade-OL",
						  title  = "'.$deck_name.'",
						  id 	 = "'.$student_id.'",
						  number =  2,
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
			$result['msg'] = '<h4><i class="icon fa fa-check">Code copy to clipboard!</i></h4>';
			
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
    if (!empty($_POST['code']) && !empty($_POST['student_id']) && !empty($_POST['type']) && $_POST['type'] == 'hangman-import-entry') {
        include "config.php";
        $result = array();
        

		$student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : '';
		$import_code = !empty($_POST['code']) ? trim($_POST['code']) : '';
		
		
		$inserted = false;
		
		$check_exists = query('SELECT * FROM text_data WHERE number = 2 and code = "'.$import_code.'" ');
		if ($check_exists->num_rows > 0) {
			$fetch_text_exists_data = fetch($check_exists);
			$title = $fetch_text_exists_data['title'];
			$data = $fetch_text_exists_data['data'];
			$query = 'INSERT INTO `text` SET 
				  app    = "Arcade-OL",
				  title  = "'.$title.'",
				  id 	 = "'.$student_id.'",
				  number =  2,
				  data 	 =  "'.$data.'"';
			//Insert new data
			$inserted = mysqli_query($con, $query);
			$inserted = mysqli_insert_id($con);
			$result['status'] = 1;
			$result['msg'] = '<h4><i class="icon fa fa-check"></i> Hangman added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
            $result['html'] = '';
            $result['html'] .= '<tr class="hangman_tr_' . $inserted. '">';
            $result['html'] .= '<td>' . $title . '</td>';
            $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-hangman-modal" data-id="' . $inserted . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
            $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-hangman" class="badge bg-red delete-hangman" data-id="' . $inserted . '"><i class="fa fa-trash-o"></i></a></td>';
            $result['html'] .= '</tr>';
            $result['type'] = 2;

		} else {
			$result['table_id'] = '';
			$result['status'] = 0;
			$result['msg'] = '<h4><i class="icon fa fa-warning"></i> Code invalid, please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
		}
		echo json_encode($result);
		exit;
    }

    /**
     * Display data to edit in modal popup
     */
    if (isset($table_id) && !empty($table_id) && isset($type) && $type == 'Update-Hangman') {
        include "config.php";
        $sql = query('SELECT * FROM text WHERE table_id="' . $table_id . '"');
        $data = fetch($sql);
        $html = '';
        if (!empty($data)) {
            $html .= '<div class="hangman-edit">';
            $html .= '<p>Custom Hangman</p>';
            $html .= '<form id="hangman-edit-form" name="hangman-edit">';
            $html .= ' <div class="form-group"><input class="form-control hangman_title_edit_' . $_POST['table_id'] . '" id="hangman-title-edit" type="text" name="title" placeholder="Type lesson title here..." required="" value="' . $data['title'] . '" onkeyup="check_duplicate_hangman_edit(' . $_POST['table_id'] . ')" ><span class="hangman_title_error hangman_title_error_' . $_POST['table_id'] . '" style="color:red;"></span></div>';
            $html .= '<input type="hidden" class="table_id" name="table_id" value="' . $_POST['table_id'] . '">';
            if (!empty($data['data'])) {
                $keys = explode('`', $data['data']);
                foreach ($keys as $key => $value) {
                    $html .= '<p><input type="text" class="form-control text_data_edit" value="' . $value . '"  name="fields[' . $key . ']">';
                }
            }
            $html .= '</form>';
            $html .= '</div>';
        }
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }
    /**
     * Update hangman game data from modal popup
     */
    if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Update-Hangman') {
        include "config.php";
        $result = array();
        parse_str($_POST['form_data'], $form_data);

        //Check Fileds data exist
        if (!empty($form_data['fields'])) {
            if (!empty($form_data['table_id'])) {
                foreach ($form_data['fields'] as $key => $value) {

                    //Check fields value blank then remove
                    if (empty($value)) {
                        unset($form_data['fields'][$key]);
                    }
                }

                $data = implode('`', $form_data['fields']);
                $result['status'] = query('UPDATE `text` SET title = "' . $form_data['title'] . '", data = "' . $data . '" WHERE table_id="' . $form_data['table_id'] . '"');
                $result['msg'] = '<h4><i class="icon fa fa-check"></i> Hangman updated successfully.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </h4>';
                $result['html'] = '';
                $result['html'] .= '<tr class="hangman_tr_' . $form_data['table_id'] . '">'; 

                $result['html'] .= '<td>' . $form_data['title'] . '</td>';
                $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-hangman-modal" data-id="' . $form_data['table_id'] . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
                $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-hangman" class="badge bg-red delete-hangman" data-id="' . $form_data['table_id'] . '"><i class="fa fa-trash-o"></i></a></td>';
                $result['html'] .= '</tr>';
                $result['table_id'] = $form_data['table_id'];
            }
        }
        if (empty($result['status'])) {
            $result['status'] = 0;
            $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        }
        echo json_encode($result);
        exit;
    }



    /**
     * Delete custom lessons data
     */
    if (!empty($_POST['delete_id']) && !empty($_POST['type']) && $_POST['type'] == 'hangman-delete') {
        include "config.php";
        global $con;
        if (!empty($_POST['delete_id'])) {

            mysqli_query($con, 'DELETE FROM `text` WHERE table_id="' . $_POST['delete_id'] . '"');
            $result['status'] = 1;
            $result['msg'] = '<h4><i class="icon fa fa-check"></i> Hangman deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        }

        if (empty($result['status'])) {
            $result['status'] = 0;
            $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        }

        echo json_encode($result);
        exit;
    }

    /*     * ************************************** SECTION C ******************************************************** */

    /**
     * Display data to edit in modal popup
     */
    if (isset($table_id) && !empty($table_id) && isset($type) && $type == 'Update-Game') {
        include "config.php";
        $sql = query('SELECT * FROM text WHERE table_id="' . $table_id . '"');
        $data = fetch($sql);
        $html = '';
        if (!empty($data)) {
            $html .= '<div class="game-edit">';
            $html .= '<p>Game</p>';
            $html .= '<form id="game-edit-form" name="gmae-edit">';
            $html .= '<div class="form-group"> <input class="form-control game_title_edit_' . $_POST['table_id'] . '" id="game-title_edit" type="text" name="title" placeholder="Type lesson title here..." required="" value="' . $data['title'] . '"  onkeyup="check_duplicate_game_edit(' . $_POST['table_id'] . ')" ><span class="game_title_error game_title_error_' . $_POST['table_id'] . '" style="color:red;"></span></div>';
            $html .= '<input type="hidden" name="table_id" value="' . $_POST['table_id'] . '">';
            if (!empty($data['data'])) {
                $html .= '<p><textarea type="text" class="form-control" name="data">' . $data['data'] . '</textarea>';
            }
            $html .= '</form>';
            $html .= '</div>';
        }
        echo json_encode(array('status' => 200, 'html' => $html));
        exit;
    }

    /**
     * Update game data from modal popup
     */
    if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Update-Game') {
        include "config.php";
        $result = array();
        parse_str($_POST['form_data'], $form_data);
        //Check Fileds data exist
        if (!empty($form_data['data'])) {
            if (!empty($form_data['table_id'])) {
                $result['status'] = query('UPDATE `text` SET  title = "' . $form_data['title'] . '", data = "' . $form_data['data'] . '" WHERE table_id="' . $form_data['table_id'] . '"');
                $result['msg'] = '<h4><i class="icon fa fa-check"></i> Game updated successfully.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
                $result['html'] = '';
                $result['html'] .= '<tr class="game_tr_' . $form_data['table_id'] . '">';                
                $result['html'] .= '<td>' . $form_data['title'] . '</td>';
                $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-game-modal" data-id="' . $form_data['table_id'] . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
                $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-game" class="badge bg-red game-data-delete" data-id="' . $form_data['table_id'] . '"><i class="fa fa-trash-o"></i></a></td>';
                $result['html'] .= '</tr>';
                $result['table_id'] = $form_data['table_id'];
            }
        }
        if (empty($result['status'])) {
            $result['status'] = 0;
            $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        }
        echo json_encode($result);
        exit;
    }

    /**
     * Add new game 
     */
    if (!empty($_POST['form_data']) && !empty($_POST['type']) && $_POST['type'] == 'Add-game') {
        include "config.php";
        $result = array();
        parse_str($_POST['form_data'], $form_data);

        $data = "SELECT id,app,number,title FROM `text` where id ='" . $form_data['user_id'] . "' AND app='Arcade-OL' AND number='1' AND title='" . $form_data['title'] . "'";
        $res_data = query($data);
        $re_data = fetch($res_data);


        if (!empty($re_data['title'])) {


            $result['status'] = query('UPDATE `text` SET app="Arcade-OL", number="1", title="' . $form_data['title'] . '", data="' . $form_data['data'] . '"  WHERE id="' . $form_data['user_id'] . '" AND title="' . $form_data['title'] . '" ');
            $result['msg'] = '<h4><i class="icon fa fa-check"></i> Game updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        } else {
            //$data = implode('`', $form_data['fields']);
            $result['status'] = query("INSERT INTO text (`id`, `app`, `number`, `title`, `data`) VALUES ('" . $form_data['user_id'] . "','Arcade-OL',1,'{$form_data['title']}','{$form_data['data']}')");
            $result['id'] = mysqli_insert_id($con);
            $result['msg'] = '<h4><i class="icon fa fa-check"></i> Game added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
            $result['html'] = '';
            $result['html'] .= '<tr class="game_tr_' . $result['id'] . '">';
            $result['html'] .= '<td>' . $form_data['title'] . '</td>';
            $result['html'] .= '<td><a href="javascript:void(0)" class="badge bg-green edit-game-modal" data-id="' . $form_data['table_id'] . '"><i class="fa  fa-edit (alias)"></i></a> </td>';
            $result['html'] .= '<td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-game" class="badge bg-red game-data-delete" data-id="' . $form_data['table_id'] . '"><i class="fa fa-trash-o"></i></a></td>';
            $result['html'] .= '</tr>';
            $result['type'] = 2;
        }


        echo json_encode($result);
        exit;
    }

    if (!empty($_POST['delete_id']) && !empty($_POST['type']) && $_POST['type'] == 'game-delete') {
        include "config.php";
        global $con;

        if (!empty($_POST['delete_id'])) {

            mysqli_query($con, 'DELETE FROM `text` WHERE table_id="' . $_POST['delete_id'] . '"');
            $result['status'] = 1;
            $result['msg'] = '<h4><i class="icon fa fa-check"></i> Game deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        }

        if (empty($result['status'])) {
            $result['status'] = 0;
            $result['msg'] = '<h4><i class="icon fa fa-warning"></i> Somethings goes wrong please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        }

        echo json_encode($result);
        exit;
    }
}

/**
 * Update setting for Arcade-OL query
 * 
 * @param string $update_arcade_setting_data
 * @return boolean Arcade-OL table update status
 */
function updateAppArcadeSettingData($update_arcade_setting_data = '') {

    $update_arcade_setting_ready_data = array(315 => $update_arcade_setting_data['arcade_font_size'], 317 => $update_arcade_setting_data['arcade_font_style'], 320 => $update_arcade_setting_data['arcade_voice'], 321 => $update_arcade_setting_data['arcade_voice_rate'], 322 => $update_arcade_setting_data['arcade_voice_pitch'], 316 => $update_arcade_setting_data['arcade_font_color'], 318 => $update_arcade_setting_data['arcade_bg_color'], 319 => $update_arcade_setting_data['arcade_accessory_color'], 326 => $update_arcade_setting_data['arcade_samurai_flash'], 327 => $update_arcade_setting_data['arcade_echo_arrows'], 330 => $update_arcade_setting_data['arcade_visual'], 325 => $update_arcade_setting_data['arcade_music_volume'], 328 => $update_arcade_setting_data['wizards_tower_sfx'], 334 => $update_arcade_setting_data['arcade_settings_lock']);
    $update_arcade_setting_final_status = 1;
    foreach ($update_arcade_setting_ready_data as $update_arcade_setting_data_id => $update_arcade_setting_data_variable) {
        $update_arcade_setting_status = updateAppArcadeSettingField($update_arcade_setting_data_id, $update_arcade_setting_data_variable);

        if ($update_arcade_setting_status == 0) {
            $update_arcade_setting_final_status = 0;
        }
    }
    return $update_arcade_setting_final_status;
}

/**
 * Update setting for Arcade-OL field
 * 
 * @param int $update_arcade_setting_item_id 
 * @param string $update_arcade_setting_variable 
 * @return boolean Arcade-OL table update status
 */
function updateAppArcadeSettingField($update_arcade_setting_item_id = 0, $update_arcade_setting_variable = '') {
    global $con, $studentid;
    $arcade_settings = 'settings';
    //Check if setting already exists for student
    $query = query("SELECT COUNT(*) as total FROM " . $arcade_settings . " WHERE id=" . $studentid . " AND item='" . $update_arcade_setting_item_id . "'");

    $data = fetch($query);
    if ($data['total'] != 0) {
        $typio_settings_update_query = "UPDATE `" . $arcade_settings . "` SET `variable`='" . $update_arcade_setting_variable . "' WHERE `id`='" . $studentid . "' AND `item`=" . $update_arcade_setting_item_id;
        $typio_settings_update_query_run = mysqli_query($con, $typio_settings_update_query);

        return $typio_settings_update_query_run;
    } else {
        $typio_settings_insert_query = "INSERT INTO " . $arcade_settings . " (`id`,`item`,`variable`) VALUES( " . $studentid . ",'" . $update_arcade_setting_item_id . "', '" . $update_arcade_setting_variable . "' )";

        $typio_settings_insert_query_run = mysqli_query($con, $typio_settings_insert_query);
        return $typio_settings_insert_query_run;
    }
}
