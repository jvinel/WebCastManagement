<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Playlists<?php $this->end(); ?>
<?php $this->start('description'); ?>View<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li><?php  echo $this->Html->link( '<i class="fa fa-play-circle"></i> Playlists', array('action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-eye"></i> View</li>
<?php $this->end(); ?>
    <div class="col-lg-10">

        <div class="form-group">
            <label>Name</label>
            <p class="form-control-static"><?php echo h($playlist['Playlist']['name']); ?></p>
        </div>
        <div class="form-group">
            <label>Items</label>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped tablesorter">
                  <thead>
                    <tr>
                      <th class="header">Name </th>
                      <th class="header">Timeout </th>
                      <th class="header">Actions </th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo $item['url']; ?></td>
                        <td><?php echo $item['timeout']; ?></td>
                        <td>
                          <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('controller'=>'playlistitems', 'action' => 'editPlaylist', $playlist['Playlist']['id'], $item['id']),array('escape' => false)); ?>
                          &nbsp;&nbsp;<?php echo $this->Form->postLink(
                              '<i class="fa fa-eraser"></i> Delete',
                              array('controller'=>'playlistitems', 'action' => 'deletePlaylist', $playlist['Playlist']['id'], $item['id']),
                              array('escape' => false, 'confirm' => 'Are you sure?'));
                          ?>
                          &nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-arrow-up"></i> Up', array('controller'=>'playlistitems','action' => 'up', $playlist['Playlist']['id'], $item['id']),array('escape' => false)); ?>
                          &nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-arrow-down"></i> Down', array('controller'=>'playlistitems','action' => 'down', $playlist['Playlist']['id'], $item['id']),array('escape' => false)); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php unset($item); ?>
                    </tbody>
                </table>
                <?php  echo $this->Html->link( '<i class="fa fa-plus-circle"></i> Add Item', array('controller' => 'playlistitems', 'action' => 'addPlaylist',$playlist['Playlist']['id']),array('escape' => false)); ?>
              </div>
        </div>
    </div>
    <div class="col-lg-2">
        
                <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $playlist['Playlist']['id']),array('escape' => false, 'class' => "list-group-item")); ?>
                <?php echo $this->Form->postLink(
                          '<i class="fa fa-eraser"></i> Delete',
                          array('action' => 'delete', $playlist['Playlist']['id']),
                          array('escape' => false, 'confirm' => 'Are you sure?', 'class' => "list-group-item"));
                      ?>
            
    </div>