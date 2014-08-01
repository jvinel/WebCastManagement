<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Publishing Points<?php $this->end(); ?>
<?php $this->start('description'); ?>View<?php $this->end(); ?>
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
                <?php if ($publishingPoint['PublishingPoint']['configuration_status']==Configure::read('PUBLISHING_POINT_DRAFT')) { ?>
                    <span class="label label-info">Draft </span>
                <?php } else if ($publishingPoint['PublishingPoint']['configuration_status']==Configure::read('PUBLISHING_POINT_PUBLISHED')) { ?>
                          <span class="label label-warning">Published </span>
                <?php } else { ?>
                    <span class="label label-success">Applied </span>
                <?php } ?>
                <?php if ($publishingPoint['PublishingPoint']['live']==Configure::read('PUBLISHING_POINT_LIVE_ON')) { ?>
                    &nbsp;&nbsp;<span class="label label-danger">Live </span>
                <?php } ?>
            </p>
            
        </div>
        <div class="form-group">
            <label>Server</label>
            <p class="form-control-static"><?php echo h($publishingPoint['Server']['name']); ?></p>
        </div>
        <div class="form-group">
            <label>Location</label>
            <p class="form-control-static"><?php echo h($publishingPoint['Location']['name']); ?></p>
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
            <p class="form-control-static"><?php echo h($publishingPoint['Playlist']['name']); ?></p>
        </div>
    </div>
    <div class="col-lg-2">
        <?php if ($publishingPoint['PublishingPoint']['live']==Configure::read('PUBLISHING_POINT_LIVE_OFF')) { ?>
                <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $publishingPoint['PublishingPoint']['id']),array('escape' => false, 'class' => "list-group-item")); ?>
                <?php echo $this->Form->postLink(
                          '<i class="fa fa-eraser"></i> Delete',
                          array('action' => 'delete', $publishingPoint['PublishingPoint']['id']),
                          array('escape' => false, 'confirm' => 'Are you sure?', 'class' => "list-group-item"));
                      ?>
        <?php } ?>
        <?php if ($publishingPoint['PublishingPoint']['live']==Configure::read('PUBLISHING_POINT_DRAFT')) { ?>
                <?php echo $this->Form->postLink(
                          '<i class="fa fa-flash"></i> Publish',
                          array('action' => 'publish', $publishingPoint['PublishingPoint']['id'], 'view'),
                          array('escape' => false, 'confirm' => 'Are you sure?', 'class' => "list-group-item"));
                      ?>
         <?php } ?>
    </div>
    
