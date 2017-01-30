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
     * @return mixed[] - array of data and header
     */
    public static function GoogleDocGraphData(iFactor $factor, array $data, array $viewFactors){
        $factor -> factorizeData($data);
        $func = function($obj, $numObjects) use ($viewFactors) {
            $temp = [];
            $multiplier = $numObjects > 0 ? 1/$numObjects : 0;
            foreach ($viewFactors as $vFactor) {
                $m = $vFactor -> getParam('conversion') ? $multiplier : 1;
                $temp[] = $vFactor -> apply($obj) * $m;
            }
            return $temp;
        };
        $header = [];
        $header[] = $factor -> getName();
        foreach ($viewFactors as $f) {
            $header[] = $f -> getName();
        }
        $data = $factor -> getResultArrayForGoogleCharts($func, array_fill(0, count($viewFactors), 0));
        return ['data' => $data, 'header' => $header];
    }
    /**
     * @param iFactor $factor
     * @param iFactorable[] $data
     * @param iFactor[] $viewFactors to be displayed
     * @return string - dom element with graph attached to it
     */
    public static function GoogleDocGraph(iFactor $factor, array $data, array $viewFactors){
        $input = self::GoogleDocGraphData($factor, $data, $viewFactors);
        return Yii::app() -> controller->widget('application.extensions.googleCharts.GoogleChartsWidget', array(
            'params' => [],
            'data' => $input['data'],
            'header' => $input['header']
        ), true);
    }
}