<?php
session_start();
ob_start();
$webpath = "https://dev.accessibyte.com/";
    if($_SERVER['HTTP_HOST'] == "dev.accessibyte.com"){
        $webpath = "https://dev.accessibyte.com/";
    }
    else if($_SERVER['HTTP_HOST'] == "online.accessibyte.com"){
        $webpath = "https://online.accessibyte.com/";
    }
define('WEB_PATH', $webpath);
define('LICENSES_WEB_PATH',  "https://dev.accessibyte.com/");
define('WP_URL', 'https://dev.accessibyte.com/');
if($_SERVER['HTTP_HOST'] == "dev.accessibyte.com"){
    define('ADMIN_URL', WEB_PATH . 'online/');
}
else if($_SERVER['HTTP_HOST'] == "online.accessibyte.com"){
    define('ADMIN_URL', WEB_PATH);
}
define('ADMIN_Text', 'Accessibyte');
//define('STUDENT_PANEL', WEB_PATH.'accessibyte-online-maintenance/');
//define('STUDENT_PANEL', WEB_PATH.'apps/typio1259b/');
define('STUDENT_PANEL', WP_URL . 'apps/typiodir/');
define('STUDENT_PANEL_BDL', WP_URL . 'apps/maindir/');
define('STUDENT_PANEL_BRL', WP_URL . 'apps/brailliodir/');
define('ADMIN_DIR', dirname(__DIR__));
define('LICENSE_UPDATE_FILE_PATH', ADMIN_URL . 'update-license.php');
define('WP_LICENSE_UPDATE_LINK', 'https://dev.accessibyte.com/update-license');

// defualt timezone of chicago
date_default_timezone_set('CST6CDT');
define('WP_HOST', 'localhost');
/*define('WP_USER', 'atacadem_wor1');
define('WP_PASS', 'LWrEgx*d=si9');
define('WP_DB', 'atacadem_wor1');*/
define('WP_USER', 'devaccessibyte_devaccess_user');
define('WP_PASS', 'QZ1$r56UMg.(');
define('WP_DB', 'devaccessibyte_devaccess_wp');/**/
/* ITEM IDS DETAILS
 * TYO = 5 , PRO = 6, AAO = 7, QCO = 8
 */
//define('ITEM_IDS', array(5, 6, 7, 8));

/* ITEM IDS AND NAMES DETAILS
 * here define Licensce Type Name and Licensce Item ids
 */

//$LICENSCE_TYPE_AND_ITEM_ID = array(
//    '5' => 'Typio',
//    '6' => 'ProPack',
//    '7' => 'Accessibyte Arcade',
//    '8' => 'Quick Cards',
//);
//define('LICENSCE_TYPE_AND_ITEM_ID', $LICENSCE_TYPE_AND_ITEM_ID);

include "AES.fusion.php";
include ADMIN_DIR . "/image-settings.php";
////Check User login if not then first login
if (empty($_SESSION['User']['email']) && ( $_SERVER["REQUEST_URI"] != '/online/login/' && $_SERVER["REQUEST_URI"] != '/online/login/Robot.php' ) && ( $_SERVER["REQUEST_URI"] != '/online/login/' && $_SERVER["REQUEST_URI"] != '/online/register.php' && $_SERVER["REQUEST_URI"] != '/online/register.php' && $_SERVER['REQUEST_URI'] != '/online/forgot-pass.php' && $_SERVER['REQUEST_URI'] != '/online/login/find-email.php' && $_SERVER['PHP_SELF'] != '/online/password-reset.php' && $_SERVER['REQUEST_URI'] != '/online/login/check-email.php' && strpos($_SERVER['REQUEST_URI'], 'UID') == false )) {
    //header('Location: ' . ADMIN_URL . 'login');
}
if (!empty($_SESSION['User']['email'])) {
    $restricted_pages_while_logged_in = array(
        '/online/login/', '/online/register.php', '/online/register.php', '/online/password-reset.php', '/online/forgot-pass.php'
    );
    $can_not_visit_without_student_selected = array(
        '/online/student/student-overview.php'
    );
    if (in_array($_SERVER["REQUEST_URI"], $restricted_pages_while_logged_in) || (in_array($_SERVER["REQUEST_URI"], $can_not_visit_without_student_selected) && (!isset($_SERVER['QUERY_STRING']) || $_SERVER['QUERY_STRING'] == ''))) {
        header("Location:" . ADMIN_URL . 'student/student-list.php');
    } elseif (in_array($_SERVER['PHP_SELF'], $restricted_pages_while_logged_in)) {
        header("Location:" . ADMIN_URL . 'student/student-list.php');
    } elseif (in_array($_SERVER['PHP_SELF'], $can_not_visit_without_student_selected)) {
        unset($_SESSION['studentid']);
        list($string, $studentid) = explode('=', $_SERVER['QUERY_STRING']);
        $_SESSION['studentid'] = $studentid;
        $GLOBALS['studentid'] = $studentid;
    }
}
$HostName = "localhost";
/*$DbUser = "atacadem_squall";
$DbPassword = "a.&Ut2k(+hz7";
$Database = "atacadem_gardenDB";*/

$DbUser = "devaccessibyte_copyuser";
$DbPassword = "P?fpg2fZL~G-";
$Database = "devaccessibyte_onlinec";
$con = mysqli_connect($HostName, $DbUser, $DbPassword, $Database);
$salt = "kldfghuerhteru";
$GLOBALS['con'] = $con;
function query($Data) {
    global $con;
    return mysqli_query($con, $Data);
}

function fetch($Data) {
    return mysqli_fetch_array($Data);
}

function rows($Data) {
    return mysqli_num_rows($Data);
}

function escapeString($string) {
    global $con;
    return mysqli_real_escape_string($con, $string);
}

// 30-05-2019
function update_query($table, $where, $Data_array) {

    $str_data = '';
    if (!empty($Data_array)) {
        if (count($Data_array) > 1) {
            $add_coma = ',';
        } else {
            $add_coma = '';
        }
        $cnt = 1;
        foreach ($Data_array as $data_key => $data_value) {
            if (count($Data_array) == $cnt) {
                $add_coma = '';
            }
            $str_data .= $data_key . '= "' . $data_value . '"' . $add_coma;
            $cnt++;
        }

        if ($str_data != '') {
            query('update ' . $table . ' set ' . $str_data . ' where ' . $where);
            return TRUE;
        }
    }

    return FALSE;
}

/* Funcltion Of Limited Word Display  Below // @@Don't Change Anything@@ */

function encryptIt($q) {
    $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
    $qEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $q, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
    return( $qEncoded );
}

function decryptIt($q) {
    $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
    $qDecoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($q), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
    return( $qDecoded );
}

/* new function for encrypt/decrypt code */

function encrypt($payload) {
  $key = 'qJB0rGtIn5UB1xG03efyCp'; 
  $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
  $encrypted = openssl_encrypt($payload, 'aes-256-cbc', $key, 0, $iv);
  return base64_encode($encrypted . '::' . $iv);
}

function decrypt($garble) {
    $key = 'qJB0rGtIn5UB1xG03efyCp'; 
    list($encrypted_data, $iv) = explode('::', base64_decode($garble), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}
/* new function for encrypt/decrypt code */

function generateRandomToken() {

    //make a new random token
    //return bin2hex(random_bytes(16)); //php 7
    return bin2hex(openssl_random_pseudo_bytes(16)); //php 5
}

function get_browser_name($user_agent) {
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/'))
        return 'Opera';
    elseif (strpos($user_agent, 'Edge'))
        return 'Edge';
    elseif (strpos($user_agent, 'Chrome'))
        return 'Chrome';
    elseif (strpos($user_agent, 'Safari'))
        return 'Safari';
    elseif (strpos($user_agent, 'Firefox'))
        return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7'))
        return 'Internet Explorer';

    return 'Other';
}

//Get User Log Details 
function getUserLog($userid = FALSE, $limit = '', $order = 'DESC', $app_type = false, $teacher_code = false,$start_date = '',$end_date = '',$license = '') {

    //Set blank
    $get_log = $query_var = array();

    $where = '';
    $current_date = date('Y-m-d');

    //Check user id exist
    if (!empty($userid)) {
        $query_var[] = " log.id ='" . $userid . "'";
    }

    if (!empty($app_type)) {
        $query_var[] = " log.app ='" . $app_type . "'";
    } else {
        $query_var[] = " log.app ='Overview-OL'";
    }

    if (!empty($teacher_code)) {
        $query_var[] = " user.teacher ='" . $teacher_code . "'";
    }
    if (!empty($license)) {
        $query_var[] = " user.license ='" . $license . "'";
    }
    if(!empty($start_date) && !empty($end_date)){
        $query_var[] = " log.date >= '" . $start_date . "' AND log.date <= '" . $end_date . "'";
    }
    
    $query_var[] = " user.role ='student'";

    if (!empty($query_var)) {
        $where = 'WHERE ' . implode(' AND ', $query_var);
    }

    //Check Log limit set
    if (!empty($limit)) {
        $limit = ' LIMIT ' . $limit;
    }

    //Get log data to table
    //echo "SELECT * FROM log INNER JOIN user ON log.id = user.id $where group by log.data,log.date ORDER BY date DESC $limit ";
    $query = query("SELECT * FROM log INNER JOIN user ON log.id = user.id $where group by log.data,log.date ORDER BY date DESC $limit ");
    //$query = query("SELECT distinct (log.data),user.*,log.id,log.app,log.date,log.file,log.lognr  FROM log INNER JOIN user ON log.id = user.id $where ORDER BY date DESC $limit ");
    //get rows and store on blank data of log
    while ($row = mysqli_fetch_array($query)) {
        $date = getTimezonewiseDate($row['date']);
        $row['time_ago'] = getTimeDiff($date);
        $get_log[] = $row;
    }

    return $get_log;
}


function getTimezonewiseDate($date) {
    $id = $_SESSION['User']['id'];
    $timezone = query('SELECT * FROM settings WHERE id = "' . $id . '" AND item = "555" ');
    $timezoneName = 'America/Chicago';
    if ($timezone->num_rows > 0) {
        $timezone_data = fetch($timezone);
        $timezoneName = $timezone_data['variable'];
    }
    $datetime1 = $date;
   // $datetime = new DateTime($datetime1);
	$datetime = new DateTime($datetime1, new DateTimeZone($timezoneName));
   // $la_time = new DateTimeZone($timezoneName);
   // $datetime->setTimezone($la_time);
	$datetime->setTimezone(new DateTimeZone($timezoneName));
    return $datetime->format('Y-m-d');
}

function gettimezonewiseDateTime($date) {
    $id = $_SESSION['User']['id'];
    $timezone = query('SELECT * FROM settings WHERE id = "' . $id . '" AND item = "555" ');
    $timezoneName = 'America/Chicago';
    if ($timezone->num_rows > 0) {
        $timezone_data = fetch($timezone);
        $timezoneName = $timezone_data['variable'];
    }

    $datetime = new DateTime($date);
    $la_time = new DateTimeZone($timezoneName);
    $datetime->setTimezone($la_time);
    return $datetime->format('Y-m-d H:i:s');
}

function get_user_by_role($role) {
    $sql = query("SELECT user.id,user.username,user.firstname,user.lastname,settings.variable FROM user INNER JOIN settings on settings.id = user.id  WHERE user.role='" . $role . "' AND settings.item=3");
    $users = array();
    while ($row = fetch($sql)) {
        $users[] = $row;
    }
    return $users;
}

/**
 * Register user in system
 * 
 * @param string $fname First name
 * @param string $lname Last name
 * @param string $email email
 * @param string $pass Password
 * @param string $uname Username
 * @param string $org Organization name
 * @param string $license License
 */
function register_user($user_type, $fname, $lname, $username, $email, $pass, $org = '', $license, $age = '', $grade = '', $teacher_code, $redirect = true,$seatlimit='0',$teachername = '') {
    global $con;
    $techerCode = generateRandomString();
	
    $teacher_name="";
    if ($user_type == 'student') {
       
        $techerCode = $teacher_code;
        if ($fname == '') {
            $fname = null;
        }
        if ($lname == '') {
            $lname = null;
        }
        $org = (!empty($org) ? $org : $_SESSION['User']['organization']);
         
        if(!empty($teachername)){
            $teacher_name=$teachername;
        } else{
            $teacher_first_name=base64_decode($_SESSION['User']['firstname']);
            $teacher_last_name=base64_decode($_SESSION['User']['lastname']);
            $teacher_name=base64_encode($teacher_first_name." ".$teacher_last_name);
        }
        
    }
    //$pass = crypt($pass);
    $licenseExist = check_license_available_not($license, $user_type);  // check available license or not
    
    if (!empty($licenseExist)) {
        
        $pass = md5($pass);
        $token = getToken(30);
        
        
        query("INSERT INTO user SET
                    `username` = '{$username}',
                    `email`	   = '{$email}',
                    `password` = '{$pass}',
                    `firstname`= '{$fname}',
                    `lastname` = '{$lname}',
                    `organization` = '{$org}',
                    `teacher`	= '{$techerCode}',
                    `token` 	= '{$token}', 
                    `role`		= '{$user_type}',
                    `login`		= '0',
                    `license`	= '{$license}',
                    `age`       = '{$age}',
                    `grade`     = '{$grade}',
                    `seat_limit` ='{$seatlimit}',
                    `teacher_name` ='{$teacher_name}'
                    ");

        $user_id = mysqli_insert_id($con);
        if ($user_id) {
            //05-07-2019
            query("INSERT INTO users_licenses SET `user_id` = '{$user_id}', `license` = '{$license}' ");

            $val = explode("-", $license);

            query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '1', `variable` = '{$username}' ");
            query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '2', `variable` = '{$fname}' ");
            AddUserDefault_ExtaSettings1($user_id);
            AddUserDefault_ExtaSettings9($user_id);
            AddUserDefault_ExtaSettings2($user_id);
            AddUserDefault_ExtaSettings3($user_id);
            AddUserDefault_ExtaSettings4($user_id);
            AddUserDefault_ExtaSettings5($user_id);
            AddUserDefault_ExtaSettings6($user_id);
            AddUserDefault_ExtaSettings8($user_id);
            AddUserDefault_ExtaSettings7($user_id);
            if ($val[0] == "TYO") {
                query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '5', `variable` = '{$license}' ");
            } else if ($val[0] == "BDL") {
                query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '5', `variable` = '{$license}' ");
            }else if ($val[0] == "BRL") {
                query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '5', `variable` = '{$license}' ");
            } else if ($val[0] == "TCH") {
                query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '5', `variable` = '{$license}' ");
            } else if ($val[0] == "TCHP") {
                query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '5', `variable` = '{$license}' ");
            } else if ($val[0] == "PRO") {
                query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '6', `variable` = '{$license}' ");
  query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '125', `variable` = '4' ");
 update_query('settings', 'id = "' . $user_id . '" and item = 165' ,array('variable' => 0));

  update_query('settings', 'id = "' . $user_id . '" and item = 133' ,array('variable' => 1));
  
            } else if ($val[0] == "AAO") {
                query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '7', `variable` = '{$license}' ");
            } else if ($val[0] == "QCO") {
                query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '8', `variable` = '{$license}' ");
            }else if ($val[0] == "TCHI") {
                query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '5', `variable` = '{$license}' ");
                query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '133', `variable` = '1' ");
                query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '533', `variable` = '1' ");
 query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '125', `variable` = '4' ");
 update_query('settings', 'id = "' . $user_id . '" and item = 165' ,array('variable' => 0));

  update_query('settings', 'id = "' . $user_id . '" and item = 133' ,array('variable' => 1));
  
            } 

            //Adding Default settings
          //  AddUserDefault_ExtaSettings($user_id);
          
            // Adding Default text for student only
            if ($user_type == 'student') {
                AddUserDefault_ExtaText($user_id);
            }
            
            $result = activate_license($license, $user_id, $redirect);
            if ($result) {
                update_no_license_data($license, $user_type);   // update no of license data 
            }
            get_license_details($license, $user_id);

            // Update teacher as admin if teacher register for same license first
            /* check if single teacher exist then doesnot add teacher as admin role */
            /* Comment code for the not set first teacher as admin
            if ($user_type == 'teacher') {
                $single_license = get_license_data($license); 
                if(isset($single_license) && !empty($single_license) && $single_license['no_teacher'] > 1){
                    updateTeacherAsAdmin($license,$user_id);
                }
            }*/

            /** Add license uses note on payment history */
            add_license_note_to_history($license, $user_id, 'activate', $user_type);
            
            /* function to update promocode and price option*/
            addPriceoptionPromocode($license, $user_id);

            /* Add teacher to mailerlite group */
            if ($user_type == 'teacher') {
                $mailerArr = array('email' => base64_decode($email),'org' => base64_decode($org),'fname' => base64_decode($fname),'lname' => base64_decode($lname));
                addTomailerlite($mailerArr);
            }    
            if ($redirect && $result) {
                send_user_registration_email($username);
                $_SESSION['error']['message'] = "Registration Successful!";
                $_SESSION['error']['color'] = "success";
                header("Location:" . ADMIN_URL . 'login');
                exit;
            } elseif (!$redirect && $result && $user_type == 'teacher') {
                send_user_registration_email($username);
                $_SESSION['error']['message'] = "Your teacher account was created successfully.";
                $_SESSION['error']['color'] = "success";
            }
            elseif (!$redirect && $result) {
                send_user_registration_email($username);
                $_SESSION['error']['message'] = "Your student account was created successfully.";
                $_SESSION['error']['color'] = "success";
            }
            
        } else {
            $_SESSION['error']['message'] = "Could not save data at the moment.";
            $_SESSION['error']['color'] = "danger";
            if ($redirect) {
                header("Location:" . ADMIN_URL . 'register.php');
                exit;
            }
        }
    } else {
        $_SESSION['error']['message'] = "Not enough seats on the license.";
        $_SESSION['error']['color'] = "danger";
        header("Location:" . ADMIN_URL . 'login');
        exit;
    }
}

/**
 * this function will activate license provided while on registration
 * @param unknown_type $lincense
 * @param unknown_type $user_id
 * @param unknown_type $redirect
 * @return unknown
 */
function activate_license($lincense, $user_id, $redirect = true) {
    $val = explode("-", trim($lincense));
    if ($val[0] == "PRO")
        $item = "67695";
    elseif ($val[0] == "BDL")
        $item = "4184";
    elseif ($val[0] == "BRL")
        $item = "88653";
    elseif ($val[0] == "TYO")
        $item = "3584";
    else if ($val[0] == "TCH")
        $item = "4111";
    else if ($val[0] == "TCHI")
        $item = "67578";
    else if ($val[0] == "TCHP") /* ADd new liecence key for teacher register */
        $item = "30398";
    else if ($val[0] == "WCO")
        $item = "0";
    else if ($val[0] == "AAO")
        $item = "0";
    else if ($val[0] == "TY")
        $item = "192";
    else if ($val[0] == "QCO")
        $item = "4182";
    else if ($val[0] == "AA")
        $item = "905";
    else if ($val[0] == "WW")
        $item = "207";
    $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $lincense;
    // echo $url;exit;
    //step1
    $ch = curl_init();
    //step2
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //step3
    $result = curl_exec($ch);
    //step4
    curl_close($ch);
    //step5
    $status = json_decode($result);

    $sql = "DELETE FROM user WHERE user.id={$user_id}";
    if (isset($status) && !empty($status->success) && $status->success == "true") {
		if($status->site_count == 1){
			update_expiry_license_data($lincense,$val[0]);
			//print_r($status);
            //die();
		    //return TRUE;
		}
        return TRUE;
    } else if (isset($status) && !empty($status->error) && $status->error == "expired") {
		if($status->site_count == 0 && $status->activations_left > 0){
			update_expiry_license_data($lincense,$val[0]);
			//print_r($status);
            //die();
			return TRUE;
		}
		
        if ($redirect) {
            query($sql);
            Delete_UserAdded_ExtraSettings($user_id);
        }
        $_SESSION['error']['message'] = "This license is expired";
        $_SESSION['error']['color'] = "danger";
    } else if (isset($status) && !empty($status->error) && $status->error == "missing") {
        if ($redirect) {
            query($sql);
            Delete_UserAdded_ExtraSettings($user_id);
        }
        $_SESSION['error']['message'] = "This license is missing";
        $_SESSION['error']['color'] = "warning";
    } else if (isset($status) && !empty($status->error) && $status->error == "no_activations_left") {
        if ($redirect) {
            query($sql);
            Delete_UserAdded_ExtraSettings($user_id);
        } else {
            return TRUE;
        }
        $_SESSION['error']['message'] = "This license is already activated";
        $_SESSION['error']['color'] = "danger";

    }

    //set redirect
    if ($redirect) {
        header("Location:" . ADMIN_URL . 'register.php');
        exit;
    }

    return false;
}
/** updating license expiry date
**/
function update_expiry_license_data($license_key,$type) {
    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licenses where license_key = '" . $license_key . "'");
    $data = mysqli_fetch_array($query);

    if (isset($data) && !empty($data) && isset($data['id']) && isset($data['payment_id'])) {
	
        $payment_id = $data['payment_id'];
        $query = mysqli_query($wcon, "SELECT * FROM pia_postmeta where post_id = '" . $payment_id . "' and meta_key = '_edd_payment_stocklicense'");
        $payment_data = mysqli_fetch_array($query);
		
		if (!empty($payment_data) && isset($payment_data['meta_value']) && !empty($payment_data['meta_value']) && $payment_data['meta_value'] == 1) {
        $query1 = mysqli_query($wcon, "SELECT * FROM pia_postmeta where post_id = '" . $payment_id . "' and  meta_key = '_edd_payment_stocktime' order by meta_id asc");
        $payment_data1 = mysqli_fetch_array($query1);
       // print_r($payment_data1);
        //die();
		if(!empty($payment_data1) && isset($payment_data1['meta_value']) && !empty($payment_data1['meta_value'])){
		     $dayss = $payment_data1['meta_value'];
		  $d=strtotime("+".$dayss." days");
$newexpiry = strtotime(date("Y-m-d H:i:s", $d));
//print_r($newexpiry);
mysqli_query($wcon, "update pia_edd_licenses set expiration = '" . $newexpiry . "'  where id = '" . $data['id'] . "' and license_key = '".$license_key."'");
		    return true;
		}else{
		    
		    return false;
		}
	//	$type_stock = explode(',',$payment_data1[0][3]);
//	print_r('type'.$type_stock);
	//	$val = explode(',',$payment_data1[1][3]);
//print_r('val'.$val);
	//	$combo =array_combine($type_stock,$val);
	//	print_r($combo);
		
	//	foreach($combo as $key => $value){
	//print_r($key);
	//print_r($type);
	//		if($key == $type){
		
	//		 $dayss = $value;
	//			  $d=strtotime("+".$dayss." days");
//$newexpiry = strtotime(date("Y-m-d H:i:s", $d));
//print_r($newexpiry);
//mysqli_query($wcon, "update pia_edd_licenses set expiration = '" . $newexpiry . "'  where id = '" . $data['id'] . "' and license_key = '".$license_key."'");
      //die();
	 
//			  }
			
			
	//	}
		
	//	 return true; 
    } else {
        return false;
    }
	 } else {
        return false;
    }
}


/**
 * Check Teacher license activate or expired
 * @param unknown_type $lincense
 * @param unknown_type $user_id
 * @param unknown_type $redirect
 * @return unknown
 */
function check_login_time_license($lincense) {

    $val = explode("-", trim($lincense));

    if ($val[0] == "PRO")
        $item = "67695";
    else if ($val[0] == "BDL")
        $item = "4184";
    elseif ($val[0] == "BRL")
        $item = "88653";
    elseif ($val[0] == "TYO")
        $item = "3584";
    else if ($val[0] == "TY")
        $item = "192";
    else if ($val[0] == "TCH")
        $item = "4111";
 else if ($val[0] == "TCHI")
        $item = "67578";
    else if ($val[0] == "TCHP") /* ADd new liecence key for teacher register */
        $item = "30398";
    else if ($val[0] == "QCO")
        $item = "4182";
    else if ($val[0] == "AA")
        $item = "905";
    else if ($val[0] == "WW")
        $item = "207";

    if (empty($item)) {

        $info = array(
            'status' => 'none',
            'license' => '-'
        );
        return $info;
    }

    $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $lincense;
	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);

    $result = curl_exec($ch);
    curl_close($ch);

    $license = json_decode($result, true);
	
    if ($license['success'] == 1) {

        if ($license['license'] == 'valid') {

            if ($license['expires'] == 'lifetime') {
                $info = array(
                    'days' => 'Lifetime',
                    'status' => 'active',
                    'license' => $lincense
                );
            } else {
                $today = date_create(date('Y-m-d'));
                $expires = date_create(date('Y-m-d', strtotime($license['expires'])));
                $diff = date_diff($today, $expires);

                if ($diff->days <= 30) {
                    $info = array(
                        'days' => $diff->days,
                        'status' => 'expires',
                        'license' => $lincense
                    );
                } else {
                    $info = array(
                        'days' => $diff->days,
                        'status' => 'active',
                        'license' => $lincense
                    );
                }
            }
        } else if ($license['license'] == 'expired') {
            $info = array(
                'status' => 'expired',
                'license' => $lincense,
                'days' => ''
            );
        }
    } else {

        $info = array(
            'status' => 'invalid',
            'license' => $lincense
        );
    }

    return $info;
}

/**
 * Generate ABCD_0123 string for teacher code
 * 
 * @return string Teacher code
 * 
 * @todo This logic needs to change as there is possible duplication of code after 1000 generations
 */
function generateRandomString() {
    $number = '0123456789';
    $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charactersLength = strlen($characters);
    $numberLength = strlen($number);
    $randomString1 = array();
    $randomString2 = array();
    for ($i = 0; $i < 4; $i++) {
        $randomString1[$i] = $characters[rand(4, $charactersLength - 1)];
    }
    $randomString = implode('', $randomString1);
    for ($j = 5; $j < 9; $j++) {
        $randomString2[$j] = $number[rand(4, $numberLength - 1)];
    }
    $randomString .= '-' . implode('', $randomString2);
    return $randomString;
}

/**
 * Crypt token
 * 
 * @param int $min Minimum Length
 * @param int $max Max Length
 * @return string
 */
function crypto_rand_secure($min, $max) {
    $range = $max - $min;
    if ($range < 1)
        return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}

/**
 * Generate User toekn
 * 
 * @param type $length Length of token
 * 
 * @return string Token
 */
function getToken($length) {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
    }

    return $token;
}

