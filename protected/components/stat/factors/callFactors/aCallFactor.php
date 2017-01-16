<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.01.2017
 * Time: 15:32
 */
abstract class aCallFactor extends aFactor{
    /**
     * @param iFactorable $obj
     * @return bool|void
     */
    public function checkApplicability(iFactorable $obj) {
        return $obj instanceof iCallFactorable;
    }
}