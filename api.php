<?php 
  include "config/config.php";

  $msg ='';

  if(!isset($_REQUEST['student_id'])) {
    $msg = 'student id parameter missing';
    $result = json_encode(array('error' => $msg));
  }else if(!isset($_REQUEST['license_key'])) {
    $msg = 'license key parameter missing';
    $result = json_encode(array('error' => $msg));
  }else if(!isset($_REQUEST['action'])) {
    $msg = 'action parameter missing';
    $result = json_encode(array('error' => $msg));
  }else if(empty($_REQUEST['student_id'])) {
    $msg = 'student id blank!';
    $result = json_encode(array('error' => $msg));
  }else if(empty($_REQUEST['license_key'])) {
    $msg = 'license key blank!';
    $result = json_encode(array('error' => $msg));
  }else if(empty($_REQUEST['action'])) {
    $msg = 'action blank!';
    $result = json_encode(array('error' => $msg));
  }else if($_REQUEST['action'] != 'verify-license' && $_REQUEST['action'] != 'activate-license'){
    $msg = 'action invalid !';
    $result = json_encode(array('error' => $msg));
  } 
  else{  
    $val = explode("-", trim($_REQUEST['license_key'])); 
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
      if($_REQUEST['action'] == 'verify-license'){
        $url = WP_URL."?edd_action=check_license&item_id=" . $item . "&license=" . $_REQUEST['license_key'];
      }
      if($_REQUEST['action'] == 'activate-license'){
        $url = WP_URL."?edd_action=activate_license&item_id=" . $item . "&license=" . $_REQUEST['license_key'];
      } 
   
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
      
      if($_REQUEST['action'] == 'activate-license'){
        if(!empty($_REQUEST['student_id'])){
            $user_id = $_REQUEST['student_id'];
            $license = $_REQUEST['license_key'];
            
            if($status &&  !empty($status->success)){ 
                if ($status->success == 1 && ( $status->license == 'valid' || $status->license == 'inactive' )) {
                    $val = explode("-", $_REQUEST['license_key']); 
                    if ($val[0] == "TYO") {
                        query("DELETE FROM `settings` WHERE `id` = '{$user_id}' and `item` = '5'");
                        query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '5', `variable` = '{$license}' ");
                    } else if ($val[0] == "PRO") {
                         query("DELETE FROM `settings` WHERE `id` = '{$user_id}' and `item` = '6'");
                        query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '6', `variable` = '{$license}' ");
                    } else if ($val[0] == "AAO") {
                         query("DELETE FROM `settings` WHERE `id` = '{$user_id}' and `item` = '7'");
                        query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '7', `variable` = '{$license}' ");
                    } else if ($val[0] == "QCO") {
                         query("DELETE FROM `settings` WHERE `id` = '{$user_id}' and `item` = '8'");
                        query("INSERT INTO settings SET `id` = '{$user_id}', `item` = '8', `variable` = '{$license}' ");                      
                    } 
                    echo "License activated successfully";   
                }else{
                     echo "License already activated";    
                } 
            }else{
                 echo "License already activated";    
            } 
        } 
      }
  } 
  echo "<pre>";
   print_r($status);exit;

 


?>
