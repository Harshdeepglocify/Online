// Keyboard Progress Section JavaScript
$(document).ready(function() {
    
    // Keyboard Progress Section - Typing Journey Button
    $(document).on('click', '#TypiokpTypingJourneyBtn', function() {
        var student_id = $('#student_idkp').val();
        loadKeyboardProgressData(student_id, 'Typio-Journey');
    });

    // Keyboard Progress Section - Basic Modes Button
    $(document).on('click', '#TypiokpBasicModesBtn', function() {
        var student_id = $('#student_idkp').val();
        loadKeyboardProgressData(student_id, 'Typio-OL');
    });

    // Delete lesson button
    $(document).on('click', '.delete-lesson-btn', function() {
        var lesson_id = $(this).data('id');
        var student_id = $('#student_idkp').val();
        
        if (confirm('Are you sure you want to delete this lesson?')) {
            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {
                    'action': 'ajax_delete_lesson',
                    'is_ajax': '1',
                    'lesson_id': lesson_id,
                    'student_id': student_id
                },
                dataType: 'json',
                success: function(response) {
					
                    if (response.status == '200') {
                        // Reload the current app type data
                        var current_app_type = $('#current_app_type').val() || 'Typio-Journey';
                        loadKeyboardProgressData(student_id, current_app_type);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error deleting lesson');
                }
            });
        }
    });

    // Function to load keyboard progress data
    function loadKeyboardProgressData(student_id, app_type) {
        $('#TypioKeyboardProgressSection').addClass('ajax_loading');
        
        // Store current app type
        $('#current_app_type').remove();
        $('body').append('<input type="hidden" id="current_app_type" value="' + app_type + '">');
        
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {
                'action': 'ajax_keyboard_progress_data',
                'is_ajax': '1',
                'student_id': student_id,
                'app_type': app_type
            },
            dataType: 'json',
            success: function(response) {
				var response = $.parseJSON(response);
                if (response.status == '200') {
                    // Update lesson list
                    $('#lesson_list_container').html(response.html);
                    
                    // Update progress counts
                    $('#total_lessons_display').text(response.total_lessons);
                    $('#lessons_progress').text(response.completed_lessons);
                    $('#total_lessons_progress').text(response.total_lessons);
                    $('#keys_progress').text(response.keys_progress);
                    $('#next_lesson_display').text(response.next_lesson);
                    
                    // Update date range (you can customize this based on your needs)
                    $('#date_from_display').text('09/29/2024');
                    $('#date_to_display').text('10/01/2025');
                    
                    // Update metrics (you can customize these based on your data)
                    $('#wpm_display').text('40 WPM');
                    $('#acc_display').text('89% Accuracy');
                    $('#err_display').text('6 Errors');
                    
                    // Show/hide buttons based on data availability
                    checkButtonVisibility(student_id);
                }
                $('#TypioKeyboardProgressSection').removeClass('ajax_loading');
            },
            error: function() {
                alert('Error loading keyboard progress data');
                $('#TypioKeyboardProgressSection').removeClass('ajax_loading');
            }
        });
    }

    // Function to check which buttons should be visible
    function checkButtonVisibility(student_id) {
        $.ajax({
            type: 'POST',
            url: ADMIN_URL + 'config/config-student.php',
            data: {
                'action': 'ajax_check_app_types',
                'is_ajax': '1',
                'student_id': student_id
            },
            dataType: 'json',
            success: function(response) {
					var response = $.parseJSON(response);
                if (response.status == '200') {
                    var hasJourney = response.has_journey;
                    var hasOL = response.has_ol;
                    
                    if (hasJourney && hasOL) {
                        // Show both buttons
                        $('#TypiokpTypingJourneyBtn').show();
                        $('#TypiokpBasicModesBtn').show();
                    } else if (hasJourney) {
                        // Show only Journey button
                        $('#TypiokpTypingJourneyBtn').show();
                        $('#TypiokpBasicModesBtn').hide();
                    } else if (hasOL) {
                        // Show only Basic Modes button
                        $('#TypiokpTypingJourneyBtn').hide();
                        $('#TypiokpBasicModesBtn').show();
                    } else {
                        // Hide both buttons
                        $('#TypiokpTypingJourneyBtn').hide();
                        $('#TypiokpBasicModesBtn').hide();
                    }
                }
            }
        });
    }

    // Load initial data when page loads
    var student_id = $('#student_idkp').val();
    if (student_id) {
        checkButtonVisibility(student_id);
        // Load default data (Journey if available, otherwise OL)
        loadKeyboardProgressData(student_id, 'Typio-Journey');
    }
});
