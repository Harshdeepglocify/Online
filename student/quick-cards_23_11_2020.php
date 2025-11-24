<?php

include "../config/config-student.php";

//include "../config/config.php";

if (!$_SESSION['User']) {

    header("Location: " . ADMIN_URL . 'login');

    exit;

}

?>

<!DOCTYPE html>

<html>

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

        .modal-black modal-dialog modal-content modal-body {

            background-color: #000;

        }

        .modal-body h4{

            color: green;

        }

    </style>

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

    </head>

    <body class="hold-transition skin-blue sidebar-mini">

        <style type="text/css">

            p.error{

                color: red;

            }

        </style>

        <div class="wrapper">

            <?php include "../config/top-header.php"; ?>

            <!-- Left side column. contains the logo and sidebar -->

            <?php include "../config/left-sidebar.php"; ?>



            <!-- Content Wrapper. Contains page content -->

            <div class="content-wrapper">





                <div class="modal fade" id="edit-modal-set">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <div class="modal-header">

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                    <span aria-hidden="true">&times;</span>

                                </button>

                                <h4 class="modal-title">Edit Decks</h4>

                            </div>

                            <div class="modal-body">

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="dashboard-settings-btn btn-block edit-modal-save">Save changes</button>

                            </div>

                        </div>

                        <!-- /.modal-content -->

                    </div>

                    <!-- /.modal-dialog -->

                </div>





                <!-- Main content -->

                <!-- Quick card deck  -->

                <section class="content">

                    <div class="row">

                        <!-- Title -->

                        <div class="col-md-12">

                            <div class="student-title-main-init">

                                <h3 class=''>Quick Cards Decks</h3>

                            </div>

                        </div>

                        <!-- chart one -->

                        <div class="col-md-12">

                            <div class="chart-one-main-init student-decks-maker_type_1">

                                <!--   quick-cards Sectoin    -->

                                

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="">

                                            <div class="with-border">

                                                <h3 class="dashboard-h2">Deck</h3>

                                            </div>
                                            <div class="quick-ajax-response" aria-live="assertive">

                                                <div class="alert alert-dismissible fade in" id="alert-success">

                                                </div>

                                            </div>

                                            <input id="qc-student-id" name="user_id" class="form-control" type="hidden" value="<?php echo $_SESSION['User']['id']; ?>">

                                            <!-- /.box-header -->

                                            <div class="box-body students_decks_tbl">

                                                <table class="table table-bordered" id="students_decks_tbl">

                                                    <tbody>

                                                        <tr>

                                                            <th style="width: 15px !important;"></th>

                                                            <th >Deck Name</th>

                                                            <th style="width: 20px !important;">Cards</th>

                                                            <th style="width: 40px !important;">Edit</th>

                                                            <th style="width: 40px !important;">Delete</th>

                                                        </tr>

                                                        <?php

                                                        $text_data = get_data_from_text_table($_SESSION['User']['id'], 'QC-OL', 0);



                                                        if (!empty($text_data)) {



                                                            foreach ($text_data as $key => $value) {

                                                                echo '<tr class="deck_' . $value['table_id'] . '">';

                                                                echo '<td><input type="checkbox" name="share_quick_card_deck_ids[]" value=' . $value['table_id'] . ' class="share_quick_card_deck_ids"></td>';

                                                                echo '<td>' . $value['title'] . '</td>';

                                                                echo '<td>' . (!empty($value['data']) ? count(explode('|', $value['data'])) : 0) . '</td>';

                                                                echo '<td><a href="javascript:void(0)" class="badge bg-green student-decks-maker_type_1_edit" data-id="' . $value['table_id'] . '">

                                                                            <i class="fa fa-edit (alias)" aria-label="Edit"></i></a>

                                                                        </td>

                                                                        <td><a href="javascript:void(0)" class="badge bg-red student-decks-maker_type_1_delete" data-id="' . $value['table_id'] . '">

                                                                            <i class="fa fa-trash-o" aria-label="Delete"></i></a>

                                                                        </td>';

                                                                echo '</tr>';

                                                            }

                                                        }

                                                        ?>

                                                    </tbody>

                                                </table>

                                                <button type="button" style='margin-bottom:5px;' class="dashboard-settings-btn btn-block create_new_deck" data-toggle="modal" data-target="#deck-maker-modal">Create New Deck</button>
                                                <button type="button"  class="dashboard-settings-btn btn-block deck-import-modal " data-toggle="modal" data-target="#deck-import-modal">Import Deck</button>
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

                                                <form role="form">

                                                    <div class="form-group">

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

                                                                foreach ($student_data as $key => $value) { ?>
																<tr>
                                                                    <td><input type="checkbox" name="share_user_list[]" value="<?php echo $value['id']; ?>" class="share_quick_card_user_ids" id="<?php echo $value['id'];?>"><label for="<?php echo $value['id'];?>"><?php echo base64_decode($value['firstname']) . ' ' . base64_decode($value['lastname'])?></label></td>
																</tr>
																<?php
                                                                }

                                                            }

                                                            ?>

															</tbody>
														</table>

                                                    </div>

                                                    <div class="form-group action-btn-chart-init">

                                                        <button type="button" class="dashboard-settings-btn btn-block quick-card-deck-share-submit">Share</button>

                                                    </div>

                                                </form>

                                                <!-- End form -->

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <!--   End quick-cards Sectoin   -->

                                <div class="row">

                                    <div class="space-margin-bottom-50"></div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

                <!-- Quick card test -->

                <section class="content">

                    <div class="row">

                        <!-- Title -->

                        <!-- chart one -->

                        <div class="col-md-12">

                            <div class="chart-one-main-init student-decks-maker_type_2">

                                <!--   quick-cards Sectoin    -->

                                <div class="quick-ajax-response" aria-live="assertive">

                                    <div class="alert alert-dismissible fade in" id="alert-success"></div>

                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="">

                                            <div class="with-border">

                                                <h3 class="dashboard-h2">Test</h3>

                                            </div>

                                            <!-- /.box-header -->

                                            <div class="box-body students_test_tbl">

                                                <table class="table table-bordered" id="students_test_tbl">

                                                    <tbody>

                                                        <tr>

                                                            <th style="width: 15px !important;"></th>

                                                            <th >Test Name</th>

                                                            <th style="width: 20px">Q's</th>

                                                            <th style="width: 40px">Edit</th>

                                                            <th style="width: 40px">Delete</th>

                                                        </tr>

                                                        <?php

                                                        $text_data = get_data_from_text_table($_SESSION['User']['id'], 'QC-OL', 1);



                                                        if (!empty($text_data)) {

                                                            foreach ($text_data as $key => $value) {

                                                                echo '<tr class="test_' . $value['table_id'] . '">';

                                                                echo '<td><input type="checkbox" name="share_quick_card_test_ids[]" value=' . $value['table_id'] . ' class="share_quick_card_test_ids"></td>';

                                                                echo '<td>' . $value['title'] . '</td>';

                                                                echo '<td>' . (!empty($value['data']) ? count(explode('|', $value['data'])) - 1 : 0) . '</td>';

                                                                echo '<td><a href="javascript:void(0)" class="badge bg-green student-decks-maker_type_2_edit" data-id="' . $value['table_id'] . '">

                                                                              <i class="fa  fa-edit (alias)"></i></a>

                                                                          </td>

                                                                          <td><a href="javascript:void(0)" class="badge bg-red delete student-decks-maker_type_2_delete" data-id="' . $value['table_id'] . '">

                                                                              <i class="fa fa-trash-o" aria-label="Delete"></i></a>

                                                                          </td>';

                                                                echo '</tr>';

                                                            }

                                                        }

                                                        ?>

                                                    </tbody>

                                                </table>

                                                <button type="button" class="dashboard-settings-btn btn-block create_new_deck" data-toggle="modal" data-target="#add-students-test-modal">Create New Test</button>



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

                                                <form role="form">

                                                    <div class="form-group">

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

                                                                foreach ($student_data as $key => $value) { ?>
                                                                    <tr>
																		<td><input type="checkbox" name="share_user_list[]" value="<?php echo $value['id']; ?>" class="share_user_ids"><?php echo base64_decode($value['firstname']) . ' ' . base64_decode($value['lastname'])?></td>
																	</tr>
                                                               <?php }

                                                            }

                                                            ?>

                                                        </tbody>
														</table>


                                                    </div>

                                                    <div class="form-group action-btn-chart-init">

                                                        <button type="button" class="dashboard-settings-btn btn-block quick-card-test-share-submit">Share</button>

                                                    </div>

                                                </form>

                                                <!-- End form -->

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <!--   End quick-cards Sectoin   -->

                                <div class="row">

                                    <div class="space-margin-bottom-50"></div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

            <!-- add quick card desk model-->

            <div class="modal fade" id="deck-maker-modal" aria-hidden="false" style="display: none;">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" >

                                <span aria-hidden="true">&times;</span>

                            </button>

                            <h4 class="modal-title">Deck Maker</h4>

                        </div>

                        <div class="modal-body">

                            <form method="POST" id="add-students-deck-form">

                                <div class="form-group">

                                    <label>Deck Name</label>

                                    <input class="form-control" id="deck_name" name="deck_name" type="text"  placeholder="Type Deck Name here..." required="" value="" />

                                    <p id="error_desk_title_alert" class="error" style="display:none;">Deck name field required</p>

                                    <input id="user_id" type="hidden" name="user_id" value="<?php echo $student_id; ?>"/>

                                </div>

                                <div id="append_data"></div>

                                <div class="desk_maker_div">

                                    <div class="desk_maker_label">

                                        <label class="color">Side A:</label>

                                    </div>

                                    <div class="desk_maker_input_1">

                                        <input class="form-control" id="side_A" type="text" name="side_A[]" placeholder="Type Side A..." value="" />

                                        <p id="error_side_alert" class="error" style="display:none;">Side A field required</p>

                                    </div>

                                </div>

                                <div class="desk_maker_div">

                                    <div class="desk_maker_label">

                                        <label class="color">Side B:</label>

                                    </div>

                                    <div class="desk_maker_input_1">

                                        <input class="form-control" id="side_B" type="text" name="side_B[]" placeholder="Type Side B..." value="" />

                                    </div>

                                </div>

                                <div class="row">

                                </div>

                                <!-- <button type="button" class="btn btn-primary" id="reset_data">Reset</button> -->

                            </form>

                        </div>

                        <div class="modal-footer">



                            <!-- <div class="col-md-6" > <!-- style="margin-bottom: 10px"

                               <button type="button" class="btn btn-primary pull-right" id="add-more-side_fields">Add Card to Deck</button>

                            </div> -->



                            <div class="col-md-12">

                                <div class="col-md-5 col-sm-5">

                                    <button type="button" class="dashboard-settings-btn btn-block" id="add-more-side_fields">Add Card to Deck</button>


                                    <p id="error_desk_add_card" class="error" style="font-weight: bold;"></p>

                                </div>

                                <!-- <br> -->

                                <div class="col-md-7 col-sm-5">

                                    <button type="button" class="dashboard-settings-btn btn-block add-student-deck-btn" name="add-students">Save Deck</button>
                                    
                                </div>

                            </div>



                        </div>

                    </div>

                </div>

            </div>
			<!-- add quick card desk model-->

            <div class="modal fade" id="deck-import-modal" aria-hidden="false" style="display: none;">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" >

                                <span aria-hidden="true">&times;</span>

                            </button>

                            <h4 class="modal-title">Deck Maker import</h4>

                        </div>

                        <div class="modal-body">

                            <form method="POST" id="add-students-deck-import-form">

                                <div class="form-group">

                                    <label>Import Code</label>

                                    <input class="form-control" id="import_code" name="import_code" type="text"  placeholder="Type Deck import code here..." required="" value="" />

                                    <p id="error_desk_title_alert" class="error" style="display:none;">Import code field required</p>

                                    <input id="user_id" type="hidden" name="user_id" value="<?php echo $student_id; ?>"/>

                                </div>
                            </form>

                        </div>

                        <div class="modal-footer">

                            <div class="col-md-12">



                                    <button type="button" class="dashboard-settings-btn btn-block add-student-import-deck-btn" name="add-students">Save Deck</button>



                            </div>



                        </div>

                    </div>

                </div>

            </div>



            <!-- edit quick card desk model-->

            <div class="modal fade" id="edit-deck-maker-modal">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                <span aria-hidden="true">&times;</span>

                            </button>

                            <h4 class="modal-title color">Deck Maker Editor</h4>

                            <input type="hidden" id="user_type"   value="1">

                        </div>

                        <div class="modal-body"></div>



                        <div class="modal-footer" style="margin-top: 20px">

                            <div id="edit_deck_footer">

                                <div class="desk_maker_div" style="" >

                                    <div class="desk_maker_label">

                                        <label class="color">Side A:</label>

                                    </div>

                                    <div class="desk_maker_input_1">

                                        <input class="form-control" id="side_A_edit" type="text" name="side_A[]" placeholder="Type Side A..." value="" />

                                        <p id="error_side_alert_edit" class="error"></p>

                                    </div>

                                </div>

                                <div class="desk_maker_div">

                                    <div class="desk_maker_label">

                                        <label class="color">Side B:</label>

                                    </div>

                                    <div class="desk_maker_input_1">

                                        <input class="form-control" id="side_B_edit" type="text" name="side_B[]" placeholder="Type Side B..." value="" />

                                    </div>

                                </div>

                                <div class="row"> </div>

                                <div class="col-md-12" style="margin-bottom: 10px">

                                    <button type="button" class="dashboard-settings-btn btn-block pull-right" id="add-more-side_fields_edit">Add Card to Deck</button>

                                    <p id="error_desk_add_card_edit" class="error" style="font-weight: bold;"></p>

                                </div>

                            </div>



                            <div class="col-md-12 edit-desk-cls" style="padding-right:0px;display:inline-block;width:100%;">
								<div class='export-div col-md-8 col-sm-8'>
									<div class='code_copy col-md-7'></div>
									<button type="button" class="dashboard-settings-btn btn-block deck-export-modal">Export Deck</button>
									<p class='msg' style="display:none;">Code copied To click Board</p>

								</div>
								<div class='col-md-4 col-sm-4'>
                                <button type="button" class="dashboard-settings-btn btn-block edit-modal-save">Save changes</button>
								</div>
                            </div>

                            <script>

                                function check_duplicate_dock_edit(table_id) {

                                    var title = $('.deck_title_' + table_id).val();

                                    console.log(title);

                                    $.ajax({

                                        type: 'POST',

                                        url: ADMIN_URL + 'config/check_duplicate_entry.php',

                                        data: {table_id: table_id, title: title, item_name: 'QC-OL', number: 0},

                                        dataType: 'json',

                                        success: function (result) {

                                            $('.deck_title_edit_' + table_id).html(result);

                                            if (result) {

                                                $('.deck_title_edit_' + table_id).show();

                                                $('.edit-modal-save').attr('disabled', 'disabled');

                                            } else {

                                                $('.deck_title_edit_' + table_id).hide();

                                                $('.edit-modal-save').removeAttr('disabled');

                                            }

                                        }

                                    });

                                }

                            </script>

                        </div>

                    </div>

                    <!-- /.modal-content -->

                </div>

                <!-- /.modal-dialog -->

            </div>

            <!-- /.modal -->

            <!-- add new lola's test model -->

            <div class="modal fade" id="add-students-test-modal" aria-hidden="false" style="display: none;">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                <span aria-hidden="true">&times;</span>

                            </button>

                            <h4 class="modal-title color">Test Maker</h4>

                        </div>

                        <div class="modal-body">

                            <form method="POST" id="add-students-test-form">

                                <div class="form-group">

                                    <label >Test Name</label>

                                    <input class="form-control" id="test_name" type="text" name="test_name" placeholder="Type Test Name here..." required=""/>

                                    <p id="error_test_title_alert" style="display: none;" class="error">Test Name is required!</p>

                                    <input class="form-control" id="question_number" type="hidden"  value="0" />

                                </div>

                                <div id="append_data_for_test"></div>



                                <div class="form-group" >

                                    <label class="color">Question</label>

                                    <input class="form-control" id="question_name" type="text"  placeholder="Type question here..." />

                                    <p id="error_question_alert" style="display: none;" class="error">Question not blank!</p>

                                </div>

                                <div class="form-group"  >

                                    <label class="color">Answers (check box next to correct answer)</label>

                                    <div id="append_answer">

                                        <div style="margin-bottom: 5px" class="question_option_row">

                                            <p id="error_ans_select" style="display: none;" class="error error">Please select the right answer</p>

                                            <div style="margin-top: 8px;width: 5%;float: left;">

                                                <input type="checkbox"   class="question_option_check"   >

                                            </div>

                                            <div style="width: 95%;float: left;margin-bottom: 8px;">

                                                <input class="form-control question_option"  type="text"   placeholder="Type answer..." required=""/>

                                                <p id="error_question_option_blank_status" style="display: none;"  class="error">Can not have more than 6 option fields</p>

                                                <p id="error_question_option_blank_empty_status" style="display: none;"  class="error">Can not have option field blank</p>

                                            </div>

                                        </div>

                                    </div>

                                </div>



                            </form>

                        </div>

                        <div class="modal-footer">

                            <div class="col-md-12" style="margin-bottom: 10px">

                                <div class="form-group add-more-one-line-wrap" style="margin-top: 15px;float: right">

                                    <button type="button" class="dashboard-settings-btn btn-block" id="add-more-answer">Add An Answer</button>

                                    <button type="button" class="dashboard-settings-btn btn-block add-more-question" id="add-more-question">Add Question to Test</button>

                                    <p id="error_add_que_to_test" class="error"></p>

                                    <!--       <button type="button"   class="btn btn-success" id="reset_test_data">Reset</button> -->

                                </div>

                            </div>

                            <div class="col-md-12" style="display:inline-block;width:100%;">

                                <button type="button" class="dashboard-settings-btn btn-block" id="add-student-test-btn">Save Test</button>
								<button type="button" class="dashboard-settings-btn btn-block deck-test-import-modal " data-toggle="modal" data-target="#deck-test-import-modal">Import Test</button>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

			<!-- add quick card desk test model-->

            <div class="modal fade" id="deck-test-import-modal" aria-hidden="false" style="display: none;">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" >

                                <span aria-hidden="true">&times;</span>

                            </button>

                            <h4 class="modal-title">Test Maker import</h4>

                        </div>

                        <div class="modal-body">

                            <form method="POST" id="add-students-test-import-form">

                                <div class="form-group">

                                    <label>Test Import Code</label>

                                    <input class="form-control" id="test_import_code" name="import_code" type="text"  placeholder="Type test import code here..." required="" value="" />

                                    <p id="error_desk_title_alert" class="error" style="display:none;">Import code field required</p>

                                </div>
                            </form>

                        </div>

                        <div class="modal-footer">

                            <div class="col-md-12">



                                    <button type="button" class="dashboard-settings-btn btn-block add-student-import-deck-test-btn" name="add-students">Save Test</button>


                            </div>



                        </div>

                    </div>

                </div>

            </div>

            <!-- edit lola's test model-->



            <div class="modal fade" id="edit-students-test-modal">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                <span aria-hidden="true">&times;</span>

                            </button>

                            <h4 class="modal-title color"> Test  Editor</h4>

                            <!--  <input class="form-control" id="question_number_in_edit" type="text"  value="0" /> -->

                        </div>

                        <div class="modal-body"></div>

                        <div class="modal-footer">



                            <div style="text-align:left" id="new-question-div">

                                <div class="form-group" >

                                    <label class="color">Question</label>

                                    <input class="form-control" id="question_new_edit" type="text"  placeholder="Type question here..." />

                                    <p id="error_question_alert_edit" class="error"></p>

                                </div>

                                <div class="form-group"  >

                                    <label class="color">Answers (check box next to correct answer)</label>

                                    <div id="append_answer_in_edit">

                                        <div style="margin-bottom: 5px" class="question_option_row_new_add_edit">

                                            <p id="error_select_only_one_ans_edit" class="error"></p>

                                            <div style="margin-top: 8px;width: 5%;float: left;">



                                                <input type="checkbox"   class="question_option_check_edit"   >



                                            </div>

                                            <div style="width: 95%;float: left;margin-bottom: 8px;">

                                                <input class="form-control question_option_edit_box"  type="text"   placeholder="Type answer..." required=""/>

                                                <p id="error_option_not_blank_edit" class="error"></p>

                                                <p id="error_max_six_option_edit" class="error"></p>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-12" style="margin-bottom: 10px" id="new-question-btn-div">

                                <div class="form-group add-more-one-line-wrap" style="margin-top: 15px;float: right">

                                    <button type="button" class="dashboard-settings-btn btn-block" id="add-more-answer_edit">Add An Answer</button>

                                    <button type="button" class="dashboard-settings-btn btn-block" id="add-more-question_edit">Add Question to Test</button>



                                </div>

                            </div>


                            <div class="col-md-12 test-cls">
								<div class='export-test-div col-md-8 col-sm-8'>
									<div class='test_code_copy col-md-7 col-sm-7'></div>
									<button type="button" class="dashboard-settings-btn btn-block deck-test-export-modal ">Export Deck</button>
									<p class='test-msg' style="display:none;">Code copied To click Board</p>

								</div>
								<div class='col-md-4 col-sm-4'>
									<button type="button" class="dashboard-settings-btn btn-block edit-students-test-save">Save changes</button>
								</div>
                            </div>

                        </div>

                        <script>

                            function check_duplicate_test_edit(table_id) {

                                var title = $('.test_title_' + table_id).val();

                                console.log(title);

                                $.ajax({

                                    type: 'POST',

                                    url: ADMIN_URL + 'config/check_duplicate_entry.php',

                                    data: {table_id: table_id, title: title, item_name: 'QC-OL', number: 1},

                                    dataType: 'json',

                                    success: function (result) {

                                        $('.test_title_edit_' + table_id).html(result);

                                        if (result) {

                                            $('.test_title_edit_' + table_id).show();

                                            $('.edit-students-test-save').attr('disabled', 'disabled');

                                        } else {

                                            $('.test_title_edit_' + table_id).hide();

                                            $('.edit-students-test-save').removeAttr('disabled');

                                        }

                                    }

                                });

                            }

                        </script>

                    </div>

                    <!-- /.modal-content -->

                </div>

                <!-- /.modal-dialog -->

            </div>



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

        <!-- ChartJS 1.0.1 -->

        <script src="<?php echo ADMIN_URL ?>plugins/chartjs/Chart.min.js"></script>

        <script src="<?php echo ADMIN_URL ?>plugins/boot-box/bootbox.min.js"></script>

        <!-- bootstrap-datepicker -->

        <script src="<?php echo ADMIN_URL ?>plugins/datepicker/bootstrap-datepicker.min.js"></script>

        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->



        <script src="https://code.highcharts.com/highcharts.js"></script>

        <script src="https://code.highcharts.com/modules/data.js"></script>

        <script src="https://code.highcharts.com/modules/exporting.js"></script>

        <script src="<?php echo ADMIN_URL ?>dist/js/pages/student-overview.js" type="text/javascript"></script>



    </body>

</html>
