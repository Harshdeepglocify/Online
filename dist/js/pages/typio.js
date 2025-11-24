(function ($) {

    $.fn.setCursorToTextEnd = function () {

        this.focus();

        var $thisVal = this.val();

        this.val('').val($thisVal);

        return this;

    }

    /*Highcharts.addEvent(Highcharts.Chart, 'render', function () {
     
     if (this.renderer && this.renderer.box) {
     
     this.renderer.box.setAttribute('aria-hidden', true);
     
     this.seriesGroup.element.setAttribute('aria-hidden', true);
     
     }
     
     });*/

})(jQuery);

$('#add-typio-modal').on('shown.bs.modal', function() {
    $('#title-text-new').focus();
  })
  $('#add-typio-test-modal').on('shown.bs.modal', function() {
    $('#title-text-test').focus();
  })
  $('#edit-lessons-modal').on('shown.bs.modal', function() {
    $('#title-text-edit').focus();
  })

  $(document).on("input","#attempt",function(e)  {
    if (/^0/.test(this.value)) {
      this.value = this.value.replace(/^0/, "");
    }
  });
$("#lesson_edit_attempt").keypress(function(event) {
    var attempt = $('#attempt').val();
    if( event.which == 48 && attempt.length == 0){
            return false;
    }
});
jQuery(document).ready(function ($) {



    var typop_title_alert = "Title name field required";

    var typio_data_alert = "Text data field required";

    $(document).on('click', '.typio-import-modal', function () {
	
        $('#add-typio-modal').modal('hide');
        $('#typio-import-modal').modal('show');
		var heading = $(this).data('cstheading');
		console.log(heading);
		if(heading == 'lesson'){
			$('#modal_cst_lesson').show();
			$('#modal_cst_test').hide();
		}else{
			$('#modal_cst_lesson').hide();
			$('#modal_cst_test').show();
		}
		//modal_cst_test
		//modal_cst_lesson
        setTimeout(function (){
                $('#typio_import_code').focus();
            }, 500);
    }); 
	$(document).on('click', '#typio_reset_pro', function () {
	
        $('#add-typio-modal').modal('hide');
        $('#typio-delete-settngs-modal').modal('show');
		var heading = $(this).data('cstheading');
		console.log(heading);
		
    });

    // Add new record

    $("#add-new-text").click(function () {

        var title = $("#title-text-new").val().trim();

        var data = $("#data-new").val().trim();



        if (title != '') {

            if (data != '') {

                var form_data = $('#lessons-add-form').serialize();

                var type = 'Add-Lessons';

                var user_type = $('#user_type').val();

                $.ajax({

                    type: 'POST',

                    url: ADMIN_URL + 'config/config-typio.php',

                    data: {'form_data': form_data, 'type': type, 'user_type': user_type},

                    async: true,

                    cache: false,

                    timeout: 10000,

                    success: function (response) {

                        var response = $.parseJSON(response);

                        if (response.type == 2) {

                            var table = $('#student_typio_lesson_list_table').DataTable();
                            table.ajax.reload(null, false);

                            var table = $('#typio-custom-lessons').DataTable();
                            table.ajax.reload(null, false);
                            
                        }

                        $('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                        $('.custom_lessons .quick-ajax-response').show();

                        $("#title-text-new,#data-new").val('');
                        $("#add-typio-modal").modal('hide');
                        $('.custom_lessons .quick-ajax-response').delay(5000).fadeOut(1000);

                    },

                    error: function (xhr, status, err) {}

                });

            } else {

                alert(typio_data_alert);

                $("#data-new").setCursorToTextEnd();

            }

        } else {

            alert(typop_title_alert);

            $("#title-text-new").setCursorToTextEnd();

        }

    });

  // Add new record

    $("#add-new-textBRL").click(function () {

        var title = $("#title-text-newBRL").val().trim();

        var data = $("#data-newBRL").val().trim();



        if (title != '') {

            if (data != '') {

                var form_data = $('#lessons-add-formBRL').serialize();

                var type = 'Add-LessonsBRL';

                var user_type = $('#user_typeBRL').val();

                $.ajax({

                    type: 'POST',

                    url: ADMIN_URL + 'config/config-typio.php',

                    data: {'form_data': form_data, 'type': type, 'user_type': user_type},

                    async: true,

                    cache: false,

                    timeout: 10000,

                    success: function (response) {

                        var response = $.parseJSON(response);

                        if (response.type == 2) {

                            var table = $('#student_Brail_lesson_list_table').DataTable();
                            table.ajax.reload(null, false);

                            var table = $('#Brail-custom-lessons').DataTable();
                            table.ajax.reload(null, false);
                            
                        }

                        $('.custom_lessonsBRL .quick-ajax-responseBRL').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                        $('.custom_lessonsBRL .quick-ajax-responseBRL').show();

                        $("#title-text-newBRL,#data-newBRL").val('');
                        $("#add-Brail-modal").modal('hide');
                        $('.custom_lessonsBRL .quick-ajax-responseBRL').delay(5000).fadeOut(1000);

                    },

                    error: function (xhr, status, err) {}

                });

            } else {

                alert(typio_data_alert);

                $("#data-new").setCursorToTextEnd();

            }

        } else {

            alert(typop_title_alert);

            $("#title-text-new").setCursorToTextEnd();

        }

    });

    // Add new typio test record

    $("#add-new-typio-test").click(function () {

        var title = $("#title-text-test").val().trim();

        var data = $("#data-test").val().trim();

        var attempt = $("#attempt").val().trim();

        if (title != '') {

            if (data != '') {
                if( attempt !=""){

                var form_data = $('#test-lessons-add-form').serialize();

                var type = 'Add-typio-test-Lessons';

                var user_type = $('#user_type').val();

                $.ajax({

                    type: 'POST',

                    url: ADMIN_URL + 'config/config-typio.php',

                    data: {'form_data': form_data, 'type': type, 'user_type': user_type},

                    async: true,

                    cache: false,

                    timeout: 10000,

                    success: function (response) {

                        var response = $.parseJSON(response);

                        if (response.type == 2) {
                            var table = $('#student_typio_test_lesson_list_table').DataTable();
                            table.ajax.reload(null, false);

                            var table = $('#typio-custom-lessons').DataTable();
                            table.ajax.reload(null, false);                    
                        }

                        $('.custom_test_lesson .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                        $('.custom_test_lesson .quick-ajax-response').show();
                        $("#title-text-test").val('');
                        $("#data-test").val('');
                        $("#attempt").val('');

                        $("#title-text-new,#data-new").val('');
                        $("#add-typio-test-modal").modal('hide');
                        $('.custom_test_lesson .quick-ajax-response').delay(5000).fadeOut(1000);

                    },

                    error: function (xhr, status, err) {}

                });
            }else{
                alert("Please enter attempt value");
                $("#attempt").setCursorToTextEnd();
            }

            } else {

                alert(typio_data_alert);

                $("#data-test").setCursorToTextEnd();

            }


        } else {
            
            alert(typop_title_alert);

            $("#title-text-test").setCursorToTextEnd();

        }

    });
// Add new Brailio test record

    $("#add-new-Brail-test").click(function () {

        var title = $("#title-text-testBRL").val().trim();

        var data = $("#data-testBRL").val().trim();

        var attempt = $("#attemptBRL").val().trim();

        if (title != '') {

            if (data != '') {
                if( attempt !=""){

                var form_data = $('#test-lessons-add-formBRL').serialize();

                var type = 'Add-Brail-test-Lessons';

                var user_type = $('#user_typeBRL').val();

                $.ajax({

                    type: 'POST',

                    url: ADMIN_URL + 'config/config-typio.php',

                    data: {'form_data': form_data, 'type': type, 'user_type': user_type},

                    async: true,

                    cache: false,

                    timeout: 10000,

                    success: function (response) {

                        var response = $.parseJSON(response);

                        if (response.type == 2) {
                            var table = $('#student_Brail_test_lesson_list_table').DataTable();
                            table.ajax.reload(null, false);

                            var table = $('#Brail-custom-lessons').DataTable();
                            table.ajax.reload(null, false);                    
                        }

                        $('.custom_test_lessonBRL .quick-ajax-responseBRL').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                        $('.custom_test_lessonBRL .quick-ajax-responseBRL').show();
                        $("#title-text-testBRL").val('');
                        $("#data-testBRL").val('');
                        $("#attemptBRL").val('');

                        $("#title-text-newBRL,#data-new").val('');
                        $("#add-Brail-test-modal").modal('hide');
                        $('.custom_test_lessonBRL .quick-ajax-responseBRL').delay(5000).fadeOut(1000);

                    },

                    error: function (xhr, status, err) {}

                });
            }else{
                alert("Please enter attempt value");
                $("#attemptBRL").setCursorToTextEnd();
            }

            } else {

                alert(typio_data_alert);

                $("#data-testBRL").setCursorToTextEnd();

            }


        } else {
            
            alert(typop_title_alert);

            $("#title-text-testBRL").setCursorToTextEnd();

        }

    });

    // edit open model

    $('#edit-lessons-modal').on('shown.bs.modal', function () {

        $('#textareaID2').setCursorToTextEnd();

    });
	$(".new-option-btn").click(function () {
            $(".new-option-section-wrap").toggle();
            $(this).toggleClass('import-menu-display');
    });  


    //Show edit modal popup

    $(document).on('click', '.edit-lessons-modal', function () {

       var table_id = $(this).attr('data-id');
console.log(table_id);
        var data_type = $(this).attr('data-type');
console.log('data_type'+data_type);
        var label = $(this).attr('data-label');
console.log('label'+label);
        var type = 'Update-Lessons';
        $('.typio-msg').hide();
        $('.typio_code_copy').html('');
        $('.typio-model-title').html(label);
        $('#typio_type').val(data_type);
        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-typio.php',

            data: {'table_id': table_id, type: type,data_type:data_type},

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == '200') {

                    $('#edit-lessons-modal .modal-title').addClass('color');

                    $('#edit-lessons-modal').removeClass('modal-success modal-warning');

                    $('#edit-lessons-modal').find('.edit-lessons-btn').show();

                    $('#edit-lessons-modal').find('.modal-body').html(response.html);

                }

            },

            error: function (xhr, status, err) {



            }

        });

        $('#edit-lessons-modal').modal('show');

    });



    $(document).on('click', '.edit-lessons-modalBRL', function () {

        var table_id = $(this).attr('data-id');
console.log(table_id);
        var data_type = $(this).attr('data-type');
console.log('data_type'+data_type);
        var label = $(this).attr('data-label');
console.log('label'+label);
        var type = 'Update-LessonsBRL';
        $('.Brail-msg').hide();
        $('.Brail_code_copy').html('');
        $('.Brail-model-title').html(label);
        $('#Brail_type').val(data_type);
        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-typio.php',

            data: {'table_id': table_id, type: type,data_type:data_type},

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);
//console.log(response);
//console.log('gbherug');
                if (response.status == '200') {
  $('#edit-lessons-modalBRL .modal-titleBRL').addClass('color');

                    $('#edit-lessons-modalBRL').removeClass('modal-success modal-warning');

                    $('#edit-lessons-modalBRL').find('.edit-lessons-btnBRL').show();

                    $('#edit-lessons-modalBRL').find('.modal-bodyBRL').html(response.html);

                }

            },

            error: function (xhr, status, err) {



            }

        });

        $('#edit-lessons-modalBRL').modal('show');

    });
    //Update lessons record

    $(document).on('click', '.edit-lessons-btn', function () {

        var title = $("#title-text-edit").val().trim();

        var data = $("#textareaID2").val().trim();

        var lesson_hidded_input_error = $(".lesson_hidded_input_error").val();

        var lesson_type =  $('.data_type').val();
        var attempt = $('#lesson_edit_attempt').val();
        if(lesson_type == 'typing-test-lessons' && attempt == ''){
            alert("Please enter attempt value");
            $("#attempt").setCursorToTextEnd();
            return false;
        }
        if (lesson_hidded_input_error == '') {

            $('#lesson_title_error').hide();

            if (title != '') {

                if (data != '') {

                    var form_data = $('#lessons-edit-form').serialize();

                    var type = 'Update-Lessons';

                    var user_type = $('#user_type').val();

                    $.ajax({

                        type: 'POST',

                        url: ADMIN_URL + 'config/config-typio.php',

                        data: {'form_data': form_data, 'type': type, 'user_type': user_type , 'lesson_type' : lesson_type},

                        async: true,

                        cache: false,

                        timeout: 10000,

                        success: function (response) {

                            var response = $.parseJSON(response);

                            if (response.status == '1') {

                                var currentTable = $('.custom_lessons .quick-ajax-response');
                                if(lesson_type == 'typing-test-lessons'){
                                    currentTable = $('.custom_test_lesson .quick-ajax-response');
                                }
                                currentTable.find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                                currentTable.show();

                                currentTable.delay(5000).fadeOut(1000);





                                $('#edit-lessons-modal').find('.edit-lessons-btn').hide();

                                $('#edit-lessons-modal .modal-title').removeClass('color');

                                $("#edit-lessons-modal .close").trigger('click');
                                var table = $('#student_typio_lesson_list_table').DataTable();
                                table.clear().draw();

                                var table = $('#student_typio_test_lesson_list_table').DataTable();
                                table.clear().draw();
                                
                                var table = $('#typio-custom-lessons').DataTable();
                                table.ajax.reload(null, false);      


                            } else {

                                $('#edit-lessons-modal').addClass('modal-warning');

                                $('#edit-lessons-modal').find('.modal-body').html(response.msg);

                                $('#edit-lessons-modal').find('.edit-lessons-btn').hide();

                            }

                        },

                        error: function (xhr, status, err) { }

                    });

                } else {

                    alert(typio_data_alert);

                    $("#textareaID2").setCursorToTextEnd();

                }

            } else {

                alert(typop_title_alert);

                $("#title-text-edit").setCursorToTextEnd();

            }

        } else {

            $('#lesson_title_error').show();

        }



    });
	
	
	
    $(document).on('click', '.edit-lessons-btnBRL', function () {

        var title = $("#title-text-editBRL").val().trim();
			console.log(title);
        var data = $("#textareaID2BRL").val().trim();
			console.log(data);
        var lesson_hidded_input_error = $(".lesson_hidded_input_errorBRL").val();
			console.log(lesson_hidded_input_error);
        var lesson_type =  $('.data_type').val();
			console.log(lesson_type);
        var attempt = $('#lesson_edit_attemptBRL').val();
			console.log(attempt);
        if(lesson_type == 'typing-test-lessons' && attempt == ''){
            alert("Please enter attempt value");
            $("#attempt").setCursorToTextEnd();
            return false;
        }
        if (lesson_hidded_input_error == '') {

            $('#lesson_title_errorBRL').hide();

            if (title != '') {

                if (data != '') {

                    var form_data = $('#lessons-edit-formBRL').serialize();

                    var type = 'Update-LessonsBRL';

                    var user_type = $('#user_type').val();

                    $.ajax({

                        type: 'POST',

                        url: ADMIN_URL + 'config/config-typio.php',

                        data: {'form_data': form_data, 'type': type, 'user_type': user_type , 'lesson_type' : lesson_type},

                        async: true,

                        cache: false,

                        timeout: 10000,

                        success: function (response) {

                            var response = $.parseJSON(response);

                            if (response.status == '1') {

                                var currentTable = $('.custom_lessonsBRL .quick-ajax-responseBRL');
                                if(lesson_type == 'typing-test-lessons'){
                                    currentTable = $('.custom_test_lessonBRL .quick-ajax-responseBRL');
                                }
                                currentTable.find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                                currentTable.show();

                                currentTable.delay(5000).fadeOut(1000);





                                $('#edit-lessons-modalBRL').find('.edit-lessons-btnBRL').hide();

                                $('#edit-lessons-modalBRL .modal-titleBRL').removeClass('color');

                                $("#edit-lessons-modalBRL .close").trigger('click');
                                var table = $('#student_Brail_lesson_list_table').DataTable();
                                table.clear().draw();

                                var table = $('#student_Brail_test_lesson_list_table').DataTable();
                                table.clear().draw();
                                
                                var table = $('#Brail-custom-lessons').DataTable();
                                table.ajax.reload(null, false);      


                            } else {

                                $('#edit-lessons-modalBRL').addClass('modal-warning');

                                $('#edit-lessons-modalBRL').find('.modal-bodyBRL').html(response.msg);

                                $('#edit-lessons-modalRBL').find('.edit-lessons-btnBRL').hide();

                            }

                        },

                        error: function (xhr, status, err) { }

                    });

                } else {

                    alert(typio_data_alert);

                    $("#textareaID2BRL").setCursorToTextEnd();

                }

            } else {

                alert(typop_title_alert);

                $("#title-text-editBRL").setCursorToTextEnd();

            }

        } else {

            $('#lesson_title_errorBRL').show();

        }



    });
    
    $(document).on('click', '.typio-export-modal', function () {
		table_id = $('.table_id').val();
		var student_id = $('#user_id').val();
		var dt = new Date().getTime();
		var uuid = 'xxxx-xxxx'.replace(/[xy]/g, function(c) {
			var r = (dt + Math.random()*16)%16 | 0;
			dt = Math.floor(dt/16);
			return (c=='x' ? r :(r&0x3|0x8)).toString(16);
		});
		
		$('.typio_code_copy').html("<input type='text' class='form-control' name='typio_code_copy' id='typio_code_copy' value='"+uuid+"'>");  
		var copyText = $('#typio_code_copy');
		copyText.select();
		document.execCommand("copy");
		navigator.clipboard.writeText(uuid);
		console.log(uuid+" <---");
		$.ajax({

			//alert('data');

			type: 'POST',

			url: ADMIN_URL + 'config/config-typio.php',

			data: {

				code: uuid,

				student_id: student_id,
				table_id: table_id,

				type: 'typio-export-entry',

			},

			async: true,

			cache: false,

			timeout: 10000,

			success: function (response) {

				var response = $.parseJSON(response);


				
				if (response.status == 1) {
				   $('.typio-msg').fadeIn();

					$('.typio-msg').html(response.msg).show();

					$('.typio-msg').find('.alert').show();
				 
				} else {
                    $('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    $('.custom_lessons .quick-ajax-response').show();
				}
				$('.custom_lessons .quick-ajax-response').delay(5000).fadeOut(1000);


			},

			error: function (xhr, status, err) {

			}

		});
	});
  $(document).on('click', '.Brail-export-modal', function () {
		table_id = $('.table_id').val();
		var student_id = $('#user_id').val();
		var dt = new Date().getTime();
		var uuid = 'xxxx-xxxx'.replace(/[xy]/g, function(c) {
			var r = (dt + Math.random()*16)%16 | 0;
			dt = Math.floor(dt/16);
			return (c=='x' ? r :(r&0x3|0x8)).toString(16);
		});
		
		$('.Brail_code_copy').html("<input type='text' class='form-control' name='Brail_code_copy' id='Brail_code_copy' value='"+uuid+"'>");  
		var copyText = $('#typio_code_copy');
		copyText.select();
		document.execCommand("copy");
	
		$.ajax({

			//alert('data');

			type: 'POST',

			url: ADMIN_URL + 'config/config-typio.php',

			data: {

				code: uuid,

				student_id: student_id,
				table_id: table_id,

				type: 'Brail-export-entry',

			},

			async: true,

			cache: false,

			timeout: 10000,

			success: function (response) {

				var response = $.parseJSON(response);


				
				if (response.status == 1) {
				   $('.Brail-msg').fadeIn();

					$('.Brail-msg').html(response.msg).show();

					$('.Brail-msg').find('.alert').show();
				 
				} else {
                    $('.custom_lessonsBRL .quick-ajax-responseBRL').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    $('.custom_lessonsBRL .quick-ajax-responseBRL').show();
				}
				$('.custom_lessonsBRL .quick-ajax-responseBRL').delay(5000).fadeOut(1000);


			},

			error: function (xhr, status, err) {

			}

		});
	});
    // add import here 
	$(document).on('click', '.add-typio-import-btn', function () {
		
		var student_id = $('#user_id').val();
		var this_data = $(this);

		if ($('#typio_import_code').val() == "") {

			//alert(desk_title_alert);

			$('#typio_import_code').setCursorToTextEnd();

			return false;

		}

	   
		var import_code = $('#typio_import_code').val();


		$.ajax({

			//alert('data');

			type: 'POST',

			url: ADMIN_URL + 'config/config-typio.php',

			data: {

				code: import_code,

				student_id: student_id,

				type: 'typio-import-entry',

			},

			async: true,

			cache: false,

			timeout: 10000,

			success: function (response) {

				var response = $.parseJSON(response);



				if (response.status == 1) {

					
					$('#typio-custom-lessons tr:last').after(response.html);	
					$('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    $('.custom_lessons .quick-ajax-response').show();
                    var table = $('#student_typio_lesson_list_table').DataTable();
                    table.ajax.reload(null, false);

				} else {

					//$('.quick-ajax-response').find('.alert').removeClass(' alert-warning  alert-success').addClass('alert-warning').html(response.msg);

					$('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    $('.custom_lessons .quick-ajax-response').show();

				}

                var table = $('#typio-custom-lessons').DataTable();
                table.ajax.reload(null, false);  

                var table = $('#student_typio_test_lesson_list_table').DataTable();
                table.ajax.reload(null, false);  
				$('#typio-import-modal').find('#typio_import_code').val('');
				$("#typio-import-modal").modal('hide');
				$('.custom_lessons .quick-ajax-response').delay(5000).fadeOut(1000);

				//$('#deck-maker-modal .remove-new-field').trigger('click');

			},

			error: function (xhr, status, err) {

			}

		});
	});

$(document).on('click', '#confirm-delete-app-btn', function () {
	
	
	var student_id = $('#data_delete_student_typio').val();
	  $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_delete_app_data','is_ajax' : '1','student_id':student_id},
                async: true,
                cache: false,
                success: function (output) {
				
                   var response = $.parseJSON(output);
				   console.log(response);
				   if(response.status == "success"){
				        $('#delet_data_typio_id').text(response.message);
					    $('#delet_data_typio_id').removeClass('history_hide');
						   setTimeout(function() {
								location.reload();
							}, 2000);
				   }else{
					    $('#delet_data_typio_id').text(response.message);
						$('#delet_data_typio_id').removeClass('history_hide');
						   setTimeout(function() {
								location.reload();
							}, 2000);
				   }
				   
                }
            });   
});


$(document).on('click', '.add-Brail-import-btn', function () {
		
		var student_id = $('#user_id').val();
		var this_data = $(this);

		if ($('#Brail_import_code').val() == "") {

			//alert(desk_title_alert);

			$('#Brail_import_code').setCursorToTextEnd();

			return false;

		}

	   
		var import_code = $('#Brail_import_code').val();


		$.ajax({

			//alert('data');

			type: 'POST',

			url: ADMIN_URL + 'config/config-typio.php',

			data: {

				code: import_code,

				student_id: student_id,

				type: 'Brail-import-entry',

			},

			async: true,

			cache: false,

			timeout: 10000,

			success: function (response) {

				var response = $.parseJSON(response);



				if (response.status == 1) {

					
					$('#Brail-custom-lessons tr:last').after(response.html);	
					$('.custom_lessonsBRL .quick-ajax-responseBRLquick-ajax-responseBRLb').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    $('.custom_lessonsBRL .quick-ajax-responseBRL').show();
                    var table = $('#student_Brail_lesson_list_table').DataTable();
                    table.ajax.reload(null, false);

				} else {

					//$('.quick-ajax-response').find('.alert').removeClass(' alert-warning  alert-success').addClass('alert-warning').html(response.msg);

					$('.custom_lessonsBRL .quick-ajax-responseBRL').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    $('.custom_lessonsBRL .quick-ajax-responseBRL').show();

				}

                var table = $('#Brail-custom-lessons').DataTable();
                table.ajax.reload(null, false);  

                var table = $('#student_Brail_test_lesson_list_table').DataTable();
                table.ajax.reload(null, false);  
				$('#Brail-import-modal').find('#Brail_import_code').val('');
				$("#Brail-import-modal").modal('hide');
				$('.custom_lessonsBRL .quick-ajax-responseBRL').delay(5000).fadeOut(1000);

				//$('#deck-maker-modal .remove-new-field').trigger('click');

			},

			error: function (xhr, status, err) {

			}

		});
	});

    //Delete record

    $(document).on('click', '.lesson-delete', function () {

        var this_data = $(this);

        bootbox.confirm({

            message: "Are you sure you want to delete this lesson?",

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

                    var type = 'lessons-delete';

                    $.ajax({

                        type: 'POST',

                        url: ADMIN_URL + 'config/config-typio.php',

                        data: {'delete_id': delete_id, 'type': type},

                        async: true,

                        cache: false,

                        timeout: 10000,

                        success: function (response) {

                            var response = $.parseJSON(response);

                            this_data.closest("tr").remove();

                            var table = $('#student_typio_lesson_list_table').DataTable();
                            table.clear().draw();

                            var table = $('#typio-custom-lessons').DataTable();
                            table.ajax.reload(null, false);      

                            if (response.status == 1) {

                                $('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                                $('.custom_lessons .quick-ajax-response').show();

                            } else {

                                $('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                                $('.custom_lessons .quick-ajax-response').show();

                            }

                            $('.custom_lessons .quick-ajax-response').delay(5000).fadeOut(1000);

                        },

                        error: function (xhr, status, err) {



                        }

                    });

                }

            }

        });

    });





    //Share Data

    $(document).on('click', '.typio-share-submit', function () {



        var user_id = $('#user_id').val();

        



        var share_ids = [];

        var i = 0;

        $('.share_typio_ids:checked').each(function () {

            share_ids[i++] = $(this).val();

        });



        if (share_ids == '') {

            $('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html('Please select at least one lesson.').show();

            $('.custom_lessons .quick-ajax-response').show();

            return false;

        } else {

            $('.custom_lessons .quick-ajax-response').removeClass(' alert-warning  alert-success  alert-warning').hide();

        }
        var share_user_ids = [];
		var j = 0;
		$('.share_user_ids:checked').each(function () {
            share_user_ids[j++] = $(this).val();
		});


        if (share_user_ids == '') {

            $('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html('Please select at least one Student.').show();

            $('.custom_lessons .quick-ajax-response').show();

            return false;

        } else {

            $('.custom_lessons .quick-ajax-response').removeClass(' alert-warning  alert-success  alert-warning').hide();

        }



        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-student.php',

            data: {'user_id': user_id,

                'type': 'typio',

                'share_ids': share_ids,

                'share_user_list': share_user_ids

            },

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == 1) {

                    $('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                    $('.custom_lessons .quick-ajax-response').show();

                    $('.share_typio_ids').removeAttr('checked');

                    $(".share_user_ids").removeAttr("checked");

                } else {

                    $('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                    $('.custom_lessons .quick-ajax-response').show();

                    $('.share_typio_ids').removeAttr('checked');
                    $(".share_user_ids").removeAttr("checked");

                }

                $('.custom_lessons .quick-ajax-response').delay(5000).fadeOut(1000);

            },

            error: function (xhr, status, err) {

            }

        });

    });


    $(document).on('click', '#TypioHistoryDateSection #TypioDateFilterBtn', function () {
        $('#TypioHistoryDateSection').addClass('ajax_loading');
        var TypioDatePicker1 = $('#TypioHistoryDateSection #TypioDatePicker1').val();
        var TypioDatePicker2 = $('#TypioHistoryDateSection #TypioDatePicker2').val();
        var student_id = $('#TypioHistoryDateSection #student_id').val();
        $('#TypioHistorySection .history_hide').removeClass('history_hide');
		 
        $('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
            var typioDatePickerFrom = $("#TypioDatePicker1").val();
            var typioDatePickerTo   = $("#TypioDatePicker2").val();
			$('.date1-typio').text(typioDatePickerFrom);
			$('.date2-typio').text(typioDatePickerTo);
			
			
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_typio_ajax_date_field','is_ajax' : '1','typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					//console.log(output.data1['wpm']);
					var response = $.parseJSON(output);
					//console.log(response+'--');
                    var history_table = $('#TypioHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });
			

			
        // $.ajax({
        //     type: 'POST',
        //     url: ADMIN_URL + 'config/config-student.php',
        //     data: {action_type: 'get_history_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
        //     async: true,
        //     cache: false,
        //     timeout: 10000,
        //     success: function (response) {
        //         var response = $.parseJSON(response);
        //         if (response.status == '200') {
        //             $('.total-row').html(response.total_row);
        //             $('#TypioHistorySection').find('#TypioHistory_table').html(response.html);
        //             $('#TypioHistorySection').find('.total-row').find('strong').html(response.total_row);
        //             //$('#TypioHistoryDateSection').removeClass('ajax_loading');
        //             $('#TypioHistorySection .history_hide').removeClass('history_hide');
        //             $('#TypioHistorySection .no-history-msg-wrap').hide();
        //         }
        //         var charts = $('#TypioHistoryAreaChart').highcharts();
        //         var options = charts.options;
        //         charts = new Highcharts.Chart(options);
        //         charts.redraw();
        //     },
        //     complete: function () {
        //         var charts = $('#TypioHistoryAreaChart').highcharts();
        //         var options = charts.options;
        //         charts = new Highcharts.Chart(options);
        //         charts.redraw();
        //     }
        // });
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#TypioHistoryAreaChart_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
              
				var charts = $('#TypioHistoryAreaChart').highcharts();
				
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
				
				

            }
        });
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
				console.log(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#historyTypio1Tab2_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
               // var charts = $('#historyTypio1Tab2').highcharts();
               // var options = charts.options;
                //charts = new Highcharts.Chart(options);
                //charts.redraw();
            }
        });
		$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					//console.log(output.data1['wpm']);
					var response = $.parseJSON(output);
					if(response.recordsTotal != 0 || response.recordsTotal != "0"){
					$('#typio_wpm_cst').text(response.data2['wpm']+" WPM");
					$('#typio_acc_cst').text(response.data2['accu']+"% Accuracy");
					$('#typio_err_cst').text(response.data2['combo']+" Errors");
					$('#typio_lesson_cst').text(response.recordsFiltered+" Lessons");
					$('.TypioHistory-section-one').removeClass('history_hide');
					$('.TypioHistory-section-two').removeClass('history_hide');
					$('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
					}else{
						 $('.TypioHistory-section-one').addClass('history_hide');
						 $('.TypioHistory-section-two').addClass('history_hide');
						 $('#TypioHistorySection .cst_stu-typio').removeClass('history_hide');
					}
					//console.log(response.recordsFiltered+'--');
					//console.log(response.data1['wpm']);
					//console.log(response.data1['wpm']);
                   // var history_table = $('#TypioHistory_table').DataTable();
                    //history_table.ajax.reload(null, false);
                }
            });
    });


 $(document).on('click', '#TypioKeyboardProgressSection #TypiokpDateFilterBtn', function () {
        $('#TypioKeyboardProgressSection').addClass('ajax_loading');
        var TypioDatePicker1 = $('#TypioKeyboardProgressSection #TypiokpDatePicker1').val();
        var TypioDatePicker2 = $('#TypioKeyboardProgressSection #TypiokpDatePicker2').val();
        var student_id = $('#TypioKeyboardProgressSection #student_idkp').val();
        $('#TypiokpHistorySection .history_hide').removeClass('history_hide');
		 
        $('#TypiokpHistorySection .cst_stu-typiokp').addClass('history_hide');
            var typioDatePickerFrom = $("#TypiokpDatePicker1").val();
            var typioDatePickerTo   = $("#TypiokpDatePicker2").val();
			$('.date1-typiokp').text(typioDatePickerFrom);
			$('.date2-typiokp').text(typioDatePickerTo);
			
			
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_typio_ajax_date_field_kp','is_ajax' : '1','typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					//console.log(output.data1['wpm']);
					var response = $.parseJSON(output);
					//console.log(response+'--');
                    var history_table = $('#TypiokpHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });
			
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data_kp', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypiokpHistorySection').find('#TypiokpHistoryAreaChart_table').html(response.html);
                    $('#TypioKeyboardProgressSection').removeClass('ajax_loading');
                }
              
				var charts = $('#TypiokpHistoryAreaChart').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
				
				

            }
        });
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data_kp', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
				//console.log(response);
                if (response.status == '200') {
                    $('#TypiokpHistorySection').find('#historyTypiokp1Tab2_table').html(response.html);
                    $('#TypioKeyboardProgressSection').removeClass('ajax_loading');
                }
                /*var charts = $('#historyTypiokp1Tab2').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();*/
            }
        });
		$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history_kp','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					//console.log(output.data1['wpm']);
					var response = $.parseJSON(output);
					$('.typio_lesson_cstkpdiv #typiokp_wpm_cst').text(response.data2['wpm']+" WPM");
					$('.typio_lesson_cstkpdiv #typiokp_acc_cst').text(response.data2['accu']+"% Accuracy");
					$('.typio_lesson_cstkpdiv #typiokp_err_cst').text(response.data2['combo']+" Errors");
					$('#typio_lesson_cstkp').text(response.recordsFiltered+" Lessons");
					
                }
            });
    });
	
	$(document).on('click', '#TypiokpThisWeekFilterBtn', function () {
        $('#TypioKeyboardProgressSection').addClass('ajax_loading');
        var TypioDatePicker1 = $('#TypioKeyboardProgressSection #TypiokpThisWeekFilterstart').val();
        var TypioDatePicker2 = $('#TypioKeyboardProgressSection #TypiokpThisWeekFilterend').val();
        var student_id = $('#TypioKeyboardProgressSection #student_idkp').val();
        $('#TypiokpHistorySection .history_hide').removeClass('history_hide');
		 
        $('#TypiokpHistorySection .cst_stu-typiokp').addClass('history_hide');
            var typioDatePickerFrom = $("#TypiokpThisWeekFilterstart").val();
            var typioDatePickerTo   = $("#TypiokpThisWeekFilterend").val();
			$('.date1-typiokp').text(typioDatePickerFrom);
			$('.date2-typiokp').text(typioDatePickerTo);
			
			
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_typio_ajax_date_field_kp','is_ajax' : '1','typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					//console.log(output.data1['wpm']);
					var response = $.parseJSON(output);
					//console.log(response+'--');
                    var history_table = $('#TypiokpHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });
			
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data_kp', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypiokpHistorySection').find('#TypiokpHistoryAreaChart_table').html(response.html);
                    $('#TypioKeyboardProgressSection').removeClass('ajax_loading');
                }
              
				var charts = $('#TypiokpHistoryAreaChart').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
				
				

            }
        });
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data_kp', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
				console.log(response);
                if (response.status == '200') {
                    $('#TypiokpHistorySection').find('#historyTypiokp1Tab2_table').html(response.html);
                    $('#TypioKeyboardProgressSection').removeClass('ajax_loading');
                }
                var charts = $('#historyTypiokp1Tab2').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
            }
        });
		$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history_kp','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					//console.log(output.data1['wpm']);
					var response = $.parseJSON(output);
					$('.typio_lesson_cstkpdiv #typiokp_wpm_cst').text(response.data2['wpm']+" WPM");
					$('.typio_lesson_cstkpdiv #typiokp_acc_cst').text(response.data2['accu']+"% Accuracy");
					$('.typio_lesson_cstkpdiv #typiokp_err_cst').text(response.data2['combo']+" Errors");
					$('#typio_lesson_cstkp').text(response.recordsFiltered+" Lessons");
					
                }
            });
    });