//Get Time Different
function getTimeDiff($date) {

    $now = time();
    $your_date = strtotime($date);
    $datediff = $now - $your_date;
    $time_ago = floor($datediff / (60 * 60 * 24));

    if ($time_ago == 0 || $time_ago < 0) {
        $time_ago = 'Today';
    } elseif ($time_ago == 1) {
        $time_ago = 'Yesterday';
    } elseif ($time_ago == 2) {
        $time_ago = '2 days ago';
    } elseif ($time_ago == 3) {
        $time_ago = '3 days ago.';
    } elseif ($time_ago == 4) {
        $time_ago = '4 days ago.';
    } elseif ($time_ago == 5) {
        $time_ago = '5 days ago.';
    } elseif ($time_ago > 5) {
        $time_ago = date('M d, Y', strtotime($date));
    }
    return $time_ago;
}

/**
 * Converting timestamp to time ago in PHP e.g 1 day ago, 2 days ago
 * 
 * @param datetime $datetime
 * @param type $full
 * @return type
 */
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

/**
 * Convert number (int) to word
 * 
 * @param int $number Number to convert into word
 * @param boolean $plusOne Add plus one to $number (usefull for array that starts from 0)
 * @param string $locale Number to world language
 * @return string
 */
function numberFormatter($number, $plusOne = false, $locale = "en") {
    $numberToWord = new NumberFormatter($locale, NumberFormatter::SPELLOUT);
    if ($plusOne) {
        return $numberToWord->format($number + 1);
    } else {
        return $numberToWord->format($number);
    }
}

function numberIcons($app) {

    if ($app == 'Quick-Cards-OL') {
        return ' fa-check one ';
    } elseif ($app == 'Typio-OL') {
        return ' fa-trophy two';
    } elseif ($app == 'Arcade-OL') {
        return ' fa-rocket three';
    } else {
        return false;
    }
}

/**
 * Parse App name
 * 
 * @param string $appName App name to be parsed
 * @return string Parsed Appname
 */
function getParsedAppName($appName) {
    if ($appName == 'Typio-OL') {
        $name = 'Typio';
    } elseif ($appName == "Quick-Cards-OL") {
        $name = "Quick Cards";
    } elseif ($appName == "Arcade-OL") {
        $name = "Accessibyte Arcade";
    }
    return $name;
}

/**
 * Display App Activity data
 * 
 * @param string $app App Name
 * @param string $data Data for the app form Log table
 * @param string $file Filename from Log table for app
 * @return string App activity data
 */
function displayActivityData($app, $data, $file) {
    if ($app == "Typio-OL") {
        list($wpm, $accuracy, $keycombo) = explode('|', $data);
        $activity = $file . '<br>';
        $activity .= $wpm . " WPM, " . $accuracy . "% Accuracy, " . $keycombo . " Key Combo";
    } elseif ($app == "Quick-Cards-OL") {
        list($presidents, $missed, $card) = explode('|', $data);
        $activity = $file . " � " . $presidents . '%<br>';
        $activity .= $missed . " cards missed: " . implode(',', explode('~', $card));
    } elseif ($app == "Arcade-OL") {
        $activity = "Played " . $file;
    }
    return $activity;
}

function weeklyAppDataOverview($appName, $userid, $weekly = true) {
    //Set blank
    $get_log = $query_val = array();
    $where = '';
    $current_date = date('Y-m-d');

    //Check user id exist
    if (!empty($userid)) {
        $query_val[] = " log.id='" . $userid . "'";
    }

    if (!empty($appName)) {
        $query_val[] = " log.app='" . $appName . "'";
    }

    if ($weekly) {
        $query_val[] = "log.date >= DATE(NOW()) - INTERVAL 7 DAY";
    }

    if (!empty($query_val)) {
        $where = 'WHERE ' . implode(' AND', $query_val);
    }

    //Get log data to table
    $query = query("SELECT COUNT(*) as total FROM log INNER JOIN user ON log.id = user.id $where ORDER BY date DESC ");

    $data = fetch($query);
    if (!empty($data)) {
        return $data['total'];
    } else {
        return 0;
    }
}

/**
 * Get app logs on date range
 * 
 * @param sting $appname Appname e.g. Arcade-OL
 * @param int $userid User id
 * @param date $startdate start date
 * @param date $enddate end date
 * @return array
 */
function getapplogforweek($userid, $startdate, $enddate) {

    if (empty($userid) || empty($startdate) || empty($enddate)) {
        return;
    }
    $startdate = date('Y-m-d', strtotime($startdate));
    $enddate = date('Y-m-d', strtotime($enddate));
    //$startdate = '2017-01-01';
    global $con;
    $total = "SELECT * FROM log WHERE app ='Overview-OL' and log.id ='" . $userid . "'  AND log.date >= '" . $startdate . "' AND log.date <= '" . $enddate . "' ORDER BY date DESC";
    $total_row = mysqli_query($con, $total);
    $total_rows = mysqli_num_rows($total_row);

    $data = "SELECT count(*) as total,file FROM log WHERE app ='Overview-OL' and log.id ='" . $userid . "' AND log.date >= '" . $startdate . "' AND log.date <= '" . $enddate . "' group by file ORDER BY date DESC";

    $data_rows = mysqli_query($con, $data);
    $html = "";
    $avrg = 0;
    //$html .="<tr><td>1<td><td>232</td></tr>";

    while ($row = mysqli_fetch_assoc($data_rows)) {
        $avrg = round($row['total'] / $total_rows, 2) * 100;
        $html .= "<tr><td> " . $row['file'] . " </td><td> " . $avrg . " </td></tr>";
    }

    return $html;
}

function getapplogforweekTable($userid, $startdate, $enddate) {

    if (empty($userid) || empty($startdate) || empty($enddate)) {
        return;
    }
    $startdate = date('Y-m-d', strtotime($startdate));
    $enddate = date('Y-m-d', strtotime($enddate));
    //$startdate = '2017-01-01';
    global $con;
    $data = "SELECT * FROM log WHERE app ='Overview-OL' and log.id ='" . $userid . "' AND log.date >= '" . $startdate . "' AND log.date <= '" . $enddate . "' GROUP BY data,file,date ORDER BY date DESC";

    $data_rows = mysqli_query($con, $data);
    $total_row = 0;
    $html = "";

    while ($row = mysqli_fetch_assoc($data_rows)) {
        $class="";
        if($row['file'] == "Typio"){
            $class="Typio-OL";
        }
        else if($row['file'] == "ProPack"){
            $class="Propack";
        }
        else if($row['file'] == "Quick Cards"){
            $class="Quick-Cards-OL";
        }
        else if($row['file'] == "Arcade"){
            $class="Arcade-OL";
        }

        //$data_count = count(explode("|", $row['data']));  
        $html .= "<tr class='".$class."'><td> " . $row['file'] . " </td><td> " . date('m/d/Y', strtotime($row['date'])) . " </td>
        <td> " . $row['data'] . " </td>
        </tr>";
        $total_row += 1;
    }
    $result['total_row'] = $total_row;
    $result['html'] = $html;
    return $result;
}

function getHeaderInfo($userid, $item) {

    $where = '';
    $get_item = array();

    //Check user id exist
    if (!empty($userid)) {
        $where .= " WHERE settings.id ='" . $userid . "'";
    }
    $where .= " AND settings.item='" . $item . "' ";
    $query = query("SELECT settings.variable from settings $where");
    //get rows and store on blank data of log
    while ($row = mysqli_fetch_array($query)) {
        return $row['variable'];
    }
}

/**
 * Check license status that user has used
 *
 * @param unknown_type $userid
 * @param unknown_type $itemID
 * @return unknown
 */
function checkLicenseStatus($userid, $itemID = '') {

    $where = $item = '';

//    $query = query("SELECT settings.variable from settings WHERE settings.id ='" . $userid . "'  AND settings.item='" . $itemID . "' ");
    $query = query("SELECT license as variable from user WHERE id ='" . $userid . "' ");
    $data = fetch($query);

    $info = array('status' => 0);
    if (!empty($data) && !empty($data['variable'])) { 
        list($itemName, $type) = explode('-', $data['variable']);

        if ($itemName == "PRO")
            $item = "67695";
        else if ($itemName == "BDL")
            $item = "4184";
        elseif ($itemName == "BRL")
        $item = "88653";
        elseif ($itemName == "TYO")
            $item = "3584";
        elseif ($itemName == "TY")
            $item = "192";

        elseif ($itemName == "QCO")
            $item = "4182";
        else if ($itemName == "TCH")
            $item = "4111";
     else if ($itemName == "TCHI")
        $item = "67578";
        else if ($itemName == "TCHP") /* Add new license for teacher dashboard */
            $item = "30398";
        elseif ($itemName == "AA")
            $item = "905";

        elseif ($itemName == "WW")
            $item = "207";
        $data['variable'] = trim($data['variable']);
        $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $data['variable'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        $license = json_decode($result, true);
        if ($license['success'] == 1) { 

            if ($license['license'] == 'valid') {  

                if ($license['expires'] == 'lifetime') {
                    $info = array(
                        'days' => 'Lifetime',
                        'status' => 'active',
                        'license' => $data['variable']
                    );
                } else {
                    $today = date_create(date('Y-m-d'));
                    $expires = date_create(date('Y-m-d', strtotime($license['expires'])));
                    $diff = date_diff($today, $expires);
                    if ($diff->days <= 30) {
                        $info = array(
                            'days' => $diff->days,
                            'status' => 'expires',
                            'license' => $data['variable']
                        );
                    } else {
                        $info = array(
                            'days' => $diff->days,
                            'status' => 'active',
                            'license' => $data['variable']
                        );
                    }
                }
            } else if ($license['license'] == 'expired') { 
                $info = array(
                    'days' => '',
                    'status' => 'expired',
                    'license' => $data['variable']
                );
            } else if ($license['license'] == 'inactive') { 
                $info = array(
                    'days' => '',
                    'status' => 'inactive',
                    'license' => $data['variable']
                );
            }
        } else { 
            $info = array(
                'days' => '',
                'status' => 'invalid',
                'license' => $data['variable']
            );
        }
    } else { 
        $info = array(
            'days' => '',
            'status' => 'none',
            'license' => '-'
        );
    }
    return $info;
}

function checkLicenseStatus_FromWordpress($license_key) {

    if (!empty($license_key)) {
        list($itemName, $type) = explode('-', trim($license_key));

        if ($itemName == "PRO")
            $item = "67695";
        else if ($itemName == "BDL")
            $item = "4184";
        elseif ($itemName == "BRL")
        $item = "88653";
        elseif ($itemName == "TYO")
            $item = "3584";

        elseif ($itemName == "TY")
            $item = "192";

        elseif ($itemName == "QCO")
            $item = "4182";

        elseif ($itemName == "AA")
            $item = "905";

        elseif ($itemName == "WW")
            $item = "207";

        $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $license_key;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        $license = json_decode($result, true);

        if ($license['success'] == 1) {

            if ($license['license'] == 'valid') {

                if ($license['expires'] == 'lifetime') {
                    $info = array(
                        'days' => 'Lifetime',
                        'status' => 'active',
                        'license' => $license_key,
                        'license_limit' => $license['license_limit'],
                        'license_used' => $license['site_count'],
                    );
                } else {
                    $today = date_create(date('Y-m-d'));
                    $expires = date_create(date('Y-m-d', strtotime($license['expires'])));
                    $diff = date_diff($today, $expires);
                    if ($diff->days <= 30) {
                        $info = array(
                            'days' => $diff->days,
                            'status' => 'expires',
                            'license' => $license_key,
                            'license_limit' => $license['license_limit'],
                            'license_used' => $license['site_count'],
                        );
                    } else {
                        $info = array(
                            'days' => $diff->days,
                            'status' => 'active',
                            'license' => $license_key,
                            'license_limit' => $license['license_limit'],
                            'license_used' => $license['site_count'],
                        );
                    }
                }
            } else if ($license['license'] == 'expired') {
                $info = array(
                    'days' => '',
                    'status' => 'expired',
                    'license' => $license_key,
                    'license_limit' => $license['license_limit'],
                    'license_used' => $license['site_count'],
                );
            } else if ($license['license'] == 'inactive') {
                $info = array(
                    'days' => '',
                    'status' => 'inactive',
                    'license' => $license_key,
                    'license_limit' => $license['license_limit'],
                    'license_used' => $license['site_count'],
                );
            }
        } else {
            $info = array(
                'days' => '',
                'status' => 'invalid',
                'license' => $license_key,
                'license_limit' => $license['license_limit'],
                'license_used' => $license['site_count'],
            );
        }
    } else {
        $info = array(
            'days' => '',
            'status' => 'none',
            'license' => '-',
            'license_limit' => '-',
            'license_used' => '-',
        );
    }
    return $info;
}

if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST['field']) && !empty($_POST['field']) && $_POST['field'] == 'variable') {

        //$val = mysqli_escape_string($_POST['value']);
        $sql = 'UPDATE settings SET variable="' . $_POST['value'] . '" WHERE id="' . $studentid . '" AND item=3';
        if (query($sql)) {
            echo json_encode(array('result' => array('status' => 200)));
            exit;
        } else {
            echo json_encode(array('result' => array('status' => 300)));
            exit;
        }
    }
}

//Status results store
$current_user_status = array(
    0 => 'Offline',
    1 => 'Currently in Typio Progress Mode',
    2 => 'Currently in Typio Practice Mode',
    3 => 'Currently in Typio Free Type Mode',
    4 => 'Currently in Typio Games',
    5 => 'Currently in Quick Cards Test Mode',
    6 => 'Currently in Quick Cards Practice Mode',
    7 => 'Currently in Quick Cards deck builder',
    8 => 'Currently in Accessibyte Arcade',
    9 => 'Playing Canteen',
    10 => 'Playing Crazy Phrase',
    11 => 'Playing Echo',
    12 => 'Playing FutureBot',
    13 => 'Playing Hangman',
    14 => 'Playing Music Box',
    15 => 'Playing Radio',
    16 => 'Playing Samurai',
    17 => 'Playing Wizard\'s Tower',
    18 => 'Selecting a program',
    19 => 'Currently in the Hub',
);

//Global declared user status results
global $current_user_status;

/* * * General Options * */
$GLOBALS['font_size_options'] = $font_size_options = array(
    20 => 'Small',
    100 => 'Medium',
    200 => 'Large',
);

$font_style_options = array(
    'Roboto' => 'Regular',
    'Roboto Condensed' => 'Condensed',
    'Roboto Mono' => 'Monospaced',
    'Roboto Slab' => 'Serif',
'Montserrat' => 'Bold',
    'Open Dyslexic' => 'Open Dyslexic',
    'Chango' => 'Wide'
);


$GLOBALS['voice_options'] =  $voice_options = array(
    'Off' => 'Off',
    'Default' =>"Default",
);

$GLOBALS['voice_rate_options'] =  $voice_rate_options = array(
    5 => 'Slow',
    10 => 'Medium',
    13 => 'Fast',
);

$GLOBALS['voice_pitch_options'] = $voice_pitch_options = array(
    5 => 'Low',
    9 => 'Medium',
    12 => 'High',
);

$GLOBALS['color_options'] =  $color_options = array(
    '255, 255, 254' => 'White',
    '242, 241, 239' => 'Flat White',
    '105, 105, 105' => 'Grey',
    '0, 0, 1' => 'Black',
    '0, 0, 8'=> 'grey-blue',
    '12, 18, 22' => 'Flat Black',
    '139, 0, 139' => 'Purple',
    '109, 33, 79' => 'Flat Purple',
    '27, 20, 100' => 'Dark Blue',
    '0, 0, 255' => 'Blue',
    '15, 188, 249' => 'Flat Blue',
    '0, 255, 153' => 'Teal',
    '0, 100, 0' => 'Dark Green',
    '0, 255, 0' => 'Green',
    '163, 203, 56' => 'Flat Green',
    '255, 255, 0' => 'Yellow',
    '254, 211, 48' => 'Flat Yellow',
    '250, 130, 49' => 'Orange',
    '139, 0, 0' => 'Dark Red',
    '255, 0, 0' => 'Red',
    '234, 32, 39' => 'Flat Red',
    '255, 0, 102' => 'Pink',
    '253, 121, 168' => 'Flat Pink',
);

$card_flip_options = array(
    1 => 'On',
    0 => 'Off',
);

$soundeffects_options  = array(
    1 => 'On',
    0 => 'Off',
);


$acc_colored = array(
0 => 'Full',
1 => 'Muted',
2 => 'None'

);
$answer_options = array(
    2 => 2,
    3 => 3,
    4 => 4,
    5 => 5,
);

$deck_lock_options = array(
    1 => 'On',
    0 => 'Off',
);

$settings_lock_options = array(
    1 => 'On',
    0 => 'Off',
);

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
    return $user_data;
}

function is_user_belong_to_teacher($user_id, $teacher_code) {

    global $con;

    $query_val = array();

    //Set Table Name
    $table = "user";

    //Get data based on teacher code
    $query_val[] = " `role`='student'";

    //Get data based on teacher code
    if (!empty($teacher_code)) {
        $query_val[] = " `teacher`='" . $teacher_code . "'";
    }

    //Get data based on role
    if (!empty($user_id)) {
        $query_val[] = " `id`='" . $user_id . "'";
    }

    if (!empty($query_val)) {
        $where = ' WHERE ' . implode(' AND', $query_val);
    }

    //Build Query
    $query = "SELECT * FROM `" . $table . "` $where ";

    $user_data_rows = mysqli_query($con, $query);
    $user_data = mysqli_fetch_assoc($user_data_rows);
    if (!empty($user_data)) {
        return true;
    }

    return false;
    ;
}

function get_settings_details($arg) {

    global $con;

    $setting_data = $query_val = array();

    //Set Table Name
    $table = "settings";

    //Get data based on id
    if (!empty($arg['id'])) {
        $query_val[] = " `id`='" . $arg['id'] . "'";
    }

    //Get data based on item
    if (!empty($arg['item'])) {
        $query_val[] = " `item`='" . $arg['item'] . "'";
    }

    if (!empty($query_val)) {
        $where = ' WHERE ' . implode(' AND', $query_val);
    }

    //Build Query
    $query = "SELECT * FROM `" . $table . "` $where ";

    $setting_data_rows = mysqli_query($con, $query);

    while ($setting_data_row = mysqli_fetch_assoc($setting_data_rows)) {

        $setting_data[$setting_data_row['item']] = $setting_data_row['variable'];
    }
    //Just you get Item => variable
    return $setting_data;
}

