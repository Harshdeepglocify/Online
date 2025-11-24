<?php
	include "../config/config.php";
	require_once( '../helper/tcpdf/tcpdf.php');
	

	$teacher_name = ucfirst(base64_decode($_SESSION['User']['firstname']))." ".ucfirst(base64_decode($_SESSION['User']['lastname']));

    $student_ids = isset($_POST['ids']) ? $_POST['ids'] : '';
 	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    ob_start();
    $pdf->SetTitle('Accessibyte Student List');
    $pdf->SetHeaderMargin(30);
    
    $pdf->SetTopMargin(20);
    $pdf->setFooterMargin(20);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetAuthor('Author');
    $pdf->SetDisplayMode('real', 'default');
    $pdf->SetAutoPageBreak(TRUE);
    $pdf->AddPage(); 
    $fullpath = "Accessibyte Student List111.pdf";

    $file_name = "student_". date('his');

    $upload_folder = '/uploads/';
    $upload_dir = ADMIN_DIR.'/uploads/';
    $filename = "Accesibyte export student data ".$file_name.".pdf";
    
    $fullpath = $upload_dir."/".$filename;

    $main_path = ADMIN_URL .$upload_folder.$filename;
    $html = '';
    foreach($student_ids as $id){
        $args = array(
            'user_id' => $id,
            'role' => 'student',
        );
        $student_data = get_users($args);
        $student_name = isset($student_data[0]['firstname']) ? base64_decode($student_data[0]['firstname']) : '';

        $license = $student_data[0]['license'];

        $val = explode("-", trim($license));
        $is_tchp = true;
        if ($val[0] == "TCH")
            $is_tchp = false;
        $ty_options_value = array();

        $ty_settings_options = mysqli_query($con, "SELECT * FROM `settings` WHERE `id` = '" . $id . "' AND `item` IN (115,117,120,121,122,116,118,119,125,126,127,131,129,130,133,134,5,128,138,121,122,136,141,142,143,148,149,150,151,617)");

        while ($settings_options = mysqli_fetch_assoc($ty_settings_options)) {
            $ty_options_value[$settings_options['item']] = $settings_options['variable'];
        }
        $html .= '<p></p>'
            . '<table cellmargin="0" cellpadding="3">'
                . '<thead>'
                . '<tr style="background-color:#ececec;padding:5px;font-size:12px;"><td>Student Name: '.$student_name.'</td></tr>'
                . '</thead>'
            . '</table>';

        /** Typio Settings */    
        $html .= '<p></p>'		
            . '<table cellmargin="0" cellpadding="3" style="font-size:10px;border-color:gray;" border="1">'
                . '<thead>'
                . '<tr style="background-color:#ececec;padding:5px;font-size:12px;"><td>Typio Setting</td></tr>'
                . '</thead>'
            . '</table>'
            . '<p></p>'		
            . '<table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
            . '<tbody>';
            $html .=  '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td colspan="5">Visual Settings</td></tr>';  
            $html .=  '<tr>
                            <td width="12.5%">Font Size: '.(isset($ty_options_value[115]) ? get_setting_val($ty_options_value[115],'font_size_options') : '').'</td>
                            <td width="12.5%">Font Style: '.(isset($ty_options_value[117]) ? $ty_options_value[117] : '' ) .'</td>
                            <td width="12.5%">Font Color: '.(isset($ty_options_value[116]) ? get_setting_val($ty_options_value[116],'color_options') : '' ) .'</td>
                            <td width="12.5%">BG Color: '.(isset($ty_options_value[118]) ? get_setting_val($ty_options_value[118],'color_options') : '' ) .'</td>
                            <td width="25%">Accessory Color: '.(isset($ty_options_value[119]) ? get_setting_val($ty_options_value[119],'color_options') : '' ) .'</td>
                            <td width="25%">Selection Color: '.(isset($ty_options_value[143]) ? get_setting_val($ty_options_value[143],'selection_option') : '' ) .'</td>
                    </tr>
                    <tr>
                            <td width="12.5%">Subtitles: '.((isset($ty_options_value[141])) ? get_setting_val($ty_options_value[141],'on_off_option') : '').'</td>
                            <td width="12.5%">Visual Keyboard: '.((isset($ty_options_value[128])) ? get_setting_val($ty_options_value[128],'on_off_option') : '' ) .'</td>
                            <td width="12.5%">Highlight: '.((isset($ty_options_value[127])) ? get_setting_val($ty_options_value[127],'on_off_option') : '' ) .'</td>
                            <td width="12.5%">Visual FX: '.((isset($ty_options_value[142])) ? get_setting_val($ty_options_value[142],'on_off_option') : '' ) .'</td>
                            <td width="25%">Visual Hands: '.(isset($ty_options_value[149]) ? get_setting_val($ty_options_value[149],'color_options') : '' ) .'</td>
                            <td width="25%">Hands Style: '.(isset($ty_options_value[151]) ? get_setting_val($ty_options_value[151],'hand_style') : '' ) .'</td>
                    </tr>';            
            $html .=  '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td colspan="6">Audio Settings</td></tr>';  
            $html .=  '<tr>
                            <td width="20%">Voice: '.((isset($ty_options_value[120])) ? $ty_options_value[120] : '').'</td>
                            <td width="20%">Voice Rate: '.(isset($ty_options_value[121]) ? get_setting_val($ty_options_value[121],'voice_rate_options') : '' ) .'</td>
                            <td width="20%">Voice Pitch: '.(isset($ty_options_value[122]) ? get_setting_val($ty_options_value[122],'voice_pitch_options') : '' ) .'</td>
                            <td width="20%">Keypress: '.(isset($ty_options_value[125]) ? get_setting_val($ty_options_value[125],'typio_keypress_options') : '' ) .'</td>
                            <td width="20%">SFX: '.(isset($ty_options_value[126]) ? $ty_options_value[126] : '' ) .'</td>                                    
                    </tr>';            
            $html .=  '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td colspan="5">App Settings</td></tr>';  
            $html .=  '<tr>
                            <td width="12.5%">Accuracy Goal: '.(isset($ty_options_value[131]) ? $ty_options_value[131] : '').'</td>
                            <td width="12.5%">WPM Goal: '.(isset($ty_options_value[129]) ? $ty_options_value[129] : '' ) .'</td>
                            <td width="12.5%">Smart WPM: '.((isset($ty_options_value[138])) ? get_setting_val($ty_options_value[138],'on_off_option') : '' ) .'</td>
                            <td width="12.5%">Goal Lock: '.((isset($ty_options_value[130])) ? get_setting_val($ty_options_value[130],'on_off_option') : '' ) .'</td>
                            <td width="12.5%">Setting Lock: '.((isset($ty_options_value[134])) ? get_setting_val($ty_options_value[134],'on_off_option') : '' ) .'</td>                                    
                            <td width="12.5%">Game Lock: '.((isset($ty_options_value[133])) ? get_setting_val($ty_options_value[133],'on_off_option') : '' ) .'</td>                                    
                            <td width="12.5%">Typing Pet Coins: '.(isset($ty_options_value[136]) ? $ty_options_value[136] : '' ) .'</td>      
                            <td width="12.5%">Spell Mode: '.((isset($ty_options_value[148])) ? get_setting_val($ty_options_value[148],'on_off_option') : '' ) .'</td>                               
                        </tr>
                        <tr>
                                                               
                            <td width="12.5%">Short Lessons: '.((isset($ty_options_value[617])) ? get_setting_val($ty_options_value[617],'on_off_option') : '' ) .'</td>
                        </tr>';         
                      
            $html .= '</tbody>'
            . '</table>';


            /** Typio History */
            $html .= '<p></p><table cellmargin="0" cellpadding="3">'
                . '<thead>'
                . '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td>Typio History</td></tr>'
                . '</thead>'
            . '</table>'
            . '<p></p><table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
            . '<thead>'
            . '<tr style="background-color:#ececec;padding:5px;">
            <td width="40%" style="padding:5px;">Lesson </td>
            <td width="15%" style="padding:5px;">Date</td>
            <td width="15%" style="padding:5px;">WPM</td>
            <td width="15%" style="padding:5px;">ACC</td>
            <td width="15%" style="padding:5px;">ERR</td>
            </tr>'
            . '</thead>'
            . '<tbody>';
            $student_typio_data = typioHistoryData($id);
            $html .=  $student_typio_data;            
                
            $html .= '</tbody>'
            . '</table>';    
            

            /** Typio lessons */
            $html .= '<p></p><table cellmargin="0" cellpadding="3">'
                    . '<thead>'
                    . '<tr style="background-color:#ececec;padding:5px;text-align:center;font-size:12px;"><td>Typio Custom Lessons</td></tr>'
                    . '</thead>'
                . '</table>'
                . '<p></p><table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                . '<thead>'
                . '<tr style="background-color:#ececec;padding:5px;">
                <td width="30%" style="padding:5px;">Lesson name </td><td width="70%" style="padding:5px;">Data </td>
                </tr>'
                . '</thead>'
                . '<tbody>';
                $lesson_data = get_data_from_text_table_data($id, 'Typio-OL');
            
                if (!empty($lesson_data)) {
                    foreach($lesson_data as $lesson_row) {                
                        $html .=  '<tr><td width="30%">'. $lesson_row['title'].'</td><td width="70%">'. (!empty($lesson_row['data']) ? $lesson_row['data'] : '').'</td></tr>';            
                    }
                }
                $html .= '</tbody>'
                . '</table>';

                if($is_tchp){
                    /** Quick Cards Settings */

                    $qc_options_value = array();
                    $qc_settings_options = mysqli_query($con, "SELECT * FROM `settings` WHERE `id` = '" . $id . "' AND `item` IN (6,126,215,216,217,218,219,220,221,222,226,227,228,234,241,242,243)");

                    while ($settings_options = mysqli_fetch_assoc($qc_settings_options)) {

                        $qc_options_value[$settings_options['item']] = $settings_options['variable'];
                    }
                    $html .= '<p></p><table cellmargin="0" cellpadding="3" style="font-size:10px;border-color:gray;" border="1">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;padding:5px;font-size:12px;"><td>Quick Cards Setting</td></tr>'
                        . '</thead>'
                    . '</table>'
                    . '<p></p>'		
                    . '<table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                    . '<tbody>';
                    $html .=  '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td colspan="5">Visual Settings</td></tr>';  
                    $html .=  '<tr>
                                    <td width="12.5%">Font Size: '.(isset($qc_options_value[215]) ? get_setting_val($qc_options_value[215],'font_size_options') : '').'</td>
                                    <td width="12.5%">Font Style: '.(isset($qc_options_value[217]) ? $qc_options_value[217] : '' ) .'</td>
                                    <td width="12.5%">Font Color: '.(isset($qc_options_value[216]) ? get_setting_val($qc_options_value[216],'color_options') : '' ) .'</td>
                                    <td width="12.5%">BG Color: '.(isset($qc_options_value[218]) ? get_setting_val($qc_options_value[218],'color_options') : '' ) .'</td>
                                    <td width="12.5%">Accessory Color: '.(isset($qc_options_value[219]) ? get_setting_val($qc_options_value[219],'color_options') : '' ) .'</td>
                                    <td width="12.5%">Selection Style: '.(isset($qc_options_value[243]) ? get_setting_val($qc_options_value[243],'selection_option') : '' ) .'</td>
                                    <td width="12.5%">Subtitles: '.((isset($qc_options_value[241])) ? get_setting_val($qc_options_value[241],'on_off_option') : '').'</td>
                                    <td width="12.5%">Visual FX: '.((isset($qc_options_value[242])) ? get_setting_val($qc_options_value[242],'on_off_option') : '' ) .'</td>
                            </tr>';            
                    $html .=  '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td colspan="8">Audio Settings</td></tr>';  
                    $html .=  '<tr>
                                    <td width="25%">Voice: '.(isset($qc_options_value[220]) ? $qc_options_value[220] : '').'</td>
                                    <td width="25%">Voice Rate: '.(isset($qc_options_value[221]) ? get_setting_val($qc_options_value[221],'voice_rate_options') : '' ) .'</td>
                                    <td width="25%">Voice Pitch: '.(isset($qc_options_value[222]) ? get_setting_val($qc_options_value[222],'voice_pitch_options') : '' ) .'</td>
                                    <td width="25%">SFX: '.(isset($qc_options_value[126]) ? $qc_options_value[126] : '' ) .'</td>                                    
                            </tr>';            
                    $html .=  '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td colspan="4">App Settings</td></tr>';  
                    $html .=  '<tr>
                                    <td width="50%">Setting Lock: '.((isset($qc_options_value[234])) ? get_setting_val($qc_options_value[234],'on_off_option') : '' ) .'</td>                                    
                                    <td width="50%">Deck / Test Editor Lock: '.((isset($qc_options_value[228])) ? get_setting_val($qc_options_value[228],'on_off_option') : '' ) .'</td>
                                </tr>';         
                            
                    $html .= '</tbody>'
                    . '</table>';

                    /** Quick Cards History */
                    $html .= '<p></p><table cellmargin="0" cellpadding="3">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td>Quick Cards History</td></tr>'
                        . '</thead>'
                    . '</table>'
                    . '<p></p><table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                    . '<thead>'
                    . '<tr style="background-color:#ececec;padding:5px;">
                        <td width="15%" style="padding:5px;">Name </td>
                        <td width="15%" style="padding:5px;">Percent</td>
                        <td width="15%" style="padding:5px;">Incorrect</td>
                        <td width="40%" style="padding:5px;">Missed Cards</td>
                        <td width="15%" style="padding:5px;">Date</td>
                    </tr>'
                    . '</thead>'
                    . '<tbody>';
                    $card_history_data = quickcard_history_data($id);
                    //echo "<pre>";print_r($card_history_data);die;
                    if (!empty($card_history_data)) {
                        foreach($card_history_data as $card_history_row) {  

                            if($card_history_row['lognr'] == '915856'){
                                continue;
                            }              
                            $file = isset($card_history_row['file']) ? $card_history_row['file']: '';
                            $qui_date = isset($card_history_row['date'])?date('m/d/y', strtotime($card_history_row['date'])):'';
                            $score = isset($card_history_row['percent_score'])?$card_history_row['percent_score']:'';
                            $incorrect = isset($card_history_row['incorrect'])?$card_history_row['incorrect']:'';
                            $missed = isset($card_history_row['cards_missed'])? implode(', ', $card_history_row['cards_missed']):'';
                            $html .=  '<tr>
                                <td width="15%">'. $file.'</td>
                                    <td width="15%">'. $score.'</td>
                                    <td width="15%">'. $incorrect.'</td>
                                    <td width="40%">'.$missed.'</td>
                                    <td width="15%">' . $qui_date . '</td>
                                </tr>';            
                        }
                    }
                    $html .= '</tbody>'
                    . '</table>';  
                        
                        
                    /** Quick Cards Decks */
                    $html .= '<p></p><table cellmargin="0" cellpadding="3">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;padding:5px;text-align:center;font-size:12px;"><td>Quick Cards Decks</td></tr>'
                        . '</thead>'
                    . '</table>'
                    . '<p></p><table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                    . '<thead>'
                    . '<tr style="background-color:#ececec;padding:5px;">
                        <td width="30%" style="padding:5px;">Deck Name </td>
                        <td width="70%" style="padding:5px;">Cards</td>
                    </tr>'
                    . '</thead>'
                    . '<tbody>';
                    $card_data = get_data_from_text_table_data($id, 'QC-OL',0);
                    //echo "<pre>";print_r($card_data);die;    
                    if (!empty($card_data)) {
                        foreach($card_data as $card_row) {      
                            if($card_row['table_id'] == '42706'){
                                continue;
                            }
                            $cart_deck_title = isset($card_row['title']) ? $card_row['title'] : '';                
                            $cart_deck_text_data = !empty($card_row['data']) ? $card_row['data'] : 0;         
                            $html .=  '<tr><td width="30%">'. $cart_deck_title.'</td><td width="70%">'. $cart_deck_text_data .'</td></tr>';            
                        }
                    }
                    $html .= '</tbody>'
                    . '</table>';

                    /** Quick Cards Tests */
                    $html .= '<p></p><table cellmargin="0" cellpadding="3">'
                            . '<thead>'
                            . '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td>Quick Cards Tests</td></tr>'
                            . '</thead>'
                        . '</table>'
                        . '<p></p><table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;padding:5px;">
                            <td width="30%" style="padding:5px;">Test Name </td>
                            <td width="70%" style="padding:5px;">Q"s</td>
                        </tr>'
                        . '</thead>'
                        . '<tbody>';
                        $card_test_data = get_data_from_text_table_data($id, 'QC-OL',1);

                        if (!empty($card_test_data)) {
                            foreach($card_test_data as $card_test_row) {    
                                $cart_title = isset($card_test_row['title']) ? $card_test_row['title'] : '';                
                                $cart_text_data = !empty($card_test_row['data']) ? $card_test_row['data'] : 0;
                                $html .=  '<tr><td width="30%">'. $cart_title.'</td><td width="70%">'.$cart_text_data .'</td></tr>';            
                            }
                        }
                        $html .= '</tbody>'
                        . '</table>';


                    /** Arcade Settings */
                    $arcade_settings_query = query("SELECT * FROM `settings` WHERE id =" . $id . " AND item IN (315,316,317,318,319,320,321,322,325,326,327,328,330,334,6)");
                    $arcade_arr = array();
                    while ($arcade_settings_data_row = mysqli_fetch_assoc($arcade_settings_query)) {
                        $arcade_arr[$arcade_settings_data_row['item']] = $arcade_settings_data_row['variable'];
                    }
                    $html .= '<p></p><table cellmargin="0" cellpadding="3" style="font-size:10px;border-color:gray;" border="1">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;padding:5px;font-size:12px;"><td>Arcade Setting</td></tr>'
                        . '</thead>'
                    . '</table>'
                    . '<p></p>'		
                    . '<table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                    . '<tbody>';
                    $html .=  '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td colspan="5">Visual Settings</td></tr>';  
                    $html .=  '<tr>
                                    <td width="12.5%">Font Size: '.(isset($arcade_arr[315]) ? get_setting_val($arcade_arr[315],'font_size_options') : '').'</td>
                                    <td width="12.5%">Font Style: '.(isset($arcade_arr[317]) ? $arcade_arr[317] : '' ) .'</td>
                                    <td width="12.5%">Font Color: '.(isset($arcade_arr[316]) ? get_setting_val($arcade_arr[316],'arcade_color_options') : '' ) .'</td>
                                    <td width="12.5%">BG Color: '.(isset($arcade_arr[318]) ? get_setting_val($arcade_arr[318],'arcade_color_options') : '' ) .'</td>
                                    <td width="12.5%">Accessory Color: '.(isset($arcade_arr[319]) ? get_setting_val($arcade_arr[319],'arcade_color_options') : '' ) .'</td>
                                    <td width="12.5%">Wizards Tower SFX: '.(isset($arcade_arr[328]) ? get_setting_val($arcade_arr[328],'arcade_wizard_tower_sfx_options') : '' ) .'</td>
                                    <td width="12.5%">Samurai Flash: '.((isset($arcade_arr[326])) ? get_setting_val($arcade_arr[326],'on_off_option') : '').'</td>
                                    <td width="12.5%">Echo Arrows: '.(isset($arcade_arr[327]) ? $arcade_arr[327] : '').'</td>                        
                            </tr>
                            <tr>
                                    <td width="12.5%">Visual FX: '.((isset($arcade_arr[330])) ? get_setting_val($arcade_arr[330],'on_off_option') : '' ) .'</td>
                            </tr>';            
                    $html .=  '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td colspan="8">Audio Settings</td></tr>';  
                    $html .=  '<tr>
                                    <td width="25%">Voice: '.(isset($arcade_arr[320]) ? $arcade_arr[320] : '').'</td>
                                    <td width="25%">Voice Rate: '.(isset($arcade_arr[321]) ? get_setting_val($arcade_arr[321],'voice_rate_options') : '' ) .'</td>
                                    <td width="25%">Voice Pitch: '.(isset($arcade_arr[322]) ? get_setting_val($arcade_arr[322],'voice_pitch_options') : '' ) .'</td>
                                    <td width="25%">Music Volume: '.(isset($arcade_arr[325]) ? get_setting_val($arcade_arr[325],'arcade_music_volume_options') : '' ) .'</td>                                    
                            </tr>';            
                    $html .=  '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td colspan="4">App Settings</td></tr>';  
                    $html .=  '<tr>
                                    <td width="100%">Setting Lock: '.((isset($arcade_arr[334])) ? get_setting_val($arcade_arr[334],'on_off_option') : '' ) .'</td>           
                                </tr>';         
                            
                    $html .= '</tbody>'
                    . '</table>';


                    /** Arcade History */
                    $html .= '<p></p><table cellmargin="0" cellpadding="3">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td>Arcade History</td></tr>'
                        . '</thead>'
                        . '</table>'
                        . '<p></p><table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;padding:5px;">
                            <td width="50%" style="padding:5px;">Name </td>
                            <td width="50%" style="padding:5px;">Date</td>
                        </tr>'
                        . '</thead>'
                        . '<tbody>';
                        $query = "SELECT file,date FROM log WHERE app = 'Arcade-OL' AND id='" . $id . "'";
                        $arcade_history_data = get_query_data($query);

                        if (!empty($arcade_history_data)) {
                            foreach($arcade_history_data as $arcade_history_row) {                
                                $html .=  '<tr>
                                    <td width="50%">'. $arcade_history_row['file'].'</td>
                                    <td width="50%">' . date('m/d/y', strtotime($arcade_history_row['date'])) . '</td>
                                    </tr>';            
                            }
                        }
                    $html .= '</tbody>'
                        . '</table>'; 


                    /** Hangryman */
                    $html .= '<p></p><table cellmargin="0" cellpadding="3">'
                            . '<thead>'
                            . '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td>Hangryman</td></tr>'
                            . '</thead>'
                        . '</table>'
                        . '<p></p><table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;padding:5px;">
                            <td width="30%" style="padding:5px;">Task </td>
                            <td width="70%" style="padding:5px;">Data </td>
                        </tr>'
                        . '</thead>'
                        . '<tbody>';
                            $query = "SELECT * FROM text WHERE text.app='Arcade-OL' AND text.number=2 AND id='" . $id . "'";
                            $hangman_data = get_query_data($query);

                            if (!empty($hangman_data)) {
                                foreach($hangman_data as $hangman_row) {                
                                    $html .=  '<tr><td width="30%">'. $hangman_row['title'].'</td><td width="70%">'. (!empty($hangman_row['data']) ? $hangman_row['data'] : '').'</td></tr>';            
                                }
                            }
                        $html .= '</tbody>'
                            . '</table>'; 

                    /** Pro Pack Settings */
                    $pro_pack_options_value = array();
                    $pro_settings_options = mysqli_query($con, "SELECT * FROM `settings` WHERE `id` = '" . $id . "' AND `item` IN (6,415,416,417,418,419,420,421,422,441,442,443)");

                    while ($pro_row = mysqli_fetch_assoc($pro_settings_options)) {
                        $pro_pack_options_value[$pro_row['item']] = $pro_row['variable'];
                    }
                    $html .= '<p></p><table cellmargin="0" cellpadding="3" style="font-size:10px;border-color:gray;" border="1">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;padding:5px;font-size:12px;"><td>Pro Pack Setting</td></tr>'
                        . '</thead>'
                    . '</table>'
                    . '<p></p>'		
                    . '<table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                    . '<tbody>';
                    $html .=  '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td colspan="5">Visual Settings</td></tr>';  
                    $html .=  '<tr>
                                    <td width="14%">Font Size: '.(isset($pro_pack_options_value[415]) ? $pro_pack_options_value[415] : '').'</td>
                                    <td width="13.5%">Font Style: '.(isset($pro_pack_options_value[417]) ? $pro_pack_options_value[417] : '' ) .'</td>
                                    <td width="14.5%">Font Color: '.(isset($pro_pack_options_value[416]) ? get_setting_val($pro_pack_options_value[416],'color_options') : '' ) .'</td>
                                    <td width="14.5%">BG Color: '.(isset($pro_pack_options_value[418]) ? get_setting_val($pro_pack_options_value[418],'color_options') : '' ) .'</td>
                                    <td width="14.5%">Accessory Color: '.(isset($pro_pack_options_value[419]) ? get_setting_val($pro_pack_options_value[419],'color_options') : '' ) .'</td>
                                    <td width="14.5%">Subtitles: '.((isset($pro_pack_options_value[441])) ? get_setting_val($pro_pack_options_value[441],'on_off_option') : '').'</td>
                                    <td width="14.5%">Visual FX: '.((isset($pro_pack_options_value[442])) ? get_setting_val($pro_pack_options_value[442],'on_off_option') : '' ) .'</td>
                            </tr>';            
                    $html .=  '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td colspan="8">Audio Settings</td></tr>';  
                    $html .=  '<tr>
                                    <td width="20%">Voice: '.(isset($pro_pack_options_value[420]) ? $pro_pack_options_value[420] : '').'</td>
                                    <td width="20%">Voice Rate: '.(isset($pro_pack_options_value[421]) ? get_setting_val($pro_pack_options_value[421],'voice_rate_options') : '' ) .'</td>
                                    <td width="20%">Voice Pitch: '.(isset($pro_pack_options_value[422]) ? get_setting_val($pro_pack_options_value[422],'voice_pitch_options') : '' ) .'</td>                                                              
                            </tr>';            
                            
                    $html .= '</tbody>'
                    . '</table>';
                
                    /** Reader Files */
                    $html .= '<p></p><table cellmargin="0" cellpadding="3">'
                        . '<thead>'
                            . '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td>Reader Files</td></tr>'
                            . '</thead>'
                        . '</table>'
                        . '<p></p><table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;padding:5px;">
                            <td width="30%" style="padding:5px;">Title </td>
                            <td width="70%" style="padding:5px;">Data </td>
                        </tr>'
                        . '</thead>'
                        . '<tbody>';
                        $propack_reader_data = get_ProPack_student_data($id, '0');

                        if (!empty($propack_reader_data)) {
                            foreach($propack_reader_data as $reader_row) {                
                                $html .=  '<tr><td width="30%">'. $reader_row['title'].'</td><td width="70%">'. (!empty($reader_row['data']) ? $reader_row['data'] : '').'</td></tr>';            
                            }
                        }
                    $html .= '</tbody>'
                        . '</table>';
                    
                    /** Notepad Files */
                    $html .= '<p></p><table cellmargin="0" cellpadding="3">'
                            . '<thead>'
                            . '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td>Notepad Files</td></tr>'
                            . '</thead>'
                        . '</table>'
                        . '<p></p><table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;padding:5px;">
                            <td width="30%" style="padding:5px;">Title </td>
                            <td width="70%" style="padding:5px;">Data </td>
                        </tr>'
                        . '</thead>'
                        . '<tbody>';
                        $propack_notpad_data = get_ProPack_student_data($id, '1');
                        if (!empty($propack_notpad_data)) {
                            foreach($propack_notpad_data as $notpad_row) {         
                                $html .=  '<tr><td width="30%">'. $notpad_row['title'].'</td><td width="70%">'. (!empty($notpad_row['data']) ? htmlspecialchars($notpad_row['data']) : '').'</td></tr>';            
                            }
                        }
                    $html .= '</tbody>'
                        . '</table>';

                    /** To-Do Tasks */
                    $html .= '<p></p><table cellmargin="0" cellpadding="3">'
                            . '<thead>'
                            . '<tr style="background-color:#ececec;text-align:center;padding:5px;font-size:12px;"><td>To-Do Tasks</td></tr>'
                            . '</thead>'
                        . '</table>'
                        . '<p></p><table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;padding:5px;">
                            <td width="30%" style="padding:5px;">Tasks </td>
                            <td width="70%" style="padding:5px;">Data </td>
                        </tr>'
                        . '</thead>'
                        . '<tbody>';
                        $propack_to_do_data = get_ProPack_student_data($id, '2');
                        if (!empty($propack_to_do_data)) {
                            foreach($propack_to_do_data as $to_do_row) {                       
                                $html .=  '<tr><td width="30%">'. $to_do_row['title'].'</td><td width="70%">'. (!empty($to_do_row['data']) ? htmlspecialchars($to_do_row['data']) : '').'</td></tr>';  
                            }          
                        }
                    $html .= '</tbody>'
                        . '</table>';  
                }    
                /** Overview History */
                $html .= '<p></p><table cellmargin="0" cellpadding="3">'
                        . '<thead>'
                        . '<tr style="background-color:#ececec;padding:5px;text-align:center;font-size:12px;"><td>Overview History</td></tr>'
                        . '</thead>'
                    . '</table>'
                    . '<p></p><table cellmargin="0" cellpadding="3" style="font-size:10px;border-color:gray;" border="1">'
                    . '<thead>'
                    . '<tr style="background-color:#ececec;padding:5px;">
                    <td width="20%" style="padding:5px;">Activity </td>
                    <td width="15%" style="padding:5px;">Date</td>
                    <td width="65%" style="padding:5px;">Details</td>
                    </tr>'
                    . '</thead>'
                    . '<tbody>';
                    
                    $over_history_query = "SELECT file,date,data FROM log WHERE app ='Overview-OL' and log.id ='" . $id . "'  GROUP BY data,file ORDER BY date DESC";
                    $student_data = get_query_data($over_history_query);
                
                    if(!empty($student_data)) {
                        foreach($student_data as $row){
                            $html .=  '<tr><td width="20%">'. $row['file'].'</td><td width="15%">'.date('m/d/Y', strtotime($row['date'])) .'</td><td width="65%">'.$row['data'] .'</td></tr>';            
                        }
                    }
                $html .= '</tbody>'
                    . '</table>';
        
    }
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output($fullpath, 'F');  
    echo $main_path;
    exit;


?>