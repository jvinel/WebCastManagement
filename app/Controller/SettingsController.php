<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class SettingsController extends AppController {
    
    var $uses =  array("Server", "Location", "PublishingPoint");
    
    
    public function index() {
        $servers_cnt=$this->Server->find('count', array('conditions' => array('Server.deleted' => 0)));
        $this->set('servers_cnt', $servers_cnt);

        $pps_cnt=$this->PublishingPoint->find('count', array('conditions' => array('PublishingPoint.deleted' => 0)));
        $this->set('pps_cnt', $pps_cnt);
        
        $locations_cnt=$this->Location->find('count');
        $this->set('locations_cnt', $locations_cnt);
    }
    
    
}
?>
