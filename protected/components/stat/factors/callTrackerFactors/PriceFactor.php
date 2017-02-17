<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.02.2017
 * Time: 9:21
 */
class PriceFactor extends aOrderedFactor {
    public $name = 'Цена';
    public function applyCore(iFactorable $obj){
        return (float)($obj->getPrice());
    }
    public function checkApplicability(iFactorable $obj) {
        return $obj instanceof iCallTrackerFactorable;
    }
}