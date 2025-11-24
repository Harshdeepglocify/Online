<?php include "../config/config.php"; ?>
<?php
include "Browser.php";
$browser = new Browser();
//Direct Login to user 
$logtype = 'Standard';
if (isset($_GET['UID']) && !empty($_GET['UID'])) {
$logtype = '1-Click';

//    $urlData = explode('-',base64_decode($_GET['UID']));
    //echo "<pre>";print_r($urlData);
  //  $_POST['email'] = !empty($urlData) && isset($urlData[0]) ? $urlData[0] : '';
//    $_POST['password'] = !empty($urlData) && isset($urlData[1]) ? $urlData[1] : '';
//$link = base64_encode($_GET['UID']);
$link = $_GET['UID'];
//print_r('SELECT * FROM user WHERE Login_url="' . $link . '"');
$sql = query('SELECT * FROM user WHERE Login_url="' . $link . '"');
$data = fetch($sql); //print_r($data); die(0);
$exp = 'No';
if(!empty($data)){
$today_date = date('Y-m-d h:i:s');
//print_r($data['link_expiry']);
//print_r($today_date);
//print_r($data['wait_page']);
if((($data['link_expiry'] > $today_date) || $data['link_val'] == 'Never' ) && ($data['wait_page'] == 'No' || $_GET['wait'] == 'No') ) {
    //print_r('fowienfe');
    $_POST['email'] = !empty($data['username']) && isset($data['username']) ? $data['username'] : '';
    $_POST['password'] = !empty($data['password']) && isset($data['password']) ? $data['password'] : '';
    $_POST['id'] = $data['id'];
}

if($data['link_expiry'] < $today_date && ($data['wait_page'] == 'No' || $_GET['wait'] == 'No')) {
    $exp = 'Yes';
}

} else {
    $urlData = explode('-',base64_decode($_GET['UID']));
    //echo "<pre>";print_r($urlData);
    $_POST['email'] = !empty($urlData) && isset($urlData[0]) ? $urlData[0] : '';
    $_POST['password'] = !empty($urlData) && isset($urlData[1]) ? $urlData[1] : '';
}
$_POST['log_type'] = $_COOKIE['logdata'].' via 1-click';
include './Robot.php';
}

