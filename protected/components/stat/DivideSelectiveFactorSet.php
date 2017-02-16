<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.02.2017
 * Time: 23:23
 */
class DivideSelectiveFactorSet extends DivideFactorSet {
    public function __construct(aFactor $fact1, aFactor $fact2){
        parent::__construct($fact1, $fact2);
        $this -> name = $fact1 -> name . '/!' . $fact2 -> name;
    }
    public function applyVector1(array $objects) {
        $denom = $this -> getFactors()[1] -> applyVector($objects);
        if ($denom == 0) {
            return 0;
        }
        return $this -> getFactors()[0] -> applyVector($objects) / $denom;
    }
    public function applyVector(array $objects){
        $toDenom = [];
        $rez = 0;
        foreach ($objects as $o) {
            $temp = $this -> getFactors()[0] -> apply($o);
            $rez += $temp;
            if ($temp) {
                $toDenom[] = $o;
            }
        }
        $denom = $this -> getFactors()[1] -> applyVector($toDenom);
        if ((float)$denom == 0) {
            return 0;
        }
        return $rez / $denom;
    }
}