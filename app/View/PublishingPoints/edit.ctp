<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Publishing Points<?php $this->end(); ?>
<?php $this->start('description'); ?>Edit<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php  echo $this->Html->link( '<i class="fa fa-video-camera"></i> Publishing Points', array('controller' => 'publishingpoints', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php echo $this->Html->link('<i class="fa fa-eye"></i> View', array('controller' => 'publishingpoints', 'action' => 'view', $publishingPoint['PublishingPoint']['id']),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-pencil"></i> Edit Publishing point</li>
<?php $this->end(); ?>
<?php
echo $this->Form->create('PublishingPoint');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('name', array('type' => 'hidden'));
?>
    <div class="col-lg-10">
        <div class="form-group">
            <label>Name</label>
            <p class="form-control-static">
                <?php echo h($publishingPoint["PublishingPoint"]["name"]); ?>
            </p>
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