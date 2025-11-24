<?php include "config/config.php";

if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}
$get_license_details = get_license_details_all($_SESSION['User']['license']);
//echo '<pre>';
//print_r($get_license_details);
if (($get_license_details['license_status'] != 'expired') || ($get_license_details['license_status'] != 'valid' && $get_license_details['expires'] != 'lifetime')){
    $date1_ts = strtotime(date('Y-m-d'));
    $date2_ts = strtotime(date('Y-m-d', strtotime($get_license_details['expires'])));
    $diff = $date2_ts - $date1_ts;
    $dateDiff = round($diff / 86400);
}

$user_license = $_SESSION['User']['license'];
$no_of_licence = get_license_data($user_license);
//echo '<pre>';
//print_r($no_of_licence);
$studentCount = 0;
$teacherCount = 1;
if (!empty($no_of_licence)) {
    $studentCount = isset($no_of_licence['no_student']) ? $no_of_licence['no_student'] : '';
    $teacherCount = isset($no_of_licence['no_teacher']) ? $no_of_licence['no_teacher'] : '';
}

$pricingArr = get_pricing_option_data();   /*get wordpress pricing array*/
//echo '<pre>';
//print_r($pricingArr);
$teacherFixPrice = get_teacher_fixed_price();
//echo '<pre>';
//print_r($teacherFixPrice );
$licenseArr = explode("-", trim($user_license));
$pricingStr =  json_encode($pricingArr,true);
$type = isset($licenseArr[0]) ? $licenseArr[0] : '';
if($type == 'TCH') { 
    $product_id = '11433';
} else if($type == 'TCHP') { 
    $product_id = '38582';
} else if($type == 'TCHI') {
    $product_id = '72097';
}
$product_type = strtolower($type);
$currentPrice = isset($pricingArr[$product_type][$studentCount]) ? $pricingArr[$product_type][$studentCount] : '0.00';
//$currentPrice = $currentPrice + ($teacherCount*$teacherFixPrice);
//echo '<pre>';
//print_r($currentPrice );
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

									<div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF"  class="alert  alert-dismissable fade in">
										<h4><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											<span class="msg" aria-live="polite"></span></h4>
									</div>

                                </div>
<div class="col-md-12  " style="padding-top:40px!important;max-width: 1200px;">
 <div class="account-lbl">
<p>School Edition licences do not automatically renew but there is a simple process to continue using your account just as it is now.</p>
<ol type="1" style="font-size: 18px !important;
    font-weight: 400;margin-top:10px;
    font-family: 'Lato', sans-serif;">
<li>Purchase a fresh license</li>
<li><a href="https://www.accessibyte.com/online/update-license.php">Perform a license Update</a> to apply the new license to your existing account</li>
</ol>
<p style="margin-top: 10px;">You can update the new license details below or keep them the same. If your new license has fewer seats than your current license, you may be asked to delete some accounts before the new license
 can be applied.</p><p style="margin-top: 10px;">Your current license expires in <?php
																/* new code for updated license expiration  date */
																$dateDiff = 0;
																if($get_license_details['license_status'] == 'expired'){
																	echo '(Expired)';
																} else if ($get_license_details['license_status'] == 'valid' && $get_license_details['expires'] == 'lifetime'){
																	echo '(Lifetime)';
																} else {
																	$date1_ts = strtotime(date('Y-m-d'));
																	$date2_ts = strtotime(date('Y-m-d', strtotime($get_license_details['expires'])));
																	$diff = $date2_ts - $date1_ts;
																	$dateDiff = round($diff / 86400);
																	
																	echo  "<b>".$dateDiff . ' days </b>';
echo " (".date("m-d-Y", strtotime($get_license_details['expires'])) . ") ";
																}

                                                            
                                                                ?></p>
</div>
</div>
<div class="col-md-12">
                                <form name="renew-form" id="renew-form" method="POST" action="">
                                    <div class="row">
                                        <input type="hidden" name="teacherFixPrice" class="teacherFixPrice" value="<?= $teacherFixPrice;?>">
                                     <!--   <input type="hidden" name="product_type" class="product_type" value="<?= $type;?>"> -->
                                        <input type="hidden" name="product_total" class="product_total" value="0">
                                        <div class="row-inner" style="margin-bottom:50px">
                                         <div class="cols">
                                                <!-- <h3>License:</h3>
                                                <p><?php echo $users_licenses_details['0']['license']; ?></p> -->
