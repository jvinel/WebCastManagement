<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class EventsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    var $uses = array("Event", "EventLocation","Location", "EventImportance", "Setting","Server","Playlist");
    
    /**
     * Index page -> List of events
     */
    public function index() {
        $this->set('events', $this->Event->find('all'));
    }
    
    /**
     * View an event
     * @param type $id
     * @throws NotFoundException
     */
    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid event'));
        }

        $event = $this->Event->findById($id);
        
        if (!$event) {
            throw new NotFoundException(__('Invalid event'));
        }
        $this->set('event', $event);
        if (sizeof($event["LiveSession"])>0) {
            $this->set("displaymonitoring",true);
        } else {
            $this->set("displaymonitoring", false);
        }
        // Get event importance list
        $eventImportances = $this->EventImportance->find('list');
        $this->set(compact('eventImportances'));
        $this->EventImportance->recursive=-1;
        $eventImportancesArray = $this->EventImportance->find('all');
        $this->set('eventImportancesArray', $eventImportancesArray);
        // Get locations
        $locs = $this->Location->find('threaded', array(
                                        'fields' => array('id', 'parent_id', 'name'),
                                        'order' => array('lft ASC') // or array('id ASC')
                                    ));
        // Get EventLocation
        $eventlocs = $this->EventLocation->find('all', array(
                                        'fields' => array('id', 'location_id', 'audience'),
                                        'conditions' => array('EventLocation.event_id' => $id)
                                    ));
        
        $locations=array();
        $this->buildEventLocation($locs, &$locations, "", $eventlocs);
        $this->set(compact('locations'));
        
        // Get settings data (to calculate max video bitrate or count of users
        $setBwRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_threshold_ratio")));
        $this->set("bandwidth_threshold_ratio",$setBwRatio["Setting"]["value"]);
        $setBwUsageRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_usage_ratio")));
        $this->set("bandwidth_usage_ratio",$setBwUsageRatio["Setting"]["value"]);
        
        
    }
    
    public function add() {
        if ($this->request->is('post')) {
            $this->Event->create();
            if ($this->Event->save($this->request->data)) {
                
                // Get count of location
                $eventloc_max=$this->request->data["eventloc_max"];
                
                // Iterate through location
                for ($cpt=0;$cpt<=$eventloc_max;$cpt++) {
                    
                    $fullData=array();
                    $eventLocData=array();
                    $eventLocData["location_id"]=$this->request->data["eventloc_" . $cpt . "_locationid"];
                    $eventLocData["audience"]=$this->request->data["eventloc_" . $cpt . "_audience"];
                    $eventloc_id=$this->request->data["eventloc_" . $cpt . "_id"];
                    $eventLocation = $this->EventLocation->findById($eventloc_id);
                    $eventLocData["event_id"]=$this->Event->getLastInsertID();
                    if (!$eventLocation) {
                        $this->EventLocation->create();
                    } else {
                       $eventLocData["id"]=$eventloc_id;
                    }
                    $fullData["EventLocation"]=$eventLocData;
                    $this->EventLocation->save($fullData);
                }
                
                $this->Session->setFlash(__('Your event has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to add your event.'));
        }
        
        // Get event importance list
        $eventImportances = $this->EventImportance->find('list');
        $this->set(compact('eventImportances'));
        $this->EventImportance->recursive=-1;
        $eventImportancesArray = $this->EventImportance->find('all');
        $this->set('eventImportancesArray', $eventImportancesArray);
        
        
        // Get locations
        $locs = $this->Location->find('threaded', array(
                                        'fields' => array('id', 'parent_id', 'name'),
                                        'order' => array('lft ASC') // or array('id ASC')
                                    ));
        // Get EventLocation
        $eventlocs = $this->EventLocation->find('all', array(
                                        'fields' => array('id', 'location_id', 'audience'),
                                        'conditions' => array('EventLocation.event_id' => 0)
                                    ));
        
        $locations=array();
        $this->buildEventLocation($locs, &$locations, "", $eventlocs);
        $this->set(compact('locations'));
        
        // Get settings data (to calculate max video bitrate or count of users
        $setBwRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_threshold_ratio")));
        $this->set("bandwidth_threshold_ratio",$setBwRatio["Setting"]["value"]);
        $setBwUsageRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_usage_ratio")));
        $this->set("bandwidth_usage_ratio",$setBwUsageRatio["Setting"]["value"]);
        
        // Get playlists
        $playlists = $this->Playlist->find('list');
        $this->set(compact('playlists'));
    }
    
    /**
     * Edit an event
     * @param type $id
     * @return type
     * @throws NotFoundException
     */
    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid event'));
        }

        $event = $this->Event->findById($id);
        if (!$event) {
            throw new NotFoundException(__('Invalid event'));
        }
        if ($event["Event"]["live"]==Configure::read('PUBLISHING_POINT_LIVE_ON')) {
            throw new ForbiddenException(__('Unable to edit an event in Live state'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Event->id = $id;
            if ($this->Event->save($this->request->data)) {
                //print_r($this->request->data);
                // Get count of location
                $eventloc_max=$this->request->data["eventloc_max"];
                
                // Iterate through location
                for ($cpt=0;$cpt<=$eventloc_max;$cpt++) {
                    
                    $fullData=array();
                    $eventLocData=array();
                    $eventLocData["location_id"]=$this->request->data["eventloc_" . $cpt . "_locationid"];
                    $eventLocData["audience"]=$this->request->data["eventloc_" . $cpt . "_audience"];
                    $eventloc_id=$this->request->data["eventloc_" . $cpt . "_id"];
                    $eventLocation = $this->EventLocation->findById($eventloc_id);
                    $eventLocData["event_id"]=$id;
                    if (!$eventLocation) {
                        $this->EventLocation->create();
                    } else {
                       $eventLocData["id"]=$eventloc_id;
                    }
                    $fullData["EventLocation"]=$eventLocData;
                    $this->EventLocation->save($fullData);
                }
                
                $this->Session->setFlash(__('Your event has been updated.'));
                return $this->redirect(array('action' => 'view', $id));
            }
            $this->Session->setFlash(__('Unable to update your event.'));
        }

        if (!$this->request->data) {
            $this->request->data = $event;
        }
        $this->set('event', $event);
        
        // Get event importance list
        $eventImportances = $this->EventImportance->find('list');
        $this->set(compact('eventImportances'));
        $this->EventImportance->recursive=-1;
        $eventImportancesArray = $this->EventImportance->find('all');
        $this->set('eventImportancesArray', $eventImportancesArray);
        
        // Get locations
        $locs = $this->Location->find('threaded', array(
                                        'fields' => array('id', 'parent_id', 'name'),
                                        'order' => array('lft ASC') // or array('id ASC')
                                    ));
       
        // Get EventLocation
        $eventlocs = $this->EventLocation->find('all', array(
                                        'fields' => array('id', 'location_id', 'audience'),
                                        'conditions' => array('EventLocation.event_id' => $id)
                                    ));
        
        
        
        $locations=array();
        $this->buildEventLocation($locs, &$locations, "", $eventlocs);
        
        $this->set(compact('locations'));
        
        
        // Get settings data (to calculate max video bitrate or count of users
        $setBwRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_threshold_ratio")));
        $this->set("bandwidth_threshold_ratio",$setBwRatio["Setting"]["value"]);
        $setBwUsageRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_usage_ratio")));
        $this->set("bandwidth_usage_ratio",$setBwUsageRatio["Setting"]["value"]);
        
        // Get playlists
        $playlists = $this->Playlist->find('list');
        $this->set(compact('playlists'));
    }
    
    /**
     * Return EventLocation(id, audience) for a specified location (locationid)
     * will return array("id" =>0,"audience"=>0) with no EventLocation found
     * @param type $eventlocs Array of EventLocation for current event
     * @param type $locationid Location to get defined audience
     * @return array with id, audience (row of EventLocation table for related $locationid)
     */
    private function getAudience($eventlocs, $locationid) {
        $result=array("id" =>0,"audience"=>0);
        foreach ($eventlocs as $el) {
            if ($el["EventLocation"]["location_id"]==$locationid) {
                $result["id"]=$el["EventLocation"]["id"];
                $result["audience"]=$el["EventLocation"]["audience"];
            }
        }
        return $result;
    }
    
    /**
     * Recursively generate a flat array of tree with terminal flag indicate if the current node 
     * is a terminal node (no children) and with audience associated to the event and location
     * as defined in EventLocation
     * @param type $locs Location (tree with threaded find)
     * @param type $results (flat array)
     * @param type $prefix (prefix used for recusrive calls)
     * @param type $eventlocs (array of EventLocation for this event)
     */
    private function buildEventLocation($locs, $results, $prefix, $eventlocs) {
        // Get settings data (to calculate max video bitrate or count of users
        $setBwRemote = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "remote_bandwidth")));
        
        foreach ($locs as $loc) {
            $location=array();
            $location["locationname"]=$prefix . $loc["Location"]["name"];
            $location["locationid"]=$loc["Location"]["id"];
            $temp=$this->getAudience($eventlocs,$loc["Location"]["id"]);
            $location["locationaudience"]=$temp["audience"];
            $location["locationeventid"]=$temp["id"];
            $location["terminal"]=1;
            if (sizeof($loc["children"])>0) {
                $location["terminal"]=0;
                $results[]=$location;
                $this->buildEventLocation($loc["children"], &$results, $prefix . "&nbsp;&nbsp;&nbsp;", $eventlocs);
            }  else {
                $bw=0;
                if (sizeof($loc["Server"])>0) {
                    // Sum bandwidth for all available servers
                    foreach ($loc["Server"] as $server) {
                        $bw+=$server["bandwidth"];
                    }
                } else {
                    // Case of a remote location
                    // Get list of server for the current location (in remote location)
                    $serverList = $this->Server->findByLocationId("remote",$loc["Location"]["id"]);
                    if (sizeof($serverList)>0) {
                        // If there is at least one server to handle this remote location, 
                        // we set the bandwidth limit according to settings table
                        $bw=$setBwRemote["Setting"]["value"];
                    } 
                }
                $location["bandwidth"]=$bw;
                $results[]=$location;
            }
            
        }
    }
    
    public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        if (!$id) {
            throw new NotFoundException(__('Invalid event'));
        }
        $event = $this->Event->findById($id);
        if (!$event) {
            throw new NotFoundException(__('Invalid event'));
        }
        if ($event["Event"]["live"]==Configure::read('PUBLISHING_POINT_LIVE_ON')) {
            throw new ForbiddenException(__('Unable to delete an event in Live state'));
        }
        
        $eventname=$event["Event"]["name"];
        $this->Event->delete($id);
        $this->Session->setFlash(__('The event %s has been deleted.', $eventname));
        return $this->redirect(array('action' => 'index'));
    }
}
?>
