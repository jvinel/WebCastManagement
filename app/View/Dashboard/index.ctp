<?php $this->extend('/Dashboard/default'); ?>
<?php $this->start('title'); ?>Dashboard<?php $this->end(); ?>
<?php $this->start('description'); ?>Statistics Overview<?php $this->end(); ?>
<?php $this->start('breadcrumb'); ?>
    <li  class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
<?php $this->end(); ?>
  
    <div class="alert alert-success alert-dismissable">
       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       Welcome to WebCast Management console.
   </div>
    
    <div class="col-lg-3">
        <div class="panel panel-success">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6">
                <i class="fa fa-users fa-5x"></i>
              </div>
              <div class="col-xs-6 text-right">
                <p class="announcement-heading"><?php echo $sm_concurrent_player[0][0]["sm"]; ?></p>
                <p class="announcement-text">Connected Players</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="col-lg-3">
      <div class="panel panel-danger">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-6">
              <i class="fa fa-warning fa-5x"></i>
            </div>
            <div class="col-xs-6 text-right">
              <p class="announcement-heading"><?php echo $nb_publishing_point_error[0][0]["cnt"]; ?></p>
              <p class="announcement-text">Publishing Points Errors</p>
            </div>
          </div>
        </div>
      </div>
    </div>  
   
    <div class="col-lg-3">
    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-6">
            <i class="fa fa-building-o fa-5x"></i>
          </div>
          <div class="col-xs-6 text-right">
            <p class="announcement-heading"><?php echo $nb_server[0][0]["cnt"]; ?></p>
            <p class="announcement-text">Servers</p>
          </div>
        </div>
      </div>
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
            <p class="announcement-heading"><?php echo $nb_publishing_point[0][0]["cnt"]; ?></p>
            <p class="announcement-text">Publishing Points</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Current connected players</h3>
        </div>
        <div class="panel-body">
          <div id="splinewrapper"></div>
          <div class="clear"></div>
          <?php echo $this->HighCharts->render('Spline Chart Live Data'); ?>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
        function updateGlobalData() {
            
        }
      </script>