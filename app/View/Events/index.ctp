<?php $this->extend('/Events/default'); ?>
<?php $this->start('title'); ?>Events<?php $this->end(); ?>
<?php $this->start('description'); ?>List<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li  class="active"><i class="fa fa-flag"></i> Events</li>
<?php $this->end(); ?>
  
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped tablesorter" id="pp_table">
          <thead>
            <tr>
              <th class="header">Name</th>
              <th class="header">Short Name</th>
              <th class="header">Start</th>
              <th class="header">End</th>
              <th class="header">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($events as $event): ?>
              <tr>
                  <td>
                      <?php echo $this->Html->link($event['Event']['name'], array('controller' => 'events', 'action' => 'view', $event['Event']['id'])); ?>
                      <?php if ($event['Event']['live']==Configure::read('PUBLISHING_POINT_LIVE_ON')) { ?>
                            <span class="label label-danger">Live </span>
                      <?php } ?>
                  </td>
                  <td><?php echo h($event['Event']['short_name']); ?></td>
                  <td><?php echo $this->Time->format("Y/m/d H:i",$event['Event']['start_date']);?></td>
                  <td><?php echo $this->Time->format("Y/m/d H:i",$event['Event']['end_date']); ?></td>
                  <td>
                      <?php if ($event['Event']['live']==Configure::read('PUBLISHING_POINT_LIVE_OFF')) { ?>
                        <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $event['Event']['id']),array('escape' => false)); ?>
                        &nbsp;&nbsp;<?php echo $this->Form->postLink(
                            '<i class="fa fa-eraser"></i> Delete',
                            array('action' => 'delete', $event['Event']['id']),
                            array('escape' => false, 'confirm' => 'Are you sure?'));
                        ?>
                      <?php } ?>
                  </td>
              </tr>
              <?php endforeach; ?>
              <?php unset($event); ?>
          </tbody>
        </table>
      </div>
    <?php  echo $this->Html->link( '<i class="fa fa-plus-circle"></i> New Event', array('controller' => 'events', 'action' => 'add'),array('escape' => false)); ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
                //$('#pp_table').dataTable();
                $('#pp_table').dataTable({"sPaginationType": "bs_normal"}); 
        } );
    </script>
   
    