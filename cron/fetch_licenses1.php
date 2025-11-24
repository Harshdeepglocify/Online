<?php

include "../config/config.php";

$user_ids = query('SELECT settings.id as user_id,GROUP_CONCAT(DISTINCT settings.variable) as licenses_keys FROM settings where `item` IN ("5","6","7","8") group by settings.id');
$setting_users_array = array();
while ($row = mysqli_fetch_assoc($user_ids)) {
    $setting_users_array[$row['user_id']] = explode(',', $row['licenses_keys']);
}
$merge_array = $setting_users_array;
//echo '<pre>';
//print_r($setting_users_array);
//exit;
//$user_details_query = query('SELECT id,license FROM user');
//$users_array = array();
//$merge_array = array();
//while ($row = mysqli_fetch_assoc($user_details_query)) {
//    $user_license_array = array($row['license']);
//    $users_array[$row['id']] = $user_license_array;
//    if(isset($setting_users_array[$row['id']])){
//        $merge_array[$row['id']] = array_unique(array_merge($user_license_array,$setting_users_array[$row['id']]));
//    }else{
//        $merge_array[$row['id']] = $user_license_array;
//    }
//}

if (!empty($merge_array)) {
    foreach ($merge_array as $key => $value) {
        if (!empty($value)) {
            foreach ($value as $v) {
                $val = explode("-", trim($v));
                $item = "0";
                if (isset($val[0]) && $val[0]) {
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
                }

                $url = "http://www.accessibyte.com/?edd_action=check_license&item_id=" . $item . "&license=" . trim($v);

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

//                $status_array = array();
//                
//                if(!empty($status)){
//                    foreach($status as $k=>$vl){
//                        $status_array[$k]=$vl;
//                    }
//                }
//                $serializ = serialize($status_array);
//                $insert_update_data = array(
//                    'user_id'=>$key,
//                    'license_key'=>trim($v),
//                    'meta_value'=> html_entity_decode($serializ)
//                );
//                
//                $keys = array_keys($insert_update_data);
//                $values_arr = array_values($insert_update_data);
//                
//                echo 'INSERT INTO `wordpress_licenses1` ('.implode(",", $keys).') VALUES ("'.implode('","', $values_arr).'")';
//                exit;
//                echo '<pre>';
//                print_r($status);
//                exit;


                if (!empty($status) && $status->success == 1) {
                    $data = array(
                        'user_id' => $key,
                        'success' => $status->success,
                        'license' => trim($v),
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
                    );
                } else {
                    $data = array(
                        'user_id' => $key,
                        'success' => 0,
                        'license' => trim($v),
                        'license_status' => $status->license,
                        'item_name' => $status->item_name,
                        'checksum' => $status->checksum,
                    );
                }
//                echo '<pre>';
//                print_r($data);

                $keys = array_keys($data);
                $values_arr = array_values($data);

                $check_user = query('SELECT user_id,license FROM wordpress_licenses WHERE user_id = "' . $key . '" AND license = "' . trim($v) . '" ');
                if ($check_user->num_rows > 0) {
                    $data['updated_datetime'] = date('Y-m-d H:i:s');
                    update_query('wordpress_licenses', 'user_id = "' . $key . '" AND license = "' . trim($v) . '"', $data);
                } else {
                    $check_user = query('SELECT id,user_id,license FROM wordpress_licenses WHERE user_id = "' . $key . '" AND license = "' . trim($v) . '" ');
                    if ($check_user->num_rows > 0) {
                        while ($result_row = mysqli_fetch_assoc($check_user)) {
                            $where_id = $result_row['id'];
                        }
                        $data['updated_datetime'] = date('Y-m-d H:i:s');
                        update_query('wordpress_licenses', 'id = "' . $where_id . '" AND license = "' . trim($v) . '"', $data);
                    } else {
                        query('INSERT INTO `wordpress_licenses` (' . implode(",", $keys) . ') VALUES ("' . implode('","', $values_arr) . '")');
                    }
//                    query('INSERT INTO `wordpress_licenses` (' . implode(",", $keys) . ') VALUES ("' . implode('","', $values_arr) . '")');
                }
                
                unset($data['user_id']); 
                $data['updated_datetime'] = date('Y-m-d H:i:s');
                update_query('wordpress_licenses', 'license = "' . trim($v) . '"', $data);
            }
        }
    }

    wordpress_licenses();

    echo 'finish';
    exit;
}

function wordpress_licenses() {

    $get_licenses = query('SELECT id,user_id,license FROM wordpress_licenses where user_id=0');
    if ($get_licenses->num_rows > 0) {
        while ($row1 = mysqli_fetch_assoc($get_licenses)) {
            $val = explode("-", trim($row1['license']));
            $item = "0";
            if (isset($val[0]) && $val[0]) {
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
            }

            $url = "http://www.accessibyte.com/?edd_action=check_license&item_id=" . $item . "&license=" . trim($row1['license']);

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
                $UpdateData = array(
                    'success' => $status->success,
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
                );
            } else {
                $UpdateData = array(
                    'success' => 0,
                    'license_status' => $status->license,
                    'item_name' => $status->item_name,
                    'checksum' => $status->checksum,
                );
            }

            $UpdateData['updated_datetime'] = date('Y-m-d H:i:s');
            update_query('wordpress_licenses', 'id = ' . $row1['id'], $UpdateData);
        }
    }
}

exit;


