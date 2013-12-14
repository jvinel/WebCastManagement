<?php $this->extend('/Sites/default'); ?>
<?php $this->start('title'); ?>Servers<?php $this->end(); ?>
<?php $this->start('description'); ?>List<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-dashboard"></i> Dashboard', array('controller' => 'dashboard', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-building-o"></i> Sites</li>
<?php $this->end(); ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped tablesorter">
          <thead>
            <tr>
              <th class="header">Name <i class="fa fa-sort"></i></th>
              <th class="header">Actions <i class="fa fa-sort"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sites as $site): ?>
              <tr>
                  <td><?php echo $this->Html->link($site['Site']['name'], array('controller' => 'site', 'action' => 'view', $site['Site']['id'])); ?></td>
                  <td>
                      <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $site['Site']['id']),array('escape' => false)); ?>
                      &nbsp;&nbsp;<?php echo $this->Form->postLink(
                          '<i class="fa fa-eraser"></i> Delete',
                          array('action' => 'delete', $site['Site']['id']),
                          array('escape' => false, 'confirm' => 'Are you sure?'));
                      ?>
                  </td>
              </tr>
              <?php endforeach; ?>
              <?php unset($site); ?>
          </tbody>
        </table>
      </div>
    <?php  echo $this->Html->link( '<i class="fa fa-plus-circle"></i> Add Site', array('controller' => 'sites', 'action' => 'add'),array('escape' => false)); ?>