/*
 $(document).on('click', '#TypioHistoryDateSection #TypioDateFilterBtn', function () {
        $('#TypioHistoryDateSection').addClass('ajax_loading');
        var TypioDatePicker1 = $('#TypioHistoryDateSection #TypioDatePicker1').val();
        var TypioDatePicker2 = $('#TypioHistoryDateSection #TypiokpDatePicker2').val();
        var student_id = $('#TypioHistoryDateSection #student_id').val();
        $('#TypioHistorySection .history_hide').removeClass('history_hide');
        $('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
            var typioDatePickerFrom = $("#TypioDatePicker1").val();
            var typioDatePickerTo   = $("#TypiokpDatePicker2").val();
			$('.date1-typio').text(typioDatePickerFrom);
			$('.date2-typio').text(typioDatePickerTo);
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					//console.log(output.data1['wpm']);
					var response = $.parseJSON(output);
					$('#typio_wpm_cst').text(response.data1['wpm']+" WPM");
					$('#typio_acc_cst').text(response.data1['accu']+"% Accuracy");
					$('#typio_err_cst').text(response.data1['combo']+" Errors");
					$('#typio_lesson_cst').text(response.recordsFiltered+" Lessons");
					//console.log(response.recordsFiltered+'--');
					//console.log(response.data1['wpm']);
					//console.log(response.data1['wpm']);
                   // var history_table = $('#TypioHistory_table').DataTable();
                    //history_table.ajax.reload(null, false);
                }
            });
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_typio_ajax_date_field','is_ajax' : '1','typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					
					//$("#typio_wpm_cst").text(output.data1[0].wpm);
                    var history_table = $('#TypioHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });   
        // $.ajax({
        //     type: 'POST',
        //     url: ADMIN_URL + 'config/config-student.php',
        //     data: {action_type: 'get_history_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
        //     async: true,
        //     cache: false,
        //     timeout: 10000,
        //     success: function (response) {
        //         var response = $.parseJSON(response);
        //         if (response.status == '200') {
        //             $('.total-row').html(response.total_row);
        //             $('#TypioHistorySection').find('#TypioHistory_table').html(response.html);
        //             $('#TypioHistorySection').find('.total-row').find('strong').html(response.total_row);
        //             //$('#TypioHistoryDateSection').removeClass('ajax_loading');
        //             $('#TypioHistorySection .history_hide').removeClass('history_hide');
        //             $('#TypioHistorySection .no-history-msg-wrap').hide();
        //         }
        //         var charts = $('#TypioHistoryAreaChart').highcharts();
        //         var options = charts.options;
        //         charts = new Highcharts.Chart(options);
        //         charts.redraw();
        //     },
        //     complete: function () {
        //         var charts = $('#TypioHistoryAreaChart').highcharts();
        //         var options = charts.options;
        //         charts = new Highcharts.Chart(options);
        //         charts.redraw();
        //     }
        // });
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#TypioHistoryAreaChart_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
                var charts = $('#TypioHistoryAreaChart').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
            }
        });
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#historyTypio1Tab2_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
                var charts = $('#historyTypio1Tab2').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
            }
        });
    });*/


