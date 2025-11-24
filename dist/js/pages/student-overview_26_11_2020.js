$(function () {



    $.fn.setCursorToTextEnd = function () {

        this.focus();

        var $thisVal = this.val();

        this.val('').val($thisVal);

        return this;

    }



//Inline Edit

    $(document).on('click', '.editInline', startInline);

    //Save inline edit fields data

    $(document).on('focusout', '.saveInline', saveInline);

    //    $(".saveInline").click(saveInline);

    function startInline() {

        //Targe element id to edit

        var editTarget = $(this).attr('data-target');

        //Datarecord name

        var editName = $(this).attr('data-name');

        //Generate input

        var editInput = $('<input type="text" style="width:50%" name="' + editName + '" />');

        //Value to edit

        var editVal = $("span#" + editTarget).html();



        var editSpan = $("span#" + editTarget);

        //change Icon

        //var newIcon = $('<i class="fa fa-check"></i>');



        editInput.val(editVal.trim());

        editSpan.replaceWith(editInput);

        // $(this).html(newIcon);



        $(this).removeClass('editInline').addClass('saveInline');

        editInput.focus();

    }

    function saveInline() {

        //Targe element id to save

        var saveTarget = $(this).attr('data-target');

        //Datarecord name

        var saveName = $(this).attr('data-name');

        var input = $("input[name=" + saveName + "]");

        var saveVal = $("input[name=" + saveName + "]").val();

        var viewableText = $('<span id="' + saveTarget + '"></span>');

        //var editIcon = $('<i class="fa fa-pencil"></i>');

        // var saveIcon = $('<i class="fa fa-check"></i>');

        //var spinner = $('<i class="fa fa-refresh fa-spin" aria-hidden="true"></i>');

        //Because Ajax does not reconige $(this);

        var element = $(this);

        //element.html(spinner);

        element.attr('disabled', true);

        var isError = false;

        if (saveVal.trim() == "") {

            $("#" + saveName + "div").append('<label class="error" id="' + saveName + 'error" style="color:#ff0000;">"' + saveTarget + '" can not be null</label>');

            input.focus();

            // element.html(saveIcon);

            element.attr('disabled', false);

            isError = true;

            return false;

        }

        if (saveName == 'email') {

            if (isValidEmail(saveVal) == false) {

                $("#" + saveName + "div").append('<label class="error" id="' + saveName + 'error" style="color:#ff0000;">Invalid Email</label>');

                input.focus();

                //element.html(saveIcon);

                element.attr('disabled', false);

                isError = true;

                return false;

            }

        }

        $("div#" + saveTarget + "div").html(viewableText);

        $("#" + saveTarget).html(saveVal);

        $("label#" + saveName + "error").remove();

        element.removeClass('saveInline');

        element.addClass('editInline');

        //element.html(editIcon);

        element.attr('disabled', false);

        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config.php',

            data: {'field': saveName, 'value': saveVal},

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                if (response.result.status == '200') {

                    $("div#" + saveTarget + "div").html(viewableText);

                    $("#" + saveTarget).html(saveVal);

                    $("label#" + saveName + "error").remove();

                    if (saveName === 'fullname') {

                        $("#usernamehead").html(saveVal);

                    }

                    element.removeClass('saveInline');

                    element.addClass('editInline');

                    //element.html(editIcon);

                    element.attr('disabled', false);

                }

            },

            error: function (xhr, status, err) {

                $("div#" + saveTarget + "div").html(viewableText);

                $("#" + saveTarget).html(saveVal);

                $("label#" + saveName + "error").remove();

                element.removeClass('saveInline');

                element.addClass('editInline');

                //element.html(editIcon);

                element.attr('disabled', false);

            }

        });

    }

    //Initialize Select2 Elements

    //$('.select2').select2();



    //Date picker

    $('.datepicker').datepicker({

        autoclose: true,

        orientation: "bottom"

    });



    $("#TypioDatePicker1").datepicker().on('changeDate', function (selected) {

        var minDate = new Date(selected.date.valueOf());

        $('#TypioDatePicker2').datepicker('setStartDate', minDate);

    });

    $("#TypioDatePicker2").datepicker().on('changeDate', function (selected) {

        var maxDate = new Date(selected.date.valueOf());

        $('#TypioDatePicker1').datepicker('setEndDate', maxDate);

    });

    $("#ArcadeDatePickerFrom").datepicker().on('changeDate', function (selected) {

        var minDate = new Date(selected.date.valueOf());

        $('#ArcadeDatePickerTo').datepicker('setStartDate', minDate);

    });

    $("#ArcadeDatePickerTo").datepicker().on('changeDate', function (selected) {

        var maxDate = new Date(selected.date.valueOf());

        $('#ArcadeDatePickerFrom').datepicker('setEndDate', maxDate);

    });

    var TypioHistoryDateSubmitCheck = $('#TypioHistoryDateSubmitCheck').val();

    var ArcadeHistoryDateSubmitCheck = $('#ArcadeHistoryDateSubmitCheck').val();

    if (TypioHistoryDateSubmitCheck == 'Checked') {

        $('#typioTab').trigger('click');

        $('html, body').animate({scrollTop: $("#TypioHistoryDateSection").offset().top}, 500);

    }

    if (ArcadeHistoryDateSubmitCheck == 'Checked') {

        $('#arcade').trigger('click');

        $('html, body').animate({scrollTop: $("#ArcadeHistoryDateSection").offset().top}, 500);

    }

    var TypioSettingSubmitCheck = $('#TypioSettingSubmitCheck').val();

    if (TypioSettingSubmitCheck == 'Checked') {

        $('#typioTab').trigger('click');

        $('html, body').animate({scrollTop: $("#TypioSettingsSection").offset().top}, 500);

    }



    /*Quick Cards Section */

    $('#Quickcards_weekly_report').highcharts({

        exporting: {enabled: false},

        data: {

            table: 'Quickcards_weekly_report_table'

        },

        chart: {

            type: 'areaspline'

        },

        title: {

            text: 'Weekly Chart'

        },

        // subtitle: {

        //     text: false

        // },

        xAxis: {

            allowDecimals: false,

            labels: {

                formatter: function () {

                    return this.value; // clean, unformatted number for year

                }

            }

        },

        yAxis: {

            title: {

                text: 'Score'

            },

            labels: {

                formatter: function () {

                    return this.value; // 1000 + 'k';

                }

            }

        },

        legend: {

            enabled: false

        },

        credits: {

            enabled: false

        },

        tooltip: {

            pointFormat: '<b>{point.y:,.0f}%</b>'

        },

        plotOptions: {

            area: {

                pointStart: 1940,

                marker: {

                    enabled: false,

                    symbol: 'circle',

                    radius: 2,

                    states: {

                        hover: {

                            enabled: true

                        }

                    }

                }

            }

        }

    });



});



function qc_general_chart() {



    $('#Quickcards_general_report').highcharts({

        exporting: {enabled: false},

        data: {

            table: document.getElementById('Quickcards_general_report_tabel')

        },

        chart: {

            type: 'areaspline',

            renderTo: document.getElementById('Quickcards_general_report')

        },

        title: {

            text: 'History'

        },

        // subtitle: {

        //     text: 'History'

        // },

        xAxis: {

            allowDecimals: false,

            labels: {

                formatter: function () {

                    return this.value; // clean, unformatted number for year

                }

            }

        },

        yAxis: {

            title: {

                text: 'Score'

            },

            labels: {

                formatter: function () {

                    return this.value; // 1000 + 'k';

                }

            }

        },

        legend: {

            enabled: false

        },

        credits: {

            enabled: false

        },

        tooltip: {

            pointFormat: '{point.y:,.0f}%'

        },

        plotOptions: {

            area: {

                pointStart: 1940,

                marker: {

                    enabled: false,

                    symbol: 'circle',

                    radius: 2,

                    states: {

                        hover: {

                            enabled: true

                        }

                    }

                }

            }

        }

    });

}



