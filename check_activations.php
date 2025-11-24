<?php include "config/config.php"; 

    $query = query("SELECT * FROM user");
//print_r($query);
 //    $users = array();
  //  while ($row = fetch($query)) {
  //      $users[] = $row;
  // }
 // print_r($users);
  while ($row = fetch($query)) {
//echo '<pre>';
//print_r($row);
 $wcon = mysqli_connect(WP_HOST, WP_USER, WP_PASS, WP_DB);
   
$query2 = mysqli_query($wcon, "SELECT * FROM pia_edd_licenses where license_key = '" . $row['license'] . "'");
    
    $data = mysqli_fetch_array($query2);
     //echo '<pre>';print_r($data);
 if (isset($data) && !empty($data) && isset($data['id'])) {
        $licenseArr = array();
        $query3 = mysqli_query($wcon, "SELECT * FROM pia_edd_licensemeta where edd_license_id = '" . $data['id'] . "' and meta_key = '_wwt_no_license'");
        $pricing_data = mysqli_fetch_array($query3);
//echo '<pre>';print_r($pricing_data);
        if (!empty($pricing_data) && isset($pricing_data['meta_value']) && !empty($pricing_data['meta_value'])) {
            $licenseUnserlizeArr = unserialize($pricing_data['meta_value']);
        
            if (!empty($licenseUnserlizeArr)) {
                if ($row['role'] == 'teacher' && isset($licenseUnserlizeArr['no_teacher_use']) && $licenseUnserlizeArr['no_teacher_use'] > $licenseUnserlizeArr['no_teacher']) {
                   $users[] = $row['id'];
                } else if ($row['role'] == 'student' && isset($licenseUnserlizeArr['no_student_use']) && $licenseUnserlizeArr['no_student_use'] > $licenseUnserlizeArr['no_student']) {
                    $users[] = $row['id'];
                }
            }
            
        }
    }   
 //$users[] = $row;
    }
    
   echo '<pre>';
        // print_r($users[0]);
$line = '';
for($i=0; $i < count($users) ;$i++){
if($i == 0){
$line .= '(id ='.$users[$i];

}else{
if($i == count($users) -1 ){
$line .= ' or id =' .$users[$i].')';
}else{
$line .= ' or id =' .$users[$i];
}
}

}
print_r($line);

    ?>