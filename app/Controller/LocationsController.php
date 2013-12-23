<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class LocationsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    
    public function index() {
        $data = $this->Location->generateTreeList(null, null, null, '&nbsp;&nbsp;&nbsp;');
        $this->set('locations', $data);
    }
    
    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid location'));
        }

        $location = $this->Site->findById($id);
        
        if (!$location) {
            throw new NotFoundException(__('Invalid location'));
        }
        $this->set('location', $location);
        
    }
    
    public function add() {
        if ($this->request->is('post')) {
            $this->Location->create();
            if ($this->Location->save($this->request->data)) {
                $this->Session->setFlash(__('Your location has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to add your location.'));
        }
        $parents = $this->Location->generateTreeList(null, null, null, '-');
        $this->set(compact('parents'));
        
    }
    
    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid location'));
        }

        $location = $this->Location->findById($id);
        if (!$location) {
            throw new NotFoundException(__('Invalid location'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Location->id = $id;
            if ($this->Location->save($this->request->data)) {
                $this->Session->setFlash(__('Your location has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to update your location.'));
        }

        if (!$this->request->data) {
            $this->request->data = $location;
        }
        $parents = $this->Location->generateTreeList(null, null, null, '-');
        $this->set(compact('parents'));
        $this->set('location', $location);
    }
    
    public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        if (!$id) {
            throw new NotFoundException(__('Invalid location'));
        }
        $location = $this->Location->findById($id);
        if (!$location) {
            throw new NotFoundException(__('Invalid location'));
        }
        $locname=$location["Location"]["name"];
        if ($this->Location->delete($id)) {
            $this->Session->setFlash(__('The location %s has been deleted.', $locname));
            return $this->redirect(array('action' => 'index'));
        }
    }
    
    public function up($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        if (!$id) {
            throw new NotFoundException(__('Invalid location'));
        }
        $location = $this->Location->findById($id);
        if (!$location) {
            throw new NotFoundException(__('Invalid location'));
        }
        $this->Location->moveUp($id);
        $this->Session->setFlash(__('The location %s has been updated.', $location["Location"]["name"]));
        return $this->redirect(array('action' => 'index'));
    }
    
    public function down($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        if (!$id) {
            throw new NotFoundException(__('Invalid location'));
        }
        $location = $this->Location->findById($id);
        if (!$location) {
            throw new NotFoundException(__('Invalid location'));
        }

        $this->Location->moveDown($id);
        $this->Session->setFlash(__('The location %s has been updated.', $location["Location"]["name"]));
        return $this->redirect(array('action' => 'index'));
    }
}
?>