$(document).on('click', '#TypioThisWeekFilterBtn', function () {
        $('#TypioHistoryDateSection').addClass('ajax_loading');
		$('#TypioTodayFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisWeekFilterBtn').addClass('TypioActiveBtn');
        $('#TypioThisMonthFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisYearFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioAllTimeFilterBtn').removeClass('TypioActiveBtn');
        var TypioDatePicker1 = $('#TypioHistoryDateSection #TypioThisWeekFilterstart').val();
        var TypioDatePicker2 = $('#TypioHistoryDateSection #TypioThisWeekFilterend').val();
        var student_id = $('#TypioHistoryDateSection #student_id').val();
        $('#TypioHistorySection .history_hide').removeClass('history_hide');
        $('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
            var typioDatePickerFrom = $("#TypioThisWeekFilterstart").val();
            var typioDatePickerTo   = $("#TypioThisWeekFilterend").val();
			
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_typio_ajax_date_field','is_ajax' : '1','typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					
					//$("#typio_wpm_cst").text(output.data1[0].wpm);
                    var history_table = $('#TypioHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });  

			

			
        
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#TypioHistoryAreaChart_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
               var charts = $('#TypioHistoryAreaChart').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
            }
        });
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#historyTypio1Tab2_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
                /*var charts = $('#historyTypio1Tab2').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();*/
            }
        });
		
		$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
					if(response.recordsTotal != 0 || response.recordsTotal != "0"){
						//console.log(response.data1['wpm']+"WPM");
					$('#typio_wpm_cst').text(response.data1['wpm']+" WPM");
					$('#typio_acc_cst').text(response.data1['accu']+"% Accuracy");
					$('#typio_err_cst').text(response.data1['combo']+" Errors");
					$('#typio_lesson_cst').text(response.recordsFiltered+" Lessons");
					$('.date1-typio').text(typioDatePickerFrom);
					$('.date2-typio').text(typioDatePickerTo);
						$('.TypioHistory-section-one').removeClass('history_hide');
						$('.TypioHistory-section-two').removeClass('history_hide');
						$('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
					}else{
						 $('.TypioHistory-section-one').addClass('history_hide');
						 $('.TypioHistory-section-two').addClass('history_hide');
						 $('#TypioHistorySection .cst_stu-typio').removeClass('history_hide');
					}
					//console.log(response.recordsFiltered+'--');
					//console.log(response.data1['wpm']);
					//console.log(response.data1['wpm']);
                    //var history_table = $('#TypioHistory_table').DataTable();
                    //history_table.ajax.reload(null, false);
                }
            });
		
    });
	
	
	/**/
	$(document).on('click', '#TypioThisMonthFilterBtn', function () {
        $('#TypioHistoryDateSection').addClass('ajax_loading');
		$('#TypioTodayFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisWeekFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisMonthFilterBtn').addClass('TypioActiveBtn');
        $('#TypioThisYearFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioAllTimeFilterBtn').removeClass('TypioActiveBtn');
        var TypioDatePicker1 = $('#TypioHistoryDateSection #TypioThisMonthFilterstart').val();
        var TypioDatePicker2 = $('#TypioHistoryDateSection #TypioThisMonthFilterend').val();
        var student_id = $('#TypioHistoryDateSection #student_id').val();
        $('#TypioHistorySection .history_hide').removeClass('history_hide');
        $('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
            var typioDatePickerFrom = $("#TypioThisMonthFilterstart").val();
            var typioDatePickerTo   = $("#TypioThisMonthFilterend").val();
			
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_typio_ajax_date_field','is_ajax' : '1','typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					
					//$("#typio_wpm_cst").text(output.data1[0].wpm);
                    var history_table = $('#TypioHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });  
       
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#TypioHistoryAreaChart_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
                var charts = $('#TypioHistoryAreaChart').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
            }
        });
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#historyTypio1Tab2_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
               /* var charts = $('#historyTypio1Tab2').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();*/
            }
        });
		
		$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
					if(response.recordsTotal != 0 || response.recordsTotal != "0"){
					$('#typio_wpm_cst').text(response.data1['wpm']+" WPM");
					$('#typio_acc_cst').text(response.data1['accu']+"% Accuracy");
					$('#typio_err_cst').text(response.data1['combo']+" Errors");
					$('#typio_lesson_cst').text(response.recordsFiltered+" Lessons");
					$('.date1-typio').text(typioDatePickerFrom);
					$('.date2-typio').text(typioDatePickerTo);
						$('.TypioHistory-section-one').removeClass('history_hide');
						$('.TypioHistory-section-two').removeClass('history_hide');
						$('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
					}else{
						$('.TypioHistory-section-one').addClass('history_hide');
						$('.TypioHistory-section-two').addClass('history_hide');
						$('#TypioHistorySection .cst_stu-typio').removeClass('history_hide');
					}
					//console.log(response.recordsFiltered+'--');
					//console.log(response.data1['wpm']);
					//console.log(response.data1['wpm']);
                    //var history_table = $('#TypioHistory_table').DataTable();
                    //history_table.ajax.reload(null, false);
                }
            });
		
    });
	/**/
	$(document).on('click', '#TypioThisYearFilterBtn', function () {
        $('#TypioHistoryDateSection').addClass('ajax_loading');
		$('#TypioTodayFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisWeekFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisMonthFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisYearFilterBtn').addClass('TypioActiveBtn');
        $('#TypioAllTimeFilterBtn').removeClass('TypioActiveBtn');
        var TypioDatePicker1 = $('#TypioHistoryDateSection #TypioThisYearFilterstart').val();
        var TypioDatePicker2 = $('#TypioHistoryDateSection #TypioThisYearFilterend').val();
        var student_id = $('#TypioHistoryDateSection #student_id').val();
        $('#TypioHistorySection .history_hide').removeClass('history_hide');
        $('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
            var typioDatePickerFrom = $("#TypioThisYearFilterstart").val();
            var typioDatePickerTo   = $("#TypioThisYearFilterend").val();
			
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_typio_ajax_date_field','is_ajax' : '1','typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					
					//$("#typio_wpm_cst").text(output.data1[0].wpm);
                    var history_table = $('#TypioHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });  
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#TypioHistoryAreaChart_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
                var charts = $('#TypioHistoryAreaChart').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
            }
        });
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#historyTypio1Tab2_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
                /*var charts = $('#historyTypio1Tab2').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();*/
            }
        });
		
		$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
						if(response.recordsTotal != 0 || response.recordsTotal != "0"){
					$('#typio_wpm_cst').text(response.data1['wpm']+" WPM");
					$('#typio_acc_cst').text(response.data1['accu']+"% Accuracy");
					$('#typio_err_cst').text(response.data1['combo']+" Errors");
					$('#typio_lesson_cst').text(response.recordsFiltered+" Lessons");
					$('.date1-typio').text(typioDatePickerFrom);
					$('.date2-typio').text(typioDatePickerTo);
						$('.TypioHistory-section-one').removeClass('history_hide');
						$('.TypioHistory-section-two').removeClass('history_hide');
						$('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
					}else{
						$('.TypioHistory-section-one').addClass('history_hide');
						$('.TypioHistory-section-two').addClass('history_hide');
						$('#TypioHistorySection .cst_stu-typio').removeClass('history_hide');
					}
					//console.log(response.recordsFiltered+'--');
					//console.log(response.data1['wpm']);
					//console.log(response.data1['wpm']);
                    //var history_table = $('#TypioHistory_table').DataTable();
                    //history_table.ajax.reload(null, false);
                }
            });
		
    });
	/**/
	$(document).on('click', '#TypioAllTimeFilterBtn', function () {
        $('#TypioHistoryDateSection').addClass('ajax_loading');
		$('#TypioTodayFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisWeekFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisMonthFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisYearFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioAllTimeFilterBtn').addClass('TypioActiveBtn');
        var TypioDatePicker1 = $('#TypioHistoryDateSection #TypioAllTimeFilterstart').val();
        var TypioDatePicker2 = $('#TypioHistoryDateSection #TypioAllTimeFilterend').val();
        var student_id = $('#TypioHistoryDateSection #student_id').val();
        $('#TypioHistorySection .history_hide').removeClass('history_hide');
        $('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
            var typioDatePickerFrom = $("#TypioAllTimeFilterstart").val();
            var typioDatePickerTo   = $("#TypioAllTimeFilterend").val();
			
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_typio_ajax_date_field','is_ajax' : '1','typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					
					//$("#typio_wpm_cst").text(output.data1[0].wpm);
                    var history_table = $('#TypioHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });  

        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#TypioHistoryAreaChart_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
                var charts = $('#TypioHistoryAreaChart').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
            }
        });
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#historyTypio1Tab2_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
               /* var charts = $('#historyTypio1Tab2').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();*/
            }
        });
		
		$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
					if(response.recordsTotal != 0 || response.recordsTotal != "0"){
					$('#typio_wpm_cst').text(response.data1['wpm']+" WPM");
					$('#typio_acc_cst').text(response.data1['accu']+"% Accuracy");
					$('#typio_err_cst').text(response.data1['combo']+" Errors");
					$('#typio_lesson_cst').text(response.recordsFiltered+" Lessons");
					$('.date1-typio').text(typioDatePickerFrom);
					$('.date2-typio').text(typioDatePickerTo);
					$('.TypioHistory-section-one').removeClass('history_hide');
						$('.TypioHistory-section-two').removeClass('history_hide');
						$('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
					}else{
						$('.TypioHistory-section-one').addClass('history_hide');
						$('.TypioHistory-section-two').addClass('history_hide');
						$('#TypioHistorySection .cst_stu-typio').removeClass('history_hide');
					}
					//console.log(response.recordsFiltered+'--');
					//console.log(response.data1['wpm']);
					//console.log(response.data1['wpm']);
                    //var history_table = $('#TypioHistory_table').DataTable();
                    //history_table.ajax.reload(null, false);
                }
            });
		
    });
	/**/
	$(document).on('click', '#TypioTodayFilterBtn', function () {
        $('#TypioHistoryDateSection').addClass('ajax_loading');
        $('#TypioTodayFilterBtn').addClass('TypioActiveBtn');
        $('#TypioThisWeekFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisMonthFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioThisYearFilterBtn').removeClass('TypioActiveBtn');
        $('#TypioAllTimeFilterBtn').removeClass('TypioActiveBtn');
		
        var TypioDatePicker1 = $('#TypioHistoryDateSection #TypioTodayFilterstart').val();
        var TypioDatePicker2 = $('#TypioHistoryDateSection #TypioTodayFilterend').val();
        var student_id = $('#TypioHistoryDateSection #student_id').val();
        $('#TypioHistorySection .history_hide').removeClass('history_hide');
        $('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
            var typioDatePickerFrom = $("#TypioTodayFilterstart").val();
            var typioDatePickerTo   = $("#TypioTodayFilterend").val();
			
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_typio_ajax_date_field','is_ajax' : '1','typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
					
					//$("#typio_wpm_cst").text(output.data1[0].wpm);
                    var history_table = $('#TypioHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });  

        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#TypioHistoryAreaChart_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
                var charts = $('#TypioHistoryAreaChart').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
            }
        });
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data', start_date: TypioDatePicker1, end_date: TypioDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#TypioHistorySection').find('#historyTypio1Tab2_table').html(response.html);
                    $('#TypioHistoryDateSection').removeClass('ajax_loading');
                }
               /* var charts = $('#historyTypio1Tab2').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();*/
            }
        });
		
		$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_typio_history','is_ajax' : '1','student_id':student_id,'typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
					if(response.recordsTotal != 0 || response.recordsTotal != "0"){
					$('#typio_wpm_cst').text(response.data1['wpm']+" WPM");
					$('#typio_acc_cst').text(response.data1['accu']+"% Accuracy");
					$('#typio_err_cst').text(response.data1['combo']+" Errors");
					$('#typio_lesson_cst').text(response.recordsFiltered+" Lessons");
					$('.date1-typio').text(typioDatePickerFrom);
					$('.date2-typio').text(typioDatePickerTo);
					$('.TypioHistory-section-one').removeClass('history_hide');
						$('.TypioHistory-section-two').removeClass('history_hide');
						$('#TypioHistorySection .cst_stu-typio').addClass('history_hide');
					}else{
						$('.TypioHistory-section-one').addClass('history_hide');
						$('.TypioHistory-section-two').addClass('history_hide');
						$('#TypioHistorySection .cst_stu-typio').removeClass('history_hide');
					}
					//console.log(response.recordsFiltered+'--');
					//console.log(response.data1['wpm']);
					//console.log(response.data1['wpm']);
                    //var history_table = $('#TypioHistory_table').DataTable();
                    //history_table.ajax.reload(null, false);
                }
            });
		
    });
	/**/


 $(document).on('click', '#BrailHistoryDateSection #BrailDateFilterBtn', function () {
        $('#BrailHistoryDateSection').addClass('ajax_loading');
        var BrailDatePicker1 = $('#BrailHistoryDateSection #BrailDatePicker1').val();
        var BrailDatePicker2 = $('#BrailHistoryDateSection #BrailDatePicker2').val();
        var student_id = $('#BrailHistoryDateSection #student_id').val();
        $('#BrailHistorySection .history_hide').removeClass('history_hide');
        $('#BrailHistorySection .cst_stu-typio').addClass('history_hide');
		$('.no-history-msg-wrap.cst_stu-brail').addClass('history_hide');
            var BrailDatePickerFrom = $("#BrailDatePicker1").val();
            var BrailDatePickerTo   = $("#BrailDatePicker2").val();
			$('.date1-braillio').text(BrailDatePickerFrom);
			$('.date2-braillio').text(BrailDatePickerTo);
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_brail_history','is_ajax' : '1','student_id':student_id,'BrailDatePickerFrom':BrailDatePickerFrom,'BrailDatePickerTo':BrailDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
					$('#braillio_wpm_cst').text(response.data1['wpm']+" WPM");
					$('#braillio_acc_cst').text(response.data1['acc']+"% Accuracy");
					$('#braillio_err_cst').text(response.data1['combo']+" Errors");
					$('#braillio_lesson_cst').text(response.recordsFiltered+" Lessons");
					
					$('.date1-braillio').text(BrailDatePickerFrom);
					$('.date2-braillio').text(BrailDatePickerTo);
					
                }
            });
           /**/ $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_Brail_ajax_date_field','is_ajax' : '1','BrailDatePickerFrom':BrailDatePickerFrom,'BrailDatePickerTo':BrailDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
                    var history_table = $('#BrailHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });   /**/
        // $.ajax({
        //     type: 'POST',
        //     url: ADMIN_URL + 'config/config-student.php',
        //     data: {action_type: 'get_history_data', start_date: BrailDatePicker1, end_date: BrailDatePicker2, student_id: student_id},
        //     async: true,
        //     cache: false,
        //     timeout: 10000,
        //     success: function (response) {
        //         var response = $.parseJSON(response);
        //         if (response.status == '200') {
        //             $('.total-row').html(response.total_row);
        //             $('#BrailHistorySection').find('#BrailHistory_table').html(response.html);
        //             $('#BrailHistorySection').find('.total-row').find('strong').html(response.total_row);
        //             //$('#BrailHistoryDateSection').removeClass('ajax_loading');
        //             $('#BrailHistorySection .history_hide').removeClass('history_hide');
        //             $('#BrailHistorySection .no-history-msg-wrap').hide();
        //         }
        //         var charts = $('#BrailHistoryAreaChart').highcharts();
        //         var options = charts.options;
        //         charts = new Highcharts.Chart(options);
        //         charts.redraw();
        //     },
        //     complete: function () {
        //         var charts = $('#BrailHistoryAreaChart').highcharts();
        //         var options = charts.options;
        //         charts = new Highcharts.Chart(options);
        //         charts.redraw();
        //     }
        // });
       
	    $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data_brail', start_date: BrailDatePicker1, end_date: BrailDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
				//console.log(response+' upp');
                var response = $.parseJSON(response);
				//console.log(response+' <<');
                if (response.status == '200') {
                    $('#BrailHistorySection').find('#BrailHistoryAreaChart_table').html(response.html);
                    $('#BrailHistoryDateSection').removeClass('ajax_loading');
                }
				//$('#BrailHistory_table tbody').append(response.html);
				
                var charts = $('#BrailHistoryAreaChart').highcharts();
                var options = charts.options;
				console.log(charts.options+" -- ");
                charts = new Highcharts.Chart(options);
                charts.redraw();
				
			}
				
        });
	   
	   
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data_brail', start_date: BrailDatePicker1, end_date: BrailDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
				
                var response = $.parseJSON(response);
				
				//console.log(response.html +' >>');
                if (response.status == '200') {
                    $('#BrailHistorySection').find('#historyBrail1Tab2_table').html(response.html);
                    $('#BrailHistoryDateSection').removeClass('ajax_loading');
                }
                var charts = $('#historyBrail1Tab2').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
				
				
            }
        });
		
		
    });
	$(document).on('click', '#TypiokpTypingJourneyBtn', function () {
		//alert(ADMIN_URL + 'config/config-student.php');
		
		 $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {'action': 'ajax_keyboard_progress_data', 'is_ajax': '1', 'student_id': '78', 'app_type': 'Typio-Journey'},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
				
                //var response = $.parseJSON(response);
				
				console.log(response +' >>');
               /* if (response.status == '200') {
                    $('#BrailHistorySection').find('#historyBrail1Tab2_table').html(response.html);
                    $('#BrailHistoryDateSection').removeClass('ajax_loading');
                }
                var charts = $('#historyBrail1Tab2').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();*/
				
				
            }
        });
	});
	$(document).on('click', '#TypiokpBasicModesBtn', function () {
		alert('TypiokpBasicModesBtn');
	});

	

