<?php $this->extend('/Events/default'); ?>
<?php $this->start('title'); ?>Events<?php $this->end(); ?>
<?php echo $this->Html->script('event'); ?>
<?php $this->start('description'); ?>View<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-flag"></i> Events', array('controller' => 'events', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-eye"></i> View</li>
<?php $this->end(); ?>
    <div class="col-lg-10">
        <div class="form-group">
            <label>Name</label>
            <p class="form-control-static">
                <?php echo h($event['Event']['name']); ?>
                <?php if ($event['Event']['live']==Configure::read('PUBLISHING_POINT_LIVE_ON')) { ?>
                    <span class="label label-danger">Live </span>
                <?php } ?>
            </p>
        </div>
        <div class="form-group">
            <label>Importance</label>
            <p class="form-control-static"><?php echo h($event['EventImportance']['name']); ?><input type="hidden" id="EventEventImportanceId" value="<?php echo h($event['EventImportance']['id']); ?>"/></p>
        </div>
        <div class="form-group">
            <label>Short Name</label>
            <p class="form-control-static"><?php echo h($event['Event']['short_name']); ?></p>
        </div>
        <div class="form-group">
            <label>Description</label>
            <p class="form-control-static"><?php echo h($event['Event']['description']); ?></p>
        </div>
        <div class="form-group">
            <label>Start Date</label>
            <p class="form-control-static"><?php echo $this->Time->format("Y/m/d H:i",$event['Event']['start_date']); ?></p>
        </div>
        <div class="form-group">
            <label>End Date</label>
            <p class="form-control-static"><?php echo $this->Time->format("Y/m/d H:i",$event['Event']['end_date']); ?></p>
        </div>
        <div class="form-group">
            <label>Video BitRate (kbps) <span class="label label-info" id="recommended_bitrate"></span></label>
            <p class="form-control-static"><?php echo h($event['Event']['video_bitrate']); ?><input type="hidden" id="EventVideoBitrate" value="<?php echo h($event['Event']['video_bitrate']); ?>"/></p>
        </div>
        <div class="form-group">
            <label>Playlist</label>
            <p class="form-control-static"><?php echo h($event['Playlist']['name']); ?></p>
        </div>
        <div class="form-group">
            <label>Audience Definition</label>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped tablesorter">
                  <thead>
                    <tr>
                      <th class="header">Location </th>
                      <th class="header">Audience </th>
                      <th class="header">Estimated audience </th>
                      <th class="header">Limits </th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $cpt=0;
                            foreach($locations as $key=>$value){ ?>
                        <tr>
                            <td><?php echo $value["locationname"]; ?></td>
                            <td>
                                <?php echo $value["locationaudience"]; ?>
                            </td>
                            <td<?php if ($value["terminal"]==1) { ?> id="eventloc_<?php echo $cpt; ?>_estimated_audience" <?php } ?>></td>
                            <td>
                                <?php if ($value["terminal"]==1) { ?>
                                    <span class="label label-success" id="eventloc_<?php echo $cpt; ?>_label"></span>
                                    <input name="eventloc.<?php echo $cpt; ?>.locationid" id="eventloc_<?php echo $cpt; ?>_locationid"  type="hidden" value="<?php echo $value["locationid"]; ?>"/>
                                    <input name="eventloc.<?php echo $cpt; ?>.id" type="hidden" value="<?php echo $value["locationeventid"]; ?>"/>
                                    <input name="eventloc.<?php echo $cpt; ?>.audience"  id="eventloc_<?php echo $cpt; ?>_audience" type="hidden" class="form-control" value="<?php echo $value["locationaudience"]; ?>" style="width: 200px;"  onchange="updateEstimatedAudience();">
                                <?php 
                                    $cpt++;
                                    } ?>
                            </td>
                        </tr>
                      <?php } ?>
                  </tbody>
                </table>
                <input type="hidden" name="eventloc.max" value="<?php echo $cpt-1; ?>"  id="eventloc_max"/>
              </div>
        </div>

    </div>
    <div class="col-lg-2">
        <?php if ($event['Event']['live']==Configure::read('PUBLISHING_POINT_LIVE_OFF')) { 
                echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $event['Event']['id']),array('escape' => false, 'class' => "list-group-item"));
                echo $this->Form->postLink(
                          '<i class="fa fa-eraser"></i> Delete',
                          array('action' => 'delete', $event['Event']['id']),
                          array('escape' => false, 'confirm' => 'Are you sure?', 'class' => "list-group-item"));
                     
              }
         ?>
                <?php if ($event['Event']['video_bitrate']>0 && $event['Event']["playlist_id"]!="") { ?>
                    <?php echo $this->Html->link('<i class="fa fa-download"></i> Publishing Points', array('controller' => 'publishingpoints', 'action' => 'listevent', $event['Event']['id']),array('escape' => false, 'class' => "list-group-item")); ?>
                <?php } ?>
                <?php if (sizeof($event['LiveSession'])>0) { ?>
                    <?php echo $this->Html->link('<i class="fa fa-bar-chart-o"></i> Monitoring', array('controller' => 'monitoring', 'action' => 'view', $event['Event']['id']),array('escape' => false, 'class' => "list-group-item")); ?>
                <?php } ?>
            
    </div>
    


<script type="text/javascript">
   $(function () {
                $('#datetimepicker1').datetimepicker();
                 $('#datetimepicker2').datetimepicker();
            });
            
   // Build Event importance array for participation ratio calculation
    var importanceArray=new Array();
    <?php foreach($eventImportancesArray as $importance) { ?>
        importanceArray["<?php echo $importance["EventImportance"]["id"]; ?>"]=<?php echo $importance["EventImportance"]["participation_ratio"]; ?>;
    <?php } ?>
        
   // Build location available bandwidth array
   var bandwidthArray=new Array();
   <?php foreach($locations as $key=>$value){ ?>
        <?php if ($value["terminal"]==1) { ?>
            bandwidthArray["<?php echo $value["locationid"]; ?>"]=<?php echo $value["bandwidth"]; ?>;
        <?php } ?>
    <?php } ?>
   // Constant for bitrate calculation (from settings)
   var bandwidth_threshold_ratio=<?php echo $bandwidth_threshold_ratio; ?>;
   var bandwidth_usage_ratio=<?php echo $bandwidth_usage_ratio; ?>;
   updateEstimatedAudience();
</script>