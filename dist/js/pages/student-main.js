$(function () {
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

    /*Quick Cards Section */

    //Date picker
    $('.datepicker').datepicker({
        autoclose: true,
        orientation: "bottom"
    });

    $("#HomeArcadeDatePickerFrom").datepicker().on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#HomeArcadeDatePickerTo').datepicker('setStartDate', minDate);
    });
    $("#HomeArcadeDatePickerTo").datepicker().on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#HomeArcadeDatePickerFrom').datepicker('setEndDate', maxDate);
    });
});
/*Quick Cards Section */
function piehistorychart() {
    $('#pieChartHomeHistory').highcharts({
        data: {
            table: document.getElementById('pieChartHomeHistory_table')
        },
        chart: {
            type: 'pie',
            renderTo: document.getElementById('pieChartHomeHistory'),
            height: 280,
            options3d: {
                enabled: true,
                alpha: 2
            }
        },
        title: {
            text: false
        },
        subtitle: {
            text: false
        },
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
                text: 'Values'
            },
            labels: {
                formatter: function () {
                    return this.value; // 1000 + 'k';
                }
            }
        },
        credits: {
            enabled: false
        },
        tooltip: {
            pointFormat: '{point.y:,.0f}%'
        },
        plotOptions: {
            pie: {
                innerSize: 100,
                depth: 45
            }
        },
        colors: ['#FF9F40', '#FF6384', '#00A2E8'],
    });
}

function barChartHomeTypio1() {
	$('#barChartHomeTypio1').highcharts({
        data: {
            table: document.getElementById('barChartHomeTypio1_table')
        },
        title: {
            text: "Average"
        },
        chart: {
            type: 'column',
            renderTo: document.getElementById('barChartHomeTypio1'),
        },
        yAxis: {
            allowDecimals: false,
            visible: false,
            labels: {
                formatter: function () {
                    return 100 * this.value / $(this.axis.tickPositions).last()[0] + '%';
                }
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },

        plotOptions: {
            series: {
                borderWidth: 5,
                maxPointWidth: 50,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}%'
                }
            },
            column: {
                colorByPoint: true,
            }
        },
        colors: ['#FF6384', '#00A2E8', '#FF9F40'],
        tooltip: {
            formatter: function () {
                return this.point.y + ' ' + this.point.name.toUpperCase();
            }
        },
    });
}
function areaChartHomeTypio1() {
	$('#areaChartHomeTypio1').highcharts({

        data: {
            table: document.getElementById('areaChartHomeTypio1_table')
        },
        chart: {
            type: 'areaspline',
            renderTo: document.getElementById('areaChartHomeTypio1'),
        },
        title: {
            text: false
        },
        subtitle: {
            text: false
        },
		credits: {
            enabled: false
        },
        xAxis: {
            allowDecimals: false,
            visible: false,
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
        colors: ['#FF6384', '#00A2E8'],
        tooltip: {
            pointFormat: '{series.name} : <b>{point.y:,.0f}</b>'
        },
        plotOptions: {
            areaspline: {
                pointStart: 1940,
                fillOpacity: 0.2,
                marker: {
                    enabled: true,
                    symbol: 'circle',
                    radius: 3,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                },
                column: {
                    stacking: 'percent'
                }
            }
        }
    });
}
function areaChartHomeQuickcards() {
 	$('#areaChartHomeQuickcards').highcharts({
        data: {
            table: document.getElementById('areaChartHomeQuickcards_table')
        },
        chart: {
            type: 'areaspline',
            renderTo: document.getElementById('areaChartHomeQuickcards'),
        },
        title: {
            text: false
        },
        subtitle: {
            text: false
        },
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
        credits: {
            enabled: false
        },
        legend: {
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
        },
    });
}
jQuery(document).ready(function ($) {
    //Section A
    piehistorychart();

    barChartHomeTypio1();

    areaChartHomeTypio1();
    
    areaChartHomeQuickcards();

    $(document).on('change', '.checked', function () {
        if (this.checked) {
            $(".checked").not(this).prop('checked', false);
        }
    });

    $("#HomeHistoryDateFilterBtn").click(function (e) {

        e.preventDefault();

        var startdate = $('#report_start_date').val();
        var enddate = $('#report_end_date').val();
        var student_id = $('#student_id').val();
        var typiochecked = qcchecked = acchecked = '';

        if ($("#HomeHistoryAppTypio").prop("checked")) {
            var typiochecked = 'typio';
        }
        if ($("#HomeHistoryAppQC").prop("checked")) {
            var qcchecked = 'qc';
        }
        if ($("#HomeHistoryAppAC").prop("checked")) {
            var acchecked = 'ac';
        }
        if ($("#this_month").prop("checked")) {
            var this_month = '1';
            $("#this_week").removeAttr("checked");
        }
        if ($("#this_week").prop("checked")) {
            var this_week = '1';
            $("#this_month").removeAttr("checked");
        }

        $.ajax({
            method: "POST",
            url: ADMIN_URL + 'config/config-student.php',
            data: {student_id:student_id,startdate: startdate, enddate: enddate, typio: typiochecked, qc: qcchecked, ac: acchecked, this_week: this_week, this_month: this_month},
            async: true,
            cache: false,
            timeout: 10000,
            success: function (response) {
                var response = $.parseJSON(response);
                
                // pie chart section
                $("#pieChartHomeHistory_table").html(response.main_chart);
                var charts = $('#pieChartHomeHistory').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();

                // typio chart section
                $("#typio_table_html").html(response.typio_table_html);

                $("#barChartHomeTypio1_table").html(response.typio_chart);
                var charts = $('#barChartHomeTypio1').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();

                $("#areaChartHomeTypio1_table").html(response.typio_area_chart_html);
                var charts = $('#areaChartHomeTypio1').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();

                $(".typio_table_html_count").html(response.typio_table_html_count);
                
                // Qucik cards chart section
                $("#areaChartHomeQuickcards_table").html(response.QuickCards_chart_data);
                var charts = $('#areaChartHomeQuickcards').highcharts();
                var options = charts.options;
                charts = new Highcharts.Chart(options);
                charts.redraw();

                $(".quick_history_count").html(response.QuickCards_data_html_count);
                $("#QuickCards_data_html").html(response.QuickCards_data_html);
                
                // Arcade chart section
                $(".arcade_history_count").html(response.arcade_data_html_count);
                $("#HomeArcadeHistory_table").html(response.arcade_data_html);
            },
            error: function (xhr, status, err) {

            }
        });
    });
});