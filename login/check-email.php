<?php

include "../config/config.php";
extract($_POST);
//var_dump();
//exit;
if (isset($email) && $email) {
    //$query = query("SELECT count(*) as total FROM user where user.email='$email'");
	$email = strtolower($email);
    $usertype = $usertype;
    $email = base64_encode($email);
    $query = query("SELECT count(*) as total FROM user where user.email='$email' and role = '$usertype'");
    $data = fetch($query);
    if ($data['total'] == 0) {
        echo 'true'; //good to register
    } else {
        echo 'false'; //already registered
    }
} else if (isset($username) && $username) {
	$username = strtolower($username);
    $query = query("SELECT count(*) as total FROM user where user.username='" . $username . "' or user.username='" . base64_encode($username) . "'");
    $data = fetch($query);
    if ($data['total'] == 0) {
        echo 'true'; //good to register
    } else {
        echo 'false'; //already registered
    }
}