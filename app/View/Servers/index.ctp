<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Servers<?php $this->end(); ?>
<?php $this->start('description'); ?>List<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-building-o"></i> Servers</li>
<?php $this->end(); ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped tablesorter"  id="pp_table">
          <thead>
            <tr>
              <th class="header">Name</th>
              <th class="header">Location</th>
              <th class="header">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($servers as $server): ?>
              <tr>
                  <td><?php echo $this->Html->link($server['Server']['name'], array('controller' => 'servers', 'action' => 'view', $server['Server']['id'])); ?></td>
                  <td><?php echo $server["Location"]["name"]; ?></td>
                  <td>
                      <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $server['Server']['id']),array('escape' => false)); ?>
                      &nbsp;&nbsp;<?php echo $this->Form->postLink(
                          '<i class="fa fa-eraser"></i> Delete',
                          array('action' => 'delete', $server['Server']['id']),
                          array('escape' => false, 'confirm' => 'Are you sure?'));
                      ?>
                  </td>
              </tr>
              <?php endforeach; ?>
              <?php unset($server); ?>
          </tbody>
        </table>
      </div>
    <?php  echo $this->Html->link( '<i class="fa fa-plus-circle"></i> Add Server', array('controller' => 'servers', 'action' => 'add'),array('escape' => false)); ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
                //$('#pp_table').dataTable();
                $('#pp_table').dataTable({"sPaginationType": "bs_normal"}); 
        } );
    </script>