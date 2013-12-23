<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Publishing Points<?php $this->end(); ?>
<?php $this->start('description'); ?>List<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-video-camera"></i> Publishing Points</li>
<?php $this->end(); ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped tablesorter">
          <thead>
            <tr>
              <th class="header">Name <i class="fa fa-sort"></i></th>
              <th class="header">Server <i class="fa fa-sort"></i></th>
              <th class="header">Event <i class="fa fa-sort"></i></th>
              <th class="header">Actions <i class="fa fa-sort"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($publishingpoints as $pp): ?>
              <tr>
                  <td>
                      <?php echo $this->Html->link($pp['PublishingPoint']['name'], array('controller' => 'publishingpoints', 'action' => 'view', $pp['PublishingPoint']['id'])); ?>
                      <span class="label label-default"><?php echo $pp['PublishingPoint']['limit_player_summary']; ?></span>
                      <span class="label label-default"><?php echo $pp['PublishingPoint']['limit_bandwidth_summary']; ?></span>
                  </td>
                  <td><?php echo $this->Html->link($pp['Server']['name'], array('controller' => 'servers', 'action' => 'view', $pp['Server']['id'])); ?></td>
                  <td><?php echo $this->Html->link($pp['Event']['name'], array('controller' => 'events', 'action' => 'view', $pp['Event']['id'])); ?></td>
                  <td>
                      <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $pp['PublishingPoint']['id']),array('escape' => false)); ?>
                      &nbsp;&nbsp;<?php echo $this->Form->postLink(
                          '<i class="fa fa-eraser"></i> Delete',
                          array('action' => 'delete', $pp['PublishingPoint']['id']),
                          array('escape' => false, 'confirm' => 'Are you sure?'));
                      ?>
                  </td>
              </tr>
              <?php endforeach; ?>
              <?php unset($pp); ?>
          </tbody>
        </table>
      </div>
    <?php  echo $this->Html->link( '<i class="fa fa-plus-circle"></i> Add Publishing Point', array('controller' => 'publishingpoints', 'action' => 'add'),array('escape' => false)); ?>
