<?php

include "../config/config.php";
extract($_POST);

if (isset($username)) {
	$username = strtolower($username);
    $query = query("SELECT count(*) as total FROM user where user.username='$username' or user.email='".base64_encode($username)."' or user.username='".base64_encode($username)."'");
    $data = fetch($query);
    if ($data['total'] == 0) {
        echo 'false'; //Email does not exists
    } else {
        echo 'true'; //good to go
    }
}