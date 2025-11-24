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
<!-- ---------- Typio Sectoin one ---------- -->
<?php
$student_id = !empty($_GET['student']) ? $_GET['student'] : '';
$pro_visual_fx = array(1 => 'On', 0 => 'Off');
$pro_subtitles = array(1 => 'On', 0 => 'Off');
?>

<!-- Start Session Reader docs-->
<br>
<div class="row">
    <?php $reader_doc_data = get_ProPack_data($student_id, '0'); ?>
    <div class="col-md-6">
        <div class="pro_pack_msg">
            <div class="with-border">
                <h2 class="dashboard-h2">Reader Files</h2>
            </div>            
            <div class="quick-ajax-response" aria-live="assertive">
                <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert alert-dismissible fade in" >
                </div>
            </div> 
            <!-- /.box-header -->
            <div class="table-responsive">
                    <table id="student_propack_reader_file_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs table-list-custom" border="2">
                                <thead>
                                    <tr>
                                        <td><h4 class="Students-title-cs"><b>Title</b></h4></td>
                                        <td><h4 class="Students-title-cs"><b>Edit</b></h4></td>
                                        <td><h4 class="Students-title-cs"><b>Delete</b></h4></td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                        </table>
                </div>
           </div>

        
    </div>

    <div class="col-md-6">
        <div class="custom-p-lesson-init">
            <div class="with-border">
                <h2 class="dashboard-h2">Create Reader File</h2>
            </div>

            <div class="box-body"> 
                <form method="POST" id="reader-docs-add-form">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" id="reader-docs-title" name="title" class="form-control title" placeholder="Reader docs title here..." required="" oninput="text_restrictions(this);"/>
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo!empty($_GET['student']) ? $_GET['student'] : ''; ?>"/>
                        <input id="user_type" name="user_type" value="0" type="hidden">

                        <input type="hidden" class="pro_pack_type" name="pro_pack_type" value="reader_doc"/>
                    </div>

                    <div class="form-group">
                        <label>Text</label>
                        <textarea class="form-control" name="data" id="Reader-docs-text" rows="5" placeholder="Reader docs text here..." required=""></textarea>
                    </div>

                    <button class="pro-pack-add-new-form dashboard-settings-btn btn-block" type="button" value="1" aria-label="Reader File Save Button">Save Reader File</button>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- End Session Reader docs-->

<div class="row">
    <div class="space-margin-bottom-50"></div>
    <div class="wt-cus-dvider"></div>
    <div class="space-margin-bottom-50"></div>
</div>

<!-- Start Session notepad docs-->
<div class="row">
    <?php $notepad_doc_data = get_ProPack_data($student_id, '1'); ?>
    <div class="col-md-6">
        <div class="notepad_msg">
            <div class="with-border">
                <h2 class="dashboard-h2">Notepad Files</h2>
            </div>  
            <div class="quick-ajax-response " aria-live="assertive">
                <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert alert-dismissible fade in">
                </div>
            </div>           
            <!-- /.box-header -->

            <div class="table-responsive">
                    <table id="student_propack_notpad_file_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs table-list-custom" border="2">
                                <thead>
                                    <tr>
                                        <td><h4 class="Students-title-cs"><b>Title</b></h4></td>
                                        <td><h4 class="Students-title-cs"><b>Edit</b></h4></td>
                                        <td><h4 class="Students-title-cs"><b>Delete</b></h4></td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                        </table>
                </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="custom-p-lesson-init">
            <div class="with-border">
                <h2 class="dashboard-h2">Create Notepad File</h2>
            </div> 
            <div class="box-body"> 
                <form method="POST" id="reader-docs-add-form">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" id="notepad-docs-title" name="title" class="form-control title" placeholder="Notepad docs title here..." required=""/>
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo!empty($_GET['student']) ? $_GET['student'] : ''; ?>"/>
                        <input id="user_type" name="user_type" value="0" type="hidden">

                        <input type="hidden" class="pro_pack_type" name="pro_pack_type" value="notepad_doc"/>
                    </div>

                    <div class="form-group">
                        <label>Text</label>
                        <textarea class="form-control" name="data" id="Notepad-docs-text" rows="8" placeholder="Notepad docs text here..." required=""></textarea>
                    </div>

                    <button class="pro-pack-add-new-form dashboard-settings-btn btn-block" type="button" aria-label="Save Notepad File button" value="2">Save Notepad File</button>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- END Session notepad docs-->


