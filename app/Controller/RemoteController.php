<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class RemoteController extends AppController {
    public $components = array('RequestHandler');
    var $uses = array("PublishingPoint", "ServerNotification", "Server", "Event", "Playlist", "MonitoringData", "LiveSession");

    /**
     * Get a list of notification for a specified server (by server name)
     * @param type $server_name
     * @throws NotFoundException
     */
    public function notificationList($server_name=null) {
        if (!$server_name) {
            throw new NotFoundException(__('Invalid server name 1'));
        }
        $server=$this->Server->find('first', array('conditions' => array('Server.deleted' => 0, 'Server.name' => $server_name)));
        if (!$server) {
            throw new NotFoundException(__('Invalid server name ' . $server_name));
        } else {
            $server["Server"]["last_connection"]= date('Y-m-d H:i:s');
            //print_r($server);
            $temp2=array();
            $temp=array();
            foreach ($server["RemoteLocation"] as $remoteLoc) {
                $temp[]=$remoteLoc["id"];
            }
            $temp2["RemoteLocation"]=$temp;
            $server["RemoteLocation"]=$temp2;
            //unset($server["Parent"]);
            //print_r($server);
            $this->Server->save($server);
        }
        
        $notifications=$this->ServerNotification->find('all', array('conditions' => array('ServerNotification.status' => array(1,2), 'ServerNotification.server_name' => $server_name)));
        $this->set('notifications', $notifications);
        $this->set('_serialize', array('notifications'));
        
        //Update notification and set status to 2 (notified)
        foreach ($notifications as $notification) {
            $notification["ServerNotification"]["status"]=Configure::read('NOTIFICATION_NOTIFIED');
            $this->ServerNotification->save($notification);
        }
    }
    
    /**
     * Retrieve a publishing point by id
     * @param type $id
     * @throws NotFoundException
     */
    public function publishingPoint($id) {
        if (!$id) {
            throw new NotFoundException(__('Invalid publishing point'));
        }
        
        // Get the publishing point
        $this->PublishingPoint->recursive=-1;
        $publishingPoint = $this->PublishingPoint->findById($id);
        if (!$publishingPoint) {
            throw new NotFoundException(__('Invalid publishing point'));
        }
        // Get the server
        $this->Server->recursive=-1;
        $server=$this->Server->findById($publishingPoint['PublishingPoint']['server_id']);
        if (!$server) {
            throw new NotFoundException(__('Invalid server for this publishing point'));
        }
        // Get the event
        $this->Event->recursive=-1;
        $event=$this->Event->findById($publishingPoint['PublishingPoint']['event_id']);
        if (!$event) {
            throw new NotFoundException(__('Invalid event for this publishing point'));
        }
        
        // Create playlist
        $playlist=array();
        if ($server["Server"]["source_id"]>0) {
            // Not a main server
            if ($publishingPoint['PublishingPoint']['location_id']==$server["Server"]["location_id"]) {
                // Local publishing point -> playlist is parent server publishing point for the event
                $parent=$this->Server->findById($server['Server']['source_id']);
                $playlist=$this->createPlaylist($publishingPoint['PublishingPoint']["name"], "rtsp://" . $parent["Server"]["name"] . "/" . $event["Event"]["short_name"]);
                $playlist["Playlist"]["name"]=$publishingPoint['PublishingPoint']["name"];
            } else {
                // Remote publishing point -> playlist is current server publishing point for the event
                $playlist=$this->createPlaylist($publishingPoint['PublishingPoint']["name"], "rtsp://" . $server["Server"]["name"] . "/" . $event["Event"]["short_name"]);
            }
        } else {
            // Main server
            if ($publishingPoint['PublishingPoint']['location_id']==$server["Server"]["location_id"]) {
                // Local publishing point -> playlist is event playlist
                $playlist=$this->Playlist->findById($event["Event"]["playlist_id"]);
                $playlist["Playlist"]["name"]=$publishingPoint['PublishingPoint']["name"];
                if (!$playlist) {
                    throw new NotFoundException(__('Invalid playlist for this publishing point\'s event'));
                }
            } else {
                // Remote publishing point -> playlist is current server publishing point for the event
                $playlist=$this->createPlaylist($publishingPoint['PublishingPoint']["name"],"rtsp://" . $server["Server"]["name"] . "/" . $event["Event"]["short_name"]);
            }
        }
         $result=array_merge($publishingPoint, $playlist);
         $this->set('result', $result);
         $this->set('_serialize', array('result'));
    }
    
    private function createPlaylist($event_name, $url) {
        $data=array();
        $item=array();
        $item["url"]=$url;
        $item["position"]=1;
        $pl=array();
        $pl["name"]=$event_name;
        $data["Playlist"]=$pl;
        $data["PlaylistItem"]=$item;
        return $data;
    }
    
    public function updateNotification() {
        $json = $this->request->input('json_decode');
        $id=$json->id;
        if (!$id) {
            throw new NotFoundException(__('Invalid notification'));
        }
        $status=$json->status;
        if (!$status) {
            throw new NotFoundException(__('Invalid status'));
        }
        $notification=$this->ServerNotification->findById($id);
        if (!$notification) {
            throw new NotFoundException(__('Invalid notification'));
        }
        
        if ($notification["ServerNotification"]["status"]!=Configure::read('NOTIFICATION_NOTIFIED')) {
            throw new NotFoundException(__('Invalid notification (not in a notified state)'));
        }
        $notification["ServerNotification"]["status"]=$status;
        $this->ServerNotification->save($notification);
        $result=array();
        $result["result"]="OK";
        // If Publishing Point -> Need to change publication point status
        if (($notification["ServerNotification"]["object_type"]=="PublishingPoint") && ($notification["ServerNotification"]["operation"]=="update") && ($notification["ServerNotification"]["status"]==3)) {
           $publishingPoint=$this->PublishingPoint->findById($notification["ServerNotification"]["object_id"]); 
           if ($publishingPoint) {
               if ($publishingPoint["PublishingPoint"]["configuration_status"]==Configure::read('PUBLISHING_POINT_PUBLISHED')) {
                   $publishingPoint["PublishingPoint"]["configuration_status"]=Configure::read('PUBLISHING_POINT_CONFIGURED');
                   $this->PublishingPoint->save($publishingPoint);
               }
           }
        }
        $this->set('result', $result);
        $this->set('_serialize', array('result'));
    }
    
    public function sendMonitoring() {
        $json = $this->request->input('json_decode');
        $server_name=$json->server;
        $server=$this->Server->find('first', array('conditions' => array('Server.deleted' => 0, 'Server.name' => $server_name)));
        if (!$server) {
            throw new NotFoundException(__('Invalid server name ' . $server_name));
        }
        
        $publishing_point_name=$json->publishing_point;
        $publishingPoint=$this->PublishingPoint->find('first', array('conditions'=> array('PublishingPoint.deleted' => 0, 'PublishingPoint.name' => $publishing_point_name, 'PublishingPoint.server_id' =>$server["Server"]["id"])));
        if (!$publishingPoint) {
            throw new NotFoundException(__('Invalid publishing point ' . $publishing_point_name));
        }
        $publishingPoint["PublishingPoint"]["concurrentplayer"]=$json->connected_players;
        $publishingPoint["PublishingPoint"]["bandwidth"]=$json->current_bandwidth;
        $this->PublishingPoint->save($publishingPoint);
        
        $live=$this->LiveSession->find('first', array('conditions' => array('LiveSession.event_id'=> $publishingPoint["PublishingPoint"]["event_id"], 'LiveSession.active' => 1)));
        if ($live) {
            $this->MonitoringData->create();
            $data=array();
            $temp=array();
            $temp["publishing_point_id"]=$publishingPoint["PublishingPoint"]["id"];
            $temp["connected_players"]=$json->connected_players;
            $temp["current_bandwidth"]=$json->current_bandwidth;
            $temp["live_session_id"]=$live["LiveSession"]["id"];
            $data["MonitoringData"]=$temp;
            $this->MonitoringData->save($data);
        }
        
        $result=array();
        $result["result"]="OK";
        $this->set('result', $result);
        //$this->set('_serialize', array('result'));
    }
}
?>
