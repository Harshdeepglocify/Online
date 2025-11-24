<?php
$student_id = !empty($_GET['student']) ? $_GET['student'] : '';

$start_date = date('Y-m-d', strtotime('-6 days'));

$end_date = date('Y-m-d');

$current_time = strtotime(date('Y-m-d'));

$start_date = date('Y-m-d', strtotime('Last Monday', $current_time));

$end_date = date('Y-m-d', strtotime('Next Sunday', $current_time));

//check the current day
if (date('D') != 'Mon') {
    //take the last monday
    $start_date = date('Y-m-d', strtotime('Last Monday', $current_time));
} else {
    $start_date = date('Y-m-d');
}

//always next saturday

if (date('D') != 'Sat') {
    $end_date = date('Y-m-d', strtotime('Next Sunday', $current_time));
} else {
    $end_date = date('Y-m-d');
}
$history_end_date = date('Y-m-d');
$start_date = getTimezonewiseDate($start_date);
$end_date = getTimezonewiseDate($end_date);
$history_start_date = getTimezonewiseDate($start_date);
$history_end_date = getTimezonewiseDate($history_end_date);
?>

<style type="text/css">
    p.error{
        color: red;
    }
</style>
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
<br>
<div class="row">
    <div class="col-md-8">
        <div class="box-header-init">

            <h2 class="dashboard-h2">This Week ( <?php echo date('m/d/y', strtotime($start_date)); ?> - <?php echo date('m/d/y', strtotime($end_date)); ?> ) </h2>
        </div>
    </div>
    <div class="col-md-4">
        <!-- btn -->
        <!--  <div class="action-btn-chart-init">
             <div class="btn-group">
                 <button type="button" class="btn btn-danger">Action</button>
                 <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                     <span class="caret"></span>
                     <span class="sr-only">Toggle Dropdown</span>
                 </button>
                 <ul class="dropdown-menu" role="menu">
                     <li><a href="#">Print</a></li>
                     <li class="divider"></li>
                     <li><a href="#">Save</a></li>
                 </ul>
             </div>
         </div> -->
        <!-- end btn -->
    </div>
</div>

