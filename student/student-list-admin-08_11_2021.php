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
                            <div class="col-lg-12 expire-error-msg" style="margin-bottom:10px;" aria-live="assertive">
                                <p>Your license is expired!</p>
                            </div>
                        <?php }?>
                        <div class="col-lg-12">
                            <div class="box-body panel">
                                <div class="alert-msg-response" aria-live="assertive">
                                    <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert alert-dismissible fade in">
                                    </div>
                                </div>
                                <header class="panel-heading"></header>
                                <div class="table-responsive">
                                    <table id="admin_student_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs" border="2">
                                        <thead>
                                            <tr>

                                                <td><h4 class="Students-title-cs"><b>Nickname</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Username</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Teacher</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>School</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Last Active</b></h4></td>
                                                <?php if(!$is_expired){?>
                                                <td><h4 class="Students-title-cs"><b>Actions</b></h4></td>
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
                            <div class="box-body panel student-title-main-init student-add-custom">
                                <div class='student-add-custom-row'>
                                <?php
                                $licenseArr = get_license_details_all($_SESSION['User']['license']);
                                $no_of_licence = get_license_data($_SESSION['User']['license']);
                                if (!empty($no_of_licence) && isset($no_of_licence['no_student_use'])) {
                                    echo "<div class='student-add-column'><h3>";
                                    if(!$is_expired){
                                        if ($no_of_licence['no_student_use'] != $no_of_licence['no_student']) {
                                            ?>
                                            <a href="javascript:void(0);" class="dashboard-settings-btn btn-block add-student" data-toggle="modal" data-target="#add-student-model"><i class="fa fa-plus"></i>Add Student</a>
                                        <?php } else {                                        
                                            ?>
                                            <a href="javascript:void(0);" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#more-license-modal"><i class="fa fa-plus"></i>Add Student</a>
                                            <?php                                            
                                        }
                                    }
                                    $used = $no_of_licence['no_student_use'];
                                    $studentUsed = $no_of_licence['no_student'];
                                    if ($no_of_licence['no_student'] > 0) {
                                        $left = $no_of_licence['no_student'] - $no_of_licence['no_student_use'];
                                    }
                                    $availseat = 0;
                                    if ($left > 0) {
                                        $availseat = $left;
                                    }
                                    echo "</h3></div>";
                                    ?>
                                    <div class='student-add-column-two'>
                                        <!-- For expired license not display extra button -->
                                        <?php if(!$is_expired){?>
                                        <a href="javascript:void(0);" class="dashboard-settings-btn btn-block assign-student"><i class="fa fa-exchange"></i>Reassign Student</a>
                                        <a href="javascript:void(0);" class="dashboard-settings-btn btn-block update-pass"><i class="fa fa-lock"></i>Change Password</a>
                                        <?php }?>
                                        <a href="javascript:void(0);" class="dashboard-settings-btn btn-block delete-href"><i class="fa fa-trash"></i>Delete Student</a>
                                    </div>
                                    <div class="student-add-column-icons"><h4 class='student-custimize-text'>Student Seats in use:  <span><?php echo $used . ' / ' . $studentUsed; ?></span></h4></div>
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
        <div class="modal fade" id="confirm-delete">
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
                            <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Cancel">Cancel</button>
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
                                <p>The Following students will be deleted:</p>
                                <span class='studentList'></span>
                            </div>
                            <div class="form-group">
                                <p>Enter the new password below, then click the Update Password button.</p>
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
                                <button type="button" name="update_multi_student_pass" class="btn btn-primary update_multi_student_pass_btn">Update Password</button>
                                <span id="spinner_loader" class='spinner_loader' style="display: none; font-size: 6px;" ><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Cancel">Cancel</button>
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
                        <h4 class="modal-title">Reassign Students</h4>
                    </div>
                    <form id="reassign-student-form" name="reassign-student-form" method="POST">
                        <div class="modal-body">
                            <!--<input type="hidden" name="login_user_id" id="login_user_id" value="">-->
                            <div class="form-group">
                                <p>The Following students will be assigned to the selected teacher:</p>
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
                            <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Cancel">Cancel</button>
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
                            <button type="button" class="close btn btn-primary" data-dismiss="modal" aria-label="Cancel">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Add student to perticular teacher end -->


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
            $(document).ready(function() {
                var table = $('#admin_student_list_table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                        "type": "POST",
                        data: {'action': 'ajax_admin_student_list','is_ajax' : '1'},
                    },
                    "order": [[0, 'DESC']],
                    "columnDefs": [
                        {"targets": 0, "name": "firstname", 'searchable': true, 'orderable': true},
                        {"targets": 1, "name": "username", 'searchable': true, 'orderable': true},
                        {"targets": 2, "name": "teacher_name", 'searchable': true, 'orderable': true},
                        {"targets": 3, "name": "organization", 'searchable': true, 'orderable': true},
                        {"targets": 4, "name": "login", 'searchable': false, 'orderable': true},
                        <?php if(!$is_expired){?>
                        {"targets": 5, "name": "activity", 'searchable': false, 'orderable': false},
                        <?php }?>
                    ]
                });
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
            $(document).on('click', '.assign-student', function (event) {
                var records_to_del = [];
                var str = '';
                $("input[name='ids[]']:checked").each(function () {
                    records_to_del.push($(this).val());
                    str += "<li>"+ $(this).attr('data-name') +"</li>";
                });
                var newstr  = "<ul>"+ str +"</ul>";
                if (records_to_del.length == 0) {
                    alert('You must first select a student.');
                } else {
                    $('.studentList').html(newstr);
                    $('#reassign-student').modal('show');
                    setTimeout(function (){
                        $('#teacher_list').focus();
						$('.closemain').focus();          
                    }, 500);
                }
            });


        </script>
    </body>
</html>
