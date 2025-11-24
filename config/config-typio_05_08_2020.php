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
            $form_data['data'] = mysqli_real_escape_string($con, $form_data['data']);

            $data = "SELECT id,app,number,title,table_id FROM `text` where id ='" . $user_id . "' AND app='Typio-OL' AND title='" . $form_data['title'] . "'";
            $res_data = query($data);
            $re_data = fetch($res_data);

            $table_id = $re_data['table_id'];

            if (!empty($re_data['title'])) {
                $result = array();
                parse_str($_POST['form_data'], $form_data);
                //Check Fileds data exist
                $data = mysqli_real_escape_string($con, $form_data['data']);

                $result['status'] = query('UPDATE `text` SET app="Typio-OL", title="' . $form_data['title'] . '", data="' . $form_data['data'] . '"  WHERE id="' . $user_id . '" AND title="' . $form_data['title'] . '" ');
                $result['msg'] = '<h2><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson updated successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h2>';
                $result['type'] = 1;
            } else {
                global $con, $studentid;
                $result = array();
                parse_str($_POST['form_data'], $form_data);

                if (!empty($form_data['data'])) {

                    $data = $form_data['data'];
                    $form_data['data'] = mysqli_real_escape_string($con, $form_data['data']);

                    $result['status'] = query("INSERT INTO `text` (`id`, `app`, `number`, `title`, `data`) VALUES ('" . $user_id . "','Typio-OL',0,'{$form_data['title']}','{$form_data['data']}')");

                    $result['id'] = mysqli_insert_id($con);
                    $result['msg'] = '<h2><i class="icon fa fa-check" aria-hidden="true"></i> Custom lesson Added successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a></h2>';
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

    /**
     * Update custom lessons data from modal popup
     */
    if (isset($table_id) && !empty($table_id) && isset($type) && $type == 'Update-Lessons') {
        $sql = query('SELECT * FROM text WHERE table_id="' . $table_id . '"');
        $data = fetch($sql);
        $html = '';
        if (!empty($data)) {
            $html .= '<div class="lessions-edit">';
            $html .= '<form id="lessons-edit-form" name="lessions-edit">';
            $html .= '<div class="form-group"><input type="text" id="title-text-edit" name="title" class="form-control lesson_title_edit_' . $_POST['table_id'] . '" placeholder="Type lesson title here..." aria-label="Lesson title field. Type lesson title here." required="" value="' . $data['title'] . '" onkeyup="check_duplicate_lesson_edit(' . $_POST['table_id'] . ')"><input type="hidden" class="lesson_hidded_input_error" value="" /><span class="lesson_title_error lesson_title_error_' . $_POST['table_id'] . '" style="color:red;"></span></div>';
            $html .= '<input type="hidden" name="table_id" value="' . $_POST['table_id'] . '">';
            if (!empty($data['data'])) {
                $html .= '<p><textarea id="textareaID2" placeholder="Type lesson text here..." class="form-control" cols="55" rows="10" name="data" aria-label="Lesson text field. Type lesson text here.">' . $data['data'] . '</textarea></p>';
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
}
?>