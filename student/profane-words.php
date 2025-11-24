<?php
include "../config/config-student.php";
//include "config-student.php";
//include "../config/config.php";

if(!$_SESSION['User']){
    header("Location: " . ADMIN_URL . 'login');
    exit;
}

global $con;

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    /* if(isset($_POST['text_word'])) { 
      $mydata = $_POST['text_word'];
      $word_arr = explode(',', $mydata);
      $lastupdated = date('Y-m-d H:i:s');

      foreach($word_arr as $single_word) {
      $qry = "INSERT INTO profane_words (word, status, created_on) VALUES ('$single_word', '1', '$lastupdated')";
      if (query($qry)){
      header("Location:" . ADMIN_URL . 'student/profane-words.php');
      }
      }
      } */
    if (isset($_POST['text_word'])) {
        $mydata = $_POST['text_word'];
        $word_arr = array_map('trim', explode(',', $mydata));

        $finalData = implode(',', $word_arr);

        $lastupdated = date('Y-m-d H:i:s');
        $qry = "UPDATE profane_words SET word = '$finalData', created_on = '$lastupdated' WHERE word_id=1";
        //$qry = "INSERT INTO profane_words (word, status, created_on) VALUES ('$mydata', '1', '$lastupdated')";
        if (query($qry)) {
            header("Location:" . ADMIN_URL . 'student/profane-words.php');
        }
    }
}
$qry_select = "SELECT * FROM profane_words WHERE 1";
$records = query($qry_select);

$my_words = '';
if (mysqli_num_rows($records) > 0) {
    while ($row = mysqli_fetch_assoc($records)) {
        $my_words = $row["word"];
    }
} else {
    echo "0 results";
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
                <!-- Main content -->
                <!-- Quick card deck  -->
                <section class="content">
                    <div class="row"> 
                        <!-- Title -->
                        <div class="col-md-12">
                            <div class="student-title-main-init">              
                                <h3>Profane Words</h3>               
                            </div>
                        </div>
                        <!-- chart one -->  
                        <div class="col-md-12">
                            <form method="POST" id="add-students-deck-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <div class="chart-one-main-init student-decks-maker_type_1">
                                    <!--   quick-cards Sectoin    --> 
                                    <div class="quick-ajax-response">
                                        <div class="alert alert-dismissible fade in" id="alert-success"></div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="">  
                                                <div class="with-border">
                                                    <h3 class="box-title">Word List</h3>
                                                </div>
                                                <input id="qc-student-id" name="user_id" class="form-control" type="hidden" value="<?php echo $_SESSION['User']['id']; ?>">
                                                <!-- /.box-header -->
                                                <div class="box-body students_decks_tbl">
                                                    <div class="form-group">
                                                        <label>Words</label>
                                                        <textarea class="form-control" name="text_word" placeholder="Type Deck Word here..." required="" rows="10"/><?php if (!empty($my_words)) {
                echo $my_words;
            } ?> </textarea>

                                                        <p id="error_desk_title_alert" class="error"></p>
                                                        <!-- <input id="user_id" type="hidden" name="user_id" value="<?php echo $student_id; ?>" />-->
                                                    </div>
                                                    <label class="color">Note : Add multiple word by comma (,)</label>
                                                    <button type="submit" class="btn btn-info pull-right" name="submit">Save</button>  
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--   End quick-cards Sectoin   -->
                                    <div class="row">
                                        <div class="space-margin-bottom-50"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

            </div>
            <!-- add quick card desk model-->


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
