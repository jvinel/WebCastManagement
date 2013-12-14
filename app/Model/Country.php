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
class Country extends AppModel{
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
    
    public $hasMany = array('Site');
}

?>
