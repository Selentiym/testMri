<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.01.2017
 * Time: 15:31
 */
class VerifiedFactor extends aCallFactor {
    public $name = 'Подтверждено';
    public function getApplyMethodName(iFactorable $obj = null) {
        return "getVerified";
    }
}