<?php include "../config/config.php"; ?>
<?php
	extract($_POST);
	if(isset($Delete)){
		
		$Add = query("delete from contact_us where id = '".$Delete."' ");
			if($Add){
				echo '1';
			}
	}
	if(isset($Status)){
		if($Status == 'No'){
			$Add = query("update  contact_us set approve = 'Yes' where id = '".$User."' ");
			if($Add){
				echo '2';
			}
		}else{
			$Add = query("update  contact_us set approve = 'No' where id = '".$User."' ");
			if($Add){
				echo '3';
			}
		}	
	}
	if(isset($ViewComment)){
		header("location:View?view=$ViewComment");
		exit;
	}		
 ?>