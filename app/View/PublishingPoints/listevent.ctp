<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Publishing Points<?php $this->end(); ?>
<?php $this->start('description'); ?>Edit<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php  echo $this->Html->link( '<i class="fa fa-video-camera"></i> Publishing Points', array('controller' => 'publishingpoints', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-eye"></i> View</li>
<?php $this->end(); ?>
    <div class="col-lg-10">
        <div class="form-group">
            <label>Name</label>
            <p class="form-control-static">
                <?php echo h($publishingPoint['PublishingPoint']['name']); ?>
                <?php if ($publishingPoint['PublishingPoint']['configuration_status']==0) { ?>
                    <span class="label label-default">Draft </span>
                <?php } else { ?>
                <?php } ?>
            </p>
            
        </div>
        <div class="form-group">
            <label>Server</label>
            <p class="form-control-static"><?php echo h($publishingPoint['Server']['name']); ?></p>
        </div>
        <div class="form-group">
            <label>Limit connected player</label>
            <p class="form-control-static"><?php echo h($publishingPoint['PublishingPoint']['limit_connected_player']); ?></p>
        </div>
        <div class="form-group">
            <label>Limit player bandwidth</label>
            <p class="form-control-static"><?php echo h($publishingPoint['PublishingPoint']['limit_player_bandwidth']); ?></p>
        </div>
        <div class="form-group">
            <label>Event</label>
            <p class="form-control-static"><?php echo h($publishingPoint['Event']['name']); ?></p>
        </div>
        <div class="form-group">
            <label>Playlist</label>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped tablesorter">
                  <thead>
                    <tr>
                      <th class="header">Name <i class="fa fa-sort"></i></th>
                      <th class="header">Timeout <i class="fa fa-sort"></i></th>
                      <th class="header">Actions <i class="fa fa-sort"></i></th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php foreach ($sources as $source): ?>
                    <tr>
                        <td><?php echo $source['url']; ?></td>
                        <td><?php echo $source['timeout']; ?></td>
                        <td>
                          <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('controller'=>'publishingpointsources', 'action' => 'editPublishingPoint', $publishingPoint['PublishingPoint']['id'], $source['id']),array('escape' => false)); ?>
                          &nbsp;&nbsp;<?php echo $this->Form->postLink(
                              '<i class="fa fa-eraser"></i> Delete',
                              array('controller'=>'publishingpointsources', 'action' => 'deletePublishingPoint', $publishingPoint['PublishingPoint']['id'], $source['id']),
                              array('escape' => false, 'confirm' => 'Are you sure?'));
                          ?>
                          &nbsp;&nbsp;<?php echo $this->Form->postLink('<i class="fa fa-arrow-up"></i> Up', array('controller'=>'publishingpointsources','action' => 'up', $publishingPoint['PublishingPoint']['id'], $source['id']),array('escape' => false)); ?>
                          &nbsp;&nbsp;<?php echo $this->Form->postLink('<i class="fa fa-arrow-down"></i> Down', array('controller'=>'publishingpointsources','action' => 'down', $publishingPoint['PublishingPoint']['id'], $source['id']),array('escape' => false)); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php unset($source); ?>
                    </tbody>
                </table>
                <?php  echo $this->Html->link( '<i class="fa fa-plus-circle"></i> Add Source', array('controller' => 'publishingpointsources', 'action' => 'addPublishingPoint',$publishingPoint['PublishingPoint']['id']),array('escape' => false)); ?>
              </div>
        </div>
    </div>
    <div class="col-lg-2">
                <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $publishingPoint['PublishingPoint']['id']),array('escape' => false, 'class' => "list-group-item")); ?>
                <?php echo $this->Form->postLink(
                          '<i class="fa fa-eraser"></i> Delete',
                          array('action' => 'delete', $publishingPoint['PublishingPoint']['id']),
                          array('escape' => false, 'confirm' => 'Are you sure?', 'class' => "list-group-item"));
                      ?>
    </div>
    
