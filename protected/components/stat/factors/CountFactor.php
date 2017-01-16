<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.01.2017
 * Time: 13:57
 */
class CountFactor extends aFactor{
    public $name = 'Количество';
    public function applyCore(iFactorable $obj) {
        return 1;
    }
    public function checkApplicability(iFactorable $obj){
        return true;
    }
}