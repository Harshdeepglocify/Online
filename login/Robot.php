<?php

if (!defined('ADMIN_URL')) {

    include "../config/config.php";
	include '../vendor/autoload.php';
	
}
use UAParser\Parser;
/*$parser = Parser::create(); // loads default regexes
$result = $parser->parse($_SERVER['HTTP_USER_AGENT']);*/
/**/
function getUserLoginInfo($user_id = 0) {
    $uaString = $_SERVER['HTTP_USER_AGENT'] ?? "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/140.0.0.0 Safari/537.36";
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $parser = Parser::create();
    $result = $parser->parse($uaString);

    // CPU architecture
   // CPU architecture detection
	$cpuArch = "unknown";

	$uaLower = strtolower($uaString);

	if (strpos($uaLower, "x86_64") !== false || strpos($uaLower, "win64") !== false || strpos($uaLower, "x64") !== false) {
		$cpuArch = "amd64";
	} elseif (strpos($uaLower, "arm") !== false || strpos($uaLower, "aarch64") !== false) {
		$cpuArch = "arm";
	} elseif (strpos($uaLower, "i386") !== false || strpos($uaLower, "i686") !== false) {
		$cpuArch = "x86";
	} elseif (strpos($uaLower, "ppc") !== false) {
		$cpuArch = "powerpc";
	} else {
		// fallback: detect mobile vs desktop
		if (stripos($uaLower,"android") !== false || stripos($uaLower,"iphone") !== false || stripos($uaLower,"ipad") !== false) {
			$cpuArch = "arm";
		} else {
			$cpuArch = "unknown";
		}
	}


    // Engine
    $engineName = "Other";
    if (stripos($uaString,"AppleWebKit")!==false) {
        $engineName = ($result->ua->family==="Chrome" || $result->ua->family==="Edge") ? "Blink" : "WebKit";
    } elseif (stripos($uaString,"Gecko")!==false) {
        $engineName="Gecko";
    }

    // Device type, vendor, model
    $deviceType="Desktop";
    $deviceVendor=null;
    $deviceModel=null;

    if (!empty($result->device->family) && strtolower($result->device->family)!=="other") {
        $deviceType = $result->device->family;
        $deviceVendor = $result->device->brand ?? "Unknown";
        $deviceModel = $result->device->model ?? "Unknown";
    } elseif (stripos($uaString,"Mobile")!==false || stripos($uaString,"iPhone")!==false || stripos($uaString,"Android")!==false) {
        $deviceType="Mobile";
        $deviceVendor = $result->device->brand ?? "Generic";
        $deviceModel = $result->device->model ?? "Generic";
    } else {
        // Desktop fallback
        if (stripos($uaString,"Macintosh")!==false) $deviceVendor="Apple";
        elseif (stripos($uaString,"Windows")!==false) $deviceVendor="PC";
        elseif (stripos($uaString,"Linux")!==false) $deviceVendor="PC";
        else $deviceVendor="PC";
        $deviceModel="Desktop";
    }

    // OS version mapping
   

	$osFamily  = $result->os->family;
	$osVersion = $result->os->toVersion() ?: "unknown";
	
	//$result->os->family
	// Handle Windows special cases
	if ($osFamily == "Windows") {
		if (preg_match('/Windows NT 10.0/', $uaString)) $osVersion = "10";
		elseif (preg_match('/Windows NT 6.3/', $uaString)) $osVersion = "8.1";
		elseif (preg_match('/Windows NT 6.1/', $uaString)) $osVersion = "7";
	}

	// Handle Android
	elseif ($osFamily == "Android") {
		$matches  = [];
		
		if (preg_match('/Android\s([0-9._]+)/i', $uaString, $matches)) {
			$osVersion = $matches[1];
			
		}
	}

	// Handle iOS
	elseif ($osFamily == "iOS" || $osFamily == "Mac OS X") {
		$matches  = [];
		if (preg_match('/OS\s([0-9_]+)/i', $uaString, $matches)) {
			$osVersion = str_replace("_", ".", $matches[1]); // Convert 17_2 to 17.2
			
		}
	}



$userDevice = [
        "user_id" => $user_id,
        "date" => $date,
        "time" => $time,
        "IP" => $ip,
        "browser" => $result->ua->family,
        "browser_version" => $result->ua->toVersion(),
        "device_vendor" => $deviceVendor,
        "device_model" => $deviceModel,
        "device_type" => $deviceType,
        "os_name" => $result->os->family,
        "os_version" => $osVersion,
        "engine_name" => $engineName,
        "engine_version" => $result->ua->toVersion(),
        "cpu_architectured" => $cpuArch
    ];
    return $userDevice;
}
/*
function getUserLoginInfo($user_id = 0) {
    $uaString = $_SERVER['HTTP_USER_AGENT']
        ?? "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/140.0.0.0 Safari/537.36";
    $ip   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $date = date('Y-m-d');
    $time = date('H:i:s');

    // Use your paid / Pro license key here
    $paidKey = '75E02F82-FE16-4C69-80F6-42B7A0282256';

    // Pass the key when creating the parser
    $parser = UAParser\Parser::create([
        'key' => $paidKey
    ]);

    $result = $parser->parse($uaString);

    // ---------------- CPU ARCH ----------------
    $cpuArch = "unknown";
    $uaLower = strtolower($uaString);

    if (strpos($uaLower, "x86_64") !== false || strpos($uaLower, "win64") !== false || strpos($uaLower, "x64") !== false) {
        $cpuArch = "amd64";
    } elseif (strpos($uaLower, "arm") !== false || strpos($uaLower, "aarch64") !== false) {
        $cpuArch = "arm";
    } elseif (strpos($uaLower, "i386") !== false || strpos($uaLower, "i686") !== false) {
        $cpuArch = "x86";
    } elseif (strpos($uaLower, "ppc") !== false) {
        $cpuArch = "powerpc";
    } elseif (stripos($uaLower, "android") !== false || stripos($uaLower, "iphone") !== false || stripos($uaLower, "ipad") !== false) {
        $cpuArch = "arm";
    }

    // ---------------- ENGINE ----------------
    $engineName    = "Other";
    $engineVersion = null;

    if (stripos($uaString, "AppleWebKit") !== false) {
        $engineName = ($result->ua->family === "Chrome" || $result->ua->family === "Edge") ? "Blink" : "WebKit";
    } elseif (stripos($uaString, "Gecko") !== false) {
        $engineName = "Gecko";
    }

    if (preg_match('/AppleWebKit\/([\d\.]+)/i', $uaString, $m)) {
        $engineVersion = $m[1];
    } elseif (preg_match('/Gecko\/([\d\.]+)/i', $uaString, $m)) {
        $engineVersion = $m[1];
    }

    // ---------------- DEVICE ----------------
    $deviceType   = "Desktop";
    $deviceVendor = "Unknown";
    $deviceModel  = "Unknown";

    if (!empty($result->device->family) && strtolower($result->device->family) !== "other") {
        $deviceType   = $result->device->family;
        $deviceVendor = $result->device->brand ?? "Unknown";
        $deviceModel  = $result->device->model ?? "Unknown";
    } elseif (stripos($uaString, "Mobile") !== false || stripos($uaString, "iPhone") !== false || stripos($uaString, "Android") !== false) {
        $deviceType   = "Mobile";
        $deviceVendor = $result->device->brand ?? "Generic";
        $deviceModel  = $result->device->model ?? "Generic";
    } else {
        if (stripos($uaString, "Macintosh") !== false) {
            $deviceVendor = "Apple";
        } elseif (stripos($uaString, "Windows") !== false) {
            $deviceVendor = "PC";
        } elseif (stripos($uaString, "Linux") !== false) {
            $deviceVendor = "PC";
        }
        $deviceModel = "Desktop";
    }

    // ---------------- OS ----------------
    $osFamily  = $result->os->family;
    $osVersion = $result->os->toVersion() ?: "unknown";

    if ($osFamily === "Windows") {
        if (preg_match('/Windows NT 10.0/', $uaString)) {
            $osVersion = "10";
        } elseif (preg_match('/Windows NT 6.3/', $uaString)) {
            $osVersion = "8.1";
        } elseif (preg_match('/Windows NT 6.1/', $uaString)) {
            $osVersion = "7";
        }
    } elseif ($osFamily === "Android" && preg_match('/Android\s([0-9._]+)/i', $uaString, $m)) {
        $osVersion = $m[1];
    } elseif (($osFamily === "iOS" || $osFamily === "Mac OS X") && preg_match('/OS\s([0-9_]+)/i', $uaString, $m)) {
        $osVersion = str_replace("_", ".", $m[1]);
    }

    return [
        "user_id"          => $user_id,
        "date"             => $date,
        "time"             => $time,
        "ip"               => $ip,
        "browser"          => $result->ua->family,
        "browser_version"  => $result->ua->toVersion(),
        "device_vendor"    => $deviceVendor,
        "device_model"     => $deviceModel,
        "device_type"      => $deviceType,
        "os_name"          => $osFamily,
        "os_version"       => $osVersion,
        "engine_name"      => $engineName,
        "engine_version"   => $engineVersion ?? "unknown",
        "cpu_architecture" => $cpuArch
    ];
}*/
//include "../config/AES.fusion.php";