function send_user_registration_email($username) {
    $query = query("SELECT user.license,user.email,user.username,user.id,user.firstname,user.lastname FROM user where user.username='$username' or user.username='" . base64_encode($username) . "'");
    $data = fetch($query);
    $email = base64_decode($data['email']);
    $variables = array();
//    $encryptToken = rawurlencode(encryptIt($data['token']));
    $variables['firstname'] = base64_decode($data['firstname']);
    $variables['lastname'] = base64_decode($data['lastname']);
    $variables['username'] = base64_decode($data['username']);
    $variables['license'] = $data['license'];
    //Get html template for reset password email
    $template = file_get_contents(ADMIN_URL . 'emails/registration-email.html');

    foreach ($variables as $key => $value) {
        $template = preg_replace_callback('/{{([a-zA-Z0-9\_\-]*?)}}/i', function($match) use ($variables) {
            return $variables[$match[1]];
        }, $template);
    }

    $header = "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html; charset: utf8\r\n";
    $header .= "From: <contact@accessibyte.com>" . "\r\n";
    $header .= 'Bcc: log@accessibyte.com';

    //mail($email, "Registration confirmation email", $template, $header);

//    send_email($email, 'Registration confirmation email', $template,1);
}

function send_token_to_reset_password($username) {
    //$query = query("SELECT user.email,user.token,user.id,user.firstname,user.lastname FROM user where user.username='$username' or user.email='" . base64_encode($username) . "' or user.username='" . base64_encode($username) . "'");
     //$query = query("SELECT user.email,user.token,user.id,user.firstname,user.lastname FROM user where user.username='$username' or user.email='" . base64_encode($username) . "' or user.username='" . base64_encode($username) . "'");
$query = query("SELECT user.email,user.token,user.id,user.firstname,user.lastname FROM user where (user.username='$username' or user.username='" . base64_encode($username) . "' )");
     

$data = fetch($query);

    $email = base64_decode($data['email']);
    $variables = array();
    $encryptToken = rawurlencode(encrypt($data['token']));
    $variables['firstname'] = base64_decode($data['firstname']);
    $variables['lastname'] = base64_decode($data['lastname']);
    $variables['link_to_reset'] = ADMIN_URL . 'password-reset.php?token=' . $encryptToken;
    //Get html template for reset password email
    $template = file_get_contents(ADMIN_URL . 'emails/reset-pass.html');

    foreach ($variables as $key => $value) {
        $template = preg_replace_callback('/{{([a-zA-Z0-9\_\-]*?)}}/i', function($match) use ($variables) {
            return $variables[$match[1]];
        }, $template);
    }

    send_email($email, 'Your password reset link', $template);
}

/**
 * Send HTML email
 * 
 * @param type $to To , Receiver
 * @param type $subject, Email subject
 * @param type $message, HTML template
 */
function send_email($to, $subject, $message, $redirect = '') {

    require 'PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();

    try {
        //Recipients
        $mail->setFrom('support@accessibyte.com', 'Accessibyte Support');
        $mail->addAddress($to);     // Add a recipient
        $mail->addReplyTo($to);
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $message;

        // When Forgot Password Than add BCC
        if (isset($_POST) && isset($_POST['hidden_forgot_pass']) && $_POST['hidden_forgot_pass'] == 'forgotpass') {
			$mail->addBCC('log@accessibyte.com');
        }
		
        // When Forgot Password Than add BCC End

        $mail->send();
        if (empty($redirect)) {
            $_SESSION['error']['message'] = "Password reset link has been sent.";
            $_SESSION['error']['color'] = 'success';
        }
        //echo 'Mail sent: ';
    } catch (Exception $e) {
        //echo 'Mailer Error: ' . $mail->ErrorInfo;
        $_SESSION['error']['message'] = "Could not send password reset link. Try again";
        $_SESSION['error']['color'] = 'danger';
    }
    if (empty($redirect)) {
        header('Location:' . ADMIN_URL . 'forgot-pass.php');
        exit;
    }
}

/**
 * Reset user password
 * @param type $password , New password
 * @param type $token. user token
 */
function reset_user_pass($password, $token, $__redirect = true) {
    $decToken = decrypt($token);
    $query = query("SELECT user.id,user.email FROM user where user.token='$decToken'");
    $user = fetch($query);

    if (!empty($user) && !empty($user['id'])) {
        $userid = $user['id'];
        //$encPass = crypt($password);
        $encPass = md5($password);
        $newToken = getToken(30);
		$email = base64_decode($user['email']);
        //Good to update token after password reset for security purpose
        if (query("UPDATE user set `password`='" . $encPass . "', `token`='" . $newToken . "'  WHERE id=$userid")) {
            $_SESSION['error']['message'] = "Password was changed successfully.";
            $_SESSION['error']['color'] = 'success';
			
			/* update wordpress user pass for same */
			$wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
            $query = mysqli_query($wcon, "SELECT id FROM pia_users where user_login = '" . $email . "' or user_email = '" . $email . "'");
            $data = mysqli_fetch_array($query);
            if(!empty($data['id'])){
                mysqli_query($wcon, "update pia_users set user_pass = '" . $encPass . "'  where ID = '" . $data['id'] . "'");
            }
			/* update wordpress user pass for same */
            if ($__redirect) {
                header('Location:' . ADMIN_URL . 'login');
                exit;
            }
        } else {
            $_SESSION['error']['message'] = "Something went wrong.";
            $_SESSION['error']['color'] = 'danger';

            if ($__redirect) {
                header('Location:' . ADMIN_URL . 'forgot-pass.php');
                exit;
            }
        }
    }
}

/**
 * Adding User Default extra settings
 * @param type $user_id
 * @return type
 */


function AddUserDefault_ExtaSettings1($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                           
                            ( '" . $user_id . "', '15', '100' ),
                            ( '" . $user_id . "', '115', '100' ),
                            ( '" . $user_id . "', '215', '100' ),
                            ( '" . $user_id . "', '315', '100' ),
                            ( '" . $user_id . "', '415', '100' ),

                            ( '" . $user_id . "', '16', '0, 0, 8' ),
                            ( '" . $user_id . "', '116', '0, 0, 8'  ),
                            ( '" . $user_id . "', '216', '0, 0, 8'  ),
                            ( '" . $user_id . "', '316', '0, 0, 8'  ),
                            ( '" . $user_id . "', '416', '0, 0, 8'  ),

                            ( '" . $user_id . "', '17', 'Montserrat' ),
                            ( '" . $user_id . "', '117', 'Montserrat' ),
                            ( '" . $user_id . "', '217', 'Montserrat' ),
                            ( '" . $user_id . "', '317', 'Montserrat' ),
                            ( '" . $user_id . "', '417', 'Montserrat' ),

                            ( '" . $user_id . "', '18', '255, 255, 254' ),
                            ( '" . $user_id . "', '118', '255, 255, 254' ),
                            ( '" . $user_id . "', '218', '255, 255, 254' ),
                            ( '" . $user_id . "', '318', '255, 255, 254' ),
                            ( '" . $user_id . "', '418', '255, 255, 254' ),

                            ( '" . $user_id . "', '19', '255, 0, 120' ),
                            ( '" . $user_id . "', '119', '255, 0, 102' ),
                            ( '" . $user_id . "', '219', '255, 0, 120' ),
                            ( '" . $user_id . "', '319', '255, 0, 120' ),
                            ( '" . $user_id . "', '419', '255, 0, 120' )

                        

                            



                            ");
    return $query;
}
function AddUserDefault_ExtaSettings9($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                           
                           

                            ( '" . $user_id . "', '20', 'Default' ),
                            ( '" . $user_id . "', '120', 'Default' ),
                            ( '" . $user_id . "', '220', 'Default' ),
                            ( '" . $user_id . "', '320', 'Default'),
                            ( '" . $user_id . "', '420', 'Default' ),

                            ( '" . $user_id . "', '21', '10' ),
                            ( '" . $user_id . "', '121', '10' ),
                            ( '" . $user_id . "', '221', '10' ),
                            ( '" . $user_id . "', '321', '10' ),
                            ( '" . $user_id . "', '421', '10' ),
    
                            ( '" . $user_id . "', '22', '9' ),
                            ( '" . $user_id . "', '122', '9' ),
                            ( '" . $user_id . "', '222', '9' ),
                            ( '" . $user_id . "', '322', '9' ),
                            ( '" . $user_id . "', '422', '9' )

                            



                            ");
    return $query;
}
function AddUserDefault_ExtaSettings4($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                           
                            ( '" . $user_id . "', '515', '100' ),
                          
                            ( '" . $user_id . "', '516', '0, 0, 8'  ),
                            
                            ( '" . $user_id . "', '517', 'Roboto' ),
                           
                            ( '" . $user_id . "', '518', '255, 255, 254' ),
                          

                            
                            ( '" . $user_id . "', '519', '255, 0, 102' ),
                            
                            ( '" . $user_id . "', '520', 'Default' ),
                          
                            ( '" . $user_id . "', '521', '10' ),
                            
                            ( '" . $user_id . "', '522', '9' )
                            
                            



                            ");
    return $query;
}
function AddUserDefault_ExtaSettings2($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                          

                          ( '" . $user_id . "', '159', '1' ),
                            ( '" . $user_id . "', '160', 'Light' ),
                            ( '" . $user_id . "', '161', 'Large' ),
                            ( '" . $user_id . "', '162', 'Medium' ),
                            ( '" . $user_id . "', '163', 'Medium' ),
                            ( '" . $user_id . "', '164', 'Full' ),
                            ( '" . $user_id . "', '165', '1' ),
                            ( '" . $user_id . "', '259', '1' ), 
                            
                            ( '" . $user_id . "', '260', 'Light' ),
                            ( '" . $user_id . "', '261', 'Large' ),
                            ( '" . $user_id . "', '262', 'Medium' ),
                            ( '" . $user_id . "', '263', 'Medium' ),
                            ( '" . $user_id . "', '264', 'Full' ),
                            ( '" . $user_id . "', '359', '1' ),
                            ( '" . $user_id . "', '360', 'Light' ),
                            ( '" . $user_id . "', '361', 'Large' ),
                            ( '" . $user_id . "', '362', 'Medium' ),
                            ( '" . $user_id . "', '363', 'Medium' ),
                            ( '" . $user_id . "', '364', 'Full' ),
                            ( '" . $user_id . "', '459', '1' ),
                            ( '" . $user_id . "', '460', 'Light' ),
                            ( '" . $user_id . "', '461', 'Large' ),
                            ( '" . $user_id . "', '462', 'Medium' ),
                            ( '" . $user_id . "', '463', 'Medium' ),
                            ( '" . $user_id . "', '464', 'Full' )



                            ");
    return $query;
}
function AddUserDefault_ExtaSettings5($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                          

                          ( '" . $user_id . "', '559', '1' ),
                            ( '" . $user_id . "', '560', 'Light' ),
                            ( '" . $user_id . "', '561', 'Large' ),
                            ( '" . $user_id . "', '562', 'Medium' ),
                            ( '" . $user_id . "', '563', 'Medium' ),
                            ( '" . $user_id . "', '564', 'Full' ),
                            ( '" . $user_id . "', '565', '1' )
                           



                            ");
    return $query;
}
function AddUserDefault_ExtaSettings3($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                          

                            ( '" . $user_id . "', '125', '4' ),
                            ( '" . $user_id . "', '126', 'Default' ),
                            ( '" . $user_id . "', '226', 'Default' ),
                            ( '" . $user_id . "', '127', '1' ),
                            ( '" . $user_id . "', '128', '1' ),
                            ( '" . $user_id . "', '129', '10' ),
                            ( '" . $user_id . "', '130', '0' ),
                            ( '" . $user_id . "', '230', '0' ),
                            ( '" . $user_id . "', '131', '80' ),
                            ( '" . $user_id . "', '133', '0' ),
                            ( '" . $user_id . "', '233', '0' ),
                            ( '" . $user_id . "', '134', '0' ),
                            ( '" . $user_id . "', '234', '0' ),
                            ( '" . $user_id . "', '136', '0' ),
                            ( '" . $user_id . "', '138', '1' ),
                            ( '" . $user_id . "', '139', '0' )

                           



                            ");
    return $query;
}
function AddUserDefault_ExtaSettings8($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                          


                            ( '" . $user_id . "', '241', '600' ),
                            ( '" . $user_id . "', '341', '600' ),
                            ( '" . $user_id . "', '441', '600' ),
                            ( '" . $user_id . "', '141', '0' ),
                            ( '" . $user_id . "', '442', '1' ),
							( '" . $user_id . "', '143', '0' ),
							( '" . $user_id . "', '148', '0' ),
                            ( '" . $user_id . "', '149', '15, 188, 249' ),
							( '" . $user_id . "', '150', '0' ),
							( '" . $user_id . "', '151', '2' ),
                            ( '" . $user_id . "', '243', '0' ),
                            ( '" . $user_id . "', '343', '0' ),
                            ( '" . $user_id . "', '142', '1' ),
                            ( '" . $user_id . "', '443', '0' )
                        



                            ");
    return $query;
}
function AddUserDefault_ExtaSettings7($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                          

                            ( '" . $user_id . "', '525', '4' ),
                            ( '" . $user_id . "', '526', 'Default' ),
                          
                            ( '" . $user_id . "', '527', '1' ),
                            ( '" . $user_id . "', '528', '1' ),
                            ( '" . $user_id . "', '529', '10' ),
                            ( '" . $user_id . "', '530', '0' ),
                       
                            ( '" . $user_id . "', '531', '80' ),
                            ( '" . $user_id . "', '533', '0' ),
                           ( '" . $user_id . "', '534', '0' ),
                            ( '" . $user_id . "', '541', '0' )
                           
							
                           
                        



                            ");
    return $query;
}
function AddUserDefault_ExtaSettings6($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                          

                            
                           
							( '" . $user_id . "', '543', '0' ),
							( '" . $user_id . "', '548', '0' ),
                            ( '" . $user_id . "', '549', '15, 588, 249' ),
							( '" . $user_id . "', '550', '0' ),
							( '" . $user_id . "', '551', '2' ),
                            
                            ( '" . $user_id . "', '542', '1' ),

                            ( '" . $user_id . "', '536', '0' ),
                            ( '" . $user_id . "', '538', '1' ),
                            ( '" . $user_id . "', '539', '0' )
                           
                        



                            ");
    return $query;
}
function AddUserDefault_ExtaSettings($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                            ( '" . $user_id . "', '140', '0' ),
                            ( '" . $user_id . "', '159', '1' ),
                            ( '" . $user_id . "', '160', 'Light' ),
                            ( '" . $user_id . "', '161', 'Medium' ),
                            ( '" . $user_id . "', '162', 'Medium' ),
                            ( '" . $user_id . "', '163', 'Medium' ),
                            ( '" . $user_id . "', '164', 'Full' ),
                            ( '" . $user_id . "', '165', '1' ),
                            ( '" . $user_id . "', '259', '1' ), 
                            
                            ( '" . $user_id . "', '260', 'Light' ),
                            ( '" . $user_id . "', '261', 'Large' ),
                            ( '" . $user_id . "', '262', 'Medium' ),
                            ( '" . $user_id . "', '263', 'Medium' ),
                            ( '" . $user_id . "', '264', 'Full' ),
                            ( '" . $user_id . "', '359', '1' ),
                            ( '" . $user_id . "', '360', 'Light' ),
                            ( '" . $user_id . "', '361', 'Large' ),
                            ( '" . $user_id . "', '362', 'Medium' ),
                            ( '" . $user_id . "', '363', 'Medium' ),
                            ( '" . $user_id . "', '364', 'Full' ),
                            ( '" . $user_id . "', '459', '1' ),
                            ( '" . $user_id . "', '460', 'Light' ),
                            ( '" . $user_id . "', '461', 'Large' ),
                            ( '" . $user_id . "', '462', 'Medium' ),
                            ( '" . $user_id . "', '463', 'Medium' ),
                            ( '" . $user_id . "', '464', 'Full' ),
                            ( '" . $user_id . "', '15', '100' ),
                            ( '" . $user_id . "', '115', '100' ),
                            ( '" . $user_id . "', '215', '100' ),
                            ( '" . $user_id . "', '315', '100' ),
                            ( '" . $user_id . "', '415', '100' ),

                            ( '" . $user_id . "', '16', '0, 0, 8' ),
                            ( '" . $user_id . "', '116', '0, 0, 8'  ),
                            ( '" . $user_id . "', '216', '0, 0, 8'  ),
                            ( '" . $user_id . "', '316', '0, 0, 8'  ),
                            ( '" . $user_id . "', '416', '0, 0, 8'  ),

                            ( '" . $user_id . "', '17', 'Roboto' ),
                            ( '" . $user_id . "', '117', 'Roboto' ),
                            ( '" . $user_id . "', '217', 'Roboto' ),
                            ( '" . $user_id . "', '317', 'Roboto' ),
                            ( '" . $user_id . "', '417', 'Roboto' ),

                            ( '" . $user_id . "', '18', '255, 255, 254' ),
                            ( '" . $user_id . "', '118', '255, 255, 254' ),
                            ( '" . $user_id . "', '218', '255, 255, 254' ),
                            ( '" . $user_id . "', '318', '255, 255, 254' ),
                            ( '" . $user_id . "', '418', '255, 255, 254' ),

                            ( '" . $user_id . "', '19', '255, 0, 120' ),
                            ( '" . $user_id . "', '119', '255, 0, 102' ),
                            ( '" . $user_id . "', '219', '255, 0, 120' ),
                            ( '" . $user_id . "', '319', '255, 0, 120' ),
                            ( '" . $user_id . "', '419', '255, 0, 120' ),

                            ( '" . $user_id . "', '20', 'Default' ),
                            ( '" . $user_id . "', '120', 'Default' ),
                            ( '" . $user_id . "', '220', 'Default' ),
                            ( '" . $user_id . "', '320', 'Default'),
                            ( '" . $user_id . "', '420', 'Default' ),

                            ( '" . $user_id . "', '21', '10' ),
                            ( '" . $user_id . "', '121', '10' ),
                            ( '" . $user_id . "', '221', '10' ),
                            ( '" . $user_id . "', '321', '10' ),
                            ( '" . $user_id . "', '421', '10' ),
    
                            ( '" . $user_id . "', '22', '9' ),
                            ( '" . $user_id . "', '122', '9' ),
                            ( '" . $user_id . "', '222', '9' ),
                            ( '" . $user_id . "', '322', '9' ),
                            ( '" . $user_id . "', '422', '9' ),

                            ( '" . $user_id . "', '125', '4' ),
                            ( '" . $user_id . "', '126', 'Default' ),
                            ( '" . $user_id . "', '226', 'Default' ),
                            ( '" . $user_id . "', '127', '1' ),
                            ( '" . $user_id . "', '128', '1' ),
                            ( '" . $user_id . "', '129', '10' ),
                            ( '" . $user_id . "', '130', '0' ),
                            ( '" . $user_id . "', '230', '0' ),
                            ( '" . $user_id . "', '131', '80' ),
                            ( '" . $user_id . "', '133', '0' ),
                            ( '" . $user_id . "', '233', '0' ),
                            ( '" . $user_id . "', '134', '0' ),
                            ( '" . $user_id . "', '234', '0' ),
                            ( '" . $user_id . "', '136', '0' ),
                            ( '" . $user_id . "', '138', '1' ),
                            ( '" . $user_id . "', '139', '0' ),

                            ( '" . $user_id . "', '241', '600' ),
                            ( '" . $user_id . "', '341', '600' ),
                            ( '" . $user_id . "', '441', '600' ),
                            ( '" . $user_id . "', '141', '0' ),
                            ( '" . $user_id . "', '442', '1' ),
							( '" . $user_id . "', '143', '0' ),
							( '" . $user_id . "', '148', '0' ),
                            ( '" . $user_id . "', '149', '15, 188, 249' ),
							( '" . $user_id . "', '150', '0' ),
							( '" . $user_id . "', '151', '2' ),
                            ( '" . $user_id . "', '243', '0' ),
                            ( '" . $user_id . "', '343', '0' ),
                            ( '" . $user_id . "', '142', '1' ),
                            ( '" . $user_id . "', '443', '0' ), 
                            ( '" . $user_id . "', '139', '0' )
                            ");
    return $query;
}


function NewUserDefault_ExcitingSettings($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                            
							( '" . $user_id . "', '159', '1' ),
                            ( '" . $user_id . "', '160', 'Light' ),
                            ( '" . $user_id . "', '161', 'Large' ),
                            ( '" . $user_id . "', '162', 'Medium' ),
                            ( '" . $user_id . "', '163', 'Medium' ),
                            ( '" . $user_id . "', '164', 'Full' ),
                            ( '" . $user_id . "', '165', '1' ),
                            ( '" . $user_id . "', '259', '1' ),
                            ( '" . $user_id . "', '260', 'Light' ),
                            ( '" . $user_id . "', '261', 'Large' ),
                            ( '" . $user_id . "', '262', 'Medium' ),
                            ( '" . $user_id . "', '263', 'Medium' ),
                            ( '" . $user_id . "', '264', 'Full' ),
                            ( '" . $user_id . "', '359', '1' ),
                            ( '" . $user_id . "', '360', 'Light' ),
                            ( '" . $user_id . "', '361', 'Large' ),
                            ( '" . $user_id . "', '362', 'Medium' ),
                            ( '" . $user_id . "', '363', 'Medium' ),
                            ( '" . $user_id . "', '364', 'Full' ),
                            ( '" . $user_id . "', '459', '1' ),
                            ( '" . $user_id . "', '460', 'Light' ),
                            ( '" . $user_id . "', '461', 'Large' ),
                            ( '" . $user_id . "', '462', 'Medium' ),
                            ( '" . $user_id . "', '463', 'Medium' ),
                            ( '" . $user_id . "', '464', 'Full' )
                            ");
    return $query;
}
/**
 * Adding User Default extra text
 * @param type $user_id
 * @return type
 */
