<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Publishing Points<?php $this->end(); ?>
<?php $this->start('description'); ?>List<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-video-camera"></i> Publishing Points</li>
<?php $this->end(); ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped tablesorter" id="pp_table">
          <thead>
            <tr>
              <th class="header">Name</th>
              <th class="header">Status</th>
              <th class="header">Limits</th>
              <th class="header">Server</th>
              <th class="header">Event</th>
              <th class="header">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($publishingpoints as $pp): ?>
              <tr>
                  <td>
                      <?php echo $this->Html->link($pp['PublishingPoint']['name'], array('controller' => 'publishingpoints', 'action' => 'view', $pp['PublishingPoint']['id'])); ?>
                  </td>
                  <td>
                      <?php if ($pp['PublishingPoint']['configuration_status']==Configure::read('PUBLISHING_POINT_DRAFT')) { ?>
                          <span class="label label-info">Draft </span>
                      <?php } else if ($pp['PublishingPoint']['configuration_status']==Configure::read('PUBLISHING_POINT_PUBLISHED')) { ?>
                          <span class="label label-warning">Published </span>
                      <?php } else { ?>
                          <span class="label label-success">Applied </span>
                      <?php } ?>
                      <?php if ($pp['PublishingPoint']['live']==Configure::read('PUBLISHING_POINT_LIVE_ON')) { ?>
                            &nbsp;&nbsp;<span class="label label-danger">Live </span>
                      <?php } ?> 
                      <?php if ($pp['PublishingPoint']['status']==Configure::read('PUBLISHING_POINT_UNKNOWN')) { ?>
                          &nbsp;&nbsp;<span class="label label-danger"><i class="fa fa-question"></i></span>&nbsp;&nbsp;
                      <?php } else if ($pp['PublishingPoint']['status']==Configure::read('PUBLISHING_POINT_STOPPED')) { ?>
                          &nbsp;&nbsp;<span class="label label-warning"><i class="fa fa-stop"></i></span>&nbsp;&nbsp;
                      <?php } else if ($pp['PublishingPoint']['status']==Configure::read('PUBLISHING_POINT_PLAYING')) { ?>
                          &nbsp;&nbsp;<span class="label label-success"><i class="fa fa-play"></i></span>&nbsp;&nbsp;
                      <?php } else if ($pp['PublishingPoint']['status']==Configure::read('PUBLISHING_POINT_ENDED')) { ?>
                          &nbsp;&nbsp;<span class="label label-danger"><i class="fa fa-eject"></i></span>&nbsp;&nbsp;
                      <?php } ?>
                  </td>
                  <td>
                      <span class="label label-default"><?php echo $pp['PublishingPoint']['limit_player_summary']; ?></span>
                      <span class="label label-default"><?php echo $pp['PublishingPoint']['limit_bandwidth_summary']; ?></span>
                  </td>
                  <td>
                      <!--<?php echo $this->Html->link($pp['Server']['name'], array('controller' => 'servers', 'action' => 'view', $pp['Server']['id'])); ?>-->
                      <?php echo $pp['Server']['name']; ?>
                  </td>
                  <td>
                      <!--<?php echo $this->Html->link($pp['Event']['name'], array('controller' => 'events', 'action' => 'view', $pp['Event']['id'])); ?>-->
                      <?php echo $pp['Event']['name']; ?>
                  </td>
                  <td>
                      <?php if ($pp['PublishingPoint']['live']==Configure::read('PUBLISHING_POINT_LIVE_OFF')) { ?>
                            <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $pp['PublishingPoint']['id']),array('escape' => false)); ?>
                            &nbsp;&nbsp;<?php echo $this->Form->postLink(
                                '<i class="fa fa-eraser"></i> Delete',
                                array('action' => 'delete', $pp['PublishingPoint']['id']),
                                array('escape' => false, 'confirm' => 'Are you sure?'));
                            ?>
                      <?php } ?>
                      <?php if ($pp['PublishingPoint']["configuration_status"]==Configure::read('PUBLISHING_POINT_DRAFT')) { ?>
                        &nbsp;&nbsp;<?php echo $this->Form->postLink(
                            '<i class="fa fa-flash"></i> Publish',
                            array('action' => 'publish', $pp['PublishingPoint']['id'], 'index'),
                            array('escape' => false, 'confirm' => 'Are you sure?')); ?>
                      <?php } ?>
                  </td>
              </tr>
              <?php endforeach; ?>
              <?php unset($pp); ?>
          </tbody>
        </table>
      </div>
    <?php  echo $this->Html->link( '<i class="fa fa-plus-circle"></i> Add Publishing Point', array('controller' => 'publishingpoints', 'action' => 'add'),array('escape' => false)); ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
                //$('#pp_table').dataTable();
                $('#pp_table').dataTable({"sPaginationType": "bs_normal"}); 
        } );
    </script>