<h3>Product</h3>
<p><select name="product_type" id="product_type" class="product_type" style="padding: 8px;">
<?php if($type == 'TCH'){ ?> <option value="TCH" <?php if($type == 'TCH'){ ?> selected <?php } ?>>Typio School Edition</option><?php } ?>
<?php if($type == 'TCHP'){ ?><option value="TCHP" <?php if($type == 'TCHP'){ ?> selected <?php } ?>>Accessibyte All Access School Edition</option><?php } ?>
<?php if($type == 'TCHI'){ ?><option value="TCHI" <?php if($type == 'TCHI'){ ?> selected <?php } ?>>Typio Pro Institution Edition</option><?php } ?>
</select> </p> </div>
                                          
                                            <!--<div class="cols">
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
                                            </div> -->
                                            <div class="cols">
                                                <h3>Student Seats</h3>
                                                <p><input type="number" name="no_student" id="no_student" value="<?php echo $studentCount;?>" min="0" max="100" style="cursor: text!important;
   padding: 5px;"></p>
                                          </div>
                                            <div class="cols">
                                                <h3>Teachers Seats</h3>
                                                <p><input type="number" name="no_teacher" id="no_teacher" value="<?php echo $teacherCount;?>" min="1" max="100" style="cursor: text!important;
   padding: 5px;"></p>
                                            </div>
 <div class="cols">
                                                <h3>Years	</h3>
                                                <p><select name="discount" id="discount" class="discount" style="padding: 8px;width: 200px">
<option value="1" >          1 year       </option>
<option value="2" >          2 year       </option>
<option value="3" >          3 year       </option></select>
</select> </p>
                                            </div>
<div class="cols"> <p><span class="total" style="top:19px;!important;font-weight: 400;">$0.00</span></p></div>
                                        </div>
                                    </div>
<input type="hidden" name="purchase_link" id="purchase_link" value="" >
                           <!--         <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <p><span class="total">$0.00</span></p>
                                        </div>

                                    </div> -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="comman-flex-div comman-flex-div-custom">
                                                <a href="<?php echo LICENSES_WEB_PATH."/?add_to_cart=".$product_id."&edd_options[product]=".$type."&edd_options[quantity]=".$studentCount."&edd_options[no_teacher]=".$teacherCount;?>" name="request_quote" class="purchase_now dashboard-settings-btn btn-block"  role="button" id="purchase_now">Purchase Now</a>
                                                <input type="button" name="request_quote" class="request_quote dashboard-settings-btn btn-block" id="request_quote" value="Request Quote">
    											<span id="spinner_loader" class='spinner_loader' style="display: none; font-size: 6px;" ><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-8 downloadlink" style="display:none;">
                                            <span>You will receive an email with your quote. You can also <a class='download-pdf' href='javascript:void(0);' data-href=''>Click here</a> to download it down.</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
    </div>
                        </div>
                    </div>
                </section>
            </div>


 <div class="modal " id="quote_model">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close close1" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title msg1"></h4> 
                    </div>
                    <div class="modal-body">
                      
  <span>Success! Your quote has been sent to your inbox.You can also <a class='download-pdf11' href='javascript:void(0);' data-href=''>Click here</a> to download it now.</span>
                                     
                    </div>
                  

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
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
$("#product_type").change(function(){
                calculatePrice();
            });
