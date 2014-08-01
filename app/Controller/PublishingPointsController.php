<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class PublishingPointsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    var $uses = array("Event", "EventLocation", "PublishingPoint", "Server", "Playlist", "Location", "Setting");
    
    /**
     * List all publishing points
     */
    public function index() {
        $this->PublishingPoint->recursive = 0;
        $publishingpoints=$this->PublishingPoint->find('all');
        $this->set('publishingpoints', $publishingpoints);
    }
    
    /**
     * Add a publishing point to a server (redirect to server page)
     * @param type $server_id
     * @return type
     * @throws NotFoundException
     */
    public function addServer($server_id=null) {
        if (!$server_id) {
            throw new NotFoundException(__('Invalid server'));
        }
        if ($this->request->is('post')) {
            $this->PublishingPoint->create();
            if ($this->PublishingPoint->save($this->request->data)) {
                $this->Session->setFlash(__('Your publishing point has been saved.','success'));
                return $this->redirect(array('controller' => 'servers', 'action' => 'view', $server_id));
            }
            $this->Session->setFlash(__('Unable to add your publishing point.'));
        }
        $this->set('server_id', $server_id);
        
        // Get events
        $events = $this->Event->find('list', array('conditions' => array('Event.deleted' => 0)));
        $this->set(compact('events'));
        
         // Get playlists
        $playlists = $this->Playlist->find('list');
        $this->set(compact('playlists'));
        
        // Get locations
        $locations = $this->Location->generateTreeList(null, null, null, '- ');
        $this->set(compact('locations'));
    }
    
    /**
     * Edit a publishing point from a server page (redirect to server)
     * @param type $server_id
     * @param type $id
     * @return type
     * @throws NotFoundException
     */
    public function editServer($server_id=null,$id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid publishing point'));
        }
        if (!$server_id) {
            throw new NotFoundException(__('Invalid server'));
        }
        $publishingPoint = $this->PublishingPoint->findById($id);
        if (!$publishingPoint) {
            throw new NotFoundException(__('Invalid publishing point'));
        }
        if ($publishingPoint["PublishingPoint"]["live"]==Configure::read('PUBLISHING_POINT_LIVE_ON')) {
            throw new ForbiddenException(__('Unable to edit a publishing point in Live state'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->PublishingPoint->id = $id;
            $this->PublishingPoint->configuration_status=0;
            
            if ($this->PublishingPoint->save($this->request->data)) {
                $this->Session->setFlash(__('Your publishing point has been updated.'),'success');
                return $this->redirect(array('controller' => 'servers', 'action' => 'view', $server_id));
            }
            $this->Session->setFlash(__('Unable to update your publishing point.'));
        }

        if (!$this->request->data) {
            $this->request->data = $publishingPoint;
        }
        $this->set('publishingPoint', $publishingPoint);
        
        // Get events
        $events = $this->Event->find('list', array('conditions' => array('Event.deleted' => 0)));
        $this->set(compact('events'));
        
         // Get playlists
        $playlists = $this->Playlist->find('list');
        $this->set(compact('playlists'));
        
        // Get locations
        $locations = $this->Location->generateTreeList(null, null, null, '- ');
        $this->set(compact('locations'));
    }
    
    /**
     * Edit a publishing point
     * @param type $id
     * @return type
     * @throws NotFoundException
     */
    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid publishing point'));
        }
        $this->PublishingPoint->recursive=0;
        $publishingPoint = $this->PublishingPoint->findById($id);
        if (!$publishingPoint) {
            throw new NotFoundException(__('Invalid publishing point'));
        }
        if ($publishingPoint["PublishingPoint"]["live"]==Configure::read('PUBLISHING_POINT_LIVE_ON')) {
            throw new ForbiddenException(__('Unable to edit a publishing point in Live state'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->PublishingPoint->id = $id;
            $this->request->data["PublishingPoint"]["configuration_status"]=Configure::read('PUBLISHING_POINT_DRAFT');
            if ($this->PublishingPoint->save($this->request->data)) {
                $this->Session->setFlash(__('Your publishing point has been updated.'),'success');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to update your publishing point.'), 'error');
        }

        if (!$this->request->data) {
            $this->request->data = $publishingPoint;
        }
        $this->set('publishingPoint', $publishingPoint);
        
        // Get events
        $events = $this->Event->find('list', array('conditions' => array('Event.deleted' => 0)));
        $this->set(compact('events'));
        
        // Get servers
        $servers = $this->Server->find('list', array('conditions' => array('Server.deleted' => 0)));
        $this->set(compact('servers'));
        
        // Get playlists
        $playlists = $this->Playlist->find('list');
        $this->set(compact('playlists'));
        
        // Get locations
        $locations = $this->Location->generateTreeList(null, null, null, '- ');
        $this->set(compact('locations'));
    }
    
    /**
     * Add a new publishing point
     * @return type
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->PublishingPoint->create();
            if ($this->PublishingPoint->save($this->request->data)) {
                $this->Session->setFlash(__('Your publishing point has been saved.','success'));
                return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to add your publishing point.'));
        }
        
        
        // Get events
        $events = $this->Event->find('list', array('conditions' => array('Event.deleted' => 0)));
        $this->set(compact('events'));
        
        // Get servers
        $servers = $this->Server->find('list', array('conditions' => array('Server.deleted' => 0)));
        $this->set(compact('servers'));
        
         // Get playlists
        $playlists = $this->Playlist->find('list');
        $this->set(compact('playlists'));
        
        // Get locations
        $locations = $this->Location->generateTreeList(null, null, null, '- ');
        $this->set(compact('locations'));
    }
    
    /**
     * Delete a publishing point from the server page (redirect to server)
     * @param type $server_id
     * @param type $id
     * @return type
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function deleteServer($server_id, $id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        $pp = $this->PublishingPoint->findById($id);
        if (!$pp) {
            throw new NotFoundException(__('Invalid Publishing Point'));
        }
        if ($pp["PublishingPoint"]["live"]==Configure::read('PUBLISHING_POINT_LIVE_ON')) {
            throw new ForbiddenException(__('Unable to edit a publishing point in Live state'));
        }
        $ppname=$pp["PublishingPoint"]["name"];
        $this->PublishingPoint->delete($id);
        $this->Session->setFlash(__('The publishing point %s has been deleted.', h($ppname)),'success');
        return $this->redirect(array('controller' => 'servers', 'action' => 'view', $server_id));
    }
    
    /**
     * Delete a publishing point
     * @param type $id
     * @return type
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        $pp = $this->PublishingPoint->findById($id);
        if (!$pp) {
            throw new NotFoundException(__('Invalid Publishing Point'));
        }
        if ($pp["PublishingPoint"]["live"]==Configure::read('PUBLISHING_POINT_LIVE_ON')) {
            throw new ForbiddenException(__('Unable to edit a publishing point in Live state'));
        }
        $ppname=$pp["PublishingPoint"]["name"];
        $this->PublishingPoint->delete($id);
        $this->Session->setFlash(__('The publishing point %s has been deleted.', h($ppname)),'success');
        return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'index'));
    }
    
    /**
     * View a publishing point
     * @param type $id
     * @throws NotFoundException
     */
    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid publishing point'));
        }

        $publishingPoint = $this->PublishingPoint->findById($id);
        
        if (!$publishingPoint) {
            throw new NotFoundException(__('Invalid publishing point'));
        }
        $this->set('publishingPoint', $publishingPoint);
        
        
    }
    
    /**
     * Set a publish point in published status
     * @param type $id
     * @param type $return
     * @return type
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function publish($id, $return='view') {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        $this->PublishingPoint->recursive=0;
        $pp = $this->PublishingPoint->findById($id);
        if (!$pp) {
            throw new NotFoundException(__('Invalid Publishing Point'));
        }
        if ($pp["PublishingPoint"]["live"]==Configure::read('PUBLISHING_POINT_LIVE_ON')) {
            throw new ForbiddenException(__('Unable to publish a publishing point in Live state'));
        }
        $continu=true;
        if ($pp["Server"]["source_id"]=="") {
            // Check that a playlist is specified (parent server)
            if ($pp["PublishingPoint"]["playlist_id"]=="") {
                // Check if publishing is for a remote location
                if (($pp["PublishingPoint"]["location_id"]==$pp["Server"]["location_id"])||($pp["PublishingPoint"]["location_id"]=="")) {
                    $continu=false;
                    $this->Session->setFlash("Unable to publish. A playlist must be specified for this publishing point", 'error');
                }
            }
        } 
        
        if ($continu) {
            $pp["PublishingPoint"]["configuration_status"]=Configure::read('PUBLISHING_POINT_PUBLISHED');
            $this->PublishingPoint->save($pp);
            $this->Session->setFlash(__('The publishing point %s has been published.', h($pp["PublishingPoint"]["name"])), 'success');
        }
        if ($return=='view') {
            return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'view', $id));
        } else {
            return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'index'));
        }
    }
    
    /**
     * List all publishing for an event
     * @param type $event_id
     * @throws NotFoundException
     */
    public function listEvent($event_id) {
        if (!$event_id) {
            throw new NotFoundException(__('Invalid event'));
        }

        $event = $this->Event->findById($event_id);
        
        if (!$event) {
            throw new NotFoundException(__('Invalid event'));
        }
        
        // Get locations
        $this->Location->recursive=-1;
        $locations = $this->Location->find('threaded', array(
                                        'fields' => array('id', 'parent_id', 'name', 'short_name'),
                                        'order' => array('lft ASC') // or array('id ASC')
                                    ));
        $setBwRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_threshold_ratio")));
        $setBwUsageRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_usage_ratio")));
        $setRemoteBw = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "remote_bandwidth")));
        $publishingpoints=$this->getPublishingPointList($event["Event"], $locations,$setBwRatio["Setting"]["value"], $setBwUsageRatio["Setting"]["value"],$setRemoteBw["Setting"]["value"]);
        $hasDraft=false;
        $hasApplied=false;
        $hasLive=false;
        $hasReady=false;
        foreach ($publishingpoints as $publishingpoint) {
            
            if ($publishingpoint["PublishingPoint"]["configuration_status"]==Configure::read('PUBLISHING_POINT_DRAFT')) {
                $hasDraft=true;
                
            }
            if ($publishingpoint["PublishingPoint"]["configuration_status"]==Configure::read('PUBLISHING_POINT_CONFIGURED')) {
                $hasApplied=true;
                
                if ($publishingpoint["PublishingPoint"]["live"]==Configure::read('PUBLISHING_POINT_LIVE_OFF')) {
                    $hasReady=true;
                }
            }
            if ($publishingpoint["PublishingPoint"]["live"]==Configure::read('PUBLISHING_POINT_LIVE_ON')) {
                
                $hasLive=true;
            }
        }
        $this->set('has_live', $hasLive);
        $this->set('has_ready', $hasReady);
        $this->set('has_applied', $hasApplied);
        $this->set('has_draft', $hasDraft);
        $this->set('publishingpoints', $publishingpoints);
        $this->set('event', $event);
    }
    
    /**
     * Publish all publishing point of a specified event
     * @param type $event_id
     * @return type
     * @throws NotFoundException
     */
    public function publishAll($event_id) {
        if (!$event_id) {
            throw new NotFoundException(__('Invalid event'));
        }

        $event = $this->Event->findById($event_id);
        
        if (!$event) {
            throw new NotFoundException(__('Invalid event'));
        }
        
        // Get locations
        $this->Location->recursive=-1;
        $locations = $this->Location->find('threaded', array(
                                        'fields' => array('id', 'parent_id', 'name', 'short_name'),
                                        'order' => array('lft ASC') // or array('id ASC')
                                    ));
        $setBwRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_threshold_ratio")));
        $setBwUsageRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_usage_ratio")));
        $setRemoteBw = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "remote_bandwidth")));
        $publishingpoints=$this->getPublishingPointList($event["Event"], $locations,$setBwRatio["Setting"]["value"], $setBwUsageRatio["Setting"]["value"],$setRemoteBw["Setting"]["value"]);
        foreach ($publishingpoints as $publishingpoint) {
            if ($publishingpoint["PublishingPoint"]["configuration_status"]==Configure::read('PUBLISHING_POINT_DRAFT')) {
                $publishingpoint["PublishingPoint"]["configuration_status"]=Configure::read('PUBLISHING_POINT_PUBLISHED');
                $this->PublishingPoint->save($publishingpoint);
            }
        }
        return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'listevent', $event_id));
    }
    
    /**
     * Start live for publishing point related to vent specified
     * @param type $event_id
     * @return type
     * @throws NotFoundException
     */
    public function startLive($event_id) {
        if (!$event_id) {
            throw new NotFoundException(__('Invalid event'));
        }

        $event = $this->Event->findById($event_id);
        
        if (!$event) {
            throw new NotFoundException(__('Invalid event'));
        }
        // Save event status
        $event["Event"]["live"]=Configure::read('PUBLISHING_POINT_LIVE_ON');
        $this->Event->save($event);
        
        // Get locations
        $this->Location->recursive=-1;
        $locations = $this->Location->find('threaded', array(
                                        'fields' => array('id', 'parent_id', 'name', 'short_name'),
                                        'order' => array('lft ASC') // or array('id ASC')
                                    ));
        $setBwRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_threshold_ratio")));
        $setBwUsageRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_usage_ratio")));
        $setRemoteBw = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "remote_bandwidth")));
        $publishingpoints=$this->getPublishingPointList($event["Event"], $locations,$setBwRatio["Setting"]["value"], $setBwUsageRatio["Setting"]["value"],$setRemoteBw["Setting"]["value"]);
        foreach ($publishingpoints as $publishingpoint) {
            if ($publishingpoint["PublishingPoint"]["configuration_status"]==Configure::read('PUBLISHING_POINT_CONFIGURED')) {
                $publishingpoint["PublishingPoint"]["live"]=Configure::read('PUBLISHING_POINT_LIVE_ON');
                $this->PublishingPoint->save($publishingpoint);
            }
        }
        return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'listevent', $event_id));
    }
    
    /**
     * Stop live publishint point for a specified event
     * @param type $event_id The event
     * @return type
     * @throws NotFoundException
     */
    public function stopLive($event_id) {
        if (!$event_id) {
            throw new NotFoundException(__('Invalid event'));
        }

        $event = $this->Event->findById($event_id);
        
        if (!$event) {
            throw new NotFoundException(__('Invalid event'));
        }
        // Save event status
        $event["Event"]["live"]=Configure::read('PUBLISHING_POINT_LIVE_OFF');
        $this->Event->save($event);
        
        
        // Get locations
        $this->Location->recursive=-1;
        $locations = $this->Location->find('threaded', array(
                                        'fields' => array('id', 'parent_id', 'name', 'short_name'),
                                        'order' => array('lft ASC') // or array('id ASC')
                                    ));
        $setBwRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_threshold_ratio")));
        $setBwUsageRatio = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "bandwidth_usage_ratio")));
        $setRemoteBw = $this->Setting->find('first', array('conditions' => array('Setting.key'=> "remote_bandwidth")));
        $publishingpoints=$this->getPublishingPointList($event["Event"], $locations,$setBwRatio["Setting"]["value"], $setBwUsageRatio["Setting"]["value"],$setRemoteBw["Setting"]["value"]);
        foreach ($publishingpoints as $publishingpoint) {
            if ($publishingpoint["PublishingPoint"]["live"]==Configure::read('PUBLISHING_POINT_LIVE_ON')) {
                $publishingpoint["PublishingPoint"]["live"]=Configure::read('PUBLISHING_POINT_LIVE_OFF');
                $this->PublishingPoint->save($publishingpoint);
            }
        }
        return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'listevent', $event_id));
    }
    
    /**
     * Get all publishing point for an event (create new publishing point of needed)
     * @param type $event
     * @param type $locations
     * @param type $bandwidth_threshold_ratio
     * @param type $bandwidth_usage_ratio
     * @param type $remote_bandwidth
     * @return type
     */
    private function getPublishingPointList($event, $locations,$bandwidth_threshold_ratio, $bandwidth_usage_ratio, $remote_bandwidth) {
        
        $list=array();
        
        foreach ($locations as $loc) {
            if (sizeof($loc["children"])==0) {
                // For every location (only terminal location are considered : Toulouse but not France)
                
                // Get list of server for this location
                $serverList=$this->Server->findByLocationId("local", $loc["Location"]["id"]);
                foreach ($serverList as $server) {
                    $list[]=$this->PublishingPoint->getPublishingPointForEventLocation($event, $server["Server"], $loc["Location"], $event["short_name"], $bandwidth_threshold_ratio, $bandwidth_usage_ratio, 0);
                }
                
                // Get list of server for this location
                $serverList=$this->Server->findByLocationId("remote", $loc["Location"]["id"]);
                foreach ($serverList as $server) {
                    $list[]=$this->PublishingPoint->getPublishingPointForEventLocation($event, $server["Server"], $loc["Location"], $event["short_name"]. $loc["Location"]["short_name"], $bandwidth_threshold_ratio, $bandwidth_usage_ratio, $remote_bandwidth);
                }
                
                
            } else {
                $list=array_merge($list, $this->getPublishingPointList($event, $loc["children"],$bandwidth_threshold_ratio, $bandwidth_usage_ratio, $remote_bandwidth));
            }
        }
        return $list;
    }
    
    
}
?>
