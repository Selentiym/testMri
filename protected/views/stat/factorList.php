<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 03.02.2017
 * Time: 13:42
 */
$data = $_GET;
$this -> renderPartial('//navBar',['button' => false]);
$graph = current($data["graphs"]);
$factors = FactorForm::createGraphFactorsFromConfig($graph);
$view = $factors['view'][$data['column'] - 1];
$factor = $factors['filter'];
$from = $data["from"];
$to = $data["to"];
$dataToQuery = $data;
$dataToQuery['from'] = '%startTime%';
$dataToQuery['to'] = '%endTime%';
$datepicker = $this -> renderPartial("//_datepicker",["get" => $get, "from" => $from, "to" => $to,"url" => Yii::app() -> baseUrl . '/factorList?'.http_build_query($dataToQuery)],true);
echo $datepicker;
$from = $_GET["from"];
$to = $_GET["to"];

$mod = Yii::app() -> getModule('landingData');
Yii::app() -> getModule('googleDoc');
/**
 * @type landingDataModule $mod
 */
$land = $mod -> getDefaultLanding();
$calls = $mod -> getEnterData($land -> textId, landingDataModule::giveCriteriaForTimePeriod($from, $to));
echo $land -> textId;
/**
 * @type iFactor $factor
 */
$factor -> factorizeData($calls);
$factored = $factor -> getResult();
$toShow = [];
//$toShow[] = $data['valueId'];
if (!empty($toShow)) {
    foreach ($toShow as $val) {
        $this->renderPartial('/stat/factorValue', [
            'factor' => $factor,
            'factorResult' => $factored[$val],
            'view' => $view
        ]);
    }
} else {
    foreach ($factored as $val => $factorResult) {
        $this->renderPartial('/stat/factorValue', [
            'factor' => $factor,
            'factorResult' => $factorResult,
            'view' => $view
        ]);
    }
}