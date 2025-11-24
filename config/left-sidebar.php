<?php 
//
$id = $_SESSION['User']['id'];

//$exists = checkUserExists($id);
function checkUserExists($id) {
     global $con;
     

	$query = "SELECT * FROM report_downloads WHERE user_id = '$id' LIMIT 1";
    $result = mysqli_query($con, $query);
    $exists = (mysqli_num_rows($result) > 0) ? 1 : 0;

    return $exists;
}
$exists = checkUserExists($id);

?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar -->

    <section class="sidebar">
		<?php 
			$is_expired = check_expire_or_not();
		?>
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="user-info-init">
                <div class="user-info-sub-name"><?php 
                if(isset($_SESSION['User'])){
                    echo base64_decode($_SESSION['User']['firstname']) . ' ' . base64_decode($_SESSION['User']['lastname']);
                }
                $users_licenses_details = users_license_list($_SESSION['User']['id']);
                $lic = explode('-',$users_licenses_details[0]['license']);
                 ?></div>
                <p class="user-info-sub-rock"><?php echo base64_decode($_SESSION['User']['organization']); ?></p>
                <!--<div class="user-info-sub-teacher">License:</div>
                <p class="user-info-sub-code"><?php echo $_SESSION['User']['license']; ?></p>
				<p class="user-info-sub-code"><?php
                    $users_licenses_details = users_license_list($_SESSION['User']['id']);                          
                    if($users_licenses_details['0']['days_left'] == 'inactive'){
                        echo '(Inactive)';
                    }else if($users_licenses_details['0']['days_left'] == 'expired'){
                        echo '(Expired)';
                    }else if($users_licenses_details['0']['expires'] == 'lifetime'){
                        echo '(Lifetime)';
                    }else{
                        echo '('.$users_licenses_details['0']['days_left'].' days remaining)';
                    }
                ?>
                </p>-->
            </div>
        </div>

        <!-- /.search form -->
        <!-- sidebar config: : style can be found in sidebar-->
        <ul class="sidebar-menu"><br />
			<?php if(!$is_expired){ ?>
            <!--<li class="treeview <?php //echo basename($_SERVER['PHP_SELF']) == 'index.php' && $_SERVER['REQUEST_URI'] == "/accessibyte/online/" ? ' active' : ''; ?>"  >
                <a href="<?php //echo ADMIN_URL; ?>" class="accessibyte-link-dash-sidebar">
                    <i class="fa fa-dashboard" aria-hidden="true"></i> <span>Dashboard</span>
                </a>
            </li>-->
			<?php }?>
            <?php if(isset($_SESSION['User']['is_admin']) &&  $_SESSION['User']['is_admin'] == 1 ) { ?>
                <li class="treeview <?php echo basename($_SERVER['PHP_SELF']) == 'student-list.php' || basename($_SERVER['PHP_SELF']) == 'add-new-student.php' ? ' active' : ''; ?>">
                    <a href="<?php echo ADMIN_URL . "student/student-list.php"; ?>" class="accessibyte-link-dash-sidebar">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>My Students</span>                
                    </a>
                </li>
                <li class="treeview <?php echo basename($_SERVER['PHP_SELF']) == 'student-list-admin.php' || basename($_SERVER['PHP_SELF']) == 'add-new-student.php' ? ' active' : ''; ?>">
                    <a href="<?php echo ADMIN_URL . "student/student-list-admin.php"; ?>" class="accessibyte-link-dash-sidebar">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>All Students</span>                
                    </a>
                </li>
            <?php } else { ?>
                <li class="treeview <?php echo basename($_SERVER['PHP_SELF']) == 'student-list.php' || basename($_SERVER['PHP_SELF']) == 'add-new-student.php' ? ' active' : ''; ?>">
                <a href="<?php echo ADMIN_URL . "student/student-list.php"; ?>" class="accessibyte-link-dash-sidebar">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>My Students</span>                
                </a>
            </li>
            <?php } ?>
			<?php // if(isset($_SESSION['User']['is_admin']) &&  $_SESSION['User']['is_admin'] == 1 ) { ?>
            <li class="treeview <?php if($exists == 1){ }else{ echo 'history_hide'; }  echo basename($_SERVER['PHP_SELF']) == 'student-reports.php' || basename($_SERVER['PHP_SELF']) == 'astudent-reports.php' ? ' active' : ''; ?>" id='student_rpt_mnu'>
                <a href="<?php echo ADMIN_URL . "student/student-reports.php"; ?>" class="accessibyte-link-dash-sidebar">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Student Reports</span>                
                </a>
            </li>   
            <?php //} ?>
			<?php if(isset($_SESSION['User']['is_admin']) &&  $_SESSION['User']['is_admin'] == 1 ) { ?>
            <li class="treeview <?php echo basename($_SERVER['PHP_SELF']) == 'teacher-list.php' || basename($_SERVER['PHP_SELF']) == 'add-new-teacher.php' ? ' active' : ''; ?>">
                <a href="<?php echo ADMIN_URL . "teacher/teacher-list.php"; ?>" class="accessibyte-link-dash-sidebar">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Teachers List</span>                
                </a>
            </li>   
            <?php } ?>
            <!--<li class="treeview <?php // echo basename($_SERVER['PHP_SELF']) == 'student-list-old.php' || basename($_SERVER['PHP_SELF']) == 'add-new-student.php' ? ' active' : ''; ?>">
                <a href="<?php // echo ADMIN_URL . "student/student-list-old.php"; ?>" class="accessibyte-link-dash-sidebar">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Students List (old)</span>                
                </a>
            </li>-->
			
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'typio.php' ? ' active' : ''; ?>">
                <a href="<?php echo ADMIN_URL . "student/typio.php"; ?>" class="accessibyte-link-dash-sidebar">
                    <i class="fa fa-keyboard-o" aria-hidden="true"></i> 
                    <span>Typio Docs</span>
                </a>
            </li>     
