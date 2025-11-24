<?php include "../config/config.php"; 

global $con;
    

if(!empty($_SERVER['HTTP_CLIENT_IP'])){
    $cookie_client_ip = $_SERVER['HTTP_CLIENT_IP'];
}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $cookie_client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
    $cookie_client_ip = $_SERVER['REMOTE_ADDR'];
}

$cookie_created_at = date('Y-m-d H:i:s');

$sql = "INSERT INTO 
            tbl_cookie_accept (cookie_client_ip, cookie_name, cookie_value, cookie_policy_url, cookie_expire_days, cookie_created_at, cookie_accept) 
        VALUES 
            ('".$cookie_client_ip."', '".$_POST['cookie_name']."', '".$_POST['cookie_value']."', '".$_POST['cookie_policy_url']."', '".$_POST['cookie_expire_days']."', '".$cookie_created_at."', 'true')";

mysqli_query($con, $sql);

?>