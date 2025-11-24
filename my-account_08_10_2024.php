<?php include "config/config.php"; ?>
<?php
if (!$_SESSION['User']) {
    header("Location: " . ADMIN_URL . 'login');
    exit;
}

$get_license_details = get_license_details_all($_SESSION['User']['license']);
$activation = isset($get_license_details['license_limit']) ? $get_license_details['license_limit'] - 1 : '';
$id = $_SESSION['User']['id'];
$args = array(
    'teacher_code' => !empty($_SESSION['User']) ? $_SESSION['User']['teacher'] : '',
    'role' => 'student',
);
$students_list = get_users($args);

$timezone = query('SELECT * FROM settings WHERE id = "' . $id . '" AND item = "555" ');
$timezoneName = '';
if ($timezone->num_rows > 0) {
    $timezone_data = fetch($timezone);
    $timezoneName = $timezone_data['variable'];
}
if (isset($_POST) && !empty($_POST)) {

    if (isset($_POST['Submit']) && $_POST['Submit'] == 'Activate New License') {
        extract($_POST);
        $error = 0;

        if (empty($license)) {
            $error = 1;
            $_SESSION['error']['message'] = 'License can not leave empty';
            $_SESSION['error']['color'] = 'danger';
        }
        if ($error == 0) {

            $username = $_SESSION['User']['username'];
            $username = escapeString($username);
            update_license_my_account($username, trim($license));
        }
    } else if (isset($_POST['Submit']) && $_POST['Submit'] == 'Update Timezone') {
        extract($_POST);
        $error = 0;

        if (empty($timezone)) {
            $error = 1;
            $_SESSION['error']['message'] = 'Please select timezone';
            $_SESSION['error']['color'] = 'danger';
        }

        if ($error == 0) {


            update_timezone(trim($timezone), $id);
        }
    } else if (isset($_POST['Submit']) && $_POST['Submit'] == 'Lock License') {
        $lockval = 0;
        if(isset($_POST['is_lock'])){
            $lockval = '1';
        }
            $license = $_SESSION['User']['license'];
            update_license_lock(trim($license),$lockval);

    }
}
$is_expired = check_expire_or_not();
$is_lock = check_license_lock($_SESSION['User']['license']);

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

                                <h3>My Account</h3>

                            </div>

                        </div>
                        <div class="col-md-12">

                            <div class="box-body panel my-account-main-init my-account-main-init-main">

                                <div class="typio-sidebar-alert alert alert-dismissible" aria-live="assertive"></div>
                                <div class="col-md-12 row errorView">

                                    <?php
                                    if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
                                        ?>
                                        <div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">
                                            <h4><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                <?php echo $_SESSION['error']['message'] ?></h4>
                                        </div>
                                        <?php
                                    }
                                    unset($_SESSION['error']);
                                    ?>
                                </div>
                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="">

                                            <div class="with-border">

                                                <h3 class="box-title"><b><?php
                                                        if (isset($_SESSION['User'])) {
                                                            echo base64_decode($_SESSION['User']['firstname']) . ' ' . base64_decode($_SESSION['User']['lastname']);
                                                        }
                                                        ?></b></h3>
                                                <h3 class="user-info-sub-rock"><?php echo base64_decode($_SESSION['User']['organization']); ?></h3>
                                            </div>



                                        </div>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="account-lbl">
                                            <h3>Username :</h3>
                                            <p><?php echo base64_decode($_SESSION['User']['username']); ?></p>
                                        </div>

                                        <?php
                                        //$users_licenses_details = users_license_list($_SESSION['User']['id']);
										?>
                                        <div class="account-lbl">
                                            <h3>License :</h3>
                                            <p><input type="hidden" id="licenseid">
                                                <span class="hide_encrypt">***************</span>
                                                <span class="view_encrypt" style="display:none;">
                                                    <?php echo $_SESSION['User']['license']; ?></span>&nbsp;
                                                <span class="licenseChk"><label for="checkval" onclick="myFunction()" class="checkid"><i class="fa fa-eye" aria-hidden="true"></i> show<label>
                                                            </span></p>
                                                            <p>
                                                                <?php

                                                                $no_of_licence = get_license_data($_SESSION['User']['license']);
                                                                if (!empty($no_of_licence)) {
                                                                    $use = $no_of_licence['no_teacher_use'] ? $no_of_licence['no_teacher_use'] : '';
                                                                    $no_teacher = $no_of_licence['no_teacher'];
                                                                    echo $use." of ".$no_teacher." Teacher Dashboard seats in use.";
                                                                }
                                                                ?>
                                                            </p>
                                                            </div>
                                                            <div class="account-lbl">
                                                                <h3>Expiration :</h3>
                                                                <p><?php
																/* new code for updated license expiration  date */
																$dateDiff = 0;
																if($get_license_details['license_status'] == 'expired'){
																	echo '(Expired)';
																} else if ($get_license_details['license_status'] == 'valid' && $get_license_details['expires'] == 'lifetime'){
																	echo '(Lifetime)';
																} else {
                                                                    //echo $get_license_details['expires'];
																	$date1_ts = strtotime(date('Y-m-d'));
																	$date2_ts = strtotime(date('Y-m-d', strtotime($get_license_details['expires'])));
																	$diff = $date2_ts - $date1_ts;
																	$dateDiff = round($diff / 86400);
																	echo date("m-d-Y", strtotime($get_license_details['expires'])) . " ";
																	echo '(' . $dateDiff . ' days remaining)';
																}

                                                                //$_SESSION['User']['license'] = $users_licenses_details['0']['license'];
                                                                ?>
                                                                </p>
                                                            </div>

                                                            <?php
                                                            $display = false;
                                                            $ismulti = 0;
                                                            if(empty($is_lock)){
                                                                $display = true;
                                                                $ismulti = $no_of_licence['no_teacher'];
                                                            }
                                                            if(isset($_SESSION['User']['is_admin']) && !empty($_SESSION['User']['is_admin']) || $display){ ?>
                                                                <div class="account-lbl account-btn">
                                                                    <form action="" method="POST" id="update_license_frm" class="margin-bottom">
                                                                        <input type="hidden" value="<?php echo count($students_list); ?>" id="number_of_students" />
                                                                        <input type="hidden" value="<?php echo $activation; ?>" id="old_activation" />
                                                                        <!-- <h3>Update License :</h3>
                                                                        <div class="form-group">
                                                                            <input type="text" name="license" required="" id="edd_license" class="form-control" placeholder="License" autocomplete="off" aria-label="License field">
                                                                            <span id="update_lic_spinner" style="display: none; font-size: 6px;" ><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="checkbox" id="agree_term" value="1" name="agree_term"/> <label for='agree_term'> I agree to Accessibyte's </label> <a href="<?php echo WEB_PATH; ?>terms-privacy" target="_blank">terms and privacy</a>
                                                                        </div> -->
                                                                        <div class="form-group">
                                                                            <button type="button" class="dashboard-settings-btn btn-block" onclick="window.location.href='<?php echo ADMIN_URL . 'renew-license.php'; ?>'">
                                                                                Renew License
                                                                            </button>
                                                                            <!--<a href="<?php //echo ADMIN_URL . "renew-license.php"; ?>" class="dashboard-settings-btn btn-block">Renew License</a>-->
                                                                        </div>
                                                                        <div class="form-group-btn-wrap">
                                                                            <div class="form-group-btn-col">
                                                                                <?php if($dateDiff < 14) { ?>
                                                                                    <button type="button" class="dashboard-settings-btn btn-block" onclick="return alert('You need to renew this license before adding additional student seats.')">
                                                                                        Add Seats to License
                                                                                    </button>
                                                                                <!--<a href="javascript:void(0);" onclick="return alert('You need to renew this license before adding additional student seats.')" class="dashboard-settings-btn btn-block">Add Seats to License</a>-->
                                                                                <?php } else { ?>
                                                                                    <button type="button" class="dashboard-settings-btn btn-block" onclick="window.location.href='<?php echo ADMIN_URL . 'add-seats.php'; ?>'">
                                                                                        Add Seats to License
                                                                                    </button>
                                                                                <!--<a href="<?php //echo ADMIN_URL . "add-seats.php"; ?>" class="dashboard-settings-btn btn-block" >Add Seats to License</a>-->
                                                                                <?php } ?>
                                                                            </div>
                                                                            <div class="form-group-btn-col">
                                                                                <!--<input type="<?php echo ($ismulti == 1) ? 'button' : 'submit';?>" value="Update to New License" name="Submit" class="dashboard-settings-btn btn-block" id="<?php echo ($ismulti == 1) ? 'update-license-btn' : '';?>" />-->
                                                                                <button type="button" class="dashboard-settings-btn btn-block" onclick="window.location.href='https://online.accessibyte.com/update-license.php'">
                                                                                    Update to New License
                                                                                </button>
                                                                                <!--<a href="https://online.accessibyte.com/update-license.php " class="dashboard-settings-btn btn-block">Update to New License</a>-->
                                                                                <input type="hidden" value="Update to New License" name="Submit" />       
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            <?php }?>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="account-lbl account-btn">
                                                                    <form action="" method="POST" id="update_timezone_frm" class="margin-bottom">
                                                                        <h3>Select Timezone :</h3>
                                                                        <?php
                                                                        $timezone_arr = array(
                                                                                        '(UTC-12:00) International Date Line West'                      => 'Pacific/Wake',
                                                                                        '(UTC-11:00) Coordinated Universal Time'                        => 'Coordinated Universal Time',
                                                                                        '(UTC-10:00) Hawaii'                                            => 'US/Hawaii',
                                                                                        '(UTC-09:00) Alaska'                                            => 'US/Alaska',
                                                                                        '(UTC-08:00) Baja California'                                   => 'America/Tijuana',
                                                                                        '(UTC-08:00) Pacific Time (US & Canada)'                        => 'US/Pacific',
                                                                                        '(UTC-07:00) Arizona'                                           => 'US/Arizona',
                                                                                        '(UTC-07:00) Chihuahua, La Paz, Mazatlan'                       => 'America/Mazatlan',
                                                                                        '(UTC-07:00) Mountain Time (US & Canada)'                       => 'US/Mountain',
                                                                                        '(UTC-06:00) Central America'                                   => 'America/Managua',
                                                                                        '(UTC-06:00) Central Time (US & Canada) '                       => 'US/Central',
                                                                                        '(UTC-06:00) Guadalajara Mexico City, Monterrey'                => 'America/Monterrey',
                                                                                        '(UTC-06:00) Saskatchewan'                                      => 'Canada/Saskatchewan',
                                                                                        '(UTC-05:00) Bogota, Lima, Quito'                               => 'America/Lima',
                                                                                        '(UTC-05:00) Eastern Time (US & Canada)'                        => 'US/Eastern',
                                                                                        '(UTC-05:00) Indiana (East)'                                    => 'US/East-Indiana',
                                                                                        '(UTC-04:30) Caracas'                                           => 'America/Caracas',
                                                                                        '(UTC-04:00) Asuncion'                                          => 'America/Asuncion',
                                                                                        '(UTC-04:00) Atlantic Time (Canada)'                            => 'Canada/Atlantic',
                                                                                        '(UTC-04:00) Cuiaba'                                            => 'America/Campo_Grande',
                                                                                        '(UTC-04:00) Georgetown, La Paz, Manaus, San Juan'              => 'America/La_Paz',
                                                                                        '(UTC-04:00) Santiago'                                          => 'America/Santiago',
                                                                                        '(UTC-03:30) Newfounland'                                       => 'America/St_Johns',
                                                                                        '(UTC-03:00) Brasilia'                                          => 'America/Sao_Paulo',
                                                                                        '(UTC-03:00) Buenis Aires'                                      => 'America/Argentina/Buenos_Aires',
                                                                                        '(UTC-03:00) Cayenne, Fortaleza'                                => 'America/Araguaina',
                                                                                        '(UTC-03:00) Greenland'                                         => 'America/Godthab',
                                                                                        '(UTC-03:00) Montevideo'                                        => 'America/Montevideo',
                                                                                        '(UTC-03:00) Salvador'                                          => 'America/Bahia',
                                                                                        '(UTC-02:00) Coordinated Universal Time-02'                     => 'Coordinated Universal Time-02',
                                                                                        '(UTC-01:00) Azores'                                            => 'Atlantic/Azores',
                                                                                        '(UTC-01:00) Cape Verde ls'                                     => 'Atlantic/Cape_Verde"',
                                                                                        '(UTC) Casablanca'                                              => 'Africa/Casablanca',
                                                                                        '(UTC) Coordinated Universal Time'                              => 'Coordinated Universal Time',
                                                                                        '(UTC) Dublin, Edinburgh, Lisbon, London'                       => 'Europe/London',
                                                                                        '(UTC) Monrovia, Reykjavik'                                     => 'Africa/Casablanca',
                                                                                        '(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'  => 'Europe/Amsterdam',
                                                                                        '(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague' => 'Europe/Belgrade',
                                                                                        '(UTC+01:00) Brussels, Copenhagen, Madrid, Paris'               => 'Europe/Brussels',
                                                                                        '(UTC+01:00) Sarajevo, Skopje, Warasaw, Zagreb'                 => 'Europe/Sarajevo',
                                                                                        '(UTC+01:00) Brussels, Copenhagen, Madrid, Paris'               => 'Europe/Brussels',
                                                                                        '(UTC+01:00) Tripoli'                                           => 'Africa/Tripoli',
                                                                                        '(UTC+01:00) West Central Africa'                               => 'Africa/Lagos',
                                                                                        '(UTC+01:00) windhoek'                                          => 'Africa/Windhoek',
                                                                                        '(UTC+02:00) Athens, Bucharest'                                 => 'Europe/Athens',
                                                                                        '(UTC+02:00) Beriut'                                            => 'Asia/Beirut',
                                                                                        '(UTC+02:00) Cairo'                                             => 'Africa/Cairo',
                                                                                        '(UTC+02:00) Damascus'                                          => 'Asia/Damascus',
                                                                                        '(UTC+02:00) E. Europe'                                         => 'Europe/Bucharest',
                                                                                        '(UTC+02:00) Harare, Pretoria'                                  => 'Africa/Harare',
                                                                                        '(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, vilnius'     => 'Europe/Helsinki',
                                                                                        '(UTC+02:00) Istanbul'                                          => 'Europe/Istanbul',
                                                                                        '(UTC+02:00) Jerusalem'                                         => 'Asia/Jerusalem',
                                                                                        '(UTC+03:00) Amman'                                             => 'Asia/Amman',
                                                                                        '(UTC+03:00) Baghdad'                                           => 'Asia/Baghdad',
                                                                                        '(UTC+03:00) Kaliningrad, Minsk'                                => 'Africa/Addis_Ababa',
                                                                                        '(UTC+03:00) Kuwait, Riyadh'                                    => 'Asia/Kuwait',
                                                                                        '(UTC+03:00) Nairobi'                                           => 'Africa/Nairobi',
                                                                                        '(UTC+03:00) Tehran'                                            => 'Asia/Tehran',
                                                                                        '(UTC+04:00) Abu Dhabi, Muscat'                                 => 'Asia/Muscat',
                                                                                        '(UTC+04:00) Baku'                                              => 'Asia/Baku',
                                                                                        '(UTC+04:00) Moscow, St. Petersburg, Volgograd'                 => 'Europe/Volgograd',
                                                                                        '(UTC+04:00) Port Louis'                                        => 'Indian/Mauritius',
                                                                                        '(UTC+04:00) Tbilisi'                                           => 'Asia/Tbilisi',
                                                                                        '(UTC+04:00) Yerevan'                                           => 'Asia/Yerevan',
                                                                                        '(UTC+04:30) Kabul'                                             => 'Asia/Kabul',
                                                                                        '(UTC+05:00) Ashgabat, Tashkent'                                => 'Asia/Karachi',
                                                                                        '(UTC+05:00) Islamabad, Karachi'                                => 'Asia/Karachi',
                                                                                        '(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi'               => 'Asia/Calcutta',
                                                                                        '(UTC+05:30) Sri Jayawardenepura'                               => 'Asia/Colombo',
                                                                                        '(UTC+05:45) Kathmandu'                                         => 'Asia/Kathmandu',
                                                                                        '(UTC+06:00) Astana'                                            => 'Asia/Dhaka',
                                                                                        '(UTC+06:00) Dhaka'                                             => 'Asia/Dhaka',
                                                                                        '(UTC+06:00) Ekaterinburg'                                      => 'Asia/Yekaterinburg',
                                                                                        '(UTC+06:30) Yangon (Rangoon)'                                  => 'Asia/Rangoon',
                                                                                        '(UTC+07:00) Bangkok, Hanoi, Jakarta'                           => 'Asia/Bangkok',
                                                                                        '(UTC+07:00) Novosibirsk'                                       => 'Asia/Novosibirsk',
                                                                                        '(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi'             => 'Asia/Hong_Kong',
                                                                                        '(UTC+08:00) Krasnoyarsk'                                       => 'Asia/Krasnoyarsk',
                                                                                        '(UTC+08:00) Kuala Lumpur, Singapore'                           => 'Asia/Singapore',
                                                                                        '(UTC+08:00) Perth'                                             => 'Australia/Perth',
                                                                                        '(UTC+08:00) Taipei'                                            => 'Asia/Taipei',
                                                                                        '(UTC+08:00) Ulaanbaatar'                                       => 'Asia/Ulaanbaatar',
                                                                                        '(UTC+09:00) Irkutsk'                                           => 'Asia/Irkutsk',
                                                                                        '(UTC+09:00) Osaka, Sapporo, Tokyo'                             => 'Asia/Tokyo',
                                                                                        '(UTC+09:00) Sqqul'                                             => 'Sqqul',
                                                                                        '(UTC+09:30) Adelaide'                                          => 'Australia/Adelaide',
                                                                                        '(UTC+10:00) Darwin'                                            => 'Australia/Darwin',
                                                                                        '(UTC+10:00) Brisbane'                                          => 'Australia/Brisbane',
                                                                                        '(UTC+10:00) Canberra, Melbourne, Sydney'                       => 'Australia/Sydney',
                                                                                        '(UTC+10:00) Guam, Port Moresby'                                => 'Pacific/Guam',
                                                                                        '(UTC+10:00) Hobart'                                            => 'Australia/Hobart',
                                                                                        '(UTC+10:00) Yakutsk'                                           => 'Asia/Yakutsk',
                                                                                        '(UTC+11:00) Soloman Ls, New Caledonia'                         => 'Asia/Magadan',
                                                                                        '(UTC+11:00) Vladivostok'                                       => 'Asia/Vladivostok',
                                                                                        '(UTC+12:00) Auckland, Wellington'                              => 'Pacific/Auckland',
                                                                                        '(UTC+12:00) Coordinated Universal Time+12'                     => 'Coordinated Universal Time+12',
                                                                                        '(UTC+12:00) Fiji'                                              => 'Pacific/Fiji',
                                                                                        '(UTC+12:00) Magadan'                                           => 'Asia/Magadan',
                                                                                        '(UTC+13:00) Nukualofa'                                         => 'Pacific/Tongatapu',
                                                                                        '(UTC+13:00) Samoa'                                             => 'Pacific/Apia',
                                                                                        '(UTC+14.00) Kiritimati Island'                                 => 'Pacific/Kiritimati',
                                                                                    );
                                                                                    ?>
                                                                        <p>
                                                                            <select name="timezone" id="timezone_setting" class="form-control">
                                                                                <option value="">Select Timezone</option>
                                                                                <?php
                                                                               /* $allTimezone = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                                                                                if (!empty($allTimezone)) {
                                                                                    foreach ($allTimezone as $key => $val) {
                                                                                        $selected = "";
                                                                                        if (!empty($timezoneName) && $timezoneName == $val) {
                                                                                            $selected = "selected";
                                                                                        }
                                                                                        echo "<option value=" . $val . " " . $selected . ">" . toUtcOffset($val) . ' ' . $val . "</option>";
                                                                                    }
                                                                                }*/
                                                                                if (!empty($timezone_arr)) {
                                                                                    foreach ($timezone_arr as $key => $val) {
                                                                                        $selected = "";
                                                                                        if (!empty($timezoneName) && $timezoneName == $val) {
                                                                                            $selected = "selected";
                                                                                        }
                                                                                        echo "<option value='" . $val . "' " . $selected . ">" . $key . "</option>";
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </p>
                                                                        <div class="form-group" style="margin-top:10px;">
                                                                            <input type="submit" value="Update Timezone" name="Submit" class="dashboard-settings-btn btn-block" />
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <?php if(isset($_SESSION['User']['is_admin']) && !empty($_SESSION['User']['is_admin'])){ ?>
                                                                    <div class="account-lbl">
                                                                        <form action="" method="POST" id="lock_license_frm" class="margin-bottom">
                                                                            <h3>Lock license :</h3>
                                                                            <div class="lock_license_frm_checkbox">
                                                                                <input type='checkbox' name='is_lock' id='is_lock' value='1' <?php echo ($is_lock == '1') ? 'checked' : '';?>>
                                                                                <label for="is_lock">Check this box to prevent teachers from applying this license to their existing accounts.</label>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <input type="submit" value="Lock License" name="Submit" class="dashboard-settings-btn btn-block" id="lock-license-btn" />
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                <?php }?>
																<!-- <div class="extra_btn">
                                                                    <?php if($dateDiff < 14){?>
                                                                    <a href="javascript:void(0);" onclick="return alert('You need to renew this license before adding additional student seats.')" class="dashboard-settings-btn btn-block">Add Seats to License</a>    
                                                                    <?php } else {?>
                                                                    <a href="<?php //echo ADMIN_URL . "add-seats.php"; ?>" class="dashboard-settings-btn btn-block" >Add Seats to License</a>
                                                                    <?php }?>
                                                                    <a href="<?php //echo ADMIN_URL . "renew-license.php"; ?>" class="dashboard-settings-btn btn-block">Renew License</a>
                                                                </div> -->
                                                            </div>

                                                            </div>
                                                            <div class="row">

                                                                <div class="space-margin-bottom-50"></div>

                                                            </div>

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

                                                            <script>
                                                                function myFunction() {
                                                                    var x = document.getElementById("licenseid");
                                                                    if (x.type === "hidden") {
                                                                        x.type = "text";
                                                                        $('#licenseid').hide();
                                                                        $('.checkid').html('<i class="fa fa-eye-slash" aria-hidden="true"></i> Hide');
                                                                        $('.view_encrypt').show();
                                                                        $('.hide_encrypt').hide();
                                                                    } else {
                                                                        x.type = "hidden";
                                                                        $('.checkid').html('<i class="fa fa-eye" aria-hidden="true"></i> Show');
                                                                        $('.hide_encrypt').show();
                                                                        $('.view_encrypt').hide();
                                                                    }
                                                                }

                                                            </script>
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
                                                                $(document).on('click', '#update-license-btn', function () {

                                                                    var license_key = $('#edd_license').val().trim();
                                                                    var number_of_students = $('#number_of_students').val();
                                                                    var old_activation_limit = $('#old_activation').val();
                                                                    if ($("#agree_term").prop('checked') == true) {
                                                                        var msg = '';

                                                                        if (license_key) {

                                                                            $.ajax({
                                                                                url: '<?php echo ADMIN_URL; ?>common.php',
                                                                                type: 'POST',
                                                                                dataType: 'json',
                                                                                data: {licenseCheck: 'license_check', license_key: license_key},
                                                                                beforeSend: function () {
                                                                                    $('#update_lic_spinner').show();
                                                                                },
                                                                                success: function (result) {

                                                                                    if (result.license_status != 'none') {
                                                                                        if (result.license_status == 'valid' || result.license_status == 'inactive') {

                                                                                            var left = parseInt(result.activations_left);
                                                                                            var total_activation = parseInt(number_of_students) + 1;

                                                                                            var get_diff = total_activation - left;
                                                                                            var seat_avail = left - 1;
                                                                                            console.log(left);
                                                                                            if (left > 0) {
                                                                                                if (parseInt(result.license_limit) > 0 && (parseInt(left) < parseInt(total_activation))) {
                                                                                                    /*var get_diff = parseInt(result.license_limit) - parseInt(number_of_students);*/
                                                                                                    /*if(get_diff > 0){*/
                                                                                                    /*msg = 'Not enough seats on this license. You have '+number_of_students+' students and this license has '+result.license_limit+' seats. Please delete '+get_diff+' students before applying the license.';*/
                                                                                                    /*msg = 'Not enough seats on this license. You have have '+number_of_students+' seats available. Please delete '+get_diff+' students before applying the license.';*/
                                                                                                    msg = 'Not enough seats on this license. You have have ' + seat_avail + ' seats available. Please delete ' + get_diff + ' students before applying the license or purchase additional license seats.';
                                                                                                    /*}else{
                                                                                                     $('#update_license_frm').submit();
                                                                                                     /*}*/
                                                                                                } else {

                                                                                                    $('#update_license_frm').submit();
                                                                                                }
                                                                                            } else {
                                                                                                msg = 'You have have 0 activation left on this license.';
                                                                                            }
                                                                                        } else {
                                                                                            msg = 'Your license key is ' + result.license_status;
                                                                                        }
                                                                                    } else {
                                                                                        msg = 'Your license key is Invalid';
                                                                                    }

                                                                                    if (msg) {
                                                                                        var erroMassage = '<div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">\n\
                                                                                                            <h4><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                                                                            ' + msg + '\n\
                                                                                                        </div>';
                                                                                        $('.errorView').html(erroMassage);
                                                                                    }
                                                                                },
                                                                                complete: function () {
                                                                                    $('#update_lic_spinner').hide();
                                                                                }
                                                                            });
                                                                        } else {
                                                                            msg = "Please enter license key.";
                                                                            var erroMassage = '<div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">\n\
                                                                                                            <h4><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                                                                            ' + msg + '\n\
                                                                                                        </div>';
                                                                            $('.errorView').html(erroMassage);
                                                                        }
                                                                    } else {
                                                                        msg = "Please agree terms and privacy policy.";
                                                                        var erroMassage = '<div style="font-family: Roboto Slab, serif; background-color: #000; color: #FFFFFF" class="alert  alert-dismissable fade in">\n\
                                                                                                            <h4><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n\
                                                                                                            ' + msg + '\n\
                                                                                                        </div>';
                                                                        $('.errorView').html(erroMassage);
                                                                    }
                                                                });
                                                            </script>

                                                            </body>
                                                            </html>
