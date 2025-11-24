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

 //echo "<pre>"; print_r($_SESSION); exit;
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
                if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                    ?>
                                                    <!--<div style="font-family: Roboto Slab, serif; background-color: #000;" class="alert alert-<?php // echo $_SESSION['error']['color']          ?> alert-dismissable fade in">-->
                    <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" aria-live="assertive" class="alert  alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?php echo $_SESSION['error']['message'] ?>
                    </div>
                    <?php
                } unset($_SESSION['error']);

                //$users = get_user_by_role('student');             
                $args = array(
                    'teacher_code' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
                    'role' => 'student',
                );
                $users = get_users($args);

                //echo "<pre>";print_r($date);
                ?>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box-body panel">
                                <div class="alert-msg-response">
                                    <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert alert-dismissible fade in">
                                    </div>
                                </div>
                                <header class="panel-heading">
                                    <!-- <h4><b>Students</b></h4>
                                    <div class="student-list-head-icon">
                                                                        <img src="<?php //echo ADMIN_URL.'img/user-one.png';          ?>" class="head-typio">
                                                                        <img src="<?php // echo ADMIN_URL.'img/user-two.png';          ?>" class="head-quick-card">
                                                                        <img src="<?php //echo ADMIN_URL.'img/user-three.png';          ?>" class="head-arcade">
                                                                    </div> -->
                                </header>
                                <div class="table-responsive">
                                    <table id="student_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs" border="2">
                                        <thead>
                                            <tr>

                                                <td><h4 class="Students-title-cs"><b>Teacher</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>School</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Student's</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Student See Limit</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Actions</b></h4></td> 
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
                                <div class='student-add-custom-row'>
                                <?php
                                $licenseArr = get_license_details_all($_SESSION['User']['license']);
                                $no_of_licence = get_license_data($_SESSION['User']['license']);

                                if (!empty($no_of_licence) && isset($no_of_licence['no_teacher_use'])) {
                                    echo "<div class='student-add-column'><h3>";

                                    if ($no_of_licence['no_teacher_use'] != $no_of_licence['no_teacher']) {
                                        ?>
                                        <a href="<?php echo ADMIN_URL . "teacher/add-new-teacher.php"; ?>" class="dashboard-settings-btn btn-block"><i class="fa fa-plus"></i>Add Teacher</a>
                                    <?php } else {
                                        ?>
                                        <a href="javascript:void(0);" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#more-license-modal"><i class="fa fa-plus"></i>Add Teacher</a>
                                        <?php
                                    }                                 
                                    $used = $no_of_licence['no_teacher_use'];
                                    $studentUsed = $no_of_licence['no_teacher'];
                                    if ($no_of_licence['no_teacher'] > 0) {
                                        $left = $no_of_licence['no_teacher'] - $no_of_licence['no_teacher_use'];
                                    }
                                    $availseat = 0;
                                    if ($left > 0) {
                                        $availseat = $left;
                                    }
                                    echo "</h3></div>";
                                    ?>
                                    <div class="student-add-column-two">
                                        <a href="javascript:void(0);" class="dashboard-settings-btn btn-block update-pass"><i class="fa fa-plus"></i>Change Password</a>
                                        <a href="javascript:void(0);" class="dashboard-settings-btn btn-block delete-href"><i class="fa fa-plus"></i>Delete Teacher</a>
                                    </div>                                    
                                    <div class="student-add-column-icons"><h4 class='student-custimize-text'>Teacher Seats in use :  <span><?php echo $used . ' / ' . $studentUsed; ?></span></h4></div>                                    
                                    <?php
                                } else {
                                    echo "<div class='student-add-column'><h3>";
                                    if (!empty($licenseArr)) {
                                        $left = isset($licenseArr['activations_left']) ? $licenseArr['activations_left'] : '';
                                        if (!empty($left) && $left > 0) {
                                            ?>
                                            <a href="<?php echo ADMIN_URL . "teacher/add-new-teacher.php"; ?>" class="dashboard-settings-btn btn-block"><i class="fa fa-plus"></i>Add Teacher</a>
                                        <?php } else { ?>
                                            <a href="javascript:void(0);" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#more-license-modal"><i class="fa fa-plus"></i>Add Teacher</a>
                                            <?php
                                        }
                                    }
                                    echo "</h3></div>";
                                    ?>
                                    <div class="student-add-column-two">
                                        <a href="javascript:void(0);" class="dashboard-settings-btn btn-block update-pass"><i class="fa fa-plus"></i>Change Password</a>
                                        <a href="javascript:void(0);" class="dashboard-settings-btn btn-block delete-href"><i class="fa fa-plus"></i>Delete Teacher</a>
                                    </div>
                                    <?php
                                    if (!empty($licenseArr)) {
                                        $totle = isset($licenseArr['license_limit']) ? $licenseArr['license_limit'] - 1 : '';
                                        $left = isset($licenseArr['activations_left']) ? $licenseArr['activations_left'] : '';
                                        $used = $totle - $left;
                                        ?>
                                        <div class="student-add-column-icons"><h4 class='student-custimize-text'>Teacher seats in use :  <span><?php echo $used . ' / ' . $totle; ?></span></h4></div>                                        
                                    <?php
                                    }
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

        <div class="modal fade" id="active-license-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Add License</h4>
                    </div>
                    <form id="active-license-form" name="active-license-form" method="POST">
                        <div class="modal-body">	            		
                            <input type="hidden" name="user_id" id="user_id" value="">
                            <div class="form-group">
                                <input class="form-control" type="text" name="license" id="edd_license" autofocus>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="ActiveLicenseSubmit" class="dashboard-settings-btn btn btn-primary edit-lessons-btn">Add License</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Delete Teacher</h4>
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
                                <input class="form-control delete_multi_teacher_text" type="text" name="delete_text" id="delete_text" autofocus>
                                <button type="button" name="delete_multi_teacher" class="btn btn-primary delete_multi_teacher_btn">Permanently Delete Teachers</button>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Update Teacher password</h4>
                    </div>
                    <form id="update-pass-student-form" name="update-pass-student-form" method="POST">
                        <div class="modal-body">	            		
                            <div class="form-group">
                                <p>The Following Teachers will be deleted:</p>
                                <span class='studentList'></span>
                            </div>
                            <div class="form-group">
                                <p>To confirm, update password change all above student.</p>                                
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
                                <button type="button" name="update_multi_teacher_pass" class="btn btn-primary update_multi_teacher_pass_btn">Update Password</button>
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
                "order": [[0, 'DESC']],
                "columnDefs": [
                    {"targets": 0, "name": "firstname", 'searchable': true, 'orderable': true},
                    {"targets": 1, "name": "username", 'searchable': true, 'orderable': true},
                    {"targets": 2, "name": "students", 'searchable': false, 'orderable': false},
                    {"targets": 3, "name": "limit", 'searchable': false, 'orderable': false},
                    {"targets": 4, "name": "activity", 'searchable': false, 'orderable': false},
                ]
            });
        });    
            $(document).on('click', '#sync_lic', function () {
                $.ajax({
                    url: '<?php echo ADMIN_URL; ?>cron/fetch_licenses.php',
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function () {
                        $('#sync_lic').attr('disabled', 'disabled');
                        $('#icon_loader').show();
                        $('#sync_lic_msg').html('Synchronization is in progress...');

                    },
                    success: function (result) {
                        location.reload();
                    },
                    complete: function () {
                        $('#icon_loader').hide();
                        $('#sync_lic_msg').html('<span style="color:green;">License Syncronization Successfully.</span>');
                        $('#sync_lic').removeAttr('disabled', 'disabled');
                    }

                });
            });

            $(document).on('click', '#newlicense_popup', function () {
                $('#add_new_license').val('');
                setTimeout(function () {
                    $('#add_new_license').focus();
                }, 500);
                
            });
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
                }
            });
            
        </script>
    </body>
</html>