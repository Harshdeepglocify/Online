<?php
include "config.php";
if (isset($_POST['is_ajax']) && isset($_POST['action']) && $_POST['action'] == 'ajax_teacher_list') {

    $result = array();
	$is_expired = check_expire_or_not();
    $args = array();
    $search_columns = 'firstname,lastname,organization';
    $order_by = 'user';

    $where = array(
        'role' =>'teacher',
        'license' =>$admin_license =$_SESSION['User']['license'],
    );
    $joins = array('log as l','l.id = u.id');
    $groupby = '';
    $records = json_datatable('user', '*', $where, $order_by, $search_columns,$groupby = '',$joins = array());
    if (!empty($records)) {
        $data = array();
        foreach ($records['data'] as $user)
        {

            $firstname =  ucfirst(base64_decode($user['firstname']));
            $lastname =  ucfirst(base64_decode($user['lastname']));
            $username = base64_encode($user['username'] . '-' . $user['password']);
            $student_count = '0';
            $query = query("SELECT count(*) student_count  FROM user where teacher='".$user['teacher']."' AND role ='student'");
            if (!empty($query->num_rows)) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $student_count=$row['student_count'];
                }
            }
            $deleteuser = ucfirst(base64_decode($user['firstname'])) .' '.ucfirst(base64_decode($user['lastname']));
			$actionButton = '';
            if(!$is_expired){
                $actionButton = '<span> <a href="javascript:void(0);" data-name="' . $deleteuser . ' " data-id="'.$user['id'] .'" aria-label="Change ' . ucfirst(base64_decode($user['firstname'])) . ' password " class="accessibyte-link update-pass">Change Password</a></span>';
            }
            if($user['id'] != $_SESSION['User']['id']){
                $actionButton .= '<span class="studentActionLogin"><a href="javascript:void(0)" data-count="'.$student_count.'" data-id="'.$user['id'] .'" data-name="' . ucfirst(base64_decode($user['firstname'])) . '(' . base64_decode($user['username']) . ')" data-URL="url_' . $user['id'] . '" class="accessibyte-link delete-href" aria-label="Delete ' . $deleteuser . ' ">Delete</a><div class="click_one_hide"></div></span>';
            }
            $limit = $user['seat_limit'];
            if($user['seat_limit'] == '0'){
                $limit = 'No Limit';
            }
            $data[] = array(
                '<div class="icheck-primary d-inline"><label for="all_chkbox'.$user['id'].'">'.$firstname .' '.$lastname.'</label></div>',
                '<span class="">' . base64_decode($user['organization']) . '<span>',
                '<span class="">'.$student_count.'<span>',
                /*'<div class="limit-button-wrap"><span class="limit-main-wrap"><a href="javascript:;" onclick =update_seats_limit("minus","'.$_SESSION['User']['license'].'","'.$user['id'].'") >-</a>&nbsp;&nbsp;<span id="student_seats_'.$user['id'].'">'.$user['seat_limit'].'</span>&nbsp;&nbsp;<a href="javascript:;" onclick =update_seats_limit("plus","'.$_SESSION['User']['license'].'","'.$user['id'].'") >+</a><span><a href="javascript:void(0);" data-id="'.$user['id'].'" data-limit="'.$user['seat_limit'].'" class="dashboard-settings-btn btn-block updateLimitModel">Update limit</a></div>',*/
                '<div class="limit-button-wrap"><span class="limit-main-wrap"><span id="student_seats_'.$user['id'].'">'.$limit.'</span>&nbsp;&nbsp;</span><a href="javascript:void(0);" data-id="'.$user['id'].'" data-limit="'.$user['seat_limit'].'" class="dashboard-settings-btn btn-block updateLimitModel">Update limit</a></div>',
                '<div class="studentAction">
                '.$actionButton.'
                </div>'
            );
        }
        $records['data'] = $data;
        $records['status'] = 1;

    }

    if (empty($records)) {
        $records['status'] = 0;
    }

    echo json_encode($records);
    exit;
}
if (!empty($_POST['action']) && $_POST['action'] == "delete_multi_teacher_account" && !empty($_POST['teacher_delete_id'])) {

    $teacherid = $_POST['teacher_delete_id'];
    $result = array();
    if (!empty($teacherid)) {
        
        $query = 'SELECT teacher  FROM user where `id`=' . $teacherid;
        $query_result = mysqli_query($con, $query);
        $teacher_data =mysqli_fetch_assoc($query_result);
        if(!empty($teacher_data)){
            $query = 'SELECT count(ID) as total_teacher  FROM user where `teacher`="' . $teacher_data['teacher'].'"';
            $query_result = mysqli_query($con, $query);
            $teacher_student_data =mysqli_fetch_assoc($query_result);
            $nos_of_student =$teacher_student_data['total_teacher']-1;
            if($nos_of_student < 0){
                $nos_of_student =0;
            }

            $query = 'SELECT id,license FROM user where `id`=' . $teacherid;
            $query_result = mysqli_query($con, $query);
            $license_key_array = array();
            while ($row = mysqli_fetch_assoc($query_result)) {
                $ItemType = '';
                if ($row['license'] != '') {
                    $val = explode("-", $row['license']);

                    if ($val[0] == "PRO")
                        $ItemType = "ProPack";
                    else if ($val[0] == "BDL")
                        $ItemType = "All Access";
                    elseif ($val[0] == "TYO")
                        $ItemType = "Typio";
                    else if ($val[0] == "TCH")
                        $ItemType = "Teacher";
                    else if ($val[0] == "QCO")
                        $ItemType = "Quick Cards";
                    else if ($val[0] == "AA")
                        $ItemType = "Accessibyte Arcade";
                    else if ($val[0] == "TCHP")
                        $ItemType = "Teacher All Access";
                }
                $check_license_key_exists = query('SELECT license_key FROM unassign_license_keys where `license_key`="' . trim($row['license']) . '" order by license_key desc');
                if ($check_license_key_exists->num_rows <= 0 || $check_license_key_exists->num_rows == '') {
                    query("INSERT INTO `unassign_license_keys` (`license_type`, `license_key`, `created_by`) VALUES ('" . $ItemType . "','" . trim($row['license']) . "','" . $_SESSION['User']['id'] . "')");
                }

                $license_key_array[] = trim($row['license']);
                if(!empty($row['license'])){
                    update_no_student_license_using_teacher($row['license'],'teacher',$nos_of_student);
                }
            }

            $wp_result = deactive_licenses($license_key_array, $teacherid);
            query('DELETE FROM `user` WHERE id="' . $teacherid . '"');
            query('DELETE FROM `user` WHERE teacher="' . $teacher_data['teacher'] . '"');
            query('DELETE FROM `settings` WHERE id="' . $teacherid . '"');
            query('DELETE FROM `data` WHERE id="' . $teacherid . '"');
            query('DELETE FROM `text` WHERE id="' . $teacherid . '"');

            $check_user_exists = query('SELECT user_id FROM wordpress_licenses where `user_id`="' . $teacherid . '"');
            if ($check_user_exists->num_rows > 0) {
                query("UPDATE `wordpress_licenses` SET `user_id`=0 WHERE `user_id`='" . $teacherid . "'");
            }
        
            $result['status'] = TRUE;
            $result['msg'] = 'Teacher deleted successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        } else {
            $result['status'] = false;
            $result['msg'] = 'Teacher cannot be deleted, Please try again! <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';
        }
        echo json_encode($result);
        exit;
    }
    exit;
}
if(!empty($_POST['action']) && $_POST['action'] == "check_student_add_limit"){
    $type= $_POST['type'];
    if($type == "minus"){
         $result['status'] = '1';
    }
    else if($type == "plus"){
        $check_license_key_exists = query('SELECT sum(seat_limit) as total_limit FROM user where `license`="' . trim($_POST['license']) . '" AND role ="teacher" and is_admin !="1" order by license desc');
        $row = fetch($check_license_key_exists);
        $student_seats_limit =$row['total_limit'];
        $licenseArr = get_license_data(trim($_POST['license']));
        $license_student_limit =$licenseArr['no_student'];
        $remaining_student_limit = $licenseArr['no_student']-$licenseArr['no_student_use'];
        $query = query("SELECT seat_limit  FROM user where ID='".$_POST['teacher_id']."' AND role ='teacher'");
        $add_student_limit = 0;
        if (!empty($query->num_rows)) {
            while ($row = mysqli_fetch_assoc($query)) {
                $add_student_limit=$row['seat_limit'];
            }
        }

        if($add_student_limit + 1 > $remaining_student_limit){
            $result['status'] = '0';
            $result['message'] = 'No more student seats remaining.';
        }else{
            $result['status'] = '1';
        }
        /*else if(intval($student_seats_limit) + 1 <= $license_student_limit){
            $result['status'] = '1';
        }/*else{

            $result['status'] = '0';
            $result['message'] = 'Sorry, Student Seat limit is reached.';
        }*/
    }
    echo json_encode($result);
    exit;
}