jQuery(document).ready(function ($) {



    //Call Quick Cards general area chart

    qc_general_chart();



    $(document).on('click', '.edit-data-modal', function () {



        var table_id = $(this).attr('data-id');

        var type = 'Decks-edit';



        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-quick-cards.php',

            data: {'table_id': table_id, type: type},

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == '200') {

                    $('#edit-modal-set').removeClass('modal-success');

                    $('#edit-modal-set').find('.edit-modal-save').show();

                    $('#edit-modal-set').find('.modal-body').html(response.html);

                }

            },

            error: function (xhr, status, err) {



            }

        });

        $('#edit-modal-set').modal('show');

    });



    $(document).on('click', '.edit-modal-save1', function () {



        var form_data = $('#decks-edit-form').serialize();

        var type = 'Decks-edit';



        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-quick-cards.php',

            data: {'form_data': form_data, 'type': type},

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == '1') {

                    $('#edit-modal-set').addClass('modal-success');

                    $('#edit-modal-set').find('.modal-body').html(response.msg);

                    $('#edit-modal-set').find('.edit-modal-save').hide();

                } else {

                    $('#edit-modal-set').addClass('modal-warning');

                    $('#edit-modal-set').find('.modal-body').html(response.msg);

                    $('#edit-modal-set').find('.edit-modal-save').hide();

                }

            },

            error: function (xhr, status, err) {



            }

        });

    });



    $(document).on('click', '.student-decks-maker_type_1_delete', function () {

        var this_data = $(this);

        bootbox.confirm({

            message: "Are you sure, you want to Delete?",

            buttons: {

                confirm: {

                    label: 'Yes',

                    className: 'dashboard-settings-btn btn-block'

                },

                cancel: {

                    label: 'No',

                    className: 'dashboard-settings-btn btn-block'

                }

            },

            callback: function (result) {



                if (result) {



                    var delete_id = this_data.attr('data-id');

                    var type = 'Decks-delete';



                    $.ajax({

                        type: 'POST',

                        url: ADMIN_URL + 'config/config-quick-cards.php',

                        data: {'delete_id': delete_id, 'type': type},

                        async: true,

                        cache: false,

                        timeout: 10000,

                        success: function (response) {

                            var response = $.parseJSON(response);

                            this_data.closest("tr").hide();

                            if (response.status == 1) {

                                $('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();

                                $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                            } else {

                                $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                            }

                            $('.student-decks-maker_type_1 .quick-ajax-response').delay(5000).fadeOut(1000);

                        },

                        error: function (xhr, status, err) {



                        }

                    });

                }

            }

        });

    });



    $(document).on('click', '#qc-date-filter', function () {



        var start_date = $('#qc-start-date').val();

        var end_date = $('#qc-end-date').val();

        var student_id = $('#qc-student-id').val();



        if (start_date != "" && end_date != "") {

            $.ajax({

                type: 'POST',

                url: ADMIN_URL + 'config/config-quick-cards.php',

                data: {start_date: start_date,

                    end_date: end_date,

                    type: 'quick-card-history',

                    student_id: student_id,

                },

                async: true,

                cache: false,

                timeout: 10000,

                success: function (response) {

                    var response = $.parseJSON(response);

                    if (response.status == 1) {

                        if (response.chart_html) {

                            $('#qc-history-section').find('#Quickcards_general_report_tabel tbody').html(response.chart_html);

                        }

                        if (response.table_html) {

                            $('#qc-history-section').find('#Quickcards_general_report_display tbody').html(response.table_html);

                            $('#qc-history-section').find('#qc-table-display-wrap .box-header h3 strong').html(response.table_count_msg);

                        }

                    } else {

                        $('#qc-history-section').find('#qc-table-display-wrap .box-header h3 strong').html(response.table_count_msg);

                        $('#qc-history-section').find('#Quickcards_general_report_tabel tbody').html('');

                        $('#qc-history-section').find('#Quickcards_general_report_display tbody').html('');

                    }

                    $('#qc-history-section .history_hide').removeClass('history_hide');
                  	$('#qc-history-section .no-history-msg-wrap').hide();

                    var qcharts = $('#Quickcards_general_report').highcharts();

                    var qoptions = qcharts.options;

                    qcharts = new Highcharts.Chart(qoptions);

                    qcharts.redraw();

                },

                error: function (xhr, status, err) {



                }

            });

        }

    });



    $(document).on('click', '#qc-settings-submit', function () {



        var form_data = $('#qc-settings-form').serialize();

        var student_id = $('#qc-student-id').val();



        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-quick-cards.php',

            data: {form_data: form_data,

                student_id: student_id,

                type: 'qc-settings-process',

            },

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == 1) {

                    //$('.qc-setting-alert').removeClass('alert-success alert-warning').addClass('alert-success').html(response.msg);

                    //$('.qc-setting-alert').show();

                    window.location.reload();

                } else {

                    // $('.qc-setting-alert').removeClass(' alert-warning  alert-success').addClass('alert-warning').html(response.msg);

                    //$('.qc-setting-alert').show();

                }

            },

            error: function (xhr, status, err) {

            }

        });

    });



    /**

     * Saving Pro Pack data

     */

    $(document).on('click', '#pro-pack-settings-submit', function () {



        var form_data = $('#pro-pack-settings-form').serialize();

        var student_id = $('#pro-pack-student-id').val();



        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-quick-cards.php',

            data: {form_data: form_data,

                student_id: student_id,

                type: 'pro-pack-settings-process',

            },

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == 1) {

                    window.location.reload();

                }

            },

            error: function (xhr, status, err) {

            }

        });

    });



    var max_fields = 8;

    var x = 1;

    //Add new fileds

    $(document).on('click', '.add-new-field', function () {

        if (x < max_fields) {

            $('.add-new-wrap').append('<div class="form-group"><input id="qc-cards-new-' + x + '" name="qc-cards-new[]" class="form-control" placeholder="Card Name" type="text"><a href="javascript:void(0)" class="remove-new-field">Remove</a></div>');

            x++;

        }

    });



    //Remove Add new fields

    $(document).on('click', '.remove-new-field', function () {

        $(this).parent('div').remove();

        x--;

    });



    //New Decks save process

    $(document).on('click', '#add-new-decks', function () {



        if ($('#qc-title-text-new').val() == "") {

            return false;

        }



        var decks_new_data = $('#new-decks-add-form').serialize();

        var student_id = $('#qc-student-id').val();



        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-quick-cards.php',

            data: {

                decks_new_data: decks_new_data,

                student_id: student_id,

                type: 'decks-new-entry',

            },

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == 1) {

                    $('.quick-card-lessons .quick-ajax-response').fadeIn();

                    $('.quick-card-lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();



                } else {

                    $('.quick-card-lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();



                }

                $('#new-decks-add-form #qc-title-text-new').val('');

                $('#new-decks-add-form #qc-cards-new-0').val('');

                $('#new-decks-add-form .remove-new-field').trigger('click');

                $('.quick-card-lessons .quick-ajax-response').delay(5000).fadeOut(1000);

            },

            error: function (xhr, status, err) {

            }

        });

    });



    //share for teacher ---Custom Lessons

    $(document).on('click', '.quick-card-share-submit', function () {



       

        var share_ids = [];

        var i = 0;



        $('.share_quick_card_deck_ids:checked').each(function () {

            share_ids[i++] = $(this).val();

        });



        if (share_ids == '') {

            $('.quick-card-lessons .quick-ajax-response').fadeIn();

            $('.quick-card-lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html('Please select at least one custom lesson.').show();



            $('.quick-card-lessons .quick-ajax-response').delay(5000).fadeOut(1000);

            return false;

        } else {

            $('.quick-card-lessons .quick-ajax-response').removeClass(' alert-warning  alert-success').hide();

        }
        var share_user_ids = [];
		var j = 0;
		$('.share_quick_card_user_ids:checked').each(function () {
            share_user_ids[j++] = $(this).val();
		});
		      


        if (share_user_ids == '') {

            $('.quick-card-lessons .quick-ajax-response').fadeIn();

            $('.quick-card-lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html('Please select at least one student.').show();



            $('.quick-card-lessons .quick-ajax-response').delay(5000).fadeOut(1000);

            return false;

        } else {

            $('.quick-card-lessons .quick-ajax-response').removeClass(' alert-warning  alert-success').hide();

        }



        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-student.php',

            data: {

                'type': 'lessons',

                'share_ids': share_ids,

                'share_user_list': share_user_ids

            },

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == 1) {

                    $('.quick-card-lessons .quick-ajax-response').fadeIn();

                    $('.quick-card-lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();



                    $('.share_quick_card_ids').removeAttr('checked');

                    $("option:selected").removeAttr("selected");

                } else {

                    $('.quick-card-lessons .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();



                    $('.quick-card-lessons .share_quick_card_ids').removeAttr('checked');

                }

                $('.quick-card-lessons .quick-ajax-response').delay(5000).fadeOut(1000);



            },

            error: function (xhr, status, err) {



            }

        });

    });



    //share for teacher ---quick card deck

    $(document).on('click', '.quick-card-deck-share-submit', function () {



        var share_ids = [];
		var share_user_list = [];

        var i = 0;





        $('.share_quick_card_deck_ids:checked').each(function () {

            share_ids[i++] = $(this).val();

        });

		

        if (share_ids == '') {

            $('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();

            $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html('Please select at least one deck name.').show();



            $('.student-decks-maker_type_1 .quick-ajax-response').delay(5000).fadeOut(1000);

            return false;

        } else {

            $('.student-decks-maker_type_1 .quick-ajax-response').removeClass(' alert-warning  alert-success').hide();

        }

		
		var j = 0;
		$('.share_quick_card_user_ids:checked').each(function () {
			share_user_list[j++] = $(this).val();
		});

        if (share_user_list == '') {

            $('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();

            $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html('Please select at least student.').show();



            $('.student-decks-maker_type_1 .quick-ajax-response').delay(5000).fadeOut(1000);

            return false;

        } else {

            $('.student-decks-maker_type_1 .quick-ajax-response').removeClass(' alert-warning  alert-success').hide();

        }



        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-student.php',

            data: {

                'type': 'deck',

                'share_ids': share_ids,

                'share_user_list': share_user_list

            },

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == 1) {

                    $('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();

                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();



                    $('.share_quick_card_deck_ids').removeAttr('checked');

                    $("option:selected").removeAttr("selected");

                } else {

                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();



                    $('.student-decks-maker_type_1 .share_quick_card_deck_ids').removeAttr('checked');

                }

                $('.student-decks-maker_type_1 .quick-ajax-response').delay(5000).fadeOut(1000);

            },

            error: function (xhr, status, err) {



            }

        });

    });



    //share for teacher ---quick card deck

    $(document).on('click', '.quick-card-test-share-submit', function () {



        

        var share_ids = [];
        var share_user_list = [];
        var i = 0;





        $('.share_quick_card_test_ids:checked').each(function () {

            share_ids[i++] = $(this).val();

        });



        if (share_ids == '') {

            $('.student-decks-maker_type_2 .quick-ajax-response').fadeIn();

            $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html('Please select at least one test.').show();

            $('.student-decks-maker_type_2 .quick-ajax-response').delay(5000).fadeOut(1000);

            return false;

        } else {

            $('.student-decks-maker_type_2 .quick-ajax-response').removeClass(' alert-warning  alert-success').hide();

        }

        var j = 0;
		$('.share_user_ids:checked').each(function () {
			share_user_list[j++] = $(this).val();
		});

        if (share_user_list == '') {

            $('.student-decks-maker_type_2 .quick-ajax-response').fadeIn();

            $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html('Please select at least one student.').show();

            $('.student-decks-maker_type_2 .quick-ajax-response').delay(5000).fadeOut(1000);

            return false;

        } else {

            $('.quick-ajax-response').removeClass(' alert-warning  alert-success').hide();

        }



        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-student.php',

            data: {

                'type': 'test',

                'share_ids': share_ids,

                'share_user_list': share_user_list

            },

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == 1) {

                    $('.student-decks-maker_type_2 .quick-ajax-response').fadeIn();

                    $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                    $('.student-decks-maker_type_2 .quick-ajax-response').delay(5000).fadeOut(1000);

                    $('.share_quick_card_test_ids').removeAttr('checked');

                    $(".share_user_ids").removeAttr("checked");

                } else {

                    $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                    $('.student-decks-maker_type_2 .quick-ajax-response').delay(5000).fadeOut(1000);

                    $('.student-decks-maker_type_2 .share_quick_card_test_ids').removeAttr('checked');
                    $('.student-decks-maker_type_2 .share_user_ids').removeAttr('checked');

                }



            },

            error: function (xhr, status, err) {



            }

        });

    });
	$(document).on('click', '.deck-export-modal', function () {
		table_id = $('.table_id').val();
		var student_id = $('#qc-student-id').val();
		var dt = new Date().getTime();
		var uuid = 'xxxx-xxxx'.replace(/[xy]/g, function(c) {
			var r = (dt + Math.random()*16)%16 | 0;
			dt = Math.floor(dt/16);
			return (c=='x' ? r :(r&0x3|0x8)).toString(16);
		});
		
		$('.code_copy').html("<input type='text' class='form-control' name='code_copy' id='code_copy' value='"+uuid+"'>");  
		 var copyText = $('#code_copy');
		  copyText.select();
		  
		  document.execCommand("copy");
		
		$.ajax({

			

			type: 'POST',

			url: ADMIN_URL + 'config/config-quick-cards.php',

			data: {

				students_decks_new_data: uuid,

				student_id: student_id,
				table_id: table_id,

				type: 'student-decks-export-entry',

			},

			async: true,

			cache: false,

			timeout: 10000,

			success: function (response) {

				var response = $.parseJSON(response);



				if (response.status == 1) {
					
						
				   
					$('.msg').fadeIn();

					$('.msg').html(response.msg).show();

					$('.msg').find('.alert').show();

					




				} else {

				

					$('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html(response.msg).show();

					$('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').show();

				}

				$('.student-decks-maker_type_1 .quick-ajax-response').delay(5000).fadeOut(1000);


			},

			error: function (xhr, status, err) {

			}

		});
	});
	$(document).on('click', '.deck-test-export-modal', function () {
		table_id = $('.table_id_in_edit').val();
		var student_id = $('#qc-student-id').val();
		var dt = new Date().getTime();
		var uuid = 'xxxx-xxxx'.replace(/[xy]/g, function(c) {
			var r = (dt + Math.random()*16)%16 | 0;
			dt = Math.floor(dt/16);
			return (c=='x' ? r :(r&0x3|0x8)).toString(16);
		});
		
		$('.test_code_copy').html("<input type='text' class='form-control' name='test_code_copy' id='test_code_copy' value='"+uuid+"'>");  
		var copyText = $('#test_code_copy');
		copyText.select();
		document.execCommand("copy");
		$.ajax({

			

			type: 'POST',

			url: ADMIN_URL + 'config/config-quick-cards.php',

			data: {

				students_test_data: uuid,

				student_id: student_id,
				table_id: table_id,

				type: 'student-decks-test-export-entry',

			},

			async: true,

			cache: false,

			timeout: 10000,

			success: function (response) {

				var response = $.parseJSON(response);



				if (response.status == 1) {
					
						

	//alert("Copied the text: " + copyText.val());
				   
					$('.test-msg').fadeIn();

					$('.test-msg').html(response.msg).show();

					$('.test-msg').find('.alert').show();

					




				} else {

					//$('.quick-ajax-response').find('.alert').removeClass(' alert-warning  alert-success').addClass('alert-warning').html(response.msg);

					$('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html(response.msg).show();

					$('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').show();

				}

				$('.student-decks-maker_type_2 .quick-ajax-response').delay(5000).fadeOut(1000);

				//$('#deck-maker-modal .remove-new-field').trigger('click');

			},

			error: function (xhr, status, err) {

			}

		});
	});


});





