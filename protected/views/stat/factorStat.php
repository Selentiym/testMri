<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.01.2017
 * Time: 18:40
 */
$this -> renderPartial("//navBar",['button' => false]);
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
<p>При нижатии "новый график" появляется форма задания параметров графика. Слева находится список факторов, по которым будет происходить разбиение. Справа - список факторов, которые будут рисоваться на графиках. Некоторые факторы могут иметь переменные, которые нужно ввести в текстовое поле. Например, фактор типа Parameter имеет только одну переменную - имя параметра для использования. Для полного определения фактора нужно задать его тип из выпадающего списка и все переменные для данного типа. Список какие переменные у каких типов будет дан ниже.</p>
<p>Как справа, так и слева может быть выбрано несколько факторов (добавлять самой внешней кнопкой с зеленым плюсом). Если задано несколько факторов слева, то по оси абсцисс будет отложено прямое произведение множеств значений исходных факторов. Порядок имеет значение! Так, если будет выбран Time, а потом Day фактор, то сначала будут перечисляться все дни при фиксированном значении времени, потом то же самое для следующего времени. Если же сначала будет Day, потом Time, то сначала понедельник все времена, потом вторник все времена и т.д.</p>
<p>Перерисовка всех графиков происходит по кнопке "нарисовать"</p>
<p>Можно менять интервал времен, за которые берется статистика. Для перерисовки нужно нажать "нарисовать"</p>
<p>Удалять графики, факторы и переменные факторов можно нажатием соответсвующих кнопок "крестик".</p>

