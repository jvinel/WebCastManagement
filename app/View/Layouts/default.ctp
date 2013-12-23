<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=8">
	<title>WebCast Management: <?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

                // Css
		echo $this->Html->css('bootstrap');
		echo $this->Html->css('bootstrap-select.min');
                echo $this->Html->css('sb-admin');
                echo $this->Html->css('font-awesome');
                echo $this->Html->css('bootstrap-datetimepicker.min');

                // Js
                echo $this->Html->script('jquery-1.10.2');
                echo $this->Html->script('bootstrap');
                echo $this->Html->script('respond.min');
                echo $this->Html->script('html5shiv');
                echo $this->Html->script('bootstrap-select.min');
                echo $this->Html->script('moment');
                echo $this->Html->script('bootstrap-datetimepicker.min');
                
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
        
</head>
<body>
    <div id="wrapper">

      <!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.html">WebCast Management</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <?php echo $this->fetch('menu'); ?>  
          </ul>

          
        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <h1><?php echo $this->fetch('title'); ?> <small><?php echo $this->fetch('description'); ?></small></h1>
            <ol class="breadcrumb">
                <?php echo $this->fetch('breadcrumb'); ?>
            </ol>
            <?php if(($this->Session->check('Message.flash'))) { ?>
                <div class="alert alert-info alert-dismissable">
                    <?php echo $this->Session->flash(); ?>
                </div>
            <?php } ?>
          </div>
        </div><!-- /.row -->

        <div class="row">
            <?php echo $this->fetch('content'); ?>
        </div>

        <div class="row">
            <br/><br/><br/>
            <div class="alert alert-warning alert-dismissable">
                <?php echo $this->element('sql_dump'); ?>
            </div>
        </div>
      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->
	
	      

</body>
</html>
