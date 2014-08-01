<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class MonitoringController extends AppController {
    public $components = array('RequestHandler');
    var $uses = array("Event", "Server", "PublishingPoint", "MonitoringData", "Location", "LiveSession");
    
    public function view($event_id=null) {
        if (!$event_id) {
            throw new NotFoundException(__('Invalid event'));
        }

        $event = $this->Event->findById($event_id);
        if (!$event) {
            throw new NotFoundException(__('Invalid event'));
        }
        
        $this->set('event', $event);
    }
    
    public function globalConnectedPlayer($session_id=null, $laststamp=0) {
        if (!$session_id) {
            throw new NotFoundException(__('Invalid session'));
        }
        $this->LiveSession->recursive=-1;
        $liveSession = $this->LiveSession->findById($session_id);
        if (!$liveSession) {
            throw new NotFoundException(__('Invalid session'));
        }
        $results=$this->MonitoringData->query(
                  "SELECT round(sum(sub.avg_connected)) as connected, sub.date_min * 30 *1000 as timevalue "
                . "FROM (	"
                . "     SELECT avg(md.connected_players) as avg_connected, md.publishing_point_id, unix_timestamp(md.created) DIV 30 as date_min "
                . "     FROM monitoring_datas md 	"
                . "     WHERE md.live_session_id=" . $session_id
                . "       AND (unix_timestamp(md.created) DIV 30)*30*1000> " . $laststamp 
                . "     GROUP BY md.publishing_point_id, unix_timestamp(md.created) DIV 30 "
                . ")  sub "
                . "WHERE from_unixtime(sub.date_min * 30)<(NOW() - INTERVAL 1 MINUTE) "
                . "GROUP by sub.date_min "
                . "ORDER BY 2");
        $this->set('results', $results);
        //$this->set('_serialize', array('result'));
    }
    
}
?>