$(document).on('click', '#BrailKeyboardProgressSection #BrailkpDateFilterBtn', function () {
        $('#BrailKeyboardProgressSection').addClass('ajax_loading');
        var BrailDatePicker1 = $('#BrailKeyboardProgressSection #BrailkpDatePicker1').val();
        var BrailDatePicker2 = $('#BrailKeyboardProgressSection #BrailkpDatePicker2').val();
        var student_id = $('#BrailKeyboardProgressSection #student_idkp').val();
        $('#BrailkpHistorySection .history_hide').removeClass('history_hide');
        $('#BrailkpHistorySection .cst_stu-brail').addClass('history_hide');
		$('.no-history-msg-wrap.cst_stu-brail_kp').addClass('history_hide');
            var BrailDatePickerFrom = $("#BrailkpDatePicker1").val();
            var BrailDatePickerTo   = $("#BrailkpDatePicker2").val();
			$('.date1-brailliokp').text(BrailDatePickerFrom);
			$('.date2-brailliokp').text(BrailDatePickerTo);
			
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_brail_history_kp','is_ajax' : '1','student_id':student_id,'BrailDatePickerFrom':BrailDatePickerFrom,'BrailDatePickerTo':BrailDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
					$('#braillio_wpm_cst_kp').text(response.data1['wpm']+" WPM");
					$('#braillio_acc_cst_kp').text(response.data1['acc']+"% Accuracy");
					$('#braillio_err_cst_kp').text(response.data1['combo']+" Errors");
					$('#braillio_lesson_cstkp').text(response.recordsFiltered+" Lessons");
					$('.date1-brailliokp').text(BrailDatePickerFrom);
					$('.date2-brailliokp').text(BrailDatePickerTo);
					
                }
            });
           /**/
		   $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_Brail_ajax_date_field_kp','is_ajax' : '1','BrailDatePickerFrom':BrailDatePickerFrom,'BrailDatePickerTo':BrailDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
                    var history_table = $('#BrailkpHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });   
       
       
	    $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data_brail_kp', start_date: BrailDatePicker1, end_date: BrailDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
				//console.log(response+' upp');
                var response = $.parseJSON(response);
				//console.log(response+' <<');
                if (response.status == '200') {
                    $('#BrailkpHistorySection').find('#BrailHistoryAreaChart_table_kp').html(response.html);
                    $('#BrailKeyboardProgressSection').removeClass('ajax_loading');
                }
				//$('#BrailHistory_table tbody').append(response.html);
				
                var charts = $('#BrailHistoryAreaChart_kp').highcharts();
                var options = charts.options;
				console.log(charts.options+" -- ");
                charts = new Highcharts.Chart(options);
                charts.redraw();
				
			}
				
        });
	   
	   
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data_brail_kp', start_date: BrailDatePicker1, end_date: BrailDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
				
                var response = $.parseJSON(response);
				
				//console.log(response.html +' >>');
                if (response.status == '200') {
                    $('#BrailkpHistorySection').find('#historyBrail1Tab2_table_kp').html(response.html);
                    $('#BrailKeyboardProgressSection').removeClass('ajax_loading');
                }
                var charts = $('#historyBrail1Tab2_kp').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
				
				
            }
        });
		// $('#BrailKeyboardProgressSection').removeClass('ajax_loading');
		
    });

