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
class PlaylistItem extends AppModel{
    public $actsAs = array('Utils.List');
    public $order = "PlaylistItem.position";
    public $validate = array(
        'url' => array(
            'rule' => 'notEmpty'
        )
    );
    
    public $belongsTo = array(
    	'Playlist' => array ('foreingKey'=> 'playlist_id')
    );
    
}

?>
