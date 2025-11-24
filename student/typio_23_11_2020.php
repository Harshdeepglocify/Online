<?php
include "../config/config-student.php";

if (!$_SESSION['User']) {

    header("Location: " . ADMIN_URL . 'login');

    exit;
}
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

                        <!-- Title -->

                        <div class="col-md-12">

                            <div class="student-title-main-init">

                                <h3>Typio Lessons</h3>

                            </div>

                        </div>

                        <!-- chart one -->

                        <div class="col-md-12">

                            <div class="chart-one-main-init">

                                <div class="typio-sidebar-alert alert alert-dismissible" id="alert-success" aria-live="assertive"></div>



                                <!--   Typio Sectoin two   -->

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="custom_lessons">

                                            <div class="with-border">

                                                <h3 class="dashboard-h2">Custom Lessons</h3>

                                            </div>

                                            <div class="quick-ajax-response " aria-live="assertive">

                                                <div style="" id="alert-success" class="alert alert-dismissible fade in alert-warning" >

                                                </div>

                                            </div>

                                            <!-- /.box-header -->

                                            <div class="box-body typio-custom-lessons">

                                                <table class="table table-bordered" id="typio-custom-lessons">

                                                    <tbody>

                                                        <tr>

                                                            <th style="width: 10px !important">&nbsp;</th>

                                                            <th>Lesson</th>

                                                            <th style="width: 40px  !important">Edit</th>

                                                            <th style="width: 40px  !important">Delete</th>

                                                        </tr>



                                                        <?php
                                                        $text_typio_lessons_result = get_data_from_text_table($_SESSION['User']['id'], 'Typio-OL');



                                                        if (!empty($text_typio_lessons_result)) {



                                                            $text_typio_lessons_count = 0;



                                                            foreach ($text_typio_lessons_result as $text_typio_lessons_row_key => $text_typio_lessons_row_value) {



                                                                $text_typio_lessons_count++;



                                                                echo '<tr class="lessons_tr_' . $text_typio_lessons_row_value['table_id'] . '">';

                                                                echo '<td><input type="checkbox" name="share_typio_ids[]" value="' . $text_typio_lessons_row_value['table_id'] . '" class="share_typio_ids"></td>';

                                                                echo '<td>' . $text_typio_lessons_row_value['title'] . '</td>';

                                                                echo '<td><a href="javascript:void(0)" class="badge bg-green edit-lessons-modal" data-id="' . $text_typio_lessons_row_value['table_id'] . '">

                                                                  <i class="fa  fa-edit (alias)"></i></a>

                                                          </td>

                                                          <td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red lesson-delete" data-id="' . $text_typio_lessons_row_value['table_id'] . '">

                                                          <i class="fa fa-trash-o"></i></a>

                                                          </td>';

                                                                echo '</tr>';
                                                            }
                                                        }
                                                        ?>

                                                    </tbody>

                                                </table>
                                                <button type="button" style="margin-top:15px;" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-typio-modal" >Create New</button>
                                                <button type="button" class="dashboard-settings-btn btn-block  typio-import-modal " data-toggle="modal" data-target="#typio-import-modal">Import Typio</button>
                                            </div>



                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="">

                                            <div class="with-border">

                                                <h3 class="dashboard-h2">Share With</h3>

                                            </div>

                                            <div class="box-body">

                                                <!-- form -->

                                                <form role="form" action="" method="POST" id="share_typio_form">

                                                    <div class="form-group" id="share_typio_form_box">

                                                        <label>Students</label>

                                                        <table class="table table-bordered" id="share-table">
                                                            <tbody>

                                                                <?php
