<?php

include "../config/config.php";
 unset($_COOKIE['keep_login']);

//setcookie("keep_login", "", time() - 3600);
setcookie('keep_login', null, -1, '/','.accessibyte.com');
	//setcookie("keep_login", "", time()-3600*48,'/','.accessibyte.com');
	// unset($_COOKIE['keep_login']);

session_destroy();

?>

<script type="text/javascript">

    var count = 2

    var counter = setInterval(timer, 1000);

    ;

    function timer()

    {

        count = count - 1;

        if (count <= 0)

        {

            clearInterval(counter);

            return;

        }

        document.getElementById("timer").innerHTML = count + " secs"; // watch for spelling

    }

    function Redirect() {

        window.location = "<?php echo ADMIN_URL . 'login'; ?>";

    }

    setTimeout('Redirect()', 3000);



</script>





<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">



        <!-- Tell the browser to be responsive to screen width -->

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<?php include('../config/css.php') ?>

    </head>

    <body class="hold-transition skin-blue sidebar-mini">

        <!-- Main content -->

        <section class="content">      



            <div class="row">    

                <div class=""></div>

                <div class="col-md-offset-4  col-md-4">

                    <!-- Widget: user widget style 1 -->

                    <div class="box box-widget widget-user logout wt-logout">

                        <!-- Add the bg color to the header using any of the bg-* classes -->

                        <div class="widget-user-header wt-user-header-login">

                            <img class="img-responsive" src="<?php echo ADMIN_URL; ?>img/accessibyte-online-logo.png">

                        </div>



                        <div class="box-footer wt-login-footer">

                            <div class="row">

                                <h4 align="center">Please wait while you are logged out...</h4>

                            </div><!-- /.row -->

                        </div>

                        <div class="overlay wt-overlay">

                            <?php

                            if (defined('LOGOUT_SPINNER') && !empty(LOGOUT_SPINNER)) {

                                echo '<img src="' . LOGOUT_SPINNER . '" >';

                            } else {

                                echo '<i class="fa fa-refresh fa-spin"></i>';

                            }

                            ?>

                        </div>

                    </div><!-- /.widget-user -->

                </div>



                <!-- /.col -->

            </div><!-- /.row -->



            <!-- =========================================================== -->



            <!-- /.row -->



            <!-- =========================================================== -->







            <!-- /.row -->



            <!-- /.row -->



        </section><!-- /.content -->









        <div class="control-sidebar-bg"></div>

    </div><!-- ./wrapper -->



    <!-- jQuery 2.1.4 -->

    <script src="<?php echo ADMIN_URL ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

    <!-- Bootstrap 3.3.5 -->

    <script src="<?php echo ADMIN_URL ?>bootstrap/js/bootstrap.min.js"></script>

    <!-- Slimscroll -->

    <script src="<?php echo ADMIN_URL ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>

    <!-- FastClick -->

    <script src="<?php echo ADMIN_URL ?>plugins/fastclick/fastclick.min.js"></script>

    <!-- AdminLTE App -->

    <script src="<?php echo ADMIN_URL ?>dist/js/app.min.js"></script>

    <!-- AdminLTE for demo purposes -->

    <script src="<?php echo ADMIN_URL ?>dist/js/demo.js"></script>

</body>

</html>

