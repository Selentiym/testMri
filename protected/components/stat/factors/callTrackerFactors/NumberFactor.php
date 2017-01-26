<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.01.2017
 * Time: 12:45
 */
class NumberFactor extends aFactor {
    public $name = 'Набранный номер';
    public function getApplyMethodName(iFactorable $obj = null) {
        return 'getDialedNumber';
    }
    public function checkApplicability(iFactorable $obj) {
        return $obj instanceof iNumberFactorable;
    }
}