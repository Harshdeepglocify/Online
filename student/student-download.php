<?php
include "../config/config.php";
include "../helper/student_helper.php";

if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}

/* For expired license not access this page directly */
$is_expired = check_expire_or_not();
if($is_expired && $_SESSION['User']['is_admin'] ==1){
    header("Location: " . ADMIN_URL);
    exit;
}

if (empty($_GET['file'])) {
    http_response_code(400);
    echo "Missing file parameter.";
   // exit;
}

$filename = $_GET['file']; 


//$filepath = ADMIN_URL . 'uploads/reports/' . $filename;
$filepath = __DIR__  . '/../uploads/reports/' . $filename;

if (!file_exists($filepath)) {
    http_response_code(404);
    echo "File not found.";
    
}

if (ob_get_length()) {
    ob_end_clean();
}

header('Content-Description: File Transfer');
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));

readfile($filepath);
exit;
?>
