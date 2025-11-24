<?php
include "../config/config.php";
if (isset($_POST) && !empty($_POST)) {
    $val = explode("-", trim($_POST['license']));

    $item = "0";
    if (isset($_POST['usertype']) && $_POST['usertype'] == 'teacher') {
        if (!empty($val[0]) && $val[0] == "TCH") {
            $item = "4111";
        } else if(!empty($val[0]) && $val[0] == "TCHP"){    // add new license key for teacher dashboard
			$item = "30398";
		} else if(!empty($val[0]) && $val[0] == "TCHI"){    // add new license key for teacher dashboard
			$item = "67578";
		} else {
            echo 'false';
            exit;
        }
    } else {
        if (isset($val[0]) && $val[0]) {
            if ($val[0] == "PRO")
                $item = "9800";
            elseif ($val[0] == "BDL")
                $item = "4184";
            elseif ($val[0] == "TYO")
                $item = "3584";
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
            else if ($val[0] == "BRL")
                $item = "88653";
        }
    }

    //$url = "https://www.accessibyte.com/?edd_action=check_license&item_id=" . $item . "&license=" . trim($_POST['license']);
//	echo $item;echo $_POST['license'];
	$url = WP_URL . "?edd_action=check_license&item_id=" . $item . "&license=" . trim($_POST['license']);

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
//echo "<pre>";print_r($status);die;
    $massage = '';
    if ($status && !empty($status->success)) {
		
        if ($status->success == 1 && $status->license != 'item_name_mismatch') {
			
            if ($status->license == 'valid' || $status->license == 'inactive') {
                if ($status->license_limit == $status->site_count) {
                    $massage = 'This license is in use or out of activations.';
				} else {
							$massage = 'true';
				}
            }
            
            if ($status->license == 'expired') {
				if($status->site_count == 0 && $status->activations_left > 0){
					$massage = 'true';
				}
                $massage = 'This license is expired.';
            }else{
                $massage = 'true';
            }
        } else {
			
			if($_POST['usertype'] == 'student'){
                $massage = "This is a School Edition license. You are about to create a student account that won't be paired with a Teacher Dashboard. You can do that but we recommend <a href='https://dev.accessibyte.com/online/register.php'><u style='color: blue!important;'>registering a Teacher Dashboard</u></a>, then adding students so you can take full advantage of the School Edition features.";
              
            }else{
                 $massage = "License key is invalid!";
            }
		}

//        if ($status->success == 1 && ( $status->license == 'valid' || $status->license == 'inactive' ) && ( $status->activations_left != 0 || $status->activations_left === 'unlimited')) {
//            echo 'true';
//        } else {
//            echo 'false';
//        }
        
        echo $massage; 
        
    } else {
        echo 'Wrong license type.';
    }
}