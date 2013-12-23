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
class Location extends AppModel{
    public $actsAs = array('Tree');
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
    
    public $hasMany = array('Server', 'PublishingPoint', 'EventLocation');
    public $hasAndBelongsToMany = array(
        'Member' => array(
            'className' => 'Server',
        )
    );
    
}

?>