$(document).on('click', '#BrailThisWeekFilterBtn', function () {
        $('#BrailHistoryDateSection').addClass('ajax_loading');
        var BrailDatePicker1 = $('#BrailHistoryDateSection #BrailThisWeekFilterstart').val();
        var BrailDatePicker2 = $('#BrailHistoryDateSection #BrailThisWeekFilterend').val();
        var student_id = $('#BrailHistoryDateSection #student_id').val();
        $('#BrailHistorySection .history_hide').removeClass('history_hide');
        $('.no-history-msg-wrap.cst_stu-brail').addClass('history_hide');
            var BrailDatePickerFrom = $("#BrailThisWeekFilterstart").val();
            var BrailDatePickerTo   = $("#BrailThisWeekFilterend").val();
			$('.date1-braillio').text(BrailDatePickerFrom);
			$('.date2-braillio').text(BrailDatePickerTo);
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_brail_history','is_ajax' : '1','student_id':student_id,'BrailDatePickerFrom':BrailDatePickerFrom,'BrailDatePickerTo':BrailDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
					$('#braillio_wpm_cst').text(response.data1['wpm']+" WPM");
					$('#braillio_acc_cst').text(response.data1['acc']+"% Accuracy");
					$('#braillio_err_cst').text(response.data1['combo']+" Errors");
					$('#braillio_lesson_cst').text(response.recordsFiltered+" Lessons");
					
					$('.date1-braillio').text(BrailDatePickerFrom);
					$('.date2-braillio').text(BrailDatePickerTo);
					
                }
            });
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_Brail_ajax_date_field','is_ajax' : '1','BrailDatePickerFrom':BrailDatePickerFrom,'BrailDatePickerTo':BrailDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
                    var history_table = $('#BrailHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });   
       
	    $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data_brail', start_date: BrailDatePicker1, end_date: BrailDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
				//console.log(response+' upp');
                var response = $.parseJSON(response);
				//console.log(response+' <<');
                if (response.status == '200') {
                    $('#BrailHistorySection').find('#BrailHistoryAreaChart_table').html(response.html);
                    $('#BrailHistoryDateSection').removeClass('ajax_loading');
                }
				//$('#BrailHistory_table tbody').append(response.html);
				
                var charts = $('#BrailHistoryAreaChart').highcharts();
                var options = charts.options;
				console.log(charts.options+" -- ");
                charts = new Highcharts.Chart(options);
                charts.redraw();
				
			}
				
        });
	   
	   
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data_brail', start_date: BrailDatePicker1, end_date: BrailDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
				
                var response = $.parseJSON(response);
				
				//console.log(response.html +' >>');
                if (response.status == '200') {
                    $('#BrailHistorySection').find('#historyBrail1Tab2_table').html(response.html);
                    $('#BrailHistoryDateSection').removeClass('ajax_loading');
                }
                var charts = $('#historyBrail1Tab2').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
				
				
            }
        });
		
    });
	
	
	
	


	$(document).on('click', '#BrailKeyboardProgressSection #BrailkpThisWeekFilterBtn', function () {
        $('#BrailKeyboardProgressSection').addClass('ajax_loading');
        var BrailDatePicker1 = $('#BrailKeyboardProgressSection #BrailkpThisWeekFilterstart').val();
        var BrailDatePicker2 = $('#BrailKeyboardProgressSection #BrailkpThisWeekFilterend').val();
        var student_id = $('#BrailKeyboardProgressSection #student_idkp').val();
        $('#BrailkpHistorySection .history_hide').removeClass('history_hide');
        $('.no-history-msg-wrap.cst_stu-brail').addClass('history_hide');
            var BrailDatePickerFrom = $("#BrailkpThisWeekFilterstart").val();
            var BrailDatePickerTo   = $("#BrailkpThisWeekFilterend").val();
			$('.date1-brailliokp').text(BrailDatePickerFrom);
			$('.date2-brailliokp').text(BrailDatePickerTo);
			$.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'ajax_student_brail_history_kp','is_ajax' : '1','student_id':student_id,'BrailDatePickerFrom':BrailDatePickerFrom,'BrailDatePickerTo':BrailDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
					var response = $.parseJSON(output);
						//console.log(response.data1['wpm']+"WPM");
					$('#braillio_wpm_cst_kp').text(response.data1['wpm']+" WPM");
					$('#braillio_acc_cst_kp').text(response.data1['acc']+"% Accuracy");
					$('#braillio_err_cst_kp').text(response.data1['combo']+" Errors");
					$('#braillio_lesson_cstkp').text(response.recordsFiltered+" Lessons");
					$('.date1-brailliokp').text(BrailDatePickerFrom);
					$('.date2-brailliokp').text(BrailDatePickerTo);
					
                }
            });
           /**/
		   $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_Brail_ajax_date_field_kp','is_ajax' : '1','BrailDatePickerFrom':BrailDatePickerFrom,'BrailDatePickerTo':BrailDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
				
                    var history_table = $('#BrailkpHistory_table').DataTable();
                    history_table.ajax.reload(null, false);
                }
            });   
       
       
	    $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_chart_data_brail_kp', start_date: BrailDatePicker1, end_date: BrailDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
				//console.log(response+' upp');
                var response = $.parseJSON(response);
				//console.log(response+' <<');
                if (response.status == '200') {
                    $('#BrailkpHistorySection').find('#BrailHistoryAreaChart_table_kp').html(response.html);
                    $('#BrailKeyboardProgressSection').removeClass('ajax_loading');
                }
				//$('#BrailHistory_table tbody').append(response.html);
				
                var charts = $('#BrailHistoryAreaChart_kp').highcharts();
                var options = charts.options;
				console.log(charts.options+" -- ");
                charts = new Highcharts.Chart(options);
                charts.redraw();
				
			}
				
        });
	   
	   
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_history_average_chart_data_brail_kp', start_date: BrailDatePicker1, end_date: BrailDatePicker2, student_id: student_id},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
				
                var response = $.parseJSON(response);
				
				//console.log(response.html +' >>');
                if (response.status == '200') {
                    $('#BrailkpHistorySection').find('#historyBrail1Tab2_table_kp').html(response.html);
                    $('#BrailKeyboardProgressSection').removeClass('ajax_loading');
                }
                var charts = $('#historyBrail1Tab2_kp').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();
				
				
            }
        });
		
    });



    // Student JS
	$(document).on('click', '#cst-dev-typio-copy', function () {
	let textArray = [];
	let p_text  		=  $(".text-left.typio_lesson_cst_cls.typio_lesson_main_cls").text().trim();
	let date_from_text  =  $(".date1-typio").text().trim();
	let date_to_text  	=  $(".date2-typio").text().trim();
	let wpm_text  		=  $("#typio_wpm_cst").text().trim();
	let acc_text  		=  $("#typio_acc_cst").text().trim();
	let err_text  		=  $("#typio_err_cst").text().trim();
    
	let finalText = [
        p_text,
        date_from_text + " - " + date_to_text,   // added dash for readability
        "" + wpm_text,
        "" + acc_text,
        "" + err_text
    ].join(", ");

    // Copy to clipboard
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(finalText).then(() => {
            //alert("Copied: " + finalText);
			$("#copyMessage").fadeIn(200).delay(1000).fadeOut(400);
        });
    } else {
        // fallback for old browsers
        let temp = '';
        $("body").append(temp);
        temp.val(finalText).select();
        document.execCommand("copy");
        temp.remove();
        //alert("Copied: " + finalText);
    }
	//Averages out of 5 Lessons, 07/01/2025 - 09/11/2025, 15 WPM, 88% Accuracy, 8 Errors
	//Averages out of 24 lessons, 08/17/2025 - 08/19/2025: 7 WPM, 89% Accuracy, 5 Errors.
	
	});
	$(document).on('click', '#cst-kpdev-typio-copy', function () {
	let textArray = [];
	let p_text  		=  $(".text-left.typio_lesson_cst_cls.typio_lesson_cstkp").text().trim();
	let date_from_text  =  $(".date1-typiokp").text().trim();
	let date_to_text  	=  $(".date2-typiokp").text().trim();
	let wpm_text  		=  $("#typiokp_wpm_cst").text().trim();
	let acc_text  		=  $("#typiokp_acc_cst").text().trim();
	let err_text  		=  $("#typiokp_err_cst").text().trim();
    
	let finalText = [
        p_text,
        date_from_text + " - " + date_to_text,   // added dash for readability
        "" + wpm_text,
        "" + acc_text,
        "" + err_text
    ].join(", ");

    // Copy to clipboard
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(finalText).then(() => {
            //alert("Copied: " + finalText);
			$("#copyMessagekp").fadeIn(200).delay(1000).fadeOut(400);
        });
    } else {
        // fallback for old browsers
        let temp = '';
        $("body").append(temp);
        temp.val(finalText).select();
        document.execCommand("copy");
        temp.remove();
        //alert("Copied: " + finalText);
    }
	//Averages out of 5 Lessons, 07/01/2025 - 09/11/2025, 15 WPM, 88% Accuracy, 8 Errors
	//Averages out of 24 lessons, 08/17/2025 - 08/19/2025: 7 WPM, 89% Accuracy, 5 Errors.
	
	});

