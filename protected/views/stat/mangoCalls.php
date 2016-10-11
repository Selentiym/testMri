<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2016
 * Time: 20:37
 */
Yii::app() -> getClientScript() -> registerScriptFile(Yii::app() -> baseUrl.'/js/GoogleChartsLoader.js', CClientScript::POS_BEGIN);
Yii::app() -> getClientScript() -> registerScriptFile(Yii::app() -> baseUrl.'/js/charts.js', CClientScript::POS_BEGIN);

$this -> renderPartial('//navBar',array('button' => 'no'));
/**
 * @type Controller $this
 */

$datepicker = $this -> renderPartial("//_datepicker",["get" => $_GET, "from" => $from, "to" => $to,"url" => Yii::app() -> baseUrl."/stat/mangoCalls/%startTime%/%endTime%"],true);
$range = ["from" => $_GET["from"], "to" => $_GET["to"]];
$mrtToGo = externalStat::giveMrtToGoData($range["from"], $range["to"], 10);

$data = array_values(mCall::callsAverageByPeriod($range['from'], $range['to']));
$ratio = [];
foreach ($data as $d) {
    $name = $d[0];
    $v = (int)$mrtToGo->$name;
    if ($v > 0) {
        $d[2] = $v;
        $ratio[] = [$d[0],(float)$d[1]/$v];
    } else {
        $d[2] = 0;
        $ratio[] = [$d[0],0];
    }
    $dataDraw[] = $d;
}
$data = [['Время','Звонки','Посещения']] + $dataDraw;
$ratio = [['Время','Звонки/посещения']] + $ratio;
//var_dump($ratio);
//var_dump($data);

/*$data = [
    ['Year', 'Sales', 'Expenses'],
    ['2013',  1000,      400],
    ['2014',  1170,      460],
    ['2015',  660,       1120],
    ['2016',  1030,      540]
];*/

$options = [];
Yii::app() -> getClientScript() -> registerScript('drawChart',"
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function(){
        drawAreaChart(".json_encode($data).",".json_encode($options).", $('#chart').get(0))
    });
    google.charts.setOnLoadCallback(function(){
        drawAreaChart(".json_encode($ratio).",".json_encode($options).", $('#chart2').get(0))
    });
", CClientScript::POS_READY);

echo $datepicker;


//mCall::loadDataByApi($range["from"], $range["to"]);
echo CHtml::link('Загрузить статистику', Yii::app() -> createUrl('stat/loadMango',['from' => $range['from'], 'to' => $range['to']]));
?>

<div id="chart">

</div>
<div id="chart2">

</div>