<?php $this->extend('/Events/default'); ?>
<?php $this->start('title'); ?>Events<?php $this->end(); ?>
<?php $this->start('description'); ?>Edit<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-flag"></i> Events', array('controller' => 'events', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-pencil"></i> Edit</li>
<?php $this->end(); ?>
    <div class="col-lg-10">
        <div class="form-group">
            <label>Name</label>
            <p class="form-control-static"><?php echo h($event['Event']['name']); ?></p>
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
            <p class="form-control-static"><?php echo $this->Time->format("Y/m/d H:m",$event['Event']['start_date']); ?></p>
        </div>
        <div class="form-group">
            <label>End Date</label>
            <p class="form-control-static"><?php echo $this->Time->format("Y/m/d H:m",$event['Event']['end_date']); ?></p>
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
                      <?php foreach($locations as $key=>$value){ ?>
                        <tr>
                            <td><?php echo $value["locationname"]; ?></td>
                            <td>
                                <?php echo $value["locationaudience"]; ?>
                            </td>
                        </tr>
                      <?php } ?>
                  </tbody>
                </table>
              </div>
        </div>

    </div>
    <div class="col-lg-2">
        
                <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $event['Event']['id']),array('escape' => false, 'class' => "list-group-item")); ?>
                <?php echo $this->Form->postLink(
                          '<i class="fa fa-eraser"></i> Delete',
                          array('action' => 'delete', $event['Event']['id']),
                          array('escape' => false, 'confirm' => 'Are you sure?', 'class' => "list-group-item"));
                      ?>
                <?php echo $this->Html->link('<i class="fa fa-download"></i> Publishing Points', array('action' => 'edit', $event['Event']['id']),array('escape' => false, 'class' => "list-group-item")); ?>
            
    </div>
    


<script type="text/javascript">
   $(function () {
                $('#datetimepicker1').datetimepicker();
                 $('#datetimepicker2').datetimepicker();
            });
</script>