(function($){
    $.fn.setCursorToTextEnd = function(){
        this.focus();
        var $thisVal = this.val();
        this.val('').val($thisVal);
        return this;
    }
})(jQuery);

jQuery(document).ready(function ($) {
    //Section A
    $(document).on('click', '#ArcadeHistoryDateSection #ArcadeDateFilterBtn', function () {
        $('#ArcadeHistoryDateSection').addClass('ajax_loading');
        var ArcadeDatePicker1 = $('#ArcadeHistoryDateSection #ArcadeDatePickerFrom').val();
        var ArcadeDatePicker2 = $('#ArcadeHistoryDateSection #ArcadeDatePickerTo').val();
        
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {action_type: 'get_arcade_history_data', start_date: ArcadeDatePicker1, end_date: ArcadeDatePicker2},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                console.log(response.html);
                if (response.status == '200') {
                    $('#ArcadeHistory_table').html(response.html);
                    $('#ArcadeHistoryDateSection').removeClass('ajax_loading');
                }
                
                $('.ArcadeHistory_table_wrap').show();
                $('.arcade.no-activity-msg-wrap').hide();
            },
            error: function (xhr, status, err) {

            }
        });
    });
    
    //Section B Start
    //Show edit modal popup
    $(document).on('click', '.edit-hangman-modal', function () {
        var table_id = $(this).attr('data-id');
        var type = 'Update-Hangman';
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student-arcade.php',
            data: {'table_id': table_id, type: type},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '200') {
                    $('#edit-hangman-modal').removeClass('modal-success modal-warning');
                    $('#edit-hangman-modal').find('.edit-hangman-btn').show();
                    $('#edit-hangman-modal').find('.modal-body').html(response.html);
                }
            }
        });
        $('#edit-hangman-modal').modal('show');
    });

    //Update record
    /*$(document).on('click', '.edit-hangman-btn', function () {
        var form_data = $('#hangman-edit-form').serialize();
        var type = 'Update-Hangman';
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student-arcade.php',
            data: {'form_data': form_data, 'type': type},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.status == '1') {
                    $('#edit-hangman-modal').addClass('modal-success');
                    $('#edit-hangman-modal').find('.modal-body').html(response.msg);
                    $('#edit-hangman-modal').find('.edit-hangman-btn').hide();
                } else {
                    $('#edit-hangman-modal').addClass('modal-warning');
                    $('#edit-hangman-modal').find('.modal-body').html(response.msg);
                    $('#edit-hangman-modal').find('.edit-hangman-btn').hide();
                }
            }
        });
    });*/
    var i = 1;
    

    // Add new record
    $(".add-hangman-btn").click(function () {
        var form_data = $('#add-hangman-form').serialize();
        var title = $("#hangman-title").val();
        var type = 'Add-Hangman';

        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student-arcade.php',
            data: {'form_data': form_data, 'type': type},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                var $tableBody = $('#hangman-table').find("tbody"),
                        $trLast = $tableBody.find("tr:last"),
                        $trNew = $trLast.clone();
                var count = $("#hangman-table tr:last td:nth-child(1)").text();
                if(count === ''){
                    var no = 1;
                } else {
                    var no = parseInt(count) + 1;
                }
                var html = '<tr><td>'+title+'</td><td><a href="#" class="badge bg-green edit-hangman-modal" data-id="'+response.id+'"><i class="fa fa-edit"></i></a></td><td><a href="#" class="badge bg-red delete-hangman" data-toggle="modal" data-target="#delete-modal-hangman" data-id="'+response.id+'"><i class="fa fa-trash-o"></i></a></td></tr>';
                $trLast.after(html);
               $("#hangman-title").val('');
                $("input[name='fields[1]']").val('');
                $("#append").html('');
                $("#add-hangman-modal").modal('hide');
            },
            error: function (xhr, status, err) {

            }
        });
    });

    //Delete record
    /*$(document).on('click', '.delete-hangman', function () {
        var this_data = $(this);
        bootbox.confirm({
            message: "Are you sure, you want to delete?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    var delete_id = this_data.attr('data-id');
                    var type = 'hangman-delete';
                    $.ajax({
                        type: 'POST',
                        url: ADMIN_URL + 'config/config-student-arcade.php',
                        data: {'delete_id': delete_id, 'type': type},
                        async: true,
                        cache: false,
                        timeout: 10000,
                        success: function (response) {
                            var response = $.parseJSON(response);
                            this_data.closest("tr").remove();
                            if (response.status == 1) {
                                $('.quick-ajax-response-hangman').find('.alert').removeClass('alert-warning alert-success').addClass('alert-success').html(response.msg).show();
                            } else {
                                $('.quick-ajax-response-hangman').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html(response.msg).show();
                            }
                            $('.quick-ajax-response').delay(5000).fadeOut(1000);
                        }
                    });
                }
            }
        });
    });*/
    //For section C
    // $(document).on('click', '.game-data-edit', function () {
    //     var table_id = $(this).attr('data-id');
    //     var type = 'Update-Game';
    //     $.ajax({
    //         type: 'POST',
    //         url: ADMIN_URL + 'config/config-student-arcade.php',
    //         data: {'table_id': table_id, type: type},
    //         async: true,
    //         cache: false,
    //         timeout: 10000,
    //         success: function (response) {
    //             var response = $.parseJSON(response);
    //             if (response.status == '200') {
    //                 $('#edit-game-modal').removeClass('modal-success modal-warning');
    //                 $('#edit-game-modal').find('.edit-game-btn').show();
    //                 $('#edit-game-modal').find('.modal-body').html(response.html);
    //             }
    //         }
    //     });
    //     $('#edit-game-modal').modal('show');
    // });
    //Game record
    // $(document).on('click', '.edit-game-btn', function () {
    //     var form_data = $('#game-edit-form').serialize();
    //     var type = 'Update-Game';
    //     $.ajax({
    //         type: 'POST',
    //         url: ADMIN_URL + 'config/config-student-arcade.php',
    //         data: {'form_data': form_data, 'type': type},
    //         async: true,
    //         cache: false,
    //         timeout: 10000,
    //         success: function (response) {
    //             var response = $.parseJSON(response);
    //             if (response.status == '1') {
    //                 $('#edit-game-modal').addClass('modal-success');
    //                 $('#edit-game-modal').find('.modal-body').html(response.msg);
    //                 $('#edit-game-modal').find('.edit-game-btn').hide();
    //             } else {
    //                 $('#edit-game-modal').addClass('modal-warning');
    //                 $('#edit-game-modal').find('.modal-body').html(response.msg);
    //                 $('#edit-game-modal').find('.edit-game-btn').hide();
    //             }
    //         }
    //     });
    // });
    // $('#add-game-modal').on('shown.bs.modal', function() {
    //     $("#game-title").focus();
    // });
    // $('#add-hangman-modal').on('shown.bs.modal', function() {
    //     $("#hangman-title").focus();
    // });
    // Add new record
    // $(".add-game-btn").click(function () {
    //     var form_data = $('#add-game-form').serialize();
    //     var title = $("#game-title").val();
    //     var type = 'Add-game';

    //     $.ajax({
    //         type: 'POST',
    //         url: ADMIN_URL + 'config/config-student-arcade.php',
    //         data: {'form_data': form_data, 'type': type},
    //         async: true,
    //         cache: false,
    //         timeout: 10000,
    //         success: function (response) {
    //             var response = $.parseJSON(response);
    //             var $tableBody = $('#game-table').find("tbody"),
    //                     $trLast = $tableBody.find("tr:last"),
    //                     $trNew = $trLast.clone();
    //             //$trLast.after($trNew);
    //             var count = $("#hangman-table tr:last td:nth-child(1)").text();
    //             if(count === ''){
    //                 var no = 1;
    //             } else {
    //                 var no = parseInt(count) + 1;
    //             }
    //             var html = '<tr><td>'+no+'</td><td>'+title+'</td><td><a href="#" class="badge bg-green game-data-edit" data-id="'+response.id+'"><i class="fa  fa-edit"></i></a></td><td><a href="#" class="badge bg-red game-data-delete" data-id="'+response.id+'"><i class="fa fa-trash-o"></i></a></td></tr>';
    //             $trLast.after(html);
    //              $("#gmae-title").val('');
    //             $("input[name='data']").val('');
    //             $("#add-game-modal").modal('hide');
    //         }
    //     });
    // });
     //Delete record
    // $(document).on('click', '.game-data-delete', function () {
    //     var this_data = $(this);
    //     bootbox.confirm({
    //         message: "Are you sure, you want to delete?",
    //         buttons: {
    //             confirm: {
    //                 label: 'Yes',
    //                 className: 'btn-success'
    //             },
    //             cancel: {
    //                 label: 'No',
    //                 className: 'btn-danger'
    //             }
    //         },
    //         callback: function (result) {
    //             if (result) {
    //                 var delete_id = this_data.attr('data-id');
    //                 var type = 'game-delete';
    //                 $.ajax({
    //                     type: 'POST',
    //                     url: ADMIN_URL + 'config/config-student-arcade.php',
    //                     data: {'delete_id': delete_id, 'type': type},
    //                     async: true,
    //                     cache: false,
    //                     timeout: 10000,
    //                     success: function (response) {
    //                         var response = $.parseJSON(response);
    //                         this_data.closest("tr").remove();
    //                         if (response.status == 1) {
    //                             $('.quick-ajax-response-game').find('.alert').removeClass('alert-warning alert-success').addClass('alert-success').html(response.msg).show();
    //                         } else {
    //                             $('.quick-ajax-response-game').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html(response.msg).show();
    //                         }
    //                     }
    //                 });
    //             }
    //         }
    //     });
    // });
});