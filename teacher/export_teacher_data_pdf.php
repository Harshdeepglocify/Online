<?php
	include "../config/config.php";
	require_once( '../helper/tcpdf/tcpdf.php');
	

	$teacher_name = ucfirst(base64_decode($_SESSION['User']['firstname']))." ".ucfirst(base64_decode($_SESSION['User']['lastname']));


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
    $fullpath = "Accessibyte Teachers List.pdf";

    
    $html = '<p></p>'
        . '<table cellmargin="0" cellpadding="3">'
            . '<thead>'
            . '<tr style="background-color:#ececec;padding:5px;font-size:12px;"><td>Admin Name: '.$teacher_name.'</td><td>License: '.$_SESSION['User']['license'].'</td></tr>'
            . '</thead>'
        . '</table>'
        . '<p></p>'				  
        
        . '<p>&nbsp;</p>'
        . '<table cellmargin="0" cellpadding="5" style="font-size:10px;border-color:gray;" border="1">'
        . '<thead>'
        . '<tr style="background-color:#ececec;padding:5px;">
        <td width="14%" style="padding:5px;">First Name </td>
        <td width="14%" style="padding:5px;">Last Name </td>
        <td width="14%" style="padding:5px;">Username</td>
        <td width="14%" style="padding:5px;">Email</td>
        <td width="14%" style="padding:5px;">Organization</td>
        <td width="10%" style="padding:5px;">Students</td>
        <td width="18%" style="padding:5px;">Student Seat Limit</td>
        </tr>'
        . '</thead>'
        . '<tbody>';
        $query = "SELECT * FROM user WHERE `role` ='teacher' AND `license` ='".$_SESSION['User']['license']."' ORDER BY firstname ASC";
            

        $query_result = mysqli_query($con, $query);

        if (mysqli_num_rows($query_result) > 0 ) {

        while ($student_row = mysqli_fetch_assoc($query_result)) {
            
            $firstname =  ucfirst(base64_decode($student_row['firstname']));
            $lastname =  ucfirst(base64_decode($student_row['lastname']));
            $nickname = $firstname  .' '.$lastname;
            $teacher_name = base64_decode($student_row['teacher_name']);
            $organization = base64_decode($student_row['organization']);
            $username =  base64_decode($student_row['username']);

            $email = isset( $student_row['email'] )? base64_decode( $student_row['email'] ): "";
            $license =$student_row['license'];
            $activity = !empty($student_row['login']) ? getTimtstampDiff($student_row['login']):"";

            $student_count = '0';
            $query = query("SELECT count(*) student_count  FROM user where teacher='".$student_row['teacher']."' AND role ='student'");
            if (!empty($query->num_rows)) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $student_count=$row['student_count'];
                }
            }
            $seat_limit = $student_row['seat_limit'];
            if(empty($student_row['seat_limit'])){
                $seat_limit = 'No Limit';
            }
            $html .=  '<tr style="padding:5px;">
                        <td width="14%">'.$firstname.'</td>
                        <td width="14%">'.$lastname.'</td>
                        <td width="14%">'.$username .'</td>
                        <td width="14%">'.$email.'</td>
                        <td width="14%">'.$organization .'</td>
                        <td width="10%">'.$student_count.'</td>
                        <td width="18%">'.$seat_limit .'</td>
            </tr>';
            
            }
        }
        $html .= '</tbody>'
        . '</table>';
        
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output($fullpath, 'D');  


?>