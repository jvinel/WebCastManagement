<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Locations<?php $this->end(); ?>
<?php $this->start('description'); ?>List<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-sitemap"></i> Locations</li>
<?php $this->end(); ?>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped tablesorter">
          <thead>
            <tr>
              <th class="header">Name </th>
              <th class="header">Actions </th>
            </tr>
          </thead>
          <tbody>
              <?php foreach($locations as $key=>$value){ ?>
                <tr>
                    <td><?php echo $value; ?></td>
                    <td>
                      <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $key),array('escape' => false)); ?>
                      &nbsp;&nbsp;<?php echo $this->Form->postLink(
                          '<i class="fa fa-eraser"></i> Delete',
                          array('action' => 'delete', $key),
                          array('escape' => false, 'confirm' => 'Are you sure?'));
                      ?>
                      &nbsp;&nbsp;<?php echo $this->Form->postLink('<i class="fa fa-arrow-up"></i> Up', array('action' => 'up', $key),array('escape' => false)); ?>
                      &nbsp;&nbsp;<?php echo $this->Form->postLink('<i class="fa fa-arrow-down"></i> Down', array('action' => 'down', $key),array('escape' => false)); ?>
                    </td>
                </tr>
              <?php } ?>
          </tbody>
        </table>
      </div>
    <?php  echo $this->Html->link( '<i class="fa fa-plus-circle"></i> Add Location', array('controller' => 'locations', 'action' => 'add'),array('escape' => false)); ?>
