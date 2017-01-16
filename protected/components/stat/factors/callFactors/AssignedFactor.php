<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.01.2017
 * Time: 15:22
 */
class AssignedFactor extends aCallFactor {
    public $name="Записи";

    /**
     * @param iFactorable|null $obj
     * @return string
     */
    public function getApplyMethodName(iFactorable $obj = null) {
        return "getAssigned";
    }
}