<div class="row">
    <?php
    $QuickCards_data = get_QuickCards_data($student_id, $start_date, $end_date);

	if( !empty($QuickCards_data) ) {

    ?>
    <!-- table -->
    <div class="col-md-6">
        <div class="box-header">
            <h3 class="dashboard-h3"><strong><?php echo count($QuickCards_data) ?> Lessons complete</strong></h3>
        </div>

        <div class="box-body table-responsive no-padding">
            <div class="quick-ajax-response" aria-live="assertive" style="display:none;">
                <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert alert-dismissible fade in" >
                </div>
            </div>
            <table class="table table-hover table-bordered" id="Quick_card_this_weeek">
                <tbody>
                    <tr>
                        <th >Name</th>
                        <th  >Score</th>
                        <th  >#Missed</th>
                        <th  >Missed Cards</th>
                        <th  >Date</th>
                        <th></th>
                    </tr>
                    <?php
                    if (!empty($QuickCards_data)) {

                        foreach ($QuickCards_data as $key => $value) {
                            echo '<tr>';
                            echo '<td>' . $value['file'] . '</td>';
                            echo '<td align="center">' . $value['percent_score'] . '%</td>';
                            echo '<td align="center">' . $value['incorrect'] . '</td>';
                            echo '<td>' . implode(', ', $value['cards_missed']) . '</td>';
                            echo '<td>' . date('m/d/y', strtotime($value['date'])) . '</td>';
                            echo '<td>';
                            echo '<a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red history-delete" data-id=' . $value['lognr'] . '>
                                          <i class="fa fa-trash-o" aria-label="Delete"></i></a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- end table -->
    <!-- Area Chart -->
    <div class="col-md-6">
        <div class="chart">
            <div id="Quickcards_weekly_report" style="height: 250px;" height="250"></div>
        </div>
        <table id="Quickcards_weekly_report_table" style="display:none;">
            <tbody>
                <tr>
                    <th></th>
                    <th>Percent</th>
                </tr>
                <?php
                if (!empty($QuickCards_data)) {
                    foreach ($QuickCards_data as $key => $value) {
                        echo '<tr>';
                        echo '<td>' . $value['file'] . '</td>';
                        echo '<td>' . $value['percent_score'] . '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- End Area Chart -->

    <?php
	} else { ?>
		<div class="no-activity-msg-wrap">
			<div class="no-activity-msg">No activity this week</div>
		</div>
	<?php } ?>
</div>
<!--  End Quick cards Sectoin one  -->

<div class="row">
    <div class="space-margin-bottom-50"></div>
    <div class="wt-cus-dvider"></div>
    <div class="space-margin-bottom-50"></div>
</div>

<!--  Quick cards Sectoin two  -->


<!-- <div class="row">
    <div class="col-md-12">
        <div class="box-header-init">
            <h2 class="dashboard-h2">Deck Maker</h2>
        </div>
    </div>
</div> -->

<div class="row">
    <div class="col-md-6">
        <div class="student-decks student-decks-maker_type_1" id="student_decks">
            <div class="quick-ajax-response" aria-live="assertive">
                <div style="font-family: Roboto Slab, serif; background-color: #000; color: #fff" class="alert alert-dismissible fade in"></div>
            </div>
            <!-- /.box-header -->
            <div class="box-body students_decks_tbl">
                <h2 class="dashboard-h2">Flashcard decks</h2>
                <table class="table table-bordered" id="students_decks_tbl">
                    <tbody>
                        <tr>
                            <th style="width: 20px">Deck Name</th>
                            <th style="width: 20px">Cards</th>
                            <th style="width: 40px">Edit</th>
                            <th style="width: 40px">Delete</th>
                        </tr>
                        <?php
                        $text_data = get_data_from_text_table($student_id, 'QC-OL', 0);

                        if (!empty($text_data)) {

                            foreach ($text_data as $key => $value) {
                                echo '<tr class="deck_'.$value['table_id'].'">';
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
                <!--<button type="button" style='margin-top:10px;' class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#deck-maker-modal">Create New Deck</button>
                <button type="button" class="dashboard-settings-btn btn-block deck-import-modal " data-toggle="modal" data-target="#deck-import-modal">Import Deck</button>-->
				<div class="new-option-section">
                    <button type="button" style="margin-top:15px;" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#deck-maker-modal" >Create New Deck</button>
                    <button class="new-option-btn" id="new-option-btn" aria-label="more options">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                </div>
                <div class="new-option-section-wrap" style="display:none;" id="new-option-section-wrap">
                        <ul class="new-option">
                            <li>
                                <button type="button"  class="dashboard-settings-btn btn-block deck-import-modal " data-toggle="modal" data-target="#deck-import-modal">Import Deck from Share Code </button>
                            </li>                               
                            <li>
                                <button type="button" class="dashboard-settings-btn btn-block deck-csv-import-modal" data-toggle="modal" data-target="#typio-import-csv_modal" onclick="set_focus_file_type();">Import from CSV</button>
                            </li>
                        </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="student-decks student-decks-maker_type_2" id="student_test">
            <div class="quick-ajax-response" aria-live="assertive" >
                <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert alert-dismissible fade in" >
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <h2 class="dashboard-h2">Tests</h2>
                <table class="table table-bordered" id="students_test_tbl">
                    <tbody>
                        <tr>
                            <th >Test Name</th>
                            <th style="width: 40px  !important;">Q's</th>
                            <th style="width: 40px !important;">Edit</th>
                            <th style="width: 40px !important;">Delete</th>
                        </tr>
                        <?php
                        $text_data = get_data_from_text_table($student_id, 'QC-OL', 1);

                        if (!empty($text_data)) {
                            foreach ($text_data as $key => $value) {
                                echo '<tr class="test_'.$value['table_id'].'">';
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
                <!--<button type="button" style='margin-top:10px;' class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-students-test-modal">Create New Test</button>
                <button type="button" class="dashboard-settings-btn btn-block deck-test-import-modal " data-toggle="modal" data-target="#deck-test-import-modal">Import Test</button>-->
				<div class="new-option-section">
                        <button type="button" style='margin-top:10px;' class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-students-test-modal">Create New Test</button>
                        <button class="new-option-btn" id="new-option-btn-test-pg">
                            <i class="fa fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="new-option-section-wrap-test-pg" style="display:none;" id="new-option-section-wrap-test-pg">
                            <ul class="new-option">
                                <li>
                                    <button type="button" class="dashboard-settings-btn btn-block deck-test-import-modal " data-toggle="modal" data-target="#deck-test-import-modal">Import Test</button>
                                </li>                               
                            </ul>
                    </div>
            </div>
        </div>
    </div>
</div>
<!--  End Quick cards Sectoin two  -->
<div class="row">
    <div class="space-margin-bottom-50"></div>
    <div class="wt-cus-dvider"></div>
    <div class="space-margin-bottom-50"></div>
</div>

<!--  Quick cards Sectoin three  -->
<div class="row" >
    <div class="col-md-12">
        <div class="box-header-init">
            <h2 class="dashboard-h2">History</h2>
        </div>
    </div>
    <form class="history-filter-p-init">
        <div class="form-group col-md-2 col-xs-12">
			<label>From:</label>
            <div class="input-group date">
                <input type="text" class="form-control pull-right datepicker" value="<?php echo date('m/d/Y', strtotime($history_start_date)); ?>" id="qc-start-date" aria-label="Beginning date field. Use the following format when typing a custom date range: two digit month, forward slash, two digit day, forward slash, two digit year.">
                <div class="input-group-addon">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                </div>
            </div>

            <!-- /.input group -->
        </div>
        <div class="form-group col-md-2 col-xs-12">
			<label>To:</label>
            <div class="input-group date">
                <input type="text" class="form-control pull-right datepicker" value="<?php echo date('m/d/Y', strtotime($history_end_date)); ?>" id="qc-end-date" aria-label="Beginning date field. Use the following format when typing a custom date range: two digit month, forward slash, two digit day, forward slash, two digit year.">
                <div class="input-group-addon">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                </div>
            </div>

            <!-- /.input group -->
        </div>
        <div class="form-group col-md-2 col-xs-12">
            <div class="btn-group">
                <input type="hidden" class="form-control qc-student-id" data-id="<?php echo $student_id; ?>" value="<?php echo $student_id; ?>" id="qc-student-id">
                <button type="button" name="qc-date-filter" id="qc-date-filter" class="dashboard-settings-btn btn-block" aria-label="Show History button">Show history</button>
            </div>
        </div>
    </form>
</div>

<div class="row" id="qc-history-section">
    <?php
    $qc_history_data = get_QuickCards_data($student_id,$history_start_date, $history_end_date);
    $class_history_quick_hide = empty(count($qc_history_data))? ' history_hide ' : '';
    ?>
    <!-- table -->
    <div class="col-md-6 <?php echo $class_history_quick_hide; ?>" id="qc-table-display-wrap">
        <div class="box-header">
            <h3 class="dashboard-h3"><strong><?php echo count($qc_history_data) ?> Lessons complete</strong></h3>
        </div>
        <!-- quick-card-history-ajax-response -->
        <div class="quick-card-history-ajax-response" style="display: none;">
            <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert alert-dismissible fade in" >
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover table-bordered" id="Quickcards_general_report_display">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <th>Score</th>
                        <th># Missed</th>
                        <th>Missed Cards</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                    <?php
                    if (!empty($qc_history_data)) {
                        foreach ($qc_history_data as $key => $value) {
                            echo '<tr>';
                            echo '<td>' . $value['file'] . '</td>';
                            echo '<td align="center">' . $value['percent_score'] . '%</td>';
                            echo '<td align="center">' . $value['incorrect'] . '</td>';
                            echo '<td>' . implode(', ', $value['cards_missed']) . '</td>';
                            echo '<td>' . date('m/d/y', strtotime($value['date'])) . '</td>';
                            echo '<td>';
                            echo '<a href="javascript:void(0)" data-toggle="modal" data-target="#delete-modal-set" class="badge bg-red this-history-delete" data-id=' . $value['lognr'] . '>
                                          <i class="fa fa-trash-o" aria-label="Delete"></i></a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- end table -->
    <!-- Area Chart -->
    <div class="col-md-6 <?php echo $class_history_quick_hide; ?>">
        <div class="chart">
            <div id="Quickcards_general_report" style="height: 250px;" height="250"></div>
        </div>
        <table id="Quickcards_general_report_tabel" style="display:none;">
            <tbody>
                <tr>
                    <th></th>
                    <th>Percent</th>
                </tr>
                <?php
                if (!empty($qc_history_data)) {
                    foreach ($qc_history_data as $key => $value) {
                        echo '<tr>';
                        echo '<td>' . $value['file'] . '</td>';
                        echo '<td>' . $value['percent_score'] . '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- End Area Chart -->

    <?php
        if( !empty( $class_history_quick_hide ) ){
        	echo '<div class="no-history-msg-wrap">
					<div class="no-history-msg">No history to display</div>
				</div>';
        } ?>
</div>
<!--  End Quick cards Sectoin three  -->
<div class="row">
    <div class="space-margin-bottom-50"></div>
    <div class="wt-cus-dvider"></div>
    <div class="space-margin-bottom-50"></div>
</div>
<!--  Quick cards Sectoin four  -->
<div class="row settings-area-p-init">

</div>

<div class="row settings-area-p-init">

    <div class="qc-setting-alert alert alert-dismissible"></div>
    <?php
    $qc_options_value = array();
    $qc_settings_options = mysqli_query($con, "SELECT * FROM `settings` WHERE `id` = '" . $student_id . "' AND `item` IN (6,126,215,216,217,218,219,220,221,222,226,227,228,234,241,242,243)");

    while ($settings_options = mysqli_fetch_assoc($qc_settings_options)) {

        $qc_options_value[$settings_options['item']] = $settings_options['variable'];
    }

    $typio_sfx_options = array('Default' => 'Default', 'Jungle' => 'Jungle', 'Cool' => 'Cool', 'Ninja' => 'Ninja', 'Chicken' => 'Chicken', 'Boxing' => 'Boxing', 'Vibraphone' => 'Vibraphone', 'Soft' => 'Soft', 'Silent' => 'Silent');

    $typio_visual_fx = array(1 => 'On', 0 => 'Off');
    $typio_subtitles = array(1 => 'On', 0 => 'Off');

    $typio_selection_color = array(1 => 'Accessory Color', 0 => 'Invert')
    ?>


    <form method="post" action="" name="qc-settings-form" id="qc-settings-form">
        <div class="col-md-12">
            <div class="box-header-init">
                <h2 class="dashboard-h2">Visual Settings</h2>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Font Size</h3></label>
                <select class="form-control" name="qc-options[215]" aria-label="Font Size">
                    <?php
                    if (!empty($font_size_options)) {
                        foreach ($font_size_options as $key => $value) {
                            echo '<option ' . (!empty($qc_options_value[215]) && $qc_options_value[215] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Font Style</h3></label>
                <select class="form-control" name="qc-options[217]" aria-label="Font Style">
                    <?php
                    if (!empty($font_style_options)) {
                        foreach ($font_style_options as $key => $value) {
                            echo '<option ' . (!empty($qc_options_value[217]) && $qc_options_value[217] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Font Color</h3></label>
                <select class="form-control" name="qc-options[216]" aria-label="Font Color">
                    <?php
                    if (!empty($color_options)) {
                        foreach ($color_options as $key => $value) {
                            echo '<option ' . (!empty($qc_options_value[216]) && $qc_options_value[216] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">BG Color</h3></label>
                <select class="form-control" name="qc-options[218]" aria-label="Background Color">
                    <?php
                    if (!empty($color_options)) {
                        foreach ($color_options as $key => $value) {
                            echo '<option ' . (!empty($qc_options_value[218]) && $qc_options_value[218] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Accessory Color</h3></label>
                <select class="form-control" name="qc-options[219]" aria-label="Accessory Color">
                    <?php
                    if (!empty($color_options)) {
                        foreach ($color_options as $key => $value) {
                            echo '<option ' . (!empty($qc_options_value[219]) && $qc_options_value[219] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-4">

                <label><h3 class="dashboard-h3">Selection Style</h3></label>

                <select class="form-control" name="qc-options[243]" aria-label="Selection Style">

                    <option value="0" <?php echo (isset($qc_options_value[243]) && $qc_options_value[243] == 0) ? 'selected="selected"' : ''; ?>>Invert</option>

                    <option value="1" <?php echo (isset($qc_options_value[243]) && $qc_options_value[243] == 1) ? 'selected="selected"' : ''; ?>>Accessory Color</option>

                </select>

            </div>

            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Subtitles</h3></label>
                <select class="form-control" name="qc-options[241]" aria-label="Subtitles">
                    <?php
                    if (!empty($typio_visual_fx)) {
                        foreach ($typio_visual_fx as $key => $value) {
                            echo '<option ' . ( isset($qc_options_value[241]) && $qc_options_value[241] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Visual FX</h3></label>
                <select class="form-control" name="qc-options[242]" aria-label="Visual FX">
                    <?php
                    if (!empty($typio_visual_fx)) {
                        foreach ($typio_visual_fx as $key => $value) {
                            echo '<option ' . ( isset($qc_options_value[242]) && $qc_options_value[242] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>



        </div>
	<br>
	<hr width="90%" size="6" align="center" color="#e5e5e5" border-color="#e5e5e5">
        <div class="col-md-12">
            <div class="box-header-init"> <br>
                <h2 class="dashboard-h2">Audio Settings</h2>
            </div>
        </div>

        <!-- end first col-md-4 -->
        <div class="col-md-12">


            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Voice</h3></label>
                <select class="form-control" name="qc-options[220]" disabled="disabled" aria-label="Voice. Can't be edited outside of the program">
                    <?php
                    if (!empty($voice_options)) {
                        foreach ($voice_options as $key => $value) {
                            echo '<option ' . (!empty($qc_options_value[220]) && $qc_options_value[220] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Voice Rate</h3></label>
                <select class="form-control" name="qc-options[221]" aria-label="Voice rate">
                    <?php
                    if (!empty($voice_rate_options)) {
                        foreach ($voice_rate_options as $key => $value) {
                            echo '<option ' . (!empty($qc_options_value[221]) && $qc_options_value[221] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Voice Pitch</h3></label>
                <select class="form-control" name="qc-options[222]" aria-label="Voice Pitch">
                    <?php
                    if (!empty($voice_pitch_options)) {
                        foreach ($voice_pitch_options as $key => $value) {
                            echo '<option ' . (!empty($qc_options_value[222]) && $qc_options_value[222] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- <div class="form-group">
                <br />
            </div> -->

            <!-- <div class="form-group">
                <label>Card Flip</label>
                <select class="form-control" name="qc-options[226]">
            <?php
            if (!empty($card_flip_options)) {
                //foreach ($card_flip_options as $key => $value) {
                //  echo '<option ' . ( isset($qc_options_value[226]) && $qc_options_value[226] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                //}
            }
            ?>
                </select>
            </div> -->

            <!--  <div class="form-group">
                 <label># of Answers</label>
                 <select class="form-control" name="qc-options[227]">
            <?php
            /* if (!empty($answer_options)) {
              foreach ($answer_options as $key => $value) {
              echo '<option ' . (!empty($qc_options_value[227]) && $qc_options_value[227] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
              }
              } */
            ?>
                 </select>
             </div> -->

            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">SFX</h3></label>
                <select class="form-control" name="qc-options[126]" aria-label="Sound effects">
                    <?php foreach ($typio_sfx_options as $key => $value) { ?>
                        <option value="<?php echo $key; ?>" <?php echo (!empty($ty_options_value[126]) && $ty_options_value[126] == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
	<br>
	<hr width="90%" size="6" align="center" color="#e5e5e5" border-color="#e5e5e5">

        <div class="col-md-12">
            <div class="box-header-init"><br>
                <h2 class="dashboard-h2">App Settings</h2>
            </div>
        </div>

        <!-- end first col-md-4 -->
        <div class="col-md-12">
            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Settings Lock</h3></label>
                <select class="form-control" name="qc-options[234] aria-label="Settings lock"">
                    <?php
                    if (!empty($settings_lock_options)) {
                        foreach ($settings_lock_options as $key => $value) {
                            echo '<option ' . ( isset($qc_options_value[234]) && $qc_options_value[234] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group  col-md-4">
                <label><h3 class="dashboard-h3">Deck / Test Editor Lock</h3></label>
                <select class="form-control" name="qc-options[228]" aria-label="Deck and Test editor lock">
                    <?php
                    if (!empty($deck_lock_options)) {
                        foreach ($deck_lock_options as $key => $value) {
                            echo '<option ' . ( isset($qc_options_value[228]) && $qc_options_value[228] == $key ? 'selected="selected"' : '' ) . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="form-group"><br>
                <button type="button" name="qc-settings-submit" id="qc-settings-submit" class="dashboard-settings-btn btn-block" aria-label="Save settings button">Save settings</button>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="edit-modal-set">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Decks Editor</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="dashboard-settings-btn btn-block edit-modal-save">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->

        <script>
            function check_duplicate_dock_edit(table_id) {
                var title = $('.deck_title_' + table_id).val();
                var student_id = '<?php echo $_GET['student']; ?>';

                $.ajax({
                    type: 'POST',
                    url: ADMIN_URL + 'config/check_duplicate_entry.php',
                    data: {table_id: table_id, title: title, item_name: 'QC-OL', number: 0,student:student_id},
                    dataType: 'json',
                    success: function (result) {
                        $('.deck_title_edit_' + table_id).html(result);
                        if (result) {
                            $('.deck_title_edit_' + table_id).show();
                            $('.edit-modal-save').attr('disabled','disabled');
                        } else {
                            $('.deck_title_edit_' + table_id).hide();
                            $('.edit-modal-save').removeAttr('disabled');
                        }
                    }
                });
            }
        </script>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="deck-maker-modal" aria-hidden="false" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Deck Maker</b></h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="add-students-deck-form">
                    <div class="form-group">
                        <label>Deck Name</label>
                        <input class="form-control" id="deck_name" name="deck_name" type="text"  placeholder="Type Deck Name here..." required="" value="" />
                        <input id="user_id" type="hidden" name="user_id" value="<?php echo $student_id; ?>"/>
                        <p id="error_desk_title_alert" class="error" style="display:none;">Deck name field required</p>
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
                    <div class="row"></div>

                </form>
            </div>
            <div class="modal-footer">
                <div class="col-md-12" style="margin-bottom: 10px">
                    <button type="button" class="dashboard-settings-btn btn-block pull-right" id="add-more-side_fields">Add Card to Deck</button>
                    <p id="error_desk_add_card" class="error" style="display:none;" style="font-weight: bold;">Press Add card to deck Button</p>
                </div>

                <div class="col-md-12">

                    <button type="button" class="dashboard-settings-btn btn-block add-student-deck-btn" name="add-students">Save Deck</button>
                </div>


            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="edit-deck-maker-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title color"><b>Deck Editor</b></h4>
                <input type="hidden" id="user_type"   value="0">
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
                            <p id="error_side_alert" class="error" style="display:none;">Side A field required</p>
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
                    </div>
                </div>

                <div class="col-md-12">
					<div class='export-div col-md-8'>
									<div class='code_copy col-md-7'></div>
									<button type="button" class="dashboard-settings-btn btn-block deck-export-modal">Export Deck</button>
									<p class='msg' style="display:none;">Code copied To click Board</p>

								</div>
								<div class='col-md-4'>
                    <button type="button" class="dashboard-settings-btn btn-block edit-modal-save">Save changes</button>
					</div>
                </div>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class="modal fade" id="typio-import-csv_modal" aria-hidden="false" style="display: none;">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close" >
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Deck Maker CSV import</h4>

			</div>

			<div class="modal-body">

				<form method="POST" name="add-typio-csv-import-form" id="add-typio-csv-import-form" enctype="multipart/form-data">
					<div class="form-group">
						<label>CSV File</label>
						 <input type="file" class="form-control" name="typio_file_upload" id="typio_file_upload">
					</div>
					<input type="hidden" name="student_overview_page" id="student_overview_page" value="1"/>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col-md-12">
					<button type="button" class="dashboard-settings-btn btn-block" name="import_csv_typio" id="import_csv_typio">Import Deck</button>
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
<!-- add new lola's test model -->
<div class="modal fade" id="add-students-test-modal" aria-hidden="false" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title color"><b>Test Maker</b></h4>
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
                        <button type="button" class="dashboard-settings-btn btn-block" id="add-more-question">Add Question to Test</button>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="dashboard-settings-btn btn-block" id="add-student-test-btn">Save Test</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- add quick card desk test import-->

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
                <h4 class="modal-title color"> Test Editor </h4>
                 <!-- <input class="form-control" id="question_number_in_edit" type="text"  value="0" /> -->
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">

                <div style="text-align:left" id="new-question-div">
                    <div class="form-group" >
                        <label class="color">Question</label>
                        <input class="form-control" id="question_new_edit" type="text"  placeholder="Type question here..." />
                        <p id="error_question_alert_edit" class="error" style="display:none;">Question not blank!</p>
                    </div>
                    <div class="form-group"  >
                        <label class="color">Answers (check box next to correct answer)</label>
                        <div id="append_answer_in_edit">
                            <div style="margin-bottom: 5px" class="question_option_row_new_add_edit">
                                <p id="error_select_only_one_ans_edit" class="error" style="display:none;">Please select the only one right answer</p>
                                <div style="margin-top: 8px;width: 5%;float: left;">
                                    <input type="checkbox"   class="question_option_check_edit"   >
                                </div>
                                <div style="width: 95%;float: left;margin-bottom: 8px;">
                                    <input class="form-control question_option_edit_box"  type="text"   placeholder="Type answer..." required=""/>
                                    <p id="error_option_not_blank_edit" class="error" style="display:none;">Can not have option field blank</p>
                                    <p id="error_max_six_option_edit" class="error" style="display:none;">Can not have more than 6 option fields</p>
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
                <div class="col-md-12">
					<div class='export-test-div col-md-8'>
						<div class='test_code_copy col-md-7'></div>
						<button type="button" class="dashboard-settings-btn btn-block deck-test-export-modal ">Export Deck</button>
						<p class='test-msg' style="display:none;">Code copied To click Board</p>

					</div>
					<div class='col-md-4'>
                    <button type="button" class="dashboard-settings-btn btn-block edit-students-test-save">Save changes</button>
					</div>
                </div>
            </div>
            <script>
                function check_duplicate_test_edit(table_id) {
                    var title = $('.test_title_' + table_id).val();
                    var student_id = '<?php echo $_GET['student']; ?>';

                    $.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/check_duplicate_entry.php',
                        data: {table_id: table_id, title: title, item_name: 'QC-OL', number: 1,student:student_id},
                        dataType: 'json',
                        success: function (result) {
                            $('.test_title_edit_' + table_id).html(result);
                            if (result) {
                                $('.test_title_edit_' + table_id).show();
                                $('.edit-students-test-save').attr('disabled','disabled');
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
