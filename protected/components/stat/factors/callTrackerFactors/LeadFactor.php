<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.02.2017
 * Time: 22:57
 */
class LeadFactor extends aFactor {
    /**
     * @param iFactorable $obj
     * @return int
     */
    public $name = 'Лиды';
    public function applyCore(iFactorable $obj) {
        $temp = $obj -> getCalled() + $obj -> getFormed();
        if ($temp > 1) {
            return 1;
        }
        return $temp;
    }
    public function checkApplicability(iFactorable $obj) {
        return ($obj instanceof iCallTrackerFactorable);
    }
}