<?php include "config/config.php";
if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}
$get_license_details = get_license_details_all($_SESSION['User']['license']);
if (($get_license_details['license_status'] != 'expired') || ($get_license_details['license_status'] != 'valid' && $get_license_details['expires'] != 'lifetime')){
    $date1_ts = strtotime(date('Y-m-d'));
    $date2_ts = strtotime(date('Y-m-d', strtotime($get_license_details['expires'])));
    $diff = $date2_ts - $date1_ts;
    $dateDiff = round($diff / 86400);
}
$user_license = $_SESSION['User']['license'];
$no_of_licence = get_license_data($user_license);
$studentCount = 0;
$teacherCount = 1;
if (!empty($no_of_licence)) {
    $studentCount = isset($no_of_licence['no_student']) ? $no_of_licence['no_student'] : '';
    $teacherCount = isset($no_of_licence['no_teacher']) ? $no_of_licence['no_teacher'] : '';
}

$pricingArr = get_pricing_option_data();   /* get wordpress pricing array*/
$teacherFixPrice = get_teacher_fixed_price();
$licenseArr = explode("-", trim($user_license));
$pricingStr =  json_encode($pricingArr,true);
$type = isset($licenseArr[0]) ? $licenseArr[0] : '';
if($type == 'TCH'){
    $product_id = '11433';
} else if($type == 'TCHP'){
    $product_id = '38582';
}
$product_type = strtolower($type);
$currentPrice = isset($pricingArr[$product_type][$studentCount]) ? $pricingArr[$product_type][$studentCount] : '0.00';

