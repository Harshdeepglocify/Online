$(document).ready(function () {
    var message = '';

    jQuery.validator.addMethod("licCheck", function (value, element) {
        var split_lic = $('#edd_license').val();
        if (split_lic != '') {
            var str_split = split_lic.split('-');
            if (str_split[0].trim() == 'TY') {
                return false;
            } else {
                return true;
            }
        }
    }, "Please enter valid License key.");

    $("#active-license-form").validate({
        rules: {
            license: {
                required: true,
                remote: {
                    url: ADMIN_URL + 'config/validate-license.php',
                    type: 'POST',
                    data: {
                        license: function () {
                            return $("#edd_license").val();
                        }
                    },
                    dataFilter: function (response) {
//                        response = $.parseJSON(response);
                        if (response == 'true') {
                            return true;
                        } else {
                            message = response;
                            return false;
                        }
                    }
                },
                licCheck: true
                }
        },
        messages: {
            license: {
                required: "License is required",
                remote: function () {
                    return message;
                }
//                remote: "This License is expired or already activated."
            }
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");
            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents(".form-group").addClass("has-error");
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                //$("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }
        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                //$("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
            //$(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass("has-error");
            //$(element).parents(".form-group").addClass("has-success").removeClass("has-error");
        }
    });
});

$(function () {
    //Initialize Select2 Elements
    //$('.select2').select2();

    $(document).on('keyup', '#add_new_license', function () {

        if ($(this).val()) {
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/check_license_key_available.php',
                data: {license: $(this).val()},
                beforeSend: function () {
                    $('.spinner_loader').show();
                    $('#license_error').text('');
                    $('.add-new-license-btn').prop('disabled', true);

                },
                success: function (response) {
                    $('.spinner_loader').hide();
                    $('.add-new-license-btn').removeAttr('disabled');

                    if (response == 'Already exists') {
                        $('#license_error').text('License key already exists');
                        $('.add-new-license-btn').attr('disabled', 'disabled');
                    }
                    if (response == 'License key not found') {
                        $('#license_error').text('License key not found. please check license key.');
                        $('.add-new-license-btn').attr('disabled', 'disabled');
                    }

                    if (response == 'required') {
                        $('#license_error').text('License key is required');
                        $('.add-new-license-btn').attr('disabled', 'disabled');
                    }

                    if (response == 'License key Invalid') {
                        $('#license_error').text('License key is Invalid');
                        $('.add-new-license-btn').attr('disabled', 'disabled');
                    }

                    if (response == 'success') {
                        $('.add-new-license-btn').removeAttr('disabled');
                    }
                }

            });
        } else {
            $('.add-new-license-btn').attr('disabled', 'disabled');
        }
    });

    $(document).on('click', '.add-new-license-btn', function () {
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/check_license_key_available.php',
            data: {input_license: $('#add_new_license').val()},
            beforeSend: function () {
                $('.spinner_loader').show();
                $('#license_error').text('');
            },
            success: function (response) {
                $('.spinner_loader').hide();

                if (response == 'success') {
                    $('#add-new-license-modal').modal('hide');
                    $('#license_error').text('');
                    location.reload();
                }
            }
        });
    });

    if ($('.datepicker').length > 0) {
        //Date picker
        $('.datepicker').datepicker({
            autoclose: true,
            orientation: "bottom"
        });
    }

    //Delete record
    $(document).on('click', '.delete_student', function () {
        var this_data = $(this);
        var first_name = this_data.attr('data-name');
        bootbox.confirm({
//            message: "Are you sure you want to remove <b>" + first_name + "</b> from your dashboard ?",
            message: "<b>Are you sure you want to delete " + first_name + "? </b><br><span style='color:red; font-weight:bold;'>All data will be permanently deleted. This cannot be undone.</span>",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'dashboard-settings-btn'
                },
                cancel: {
                    label: 'No',
                    className: 'dashboard-settings-btn'
                }
            },
            callback: function (result) {
                if (result) {
                    var delete_id = this_data.attr('data-id');
                    var action = 'delete_student_account';
                    $.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/config-student.php',
                        data: {'student_delete_id': delete_id, 'action': action},
                        async: true,
                        cache: false,
                        timeout: 10000,
                        success: function (response) {
                            var response = $.parseJSON(response);
                            this_data.closest("tr").remove();

                            if (response.status == true) {
                                $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                            } else {
                                $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                            }

                            setTimeout(function () {
                                $('.alert-msg-response').delay(5000).fadeOut(1000);
                                location.reload();
                            }, 2000);


                        }
                    });
                }
            }
        });
    });

    //Show License modal popup
    $(document).on('click', '.active-license-modal', function () {

        var user_id = $(this).attr('data-user_id');
        //var type = 'student-license-updating';
        $('#active-license-modal').find('.modal-body #user_id').val(user_id);
        $('#active-license-modal').modal('show');
        /*$.ajax({
         type: 'POST',
         url: ADMIN_URL + 'config/config-student.php',
         data: {'user_id': user_id, type: type},
         async: true,
         cache: false,
         timeout: 10000,
         success: function (response) {
         var response = $.parseJSON(response);
         if (response.status == '200') {
         $('#active-license-modal').removeClass('modal-success modal-warning');
         $('#active-license-modal').find('.edit-lessons-btn').show();
         $('#active-license-modal').find('.modal-body').html(response.html);
         }
         },
         error: function (xhr, status, err) {
         
         }
         });*/

    });

    $(document).on('click', '.one-click-login', function () {
        var this_data = $(this);
        var first_name = this_data.attr('data-name');

        inputID = $(this).attr('data-URL');
        $('#' + inputID).parent().show();
        var input = document.getElementById(inputID);
        var input_value =$("#"+inputID).val();
        var isiOSDevice = navigator.userAgent.match(/ipad|iphone/i);
        setTimeout(function () {
            //$('.bootbox-body').find('.urlcls').select();
			$('.bootbox-close-button').attr('aria-label','close pop-up');
			$('.closebtn').attr('aria-label','close pop-up');
            $('.bootbox-close-button').focus();
			$('.urlcls').attr('aria-label','You can paste this link…');
        }, 500);
		
		
        $('#' + inputID).focus();
		$('#' + inputID).select();
        var login_url = $('#' + inputID).val();
        bootbox.confirm({
            message: "You can paste this link where needed. But be careful! Anyone can log in to the student's account using this link. Treat it like a password and share it carefully.<br/><input type='text' class='form-control urlcls' value='"+login_url+"'>",
            buttons: {
                cancel: {
                    label: 'Close',
                    className: 'dashboard-settings-btn closebtn'
                },
                confirm: {
                    label: 'Copy Link',
                    className: 'dashboard-settings-btn'
                }               
            },
            callback: function (result) {
                $('.urlcls').select();
                if (result) {
                    if (isiOSDevice ) {
                        iosCopyToClipboard(login_url);                      
                    }else{
                      
                        document.execCommand("copy");
                    }

                    /*this_data.parent().append('<div class="wt-fade-message">Copied</div>');*/
                    $(".wt-fade-message").fadeOut(3000, function () {
                        $('.wt-fade-message').remove();
                    });
                }
                $('#' + inputID).parent().hide();
            }
        });
    });
	
	//Delete multiple student record
    $(document).on('click', '.delete_multi_student_btn', function () {
        var this_data = $(this);
        var text_data = $('.delete_multi_student_text').val();
        var delete_id = [];
        if (text_data == "") {
            $('.delete_multi_student_text').focus();
            return false;
        }
        $("input[name='ids[]']:checked").each(function () {
            delete_id.push($(this).val());
        });    
        if (text_data.toLowerCase() == 'delete') {
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'student_delete_id': delete_id, 'action': 'delete_multi_student_account'},
                async: true,
                cache: false,
                timeout: 10000,
				beforeSend: function () {
                    $('.spinner_loader').show();                
                },
                success: function (response) {
					$('.spinner_loader').hide();             
                    var response = $.parseJSON(response);
                    $('#confirm-delete').modal('hide');
                    if (response.status == true) {
                        $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    } else {
                        $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    }
                    
                    setTimeout(function () {
                        $('.alert-msg-response').delay(5000).fadeOut(1000);
                        window.location.reload();
                    }, 2000);


                }
            });
        } else {        
            $('.delete_multi_student_text').focus();
        }

    });
    //update multiple student password
    $(document).on('click', '.update_multi_student_pass_btn', function () {
        var pass = $('.password').val();
        var password_confirm = $('.password_confirm').val();
        var student_id = [];
        $('.error').html('');
        if (pass == ""){
            $('.password').focus();
            $('.error').html('Please enter Password');
            return false;
        }
        if (password_confirm == "") {            
            $('.password_confirm').focus();
            $('.error').html('Please enter confirm Password');
            return false;
        }
        if (pass != password_confirm) {
            $('.error').html('Password does not match');
            return false;
        }

        $("input[name='ids[]']:checked").each(function () {
            student_id.push($(this).val());
        });    
        
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {'student_id': student_id,'password':pass, 'action': 'update_multi_student_password'},
            async: true,
            cache: false,
            timeout: 10000,
			beforeSend: function () {
				$('.spinner_loader').show();                
			},
            success: function (response) {
				$('.spinner_loader').hide();             
                var response = $.parseJSON(response);
                $('#update-password').modal('hide');
                if (response.status == true) {
                    $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                } else {
                    $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                }
                
                setTimeout(function () {
                    $('.alert-msg-response').delay(5000).fadeOut(1000);
                    window.location.reload();
                }, 2000);


            }
        });       
    });
    //Assign selected student to teacher
    $(document).on('click', '.assign_student_btn', function () {
        var teacher_code = $('.teacher_list').val();
        var student_id = [];
        if (teacher_code == ""){
            $('.teacher_list').focus();
            return false;
        }
        $("input[name='ids[]']:checked").each(function () {
            student_id.push($(this).val());
        });    
        
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {'student_id': student_id,'teacher_code':teacher_code, 'action': 'assign_student_to_teacher'},
            async: true,
            cache: false,
            timeout: 10000,
			beforeSend: function () {
				$('.spinner_loader').show();                
			},
            success: function (response) {
				$('.spinner_loader').hide();             
                var response = $.parseJSON(response);
                $('#reassign-student').modal('hide');
                if (response.status == true) {
                    $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                } else {
                    $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                }
                
                setTimeout(function () {
                    $('.alert-msg-response').delay(5000).fadeOut(1000);
                    window.location.reload();
                }, 2000);


            }
        });       
    });
    //Add new student to selected teacher
    $(document).on('click', '.add_student_btn', function () {
        var teacher_code = $('#add-student-form').find('.teacher_list').val();
        var firstname = $('.firstname').val();
        var uname = $('.uname').val();
        var password = $('#add-student-form').find('.password').val();
        console.log(password);
        $('.commonerror').html('');
        if (teacher_code == ""){
            $('.teacher_list').focus();
            $('.teachername').html('Please select teacher');
        }
        if (firstname == ""){
            $('.firstname').focus();
            $('.nickname').html('Please enter nickname');
        }
        if (uname == ""){
            $('.uname').focus();
            $('.uname').html('Please enter username');
        }
        if (password == ""){
            $('.password').focus();
            $('.password').html('Please enter password');            
        }
        if(teacher_code == "" || firstname == "" || uname == "" || password == ""){
            return false;
        }
        formdata = $('#add-student-form').serialize();
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {form_data:formdata, 'action': 'add_student_to_teacher'},
            async: true,
            cache: false,
            timeout: 10000,
			beforeSend: function () {
				$('.spinner_loader').show();                
			},
            success: function (response) {
				$('.spinner_loader').hide();             
                var response = $.parseJSON(response);
                $('#add-student-model').modal('hide');
                if (response.status == true) {
                    $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                } else {
                    $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                }
                
                //$('#admin_student_list_table').DataTable().ajax.reload(null, false);
                setTimeout(function () {
                    $('.alert-msg-response').delay(5000).fadeOut(1000);
                    window.location.reload();
                }, 2000);


            }
        });       
    });
});
function iosCopyToClipboard(el) {
    
	textArea = document.createElement('textArea');
    textArea.readOnly = true;
    textArea.contentEditable = true;
    textArea.value = el;
    document.body.appendChild(textArea);

    var range, selection;
    range = document.createRange();
    range.selectNodeContents(textArea);
    selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);
    textArea.setSelectionRange(0, 999999);


    document.execCommand('copy');
    document.body.removeChild(textArea);

}
