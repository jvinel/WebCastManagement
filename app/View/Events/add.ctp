<?php $this->extend('/Events/default'); ?>
<?php $this->start('title'); ?>Events<?php $this->end(); ?>
<?php $this->start('description'); ?>List<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-flag"></i> Events', array('controller' => 'events', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-pencil"></i> Add</li>
<?php $this->end(); ?>
<?php echo $this->Form->create('Event'); ?>
    <div class="col-lg-10">
        <div class="form-group">
            <?php echo $this->Form->input('name', array("class"=>"form-control")); ?>
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
                    <input type='text' class="form-control"  data-format="YYYY/MM/DD HH:mm" name="data[Event][start_date]"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('end_date'); ?><br/>
            <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control"  data-format="YYYY/MM/DD HH:mm" name="data[Event][end_date]"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
        </div>

    
    <div class="form-group">
            <label>Audience Definition</label>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped tablesorter">
                  <thead>
                    <tr>
                      <th class="header">Location </th>
                      <th class="header">Audience </th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $cpt=0;
                            foreach($locations as $key=>$value){ ?>
                        <tr>
                            <td><?php echo $value["locationname"]; ?></td>
                            <td>
                                 
                                <?php if ($value["terminal"]==1) { ?>
                                    <input name="eventloc.<?php echo $cpt; ?>.locationid" type="hidden" value="<?php echo $value["locationid"]; ?>"/>
                                    <input name="eventloc.<?php echo $cpt; ?>.id" type="hidden" value="<?php echo $value["locationeventid"]; ?>"/>
                                    <input name="eventloc.<?php echo $cpt; ?>.audience"  type="number" class="form-control" value="<?php echo $value["locationaudience"]; ?>" style="width: 200px;">
                                    
                                <?php 
                                    $cpt++;
                                    } ?>
                            </td>
                        </tr>
                      <?php } ?>
                  </tbody>
                </table>
                <input type="hidden" name="eventloc.max" value="<?php echo $cpt-1; ?>"/>
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
</script>