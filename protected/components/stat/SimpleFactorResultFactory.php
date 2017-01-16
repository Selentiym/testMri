<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.12.2016
 * Time: 19:34
 */
class SimpleFactorResultFactory extends aFactorResultFactory {

    /**
     * @param string $arg
     * @param iFactor $factor
     * @return iFactorResult
     */
    public function build($arg, iFactor $factor) {
        return new SimpleResult($arg, $factor);
    }
}