if( isset($_SESSION['user']['UID']) &&  $_SESSION['user']['UID'] != "" && !isset($_SESSION['error'])){ 
	$logtype = 'Keep_login';
	$_POST['email'] =  $_SESSION['user']['UID'] ;
	$_POST['password'] =  $_SESSION['user']['pwd'];
    $_POST['log_type'] = $_COOKIE['logdata'].' via keep login';
	include './Robot.php';
}
if(isset($_COOKIE['keep_login'])) { 
	$logtype = 'Keep_login';
	//print_r($_COOKIE['keep_login']);
	$details = explode('|',$_COOKIE['keep_login']);
	//print_r($details);
	$_POST['email'] =  $details[0] ;
	$_POST['password'] =  $details[1];
    $_POST['log_type'] = $_COOKIE['logdata'].' via keep login';
    
    
    include './Robot.php';
}
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$microsoft_edge ="";
if (strpos($userAgent, "Edg") !== false) {
    $microsoft_edge ="1";
}
$safari ="";
if (strpos($userAgent, "Safari") !== false) {
    $safari ="1";
}
$chrome ="";
if (strpos($userAgent, "Chrome") !== false) {
    $chrome ="1";
}
$ioschrome ="";
if (strpos(strtolower($userAgent), "crios") !== false || strpos(strtolower($userAgent), "fxios") !== false) {
    $ioschrome ="1";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
       
        
 <!--<link rel="manifest" href="manifest.json">-->
 <link rel="manifest" id="my-manifest-placeholder">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="apple-mobile-web-status-bar-style" content="black-translucent" />
<meta name="apple-touch-icon" href="../img/output-onlinepngtools1.png"/>
<meta name="theme-color" content="#141414" />

<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
       
        <title><?php echo ADMIN_Text; ?> | Login</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
        <link href="../img/splashscreens/iphone5_splash.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
        <link href="../img/splashscreens/iphone6_splash.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
        <link href="../img/splashscreens/iphoneplus_splash.png" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
        <link href="../img/splashscreens/iphonex_splash.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
        <link href="../img/splashscreens/iphonexr_splash.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
        <link href="../img/splashscreens/iphonexsmax_splash.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
        <link href="../img/splashscreens/ipad_splash.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
        <link href="../img/splashscreens/ipadpro1_splash.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
        <link href="../img/splashscreens/ipadpro3_splash.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
        <link href="../img/splashscreens/ipadpro2_splash.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
        <!-- Bootstrap 3.3.5 -->
        <?php include "../config/css.php"; ?>
        <!-- iCheck -->

        <!-- cookies assets start -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>dist/css/jquery-eu-cookie-law-popup.css"/>
        <script src="<?php echo ADMIN_URL; ?>dist/js/jquery-eu-cookie-law-popup.js"></script>

        <!-- cookies assets end -->

        <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>plugins/iCheck/square/blue.css">
<link href="<?php echo ADMIN_URL; ?>dist/css/style.css" rel="stylesheet" />

        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script>
        
        // window.close pwa function
      //      UI.getCurrent().getPage().executeJS("window.close();");
            var myCurrentUrl = window.location.href;
            var myDynamicManifest = {
            "id": myCurrentUrl,
            "name": "Accessibyte", 
            "short_name": "Accessibyte", 
            "theme_color": "#141414",
            "background_color": "#ffffff",
            "display": "fullscreen",
            "display_override": ["window-controls-overlay"],
              "start_url": myCurrentUrl,
              "icons": [
                    {
                      "src": "https://www.accessibyte.com/online/img/192.png",
                       "sizes": "192x192",
                      "type": "image/png"

                    },
                    {
                        "src": "https://www.accessibyte.com/online/img/512.png",
                        "sizes": "512x512",
                        "type": "image/png"

                    }
                ],
                "screenshots": [
                    {
                      "src": "https://www.accessibyte.com/online/img/screenshot-wide.png",
                      "sizes": "1280x720",
                      "type": "image/png",
                      "form_factor": "wide"
                    },
                    {
                      "src": "https://www.accessibyte.com/online/img/screenshot-narrow.png",
                      "sizes": "375x667",
                      "type": "image/png",
                      "form_factor": "narrow"
                    }
                ]
            }
            const stringManifest = JSON.stringify(myDynamicManifest);
            const blob = new Blob([stringManifest], {type: 'application/json'});
            const manifestURL = URL.createObjectURL(blob);
            document.querySelector('#my-manifest-placeholder').setAttribute('href', manifestURL);
            //window.location.reload();
        </script>
        <style>
            .help-block-error{ color:#a94442; }
            .login-header {
                    margin: 2px;
                display:flex;
                background: none repeat scroll 0 0 #fff;
                border-top-left-radius: 4px;
                border-top-right-radius: 4px;
                padding: 1px 1px;
            }
            
            <?php
    //         if (defined('LOGIN_PAGE_IMG') && !empty(LOGIN_PAGE_IMG)) {
    //             echo '.login-page, .register-page { 
			 // 				background: url(' . LOGIN_PAGE_IMG . ') no-repeat center center fixed; 
			 // 				-webkit-background-size: cover;
			 // 				-moz-background-size: cover;
			 // 				-o-background-size: cover;
			 // 				background-size: cover;
			  				
				// 		}';
    //         } else {
                echo '.login-page, .register-page { 
                            background: #fff ; 
                            -webkit-background-size: cover;
                            -moz-background-size: cover;
                            -o-background-size: cover;
                            background-size: cover;
                            
                        }';
            // }
            ?>
        </style>
        <script>
            var ADMIN_URL = '<?php echo ADMIN_URL; ?>';
        </script>
        <script type="text/javascript">
            function validLogin() {
                var email = $('#email').val();
                var password = $('#password').val();
                var dataString = 'email=' + email + '&password=' + password;
                $.ajax({
                    type: "POST",
                    url: "Robot.php",
                    data: dataString,
                    cache: false,
                    success: function (result) {
                        if (result == 1) {
                            window.location = '<?php echo ADMIN_URL; ?>';
                        } else {
                            $("#errorMessage").html('<div class="alert_box error"><button class="close" aria-live="polite"></button>' + result + '</div>');
                        }
                    }
                });
            }
        </script>
        <!-- <script>
            (function () {
                var s = document.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = 'https://app.termly.io/embed.min.js';
                s.id = '77c63cce-b493-44dd-914f-168104a58cdc';
                s.setAttribute("data-name", "termly-embed-banner");
                var x = document.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            })();
        </script> -->
    </head>
    <style>
        .alert .close {
            color: #ffffff;
            opacity: 1;
            font-family: Roboto Slab, serif;
        }

    </style>
    <body class="hold-transition login-page  theme-page eupopup eupopup-bottom">
    <!-- HTML NEEDED FOR THE IMPORTANT MESSAGE MODAL POPUP -->
   
<?php if(isset($_GET['UID']) && !empty($data)){ ?>
 <div class="login-box-body login-header  theme-page">
                <span class="logo-lg">
                    <div class="image">
                        <img src="<?php echo ADMIN_URL . 'img/accessibyte-logo-update.svg' ?>" class="accessibyte-logo center-block img-responsive" alt="Accessibyte Online logo">
                    </div>
                </span>
            </div>
<div class="login-box">
            <!-- <div class="login-logo">&nbsp;</div> --> <!-- /.login-logo -->
            
            <div class="login-box-body  theme-page">
 <br>   <br>   <br> <br>   <br>   <br>
<?php if($exp == 'No'){ ?>
                <p class="login-box-msg">Log in to your account</p>
                
                      <br>   <br>   <br><br>   <br>
                    <div class="row">
                        <input type="hidden" name="login_link" id="login_link" value="https://www.accessibyte.com/online/login/?UID=<?= $_GET['UID'] ?>" />

                        <div class="col-md-12 col-xs-12" style="
    text-align: center;
">
                            <button type="submit" name="Sign In" aria-label="<?php echo $login_aria_label;?>" class="btn btn-primary login_redirect btn-block btn-flat"/>Log In<i class="fa fa-long-arrow-right" aria-hidden="true"></i> </button>                      

                        <br>

<input type="hidden" id="userUID" name="userUID" value="<?= $_GET['UID'] ?>" />
<input type="checkbox" id="ignore_wait1" class="ignore_wait1" name="ignore_wait"  />
<label for="ignore_wait"> Don't wait to login when I visit this page</label>
</div><!-- /.col -->
			
						<div class="col-md-12 col-xs-12">

                      
                        </div><!-- /.col -->
                    </div>
              
                
            </div><!-- /.login-box-body -->
<?php }else{ ?>
  <h1 class="login-box-msg">Login Link Expired</h1>
                
<?php } ?>
        </div><!-- /.login-box -->


<?php }else { ?>
    <div class="modal fade " id="important-msg" tabindex="-1" role="dialog" aria-labelledby="important-msg-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background:#f06; color:#fff;">
                    <button type="button" class="close" style="color: #fff;" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="important-msg-label">Alert!</h4>
                </div>
                <div class="modal-body">
                    <p>iPhone and iPad users must use the Safari browser.</p>
                </div>
                <div class="modal-footer" style="background:#f06; color:#fff;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- END HTML NEEDED FOR THE IMPORTANT MESSAGE MODAL POPUP -->

        <div class="container" aria-live="polite">
            <?php
            if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                if ($_SESSION['error']['message'] == 'IPHONE_ERROR'){?>
                    <script type="text/javascript">
                        setTimeout(function () {
                            $('#important-msg').modal('show');
                        }, 1000);
                    </script>
                <?php }
                else{
                    ?>
                    <!--<div style="font-family: Roboto Slab, serif; background-color: #000;" class="alert alert-<?php // echo $_SESSION['error']['color']   ?> alert-dismissable fade in">-->
                <div style="font-family: Roboto Slab, serif; background-color: #000; font-size: 20px; color: #FFFFFF" class="alert  alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="Close popup alert" aria-live="polite">&times;</a>
                <?php echo $_SESSION['error']['message'] ?>
                </div>
            <?php }}
              $user_agent = getenv("HTTP_USER_AGENT");
              $os = "";
              if(strpos($user_agent, "Mac") !== FALSE)
             {  $os = "Mac"; }
           $login_aria_label ="Log in button";
           $custom_message ="";
           if (($browser->getBrowser() != Browser::BROWSER_CHROME || $browser->getBrowser() == Browser::BROWSER_CHROME &&  $os =="Mac")  && $browser->getBrowser() != Browser::BROWSER_SAFARI ) {
            //  if ($browser->getBrowser() != Browser::BROWSER_CHROME   && $browser->getBrowser() != Browser::BROWSER_SAFARI ) {
                     $iphone_device =0;
                     if($browser->getPlatform() == Browser::BROWSER_IPHONE || $browser->getPlatform() == Browser::BROWSER_IPAD || $browser->getPlatform() == Browser::BROWSER_IPOD){
                         $iphone_device =1;
                     }

                     if ($browser->getBrowser() != Browser::BROWSER_SAFARI && $os == 'Mac' && $iphone_device == 0) {
                                 $custom_message = "Note: The Safari browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Safari browser";
                                 $login_aria_label="Log in button. The Safari browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Safari browser";
                     }//else if ($browser->getBrowser() != Browser::BROWSER_SAFARI && $browser->getBrowser() == Browser::BROWSER_IPHONE && $browser->getBrowser() == Browser::BROWSER_IPAD && $browser->getBrowser() == Browser::BROWSER_IPOD) {
                     else if($iphone_device == 1 && $safari ==1 && ( $chrome == 1 || $ioschrome ==1 )){
                          $custom_message = "Note: The Safari browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Safari browser";
                          $login_aria_label="Log in button. The Safari browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Safari browser";
                     }
                      else if ($browser->getBrowser() != Browser::BROWSER_SAFARI && $iphone_device != 1 && $browser->getPlatform() !="Windows") {
                       $custom_message = "Note: The Safari browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Safari browser";
                       $login_aria_label="Log in button. The Safari browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Safari browser";

                     }else if($iphone_device != 1){
                       $custom_message= "Note: The Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Chrome browser";
                       $login_aria_label= "Log in button. The Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Chrome browser";

                     }
}
              //  else if($microsoft_edge == "1"){
                 //     $custom_message= "Note: The Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Chrome browser";
                   //    $login_aria_label= "Log in button. The Chrome browser is recommended. If you experience any issues, such as delayed app speech, please switch to the Chrome browser";
              //  }
          

   unset($_SESSION['error']); ?>
			
            
<!--            <div style="font-family: Roboto Slab, serif; background-color: #000; font-size: 20px; color: #FFFFFF" class="alert  alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="Close popup alert" aria-live="polite">&times;</a>
                This is where I type the popup notification text Click <a href="//www.accessibyte.com/accessibyte-online">here</a> for details.
            </div>-->           
        </div>
         <div class="login-box-body login-header  theme-page">
                <span class="logo-lg">
                    <div class="image">
                        <img src="<?php echo ADMIN_URL . 'img/accessibyte-logo-update.svg' ?>" class="accessibyte-logo center-block img-responsive" alt="Accessibyte Online logo">
                    </div>
                </span>
            </div>
        <div class="login-box">
            <!-- <div class="login-logo">&nbsp;</div> --> <!-- /.login-logo -->
           
            <div class="login-box-body  theme-page">
                <p class="login-box-msg">Log in to your account</p>
                <form action="<?php echo ADMIN_URL . 'login/Robot.php' ?>" method="post" id="login-form">
                    <div id="errorMessage" class="help-block help-block-error"></div>
                    <div class="form-group has-feedback ">
						<label>Username</label>
                        <input type="text" name="email" id="email" autofocus="" required="" class="form-control" aria-label="Username field" placeholder="Enter Username">
                    </div>
 <input type='hidden' name='sresolution' id='sresolution' value=''>
                    <div class="form-group has-feedback ">
						<label>Password</label>
                        <input type="password" name="password" required="" id="password" class="form-control" aria-label="Password field" placeholder="Password">
                    </div>
					<?php
                        if(isset($custom_message) && $custom_message !=""){
                    ?>
                    <div style="font-family: Roboto Slab, serif; font-size: 14px;" class="form-group has-feedback">
                        <span aria-label="<?php echo $custom_message;?>"><strong><?php echo $custom_message;?></strong></span>
                    </div>
                    <?php } ?>
                    <div class="row">
                        

                        <div class="col-md-12 col-xs-12" style="margin-top:20px;margin-bottom:20px">
                            <input type="hidden" id="log_type" class="ignore_wait1" name="log_type" value="" />
    				        <div id="keeplogin_div" style="display:none">
            					<input type="checkbox" id="keep_login" class="ignore_wait1" name="keep_login"  />
                                <label for="keep_login" style="margin-bottom:12px"> Keep me logged in</label> 
                            </div> 
                            <button type="submit" name="Sign In" aria-label="<?php echo $login_aria_label;?>" class="btn btn-primary btn-block btn-flat"/>Log In<i class="fa fa-long-arrow-right" aria-hidden="true"></i> </button>                      
                        </div>
<!-- /.col -->

			
						<div class="col-md-12 col-xs-12" style="
    margin-top: 20px;margin-bottom: 70px;
">

                            <a href="<?php echo ADMIN_URL . 'register.php'; ?>" aria-label="Register or update account button." class="btn btn-primary btn-block btn-flat btn-accessibyte">Register or Update Account</a>
                            <div class="terms_conditions">
                                <a href="<?php echo ADMIN_URL . 'forgot-pass.php'; ?>" class="accessibyte-link">Forgot Password?</a><br>

                            </div>
                        </div><!-- /.col -->
                    </div>
                </form>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-left">
                            <!-- Need an account? <a href="<?php //echo ADMIN_URL . 'registration.php';  ?>" class="accessibyte-link">Register here</a> -->
                        </div>

                    </div>
                </div>
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->
<?php }?>
        <!-- jQuery 2.1.4 -->
        <script src="<?php echo ADMIN_URL; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo ADMIN_URL; ?>bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="<?php echo ADMIN_URL; ?>plugins/iCheck/icheck.min.js"></script>
        <script src="<?php echo ADMIN_URL; ?>plugins/jQuery-validate/jquery.validate.js"></script>
        <script src="<?php echo ADMIN_URL; ?>dist/js/validate.js"></script>
        <script src="<?php echo ADMIN_URL; ?>dist/js/script.js"></script>
        <script type="module" async>
            function isInAndroidApp() {
              let userAgent = navigator.userAgent || navigator.vendor || window.opera;
            
              // Check for Android in the user agent and WebView identifiers
              if (/android/i.test(userAgent)) {
                // Detect WebView or other app-specific identifiers
                if (/wv|\.app|version/i.test(userAgent)) {
                  return true; // Likely inside an Android app's WebView
                }
              }
              return false; // Not in an Android app
            }

            function isWindowApplication() {
                //var isElectronApp = window && window.process && window.process.versions && window.process.versions.electron;
                var isElectronApp = !!(navigator.userAgent.toLowerCase().indexOf('electron') > -1);
                if (isElectronApp) { 
                    return true;
                } else { 
                    return false;
                }
            }
            let displayMode = 'browser';
            const mqStandAlone = '(display-mode: standalone)'; 
            if (navigator.standalone || window.matchMedia(mqStandAlone).matches || document.fullscreenElement || window.matchMedia('fullscreen').matches) {
                displayMode = 'PWA'; 
                $('#keeplogin_div').show();
            }
            if (isInAndroidApp()  || isWindowApplication()) { 
                displayMode = 'PWA';
                $('#keeplogin_div').show();
            }
          console.log('displaymode'+displayMode);
          $('#log_type').val(displayMode +' via '+'<?php echo $logtype ?>');
          var  cvalue = displayMode;
          const d = new Date();
          d.setTime(d.getTime() + (1*24*60*60*1000));
          let expires = "expires="+ d.toUTCString();
          document.cookie = 'logdata' + "=" + cvalue + ";" + expires ;
           
        </script>
        <script>
           /* $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
                var width = screen.width;
                var height = screen.height;
                console.log('resol : '+ width +' * '+ height);
                $('#sresolution').val(width+' * '+ height);
            });*/
        </script>
