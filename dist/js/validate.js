$.validator.setDefaults({
    submitHandler: function (form) {
        if ($(form).valid())
            form.submit();
        return false; // prevent normal form posting
    }
});
$(document).ready(function () {
    var message = '';

    jQuery.validator.addMethod("lic_check", function (value, element) {
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
	jQuery.validator.addMethod("username_check", function (value, element) {
        var username = $('#username').val();
		
        if (username != '') {
            var re = /^[A-Za-z0-9_-]+$/; // or /^\w+$/ as mentioned
			if (!re.test(username)) {
				return false;
			} else {
				return true;
			}
		
        }
    }, "Only letters, Numbers, dash and Underscore allowed in Username.");
//New Registration form validation
  $("#register-cbtf").validate({
            rules: {
                firstname: "required",
                lastname: {required: {
                        depends: function (element) {
                            return $('#user_type_teacher').is(":checked");
                        }
                    }
                },
                username: {
                    required: {
                        depends: function (element) {
                            return $('#user_type_student').is(":checked");
                        }
                    },
    				username_check : true,
                    required: true,
                    minlength: 4,
                    remote: {
                        url: ADMIN_URL + 'login/check-email.php',
                        type: 'POST',
                        data: {
                            username: function () {
                                return $("#username").val();
                            },
                            user_type: function () {
                                return $('#user_type_teacher').is(":checked");
                            }
                        }
                    }
                },
                password: {
                    required: true,
                    minlength: 5
                },
                email: {
                    required: true, /*{
                     depends: function (element) {
                     return $('#user_type_teacher').is(":checked");
                     },
                     }*/
                    email: true,
                    remote: {
                        url: ADMIN_URL + 'login/check-email.php',
                        type: 'POST',
                        data: {
                            email: function () {
                                return $("#email").val();
                            },
                            usertype: function(){
                                return $("#user_type").val();
                            }
                        }
                    }
                },
                agree: "required",
            },
            messages: {
                firstname: "Please enter your First Name",
                lastname: "Please enter your Last Name",
                username: {
                    required: "Please enter a username",
                    minlength: "Your username must consist of at least 4 characters",
                    remote: "Username not available"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                password_confirm: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above"
                },
                email: {
                    email: "Please enter a valid email address",
                    remote:"This email address is not available. Choose a different address."
                    // remote: "This email is already registered. <a href='update-license.php'>Click here</a> to update license for that account."
                },
                agree: {
                    required: "Please accept our policy"
                },
                //agree: "Please accept our policy",
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");
                // Add `has-feedback` class to the parent div.form-group
                // in order to add icons to inputs
                element.parents(".form-group").addClass("has-error");
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent());
                    //error.insertAfter(element);
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
               
                //$('.register-cftb').on('click', function(e) {
        	    //$('#register-cbtf').on('submit', function(e) {
            	/*	e.preventDefault();
            		var $form = $('#register-cbtf');
            		var formData = new FormData($($form)[0]);
            		var action = "https://www.accessibyte.com/wp-admin/admin-ajax.php";
            		$.post($form.attr('action'), $form.serialize(), function(data) {
            			$('.cftb-success').show();
            		}, 'json');*/
            	//});
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
                //$(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").removeClass("has-error");
                //$(element).parents(".form-group").addClass("has-success").removeClass("has-error");
            },
            submitHandler: function(form) {
                $('#loader').show();
                $(".register-cftb").attr('disabled','disabled');
                var $form = $('#register-cbtf');
        		var formData = new FormData($($form)[0]);
        		var action = "https://www.accessibyte.com/wp-admin/admin-ajax.php";
        		$.post(action, $form.serialize(), function(response) {
        		    if(response.status=='error'){
        		        $('.g-recaptcha').append('<em id="captcha-error" class="error help-block">'+response.message+'</em>');
        		        $(".register-cftb").removeAttr('disabled');
        		        $('#loader').hide();
        		    } else {
        		        $('#captcha-error').remove();
        		        //$('.cftb-success').show();
        		        alert('User registered successfully');
        		        //$('#myModal').modal('show');
        		        $("#register-cbtf")[0].reset(); 
        		        setTimeout(function () {
        		              //$('#myModal').modal('hide');
        		              location.reload();
                             //window.location.replace("https://www.accessibyte.com/online/login");
                        }, 2000);
        		    }
        		}, 'json');
        		
                /*$.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        $('#answers').html(response);
                    }            
                });*/
            }
            });
	
    //Registration form validation
    $("#registartion").validate({
        rules: {
            firstname: "required",
            lastname: {required: {
                    depends: function (element) {
                        return $('#user_type_teacher').is(":checked");
                    }
                }
            },
            username: {
                required: {
                    depends: function (element) {
                        return $('#user_type_student').is(":checked");
                    }
                },
				username_check : true,
                required: true,
                minlength: 4,
                remote: {
                    url: ADMIN_URL + 'login/check-email.php',
                    type: 'POST',
                    data: {
                        username: function () {
                            return $("#username").val();
                        },
                        user_type: function () {
                            return $('#user_type_teacher').is(":checked");
                        }
                    }
                }
            },
            password: {
                required: true,
                minlength: 5
            },
            password_confirm: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            },
            email: {
                required: true, /*{
                 depends: function (element) {
                 return $('#user_type_teacher').is(":checked");
                 },
                 }*/
                email: true,
                remote: {
                    url: ADMIN_URL + 'login/check-email.php',
                    type: 'POST',
                    data: {
                        email: function () {
                            return $("#email").val();
                        },
                        usertype: function(){
                            return $("#user_type").val();
                        }
                    }
                }
            },
            email_confirm: {
                required: true, /*{
                 depends: function (element) {
                 return $('#user_type_teacher').is(":checked");
                 }
                 },*/
                email: true,
                equalTo: "#email"
            },
            agree: "required",
            license: {
                required: true,
                remote: {
                    url: ADMIN_URL + 'config/validate-license.php',
                    type: 'POST',
                    data: {
                        license: function () {
                            return $("#edd_license").val();
                        },
                        usertype: function () {
                            return $('#user_type').val();
                        }
                    },
                     dataFilter: function (response) {

//                        response = $.parseJSON(response);
console.log(response);
                        if (response == 'true') {
                            return true;
                        } else if(response != 'false' && response != 'Wrong license type.' &&  response != 'License key is invalid!'  &&  response != 'This license is expired.' &&  response != 'This license is in use or out of activations.'){
                            message = response;
$('.checkednote').html(message);
                           return true;
                        }else{
                            $('.checkednote').html('');
                            message = response;
                            return false;
                        }

}
                },
                lic_check: true
            }
        },
        messages: {
            firstname: "Please enter your Display Name",
            lastname: "Please enter your lastname",
            username: {
                required: "Please enter a username",
                minlength: "Your username must consist of at least 4 characters",
                remote: "Username not available"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            password_confirm: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long",
                equalTo: "Please enter the same password as above"
            },
            age: {
                required: "Please Enter Age"
            },
            grade: {
                required: "Please Enter grade"
            },
            email_confirm: {
                required: "Please provide a email",
                email: "Please enter a valid email address",
                equalTo: "Please enter the same email as above"
            },
            email: {
                email: "Please enter a valid email address",
                remote:"This email address is not available. Choose a different address."
                // remote: "This email is already registered. <a href='update-license.php'>Click here</a> to update license for that account."
      

},
            agree: "Please accept our policy",
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

    //Login form validation
    $("#login-form").validate({
        rules: {
            password: {
                required: true,
                minlength: 5
            },
            email: {
                required: true,
                /*email: true*/
            },
        },
        messages: {

            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            email: "Please enter a valid username",
            agree: "Please accept our policy"
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
            $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
            //$(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });

    $("#frogot-pass-form").validate({
        rules: {
            username: {
                required: true,
                remote: {
                    url: ADMIN_URL + 'login/find-email.php',
                    type: 'POST',
                    data: {
                        username: function () {
                            return $("#username").val();
                        }
                    }
                }
            }
        },
        messages: {
            username: {
                remote: "This username is not registered in system",
                required: 'You must provide username that you are registered with'
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
            $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
            //$(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });

    //Reset password form
    $("#password-reset-form").validate({
        rules: {
            password: {
                required: true,
                minlength: 5
            },
            password_confirm: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            password_confirm: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long",
                equalTo: "Please enter the same password as above"
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
            $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
            //$(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });

    // $('input.user_type').on('ifChecked', function (event) {
    //     if ($(this).val() == 'student') {
    //         $('.organization_wrap').hide();
    //     } else {
    //         $('.organization_wrap').show();
    //     }
    // });

    //$('#registartion').hide();

    $('#user_type_student').on('click', function (event) {
        $('#registartion,#bottom_row,.student,#age,#grade,.student_value,.teacherusername').show();
        $('.register_header,#top_row,.organization_wrap,#student_info,#typio_info,#Lstname').hide();
        $('#user_type').val('student');
    });

    $('#user_type_teacher').on('click', function (event) {
        $('#registartion,#bottom_row,.organization_wrap,.teacher_value,#Lstname').show();
        $('.register_header,#top_row,#age,#grade,#student_info,.teacherusername,#typio_info').hide();
        $('#user_type').val('teacher');
    });

    $('#add_student_dashboard').on('click', function (event) {
        $('#student_info').show();
        $('.register_header,#age,#grade,#typio_info,#bottom_row').hide();
    });

    $('#typio_for_windows').on('click', function (event) {
        $('#typio_info').show();
        $('.register_header,#age,#grade,#student_info,#bottom_row').hide();
    });

});