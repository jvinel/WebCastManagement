<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class DashboardController extends AppController {
    
    var $uses = array("Server", "PublishingPoint", "MonitoringData");
    
    public function index() {
        $nb_server=$this->Server->query("SELECT count(*) AS cnt FROM servers");
        $this->set('nb_server', $nb_server);
        $nb_publishing_point=$this->PublishingPoint->query("SELECT count(*) AS cnt FROM publishing_points");
        $this->set('nb_publishing_point', $nb_publishing_point);
        $nb_publishing_point_error=$this->PublishingPoint->query("select count(distinct pp.id) AS cnt from publishing_points pp where not exists (select id from monitoring_datas md where md.publishing_point_id=pp.id AND md.date<DATE_SUB(now(), INTERVAL 5 MINUTE))");
        $this->set('nb_publishing_point_error', $nb_publishing_point_error);
        $sm_concurrent_player=$this->PublishingPoint->query("SELECT sum(pp.concurrentplayer) as sm FROM publishing_points pp WHERE EXISTS (select id from monitoring_datas md where md.publishing_point_id=pp.id AND md.date<DATE_SUB(now(), INTERVAL 5 MINUTE))");
        $this->set('sm_concurrent_player', $sm_concurrent_player);
        
        
    }
    
    
}
?>
