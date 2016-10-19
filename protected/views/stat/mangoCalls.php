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
$periodMins = $_GET["period"];
if (($periodMins < 1) || ($periodMins > 60*24)) {
    $periodMins = 10;
}
$datepicker = $this -> renderPartial("//_datepicker",["get" => $_GET, "from" => $from, "to" => $to,"url" => Yii::app() -> baseUrl."/stat/mangoCalls/%startTime%/%endTime%"],true);
$range = ["from" => $_GET["from"], "to" => $_GET["to"]];
$mrtToGo = externalStat::giveMrtToGoData($range["from"], $range["to"], $periodMins);
$onlineCalls = StatCall::giveFormDataAverageByPeriod($range["from"], $range["to"], $periodMins);

$data = array_values(mCall::callsAverageByPeriod($range['from'], $range['to'], $periodMins));
$ratio = [];
//var_dump($onlineCalls);
foreach ($data as $d) {
    $name = $d[0];
    $v = (int)$mrtToGo->$name;
    $sum = (int)$d[1] + $onlineCalls[$name][1];
    if ($v > 0) {
        $d[2] = $v;
        $ratio[] = [$d[0],(float)$d[1]/$v,(float)($onlineCalls[$name][1])/$v,(float)$sum/$v];
    } else {
        $d[2] = 0;
        $ratio[] = [$d[0],0,0,0];
    }
    $dataDraw[] = $d;
}
$data = [['Время','Звонки','Посещения']] + $dataDraw;
$onlineDraw = array_values([['Время','Заявки']] + $onlineCalls);
$ratio = [['Время','Звонки/посещения','Заявки/посещения','Сумма/посещения']] + $ratio;
//var_dump($onlineDraw);
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
        drawAreaChart(".json_encode($onlineDraw).",".json_encode($options).", $('#chart2').get(0))
    });
    google.charts.setOnLoadCallback(function(){
        drawAreaChart(".json_encode($ratio).",".json_encode($options).", $('#chart3').get(0))
    });
", CClientScript::POS_READY);

echo $datepicker;


//mCall::loadDataByApi($range["from"], $range["to"]);
echo CHtml::link('Загрузить статистику', Yii::app() -> createUrl('stat/loadMango',['from' => $range['from'], 'to' => $range['to']]));
?>
<form>
    <input type="text" placeholder="Период детализации (в минутах)" name="period" value="<?php echo $periodMins; ?>"/>
    <input type="submit" value="OK"/>
</form>
<div id="chart">

</div>
<div id="chart2">

</div>
<div id="chart3">

</div>