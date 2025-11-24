<?php
include "config/config.php";

$tt = query('SELECT * FROM user');

$row1 = mysqli_num_rows($tt);


    print_r($row1);
$i= 0;

while($row = mysqli_fetch_assoc($tt)){
    

    $tt1 = query('SELECT * FROM settings where id ="'.$row["id"].'" and item = 464 ');
    $row2 = mysqli_num_rows($tt1);
   
    if($row2 == 0){
$i++;
echo '<pre>';
        print_r('SELECT * FROM settings where id ="'.$row["id"].'" and item = 464 ');
		  echo 'sadasfffedfwef';
		print_r($row['id']);
        //NewUserDefault_ExcitingSettings($row['id']);
    }else{
		//  echo '<pre>';
		//print_r('fnwieonfew');
	}
    // 
}
print_r($i);




?>