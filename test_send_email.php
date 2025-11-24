<?php

include "config/config.php";

$header  = "MIME-Version: 1.0\r\n";
$header .= "Content-type: text/html; charset: utf8\r\n";
$template = file_get_contents(ADMIN_URL . 'emails/registration-email.html');


mail("phpdev6@worldwebtechnology.in","My subject test",$template,$header);

echo 'send successfully';







exit;


