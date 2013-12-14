<?php
/**
 * Description of ServersController
 *
 * @author vinel_j
 */
class DashboardController extends AppController {
    
    var $uses = array("Server", "PublishingPoint", "MonitoringData");
    public $components = array('HighCharts.HighCharts');
    
    public function index() {
        $nb_server=$this->Server->query("SELECT count(*) AS cnt FROM servers");
        $this->set('nb_server', $nb_server);
        $nb_publishing_point=$this->PublishingPoint->query("SELECT count(*) AS cnt FROM publishing_points");
        $this->set('nb_publishing_point', $nb_publishing_point);
        $nb_publishing_point_error=$this->PublishingPoint->query("select count(distinct pp.id) AS cnt from publishing_points pp where not exists (select id from monitoring_datas md where md.publishing_point_id=pp.id AND md.date<DATE_SUB(now(), INTERVAL 5 MINUTE))");
        $this->set('nb_publishing_point_error', $nb_publishing_point_error);
        $sm_concurrent_player=$this->PublishingPoint->query("SELECT sum(pp.concurrentplayer) as sm FROM publishing_points pp WHERE EXISTS (select id from monitoring_datas md where md.publishing_point_id=pp.id AND md.date<DATE_SUB(now(), INTERVAL 5 MINUTE))");
        $this->set('sm_concurrent_player', $sm_concurrent_player);
        
        $chartData =<<<EOF
(function() { var data = [], time = (new Date()).getTime(), i; for (i = -19; i <= 0; i++) { data.push({ x: time + i * 1000, y: Math.random() }); } return data; })()
EOF;

        $chartName = 'Spline Chart Live Data';

        // anonymous Callback function to format the text of the tooltip
        $tooltipFormatFunction =<<<EOF
function() { return '<b>'+ this.series.name +'</b><br/>'+ Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+ Highcharts.numberFormat(this.y, 2);}
EOF;

        // Fires when the chart is finished loading.
        $eventsLoadFunction =<<<EOF
function() { var series = this.series[0]; setInterval(function() { var x = (new Date()).getTime(), y = Math.random(); series.addPoint([x, y], true, true);}, 1000);}
EOF;

        $mychart = $this->HighCharts->create( $chartName, 'spline' );

        $this->HighCharts->setChartParams(
				$chartName,
				array(
					'renderTo'				=> 'splinewrapper',  // div to display chart inside
					//'chartWidth'				=> 1000,
					'chartHeight'				=> 300,
					'chartMarginRight'			=> 10,
					//'chartEventsLoad'			=> $eventsLoadFunction,
                                        'chartEventsLoad'			=> 'function() { updateGlobalData(); }',
					'chartBackgroundColorLinearGradient' 	=> array(0,0,0,300),
					'chartBackgroundColorStops'             => array(array(0,'rgb(217, 217, 217)'),array(1,'rgb(255, 255, 255)')),

					//'title'					=> 'Global connected players',

					'legendEnabled' 			=> FALSE,
					'exportingEnabled' 			=> FALSE,
					'creditsEnabled' 			=> FALSE,

		            		'tooltipEnabled' 			=> TRUE,
                    			'tooltipBackgroundColorLinearGradient' 	=> array(0,0,0,60),
                    			'tooltipBackgroundColorStops' 		=> array(array(0,'#FFFFFF'),array(1,'#E0E0E0')),
                    			'tooltipEnabled' 			=> TRUE,
		            		'tooltipFormatter'			=> $tooltipFormatFunction,

					'xAxisType' 				=> 'datetime',
					'xAxisTickPixelInterval' 		=> 150,

					'yAxisTitleText' 			=> 'Value',
					'yAxisPlotLines' 			=> array( array('color' => '#808080', 'width' => 1, 'value' => 0 )),

					/* autostep options */
					'enableAutoStep' 			=> FALSE
				)
        );

        $series = $this->HighCharts->addChartSeries();
        $chartData1 = array( );
        $series->addName('Global')
            ->addData($chartData1);

        $mychart->addSeries($series);
    }
    
    
}
?>
