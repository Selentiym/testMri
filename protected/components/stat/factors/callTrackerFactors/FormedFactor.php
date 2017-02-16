<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.02.2017
 * Time: 22:29
 */
class FormedFactor extends aFactor {
    public $name = 'Формы';
    public function applyCore(iFactorable $obj) {
        return (int)$obj -> formed;
    }
    public function checkApplicability(iFactorable $obj) {
        try {
            $obj -> formed;
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}