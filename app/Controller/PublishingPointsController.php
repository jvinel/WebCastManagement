<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class PublishingPointsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    var $uses = array("Event", "PublishingPoint", "Server");
    
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
                $this->Session->setFlash(__('Your publishing point has been saved.'));
                return $this->redirect(array('controller' => 'servers', 'action' => 'view', $server_id));
            }
            $this->Session->setFlash(__('Unable to add your publishing point.'));
        }
        $this->set('server_id', $server_id);
        
        // Get events
        $events = $this->Event->find('list', array('conditions' => array('Event.deleted' => 0)));
        $this->set(compact('events'));
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

        if ($this->request->is(array('post', 'put'))) {
            $this->PublishingPoint->id = $id;
            if ($this->PublishingPoint->save($this->request->data)) {
                $this->Session->setFlash(__('Your publishing point has been updated.'));
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
        
        $publishingPoint = $this->PublishingPoint->findById($id);
        if (!$publishingPoint) {
            throw new NotFoundException(__('Invalid publishing point'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->PublishingPoint->id = $id;
            if ($this->PublishingPoint->save($this->request->data)) {
                $this->Session->setFlash(__('Your publishing point has been updated.'));
                return $this->redirect(array('action' => 'index'));
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
        
        // Get servers
        $servers = $this->Server->find('list', array('conditions' => array('Server.deleted' => 0)));
        $this->set(compact('servers'));
    }
    
    /**
     * Add a new publishing point
     * @return type
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->PublishingPoint->create();
            if ($this->PublishingPoint->save($this->request->data)) {
                $this->Session->setFlash(__('Your publishing point has been saved.'));
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
        $ppname=$pp["PublishingPoint"]["name"];
        $this->PublishingPoint->delete($id);
        $this->Session->setFlash(__('The publishing point %s has been deleted.', h($ppname)));
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
        $ppname=$pp["PublishingPoint"]["name"];
        $this->PublishingPoint->delete($id);
        $this->Session->setFlash(__('The publishing point %s has been deleted.', h($ppname)));
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
        
        $this->set('sources', $publishingPoint["PublishingPointSource"]);
        
    }
}
?>
