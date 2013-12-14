<?php $this->start('menu'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-dashboard"></i> Dashboard', array('controller' => 'dashboard', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><a href="charts.html"><i class="fa fa-bar-chart-o"></i> Charts</a></li>
    <li><a href="tables.html"><i class="fa fa-table"></i> Tables</a></li>
    <li class="active"><?php  echo $this->Html->link( '<i class="fa fa-building-o"></i> Servers', array('controller' => 'servers', 'action' => 'index'),array('escape' => false)); ?></li>
<?php $this->end(); ?>

<?php echo $this->fetch('content'); ?>