<style>
.footer {
border-top: 1px solid #d2d6de;
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: White;
  color: #000000;
  text-align: center;
padding: 10px 10px 0px;
margin-left:0px!important
}
</style>

<div class="main-footer footer  theme-page">
 <div class='col-md-12'><span style='align-items: center;'><strong style='margin-right:25px;'><a href=' https://www.accessibyte.com/terms-privacy/' target='_blank' style='font-size: medium;
    color: black;'><u class=" theme-page">Privacy Policy</u></a></strong><strong><a href=' https://www.accessibyte.com/terms-privacy/' target='_blank' style='font-size: medium;
    color: black;' ><u class=" theme-page">Acknowlegements</u></a></strong></span></div>
<div class='col-md-12' style="margin-top:10px"><p>&copy;2024 Accessibyte, LLC. Typio<sup>tm</sup> and Braillio<sup>tm</sup> are a trademark of Accessibyte, LLC.</p></div>
</div>
    </body>
</html>
<script>

$("#ignore_wait1").on("ifChecked", checval);
  
 function checval(){
var user = $('#userUID').val();

  $.ajax({

                    type: 'POST',

                    url: ADMIN_URL + 'config/config.php',

                    data: {'UID': user, 'type': 'update_wait','wait_val' : 'No'},

                    async: true,

                    cache: false,

                    timeout: 10000,

                    success: function (response) {

                        var response = $.parseJSON(response);
if(response.error == 1){
console.log(response.message);
}else{
alert(response.message);
}
                     console.log(response);

                    },

                    error: function (xhr, status, err) {

console.log('Something Went Wrong!!');
}

                });


}

$("#ignore_wait1").on("ifUnchecked", checval3);
  
 function checval3(){
var user = $('#userUID').val();

  $.ajax({

                    type: 'POST',

                    url: ADMIN_URL + 'config/config.php',

                    data: {'UID': user, 'type': 'update_wait','wait_val' : 'Yes'},

                    async: true,

                    cache: false,

                    timeout: 10000,

                    success: function (response) {

                        var response = $.parseJSON(response);

                     if(response.error == 1){
console.log(response.message);
}else{
alert(response.message);
}
                     console.log(response);

                    },

                    error: function (xhr, status, err) {

console.log('Something Went Wrong!!');
}

                });
}
$('.login_redirect ').click(function(){

var link = $('#login_link').val()+'&wait=No';
console.log(link);
location.href=link;






});
//function myFunction(value){
//console.log(value);
//$('#email').val(value.toLowerCase());


//}
</script>

