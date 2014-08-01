<?php $this->extend('/Dashboard/default'); ?>
<?php echo $this->Html->script('highcharts'); ?>
<?php $this->start('title'); ?>Dashboard<?php $this->end(); ?>
<?php $this->start('description'); ?>Statistics Overview<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li  class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
<?php $this->end(); ?>
  
    <div class="alert alert-success alert-dismissable">
       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       Welcome to WebCast Management console.
   </div>
    
    <div class="col-lg-3">
        <div class="panel panel-success">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6">
                <i class="fa fa-users fa-5x"></i>
              </div>
              <div class="col-xs-6 text-right">
                <p class="announcement-heading">
                    <?php  if ( $sm_concurrent_player[0][0]["sm"])  {
                                echo $sm_concurrent_player[0][0]["sm"]; 
                    } else {
                        echo "0";
                    } ?>
                </p>
                <p class="announcement-text">Connected Players</p>
              </div>
            </div>
          </div>
          <a href="<?php echo $this->Html->url(array('controller' => 'monitoring', 'action' => 'index')); ?>">
            <div class="panel-footer announcement-bottom">
              <div class="row">
                <div class="col-xs-6">
                  View Monitoring
                </div>
                <div class="col-xs-6 text-right">
                  <i class="fa fa-arrow-circle-right"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
    <div class="col-lg-3">
        <?php if ($nb_incoming_event[0][0]["cnt"]==0) { ?> 
            <div class="panel panel-success">
        <?php } else { ?>
            <div class="panel panel-danger">
        <?php } ?>
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6">
              <i class="fa fa-flag fa-5x"></i>
            </div>
            <div class="col-xs-6 text-right">
              <p class="announcement-heading"><?php echo $nb_incoming_event[0][0]["cnt"]; ?></p>
              <p class="announcement-text">Live events</p>
            </div>
          </div>
        </div>
        <a href="<?php echo $this->Html->url(array('controller' => 'events', 'action' => 'index')); ?>">
            <div class="panel-footer announcement-bottom">
              <div class="row">
                <div class="col-xs-6">
                  View Events
                </div>
                <div class="col-xs-6 text-right">
                  <i class="fa fa-arrow-circle-right"></i>
                </div>
              </div>
            </div>
          </a>
      </div>
    </div>  
   
    <div class="col-lg-3">
        <?php if ($nb_server[0][0]["cnt"]==$nb_connected_server[0][0]["cnt"]) { ?> 
            <div class="panel panel-success">
        <?php } else { ?>
            <div class="panel panel-danger">
        <?php } ?>
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-6">
            <i class="fa fa-building-o fa-5x"></i>
          </div>
          <div class="col-xs-6 text-right">
            <p class="announcement-heading"><?php echo $nb_connected_server[0][0]["cnt"]; ?>/<?php echo $nb_server[0][0]["cnt"]; ?></p>
            <p class="announcement-text">Connected servers</p>
          </div>
        </div>
      </div>
      <a href="<?php echo $this->Html->url(array('controller' => 'servers', 'action' => 'index')); ?>">
        <div class="panel-footer announcement-bottom">
          <div class="row">
            <div class="col-xs-6">
              View Servers
            </div>
            <div class="col-xs-6 text-right">
              <i class="fa fa-arrow-circle-right"></i>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>    
  <div class="col-lg-3">
    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-6">
            <i class="fa fa-video-camera fa-5x"></i>
          </div>
          <div class="col-xs-6 text-right">
            <p class="announcement-heading"><?php echo $nb_publishing_point[0][0]["cnt"]; ?></p>
            <p class="announcement-text">Publishing Points</p>
          </div>
        </div>
      </div>
      <a href="<?php echo $this->Html->url(array('controller' => 'publishingpoints', 'action' => 'index')); ?>">
        <div class="panel-footer announcement-bottom">
          <div class="row">
            <div class="col-xs-6">
              View Publishing Points
            </div>
            <div class="col-xs-6 text-right">
              <i class="fa fa-arrow-circle-right"></i>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>
<?php if  ($sm_concurrent_player[0][0]["sm"]>0) { ?>
    <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Current connected players</h3>
            </div>
            <div class="panel-body">
              <div id="piechart"></div>
              <div class="clear"></div>

            </div>
          </div>
        </div>
      </div>
    <script type="text/javascript">
            function updateGlobalData() {

            }
            $(function () {

            var colors = Highcharts.getOptions().colors,
                categories = [
                    <?php 
                        $total_cnt=0;
                        foreach ($chartdata as $data) { 
                            $total_cnt+=$data["user_cnt"];
                            ?>
                         '<?php echo $data["name"]; ?>',
                    <?php } ?>
                    ],
                name = 'Global Location',
                data = [
                    <?php 
                        $cpt=0;
                        foreach ($chartdata as $data) { ?>
                        {
                            y: <?php echo round(($data["user_cnt"]/$total_cnt)*100,2); ?>,
                            color: colors[<?php echo $cpt++; ?>],
                            <?php if (array_key_exists("children", $data)) { ?>
                                drilldown: {
                                    name: '<?php echo $data["name"]; ?> locations',
                                    categories: [
                                        <?php foreach ($data["children"] as $location) {?>
                                            '<?php echo $location["name"]; ?>', 
                                        <?php } ?>
                                    ],
                                    data: [
                                        <?php foreach ($data["children"] as $location) {?>
                                            <?php echo round(($location["user_cnt"]/$total_cnt)*100,2); ?>, 
                                        <?php } ?>
                                    ],
                                    color: colors[0]
                                }
                            <?php } else { ?>
                                drilldown: {
                                    name: '<?php echo $data["name"]; ?> locations',
                                    categories: [
                                            '<?php echo $data["name"]; ?>', 
                                    ],
                                    data: [
                                            <?php echo round(($data["user_cnt"]/$total_cnt)*100,2); ?>, 
                                    ],
                                    color: colors[0]
                                }
                            <?php } ?>
                        }, 
                    <?php } ?>

                    ];


            // Build the data arrays
            var browserData = [];
            var versionsData = [];
            for (var i = 0; i < data.length; i++) {

                // add browser data
                browserData.push({
                    name: categories[i],
                    y: data[i].y,
                    color: data[i].color
                });

                // add version data
                for (var j = 0; j < data[i].drilldown.data.length; j++) {
                    var brightness = 0.2 - (j / data[i].drilldown.data.length) / 5 ;
                    versionsData.push({
                        name: data[i].drilldown.categories[j],
                        y: data[i].drilldown.data[j],
                        color: Highcharts.Color(data[i].color).brighten(brightness).get()
                    });
                }
            }

            // Create the chart
            $('#piechart').highcharts({
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Current User Location Repartition'
                },
                yAxis: {
                    title: {
                        text: 'Total percent'
                    }
                },
                plotOptions: {
                    pie: {
                        shadow: false,
                        center: ['50%', '50%']
                    }
                },
                tooltip: {
                        valueSuffix: '%'
                },
                series: [{
                    name: 'NatCo',
                    data: browserData,
                    size: '60%',
                    dataLabels: {
                        formatter: function() {
                            return this.y > 5 ? this.point.name : null;
                        },
                        color: 'white',
                        distance: -30
                    }
                }, {
                    name: 'Sites',
                    data: versionsData,
                    size: '80%',
                    innerSize: '60%',
                    dataLabels: {
                        formatter: function() {
                            // display only if larger than 1
                            return this.y > 1 ? '<b>'+ this.point.name +':</b> '+ this.y +'%'  : null;
                        }
                    }
                }]
            });
        });
    </script>
<?php } ?>