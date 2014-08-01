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
class Event extends AppModel{
    public $actsAs = array('Utils.SoftDelete');
    
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
    
    public $hasMany = array(
        'EventLocation', 'PublishingPoint', 'LiveSession'
    );
    
    public $belongsTo = array(
        'EventImportance' => array('foreignKey'=>'event_importance_id'),
        'Playlist' => array('foreignKey'=>'playlist_id')
    );
    
    public function beforeSave() {
        $LiveSession = ClassRegistry::init('LiveSession');
        if ($this->data["Event"]["live"]==Configure::read('PUBLISHING_POINT_LIVE_ON')) {
            $live=$LiveSession->find('first', array('conditions' => array('LiveSession.event_id'=> $this->data["Event"]["id"], 'LiveSession.active' => 1)));
            if (!$live) {
                $LiveSession->create();
                $data = array();
                $temp = array();
                $temp["event_id"] = $this->data["Event"]["id"];
                $temp["active"] = 1;
                $temp["start_date"] = date("Y-m-d H:i:s");
                $data["LiveSession"] = $temp;
                $LiveSession->save($data);
            }
        } else {
            $live=$LiveSession->find('first', array('conditions' => array('LiveSession.event_id'=> $this->data["Event"]["id"], 'LiveSession.active' => 1)));
            if ($live) {
                $live["LiveSession"]["active"] = 0;
                $live["LiveSession"]["end_date"] = date("Y-m-d H:i:s");
                $LiveSession->save($live);
            }
        }
        // $changed_fields is an array of fields that changed
        return true;
    }
}

?>
