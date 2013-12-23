<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Locations<?php $this->end(); ?>
<?php $this->start('description'); ?>Edit<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php  echo $this->Html->link( '<i class="fa fa-sitemap"></i> Locations', array('controller' => 'locations', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-pencil"></i> Edit</li>
<?php $this->end(); ?>
<?php echo $this->Form->create('Location'); ?>
<?php  echo $this->Form->input('id', array('type' => 'hidden')); ?>
    <div class="col-lg-10">
        <div class="form-group">
            <?php echo $this->Form->input('name', array("class"=>"form-control")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('short_name', array("class"=>"form-control")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('parent_id'); ?><br/>
            <?php echo $this->Form->input('parent_id', array('label'=>false, 'empty' => true, "class" => "selectpicker")); ?>
        </div>
    </div>
    <div class="col-lg-2">
         <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i>  Save', array('type' => 'submit', 'escape' => false,"class"=>"btn btn-default")); ?>
    </div>
    <script>
        $('select').selectpicker();
    </script>
<?php echo $this->Form->end(); ?>