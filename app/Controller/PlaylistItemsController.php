<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class PublishingPointSourcesController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    var $uses = array("PublishingPointSource", "PublishingPoint", "Server");
    
    /**
     * Add a publishing point source to a publishing point 
     * (redirect to publishing point page)
     * @param type $publishingPoint_id
     * @return type
     * @throws NotFoundException
     */
    public function addPublishingPoint($publishingPoint_id=null) {
        if (!$publishingPoint_id) {
            throw new NotFoundException(__('Invalid publishing point'));
        }
        if ($this->request->is('post')) {
            $this->PublishingPointSource->create();
            if ($this->PublishingPointSource->save($this->request->data)) {
                $this->Session->setFlash(__('Your source has been saved.'));
                return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'view', $publishingPoint_id));
            }
            $this->Session->setFlash(__('Unable to add your source.'));
        }
        $this->set('publishingPoint_id', $publishingPoint_id);
        
    }
    
    /**
     * Edit a publishing point source from a publishing point page (redirect to publishing point)
     * @param type $publishingPoint_id
     * @param type $id
     * @return type
     * @throws NotFoundException
     */
    public function editPublishingPoint($publishingPoint_id=null,$id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid source'));
        }
        if (!$publishingPoint_id) {
            throw new NotFoundException(__('Invalid publishing point'));
        }
        $ppSource = $this->PublishingPointSource->findById($id);
        if (!$ppSource) {
            throw new NotFoundException(__('Invalid source'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->PublishingPointSource->id = $id;
            if ($this->PublishingPointSource->save($this->request->data)) {
                $this->Session->setFlash(__('Your source has been updated.'));
                return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'view', $publishingPoint_id));
            }
            $this->Session->setFlash(__('Unable to update your source.'));
        }

        if (!$this->request->data) {
            $this->request->data = $ppSource;
        }
        $this->set('ppSource', $ppSource);
        
    }
    
    
    /**
     * Delete a publishing point source from the publishing point page 
     * (redirect to publishing point)
     * @param type $publishingPoint_id
     * @param type $id
     * @return type
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function deletePublishingPoint($publishingPoint_id, $id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        $pp = $this->PublishingPointSource->findById($id);
        if (!$pp) {
            throw new NotFoundException(__('Invalid Publishing Point'));
        }
        $ppname=$pp["PublishingPointSource"]["url"];
        $this->PublishingPointSource->delete($id);
        $this->Session->setFlash(__('The source %s has been deleted.', h($ppname)));
        return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'view', $publishingPoint_id));
    }
    
    public function up($publishingPoint_id, $id) {
        
        $pp = $this->PublishingPointSource->findById($id);
        if (!$pp) {
            throw new NotFoundException(__('Invalid Publishing Point'));
        }
        $this->PublishingPointSource->moveUp($pp, $id);
        return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'view', $publishingPoint_id));
    }
    
    public function down($publishingPoint_id, $id) {
        
        $pp = $this->PublishingPointSource->findById($id);
        if (!$pp) {
            throw new NotFoundException(__('Invalid Publishing Point'));
        }
        $this->PublishingPointSource->moveDown($pp, $id);
        return $this->redirect(array('controller' => 'publishingpoints', 'action' => 'view', $publishingPoint_id));
    }
   
}
?>