//Decks Maker Module

//New Decks save process

var desk_title_alert = 'Deck name field required';

var desk_add_card = 'Press Add card to deck Button';

var side_alert = 'Side A field required';



$("#deck-maker-modal").on("hidden.bs.modal", function () {

    $("#append_data").html("");

    $('#deck_name,#side_A,#side_B').val('');

});



$('#deck-maker-modal').on('shown.bs.modal', function () {

    $('#deck_name').setCursorToTextEnd();

});
/*$('#deck-import-modal').on('shown.bs.modal', function () {
	$('#deck-maker-modal').modal('hide');
  //  $('#deck_name').setCursorToTextEnd();

});*/
$(document).on('click', '.deck-import-modal', function () {
	
	$('#deck-maker-modal').modal('hide');
	$('#deck-import-modal').modal('show');
	setTimeout(function (){
			$('#import_code').focus();
		}, 500);
});
$(document).on('click', '.deck-test-import-modal', function () {
	$('#add-students-test-modal').modal('hide');
	$('#deck-test-import-modal').modal('show');
	setTimeout(function (){
			$('#test_import_code').focus();
		}, 500);
	
});

 
$('#deck-maker-modal').on('hidden.bs.modal', function () {
	$('#deck-maker-modal').hide();
  //  $('#deck_name').setCursorToTextEnd();

});