if(!empty($_POST['action']) && !empty($_POST['teacher_id']) && $_POST['action'] == "update_teachet_student_limit"){
    $query = query("SELECT seat_limit,license,teacher  FROM user where ID='".$_POST['teacher_id']."' AND role ='teacher'");
    $data = fetch($query);
    $result = array();
    if (!empty($query->num_rows) && !empty($data)) {
        
        $sql = query('SELECT sum(seat_limit) as total_seat FROM user WHERE license="' . $data['license']. '" and `id` !="'.$_POST['teacher_id'].'" and role="teacher"');
        $limitdata = fetch($sql);

        $sql = query('SELECT sum(seat_limit) as total_seat FROM user WHERE license="' . $data['license']. '" and role="teacher"');
        $totallimitdata = fetch($sql);

        $licenseArr = get_license_data(trim($data['license']));
        $license_student_limit = $licenseArr['no_student'];
        $total_seat_exist = $limitdata['total_seat'] + $_POST['student_list_limit'];
        $remianing_seat_assign = $license_student_limit - $totallimitdata['total_seat'];
        $remainingseatassign = 0;
        if($remianing_seat_assign > 0){
            $remainingseatassign = $remianing_seat_assign;
        }
        $studentsql = query('SELECT count(id) as scount FROM user WHERE license="' . $data['license']. '" and `teacher` ="'.$data['teacher'].'" and role="student"');
        $studentdata = fetch($studentsql);
        if(!empty($_POST['student_list_limit']) && $studentdata['scount'] > $_POST['student_list_limit']){
            $result['status'] = '0';
            $result['msg'] = 'You can not update seat limit below active student.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';    
        }
        else if($total_seat_exist <= $license_student_limit){
            $update_license_student_add_data = query('UPDATE user set seat_limit ="'.$_POST['student_list_limit'].'" where role ="teacher" AND ID="'.$_POST['teacher_id'].'"');
            $result['status'] = '1'; 
            $result['msg'] = 'Student limit update successfully <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';       
        } else {
            $result['status'] = '0';
            $result['msg'] = 'No more student seats remaining.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';    
        }        
    } else {
        $result['status'] = '0';
        $result['msg'] = 'Invalid Teacher id. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
    }
    echo json_encode($result);
    exit;
}
if(!empty($_POST['action']) && $_POST['action'] == "update_student_add_limit"){
    $type= $_POST['type'];
    $query = query("SELECT seat_limit  FROM user where ID='".$_POST['teacher_id']."' AND role ='teacher'");
    if (!empty($query->num_rows)) {
        while ($row = mysqli_fetch_assoc($query)) {
            $add_student_limit=$row['seat_limit'];
        }
    }

    if($type == "minus"){
        $student_count =0;
         $query = query("SELECT teacher  FROM user where ID='".$_POST['teacher_id']."'");
            $teacher_code="";
            if (!empty($query->num_rows)) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $teacher_code=$row['teacher'];
                }
            }

        $query = query("SELECT count(*) student_count  FROM user where teacher='".$teacher_code."' AND role ='student'");
            if (!empty($query->num_rows)) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $added_student_count=$row['student_count'];
                }
            }
            if(intval($add_student_limit) == 0){
                $result['status'] = '0';
                $result['message'] = 'Sorry, Student Seat limit is zero.';
                echo json_encode($result);
                exit;

            }
            if(intval($add_student_limit) - 1 >= $added_student_count ){
                $update_license_student_add_data = query('UPDATE user set seat_limit =seat_limit-1 where `license`="' . trim($_POST['license']) . '" AND role ="teacher" and is_admin !="1" AND ID="'.$_POST['teacher_id'].'"');


            }else{
                $result['status'] = '0';
                $result['message'] = 'Teacher must first delete students to reduce their seat count';
                echo json_encode($result);
                exit;

            }
            $student_seats_limit =intval($add_student_limit)-1;
    }
    else if($type == "plus"){
        $update_license_student_add_data = query('UPDATE user set seat_limit =seat_limit+1 where `license`="' . trim($_POST['license']) . '" AND role ="teacher" and is_admin !="1" AND ID="'.$_POST['teacher_id'].'"');
        $student_seats_limit =intval($add_student_limit)+1;

    }
    $result['status'] = '1';
    $result['student_seats_limit'] = $student_seats_limit;

    echo json_encode($result);
    exit;
}


