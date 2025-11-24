<?php 
$HostName = "localhost";
$DbUser = "devaccessibyte_copyuser";
$DbPassword = "P?fpg2fZL~G-";
$Database = "devaccessibyte_onlinec";

global $con;
$con = mysqli_connect($HostName, $DbUser, $DbPassword, $Database);
if (!$con) {
 
}else{
	
	$sql = "DELETE FROM report_requests 
        WHERE requested_at < NOW() - INTERVAL 1 DAY";

	mysqli_query($con, $sql);

	
}