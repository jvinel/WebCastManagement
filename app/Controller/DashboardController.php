<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class DashboardController extends AppController {
    
    var $uses = array("Server", "PublishingPoint", "MonitoringData", "Location");
    
    public function index() {
        $nb_server=$this->Server->query("SELECT count(*) AS cnt FROM servers WHERE deleted<>1");
        $this->set('nb_server', $nb_server);
        $nb_connected_server=$this->Server->query("SELECT count(*) AS cnt FROM servers WHERE deleted<>1 AND last_connection>NOW() - INTERVAL 5 MINUTE");
        $this->set('nb_connected_server', $nb_connected_server);
        $nb_publishing_point=$this->PublishingPoint->query("SELECT count(*) AS cnt FROM publishing_points WHERE deleted<>1");
        $this->set('nb_publishing_point', $nb_publishing_point);
        $nb_incoming_event=$this->Server->query("SELECT count(*) AS cnt FROM events WHERE deleted<>1 AND live=1");
        $this->set('nb_incoming_event', $nb_incoming_event);
        $sm_concurrent_player=$this->PublishingPoint->query("SELECT sum(pp.concurrentplayer) as sm FROM publishing_points pp WHERE EXISTS (select id from monitoring_datas md where md.publishing_point_id=pp.id AND md.created>DATE_SUB(now(), INTERVAL 5 MINUTE)) AND pp.live=1");
        $this->set('sm_concurrent_player', $sm_concurrent_player);
        
        
        // Get locations
        $locations = $this->Location->find('threaded', array(
                                        'fields' => array('id', 'parent_id', 'name'),
                                        'order' => array('lft ASC') // or array('id ASC')
                                    ));
        //$chartData=array();
        $chartData=$this->buildGlobalLocationRepartitionChartData($locations);
        //print_r($chartData);
         $this->set('chartdata', $chartData);
    }
    
    private function buildGlobalLocationRepartitionChartData($locations) {
        
        $stats=$this->PublishingPoint->query("SELECT sum(pp.concurrentplayer) as sm, pp.location_id FROM publishing_points pp WHERE EXISTS (select id from monitoring_datas md where md.publishing_point_id=pp.id AND md.created>DATE_SUB(now(), INTERVAL 5 MINUTE)) AND pp.live=1 GROUP BY pp.location_id");
        $result=array();
        foreach ($locations as $location) {
            $temp=array();
            $temp["name"]=$location["Location"]["name"];
            if (sizeof($location["children"])>0) {
                $temp2=$this->buildGlobalLocationRepartitionChartData($location["children"]);
                $sum=0;
                foreach ($temp2 as $data) {
                    $sum+=$data["user_cnt"];
                }
                $temp["user_cnt"]=$sum;
                $temp["children"]=$temp2;
            }  else {
                $temp["user_cnt"]=0;
                foreach ($stats as $stat) {
                    
                    if ($stat["pp"]["location_id"]==$location["Location"]["id"]) {
                        $temp["user_cnt"]=$stat["0"]["sm"];
                        break;
                    }
                }
            }
            $result[]=$temp;
        }
        return $result;
        
    }
    
    
}
?>