$("#discount").change(function(){
                calculatePrice();
            });
            $("#no_teacher").change(function(){
                calculatePrice();
            });
            $(document).on('click', '.request_quote', function () {
                 var renewal_date = $('#renewal_date').val();
var product_type = $('#product_type').val();
console.log($(":input[type=text][readonly='readonly']").val());
console.log('renew: '+renewal_date);
var purchase_link = $("#purchase_link").val();
console.log(purchase_link);
                var product_total = $('.product_total').val();
console.log('product_total : '+product_total );
                var no_student = $('#no_student').val();
console.log('no_student  : '+no_student  );
                var no_teacher = $('#no_teacher').val();
console.log('no_teacher : '+ no_teacher);   
             var year_discount = $('#discount').val();
console.log('year_discount  : '+ year_discount );   
                $.ajax({
                    type: 'POST',
                    url: '<?php echo ADMIN_URL?>config/config-teacher.php',
                    data: {'purchase_link':purchase_link ,'no_student': no_student,'product_type':product_type,'no_teacher':no_teacher,'renewal_date':renewal_date,'total':product_total,'type':'send_quote_renew','action': 'send_quote_new'},
                    beforeSend: function () {
                        $('.spinner_loader').show();
                    },
                    success: function (response) {
						$('.errorView').show();
                        var response = $.parseJSON(response);
                        $('.spinner_loader').hide();
                     $('.msg').html("Your quote has been sent to your inbox. You can also <a class='download-pdf' href='javascript:void(0);' style='color:#ff0066' data-href=''>Click here</a> to download it now");
         //     $('.msg1').text(response.msg);
                      //  $('.downloadlink').show();
                        $('.download-pdf').attr('data-href',response.downloadlink);
 //$('.download-pdf1').attr('data-href',response.downloadlink);
//$('#quote_model').show();
                    }
                });
            });

$('.close1').click(function(){
$('#quote_model').hide();
})
            $(document).on("click", 'a.download-pdf', function () {
                path = $(this).attr('data-href');
                window.open(path);
            });
        });
        function calculatePrice(){
var yearDiscount  = 1;
var year_discount = $('#discount').val();
            var no_student = $("#no_student").val();
            var no_teacher = $("#no_teacher").val();
            var teacherFixPrice = $(".teacherFixPrice").val();
            var customArr = '<?php echo $pricingStr;?>';
            var no_all_std = '<?php echo $studentCount;?>';
            var dateDiff = '<?php echo $dateDiff;?>';
var product_type = $("#product_type").val();

if(product_type == 'TCH'){
    var product_id = '11433';
} else if(product_type == 'TCHP'){
    var product_id = '38582';
}
else if(product_type == 'TCHI'){
    var product_id = '72097';
}
        <!--    var product_id = '<?php echo $product_id;?>'; -->
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
                                   console.log(year_discount  + 'year_discount'); 
if(year_discount != 1 && typeof yearDiscount != 'undefined' && yearDiscount != '' && yearDiscount != 0){
console.log('inside_discount');
                                var dis = (finaltotal * yearDiscount ) / 100;
                                var discountval = (parseFloat(finaltotal) - parseFloat(dis)).toFixed(2);
                                discountval = discountval * (year_discount - 1);   /* Year beased changed discount value */
                                finaltotal = (parseFloat(discountval) + parseFloat(finaltotal)).toFixed(2);    /* Add one year value to discounted value */                                
                            }
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
if(year_discount != 1 && typeof yearDiscount != 'undefined' && yearDiscount != '' && yearDiscount != 0){
console.log('inside_discount');
                                var dis = (finaltotal * yearDiscount ) / 100;
                                var discountval = (parseFloat(finaltotal) - parseFloat(dis)).toFixed(2);
                                discountval = discountval * (year_discount - 1);   /* Year beased changed discount value */
                                finaltotal = (parseFloat(discountval) + parseFloat(finaltotal)).toFixed(2);    /* Add one year value to discounted value */                                
                            }
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
            $("#purchase_now").attr("href",  "<?php echo LICENSES_WEB_PATH?>?add_to_cart=" + product_id + "&edd_options[product]=" + product_type + "&edd_options[quantity]=" + no_student + "&edd_options[no_teacher]=" + no_teacher + "&edd_options['discount']=" + year_discount );
     var link = "<?php echo LICENSES_WEB_PATH?>?add_to_cart=" + product_id + "&edd_options[product]=" + product_type + "&edd_options[quantity]=" + no_student + "&edd_options[no_teacher]=" + no_teacher + "&edd_options[discount]=" + year_discount ;
$("#purchase_link").val(link);

   }
    </script>

    </body>
    </html>
