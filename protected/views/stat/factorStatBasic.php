<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.01.2017
 * Time: 21:07
 */
$a = [];

$a[] = new TestClass("2016-02-28 19:27:37",2,3, 1, 1);
$a[] = new TestClass("2016-11-25 16:34:54",2,3, 1, 1);
$a[] = new TestClass("2016-12-13 09:50:22",2,4, 1, 3);
$a[] = new TestClass("2016-12-13 13:50:22",2,4, 1, 7);
$a[] = new TestClass("2017-01-09 04:10:44",3,7, 2, 1);
$a[] = new TestClass("2016-02-03 23:59:57",2,7, 1, 4);
$a[] = new TestClass("2017-01-05 10:35:15",3,7, 1, 4);
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