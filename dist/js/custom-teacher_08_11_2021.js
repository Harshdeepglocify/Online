$(document).ready(function () {

$(function () {
    
    //Delete multiple teacher record
    $(document).on('click', '.delete_multi_teacher_btn', function () {
        var this_data = $(this);
        var text_data = $('.delete_multi_teacher_text').val();
        var delete_id = $(this).attr('data-id');
        var student_count = $(this).attr('data-count');
        if (text_data == "") {
            $('.delete_multi_teacher_text').focus();
            return false;
        }
        if (text_data.toLowerCase() == 'delete') {
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-teacher.php',
                data: {'teacher_delete_id': delete_id, 'action': 'delete_multi_teacher_account'},
                async: true,
                cache: false,
                timeout: 10000,
                success: function (response) {
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
            $('.delete_multi_teacher_text').focus();
        }
        

    });
        //update multiple student password
        $(document).on('click', '.update_multi_teacher_pass_btn', function () {
            var pass = $('.password').val();
            var password_confirm = $('.password_confirm').val();
            var teacher_id = $(this).attr('data-id');
            if (pass == ""){
                $('.password').focus();
                return false;
            }
            if (password_confirm == "") {            
                $('.password_confirm').focus();
                return false;
            }
            if (pass != password_confirm) {
                $('.error').html('Password does not match');
                return false;
            }
            
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-teacher.php',
                data: {'teacher_id': teacher_id,'password':pass, 'action': 'update_multi_teacher_password'},
                async: true,
                cache: false,
                timeout: 10000,
                success: function (response) {
                    var response = $.parseJSON(response);
                    $('#update-password').modal('hide');
                    if (response.status == true) {
                        $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    } else {
                        $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    }
                    $('#student_list_table').DataTable().ajax.reload();
                    // setTimeout(function () {
                    //     $('.alert-msg-response').delay(5000).fadeOut(1000);
                    //     window.location.reload();
                    // }, 2000);
                }
            });       
        });
        //update license limit
        $(document).on('click', '.update_student_limit', function () {
            var teacher_id = $(this).attr('data-id');
            var student_list_limit = $('.student_list_limit').val();
            
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-teacher.php',
                data: {'teacher_id': teacher_id,'student_list_limit':student_list_limit, 'action': 'update_teachet_student_limit'},
                async: true,
                cache: false,
                timeout: 10000,
                success: function (response) {
                    var response = $.parseJSON(response);
                    $('#update-lic-limit').modal('hide');
                    if (response.status == true) {
                        $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    } else {
                        $('.alert-msg-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                    }
                    $('#student_list_table').DataTable().ajax.reload();  
					setTimeout(function () {
                        $('.alert-msg-response').delay(5000).fadeOut(1000);
                        window.location.reload();
                    }, 2000);                 					
                }
            });       
        });
   });
});
function update_seats_limit(type="",license="",teacher_id=""){
     $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-teacher.php',
                data: {'license': license,'type':type,'teacher_id':teacher_id, 'action': 'check_student_add_limit'},
                async: true,
                cache: false,
                timeout: 10000,
                success: function (response) {
                    var response = $.parseJSON(response);
                    if(response.status == 1){
                        $.ajax({
                            type: 'POST',
                            url: ADMIN_URL + 'config/config-teacher.php',
                            data: {'license': license,'type':type,'teacher_id':teacher_id, 'action': 'update_student_add_limit'},
                            async: true,
                            cache: false,
                            timeout: 10000,
                            success: function (response) {
                                var response = $.parseJSON(response);
                                if(response.status == 1){
                                    $("#student_seats_"+teacher_id).html(response.student_seats_limit);
                                }else{
                                    alert(response.message);
                                }
                            }
                        });
                    }else{
                        alert(response.message);
                    }
                  }
            });
                
}
   
  

