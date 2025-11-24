<?php 
include "../config/config-student.php"; 
if(!$_SESSION['User']){
    header("Location: " . ADMIN_URL . 'login');
    exit;
}
/* For expired license not access this page directly */
$is_expired = check_expire_or_not();
if($is_expired){
    header("Location: " . ADMIN_URL);
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
            .alert a.close {
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
                <section class="content pro-pack-section">
                    <div class="row">
                        <!-- Title -->
                        <div class="col-md-12">
                            <div class="student-title-main-init">              
                                <h3>Pro Pack</h3>
                            </div>
                        </div>
                        <!-- chart one -->  
                        <div class="col-md-12">
                            <div class="chart-one-main-init">
                                
                                <!-- ---------- Typio Sectoin two ---------- -->                     
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="pro_pack_msg">
                                            <div class="with-border">
                                                <h2 class="dashboard-h2">Reader Docs</h2>
                                            </div>
                                            <div class="quick-ajax-response " aria-live="assertive">
                                                <div class="alert alert-dismissible" id="alert-success">
                                                </div>
                                            </div> 
                                            <!-- /.box-header -->
                                            <div class="box-body " >
                                                <div class="table-responsive">
                                                <table class="table table-bordered" id="reader_doc_listing">
                                                <thead>
                                                        <tr>
                                                            <th style="width: 10px !important">&nbsp;</th>
                                                            <th>Title</th>
                                                            <th style="width: 40px !important">Edit</th>
                                                            <th style="width: 40px !important">Delete</th>
                                                        </tr>
                                                     </thead>
                                                     <tbody>   
                                                         <?php
                                                         /*
                                                        $reader_doc_data = get_ProPack_data($_SESSION['User']['id'], '0');
                                                        if (!empty($reader_doc_data)) {

                                                            foreach ($reader_doc_data as $key => $_value) {

                                                                echo '<tr class="pro_pack_tr_' . $_value['table_id'] . '">';
                                                                echo '<td><input type="checkbox" name="share_pro_pack_ids[]" value="' . $_value['table_id'] . '" class="share_pro_pack_ids"></td>';
                                                                echo '<td>' . $_value['title'] . '</td>';
                                                                echo '<td><a href="javascript:void(0)" class="badge bg-green edit-pro-pack-modal" data-id="' . $_value['table_id'] . '"><i class="fa  fa-edit (alias)"></i></a></td>';
                                                                echo '<td><a href="javascript:void(0)" data-toggle="modal" data-type="Reader Doc" data-target="#delete-modal-set" class="badge bg-red delete-pro-pack-modal" data-id="' . $_value['table_id'] . '"><i class="fa fa-trash-o" aria-label="Delete"></i></a></td>';
                                                                echo '</tr>';
                                                            }
                                                        }*/
                                                        ?>
                                                    </tbody>
                                                </table>
                                                </div>
												<button type="button" style="margin-top:15px;" class="dashboard-settings-btn btn-block" data-toggle="modal" data-target="#add-propack-modal" >Create New</button>
                                            </div>
                                        </div>
                                    </div>                                   
                                    <div class="col-md-6">
                                        <div class="">
                                            <div class="with-border">
                                                <h2 class="dashboard-h2">Share With</h2>
                                            </div>
                                            <div class="box-body">
                                                <!-- form -->
                                                <form role="form" action="" method="POST" id="share_pro_pack_form">
                                                    <div class="form-group">
                                                        <!-- <label>Students</label> -->
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered" id="share-table">
                                                                <thead>
                                                                    <tr><th>Students</th></tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                //Argument for data
                                                                if(!empty($_SESSION['User']['is_admin'])){
                                                                    $args = array(
                                                                        'license' => !empty($_SESSION['User']) ? $_SESSION['User']['license'] : '',
                                                                        'role' => 'student',
                                                                    );
                                                                } else {
                                                                    $args = array(
                                                                        'teacher_code' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
                                                                        'role' => 'student',
                                                                    );
                                                                }
                                                                $student_data = get_users($args);

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
                                                    </div>
                                                    <div class="form-group action-btn-chart-init">
                                                        <button type="button" class="dashboard-settings-btn btn-block pro-pack-share-submit">Share</button>
                                                    </div>
                                                </form> 
                                                <!-- End form -->                             
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- ---------- End Typio Sectoin ---------- -->
                                <div class="row">
                                    <div class="space-margin-bottom-50"></div>
                                </div>                
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!--End Typio Sectoin four  --> 
			<!-- add propack-modal -->
			<div class="modal fade" id="add-propack-modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title color">Create New Reader Docs </h4>
						</div>
						<div class="modal-body">
							<form method="POST" id="reader-docs-add-form">
								<div class="form-group">
									<input type="hidden" name="user_type" value="1" id="user_type">
									<label>Reader docs Title...</label>
									<input type="text" id="reader-docs-title" name="title" class="form-control title" placeholder="Reader docs title here..." required=""/>
									<input type="hidden" id="user_id" name="user_id" value="<?php echo!empty($_SESSION['User']['id']) ? $_SESSION['User']['id'] : ''; ?>"/>
									<input type="hidden" class="pro_pack_type" name="pro_pack_type" value="reader_doc"/>
									<input type="hidden" class="teacher_area" name="teacher_area" value="1"/>
								</div>
								<div class="form-group">
									<label>Reader docs text</label>
									<textarea class="form-control" name="data" id="Reader-docs-text" rows="8" placeholder="Reader docs text here..." required=""></textarea>
								</div>
								<button class="dashboard-settings-btn btn-block pro-pack-add-new-form " type="button" value="1">Save</button>
							</form>
						</div>
						<div class="modal-footer">
							
						</div>
					</div>
				</div>
			</div>
			<!-- add propack model-->
			
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
                </div>
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
        
        <script src="<?php echo ADMIN_URL ?>plugins/datepicker/bootstrap-datepicker.min.js"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->  
        <script src="<?php echo ADMIN_URL ?>dist/js/pages/student-propack.js"></script>
        <script src="<?php echo ADMIN_URL ?>plugins/datatables/datatable-new/jquery.dataTables.min.js"></script>
        <script>
        $(document).ready(function() {
            var typio_table = $('#reader_doc_listing').DataTable({
                   "processing": true,
                   "serverSide": true,
                   "ajax": {
                       "url": "<?php echo ADMIN_URL; ?>config/config-student.php",
                       "type": "POST",
                       data: {'action': 'ajax_reader_doc_listing_lesson','is_reader_ajax' : '1'},
                   },
                   "order": [[1, 'DESC']],
                   "columnDefs": [
                       {"targets": 0, "name": "id", 'searchable': false, 'orderable': false},
                       {"targets": 1, "name": "title", 'searchable': false, 'orderable': true},
                       {"targets": 2, "name": "activity", 'searchable': false, 'orderable': false},
                       {"targets": 3, "name": "activity", 'searchable': false, 'orderable': false},
                       
                   ]
               });       
            });

            $(document).ready(function() {
            var typio_table = $('#share-table1').DataTable({
                   "order": [[0, 'DESC']],
                   "columnDefs": [
                       {"targets": 0, "name": "id", 'searchable': true, 'orderable': true},
                   ]
                   
               });       
            });         
        </script>
    </body>
</html>