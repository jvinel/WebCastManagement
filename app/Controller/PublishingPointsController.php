<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class PublishingPointsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    
    public function add($server_id=null) {
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
    }
    
    public function edit($server_id=null,$id = null) {
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
    }
    
    public function delete($server_id, $id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->PublishingPoint->delete($id)) {
            $this->Session->setFlash(__('The publishing point with id: %s has been deleted.', h($id)));
            return $this->redirect(array('controller' => 'servers', 'action' => 'view', $server_id));
        }
    }
}
?>
