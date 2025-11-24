<?php
include "../config/config.php";
include "../helper/student_helper.php";

if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}

if (empty($_SESSION['User']['is_admin'])) {
    header("Location: " . ADMIN_URL);
    exit;
}
$is_expired = check_expire_or_not();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo ADMIN_Text; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->

        <?php include "../config/css.php"; ?>
        <script>
            var ADMIN_URL = '<?php echo ADMIN_URL; ?>';
        </script>
        <link href='https://fonts.googleapis.com/css?family=Roboto Slab' rel='stylesheet'>

        <style type="text/css">
            #alert-success {
                font-family: 'Roboto Slab';
                background-color: #000 !important;
                border-color: #000 !important;
                color: #fff !important;
            }
            #alert-success h4{
                font-family: 'Roboto Slab';
                font-weight: normal;
            }
            #alert-success a.close {
                color: #FFFFFF;
                opacity: 1;
                text-decoration: none;
            }
	.dataTables_length, .dataTables_filter {display: block !important;} /* Added by Joe 18-10-2024 */
	#admin_student_list_table_filter {display: none !important;} /* Added by Joe 18-10-2024 */
        </style>
        <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>plugins/datatables/datatable-new/dataTables.bootstrap4.css">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <?php include "../config/top-header.php"; ?>

            <!-- Left side column. contains the logo and sidebar -->
            <?php include "../config/left-sidebar.php"; ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <?php if($is_expired){?>
                            <div class="col-lg-12 expire-error-msg" style="margin-bottom:10px;">
                                <p><?php echo checkLicenseDayDiff(); ?> Days until all account data is permanently deleted. Your license is expired!</p>
                            </div>
                        <?php }?>
                        <div class="col-lg-12">
                            <div class="box-body panel">
                                <div class="alert-msg-response" aria-live="assertive">
                                    <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert alert-dismissible fade in">
                                    </div>
                                </div>
                                <?php  
                                if (isset($_SESSION['error_msg']) && !empty($_SESSION['error_msg'])) {
                                ?>                                                    
                                    <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" aria-live="assertive" class="alert alert-dismissable fade in">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?php echo $_SESSION['error_msg'] ?>
                                    </div>
                                    <?php
                                } unset($_SESSION['error_msg']);
                                ?>
                                <header class="panel-heading"></header>
                                <div class="table-responsive">
                                    <table id="admin_student_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs" border="2">
                                        <thead>
                                            <tr>

                                                <th><h4 class="Students-title-cs"><b>Nickname</b></h4></th>
                                                <th><h4 class="Students-title-cs"><b>Username</b></h4></th>
                                                <th><h4 class="Students-title-cs"><b>Teacher</b></h4></th>
                                                <th><h4 class="Students-title-cs"><b>School</b></h4></th>
                                                <th><h4 class="Students-title-cs"><b>Last Active</b></h4></th>
                                                <?php if(!$is_expired){?>
                                                <th><h4 class="Students-title-cs"><b>Actions</b></h4></th>
                                                <?php }?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <span id="spinner_loader" style="display:none;" class="spinner_loader_table"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                            <div class="box-body panel student-title-main-init student-add-custom">
                                <div class='student-add-custom-row student-add-custom-row-v2'>
                                <?php
                                $licenseArr = get_license_details_all($_SESSION['User']['license']);
                                $no_of_licence = get_license_data($_SESSION['User']['license']);
                                if (!empty($no_of_licence) && isset($no_of_licence['no_student_use'])) {
                                    echo "<div class='student-add-column'>";
                                    if(!$is_expired){
                                        if ($no_of_licence['no_student_use'] != $no_of_licence['no_student']) {
                                            ?>
                                            <div class="new-option-section">
                                                <button type="button" class="dashboard-settings-btn btn-block add-student" data-toggle="modal" data-target="#add-student-model">
                                                    <i class="fa fa-plus"></i> Add Student
                                                </button>
                                                <!--<a href="javascript:void(0);" class="dashboard-settings-btn btn-block add-student" data-toggle="modal" data-target="#add-student-model"><i class="fa fa-plus"></i>Add Student</a>-->
                                                <button class="new-option-btn" aria-label="Add student more options">
                                                    <i class="fa fa-chevron-down"></i>
                                                </button>
                                            </div>

                                           
                                        <?php } else {                                        
                                            ?>
                                            <div class="new-option-section">
                                                <button type="button" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#more-license-modal">
                                                    <i class="fa fa-plus"></i> Add Student
                                                </button>
                                                <!--<a href="javascript:void(0);" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#more-license-modal"><i class="fa fa-plus"></i>Add Student</a>-->
                                                <button class="new-option-btn" aria-label="Add student more options">
                                                    <i class="fa fa-chevron-down"></i>
                                                </button>
                                            </div>
                                            <?php                                            
                                        }
                                        ?>
                                        <div class="new-option-section-wrap" style="display: none;">
                                            <ul class="new-option">
                                                <li>
                                                    <button type="button" class="student-import-csv">
                                                        Import CSV
                                                    </button>
                                                    <!--<a href="javascript:void(0);" class="student-import-csv" >Import CSV</a>-->
                                                </li>
                                                <li>
                                                    <button type="button" class="student-export-to-csv" data-id="student-admin-list">
                                                        Export CSV
                                                    </button>
                                                    <!--<a href="javascript:void(0);" class="student-export-to-csv" data-id="student-admin-list">Export CSV</a>-->
                                                </li>                                                
                                                <li>
                                                    <button type="button" class="student-export-to-pdf" onclick="window.location.href='<?php echo ADMIN_URL . 'student/export_student_data_pdf.php'; ?>'">
                                                        Export PDF
                                                    </button>
                                                    <!--<a href="<?php //echo ADMIN_URL . "student/export_student_data_pdf.php";?>" class="student-export-to-pdf" >Export PDF</a>-->
                                                </li>                                                                                                
                                            </ul>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    
                                <!-- START : PUT Download Option  ON 02-09-2021 ADDED BY PHP DEV 3 -->
                                 
                                    
                                  
                                  <!-- END : PUT Download Option  ON 02-09-2021 ADDED BY PHP DEV 3 -->

                                    <?php

                                    $used = $no_of_licence['no_student_use'];
                                    $studentUsed = $no_of_licence['no_student'];
                                    if ($no_of_licence['no_student'] > 0) {
                                        $left = $no_of_licence['no_student'] - $no_of_licence['no_student_use'];
                                    }
                                    $availseat = 0;
                                    if ($left > 0) {
                                        $availseat = $left;
                                    }
                                    echo "</div>";
                                    
                                    ?>
                                    <div class="student-add-column-icons"><h4 class='student-custimize-text'>Student Seats in use:  <span><?php echo $used . ' / ' . $studentUsed; ?></span></h4></div>
                                    <?php if(!$is_expired) { ?>
                                    <div class='student-add-column'>
                                        <div class="new-option-section">
                                                <button type="button" class="dashboard-settings-btn btn-block student-export-histoy-to-csv">
                                                    <i class="fa fa-download"></i> Export Selected Typio CSV
                                                </button>
                                                <button class="new-option-btn" aria-label="Export data more options">
                                                    <i class="fa fa-chevron-down"></i>
                                                </button>
                                        </div>
                                        <div class="new-option-section-wrap" style="display: none;">
                                            <ul class="new-option">
                                                <li>
                                                    <button type="button" class="student-export-histoy-to-csv GallData">
                                                        Export All Student Typio CSV
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" class="student-download-selectedall-data student-export-data-pdf">
                                                        Download Selected all Data
                                                    </button>
                                                </li> 
                                                <li>
                                                    <button type="button" class="student-export-data-pdf GallData">
                                                        Download all Student Data
                                                    </button> 
                                                </li>                                                                                                
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <?php }?>
                                    <div class='student-add-column-two'>
                                        <!-- For expired license not display extra button -->
                                        <?php if(!$is_expired){?>
                                            <button type="button" class="dashboard-settings-btn btn-block assign-student">
                                                <i class="fa fa-exchange"></i> Reassign Student
                                            </button>
                                        <!--<a href="javascript:void(0);" class="dashboard-settings-btn btn-block assign-student"><i class="fa fa-exchange"></i>Reassign Student</a>-->
                                        <button type="button" class="dashboard-settings-btn btn-block update-pass">
                                            <i class="fa fa-lock"></i> Change Password
                                        </button>
                                        <!--<a href="javascript:void(0);" class="dashboard-settings-btn btn-block update-pass"><i class="fa fa-lock"></i>Change Password</a>-->
                                        
                                        <?php }?>

                                        <button type="button" class="dashboard-settings-btn btn-block delete-href">
                                            <i class="fa fa-trash"></i> Delete Selected Student
                                        </button>
                                        <!--<a href="javascript:void(0);" class="dashboard-settings-btn btn-block delete-href"><i class="fa fa-trash"></i>Delete Selected Student</a>-->
                                        <?php
                                            /* START : Put Delete All Student Functionality Added BY PHP DEV 6 ON 06-09-2021*/
                                            if( $is_expired ){
                                            ?>
                                            <button type="button" class="dashboard-settings-btn btn-block delete-all-button">
                                                <i class="fa fa-trash"></i> Delete All Student Data Now
                                            </button>
                                            <!--<a href="javascript:void(0);" class="dashboard-settings-btn btn-block delete-all-button"><i class="fa fa-trash"></i>Delete All Student Data Now</a>-->
                                            <?php
                                            /* END : Put Delete All Student Functionality Added BY PHP DEV 6 ON 06-09-2021*/

                                            }
                                         ?>
                                        
                                        
                                    </div>
                                    
                                    
                                    
                                    <?php
                                }
                                ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>


            </div>
<?php include "../config/footer.php"; ?>

            <!-- Control Sidebar -->
<?php include "../config/setting.php"; ?>

            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>

        </div><!-- ./wrapper -->


        <div class="modal fade" id="more-license-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Additional seat Request</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <p>All seats are in use. To add more students you will need to delete a current student or purchase additional student seats.</p>
                    </div>
                    <div class="modal-footer">
                        <a class="dashboard-settings-btn btn btn-block" href="mailto:sales@accessibyte.com?subject=Additional student seats for [<?php echo $_SESSION['User']['license']; ?>]">Contact us for additional seats</a>
                    </div>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <!-- Delete multiple student start -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delete multi student" aria-hidden="true" id="confirm-delete">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closemain" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Delete Student</h4>
                    </div>
                    <form id="delete-student-form" name="delete-student-form" method="POST">
                        <div class="modal-body">
                            <!--<input type="hidden" name="login_user_id" id="login_user_id" value="">-->
                            <div class="form-group">
                                <p>The Following students will be deleted:</p>
                                <span class='studentList'></span>
                            </div>
                            <div class="form-group">
                                <p>All data for those students will be deleted. This cannot be undone.</p>
                                <p>After being deleted, The student seats can be used to create a new student account.</p>
                            </div>
                            <div class="form-group">
                                <p>To confirm, type DELETE in the field below, then click the Delete Students button.</p>
                                <div class="delete-student-multi-wrap">
                                    <input class="form-control delete_multi_student_text" type="text" name="delete_text" id="delete_text" autofocus>
                                    <button type="button" name="delete_multi_student" class="btn btn-primary delete_multi_student_btn">Permanently Delete Students</button>
                                    <span id="update_lic_spinner" class='spinner_loader' style="display: none; font-size: 6px;" ><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Delete multiple student  END -->
        <!-- Update student Password start -->
        <div class="modal fade" id="update-password">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closemain" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Update Student password</h4>
                    </div>
                    <form id="update-pass-student-form" name="update-pass-student-form" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <p>The Following student passwords will be changed:</p>
                                <span class='studentList'></span>
                            </div>
                            <!-- <div class="form-group">
                                <p>To confirm, update password change all above student.</p>
                            </div> -->
                            <span class='error'></span>
                            <div class="form-group">
                                <label>Password *</label>
                                <input type="password" name="password" required="" id="password" class="form-control password" placeholder="Password" autofocus>
                            </div>
                            <div class="form-group">
                                <label>Password confirm *</label>
                                <input type="password" name="password_confirm" required="" id="password_confirm" class="password_confirm form-control" placeholder="Password Confirm">
                            </div>
                            <div class="form-group">
                                <button type="button" name="update_multi_student_pass" class="btn btn-primary update_multi_student_pass_btn">Update Password</button>
                                <span id="spinner_loader" class='spinner_loader' style="display: none; font-size: 6px;" ><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Update student Password  END -->
        <!-- Reassign student to perticular teacher start -->
        <div class="modal fade" id="reassign-student">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closemain" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" aria-label="Reassign Students">Reassign Students</h4>
                    </div>
                    <form id="reassign-student-form" name="reassign-student-form" method="POST">
                        <div class="modal-body">
                            <!--<input type="hidden" name="login_user_id" id="login_user_id" value="">-->
                            <div class="form-group">
                                <p>The Following students will be Assign to selected teacher:</p>
                                <span class='studentList'></span>
                            </div>
                            <div class="form-group">
                                <select name='teacher_list' class="form-control teacher_list" id="teacher_list">
                                    <?php $teacherArr = getAllTeacherWithoutAdmin();
                                    if(!empty($teacherArr)){
                                        echo "<option value=''>Select Teacher</option>";
                                        foreach($teacherArr as $val){
                                            $username = base64_decode($val['firstname']) .' '. base64_decode($val['lastname']);
                                            echo "<option value=".$val['teacher'].">".base64_decode($val['firstname'])." (".$username.")</option>";
                                        }
                                    }
                                    ?>                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" name="assign_student" class="btn btn-primary assign_student_btn">Reassign Student</button>
                                <span id="spinner_loader" class='spinner_loader' style="display: none; font-size: 6px;" ><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Reassign student to perticular teacher end -->
        <!-- Add student to perticular teacher start -->
        <div class="modal fade" id="add-student-model">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closemain" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Add Students</h4>
                    </div>
                    <form id="add-student-form" name="add-student-form" method="POST">
                        <div class="modal-body">

                            <div class="form-group">
                                <p>Add students to selected teacher:</p>
                                <span class='studentList'></span>
                            </div>
                            <div class="form-group">
                                <select name='teacher_list' class="form-control teacher_list" id="teacher_list">
                                    <?php $teacherArr = getAllTeacherWithoutAdmin();
                                    if(!empty($teacherArr)){
                                        echo "<option value=''>Select Teacher</option>";
                                        foreach($teacherArr as $val){
                                            $username = base64_decode($val['firstname']) .' '. base64_decode($val['lastname']);
                                            echo "<option value=".$val['teacher'].">".base64_decode($val['firstname'])." (".$username.")</option>";
                                        }
                                    }
                                    ?>                                    
                                </select>
                                <span class='teachername commonerror'></span>
                            </div>
                            <div class="form-group ">
                                <label><!-- First name -->Nick Name *</label>
                                <input type="text" name="firstname" id="firstname" autofocus="" required="" class="form-control firstname" placeholder="Nick Name">
                                <span class='nickname commonerror'></span>
                            </div>

                            <div class="form-group ">
                                <label>Username *</label>
                                <input type="text" name="username" id="username" required="" class="form-control uname" placeholder="Username">
                                <span class='uname commonerror'></span>
                            </div>

                            <div class="form-group">
                                <label>Password *</label>
                                <input type="password" name="password" required="" id="password" class="form-control password" placeholder="Password">
                                <span class='password commonerror'></span>
                            </div>
                            <div class="form-group">
                                <button type="button" name="add_student_btn" class="btn btn-primary dashboard-settings-btn add_student_btn">Add New Student</button>
                                <span id="spinner_loader" style="display: none; font-size: 6px;" ><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Add student to perticular teacher end -->

        <!-- Import Student Popup start -->
        <div class="modal fade" id="import-student-popup">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closemain" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Import Student</h4>
                    </div>
                    <form id="import_csv_student" name="import_csv_student" method="POST">
                        <div class="modal-body">
                            
                            <div class="form-group">
                                <label>Select File</label>
                                <input type="file" name="import_file" id="import_file" accept=".csv"/>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                         <button type="submit" name="import_file" class="btn import_csv_student dashboard-settings-btn ">Import Student</button>
                         <button type="button" class="close btn dashboard-settings-btn" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Import Student Popup  END -->                            

        <!-- jQuery 2.1.4 -->
        <script src="<?php echo ADMIN_URL ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo ADMIN_URL ?>bootstrap/js/bootstrap.min.js"></script>
        <!-- FastClick -->
        <script src="<?php echo ADMIN_URL ?>plugins/fastclick/fastclick.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo ADMIN_URL ?>dist/js/app.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/boot-box/bootbox.min.js"></script>
        <script src="<?php echo ADMIN_URL; ?>plugins/jQuery-validate/jquery.validate.js"></script>
        <script src="<?php echo ADMIN_URL ?>dist/js/custom-student.js"></script>

        <!-- SlimScroll 1.3.0 -->
        <script src="<?php echo ADMIN_URL ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/datatables/datatable-new/jquery.dataTables.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/datatables/datatable-new/dataTables.bootstrap4.js"></script>

        <script>
            


            function hideOldDates() {
                const currentDate = new Date();
                const allTdSpans = document.querySelectorAll('td.target-column-4 span');
                // Select all span elements specifically in the 4th <td> (3rd column by index) in each row
                //const allTdSpans = document.querySelectorAll('#admin_student_list_table tbody tr td:nth-child(4) span');

                allTdSpans.forEach(function(span) {
                    const dateText = span.innerText.trim();
                    const dateValue = new Date(dateText);

                    // Check if the span contains a valid date
                    if (!isNaN(dateValue.getTime())) {
                        const yearDifference = currentDate.getFullYear() - dateValue.getFullYear();

                        // Check if the date is more than 4 years old
                        if (yearDifference > 4 || (yearDifference === 4 && currentDate.getMonth() > dateValue.getMonth())) {
                            // Hide the <td> element containing the old date
                            span.parentElement.style.opacity = 0;
                        }
                    }
                });
            }

            $(document).ready(function() {
                function isBase64(str) {
                    try {
                        return btoa(atob(str)) === str;
                    } catch (err) {
                        return false;
                    }
                }
                var table = $('#admin_student_list_table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                        "type": "POST",
                        data: {'action': 'ajax_admin_student_list','is_ajax' : '1'},
                        "dataSrc": function (json) {
                            // Capture the "dataforUse" parameter from the server response
                            extraDataForUse = json.dataforUse;
                            return json.data; // Still return the regular "data" for DataTable
                        }
                        /*"dataSrc": function (json) {
                            // Process the data and decode firstname values
                            return json.data.map(function (row) {                 
                                try {
                                    // Decode Base64 encoded firstname value
                                    row[0] = atob(row[0]);
                                } catch (e) {
                                    console.error("Invalid Base64 data: ", row[0]);
                                    row[0] = "Invalid data";  // Set a default value for invalid data
                                }
                                return row;
                            });
                        },*/
                    },
                    "paging": false,
		            "lengthMenu": [25, 50, 100, 250, 500], // Set pagination options (5, 10, 25, 50 records per page) Added by Joe 18-10-2024
                    "pageLength": 500,
                    "lengthChange": true,
                    "order": [
  [0, 'asc'],    // Nickname A?Z
  [4, 'desc']    // Last Active newest?oldest
],
  columns: [
    { data: 0, name: 'firstname'    },
    { data: 1, name: 'username'     },
    { data: 2, name: 'teacher_name' },
    { data: 3, name: 'organization' },
    { data: 4, name: 'login'        },
    { data: 5, name: 'activity', orderable: false }
  ],
                    "initComplete": function(settings, json) {
                        hideOldDates();
                    },
                    "drawCallback": function(settings) {

                        $('#admin_student_list_table_paginate').attr('aria-label', 'Pagination Navigation');
                        $('#admin_student_list_table_paginate').attr('aria-role', 'Navigation');
                        // Add aria-label to pagination buttons 
                        $('#admin_student_list_table_paginate .paginate_button').each(function() {
                            var buttonText = $(this).text().trim();
                            // Add aria-label with button text
                            $(this).attr('aria-label', 'Go to page ' + buttonText);
                        });
                        // Add aria-label to previous and next buttons
                        $('#admin_student_list_table_paginate .previous').attr('aria-label', 'Go to previous page');
                        $('#admin_student_list_table_paginate .next').attr('aria-label', 'Go to next page');
                    }
                });
                table.on('draw', function() {
                    hideOldDates();
                });

                $(document).on('click', '.student-export-histoy-to-csv', function (event) {
                    var records_to_export = [];
                    var str = ''; 

                    if ($(this).hasClass('GallData')) { 
                        records_to_export.push(...extraDataForUse.map(String));
                    } else { 
                        $("input[name='ids[]']:checked").each(function () {
                            records_to_export.push($(this).val());
                            str += "<li>"+ $(this).attr('data-name') +"</li>";
                        });

                        if (records_to_export.length == 0) {
                            alert('Please select any one row!'); return false;
                        }
                    }
                    $('#spinner_loader').show();
                    //$(this).attr('href',ADMIN_URL + 'student/export_student_history_csv_data.php');
                    $.ajax({
                        url: ADMIN_URL + 'student/export_student_history_csv_data.php',
                        type: "post",
                        method:"POST",
                        data:{ ids : records_to_export },
                        success: function(data) {
                            $('#spinner_loader').hide();
                            /*
                            * Make CSV downloadable
                            */
                            var downloadLink = document.createElement("a");
                            var fileData = ['\ufeff'+data];

                            var blobObject = new Blob(fileData,{
                                type: "text/csv;charset=utf-8;"
                            });

                            var url = URL.createObjectURL(blobObject);
                            downloadLink.href = url;
                            downloadLink.download = "Student Typio History CSV.csv";

                            /*
                            * Actually download CSV
                            */
                            document.body.appendChild(downloadLink);
                            downloadLink.click();
                            document.body.removeChild(downloadLink);                                           
                        },
                        error: function() {
                            alert("An error occured, please try again.");   $('#spinner_loader').hide();       
                        }
                    });
                });

                $(document).on('click', '.student-export-data-pdf', function (event) {
                var records_to_export = [];
                var str = '';

                if ($(this).hasClass('GallData')) { 
                    records_to_export.push(...extraDataForUse.map(String));
                } else { 
                        $("input[name='ids[]']:checked").each(function () {
                            records_to_export.push($(this).val());
                            str += "<li>"+ $(this).attr('data-name') +"</li>";
                        });

                    if (records_to_export.length == 0) {
                        alert('Please select any one row!'); return false;
                    }
                }
                $('#spinner_loader').show();
                $.ajax({
                    url: ADMIN_URL + 'student/export_student_all_data_pdf.php',
                    type: "post",
                    method:"POST",
                    data:{ids : records_to_export},
                    success: function(response) {
                       $('#spinner_loader').hide(); window.open(response);                             
                    },
                    error: function() {
                        alert("An error occured, please try again.");    $('#spinner_loader').hide();      
                    }
                });
            });
            });
            
            /* START : Put Delete All Student Functionality Added BY PHP DEV 6 ON 06-09-2021 */
            $(document).on('click', '.delete-all-button', function (event) {
                if(confirm("Are you sure? All data for all students will be delete. This cannot be undone.")){
                    $("input[name='ids[]']").each(function () {
                        $(this).prop('checked', true);
                    });
                    var records_to_del = [];
                    var str = '';
                    $("input[name='ids[]']:checked").each(function () {
                        records_to_del.push($(this).val());
                        str += "<li>"+ $(this).attr('data-name') +"</li>";
                    });
                    var newstr  = "<ul>"+ str +"</ul>";
                    if (records_to_del.length == 0) {
                        alert('Please select any one row!');
                    } else {
                        $('.studentList').html(newstr);
                        $('#confirm-delete').modal('show');
                        setTimeout(function (){
                            $('#delete_text').focus();
                            $('.closemain').focus();
                        }, 500);
                    }
                    //$(".delete-href").click();
                }
            });
            /* END : Put Delete All Student Functionality Added BY PHP DEV 6 ON 06-09-2021 */
            $(document).on('click', '.delete-href', function (event) {
                var records_to_del = [];
                var str = '';
                $("input[name='ids[]']:checked").each(function () {
                    records_to_del.push($(this).val());
                    str += "<li>"+ $(this).attr('data-name') +"</li>";
                });
                var newstr  = "<ul>"+ str +"</ul>";
                if (records_to_del.length == 0) {
                    alert('Please select any one row!');
                } else {
                    $('.studentList').html(newstr);
                    $('#confirm-delete').modal('show');
                    setTimeout(function (){
                        $('#delete_text').focus();
                        $('.closemain').focus();                    
                    }, 500);                    
                }
            });

            $(document).on('click', '.update-pass', function (event) {
                var records_to_del = [];
                var str = '';
                $("input[name='ids[]']:checked").each(function () {
                    records_to_del.push($(this).val());
                    str += "<li>"+ $(this).attr('data-name') +"</li>";
                });
                var newstr  = "<ul>"+ str +"</ul>";
                if (records_to_del.length == 0) {
                    alert('Please select any one row!');
                } else {
                    $('.studentList').html(newstr);
                    $('#update-password').modal('show');
                    setTimeout(function (){
                        $('#password').focus();
                        $('.closemain').focus();         
                    }, 500);
                }
            });
            $(document).on('click', '.student-import-csv', function (event) {
                $('#import-student-popup').modal('show');
                setTimeout(function (){
                    $('.closemain').focus();
                }, 500);
            });
            $(document).on('click', '.assign-student', function (event) {
                var records_to_del = [];
                var str = '';
                $("input[name='ids[]']:checked").each(function () {
                    records_to_del.push($(this).val());
                    str += "<li>"+ $(this).attr('data-name') +"</li>";
                });
                var newstr  = "<ul>"+ str +"</ul>";
                if (records_to_del.length == 0) {
                    alert('You must first select a teacher.');
                } else {
                    $('.studentList').html(newstr);
                    $('#reassign-student').modal('show');
                    setTimeout(function (){
                        $('#teacher_list').focus();
                        $('.closemain').focus();         
                    }, 500);
                }
            });

            /* START : Teacher Data CSV Import Code ON 03-09-2021 ADDED BY PHP DEV 3 */
            $('#import_csv_student').on("submit", function(e){
        
                var importFileVal = $('#import_file').val();
                if(importFileVal==''){
                    alert("Please select import file");
                    return false;

                }
                e.preventDefault(); //form will not submitted
                $.ajax({
                    url: ADMIN_URL + 'student/import_student_data.php',
                    type: "post",
                    method:"POST",
                    data:new FormData(this),
                    contentType:false,    // The content type used when sending data to the server.
                    cache:false,          // To unable request pages to be cached
                    processData:false,
                    success: function(response) {
                        var data =JSON.parse(response);
                        if(data.status == 1) {                           
                            $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(data.import_student+ " students import successfully <br/>" + data.error_data).show();                            
                        } else if(data.status == 0) {
                            $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(data.msg).show();                            
                        }
                        setTimeout(function () {
                            $('.alert-msg-response').delay(5000).fadeOut(1000);
                            window.location.reload();
                        }, 3000);  
                        $('#import-student-popup').modal('hide');
                        
                    },
                    error: function() {
                        alert("An error occured, please try again.");         
                    }
                });

            });
            /* END : Teacher Data CSV Import Code ON 03-09-2021 ADDED BY PHP DEV 3 */

        </script>
    </body>
</html>