<div class="row">
    <div class="space-margin-bottom-50"></div>
    <div class="wt-cus-dvider"></div>
    <div class="space-margin-bottom-50"></div>
</div>
<!-- Start Session to_do_doc docs-->
<div class="row">
    <?php $to_do_doc_data = get_ProPack_data($student_id, '2'); ?>
    <div class="col-md-6">
        <div class="to_do_msg">
            <div class="with-border">
                <h2 class="dashboard-h2">To-Do Tasks</h2>
            </div>            
            <div class="quick-ajax-response " aria-live="assertive">
                <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert alert-dismissible fade in" >
                </div>
            </div> 
            <!-- /.box-header -->
          

         
            <div class="table-responsive">
                    <table id="student_propack_todo_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs table-list-custom" border="2">
                        <thead>
                            <tr>
                                <td><h4 class="Students-title-cs"><b>Tasks</b></h4></td>
                                <td><h4 class="Students-title-cs"><b>Edit</b></h4></td>
                                <td><h4 class="Students-title-cs"><b>Delete</b></h4></td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
              </div>


        </div>
    </div>

    <div class="col-md-6">
        <div class="custom-p-lesson-init">
            <div class="with-border">
                <h2 class="dashboard-h2">Task Title</h2>
            </div>

            <div class="box-body">

                <form method="POST" id="to-do-docs-add-form">
                    <div class="form-group">
                        <label>To-Do docs Title</label>
                        <input type="text" id="to-do-docs-title" name="title" class="form-control title" placeholder="To-Do docs title here..." required=""/>
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo!empty($_GET['student']) ? $_GET['student'] : ''; ?>"/>
                        <input id="user_type" name="user_type" value="0" type="hidden">

                        <input type="hidden" class="pro_pack_type" name="pro_pack_type" value="to_do_doc"/>
                    </div>

                    <div class="form-group" style="display: none">
                        <label>To-Do docs text</label>
                        <textarea class="form-control" name="data" id="To-Do-docs-text" rows="8" placeholder="To-Do docs text here..." required="">1</textarea>
                    </div>  

                    <button class="pro-pack-add-new-form dashboard-settings-btn btn-block" type="button" value="3">Save To-Do Task</button>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- END Session to_do_doc docs-->

<div class="modal fade" id="edit-Pro-Pack-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title color">Pro Pack Editor</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="dashboard-settings-btn btn-block edit-pro-pack-btn">Save changes</button>
            </div>
        </div>

        <script>
            function check_duplicate_reader_docs_edit(table_id) {
                var title = $('.reader_docs_title_edit_' + table_id).val();
                var number = $('#type_number').val();
                var student_id = '<?php echo $_GET['student']; ?>';

                $.ajax({
                    type: 'POST',
                    url: ADMIN_URL + 'config/check_duplicate_entry.php',
                    data: {table_id: table_id, title: title, item_name: 'PP-OL', number: number, student: student_id},
                    dataType: 'json',
                    success: function (result) {
                        $('.reader_docs_title_error' + table_id).html(result);
                        if (result) {
                            $('.reader_docs_title_error' + table_id).show();
                            $('.edit-pro-pack-btn').attr('disabled', 'disabled');
                        } else {
                            $('.reader_docs_title_error' + table_id).hide();
                            $('.edit-pro-pack-btn').removeAttr('disabled');
                        }
                    }
                });
            }
        </script>

    </div>
</div>

<!-- /.modal -->
<div class="row">
    <div class="space-margin-bottom-50"></div>
    <div class="wt-cus-dvider"></div>
    <div class="space-margin-bottom-50"></div>
</div>
<div class="row settings-area-p-init">

