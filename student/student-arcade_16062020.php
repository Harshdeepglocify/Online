<?php
$student_id = !empty($_GET['student']) ? $_GET['student'] : '';

$expload_license = '';
if ($studentid) {
    $expload_license = explode('-', $student_data[0]['license']);
}

if (isset($_REQUEST['ArcadeHistoryDateSubmit']) && ($_REQUEST['ArcadeHistoryDateSubmit'] == 'Show')) {
    $arcade_history_from = date('Y-m-d', strtotime($_REQUEST['ArcadeHistoryDateFrom']));
    $arcade_history_to = date('Y-m-d', strtotime($_REQUEST['ArcadeHistoryDateTo']));
    $arcade_history_submit_check = 'Checked';
} else {
    $arcade_history_from = date('Y-m-d', strtotime('-14 days'));
    $arcade_history_to = date('Y-m-d');
    $arcade_history_submit_check = 'Unchecked';
}

global $studentid;
if (isset($_POST['ArcadeSettingSubmit'])) {
    unset($_POST['ArcadeSettingSubmit']);
    $data = updateAppArcadeSettingData($_POST);

    $arcade_setting_submit_check = 'Checked';
} else {
    $arcade_setting_submit_check = 'Unhecked';
}
?>
<!--   Arcade Section one   --> 

<!-- hidden on 11/11/19 since this section didn't work or show anything -->
<div class="row" id="ArcadeHistoryDateSection">
    <div class="col-md-12">
        <div class="box-header-init">
            <h2 class="dashboard-h2">History</h2>
        </div>
    </div>
    <div class="form-group col-md-2 col-xs-12"> 
		<label>From:</label>	
        <div class="input-group date">
            <input id="ArcadeDatePickerFrom" name="ArcadeHistoryDateFrom" value="<?php echo date('m/d/Y', strtotime($arcade_history_from)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm-dd-yyyy" data-date-end-date="0d">
            <div class="input-group-addon">
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </div>
         </div>
    </div>

    <div class="form-group col-md-2 col-xs-12">  
		<label>To:</label>
        <div class="input-group date">
            <input id="ArcadeDatePickerTo" name="ArcadeHistoryDateTo" value="<?php echo date('m/d/Y', strtotime($arcade_history_to)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm-dd-yyyy" data-date-end-date="0d">
            <div class="input-group-addon">
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </div>
        </div>
    </div>

    <div class="form-group col-md-2 col-xs-12">
        <!-- btn -->
       <div class="btn-group">
            <input type="hidden" value="<?php echo $arcade_history_submit_check; ?>" id="ArcadeHistoryDateSubmitCheck">
            <input type="submit" name="ArcadeHistoryDateSubmit" value="Show history" id="ArcadeDateFilterBtn" class="dashboard-settings-btn" aria-label="Show Hisotry button">
        </div>
        <!-- end btn -->
     </div>
</div>

<div class="row">
    <?php
    $arcade_history = getArcadeHistory($student_id, $arcade_history_from, $arcade_history_to);
    $class_history_arcade_hide = empty($arcade_history)? ' history_hide ' : '';
    ?>
 
    <div class="col-md-12 ">
    	<div class="ArcadeHistory_table_wrap <?php echo $class_history_arcade_hide; ?>">
        
	    	<div class="box-header">
	            <h2 class="dashboard-h2"><strong><?php echo count($arcade_history) ?>Lessons complete</strong></h2>            
	        </div>
	        <div class="box-body table-responsive no-padding">
	            <table class="table table-hover table-bordered" id="ArcadeHistory_table">
	                <tbody>
	                    <tr>
	                        <th>Name</th>
	                        <th>Date</th>                       
	                    </tr>
	                    <?php
	                    if (!empty($arcade_history)) {
	
	                        foreach ($arcade_history as $key => $value) {
	
	                            echo '<tr>';
	                            echo '<td>' . $value['file'] . '</td>';
	                            echo '<td>' . $value['date'] . '</td>';
	                            echo '</tr>';
	                        }
	                    }
	                    ?>                    
	                </tbody>
	            </table>
	        </div>
		
    	</div>
    <?php
	if( !empty( $class_history_arcade_hide ) ){ ?>
		<div class="arcade no-activity-msg-wrap">
			<div class="no-activity-msg">No history to display</div>
		</div>
	<?php } ?>
	</div>
    <!-- end table -->                  