$('#edit-deck-maker-modal').on('shown.bs.modal', function () {
	
    $('#deck_name_edit').setCursorToTextEnd();

});

// Create a new Deck--------

$(document).on('click', '.add-student-deck-btn', function () {



    var this_data = $(this);

    if ($('#deck_name').val() == "") {

        //alert(desk_title_alert);

        $('#deck_name').setCursorToTextEnd();

        return false;

    }

    if ($('#append_data').html() == "") {

        $('#side_A').setCursorToTextEnd();

        //alert(desk_add_card);

        return false;

    }

    var students_decks_new_data = $('#add-students-deck-form').serialize();

    var student_id = $('#qc-student-id').val();



    $.ajax({

        //alert('data');

        type: 'POST',

        url: ADMIN_URL + 'config/config-quick-cards.php',

        data: {

            students_decks_new_data: students_decks_new_data,

            student_id: student_id,

            type: 'student-decks-new-entry',

        },

        async: true,

        cache: false,

        timeout: 10000,

        success: function (response) {

            var response = $.parseJSON(response);



            if (response.status == 1) {

                if (response.table_id != '') {

                    $(".deck_" + response.table_id).remove();

                }

                $('#students_decks_tbl tr:last').after(response.html);

                $('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();

                $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').show();

                $("#deck-maker-modal").modal('hide');



                $("#deck-maker-modal").find("#deck_name").val('');

                $('#deck-maker-modal').find('#side_A').val('');

                $('#deck-maker-modal').find('#side_B').val('');

                $('#deck-maker-modal').find('#side_A_data').val('');

                $('#deck-maker-modal').find('#side_B_data').val('');

                $('#deck-maker-modal #append_data').html('');







            } else {

                //$('.quick-ajax-response').find('.alert').removeClass(' alert-warning  alert-success').addClass('alert-warning').html(response.msg);

                $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html(response.msg).show();

                $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').show();

            }

            $('.student-decks-maker_type_1 .quick-ajax-response').delay(5000).fadeOut(1000);

            //$('#deck-maker-modal .remove-new-field').trigger('click');

        },

        error: function (xhr, status, err) {

        }

    });

});

// Create a new Deck using import code --------

$(document).on('click', '.add-student-import-deck-btn', function () {



    var this_data = $(this);

    if ($('#import_code').val() == "") {

        //alert(desk_title_alert);

        $('#import_code').setCursorToTextEnd();

        return false;

    }

   
    var import_code = $('#import_code').val();

    var student_id = $('#qc-student-id').val();



    $.ajax({

        //alert('data');

        type: 'POST',

        url: ADMIN_URL + 'config/config-quick-cards.php',

        data: {

            students_decks_new_data: import_code,

            student_id: student_id,

            type: 'student-decks-new-import-entry',

        },

        async: true,

        cache: false,

        timeout: 10000,

        success: function (response) {

            var response = $.parseJSON(response);



            if (response.status == 1) {

                if (response.table_id != '') {

                    $(".deck_" + response.table_id).remove();

                }

                $('#students_decks_tbl tr:last').after(response.html);

                $('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();

                $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').show();

            } else {

                //$('.quick-ajax-response').find('.alert').removeClass(' alert-warning  alert-success').addClass('alert-warning').html(response.msg);
				$('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();
                $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html(response.msg).show();

                $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').show();

            }
			$('#deck-import-modal').find('#import_code').val('');
			$("#deck-import-modal").modal('hide');
            $('.student-decks-maker_type_1 .quick-ajax-response').delay(5000).fadeOut(1000);

            //$('#deck-maker-modal .remove-new-field').trigger('click');

        },

        error: function (xhr, status, err) {

        }

    });

});



// Add New card on Deck---

$(document).on('click', '#add-more-side_fields', function () {

//$('#add-more-side_fields').click(function () {

    var deck_name = $('#deck_name').val();

    var side_a = $('#side_A').val();

    var side_b = $('#side_B').val();

    if ($.trim(deck_name) != '') {

        $('#error_desk_title_alert').hide();

        //alert(deck_name);

        if ($.trim(side_a) != '') {

            $('#error_side_alert').hide();

            $('#append_data').append('<div style="padding-bottom:1px"><h3 class="color" >Side A: <span class="text_deck">' + side_a + '<span> <a  aria-label="Delete Answer" href="javascript:void(0)" id="remove_data" onclick="$(this).closest(\'div\').remove();"><i class="fa fa-remove" style="font-size:16px;color:grey"></i></a></h3><input class="form-control" id="side_A_data" type="hidden" value="' + side_a + '" name="side_A_data[]" /> <h3 class="color" style="margin-top:-10px">Side B: <span class="text_deck">' + side_b + '</span></h3> <input class="form-control" id="side_B_data" type="hidden" value="' + side_b + '" name="side_B_data[]" /></div>');

            $('#side_A,#side_B').val('');

            $('#side_A').setCursorToTextEnd();

        } else {

            //alert(side_alert);

            $('#error_side_alert').show();

            $('#side_A').setCursorToTextEnd();

            return false;

        }

    } else {

        //alert(desk_title_alert);

        $('#error_desk_title_alert').show();

        $('#deck_name').setCursorToTextEnd();

        return false;

    }

});



$('#add-more-side_fields_edit').click(function () {



    var deck_name = $('#deck_name_edit').val();

    var side_a = $('#side_A_edit').val();

    var side_b = $('#side_B_edit').val();



    if ($.trim(deck_name) != '') {

        $('#error_desk_title_alert').hide();

        if ($.trim(side_a) != '') {

            $('#error_side_alert_edit').hide();

            $('#append_data_edit').append('<div class="edit_deck_side_row"><label class="color desk_maker_label" style="margin-top: 2px;" >Side A:</label><input class="form-control desk_maker_input_2" placeholder="Type Side A..." type="text" value="' + side_a + '" name="side_A_data[]" /><a href="javascript:void(0)" id="edit_side_box_remove"  aria-label="Delete Answer" ><i class="fa fa-remove" style="font-size:16x;color:grey"></i></a><label class="color desk_maker_label" style="margin: 1px;margin-left: 0px;">Side B:</label><input class="form-control desk_maker_input_2" placeholder="Type Side B..."  type="text" value="' + side_b + '" name="side_B_data[]"></div>');



            $('#side_A_edit,#side_B_edit').val('');

            $('#side_A_edit').setCursorToTextEnd();

        } else {

            //alert(side_alert);

            $('#error_side_alert_edit').show();

            $('#side_A_edit').setCursorToTextEnd();

            return false;

        }

    } else {

        $('#error_desk_title_alert').show();

//        alert(desk_title_alert);

        $('#deck_name_edit').setCursorToTextEnd();

        return false;

    }

});







$(document).on('click', '.student-decks-maker_type_1_edit', function () {

//$('.student-decks-maker_type_1_edit').click(function () {

    var table_id = $(this).attr('data-id');
	$('.msg').hide();
	$('.code_copy').html('');

    var type = 'Decks-maker-edit';

    $.ajax({

        type: 'POST',

        url: ADMIN_URL + 'config/config-quick-cards.php',

        data: {'table_id': table_id, type: type},

        async: true,

        cache: false,

        timeout: 10000,

        success: function (response) {

            var response = $.parseJSON(response);

            if (response.status == '200') {

                $('#edit-deck-maker-modal .modal-title').addClass('color');

                $('#edit-deck-maker-modal').removeClass('modal-success modal-warning');

                $('#edit-deck-maker-modal').find('.modal-body').html(response.html);

                $('#edit-deck-maker-modal').find('.modal-footer').show();



                $('#side_A_edit,#side_B_edit').val('');

            }

        },

        error: function (xhr, status, err) {



        }

    });

    $('#edit-deck-maker-modal').modal('show');

    $('#deck_name_edit').setCursorToTextEnd();

});



$(document).on('click', '.edit-modal-save', function () {

    var student_id = $('#qc-student-id').val();

    var user_type = $('#user_type').val();

    var deck_name = $('#deck_name_edit').val();



    if ($('#deck_name_edit').val() != "") {



        if ($('#append_data_edit').html() == "") {

            alert('Add card to deck');

            return false;

        }



        var form_data = $('#decks-maker-edit-form').serialize();

        var type = 'Decks-maker-edit';

        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-quick-cards.php',

            data: {'form_data': form_data, 'type': type, 'student_id': student_id, 'user_type': user_type},

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == '1') {

                    //alert('OK');

                    $('.student-decks-maker_type_1_edit').closest("tr").hide();

                    $('#students_decks_tbl tr:last').after(response.html);

                    $('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();

                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').show();

                    $("#edit-deck-maker-modal").modal('hide');

                    /*$('.student-decks-maker_type_1_edit').closest("tr").hide();

                     $('#students_decks_tbl tr:last').after(response.html);

                     

                     $('#edit-deck-maker-modal').addClass('modal-success');

                     $('#edit-deck-maker-modal .modal-title').removeClass('color');

                     $('#edit-deck-maker-modal').find('.modal-body').html(response.msg);

                     $('#edit-deck-maker-modal').find('.modal-footer').hide();*/

                    // $(".students_decks_tbl").load(window.location + " #students_decks_tbl");

                } else {

                    $('#edit-deck-maker-modal').addClass('modal-warning');

                    $('#edit-deck-maker-modal').find('.modal-body').html(response.msg);

                    $('#edit-deck-maker-modal').find('.modal-footer').hide();

                }

                $('.student-decks-maker_type_1 .quick-ajax-response').delay(5000).fadeOut(1000);

            },

            error: function (xhr, status, err) { }

        });

    } else {

        alert(desk_title_alert);

        $('#deck_name_edit').setCursorToTextEnd();

    }

});





