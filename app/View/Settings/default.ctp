<?php $this->start('menu'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-dashboard"></i> Dashboard', array('controller' => 'dashboard', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php  echo $this->Html->link( '<i class="fa fa-bar-chart-o"></i> Monitoring', array('controller' => 'events', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php  echo $this->Html->link( '<i class="fa fa-flag"></i> Events', array('controller' => 'events', 'action' => 'index'),array('escape' => false)); ?></li>
    <li  class="active"><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
<?php $this->end(); ?>

<?php echo $this->fetch('content'); ?>