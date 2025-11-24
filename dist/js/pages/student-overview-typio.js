

$(function () {
    
    /* Last week Bar Chart */
    /*Highcharts.chart('barChartTypio1Tab2', {
    	exporting: { enabled: false },
        data: {
            table: 'barChartTypio1Tab2_table'
        },
        title: {
            text: "Average"
        },
        chart: {
            type: 'column'
        },
        yAxis: {
            allowDecimals: false,
            visible: false,
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                maxPointWidth: 50,
                dataLabels: {
                    enabled: false,
	                color: '#000000',
	                backgroundColor: '#FFFFFF',
	                borderWidth: '1',
	                align: 'center',
	                
                }
            },
            column: {
                colorByPoint: false
            }
        },
        colors: ['#FF6384', '#00A2E8', '#FF9F40'],
        tooltip: {
            formatter: function () {
            	if(this.point.name == 'WPM' || this.point.name == 'Errors'){
            		return this.point.y +' '+ this.point.name;	
            	}else{
            		return this.point.y + this.point.name;
            	}

            }
        },
    });*/

    /* Last week Area Chart */
   /* Highcharts.chart('areaChartTypio1Tab2', {
    	exporting: { enabled: false },
        data: {
            table: 'areaChartTypio1Tab2_table'
        },
        chart: {
            type: 'areaspline'
        },
        title: {
            text: 'History'
        },
        subtitle: {
            // text: false
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
                text: ''
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
        colors: ['#FF6384', '#00A2E8','#FFBC75'],
        tooltip: {
        	useHTML: true,
            //pointFormat: '<b>{point.y:,.0f}{series.name}</b>'
            formatter: function () {
                
            	if(this.series.name == 'WPM' || this.series.name == 'Errors'){
            		return this.point.name + '<br/><b>'+this.point.y +' '+ this.series.name +'</b>';
            	} else{
            		return this.point.name + '<br/><b>'+this.point.y + this.series.name +'</b>';
            	}

            }
        },
        plotOptions: {
            areaspline: {
                pointStart: 1940,
                fillOpacity: 0.3,
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 3,
                    states: {
                        hover: {
                            enabled: false
                        }
                    }
                }
            }
        }
    });*/
});
function typiohistoryareachart_render() {

    $("#historyTypio1Tab2").highcharts({
        exporting: { enabled: false },
        data: {
            table: document.getElementById('historyTypio1Tab2_table')
        },
        chart: {
            type: 'column',
            renderTo: document.getElementById('historyTypio1Tab2')
        },
         title: {
             text: "Average"
         },
         yAxis: {
             allowDecimals: false,
             visible: false,
         },
         legend: {
             enabled: false
         },
         credits: {
             enabled: false
         },
         plotOptions: {
             series: {
                 borderWidth: 0,
                 maxPointWidth: 50,
                 dataLabels: {
                     enabled: true,
                     color: '#000000',
                     backgroundColor: '#FFFFFF',
                     borderWidth: '1',
                     align: 'center',
                     /*padding:16,*/
                 }
             },
             column: {
                 colorByPoint: true
             }
         },
         colors: ['#FF6384', '#00A2E8', '#FF9F40'],
         tooltip: {
             formatter: function () {
                 if(this.point.name == 'WPM' || this.point.name == 'Errors'){
                     return this.point.y +' '+ this.point.name;  
                 }else{
                     return this.point.y + this.point.name;
                 }

             }
         },
     });


    $("#TypioHistoryAreaChart").highcharts({
    	exporting: { enabled: false },
        data: {
            table: document.getElementById('TypioHistoryAreaChart_table')
        },
        chart: {
            type: 'areaspline',
            renderTo: document.getElementById('TypioHistoryAreaChart')
        },
        title: {
            text: ''
        }, 
        credits: {
            enabled: false
        },
        xAxis: {
            allowDecimals: true,
            visible: true,
            labels: {
                formatter: function () {
                    return this.value; // clean, unformatted number for year
                }
            }
        },
        yAxis: {
            title: {
                text: ''
            },
            labels: {
                formatter: function () {
                    return this.value;
                }
            }
        },
		legend: {
             enabled: false
         },
        tooltip: {
        	formatter: function () {
            	if(this.series.name == 'WPM' || this.series.name == "Errors"){
            		return this.point.name + '<br/><b>'+this.point.y +' '+ this.series.name +'</b>';
            	}else{
            		return this.point.name + '<br/><b>'+this.point.y + this.series.name +'</b>';
            	}

            }
           // pointFormat: '<b>{point.y:,.0f}{series.name}</b>'
        },
        colors: ['#FF6384', '#00A2E8','#FFBC75'],
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

jQuery(document).ready(function ($) {
 
    /* Selected date Area Chart */
    typiohistoryareachart_render();
     
});

 