$(document).on('click', '#edit_side_box_remove', function () {

    var r = $('.edit_deck_side_row').length;

    if (r > 1) {

        $(this).closest('.edit_deck_side_row').remove();

    } else {

        alert('add one ');

    }

});



// Quick card deck for teacher 





///------Quick Card test process-----------------

var test_title_alert = 'Test Name is required!';

var desk_add_card = 'Add card to deck';

var question_alert = 'Question not blank!';



// add new test------------



var test_maker_add_answer_div = '<div style="margin-bottom: 5px" class="question_option_row"><p id="error_ans_select" style="display: none;" class="error error_ans">Please select the right answer</p><div style="margin-top: 8px;width: 5%;float: left;"><input aria-label="Correct Answer Checkbox" type="checkbox" class="question_option_check"  ></div><div style="width: 95%;float: left;margin-bottom: 8px;"><input class="form-control question_option"  type="text"   placeholder="Type answer..." value=""/></div></div>';



$("#add-students-test-modal").on("hidden.bs.modal", function () {

    $('#test_name,#question_name').val('');

    $('#test_name').setCursorToTextEnd();

    $('#append_data_for_test,#append_answer').html('');

    $('#append_answer').append(test_maker_add_answer_div);

});



$('#reset_test_data').click(function () {

    $('#test_name,#question_name').val('');

    $('#test_name').setCursorToTextEnd();

    $('#append_data_for_test,#append_answer').html('');

    $('#append_answer').append(test_maker_add_answer_div);

});



$('#add-students-test-modal').on('shown.bs.modal', function () {

    $('#test_name').setCursorToTextEnd();

})

$('#edit-students-test-modal').on('shown.bs.modal', function () {

    $('#title_name_edit').setCursorToTextEnd();

})



$('#add-more-answer').click(function () {

    var test_name = $('#test_name').val();

    var question_name = $('#question_name').val();

    var question_option_row = $('.question_option_row').length;

    var question_option_blank_status = 0;





    if ($.trim(test_name) != "") {

        $('#error_test_title_alert').hide();

        $('#error_question_alert').hide();

        if ($.trim(question_name) != "") {



            $(".question_option").each(function () {

                if ($(this).val() == '') {

                    $('.question_option').focus();

                    question_option_blank_status = 1;

                }

            });

            $('#error_question_option_blank_status').hide();

            $('#error_question_option_blank_empty_status').hide();



            if (question_option_blank_status == 0) {

                if (question_option_row < 6) {

                    $('#append_answer').append(test_maker_add_answer_div);

                    $('.question_option').focus();

                    return true;

                } else {

                    //alert("can not have more than 6 option fields!");

                    $('#error_question_option_blank_status').show();

                    return false;

                }

            } else {

                //alert("can not have option field blank!");

                $('#error_question_option_blank_empty_status').show();

                return false;

            }

            return true;

        } else {

            $('#question_name').setCursorToTextEnd();

            //alert(question_alert);

            $('#error_question_alert').show();

            return false;

        }

        return true;

    } else {

        $('#test_name').setCursorToTextEnd();

        //alert(test_title_alert);

        $('#error_test_title_alert').show();

        return false;

    }



});



//$(document).on('click','#add-more-question',function(){

$('#add-more-question').click(function () {

    var test_name = $('#test_name').val();

    var question_name = $('#question_name').val();

    var question_number = parseInt($('#question_number').val());



    var right_answer_index = 0;

    var right_answer_arr = [];

    $(".question_option_check").each(function (i) {

        if ($(this).prop("checked") == true) {

            right_answer_arr[i] = '*';

            right_answer_index = right_answer_index + 1;

        } else {

            right_answer_arr[i] = '';

        }

    });



    if (test_name != "") {

        $('#error_test_title_alert').hide();

        if (question_name != "") {

            $('#error_question_alert').hide();

            var option_box = '';

            var right_answer = '';

            var row = 1;

            $(".question_option").each(function (j) {

                if ($(this).val() != '') {

                    right_answer = right_answer_arr[j];



                    if (j == 0) {

                        option_box += '<h3  class="test_maker_answer colo"><span class="answer-title color">A: </span><span class="text_deck">  <span class="colored-star">' + right_answer + '</span> ' + $(this).val() + '<span><input type="hidden" value="' + right_answer + '' + $(this).val() + '" name="question_option[' + question_number + '][]" /></span></span></h3>';

                    } else {

                        option_box += '<h3  class="test_maker_answer colo"><span class="answer-title color">&nbsp; </span><span class="text_deck">  <span class="colored-star">' + right_answer + '</span> ' + $(this).val() + '<span><input type="hidden" value="' + right_answer + '' + $(this).val() + '" name="question_option[' + question_number + '][]" /></span></span></h3>';

                    }

                }

            });



            if (right_answer_index == 1) {

                $('#error_ans_select').hide();



                $('#append_data_for_test').append('<div><h3><span  class="color">Q:</span> ' + question_name + '<a href="javascript:void(0)"  aria-label="Delete Answer" id="remove_data" onclick="$(this).closest(\'div\').remove();"><i class="fa fa-remove" style="font-size:16px;color:grey"></i></a><input type="hidden" value="' + question_name + '" name="question_arr[' + question_number + ']" /><input type="hidden" value="' + question_number + '"   /></h3><div style="margin-left:20px">' + option_box + '</div></div>');



                var updateNumber = question_number + 1;

                $('#question_number').val(updateNumber);

                $('#question_name').val('');

                $('#question_name').setCursorToTextEnd();



                $('#append_answer').html('');

                $('#append_answer').append('<div style="margin-bottom: 5px" class="question_option_row"><p id="error_ans_select" style="display: none;" class="error error_ans">Please select the right answer</p><div style="margin-top: 8px;width: 5%;float: left;"><input aria-label="Correct Answer Checkbox" type="checkbox" class="question_option_check"  ></div><div style="width: 95%;float: left;margin-bottom: 8px;"><input class="form-control question_option"  type="text"   placeholder="Type answer..." /></div></div>');

                return true;

            } else {

                $('#error_ans_select').show();

                $('#error_ans_select').setCursorToTextEnd();

                return false;

                //alert("please select the right answer");

            }

            return true;

        } else {

            $('#question_name').setCursorToTextEnd();

            $('#error_question_alert').show();

            return false;



            //alert(question_alert);

        }

    } else {

        $('#test_name').setCursorToTextEnd();

        $('#error_test_title_alert').show();

        return false;

        //alert(test_title_alert);

    }



});





