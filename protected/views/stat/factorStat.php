<?php
/**
 * @type Controller $this
 */
$datepicker = $this -> renderPartial("//_datepicker",["get" => $get, "from" => $from, "to" => $to,"url" => Yii::app() -> baseUrl."/factorStat/%startTime%/%endTime%"],true);
$range = ["from" => $_GET["from"], "to" => $_GET["to"]];


/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.01.2017
 * Time: 15:34
 */
$mod = Yii::app() -> getModule('landingData');
Yii::app() -> getModule('googleDoc');
/**
 * @type landingDataModule $mod
 */
$calls_init = $mod -> getEnterData('mrktClinics', landingDataModule::giveCriteriaForTimePeriod($range['from'], $range['to']));
//$calls_init = $mod -> getEnterData('mrktClinics', landingDataModule::giveCriteriaForTimePeriod(1485201600, 1485287999));
$calls = [];
if ($_GET["num"]>0) {
    $num = $_GET["num"];
} else {
    $num = 10;
}
for ($i = 0; $i < $num; $i++) {
    $calls[] = $calls_init[array_rand($calls_init)];
}
//var_dump($range);
//var_dump($calls);
//$calls =
$factorA = new NumberFactor();

//$factorA = new TimeFactor(60*60, 'H:i');
if (true) {
//if ($_GET["num"] == 'inf') {
    $calls = $calls_init;
}
//$enter = Enter::model() -> findByPk(60);
//echo (string) $enter -> getAssigned();

//echo "<pre>";
//var_dump($enter -> getGoogleDoc());
//echo "</pre>";
//$gd = $enter -> getGoogleDoc();
//echo (string)$gd -> getAssigned();


$v = [new ParameterFactor('called'), new AssignedFactor()];
//echo "<p>Выбранное количество заходов: ". $num."</p>";
//echo "<p>Чтобы выбрать все записи, введите 'inf'</p>";
//echo "<form><input type='text' name='num'/><input type='submit'></form>";
//var_dump($factorA -> factorizeData($calls));
GraphicsByFactors::GoogleDocGraph($factorA,$calls, $v);
//echo "<div>";var_dump($factorA -> getResult());echo "</div>";
//phpinfo();
return;

//var_dump($calls);
//return;
$mod = Yii::app() -> getModule('googleDoc');
$callModel = GDCallFactorable::model();

$criteria = StatCall::model() -> giveCriteriaForTimePeriod($range['from'], $range['to']);
$criteria -> order = 'date DESC';
//$criteria -> compare('id_call_type',CallType::model() -> getNumber('verifyed'));
$calls = $callModel -> findAll($criteria);

echo $datepicker;
//foreach ($calls as $call) {
    /**
     * @type StatCall $call
     */
    /*echo '<p>'.$call ->fio .' '.$call -> date.'</p>';
}*/
//$factorA = new DayFactor();
$factorA = new TimeFactor(60*60, 'H:i');

$v = [new CountFactor(),new AssignedFactor(),new VerifiedFactor()];
GraphicsByFactors::GoogleDocGraph($factorA,$calls, $v);
return;

/*$a[] = new TestClass(1,4, 1);
$a[] = new TestClass(2,2, 3);
$a[] = new TestClass(5,2, 1.5);
$a[] = new TestClass(5,2, 1.5);*/
//$f = new TimeFactor(60*10);
//$factorA = new TimeFactor(60*60, 'H');
$factorB = new TimeFactor(60*60, 'H');
$factorA = new DayFactor();
//$factorB = new ParameterFactor('b');
/*$factorA = new ParameterFactor('a');
$factorA -> addNewPossibleValue('a2');
$factorA -> addNewPossibleValue('a3');
$factorB = new ParameterFactor('b');
$factorB -> addNewPossibleValue('b3');
$factorB -> addNewPossibleValue('b4');
$factorB -> addNewPossibleValue('b7');
$factorC = new ParameterFactor('c');
$factorC -> addNewPossibleValue('c1');
$factorC -> addNewPossibleValue('c2');
/*$factorB -> addNewPossibleValue('B1');
$factorB -> addNewPossibleValue('B2');*/
$factor = $factorA -> multiplyBy($factorB);

$viewFactors[] = new CountFactor();
$viewFactors[] = new ParameterFactor('weight');
$viewFactors[1] -> name = 'Вес';
GraphicsByFactors::GoogleDocGraph($factor, $a, $viewFactors);
/**$func = function($obj) use ($viewFactor) {
return [1, $obj -> weight];
};
$factor -> factorizeData($a);
$data = $factor -> getResultArrayForGoogleCharts($func, [0,0]);
var_dump($data);
$w = $this->widget('application.extensions.googleCharts.GoogleChartsWidget', array(
'params' => [],
'data' => $data,
'header' => ['Параметры', 'Количество','Вес']
));//*/