<?php if(!$is_expired && $lic[0] != 'TCHI'){ ?>        
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'quick-cards.php' ? ' active' : ''; ?>">
                <a href="<?php echo ADMIN_URL . "student/quick-cards.php"; ?>" class="accessibyte-link-dash-sidebar">
                    <i class="fa fa-laptop" aria-hidden="true"></i> 
                    <span>Quick Cards Docs</span>
                </a>
            </li> 

            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'arcade.php' ? ' active' : ''; ?>">
              <a href="<?php echo ADMIN_URL . "student/arcade.php"; ?>" class="accessibyte-link-dash-sidebar">
                <i class="fa fa-gamepad" aria-hidden="true"></i> 
                <span>Arcade Docs</span>
              </a>
			</li>

            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'pro-pack.php' ? ' active' : ''; ?>">
                <a href="<?php echo ADMIN_URL . "student/pro-pack.php"; ?>" class="accessibyte-link-dash-sidebar">
                    <i class="fa fa-book" aria-hidden="true"></i> 
                    <span>Pro Pack Docs</span>
                </a>
            </li>
			<?php }?>    
            <!-- <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'profane-words.php' ? ' active' : ''; ?>">
                <a href="<?php echo ADMIN_URL . "student/profane-words.php"; ?>">
                    <i class="fa fa-file-word-o" aria-hidden="true"></i> 
                    <span>Profane Words</span>
                </a>
            </li> -->
			
			<li class="<?php echo basename($_SERVER['PHP_SELF']) == 'my-account.php' ? ' active' : ''; ?>">
                <a href="<?php echo ADMIN_URL . "my-account.php"; ?>" class="accessibyte-link-dash-sidebar">
                    <i class="fa fa-laptop" aria-hidden="true"></i> 
                    <span>My Account</span>
                </a>
            </li>
			<li class="">
                <a href="<?php echo WP_URL . "knowledge-base"; ?>" target="_blank" class="accessibyte-link-dash-sidebar">
                    <i class="fa fa-book" aria-hidden="true"></i> 
                    <span>Help</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>