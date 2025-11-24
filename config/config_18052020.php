<?php

session_start();
ob_start();
define('WEB_PATH', 'https://www.accessibyte.com/');
define('ADMIN_URL', WEB_PATH . 'online/');
define('ADMIN_Text', 'Accessibyte');
//define('STUDENT_PANEL', WEB_PATH.'accessibyte-online-maintenance/');
//define('STUDENT_PANEL', WEB_PATH.'apps/typio1259b/');
define('STUDENT_PANEL', WEB_PATH . 'apps/maindir/');
define('STUDENT_PANEL_BDL', WEB_PATH . 'apps/maindir/');
define('ADMIN_DIR', dirname(__DIR__));
define('LICENSE_UPDATE_FILE_PATH', WEB_PATH . 'online/update-license.php');
define('WP_LICENSE_UPDATE_LINK', 'https://www.accessibyte.com/update-license');

// defualt timezone of chicago
date_default_timezone_set('CST6CDT');


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
function getUserLog($userid = FALSE, $limit = '', $order = 'DESC', $app_type = false, $teacher_code = false) {

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

    $query_var[] = " user.role ='student'";

    if (!empty($query_var)) {
        $where = 'WHERE ' . implode(' AND ', $query_var);
    }

    //Check Log limit set
    if (!empty($limit)) {
        $limit = ' LIMIT ' . $limit;
    }

    //Get log data to table
    $query = query("SELECT * FROM log INNER JOIN user ON log.id = user.id $where ORDER BY date DESC $limit ");

    //get rows and store on blank data of log
    while ($row = mysqli_fetch_array($query)) {

        $row['time_ago'] = getTimeDiff($row['date']);
        $get_log[] = $row;
    }

    return $get_log;
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
function register_user($user_type, $fname, $lname, $username, $email, $pass, $org, $license, $age = '', $grade = '', $teacher_code, $redirect = true) {
    global $con;
    $techerCode = generateRandomString();

    if ($user_type == 'student') {
        $techerCode = $teacher_code;
        if ($fname == '') {
            $fname = null;
        }
        if ($lname == '') {
            $lname = null;
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
                `grade`     = '{$grade}'
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

        $result = activate_license($license, $user_id, $redirect);

        get_license_details($license, $user_id);

        if ($redirect && $result) {
            send_user_registration_email($username);
            $_SESSION['error']['message'] = "Registration Successful!";
            $_SESSION['error']['color'] = "success";
            header("Location:" . ADMIN_URL . 'login');
            exit;
        } elseif (!$redirect && $result) {
            send_user_registration_email($username);
            $_SESSION['error']['message'] = "Your student account created successfully.";
            $_SESSION['error']['color'] = "success";
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
    $data = "SELECT * FROM log WHERE app ='Overview-OL' and log.id ='" . $userid . "' AND log.date >= '" . $startdate . "' AND log.date <= '" . $enddate . "' GROUP BY data ORDER BY date DESC";

    $data_rows = mysqli_query($con, $data);
    $total_row = 0;
    $html = "";

    while ($row = mysqli_fetch_assoc($data_rows)) {
        //$data_count = count(explode("|", $row['data']));  
        $html .= "<tr><td> " . $row['file'] . " </td><td> " . date('m/d/Y', strtotime($row['date'])) . " </td>
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
function checkLicenseStatus($userid, $itemID='') {

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
    'Google US English' => 'Google US English',
    'Google UK English' => 'Google UK English',
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
    $query = query("SELECT user.license,user.email,user.username,user.id,user.firstname,user.lastname FROM user where user.username='$username'");
    $data = fetch($query);
    $email = base64_decode($data['email']);
    $variables = array();
//    $encryptToken = rawurlencode(encryptIt($data['token']));
    $variables['firstname'] = base64_decode($data['firstname']);
    $variables['lastname'] = base64_decode($data['lastname']);
    $variables['username'] = $data['username'];
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
    $query = query("SELECT user.email,user.token,user.id,user.firstname,user.lastname FROM user where user.username='$username'");
    $data = fetch($query);
    $email = base64_decode($data['email']);
    $variables = array();
    $encryptToken = rawurlencode(encryptIt($data['token']));
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
    $decToken = decryptIt($token);
    $query = query("SELECT user.id FROM user where user.token='$decToken'");
    $user = fetch($query);

    if (!empty($user) && !empty($user['id'])) {
        $userid = $user['id'];
        //$encPass = crypt($password);
        $encPass = md5($password);
        $newToken = getToken(30);
        //Good to update token after password reset for security purpose
        if (query("UPDATE user set `password`='" . $encPass . "', `token`='" . $newToken . "'  WHERE id=$userid")) {
            $_SESSION['error']['message'] = "Password was changed successfully.";
            $_SESSION['error']['color'] = 'success';

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

                            ( '" . $user_id . "', '18', '12, 18, 22' ),
                            ( '" . $user_id . "', '118', '12, 18, 22' ),
                            ( '" . $user_id . "', '218', '12, 18, 22' ),
                            ( '" . $user_id . "', '318', '12, 18, 22' ),
                            ( '" . $user_id . "', '418', '12, 18, 22' ),

                            ( '" . $user_id . "', '19', '255, 0, 120' ),
                            ( '" . $user_id . "', '119', '255, 0, 120' ),
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
                            ( '" . $user_id . "', '138', '0' ),
                            ( '" . $user_id . "', '139', '0' ),

                            ( '" . $user_id . "', '141', '600' ),
                            ( '" . $user_id . "', '241', '600' ),
                            ( '" . $user_id . "', '341', '600' ),
                            ( '" . $user_id . "', '441', '600' ),

                            ( '" . $user_id . "', '143', '0' ),
                            ( '" . $user_id . "', '243', '0' ),
                            ( '" . $user_id . "', '343', '0' ),
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
                            ( '" . $user_id . "', 'Arcade-OL', '2', 'Animals', 'Cat`Mouse`Frog`Lion`Zebra`Monkey`Cow`Bird`Hippo`Moose'),
                            ( '" . $user_id . "', 'Arcade-OL', '2', 'Fruit', 'Banana`Grape`Apple`Kiwi`Orange`Lemon`Pineapple`Mango`Strawberry`Blueberry'),
                            ( '" . $user_id . "', 'Arcade-OL', '2','Ocean', 'Water`Sand`Starfish`Fish`Seaweed`Waves`Boat`Scuba`Octopus`Reef' ),
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
            $get_data = query("select * from user where user.username = '" . $username . "' AND user.password='" . $password . "'");
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

                if ($status->success == "true") {

                    // Update License in User table
                    update_query('user', 'id = "' . $result['id'] . '" ', array('license' => $license));

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

                    $_SESSION['error']['message'] = "License is updated successfully.";
                    
                    header("Location: " . ADMIN_URL . 'login');
                } else {
                    $_SESSION['error']['message'] = "License key is invalid.";
                }
            }
        } else {
            $_SESSION['error']['message'] = "You can't renew using a trial license.";
        }
    } else {
        $_SESSION['error']['message'] = "License key not found. Please check your License key.";
    }
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
?>