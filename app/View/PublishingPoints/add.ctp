<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Publishing Points<?php $this->end(); ?>
<?php $this->start('description'); ?>Add<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php  echo $this->Html->link( '<i class="fa fa-video-camera"></i> Publishing Points', array('controller' => 'publishingpoints', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-pencil"></i> Add Publishing point</li>
<?php $this->end(); ?>
<?php
echo $this->Form->create('PublishingPoint');
?>
    <div class="col-lg-10">
        <div class="form-group">
            <?php echo $this->Form->input('name', array("class"=>"form-control")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('server_id'); ?><br/>
            <?php echo $this->Form->input('server_id', array('label'=>false, 'empty' => true, "class" => "selectpicker")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('location_id'); ?><br/>
            <?php echo $this->Form->input('location_id', array('label'=>false, 'empty' => true, "class" => "selectpicker")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('limit_connected_player', array("class"=>"form-control")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('limit_player_bandwidth', array("class"=>"form-control")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('event_id'); ?><br/>
            <?php echo $this->Form->input('event_id', array('label'=>false, 'empty' => true, "class" => "selectpicker")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('playlist_id'); ?><br/>
            <?php echo $this->Form->input('playlist_id', array('label'=>false, 'empty' => true, "class" => "selectpicker")); ?>
        </div>
    </div>
    <div class="col-lg-2">
         <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i>  Save', array('type' => 'submit', 'escape' => false,"class"=>"btn btn-default")); ?>
    </div>
    
<?php echo $this->Form->end(); ?>

    <script>
        $('select').selectpicker();
    </script>