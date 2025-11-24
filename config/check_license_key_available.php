<?php

include "config.php";
if(!$_SESSION['User']){
    header("Location: " . ADMIN_URL . 'login');
    exit;
}
if (isset($_POST) && isset($_POST['license']) && !empty($_POST) && $_POST['license'] != '') {
    $val = explode("-", trim($_POST['license']));

    $item = "0";
    if (isset($val[0]) && $val[0]) {
        if ($val[0] == "PRO")
            $item = "9800";
        elseif ($val[0] == "BDL")
            $item = "4184";
        elseif ($val[0] == "BRL")
              $item = "88653";
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
    }

    $url = WP_URL."?edd_action=check_license&item_id=" . $item . "&license=" . trim($_POST['license']);

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
//print_r($status);
    if ($status && !empty($status->success)) {
        if ($status->success == 1) {
           
            $license_key = trim($_POST['license']);
            $val = explode("-", $license_key);
            $check_license_key_exists = query('SELECT license_key FROM unassign_license_keys where `license_key`="' . $license_key . '" order by license_key desc');

            if ($check_license_key_exists->num_rows <= 0) {

//                $ItemName = '';
//                if ($val[0] == "PRO")
//                    $ItemName = "ProPack";
//                elseif ($val[0] == "TYO")
//                    $ItemName = "Typio";
//                else if ($val[0] == "AAO")
//                    $ItemName = "Accessibyte Arcade";
//                else if ($val[0] == "QCO")
//                    $ItemName = "Quick Cards";
//                else if ($val[0] == "BDL")
//                    $ItemName = "Accessibyte All Access";

//                if(isset($_POST['input_license']) && $_POST['input_license'] != ''){
//                    $user_ids = query('SELECT GROUP_CONCAT(DISTINCT settings.id) as user_ids FROM settings JOIN user ON user.id=settings.id where `variable`="' . $license_key . '" and `teacher`="' . $_SESSION["User"]["teacher"] . '" group by variable');
//                    $userid = null;
//
//                    if ($user_ids->num_rows > 0) {
//                        while ($row = mysqli_fetch_assoc($user_ids)) {
//                            $userid = $row['user_ids'];
//                        }
//                        mysqli_query($con, "INSERT INTO `unassign_license_keys` (`license_type`, `license_key`, `user_id`, `created_by`) VALUES ('" . $ItemName . "','" . $license_key . "','" . $userid . "','" . $_SESSION['User']['id'] . "')");
//                    }
//
                    echo 'success';
                    exit;
//                }
            } else {
                echo 'Already exists';
                exit;
            }
        } else {
            echo 'License key Invalid';
            exit;
        }
    } else {
        echo 'License key not found';
        exit;
    }
} 



if(isset($_POST['input_license']) && $_POST['input_license'] != ''){
    
    $license_key = trim($_POST['input_license']);
    $val = explode("-", $license_key);
    
     $ItemName = '';
    if ($val[0] == "PRO")
        $ItemName = "ProPack";
    elseif ($val[0] == "TYO")
        $ItemName = "Typio";
    else if ($val[0] == "AAO")
        $ItemName = "Accessibyte Arcade";
    else if ($val[0] == "QCO")
        $ItemName = "Quick Cards";
    else if ($val[0] == "BDL")
        $ItemName = "Accessibyte All Access";
    else if ($val[0] == "BRL")
        $ItemName = "Braillio Home User";
    
    $user_ids = query('SELECT GROUP_CONCAT(DISTINCT settings.id) as user_ids FROM settings JOIN user ON user.id=settings.id where `variable`="' . $license_key . '" and `teacher`="' . $_SESSION["User"]["teacher"] . '" group by variable');
    $userid = null;

    if ($user_ids->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($user_ids)) {
            $userid = $row['user_ids'];
        }
        mysqli_query($con, "INSERT INTO `unassign_license_keys` (`license_type`, `license_key`, `user_id`, `created_by`) VALUES ('" . $ItemName . "','" . $license_key . "','" . $userid . "','" . $_SESSION['User']['id'] . "')");
    }

    echo 'success';
    exit;
}else {
    echo 'required';
    exit;
}