</div>

<!--   End Arcade Sectoin one   -->
<!-- space removed when history section hidden-->
<div class="row">
    <div class="space-margin-bottom-50"></div>                        
    <hr>
    <div class="space-margin-bottom-50"></div>
</div>
<!--   Arcade Sectoin two   -->
<?php

function gethangmanData() {
    global $con, $studentid;
    $hangmanData = array();
    $table = 'text';
    $query = query("SELECT * FROM text WHERE text.app='Arcade-OL' AND text.number=2 AND id='" . $studentid . "'");
    while ($row = mysqli_fetch_array($query)) {
        $hangmanData[] = $row;
    }
    return $hangmanData;
}

$hangmaneGameData = gethangmanData();
?>
<div class="row">
    <div class="col-md-6">
        <div class="hangman_msg">
            <div class="with-border">
                <h2 class="dashboard-h2">Hangman</h2>
            </div>
            <div class="quick-ajax-response" aria-live="assertive">
                <div class="alert alert-dismissible fade in" id="alert-success">
                </div>
            </div> 
            
            <!-- /.box-header -->
            <div class="box-body hangman-table">
                <table class="table table-bordered" id="hangman-table">
                    <tbody>
                        <tr>                            
                            <th>Task</th>
                            <th style="width: 40px !important">Edit</th>
                            <th style="width: 40px !important">Delete</th>
                        </tr>
                        <?php
                        if (isset($hangmaneGameData) && !empty($hangmaneGameData)) {
                            $i = 1;
                            foreach ($hangmaneGameData as $key => $value) {
                                ?>
                                <tr class="hangman_tr_<?php echo $value['table_id']; ?>">                                    
                                    <td><?php echo $value['title'] ?></td>
                                    <td><a href="#" class="badge bg-green edit-hangman-modal" data-id="<?php echo $value['table_id'] ?>">
                                            <i class="fa  fa-edit"></i></a>
                                    </td>
                                    <td><a href="#" class="badge bg-red delete-hangman" data-toggle="modal" data-target="#delete-modal-hangman" data-id="<?php echo $value['table_id'] ?>">
                                            <i class="fa fa-trash-o" aria-label="Delete"></i></a>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
 			<button type="button" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-hangman-modal" aria-label="Create new hangman lesson button">Create new hangman lesson</button>
    </div>
</div>

<!--   End Arcade Sectoin two   -->
<div class="row">
    <div class="space-margin-bottom-50"></div>
    <hr>
    <div class="space-margin-bottom-50"></div>
</div>

<!--   Arcade Sectoin three   -->

<?php

function getsectionThreeData() {
    global $con, $studentid;
    $sectionThreeData = array();
    $table = 'text';
    $query = query("SELECT * FROM text WHERE text.app='Arcade-OL' AND text.number=1 AND id='" . $studentid . "'");
    while ($row = mysqli_fetch_array($query)) {
        $sectionThreeData[] = $row;
    }
    return $sectionThreeData;
}

$gameData = getsectionThreeData();
?>
<div class="row">
    <div class="col-md-6">
        <div class="crazy_msg">
            <div class="with-border">
                <h3 class="box-title">Crazy Phrase</h3>
            </div>
            <div class="quick-ajax-response " aria-live="assertive">
                <div class="alert alert-dismissible fade in" id="alert-success">
                </div>
            </div> 
            
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add-game-modal">Add New</button>

            <!-- /.box-header -->

            <div class="box-body game-table">
                <table class="table table-bordered" id="game-table">
                    <tbody>
                        <tr>
                            
                            <th>Task</th>
                            <th style="width: 40px !important">Edit</th>
                            <th style="width: 40px !important">Delete</th>
                        </tr>
                        <?php
                        if (isset($gameData) && !empty($gameData)) {
                            $j = 1;
                            foreach ($gameData as $k => $v) {
                                ?>
                                <tr class="game_tr_<?php echo $v['table_id']; ?>">
                                    
                                    <td><?php echo $v['title'] ?></td>
                                    <td>
                                        <a href="#" class="badge bg-green game-data-edit" data-id="<?php echo $v['table_id'] ?>">
                                            <i class="fa  fa-edit"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" class="badge bg-red game-data-delete" data-id="<?php echo $v['table_id'] ?>">
                                            <i class="fa fa-trash-o" aria-label="Delete"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                $j ++;
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!--  End Arcade Sectoin three  --> 
<!-- Space removed when crazy phrase section removed-->
<div class="row">
    <div class="space-margin-bottom-50"></div>
    <hr>
    <div class="space-margin-bottom-50"></div>
</div>

<!--  Arcade Sectoin four  -->   
<div class="row" id="ArcadeSettingsSection">
<?php
$arcade_settings = "settings";

$arcade_settings_query = query("SELECT * FROM `" . $arcade_settings . "` WHERE id =" . $studentid . " AND item IN (315,316,317,318,319,320,321,322,325,326,327,328,330,334,6)");
while ($arcade_settings_data_row = fetch($arcade_settings_query)) {
    //Font Size
    if ($arcade_settings_data_row['item'] == '315') {
        $db_arcade_font_size = $arcade_settings_data_row['variable'];
    }
    //Font style
    if ($arcade_settings_data_row['item'] == '317') {
        $db_arcade_font_style = $arcade_settings_data_row['variable'];
    }
    //Voice
    if ($arcade_settings_data_row['item'] == '320') {
        $db_arcade_voice = $arcade_settings_data_row['variable'];
    }
    //Voice rrate
    if ($arcade_settings_data_row['item'] == '321') {
        $db_arcade_voice_rate = $arcade_settings_data_row['variable'];
    }
    //Voice Pitch
    if ($arcade_settings_data_row['item'] == '322') {
        $db_arcade_voice_pitch = $arcade_settings_data_row['variable'];
    }
    //Font Color
    if ($arcade_settings_data_row['item'] == '316') {
        $db_arcade_font_color = $arcade_settings_data_row['variable'];
    }
    //BG Color
    if ($arcade_settings_data_row['item'] == '318') {
        $db_arcade_bg_color = $arcade_settings_data_row['variable'];
    }
    //Accessory Color
    if ($arcade_settings_data_row['item'] == '319') {
        $db_arcade_accessory_color = $arcade_settings_data_row['variable'];
    }
    //Samurai Flash
    if ($arcade_settings_data_row['item'] == '326') {
        $db_arcade_samurai_flash = $arcade_settings_data_row['variable'];
    }
    //Echo Arrows
    if ($arcade_settings_data_row['item'] == '327') {
        $db_arcade_echo_arrows = $arcade_settings_data_row['variable'];
    }
    //Visuals
    if ($arcade_settings_data_row['item'] == '330') {
        $db_arcade_visuals = $arcade_settings_data_row['variable'];
    }
    //Music Volume
    if ($arcade_settings_data_row['item'] == '325') {
        $db_arcade_music_volume = $arcade_settings_data_row['variable'];
    }
    //Wizard's Towers SFX
    if ($arcade_settings_data_row['item'] == '328') {
        $db_arcade_wizard_tower_sfx = $arcade_settings_data_row['variable'];
    }
    //Settings Lock
    if ($arcade_settings_data_row['item'] == '334') {
        $db_arcade_settings_lock = $arcade_settings_data_row['variable'];
    }
    //License
    if ($arcade_settings_data_row['item'] == '6') {
        $db_arcade_license = $arcade_settings_data_row['variable'];
    }
}//End while
$arcade_font_size_options = array(20 => 'Small', 100 => 'Medium', 200 => 'Large');
$arcade_font_style_options = array('Roboto' => 'Regular', 'Roboto Condensed' => 'Condensed', 'Roboto Mono' => 'Monospaced', 'Roboto Slab' => 'Serif');
$arcade_voice_options = array('Google US English' => 'US English', 'Google UK English' => 'UK English');
$arcade_voice_rate_options = array(5 => 'Slow', 10 => 'Medium', 13 => 'Fast');
$arcade_voice_pitch_options = array(5 => 'Low', 9 => 'Medium', 12 => 'High');
$arcade_font_color_options = array('255, 255, 254' => 'Flat White', '242, 241, 239' => 'Hard White', '255, 192, 203' => 'Flat Pink', '255, 0, 102' => 'Accessibyte Pink', '242, 38, 19' => 'Flat Red', '239, 58, 39' => 'Hard Red', '139, 0, 0' => 'Dark Red', '237, 111, 53' => 'Flat Orange', '255, 255, 0' => 'Flat Yellow', '204, 204, 0' => 'Hard Yellow', '46, 204, 113' => 'Flat Green', '0, 100, 0' => 'Hard Green', '0, 255, 153' => 'Flat Teal', '0, 0, 255' => 'Flat Blue', '255, 255, 248' => 'Hard Blue', '0, 0, 139' => 'Dark Blue', '102, 51, 153' => 'Flat Purple', '139, 0, 139' => 'Hard Purple', '12, 18, 2' => 'Flat Black', '0, 0, 1' => 'Hard Black', '105, 105, 105' => 'Flat Grey');
$arcade_bg_color_options = $arcade_font_color_options;
$arcade_accessory_color_options = $arcade_font_color_options;
$arcade_samurai_flash_options = array(1 => 'On', 0 => 'Off');
$arcade_music_volume_options = array(0 => 'Silent', 1 => 'Normal', 2 => 'Quite');
$arcade_echo_arrows_options = array(0 => 'Bold', 1 => 'Thin', 2 => 'Double');
$arcade_wizard_tower_sfx_options = array(1 => 'Normal', 2 => 'Retro', 0 => 'Off');
$arcade_visuals_options = array(1 => 'On', 0 => 'Off');
$arcade_settings_lock_options = array(1 => 'On', 0 => 'Off');
?>
<div class="row">
    <form method="POST" class="setting-inner-arcade-p-init" action="">
        <div class="col-md-12"> 
            <div class="box-header-init"> 
                <h2 class="dashboard-h2">Visual Settings</h2> 
            </div> 
        </div>
        <div class="col-md-12">
            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Font Size</h3></label>
                <select class="form-control" name="arcade_font_size" aria-label="Font Size">
                    <?php foreach ($arcade_font_size_options as $arcade_font_size_option_key => $arcade_font_size_option_value) { ?>
                        <option value="<?php echo $arcade_font_size_option_key ?>" <?php echo ( isset($db_arcade_font_size) && $arcade_font_size_option_key == $db_arcade_font_size) ? 'selected="selected"' : '' ?>><?php echo $arcade_font_size_option_value ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Font Style</h3></label>
                <select class="form-control" name="arcade_font_style">
                    <?php foreach ($arcade_font_style_options as $arcade_font_style_option_key => $arcade_font_style_option_value) { ?>
                        <option value="<?php echo $arcade_font_style_option_key ?>" <?php echo ( isset($db_arcade_font_style) && $arcade_font_style_option_key == $db_arcade_font_style) ? 'selected="selected"' : '' ?>><?php echo $arcade_font_style_option_value ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Font Color</h3></label>
                <select class="form-control" name="arcade_font_color">
                    <?php foreach ($arcade_font_color_options as $arcade_font_color_options_key => $arcade_font_color_options_value) { ?>
                        <option value="<?php echo $arcade_font_color_options_key ?>" <?php echo ( isset($db_arcade_font_color) && $arcade_font_color_options_key == $db_arcade_font_color) ? 'selected="selected"' : '' ?>><?php echo $arcade_font_color_options_value ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">BG Color</h3></label>
                <select class="form-control" name="arcade_bg_color">
                    <?php foreach ($arcade_bg_color_options as $arcade_bg_color_options_key => $arcade_bg_color_options_value) { ?>
                        <option value="<?php echo $arcade_bg_color_options_key ?>" <?php echo ( isset($db_arcade_bg_color) && $arcade_bg_color_options_key == $db_arcade_bg_color) ? 'selected="selected"' : '' ?>><?php echo $arcade_bg_color_options_value ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Accessory Color</h3></label>
                <select class="form-control" name="arcade_accessory_color">
                    <?php foreach ($arcade_accessory_color_options as $arcade_accessory_color_options_key => $arcade_accessory_color_options_value) { ?>
                        <option value="<?php echo $arcade_accessory_color_options_key ?>" <?php echo ( isset($db_arcade_accessory_color) && $arcade_accessory_color_options_key == $db_arcade_accessory_color) ? 'selected="selected"' : '' ?>><?php echo $arcade_accessory_color_options_value ?></option>
                    <?php } ?>
                </select>
            </div>

<!--settings hidden until added to game-->

            <div class="form-group col-md-4">
                <label>Wizard's Tower SFX</label>
                <select class="form-control" name="wizards_tower_sfx">
                    <?php foreach ($arcade_wizard_tower_sfx_options as $arcade_wizard_tower_sfx_options_key => $arcade_wizard_tower_sfx_options_value) { ?>
                        <option value="<?php echo $arcade_wizard_tower_sfx_options_key ?>" <?php echo ( isset($db_arcade_wizard_tower_sfx) && $arcade_wizard_tower_sfx_options_key == $db_arcade_wizard_tower_sfx) ? 'selected="selected"' : '' ?>><?php echo $arcade_wizard_tower_sfx_options_value ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Samurai Flash</label>
                <select class="form-control" name="arcade_samurai_flash">
                    <?php foreach ($arcade_samurai_flash_options as $arcade_samurai_flash_options_key => $arcade_samurai_flash_options_value) { ?>
                        <option value="<?php echo $arcade_samurai_flash_options_key ?>" <?php echo ( isset($db_arcade_samurai_flash) && $arcade_samurai_flash_options_key == $db_arcade_samurai_flash) ? 'selected="selected"' : '' ?>><?php echo $arcade_samurai_flash_options_value ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Echo Arrows</label>
                <select class="form-control" name="arcade_echo_arrows">
                    <?php foreach ($arcade_echo_arrows_options as $arcade_echo_arrows_options_key => $arcade_echo_arrows_options_value) { ?>
                        <option value="<?php echo $arcade_echo_arrows_options_key ?>" <?php echo ( isset($db_arcade_echo_arrows) && $arcade_echo_arrows_options_key == $db_arcade_echo_arrows) ? 'selected="selected"' : '' ?>><?php echo $arcade_echo_arrows_options_value ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Visual FX</label>
                <select class="form-control" name="arcade_visual">
                    <?php foreach ($arcade_visuals_options as $arcade_visuals_options_key => $arcade_visuals_options_value) { ?>
                        <option value="<?php echo $arcade_visuals_options_key ?>" <?php echo ( isset($db_arcade_visuals) && $arcade_visuals_options_key == $db_arcade_visuals) ? 'selected="selected"' : '' ?>><?php echo $arcade_visuals_options_value ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <!-- end first col-md-4 -->
        <div class="col-md-12"> 
            <div class="box-header-init"> 
                <h2 class="dashboard-h2">Audio Settings</h2> 
            </div> 
        </div>
        <div class="col-md-12">
            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Voice</h3></label>
                <select class="form-control" name="arcade_voice">
                    <?php foreach ($arcade_voice_options as $arcade_voice_options_key => $arcade_voice_options_value) { ?>
                        <option value="<?php echo $arcade_voice_options_key ?>" <?php echo ( isset($db_arcade_voice) && $arcade_voice_options_key == $db_arcade_voice) ? 'selected="selected"' : '' ?>><?php echo $arcade_voice_options_value ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Voice Rate</h3></label>
                <select class="form-control" name="arcade_voice_rate">
                    <?php foreach ($arcade_voice_rate_options as $arcade_voice_rate_options_key => $arcade_voice_rate_value) { ?>
                        <option value="<?php echo $arcade_voice_rate_options_key ?>" <?php echo ( isset($db_arcade_voice_rate) && $arcade_voice_rate_options_key == $db_arcade_voice_rate) ? 'selected="selected"' : '' ?>><?php echo $arcade_voice_rate_value ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Voice Pitch</h3></label>
                <select class="form-control" name="arcade_voice_pitch">
                    <?php foreach ($arcade_voice_pitch_options as $arcade_voice_pitch_options_key => $arcade_voice_pitch_options_value) { ?>
                        <option value="<?php echo $arcade_voice_pitch_options_key ?>" <?php echo ( isset($db_arcade_voice_pitch) && $arcade_voice_pitch_options_key == $db_arcade_voice_pitch) ? 'selected="selected"' : '' ?>><?php echo $arcade_voice_pitch_options_value ?></option>
                    <?php } ?>
                </select>
            </div>
<!-- if this doesnt get used delete it-->
            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Music Volume</h3></label>
                <select class="form-control" name="arcade_music_volume">
                    <?php foreach ($arcade_music_volume_options as $arcade_music_volume_options_key => $arcade_music_volume_options_value) { ?>
                        <option value="<?php echo $arcade_music_volume_options_key ?>" <?php echo ( isset($db_arcade_music_volume) && $arcade_music_volume_options_key == $db_arcade_music_volume) ? 'selected="selected"' : '' ?>><?php echo $arcade_music_volume_options_value ?></option>
                    <?php } ?>
                </select>
            </div>

        </div>

        <!-- end first col-md-4 -->
        <div class="col-md-12"> 
            <div class="box-header-init"> 
                <h2 class="dashboard-h2">App Settings</h2> 
            </div> 
        </div>
        <div class="col-md-12">


            <div class="form-group col-md-4">
                <label><h3 class="dashboard-h3">Setting Lock</h3></label>
                <select class="form-control" name="arcade_settings_lock">
                    <?php foreach ($arcade_settings_lock_options as $arcade_settings_lock_options_key => $arcade_settings_lock_options_value) { ?>
                        <option value="<?php echo $arcade_settings_lock_options_key ?>" <?php echo ( isset($db_arcade_settings_lock) && $arcade_settings_lock_options_key == $db_arcade_settings_lock) ? 'selected="selected"' : '' ?> ><?php echo $arcade_settings_lock_options_value ?></option>
                    <?php } ?>
                </select>
            </div>
            
        </div>
        <div class="col-md-4 col-md-offset-4">
            <div class="form-group">
                <input type="hidden" value="<?php echo $arcade_setting_submit_check; ?>" id="ArcadeSettingSubmitCheck">
                <input type="submit" name="ArcadeSettingSubmit" value="Save" id="ArcadeSettingSubmitBtn" class="dashboard-settings-btn btn-block">
            </div>
        </div>
    </form>
</div>
</div>

<!-- add hangman modal -->
<div class="modal fade" id="add-hangman-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title color">Create new Hangman lesson </h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="add-hangman-form">
                    <div class="form-group">
                        <input class="form-control " id="hangman-title" type="text" name="title" placeholder="Type lesson title here..." required=""/>
                        <input id="user_id" type="hidden" name="user_id" value="<?php echo $student_id; ?>"/>
                    </div>
                    <div class="form-group">
                        <input class="form-control text_data" type="text" name="fields[1]" placeholder="Type a word here..." required=""/>
                    </div>
                    <div id="append"></div>
                    <button type="button" class="btn btn-primary" id="add-more-fields">Add New Word</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary add-hangman-btn">Save</button>
            </div>
        </div>
    </div>
</div>
<!--  End hangman modal  --> 
<div class="modal fade" id="edit-hangman-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title color">Hangman Editor</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary edit-hangman-btn">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
        <script>
            function check_duplicate_hangman_edit(table_id) {
                var title = $('.hangman_title_edit_' + table_id).val();
                var student_id = '<?php echo $_GET['student']; ?>';
                $.ajax({
                    type: 'POST',
                    url: ADMIN_URL + 'config/check_duplicate_entry.php',
                    data: {table_id: table_id, title: title, item_name: 'Arcade-OL', student: student_id, number: 2},
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

<!-- Add Game modal -->
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
                        <input id="user_id" type="hidden" name="user_id" value="<?php echo $student_id; ?>"/>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="data_text" type="text" name="data" placeholder="Type your story here..." required=""></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary add-game-btn">Save </button>
            </div>
        </div>
    </div>
</div>
<!--  End Game modal  --> 
<div class="modal fade" id="edit-game-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Crazy Phrase story</h4>

            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary edit-game-btn">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
        <script>

            function check_duplicate_game_edit(table_id) {
                var title = $('.game_title_edit_' + table_id).val();
                var student_id = '<?php echo $_GET['student']; ?>';
                $.ajax({
                    type: 'POST',
                    url: ADMIN_URL + 'config/check_duplicate_entry.php',
                    data: {table_id: table_id, title: title, item_name: 'Arcade-OL', student: student_id, number: 1},
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
<!-- /.modal -->