<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.01.2017
 * Time: 18:40
 */
$datepicker = $this -> renderPartial("//_datepicker",["get" => $get, "from" => $from, "to" => $to,"url" => "func", "function" => "datePickerUpdate"],true);
$cs = Yii::app() -> getClientScript();
$cs -> registerScriptFile(Yii::app() -> baseUrl.'/js/chartsGoogle.js',CClientScript::POS_BEGIN);
$cs -> registerScriptFile(Yii::app() -> baseUrl.'/js/GoogleChartsLoader.js',CClientScript::POS_BEGIN);
$cs -> registerScript('checkGraph',"
    $('#newGraph').click(function(){
        var gr = new GraphForm({});
    });
    GraphsContainer = $('#graphsContainer');
",CClientScript::POS_READY);
$cs -> registerCssFile(Yii::app() -> baseUrl.'/css/charts.css');
if ($_GET["graphs"]) {
    foreach ($_GET["graphs"] as $key => $graph) {
        $cs->registerScript($key, "
        new GraphForm(" . json_encode($graph) . ");
    ", CClientScript::POS_READY);
    }
}
$cs -> registerScript('baseUrl',"
baseUrl = '".Yii::app() -> baseUrl."';
",CClientScript::POS_BEGIN);
$cs -> registerScript('typesInit',"
fromTimeUnix = '".$_GET['from']."';
toTimeUnix = '".$_GET['to']."';
types = ".json_encode(FactorForm::getTypes()).";
", CClientScript::POS_BEGIN);
$cs -> registerScript('readyScriptCharts',"
$('#draw').click(function(){
    for (var i = 0; i < graphs.length; i++) {
        graphs[i].draw();
    }
});
",CClientScript::POS_READY);
Yii::app() -> getClientScript() -> registerScript("google_charts_load_library","
    google.charts.load('current', {'packages':['corechart']});
",CClientScript::POS_READY);
echo $datepicker;
?>
<form method="get" id="mainForm">
    <input id="draw" type="button" value="Нарисовать">
    <input id="newGraph" type="button" value="Новый график">
    <div id="graphsContainer">

    </div>
</form>
<?php
    var_dump($_GET);
?>