</div>
<div class="row settings-area-p-init">

    <div class="qc-setting-alert alert alert-dismissible"></div>
    <?php
    $pro_pack_options_value = array();
    $pro_settings_options = mysqli_query($con, "SELECT * FROM `settings` WHERE `id` = '" . $student_id . "' AND `item` IN (6,415,416,417,418,419,420,421,422,441,442,443)");

    while ($pro_row = mysqli_fetch_assoc($pro_settings_options)) {
        $pro_pack_options_value[$pro_row['item']] = $pro_row['variable'];
    }
    ?>


    <form method="post" action="" name="pro-pack-settings-form" id="pro-pack-settings-form">
        <div class="col-md-12">
            <div class="box-header-init">
                <h2 class="dashboard-h2">Visual Settings</h2>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Font Size</h3></label>
                <select class="form-control" name="pro-pack-options[415]" aria-label="Font Size">
                    <?php
                    if (!empty($font_size_options)) {
                        foreach ($font_size_options as $key => $value) {
                            echo '<option ' . (!empty($pro_pack_options_value[415]) && $qc_options_value[415] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Font Style</h3></label>
                <select class="form-control" name="pro-pack-options[417]" aria-label="Font Style">
                    <?php
                    if (!empty($font_style_options)) {
                        foreach ($font_style_options as $key => $value) {
                            echo '<option ' . (!empty($pro_pack_options_value[417]) && $pro_pack_options_value[417] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Font Color</h3></label>
                <select class="form-control" name="pro-pack-options[416]" aria-label="Font Color">
                    <?php
                    if (!empty($color_options)) {
                        foreach ($color_options as $key => $value) {
                            echo '<option ' . (!empty($pro_pack_options_value[416]) && $pro_pack_options_value[416] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">BG Color</h3></label>
                <select class="form-control" name="pro-pack-options[418]" aria-label="Background Color">
                    <?php
                    if (!empty($color_options)) {
                        foreach ($color_options as $key => $value) {
                            echo '<option ' . (!empty($pro_pack_options_value[418]) && $pro_pack_options_value[418] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Accessory Color</h3></label>
                <select class="form-control" name="pro-pack-options[419]" aria-label="Accessory Color">
                    <?php
                    if (!empty($color_options)) {
                        foreach ($color_options as $key => $value) {
                            echo '<option ' . (!empty($pro_pack_options_value[419]) && $pro_pack_options_value[419] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Selection Style</h3></label>
                <select class="form-control" name="pro-pack-options[443]" aria-label="Selection Style">
                    <option value="0" <?php echo (isset($pro_pack_options_value[443]) && $pro_pack_options_value[443] == 0) ? 'selected="selected"' : ''; ?>>Invert</option>
                    <option value="1" <?php echo (isset($pro_pack_options_value[443]) && $pro_pack_options_value[443] == 1) ? 'selected="selected"' : ''; ?>>Accessory Color</option>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Subtitles</h3></label>
                <select class="form-control" name="pro-pack-options[441]" aria-label="Subtitles">
                    <?php
                    if (!empty($pro_visual_fx)) {
                        foreach ($pro_visual_fx as $key => $value) {
                            echo '<option ' . ( isset($pro_pack_options_value[441]) && $pro_pack_options_value[441] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Visual FX</h3></label>               
                <select class="form-control" name="pro-pack-options[442]" aria-label="Visual Effects">
                    <?php
                    if (!empty($pro_visual_fx)) {
                        foreach ($pro_visual_fx as $key => $value) {
                            echo '<option ' . ( isset($pro_pack_options_value[442]) && $pro_pack_options_value[442] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

        </div>
<br>
<hr width="90%" size="6" align="center" color="#e5e5e5" border-color="#e5e5e5">
        <div class="col-md-12">
            <div class="box-header-init"><br>
                <h2 class="dashboard-h2">Audio Settings</h2>
            </div>
        </div>

        <!-- end first col-md-4 --> 
        <div class="col-md-12">

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Voice</h3></label>
                <select class="form-control" name="pro-pack-options[420]">
                    <?php
                    if (!empty($voice_options)) {
                        foreach ($voice_options as $key => $value) {
                            echo '<option ' . (!empty($pro_pack_options_value[420]) && $pro_pack_options_value[420] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    if(!empty($pro_pack_options_value[420]) && $pro_pack_options_value[420] !="Off" && $pro_pack_options_value[420] !="Default") { ?>
                        <option value="<?php echo $pro_pack_options_value[420]; ?>" selected><?php echo $pro_pack_options_value[420]; ?></option>
                   <?php }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Voice Rate</h3></label>
                <select class="form-control" name="pro-pack-options[421]" aria-label="Voice Rate">
                    <?php
                    if (!empty($voice_rate_options)) {
                        foreach ($voice_rate_options as $key => $value) {
                            echo '<option ' . (!empty($pro_pack_options_value[421]) && $pro_pack_options_value[421] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Voice Pitch</h3></label>
                <select class="form-control" name="pro-pack-options[422]" aria-label="Voice Pitch">
                    <?php
                    if (!empty($voice_pitch_options)) {
                        foreach ($voice_pitch_options as $key => $value) {
                            echo '<option ' . (!empty($pro_pack_options_value[422]) && $pro_pack_options_value[422] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="form-group"><br>
                <div class="comman-button-input-option-wrap">    
                    <input type="hidden" class="form-control pro-pack-student-id" value="<?php echo $student_id; ?>" id="pro-pack-student-id">
                    <button type="button" name="pro-pack-settings-submit" aria-label="Save propack settings button" data-for="single" id="pro-pack-settings-submit" class="pro-pack-save-settings-data pro-pack-add-new-form dashboard-settings-btn btn-block">Save ProPack Settings</button>
                    <span name="common_option" class="common-option-btn" aria-label="Save propack settings more options" aria-role="button">
                        <i class="fa fa-chevron-down"></i>
                    </span>
                </div>
                <div class="common-option-section-wrap" style="display: none;">
                    <ul class="new-option">
                        <li><input type="button" class='pro-pack-save-settings-data all-student-settings' data-for="all" name="pro-pack-settings-submit" value="Save Propack Settings For All Students" aria-label="Save settings button for all student" class="dashboard-settings-btn btn-block" ></li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="<?php echo ADMIN_URL ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>  
    <script>
            $(document).ready(function() {
                $('#pro-pack-Tab').click(function(){
                    if($('#propack').attr('data-load') !== undefined) {
                        var table = $('#student_propack_reader_file_list_table').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                                "type": "POST",
                                data: {'action': 'ajax_student_propack_reader_file','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>"},
                            },
                            "order": [[0, 'ASC']],
                            "columnDefs": [
                                {"targets": 0, "name": "title", 'searchable': true, 'orderable': true},
                                {"targets": 1, "name": "activity", 'searchable': false, 'orderable': false},
                                {"targets": 2, "name": "activity", 'searchable': false, 'orderable': false}
                                
                            ]
                        });
                        var table = $('#student_propack_notpad_file_list_table').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                                "type": "POST",
                                data: {'action': 'ajax_student_propack_notpad_file','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>"},
                            },
                            "order": [[0, 'DESC']],
                            "columnDefs": [
                                {"targets": 0, "name": "title", 'searchable': false, 'orderable': true},
                                {"targets": 1, "name": "activity", 'searchable': false, 'orderable': false},
                                {"targets": 2, "name": "activity", 'searchable': false, 'orderable': false}
                                
                            ]
                        });
                        var table = $('#student_propack_todo_list_table').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                                "type": "POST",
                                data: {'action': 'ajax_student_propack_todo_list','is_ajax' : '1','student_id':"<?php echo $_GET['student'];?>"},
                            },
                            "order": [[0, 'DESC']],
                            "columnDefs": [
                                {"targets": 0, "name": "title", 'searchable': false, 'orderable': true},
                                {"targets": 1, "name": "activity", 'searchable': false, 'orderable': false},
                                {"targets": 2, "name": "activity", 'searchable': false, 'orderable': false}
                                
                            ]
                        });
                        $('#propack').removeAttr('data-load');
                    }
                });
            }); 
		function text_restrictions(el, minLength = 999) {
				// filter value: lowercase + only a–z, 0–9, - and _
				el.value = el.value
					.replace(/[^a-zA-Z0-9\-_,.]/g, '')
					.slice(0, minLength); // <-- stop at max length

				// error element (id + "-error")
				const errorEl = document.getElementById(el.id + '-error');

				// show or hide error based on min length
				if (el.value.length < minLength) {
					errorEl.textContent = 'Minimum ' + minLength + ' characters required.';
					errorEl.style.display = 'block';
				} else {
					errorEl.style.display = 'none';
				}
			}
            
            
    </script>

