<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.02.2017
 * Time: 19:23
 */
class CalledFactor extends aFactor {
    public $name = 'Звонки';
    public function applyCore(iFactorable $obj = null) {
        return $obj -> getCalled();
    }
    public function checkApplicability(iFactorable $obj) {
        return $obj instanceof iCalledFactorable;
    }
}