$(document).on('click', '#add-student-test-btn', function () {



    if ($('#test_name').val() == "") {

        //$('#test_name').setCursorToTextEnd();  

        $('#error_test_title_alert').show();

        return false;

    }

    if ($('#append_data_for_test').html() == "") {

        //alert('Add question to deck');

        $('#error_ans_select').show();

        return false;

    }

    var students_test_data = $('#add-students-test-form').serialize();

    //alert(students_test_data);

    var student_id = $('#qc-student-id').val();

    //var student_id = this_data.attr('data-id');

    $.ajax({

        //alert('data');

        type: 'POST',

        url: ADMIN_URL + 'config/config-quick-cards.php',

        data: {

            students_test_data: students_test_data,

            student_id: student_id,

            type: 'student-test-new-entry',

        },

        async: true,

        cache: false,

        timeout: 10000,

        success: function (response) {

            var response = $.parseJSON(response);

            if (response.status == 1) {

                if (response.table_id != '') {

                    $(".test_" + response.table_id).remove();

                }



                $('#students_test_tbl tr:last').after(response.html);

                $('.student-decks-maker_type_2 .quick-ajax-response').fadeIn();

                $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').show();



                $("#add-students-test-modal").modal('hide');

                $("#add-students-test-modal").find("#test_name").val('');

                $('#add-students-test-modal').find('#question_name').val('');

                $('#add-students-test-modal #append_answer').html('');

                $('#add-students-test-modal #append_data_for_test').html('');



            } else {

                //$('.quick-ajax-response').find('.alert').removeClass(' alert-warning  alert-success').addClass('alert-warning').html(response.msg);

                $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').show();

            }

            $('.student-decks-maker_type_2 .quick-ajax-response').delay(5000).fadeOut(1000);

            //$('#deck-maker-modal .remove-new-field').trigger('click');

        },

        error: function (xhr, status, err) {

        }

    });

});

// Create a new Deck test using import code --------

$(document).on('click', '.add-student-import-deck-test-btn', function () {



    var this_data = $(this);

    if ($('#test_import_code').val() == "") {

        //alert(desk_title_alert);

        $('#test_import_code').setCursorToTextEnd();

        return false;

    }

   
    var import_code = $('#test_import_code').val();

    var student_id = $('#qc-student-id').val();



    $.ajax({

        //alert('data');

        type: 'POST',

        url: ADMIN_URL + 'config/config-quick-cards.php',

        data: {

            students_test_data: import_code,

            student_id: student_id,

            type: 'student-decks-test-new-import-entry',

        },

        async: true,

        cache: false,

        timeout: 10000,

        success: function (response) {

            var response = $.parseJSON(response);



            if (response.status == 1) {

                if (response.table_id != '') {

                    $(".test_" + response.table_id).remove();

                }

                $('#students_test_tbl tr:last').after(response.html);

                $('.student-decks-maker_type_2 .quick-ajax-response').fadeIn();

                $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').show();


             } else {

                //$('.quick-ajax-response').find('.alert').removeClass(' alert-warning  alert-success').addClass('alert-warning').html(response.msg);
				 $('.student-decks-maker_type_2 .quick-ajax-response').fadeIn();
                $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').show();

            }

            $('.student-decks-maker_type_2 .quick-ajax-response').delay(5000).fadeOut(1000);
			$('#deck-test-import-modal').find('#test_import_code').val('');
			$("#deck-test-import-modal").modal('hide');
            
            //$('#deck-maker-modal .remove-new-field').trigger('click');

        },

        error: function (xhr, status, err) {

        }

    });

});

// delete new test------------

$(document).on('click', '.student-decks-maker_type_2_delete', function () {

    var this_data = $(this);

    bootbox.confirm({

        message: "Are you sure, you want to Delete?",

        buttons: {

            confirm: {

                label: 'Yes',

                className: 'dashboard-settings-btn btn-block'

            },

            cancel: {

                label: 'No',

                className: 'dashboard-settings-btn btn-block'

            }

        },

        callback: function (result) {

            if (result) {

                var delete_id = this_data.attr('data-id');

                var type = 'Test-delete';



                $.ajax({

                    type: 'POST',

                    url: ADMIN_URL + 'config/config-quick-cards.php',

                    data: {'delete_id': delete_id, 'type': type},

                    async: true,

                    cache: false,

                    timeout: 10000,

                    success: function (response) {

                        var response = $.parseJSON(response);

                        this_data.closest("tr").hide();

                        if (response.status == 1) {

                            $('.student-decks-maker_type_2 .quick-ajax-response').fadeIn();

                            $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                        } else {

                            $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                        }

                        $('.student-decks-maker_type_2 .quick-ajax-response').delay(5000).fadeOut(1000);

                    },

                    error: function (xhr, status, err) {



                    }

                });

            }

        }

    });

});



// edit new test------------

$(document).on('click', '.student-decks-maker_type_2_edit', function () {

    var table_id = $(this).attr('data-id');

	$('.test-msg').hide();
	$('.test_code_copy').html('');

    var type = 'Tests-maker-edit';

    $.ajax({

        type: 'POST',

        url: ADMIN_URL + 'config/config-quick-cards.php',

        data: {'table_id': table_id, 'type': type},

        async: true,

        cache: false,

        timeout: 10000,

        success: function (response) {

            var response = $.parseJSON(response);

            if (response.status == '200') {

                $('#edit-students-test-modal').removeClass('modal-success modal-warning');

                $('#edit-students-test-modal').find('.edit-students-test-save').show();

                $('#edit-students-test-modal .modal-title').addClass('color');

                $('#edit-students-test-modal').find('#new-question-div,#new-question-btn-div').show();

                $('#edit-students-test-modal').find('.modal-body').html(response.html);

            }

        },

        error: function (xhr, status, err) {



        }

    });



    $('#edit-students-test-modal').modal('show');

    $('#title_name_edit').setCursorToTextEnd();

});



$(document).on('click', '.add-more-answer_in_edit', function () {

    var q = $(this).val();



    var test_name = $('#title_name_edit').val();

    var question_name = $('#question_name_edit_' + q).val();



    var question_option_row = $('.edit_answer_row_' + q).length;

    var question_option_blank_status = 0;



    if (test_name != "") {

        if (question_name != "") {

            $(".optionTextBoxRow_edit" + q).each(function () {

                if ($(this).val() == '') {

                    $('.optionTextBoxRow_edit' + q).focus();

                    question_option_blank_status = 1;

                }

            })



            if (question_option_blank_status == 0) {

                if (question_option_row < 6) {

                    var edit_answer_box = '<div  class="row edit_answer_row_' + q + ' remove_answer"><div class="answer_filed_row test_maker_q"><input aria-label="Correct Answer Checkbox" type="checkbox"   class="change_answer questionRight' + q + '"  name="questionRight[' + q + '][' + question_option_row + ']" /></div><div class="test_maker_q_answer"><input class="form-control optionTextBoxRow_edit' + q + '"  type="text" name="optionTextBox_edit[' + q + '][' + question_option_row + ']"    placeholder="Type answer..." required=""/></div><div class="test_maker_q_answer_remove"><button type="button"  aria-label="Delete Answer" class="edit_remove_btn edit_question_answer_remove dashboard-settings-btn btn-block" value="' + q + '"><i class="fa fa-remove" style="font-size:16px;color:grey"></i></button></div></div>';



                    $("#edit_answer_row_id" + q).append(edit_answer_box);

                    $('.optionTextBoxRow_edit' + q).focus();



                } else {

                    alert("can not have more than 6 option fields!");

                }

            } else {

                alert("can not have option field blank!");

            }



        } else {

            $('#question_name_edit_' + q).setCursorToTextEnd();

            alert(question_alert);

        }

    } else {

        $('#title_name_edit').setCursorToTextEnd();

        alert(test_title_alert);

    }



});