$is_expired = check_expire_or_not();
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

        <?php include "config/css.php"; ?>

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <?php include "config/top-header.php"; ?>

            <!-- Left side column. contains the logo and sidebar -->
            <?php include "config/left-sidebar.php"; ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <?php if($is_expired){?>
                            <div class="col-lg-12 expire-error-msg" style="margin-bottom:10px;">
                                <p>Your license is expired!</p>
                            </div>
                        <?php }?>
                        <div class="col-md-12">

                            <div class="box-body panel student-title-main-init">

                                <h3>Renew license</h3>

                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="box-body panel my-account-main-init">
                                <?php
                                    $users_licenses_details = users_license_list($_SESSION['User']['id']);
                                ?>
                                <div class="typio-sidebar-alert alert alert-dismissible" aria-live="assertive"></div>
                                <div class="col-md-12 row errorView " style="display:none;">

									<div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">
										<h4><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											<span class="msg"></span></h4>
									</div>

                                </div>
                                <form name="renew-form" id="renew-form" method="POST" action="">
                                    <div class="row">
                                        <input type="hidden" name="teacherFixPrice" class="teacherFixPrice" value="<?= $teacherFixPrice;?>">
                                        <input type="hidden" name="product_type" class="product_type" value="<?= $type;?>">
                                        <input type="hidden" name="product_total" class="product_total" value="0">
                                        <div class="row-inner">
                                            <div class="cols">
                                                <h3>License:</h3>
                                                <p><?php echo $users_licenses_details['0']['license']; ?></p>
                                            </div>
                                            <div class="cols">
                                                <?php
                                                if($get_license_details['license_status'] == 'expired'){
                                                        $newdate = '(Expired)';
                                                    } else if ($get_license_details['license_status'] == 'valid' && $get_license_details['expires'] == 'lifetime'){
                                                        $newdate = '(Lifetime)';
                                                    } else {
                                                        $newdate = date('m-d-Y', strtotime('+1 year', strtotime($get_license_details['expires'])));
                                                    }
                                                ?>
                                                <h3>Expiration:</h3>
                                                <p><input type="text" name="renewal_date" id="renewal_date" value="<?= $newdate;?>" readonly></p>
                                            </div>
                                            <div class="cols">
                                                <h3>Students:</h3>
                                                <p><input type="number" name="no_student" id="no_student" value="<?php echo $studentCount;?>" min="0" max="100"></p>
                                            </div>
                                            <div class="cols">
                                                <h3>Teachers:</h3>
                                                <p><input type="number" name="no_teacher" id="no_teacher" value="<?php echo $teacherCount;?>" min="1" max="100"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <p><span class="total">$0.00</span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="comman-flex-div comman-flex-div-custom">
                                                <a href="<?php echo LICENSES_WEB_PATH."/?add_to_cart=".$product_id."&edd_options[product]=".$type."&edd_options[quantity]=".$studentCount."&edd_options[no_teacher]=".$teacherCount;?>" name="request_quote" class="purchase_now dashboard-settings-btn btn-block" id="purchase_now">Purchase Now</a>
                                                <input type="button" name="request_quote" class="request_quote dashboard-settings-btn btn-block" id="request_quote" value="Request Quote">
    											<span id="spinner_loader" class='spinner_loader' style="display: none; font-size: 6px;" ><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-8 downloadlink" style="display:none;">
                                            <span>Success! You will receive an email with your quote. You can also <a class='download-pdf' href='javascript:void(0);' data-href=''>Click here</a> to download it down.</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

    <?php include "config/footer.php"; ?>

    <!-- Control Sidebar -->
    <?php include "config/setting.php"; ?>
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

    <script>
        $(document).ready(function(){
            calculatePrice();
            $("#no_student").change(function(){
                calculatePrice();
            });
            $("#no_teacher").change(function(){
                calculatePrice();
            });
            $(document).on('click', '.request_quote', function () {
                var renewal_date = $('#renewal_date').val();
                var product_total = $('.product_total').val();
                var no_student = $('#no_student').val();
                var no_teacher = $('#no_teacher').val();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo ADMIN_URL?>config/config-teacher.php',
                    data: {'no_student': no_student,'no_teacher':no_teacher,'renewal_date':renewal_date,'total':product_total,'type':'send_quote_renew','action': 'send_quote'},
                    beforeSend: function () {
                        $('.spinner_loader').show();
                    },
                    success: function (response) {
						$('.errorView').show();
                        var response = $.parseJSON(response);
                        $('.spinner_loader').hide();
                        $('.msg').text(response.msg);
                        $('.downloadlink').show();
                        $('.download-pdf').attr('data-href',response.downloadlink);

                    }
                });
            });
            $(document).on("click", 'a.download-pdf', function () {
                path = $(this).attr('data-href');
                window.open(path);
            });
        });
        function calculatePrice(){
            var no_student = $("#no_student").val();
            var no_teacher = $("#no_teacher").val();
            var teacherFixPrice = $(".teacherFixPrice").val();
            var customArr = '<?php echo $pricingStr;?>';
            var no_all_std = '<?php echo $studentCount;?>';
            var dateDiff = '<?php echo $dateDiff;?>';
            var product_id = '<?php echo $product_id;?>';
			var current_price = '<?php echo $currentPrice;?>';
            var teacher = no_teacher;
            $('.total').text("$0.00");
            var teacherPrice = 0;
            console.log(teacherFixPrice);
			if (teacherFixPrice != '0') {
				teacherPrice = teacher * teacherFixPrice;            
			}

            var product_type = $(".product_type").val();

            var productname = product_type.toLowerCase();
            customArr = $.parseJSON(customArr);

            if (typeof customArr != 'undefined' && no_student != '' && no_student != '0') {
                var newStdval;
                var newDayval = 1;
                var finalval;
                var newStudentCount;
                $.each(customArr, function (key, value) {
                    if (key == productname) {
                        newval = finaltotal = 0;
                        $.each(value, function (vkey, vvalue) {

                            if (key == productname) {
                                pricekey = vkey.split("-");
                                $('.total').text("$" + vvalue);
                                if (pricekey[0] == no_student) {
                                    total = vvalue;
                                    finaltotal = (parseFloat(teacherPrice) + parseFloat(vvalue)).toFixed(2);  /* Add code for add teacher price in student price */
                                    console.log(finaltotal);
                                    $('.total').text("$" + finaltotal);
                                    $('.product_total').val(finaltotal);
                                    return false;
                                } else if (pricekey[0] != no_student && no_student != "0") {

                                    lastkey = Object.keys(value).pop();
                                    lastvalue = value[Object.keys(value).pop()];
                                    lastval = lastvalue / lastkey;
                                    newval = lastval * (no_student - lastkey);
                                    finaltotal = (parseFloat(lastvalue) + parseFloat(newval)).toFixed(2);
                                    finaltotal = (parseFloat(teacherPrice) + parseFloat(finaltotal)).toFixed(2);
                                    $('.total').text("$" + finaltotal);
                                    $('.product_total').val(finaltotal);
                                }
                            }
                        });
                    }

                });

            } else {
                jQuery('.total').text("$0.00");
            }
            $("#purchase_now").attr("href",  "<?php echo LICENSES_WEB_PATH?>?add_to_cart=" + product_id + "&edd_options[product]=" + product_type + "&edd_options[quantity]=" + no_student + "&edd_options[no_teacher]=" + no_teacher);
        }
    </script>

    </body>
    </html>
