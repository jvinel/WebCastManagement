<?php $this->extend('/Settings/default'); ?>
<?php $this->start('title'); ?>Settings<?php $this->end(); ?>
<?php $this->start('description'); ?>Overview<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li  class="active"><i class="fa fa-gears"></i> Settings</li>
<?php $this->end(); ?>
  
    <div class="alert alert-success alert-dismissable">
       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       You can configure Servers, Publishing Points, Locations and Playlists.
   </div>
    
    <div class="col-lg-3">
        <div class="panel panel-info">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6">
                <i class="fa fa-building-o fa-5x"></i>
              </div>
              <div class="col-xs-6 text-right">
                <p class="announcement-heading"><?php echo $servers_cnt; ?></p>
                <p class="announcement-text">Servers</p>
              </div>
            </div>
          </div>
            <a href="<?php echo $this->Html->url(array('controller' => 'servers', 'action' => 'index')); ?>">
            <div class="panel-footer announcement-bottom">
              <div class="row">
                <div class="col-xs-6">
                  View Servers
                </div>
                <div class="col-xs-6 text-right">
                  <i class="fa fa-arrow-circle-right"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
    <div class="col-lg-3">
        <div class="panel panel-info">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6">
                <i class="fa fa-video-camera fa-5x"></i>
              </div>
              <div class="col-xs-6 text-right">
                <p class="announcement-heading"><?php echo $pps_cnt; ?></p>
                <p class="announcement-text">Publishing Points</p>
              </div>
            </div>
          </div>
            <a href="<?php echo $this->Html->url(array('controller' => 'publishingpoints', 'action' => 'index')); ?>">
            <div class="panel-footer announcement-bottom">
              <div class="row">
                <div class="col-xs-6">
                  View Publishing Points
                </div>
                <div class="col-xs-6 text-right">
                  <i class="fa fa-arrow-circle-right"></i>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
    <div class="col-lg-3">
      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6">
              <i class="fa fa-sitemap fa-5x"></i>
            </div>
            <div class="col-xs-6 text-right">
              <p class="announcement-heading"><?php echo $locations_cnt; ?></p>
              <p class="announcement-text">Locations</p>
            </div>
          </div>
        </div>
        <a href="<?php echo $this->Html->url(array('controller' => 'locations', 'action' => 'index')); ?>">
            <div class="panel-footer announcement-bottom">
              <div class="row">
                <div class="col-xs-6">
                  View Locations
                </div>
                <div class="col-xs-6 text-right">
                  <i class="fa fa-arrow-circle-right"></i>
                </div>
              </div>
            </div>
          </a>
      </div>
    </div>  
   <div class="col-lg-3">
      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6">
              <i class="fa fa-play-circle fa-5x"></i>
            </div>
            <div class="col-xs-6 text-right">
              <p class="announcement-heading"><?php echo $playlists_cnt; ?></p>
              <p class="announcement-text">Playlists</p>
            </div>
          </div>
        </div>
        <a href="<?php echo $this->Html->url(array('controller' => 'playlists', 'action' => 'index')); ?>">
            <div class="panel-footer announcement-bottom">
              <div class="row">
                <div class="col-xs-6">
                  View Playlists
                </div>
                <div class="col-xs-6 text-right">
                  <i class="fa fa-arrow-circle-right"></i>
                </div>
              </div>
            </div>
          </a>
      </div>
    </div>  
    