$(document).on('click', '#cst-dev-braillio-copy', function () {
		
		
	let textArray = [];
	let p_text  		=  $(".braillio_lesson_cst_cls").text().trim();
	let date_from_text  =  $(".date1-braillio").text().trim();
	let date_to_text  	=  $(".date2-braillio").text().trim();
	let wpm_text  		=  $("#braillio_wpm_cst").text().trim();
	let acc_text  		=  $("#braillio_acc_cst").text().trim();
	let err_text  		=  $("#braillio_err_cst").text().trim();
	let finalText = [
        p_text,
        date_from_text + " - " + date_to_text, 
        "" + wpm_text,
        "" + acc_text,
        "" + err_text
    ].join(", ");

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(finalText).then(() => {
			$("#braillio_copyMessage").fadeIn(200).delay(1000).fadeOut(400);
        });
    } else {
        let temp = '';
        $("body").append(temp);
        temp.val(finalText).select();
        document.execCommand("copy");
        temp.remove();
        
    }
	//Averages out of 5 Lessons, 07/01/2025 - 09/11/2025, 15 WPM, 88% Accuracy, 8 Errors
	//Averages out of 24 lessons, 08/17/2025 - 08/19/2025: 7 WPM, 89% Accuracy, 5 Errors.
	
	});

