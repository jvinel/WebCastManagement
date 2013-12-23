<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Servers<?php $this->end(); ?>
<?php $this->start('description'); ?>List<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php  echo $this->Html->link( '<i class="fa fa-building-o"></i> Servers', array('controller' => 'servers', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php echo $this->Html->link('<i class="fa fa-eye"></i> View', array('action' => 'view', $server['Server']['id']),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-pencil"></i> Edit</li>
<?php $this->end(); ?>
<?php
echo $this->Form->create('Server');
echo $this->Form->input('id', array('type' => 'hidden'));
?>
    <div class="col-lg-10">
        <div class="form-group">
            <?php echo $this->Form->input('name', array("class"=>"form-control")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('location_id'); ?><br/>
            <?php echo $this->Form->input('location_id', array('label'=>false, 'empty' => true, "class" => "selectpicker")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('RemoteLocation'); ?><br/>
            <?php echo $this->Form->input('RemoteLocation', array('label'=>false, 'empty' => true, "class" => "selectpicker")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('parent_id'); ?><br/>
            <?php echo $this->Form->input('parent_id', array('label'=>false, 'empty' => true, "class" => "selectpicker")); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('url', array("class"=>"form-control")); ?>
        </div>
    </div>
    <div class="col-lg-2">
         <?php echo $this->Form->button('<i class="fa fa-floppy-o"></i>  Save', array('type' => 'submit', 'escape' => false,"class"=>"btn btn-default")); ?>
    </div>
    <script>
        $('select').selectpicker();
    </script>
<?php echo $this->Form->end(); ?>