function AddUserDefault_ExtaText($user_id) {

    $query = query("INSERT INTO text (id, app, number, title, data) VALUES 
                            ( '" . $user_id . "', 'QC-OL', '0', 'Demo Deck', 'Typio`An accessible typing tutor|Quick Cards`A flashcard and test taking app|Accessibyte Arcade`A ton of accessible games|ProPack`Super accessible school tools|Teacher Dashboard`Supercharge your students' ),
                            ( '" . $user_id . "', 'QC-OL', '1' , 'Demo Test' ,'Samurai`*Uses a single timed keypress`Teaches basic menu navigation`Requires input memorization`Teaches resource management and planning|Echo`Uses a single timed keypress`Teaches basic menu navigation`*Requires input memorization`Teaches resource management and planning|Canteen`Uses a single timed keypress`Teaches basic menu navigation`Requires input memorization`*Teaches resource management and planning|Wizards Tower`Uses a single timed keypress`*Teaches basic menu navigation`Requires input memorization`Teaches resource management and planning' ) ");
    return $query;
}

function Delete_UserAdded_ExtraSettings($user_id) {
    $query = query("DELETE FROM `settings` WHERE id = '" . $user_id . "'");
    return $query;
}

function send_email_test($to, $subject, $message, $redirect = '') {

    require 'PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();

//    try {
    //Recipients
    $mail->setFrom('support@accessibyte.com', 'Accessibyte Support');
    $mail->addAddress($to);     // Add a recipient
    $mail->addReplyTo($to);

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = $message;

    $mail->send();

    //echo 'Mail sent: ';
//    } catch (Exception $e) {
//        //echo 'Mailer Error: ' . $mail->ErrorInfo;
//        $_SESSION['error']['message'] = "Could not send password reset link. Try again";
//        $_SESSION['error']['color'] = 'danger';
//    }
//	if(empty($redirect)){
//    	header('Location:' . ADMIN_URL . 'register.php');
//    	exit;
//	}
}

function users_license_list($user_id = '') {

    $license_details_array = array();
    $user_licenses = query('SELECT user_id,license,expires,license_status,success FROM wordpress_licenses where `user_id` = ' . $user_id);
    $manual_sr = 100;
    while ($row = mysqli_fetch_assoc($user_licenses)) {
        $val = explode("-", trim($row['license']));

        if ($val[0] == "PRO")
            $item = "Pro Pack";
        elseif ($val[0] == "BDL")
            $item = "All Access";
        elseif ($val[0] == "BRL")
            $item = "Braillio Home";
        elseif ($val[0] == "TYO")
            $item = "Typio";
        else if ($val[0] == "TCH")
            $item = "Teacher Dashboard";
 else if ($val[0] == "TCHI")
       $item = "Typio Pro For Institutions";
        else if ($val[0] == "TCHP")
            $item = "Teacher Dashboard - All Access";
        else if ($val[0] == "WCO")
            $item = "";
        else if ($val[0] == "AAO")
            $item = "All Access";
        else if ($val[0] == "TY")
            $item = "Typio Single User";
        else if ($val[0] == "QCO")
            $item = "Quick Cards";
        else if ($val[0] == "AA")
            $item = "Accessibyte Arcade";
        else if ($val[0] == "WW")
            $item = "WordWav";

        $manual_sr = 100;
        if ($item == "Typio") {
            $manual_sr = 1;
        } else if ($item == "Pro Pack") {
            $manual_sr = 2;
        } else if ($item == "Quick Cards") {
            $manual_sr = 3;
        }

        $dateDiff = $row['license_status'];
        if (!empty($row['expires']) && strtotime(date('Y-m-d', strtotime($row['expires']))) >= strtotime(date('Y-m-d'))) {
            $date1_ts = strtotime(date('Y-m-d'));
            $date2_ts = strtotime(date('Y-m-d', strtotime($row['expires'])));
            $diff = $date2_ts - $date1_ts;
            $dateDiff = round($diff / 86400);
//            echo $dateDiff; exit;
//            $license_details_array[] = array(
//                'manual_sr'=>$manual_sr,
//                'item_name'=>$item,
//                'license'=>$row['license'],
//                'days_left'=>$dateDiff,
//                'status'=>$row['license_status'],
//                'expires'=>$row['expires'],
//                'success'=>$row['success'],
//            );
        }

        $license_details_array[] = array(
            'manual_sr' => $manual_sr,
            'item_name' => $item,
            'license' => $row['license'],
            'days_left' => $dateDiff,
            'status' => $row['license_status'],
            'expires' => $row['expires'],
            'success' => $row['success'],
        );
    }


    asort($license_details_array);
    return $license_details_array;
//    echo '<pre>';
//        print_r($license_details_array);
//    exit;
}

function get_license_details($license, $user_id) {
    $val = explode("-", trim($license));
    if ($val[0] == "PRO")
        $item = "67695";
    else if ($val[0] == "BDL")
        $item = "4184";
    elseif ($val[0] == "BRL")
        $item = "88653";
    elseif ($val[0] == "TYO")
        $item = "3584";
    else if ($val[0] == "TY")
        $item = "192";
    else if ($val[0] == "TCH")
        $item = "4111";
 else if ($val[0] == "TCHI")
        $item = "67578";
    else if ($val[0] == "TCHP") /* Add new liecenece for teacher dashboard */
        $item = "30398";
    else if ($val[0] == "QCO")
        $item = "4182";
    else if ($val[0] == "AA")
        $item = "905";
    else if ($val[0] == "WW")
        $item = "207";

    if (empty($item)) {

        $info = array(
            'status' => 'none',
            'license' => '-'
        );
        return $info;
    }

    $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

    //step1
    $ch = curl_init();
    //step2
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //step3
    $result = curl_exec($ch);
    //step4
    curl_close($ch);
    //step5
    $status = json_decode($result);

    if (!empty($status) && $status->success == 1) {
        $data = array(
            'user_id' => $user_id,
            'success' => $status->success,
            'license' => $license,
            'license_status' => $status->license,
            'item_id' => $status->item_id,
            'item_name' => $status->item_name,
            'checksum' => $status->checksum,
            'expires' => $status->expires,
            'payment_id' => $status->payment_id,
            'customer_name' => $status->customer_name,
            'customer_email' => $status->customer_email,
            'license_limit' => $status->license_limit,
            'site_count' => $status->site_count,
            'activations_left' => $status->activations_left,
            'price_id' => $status->price_id,
            'wp_date_created' => $status->date_created
        );
    } else {
        $data = array(
            'user_id' => $user_id,
            'success' => 0,
            'license' => $license,
            'license_status' => $status->license,
            'item_name' => $status->item_name,
            'checksum' => $status->checksum,
        );
    }
//    echo '<pre>';
//    print_r($data);
//    exit;

    $keys = array_keys($data);
    $values_arr = array_values($data);
//    query('DELETE FROM `wordpress_licenses` WHERE user_id="' . $user_id . '"');
    update_query('wordpress_licenses', 'user_id = "' . $user_id . '" ', array('user_id' => 0));
    $check_user = query('SELECT user_id,license FROM wordpress_licenses WHERE user_id = "' . $user_id . '" AND license = "' . $license . '" ');
    if ($check_user->num_rows > 0) {
        $data['updated_datetime'] = date('Y-m-d H:i:s');
        update_query('wordpress_licenses', 'user_id = "' . $user_id . '" AND license = "' . $license . '"', $data);
    } else {
        $check_user = query('SELECT id,user_id,license FROM wordpress_licenses WHERE user_id = 0 AND license = "' . $license . '" ');
        if ($check_user->num_rows > 0) {
            while ($result_row = mysqli_fetch_assoc($check_user)) {
                $where_id = $result_row['id'];
            }
            $data['updated_datetime'] = date('Y-m-d H:i:s');
            update_query('wordpress_licenses', 'id = "' . $where_id . '" AND license = "' . $license . '"', $data);
        } else {
            query('INSERT INTO `wordpress_licenses` (' . implode(",", $keys) . ') VALUES ("' . implode('","', $values_arr) . '")');
        }
    }

    unset($data['user_id']);
    $data['updated_datetime'] = date('Y-m-d H:i:s');
    update_query('wordpress_licenses', 'license = "' . $license . '"', $data);
}

function deactiveLicAftGetLiceDet($license, $user_id) {

    $val = explode("-", trim($license));

    if ($val[0] == "PRO")
        $item = "67695";
    else if ($val[0] == "BDL")
        $item = "4184";
    elseif ($val[0] == "BRL")
        $item = "88653";
    elseif ($val[0] == "TYO")
        $item = "3584";
    else if ($val[0] == "TY")
        $item = "192";
    else if ($val[0] == "TCH")
        $item = "4111";
 else if ($val[0] == "TCHI")
        $item = "67578";
    else if ($val[0] == "TCHP") /* Add new liecenece for teacher dashboard */
        $item = "30398";
    else if ($val[0] == "QCO")
        $item = "4182";
    else if ($val[0] == "AA")
        $item = "905";
    else if ($val[0] == "WW")
        $item = "207";

    $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

    //step1
    $ch = curl_init();
    //step2
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //step3
    $result = curl_exec($ch);
    //step4
    curl_close($ch);
    //step5
    $status = json_decode($result);

    if (!empty($status) && $status->success == 1) {
        $data = array(
            'user_id' => '',
            'success' => $status->success,
            'license' => $license,
            'license_status' => $status->license,
            'item_id' => $status->item_id,
            'item_name' => $status->item_name,
            'checksum' => $status->checksum,
            'expires' => $status->expires,
            'payment_id' => $status->payment_id,
            'customer_name' => $status->customer_name,
            'customer_email' => $status->customer_email,
            'license_limit' => $status->license_limit,
            'site_count' => $status->site_count,
            'activations_left' => $status->activations_left,
            'price_id' => $status->price_id,
            'wp_date_created' => $status->date_created
        );

        $wp_result = true;
    } else {
        $data = array(
            'user_id' => '',
            'success' => 0,
            'license' => $license,
            'license_status' => $status->license,
            'item_name' => $status->item_name,
            'checksum' => $status->checksum,
        );
        $wp_result = false;
    }

    $keys = array_keys($data);
    $values_arr = array_values($data);

    $check_user = query('SELECT user_id,license FROM wordpress_licenses WHERE user_id = "' . $user_id . '" AND license = "' . $license . '" ');
    if ($check_user->num_rows > 0) {
        $data['updated_datetime'] = date('Y-m-d H:i:s');
        update_query('wordpress_licenses', 'user_id = "' . $user_id . '" AND license = "' . $license . '"', $data);
    } else {
        $check_user = query('SELECT id,user_id,license FROM wordpress_licenses WHERE user_id = 0 AND license = "' . $license . '" ');
        if ($check_user->num_rows > 0) {
            while ($result_row = mysqli_fetch_assoc($check_user)) {
                $where_id = $result_row['id'];
            }
            $data['updated_datetime'] = date('Y-m-d H:i:s');
            update_query('wordpress_licenses', 'id = "' . $where_id . '" AND license = "' . $license . '"', $data);
        } else {
            query('INSERT INTO `wordpress_licenses` (' . implode(",", $keys) . ') VALUES ("' . implode('","', $values_arr) . '")');
        }
    }

    unset($data['user_id']);
    $data['updated_datetime'] = date('Y-m-d H:i:s');
    update_query('wordpress_licenses', 'license = "' . $license . '"', $data);

    return $wp_result;
}

function deactive_licenses($licenses_array, $user_id) {

    $licenses_list = array();
    if (!empty($licenses_array)) {
        foreach ($licenses_array as $license) {

            $val = explode("-", trim($license));

            if ($val[0] == "PRO")
                $item = "67695";
            else if ($val[0] == "BDL")
                $item = "4184";
            elseif ($val[0] == "BRL")
                $item = "88653";
            elseif ($val[0] == "TYO")
                $item = "3584";
            else if ($val[0] == "TY")
                $item = "192";
            else if ($val[0] == "TCH")
                $item = "4111";
 else if ($val[0] == "TCHI")
        $item = "67578";
            else if ($val[0] == "TCHP") /* Add new liecenece for teacher dashboard */
                $item = "30398";
            else if ($val[0] == "QCO")
                $item = "4182";
            else if ($val[0] == "AA")
                $item = "905";
            else if ($val[0] == "WW")
                $item = "207";

            $url = WP_URL . "?edd_action=deactivate_license&item_id=" . $item . "&license=" . $license;

            //step1
            $ch = curl_init();
            //step2
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            //step3
            $result = curl_exec($ch);
            //step4
            curl_close($ch);
            //step5
            $status = json_decode($result);

            if (!empty($status) && $status->success == 1) {
                deactiveLicAftGetLiceDet($license, $user_id);
            }
        }

        return $status;
    }
}

function update_license($username, $password, $license) {

    $license_item = '';
    $item = '';
    $val = explode("-", trim($license));
    if ($val[0] == "PRO") {
        $item = "67695";
        $license_item = 6;
    } elseif ($val[0] == "BDL") {
        $item = "4184";
        $license_item = 5;
    }
    elseif ($val[0] == "BRL") {
        $item = "88653";
        $license_item = 5;
    }
     elseif ($val[0] == "TYO") {
        $item = "3584";
        $license_item = 5;
    } else if ($val[0] == "TCH") {
        $item = "4111";
        $license_item = 5;
} else if ($val[0] == "TCHI"){
        $item = "67578";
        $license_item = 5;
    } else if ($val[0] == "TCHP") { /* Add new liecenece for teacher dashboard */
        $item = "30398";
        $license_item = 5;
    } else if ($val[0] == "WCO") {
        $item = "0";
    } else if ($val[0] == "AAO") {
        $item = "0";
        $license_item = 7;
    } else if ($val[0] == "TY") {
        $item = "192";
    } else if ($val[0] == "QCO") {
        $item = "4182";
        $license_item = 8;
    } else if ($val[0] == "AA") {
        $item = "905";
    } else if ($val[0] == "WW") {
        $item = "207";
    }
    $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

    //step1
    $ch = curl_init();
    //step2
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //step3
    $result_curl = curl_exec($ch);
    //step4
    curl_close($ch);

    $check_lic = json_decode($result_curl);
    
    if ($check_lic->success == 1) {

        $today = date_create(date('Y-m-d'));
        $expires = date_create(date('Y-m-d', strtotime($check_lic->expires)));
        $diff = date_diff($today, $expires);

        if ($diff->days > 14) {
            $password = md5($password);
            $get_data = query("select * from user where (user.username = '" . $username . "' or user.username = '" . base64_encode($username) . "') AND user.password='" . $password . "'");
            $result = fetch($get_data);
            
            if (!empty($result)) {
                $flag = 1;
				$is_valid = 0;
                if ($result['role'] == 'teacher' && ($val[0] == "TCH" || $val[0] == "TCHP" || $val[0] == "TCHI")) {
                    $is_valid = 1;
                }
                if ($result['role'] != 'teacher' && $val[0] != "TCH" && $val[0] != "TCHP" && $val[0] != "TCHI") {
                    $is_valid = 1;
                }
				if (!empty($is_valid)) {
					$args = array(
						'teacher_code' => $result['teacher'],
						'role' => 'student',
					);
					$teacher_students = get_users($args);

					// Without login check  Link = /accessibyte/online/update-license.php
					if (isset($_POST['update_license_withoutLogin']) && $_POST['update_license_withoutLogin'] == 'withoutlogin') {
						if (!empty($result) && $result['role'] == 'teacher') {
							$studentsCount = count($teacher_students);
							$new_update_license_limit = $check_lic->license_limit - 1;
							
							$left = $check_lic->activations_left;
						   
							$total = $studentsCount + 1;
							$seat_avail = $left - 1;
							$diffoflimit =  $total - $left;
							if($left > 0){
								if ($left < $total) {
									$_SESSION['error']['message'] = "Not enough seats on this license. You have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
									$flag = 0;
								}
							} else {
								$_SESSION['error']['message'] = "You have 0 activation left on this license.";
								$flag = 0;
							}
						}
					}
					
					if ($flag) {
						$url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
						// echo $url;exit;
						//step1
						$ch = curl_init();
						//step2
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_HEADER, false);
						//step3
						$result_active = curl_exec($ch);
						//step4
						curl_close($ch);

						$status = json_decode($result_active);

						if ($status->success == "true") {

							// Update License in User table
							update_query('user', 'id = "' . $result['id'] . '" ', array('license' => $license));
							if ($result['role'] == 'teacher') {
								update_query('user', 'teacher = "' . $result['teacher'] . '" ', array('license' => $license));  // update all student hase same license
							}
							
							if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP" || $val[0] == "TCHI")) {

								if (!empty($teacher_students)) {
									foreach ($teacher_students as $stud) {
										// Update License in Settings table
										update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
										$url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
										
										//step1
										$ch = curl_init();
										//step2
										curl_setopt($ch, CURLOPT_URL, $url);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
										curl_setopt($ch, CURLOPT_HEADER, false);
										//step3
										$result_active = curl_exec($ch);
										//step4
										curl_close($ch);

										$status = json_decode($result_active);
										/* update user license start here */
										
										$check_exists_license_query = query("select * from users_licenses where user_id = '" . $stud['id'] . "' AND license='" . $license . "'");
										$users_license_result = fetch($check_exists_license_query);

										if (empty($users_license_result)) {
											// Data save in Users Licenses table
											query("INSERT INTO users_licenses SET `user_id` = '" . $stud['id'] . "', license='" . $license . "' ");
										} else {
											update_query('users_licenses', 'user_id = "' . $stud['id'] . '"', array('license' => $license));  // update all same license of student
										}
									
										
										/* update user license end here  */
									}
									update_no_license_data_new($license,count($teacher_students));
								}
							}

							if (!empty($license_item)) {
								// Update License in Settings table
								update_query('settings', 'id = "' . $result['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
							}

							$check_exists_license_query = query("select * from users_licenses where user_id = '" . $result['id'] . "' AND license='" . $license . "'");
							$users_license_result = fetch($check_exists_license_query);

							if (empty($users_license_result)) {
								// Data save in Users Licenses table
								query("INSERT INTO users_licenses SET `user_id` = '" . $result['id'] . "', license='" . $license . "' ");
							}

							get_license_details($license, $result['id']);        // license details update in 'wordpress_licenses' table
							
							if ($result['role'] == 'teacher') {
								update_query('wordpress_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
							}
							$_SESSION['error']['message'] = "License is updated successfully.";

							header("Location: " . ADMIN_URL . 'login');
							exit;
						} else {
							$_SESSION['error']['message'] = "License key is invalid.";
						}
					}
				} else {
                    $_SESSION['error']['message'] = $val[0] . " is not allowed for " . ucfirst($result['role']);
                }
            }
        } else {
            $_SESSION['error']['message'] = "You can't renew using a trial license.";
        }
    } else {
        $_SESSION['error']['message'] = "License key not found. Please check your License key.";
    }
}

function update_license_new($username, $license) {

    $license_item = '';
    $item = '';
    $val = explode("-", trim($license));
    if ($val[0] == "PRO") {
        $item = "67695";
        $license_item = 6;
    } elseif ($val[0] == "BDL") {
        $item = "4184";
        $license_item = 5;
    } elseif ($val[0] == "BRL") {
        $item = "88653";
        $license_item = 5;
    } elseif ($val[0] == "TYO") {
        $item = "3584";
        $license_item = 5;
    } else if ($val[0] == "TCH") {
        $item = "4111";
        $license_item = 5;
 } else if ($val[0] == "TCHI") {
        $item = "67578";
        $license_item = 5;
    } else if ($val[0] == "TCHP") {
        $item = "30398";
        $license_item = 5;
    } else if ($val[0] == "WCO") {
        $item = "0";
    } else if ($val[0] == "AAO") {
        $item = "0";
        $license_item = 7;
    } else if ($val[0] == "TY") {
        $item = "192";
    } else if ($val[0] == "QCO") {
        $item = "4182";
        $license_item = 8;
    } else if ($val[0] == "AA") {
        $item = "905";
    } else if ($val[0] == "WW") {
        $item = "207";
    }
    $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

    //step1
    $ch = curl_init();
    //step2
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //step3
    $result_curl = curl_exec($ch);
    //step4
    curl_close($ch);

    $check_lic = json_decode($result_curl);

    if (isset($check_lic->success) && $check_lic->success == 1) {

        $today = date_create(date('Y-m-d'));
        $expires = date_create(date('Y-m-d', strtotime($check_lic->expires)));
        $diff = date_diff($today, $expires);

        if ($diff->days > 14) {
            $get_data = query("select * from user where user.username = '" . $username . "' or user.username = '" . base64_encode($username) . "'");
            $result = fetch($get_data);

            if (!empty($result)) {
				
				
                $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
                // echo $url;exit;
                //step1
                $ch = curl_init();
                //step2
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, false);
                //step3
                $result_active = curl_exec($ch);
                //step4
                curl_close($ch);

                $status = json_decode($result_active);
				//echo "<pre>";print_r($status);die;
                if ($status->success == "true") {

                    // Update License in User table
                    update_query('user', 'id = "' . $result['id'] . '" ', array('license' => $license));
//                    update_query('user', 'teacher = "' . $result['teacher'] . '" AND license = "' . $result['license'] . '"', array('license' => $license));  // update all student hase same license
                    update_query('user', 'teacher = "' . $result['teacher'] . '" ', array('license' => $license));  // update all student hase same license

                    if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP" || $val[0] == "TCHI")) {

                        $args = array(
                            'teacher_code' => $result['teacher'],
                            'role' => 'student',
                        );
                        $teacher_students = get_users($args);

                        if (!empty($teacher_students)) {
                            foreach ($teacher_students as $stud) {
                                // Update License in Settings table
                                update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
								$url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
								// echo $url;exit;
								//step1
								$ch = curl_init();
								//step2
								curl_setopt($ch, CURLOPT_URL, $url);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_HEADER, false);
								//step3
								$result_active = curl_exec($ch);
								//step4
								curl_close($ch);

								$status = json_decode($result_active);
								/* update user license start here */
									
								$check_exists_license_query = query("select * from users_licenses where user_id = '" . $stud['id'] . "' AND license='" . $license . "'");
								$users_license_result = fetch($check_exists_license_query);

								if (empty($users_license_result)) {
									// Data save in Users Licenses table
									query("INSERT INTO users_licenses SET `user_id` = '" . $stud['id'] . "', license='" . $license . "' ");
								} else {
									update_query('users_licenses', 'user_id = "' . $stud['id'] . '"', array('license' => $license));  // update all same license of student
								}
								
								
								/* update user license end here  */
                            }
                            update_no_license_data_new($license,count($teacher_students));
                        }
                    }

                    if (!empty($license_item)) {
                        // Update License in Settings table
                        update_query('settings', 'id = "' . $result['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                    }

                    $check_exists_license_query = query("select * from users_licenses where user_id = '" . $result['id'] . "' AND license='" . $license . "'");
                    $users_license_result = fetch($check_exists_license_query);

                    if (empty($users_license_result)) {
                        // Data save in Users Licenses table
                        query("INSERT INTO users_licenses SET `user_id` = '" . $result['id'] . "', license='" . $license . "' ");
                    }

                    get_license_details($license, $result['id']);        // license details update in 'wordpress_licenses' table
                    update_query('users_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
                    update_query('wordpress_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student

                    $_SESSION['error']['message'] = "License updated successfully.";

                    // header("Location: " . ADMIN_URL . 'my-account.php');
                } else {
                    $_SESSION['error']['message'] = "License key is invalid.";
                }
            }
        } else {
            $_SESSION['error']['message'] = "You can't renew trial license keys.";
        }
    } else {
        $_SESSION['error']['message'] = "License key not found. Please check your License key.";
    }
}

function get_license_details_all($license) {
    $val = explode("-", trim($license));
    
    if ($val[0] == "PRO")
        $item = "67695";
    else if ($val[0] == "BDL")
        $item = "4184";
     else if ($val[0] == "BRL")
        $item = "88653";
    elseif ($val[0] == "TYO")
        $item = "3584";
    else if ($val[0] == "TY")
        $item = "192";
    else if ($val[0] == "TCH")
        $item = "4111";
  else if ($val[0] == "TCHI")
        $item = "67578";
    else if ($val[0] == "TCHP") /* Add new liecenece for teacher dashboard */
        $item = "30398";
    else if ($val[0] == "QCO")
        $item = "4182";
    else if ($val[0] == "AA")
        $item = "905";
    else if ($val[0] == "WW")
        $item = "207";

    if (empty($item)) {

        $info = array(
            'status' => 'none',
            'license' => '-'
        );
        return $info;
    }

    $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

    //step1
    $ch = curl_init();
    //step2
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //step3
    $result = curl_exec($ch);
    //echo 'status result is='; print_r($result);
    //step4
    curl_close($ch);
    //step5
    $status = json_decode($result);
    
    if (!empty($status) && $status->success == 1) {
        $data = array(
            'success' => $status->success,
            'license' => $license,
            'license_status' => $status->license,
            'item_id' => $status->item_id,
            'item_name' => $status->item_name,
            'checksum' => $status->checksum,
            'expires' => $status->expires,
            'payment_id' => $status->payment_id,
            'customer_name' => $status->customer_name,
            'customer_email' => $status->customer_email,
            'license_limit' => $status->license_limit,
            'site_count' => $status->site_count,
            'activations_left' => $status->activations_left,
            'price_id' => $status->price_id,
            'wp_date_created' => $status->date_created
        );
    } else {
        $data = array(
            'user_id' => $user_id,
            'success' => 0,
            'license' => $license,
            'license_status' => $status->license,
            'item_name' => $status->item_name,
            'checksum' => $status->checksum,
        );
    }
    return $data;
}

function update_timezone($timezone, $id) {

    $query = query("SELECT * FROM settings where id='$id' and item='555'");
    $user = fetch($query);

    if (!empty($user) && !empty($user['id'])) {

        //Good to update token after password reset for security purpose
        if (query("UPDATE settings set `variable`='" . $timezone . "'  WHERE item ='555' and id=$id")) {
            $_SESSION['error']['message'] = "Timezone updated successfully.";
            $_SESSION['error']['color'] = 'success';
        } else {
            $_SESSION['error']['message'] = "Something went wrong.";
            $_SESSION['error']['color'] = 'danger';
        }
    } else {

        query("INSERT INTO settings SET `variable` = '" . $timezone . "', id='" . $id . "',`item` = '555' ");

        $_SESSION['error']['message'] = "Timezone inserted successfully.";
        $_SESSION['error']['color'] = 'success';
    }
    header("Location: " . ADMIN_URL . 'my-account.php');
    exit;
}

if (isset($_GET['idnew'])) {

    $all_users_get_query = query('SELECT id,username,license FROM user');
    if ($all_users_get_query->num_rows > 0) {
        while ($result_row = mysqli_fetch_assoc($all_users_get_query)) {
            echo '<pre>';
            print_r($result_row);
            echo "INSERT INTO users_licenses SET `user_id` = '{$result_row['id']}', `license` = '{$result_row['license']}' ";
            echo "<br>";
            echo "<br>";
            query("INSERT INTO users_licenses SET `user_id` = '{$result_row['id']}', `license` = '{$result_row['license']}' ");
        }
    }
    exit;
}

function toUtcOffset($timezone) {
    $userTimeZone = new DateTimeZone($timezone);
    $offset = $userTimeZone->getOffset(new DateTime("now", new DateTimeZone('UTC'))); // Offset in seconds
    $seconds = abs($offset);
    $sign = $offset > 0 ? '+' : '-';
    $hours = floor($seconds / 3600);
    $mins = floor($seconds / 60 % 60);
    $secs = floor($seconds % 60);
    return sprintf("( UTC$sign%02d:%02d )", $hours, $mins, $secs);
}

// get activitylog
function getUserActivityLog($userid = FALSE, $limit = '',$start_date = '',$end_date = '',$log = ''){
    $get_log = $query_var = array();

    $where = '';
    $current_date = date('Y-m-d');

    //Check user id exist
    if (!empty($userid)) {
        $query_var[] = " log.id ='" . $userid . "'";
    }
    
    if(!empty($start_date) && !empty($end_date)){
        $query_var[] = " log.date >= '" . $start_date . "' AND log.date <= '" . $end_date . "'";
    } else {
        $query_var[] = " log.date >= '" . $current_date . "' AND log.date <= '" . $current_date . "'";
    }
    $query_var[] = " user.role ='student'";

    if (!empty($query_var)) {
        $where = 'WHERE ' . implode(' AND ', $query_var);
    }

    //Check Log limit set
    if (!empty($limit)) {
        $limit = ' LIMIT ' . $limit;
    }

    //Get log data to table
    if(!empty($log)){
        $query = query("SELECT app FROM activity as log INNER JOIN user ON log.id = user.id $where ORDER BY date DESC $limit ");
    } else {
        $query = query("SELECT SUM(value) as total FROM activity as log INNER JOIN user ON log.id = user.id $where ORDER BY date DESC $limit ");
    }
        
    //get rows and store on blank data of log
    $logData = '';
    while ($row = mysqli_fetch_array($query)) {
        if(!empty($log)){
            $logData = $row['app'];
        } else {
            $total = $row['total'];
            if(!empty($total) && $total > 60){
                $min = $total % 60;
                $hours = floor($total / 60);           
                if($min){
                    $min = $min . "m";
                }
                $logData = $hours . "hr " . $min;
            } else if(!empty($total) && $total < 60){
                $logData = $total . " min";
            }     
        }
    }
    return $logData;
}

/**
 * Function check license remaining for teacher or student
 * @param type $license_key
 * @param type $type
 * @return boolean|booleanFunctionoolean
 */
function check_license_available_not($license_key, $type) {


    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licenses where license_key = '" . $license_key . "'");
    
    $data = mysqli_fetch_array($query);
    
    if (isset($data) && !empty($data) && isset($data['id'])) {
        $licenseArr = array();
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where edd_license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
        $pricing_data = mysqli_fetch_array($query);
        if (!empty($pricing_data) && isset($pricing_data['meta_value']) && !empty($pricing_data['meta_value'])) {
            $licenseUnserlizeArr = unserialize($pricing_data['meta_value']);
        
            if (!empty($licenseUnserlizeArr)) {
                if ($type == 'teacher' && isset($licenseUnserlizeArr['no_teacher_use']) && $licenseUnserlizeArr['no_teacher_use'] != $licenseUnserlizeArr['no_teacher']) {
                    return $licenseUnserlizeArr;
                } else if ($type == 'student' && isset($licenseUnserlizeArr['no_student_use']) && $licenseUnserlizeArr['no_student_use'] != $licenseUnserlizeArr['no_student']) {
                    return $licenseUnserlizeArr;
                }
            }
            return array();
        }
        return $data;
    } else {
        return true;
    }
}

/**
 * Function update no of license remaining based on teacher or student 
 * @param type $license_key
 * @param type $type
 * @return boolean
 */
function update_no_license_data($license_key, $type) {


    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licenses where license_key = '" . $license_key . "'");
    $data = mysqli_fetch_array($query);

    if (isset($data) && !empty($data) && isset($data['id'])) {
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where edd_license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
        $pricing_data = mysqli_fetch_array($query);
        if (!empty($pricing_data) && isset($pricing_data['meta_value']) && !empty($pricing_data['meta_value'])) {
            $licenseArr = unserialize($pricing_data['meta_value']);
            
            if (!empty($licenseArr)) {

                $no_teacher_use = $licenseArr['no_teacher_use'];
                $no_student_use = $licenseArr['no_student_use'];
                if ($type == 'teacher') {
                    $no_teacher_use = $licenseArr['no_teacher_use'] + 1;
                } else if ($type == 'student') {
                    $no_student_use = $licenseArr['no_student_use'] + 1;
                }
                $licenseUpArr = array(
                    'no_teacher' => $licenseArr['no_teacher'],
                    'no_student' => $licenseArr['no_student'],
                    'no_teacher_use' => $no_teacher_use,
                    'no_student_use' => $no_student_use,
                    
                );
                $updateSerializeArr = serialize($licenseUpArr);
                mysqli_query($wcon, "update pia_edd_licensemeta set meta_value = '" . $updateSerializeArr . "'  where edd_license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
            }
        }
        return array();
    } else {
        return true;
    }
}

/**
 * Function get license data using license key
 * @param type $license_key
 * @return type
 */
function get_license_data($license_key) {

    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licenses where license_key = '" . $license_key . "'");
    $data = mysqli_fetch_array($query);
    $licenseArr = array();
    if (isset($data) && !empty($data) && isset($data['id'])) {
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where edd_license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
        $pricing_data = mysqli_fetch_array($query);
        if (!empty($pricing_data) && isset($pricing_data['meta_value']) && !empty($pricing_data['meta_value'])) {
            $licenseArr = unserialize($pricing_data['meta_value']);
        }
    }
    return $licenseArr;
   
}

/**
 * Function update no of license on delete stude
 * @param type $license_key
 * @param type $type
 * @return boolean
 */
function update_no_student_license($license_key, $type = 'student') {


    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licenses where license_key = '" . $license_key . "'");
    $data = mysqli_fetch_array($query);

    if (isset($data) && !empty($data) && isset($data['id'])) {
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where edd_license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
        $pricing_data = mysqli_fetch_array($query);
        if (!empty($pricing_data) && isset($pricing_data['meta_value']) && !empty($pricing_data['meta_value'])) {
            $licenseArr = unserialize($pricing_data['meta_value']);
            if (!empty($licenseArr)) {

                $no_teacher_use = $licenseArr['no_teacher_use'];
                $no_student_use = $licenseArr['no_student_use'];
                if ($type == 'student') {
                    $no_student_use = $licenseArr['no_student_use'] - 1;
                }
				if ($type == 'teacher') {
                    $no_teacher_use = $licenseArr['no_teacher_use'] - 1;

                }
                $licenseUpArr = array(
                    'no_teacher' => $licenseArr['no_teacher'],
                    'no_student' => $licenseArr['no_student'],
                    'no_teacher_use' => $no_teacher_use,
                    'no_student_use' => $no_student_use,
                );
                $updateSerializeArr = serialize($licenseUpArr);
                mysqli_query($wcon, "update pia_edd_licensemeta set meta_value = '" . $updateSerializeArr . "'  where edd_license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
            }
        }
        return array();
    } else {
        return true;
    }
}


/**
 * Function check login status for allow login from backend setting 
 * @return boolean
 */
function get_allow_login_status() {

    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT * FROM pia_options where option_name = '_wwt_expire_login'");

    $data = mysqli_fetch_array($query);
    if (!empty($data) && isset($data['option_value']) && !empty($data['option_value'])) {
        return $data['option_value'];
    }
    return false;
}

function getapplogforweek_all($userid, $startdate, $enddate) {

    if (empty($userid) || empty($startdate) || empty($enddate)) {
        return;
    }
    $startdate = date('Y-m-d', strtotime($startdate));
    $enddate = date('Y-m-d', strtotime($enddate));
    //$startdate = '2017-01-01';
    global $con;
    $total = "SELECT * FROM log WHERE log.id ='" . $userid . "'  AND log.date >= '" . $startdate . "' AND log.date <= '" . $enddate . "' ORDER BY date DESC";
    $total_row = mysqli_query($con, $total);
    $total_rows = mysqli_num_rows($total_row);

    $data = "SELECT count(*) as total,file FROM log WHERE log.id ='" . $userid . "' AND log.date >= '" . $startdate . "' AND log.date <= '" . $enddate . "' group by file ORDER BY date DESC";

    $data_rows = mysqli_query($con, $data);
    $html = "";
    $avrg = 0;
    //$html .="<tr><td>1<td><td>232</td></tr>";

    while ($row = mysqli_fetch_assoc($data_rows)) {
        $avrg = round($row['total'] / $total_rows, 2) * 100;
        $html .= "<tr><td> " . $row['file'] . " </td><td> " . $avrg . " </td></tr>";
    }

    return $html;
}

function getapplogforweekTable_all($userid, $startdate, $enddate) {

    if (empty($userid) || empty($startdate) || empty($enddate)) {
        return;
    }
    $startdate = date('Y-m-d', strtotime($startdate));
    $enddate = date('Y-m-d', strtotime($enddate));
    //$startdate = '2017-01-01';
    global $con;
   $data = "SELECT * FROM log WHERE app ='Overview-OL' and log.id ='" . $userid . "' AND log.date >= '" . $startdate . "' AND log.date <= '" . $enddate . "' GROUP BY data,date ORDER BY date DESC";
  

    $data_rows = mysqli_query($con, $data);
    $total_row = 0;
    $html = "";
    $class_arr=array('Typio-OL','Propack','Quick-Cards-OL','Arcade-OL');
    //$count =0;
    while ($row = mysqli_fetch_assoc($data_rows)) {
        //$data_count = count(explode("|", $row['data']));  
        $class="";
        if($row['file'] == "Typio"){
            $class="Typio-OL";
        }
        else if($row['file'] == "ProPack"){
            $class="Propack";
        }
        else if($row['file'] == "Quick Cards"){
            $class="Quick-Cards-OL";
        }
        else if($row['file'] == "Arcade"){
            $class="Arcade-OL";
        }
      
        $html .= "<tr class='".$class."'><td> " . $row['file'] . " </td><td> " . date('m/d/Y', strtotime($row['date'])) . " </td>
        <td> " . $row['data'] . " </td>
        </tr>";
        $total_row += 1;
    }
    $result['total_row'] = $total_row;
    $result['html'] = $html;
    return $result;
}
 /* START : Calculate Overview time for student  */
function getUserActivityLog_graph($userid = FALSE, $limit = '', $start_date = '', $end_date = '', $type = '') {
    $get_log = $query_var = array();

    $where = '';
    $current_date = date('Y-m-d');

    //Check user id exist
    if (!empty($userid)) {
        $query_var[] = " log.id ='" . $userid . "'";
    }

    if (!empty($start_date) && !empty($end_date)) {
        $query_var[] = " log.date >= '" . $start_date . "' AND log.date <= '" . $end_date . "'";
    } else {
        $query_var[] = " log.date >= '" . $current_date . "' AND log.date <= '" . $current_date . "'";
    }
    $query_var[] = " user.role ='student'";

    if($type !=""){
         $query_var[] = "app like '".$type."%'";

    }else{
       $query_var[] = "(app like 'AA%' || app like 'QC%' || app like 'TY%' || app like 'PP%' )";
    }

    if (!empty($query_var)) {
        $where = 'WHERE ' . implode(' AND ', $query_var);
    }

    //Check Log limit set
    if (!empty($limit)) {
        $limit = ' LIMIT ' . $limit;
    }

    //Get log data to table
   
    $query = query("SELECT SUM(value) as total FROM activity as log INNER JOIN user ON log.id = user.id $where ORDER BY date DESC $limit ");
   
    //get rows and store on blank data of log
    $logData = '';
    while ($row = mysqli_fetch_array($query)) {
        if (!empty($log)) {
            $logData = $row['app'];
        } else {
            $total = $row['total'];
            if (!empty($total) && $total > 60) {
                $min = $total % 60;
                $hours = floor($total / 60);
                ;
                if ($min) {
                    $min = $min . "m";
                }
                $logData = $hours . "hrs " . $min;
            } else if (!empty($total) && $total < 60) {
                $logData = $total . " min";
            }
        }
    }
    return $logData;
}
 /* END : Calculate Overview time for student  */
 /**
 * Function update no of license remaining based on teacher or student 
 * @param type $license_key
 * @param type $type
 * @return boolean
 */
function update_no_license_data_new($license_key, $no_of_student,$no_of_teacher = 1) {


    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licenses where license_key = '" . $license_key . "'");
    $data = mysqli_fetch_array($query);

    if (isset($data) && !empty($data) && isset($data['id'])) {
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where edd_license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
        $pricing_data = mysqli_fetch_array($query);
        if (!empty($pricing_data) && isset($pricing_data['meta_value']) && !empty($pricing_data['meta_value'])) {
            $licenseArr = unserialize($pricing_data['meta_value']);
            
            if (!empty($licenseArr)) {

                $no_teacher_use = $licenseArr['no_teacher_use'];
                $no_student_use = $licenseArr['no_student_use'];
                
                $no_teacher_use = $licenseArr['no_teacher_use'] + $no_of_teacher;
                $no_student_use = $licenseArr['no_student_use'] +  $no_of_student;
                $licenseUpArr = array(
                    'no_teacher' => $licenseArr['no_teacher'],
                    'no_student' => $licenseArr['no_student'],
                    'no_teacher_use' => $no_teacher_use,
                    'no_student_use' => $no_student_use,
                    
                );
                $updateSerializeArr = serialize($licenseUpArr);
                mysqli_query($wcon, "update pia_edd_licensemeta set meta_value = '" . $updateSerializeArr . "'  where edd_license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
            }
        }
        return array();
    } else {
        return true;
    }
}
/**
  * Function Check admin role exist for teacher role
  */
 function checkTeacherAdminornot($license_key){
    $query = query("SELECT COUNT(*) as total FROM user where license = '".$license_key."' and role = 'teacher' and 'is_admin' = '1'");

    $data = fetch($query);
    return $data;
 }
 /**
  * Function Check admin role exist for teacher
  */
 function checkTeacherisAdmin($license_key,$teacher_id){
    $query = query("SELECT is_admin FROM user where id = '".$teacher_id."' and license = '".$license_key."' and role = 'teacher' ");

    $data = fetch($query);
    return $data;
 }
 /**
  * Function use for only teacher to update role as admin who register as first
  */
 function updateTeacherAsAdmin($license_key,$id){
    $query = query("SELECT COUNT(*) as total FROM user where license = '".$license_key."' and role = 'teacher' and `is_admin` = '1'");
    $data = fetch($query);
    if(empty($data['total'])){
        query("UPDATE user set `is_admin` = '1' where `license`='" . $license_key . "' and id = $id");
        return true;         
    }
    
    return false;
 }

 /**
  * Function return except admin other teacher list
  */
 function getAllTeacherWithoutAdmin($license = '',$current_user_id = ''){
    if(empty($license)){
        $license = $_SESSION['User']['license'];
    }
    $currentStr = " AND 'is_admin' != '1' ";
    if(!empty($current_user_id)){
        $currentStr = " AND 'id' != '".$current_user_id."' ";
    }
    $teacher_query = query(" SELECT * FROM user WHERE license = '".$license."' and role= 'teacher' ".$currentStr." ");
    $users = array();
    while ($row = fetch($teacher_query)) {
        $users[] = $row;
    }   
    return $users;
}

function update_no_student_license_using_teacher($license_key, $type = 'student',$teacher_count = 0) {


    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licenses where license_key = '" . $license_key . "'");
    $data = mysqli_fetch_array($query);

    if (isset($data) && !empty($data) && isset($data['id'])) {
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where edd_license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
        $pricing_data = mysqli_fetch_array($query);
        if (!empty($pricing_data) && isset($pricing_data['meta_value']) && !empty($pricing_data['meta_value'])) {
            $licenseArr = unserialize($pricing_data['meta_value']);
            if (!empty($licenseArr)) {

                $no_teacher_use = $licenseArr['no_teacher_use'];
                $no_student_use = $licenseArr['no_student_use'];
                if ($type == 'student') {
                    $no_student_use = $licenseArr['no_student_use'] - 1;
                }
                if ($type == 'teacher') {
                    $no_teacher_use = $licenseArr['no_teacher_use'] - $teacher_count;                    
                }
                $licenseUpArr = array(
                    'no_teacher' => $licenseArr['no_teacher'],
                    'no_student' => $licenseArr['no_student'],
                    'no_teacher_use' => $no_teacher_use,
                    'no_student_use' => $no_student_use,
                );
                $updateSerializeArr = serialize($licenseUpArr);
                mysqli_query($wcon, "update pia_edd_licensemeta set meta_value = '" . $updateSerializeArr . "'  where edd_license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
            }
        }
        return array();
    } else {
        return true;
    }
}
function update_license_multi_teacher($username, $password, $license){ 

    $license_item = '';
    $item = '';
    $val = explode("-", trim($license));
    if ($val[0] == "PRO") {
        $item = "67695";
        $license_item = 6;
    } elseif ($val[0] == "BDL") {
        $item = "4184";
        $license_item = 5;
    } elseif ($val[0] == "BRL") {
        $item = "88653";
        $license_item = 5;
    } elseif ($val[0] == "TYO") {
        $item = "3584";
        $license_item = 5;
    } else if ($val[0] == "TCH") {
        $item = "4111";
        $license_item = 5;
 } else if ($val[0] == "TCHI") {
        $item = "67578";
        $license_item = 5;
    } else if ($val[0] == "TCHP") { /* Add new liecenece for teacher dashboard */
        $item = "30398";
        $license_item = 5;
    } else if ($val[0] == "WCO") {
        $item = "0";
    } else if ($val[0] == "AAO") {
        $item = "0";
        $license_item = 7;
    } else if ($val[0] == "TY") {
        $item = "192";
    } else if ($val[0] == "QCO") {
        $item = "4182";
        $license_item = 8;
    } else if ($val[0] == "AA") {
        $item = "905";
    } else if ($val[0] == "WW") {
        $item = "207";
    }
    $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

    //step1
    $ch = curl_init();
    //step2
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //step3
    $result_curl = curl_exec($ch);
    //step4
    curl_close($ch);

    $check_lic = json_decode($result_curl);

    if ($check_lic->success == 1) { 

        $today = date_create(date('Y-m-d'));
        $expires = date_create(date('Y-m-d', strtotime($check_lic->expires)));
        $diff = date_diff($today, $expires);

        if ($diff->days > 14) {
            $password = md5($password);
            $get_data = query("select * from user where (user.username = '" . $username . "' or user.username = '" . base64_encode($username) . "') AND user.password='" . $password . "'");
            $result = fetch($get_data);
            $oldLic = get_license_data($result['license']);

            if (!empty($result)) {
                $flag = 1;
                $is_valid = 0;
                $is_multi_teacher = 0;
                $allTeacher = array();
                if ($result['role'] == 'teacher' && ($val[0] == "TCH" || $val[0] == "TCHP" || $val[0] == "TCHI")) {
                    $is_valid = 1;
                    if(!empty($result['is_admin']) || $result['is_admin'] == '1'){
                        $is_multi_teacher = 1;
                        $allTeacher = getAllTeacher($result['license']);   
                    } else{
                        $single_license = get_license_data($result['license']); 
                        if(isset($single_license) && !empty($single_license) && $single_license['no_teacher'] > 1 && empty($result['is_lock'])){
                            $is_multi_teacher = 1;
                            $allTeacher = getAllTeacher($result['license']);                            
                        }
                    }
                }
                if ($result['role'] != 'teacher' && $val[0] != "TCH" && $val[0] != "TCHP" && $val[0]  != "TCHI") {
                    $is_valid = 1;
                }
                
                if (!empty($is_valid)) {
                    // Without login check  Link = /accessibyte/online/update-license.php
                    if (isset($_POST['update_license_withoutLogin']) && $_POST['update_license_withoutLogin'] == 'withoutlogin') {
                        if (!empty($result) && $result['role'] == 'teacher') {
                            if(!empty($is_multi_teacher)) {
                                $studentsCount = 0;
                                foreach($allTeacher as $allTeacherval) {
                                    $args = array(
                                        'teacher_code' => $allTeacherval['teacher'],
                                        'role' => 'student',
                                    );
                                    $teacher_students = get_users($args);
                                    if(!empty($teacher_students))
                                    $studentsCount += count($teacher_students);
                                }
                                $newLicenseArr = get_license_data($license);
                                $no_teacher_new_lic = $no_use_new = $teacher_left_new = $no_student_new_lic =  $no_std_use_new = $student_left_new = "0";
                                if(!empty($newLicenseArr)) {
                                    $no_teacher_new_lic = $newLicenseArr['no_teacher'];
                                    $no_use_new = $newLicenseArr['no_teacher_use'] ? $newLicenseArr['no_teacher_use'] : '0';
                                    $teacher_left_new  = $no_teacher_new_lic - $no_use_new;
                                    $no_student_new_lic = $newLicenseArr['no_student'];
                                    $no_std_use_new = $newLicenseArr['no_student_use'] ? $newLicenseArr['no_student_use'] : '0';
                                    $student_left_new  = $no_student_new_lic - $no_std_use_new;
                                }                               
                                $oldLicenseArr = get_license_data($result['license']);
                                $no_use_old = $no_teacher = $teacher_left_old = "0";
                                if(!empty($oldLicenseArr)) {
                                    $no_use_old = $oldLicenseArr['no_teacher_use'] ? $oldLicenseArr['no_teacher_use'] : '0';
                                    $no_teacher = $oldLicenseArr['no_teacher'];
                                    $teacher_left_old  = $no_teacher - $no_use_old;
                                }
                                //no_teacher_new_lic - number of teacher allowed to add
                                //no_use_old - number of teachers that are already added
                                //teacher_left_new - number of teachers left

                                if($teacher_left_new > 0) { 
                                    if($no_teacher_new_lic < $no_use_old) { 
                                        $diffTeacher = $no_use_old - $teacher_left_new;
                                        $_SESSION['error']['message'] = "Not enough teacher seats on this license. You have " . $teacher_left_new . " seats available. Please delete " . $diffTeacher . " teacher before applying the license or purchase additional license seats.";
                                        $flag = 0;
                                    } else { 
                                        $left = $student_left_new;
                                        $total = $studentsCount;
                                        $seat_avail = $left;
                                        $diffoflimit = $total - $left;
                                        if ($left > 0) {
                                            if ($left < $total) {
                                                $_SESSION['error']['message'] = "Not enough seats on this license. You have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
                                                $flag = 0;
                                            }
                                        } else {
                                            $_SESSION['error']['message'] = "You have 0 activation left on this license.";
                                            $flag = 0;
                                        }
                                    }
                                } else {                            
                                    $_SESSION['error']['message'] = "You have 0 activation left on this license.";
                                    $flag = 0; 
                                }
                            } else { 
                                $args = array(
                                    'teacher_code' => $result['teacher'],
                                    'role' => 'student',
                                );
                                $teacher_students = get_users($args);            
                                // Without login check  Link = /accessibyte/online/update-license.php                                                                
                                $studentsCount = count($teacher_students);
                                $new_update_license_limit = $check_lic->license_limit - 1;
                                $left = $check_lic->activations_left;
                                $total = $studentsCount + 1;
                                $seat_avail = $left - 1;
                                $diffoflimit = $total - $left;
                                if ($left > 0) {
                                    if ($left < $total) {
                                        $_SESSION['error']['message'] = "Not enough seats on this license. You have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
                                        $flag = 0;
                                    }
                                } else {
                                    $_SESSION['error']['message'] = "You have 0 activation left on this license.";
                                    $flag = 0;
                                }                                  
                            }
                        }
                    }
                    
                    if ($flag) {
                        if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP" || $val[0] == "TCHI")) {
                            
                            if (!empty($is_multi_teacher) && !empty($allTeacher)) {
                                // Update License in User table
                                update_query('user', 'id = "' . $result['id'] . '" ', array('license' => $license));
                                foreach($allTeacher as $tval){
                                    update_query('user', 'teacher = "' . $tval['teacher'] . '" ', array('license' => $license));  // update all student hase same license
                                    // Update License in Settings table
                                    update_query('settings', 'id = "' . $tval['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));

                                    /*Teacher license activation */
                                    $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_HEADER, false);
                                    $result_active = curl_exec($ch);
                                    curl_close($ch);
                                    $teacher_lice_status = json_decode($result_active);
                                    /*Teacher license activation */

                                    /* update user license of teacher start here */
                                    $check_exists_license_query = query("select * from users_licenses where user_id = '" . $tval['id'] . "' AND license='" . $license . "'");
                                    $users_license_result = fetch($check_exists_license_query);

                                    if (empty($users_license_result)) {
                                        query("INSERT INTO users_licenses SET `user_id` = '" . $tval['id'] . "', license='" . $license . "' ");
                                    } else {
                                        update_query('users_licenses', 'user_id = "' . $tval['id'] . '"', array('license' => $license));  // update all same license of student
                                    }
                                    /* update user license of teacher end here */

                                    $args = array(
                                        'teacher_code' => $tval['teacher'],
                                        'role' => 'student',
                                    );
                                    $teacher_studentsAll = get_users($args);
                                    foreach ($teacher_studentsAll as $stud) {
                                        // Update License in Settings table
                                        update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                                        $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;

                                        //step1
                                        $ch = curl_init();
                                        //step2
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_HEADER, false);
                                        //step3
                                        $result_active = curl_exec($ch);
                                        //step4
                                        curl_close($ch);

                                        $status = json_decode($result_active);
                                        /* update user license start here */
                                        $check_exists_license_query = query("select * from users_licenses where user_id = '" . $stud['id'] . "' AND license='" . $license . "'");
                                        $users_license_result = fetch($check_exists_license_query);

                                        if (empty($users_license_result)) {
                                            // Data save in Users Licenses table
                                            query("INSERT INTO users_licenses SET `user_id` = '" . $stud['id'] . "', license='" . $license . "' ");
                                        } else {
                                            update_query('users_licenses', 'user_id = "' . $stud['id'] . '"', array('license' => $license));  // update all same license of student
                                        }
                                        /* update user license end here  */
                                    }                                        
                                }
                                update_no_license_data_new($license,$studentsCount,count($allTeacher)); /* update teacher data */

                                get_license_details($license, $result['id']);        // license details update in 'wordpress_licenses' table
                                if ($result['role'] == 'teacher') {
                                    update_query('users_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
                                    update_query('wordpress_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
                                }

$url1 = 'https://dev.accessibyte.com?edd_action=license_unsubscribe&license_id='.$oldLic["id"].'&license_key='.$oldLic["key"];

$curld = curl_init();

                                curl_setopt_array($curld, array(
                                  CURLOPT_URL => $url1 ,
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_ENCODING => '',
                                  CURLOPT_MAXREDIRS => 10,
                                  CURLOPT_TIMEOUT => 0,
                                  CURLOPT_FOLLOWLOCATION => true,
                                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                  CURLOPT_CUSTOMREQUEST => 'POST',
                                  CURLOPT_POSTFIELDS => array('r' => '11'),
                                  CURLOPT_HTTPHEADER => array(
                                    'Cookie: PHPSESSID=174458c8b76f3286d249ee63863f8d90'
                                  ),
                                ));
                                
                                $response_d = curl_exec($curld);
                                
                                curl_close($curld);

                                $_SESSION['error']['message'] = "License is updated successfully.";
								addPriceoptionPromocode($license,$result['id']);  /* update promocdoe and price option */
                                header("Location: " . ADMIN_URL . 'login');
                                //exit;
                            }    
                        }
                        if(empty($is_multi_teacher)){
							
                            $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
                            //step1
                            $ch = curl_init();
                            //step2
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_HEADER, false);
                            //step3
                            $result_active = curl_exec($ch);
                            //step4
                            curl_close($ch);

                            $status = json_decode($result_active);
                            
                            if ($status->success == "true") {
                                // Update License in User table
                                update_query('user', 'id = "' . $result['id'] . '" ', array('license' => $license));
                                if ($result['role'] == 'teacher') {
                                    if(empty($is_multi_teacher)){                                       
                                        update_query('user', 'teacher = "' . $result['teacher'] . '" ', array('license' => $license));  // update all student hase same license
                                    }
                                }
                                
                                if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP" || $val[0] == "TCHI")) {
                                    $noofStudent = 0;
                                    if (empty($is_multi_teacher) && !empty($teacher_students)) {   /* For single teacher */
										$noofStudent = count($teacher_students);
                                        foreach ($teacher_students as $stud) {
                                            // Update License in Settings table
                                            update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                                            $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;

                                            //step1
                                            $ch = curl_init();
                                            //step2
                                            curl_setopt($ch, CURLOPT_URL, $url);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($ch, CURLOPT_HEADER, false);
                                            //step3
                                            $result_active = curl_exec($ch);
                                            //step4
                                            curl_close($ch);

                                            $status = json_decode($result_active);
                                            /* update user license start here */
                                            $check_exists_license_query = query("select * from users_licenses where user_id = '" . $stud['id'] . "' AND license='" . $license . "'");
                                            $users_license_result = fetch($check_exists_license_query);

                                            if (empty($users_license_result)) {
                                                // Data save in Users Licenses table
                                                query("INSERT INTO users_licenses SET `user_id` = '" . $stud['id'] . "', license='" . $license . "' ");
                                            } else {
                                                update_query('users_licenses', 'user_id = "' . $stud['id'] . '"', array('license' => $license));  // update all same license of student
                                            }
                                            /* update user license end here  */
                                        }
                                    }
									update_no_license_data_new($license,$noofStudent);
                                    /*Update license nos'of student and teacher data */
                                }
								if ($result['role'] == 'student') {
                                    update_no_license_data_new($license,'1','0');   /* update no of activation */
                                }
                                if (!empty($license_item)) {
                                    // Update License in Settings table
                                    update_query('settings', 'id = "' . $result['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                                }

                                $check_exists_license_query = query("select * from users_licenses where user_id = '" . $result['id'] . "' AND license='" . $license . "'");
                                $users_license_result = fetch($check_exists_license_query);

                                if (empty($users_license_result)) {
                                    // Data save in Users Licenses table
                                    query("INSERT INTO users_licenses SET `user_id` = '" . $result['id'] . "', license='" . $license . "' ");
                                }

                                get_license_details($license, $result['id']);        // license details update in 'wordpress_licenses' table
                                if ($result['role'] == 'teacher') {
                                    update_query('users_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
                                    update_query('wordpress_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
                                }
$url1 = 'https://dev.accessibyte.com?edd_action=license_unsubscribe&license_id='.$oldLic["id"].'&license_key='.$oldLic["key"];

$curld = curl_init();

                                curl_setopt_array($curld, array(
                                  CURLOPT_URL => $url1 ,
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_ENCODING => '',
                                  CURLOPT_MAXREDIRS => 10,
                                  CURLOPT_TIMEOUT => 0,
                                  CURLOPT_FOLLOWLOCATION => true,
                                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                  CURLOPT_CUSTOMREQUEST => 'POST',
                                  CURLOPT_POSTFIELDS => array('r' => '11'),
                                  CURLOPT_HTTPHEADER => array(
                                    'Cookie: PHPSESSID=174458c8b76f3286d249ee63863f8d90'
                                  ),
                                ));
                                
                                $response_d = curl_exec($curld);
                                
                                curl_close($curld);

                                    
                                $_SESSION['error']['message'] = "License is updated successfully.";
								addPriceoptionPromocode($license,$result['id']);  /* update promocdoe and price option */
                                header("Location: " . ADMIN_URL . 'login');
                                //exit();
                            } else {
                                $_SESSION['error']['message'] = "License key is invalid.";
                            }
                        }
                    }
                } else {
                    $_SESSION['error']['message'] = $val[0] . " is not allowed for " . ucfirst($result['role']);
                }
            } else {
				$_SESSION['error']['message'] = "Username or password incorrect, Please try again.";
			}
        } else {
            $_SESSION['error']['message'] = "You can't renew using a trial license.";
        }
    } else {
        $_SESSION['error']['message'] = "License key not found. Please check your License key.";
    }
}
function update_license_multi_teacherold($username, $password, $license){

    $license_item = '';
    $item = '';
    $val = explode("-", trim($license));
    if ($val[0] == "PRO") {
        $item = "67695";
        $license_item = 6;
    } elseif ($val[0] == "BDL") {
        $item = "4184";
        $license_item = 5;
    } elseif ($val[0] == "BRL") {
        $item = "88653";
        $license_item = 5;
    } elseif ($val[0] == "TYO") {
        $item = "3584";
        $license_item = 5;
    } else if ($val[0] == "TCH") {
        $item = "4111";
        $license_item = 5;
 } else if ($val[0] == "TCHI") {
        $item = "67578";
        $license_item = 5;
    } else if ($val[0] == "TCHP") { /* Add new liecenece for teacher dashboard */
        $item = "30398";
        $license_item = 5;
    } else if ($val[0] == "WCO") {
        $item = "0";
    } else if ($val[0] == "AAO") {
        $item = "0";
        $license_item = 7;
    } else if ($val[0] == "TY") {
        $item = "192";
    } else if ($val[0] == "QCO") {
        $item = "4182";
        $license_item = 8;
    } else if ($val[0] == "AA") {
        $item = "905";
    } else if ($val[0] == "WW") {
        $item = "207";
    }
    $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

    //step1
    $ch = curl_init();
    //step2
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //step3
    $result_curl = curl_exec($ch);
    //step4
    curl_close($ch);

    $check_lic = json_decode($result_curl);

    if ($check_lic->success == 1) {

        $today = date_create(date('Y-m-d'));
        $expires = date_create(date('Y-m-d', strtotime($check_lic->expires)));
        $diff = date_diff($today, $expires);

        if ($diff->days > 14) {
            $password = md5($password);
            $get_data = query("select * from user where (user.username = '" . $username . "' or user.username = '" . base64_encode($username) . "') AND user.password='" . $password . "'");
            $result = fetch($get_data);

            if (!empty($result)) {
                $flag = 1;
                $is_valid = 0;
                $is_multi_teacher = 0;
                $allTeacher = array();
                if ($result['role'] == 'teacher' && ($val[0] == "TCH" || $val[0] == "TCHP" || $val[0] == "TCHI")) {
                    $is_valid = 1;
                    if(!empty($result['is_admin']) || $result['is_admin'] == '1'){
                        $is_multi_teacher = 1;
                        $allTeacher = getAllTeacher($result['license']);   
                    } else{
                        $single_license = get_license_data($result['license']); 
                        if(isset($single_license) && !empty($single_license) && $single_license['no_teacher'] > 1 && empty($result['is_lock'])){
                            $is_multi_teacher = 1;
                            $allTeacher = getAllTeacher($result['license']);                            
                        }
                    }
                }
                if ($result['role'] != 'teacher' && $val[0] != "TCH" && $val[0] != "TCHP" && $val[0] != "TCHI") {
                    $is_valid = 1;
                }
                if (!empty($is_valid)) {
                    // Without login check  Link = /accessibyte/online/update-license.php
                    if (isset($_POST['update_license_withoutLogin']) && $_POST['update_license_withoutLogin'] == 'withoutlogin') {
                        if (!empty($result) && $result['role'] == 'teacher') {
                            if(!empty($is_multi_teacher)){
                                $studentsCount = 0;
                                foreach($allTeacher as $allTeacherval){
                                    $args = array(
                                        'teacher_code' => $allTeacherval['teacher'],
                                        'role' => 'student',
                                    );
                                    $teacher_students = get_users($args);
                                    if(!empty($teacher_students))
                                    $studentsCount += count($teacher_students);
                                }
                                $newLicenseArr = get_license_data($license);
                                $no_teacher_new_lic = $no_use_new = $teacher_left_new = $no_student_new_lic =  $no_std_use_new = $student_left_new = "0";
                                if(!empty($newLicenseArr)){
                                    $no_teacher_new_lic = $newLicenseArr['no_teacher'];
                                    $no_use_new = $newLicenseArr['no_teacher_use'] ? $newLicenseArr['no_teacher_use'] : '0';
                                    $teacher_left_new  = $no_teacher_new_lic - $no_use_new;
                                    

                                    $no_student_new_lic = $newLicenseArr['no_student'];
                                    $no_std_use_new = $newLicenseArr['no_student_use'] ? $newLicenseArr['no_student_use'] : '0';
                                    $student_left_new  = $no_student_new_lic - $no_std_use_new;
                                }

                                
                                $oldLicenseArr = get_license_data($result['license']);
                                $no_use_old = $no_teacher = $teacher_left_old = "0";
                                if(!empty($oldLicenseArr)){
                                    $no_use_old = $oldLicenseArr['no_teacher_use'] ? $oldLicenseArr['no_teacher_use'] : '0';
                                    $no_teacher = $oldLicenseArr['no_teacher'];
                                    $teacher_left_old  = $no_teacher - $no_use_old;
                                }

                                if($teacher_left_new > 0){
                                    if($teacher_left_new < $no_use_old){
                                        $diffTeacher = $no_use_old - $teacher_left_new;
                                        $_SESSION['error']['message'] = "Not enough teacher seats on this license. You have " . $teacher_left_new . " seats available. Please delete " . $diffTeacher . " teacher before applying the license or purchase additional license seats.";
                                        $flag = 0;
                                    } else {
                                        $left = $student_left_new;
                                        $total = $studentsCount;
                                        $seat_avail = $left;
                                        $diffoflimit = $total - $left;
                                        if ($left > 0) {
                                            if ($left < $total) {
                                                $_SESSION['error']['message'] = "Not enough seats on this license. You have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
                                                $flag = 0;
                                            }
                                        } else {
                                            $_SESSION['error']['message'] = "You have 0 activation left on this license.";
                                            $flag = 0;
                                        }
                                    }
                                } else {                            
                                    $_SESSION['error']['message'] = "You have 0 activation left on this license.";
                                    $flag = 0;
                                    
                                }
                            } else {
                                $args = array(
                                    'teacher_code' => $result['teacher'],
                                    'role' => 'student',
                                );
                                $teacher_students = get_users($args);            
                                // Without login check  Link = /accessibyte/online/update-license.php                                                                
                                $studentsCount = count($teacher_students);
                                $new_update_license_limit = $check_lic->license_limit - 1;
    
                                $left = $check_lic->activations_left;
    
                                $total = $studentsCount + 1;
                                $seat_avail = $left - 1;
                                $diffoflimit = $total - $left;
                                if ($left > 0) {
                                    if ($left < $total) {
                                        $_SESSION['error']['message'] = "Not enough seats on this license. You have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
                                        $flag = 0;
                                    }
                                } else {
                                    $_SESSION['error']['message'] = "You have 0 activation left on this license.";
                                    $flag = 0;
                                }
                                                                
                            }
                        }
                    }
                    // echo "<pre>";print_r($teacher_students);
                    // echo $_SESSION['error']['message'];
                    // echo $flag;die;
                    if ($flag) {
                        if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP" || $val[0] == "TCHI")) {
                            
                            if (!empty($is_multi_teacher) && !empty($allTeacher)) {
                                // Update License in User table
                                update_query('user', 'id = "' . $result['id'] . '" ', array('license' => $license));
                                foreach($allTeacher as $tval){
                                    update_query('user', 'teacher = "' . $tval['teacher'] . '" ', array('license' => $license));  // update all student hase same license
                                    // Update License in Settings table
                                    update_query('settings', 'id = "' . $tval['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));

                                    /*Teacher license activation */
                                    $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_HEADER, false);
                                    $result_active = curl_exec($ch);
                                    curl_close($ch);
                                    $teacher_lice_status = json_decode($result_active);
                                    /*Teacher license activation */

                                    /* update user license of teacher start here */
                                    $check_exists_license_query = query("select * from users_licenses where user_id = '" . $tval['id'] . "' AND license='" . $license . "'");
                                    $users_license_result = fetch($check_exists_license_query);

                                    if (empty($users_license_result)) {
                                        query("INSERT INTO users_licenses SET `user_id` = '" . $tval['id'] . "', license='" . $license . "' ");
                                    } else {
                                        update_query('users_licenses', 'user_id = "' . $tval['id'] . '"', array('license' => $license));  // update all same license of student
                                    }
                                    /* update user license of teacher end here */

                                    $args = array(
                                        'teacher_code' => $tval['teacher'],
                                        'role' => 'student',
                                    );
                                    $teacher_studentsAll = get_users($args);
                                    foreach ($teacher_studentsAll as $stud) {
                                        // Update License in Settings table
                                        update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                                        $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;

                                        //step1
                                        $ch = curl_init();
                                        //step2
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_HEADER, false);
                                        //step3
                                        $result_active = curl_exec($ch);
                                        //step4
                                        curl_close($ch);

                                        $status = json_decode($result_active);
                                        /* update user license start here */
                                        $check_exists_license_query = query("select * from users_licenses where user_id = '" . $stud['id'] . "' AND license='" . $license . "'");
                                        $users_license_result = fetch($check_exists_license_query);

                                        if (empty($users_license_result)) {
                                            // Data save in Users Licenses table
                                            query("INSERT INTO users_licenses SET `user_id` = '" . $stud['id'] . "', license='" . $license . "' ");
                                        } else {
                                            update_query('users_licenses', 'user_id = "' . $stud['id'] . '"', array('license' => $license));  // update all same license of student
                                        }
                                        /* update user license end here  */
                                    }                                        
                                }
                                update_no_license_data_new($license,$studentsCount,count($allTeacher)); /* update teacher data */

                                get_license_details($license, $result['id']);        // license details update in 'wordpress_licenses' table
                                if ($result['role'] == 'teacher') {
                                    update_query('users_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
                                    update_query('wordpress_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
                                }
                                $_SESSION['error']['message'] = "License is updated successfully.";
								addPriceoptionPromocode($license,$result['id']);  /* update promocdoe and price option */
                                header("Location: " . ADMIN_URL . 'login');
                                //exit;
                            }    
                        }
                        if(empty($is_multi_teacher)){
							
                            $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
                            //step1
                            $ch = curl_init();
                            //step2
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_HEADER, false);
                            //step3
                            $result_active = curl_exec($ch);
                            //step4
                            curl_close($ch);

                            $status = json_decode($result_active);
                            
                            if ($status->success == "true") {
                                // Update License in User table
                                update_query('user', 'id = "' . $result['id'] . '" ', array('license' => $license));
                                if ($result['role'] == 'teacher') {
                                    if(empty($is_multi_teacher)){                                       
                                        update_query('user', 'teacher = "' . $result['teacher'] . '" ', array('license' => $license));  // update all student hase same license
                                    }
                                }
                                
                                if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP" || $val[0] == "TCHI")) {
                                    $noofStudent = 0;
                                    if (empty($is_multi_teacher) && !empty($teacher_students)) {   /* For single teacher */
										$noofStudent = count($teacher_students);
                                        foreach ($teacher_students as $stud) {
                                            // Update License in Settings table
                                            update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                                            $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;

                                            //step1
                                            $ch = curl_init();
                                            //step2
                                            curl_setopt($ch, CURLOPT_URL, $url);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($ch, CURLOPT_HEADER, false);
                                            //step3
                                            $result_active = curl_exec($ch);
                                            //step4
                                            curl_close($ch);

                                            $status = json_decode($result_active);
                                            /* update user license start here */
                                            $check_exists_license_query = query("select * from users_licenses where user_id = '" . $stud['id'] . "' AND license='" . $license . "'");
                                            $users_license_result = fetch($check_exists_license_query);

                                            if (empty($users_license_result)) {
                                                // Data save in Users Licenses table
                                                query("INSERT INTO users_licenses SET `user_id` = '" . $stud['id'] . "', license='" . $license . "' ");
                                            } else {
                                                update_query('users_licenses', 'user_id = "' . $stud['id'] . '"', array('license' => $license));  // update all same license of student
                                            }
                                            /* update user license end here  */
                                        }
                                    }
									update_no_license_data_new($license,$noofStudent);
                                    /*Update license nos'of student and teacher data */
                                }
								if ($result['role'] == 'student') {
                                    update_no_license_data_new($license,'1','0');   /* update no of activation */
                                }
                                if (!empty($license_item)) {
                                    // Update License in Settings table
                                    update_query('settings', 'id = "' . $result['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                                }

                                $check_exists_license_query = query("select * from users_licenses where user_id = '" . $result['id'] . "' AND license='" . $license . "'");
                                $users_license_result = fetch($check_exists_license_query);

                                if (empty($users_license_result)) {
                                    // Data save in Users Licenses table
                                    query("INSERT INTO users_licenses SET `user_id` = '" . $result['id'] . "', license='" . $license . "' ");
                                }

                                get_license_details($license, $result['id']);        // license details update in 'wordpress_licenses' table
                                if ($result['role'] == 'teacher') {
                                    update_query('users_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
                                    update_query('wordpress_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
                                }
								
                                $_SESSION['error']['message'] = "License is updated successfully.";
								addPriceoptionPromocode($license,$result['id']);  /* update promocdoe and price option */
                                header("Location: " . ADMIN_URL . 'login');
                                //exit();
                            } else {
                                $_SESSION['error']['message'] = "License key is invalid.";
                            }
                        }
                    }
                } else {
                    $_SESSION['error']['message'] = $val[0] . " is not allowed for " . ucfirst($result['role']);
                }
            } else {
				$_SESSION['error']['message'] = "Username or password incorrect, Please try again.";
			}
        } else {
            $_SESSION['error']['message'] = "You can't renew using a trial license.";
        }
    } else {
        $_SESSION['error']['message'] = "License key not found. Please check your License key.";
    }
}
function update_license_my_account($username, $license) {

    $license_item = '';
    $item = '';
    $val = explode("-", trim($license));
    if ($val[0] == "PRO") {
        $item = "67695";
        $license_item = 6;
    } elseif ($val[0] == "BDL") {
        $item = "4184";
        $license_item = 5;
    } elseif ($val[0] == "BRL") {
        $item = "88653";
        $license_item = 5;
    } elseif ($val[0] == "TYO") {
        $item = "3584";
        $license_item = 5;
    } else if ($val[0] == "TCH") {
        $item = "4111";
        $license_item = 5;
} else if ($val[0] == "TCHI") {
        $item = "67578";
        $license_item = 5;
    } else if ($val[0] == "TCHP") {
        $item = "30398";
        $license_item = 5;
    } else if ($val[0] == "WCO") {
        $item = "0";
    } else if ($val[0] == "AAO") {
        $item = "0";
        $license_item = 7;
    } else if ($val[0] == "TY") {
        $item = "192";
    } else if ($val[0] == "QCO") {
        $item = "4182";
        $license_item = 8;
    } else if ($val[0] == "AA") {
        $item = "905";
    } else if ($val[0] == "WW") {
        $item = "207";
    }
    $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

    //step1
    $ch = curl_init();
    //step2
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //step3
    $result_curl = curl_exec($ch);
    //step4
    curl_close($ch);

    $check_lic = json_decode($result_curl);

    if (isset($check_lic->success) && $check_lic->success == 1) {

        $today = date_create(date('Y-m-d'));
        $expires = date_create(date('Y-m-d', strtotime($check_lic->expires)));
        $diff = date_diff($today, $expires);

        if ($diff->days > 14) {
            $get_data = query("select * from user where user.username = '" . $username . "' or user.username = '" . base64_encode($username) . "'");
            $result = fetch($get_data);
            $is_multi_teacher = 0;
            $flag = 1;
            $allTeacher = array();
            if (!empty($result)) {
                
                if(!empty($result['is_admin']) || $result['is_admin'] == '1'){
                    $is_multi_teacher = 1;
                    $allTeacher = getAllTeacher($result['license']);   
                } else {
                    $single_license = get_license_data($result['license']); 
                    if(isset($single_license) && !empty($single_license) && $single_license['no_teacher'] > 1 && empty($result['is_lock'])){
                        $is_multi_teacher = 1;
                        $allTeacher = getAllTeacher($result['license']);                            
                    }
                }
                if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP" || $val[0] == "TCHI")) {

                    if (!empty($is_multi_teacher) && !empty($allTeacher)) {
                        $studentsCount = 0;
                        foreach($allTeacher as $teacherval){
                            $args = array(
                                'teacher_code' => $teacherval['teacher'],
                                'role' => 'student',
                            );
                            $teacher_students = get_users($args);
                            if(!empty($teacher_students))
                            $studentsCount += count($teacher_students);
                        }
                        $newLicenseArr = get_license_data($license);
                        $no_teacher_new_lic = $no_use_new = $teacher_left_new = $no_student_new_lic =  $no_std_use_new = $student_left_new = "0";
                        if(!empty($newLicenseArr)){
                            $no_teacher_new_lic = $newLicenseArr['no_teacher'];
                            $no_use_new = $newLicenseArr['no_teacher_use'] ? $newLicenseArr['no_teacher_use'] : '0';
                            $teacher_left_new  = $no_teacher_new_lic - $no_use_new;
                            

                            $no_student_new_lic = $newLicenseArr['no_student'];
                            $no_std_use_new = $newLicenseArr['no_student_use'] ? $newLicenseArr['no_student_use'] : '0';
                            $student_left_new  = $no_student_new_lic - $no_std_use_new;
                        }

                        
                        $oldLicenseArr = get_license_data($result['license']);
                        $no_use_old = $no_teacher = $teacher_left_old = "0";
                        if(!empty($oldLicenseArr)){
                            $no_use_old = $oldLicenseArr['no_teacher_use'] ? $oldLicenseArr['no_teacher_use'] : '0';
                            $no_teacher = $oldLicenseArr['no_teacher'];
                            $teacher_left_old  = $no_teacher - $no_use_old;
                        }

                        if($teacher_left_new > 0){
                            if($teacher_left_new < $no_use_old){
                                $diffTeacher = $no_use_old - $teacher_left_new;
                                $_SESSION['error']['message'] = "Not enough teacher seats on this license. You have " . $teacher_left_new . " seats available. Please delete " . $diffTeacher . " teachers before applying the license or purchase additional license seats.";
                                $flag = 0;
                            } else {
                                $left = $student_left_new;
                                $total = $studentsCount;
                                $seat_avail = $left;
                                $diffoflimit = $total - $left;
                                if ($left > 0) {
                                    if ($left < $total) {
                                        $_SESSION['error']['message'] = "Not enough seats on this license. You have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
                                        $flag = 0;
                                    }
                                } else {
                                    $_SESSION['error']['message'] = "You have 0 activation left on this license.";
                                    $flag = 0;
                                }
                            }
                        } else {                            
                            $_SESSION['error']['message'] = "You have 0 activation left on this license.";
                            $flag = 0;
                            
                        }
                        // echo $_SESSION['error']['message'];
                        // echo $flag;die;
                        if($flag){
                            
                            // Update License in User table
                            update_query('user', 'id = "' . $result['id'] . '" ', array('license' => $license));
                            foreach($allTeacher as $tval){
                                update_query('user', 'teacher = "' . $tval['teacher'] . '" ', array('license' => $license));  // update all student hase same license
                                // Update License in Settings table
                                update_query('settings', 'id = "' . $tval['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));

                                /*Teacher license activation */
                                $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_HEADER, false);
                                $result_active = curl_exec($ch);
                                curl_close($ch);
                                $teacher_lice_status = json_decode($result_active);
                                /*Teacher license activation */

                                /* update user license of teacher start here */
                                $check_exists_license_query = query("select * from users_licenses where user_id = '" . $tval['id'] . "' AND license='" . $license . "'");
                                $users_license_result = fetch($check_exists_license_query);

                                if (empty($users_license_result)) {
                                    query("INSERT INTO users_licenses SET `user_id` = '" . $tval['id'] . "', license='" . $license . "' ");
                                } else {
                                    update_query('users_licenses', 'user_id = "' . $tval['id'] . '"', array('license' => $license));  // update all same license of student
                                }
                                /* update user license of teacher end here */

                                $args = array(
                                    'teacher_code' => $tval['teacher'],
                                    'role' => 'student',
                                );
                                $teacher_studentsAll = get_users($args);
                                foreach ($teacher_studentsAll as $stud) {
                                    // Update License in Settings table
                                    update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                                    $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;

                                    //step1
                                    $ch = curl_init();
                                    //step2
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_HEADER, false);
                                    //step3
                                    $result_active = curl_exec($ch);
                                    //step4
                                    curl_close($ch);

                                    $status = json_decode($result_active);
                                    /* update user license start here */
                                    $check_exists_license_query = query("select * from users_licenses where user_id = '" . $stud['id'] . "' AND license='" . $license . "'");
                                    $users_license_result = fetch($check_exists_license_query);

                                    if (empty($users_license_result)) {
                                        // Data save in Users Licenses table
                                        query("INSERT INTO users_licenses SET `user_id` = '" . $stud['id'] . "', license='" . $license . "' ");
                                    } else {
                                        update_query('users_licenses', 'user_id = "' . $stud['id'] . '"', array('license' => $license));  // update all same license of student
                                    }
                                    /* update user license end here  */
                                }                                        
                            }
                            update_no_license_data_new($license,$studentsCount,count($allTeacher)); /* update teacher data */
                            
                        }
                    } else if(empty($is_multi_teacher)){ 
                        $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
                        // echo $url;exit;
                        //step1
                        $ch = curl_init();
                        //step2
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HEADER, false);
                        //step3
                        $result_active = curl_exec($ch);
                        //step4
                        curl_close($ch);

                        $status = json_decode($result_active);
                        if ($status->success == "true") {
                            update_query('user', 'teacher = "' . $result['teacher'] . '" ', array('license' => $license));  // update all student hase same license
                            $args = array(
                                'teacher_code' => $result['teacher'],
                                'role' => 'student',
                            );
                            $teacher_students = get_users($args);
							$noofStudent = 0;
                            if (!empty($teacher_students)) {
								$noofStudent = count($teacher_students);
                                foreach ($teacher_students as $stud) {
                                    // Update License in Settings table
                                    update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                                    $url = WP_URL . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
                                    // echo $url;exit;
                                    //step1
                                    $ch = curl_init();
                                    //step2
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_HEADER, false);
                                    //step3
                                    $result_active = curl_exec($ch);
                                    //step4
                                    curl_close($ch);

                                    $status = json_decode($result_active);
                                    /* update user license start here */
                                        
                                    $check_exists_license_query = query("select * from users_licenses where user_id = '" . $stud['id'] . "' AND license='" . $license . "'");
                                    $users_license_result = fetch($check_exists_license_query);

                                    if (empty($users_license_result)) {
                                        // Data save in Users Licenses table
                                        query("INSERT INTO users_licenses SET `user_id` = '" . $stud['id'] . "', license='" . $license . "' ");
                                    } else {
                                        update_query('users_licenses', 'user_id = "' . $stud['id'] . '"', array('license' => $license));  // update all same license of student
                                    }
                                    
                                    
                                    /* update user license end here  */
                                }
                                
                            }
							update_no_license_data_new($license,$noofStudent);
                        } else {
                            $flag = 0;
                            $_SESSION['error']['message'] = "License key is invalid.";
                        }
                    }
                
                    if($flag){
                        if (!empty($license_item)) {
                            // Update License in Settings table
                            update_query('settings', 'id = "' . $result['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                        }

                        $check_exists_license_query = query("select * from users_licenses where user_id = '" . $result['id'] . "' AND license='" . $license . "'");
                        $users_license_result = fetch($check_exists_license_query);

                        if (empty($users_license_result)) {
                            // Data save in Users Licenses table
                            query("INSERT INTO users_licenses SET `user_id` = '" . $result['id'] . "', license='" . $license . "' ");
                        }

                        get_license_details($license, $result['id']);        // license details update in 'wordpress_licenses' table
                        update_query('users_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
                        update_query('wordpress_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
						addPriceoptionPromocode($license,$result['id']); /* update pricing option and promocode*/
                        $_SESSION['error']['message'] = "License is updated successfully.";
                    }
                }               
            }
        } else {
            $_SESSION['error']['message'] = "You can't renew trial license keys.";
        }
    } else {
        $_SESSION['error']['message'] = "License key not found. Please check your License key.";
    }
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
/**
 * Add custom sorting for encoded value
 */
function custom_sort($records,$sortkey,$order){
    foreach ($records as $key => $user) {
        $records[$key]['sort_firstname'] = trim(ucfirst(base64_decode($user['firstname'])));
        $records[$key]['sort_username'] = trim(ucfirst(base64_decode($user['username'])));
        $records[$key]['sort_teacher_name'] = trim(ucfirst(base64_decode($user['teacher_name'])));
        $records[$key]['sort_org'] = base64_decode($user['organization']);        
    }
    if( $sortkey == 'firstname'){
        $sortkey = 'sort_firstname';
    } else if($sortkey == 'username'){
        $sortkey = 'sort_username';
    } else if($sortkey == 'teacher_name'){
        $sortkey = 'sort_teacher_name';
    } else if($sortkey == 'organization'){
        $sortkey = 'sort_org';
    }    
    if(!empty($sortkey)){
        if($order == 'desc'){
            $records = array_sort($records,$sortkey,SORT_DESC);
        } else {
            $records = array_sort($records,$sortkey,SORT_ASC);
        }           
    }    
    return $records;    
}
/**
 * function for multidimension array sorting
 */
function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = trim($v2);
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }
        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }       
        foreach ($sortable_array as $k1 => $v) {
            $new_array[$k1] = $array[$k1];
        }
    }
    return $new_array;
}
/**
 * check license expired or not
 */
function check_expire_or_not(){
    $is_expired = false;
    if(empty($_SESSION['User']['expire_login_status']) && isset( $_SESSION['User']['is_expired']) && !empty( $_SESSION['User']['is_expired'])){ 
        $is_expired = true;
    }
    return $is_expired;
}
/**
 * get seat limit total of teachers
*/
function teacherSeatLimitTotal($license_key){
    $query = query("SELECT sum(seat_limit) as total FROM user where license = '".$license_key."' and role = 'teacher'");

    $data = fetch($query);
    return $data;
}
/**
 * Lock license or not
 */
function update_license_lock($license_key,$lockval){
    $lockstr = 'Unlocked';
    if($lockval == '1')
    $lockstr = 'Locked';
    if (query("UPDATE user set `is_lock`='".$lockval."'  WHERE `license`='" . $license_key . "'")) {
        $_SESSION['error']['message'] = "License ".$lockstr." successfully.";
        $_SESSION['error']['color'] = 'success';
    } else {
        $_SESSION['error']['message'] = "Something went wrong.";
        $_SESSION['error']['color'] = 'danger';
        
    }   
    header("Location: " . ADMIN_URL . 'my-account.php');
    exit;      
}
/**
 * Return license lock or not
 */
function check_license_lock($license_key){
    
    $query = query("SELECT is_lock FROM user where `role` = 'teacher' and `license`='" . $license_key . "'");
    $user = fetch($query);
    if(!empty($user)){
        return $user['is_lock'];
    }
    return false;
}
/**
* Function return teacher list
*/
function getAllTeacher($license = ''){
	if(empty($license)){
		$license = $_SESSION['User']['license'];
	}
	$teacher_query = query(" SELECT * FROM user WHERE license = '".$license."' and role= 'teacher'");
	$users = array();
	while ($row = fetch($teacher_query)) {
		$users[] = $row;
	}   
	return $users;
}
/**
  * Function use for insert user login log into table
  */
  function insertUserLoginLog($logArr){
      if(!empty($logArr)){
        $user_id = $logArr['user_id'];
        $login_date_time = $logArr['login_date_time'];
        $IP = $logArr['IP'];
        $browser_type = $logArr['browser_type'];    
        query("INSERT INTO user_login_detail SET `user_id` = '{$user_id}',`IP` = '{$IP}',`browser_type` = '{$browser_type}', `login_date_time` = '{$login_date_time}' ");
      }
      return true;
  }
  /**
  * Function use for insert user login details into user_login_details table
  */
  function insertUserLoginDetails($logArrData){
      if(!empty($logArrData)){
		   
        $user_id          = $logArrData["user_id"];
		$ip_address       = $logArrData["IP"];
		$browser          = $logArrData["browser"];
		$browser_version  = $logArrData["browser_version"];
		$device_vendor    = $logArrData["device_vendor"];
		$device_model     = $logArrData["device_model"];
		$device_type      = $logArrData["device_type"];
		$os_name          = $logArrData["os_name"];
		$os_version       = $logArrData["os_version"];
		$engine_name      = $logArrData["engine_name"];
		$engine_version   = $logArrData["engine_version"];
		$cpu_architecture = $logArrData["cpu_architectured"];
      
		query("INSERT INTO user_login_details (user_id, ip_address, browser, browser_version, device_vendor, device_model, device_type, os_name, os_version, engine_name, engine_version, cpu_architecture)
				VALUES ($user_id, '$ip_address', '$browser', '$browser_version', '$device_vendor', '$device_model', '$device_type', '$os_name', '$os_version', '$engine_name', '$engine_version', '$cpu_architecture')");
      }
      return true;
  }
  /**
   * Add price option and promocode from license 
   */
  //addPriceoptionPromocode('TCHP-KEEA-DRZC-IMJS-QYZT-TBBG','0');
  function addPriceoptionPromocode($license_key,$user_id){

    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licenses where license_key = '" . $license_key . "'");
    $data = mysqli_fetch_array($query);
    if (isset($data) && !empty($data) && isset($data['payment_id'])) {
        $payment_id = $data['payment_id'];
        $query = mysqli_query($wcon, "SELECT * FROM pia_postmeta where post_id = '" . $payment_id . "' and meta_key = '_edd_payment_meta'");
        $payment_data = mysqli_fetch_array($query);
        $download_id = $data['download_id'];
        $price_id = $data['price_id'];
        $promocode = '';
        if (!empty($payment_data) && isset($payment_data['meta_value']) && !empty($payment_data['meta_value'])) {
            $licenseArr = unserialize($payment_data['meta_value']);
            $promocode = (isset($licenseArr['user_info']['discount']) && $licenseArr['user_info']['discount'] != 'none') ? $licenseArr['user_info']['discount'] : '';            
        } 
        
        $price_option = $download_id.'|'.$price_id;    
        update_query('user', 'license = "' . $license_key  . '" AND id = "' . $user_id . '" ', array('price_option' => $price_option,'promo_code' => $promocode)); // update price option and promocode        
        
        $setting_query = query("SELECT id FROM settings where id='$user_id' and item='10'");
        $setting_data = fetch($setting_query);   
        if(!empty($setting_data['id'])){
            update_query('settings', 'id = "' . $user_id . '" AND item = "10" ', array('variable' => $price_option));
            update_query('settings', 'id = "' . $user_id . '" AND item = "11" ', array('variable' => $promocode));
        } else {
            query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '10', `variable` = '{$price_option}' ");
            query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '11', `variable` = '{$promocode}' ");
        } 
        
    }
  }
  /**
   * Get pricing array of setting from wordpress table 
   */
  function get_pricing_option_data(){
    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT option_value FROM pia_options where option_name = '_wwt_price_setting'");
    $data = mysqli_fetch_array($query);
    $result = array();
    if (isset($data) && !empty($data)) {
        if (!empty($data['option_value']) && !empty($data['option_value'])) {
            $result = unserialize($data['option_value']);            
        } 
    }
    return $result;
  }
  function get_teacher_fixed_price(){
    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT option_value FROM pia_options where option_name = '_wwt_teacher_fixed_price'");
    $data = mysqli_fetch_array($query);
    $result = 0;
    if (isset($data) && !empty($data)) {
        if (!empty($data['option_value']) && !empty($data['option_value'])) {
            $result = $data['option_value'];            
        } 
    }
    return $result;
  }
  function send_add_seat_quote($quote = ''){
    $username = $_SESSION['User']['username'];    
    $query = query("SELECT user.license,user.email,user.username,user.id,user.firstname,user.lastname,user.organization FROM user where user.username='$username' or user.username='" . base64_encode($username) . "'");
    $data = fetch($query);
    $variables = array();
    $variables['firstname'] = base64_decode($data['firstname']);
    $variables['lastname'] = base64_decode($data['lastname']);
    $variables['username'] = base64_decode($data['username']);
    $variables['organization'] = base64_decode($data['organization']);
    $variables['no_student'] = ($quote['no_student']);
    $variables['no_teacher'] = ($quote['no_teacher']);
    $variables['license'] = $data['license'];
    $variables['email'] = base64_decode($data['email']);
    $variables['total_price'] = $quote['product_total'];
    $licenseArr = explode("-", trim($_SESSION['User']['license']));
    $variables['product_shortname'] = isset($licenseArr[0]) ? $licenseArr[0] : '';
    //Get html template for reset password email
    pdfformat($variables);
    
    header("Location:" . ADMIN_URL . 'add-seats.php');
    exit;
  }
  function pdfformat($fields){
    require_once( './helper/tcpdf/tcpdf.php');
    require 'PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();
    $to = 'sanjaym@worlwebtechnology.in';
    try {
        //Recipients
        
        $name = $fields['username'];
        $email = $fields['email'];
        $org = $fields['organization'];
        $price = $fields['total_price'];
        $no_student = $fields['no_student'] . " Student";
        $no_teacher = $fields['no_teacher'] . " Teacher Seats";
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
if($shortname == 'TCHI'){
                $product_name =  'Typio Pro For Institutions';
            }
            $product_name = $product_name . " - Additional Seats ".$no_student. " - ".$no_teacher;
            
            //$upload_dir = $_SERVER['DOCUMENT_ROOT'].'accessibyte/online/';
            $upload_dir = ADMIN_DIR.'/uploads/';
            
            $file_name = rand(0,999);  /* generate custom string for avoid duplicate for same name*/
            $filename = "Accesibyte Quote for ".$file_name.".pdf";
            
            $fullpath = $upload_dir."/".$filename;
            
            $to = 'sanjaym@worldwebtechnology.in';
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
                . '<tr><td width="70%">'.$product_name.'</td><td width="30%">'.$price.'</td></tr>'
                . '<tr><td colspan="2"></td></tr>'
                . '<tr><td width="70%" style="border-bottom:1px solid #ececec;text-align:right;">SUBTOTAL</td><td width="30%" style="border-bottom:1px solid #ececec;">'.$price.'</td></tr>'
                . '<tr><td width="70%" style="border-bottom:1px solid #ececec;text-align:right;">TOTAL DUE </td><td width="30%" style="border-bottom:1px solid #ececec;">'.$price.'</td></tr>'
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
            
            $_SESSION['error']['message'] = "Requested quote has been sent.";
            $_SESSION['error']['color'] = 'success';
                    
        }
    } catch (Exception $e) {
        $_SESSION['error']['message'] = "Could not send quote. Try again.";
        $_SESSION['error']['color'] = 'danger';
        
    }    
  }
  /**
   * Add teacher to teacher subscriber group of mailerlite
   */
  function addTomailerlite($data){
    
    $subscriber = array(
        'email' => $data['email'],
        'name' => $data['fname'],
        'fields' => array(
          'surname' => $data['lname'],
          'company' => $data['org']
        )
    );
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.mailerlite.com/api/v2/groups/59403140/subscribers",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($subscriber),
      CURLOPT_HTTPHEADER => array(
        "content-type: application/json",
        "x-mailerlite-apikey: 7bd6aad70dc49827d626d55f5445bfe8"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    // if ($err) {
    //   echo "cURL Error #:" . $err;
    // } else {
    //   echo $response;
    // }
    return true;
}

/**
 * Check day difference of license
 */
function checkLicenseDayDiff() {
    $userid = $_SESSION['User']['id'];  
    $query = query("SELECT license as variable from user WHERE id ='" . $userid . "' ");
    $data = fetch($query);

    $info = 0;

    if (!empty($data) && !empty($data['variable'])) {
        list($itemName, $type) = explode('-', $data['variable']);

        if ($itemName == "PRO")
            $item = "67695";
        else if ($itemName == "BDL")
            $item = "4184";
        else if ($itemName == "BRL")
            $item = "88653";
        elseif ($itemName == "TYO")
            $item = "3584";

        elseif ($itemName == "TY")
            $item = "192";

        elseif ($itemName == "QCO")
            $item = "4182";
        else if ($itemName == "TCH")
            $item = "4111";
 else if ($itemName == "TCHI")
            $item = "67578";
        else if ($itemName == "TCHP") /* Add new liecenece for teacher dashboard */
            $item = "30398";
        elseif ($itemName == "AA")
            $item = "905";

        elseif ($itemName == "WW")
            $item = "207";

        $data['variable'] = trim($data['variable']);
        $url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . $data['variable'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $result = curl_exec($ch);
        curl_close($ch);

        $license = json_decode($result, true);
        if ($license['success'] == 1) {

            if ($license['license'] == 'expired') {
                $today = date_create(date('Y-m-d'));

                $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
                $query = mysqli_query($wcon, "SELECT * FROM pia_options where option_name = '_wwt_cron_days'");

                $daySetting =  "365";
                $data = mysqli_fetch_array($query);
                if (!empty($data) && isset($data['option_value']) && !empty($data['option_value'])) {
                    $daySetting = $data['option_value'];
                }
                
                $expires = date_create(date('Y-m-d', strtotime($license['expires'])));
                $diff = date_diff($today, $expires);
                $days_remaining =  isset($diff->days) ? $diff->days : '0';                
                return $daySetting - $days_remaining;
            }
        }
    }
    return $info;
}
function get_query_data($query){
    $user_data_rows = query($query);
    $student_data = array();
    while ($user_data_row = mysqli_fetch_assoc($user_data_rows)) {

        $student_data[] = $user_data_row;
    }
    return $student_data;
}
function typioHistoryData($userid = FALSE, $typio_start_date = '', $typio_end_date = ''){
    
    $log_typio_query = "SELECT * FROM `log` WHERE (`app` LIKE 'Typio-OL' OR `app` LIKE 'Typio-Journey' OR `app` LIKE 'Typio-BRL') AND id ='" . $userid . "'";
    $custom_date = '';
    $custom_date =  custom_student_date($typio_start_date);
        
    $lbl = "COM";
    if($custom_date){
        $lbl = "ERR";
    }
    if (( $typio_start_date != 0 ) && ( $typio_end_date != 0 )) {
        
        $typio_start_date = date('Y-m-d', strtotime($typio_start_date)) . ' 00:00:00';
        $typio_end_date = date('Y-m-d', strtotime($typio_end_date)) . ' 23:59:59';
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }
    $log_typio_query_row = query($log_typio_query);
    while ($log_typio_row = mysqli_fetch_assoc($log_typio_query_row)) {
        $log_typio_data[] = $log_typio_row;
    }
    $_average_scale = Typio_average_chart_write_data($userid, $typio_start_date, $typio_end_date);
    $log_typio_data_html = '';
    $log_typio_data_html .= '<tr><td colspan="2" width="55%">Average </td><td width="15%">'.$_average_scale['WPM'].'</td><td width="15%">'.$_average_scale['Accuracy'].'</td><td width="15%">'.$_average_scale['Combo'].'</td></tr>';

    if(!empty($log_typio_data)){
        foreach($log_typio_data as $log_typio_data_row) {
        
            $title = ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
            $log_typio_data_value = explode('|', $log_typio_data_row['data']);
            $log_typio_data_html .= '<tr><td width="40%">';
            $log_typio_data_html .= ($log_typio_data_row['file']) ? $log_typio_data_row['file'] : '';
            $log_typio_data_html .= '</td><td width="15%">';
            $log_typio_data_html .= ($log_typio_data_row['date']) ? date('m/d/y', strtotime($log_typio_data_row['date'])) : '';
            $log_typio_data_html .= '</td><td width="15%">';
            $log_typio_data_html .= ($log_typio_data_value[0]) ? $log_typio_data_value[0] : '';
            $log_typio_data_html .= '</td><td width="15%">';
            $log_typio_data_html .= ($log_typio_data_value[1]) ? intval($log_typio_data_value[1]) . '%' : '';
            $log_typio_data_html .= '</td><td width="15%">';
            $error_data = $log_typio_data_value[2];
            if($custom_date){
                $error_data = $log_typio_data_value[2];
            }
            $log_typio_data_html .= $error_data;
            $log_typio_data_html .= '</td>';
            $log_typio_data_html .= '</tr>';
        }
    }
    return $log_typio_data_html;
}
/**
 * Typio history average data
 */
function Typio_average_chart_write_data($student_id = 0, $typio_start_date = 0, $typio_end_date = 0) {
    
    $studentid = !empty($student_id) ? $student_id : '';
	$_average_scale = array();

    $log_data_table = "log";
    $log_data_table_wpm_title = ' WPM';
    $log_data_table_accuracy_title = '% Accuracy';
    
    $custom_date =  custom_student_date($typio_start_date);
    $log_data_table_combo_title = " Combo";
    if($custom_date){
        $log_data_table_combo_title = " Errors";
    }
    
    $log_data_table_wpm_value = 0;
    $log_data_table_accuracy_value = 0;
    $log_data_table_combo_value = 0;

    $log_typio_query = "SELECT * FROM `" . $log_data_table . "` WHERE `app` LIKE 'Typio-OL' AND id='" . $studentid . "'";
    if (($typio_start_date != 0) && ($typio_end_date != 0)) {
        $log_typio_query .= " AND `date` BETWEEN '" . $typio_start_date . "' AND '" . $typio_end_date . "' ";
    }

    $log_typio_query_row = query($log_typio_query);
    while ($log_typio_row = mysqli_fetch_assoc($log_typio_query_row)) {
        $log_typio_data[] = $log_typio_row;
    }

    
    $log_typio_data_count = 0;
    $log_typio_data_html = '';
    if(!empty($log_typio_data)){
        foreach($log_typio_data as $log_typio_data_row) {
            $log_typio_data_count++;
            $log_typio_data_value = explode('|', $log_typio_data_row['data']);
            $log_data_table_wpm_value += ($log_typio_data_value[0]) ? $log_typio_data_value[0] : 0;
            $log_data_table_accuracy_value += ($log_typio_data_value[1]) ? $log_typio_data_value[1] : 0;
            $log_data_table_combo_value += isset($log_typio_data_value[2]) ? $log_typio_data_value[2] : 0;
        }
    }
    $combo_data = '';
    if($custom_date){
        $combo_data = $log_data_table_combo_value;
    }
    $_average_scale['WPM'] = !empty($log_data_table_wpm_value) ? floor(($log_data_table_wpm_value / $log_typio_data_count)) : '0';
    $_average_scale['Accuracy'] = !empty($log_data_table_accuracy_value) ? floor(($log_data_table_accuracy_value / $log_typio_data_count)) : '0' ;
    $_average_scale['Combo'] = !empty($combo_data) ? floor(($combo_data / $log_typio_data_count)) : '0' ;

    return $_average_scale;
}
/**
 * Typio custom lessons
 */
function get_data_from_text_table_data($user_id = 0, $app_type, $number = 0) {
    
    $text_data = $query_val = array();

    //Set Table Name
    $table = "text";

    if (!empty($user_id)) {
        $query_val[] = " `id`='" . $user_id . "'";
    }

    if (!empty($app_type)) {
        $query_val[] = " `app`='" . $app_type . "'";
    }

    if (!empty($number) || $number == 0) {
        $query_val[] = " `number`='" . $number . "'";
    }

    if (!empty($query_val)) {
        $where = ' WHERE ' . implode(' AND', $query_val);
    }

    //Build Query
    $query = "SELECT * FROM `" . $table . "` $where ";
    
    $text_data_rows = get_query_data($query);    
    return $text_data_rows;
}
/**
 * Quick card history data
 */
function quickcard_history_data($user_id = 0, $start_date = 0, $end_date = 0){
    $Quickcard_data = array();
    //Set Table Name
    $table = "log";

    //Build Query
    $query = "SELECT * FROM `" . $table . "` WHERE `app` = 'Quick-Cards-OL' ";
    
    //Check and pass extra param
    if (( $start_date != 0 ) && ( $end_date != 0 )) {
        $query .= " AND `date` BETWEEN '" . $start_date . "' AND '" . $end_date . "' ";
    }

    if (!empty($user_id)) {
        $query .= " AND `id` = '" . $user_id . "' ";
    }    
    $Quickcard_data_rows = get_query_data($query);
    if (!empty($Quickcard_data_rows)) {
        foreach($Quickcard_data_rows as $Quickcard_data_row) {     

            $Quickcard_data_row['data'] = $Quickcard_data_row['data'];
            $data_value = explode('|', $Quickcard_data_row['data']);
            if (!empty($data_value[0])) {
                $Quickcard_data_row['percent_score'] = $data_value[0];
            }
            if (!empty($data_value[1])) {
                $Quickcard_data_row['incorrect'] = $data_value[1];
            }
            if (!empty($data_value[2])) {
                $cards_missed = explode('~', $data_value[2]);
                $Quickcard_data_row['cards_missed'] = $cards_missed;
            }

            $Quickcard_data[] = $Quickcard_data_row;
        }
    }
    return $Quickcard_data;
}
function get_ProPack_student_data($student_id, $pro_pack_type = '', $extra_param = array()) {
    $query_var = $final_array = array();
    if (!empty($student_id)) {
        $query_var[] = " id ='" . $student_id . "'";
    }
    if (isset($pro_pack_type) && trim($pro_pack_type) != '') {
        $query_var[] = " number ='" . $pro_pack_type . "'";
    }
    $query_var[] = " app ='PP-OL'";

    if (!empty($query_var)) {
        $where = 'WHERE ' . implode(' AND ', $query_var);
    }

    $query = "SELECT * FROM text  $where ORDER BY table_id DESC";
    $data = get_query_data($query);
    return $data;
}
function custom_student_date($start_date = ''){
    $custom_date = "2019-03-01";   /* Y-m-d format */
    if(!empty($start_date)){
        $start_date = date('Y-m-d', strtotime($start_date));
        if($custom_date <= $start_date){
            return true;
        }
    }    
    return false;
}

/**
 * Arcade color option
 */
$GLOBALS['arcade_color_options'] = array('255, 255, 254' => 'Flat White', '242, 241, 239' => 'Hard White', '255, 192, 203' => 'Flat Pink', '255, 0, 102' => 'Accessibyte Pink', '242, 38, 19' => 'Flat Red', '239, 58, 39' => 'Hard Red', '139, 0, 0' => 'Dark Red', '237, 111, 53' => 'Flat Orange', '255, 255, 0' => 'Flat Yellow', '204, 204, 0' => 'Hard Yellow', '46, 204, 113' => 'Flat Green', '0, 100, 0' => 'Hard Green', '0, 255, 153' => 'Flat Teal', '0, 0, 255' => 'Flat Blue', '255, 255, 248' => 'Hard Blue', '0, 0, 139' => 'Dark Blue', '102, 51, 153' => 'Flat Purple', '139, 0, 139' => 'Hard Purple', '12, 18, 2' => 'Flat Black', '0, 0, 1' => 'Hard Black', '105, 105, 105' => 'Flat Grey');

/**
 * Selection option
 */
$GLOBALS['selection_option'] = array('0' => 'Invert', '1' => 'Accessory Color');

/**
 * On/off value
 */
$GLOBALS['on_off_option'] = array(
    1 => 'On',
    0 => 'Off',
);

$GLOBALS['hand_style'] = array(
    0 => 'Off',
    1 => 'Solid',
    2 => 'Clear',
);
$GLOBALS['typio_keypress_options'] = $typio_keypress_options = array(4 => 'Read', 1 => 'Pop', 2 => 'Click', 3 => 'Theme');
$GLOBALS['arcade_wizard_tower_sfx_options'] = $arcade_wizard_tower_sfx_options = array(1 => 'Normal', 2 => 'Retro', 0 => 'Off');
$GLOBALS['arcade_music_volume_options'] = $arcade_music_volume_options = array(0 => 'Silent', 1 => 'Normal', 2 => 'Quite');
function get_setting_val($key_data,$key_name){
    
    if(isset($GLOBALS[$key_name][$key_data])){
        return $GLOBALS[$key_name][$key_data];
    }
    return $key_data;
}
function add_license_note_to_history($license_key, $user_id , $type , $user_type){

    if(empty($license_key)){
        $license_key = $_SESSION['User']['license'];
    }
    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT user_id,payment_id FROM pia_edd_licenses where license_key = '" . $license_key . "'");
    $data = mysqli_fetch_array($query);
    if (isset($data) && !empty($data) && isset($data['payment_id'])) {
        $payment_id = $data['payment_id'];
        $currenttime = date('Y-m-d H:i:s');
        $text = '';
        $date = date('Y-m-d');
        $time_date = gettimezonewiseDateTime($date);
        if($type == 'activate'){
            $text = "license activated.";
        } else if($type == 'deactive'){
            $text = "deleted.";
        }
        $note = $currenttime ." ".ucfirst($user_type)." ".$user_id . " ".$text;
        $user_id = $data['user_id'];
        mysqli_query($wcon, "INSERT INTO pia_comments SET `comment_post_ID` = '{$payment_id}',`comment_type` = 'edd_payment_note',`comment_author_email` = '',`comment_author_url` = '',`comment_author_IP` = '',`comment_author` = '',`comment_parent` = '0',`comment_approved` = '1',`comment_date_gmt` = '{$time_date}',`comment_content` = '{$note}',`user_id` = '{$user_id}', `comment_date` = '{$time_date}' ");
    }
    return true;
}

function getBrowser1() {

	$u_agent = $_SERVER['HTTP_USER_AGENT'];
  $bname = 'Unknown';
  $platform = 'Unknown';
  $version= "";

  //First get the platform?
  if (preg_match('/linux/i', $u_agent)) {
    $platform = 'linux';
  }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
    $platform = 'mac';
  }elseif (preg_match('/windows|win32/i', $u_agent)) {
    $platform = 'windows';
  }

  // Next get the name of the useragent yes seperately and for good reason
  if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
    $bname = 'Internet Explorer';
    $ub = "MSIE";
  }elseif(preg_match('/Firefox/i',$u_agent)){
    $bname = 'Mozilla Firefox';
    $ub = "Firefox";
  }elseif(preg_match('/OPR/i',$u_agent)){
    $bname = 'Opera';
    $ub = "Opera";
  }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edg/i',$u_agent)){
    $bname = 'Google Chrome';
    $ub = "Chrome";
  }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edg/i',$u_agent)){
    $bname = 'Apple Safari';
    $ub = "Safari";
  }elseif(preg_match('/Netscape/i',$u_agent)){
    $bname = 'Netscape';
    $ub = "Netscape";
  }elseif(preg_match('/Edg/i',$u_agent)){
    $bname = 'Edge';
    $ub = "Edg";
  }elseif(preg_match('/Trident/i',$u_agent)){
    $bname = 'Internet Explorer';
    $ub = "MSIE";
  }

  // finally get the correct version number
  $known = array('Version', $ub, 'other');
  $pattern = '#(?<browser>' . join('|', $known) .
')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
  if (!preg_match_all($pattern, $u_agent, $matches)) {
    // we have no matching number just continue
  }
  // see how many we have
  $i = count($matches['browser']);
  if ($i != 1) {
    //we will have two since we are not using 'other' argument yet
    //see if version is before or after the name
    if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
        $version= $matches['version'][0];
    }else {
        $version= $matches['version'][1];
    }
  }else {
    $version= $matches['version'][0];
  }

  // check if we have a number
  if ($version==null || $version=="") {$version="?";}

  return array(
    'userAgent' => $u_agent,
    'name'      => $bname,
    'version'   => $version,
    'platform'  => $platform,
    'pattern'    => $pattern
  );
}


function checkusersettings($user_id){
     $OS = getBrowser1();

    $query = query("select * from settings where id = '" . $user_id . "' AND item ='13'");
    $result = fetch($query);

  if (!empty($query->num_rows)) {
                while ($row = mysqli_fetch_assoc($query)) {
                   print_r($row);
                }
}
$data2 = array(
            'id' => $user_id,
            'item' => 13,
            'variable' => $OS['platform'],
           
        );
if(!empty($result)){
update_query('settings', 'id = "' . $user_id . '" and item = 13', $data2 );
}else{

  query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '13', `variable` = '{$OS['platform']}' ");

}
$check_item2 = query("select * from settings where id = '" . $user_id . "' AND item ='12'");
    $result1 = fetch($check_item2);
$data = array(
            'id' => $user_id,
            'item' => 12,
            'variable' => $OS['version'],
           
        );
if(!empty($result1)){
update_query('settings', 'id = "' . $user_id . '" and item = 12', $data );
}else{

  query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '12', `variable` = '{$OS['version']}' ");

}
$check_item3 = query("select * from settings where id = '" . $user_id . "' AND item ='8'");
    $result2 = fetch($check_item3);
$data1 = array(
            'id' => $user_id,
            'item' => 8,
            'variable' => $OS['name'],
           
        );
if(!empty($result2)){
update_query('settings', 'id = "' . $user_id . '" and item = 8', $data1 );
}else{

  query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '8', `variable` = '{$OS['name']}' ");

}
$check_item4 = query("select * from settings where id = '" . $user_id . "' AND item ='7'");
    $result3 = fetch($check_item4);
$data2 = array(
            'id' => $user_id,
            'item' => 8,
            'variable' => $_SESSION['user']['resolution'],
           
        );
if(!empty($result3)){
update_query('settings', 'id = "' . $user_id . '" and item = 7', $data2 );
}else{

  query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '7', `variable` = '{$_SESSION['user']['resolution']}' ");

}

return 1;

}



if (isset($_POST['UID']) && $_POST['type'] == 'update_wait') {
$sql = query('SELECT * FROM user WHERE Login_url="' . $_POST['UID'] . '"');
    $data = fetch($sql);
$exp = 'No';
if(!empty($data)){
 update_query('user', 'id = "' . $data['id'] . '"  ',array('wait_page' => $_POST["wait_val"]));

$reponse['message'] = 'updated success!!';
$reponse['error'] = 1 ;

}else{


$reponse['message'] = 'something is wrong!!';
$reponse['error'] = 0 ;


}
echo json_encode($reponse);

}


?>