$(document).on('click', '.cst-dev-braillio-copykp', function () {
		
		
	let textArray = [];
	let p_text  		=  $(".braillio_lesson_cst_clskp").text().trim();
	let date_from_text  =  $(".date1-brailliokp").text().trim();
	let date_to_text  	=  $(".date2-brailliokp").text().trim();
	let wpm_text  		=  $("#braillio_wpm_cst_kp").text().trim();
	let acc_text  		=  $("#braillio_acc_cst_kp").text().trim();
	let err_text  		=  $("#braillio_err_cst_kp").text().trim();
	let finalText = [
        p_text,
        date_from_text + " - " + date_to_text, 
        "" + wpm_text,
        "" + acc_text,
        "" + err_text
    ].join(", ");

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(finalText).then(() => {
			$("#braillio_copyMessagekp").fadeIn(200).delay(1000).fadeOut(400);
        });
    } else {
        let temp = '';
        $("body").append(temp);
        temp.val(finalText).select();
        document.execCommand("copy");
        temp.remove();
        
    }
	//Averages out of 5 Lessons, 07/01/2025 - 09/11/2025, 15 WPM, 88% Accuracy, 8 Errors
	//Averages out of 24 lessons, 08/17/2025 - 08/19/2025: 7 WPM, 89% Accuracy, 5 Errors.
	
	});

    //Delete record

    $(document).on('click', '.history-delete', function () {

        var this_data = $(this);

        var total_row = $('.total-row').text();

        total_row = parseInt(total_row);



        bootbox.confirm({

            message: "Are you sure, you want to delete custom lesson?",

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

                    var type = 'historys-delete';

                    $.ajax({

                        type: 'POST',

                        url: ADMIN_URL + 'config/config-typio.php',

                        data: {'delete_id': delete_id, 'type': type},

                        async: true,

                        cache: false,

                        timeout: 10000,

                        success: function (response) {

                            var response = $.parseJSON(response);



                            if (response.status == 1) {

                                this_data.closest('div').find('.quick-ajax-response .alert').removeClass('alert-warning alert-success').html(response.msg).show();

                                this_data.closest('div').find('.quick-ajax-response').show();

                            } else {

                                this_data.closest('div').find('.quick-ajax-response .alert').removeClass('alert-warning alert-success').html(response.msg).show();

                                this_data.closest('div').find('.quick-ajax-response').show();

                            }

                            total_row = total_row - 1;



                            $('.total-row').text(total_row);

                            this_data.closest('div').find('.quick-ajax-response').delay(5000).fadeOut(1000);

                            this_data.closest("tr").remove();

                        },

                        error: function (xhr, status, err) {



                        }

                    });

                }

            }

        });

    });

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    $(document).on('click', '.history-play', function () {
        var this_data = $(this);
        var delete_id = this_data.attr('data-id');

        var html = '<iframe src="https://www.accessibyte.com/apps/typio_replay/index.html" frameborder="0" height="500" width="780"></iframe>';
        $('#play-history-modal').find('.modal-body').html(html);
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-typio.php',
            data: {'delete_id': delete_id, 'type': 'play'},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                response = JSON.parse(response);
                //window.open(WEB_PATH + 'apps/typio_replay/index.html', "myWindow", "width=1200,height=800"); 
                $('#play-history-modal .modal-title').addClass('color');
                $('#play-history-modal').removeClass('modal-success modal-warning');
                setCookie('TY-Replay', response.TY_Replay, 365);
            },
            error: function (xhr, status, err) {

            }
        });
        $('#play-history-modal').modal('show');
    });


    //Delete record

    $(document).on('click', '.this-history-delete', function () {

        var this_data = $(this);

        bootbox.confirm({

            message: "Are you sure, you want to delete custom lesson?",

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

                    var type = 'historys-delete';

                    $.ajax({

                        type: 'POST',

                        url: ADMIN_URL + 'config/config-typio.php',

                        data: {'delete_id': delete_id, 'type': type},

                        async: true,

                        cache: false,

                        timeout: 10000,

                        success: function (response) {

                            var response = $.parseJSON(response);

                            this_data.closest("tr").remove();

                            if (response.status == 1) {

                                $('.quick-card-history-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                                $('.quick-card-history-ajax-response').show();

                                var table = $('#student_quick_cards_week_list_table').DataTable();
                                table.clear().draw();

                                var table = $('#student_quick_cards_history_list_table').DataTable();
                                table.clear().draw();



                            } else {

                                $('.quick-card-history-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                                $('.quick-card-history-ajax-response').show();

                            }

                            $('.quick-card-history-ajax-response').delay(5000).fadeOut(1000);

                        },

                        error: function (xhr, status, err) {



                        }

                    });

                }

            }

        });

    });


	/*$(document).on('click', '#import_csv_typio', function () {
        var student_id = $('#user_id').val();
        var file_data = $('#typio_file_upload').prop('files')[0];  
        var file_name =$('#typio_file_upload').val();  
        var extension = file_name.substr( (file_name.lastIndexOf('.') +1) ); 

        if(extension != "csv"){
            alert("Please select csv file upload for import data");
            return false;
        }

        var form_data = new FormData();                  
        form_data.append('file', file_data);
        form_data.append('student_id', student_id);
        form_data.append('insert_type', 'Typio-OL');
        form_data.append('type', 'typio-csv-import-entry');
       
        var file_name = $('#typio_file_upload')[0].files[0];   
       

        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-typio.php',
            data :form_data,
            async: true,
            cache: false,
            timeout: 10000,
            contentType: false,
            processData: false,
            success: function (response) {

                var response = $.parseJSON(response);
                if (response.status == 1) {
                    $('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    $('.custom_lessons .quick-ajax-response').show();
                    $("#typio-custom-lessons").append(response.html);

                } else {
                    $('.custom_lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    $('.custom_lessons .quick-ajax-response').show();

                }
                $("#add-typio-csv-import-form")[0].reset();
                $("#typio-import-csv_modal").modal('hide');
                $('.custom_lessons .quick-ajax-response').delay(5000).fadeOut(1000);
            },

            error: function (xhr, status, err) {

            }

        });
    });*/








});