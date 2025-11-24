<?php
include "../config/config.php";
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
                                <h3>Accessibyte Arcade</h3>
                            </div>
                        </div>
                        <!-- chart one -->
                        <div class="col-md-12">
                            <div class="chart-one-main-init">
                                <?php
                                //Argument for data
                                $args = array(
                                    'teacher_code' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
                                    'role' => 'student',
                                );
                                $student_data = get_users($args);

                                function gethangmanData() {

                                    $user_id = $_SESSION['User']['id'];

                                    global $con;

                                    $hangmanData = array();
                                    $table = 'text';
                                    $query = query("SELECT * FROM text WHERE text.app='Arcade-OL' AND text.number=2 AND id='" . $user_id . "'");

                                    while ($row = mysqli_fetch_array($query)) {
                                        $hangmanData[] = $row;
                                    }
                                    return $hangmanData;
                                }

                                $hangmaneGameData = gethangmanData();
                                ?>
                                <!--   Arcade Sectoin    -->

                                <div class="row">
                                    <div class="hang-sidebar-alert alert alert-dismissible"></div>
                                    <div class="col-md-6">
										<div class="with-border">
                                            <h3 class="dashboard-h2">Hangman</h3>
                                        </div>
                                        <div class="hangman_msg">
                                            <!-- /.box-header -->
                                            <div class="quick-ajax-response" aria-live="assertive">
                                                <div class="alert alert-dismissible fade in" id="alert-success"></div>
                                            </div>

                                            <!-- /.box-header -->
                                            <div class="box-body ">
                                                <table class="table table-bordered hangman-table" id="hangman-table">
                                                    <tbody>
                                                        <tr>
                                                            <th style="width: 10px !important;"></th>
                                                            <th>Hangman Lesson Title</th>
                                                            <th style="width: 40px  !important;">Edit</th>
                                                            <th style="width: 40px  !important;">Delete</th>
                                                        </tr>
                                                        <?php
                                                        if (!empty($hangmaneGameData)) {
                                                            $i = 1;
                                                            foreach ($hangmaneGameData as $key => $value) {
                                                                ?>
                                                                <tr class="hangman_tr_<?php echo $value['table_id']; ?>">
                                                                    <td><input type="checkbox" name="share_hangman_ids[]" value="<?php echo $value['table_id']; ?>" class="share_hangman_ids"></td>
                                                                    <td><?php echo $value['title'] ?></td>
                                                                    <td><a href="javascript:void(0)" class="badge bg-green edit-hangman-modal" data-id="<?php echo $value['table_id'] ?>">
                                                                            <i class="fa fa-edit" aria-label="Edit"></i>
                                                                        </a>
                                                                    </td>
                                                                    <td><a href="javascript:void(0)" class="badge bg-red delete-hangman" data-toggle="modal" data-target="#delete-modal-hangman" data-id="<?php echo $value['table_id'] ?>">
                                                                            <i class="fa fa-trash-o" aria-label="Delete"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $i++;
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
												<button type="button" style="margin-top:15px;" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-hangman-modal" >Create New</button>
                                                <button type="button" class="dashboard-settings-btn btn-block  hangman-import-modal " data-toggle="modal" data-target="#hangman-import-modal">Import Hangman</button>
                                            </div>
                                            <!-- /.box-body -->

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="">
                                            <div class="with-border">
                                                <h3 class="dashboard-h2">Share With</h3>
                                            </div>
                                            <div class="">
                                                <!-- form -->
                                                <form role="form">
                                                    <div class="form-group">
                                                        <label>Students</label>
														<table class="table table-bordered share-table-arcade" id="share-table">
															<tbody>
																<?php
																if (!empty($student_data)) {
																	foreach ($student_data as $key => $value) { ?>
																	<tr>
                                                                        <td><input type="checkbox" name="share_user_list[]" value="<?php echo $value['id']; ?>" class="share_user_ids" id="<?php echo $value['id']?>"><label for="<?php echo $value['id']?>"><?php echo base64_decode($value['firstname']) . ' ' . base64_decode($value['lastname'])?></label></td>
																	</tr>
																   <?php }
																}
																?>
															</tbody>
														</table>
                                                    </div>
                                                    <div class="form-group action-btn-chart-init">
                                                        <button type="button" class="dashboard-settings-btn btn-block hangman-share-submit">Share</button>
                                                    </div>
                                                </form>
                                                <!-- End form -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ---------- End Arcade Sectoin ---------- -->

                                <!--   End Arcade Sectoin   -->
                                <div class="row">
                                    <div class="space-margin-bottom-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include "../config/footer.php"; ?>

            <!-- Control Sidebar -->
            <?php include "../config/setting.php"; ?>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>

        </div><!-- ./wrapper -->

        <!-- add hangman-modal -->
        <div class="modal fade" id="add-hangman-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title color">Add Hangman </h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="add-hangman-form">
                            <div class="form-group">
                                <input class="form-control" id="hangman-title" type="text" name="title" placeholder="Type lesson title here..." required=""/>
                                <input id="user_id" type="hidden" name="user_id" value="<?php echo $_SESSION['User']['id']; ?>"/>
                                <input type="hidden" id="user_type" name="user_type" value="1"/>
                            </div>
                            <div class="form-group">
                                <input class="form-control text_data"  type="text" name="fields[1]" placeholder="Type new word here..." required=""/>
                            </div>
                            <div id="append"></div>
                            <button type="button" class="dashboard-settings-btn btn-block" id="add-more-fields">Add New</button>
                        </form>
                    </div>
                    <div class="modal-footer">
						<button type="button" class="dashboard-settings-btn btn-block add-hangman-btn">Save</button>
                    </div>
                </div>
            </div>
        </div>

		<!-- add hangman import model-->

		<div class="modal fade" id="hangman-import-modal" aria-hidden="false" style="display: none;">

			<div class="modal-dialog">

				<div class="modal-content">

					<div class="modal-header">

						<button type="button" class="close" data-dismiss="modal" aria-label="Close" >

							<span aria-hidden="true">&times;</span>

						</button>

						<h4 class="modal-title">Hangman import</h4>

					</div>

					<div class="modal-body">

						<form method="POST" id="add-hangman-import-form">

							<div class="form-group">

								<label>Hangman Import Code</label>

								<input class="form-control" id="hangman_import_code" name="hangman_import_code" type="text"  placeholder="Type Hangman import code here..." required="" value="" />

								<p id="error_desk_title_alert" class="error" style="display:none;">Import code field required</p>

							</div>
						</form>

					</div>

					<div class="modal-footer">

						<div class="col-md-12">


								<button type="button" class="dashboard-settings-btn btn-block add-hangman-import-btn" name="add-students">Save hangman</button>


						</div>



					</div>

				</div>

			</div>

		</div>
        <!--  Edit hangman-modal  -->
        <div class="modal fade" id="edit-hangman-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title color"> Hangman Edittor</h4>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
						<div class='export-div col-md-8 col-sm-8'>
							<div class='hangman_code_copy col-md-6'></div>
							<button type="button" class="dashboard-settings-btn btn-block hangman-export-modal ">Export hangman</button>
							<p class='hangman-msg' style="display:none;">Code copied To click Board</p>
						</div>
						<div class="col-md-4 col-sm-4">
                        <button type="button" class="dashboard-settings-btn btn-block edit-hangman-btn">Save changes</button>
						</div>
                    </div>
                </div>
                <!-- /.modal-content -->

                <script>
                    function check_duplicate_hangman_edit(table_id) {
                        var title = $('.hangman_title_edit_' + table_id).val();
                        $.ajax({
                            type: 'POST',
                            url: ADMIN_URL + 'config/check_duplicate_entry.php',
                            data: {table_id: table_id, title: title, item_name: 'Arcade-OL', number: 2},
                            dataType: 'json',
                            success: function (result) {
                                $('.hangman_title_error_' + table_id).html(result);
                                if (result) {
                                    $('.hangman_title_error_' + table_id).show();
                                    $('.edit-hangman-btn').attr('disabled', 'disabled');
                                } else {
                                    $('.hangman_title_error_' + table_id).hide();
                                    $('.edit-hangman-btn').removeAttr('disabled');
                                }
                            }
                        });
                    }
                </script>
            </div>
            <!-- /.modal-dialog -->
        </div>


        <!-- /.modal -->

        <!-- Add Game Section C -->
        <div class="modal fade" id="add-game-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Create new Crazy Phrase story </h4>
                        <span>Place words you want your student to type between < and > characters.</span>
                        <br>
                        <span>"Example: Today is a &LT;adjective&GT; day</span>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="add-game-form">
                            <div class="form-group">
                                <input class="form-control" id="game-title" type="text" name="title" placeholder="Type lesson title here..." required=""/>
                                <input id="user_id" type="hidden" name="user_id" value="<?php echo $_SESSION['User']['id']; ?>"/>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="data_text" type="text" name="data" placeholder="Type your story here..." required=""></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="dashboard-settings-btn btn-block add-game-btn">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- edit Game Section C -->
        <div class="modal fade" id="edit-game-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title color">Crazy Phrase story Editor</h4>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="dashboard-settings-btn btn-block edit-game-btn">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
                <script>

                    function check_duplicate_game_edit(table_id) {
                        var title = $('.game_title_edit_' + table_id).val();
                        $.ajax({
                            type: 'POST',
                            url: ADMIN_URL + 'config/check_duplicate_entry.php',
                            data: {table_id: table_id, title: title, item_name: 'Arcade-OL', number: 1},
                            dataType: 'json',
                            success: function (result) {
                                $('.game_title_error_' + table_id).html(result);
                                if (result) {
                                    $('.game_title_error_' + table_id).show();
                                    $('.edit-game-btn').attr('disabled', 'disabled');
                                } else {
                                    $('.game_title_error_' + table_id).hide();
                                    $('.edit-game-btn').removeAttr('disabled');
                                }
                            }
                        });
                    }
                </script>
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- jQuery 2.1.4 -->
        <script src="<?php echo ADMIN_URL ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo ADMIN_URL ?>bootstrap/js/bootstrap.min.js"></script>
        <!-- FastClick -->
        <script src="<?php echo ADMIN_URL ?>plugins/fastclick/fastclick.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo ADMIN_URL ?>dist/js/app.min.js"></script>
        <!-- SlimScroll 1.3.0 -->
        <script src="<?php echo ADMIN_URL ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/boot-box/bootbox.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>dist/js/pages/arcade.js"></script>
    </body>
</html>
