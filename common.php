<?php

include "config/config.php";

if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}

if ($_POST['licenseCheck'] == 'license_check') {
    $license = trim($_POST['license_key']);
    if (!empty($license)) {


        $val = explode("-", trim($license));

        if ($val[0] == "PRO")
            $item = "9800";
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
        else if ($val[0] == "TCHP") /* Add new liecenece for teacher dashboard */
            $item = "30398";
			else if ($val[0] == "TCHI") /* Add new liecenece for teacher dashboard */
            $item = "67578";
        else if ($val[0] == "QCO")
            $item = "4182";
        else if ($val[0] == "AA")
            $item = "905";
        else if ($val[0] == "WW")
            $item = "207";

        if (empty($item)) {

            $data = array(
                'license_status' => 'none',
                'license' => '-'
            );
            echo json_encode($data);
            exit;
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

        $_POST['licenseCheck'] = '';
        echo json_encode($data);
        exit;
    }
}
