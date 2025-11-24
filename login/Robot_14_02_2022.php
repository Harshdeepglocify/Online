<?php

if (!defined('ADMIN_URL')) {

    include "../config/config.php";
}

//include "../config/AES.fusion.php";
if(!isset($_GET['UID']) && !isset($_GET['PW'])){
    include "Browser.php";
}

$browser = new Browser();
unset($_SESSION['error']);
extract($_POST);

$Message = array();

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

        if (empty($_GET['PW']) && !isset($_GET['UID'])) {

            $matchPass = md5($password);
        } else {

            $matchPass = $password;
        }
		$email = strtolower($email);
        $get_data = query("select * from user where (user.username = '" . base64_encode($email) . "' or user.email = '" . base64_encode($email) . "' OR user.username = '" . $email . "') AND user.password='" . $matchPass . "'");
		
        $row = fetch($get_data);
		
        if(isset($api)) {
            header("Access-Control-Allow-Origin: *");
            if (!empty($row)) {
                echo json_encode(array('status'=> 'success', 'data'=> $row));exit;
            } else {
                echo json_encode(array('status'=> 'error'));exit;
            }
        }

        if (!empty($row)) {


            if (( md5($password) == $row['password'] ) || $password == $row['password']) {

                unset($_SESSION['error']);
				
				/* insert user login log */
                $logArr = array(
                    'user_id' => $row['id'],
                    'login_date_time' => date('Y-m-d H:i:s'),
                    'IP' => $_SERVER['REMOTE_ADDR'],
                    'browser_type' => $browser->getBrowser(),
                );
                insertUserLoginLog($logArr);
                // If role is student
                if (!empty($row['role']) && $row['role'] == 'student') {


                    // IF browser is chrome then only student will be able to login
                    //if (get_browser_name($_SERVER['HTTP_USER_AGENT']) != 'Chrome') {
                    //echo $browser->getPlatform()."<br>";
                    // echo $browser->getBrowser();exit;
                 /*   if($browser->getPlatform() == Browser::PLATFORM_IPHONE || $browser->getPlatform() == Browser::PLATFORM_IPOD || $browser->getPlatform() == Browser::PLATFORM_IPAD) {
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

                    if (!empty($row['license']) && $row['license'] != '') {
                        $license_key_explode = explode("-", $row['license']);
                        if (!empty($license_key_explode)) {
                            if ($license_key_explode[0] == 'TYO' || $license_key_explode[0] == 'TCH') {
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
								header("Location: " . STUDENT_PANEL_BDL);
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

                                header("Location: " . ADMIN_URL);
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

                    header("Location: " . ADMIN_URL);

                    exit;
                }
            } else {

                $error = 1;
            }
        } else {

            $error = 1;
        }

        if ($error == 1) {

            unset($_SESSION['error']);

            $_SESSION['error']['message'] = "Invalid username or password";

            $_SESSION['error']['color'] = "danger";

            header("Location: " . ADMIN_URL . 'login');

            exit;
        }
    }
}
?>

