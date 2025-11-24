<?php

if(!$_SESSION['User']){
    header("Location: " . ADMIN_URL . 'login');
    exit;
}

// This File Call after include config file 

//$get_unassign_license_keys_query_result = query('SELECT id,license_type,license_key,created_by,user_id,license_limit,site_count FROM unassign_license_keys JOIN wordpress_licenses ON unassign_license_keys.license_key = wordpress_licenses.license where `created_by`=' . $_SESSION['User']['id'] . ' order by license_type desc') ;
$get_unassign_license_keys_query_result = query('SELECT ulk.id,license_type,license_key,created_by,ulk.user_id,license_limit,site_count as license_used,expires FROM unassign_license_keys ulk JOIN wordpress_licenses ON ulk.license_key = wordpress_licenses.license where `created_by`=' . $_SESSION['User']['id'] . ' GROUP BY wordpress_licenses.license order by expires asc') ;

function get_user_name($user_id){
    
    $get_user_details = query('SELECT username FROM user where `id`=' . $user_id ) ;    
    $user_name = '';
    if($get_user_details->num_rows > 0){
        while ($row = mysqli_fetch_assoc($get_user_details)) {
            $user_name = ucfirst($row['username']);
        }
    }
    
    return $user_name;
    
}




?>