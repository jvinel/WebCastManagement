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
class Event extends AppModel{
    public $actsAs = array('Utils.SoftDelete');
    
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
    
    public $hasAndBelongsToMany = array(
        'PublishingPoint' => array(
            'className' => 'PublishingPoint',
        )
    );
    
    public $hasMany = array(
        'EventLocation'
    );
}

?>
