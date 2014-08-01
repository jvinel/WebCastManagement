<?php $this->extend('/Events/default'); ?>
<?php $this->start('title'); ?>Events<?php $this->end(); ?>
<?php echo $this->Html->script('event'); ?>
<?php $this->start('description'); ?>Edit<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-flag"></i> Events', array('controller' => 'events', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-pencil"></i> Edit</li>
<?php $this->end(); ?>
<?php echo $this->Form->create('Event'); ?>
    <div class="col-lg-10">
        <div class="form-group">
            <?php echo $this->Form->input('name', array("class"=>"form-control")); ?>
        </div>
        
        <div class="form-group">
            <?php echo $this->Form->label('event_importance_id'); ?><br/>
            <?php echo $this->Form->input('event_importance_id', array('label'=>false, 'empty' => false, "class" => "selectpicker","onchange" => "updateEstimatedAudience();")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('short_name', array("class"=>"form-control")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('description', array("class"=>"form-control")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('start_date'); ?><br/>
            <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control"  data-format="YYYY/MM/DD HH:mm" name="data[Event][start_date]" value="<?php echo $this->Time->format("Y/m/d H:i",$event['Event']['start_date']); ?>"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('end_date'); ?><br/>
            <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control"  data-format="YYYY/MM/DD HH:mm" name="data[Event][end_date]" value="<?php echo $this->Time->format("Y/m/d H:i",$event['Event']['end_date']); ?>"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
        </div>
        <div class="form-group">
            <label>Video BitRate (kbps) <span class="label label-info" id="recommended_bitrate"></span></label>
            <?php echo $this->Form->input('video_bitrate', array("label" => false, "class"=>"form-control", "onchange" => "updateBitrate();")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('playlist_id'); ?><br/>
            <?php echo $this->Form->input('playlist_id', array('label'=>false, 'empty' => true, "class" => "selectpicker")); ?>
        </div>
        <div class="form-group">
            <label>Audience Definition <span class="label label-success" id="audience_status"></span></label>
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
                                 
                                <?php if ($value["terminal"]==1) { ?>
                                    <input name="eventloc.<?php echo $cpt; ?>.locationid" id="eventloc_<?php echo $cpt; ?>_locationid" type="hidden" value="<?php echo $value["locationid"]; ?>"/>
                                    <input name="eventloc.<?php echo $cpt; ?>.id" type="hidden" value="<?php echo $value["locationeventid"]; ?>"/>
                                    <input name="eventloc.<?php echo $cpt; ?>.audience" id="eventloc_<?php echo $cpt; ?>_audience"  type="number" class="form-control" value="<?php echo $value["locationaudience"]; ?>" style="width: 200px;" onchange="updateEstimatedAudience();">
                                <?php 
                                    } ?>
                            </td>
                            <td<?php if ($value["terminal"]==1) { ?> id="eventloc_<?php echo $cpt; ?>_estimated_audience" <?php } ?>></td>
                            <td>
                                <?php if ($value["terminal"]==1) { ?>
                                    <span class="label label-success" id="eventloc_<?php echo $cpt; ?>_label"></span>
                                <?php 
                                    $cpt++;
                                    } ?>
                            </td>
                        </tr>
                      <?php } ?>
                        
                  </tbody>
                </table>
                <input type="hidden" name="eventloc.max" value="<?php echo $cpt-1; ?>" id="eventloc_max"/>
              </div>
        </div>
    </div>
    <div class="col-lg-2">
         <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i>  Save', array('type' => 'submit', 'escape' => false,"class"=>"btn btn-default")); ?>
    </div>
    
<?php echo $this->Form->end(); ?>

<script type="text/javascript">
   $(function () {
                $('#datetimepicker1').datetimepicker();
                 $('#datetimepicker2').datetimepicker();
            });
    $('select').selectpicker();
    
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