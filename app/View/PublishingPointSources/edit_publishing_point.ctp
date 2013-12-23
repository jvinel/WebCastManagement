<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Publishing Points<?php $this->end(); ?>
<?php $this->start('description'); ?>Edit<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php  echo $this->Html->link( '<i class="fa fa-video-camera"></i> Publishing Points', array('controller' => 'publishingpoints', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php echo $this->Html->link('<i class="fa fa-eye"></i> View', array('controller' => 'publishingpoints', 'action' => 'view', $ppSource['PublishingPointSource']['publishing_point_id']),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-pencil"></i> Edit Source</li>
<?php $this->end(); ?>
<?php
echo $this->Form->create('PublishingPointSource');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('publishing_point_id', array('type' => 'hidden'));
?>
    <div class="col-lg-10">
        <div class="form-group">
            <?php echo $this->Form->input('url', array("class"=>"form-control")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('timeout', array("class"=>"form-control")); ?>
        </div>
        
    </div>
    <div class="col-lg-2">
         <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i>  Save', array('type' => 'submit', 'escape' => false,"class"=>"btn btn-default")); ?>
    </div>
    
<?php echo $this->Form->end(); ?>

    <script>
        $('select').selectpicker();
    </script>