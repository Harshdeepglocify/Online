(function($){
  $.fn.setCursorToTextEnd = function(){
      this.focus();
      var $thisVal = this.val();
      this.val('').val($thisVal);
      return this;
  }
})(jQuery);

jQuery(document).ready(function ($) {
  //Section B Start
  var hangman_title_alert = "Title name field required";
  var hangman_data_alert = "Text data field required";

  var game_title_alert = "Title name field required";
  var data_text_alert = "Text data field required";

  var i = 1; 

  // open model focus..............
  $('#add-hangman-modal').on('shown.bs.modal', function () {
    $('#hangman-title').setCursorToTextEnd();
    $('.text_data').val();
  });

  // Close model focus..............
  $("#add-hangman-modal").on("hidden.bs.modal", function(){
    $("#append").html("");
    $('#hangman-title,.text_data').val('');
  });

  // add more filed
  $(document).on('click', '#add-more-fields', function () {
    var title = $("#hangman-title").val();
    var status = 0;
    $(".text_data").each(function(){
      if($(this).val()==''){
        status =1;
      }
    }); 
   
    if(title!=''){
      if(status==0){
         i = i + 1;
        var input = '<div class="form-group"><input class="form-control text_data next' + i + '" type="text" name="fields[' + i + ']" placeholder="Type new word here..." required=""/></div>';
        $("#append").append(input);
        $('.next' + i).setCursorToTextEnd();
      }else{
         alert(hangman_data_alert);
        $('.next' + i).setCursorToTextEnd();
      } 
    }else{
      alert(hangman_title_alert);
      $("#hangman-title").setCursorToTextEnd();
    } 
  });

  // Add new record
  $(".add-hangman-btn").click(function () { 
    var title = $("#hangman-title").val();
    var text_data = $(".text_data").val();

    if(title!=''){
      if(text_data!=''){
        var form_data = $('#add-hangman-form').serialize(); 
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
                if (response.type == 2) {
                    $('#hangman-table tr:last').after(response.html);
                }
                $(".hangman-table").load(window.location + " #hangman-table"); 
                $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                $('.hangman_msg .quick-ajax-response').show(); 
                $("#hangman-title").val('');  
                $(".text_data").val('');
                $("#add-hangman-modal").modal('hide');
                $('.hangman_msg .quick-ajax-response').delay(5000).fadeOut(1000);
            },
            error: function (xhr, status, err) {}
        }); 
      }else{
        alert(hangman_data_alert);
        $(".text_data").setCursorToTextEnd();
      }
    }else{
      alert(hangman_title_alert);
      $("#hangman-title").setCursorToTextEnd();
    } 
  });
