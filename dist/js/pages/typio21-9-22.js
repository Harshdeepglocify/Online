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
        setTimeout(function (){
                $('#typio_import_code').focus();
            }, 500);
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
        var data_type = $(this).attr('data-type');
        var label = $(this).attr('data-label');
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
            var typioDatePickerFrom = $("#TypioDatePicker1").val();
            var typioDatePickerTo   = $("#TypioDatePicker2").val();
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action':'set_typio_ajax_date_field','is_ajax' : '1','typioDatePickerFrom':typioDatePickerFrom,'typioDatePickerTo':typioDatePickerTo},
                async: true,
                cache: false,
                success: function (output) {
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
    });





    // Student JS





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