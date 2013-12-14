<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class SitesController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    
    public function index() {
        $this->set('sites', $this->Site->find('all'));
    }
    
    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid site'));
        }

        $site = $this->Site->findById($id);
        
        if (!$site) {
            throw new NotFoundException(__('Invalid site'));
        }
        $this->set('site', $site);
        
    }
    
    
}
?>
