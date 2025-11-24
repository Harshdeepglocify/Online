<?php
include "config/config.php";
/*if (isset($_POST) && !empty($_POST)) {
    if (isset($_POST['Submit']) && $_POST['Submit'] == 'Register') {
            ?>
            <script>
                e.preventDefault();
                var $form = $('#register-cbtf');
        		var formData = new FormData($($form)[0]);
        		var action = "https://www.accessibyte.com/wp-admin/admin-ajax.php";
        		$.post($form.attr('action'), $form.serialize(), function(data) {
        			$('.cftb-success').show();
        		}, 'json');</script>
            <?php
    }
}*/
?>
<html><head>
    <meta http-equiv="origin-trial" content="Az520Inasey3TAyqLyojQa8MnmCALSEU29yQFW8dePZ7xQTvSt73pHazLFTK5f7SyLUJSo2uKLesEtEa9aUYcgMAAACPeyJvcmlnaW4iOiJodHRwczovL2dvb2dsZS5jb206NDQzIiwiZmVhdHVyZSI6IkRpc2FibGVUaGlyZFBhcnR5U3RvcmFnZVBhcnRpdGlvbmluZyIsImV4cGlyeSI6MTcyNTQwNzk5OSwiaXNTdWJkb21haW4iOnRydWUsImlzVGhpcmRQYXJ0eSI6dHJ1ZX0=">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Accessibyte | Registration</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="icon" href="https://www.accessibyte.com/online/favicon.png" type="image/png" sizes="32x32">
    <link rel="stylesheet" href="https://www.accessibyte.com/online/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="https://www.accessibyte.com/online/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://www.accessibyte.com/online/plugins/datatables/dataTables.bootstrap.css">
    <!-- bootstrap-datepicker -->
    <link rel="stylesheet" href="https://www.accessibyte.com/online/plugins/datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://www.accessibyte.com/online/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://www.accessibyte.com/online/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="https://www.accessibyte.com/online/bootstrap/css/style.css">
    <link rel="stylesheet" type="text/css" href="https://www.accessibyte.com/online/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://www.accessibyte.com/online/dist/css/custom.css">        <!-- iCheck -->
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="https://www.accessibyte.com/online/plugins/iCheck/square/blue.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
    <!-- cookies assets start -->
    <script type="text/javascript" async="" src="https://www.gstatic.com/recaptcha/releases/fGZmEzpfeSeqDJiApS_XZ4Y2/recaptcha__en.js" crossorigin="anonymous" integrity="sha384-BkcmNQWtC2+F6VzriTvPuJFQk+CQm9Ka25Y8yH4U7AvSZTgBts+ceh072QKv1qYq"></script><script src="https://www.google.com/recaptcha/api.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://www.accessibyte.com/online/dist/css/jquery-eu-cookie-law-popup.css">
    <script src="https://www.accessibyte.com/online/dist/js/jquery-eu-cookie-law-popup.js"></script>
    <!-- cookies assets end -->
    <style>
        .help-block-error{ color:#ff0066; }
        .login-header {
                margin: 2px;
            display:flex;
            background: none repeat scroll 0 0 #fff;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            padding: 1px 1px;
        }
        .no-padding{
            padding:0!important;
        }
        .organization_wrap{
            display:none;
        }
        .cftb-success {
            background: #b9f8c1;
            display: inline-block;
            padding: 10px 10px;
            width: 100%;
            text-align: center;
            border: 1px solid #38b149;
        }
        .text-box{text-align: left;font-size: 15px;padding: 10px !important;}
        #registartion{font-family:poppins;font-size:22px;}
        .login-page, .register-page { 
  		 background: #fff ; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
		}
		#loader {
            border: 10px solid #f3f3f3;
            border-top: 10px solid #ff1170;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display:none;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn-sec-div{
            align-items: center;
            display: flex;
            flex-direction: column;
        }
	</style>
    <script>
        var ADMIN_URL = 'https://www.accessibyte.com/online/';
    </script>
    <style type="text/css">
        @font-face {
          font-weight: 400;
          font-style:  normal;
          font-family: circular;
          src: url('chrome-extension://liecbddmkiiihnedobmlmillhodjkdmb/fonts/CircularXXWeb-Book.woff2') format('woff2');
        }
        @font-face {
          font-weight: 700;
          font-style:  normal;
          font-family: circular;
        
          src: url('chrome-extension://liecbddmkiiihnedobmlmillhodjkdmb/fonts/CircularXXWeb-Bold.woff2') format('woff2');
        }
        .modal-body {
            padding: 18px 10px;
            text-align: center;
        }
        .modal-body p {
            font-size: 20px;
            color: #000;
            font-weight: 600;
        }
        .modal-body span {
            font-size: 30px;
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>

<body class="hold-transition login-page theme-page eupopup eupopup-bottom">
    <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
         <span class="glyphicon glyphicon-ok"></span>
        <p>User Register successfully</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

    <div class="container">
        <?php
            if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                ?>
                            <!--<div style="font-family: Roboto Slab, serif; background-color: #000;" class="alert alert-<?php // echo $_SESSION['error']['color']    ?> alert-dismissable fade in">-->
                <div aria-live="assertive" style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" aria-live="polite">&times;</a>
                    <?php echo $_SESSION['error']['message'] ?>
                </div>
            <?php } unset($_SESSION['error']) ?>
    </div>
    <div class="login-box-body login-header  theme-page">
        
        <span class="logo-lg">
            <div class="image">
                <img src="https://www.accessibyte.com/online/img/accessibyte%20logo.svg" class="accessibyte-logo center-block img-responsive" alt="Accessibyte Online logo">
            </div>
        </span>
    </div>
    <div class="login-box  ">          
            <div class="login-box-body theme-page">
                <!-- action="https://www.accessibyte.com/wp-admin/admin-ajax.php" -->
                
                <form method="POST" novalidate="novalidate" id="register-cbtf" class="margin-bottom" style="display: block;">
                    <div id="errorMessage" class="help-block help-block-error" aria-live="assertive"></div>
                    <input type="hidden" name="action" value="cftbpurchase">
                    <div class="form-group student_value">
                        <input type="hidden" name="user_type" id="user_type" class="form-control user_type" value="student">
                    </div>
                    <div class="form-group" style="margin-bottom:45px;">
                        <h1 class="register-heading" align="center" style="margin-bottom:0px;">Register</h1>
                        <h4 style="color:#ff0066;" align="center"> For Typio Pro Home User - 14-day Trial </h4>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-6 form-group no-padding" id="Lstname" style="padding-right: 6px !important;">
                                <label>First name*</label>
                                <input type="text" name="firstname" id="firstname" autofocus="" required="" class="form-control" placeholder="First name" aria-label="First Name, required">
                            </div>
                            <div class="col-xs-6 form-group no-padding" id="Lstname">
                                <label>Last name</label>
                                <input type="text" name="lastname" id="lastname" required="" class="form-control" placeholder="Last name" aria-label="Last Name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group teacherusername">
                        <label>Username*</label>
                        <input type="text" name="username" id="username" required="" class="form-control" placeholder="Username" aria-label="Username, required">
                    </div>
                    <div class="form-group">
                        <label>Email*</label>
                        <input type="email" name="email" id="email" required="" class="form-control" placeholder="Email" aria-label="Email, required">
                    </div>
                    <div class="form-group">
                        <label>Password*</label>
                        <input type="password" name="password" required="" id="password" class="form-control" placeholder="Password" autocomplete="" aria-label="Password, required">
                    </div>
                    <div class="form-group" style="margin-left: 19%;">
                        <div class="g-recaptcha" data-sitekey="6LfM9nUUAAAAADWkaqgsFvewCxon5HhUEpN8qSVT"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-center">
							<div class="registerCheck form-group">
								<input type="checkbox" id="agree" name="agree" class="teachCheckbox">
								<label for="agree" style="display: unset;"> I agree to Accessibyte's <a href="https://www.accessibyte.com/terms" target="_blank" class="accessibyte-link"> terms and privacy </a>
								</label>
							</div>
                        </div>
                    </div>
                    <div class="form-group btn-sec-div">
                        <input type="submit" value="Register" name="Submit" class="register-cftb btn btn-primary col-md-12" style="margin-bottom: 0px;">
                        <div id="loader"></div>
                    </div>
                    <div class="col-xs-12">
                        <div class="pull-right">
                            <a href="https://www.accessibyte.com/online/" class="accessibyte-link">Go back</a>
                        </div>
                    </div>
                    <div class="cftb-success" style="display:none;">User Register successfully</div> 
                </form>
                <!-- /.login-box-body -->
            </div><!-- /.login-box -->
            <!-- jQuery 2.1.4 -->
            <script src="https://www.accessibyte.com/online/plugins/jQuery/jQuery-2.1.4.min.js"></script>
            <!-- Bootstrap 3.3.5 -->
            <script src="https://www.accessibyte.com/online/bootstrap/js/bootstrap.min.js"></script>
            <!-- iCheck -->
            <script src="https://www.accessibyte.com/online/plugins/iCheck/icheck.min.js"></script>
            <script src="https://www.accessibyte.com/online/plugins/jQuery-validate/jquery.validate.js"></script>
            <script src="https://www.accessibyte.com/online/dist/js/validate.js"></script>
        </div>
    <script>
        /*jQuery.ajax({
            type: 'POST',
            url: 'https://www.accessibyte.com/wp-admin/admin-ajax.php',
            responseType: 'json',
            data: {
                action: 'cftbpurchase',
                ean: 'EANTEST0101010'
            },
            success: function( data ) {
                alert( data );
            },
            error: function( errorThrown ) {
                console.log( errorThrown );
            }
        });*/
       /*jQuery(document).ready(function($) {
           
            $('.register-cftb').on('click', function(e) {
                e.preventDefault();
                $("#register-cbtf").valid();
                
                jQuery.ajax({
                    type: 'POST',
                    url: 'https://www.accessibyte.com/wp-admin/admin-ajax.php',
                    responseType: 'json',
                    data: {
                        action: 'cftbpurchase',
                        ean: 'EANTEST0101010'
                    },
                    success: function( data ) {
                        alert( data );
                    },
                    error: function( errorThrown ) {
                        console.log( errorThrown );
                    }
                });
        
                var $form = $('#register-cbtf');
        		var formData = new FormData($($form)[0]);
        		var action = "https://www.accessibyte.com/wp-admin/admin-ajax.php";
        		$.post(action, $form.serialize(), function(data) {
        			$('.cftb-success').show();
        		}, 'json');
            });
            $('#register-cbtf').on('submit', function(e) {
                e.preventDefault();
                if('#register-cbtf').valid(){ 
                    alert('dfdfdfdf');
                } else { 
                    alert('cvcvcvcvcv');
                }
                
          
            });
        });*/
    </script>
</body></html>