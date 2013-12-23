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
class EventLocation extends AppModel{
    public $belongsTo = array(
        'Event', 'Location'
    );
    
    public $validate = array(  
        'audience' => array(  
            'rule' => 'numeric',  
            'message' => 'Enter the total audience expected for this location'  
        )  
    );  
}

?>
