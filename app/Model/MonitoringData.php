<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Server
 *
 * @author vinel_j
 */
class MonitoringData extends AppModel{
    public $belongsTo = array('PublishingPoint', 'LiveSession');
}

?>
