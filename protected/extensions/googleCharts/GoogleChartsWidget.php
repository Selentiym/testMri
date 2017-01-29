<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.01.2017
 * Time: 19:47
 */
class GoogleChartsWidget extends CWidget {
    /**
     * @var int number of the charts created
     */
    public static $chartNumber = 0;
    /**
     * @var string[] dom ids of elements for charts
     */
    public static $ids =[];
    /**
     * @var mixed[] options - google chart options. Will be json_encoded and
     * populated to google chart
     */
    public $options = [];
    /**
     * @var mixed[] data to be json_encoded and populated to google chart.
     * Should be a two-dimensional array, with each row consisting of the same
     * number of elements. The first element will be used as a dot label, all the
     * others will be drawn in the chart
     */
    public $data = [];

    public $header = [];
    /**
     * @var array
     */
    public $params = [];
    public static $defaultParams = [
        'tag' => 'div',
        'class' => ['googleCharts']
    ];

    private $_assetsDir;
    public function init() {
        $this -> _assetsDir = Yii::app()->getAssetManager()->publish(__DIR__.'/assets/');
    }
    public function run() {
        self::$chartNumber ++;
        $defaultParams = self::$defaultParams;
        if (!(($this -> params['id'])&&(!in_array($this -> params['id'],self::$ids)))) {
            $this -> params['id'] = 'googleChart'.self::$chartNumber;
        }
        $this -> params = array_merge($defaultParams, $this -> params);
        $this -> params['class'] = implode(' ',$this -> params['class']);
        $params = $this -> params;
        $dataSize = count(current($this -> data));
        $hSize = count($this -> header);
        if ($hSize > $dataSize) {
            throw new StatisticalException('Header size is bigger than data\'s one');
        } elseif ($hSize < $dataSize) {
            throw new StatisticalException('Header size is smaller than data\'s one');
        }

        $tag = $params['tag'];
        unset($params['tags']);
        $this -> registerScripts();
        echo Html::tag($tag, $params);
    }
    public function registerScripts() {
        $cs = Yii::app() -> getClientScript();
        //$cs -> registerScriptFile("https://www.google.com/jsapi", CClientScript::POS_BEGIN);

        $cs -> registerCoreScript('jquery');
        $cs -> registerScriptFile($this -> _assetsDir.'/GoogleChartsLoader.js',CClientScript::POS_BEGIN);
        $cs -> registerScriptFile($this -> _assetsDir.'/charts.js',CClientScript::POS_BEGIN);
        Yii::app() -> getClientScript() -> registerScript("google_charts_load_library","
            google.charts.load('current', {'packages':['corechart']});
        ",CClientScript::POS_READY);
        $id = $this -> params['id'];
        $options = CJavaScript::encode($this -> options);
        $data = json_encode(array_values([$this -> header] + $this -> data));
        //var chart = drawAreaChart($data, $options, $('#$id').get(0));});
        Yii::app() -> getClientScript() -> registerScript($id,"
        google.charts.setOnLoadCallback(function(){
            var data = $data;
            var chart = drawAreaChart(data, $options, $('#$id').get(0));
            var params = {};
            chart.data = data;
            chart.factorId = 'check!';
            console.log(chart);
            google.visualization.events.addListener(chart, 'select', function(e){chartsClickHandler.call(chart, e, data);});
        });
        ",CClientScript::POS_READY);
    }
}