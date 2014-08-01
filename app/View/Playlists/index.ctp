<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Playlists<?php $this->end(); ?>
<?php $this->start('description'); ?>List<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?php  echo $this->Html->link( '<i class="fa fa-gears"></i> Settings', array('controller' => 'settings', 'action' => 'index'),array('escape' => false)); ?></li>
    <li class="active"><i class="fa fa-play-circle"></i> Playlists</li>
<?php $this->end(); ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped tablesorter"  id="pp_table">
          <thead>
            <tr>
              <th class="header">Name</th>
              <th class="header">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($playlists as $playlist): ?>
              <tr>
                  <td><?php echo $this->Html->link($playlist['Playlist']['name'], array('action' => 'view', $playlist['Playlist']['id'])); ?></td>
                  <td>
                      <?php echo $this->Html->link('<i class="fa fa-pencil"></i> Edit', array('action' => 'edit', $playlist['Playlist']['id']),array('escape' => false)); ?>
                      &nbsp;&nbsp;<?php echo $this->Form->postLink(
                          '<i class="fa fa-eraser"></i> Delete',
                          array('action' => 'delete', $playlist['Playlist']['id']),
                          array('escape' => false, 'confirm' => 'Are you sure?'));
                      ?>
                  </td>
              </tr>
              <?php endforeach; ?>
              <?php unset($playlist); ?>
          </tbody>
        </table>
      </div>
    <?php  echo $this->Html->link( '<i class="fa fa-plus-circle"></i> Add Playlist', array( 'action' => 'add'),array('escape' => false)); ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
                //$('#pp_table').dataTable();
                $('#pp_table').dataTable({"sPaginationType": "bs_normal"}); 
        } );
    </script>