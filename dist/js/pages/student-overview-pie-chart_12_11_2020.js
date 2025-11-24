

$(function () {

	

    Highcharts.getOptions().plotOptions.pie.colors=[

    '#f7a35c','#7cb5e9', '#90ed7d', '#f15c80'];

 

    // Build the chart

    Highcharts.chart('piechartOverview', {

        exporting: { enabled: false },

        data: {

            table: 'piechartOverview_table'

        },

        chart: {

            plotBackgroundColor: null,

            plotBorderWidth: null,

            plotShadow: false,

            type: 'pie', 



        }, 

        tooltip: {

            pointFormat: '<b>{point.percentage:.1f}%</b>'

        },

        plotOptions: {

            pie: {

                allowPointSelect: true,

                cursor: 'pointer',



                dataLabels: {

                    enabled: false  

                },

                showInLegend: true

            }

        },

        legend: { 

            align: 'left',  

        },

        title: {

            text: ' '

        }, 

        credits: {

            enabled: false

        },

        series: [{

            name: '',

            colorByPoint: true,

           

        }]

    }); 



    // Date wise show chart



  Highcharts.getOptions().plotOptions.pie.colors=[

  '#f7a35c','#7cb5e9', '#90ed7d', '#f15c80'];



  Highcharts.chart('piechartOverview2', {



    exporting: { enabled: false },

    data: {

        table: 'piechartOverview_table2'

    },

    chart: {

        plotBackgroundColor: null,

        plotBorderWidth: null,

        plotShadow: false,

        type: 'pie', 

    }, 

    tooltip: {

        pointFormat: '<b>{point.percentage:.1f}%</b>'

    },

    plotOptions: {

        pie: {

            allowPointSelect: true,

            cursor: 'pointer',

            dataLabels: {

                enabled: false

            },

            showInLegend: true

        }

    },

    legend: { 

        align: 'left', 

        },

    title: {

        text: ' '

    }, 

    credits: {

        enabled: false

    },

    series: [{

        name: '',

        colorByPoint: true         

    }]

  }); 



});

 



function OverviewPiechart() {



   

  Highcharts.getOptions().plotOptions.pie.colors=[

  '#f7a35c','#7cb5e9', '#90ed7d', '#f15c80'];



  $("#piechartOverview2").highcharts({  



    exporting: { enabled: false },

    data: {

        table: 'piechartOverview_table2'

    },

    chart: {

        plotBackgroundColor: null,

        plotBorderWidth: null,

        plotShadow: false,

        type: 'pie',

         renderTo: document.getElementById('piechartOverview2')

    }, 

    tooltip: {

        pointFormat: '<b>{point.percentage:.1f}%</b>'

    },

    plotOptions: {

        pie: {

            allowPointSelect: true,

            cursor: 'pointer',

            dataLabels: {

                enabled: false

            },

            showInLegend: true

        }

    },

    legend: { 

        align: 'left', 

        },

    title: {

        text: ' '

    }, 

    credits: {

        enabled: false

    },

    series: [{

        name: '',

        colorByPoint: true         

    }]

  }); 

}

 



jQuery(document).ready(function ($) { 

    /* Selected date Area Chart */

    OverviewPiechart();

   



    $(document).on('click', '#HistoryDateSection #DateFilterBtn', function () {

    	

        $('#HistoryDateSection').addClass('ajax_loading');

        var HistoryDate1 = $('#HistoryDateSection #HistoryDate1').val();

        var HistoryDate2 = $('#HistoryDateSection #HistoryDate2').val();

        var student_id     = $('#HistoryDateSection #student_id').val();

 

        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-student.php',

            data: {action_type: 'get_overview_table_history', start_date: HistoryDate1, end_date: HistoryDate2, student_id: student_id},

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);  

                if (response.status == '200') {

                  $('#TotalRow').html(response.total_row);

                  $('#HistorySection').find('#History_table').html(response.html); 
                  $('#HistorySection .history_hide').removeClass('history_hide');
                  $('#HistorySection .no-history-msg-wrap').hide();

                } 

            },

            error: function (xhr, status, err) { }  

        });



        $.ajax({

            type: 'POST',

            url: ADMIN_URL + 'config/config-student.php',

            data: {action_type: 'get_overview_pie_char_history', start_date: HistoryDate1, end_date: HistoryDate2, student_id: student_id},

            async: true,

            cache: false,

            timeout: 10000,

            success: function (response) {

                var response = $.parseJSON(response);

                if (response.status == '200') {

                    $('#HistorySection').find('#pie_chart_table').html(response.html);
					
                    

                OverviewPiechart();

                }

                  

            },

            error: function (xhr, status, err) {



            }

        });

        $('#HistoryDateSection').removeClass('ajax_loading');

       



    });





});

