<?php $this->extend('/Servers/default'); ?>
<?php $this->start('title'); ?>Servers<?php $this->end(); ?>
<?php $this->start('description'); ?>List<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-dashboard"></i> Dashboard', array('controller' => 'dashboard', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php  echo $this->Html->link( '<i class="fa fa-building-o"></i> Servers', array('controller' => 'servers', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-eye"></i> View</li>
<?php $this->end(); ?>
    <div class="col-lg-10">
        <div class="form-group">
            <label>Name</label>
            <p class="form-control-static"><?php echo h($server['Server']['name']); ?></p>
        </div>
        <div class="form-group">
            <label>Key</label>
            <p class="form-control-static"><?php echo h($server['Server']['key']); ?></p>
        </div>
        <div class="form-group">
            <label>Publishing Points</label>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped tablesorter">
                  <thead>
                    <tr>
                      <th class="header">Name <i class="fa fa-sort"></i></th>
                      <th class="header">Actions <i class="fa fa-sort"></i></th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php foreach ($publishingPoints as $pp): ?>
                    <tr>
                        <td><?php echo $pp['name']; ?></td>
                        <td>
                          <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('controller'=>'publishingpoints', 'action' => 'edit', $server['Server']['id'], $pp['id']),array('escape' => false)); ?>
                          &nbsp;&nbsp;<?php echo $this->Form->postLink(
                              '<i class="fa fa-eraser"></i> Delete',
                              array('controller'=>'publishingpoints', 'action' => 'delete', $server['Server']['id'], $pp['id']),
                              array('escape' => false, 'confirm' => 'Are you sure?'));
                          ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php unset($pp); ?>
                    </tbody>
                </table>
                <?php  echo $this->Html->link( '<i class="fa fa-plus-circle"></i> Add Publishing Point', array('controller' => 'publishingpoints', 'action' => 'add',$server['Server']['id']),array('escape' => false)); ?>
              </div>
        </div>
    </div>
    <div class="col-lg-2">
        
                <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $server['Server']['id']),array('escape' => false, 'class' => "list-group-item")); ?>
                <?php echo $this->Form->postLink(
                          '<i class="fa fa-eraser"></i> Delete',
                          array('action' => 'delete', $server['Server']['id']),
                          array('escape' => false, 'confirm' => 'Are you sure?', 'class' => "list-group-item"));
                      ?>
            
    </div>