if(!isset($_GET['UID']) && !isset($_GET['PW']) && !isset($_SESSION['user']['UID']) && !isset($_COOKIE['keep_login'])){
    include "Browser.php";
}

$browser = new Browser();
unset($_SESSION['error']);
extract($_POST);

$Message = array();
if(isset($_POST['sresolution'])){
    $_SESSION['user']['resolution'] = $_POST['sresolution'];
}
if (isset($email)) {

    // check email not empty
    if (isset($email) && $email != "") {
        $email = $_POST['email'];
    } else {
        $_SESSION['error']['message'] = "Username cannot be blank.";
        $_SESSION['error']['color'] = "danger";
    }

    // check Password not empty
    if (!isset($password) || $password == "") {
        $_SESSION['error']['message'] = "Password cannot be blank.";
        $_SESSION['error']['color'] = "danger";
    }

    $countError = count($Message);

    if ($countError > 0) {
 
        header("Location: " . ADMIN_URL . 'login');
        exit;
    } else {

        if (empty($_GET['PW']) && !isset($_GET['UID']) && !isset($_SESSION['user']['pwd']) && !isset($_COOKIE['keep_login'])) {

            $matchPass = md5($password);
        } else {

            $matchPass = $password;
        }
		$email = strtolower($email);
         //$get_data = query("select * from user where (user.username = '" . base64_encode($email) . "' or user.email = '" . base64_encode($email) . "' OR user.username = '" . $email . "') AND user.password='" . $matchPass . "'");
		 $get_data = query("select * from user where (user.username = '" . base64_encode($email) . "'  OR user.username = '" . $email . "') AND user.password='" . $matchPass . "'");
		 
        if(isset($_POST['id'])){
            $userID = $_POST['id'];
            $get_data = query("select * from user where (user.username = '" . base64_encode($email) . "'  OR user.username = '" . $email . "') AND user.password='" . $matchPass . "' AND user.id='".$userID."'");
        }
		
        $row = fetch($get_data); 
	
        if(isset($_POST['keep_login']) &&  $_POST['keep_login'] == 'on'){

           // $_SESSION['user']['UID'] = $row['username'];
           // $_SESSION['user']['pwd'] = $row['password'];
           // setcookie(name, value, expire, path, domain, secure, httponly);
            setcookie('keep_login', $row['username'] . '|' . $row['password'] , time() + 60 * 60 * 24, '/','.accessibyte.com');
       
       }		
 
        if(isset($api)) {
            header("Access-Control-Allow-Origin: *");
            if (!empty($row)) { 
                echo json_encode(array('status'=> 'success', 'data'=> $row));exit;
            } else { 
                unset($_SESSION['user']['UID']);
	           unset($_SESSION['user']['pwd']);
			   if (isset($_COOKIE['keep_login'])) {
                    setcookie("keep_login", "", time() - 3600);
                    setcookie('keep_login', null, -1, '/','.accessibyte.com');
                	setcookie("keep_login", "", time()-3600*48,'/','.accessibyte.com');
	                unset($_COOKIE['keep_login']);
               }
                echo json_encode(array('status'=> 'error'));exit;
            }
        } 

        $user_ids = $_SERVER['REMOTE_ADDR'];
        $sql3 = "SELECT * FROM pia_user_login_try_ip WHERE user_ip_address='".$user_ids."'";
        $resultdata = $con->query($sql3);    
        $selectsql_data = fetch($resultdata);
        
        $start_date = $selectsql_data['user_try_login_time'];
        $after_adding_one_day_date = date('Y-m-d', strtotime($start_date));
        $current_date = date("Y-m-d");
        $userid_ip = $selectsql_data['id'];
        $attempt_count = $selectsql_data['user_ip_count'];
        if(strtotime($after_adding_one_day_date) == strtotime($current_date)){
            if($attempt_count == 20){
                $error = 1;
            }
        }else{
            if($attempt_count == 20){
                $updatequery = "UPDATE pia_user_login_try_ip SET user_ip_count='0',user_ip_status='unblock' WHERE id='$userid_ip'";
                $updatequeryresult = $con->query($updatequery);
            }
        }
        $limit_ip_count = 20;
        if($selectsql_data['user_ip_count'] == $limit_ip_count && $selectsql_data['user_ip_status'] == 'block' || $selectsql_data['user_ip_status'] == 'block'){
                $error = 1;
        }else{
            if (!empty($row)) {


                if (( md5($password) == $row['password'] ) || $password == $row['password']) {

                    unset($_SESSION['error']);
                    
                    /* insert user login log */
                    $logArr = array(
                        'user_id' => $row['id'],
                        'login_date_time' => date('Y-m-d H:i:s'),
                        'IP' => $_SERVER['REMOTE_ADDR'],
                        'browser_type' => $browser->getBrowser(),
						'login_type' => $_POST['log_type'],
                    );
                    
                    checkusersettings($row['id']);
                    insertUserLoginLog($logArr);
					$logArrData =  getUserLoginInfo($row['id']);
					insertUserLoginDetails($logArrData);
					setcookie('login_method', $_POST['log_type'], time() + 60 * 60 * 24, '/','.accessibyte.com');
                    // If role is student
                   
                    if (!empty($row['role']) && $row['role'] == 'student') {


                        // IF browser is chrome then only student will be able to login
                        //if (get_browser_name($_SERVER['HTTP_USER_AGENT']) != 'Chrome') {
                        //echo $browser->getPlatform()."<br>";
                        // echo $browser->getBrowser();exit;
                        /*if($browser->getPlatform() == Browser::PLATFORM_IPHONE || $browser->getPlatform() == Browser::PLATFORM_IPOD || $browser->getPlatform() == Browser::PLATFORM_IPAD) {
                            if($browser->getBrowser() != Browser::BROWSER_SAFARI && $browser->getBrowser() != Browser::BROWSER_IPHONE && $browser->getBrowser() != Browser::BROWSER_IPAD && $browser->getBrowser() != Browser::BROWSER_IPOD) {
                                unset($_SESSION['error']);
                                $_SESSION['error']['message'] = "IPHONE_ERROR";
                                $_SESSION['error']['color'] = "danger";
                                header("Location: " . ADMIN_URL . 'login');
                                exit;
                            }
                        } else {
                            if( $browser->getBrowser() != Browser::BROWSER_CHROME && $browser->getBrowser() != Browser::BROWSER_SAFARI) {
                                unset($_SESSION['error']);
                                $_SESSION['error']['message'] = "Sorry, but you must use the Google Chrome browser to login to Accessibyte Online.";
                                $_SESSION['error']['color'] = "danger";
                                header("Location: " . ADMIN_URL . 'login');
                                exit;
                            }
                        }*/

                        // Get Typio Lisence Key
                        $query = query("SELECT settings.variable from settings WHERE settings.id ='" . $row['id'] . "'  AND settings.item='5' ");
                        $data_typio = fetch($query);
                        
                        // Get Pro Lisence Key
                        $query = query("SELECT settings.variable from settings WHERE settings.id ='" . $row['id'] . "'  AND settings.item='6' ");
                        $data_pro = fetch($query);
                        // Get AAO Lisence Key
                        $query = query("SELECT settings.variable from settings WHERE settings.id ='" . $row['id'] . "'  AND settings.item='7' ");
                        $data_aao = fetch($query);
                        // Get Quick Card Lisence Key
                        $query = query("SELECT settings.variable from settings WHERE settings.id ='" . $row['id'] . "'  AND settings.item='8' ");
                        $data_quick = fetch($query);

                        $TYO_license['days'] = '';
                        $PRO_license['days'] = '';
                        $AAO_license['days'] = '';
                        $QCO_license['days'] = '';
                        // Check Lisense Status............... 
                        if (!empty($data_typio)) {

                            $TYO_license = checkLicenseStatus($row['id'], 5);
                        } else if (!empty($data_pro)) {

                            $PRO_license = checkLicenseStatus($row['id'], 6);
                        } else if (!empty($data_aao)) {

                            $AAO_license = checkLicenseStatus($row['id'], 7);
                        } else if (!empty($data_quick)) {

                            $QCO_license = checkLicenseStatus($row['id'], 8);
                        } else {
                            $BDL_license = checkLicenseStatus($row['id']);
                        }
                        // echo "TYO_license is="; print_r($TYO_license);
                        // echo "PRO_license is="; print_r($PRO_license);
                        // echo "AAO_license is="; print_r($AAO_license);
                        // echo "QCO_license is="; print_r($QCO_license);
                        // echo "BDL_license is="; print_r($BDL_license);
                        if (empty($TYO_license['days']) && empty($PRO_license['days']) && empty($AAO_license['days']) && empty($QCO_license['days']) && empty($BDL_license['days'])) {

                                /* Check if backend allow expiry user allow login then allow login otherwise it return error .*/
                                $login_allow_status = get_allow_login_status();
                                
                                if (empty($login_allow_status)) {
                                    unset($_SESSION['error']);
                                    $_SESSION['error']['message'] = "Your license is expired.  <a target='_blank' href='" . WP_LICENSE_UPDATE_LINK . "'>Click here </a> to update your license.";
                                    $_SESSION['error']['color'] = "danger";
									
                                    header("Location: " . ADMIN_URL . 'login');
                                    exit;
                                }
                        }

                        // Ganerate new token number
                        $stoken = generateRandomToken();

                        $current_time = time();

                        query("UPDATE user SET `token`='" . $stoken . "',`login`='" . $current_time . "' WHERE `id`=" . $row['id']);

                        //setcookie( 'user_details', fusion_aes_encrypt($row['username'].'|'.$password), time()+3600*24*2, '/');

                        setcookie('user_details', $row['username'] . '|' . $row['id'] . '|' . $stoken, time() + 60 * 60 * 24, '/','.accessibyte.com');

                        $user_ip_id = $_SERVER['REMOTE_ADDR'];
                        $sql_success = "SELECT * FROM pia_user_login_try_ip WHERE user_ip_address='".$user_ip_id."' " ;
                        $resultdata = $con->query($sql_success);
                        $sqldata = fetch($resultdata);  
                        $ip_address_id = $sqldata['id'];
                        $updatesqldata = "UPDATE pia_user_login_try_ip SET user_ip_count='0', user_ip_status='unblock'  WHERE id='$ip_address_id'  ";
                        $updateresultdata = $con->query($updatesqldata);
                        
                        if (!empty($row['license']) && $row['license'] != '') {
                            $license_key_explode = explode("-", $row['license']);
                            if (!empty($license_key_explode)) {
                                if ($license_key_explode[0] == 'TYO' || $license_key_explode[0] == 'TCH') {

                                    $user_data =query("select price_option,promo_code from user where  id='" . $row['id'] . "'");
                                    $user_get_data = fetch($user_data);
                                    if(!empty( $user_get_data )){
                                        $price_option=$user_get_data['price_option'];
                                        $promo_code =$user_get_data['promo_code'];

                                        $price_option_arr =explode("|",$price_option);
                                        if( isset($price_option_arr[1]) &&  $price_option_arr[1] == '99' &&  $promo_code  == "beta" && $license_key_explode[0] == 'TCH'){
                                            header("Location: https://www.accessibyte.com/apps/beta/typiodir-beta");
                                            exit;
                                        }
                                    }
                                    $user_setting_data = query("select variable from settings where item ='130'  AND id='" . $row['id'] . "'");
                                    $user_get_setting_data = fetch($user_setting_data);
                                    if(!empty($user_get_setting_data )){
                                        if($user_get_setting_data['variable'] == 1){
                                            header("Location: https://www.accessibyte.com/apps/typiodir");
                                            exit;
                                        }
                                    }
                                    header("Location: " . STUDENT_PANEL);
                                    exit;
                                } else if ($license_key_explode[0] == 'BDL' || $license_key_explode[0] == 'TCHP') {

                                    $user_data =query("select price_option,promo_code from user where  id ='" . $row['id'] . "'");
                                    $user_get_data = fetch($user_data);
                                    if(!empty( $user_get_data )){
                                        $price_option=$user_get_data['price_option'];
                                        $promo_code =$user_get_data['promo_code'];

                                        $price_option_arr =explode("|",$price_option);
                                        if( isset($price_option_arr[1]) &&  $price_option_arr[1] == '99' &&  $promo_code  == "pro" && $license_key_explode[0] == 'TCHP'){
                                            header("Location: https://www.accessibyte.com/apps/typiodir");
                                            exit;
                                        }
                                    }
                                    header("Location: " . STUDENT_PANEL_BDL);
                                    exit;
                                }
                                else if ($license_key_explode[0] == 'BRL') {

                                    $user_data =query("select price_option,promo_code from user where  id ='" . $row['id'] . "'");
                                    $user_get_data = fetch($user_data);
                                    if(!empty( $user_get_data )){
                                        $price_option=$user_get_data['price_option'];
                                        $promo_code =$user_get_data['promo_code'];

                                        $price_option_arr =explode("|",$price_option);
                                        /*if( isset($price_option_arr[1]) &&  $price_option_arr[1] == '99' &&  $promo_code  == "pro" && $license_key_explode[0] == 'TCHP'){
                                            header("Location: https://www.accessibyte.com/apps/typiodir");
                                            exit;
                                        }*/
                                    }
                                    header("Location: " . STUDENT_PANEL_BRL);
                                    exit;
                                }
                                
                            }
                        }
                        header("Location: " . STUDENT_PANEL);
                        exit;
                    } else {
                        
                        // If role is Teacher
                        $checkLicense = check_login_time_license($row['license']);
                        
                        //Set License error

                        if (empty($checkLicense['days'])) {
                            
                            /* code for check status active then expired license user able to login */
                            /*if (isset($checkLicense['status']) && $checkLicense['status'] == 'expired') {
                                $login_allow_status = get_allow_login_status();
                                if (!empty($login_allow_status)) {*/
                                    $_SESSION['User']['email'] = $row['email'];

                                    $_SESSION['User']['username'] = $row['username'];

                                    $_SESSION['User']['id'] = $row['id'];

                                    $_SESSION['User']['teacher'] = $row['teacher'];

                                    $_SESSION['User']['firstname'] = $row['firstname'];

                                    $_SESSION['User']['lastname'] = $row['lastname'];

                                    $_SESSION['User']['organization'] = $row['organization'];

                                    $_SESSION['User']['license'] = $row['license'];
                                    $_SESSION['User']['is_admin'] = $row['is_admin'];
                                    if (isset($checkLicense['status']) && $checkLicense['status'] == 'expired') {
                                        $_SESSION['User']['is_expired'] = 1;
                                    }
                                    $_SESSION['User']['expire_login_status'] = get_allow_login_status();
									
									
                                    header("Location:" . ADMIN_URL . 'student/student-list.php');
                                    //header("Location: " . ADMIN_URL);
                                    exit;
                                /*}
                            }
                            
                            unset($_SESSION['error']);

                            $_SESSION['error']['message'] = "Your license is expired.  <a target='_blank' href='" . WP_LICENSE_UPDATE_LINK . "'>Click here </a> to update your license.";

                            $_SESSION['error']['color'] = "danger";

                            header("Location: " . ADMIN_URL . 'login');

                            exit;*/
                        }

                        $_SESSION['User']['email'] = $row['email'];

                        $_SESSION['User']['username'] = $row['username'];

                        $_SESSION['User']['id'] = $row['id'];

                        $_SESSION['User']['teacher'] = $row['teacher'];

                        $_SESSION['User']['firstname'] = $row['firstname'];

                        $_SESSION['User']['lastname'] = $row['lastname'];

                        $_SESSION['User']['organization'] = $row['organization'];

                        $_SESSION['User']['license'] = $row['license'];
                        $_SESSION['User']['is_admin'] = $row['is_admin'];
                        $_SESSION['User']['is_expired'] = 0;

                        $user_ip_id = $_SERVER['REMOTE_ADDR'];
                        $sql_success = "SELECT * FROM pia_user_login_try_ip WHERE user_ip_address='".$user_ip_id."' " ;
                        $resultdata = $con->query($sql_success);
                        $sqldata = fetch($resultdata);  
                        $ip_address_id = $sqldata['id'];
                        $updatesqldata = "UPDATE pia_user_login_try_ip SET user_ip_count='0', user_ip_status='unblock' WHERE id='$ip_address_id'  ";
                        $updateresultdata = $con->query($updatesqldata);
															
                        header("Location:" . ADMIN_URL . 'student/student-list.php');
                        //header("Location: " . ADMIN_URL);
                        exit;
                    }
                } else {

                    $error = 1;
                }
            } else {
if (isset($_COOKIE['keep_login'])) {
    setcookie("keep_login", "", time() - 3600);
    setcookie('keep_login', null, -1, '/','.accessibyte.com');
	setcookie("keep_login", "", time()-3600*48,'/','.accessibyte.com');
	 unset($_COOKIE['keep_login']);
   
}
				
//setcookie("keep_login", "", time() - 4800);
	unset($_SESSION['user']['UID']);
	unset($_SESSION['user']['pwd']);
                $error = 1;
            }
        }
        if ($error == 1) {


            /* insert user login log */
            $logArr = array(
                'user_id' => 0,
                'login_date_time' => date('Y-m-d H:i:s'),
                'IP' => $_SERVER['REMOTE_ADDR'],
                'browser_type' => $browser->getBrowser(),
				'login_type' => $_POST['log_type'],
            );
            insertUserLoginLog($logArr);
			$logArrData =  getUserLoginInfo(0);
			insertUserLoginDetails($logArrData);
  setcookie('login_method', $_POST['log_type'], time() + 60 * 60 * 24, '/','.accessibyte.com');
            $user_id = $_SERVER['REMOTE_ADDR'];
            $ip_count = 1;
            $ip_status= "unblock";
            
            $sql2 = "SELECT * FROM pia_user_login_try_ip WHERE user_ip_address='".$user_id."' " ;
            $result = $con->query($sql2);    
            $selectsql = fetch($result);
            $limit_ip_count = 20;
            if($selectsql['user_ip_count'] == $limit_ip_count && $selectsql['user_ip_status'] == 'block' && $selectsql['whitelist_status'] == 'no'){
                unset($_SESSION['error']);

                $_SESSION['error']['message'] = "Please contact <a href='mailto:support@accessibyte.com'>support@accessibyte.com</a> for login assistance.";

                $_SESSION['error']['color'] = "danger";

            } else if($selectsql['user_ip_count'] == $limit_ip_count && $selectsql['user_ip_status'] == 'block'){
                unset($_SESSION['error']);

                $_SESSION['error']['message'] = "Please contact <a href='mailto:support@accessibyte.com'>support@accessibyte.com</a> for login assistance.";

                $_SESSION['error']['color'] = "danger";

            } else{
                if(empty($selectsql)){
                    $con->query("INSERT INTO pia_user_login_try_ip SET `username` = '$email',`user_ip_address` = '$user_id',`user_ip_count` = '$ip_count',`user_ip_status` = '$ip_status',`whitelist_status` = 'no'");
                }else{
                    $user_ip_id = $selectsql['id'];
                    $whitelist_status = $selectsql['whitelist_status'];
                    if($selectsql['user_ip_count'] != ''){
                        $user_ip_update_count = $selectsql['user_ip_count'] + 1;
                    }else{
                        $user_ip_update_count = 1 ;
                    }
                    if($whitelist_status != 'yes'){
                        if( $user_ip_update_count <= $limit_ip_count  ){
                            if($user_ip_update_count == $limit_ip_count){
                                $update_status = "block";
                            }else{
                                $update_status = "unblock";
                            }
                            $updatesql = "UPDATE pia_user_login_try_ip SET username = '$email',user_ip_count='$user_ip_update_count', user_ip_status='$update_status' WHERE id='$user_ip_id'";    
                        }else{

                            $updatesql = "UPDATE pia_user_login_try_ip SET user_ip_status='block' WHERE id='$user_ip_id'";
                        }
                        $updateresult = $con->query($updatesql);  
                    }
                }
                unset($_SESSION['error']);

                $_SESSION['error']['message'] = "Invalid username or password";

                $_SESSION['error']['color'] = "danger";
            }
			//
			
            header("Location: " . ADMIN_URL . 'login');

            exit;
        
        }
    }
}
?>

