<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.12.2016
 * Time: 20:28
 */
class ParameterFactor extends aGoogleChartFactor {
    private $_parameter;
    public function __construct($param){
        $this -> _parameter = $param;
        $this -> name = $this -> _parameter;
    }
    public function checkApplicability(iFactorable $obj) {
        return property_exists($obj, $this -> _parameter);
    }
    public function applyCore(iFactorable $obj) {
        $name = $this -> _parameter;
        return $obj -> $name;
    }
}