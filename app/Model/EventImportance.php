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
class PublishingPointSource extends AppModel{
    public $actsAs = array('Utils.List');
    public $order = "PublishingPointSource.position";
    public $validate = array(
        'url' => array(
            'rule' => 'notEmpty'
        )
    );
    
    public $belongsTo = array(
    	'PublishingPoint' => array ('foreingKey'=> 'publishing_point_id')
    );
    
}

?>
