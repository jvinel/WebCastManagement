<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class ServersController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    var $uses = array("Server", "PublishingPoint", "Location");
    
    public function index() {
        $this->set('servers', $this->Server->find('all'));
    }
    
    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid server'));
        }

        $server = $this->Server->findById($id);
        
        if (!$server) {
            throw new NotFoundException(__('Invalid server'));
        }
        $this->set('server', $server);
        $this->set('publishingPoints', $server["PublishingPoint"]);
        
    }
    
    public function add() {
        if ($this->request->is('post')) {
            $this->Server->create();
            if ($this->Server->save($this->request->data)) {
                $this->Session->setFlash(__('Your server has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to add your server.'));
        }
        // Get locations
        $locations = $this->Location->generateTreeList(null, null, null, '- ');
        $this->set(compact('locations'));
        // Get remote locations
        $remoteLocations = $this->Location->generateTreeList(null, null, null, '- ');
        $this->set(compact('remoteLocations'));
        // Get source servers
        $sources = $this->Server->find('list', array('conditions' => array('Server.deleted' => 0)));
        $this->set(compact('sources'));
    }
    
    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid server'));
        }

        $server = $this->Server->findById($id);
        if (!$server) {
            throw new NotFoundException(__('Invalid server'));
        }

        if ($this->request->is(array('post', 'put'))) {
            //echo "ICI";
            $this->Server->id = $id;
            //print_r($this->request->data);
            if ($this->Server->save($this->request->data)) {
                $this->Session->setFlash(__('Your server has been updated.'));
                return $this->redirect(array('action' => 'view', $id));
            }
            $this->Session->setFlash(__('Unable to update your server.'));
        }

        if (!$this->request->data) {
            $this->request->data = $server;
        }
        $this->set('server', $server);
        
        // Get locations
        $locations = $this->Location->generateTreeList(null, null, null, '- ');
        $this->set(compact('locations'));
        // Get remote locations
        $remoteLocations = $this->Location->generateTreeList(null, null, null, '- ');
        $this->set(compact('remoteLocations'));
        // Get source servers
        $sources = $this->Server->find('list', array('conditions' => array('Server.deleted' => 0, 'Server.id !=' => $id)));
        $this->set(compact('sources'));
    }
    
    public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        if (!$id) {
            throw new NotFoundException(__('Invalid server'));
        }
        $server = $this->Server->findById($id);
        if (!$server) {
            throw new NotFoundException(__('Invalid server'));
        }
        $servername=$server["Server"]["name"];
        $this->Server->delete($id);
        $this->Session->setFlash(__('The server %s has been deleted.', $servername));
        return $this->redirect(array('action' => 'index'));
    }
}
?>
