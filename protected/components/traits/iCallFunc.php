<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05.01.2017
 * Time: 10:27
 */
interface iCallFunc {
    /**
     * @param string $name
     * @return bool
     */
    function _isAllowedToEvaluate($name);
}