//Argument for data

                                                                $args = array(
                                                                    'teacher_code' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
                                                                    'role' => 'student',
                                                                );

                                                                $student_data = get_users($args);



                                                                if (!empty($student_data)) {

                                                                    foreach ($student_data as $key => $value) {
                                                                        ?>
                                                                        <tr>
                                                                            <td><input type="checkbox" name="share_user_list[]" value="<?php echo $value['id']; ?>" class="share_user_ids" id="<?php echo $value['id']?>"><label for="<?php echo $value['id']?>"><?php echo base64_decode($value['firstname']) . ' ' . base64_decode($value['lastname']) ?></label></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>

                                                            </tbody>
                                                        </table>

                                                    </div>

                                                    <div class="form-group action-btn-chart-init">

                                                        <button type="button" class="dashboard-settings-btn btn-block typio-share-submit">Share</button>

                                                    </div>

                                                </form>

                                                <!-- End form -->

                                            </div>

                                        </div>

                                    </div>



                                </div>

                                <!--   End Typio Sectoin   -->

                                <div class="row">

                                    <div class="space-margin-bottom-50"></div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

            <!--   End Typio Sectoin four   -->

            <!-- add typio-modal -->
            <div class="modal fade" id="add-typio-modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title color">Create New Lesson </h4>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="lessons-add-form">

                                <div class="form-group">

                                    <label>Type lesson title here...</label>

                                    <input type="text" id="title-text-new" name="title" class="form-control" placeholder="Type lesson title here..." required=""/>

                                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['User']['id'] ?>"/>

                                    <input type="hidden" id="user_type" name="user_type" value="1"/>

                                </div>

                                <div class="form-group">

                                    <label>Type lesson text here...</label>

                                    <textarea class="form-control" name="data" id="data-new" rows="7" aria-label="Type lesson text here" placeholder="Type lesson text here..." required=""></textarea>

                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">

                            <button id="add-new-text" type="button" aria-label="Save New Lesson" class="dashboard-settings-btn btn-block">Save</button>
                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- add typio model-->

            <!-- add typio import model-->

            <div class="modal fade" id="typio-import-modal" aria-hidden="false" style="display: none;">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" >

                                <span aria-hidden="true">&times;</span>

                            </button>

                            <h4 class="modal-title">Typio import</h4>

                        </div>

                        <div class="modal-body">

                            <form method="POST" id="add-typio-import-form">

                                <div class="form-group">

                                    <label>Typio Import Code</label>

                                    <input class="form-control" id="typio_import_code" name="typio_import_code" type="text"  placeholder="Type Typio import code here..." required="" value="" />

                                    <p id="error_desk_title_alert" class="error" style="display:none;">Import code field required</p>

                                </div>
                            </form>

                        </div>

                        <div class="modal-footer">

                            <div class="col-md-12">


                                <button type="button" class="dashboard-settings-btn btn-block add-typio-import-btn" name="add-students">Save Typio</button>


                            </div>



                        </div>

                    </div>

                </div>

            </div>

            <div class="modal fade" id="edit-lessons-modal">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                <span aria-hidden="true">&times;</span>

                            </button>

                            <h4 class="modal-title color">Custom Lessons Editor</h4>

                        </div>

                        <div class="modal-body"></div>

                        <div class="modal-footer">
                            <div class='export-div'>
                                <div class='typio_code_copy col-md-6'></div>
                                <button type="button" class="dashboard-settings-btn btn-block typio-export-modal ">Export Typio</button>
                                <p class='typio-msg col-md-12' style="display:none;text-align: center;">Code copied To click Board</p>
                            </div>
                            <button type="button" class="dashboard-settings-btn btn-block edit-lessons-btn">Save changes</button>

                        </div>

                    </div>

                    <!-- /.modal-content -->



                    <script>

                        function check_duplicate_lesson_edit(table_id) {

                            var title = $('.lesson_title_edit_' + table_id).val();

                            console.log(title);

                            $.ajax({

                                type: 'POST',

                                url: ADMIN_URL + 'config/check_duplicate_entry.php',

                                data: {table_id: table_id, title: title, item_name: 'Typio-OL'},

                                dataType: 'json',

                                success: function (result) {

                                    $('.lesson_title_error_' + table_id).html(result);

                                    if (result) {

                                        $('.lesson_hidded_input_error').val('1');

                                        $('.lesson_title_error_' + table_id).show();

                                        $('.edit-lessons-btn').attr('disabled', 'disabled');

                                    } else {

                                        $('.lesson_hidded_input_error').val('');

                                        $('.lesson_title_error_' + table_id).hide();

                                        $('.edit-lessons-btn').removeAttr('disabled');

                                    }

                                }

                            });

                        }

                    </script>



                </div>

                <!-- /.modal-dialog -->

            </div>

            <!-- /.modal -->

            <?php include "../config/footer.php"; ?>



            <!-- Control Sidebar -->

            <?php include "../config/setting.php"; ?>

            <!-- /.control-sidebar -->

            <!-- Add the sidebar's background. This div must be placed

                 immediately after the control sidebar -->

            <div class="control-sidebar-bg"></div>



        </div><!-- ./wrapper -->



        <!-- jQuery 2.1.4 -->

        <script src="<?php echo ADMIN_URL ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

        <!-- Bootstrap 3.3.5 -->

        <script src="<?php echo ADMIN_URL ?>bootstrap/js/bootstrap.min.js"></script>

        <!-- FastClick -->

        <script src="<?php echo ADMIN_URL ?>plugins/fastclick/fastclick.min.js"></script>

        <!-- AdminLTE App -->

        <script src="<?php echo ADMIN_URL ?>dist/js/app.min.js"></script>

        <!-- Sparkline -->

        <script src="<?php echo ADMIN_URL ?>plugins/sparkline/jquery.sparkline.min.js"></script>

        <!-- jvectormap -->

        <script src="<?php echo ADMIN_URL ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>

        <script src="<?php echo ADMIN_URL ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

        <!-- SlimScroll 1.3.0 -->

        <script src="<?php echo ADMIN_URL ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>



        <script src="<?php echo ADMIN_URL ?>plugins/boot-box/bootbox.min.js"></script>

        <!-- ChartJS 1.0.1 -->

        <script src="<?php echo ADMIN_URL ?>plugins/chartjs/Chart.min.js"></script>

        <!-- bootstrap-datepicker -->

        <script src="<?php echo ADMIN_URL ?>plugins/datepicker/bootstrap-datepicker.min.js"></script>

        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->

        <script src="<?php echo ADMIN_URL ?>dist/js/pages/typio.js"></script>

    </body>

</html>
