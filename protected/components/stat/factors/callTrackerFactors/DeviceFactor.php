<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.02.2017
 * Time: 23:10
 */
class DeviceFactor extends aFactor {
    public $name = 'Устройство';
    public function checkApplicability(iFactorable $obj) {
        return $obj instanceof iExperimentFactorable;
    }
    public function applyCore(iFactorable $obj) {
        if ($obj -> getExperiment() -> isMobile) {
            return 'mobile';
        } else {
            return 'desk';
        }
    }
}