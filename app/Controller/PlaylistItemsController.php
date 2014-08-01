<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class PlaylistItemsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    var $uses = array("PlaylistItem", "Playlist", "Server");
    
    
    /**
     * Add a playlist item to a playlist 
     * (redirect to playlist page)
     * @param type $playlist_id
     * @return type
     * @throws NotFoundException
     */
    public function addPlaylist($playlist_id=null) {
        if (!$playlist_id) {
            throw new NotFoundException(__('Invalid playlist'));
        }
        if ($this->request->is('post')) {
            $this->PlaylistItem->create();
            if ($this->PlaylistItem->save($this->request->data)) {
                
                $this->Session->setFlash(__('Your item has been saved.'));
                return $this->redirect(array('controller' => 'playlists', 'action' => 'view', $playlist_id));
            }
            $this->Session->setFlash(__('Unable to add your source.'));
        }
        $this->set('playlist_id', $playlist_id);
    }
    
    /**
     * Edit a playlist item from a playlist page (redirect to playlist)
     * @param type $playlist_id
     * @param type $id
     * @return type
     * @throws NotFoundException
     */
    public function editPlaylist($playlist_id=null,$id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid source'));
        }
        if (!$playlist_id) {
            throw new NotFoundException(__('Invalid playlist'));
        }
        $item = $this->PlaylistItem->findById($id);
        if (!$item) {
            throw new NotFoundException(__('Invalid source'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->PlaylistItem->id = $id;
            if ($this->PlaylistItem->save($this->request->data)) {
                $point=$this->Playlist->findById($playlist_id);
                $point["Playlist"]["configuration_status"]=0;
                $this->Playlist->save($point);
                $this->Session->setFlash(__('Your item has been updated.'));
                return $this->redirect(array('controller' => 'playlists', 'action' => 'view', $playlist_id));
            }
            $this->Session->setFlash(__('Unable to update your source.'));
        }

        if (!$this->request->data) {
            $this->request->data = $item;
        }
        $this->set('item', $item);
        
    }
    
    
    /**
     * Delete a playlist source from the playlist page 
     * (redirect to playlist)
     * @param type $playlist_id
     * @param type $id
     * @return type
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function deletePlaylist($playlist_id, $id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        $pp = $this->PlaylistItem->findById($id);
        if (!$pp) {
            throw new NotFoundException(__('Invalid Playlist Item'));
        }
        $ppname=$pp["PlaylistItem"]["url"];
        $this->PlaylistItem->delete($id);
        $this->Session->setFlash(__('The item %s has been deleted.', h($ppname)));
        return $this->redirect(array('controller' => 'playlists', 'action' => 'view', $playlist_id));
    }
    
    public function up($playlist_id, $id) {
        
        $pp = $this->PlaylistItem->findById($id);
        if (!$pp) {
            throw new NotFoundException(__('Invalid Item'));
        }
        $this->PlaylistItem->moveUp($pp, $id);
        
        return $this->redirect(array('controller' => 'playlists', 'action' => 'view', $playlist_id));
    }
    
    public function down($playlist_id, $id) {
        
        $pp = $this->PlaylistItem->findById($id);
        if (!$pp) {
            throw new NotFoundException(__('Invalid Item'));
        }
        $this->PlaylistItem->moveDown($pp, $id);
        return $this->redirect(array('controller' => 'playlists', 'action' => 'view', $playlist_id));
    }
   
}
?>
