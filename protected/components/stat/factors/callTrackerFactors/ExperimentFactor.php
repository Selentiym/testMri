<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.01.2017
 * Time: 12:44
 */
class ExperimentFactor extends aFactor {
    public $parameter;
    public $name = '';
    public function __construct($p){
        if (!$p) {
            throw new StatisticalException('Could not create Experiment factor with empty attribute given.');
        }
        $this -> parameter = $p;
        $this -> name = $p;
    }

    /**
     * @param iFactorable $obj
     * @return bool
     */
    public function checkApplicability(iFactorable $obj){
        return $obj instanceof iExperimentFactorable;
    }

    /**
     * @param iFactorable $obj
     * @return string
     */
    public function applyCore(iFactorable $obj){
        $param = $this -> parameter;
        $val = $obj -> getExperiment() -> $param;
        $val = $val ? $val : '';
        return $val;
    }
}
