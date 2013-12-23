<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class EventsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    var $uses = array("Event", "EventLocation","Location");
    
    public function index() {
        $this->set('events', $this->Event->find('all'));
    }
    
    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid event'));
        }

        $event = $this->Event->findById($id);
        
        if (!$event) {
            throw new NotFoundException(__('Invalid event'));
        }
        $this->set('event', $event);
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
    }
    
    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid event'));
        }

        $event = $this->Event->findById($id);
        if (!$event) {
            throw new NotFoundException(__('Invalid event'));
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
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to update your event.'));
        }

        if (!$this->request->data) {
            $this->request->data = $event;
        }
        $this->set('event', $event);
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
        $eventname=$event["Event"]["name"];
        $this->Event->delete($id);
        $this->Session->setFlash(__('The event %s has been deleted.', $eventname));
        return $this->redirect(array('action' => 'index'));
    }
}
?>
