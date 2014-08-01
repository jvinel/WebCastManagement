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
class EventImportance extends AppModel{
    public $actsAs = array('Utils.List');
    public $order = "EventImportance.position";
    public $validate = array(
        'url' => array(
            'name' => 'notEmpty'
        )
    );
    
    public $hasMany = array(
    	'Event' 
    );
    
}

?>
