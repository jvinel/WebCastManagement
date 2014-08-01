<?php $this->extend('/Events/default'); ?>
<?php 
    echo $this->Html->script('highstock'); 
?>
<?php $this->start('title'); ?>Monitoring<?php $this->end(); ?>
<?php $this->start('description'); ?><?php echo $event["Event"]["name"]; ?><?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-flag"></i> Events', array('controller' => 'events', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php  echo $this->Html->link( '<i class="fa fa-flag"></i> ' . $event["Event"]["short_name"], array('controller' => 'events', 'action' => 'view', $event["Event"]["id"]),array('escape' => false)); ?></li>
    <li  class="active"><i class="fa fa-bar-chart-o"></i> Monitoring</li>
<?php $this->end(); ?>
  
      
    <div class="col-lg-10">
        Session: 
        <select class="selectpicker" data-width="auto" id="selectsession">
            <?php 
                $cpt=0;
                foreach ($event["LiveSession"] as $livesession) {
                    $selected="";
                    if ($cpt==(sizeof($event["LiveSession"])-1)) {
                        $selected="selected='true'";
                    }
                    if ($livesession["active"]==1) { ?>
                    <option <?php echo $selected ?> value="<?php echo $livesession["id"]; ?>" data-content="<?php echo $this->Time->format("Y/m/d H:m",$livesession["start_date"]); ?> <span class='label label-danger'>Live</span>"><?php echo $this->Time->format("Y/m/d H:m",$livesession["start_date"]); ?> - Live</option>
                <?php } else { 
                    if ($this->Time->format("Y/m/d",$livesession["start_date"])==$this->Time->format("Y/m/d",$livesession["end_date"])) { ?>
                        <option <?php echo $selected ?>  value="<?php echo $livesession["id"]; ?>"><?php echo $this->Time->format("Y/m/d H:i",$livesession["start_date"]) . " - " . $this->Time->format("H:i",$livesession["end_date"]); ?></option>
                    <?php } else { ?>
                        <option <?php echo $selected ?>  value="<?php echo $livesession["id"]; ?>"><?php echo $this->Time->format("Y/m/d H:i",$livesession["start_date"]) . " - " . $this->Time->format("Y/m/d H:i",$livesession["end_date"]); ?></option>
                    <?php } 
                    } 
                    $cpt++;
                } ?>
        </select>
    </div>
    <div class="col-lg-2">
        <a id="deletesession" class="list-group-item" onclick="deleteSelectedSession(); return false;"><i class="fa fa-eraser"></i> Delete</a>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> Connected players</h3>
          </div>
          <div class="panel-body">
            <div id="connectedplayers"></div>
            <div class="clear"></div>
          </div>
        </div>
      </div>
    
    <div class="col-lg-4">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> Global player repartition</h3>
          </div>
          <div class="panel-body">
            <div id="playerRepartitionGlobal"></div>
            <div class="clear"></div>
          </div>
        </div>
      </div>
    </div>
    <?php 
        $active_found=false;
        foreach ($event["LiveSession"] as $livesession) {
            if ($livesession["active"]==1) { 
                $active_found=true; ?>
                <input type="hidden" id="activeSession" value="<?php echo $livesession["id"]; ?>" />
            <?php }
        } 
        if (!$active_found) { ?>
               <input type="hidden" id="activeSession" value="-1" /> 
    <?php } ?>
</div>

<script type="text/javascript">
   
    
    
    $('select').selectpicker();
    function deleteSelectedSession() {
        $("#selectsession option:selected").remove();
        $('select').selectpicker('refresh');
        updateDeleteButton();
    }
   
   function updateDeleteButton() {
       if ($("#selectsession").val()==$("#activeSession").val()) {
           $("#deletesession").hide();
       } else {
           $("#deletesession").show();
       }
   }
   
   var lastRetrievedStamp=0;
   
   
   function createGlobalConnectedChart() {
       if ($('#connectedplayers').highcharts()) {
            $('#connectedplayers').highcharts().destroy();
        }
        $.getJSON('/wcm/monitoring/globalconnectedplayer/'+ $("#selectsession").val() +'.json',	function(data) {
                console.log("Global Connected init data retrieved");
                var globalSeries=[{
                                    name: "Players",
                                    data: data
                            }];
                lastRetrievedStamp=data[data.length-1][0];
                initGlobalConnectedChart(globalSeries);
            }).fail(function() {
                console.log("Failed to retrieve global connected data");
            });
   }
   function initGlobalConnectedChart(chartSeries) {
       $('#connectedplayers').highcharts('StockChart', {
		chart : {
                        type: 'line',
			events : {
				load : function() {
                                        if ($("#selectsession").val()==$("#activeSession").val()) {
                                            // set up the updating of the chart each second
                                            var series = this.series;
                                            setInterval(function() {
                                                $.getJSON('/wcm/monitoring/globalconnectedplayer/'+ $("#selectsession").val() + '/' + lastRetrievedStamp + '.json',	function(data) {
                                                    console.log("Global Connected new data retrieved");
                                                    if (data.length>0) {
                                                        lastRetrievedStamp=data[data.length-1][0];
                                                        $.each(data, function (index, value) {
                                                           series[0].addPoint(value,true,true) 
                                                        });
                                                    }
                                                });

                                            }, 15000);
                                        }
				}
			}
		},
		rangeSelector: {
			buttons: [{
				count: 10,
				type: 'minute',
				text: '5M'
			}, {
				type: 'all',
				text: 'All'
			}],
			inputEnabled: false,
			selected: 0
		},
		
		title : {
			text : ''
		},
		
		exporting: {
			enabled: false
		},
		plotOptions: {
                    column: {
                        stacking: 'normal'
                    },
                    showInLegend: true
                },
		series : chartSeries
	});
   }
   
   function initRepartitionChart(){
       $('#playerRepartitionGlobal').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false
        },
        title: {
            text: ''
            
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                dataLabels: {
                    enabled: false
                    
                },
                
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            innerSize: '50%',
            data: [
                ['France',   45.0],
                ['Germany',       26.8],
                ['Spain', 12.8],
                ['United Kingdom',    8.5],
                ['North America',     6.2],
                ['Middle East',   0],
                ['India',   0],
                ['China',   0],
                ['Others',   0]
            ]
        }]
    });
   }
   $(document).ready(function() {
       updateDeleteButton();
       $('#selectsession').change(function() {
           updateDeleteButton();
           createGlobalConnectedChart();
       });
       
       Highcharts.setOptions({
		global : {
			useUTC : false
		}
	});
	
        
        
	// Create the chart
	createGlobalConnectedChart();
        initRepartitionChart();
   })
</script>