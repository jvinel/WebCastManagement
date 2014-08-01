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
class Server extends AppModel{
    public $actsAs = array('Utils.SoftDelete');
    
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        ),
        'bandwidth'=> array(
            'rule' => 'notEmpty'
        )
    );
    
    public $hasMany = array('PublishingPoint');
    public $belongsTo = array('Location',
            'Source' => array( 
                        'className'	=> 'Server', 
                        'foreignKey' => 'source_id' 
                )
            );
    
    public $hasAndBelongsToMany = array(
        'RemoteLocation' => array(
            'className' => 'Location',
            'unique'    => "false"
        )
    );
    
    public function findByLocationId($type="local", $location_id) {
        if ($type=="local") {
            // Get list of server for the location specified ($location_id)
            $this->recursive = -1;
            return($this->find('all', array('conditions' => array('Server.deleted' => 0, 'Server.location_id' => $location_id))));
        } else if ($type=="remote") {
            // Get list of server identified as remote for the location specified ($location_id)
            $this->recursive = -1;

            $options['joins'] = array(
                array('table' => 'locations_servers',
                    'alias' => 'LocationServer',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Server.id = LocationServer.server_id',
                    )
                ) );

            $options['conditions'] = array(
                'LocationServer.location_id' => $location_id,
                'Server.deleted' => 0 );

            // Get list of server for the current location (in remote location)
            return($this->find('all', $options));
        } else if ($type=="all") {
            return array_merge($this->findByLocationId("local", $location_id), $this->findByLocationId("remote", $location_id));
        } else {
            throw new CakeException(__('Invalid parameter'));
        }
    }
    
    public function findRemoteByLocationId($location_id) {
        
    }
}

?>
