<?php 
$HostName = "localhost";
$DbUser = "devaccessibyte_copyuser";
$DbPassword = "P?fpg2fZL~G-";
$Database = "devaccessibyte_onlinec";

global $con;
$con = mysqli_connect($HostName, $DbUser, $DbPassword, $Database);

if (!$con) {
    die("DB connection failed");
} else {
	/*$root = dirname(__DIR__, 1);
	$csvFolder = $root."/uploads/reports";
    // First, get all expired records (older than 2 days)
    $query = "SELECT download_link FROM report_downloads 
              WHERE created_at < NOW() - INTERVAL 7 DAY";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
           $filename = basename($row['download_link']); 
			$file = $csvFolder . "/" . $filename;
            // Make sure path is correct
            if (!empty($file) && file_exists($file)) {
                if (unlink($file)) {
                   // echo "Deleted file: $file <br>";
                } else {
                    //echo "Error deleting: $file <br>";
                }
            } else {
                //echo "File not found: $file <br>";
            }
        }
    }
*/
    // Now delete the DB records
    $delete = "DELETE FROM report_downloads WHERE created_at < NOW() - INTERVAL 7 DAY";
    mysqli_query($con, $delete);

   // echo "Old records removed.";
}