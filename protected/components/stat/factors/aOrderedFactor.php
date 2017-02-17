<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.02.2017
 * Time: 9:22
 */
class aOrderedFactor extends aFactor {
    public function normalizeFactoringResult() {
        $values = $this -> getResult();
        ksort($values);
        return $values;
    }
}