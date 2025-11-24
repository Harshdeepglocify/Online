<?php
include "../config/config.php";
include "../helper/student_helper.php";

if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}
/* For expired license not access this page directly */
$is_expired = check_expire_or_not();
if($is_expired && $_SESSION['User']['is_admin'] ==1){
    header("Location: " . ADMIN_URL);
    exit;
}
$downloadlist  =  "SELECT * FROM `report_downloads` where user_id ='".$_SESSION['User']['id']."'";

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
        <div class="wrapper studentreports_cst">

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
				     ?>
                
			  <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <?php if($is_expired){?>
                            <div class="col-lg-12 expire-error-msg" style="margin-bottom:10px;">
                                <p><?php echo checkLicenseDayDiff();?> Days until all account data is permanently deleted. Your license is expired!</p>
                            </div>
                        <?php }?>
                        <?php
                        if ($_SESSION['User']['id'] == 63 || $_SESSION['User']['id'] == 77) {          // user-id = 63 is Joe Teacher, user-id = 77 is Demo Teacher
                            ?>
                            <div class="col-lg-12" style="margin-bottom:10px;">
                                <input type="button" class="dashboard-settings-btn" id="sync_lic" value="Sync Licenses" />
                                <span><i class="fa fa-spinner fa-spin" id="icon_loader" style="display:none;" aria-hidden="true"></i></span>
                                <span style="margin-left:10px;" id="sync_lic_msg"></span>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="col-lg-12">
						<span id="spinner_loader" style="display:none;" class="spinner_loader_table"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                            <div class="box-body panel">
                                <div class="alert-msg-response" aria-live="assertive">
                                    <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert alert-dismissible fade in">
                                    </div>
                                </div>
                                <header class="panel-heading">    
									<!--<div class="table-header-actions" style="margin-bottom:10px;">
										<select id="report_filter_dropdown" class="form-control" style="width:200px;">
											<option value="">Reports Action</option>
											<option value="delete">Delete</option>
											
										</select>
									</div>-->
								
                                </header>
                                <div class="table-responsive">
								
                                    <table id="student_download_list_table" class="student_custom_list_table table table-bordered wt-student-list-table-cs wt-student-list-tableheader-cs" border="2">
                                        <thead>
                                            <tr>
												
                                                <td><h4 class="Students-title-cs"><input type="checkbox" id="delete_all_students">&nbsp;&nbsp;<b>Students</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Report Type</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Date Generated</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Expires</b></h4></td>
                                                <td><h4 class="Students-title-cs"><b>Actions</b></h4></td>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php 
										$result_list = mysqli_query($con, $downloadlist);
										if ($result_list && mysqli_num_rows($result_list) > 0) {
												while ($row = mysqli_fetch_assoc($resultlicense)) {
													$license = $row['license'];
													//$existing_email[]   = $row['user_email'];
												}
										}
										
										?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
			
			
			
			       </div>
				     <div class="box-body panel student-title-main-init student-add-custom"> 
						<div class='student-add-column-two-report'>
                           <button type="button" class="dashboard-settings-btn btn-block delete-href" id="report_delete_btn">
								<i class="fa fa-trash"></i> Delete Selected Reports
                           </button>
                                      
					 </div>
                </section>
		<!-- /.modal -->
		   <!-- Student lists  -->
	 <div class="modal fade" id="student-list-file">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Student Lists</h4>
                    </div>
                    
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Display Name</th>
                                            <th>Username</th>
                                           
                                        </tr>
                                    </thead>
                                   <tbody>
								   </tbody>
                                </table>
                          
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
		
        <!-- Delete File Manually  -->
     
	<div class="modal fade" id="confirm-delete-file">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Delete File</h4>
                    </div>
                    <form id="delete-report-form" name="delete-report-form" method="POST">
                        <div class="modal-body">
                           <input type="hidden" value="" id="delete-report-id" name="delete-report-id">
                           
                            <div class="form-group">
                                <p>To confirm, type DELETE in the field below, then click the Delete Report button.</p>
                                <div class="delete-report-button-wrap">
                                    <input class="form-control delete_report_button_text" type="text" name="delete_text_report" id="delete_text_report" autofocus>
                                    <button type="button" name="delete_report_button" class="btn btn-primary delete_reportbutton_btn">Permanently Delete Report</button>
                                </div>

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
            </div>
<?php include "../config/footer.php"; ?>

            <!-- Control Sidebar -->
<?php include "../config/setting.php"; ?>

                 

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
        <script src="<?php echo ADMIN_URL ?>dist/js/custom-student.js?ver=<?php echo time(); ?>"></script>

        <!-- SlimScroll 1.3.0 -->
        <script src="<?php echo ADMIN_URL ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/datatables/datatable-new/jquery.dataTables.min.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/datatables/datatable-new/dataTables.bootstrap4.js"></script>
		<script>
		function hideOldDates() {
        const currentDate = new Date();
        const allTdSpans = document.querySelectorAll('td.target-column-2 span');

        allTdSpans.forEach(function(span) {
            const dateText = span.innerText.trim();
            const dateValue = new Date(dateText);
            
            // Check if the span contains a valid date
            if (!isNaN(dateValue.getTime())) {
            const yearDifference = currentDate.getFullYear() - dateValue.getFullYear();
            
            // Check if the date is more than 4 years old
            if (yearDifference > 4 || (yearDifference === 4 && currentDate.getMonth() > dateValue.getMonth())) {
                span.parentElement.style.opacity = 0; // Hide the entire <td> element
            }
            }
        });
        }
		 $(document).ready(function() {           

            var table = $('#student_download_list_table').DataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "lengthMenu": [10, 25, 50],
                "pageLength": 999,
                "ajax": {
                    "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                    "type": "POST",
                    data: {'action': 'ajax_student_download_list','is_ajax' : '1'},
                },
                "order": [[0, 'DESC']],
                "columnDefs": [
                    {"targets": 0, "name": "students", 'searchable': false, 'orderable': false},
                    {"targets": 1, "name": "report_type", 'searchable': true, 'orderable': true},
                    {"targets": 2, "name": "file_status", 'searchable': false, 'orderable': true},
                    {"targets": 3, "name": "expires", 'searchable': false, 'orderable': false},
                    {"targets": 4, "name": "action", 'searchable': false, 'orderable': false},
                   
                ],
				 "language": {
					info: "Showing _START_ to _END_ out of _TOTAL_ entries , Reports may take up to 15 minutes to process",
					infoEmpty: "No records available",
					infoFiltered: "(filtered from _MAX_ total records)"
				},
				
                "initComplete": function(settings, json) {
				
					/*let $dropdown = $("#report_filter_dropdown");

					// Try left side (length menu)
					let $left = $("#student_download_list_table_wrapper .dataTables_length");

					// Try right side (search box)
					let $right = $("#student_download_list_table_wrapper .dataTables_filter");

					if ($left.length) {
						$left.append($dropdown);
						console.log("Dropdown added to .dataTables_length");
					} 
					else if ($right.length) {
						$right.prepend($dropdown);
						console.log("Dropdown added to .dataTables_filter");
					} 
					else {
						// fallback — guaranteed visible
						$("#student_download_list_table").before($dropdown);
						console.log("Dropdown added before table");
					}

					$dropdown.show();*/

        
                    hideOldDates();
					
                }, 
                "drawCallback": function(settings) {

                    $('#student_list_table_paginate').attr('aria-label', 'Pagination Navigation');
                    $('#student_list_table_paginate').attr('aria-role', 'Navigation');
                    //Add aria-label to pagination buttons
                    $('#student_list_table_paginate .paginate_button').each(function() {
                        var buttonText = $(this).text().trim();
                        //Add aria-label with button text
                        $(this).attr('aria-label', 'Go to page ' + buttonText);
                    });
                    //Add aria-label to previous and next buttons
                    $('#student_list_table_paginate .previous').attr('aria-label', 'Go to previous page');
                    $('#student_list_table_paginate .next').attr('aria-label', 'Go to next page');
                }
            });
            table.on('draw', function() {
                hideOldDates();
            });
           /* */
		   
		   
		  
		   
        });
		
		$(document).on('click', ' a.accessibyte-link.confirm-delete-file', function () {
			
			 //e.preventDefault();
			let fileId = $(this).data('id'); 
			/*$('#delete-report-id').val(fileId);
			$('#confirm-delete-file').show();
			$('#confirm-delete-file').removeClass('fade');*/
				//$('.spinner_loader').show();          
			  $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'report_delete_id': fileId, 'action': 'delete_report_user'},
                async: true,
                cache: false,
                timeout: 10000,
				beforeSend: function () {
                    $('#spinner_loader').show();                
                },
                success: function (response) {
					$('#spinner_loader').hide();             
                    var response = $.parseJSON(response);
                    $('#confirm-delete').modal('hide');
                    if (response.status == true) {
						 $('#student_download_list_table').DataTable().draw(false);
						//$('#confirm-delete-file').addClass('fade');
						//$('#confirm-delete-file').hide();
						//$('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    } else {
						/*$('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();*/
                    }
                    
                   /* setTimeout(function () {
                        $('.alert-msg-response').delay(5000).fadeOut(1000);
                       // window.location.reload();
                    }, 2000);*/


                }
            });	
			
		});
		
		$(document).on('click', '#confirm-delete-file .close', function () {
			
			$('#confirm-delete-file').addClass('fade');
			$('#confirm-delete-file').hide();
		});
		
		
		jQuery(document).ready(function($) {
			// Prevent table sorting when clicking the "select all" checkbox
			$('#delete_all_students').on('click', function(e) {
				e.stopPropagation(); // Stops sorting
				$('.report_all_delete_cls').prop('checked', this.checked);
			});

			$('.report_all_delete_cls').on('click', function(e) {
				e.stopPropagation(); // Optional: stops row sorting if checkboxes are inside sortable columns
				$('#delete_all_students').prop('checked',
				$('.report_all_delete_cls:checked').length === $('.chkbox').length
				);
			});
		});
		
		</script>