if (!empty($_POST['action']) && $_POST['action'] == "update_multi_teacher_password" && !empty($_POST['teacher_id'])) {

    $teacheridArr = $_POST['teacher_id'];
    $password = $_POST['password'];
    if (!empty($teacheridArr) && !empty($password) && is_array($teacheridArr)) {
        foreach($teacheridArr as $teacherid){
            $encPass = md5($password);
            $newToken = getToken(30);
            //Good to update token after password reset for security purpose
            query("UPDATE user set `password`='" . $encPass . "', `token`='" . $newToken . "'  WHERE id=$teacherid");
        }
        $result['status'] = TRUE;
        $result['msg'] = 'Teacher password update successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';

        echo json_encode($result);
        exit;
    }
    if (!empty($teacheridArr) && !empty($password) && !is_array($teacheridArr)) {
        $encPass = md5($password);
        $newToken = getToken(30);
        //Good to update token after password reset for security purpose
        query("UPDATE user set `password`='" . $encPass . "', `token`='" . $newToken . "'  WHERE id=$teacheridArr");
    
        $result['status'] = TRUE;
        $result['msg'] = 'Teacher password update successfully. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></h4>';

        echo json_encode($result);
        exit;
    }
    exit;
}
function json_datatable($table, $columns, $where = '', $order_by = '', $search_columns = '',$groupby = '',$join = array()) {
    //pr($_POST);die;

    $datatable_search_value = trim($_POST['search']['value']);
    $datatable_columns = $_POST['columns'];
    $datatable_limit = $_POST['length'];
    $datatable_offset = $_POST['start'];
    $datatable_order_name = $_POST['columns'][$_POST['order'][0]['column']]['name'];
    $datatable_order_by = $_POST['order'][0]['dir'];
    $datatable_draw = $_POST['draw'];

    if (!empty($where)) {
        foreach ($where as $key => $val) {
            $query_var[] = "`".$key."` ='" . $val . "'";
        }
    }
    $where = '';
    if (!empty($query_var)) {
        $where .= 'WHERE ' . implode(' AND ', $query_var);
    }

    $SQL = '';
    if (!empty($datatable_search_value)) {
        $qry = array();
        if ($search_columns != '') {
            if (!is_array($search_columns)) {
                $search_columns = explode(',', $search_columns);
            }
            foreach ($search_columns as $s_cl) {
                $qry[] = " `" . $s_cl . "` like '%" . base64_encode($datatable_search_value) . "%' ";
            }
        } else {
            foreach ($datatable_columns as $cl) {
                if ($cl['searchable'] == 'true')
                    $qry[] = "`" . $cl['name'] . "` like '%" . base64_encode($datatable_search_value) . "%' ";
            }
        }

        $SQL .= "( ";
        $SQL .= implode("OR", $qry);
        $SQL .= " )";
    }
    if ($SQL != '') {
        $where .= " AND ".$SQL;
    }

    $order_by_array = array();
//        if ($datatable_draw == 1) {
    if ($order_by) {

        if ($_POST['order'][0]['column'] != 0) {
            $order_by_array[] = $datatable_order_name . ' ' . $datatable_order_by;
        } else if($_POST['order'][0]['column'] == 0){
            $order_by_array[] = $datatable_order_name . ' ' . $datatable_order_by;
        } else {
            if (is_array($order_by)) {
                foreach ($order_by as $k => $v) {
                    $order_by_array[] = $k . ' ' . $v;
                }
            } else {
                $order_by_array[] = $order_by;
            }
        }
    } else {
        if ($_POST['order'][0]['column'] != 0) {
            $order_by_array[] = $datatable_order_name . ' ' . $datatable_order_by;
        } else {
            $order_by_array[] = $order_by;
        }
    }

    if (!empty($order_by_array)) {
        $order_by = implode(',', $order_by_array);
    }
    $joinQuery = '';
    if(!empty($join)){
        $joinTable = $join[0];
        $joinCondition = $join[1];
        $joinQuery = " left join $joinTable on $joinCondition";
    }
    $query = query("SELECT $columns FROM $table $joinQuery $where $groupby ORDER BY $order_by limit $datatable_offset,$datatable_limit");
    $total_query = query("SELECT COUNT(*) as total FROM $table $where");

    $total_data = fetch($total_query);
    $total = 0;
    if (!empty($total_data)) {
        $total =  $total_data['total'];
    }
    $data = array();
    if (!empty($query->num_rows)) {

        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
    }
	$data = custom_sort($data,$datatable_order_name,$datatable_order_by);    
    return array("recordsTotal" => $total, "recordsFiltered" => $total, 'data' => $data);
}
if (!empty($_POST['action']) && $_POST['action'] == "send_quote") {
    $no_student = isset($_POST['no_student']) ? $_POST['no_student'] : '';
    $no_teacher = isset($_POST['no_teacher']) ? $_POST['no_teacher'] : '';
	$type = $_POST['type'];
	$renew_date = "";
    if((!empty($no_student) || !empty($no_teacher)) || !empty($type)){
        $username = $_SESSION['User']['username'];    
        $query = query("SELECT user.license,user.email,user.username,user.id,user.firstname,user.lastname,user.organization FROM user where user.username='$username' or user.username='" . base64_encode($username) . "'");
        $data = fetch($query);
        $variables = array();
        $variables['firstname'] = base64_decode($data['firstname']);
        $variables['lastname'] = base64_decode($data['lastname']);
        $variables['username'] = base64_decode($data['username']);
        $variables['organization'] = base64_decode($data['organization']);
		if($type == 'send_quote_renew'){
			$renew_date = isset($_POST['renewal_date']) ? $_POST['renewal_date'] : '';
		}
		$variables['renew_date'] = $renew_date;
		$variables['type'] = $type;
		$variables['no_student'] = $no_student;
		$variables['no_teacher'] = $no_teacher;
        $variables['license'] = $data['license'];
        $variables['email'] = base64_decode($data['email']);
        $variables['total_price'] = isset($_POST['total']) ? $_POST['total'] : '0.00';
        $licenseArr = explode("-", trim($_SESSION['User']['license']));
        $variables['product_shortname'] = isset($licenseArr[0]) ? $licenseArr[0] : '';
        $file_name = rand(0,999);  /* generate custom string for avoid duplicate for same name*/
        $filename = "Accesibyte Quote for ".$file_name.".pdf";
        send_quotepdf($variables,$filename);     /* send quote pdf */
        $result['status'] = TRUE;
        $fullpath = WEB_PATH . "online/uploads/". $filename;
        $result['msg'] = 'Request Quote has been send.';
        $result['downloadlink'] = $fullpath;
    } else {
        $_SESSION['error']['message'] = "Could not send Quote. Try again";
        $_SESSION['error']['color'] = 'danger';
        $result['status'] = false;
        $result['msg'] = 'Could not send Quote. Try again';
    }
    echo json_encode($result);
    exit;
}
function send_quotepdf($fields,$filename){
    require_once( '../helper/tcpdf/tcpdf.php');
    require '../PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();
    $to = base64_decode($_SESSION['User']['email']);
	$to = 'sanjaym@worldwebtechnology.in';
    try {
        //Recipients
        
        $name = $fields['username'];
        $email = $fields['email'];
        $org = $fields['organization'];
        $price = $fields['total_price'];
        $no_student = $fields['no_student'] . " Student";
        $no_teacher = $fields['no_teacher'] . " Teacher Seat";
        $shortname = $fields['product_shortname'];
        $product_name = '';
        
        $msg = '';
        if(isset($shortname) && !empty($shortname)){
            
            if($shortname == 'TCHP'){
                $product_name =  'Accessibyte All Access School Edition';
            }
            if($shortname == 'TCH'){
                $product_name =  'Typio School Edition';
            }
			if($fields['type'] == 'send_quote_renew'){
				$renew_date = $fields['renew_date'];
				$product_name = $product_name . " - Additional Seats ".$no_student. " - ".$no_teacher . " - ".$renew_date;
			} else {
				$product_name = $product_name . " - Additional Seats ".$no_student. " - ".$no_teacher;
			}
            
            $upload_dir = ADMIN_DIR.'/uploads/';
            
            $fullpath = $upload_dir."/".$filename;
            
            $from = "contact@accessibyte.com"; 
            $subject = "Your Accessibyte Quote has arrived!"; 
            $message = "We are super excited you are ready to hop on board with Accessibyte.\nThe quote you have requested is attached to this email. If you need anything else or have any questions, you can reply and we will help you out."
                    . "\n";

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            ob_start();
            $pdf->SetTitle('Pdf Example');
            $pdf->SetHeaderMargin(30);
            
            $pdf->SetTopMargin(20);
            $pdf->setFooterMargin(20);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAuthor('Author');
            $pdf->SetDisplayMode('real', 'default');
            $pdf->SetAutoPageBreak(TRUE);
            $pdf->AddPage(); 
            
            $date = date('m-d-Y');
            
            $html = '<p></p>'
                . '<table>'
                    . '<thead>'
                    . '<tr style="background-color:#ececec;padding:5px;font-size:12px;"><td>ACCESSIBYTE ONLINE QUOTE </td><td>DATE : '.$date.'</td></tr>'
                    . '</thead>'
                . '</table>'
                . '<p></p>'				  
                . '<table style="font-size:10px;">'					  
                . '<tbody>'
                    . '<tr><td style="border-bottom:1px solid #ececec;font-weight:bold;">QUOTE FOR </td><td style="border-bottom:1px solid #ececec;font-weight:bold;">SCHOOL / ORGANIZATION </td></tr>'
                    . '<tr><td>'.$name.'</td><td>'.$org.'</td></tr>'
                    . '<tr><td colspan="1">'.$email.'</td></tr>'                  
                . '</tbody>'
                . '</table>'
                . '<p>&nbsp;</p>'
                . '<table style="font-size:10px;">'
                . '<thead>'
                . '<tr style="background-color:#ececec;padding:5px;"><td width="70%" style="padding:5px;">DESCRIPTION </td><td width="30%" style="padding:5px;">TOTAL</td></tr>'
                . '</thead>'
                . '<tbody>'
                . '<tr><td width="70%">'.$product_name.'</td><td width="30%">$'.$price.'</td></tr>'
                . '<tr><td colspan="2"></td></tr>'
                . '<tr><td width="70%" style="border-bottom:1px solid #ececec;text-align:right;">SUBTOTAL</td><td width="30%" style="border-bottom:1px solid #ececec;">$'.$price.'</td></tr>'
                . '<tr><td width="70%" style="border-bottom:1px solid #ececec;text-align:right;">TOTAL DUE </td><td width="30%" style="border-bottom:1px solid #ececec;">$'.$price.'</td></tr>'
                . '</tbody>'
                . '</table>'
                . '<p>&nbsp;</p>'
                . '<p style="text-align:center;background-color:black;color:white;">Please see second page for payment methods </p>';
                $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->AddPage(); 	  
            $html =  '<p></p>' 
                . '<p style="background-color:#ececec;padding:5px;">HOW TO ORDER </p>'
                . '<p></p>'
                . '<p>While Accessibyte products can be ordered instantly from our website using a credit card or PayPal, we understand many institutions need to submit a purchase order.</p>'
                . '<p>Purchase orders can be sent to <span style="text-decoration: underline;">sales@accessibyte.com</span> along with this quote. Please be sure to include the email address for the person who will be using or setting up the online software, for example a teacher or instructor. </p>'
                . '<p>Feel free to send along any questions to <span style="text-decoration: underline;">sales@accessibyte.com</span>.</p>';
                
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            $pdf->Output($fullpath, 'F');  
            $mail->setFrom('support@accessibyte.com', 'Accessibyte Support');
            $mail->addAddress($to);     // Add a recipient
            $mail->addReplyTo($to);
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = $message;
            $mail->AddAttachment($fullpath);
            $mail->send();                  
            
            $_SESSION['error']['message'] = "Request Quote has been send.";
            $_SESSION['error']['color'] = 'success';
                    
        }
    } catch (Exception $e) {
        $_SESSION['error']['message'] = "Could not send Quote. Try again";
        $_SESSION['error']['color'] = 'danger';
        
    }    
}
?>
