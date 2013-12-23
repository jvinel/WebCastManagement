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
class PublishingPoint extends AppModel{
    public $actsAs = array('Utils.SoftDelete');
    
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        ),
        'server_id'=> array(
            'rule' => 'notEmpty'
        )
    );
    
    public $belongsTo = array(
    	'Server' => array ('foreingKey'=> 'server_id'),
    	'Location' => array('foreignKey'=>'location_id'),
        'Event' => array('foreignKey'=>'event_id')
    );
    public $hasMany = array('MonitoringData', 'PublishingPointSource' => array('order' => 'PublishingPointSource.position ASC',));
    
    public $virtualFields = array(
    'limit_player_summary' => 'CONCAT(PublishingPoint.limit_connected_player , " players")',
    'limit_bandwidth_summary' => 'CONCAT(PublishingPoint.limit_player_bandwidth, " bandwidth")'
    );
}

?>
