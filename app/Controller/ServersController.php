<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class ServersController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    
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
            $this->Server->id = $id;
            if ($this->Server->save($this->request->data)) {
                $this->Session->setFlash(__('Your server has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to update your server.'));
        }

        if (!$this->request->data) {
            $this->request->data = $server;
        }
        $this->set('server', $server);
    }
    
    public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->Server->delete($id)) {
            $this->Session->setFlash(__('The server with id: %s has been deleted.', h($id)));
            return $this->redirect(array('action' => 'index'));
        }
    }
}
?>