$(document).on('click', '.edit-students-test-save', function () {





    var student_id = $('#qc-student-id').val();

    var user_type = $('#user_type').val();

    var test_name = $('#title_name_edit').val();

    var question_row = $('.edit_question_row').length;

    var question_name = '';



    var questionRight = '';





    var question_name_blank = 0;

    var question_answer_blank = 0;

    if (test_name != "") {

        var startNumber = 0;

        $(".question_row_edit").each(function () {



            question_name = $(this).val();

            var id = $(this).attr('id');

            var ids = id.split('_');



            if (question_name == '') {

                question_name_blank = 1;

                $(this).setCursorToTextEnd();

                alert(question_alert);

                return false;

            }

            question_answer_blank = 0;

            $('.questionRight' + ids[3]).each(function () {

                if ($(this).prop("checked") == true) {

                    question_answer_blank = question_answer_blank + 1;

                }

            });

            if (question_answer_blank != 1) {

                $(this).setCursorToTextEnd();

                return false;

            }

        });

        if (question_name_blank == 0) {

            if (question_answer_blank == 1) {

                var form_data = $('#tests-maker-edit-form').serialize();

                var type = 'Tests-maker-edit';

                $.ajax({

                    type: 'POST',

                    url: ADMIN_URL + 'config/config-quick-cards.php',

                    data: {'form_data': form_data, 'type': type, 'user_type': user_type, 'student_id': student_id},

                    async: true,

                    cache: false,

                    timeout: 10000,

                    success: function (response) {

                        var response = $.parseJSON(response);



                        if (response.status == true) {

                            $('.student-decks-maker_type_2_edit').closest("tr").hide();

                            $('#students_test_tbl tr:last').after(response.html);

                            $('.student-decks-maker_type_2 .quick-ajax-response').fadeIn();

                            $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                            $('.student-decks-maker_type_2 .quick-ajax-response').find('.alert').show();

                            $("#edit-students-test-modal").modal('hide');



                            /*$('.student-decks-maker_type_2_edit').closest("tr").hide();

                             $('#students_test_tbl tr:last').after(response.html);

                             $('#edit-students-test-modal').addClass('modal-black');

                             $('#edit-students-test-modal .modal-title').removeClass('color');

                             $('#edit-students-test-modal').find('.modal-body').html(response.msg);

                             $('#edit-students-test-modal').find('.edit-students-test-save,#new-question-div,#new-question-btn-div').hide();

                             */

                        } else {

                            $('#edit-students-test-modal').addClass('modal-warning');

                            $('#edit-students-test-modal').find('.modal-body').html(response.msg);

                            $('#edit-students-test-modal').find('#new-question-div').hide();

                            $('#edit-students-test-modal').find('.edit-students-test-save,#new-question-div,#new-question-btn-div').hide();

                        }



                        $('.student-decks-maker_type_2 .quick-ajax-response').delay(5000).fadeOut(1000);

                    },

                    error: function (xhr, status, err) {



                    }

                });

            } else {

                alert("please select the only one right answer");

            }

        } else {

            alert(question_alert);

        }

    } else {

        $('#title_name_edit').setCursorToTextEnd();

        alert(test_title_alert);

    }



});



$(document).on('click', '#edit_question_remove', function () {

    var r = $('.edit_question_row').length;

    if (r > 1) {

        $(this).closest('section').remove();

    } else {

        alert('add one question');

    }

});



$(document).on('click', '.edit_question_answer_remove', function () {

    var answer_no = $(this).val();

    var r = $('.edit_answer_row_' + answer_no).length;

    if (r > 1) {

        $(this).closest('.remove_answer').remove();

    } else {

        alert('add one answer');

    }

});





$('#add-more-answer_edit').click(function () {

    var test_name = $('#title_name_edit').val();

    var question_name = $('#question_new_edit').val();

    var question_option_row = $('.question_option_row_new_add_edit').length;

    var question_option_blank_status = 0;



    if (test_name != "") {

        $('#error_test_title_alert').hide();



        if (question_name != "") {

            $('#error_question_alert_edit').hide();

            $(".question_option_edit_box").each(function () {

                if ($(this).val() == '') {

                    $('.question_option_edit_box').focus();

                    question_option_blank_status = 1;

                }

            });



            if (question_option_blank_status == 0) {

                $('#error_option_not_blank_edit').hide();



                if (question_option_row < 6) {

                    $('#error_max_six_option_edit').hide();

                    $('#append_answer_in_edit').append('<div style="margin-bottom: 5px" class="question_option_row_new_add_edit"><div style="margin-top: 8px;width: 5%;float: left;"><input aria-label="Correct Answer Checkbox"  type="checkbox" class="question_option_check_edit"  ></div><div style="width: 95%;float: left;margin-bottom: 8px;"><input class="form-control question_option_edit_box"  type="text"   placeholder="Type answer..." /></div></div>');

                    $('.question_option_edit_box').focus();

                } else {

                    $('#error_max_six_option_edit').show();

                    return false;

                    //alert("can not have more than 6 option fields!");

                }

            } else {

                $('#error_option_not_blank_edit').show();

                return false;

                //alert("can not have option field blank!");

            }

        } else {

            $('#error_question_alert_edit').show();

            $('#question_new_edit').setCursorToTextEnd();

            return false;

            //alert(question_alert);

        }

    } else {

        $('#error_test_title_alert').show();

        $('#title_name_edit').setCursorToTextEnd();

//        alert(test_title_alert);

        return false;

    }



});



$('#add-more-question_edit').click(function () {

    var test_name = $('#title_name_edit').val();

    var question_name = $('#question_new_edit').val();

    var question_number = parseInt($('#question_number_in_edit').val());

    var right_answer_index = 0;

    var right_answer_arr = {};



    var html = '';



    if (test_name != "") {

        $('#error_test_title_alert').hide();

        if (question_name != "") {

            $('#error_question_alert_edit').hide();

            $(".question_option_check_edit").each(function (j) {

                if ($(this).prop("checked") == true) {

                    right_answer_arr[j] = '1';

                    right_answer_index = right_answer_index + 1;

                } else {

                    right_answer_arr[j] = '0';

                }

            });

            if (right_answer_index == 1) {

                $('#error_select_only_one_ans_edit').hide();

                html += '<section class="edit_question_row"><div><h3 class="color question_number" ><span style="width: 5%;float: left;" >Q: </span><input style="margin-bottom: 8px; width: 85%;float: left;" class="form-control"  type="text" id="question_name_edit_' + question_number + '" value="' + question_name + '" name="question_arr[' + question_number + ']" /></h3><div style="margin-left:20px"  aria-label="Delete Answer"><i class="fa fa-remove" style="font-size:16px;color:grey;padding: 10px;"></i></a><div>';



                var question_option_row = $('.question_option_row_new_add_edit').length;



                $(".question_option_edit_box").each(function (i) {



                    if (right_answer_arr[i] == 0) {

                        $checked = 'unchecked';

                    } else {

                        $checked = 'checked';

                        ;

                    }



                    html += '<div  class="row edit_answer_row_' + question_number + ' remove_answer" ><div class="answer_filed_row test_maker_q"><input aria-label="Correct Answer Checkbox" type="checkbox" ' + $checked + ' class="change_answer questionRight' + question_number + '" name="questionRight[' + question_number + '][' + i + ']"  />  </div><div class="test_maker_q_answer"><input class="form-control optionTextBoxRow_edit' + question_number + '"  type="text" name="optionTextBox_edit[' + question_number + '][' + i + ']"   value="' + $(this).val() + '"  placeholder="Type answer..." required=""/></div><div class="test_maker_q_answer_remove"><button type="button"  aria-label="Delete Answer" class="edit_remove_btn edit_question_answer_remove dashboard-settings-btn btn-block" value="' + question_number + '"><i class="fa fa-remove" style="font-size:16px;color:grey"></i></button></div></div>';

                });



                html += '<input class="form-control" id="question_number_edit" name="question_number_edit" type="hidden"  value="' + question_option_row + '" />';



                html += '<div id="edit_answer_row_id' + question_number + '"></div>';

                html += '<button type="button" style="float:right;margin-bottom10px" class="btn-sm dashboard-settings-btn btn-block add-more-answer_in_edit"   value="' + question_number + '">Add An Answer</button></div></div></section>';



                var updateNumber = question_number + 1;

                $('#question_number_in_edit').val(updateNumber);

                $('#append_data_for_test_edit').append(html);



                $('#question_new_edit').val('');

                $('#question_new_edit').setCursorToTextEnd();



                $('#append_answer_in_edit').html('');

                $('#append_answer_in_edit').append('<div style="margin-bottom: 5px" class="question_option_row_new_add_edit"><div style="margin-top: 8px;width: 5%;float: left;"><input aria-label="Correct Answer Checkbox" type="checkbox" class="question_option_check_edit"  ></div><div style="width: 95%;float: left;margin-bottom: 8px;"><input class="form-control question_option_edit_box"  type="text"   placeholder="Type answer..." /></div></div>');





            } else {

                $('#error_select_only_one_ans_edit').show();

                return false;

                //alert("please select the only one right answer");

            }



        } else {

            $('#error_question_alert_edit').show();

            $('#question_new_edit').setCursorToTextEnd();

            return false;

            //alert(question_alert);

        }

    } else {

        $('#error_test_title_alert').show();

        $('#title_name_edit').setCursorToTextEnd();

        return false;

//        alert(test_title_alert);

    }



});
 $("#new-option-btn").click(function () {
    $("#new-option-section-wrap").toggle();
    $(this).toggleClass('import-menu-display');
});
$("#new-option-btn-test-pg").click(function () {
    $(".new-option-section-wrap-test-pg").toggle();
    $(this).toggleClass('import-menu-display');
});


 $(document).on('click', '.deck-csv-import-modal', function () {
    
    $('#deck-maker-modal').modal('hide');
    $('#typio-import-csv_modal').modal('show');
});

 $(document).on('click', '#import_csv_typio', function () {
        var student_id = $('#qc-student-id').val();
        var file_data = $('#typio_file_upload').prop('files')[0];  
        var file_name =$('#typio_file_upload').val();  
        var student_overview_page =$("#student_overview_page").val();
        var extension = file_name.substr( (file_name.lastIndexOf('.') +1) ); 

        if(extension != "csv"){
            alert("Please select csv file upload for import data");
            return false;
        }

        var form_data = new FormData();                  
        form_data.append('file', file_data);
        form_data.append('student_id', student_id);
        form_data.append('insert_type', 'QC-OL');
        form_data.append('type', 'typio-csv-import-entry');
        form_data.append('student_overview_page', student_overview_page);
       
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
                    $('#students_decks_tbl tr:last').after(response.html);

                    $('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();

                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').show();

                    $("#deck-maker-modal").modal('hide');

                } else {
                    $('#students_decks_tbl tr:last').after(response.html);

                    $('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();

                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').show();

                    $("#deck-maker-modal").modal('hide');

                }
                $("#add-typio-csv-import-form")[0].reset();
                $("#typio-import-csv_modal").modal('hide');
                $('.custom_lessons .quick-ajax-response').delay(5000).fadeOut(1000);

            },

            error: function (xhr, status, err) {

            }

        });
    });


  $(document).on('click', '#import_csv_typio_test', function () {
        var student_id = $('#qc-student-id').val();
        var file_data = $('#typio_file_upload_test').prop('files')[0];  
        var file_name =$('#typio_file_upload_test').val();  
        var extension = file_name.substr( (file_name.lastIndexOf('.') +1) ); 


       if(extension != "csv"){
            alert("Please select csv file upload for import data");
            return false;
        }

        var form_data = new FormData();                  
        form_data.append('file', file_data);
        form_data.append('student_id', student_id);
        form_data.append('insert_type', 'QC-OL');
        form_data.append('type', 'typio-csv-import-entry-test');
       
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
                    $('#students_test_tbl').append(response.html);
                    if(response.inserted_ids !=""){
                        var result = response.inserted_ids.split(',');
                        if(result.length > 0){
                            for(var p=0; p<result.length; p++){
                                $("#test_"+result[p]).css("display", "block");
                            }
                        }
                    }

                    $('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();
                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').show();
                    $("#deck-maker-modal").modal('hide');
                } else {
                    $('#students_decks_tbl tr:last').after(response.html);
                    $('.student-decks-maker_type_1 .quick-ajax-response').fadeIn();
                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    $('.student-decks-maker_type_1 .quick-ajax-response').find('.alert').show();
                    $("#deck-maker-modal").modal('hide');
                }
                $("#add-typio-csv-import-form-test")[0].reset();
                $("#typio-import-csv_modal-test").modal('hide');
                $('.custom_lessons .quick-ajax-response').delay(5000).fadeOut(1000);


            },

            error: function (xhr, status, err) {

            }

        });
    });
