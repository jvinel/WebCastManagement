<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class PlaylistsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    var $uses = array("Playlist", "PublishingPoint", "Server");
    
    
    public function index() {
        $this->set('playlists', $this->Playlist->find('all'));
    }
    
    public function edit($id=null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid playlist'));
        }

        $playlist = $this->Playlist->findById($id);
        if (!$playlist) {
            throw new NotFoundException(__('Invalid playlist'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Playlist->id = $id;
            if ($this->Playlist->save($this->request->data)) {
                $this->Session->setFlash(__('Your playlist has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to update your playlist.'));
        }

        if (!$this->request->data) {
            $this->request->data = $playlist;
        }
        $this->set('playlist', $playlist);
        
    }
    
    public function add() {
        if ($this->request->is('post')) {
            $this->Playlist->create();
            if ($this->Playlist->save($this->request->data)) {
                $this->Session->setFlash(__('Your playlist has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to add your playlist.'));
        }
       
    }
    
    public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        if (!$id) {
            throw new NotFoundException(__('Invalid playlist'));
        }
        $playlist = $this->Playlist->findById($id);
        if (!$playlist) {
            throw new NotFoundException(__('Invalid playlist'));
        }
        $playlistname=$playlist["Playlist"]["name"];
        $this->Playlist->delete($id);
        $this->Session->setFlash(__('The playlist %s has been deleted.', $playlistname));
        return $this->redirect(array('action' => 'index'));
    }
    
    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid playlist'));
        }

        $playlist = $this->Playlist->findById($id);
        
        if (!$playlist) {
            throw new NotFoundException(__('Invalid playlist'));
        }
        $this->set('playlist', $playlist);
        $this->set('items', $playlist["PlaylistItem"]);
        
    }
    
    
    
   
}
?>