$(document).on('click', '.hangman-import-modal', function () {
  $('#add-hangman-modal').modal('hide');
  $('#hangman-import-modal').modal('show');
   setTimeout(function (){
    $('#hangman_import_code').focus();
  }, 500);
});
$(document).on('click', '.hangman-export-modal', function () {
  table_id = $('.table_id').val();
  var student_id = $('#user_id').val();
  var dt = new Date().getTime();
  var uuid = 'xxxx-xxxx'.replace(/[xy]/g, function(c) {
    var r = (dt + Math.random()*16)%16 | 0;
    dt = Math.floor(dt/16);
    return (c=='x' ? r :(r&0x3|0x8)).toString(16);
  });
  
  $('.hangman_code_copy').html("<input type='text' class='form-control' name='hangman_code_copy' id='hangman_code_copy' value='"+uuid+"'>");  
  var copyText = $('#hangman_code_copy');
  copyText.select();
  document.execCommand("copy");

  $.ajax({

    //alert('data');

    type: 'POST',

    url: ADMIN_URL + 'config/config-student-arcade.php',

    data: {

      code: uuid,

      student_id: student_id,
      table_id: table_id,

      type: 'hangman-export-entry',

    },

    async: true,

    cache: false,

    timeout: 10000,

    success: function (response) {

      var response = $.parseJSON(response);


      
      if (response.status == 1) {
         $('.hangman-msg').fadeIn();

        $('.hangman-msg').html(response.msg).show();

        $('.hangman-msg').find('.alert').show();
       
      } else {
        $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
        $('.hangman_msg .quick-ajax-response').show();  
      }
      $('.hangman_msg .quick-ajax-response').delay(5000).fadeOut(1000);


    },

    error: function (xhr, status, err) {

    }

  });
});
// add import here 
$(document).on('click', '.add-hangman-import-btn', function () {
  
  var student_id = $('#user_id').val();
  var this_data = $(this);

  if ($('#hangman_import_code').val() == "") {

    //alert(desk_title_alert);

    $('#hangman_import_code').setCursorToTextEnd();

    return false;

  }

   
  var import_code = $('#hangman_import_code').val();


  $.ajax({

    //alert('data');

    type: 'POST',

    url: ADMIN_URL + 'config/config-student-arcade.php',

    data: {

      code: import_code,

      student_id: student_id,

      type: 'hangman-import-entry',

    },

    async: true,

    cache: false,

    timeout: 10000,

    success: function (response) {

      var response = $.parseJSON(response);



      if (response.status == 1) {

        
        $('#hangman-table tr:last').after(response.html);	
        $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
        $('.hangman_msg .quick-ajax-response').show(); 

      } else {

        //$('.quick-ajax-response').find('.alert').removeClass(' alert-warning  alert-success').addClass('alert-warning').html(response.msg);

        $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
        $('.hangman_msg .quick-ajax-response').show(); 

      }
      $('#hangman-import-modal').find('#hangman_import_code').val('');
      $("#hangman-import-modal").modal('hide');
      $('.hangman_msg .quick-ajax-response').delay(5000).fadeOut(1000);

      //$('#deck-maker-modal .remove-new-field').trigger('click');

    },

    error: function (xhr, status, err) {

    }

  });
});
  //Delete record
  $(document).on('click', '.delete-hangman', function () {
      var this_data = $(this);
      bootbox.confirm({
          message: "Are you sure, you want to delete?",
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
                            $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                            $('.hangman_msg .quick-ajax-response').show();  
                            var table = $('#student_arcade_hangman_list_table').DataTable();
                            table.clear().draw();
                            var table = $('#hangman-table').DataTable();
                              table.ajax.reload(null, false);
                          } else {
                            $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                            $('.hangman_msg .quick-ajax-response').show();  
                          }
                           $('.hangman_msg .quick-ajax-response').delay(5000).fadeOut(1000);
                      }
                  });
              }
          }
      });
  });

  //Show edit modal popup
  $(document).on('click', '.edit-hangman-modal', function () {
      var table_id = $(this).attr('data-id');
  
  $('.hangman-msg').hide();
  $('.hangman_code_copy').html('');

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
                  var table = $('#student_arcade_hangman_list_table').DataTable();
                  table.clear().draw();

              }
          }
      });
      $('#edit-hangman-modal').modal('show');
      $('.text_data_edit').setCursorToTextEnd();
  });

  //Update record
  $(document).on('click', '.edit-hangman-btn', function () {
      var form_data = $('#hangman-edit-form').serialize();
      var type = 'Update-Hangman';
      var user_type = $('#user_type').val();
      $.ajax({
          type: 'POST',
          url: ADMIN_URL + 'config/config-student-arcade.php',
          data: {'form_data': form_data, 'type': type,'user_type': user_type},
          async: true,
          cache: false,
          timeout: 10000,
          success: function (response) {
              var response = $.parseJSON(response);
              if (response.status == '1') {
                  // $('#edit-hangman-modal').addClass('modal-success');
                  // $('#edit-hangman-modal').find('.modal-body').html(response.msg);
                  //  $('#edit-hangman-modal .modal-title').removeClass('color');
                  // $('#edit-hangman-modal').find('.edit-hangman-btn').hide();
                  /*$('.hangman_tr_' + response.table_id).remove();
                  $('#hangman-table tr:first').after(response.html);
                  $('.hangman_tr_' + response.table_id).closest("tr").attr('style', 'background-color:green;color:white;');
                  setTimeout(function () {
                      $('.hangman_tr_' + response.table_id).closest("tr").removeAttr('style', 'background-color:green;color:white;');
                  }, 5000);*/

                  $('.hangman_msg .quick-ajax-response').show();
                  $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                  
                  $('.hangman_msg .quick-ajax-response').delay(5000).fadeOut(1000);


                  $('#edit-hangman-modal').find('.edit-hangman-btn').hide();
                  $('#edit-hangman-modal .modal-title').removeClass('color');
                  $("#edit-hangman-modal .close").trigger('click');
                   $('#edit-hangman-modal').find('.modal-body').html(response.html);
                  var table = $('#student_arcade_hangman_list_table').DataTable();
                  table.clear().draw();

                  var table = $('#hangman-table').DataTable();
                              table.ajax.reload(null, false);    

              } else {
                  $('#edit-hangman-modal').addClass('modal-warning');
                  $('#edit-hangman-modal').find('.modal-body').html(response.msg);
                  $('#edit-hangman-modal').find('.edit-hangman-btn').hide();
              }
          }
      });
  });

  //Share Data
  $(document).on('click', '.hangman-share-submit', function () {

   
    var share_ids = [];
    var i = 0;
    $('.share_hangman_ids:checked').each(function () {
      share_ids[i++] = $(this).val();
    });
  
    if(share_ids == ''){
      $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html('Please select at least one hangman.').show();
      $('.hangman_msg .quick-ajax-response').show();
      return false;
    }else{
       $('.hangman_msg .quick-ajax-response').removeClass(' alert-warning  alert-success').hide();
    }
  
  var share_user_ids = [];
  var j = 0;
  $('.share_user_ids:checked').each(function () {
          share_user_ids[j++] = $(this).val();
  });
    
  if(share_user_ids == ''){
    $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html('Please select at least one student.').show();
    $('.hangman_msg .quick-ajax-response').show();
    return false;
  }else{
    $('.hangman_msg .quick-ajax-response').removeClass(' alert-warning  alert-success').hide();
  }

    $.ajax({
      type: 'POST',
      url: ADMIN_URL + 'config/config-student.php',
      data: {
          'type': 'hangman_arcade',
          'share_ids' : share_ids,
          'share_user_list' : share_user_ids 
      },
      async: true,
      cache: false,
      timeout: 10000,
      success: function (response) {
          var response = $.parseJSON(response);
          if (response.status == 1) {
              $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-success').html(response.msg).show();
              $('.hangman_msg .quick-ajax-response').show();
              $('.share_hangman_ids').removeAttr('checked');
      $('.share_user_ids').removeAttr('checked');
              
          } else {
              $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html(response.msg).show();
              $('.hangman_msg .quick-ajax-response').show();
              $('.hang-sidebar-alerts').show();
              $('.share_hangman_ids').removeAttr('checked');
      $('.share_user_ids').removeAttr('checked');
          }
            $('.hangman_msg .quick-ajax-response').delay(5000).fadeOut(1000);
      },
      error: function (xhr, status, err) {

      }
    });

  });




  

  // open model focus..............
  $('#add-game-modal').on('shown.bs.modal', function () {
    $('#game-title').setCursorToTextEnd();
    $('#data_text').val();
  }); 

  // Close model focus..............
  $("#add-game-modal").on("hidden.bs.modal", function(){ 
    $('#game-title,#data_text').val('');
  });

  
  // Add new record
  $(".add-game-btn").click(function () {
     var title = $("#game-title").val();
      var data_text = $("#data_text").val(); 
      if(title!=''){
        if(data_text!=''){
          var form_data = $('#add-game-form').serialize(); 
          var type = 'Add-game';
          $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student-arcade.php',
            data: {'form_data': form_data, 'type': type},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                if (response.type == 2) {
                          $('#game-table tr:last').after(response.html);
                      }
                $(".game-table").load(window.location + " #game-table"); 
                $('.crazy_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-success').html(response.msg).show();
                $('.crazy_msg .quick-ajax-response').show(); 

                $("#gmae-title").val('');
                $("input[name='data']").val('');
                $("#add-game-modal").modal('hide');
                 $('.crazy_msg .quick-ajax-response').delay(5000).fadeOut(1000);
            }
          });
        }else{
          alert(data_text_alert);
          $("#data_text").setCursorToTextEnd();
        }
      }else{
        alert(game_title_alert);
        $("#game-title").setCursorToTextEnd();
      } 
  });


  //For section C
  $(document).on('click', '.game-data-edit', function () {
      var table_id = $(this).attr('data-id');
      var type = 'Update-Game';
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

                  $('#edit-game-modal').removeClass('modal-success modal-warning');
                  $('#edit-game-modal').find('.edit-game-btn').show();
                  $('#edit-game-modal').find('.modal-body').html(response.html);
              }
          }
      });
      $('#edit-game-modal').modal('show');
  });
  //Game record
  $(document).on('click', '.edit-game-btn', function () {
      var form_data = $('#game-edit-form').serialize();
      var type = 'Update-Game';
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
                 // $(".game-table").load(window.location + " #game-table"); 
                 //  $('#edit-game-modal').addClass('modal-success');
                 //  $('#edit-game-modal').find('.modal-body').html(response.msg);
                 //  $('#edit-game-modal').find('.edit-game-btn').hide();
                 //   $('#edit-game-modal .modal-title').removeClass('color');
                 $('.game_tr_' + response.table_id).closest("tr").remove();
                  $('#game-table tr:first').after(response.html);
                  $('.game_tr_' + response.table_id).closest("tr").attr('style', 'background-color:green;color:white;');
                  setTimeout(function () {
                      $('.game_tr_' + response.table_id).closest("tr").removeAttr('style', 'background-color:green;color:white;');
                  }, 5000);

                  $('.crazy_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                  $('.crazy_msg .quick-ajax-response').show();
                  $('.crazy_msg .quick-ajax-response').delay(5000).fadeOut(1000);


                  $('#edit-game-modal').find('.edit-game-btn').hide();
                  $('#edit-game-modal .modal-title').removeClass('color');
                  $("#edit-game-modal .close").trigger('click');
              } else {
                  $('#edit-game-modal').addClass('modal-warning');
                  $('#edit-game-modal').find('.modal-body').html(response.msg);
                  $('#edit-game-modal').find('.edit-game-btn').hide();
              }
          }
      });
  });
  
  
   //Delete record
  $(document).on('click', '.game-data-delete', function () {
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
                  var type = 'game-delete';
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
                            $('.crazy_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-success').html(response.msg).show();
                            $('.crazy_msg .quick-ajax-response').show(); 

                          } else {
                             $('.crazy_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html(response.msg).show();
                            $('.crazy_msg .quick-ajax-response').show(); 

                          }
                            $('.crazy_msg .quick-ajax-response').delay(5000).fadeOut(1000);
                      }
                  });
              }
          }
      });
  });

  

  //Share Data
  $(document).on('click', '.crazy-share-submit', function () {

    var share_user_list = $('.crazy_share_user_list').val();
    var share_ids = [];
    var i = 0;
    $('.share_crazy_ids:checked').each(function () {
       share_ids[i++] = $(this).val();
    });

    if(share_ids == ''){
      $('.crazy_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html('Please select at least one crazy phrase.').show();
      $('.crazy_msg .quick-ajax-response').show(); 
      return false;
    }else{ 
      $('.crazy_msg .quick-ajax-response').removeClass(' alert-warning  alert-success').hide();
    }

    if( share_user_list == null){
      $('.crazy_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-warning').html('Please select at least one student.').show();
      $('.crazy_msg .quick-ajax-response').show(); 
      return false;
    }else{
      $('.crazy_msg .quick-ajax-response').removeClass(' alert-warning  alert-success').hide();
    }

    $.ajax({
      type: 'POST',
      url: ADMIN_URL + 'config/config-student.php',
      data: {
          'type': 'crazy_arcade',
          'share_ids' : share_ids,
          'share_user_list' : share_user_list 
      },
      async: true,
      cache: false,
      timeout: 10000,
      success: function (response) {
          var response = $.parseJSON(response);
          if (response.status == 1) {
            $('.crazy_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').addClass('alert-success').html(response.msg).show();
            $('.crazy_msg .quick-ajax-response').show(); 
              $('.share_crazy_ids').removeAttr('checked');
              $("option:selected").removeAttr("selected");
          } else {
              $('.crazy-sidebar-alert').removeClass(' alert-warning  alert-success').addClass('alert-warning').html(response.msg);
              $('.crazy-sidebar-alerts').show();  

              $('.share_crazy_ids').removeAttr('checked');
          }
           $('.crazy_msg .quick-ajax-response').delay(5000).fadeOut(1000);
      },
      error: function (xhr, status, err) {

      }
    });

  });


   $(document).on('click', '#ArcadeHistoryDateSection #ArcadeDateFilterBtn', function () {
      $('#ArcadeHistoryDateSection').addClass('ajax_loading');
      var ArcadeDatePicker1 = $('#ArcadeHistoryDateSection #ArcadeDatePickerFrom').val();
      var ArcadeDatePicker2 = $('#ArcadeHistoryDateSection #ArcadeDatePickerTo').val();
      var student_id = $("#student_id").val();


      $.ajax({
          type: 'POST',
          url: ADMIN_URL + 'config/config-student.php',
          data: {action_type: 'get_arcade_history_data', start_date: ArcadeDatePicker1, end_date: ArcadeDatePicker2,student_id:student_id},
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
          },
          error: function (xhr, status, err) {

          }
      });
  });

    

   
   

});
function display_arcade_history_data(){
      var ArcadeDatePickerFrom = $("#ArcadeDatePickerFrom").val();
      var ArcadeDatePickerTo   = $("#ArcadeDatePickerTo").val();
      
      $('.ArcadeHistory_table_wrap').removeClass('history_hide');
      $('.no-activity-msg-wrap').hide();        
      $.ajax({
            type: 'post',
            url: ADMIN_URL + 'config/config-student.php',
            data: {'action':'set_ajax_date_field','is_ajax' : '1','ArcadeDatePickerFrom':ArcadeDatePickerFrom,'ArcadeDatePickerTo':ArcadeDatePickerTo},
            async: true,
            success: function (output) {
               var table = $('#student_arcade_history_list_table').DataTable();
               table.ajax.reload(null, false);
             }
          });
                  
     
   }
   
$(".new-option-btn").click(function () {
      $(".new-option-section-wrap").toggle();
      $(this).toggleClass('import-menu-display');
});

$(".new-option-btn-dashboard").click(function () {
      $(".new-option-section-wrap").toggle();
      $(this).toggleClass('import-menu-display');
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
      form_data.append('insert_type', 'Arcade-OL');
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
                $("#hangman-table").append(response.html);
                $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                $('.hangman_msg .quick-ajax-response').show(); 

              } else {
                $('.hangman_msg .quick-ajax-response').find('.alert').removeClass('alert-warning alert-success').html(response.msg).show();
                $('.hangman_msg .quick-ajax-response').show(); 

              }
              $("#add-typio-csv-import-form")[0].reset();
              $("#typio-import-csv_modal").modal('hide');
              $('.hangman_msg .quick-ajax-response').delay(5000).fadeOut(1000);
          },

          error: function (xhr, status, err) {

          }

      });
  });*/
$(document).on('click', '.typio-csv-import-modal', function () {
  
      $('#typio-import-csv_modal').modal('show');
  });;