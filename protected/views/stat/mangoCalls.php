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

$data = [['Время','Звонки']] + array_values(mCall::callsAverageByPeriod($range['from'], $range['to']));
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
", CClientScript::POS_READY);

echo $datepicker;


//mCall::loadDataByApi($range["from"], $range["to"]);
?>
<div id="chart">

</div>
