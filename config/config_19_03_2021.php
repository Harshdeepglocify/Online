<?php

session_start();
ob_start();
$webapth = "https://www.accessibyte.com/";

    if($_SERVER['HTTP_HOST'] == "www.accessibyte.com"){
        $webpath = "https://www.accessibyte.com/";
    }
    else if($_SERVER['HTTP_HOST'] == "online.accessibyte.com"){
        $webpath = "https://online.accessibyte.com/";
    }


define('WEB_PATH', $webpath);
define('WP_URL', 'https://www.accessibyte.com/');
if($_SERVER['HTTP_HOST'] == "www.accessibyte.com"){
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
define('ADMIN_DIR', dirname(__DIR__));
define('LICENSE_UPDATE_FILE_PATH', WEB_PATH . 'online/update-license.php');
define('WP_LICENSE_UPDATE_LINK', 'https://www.accessibyte.com/update-license');

// defualt timezone of chicago
date_default_timezone_set('CST6CDT');


define('WP_HOST', 'localhost');
define('WP_USER', 'atacadem_wor1');
define('WP_PASS', ')mj0D]EPf3h6');
define('WP_DB', 'atacadem_wor1');

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
$DbUser = "atacadem_squall";
$DbPassword = "!QRa)]Fr1~B9";
$Database = "atacadem_gardenDB";

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
function getUserLog($userid = FALSE, $limit = '', $order = 'DESC', $app_type = false, $teacher_code = false,$start_date = '',$end_date = '') {

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
    $datetime = new DateTime($datetime1);
    $la_time = new DateTimeZone($timezoneName);
    $datetime->setTimezone($la_time);
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
function register_user($user_type, $fname, $lname, $username, $email, $pass,  $org = '', $license, $age = '', $grade = '', $teacher_code, $redirect = true,$seatlimit='0',$teachername = '') {
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

        if ($val[0] == "TYO") {
            query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '5', `variable` = '{$license}' ");
        } else if ($val[0] == "BDL") {
            query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '5', `variable` = '{$license}' ");
        } else if ($val[0] == "TCH") {
            query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '5', `variable` = '{$license}' ");
        } else if ($val[0] == "TCHP") {
            query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '5', `variable` = '{$license}' ");
        } else if ($val[0] == "PRO") {
            query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '6', `variable` = '{$license}' ");
        } else if ($val[0] == "AAO") {
            query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '7', `variable` = '{$license}' ");
        } else if ($val[0] == "QCO") {
            query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '8', `variable` = '{$license}' ");
        }

        //Adding Default settings
        AddUserDefault_ExtaSettings($user_id);

        // Adding Default text for student only
        if ($user_type == 'student') {
            AddUserDefault_ExtaText($user_id);
        }
		$licenseExist = check_license_available_not($license, $user_type);  // check available license or not
        if (!empty($licenseExist)) {
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
			if ($redirect && $result) {
				send_user_registration_email($username);
				$_SESSION['error']['message'] = "Registration Successful!";
				$_SESSION['error']['color'] = "success";
				header("Location:" . ADMIN_URL . 'login');
				exit;
			} elseif (!$redirect && $result && $user_type == 'teacher') {
                send_user_registration_email($username);
                $_SESSION['error']['message'] = "Your teacher account created successfully.";
                $_SESSION['error']['color'] = "success";
            } elseif (!$redirect && $result) {
				send_user_registration_email($username);
				$_SESSION['error']['message'] = "Your student account created successfully.";
				$_SESSION['error']['color'] = "success";
			}
		} else {
            $_SESSION['error']['message'] = "No enough licnese key exist.";
            $_SESSION['error']['color'] = "danger";
            header("Location:" . ADMIN_URL . 'login');
            exit;
        }
    } else {
        $_SESSION['error']['message'] = "Could not save data at the moment";
        $_SESSION['error']['color'] = "danger";
        if ($redirect) {
            header("Location:" . ADMIN_URL . 'register.php');
            exit;
        }
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
        $item = "9800";
    elseif ($val[0] == "BDL")
        $item = "4184";
    elseif ($val[0] == "TYO")
        $item = "3584";
    else if ($val[0] == "TCH")
        $item = "4111";
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
    $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $lincense;
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
        return TRUE;
    } else if (isset($status) && !empty($status->error) && $status->error == "expired") {
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
        $item = "9800";
    else if ($val[0] == "BDL")
        $item = "4184";
    elseif ($val[0] == "TYO")
        $item = "3584";
    else if ($val[0] == "TY")
        $item = "192";
    else if ($val[0] == "TCH")
        $item = "4111";
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

    $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $lincense;
	
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
                'license' => $lincense
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
    $data = "SELECT * FROM log WHERE app ='Overview-OL' and log.id ='" . $userid . "' AND log.date >= '" . $startdate . "' AND log.date <= '" . $enddate . "' GROUP BY data,file ORDER BY date DESC";

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
            $item = "9800";
        else if ($itemName == "BDL")
            $item = "4184";
        elseif ($itemName == "TYO")
            $item = "3584";

        elseif ($itemName == "TY")
            $item = "192";

        elseif ($itemName == "QCO")
            $item = "4182";
        else if ($itemName == "TCH")
            $item = "4111";
        else if ($itemName == "TCHP") /* Add new liecenece for teacher dashboard */
            $item = "30398";
        elseif ($itemName == "AA")
            $item = "905";

        elseif ($itemName == "WW")
            $item = "207";

        $data['variable'] = trim($data['variable']);
        $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $data['variable'];

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
            $item = "9800";
        else if ($itemName == "BDL")
            $item = "4184";
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

        $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $license_key;

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
$font_size_options = array(
    20 => 'Small',
    100 => 'Medium',
    200 => 'Large',
);

$font_style_options = array(
    'Roboto' => 'Regular',
    'Roboto Condensed' => 'Condensed',
    'Roboto Mono' => 'Monospaced',
    'Roboto Slab' => 'Serif',
);

$voice_options = array(
    'Off' => 'Off',
    'Default' =>"Default",
);

$voice_rate_options = array(
    5 => 'Slow',
    10 => 'Medium',
    13 => 'Fast',
);

$voice_pitch_options = array(
    5 => 'Low',
    9 => 'Medium',
    12 => 'High',
);

$color_options = array(
    '255, 255, 254' => 'White',
    '242, 241, 239' => 'Flat White',
    '105, 105, 105' => 'Grey',
    '0, 0, 1' => 'Black',
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
        $query_val[] = " `role`='" . $arg['username'] . "'";
    }

    //Get data based on user id
    if (!empty($arg['user_id'])) {
        $query_val[] = " `id`='" . $arg['user_id'] . "'";
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

    mail($email, "Registration confirmation email", $template, $header);

//    send_email($email, 'Registration confirmation email', $template,1);
}

function send_token_to_reset_password($username) {
    $query = query("SELECT user.email,user.token,user.id,user.firstname,user.lastname FROM user where user.username='$username' or user.email='" . base64_encode($username) . "' or user.username='" . base64_encode($username) . "'");
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
function AddUserDefault_ExtaSettings($user_id) {

    $query = query("INSERT INTO settings (id, item, variable) VALUES 
                            ( '" . $user_id . "', '15', '100' ),
                            ( '" . $user_id . "', '115', '100' ),
                            ( '" . $user_id . "', '215', '100' ),
                            ( '" . $user_id . "', '315', '100' ),
                            ( '" . $user_id . "', '415', '100' ),

                            ( '" . $user_id . "', '16', '255, 255, 254' ),
                            ( '" . $user_id . "', '116', '255, 255, 254' ),
                            ( '" . $user_id . "', '216', '255, 255, 254' ),
                            ( '" . $user_id . "', '316', '255, 255, 254' ),
                            ( '" . $user_id . "', '416', '255, 255, 254' ),

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
                            ( '" . $user_id . "', '129', '20' ),
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
                            ( '" . $user_id . "', '149', '255, 255, 254' ),
							( '" . $user_id . "', '150', 'QWERTY' ),
							( '" . $user_id . "', '151', '2' ),
                            ( '" . $user_id . "', '243', '0' ),
                            ( '" . $user_id . "', '343', '0' ),
                            ( '" . $user_id . "', '142', '1' ),
                            ( '" . $user_id . "', '443', '0' ) ");
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
        elseif ($val[0] == "TYO")
            $item = "Typio";
        else if ($val[0] == "TCH")
            $item = "Teacher Dashboard";
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
        $item = "9800";
    else if ($val[0] == "BDL")
        $item = "4184";
    elseif ($val[0] == "TYO")
        $item = "3584";
    else if ($val[0] == "TY")
        $item = "192";
    else if ($val[0] == "TCH")
        $item = "4111";
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

    $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

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
        $item = "9800";
    else if ($val[0] == "BDL")
        $item = "4184";
    elseif ($val[0] == "TYO")
        $item = "3584";
    else if ($val[0] == "TY")
        $item = "192";
    else if ($val[0] == "TCH")
        $item = "4111";
    else if ($val[0] == "TCHP") /* Add new liecenece for teacher dashboard */
        $item = "30398";
    else if ($val[0] == "QCO")
        $item = "4182";
    else if ($val[0] == "AA")
        $item = "905";
    else if ($val[0] == "WW")
        $item = "207";

    $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

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
                $item = "9800";
            else if ($val[0] == "BDL")
                $item = "4184";
            elseif ($val[0] == "TYO")
                $item = "3584";
            else if ($val[0] == "TY")
                $item = "192";
            else if ($val[0] == "TCH")
                $item = "4111";
            else if ($val[0] == "TCHP") /* Add new liecenece for teacher dashboard */
                $item = "30398";
            else if ($val[0] == "QCO")
                $item = "4182";
            else if ($val[0] == "AA")
                $item = "905";
            else if ($val[0] == "WW")
                $item = "207";

            $url = WEB_PATH . "?edd_action=deactivate_license&item_id=" . $item . "&license=" . $license;

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
        $item = "9800";
        $license_item = 6;
    } elseif ($val[0] == "BDL") {
        $item = "4184";
        $license_item = 5;
    } elseif ($val[0] == "TYO") {
        $item = "3584";
        $license_item = 5;
    } else if ($val[0] == "TCH") {
        $item = "4111";
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
    $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

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
                if ($result['role'] == 'teacher' && ($val[0] == "TCH" || $val[0] == "TCHP")) {
                    $is_valid = 1;
                }
                if ($result['role'] != 'teacher' && $val[0] != "TCH" && $val[0] != "TCHP") {
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
									$_SESSION['error']['message'] = "Not enough seats on this license. You have have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
									$flag = 0;
								}
							} else {
								$_SESSION['error']['message'] = "You have have 0 activation left on this license.";
								$flag = 0;
							}
						}
					}
					
					if ($flag) {
						$url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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
							
							if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP")) {

								if (!empty($teacher_students)) {
									foreach ($teacher_students as $stud) {
										// Update License in Settings table
										update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
										$url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
										
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
        $item = "9800";
        $license_item = 6;
    } elseif ($val[0] == "BDL") {
        $item = "4184";
        $license_item = 5;
    } elseif ($val[0] == "TYO") {
        $item = "3584";
        $license_item = 5;
    } else if ($val[0] == "TCH") {
        $item = "4111";
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
    $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

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
				
				
                $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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

                    if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP")) {

                        $args = array(
                            'teacher_code' => $result['teacher'],
                            'role' => 'student',
                        );
                        $teacher_students = get_users($args);

                        if (!empty($teacher_students)) {
                            foreach ($teacher_students as $stud) {
                                // Update License in Settings table
                                update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
								$url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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

                    $_SESSION['error']['message'] = "License is updated successfully.";

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
        $item = "9800";
    else if ($val[0] == "BDL")
        $item = "4184";
    elseif ($val[0] == "TYO")
        $item = "3584";
    else if ($val[0] == "TY")
        $item = "192";
    else if ($val[0] == "TCH")
        $item = "4111";
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

    $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

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
                $hours = floor($total / 60);;            
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
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
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
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
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
                mysqli_query($wcon, "update pia_edd_licensemeta set meta_value = '" . $updateSerializeArr . "'  where license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
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
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
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
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
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
                mysqli_query($wcon, "update pia_edd_licensemeta set meta_value = '" . $updateSerializeArr . "'  where license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
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
   $data = "SELECT * FROM log WHERE app ='Overview-OL' and log.id ='" . $userid . "' AND log.date >= '" . $startdate . "' AND log.date <= '" . $enddate . "' GROUP BY data ORDER BY date DESC";
  

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
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
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
                mysqli_query($wcon, "update pia_edd_licensemeta set meta_value = '" . $updateSerializeArr . "'  where license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
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

 function update_no_student_license_using_teacher($license_key, $type = 'student',$teacher_student_count =0) {


    $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
    $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licenses where license_key = '" . $license_key . "'");
    $data = mysqli_fetch_array($query);

    if (isset($data) && !empty($data) && isset($data['id'])) {
        $query = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
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
                    if($teacher_student_count > 0 ){
                      $no_student_use =$no_student_use -  $teacher_student_count;
                    }
                    

                    
                }
                $licenseUpArr = array(
                    'no_teacher' => $licenseArr['no_teacher'],
                    'no_student' => $licenseArr['no_student'],
                    'no_teacher_use' => $no_teacher_use,
                    'no_student_use' => $no_student_use,
                );
                $updateSerializeArr = serialize($licenseUpArr);
                mysqli_query($wcon, "update pia_edd_licensemeta set meta_value = '" . $updateSerializeArr . "'  where license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
            }
        }
        return array();
    } else {
        return true;
    }
}
/**
 * update license for multiple teacher from my account
 */
function update_license_multi_teacher_old_fun($username, $password, $license){

    $license_item = '';
    $item = '';
    $val = explode("-", trim($license));
    if ($val[0] == "PRO") {
        $item = "9800";
        $license_item = 6;
    } elseif ($val[0] == "BDL") {
        $item = "4184";
        $license_item = 5;
    } elseif ($val[0] == "TYO") {
        $item = "3584";
        $license_item = 5;
    } else if ($val[0] == "TCH") {
        $item = "4111";
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
    $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

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
                if ($result['role'] == 'teacher' && ($val[0] == "TCH" || $val[0] == "TCHP")) {
                    $is_valid = 1;
                    if(!empty($result['is_admin']) || $result['is_admin'] == '1'){
                        $is_multi_teacher = 1;
                        $allTeacher = getAllTeacherWithoutAdmin($result['license']);   
                    }
                }
                if ($result['role'] != 'teacher' && $val[0] != "TCH" && $val[0] != "TCHP") {
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
                                        $_SESSION['error']['message'] = "Not enough teacher seats on this license. You have have " . $teacher_left_new . " seats available. Please delete " . $diffTeacher . " teacher before applying the license or purchase additional license seats.";
                                        $flag = 0;
                                    } else {
                                        $left = $student_left_new;
                                        $total = $studentsCount;
                                        $seat_avail = $left;
                                        $diffoflimit = $total - $left;
                                        if ($left > 0) {
                                            if ($left < $total) {
                                                $_SESSION['error']['message'] = "Not enough seats on this license. You have have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
                                                $flag = 0;
                                            }
                                        } else {
                                            $_SESSION['error']['message'] = "You have have 0 activation left on this license.";
                                            $flag = 0;
                                        }
                                    }
                                } else {                            
                                    $_SESSION['error']['message'] = "You have have 0 activation left on this license.";
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
                                        $_SESSION['error']['message'] = "Not enough seats on this license. You have have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
                                        $flag = 0;
                                    }
                                } else {
                                    $_SESSION['error']['message'] = "You have have 0 activation left on this license.";
                                    $flag = 0;
                                }
                                                                
                            }
                        }
                    }
                    if ($flag) {
                        $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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
                                if(!empty($is_multi_teacher)){
                                    foreach($allTeacher as $teacherVal){
                                        update_query('user', 'teacher = "' . $teacherVal['teacher'] . '" ', array('license' => $license));  // update all student hase same license
                                    }   
                                } else {
                                    update_query('user', 'teacher = "' . $result['teacher'] . '" ', array('license' => $license));  // update all student hase same license
                                }
                            }
                            
                            if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP")) {
                            
                                if (!empty($is_multi_teacher) && !empty($allTeacher)) {
                                    foreach($allTeacher as $tval){
                                        // Update License in Settings table
                                        update_query('settings', 'id = "' . $tval['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));

                                        /*Teacher license activation */
                                        $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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
                                            $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;

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
                                if (empty($is_multi_teacher) && !empty($teacher_students)) {   /* For single teacher */
                                    foreach ($teacher_students as $stud) {
                                        // Update License in Settings table
                                        update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                                        $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;

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
                                    /*Update license nos'of student and teacher data */
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
                                update_query('users_licenses', 'license = "' . $result['license'] . '"', array('license' => $license));  // update all same license of student
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

function update_license_my_account_old($username, $license) {

    $license_item = '';
    $item = '';
    $val = explode("-", trim($license));
    if ($val[0] == "PRO") {
        $item = "9800";
        $license_item = 6;
    } elseif ($val[0] == "BDL") {
        $item = "4184";
        $license_item = 5;
    } elseif ($val[0] == "TYO") {
        $item = "3584";
        $license_item = 5;
    } else if ($val[0] == "TCH") {
        $item = "4111";
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
    $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

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
                    $allTeacher = getAllTeacherWithoutAdmin($result['license']);   
                }
                if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP")) {

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
                                $_SESSION['error']['message'] = "Not enough teacher seats on this license. You have have " . $teacher_left_new . " seats available. Please delete " . $diffTeacher . " teachers before applying the license or purchase additional license seats.";
                                $flag = 0;
                            } else {
                                $left = $student_left_new;
                                $total = $studentsCount;
                                $seat_avail = $left;
                                $diffoflimit = $total - $left;
                                if ($left > 0) {
                                    if ($left < $total) {
                                        $_SESSION['error']['message'] = "Not enough seats on this license. You have have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
                                        $flag = 0;
                                    }
                                } else {
                                    $_SESSION['error']['message'] = "You have have 0 activation left on this license.";
                                    $flag = 0;
                                }
                            }
                        } else {                            
                            $_SESSION['error']['message'] = "You have have 0 activation left on this license.";
                            $flag = 0;
                            
                        }
                        //echo $flag;die;
                        if($flag){
                            $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_HEADER, false);
                            $result_active = curl_exec($ch);
                            curl_close($ch);

                            $mainstatus = json_decode($result_active);
                            if ($mainstatus->success == "true") {
                                // Update License in User table
                                update_query('user', 'id = "' . $result['id'] . '" ', array('license' => $license));
                                foreach($allTeacher as $tval){
                                    update_query('user', 'teacher = "' . $tval['teacher'] . '" ', array('license' => $license));  // update all student hase same license
                                    // Update License in Settings table
                                    update_query('settings', 'id = "' . $tval['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));

                                    /*Teacher license activation */
                                    $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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
                                        $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;

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
                            } else {
                                $flag = 0;
                                $_SESSION['error']['message'] = "License key is invalid.";
                            }
                        }
                    } else if(empty($is_multi_teacher)){ 
                        $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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

                            if (!empty($teacher_students)) {
                                foreach ($teacher_students as $stud) {
                                    // Update License in Settings table
                                    update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                                    $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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
function update_license_multi_teacher($username, $password, $license){

    $license_item = '';
    $item = '';
    $val = explode("-", trim($license));
    if ($val[0] == "PRO") {
        $item = "9800";
        $license_item = 6;
    } elseif ($val[0] == "BDL") {
        $item = "4184";
        $license_item = 5;
    } elseif ($val[0] == "TYO") {
        $item = "3584";
        $license_item = 5;
    } else if ($val[0] == "TCH") {
        $item = "4111";
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
    $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

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
                if ($result['role'] == 'teacher' && ($val[0] == "TCH" || $val[0] == "TCHP")) {
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
                if ($result['role'] != 'teacher' && $val[0] != "TCH" && $val[0] != "TCHP") {
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
                                        $_SESSION['error']['message'] = "Not enough teacher seats on this license. You have have " . $teacher_left_new . " seats available. Please delete " . $diffTeacher . " teacher before applying the license or purchase additional license seats.";
                                        $flag = 0;
                                    } else {
                                        $left = $student_left_new;
                                        $total = $studentsCount;
                                        $seat_avail = $left;
                                        $diffoflimit = $total - $left;
                                        if ($left > 0) {
                                            if ($left < $total) {
                                                $_SESSION['error']['message'] = "Not enough seats on this license. You have have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
                                                $flag = 0;
                                            }
                                        } else {
                                            $_SESSION['error']['message'] = "You have have 0 activation left on this license.";
                                            $flag = 0;
                                        }
                                    }
                                } else {                            
                                    $_SESSION['error']['message'] = "You have have 0 activation left on this license.";
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
                                        $_SESSION['error']['message'] = "Not enough seats on this license. You have have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
                                        $flag = 0;
                                    }
                                } else {
                                    $_SESSION['error']['message'] = "You have have 0 activation left on this license.";
                                    $flag = 0;
                                }
                                                                
                            }
                        }
                    }
                    // echo "<pre>";print_r($teacher_students);
                    // echo $_SESSION['error']['message'];
                    // echo $flag;die;
                    if ($flag) {
                        if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP")) {
                            
                            if (!empty($is_multi_teacher) && !empty($allTeacher)) {
                                // Update License in User table
                                update_query('user', 'id = "' . $result['id'] . '" ', array('license' => $license));
                                foreach($allTeacher as $tval){
                                    update_query('user', 'teacher = "' . $tval['teacher'] . '" ', array('license' => $license));  // update all student hase same license
                                    // Update License in Settings table
                                    update_query('settings', 'id = "' . $tval['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));

                                    /*Teacher license activation */
                                    $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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
                                        $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;

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

                                header("Location: " . ADMIN_URL . 'login');
                                exit;
                            }    
                        }
                        if(empty($is_multi_teacher)){
                            $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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
                                
                                if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP")) {
                                    $noofStudent = 0;
                                    if (empty($is_multi_teacher) && !empty($teacher_students)) {   /* For single teacher */
										$noofStudent = count($teacher_students);
                                        foreach ($teacher_students as $stud) {
                                            // Update License in Settings table
                                            update_query('settings', 'id = "' . $stud['id'] . '" AND item = "' . $license_item . '" ', array('variable' => $license));
                                            $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;

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

                                header("Location: " . ADMIN_URL . 'login');
                                exit;
                            } else {
                                $_SESSION['error']['message'] = "License key is invalid.";
                            }
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
function update_license_my_account($username, $license) {

    $license_item = '';
    $item = '';
    $val = explode("-", trim($license));
    if ($val[0] == "PRO") {
        $item = "9800";
        $license_item = 6;
    } elseif ($val[0] == "BDL") {
        $item = "4184";
        $license_item = 5;
    } elseif ($val[0] == "TYO") {
        $item = "3584";
        $license_item = 5;
    } else if ($val[0] == "TCH") {
        $item = "4111";
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
    $url = WEB_PATH . "?edd_action=check_license&item_id=" . $item . "&license=" . $license;

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
                if (!empty($license_item) && $license_item == 5 && ($val[0] == "TCH" || $val[0] == "TCHP")) {

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
                                $_SESSION['error']['message'] = "Not enough teacher seats on this license. You have have " . $teacher_left_new . " seats available. Please delete " . $diffTeacher . " teachers before applying the license or purchase additional license seats.";
                                $flag = 0;
                            } else {
                                $left = $student_left_new;
                                $total = $studentsCount;
                                $seat_avail = $left;
                                $diffoflimit = $total - $left;
                                if ($left > 0) {
                                    if ($left < $total) {
                                        $_SESSION['error']['message'] = "Not enough seats on this license. You have have " . $seat_avail . " seats available. Please delete " . $diffoflimit . " students before applying the license or purchase additional license seats.";
                                        $flag = 0;
                                    }
                                } else {
                                    $_SESSION['error']['message'] = "You have have 0 activation left on this license.";
                                    $flag = 0;
                                }
                            }
                        } else {                            
                            $_SESSION['error']['message'] = "You have have 0 activation left on this license.";
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
                                $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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
                                    $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;

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
                        $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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
                                    $url = WEB_PATH . "?edd_action=activate_license&item_id=" . $item . "&license=" . $license;
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
    if(!empty($_SESSION['User']['is_expired'])){ 
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
?>