<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.02.2017
 * Time: 15:45
 */
class DivideFactorSet extends aFactorSet {
    public function __construct(aFactor $fact1, aFactor $fact2) {
        parent::__construct([$fact1,$fact2]);
        $this -> name = '('.$fact1 -> name .'/'.$fact2 -> name.')';
    }
    public function applyCore(iFactorable $obj) {
        return current($this -> getFactors()) -> apply($obj);
    }

    public function applyVector(array $objects) {
        $denom = $this -> getFactors()[1] -> applyVector($objects);
        if ($denom == 0) {
            return 0;
        }
        return $this -> getFactors()[0] -> applyVector($objects) / $denom;
    }
}