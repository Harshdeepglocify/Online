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

$admin_license =$_SESSION['User']['license'];
$teacher_data = query("SELECT concat(firstname,' ',lastname) teacher_name FROM `user` WHERE `license`='" . $admin_license . "' AND `role`='teacher' AND is_admin='0'");
$nos_of_teacher =$teacher_data->num_rows;

$is_expired = check_expire_or_not();


$licenseArr = get_license_details_all($admin_license);
$no_of_licence = get_license_data($admin_license);
$totalSeat = teacherSeatLimitTotal($admin_license);

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
                <?php
                
                //$users = get_user_by_role('student');
                $args = array(
                    'teacher_code' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
                    'role' => 'student',
                );
                $users = get_users($args);
                ?>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <?php if($is_expired){?>
                        <div class="col-lg-12 expire-error-msg" style="margin-bottom:10px;">
                            <p>Your license is expired!</p>
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
                                <header class="panel-heading">
                                </header>
                                <div class="table-responsive">
                                    <?php 
                                    $student_seat_total = 0;
                                    if (!empty($licenseArr)) {
                                        $used = $no_of_licence['no_student_use'];
                                        $studentUsed = $no_of_licence['no_student'];
                                        if(!empty($totalSeat) && isset($totalSeat['total'])){
                                            $student_seat_total = $totalSeat['total'];
                                        }
                                    }
                                    ?>    
                                    <table id="student_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs" border="2">
                                        <thead>
                                            <tr>

                                                <td><h4 class="Students-title-cs"><b>Teacher</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>School</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Students (<?php echo $student_seat_total . ' of ' . $studentUsed .' seats assigned'; ?>)</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Student Seat Limit <?php echo $used . ' of ' . $studentUsed.' seats active'; ?></b></h4></td>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="box-body panel student-title-main-init student-add-custom">
                                <div class='student-add-custom-row teacher-student-list'>
                                <?php
                                $used = $no_of_licence['no_teacher_use'];
                                $studentUsed = $no_of_licence['no_teacher'];
                                if ($no_of_licence['no_teacher'] > 0) {
                                    $left = $no_of_licence['no_teacher'] - $no_of_licence['no_teacher_use'];
                                }
                                $availseat = 0;
                                if ($left > 0) {
                                    $availseat = $left;
                                }
                                if (!empty($no_of_licence) && isset($no_of_licence['no_teacher_use'])) {
                                    echo "<div class='student-add-column'><div class='student-add-column-list'>";

                                    /* START : PUT Download Option  ON 02-09-2021 ADDED BY PHP DEV 3 */
                                    if(!$is_expired){
                                        if ($no_of_licence['no_teacher_use'] != $no_of_licence['no_teacher']) {
                                            ?>
                                             <div class="new-option-section">
                                                <button type="button" class="dashboard-settings-btn btn-block" onclick="window.location.href='<?php echo ADMIN_URL . 'teacher/add-new-teacher.php'; ?>'">
                                                   <i class="fa fa-plus"></i> Add Teacher
                                                </button>
                                                <!--<a href="<?php //echo ADMIN_URL . "teacher/add-new-teacher.php"; ?>" class="dashboard-settings-btn btn-block"><i class="fa fa-plus"></i>Add Teacher</a>-->
                                                <button class="new-option-btn" aria-label="Add teacher more options">
                                                    <i class="fa fa-chevron-down"></i>
                                                </button>
                                                </div>
                                        <?php } else {
                                            ?>
                                             <div class="new-option-section">
                                                <button type="button" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#more-license-modal">
                                                    <i class="fa fa-plus"></i> Add Teacher
                                                </button>
                                                <!--<a href="javascript:void(0);" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#more-license-modal"><i class="fa fa-plus"></i>Add Teacher</a>-->
                                                <button class="new-option-btn" aria-label="Add teacher more options">
                                                    <i class="fa fa-chevron-down"></i>
                                                </button>
                                           </div>
                                            <?php
                                        }

                                        ?>
                                        <div class="new-option-section-wrap" style="display: none;">
                                            <ul class="new-option">
                                                <li> 
                                                    <button type="button" class="teacher-import-csv">
                                                        Import Teacher List CSV
                                                    </button>    
                                                    <!--<a href="javascript:;" class="teacher-import-csv" >Import Teacher List CSV</a>-->
                                                </li>
                                                <li> 
                                                    <button type="button" class="teacher-export-to-csv">
                                                        Export Teacher List CSV
                                                    </button>    
                                                    <!--<a href="javascript:;" class="teacher-export-to-csv" >Export Teacher List CSV</a>-->
                                                </li>
                                                <li>
                                                    <button type="button" class="teacher-export-to-pdf" onclick="window.location.href='<?php echo ADMIN_URL . 'teacher/export_teacher_data_pdf.php'; ?>'">
                                                        Export Teacher List PDF
                                                    </button>   
                                                    <!--<a href="<?php //echo ADMIN_URL . "teacher/export_teacher_data_pdf.php";?>" class="teacher-export-to-pdf" >Export Teacher List PDF</a>-->
                                                </li>
                                            </ul>
                                        </div>
                                        <?php
                                    }
                                    /* END : PUT Download Option  ON 02-09-2021 ADDED BY PHP DEV 3*/                                    
                                    echo "</div></div>";
                                    ?>
                                    <div class="student-add-column-icons"><h4 class='student-custimize-text'>Teacher Seats in use:  <span><?php echo $used . ' / ' . $studentUsed; ?></span></h4></div>
                                    <div class="teacher_main_wrap">
                                        <?php if(!$is_expired){?>
                                            <button type="button" class="dashboard-settings-btn btn-block updateLimitModel">
                                                <i class="fa fa-exchange"></i> Update Student Limit
                                            </button>   
                                            <!--<a href="javascript:void(0);" class="dashboard-settings-btn btn-block updateLimitModel"><i class="fa fa-exchange"></i>Update Student Limit</a>-->
                                            
                                            <button type="button" class="dashboard-settings-btn btn-block update-pass">
                                                <i class="fa fa-lock"></i> Change Password
                                            </button>   
                                            <!--<a href="javascript:void(0);" class="dashboard-settings-btn btn-block update-pass"><i class="fa fa-lock"></i>Change Password</a>-->                                          
                                        <?php }?>
                                        <button type="button" class="dashboard-settings-btn btn-block delete-href">
                                            <i class="fa fa-trash"></i> Delete Teacher
                                        </button> 
                                        <!--<a href="javascript:void(0);" class="dashboard-settings-btn btn-block delete-href"><i class="fa fa-trash"></i>Delete Teacher</a>-->
                                    </div>
                                    
                                    <?php
                                }                               
                                ?>
                                </div>
                            </div>
  <input type='hidden' id='currntuser' name='currntuser' value="<?=$_SESSION['User']['id']?>">
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
                        <p>All seats are in use. To add more teachers you will need to delete a current teacher or purchase additional teacher seats.</p>
                    </div>
                    <div class="modal-footer">
                        <a class="dashboard-settings-btn btn btn-block" href="mailto:sales@accessibyte.com?subject=Additional teacher seats for [<?php echo $_SESSION['User']['license']; ?>]">Contact us for additional seats</a>
                    </div>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- ADD NEW LICENSE KEY -->
        <div class="modal fade" id="add-new-license-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Assign License</h4>
                    </div>
                    <form id="add-new-license-form" name="add-new-license-form" method="POST">
                        <div class="modal-body">
                            <!--<input type="hidden" name="login_user_id" id="login_user_id" value="">-->
                            <div class="form-group">
                                <input class="form-control" type="text" name="add_new_license" id="add_new_license" autofocus>
                                <div class="fa fa-spinner fa-spin spinner_loader" style="display: none" aria-hidden="true"></div>
                                <span id="license_error" style="color:red;"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" name="AddNewLicenseSubmit" class="btn btn-primary add-new-license-btn">Assign License</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- ADD NEW LICENSE KEY END -->
        <!-- Delete multiple student start -->
        <div class="modal fade" id="confirm-delete">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closemain" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Delete Teacher</h4>
                    </div>
                    <form id="delete-student-form" name="delete-student-form" method="POST">
                        <div class="modal-body">
                            <div class='hasNoStudent'></div>
                            <div class='hasStudent'>
                                <div class="form-group">
                                    <p>The Following Teacher will be deleted:</p>
                                    <span class='studentList'></span>
                                </div>
                                <div class="form-group">
                                    <p>All data belonging to that teacher will be deleted. This cannot be undone.</p>
                                    <p>After being deleted, the teacher seat can be used to create a new Teacher Dashboard account.</p>
                                </div>
                                <div class="form-group">
                                    <p>To confirm, type DELETE in the field below, then click the Delete Teacher button.</p>
                                    <div class="delete-student-multi-wrap">
                                        <input class="form-control delete_multi_teacher_text" type="text" name="delete_text" id="delete_text" autofocus>
                                        <button type="button" name="delete_multi_teacher" class="btn dashboard-settings-btn delete_multi_teacher_btn">Permanently Delete Teachers</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close btn dashboard-settings-btn" data-dismiss="modal" aria-label="Close">Cancel</button>
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
                        <h4 class="modal-title">Update Teacher password</h4>
                    </div>
                    <form id="update-pass-student-form" name="update-pass-student-form" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <p>The Following Teacher password update:</p>
                                <span class='studentList'></span>
                            </div>
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
                                <button type="button" name="update_multi_teacher_pass" class="btn dashboard-settings-btn update_multi_teacher_pass_btn">Update Password</button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close btn dashboard-settings-btn" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Update student Password  END -->
        <!-- Update student Password start -->
        <div class="modal fade" id="update-lic-limit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closemain" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Update license limit</h4>
                    </div>
                     <form id="upload_csv" method="post" enctype="multipart/form-data">    <div class="modal-body">
                            <div class="form-group">
                                <p>The Following Teacher seat limit update:</p>
                                <span class='teacherList'></span>
                            </div>
                            <div class="form-group">
                                <label><?php 
                                $sql = query('SELECT sum(seat_limit) as total_seat FROM user WHERE license="' . $_SESSION['User']['license']. '" and role="teacher"');
                                    $totallimitdata = fetch($sql);
                                    $license_student_limit = $no_of_licence['no_student'];
                                    $remianing_seat_assign = $license_student_limit - $totallimitdata['total_seat'];
                                    $remainingseatassign = 0;
                                    if($remianing_seat_assign > 0){
                                        $remainingseatassign = $remianing_seat_assign;
                                    }
                                    echo $remainingseatassign.' student seats available to be assigned. '.$totallimitdata['total_seat'].' out of '.$license_student_limit.' students already assigned to other teachers';
                                ?></label>                                
                            </div>
                            <div class="form-group">
                                <label>Student Limits</label>
                                <select name='student_list_limit' class="form-control student_list_limit" id="student_list_limit">
                                    <?php 
                                    if(isset($no_of_licence) && !empty($no_of_licence) && !empty($no_of_licence['no_student'])){
                                        echo "<option value='0'>No Limit</option>";
                                        for($i=1;$i<=$no_of_licence['no_student'];$i++){
                                            echo "<option value=".$i." >".$i."</option>";
                                        }
                                    }
                                    ?>                                   
                                </select>
                            </div>
                            <div class="form-group">
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" name="update_student_limit" class="btn dashboard-settings-btn update_student_limit">Update Limit</button>
                            <button type="button" class="close btn dashboard-settings-btn" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Update student Password  END -->

        <!-- Import Teacher Popup start -->
        <div class="modal fade" id="import-teacher-popup">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closemain" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Import Teacher</h4>
                    </div>
                    <form id="import_csv_teacher" name="import_csv_teacher" method="POST">
                        <div class="modal-body">
                            
                            <div class="form-group">
                                <label>Select File</label>
                                <input type="file" name="import_file" id="import_file" />
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                        <button type="submit" name="import_file" class="btn dashboard-settings-btn ">Import Teacher</button>
                         <button type="button" class="close btn dashboard-settings-btn" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Import Teacher Popup  END -->



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
        <script src="<?php echo ADMIN_URL ?>dist/js/custom-teacher.js"></script>

        <!-- SlimScroll 1.3.0 -->
        <script src="<?php echo ADMIN_URL ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/datatables/datatable-new/jquery.dataTables.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/datatables/datatable-new/dataTables.bootstrap4.js"></script>

        <script>
        $(document).ready(function() {
            var table = $('#student_list_table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?php echo ADMIN_URL; ?>config/config-teacher.php",
                    "type": "POST",
                    data: {'action': 'ajax_teacher_list','is_ajax' : '1'},
                },
                "paging": true,  // Enable pagination
                "lengthMenu": [10], // Set pagination options (5, 10, 25, 50 records per page)
                "pageLength": 10,
                "pagingType": "full_numbers",
                "order": [[0, 'DESC']],
                "columnDefs": [
                    {"targets": 0, "name": "firstname", 'searchable': true, 'orderable': true},
                    {"targets": 1, "name": "organization", 'searchable': true, 'orderable': true},
                    {"targets": 2, "name": "students", 'searchable': false, 'orderable': false},
                    {"targets": 3, "name": "limit", 'searchable': false, 'orderable': false},
                ],
                "drawCallback": function(settings) {

                    $('#student_list_table_paginate').attr('aria-label', 'Pagination Navigation');
                    $('#student_list_table_paginate').attr('aria-role', 'Navigation');
                    // Add aria-label to pagination buttons 
                    $('#student_list_table_paginate .paginate_button').each(function() {
                        var buttonText = $(this).text().trim();

                        // Add aria-label with button text
                        $(this).attr('aria-label', 'Go to page ' + buttonText);
                    });

                    // Add aria-label to previous and next buttons
                    $('#student_list_table_paginate .previous').attr('aria-label', 'Go to previous page');
                    $('#student_list_table_paginate .next').attr('aria-label', 'Go to next page');
                }
            });
        });
  
        $(document).on('click', '.delete-href', function (event) {
            event.preventDefault();
            var useradmin = $('#currntuser').val();
            console.log('useradmin' + useradmin );
            var records_to_del = [];
            var str = '';
            var student_count_arr = [];
            var student_count;
            var check = 1;
            $("input[name='ids[]']:checked").each(function () {
console.log('val' + $(this).val());
                if($(this).val() == useradmin){
                   alert('Please deselect your own ID');
                   check = 0;
                }else{
                    records_to_del.push($(this).val());
                str += "<li>"+ $(this).attr('data-name') +"</li>";
                student_count = $(this).attr('data-count');
                console.log('student_count'+student_count);
                check = 1;
                if(student_count > 0){
                    student_count_arr.push($(this).val());  
                }
                }
                
            });
            console.log('student_count_arr'+student_count_arr);
            var newstr  = "<ul>"+ str +"</ul>";
            console.log(records_to_del);
           
            if(check == 1){
            if (records_to_del.length == 0) {
                alert('Please select any one row!');
            } else {
             //   if($(this).val() == useradmin){
               if(student_count_arr.length != 0){
                    $('#confirm-delete').modal('show');
                    $('#confirm-delete').find('.hasStudent').hide();
                    $('#confirm-delete').find('.hasNoStudent').show();
                    $('#confirm-delete').find('.modal-body .hasNoStudent').html('The selected teacher still has students assigned to them. You must first delete or reassign these students to a different teacher');
                    setTimeout(function (){
                        $('.closemain').focus();                    
                    }, 500);
                } else {
                    $('.studentList').html(newstr);
                    $('#confirm-delete').modal('show');
                    $('#confirm-delete').find('.hasNoStudent').hide();
                    $('#confirm-delete').find('.hasStudent').show();
                    $('#confirm-delete').find('.delete_multi_teacher_btn').attr('data-count',student_count);
                    setTimeout(function (){
                        $('#delete_text').focus();
                        $('.closemain').focus();                    
                    }, 500);
                }
            //}else{
             //   alert('Please deselect your own ID');
            //}
            }
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
                $('#update-password').find('.password').focus();
                //$('#update-password').find('.update_multi_teacher_pass_btn').attr('data-id',id);
                setTimeout(function (){
                        $('#password').focus();
                        $('.closemain').focus();
                    }, 500);
            }        
        });
        $(document).on('click', '.updateLimitModel', function (event) {
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
                $('.teacherList').html(newstr);
                $('#student_list_limit').val($(this).attr('data-limit'));
                $('.update_student_limit').attr('data-id',$(this).attr('data-id')); 
                $('#update-lic-limit').modal('show');
                setTimeout(function (){
                    $('.closemain').focus();
                }, 500);
            }
        });
        /* START : Teacher Data CSV Import Popup OPEN 03-09-2021 ADDED BY PHP DEV 3 */
         $(document).on('click', '.teacher-import-csv', function (event) {
           
            $('#import-teacher-popup').modal('show');
            setTimeout(function (){
                $('.closemain').focus();
            }, 500);
        });
         /* END : Teacher Data CSV Import Popup OPEN 03-09-2021 ADDED BY PHP DEV 3  */

        /* START : Teacher Data CSV Import Code ON 03-09-2021 ADDED BY PHP DEV 3 */
        $('#import_csv_teacher').on("submit", function(e){
        
            var importFileVal = $('#import_file').val();
            if(importFileVal==''){
                alert("Please select import file");
                return false;

            }

            e.preventDefault(); //form will not submitted
            $.ajax({
                url: ADMIN_URL + 'teacher/import_teacher_data.php',
                type: "post",
                method:"POST",
                data:new FormData(this),
                contentType:false,    // The content type used when sending data to the server.
                cache:false,          // To unable request pages to be cached
                processData:false,
                success: function(response) {
                    var data =JSON.parse(response);
                    if(data.status == 1){     
                        console.log(data.error_data);                   
                        $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(data.import_teacher+" Teachers import successfully <br/>"+ data.error_data).show();                        
                    }else if(data.status == 0){
                        $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(data.msg).show();                            
                    }
                    setTimeout(function () {
                        $('.alert-msg-response').delay(5000).fadeOut(1000);
                        window.location.reload();
                    }, 3000);  
                    $('#import-teacher-popup').modal('hide');
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
