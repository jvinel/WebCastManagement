<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Server
 *
 * @author vinel_j
 */
class PublishingPoint extends AppModel {

    public $actsAs = array('Utils.SoftDelete');
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        ),
        'server_id' => array(
            'rule' => 'notEmpty'
        )
    );
    public $belongsTo = array(
        'Server' => array('foreingKey' => 'server_id'),
        'Location' => array('foreignKey' => 'location_id'),
        'Event' => array('foreignKey' => 'event_id'),
        'Playlist' => array('foreignKey' => 'playlist_id')
    );
    public $hasMany = array('MonitoringData');
    public $virtualFields = array(
        'limit_player_summary' => 'CONCAT(PublishingPoint.limit_connected_player , " players")',
        'limit_bandwidth_summary' => 'CONCAT(PublishingPoint.limit_player_bandwidth, " bandwidth")'
    );

    /**
     * Return a publishing point for an event, server and location.
     * If this publishing point not exist, create one
     * If this publishing point exists, check if limit are correct otherwise update it.
     * @param type $event
     * @param type $server
     * @param type $location
     * @param type $name
     * @param type $bandwidth_threshold_ratio
     * @param type $bandwidth_usage_ratio
     * @return type
     */
    public function getPublishingPointForEventLocation($event, $server, $location, $name, $bandwidth_threshold_ratio, $bandwidth_usage_ratio, $remote_bandwidth) {

        $pp = $this->find('first', array('conditions' => array('PublishingPoint.deleted' => 0, 'PublishingPoint.server_id' => $server["id"], 'PublishingPoint.name' => $name)));

        $limit_player = round(($server["bandwidth"] * $bandwidth_threshold_ratio) / ($event["video_bitrate"] * $bandwidth_usage_ratio));
        if ($remote_bandwidth > 0) {
            $limit_player = round(($remote_bandwidth * $bandwidth_threshold_ratio) / ($event["video_bitrate"] * $bandwidth_usage_ratio));
        }
        if (!$pp) {
            $pp = array();
            $ppData = array();
            $ppData["server_id"] = $server["id"];
            $ppData["event_id"] = $event["id"];
            $ppData["location_id"] = $location["id"];
            $ppData["name"] = $name;
            $ppData["configuration_status"] = Configure::read('PUBLISHING_POINT_DRAFT');
            $ppData["limit_connected_player"] = $limit_player;
            if ($server["source_id"] == "") {
                if ($remote_bandwidth == 0) {
                    // remote bandwidth==0 indicate that current location is not a remote location
                    $ppData["playlist_id"] = $event["playlist_id"];
                }
            }
            $this->create();
            $pp["PublishingPoint"] = $ppData;
            $this->save($pp);
            $pp = $this->find('first', array('conditions' => array('PublishingPoint.deleted' => 0, 'PublishingPoint.server_id' => $server["id"], 'PublishingPoint.name' => $name)));
        } else {
            $changed = false;
            if ($pp["PublishingPoint"]["limit_connected_player"] != $limit_player) {
                $pp["PublishingPoint"]["configuration_status"] = Configure::read('PUBLISHING_POINT_DRAFT');
                $pp["PublishingPoint"]["limit_connected_player"] = $limit_player;
                $changed = true;
            }
            if ($server["source_id"] == "") {
                if ($remote_bandwidth == 0) {
                    // remote bandwidth==0 indicate that current location is not a remote location
                    if ($pp["PublishingPoint"]["playlist_id"] != $event["playlist_id"]) {
                        $pp["PublishingPoint"]["configuration_status"] = Configure::read('PUBLISHING_POINT_DRAFT');
                        $pp["PublishingPoint"]["playlist_id"] = $event["playlist_id"];
                        $changed = true;
                    }
                }
            }
            if ($changed) {
                $this->save($pp);
                $pp = $this->find('first', array('conditions' => array('PublishingPoint.deleted' => 0, 'PublishingPoint.server_id' => $server["id"], 'PublishingPoint.name' => $name)));
            }
        }
        return $pp;
    }

    public function beforeSave() {
        $oldRecursive=$this->recursive;
        $this->recursive = -1;
        $this->old = $this->findById($this->id);
        $this->recursive=$oldRecursive;
        if ($this->old) {
            $changed_fields = array();
            foreach ($this->data[$this->alias] as $key => $value) {
                if ($this->old[$this->alias][$key] != $value) {
                    $changed_fields[] = $key;
                }
            }
            if (in_array("deleted", $changed_fields)) {
                if ($this->data["PublishingPoint"]["deleted"] == 1) {
                    $pp = $this->find('first', array('conditions' => array('PublishingPoint.deleted' => 1, 'PublishingPoint.id' => $this->data["PublishingPoint"]["id"])));
                    $ServerNotification = ClassRegistry::init('ServerNotification');
                    $data = array();
                    $temp = array();
                    $temp["server_name"] = $pp["Server"]["name"];
                    $temp["operation"] = "delete";
                    $temp["object_type"] = "PublishingPoint";
                    $temp["object_id"] = $pp["PublishingPoint"]["id"];
                    $temp["object_name"] = $pp["PublishingPoint"]["name"];
                    $data["ServerNotification"] = $temp;
                    $ServerNotification->save($data);
                }
            }
            if (in_array("configuration_status", $changed_fields)) {
                if ($this->data["PublishingPoint"]["configuration_status"] == Configure::read('PUBLISHING_POINT_PUBLISHED')) {
                    $ServerNotification = ClassRegistry::init('ServerNotification');
                    $ServerNotification->create();
                    $data = array();
                    $temp = array();
                    $temp["server_name"] = $this->data["Server"]["name"];
                    $temp["operation"] = "update";
                    $temp["object_type"] = "PublishingPoint";
                    $temp["object_id"] = $this->data["PublishingPoint"]["id"];
                    $temp["object_name"] = $this->data["PublishingPoint"]["name"];
                    $data["ServerNotification"] = $temp;
                    $ServerNotification->save($data);
                }
            }
            if (in_array("live", $changed_fields)) {
                if ($this->data["PublishingPoint"]["live"] == Configure::read('PUBLISHING_POINT_LIVE_ON')) {
                    $ServerNotification = ClassRegistry::init('ServerNotification');
                    $ServerNotification->create();
                    $data = array();
                    $temp = array();
                    $temp["server_name"] = $this->data["Server"]["name"];
                    $temp["operation"] = "start monitoring";
                    $temp["object_type"] = "PublishingPoint";
                    $temp["object_id"] = $this->data["PublishingPoint"]["id"];
                    $temp["object_name"] = $this->data["PublishingPoint"]["name"];
                    $data["ServerNotification"] = $temp;
                    $ServerNotification->save($data);
                    
                    if (!$this->data["Server"]["source_id"]) {
                        if ($this->data['PublishingPoint']['location_id']==$this->data["Server"]["location_id"]) {
                            // If its main publishing point -> Need to start it
                            $ServerNotification->create();
                            $data2 = array();
                            $temp2 = array();
                            $temp2["server_name"] = $this->data["Server"]["name"];
                            $temp2["operation"] = "start";
                            $temp2["object_type"] = "PublishingPoint";
                            $temp2["object_id"] = $this->data["PublishingPoint"]["id"];
                            $temp2["object_name"] = $this->data["PublishingPoint"]["name"];
                            $data2["ServerNotification"] = $temp2;
                            $ServerNotification->save($data2);
                        }
                    }
                }
                if ($this->data["PublishingPoint"]["live"] == Configure::read('PUBLISHING_POINT_LIVE_OFF')) {
                    $ServerNotification = ClassRegistry::init('ServerNotification');
                    $ServerNotification->create();
                    $data = array();
                    $temp = array();
                    $temp["server_name"] = $this->data["Server"]["name"];
                    $temp["operation"] = "stop monitoring";
                    $temp["object_type"] = "PublishingPoint";
                    $temp["object_id"] = $this->data["PublishingPoint"]["id"];
                    $temp["object_name"] = $this->data["PublishingPoint"]["name"];
                    $data["ServerNotification"] = $temp;
                    $ServerNotification->save($data);
                }
            }
        }
        // $changed_fields is an array of fields that changed
        return true;
    }

    public function afterSave($created, $options = array()) {
//        if ($this->data["PublishingPoint"]["deleted"]==1) {
//            $pp=$this->find('first', array('conditions' => array('PublishingPoint.deleted' => 1, 'PublishingPoint.id' => $this->data["PublishingPoint"]["id"])));
//            $ServerNotification = ClassRegistry::init('ServerNotification');
//            $data=array();
//            $temp=array();
//            $temp["server_name"]=$pp["Server"]["name"];
//            $temp["operation"]="delete";
//            $temp["object_type"]="PublishingPoint";
//            $temp["object_id"]=$pp["PublishingPoint"]["id"];
//            $temp["object_name"]=$pp["PublishingPoint"]["name"];
//            $data["ServerNotification"]=$temp;
//            $ServerNotification->save($data);
//        } else if ($this->data["PublishingPoint"]["configuration_status"]==Configure::read('PUBLISHING_POINT_PUBLISHED')) {
//            $ServerNotification = ClassRegistry::init('ServerNotification');
//            $ServerNotification->create();
//            $data=array();
//            $temp=array();
//            $temp["server_name"]=$this->data["Server"]["name"];
//            $temp["operation"]="update";
//            $temp["object_type"]="PublishingPoint";
//            $temp["object_id"]=$this->data["PublishingPoint"]["id"];
//            $temp["object_name"]=$this->data["PublishingPoint"]["name"];
//            $data["ServerNotification"]=$temp;
//            $ServerNotification->save($data);
//        }
    }

}

?>
