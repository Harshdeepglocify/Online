<?php

include "config-student.php";
global $con;

/**
 * Check Duplicate entry for Quick Cards
 * 
 * @param int table_id
 * @param string title 
 * @param string post_type(Item Name) 
 * @return boolean
 */
if (isset($_POST['title']) && isset($_POST['item_name']) && $_POST['title'] != '' && $_POST['item_name'] != '') {

    if (isset($_POST['student']) && $_POST['student'] != '') {    // This post get from student overview page
        $login_id = trim($_POST['student']);
    } else {
        $login_id = $_SESSION['User']['id'];
    }

    $title = trim($_POST['title']);
    $item_name = trim($_POST['item_name']);
    if (isset($_POST['number'])) {
        $number = trim($_POST['number']);
    } else {
        $number = '';
    }

    $table_id = trim($_POST['table_id']);

    if (!empty($number)) {
        $number_table = ' And number = ' . $number;
    } else {
        $number_table = '';
    }

    $check_exists = query('SELECT * FROM text WHERE table_id = "' . $table_id . '" AND id = "' . $login_id . '" AND app = "' . $item_name . '" AND title = "' . $title . '" ' . $number_table);
    if ($check_exists->num_rows > 0) {
        echo json_encode('');
    } else {
        $check_exists = query('SELECT * FROM text WHERE id = "' . $login_id . '" AND app = "' . $item_name . '" AND title = "' . $title . '" ' . $number_table);
        if ($check_exists->num_rows > 0) {
            echo json_encode('This Name is already exists. Please try another name.');
        } else {
            echo json_encode('');
        }
    }
    exit;
}
?> 