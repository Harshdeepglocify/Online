<div class="tab-pane active" id="overview">
    <div class="row"> 
        <div class="col-md-8"> 
            <div class="box-header-init"> 
                <h2 class="dashboard-h2">This Week ( <?php echo date('m/d/y', strtotime($start_date)); ?> - <?php echo date('m/d/y', strtotime($end_date)); ?> )</h2> 
            </div> 
        </div> 
    </div>
    
    <div class="row overview-graph-total">
        <div class="col-md-4"><div id="overview_graph_minhrs"></div></div>
        <div class="col-md-4" style="clear:both"> 
            <center><h3><strong><span class="row overview-graph-total-time"><?php echo getUserActivityLog_graph($student_id,'',$start_date,$end_date,'');?></span></strong></h3></center>
        </div> 
    </div>
    <div class="row">  
        <!-- Pie chart -->  
    <?php
    
    $is_data = getapplogforweek_all($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59');
    $clss_overview_a_history = empty($is_data)? ' history_hide ' : '';
    //if(!empty($is_data)){
            

        $chart = '';
        if( !$is_data ) {
            $chart = 'style="display: none;"';
        } else {
            $chart = '';
        }
        ?> 
        <div class="col-md-6 lessons_chart_print <?php  echo $clss_overview_a_history; ?>" > 
            <div class="chart chart-p-typio-init" <?php echo $chart; ?>> 
                <div id="piechartOverview_graph_data" style="height: 250px; width: 300px;" width="300" height="250"></div> 
            </div> 

            <table id="piechartOverview_table" style="display: none;" border="2"> 
                <tr><td></td><td></td></tr>
                <?php //echo getapplogforweek_all($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59'); ?> 

            </table> 
        </div> 
        <!-- End BAR Pie --> 
        <!-- table --> 
        <div class="col-md-6 lessons_complete_list_noprint overview-graph-tbl  <?php  echo $clss_overview_a_history; ?>"> 
            <?php $table_data = getapplogforweekTable_all($student_id, $start_date . ' 00:00:00', $end_date . ' 23:59:59'); ?> 
            <div class="box-header"> 
                <h3 class="box-title overall-event_total"><strong><?php echo!empty($table_data['total_row']) ? $table_data['total_row'] : 0; ?> Events </strong></h3>  
            </div>

            <div class="box-body table-responsive no-padding">  
                <table class="table table-hover table-bordered" > 
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Date</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php echo!empty($table_data['html']) ? $table_data['html'] : ''; ?> 
                    </tbody>
                </table> 
            </div> 
        </div>
    <?php
    if(!empty($clss_overview_a_history)){ ?>
        <div class="no-activity-msg-wrap">
            <div class="no-activity-msg">No activity this week</div>
        </div>
    <?php 
    } ?>

        <!-- end table -->  
    </div>

    <div class="row">     
        <div class="space-margin-bottom-50"></div>  
        <div class="wt-cus-dvider"></div> 
        <div class="space-margin-bottom-50"></div> 
    </div>

    <div class="row" id="HistoryDateSection"> 
        <div class="col-md-12"> 
            <div class="box-header-init"> 
                <h2 class="dashboard-h2">History</h2> 
            </div> 
        </div>

        <form method="post" class="custom-p-form-init"> 
            <div class="form-group col-md-2 col-xs-12"> 
                <label>From:</label> 
                <div class="input-group date"> 
                    <input id="HistoryDate1" name="HistoryDate1" value="<?php echo date('m/d/Y', strtotime($history_start_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d"> 
                    <input id="student_id" name="student_id" value="<?php echo isset($_GET['student']) ? $_GET['student'] : ''; ?>" type="hidden">
                    <input id="user_type" name="user_type" value="0" type="hidden"> 
                    <div class="input-group-addon"> 
                        <i class="fa fa-calendar" aria-hidden="true"></i>   
                    </div></div> 
                <!-- /.input group -->
            </div>

            <div class="form-group col-md-2 col-xs-12"> 
                <label>To:</label> 
                <div class="input-group date"> 
                    <input id="HistoryDate2" name="HistoryDate2" value="<?php echo date('m/d/Y', strtotime($history_end_date)); ?>" type="text" class="form-control pull-right datepicker" data-date-format="mm/dd/yyyy" data-date-end-date="0d"> 
                    <div class="input-group-addon">  
                        <i class="fa fa-calendar" aria-hidden="true"></i> 
                    </div> 
                </div> 
                <!-- /.input group --> 
            </div>

            <div class="form-group col-md-2 col-xs-12"> 
                <!-- btn -->  
                <div class="btn-group">  
                    <button type="button" name="OverviewDateSubmit" value="Show" id="DateFilterBtn" class="dashboard-settings-btn">Show</button>
                </div> 
                <!-- end btn --> 
            </div> 
        </form>

    </div>

    <div class="row overview-history-graph-total">
        <div class="col-md-4"> 
            <center><h3><strong><span class="row overview-history-graph-total-time"><?php echo getUserActivityLog_graph($student_id,'',$history_start_date,$history_end_date,'');?></span></strong></h3></center>
        </div> 
</div>


    <div class="row" id="HistorySection">  
        <!-- Pie chart --> 
        
        <?php $table_data = getapplogforweekTable($student_id, $history_start_date . ' 00:00:00', $history_end_date . ' 23:59:59'); 
            $clss_history = empty($table_data['total_row'])? ' history_hide ' : '';
        ?> 

        <div class="col-md-6 lessons_chart_print <?php echo $clss_history; ?>"> 
            <div class="chart chart-p-typio-init" > 
                <div id="piechartOverview2" style="height: 250px; width: 300px;" width="300" height="250"></div> 
            </div> 

            <table id="piechartOverview_table2" style="display: none;"  >
                <thead><tr><td></td><td></td></tr></thead>
                <tbody  id="pie_chart_table"> 

                    <?php echo getapplogforweek($student_id, $history_start_date . ' 00:00:00', $history_end_date . ' 23:59:59'); ?> 
                </tbody> 
            </table> 
        </div> 
        <!-- End BAR Pie --> 
        <!-- table --> 
        <div class="col-md-6 lessons_complete_list_noprint <?php echo $clss_history; ?>"> 
            
            <div class="box-header"> 
                <h3 class="box-title"><strong><span id="TotalRow"><?php echo!empty($table_data['total_row']) ? $table_data['total_row'] : 0; ?></span> Events </strong></h3>  
            </div>

            <div class="box-body table-responsive no-padding overview-history-graph-tbl">  
                <table class="table table-hover table-bordered"  > 
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Date</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody id="History_table"> 
                        <?php echo!empty($table_data['html']) ? $table_data['html'] : ''; ?> 
                    </tbody>
                </table> 
            </div> 
        </div>

        <?php 
        if( !empty( $clss_history ) ){
            echo '<div class="no-history-msg-wrap">
                    <div class="no-history-msg">No history to display</div>
                </div>';
        } ?>
        <!-- end table -->  
    </div>
</div> 
<script>
    $('#overviewTab').click(function() { 
        if ($('#overview').attr('data-load') !== undefined) { alert('nmnmn');

            var userid = '<?php echo $student_id; ?>';
            var startdate = '<?php echo $start_date . ' 00:00:00'; ?>'; 
            var enddate = '<?php echo $end_date . ' 23:59:59'; ?>';

            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action': 'ajax_OverviewChartTable', userid: userid, startdate: startdate, enddate: enddate},
                dataType: 'json',
                success: function (result) {
                    $('#piechartOverview_table').html(result);
                }
            });

            $.ajax({
                type: 'POST',
                url: ADMIN_URL + 'config/config-student.php',
                data: {'action': 'ajax_OverviewTimeSpent', userid: userid, startdate: startdate, enddate: enddate},
                dataType: 'json',
                success: function (result) {   
                    $('#overview_graph_minhrs').html(result);
                }
            });
            
            $('#overview').removeAttr('data-load');
        }
    });
</script>