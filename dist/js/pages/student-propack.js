(function ($) {
    $.fn.setCursorToTextEnd = function () {
        this.focus();
        var $thisVal = this.val();
        this.val('').val($thisVal);
        return this;
    }
})(jQuery);
jQuery(document).ready(function ($) {

    var pro_title_alert = "Title name field required";
    var pro_data_alert = "Text data field required";

    // Open Model Focus
    $('#edit-Pro-Pack-modal').on('shown.bs.modal', function () {
        $('#reader-docs-title-edit').setCursorToTextEnd();
    });

    // Add new record
    $(".pro-pack-add-new-form").click(function () {
        var type_doc = $(this).val().trim();

        var data = '';
        if (type_doc == 1) {
            title_type = '#reader-docs-title';
            data_type = '#Reader-docs-text';
            msg_class = ".pro_pack_msg";
            table_type = 'reader_doc_listing';
            tableID = '#student_propack_reader_file_list_table'; 
            data = $(data_type).val().trim();
        } else if (type_doc == 2) {
            title_type = '#notepad-docs-title';
            data_type = '#Notepad-docs-text';
            msg_class = ".notepad_msg";
            table_type = 'notepad_doc_listing';
            tableID = '#student_propack_notpad_file_list_table';
            data = $(data_type).val().trim();
        } else if (type_doc == 3) {
            title_type = '#to-do-docs-title';
            data_type = '#To-Do-docs-text';
            msg_class = ".to_do_msg";
            table_type = 'to_do_doc_listing';
            tableID = '#student_propack_todo_list_table';
            data = '1';
        }

        var title = $(title_type).val().trim();
        if (title != '') {
            if (data != '') {
                var this_object = $(this);
                var form_data = this_object.closest('form').serialize();

                //var form_data = this_object.parents('.modal-content').find('form').serialize();
                //var form_data = this_object.closest("form").serialize();
                var title = this_object.closest('form').find('.title').val();
                var pro_pack_type = this_object.closest('form').find('.pro_pack_type').val();
                $.ajax({
                    type: 'POST',
                    url: ADMIN_URL + 'config/config-student.php',
                    data: {'form_data': form_data, 'action': 'pro-pack-add-new'},
                    async: true,
                    cache: false,
                    timeout: 20000,
                    success: function (response) {
                        var response = $.parseJSON(response);

                        if (response.status == true) {
                            if (response.type == 2) {
                                $(msg_class + ' tr:last').after(response.html);
                            }
                            /*var table = jQuery('#reader_doc_listing').DataTable();
                            table.clear().draw();

                            var table = jQuery(tableID).DataTable();
                            table.clear().draw();*/

                            $(msg_class + ' .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                        } else {
                            $(msg_class + ' .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                        }
                        $(title_type).val('');
                        if (type_doc != 3) {
                            $(data_type).val('');
                        }
                        $(msg_class + ' .quick-ajax-response').show();
                        $("#add-propack-modal").modal('hide');
                        $(msg_class + '  .quick-ajax-response').delay(5000).fadeOut(1000);
                    },
                    error: function (xhr, status, err) {

                    }
                });
            } else {
                alert(pro_data_alert);
                $(data_type).setCursorToTextEnd();
            }
        } else {
            alert(pro_title_alert);
            $(title_type).setCursorToTextEnd();
        }
    });

    //Show edit modal popup
    $(document).on('click', '.edit-pro-pack-modal', function () {

        // var id = $('#reader_doc_listing tr td:child(2)').html();
        // alert(id);

        var table_id = $(this).attr('data-id');

        var type = 'Update-Pro-Pack-Content';
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {'table_id': table_id, type: type},
            async: true,
            cache: false,
            timeout: 20000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#edit-Pro-Pack-modal').removeClass('modal-success modal-warning');
                    $('#edit-Pro-Pack-modal .modal-title').addClass('color');
                    $('#edit-Pro-Pack-modal').find('.edit-pro-pack-btn').show();
                    $('#edit-Pro-Pack-modal').find('.modal-body').html(response.html);
                }
            },
            error: function (xhr, status, err) {}
        });
        $('#edit-Pro-Pack-modal').modal('show');

    });
    //Update lessons record
    $(document).on('click', '.edit-pro-pack-btn', function () {
        var title = $('#reader-docs-title-edit').val().trim();
        var data = '';

        var type_number = $('#type_number').val();
        var tbl_tr = '';
        if (type_number == 0) {
            table_type = 'reader_doc_listing';
            data = $('#textareaID2').val().trim();
            msg_class = ".pro_pack_msg";
            tbl_tr = "pro_pack_tr_";
        } else if (type_number == 1) {
            table_type = 'notepad_doc_listing';
            data = $('#textareaID2').val().trim();
            msg_class = ".notepad_msg";
            tbl_tr = "notepad_tr_";
        } else if (type_number == 2) {
            table_type = 'to_do_doc_listing';
            msg_class = ".to_do_msg";
            data = '1';
            tbl_tr = "todo_tr_";
        }

        if (title != '') {
            if (data != '') {
                var form_data = $('#pro-pack-edit-form').serialize();
                var type = 'Update-Pro-Pack-form';
                var user_type = $('#user_type').val();
                $.ajax({
                    type: 'POST',
                    url: ADMIN_URL + 'config/config-student.php',
                    data: {'form_data': form_data, 'type': type, 'user_type': user_type, 'type_number': type_number, tbl_tr: tbl_tr},
                    async: true,
                    cache: false,
                    timeout: 20000,
                    success: function (response) {
                        var response = $.parseJSON(response);
                        if (response.status == 1) {

                            /*$("." + tbl_tr + response.table_id).closest("tr").remove();
                            $('#' + table_type + ' tr:first').after(response.html);
                            $("." + tbl_tr + response.table_id).closest("tr").attr('style', 'background-color:green;color:white;');
                            setTimeout(function () {
                                $("." + tbl_tr + response.table_id).closest("tr").removeAttr('style', 'background-color:green;color:white;');
                            }, 5000);*/
//                            $('#edit-lessons-modal').addClass('modal-success');
//                            $('#edit-lessons-modal').find('.modal-body').html(response.msg);

                            $(msg_class + ' .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                            $(msg_class + ' .quick-ajax-response').show();
                            $(msg_class + ' .quick-ajax-response').delay(5000).fadeOut(1000);


                            $('#edit-Pro-Pack-modal').find('.edit-lessons-btn').hide();
                            $('#edit-Pro-Pack-modal .modal-title').removeClass('color');
                            $("#edit-Pro-Pack-modal .close").trigger('click');
                            var table = $('#student_propack_reader_file_list_table').DataTable();
                            table.clear().draw();

                            var table = $('#student_propack_notpad_file_list_table').DataTable();
                            table.clear().draw();

                            var table = $('#student_propack_todo_list_table').DataTable();
                            table.clear().draw();

                            var table = $('#reader_doc_listing').DataTable();
                                table.ajax.reload(null, false);



                            // $("."+table_type).load(window.location + " #"+table_type); 
//                    $(msg_class).closest("tr td").remove();
//
//                    // var theRowId = $('#'+table_type+' tr td').attr('data-id');
//                    // $('#tableid tr#'+theRowId).remove();
//
//                    $(msg_class+' tr:last').after(response.html);   
//                    $('#edit-Pro-Pack-modal').addClass('modal-success');
//                    $('#edit-Pro-Pack-modal .modal-title').removeClass('color');
//                    $('#edit-Pro-Pack-modal').find('.modal-body').html(response.msg);
//                    $('#edit-Pro-Pack-modal').find('.edit-pro-pack-btn').hide();
                        } else {
                            $('#edit-Pro-Pack-modal').addClass('modal-warning');
                            $('#edit-Pro-Pack-modal').find('.modal-body').html(response.msg);
                            $('#edit-Pro-Pack-modal').find('.edit-pro-pack-btn').hide();
                        }
                    },
                    error: function (xhr, status, err) {

                    }
                });
            } else {
                alert(pro_data_alert);
                $('#textareaID2').setCursorToTextEnd();
            }
        } else {
            alert(pro_title_alert);
            $('#reader-docs-title-edit').setCursorToTextEnd();
        }
    });

    //Delete record
    $(document).on('click', '.delete-pro-pack-modal', function () {

        var this_data = $(this);
        var delete_text = this_data.attr('data-type');

        if (delete_text == 'Reader Doc') {
            msg_class = ".pro_pack_msg";
        } else if (delete_text == 'Notepad Docs') {
            msg_class = ".notepad_msg";
        } else if (delete_text == 'To-Do Doc') {
            msg_class = ".to_do_msg";
        }
        bootbox.confirm({
            message: "Are you sure, you want to delete " + delete_text + " ?",
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
                    var type = 'pro-pack-delete';
                    $.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/config-student.php',
                        data: {'delete_id': delete_id, 'type': type, 'delete_text': delete_text},
                        async: true,
                        cache: false,
                        timeout: 20000,
                        success: function (response) {
                            var response = $.parseJSON(response);

                            if (response.status == 1) {
                                $(msg_class + ' .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                            } else {
                                $(msg_class + ' .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                            }
                            $(msg_class + ' .quick-ajax-response').show();
                            $(msg_class + ' .quick-ajax-response').delay(5000).fadeOut(1000);
                            this_data.closest("tr").remove();
                            var table = $('#student_propack_reader_file_list_table').DataTable();
                            table.clear().draw();

                            var table = $('#student_propack_notpad_file_list_table').DataTable();
                            table.clear().draw();

                            var table = $('#student_propack_todo_list_table').DataTable();
                            table.clear().draw();


                        },
                        error: function (xhr, status, err) {

                        }
                    });
                }
            }
        });
    });

    //Share Data
    $(document).on('click', '.pro-pack-share-submit', function () {

        var user_id = $('#user_id').val();
        var share_user_list = [];

        var share_ids = [];
        var i = 0;
        $('.share_pro_pack_ids:checked').each(function () {
            share_ids[i++] = $(this).val();
        });

        if (share_ids == '') {
            $('.pro_pack_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html('Please select at least one reader docs.').show();
            return false;
        } else {
            $('.pro_pack_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').hide();
        }
        var j = 0;
		$('.share_user_ids:checked').each(function () {
			share_user_list[j++] = $(this).val();
		});

        if (share_user_list == '') {
            $('.pro_pack_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html('Please select at least one student.').show();
            return false;
        } else {
            $('.pro_pack_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').hide();
        }

        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {'user_id': user_id,
                'type': 'pro-pack',
                'share_ids': share_ids,
                'share_user_list': share_user_list
            },
            async: true,
            cache: false,
            timeout: 20000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == 1) {
                    $('.pro_pack_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    $('.share_pro_pack_ids').removeAttr('checked');
                    $(".share_user_ids").removeAttr("checked");
                    $('.reader_doc_share_msg').show();
                } else {
                    $('.pro_pack_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();

                    $('.share_pro_pack_ids').removeAttr('checked');
                    $(".share_user_ids").removeAttr("checked");
                    $('.reader_doc_share_msg').show();
                }
                $('.pro_pack_msg .quick-ajax-response').show();
                $('.pro_pack_msg .quick-ajax-response').delay(5000).fadeOut(1000);
            },
            error: function (xhr, status, err) {
            }
        });
    });
});
