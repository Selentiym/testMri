<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.01.2017
 * Time: 14:51
 */
/**
 * @type Controller $this
 */
$datepicker = $this -> renderPartial("//_datepicker",["get" => $get, "from" => $from, "to" => $to,"url" => Yii::app() -> baseUrl."/lineStatByFactors/%startTime%/%endTime%"],true);
$range = ["from" => $_GET["from"], "to" => $_GET["to"]];

$mod = Yii::app() -> getModule('googleDoc');
$callModel = GDCallFactorable::model();

$criteria = StatCall::model() -> giveCriteriaForTimePeriod($range['from'], $range['to']);
$criteria -> order = 'date DESC';
//$criteria -> compare('id_call_type',CallType::model() -> getNumber('verifyed'));
$calls = $callModel -> findAll($criteria);

echo $datepicker;

$factorA = new TimeFactor(60*60, 'H:i');

$v = [new CountFactor(),new AssignedFactor(),new VerifiedFactor()];
GraphicsByFactors::GoogleDocGraph($factorA,$calls, $v);