/* START : Student Overview Graph For Prepare Data */

draw_student_piechart_graph();
    function draw_student_piechart_graph(){

         var graph_start_date = $('#graph_start_date').val();

        var graph_end_date = $('#graph_end_date').val();

        var student_id     = $('#graph_student_id').val();
        var response_data;

        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-student.php',

            data: {action_type: 'get_all_bar_history_data', start_date: graph_start_date, end_date: graph_end_date, student_id: student_id},

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {
			
           //    if(response !=""){
            if(response !=null && response !="") {



                var response = $.parseJSON(response);

                response_data =response.html;
                if (response.status == '200') {
                    if(response.data_available == "1"){
                        display_all_graph(response.html);
                    }
                    
                    
                }
             }
              //   } 
            },
            error: function (xhr, status, err) {
            }

        });

    }
/* END : Student Overview Graph For Prepare Data*/
/* Start : Student Overview Graph Create */
   function display_all_graph(data){
    Highcharts.setOptions({
     colors: ['#ff0066', '#7cb5e9', '#f7a35c', '#90ed7d']
    });
        
    Highcharts.chart('piechartOverview_graph_data', {
      chart: {
            type: 'bar'
      },
          title: {
            text: ''
          },
        /*  accessibility: {
            point: {
              valueDescriptionFormat: "Activate this item to add or remove this app from the chart above and the table that follows."
            }
          },*/
         /* accessibility: {
              description: 'Chart shows Apple stock prices from mid 2008 to mid 2015. It shows steady growth with one significant peak lasting through most of 2012 before normalizing.'
          },*/

          accessibility: {
                point: {
                    valueDescriptionFormat: 'Activate this item to add or remove this app from the chart above and the table that follows.'
                }
            },

          xAxis: {
              categories: [''],
              title: {
                  text: null
              },
              labels: {
                 enabled:false,//default is true
              },
			  gridLineColor: '#ffffff',
			  lineColor: '#ffffff',
          },
          yAxis: {
            min: 0,
            title: {
                text: ''
            },
			labels: {
			 enabled:false,//default is true
			},
			gridLineColor: '#ffffff',
			lineColor: '#ffffff',
          },
          legend: {
             reversed: true
          },
          credits: {
            enabled: false
          },
          exporting: {
            enabled: false
          },
          plotOptions: {
            series: {
                 stacking: 'normal'
            }
          },
         /* series: [{
            name: 'Typio-OL',
            data: [5]
          }, {
            name: 'Quick-Cards-OL',
            data: [2]
          }, {
            name: 'Overview-OL',
            data: [3]
          },{
            name: 'Arcade-OL',
            data: [3]
          }]*/
        series:data
            });
 }

 $("#new-option-btn-test").click(function () {
        $("#new-option-section-wrap-test").toggle();
        $(this).toggleClass('import-menu-display');
 });
 $(document).on('click', '.deck-csv-import-modal-test', function () {
    $('#typio-import-csv_modal-test').modal('show');
});
 /* END : Student Overview Graph Create */

/* START : Create Click Event for Student Overview type */
setTimeout(function(){ 
    $('#piechartOverview_graph_data .highcharts-legend-item tspan').click(function () {
        var type=$(this).html();
        var graph_start_date = $('#graph_start_date').val();

        var graph_end_date = $('#graph_end_date').val();

        var student_id     = $('#graph_student_id').val();

        $(".overview-graph-tbl table ."+type).toggleClass("hide-tbl-record");
        if($(".overview-graph-tbl table ."+type).hasClass("hide-tbl-record")){
                add_class="0";
        }else{
                add_class="1";
        }
        var display_type ="";
        if(!$(".overview-graph-tbl table .Typio-OL").hasClass("hide-tbl-record")){
                if(display_type !=""){
                    display_type += ","+"Typio-OL";
                }else{
                    display_type ="Typio-OL"
                }

        }
        if(!$(".overview-graph-tbl table .Propack").hasClass("hide-tbl-record")){
                if(display_type !=""){
                    display_type += ","+"Propack";
                }else{
                    display_type ="Propack"
                }

        }
        if(!$(".overview-graph-tbl table .Quick-Cards-OL").hasClass("hide-tbl-record")){
                if(display_type !=""){
                    display_type += ","+"Quick-Cards-OL";
                }else{
                    display_type ="Quick-Cards-OL"
                }

        }
        if(!$(".overview-graph-tbl table .Arcade-OL").hasClass("hide-tbl-record")){
                if(display_type !=""){
                    display_type += ","+"Arcade-OL";
                }else{
                    display_type ="Arcade-OL"
                }

        }
        
         $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-student.php',

            data: {action_type: 'get_all_bar_history_hours', start_date: graph_start_date, end_date: graph_end_date, student_id: student_id,display_type:display_type},

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                     $(".overview-graph-total-time").html(response.data);
                }
            },
            error: function (xhr, status, err) {
            }

        });

    });

}, 1000);
/* END : Create Click Event for Student Overview type */
function set_focus_file_type(){
    setTimeout(function(){ 
        $("#typio_file_upload").focus();
    }, 200);

}


