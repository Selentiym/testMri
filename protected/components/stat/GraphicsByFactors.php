<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.01.2017
 * Time: 13:32
 */
class GraphicsByFactors {
    /**
     * @param iFactor $factor
     * @param iFactorable[] $data
     * @param iFactor[] $viewFactors to be displayed
     * @return string - dom element with graph attached to it
     */
    public static function GoogleDocGraph(iFactor $factor, array $data, array $viewFactors){
        $factor -> factorizeData($data);
        $func = function($obj) use ($viewFactors) {
            $temp = [];
            foreach ($viewFactors as $vFactor) {
                $temp[] = $vFactor -> apply($obj);
            }
            return $temp;
        };
        $header = [];
        $header[] = $factor -> getName();
        foreach ($viewFactors as $f) {
            $header[] = $f -> getName();
        }
        $data = $factor -> getResultArrayForGoogleCharts($func, array_fill(0, count($viewFactors), 0));
        //return;
        Yii::app() -> controller->widget('application.extensions.googleCharts.GoogleChartsWidget', array(
            'params' => [],
            'data' => $data,
            'header' => $header
        ));
    }
}